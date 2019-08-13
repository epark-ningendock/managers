<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Filters\Customer\CustomerFilters;
use App\Hospital;
use App\EmailTemplate;
use App\Http\Requests\CustomerFormRequest;
use App\Mail\Customer\CustomerSendMail;
use App\Prefecture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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
        
        return response()->json([
            'data' => view('customer.partials.email', [
                'customer' => $customer,
                'hospital' => $hospital,
                'email_templates' => $email_templates
            ])->render(),
        ]);
    }


    public function emailSend(Request $request)
    {
        $this->validate($request, [
            'customer_email' => 'required|email|max:100',
            'hospital_email' => 'required|email|max:100',
            'title' => 'required|max:100',
            'text' => 'required|max:1000'
        ]);

        Mail::to($request->customer_email)->send(new CustomerSendMail($request->all()));

        return redirect('/customer')->with('success', trans('messages.sent', [ 'mail' => trans('messages.mails.customer') ]));
    }
}
