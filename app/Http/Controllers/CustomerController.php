<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Filters\Customer\CustomerFilters;
use App\Hospital;
use App\EmailTemplate;
use App\Http\Requests\CustomerFormRequest;
use App\Mail\Customer\CustomerSendMail;
use App\MailHistory;
use App\Prefecture;
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

        $customers       = Customer::filter($customerFilters)->orderBy('id', 'asc')->paginate($pagination);
        $customer_detail = [];// Customer::findOrFail( 1 );
        $reservations    = [];// $customer_detail->reservations()->paginate( 2 );

        return view('customer.index', [ 'customers'       => $customers]);
    }


    public function detail(Request $request)
    {
        $page_number     = ($request->page_id) ?? 1;
        $customer_detail = Customer::findOrFail($request->id);
        $reservations    = $customer_detail->reservations()->paginate(10, [ '*' ], 'page', $page_number);

        return response()->json([
            'data' => view('customer.partials.detail.tab-content', [
                'customer_detail' => $customer_detail,
                'reservations'    => $reservations
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
        if (Customer::create($request->all())) {
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

        if ($customer->update($request->all())) {
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
        $customers = Customer::where('registration_card_number', 'LIKE', '%'. request()->search_text . '%')
            ->orWhere(DB::raw("concat(first_name, ' ', family_name)"), 'LIKE', '%' . request()->search_text . '%')
            ->orWhere(DB::raw("concat(first_name_kana, ' ', family_name_kana)"), 'LIKE', '%' . request()->search_text . '%')
            ->orWhere('tel', request()->search_text)
            ->get();

        return response()->json([
            'data' => view('reservation.partials.create.customer-list', ['customers' => $customers])->render()
        ]);  
    }
}
