<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Enums\Status;
use App\Filters\Customer\CustomerFilters;
use App\Hospital;
use App\EmailTemplate;
use App\Http\Requests\CustomerFormRequest;
use App\Mail\Customer\CustomerSendMail;
use App\MailHistory;
use App\NameIntegration;
use App\Prefecture;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index(CustomerFilters $customerFilters)
    {
        $pagination = (in_array(request()->get('pagination'), [
            10,
            20,
            50,
            100,
        ])) ? request()->get('pagination') : 10;

        $customers       = Customer::filter($customerFilters)->where('hospital_id', '=', session('hospital_id'))->orderBy('id', 'asc')->paginate($pagination);
        $customer_detail = [];// Customer::findOrFail( 1 );
        $reservations    = [];// $customer_detail->reservations()->paginate( 2 );

        return view('customer.index', [ 'customers'       => $customers]);
    }


    public function detail(Request $request)
    {
        $page_number     = ($request->page_id) ?? 1;
        $customer_detail = Customer::findOrFail($request->id);
        $reservations    = $customer_detail->reservations()->paginate(10, [ '*' ], 'page', $page_number);

        if (!isset($customer_detail->epark_member_id)) {

            $identification_page_id = ($request->identification_page_id) ?? 1;

            $source_customer_id = ($request->source_customer_id) ?? $customer_detail->id;

            $source_customer = Customer::findOrFail($source_customer_id);

            $name_identifications = Customer::where('id', '<>', $source_customer_id)
                ->where(function($q) use ($source_customer){
                    $q->whereNull('epark_member_id')
                        ->where('hospital_id', session()->get('hospital_id'))
                        ->where(function($q) use ($source_customer) {
                            $q->orWhere('email', $source_customer->email)
                                ->orWhere('birthday', $source_customer->birthday)
                                ->orWhere(function($nq) use($source_customer) {
                                    $nq->where('family_name', $source_customer->family_name)
                                        ->where('first_name', $source_customer->first_name);
                                });
                        });
                })->paginate(10, [ '*' ], 'page', $identification_page_id);

        }


        return response()->json([
            'data' => view('customer.partials.detail.tab-content', [
                'customer_detail' => $customer_detail,
                'source_customer' => isset($source_customer) ? $source_customer : null,
                'reservations'    => $reservations,
                'name_identifications' => isset($name_identifications) ? $name_identifications : null
            ])->render()
        ]);
    }

    /**
     * CSV file import for customer
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function importData(Request $request)
    {
        $csv_file_path = $request->file('file_selection')->storeAs('customer-csv', time() . '.csv');
        $customerArr   = csvToArray(storage_path('app/' . $csv_file_path));

        $savedCustomer = Customer::insert($customerArr);

        if ($savedCustomer) {
            Storage::delete($csv_file_path);
            return redirect('customer')->with('success', trans('messages.created', [ 'name' => trans('messages.names.customers') ]));
        } else {
            return redirect('customer')->with('error', trans('messages.invalid_format', [ 'name' => trans('messages.names.customers') ]));
        }
    }


    public function create()
    {
        $prefectures = Prefecture::all();
        return view('customer.create', ['prefectures' => $prefectures]);
    }


    public function store(CustomerFormRequest $request)
    {
    	$request->merge([
    		'hospital_id' => session('hospital_id')
	    ]);
        $params = $request->all();

        if(!isset($params['claim_count'])) {
            $params['claim_count'] = 0;
        }

        if(!isset($params['recall_count'])) {
            $params['recall_count'] = 0;
        }
        if (Customer::create($params)) {
            return redirect('customer')->with('success', trans('messages.created', [ 'name' => trans('messages.names.customers') ]));
        } else {
            return redirect('customer')->with('error', trans('messages.invalid_format', [ 'name' => trans('messages.names.customers') ]));
        }
    }


    public function edit(Customer $customer)
    {
        $prefectures = Prefecture::all();
        $customer_detail = Customer::findOrFail($customer->id);

        return view('customer.edit', [ 'customer_detail' => $customer_detail, 'prefectures' => $prefectures ]);
    }


    public function update(CustomerFormRequest $request, Customer $customer)
    {
        $customer = Customer::findOrFail($customer->id);

        $params = $request->all();
        if(!isset($params['claim_count'])) {
            $params['claim_count'] = 0;
        }

        if(!isset($params['recall_count'])) {
            $params['recall_count'] = 0;
        }

        if ($customer->update($params)) {
            return redirect('customer')->with('success', trans('messages.updated', [ 'name' => trans('messages.names.customers') ]));
        } else {
            return redirect('customer')->with('error', trans('messages.invalid_format', [ 'name' => trans('messages.names.customers') ]));
        }
    }

    public function destroy(Customer $customer)
    {
        $customer = Customer::findOrFail($customer->id);
        $customer->delete();

        return redirect('customer')->with('success', trans('messages.deleted', [ 'name' => trans('messages.names.customers') ]));
    }


    public function showEmailForm($customer_id)
    {
        $email_templates = EmailTemplate::where('hospital_id', session()->get('hospital_id'))->get()->toArray();
        $customer = Customer::findOrFail($customer_id);
        $hospital = Hospital::findOrFail(session()->get('hospital_id'));

        $mail_histories = MailHistory::where('customer_id', $customer_id)->orderBy('sent_datetime', 'DESC')->paginate(10);

        return response()->json([
            'data' => view('customer.partials.email', [
                'customer' => $customer,
                'hospital' => $hospital,
                'email_templates' => $email_templates,
                'customer_id' => $customer_id,
                'mail_histories' => $mail_histories
            ])->render(),
        ]);
    }


    public function emailSend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'title' => 'required|max:100',
            'contents' => 'required|max:1000'
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }

        $customer = Customer::findOrFail($request->get('customer_id'));

        $attributes = $request->only([ 'customer_id', 'title', 'contents']);
        $attributes = array_merge($attributes, [
           'sender_name' => 'unei@eparkdock.com',
           'sender_address' => 'unei@eparkdock.com',
           'email' => $customer->email
        ]);

        Mail::to($customer->email)->send(new CustomerSendMail($attributes));

        $mail_history = new MailHistory($attributes);
        $mail_history->save();

        return response()->json(['success' => trans('messages.sent', [ 'mail' => trans('messages.mails.customer') ])]);
    }

    public function email_history($customer_id, Request $request){
        $record_per_page = $request->input('record_per_page', 10);
        $mail_histories = MailHistory::where('customer_id', $customer_id)->orderBy('sent_datetime', 'DESC')->paginate($record_per_page);
        return response()->json([
            'data' => view('customer.partials.email-history', [
                'customer_id' => $customer_id,
                'mail_histories' => $mail_histories,
                'record_per_page' => $record_per_page
            ])->render(),
        ]);
    }


    public function customerSearch()
    {
        $customers = Customer::where('hospital_id', session()->get('hospital_id'))
            ->where(function($query) {
                $query->where('registration_card_number', 'LIKE', '%'. request()->search_text . '%')
                    ->orWhere(DB::raw("concat(family_name, first_name)"), 'LIKE', '%' . request()->search_text . '%')
                    ->orWhere(DB::raw("concat(family_name_kana, first_name_kana)"), 'LIKE', '%' . request()->search_text . '%')
                    ->orWhere('tel', request()->search_text);
        })->get();

        return response()->json([
            'data' => view('reservation.partials.create.customer-list', ['customers' => $customers])->render()
        ]);  
    }

    public function integration($id, Request $request)
    {
        try {
            DB::beginTransaction();
            $params = $request->all();
            $params['id'] = $id;

            $validator = Validator::make($params, [
                'id' => 'required|exists:customers,id',
                'identical_ids' => 'required|array',
                'identical_ids.*' => 'required|exists:customers,id'
            ]);

            if ($validator->fails())
            {
                return response()->json(['errors'=>$validator->errors()]);
            }

            $identical_ids = $request->input('identical_ids');
            $identical_customers = collect($identical_ids)->map(function($identical_id) use($id) {
               return [
                   'customer_id' => $id,
                   'integrated_customer_id' => $identical_id
               ];
            });

            NameIntegration::insert($identical_customers->toArray());

            // bulk soft delete
            Customer::whereIn('id', $identical_ids)
                ->update([
                    'deleted_at' => Carbon::now(),
                    'status' => Status::Deleted
                ]);

            DB::commit();
            return response()->json(['success'=> trans('messages.integration-success')]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error'=> trans('messages.integration-error')]);
        }
    }
}
