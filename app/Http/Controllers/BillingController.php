<?php

namespace App\Http\Controllers;

use App\Billing;
use App\BillingMailHistory;
use App\Exports\BillingExport;
use App\Filters\Billing\BillingFilters;
use App\HospitalEmailSetting;
use App\Mail\Billing\BillingConfirmationSendMail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Excel;

class BillingController extends Controller
{
    private $excel;

    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }

    public function billingDateFilter()
    {
        
        if ( request('billing_month') ) {
            $date = Carbon::parse(request('billing_month') . '-' . 28);
            $date =  ( $date->isCurrentMonth() ) ? now() : $date;

        } else {
            $date = now();
        }


        if ( $date->day < 21 ) {

            $startMonthNumber = ( $date->isCurrentMonth() ) ? $date->copy()->subMonth(2)->month : $date->copy()->subMonth(1)->month;
            $endMonthNumber = ( $date->isCurrentMonth() ) ? $date->copy()->subMonth(1)->month : $date->month;

            $startedDate = $date->copy()->setDate($date->year, $startMonthNumber, 21);
            $endedMonth = $date->copy()->setDate($date->year, $endMonthNumber, 20);

        } else {

            $startedDate = $date->copy()->setDate($date->year, $date->copy()->subMonth(1)->month, 21);
            $endedMonth = $date->copy()->setDate($date->year, $date->month, 20);

        }

        $selectBoxMonths = [
            $startedDate->copy()->subMonth(2)->format('Y-m'),
            $startedDate->copy()->subMonth(1)->format('Y-m'),
            $startedDate->format('Y-m'),
            $startedDate->copy()->addMonth(1)->format('Y-m'),
            $startedDate->copy()->addMonth(2)->format('Y-m'),
            $startedDate->copy()->addMonth(3)->format('Y-m'),
        ];

        return ['startedDate' => $startedDate->startOfDay(), 'endedDate' => $endedMonth->endOfDay(), 'selectBoxMonths' => $selectBoxMonths];
    }

    public function index(BillingFilters $billingFilters)
    {

        $dateFilter = $this->billingDateFilter();

        $billings = Billing::filter($billingFilters)->whereBetween('created_at', [$dateFilter['startedDate'], $dateFilter['endedDate']])->paginate(10);

        return view('billing.index', [
            'billings' => $billings,
            'startedDate' => $dateFilter['startedDate'],
            'endedMonth' => $dateFilter['endedDate'],
            'selectBoxMonths' => $dateFilter['selectBoxMonths']
        ]);
    }


    public function excelExport(BillingFilters $billingFilters)
    {

        $billings = Billing::filter($billingFilters)->whereDate('from', '<', $date->addDay(21))->get();

        return $this->excel->download(new BillingExport($billings), 'billing.xlsx');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function show(Billing $billing)
    {
        return view('billing.show', ['billing' => $billing]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function edit(Billing $billing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Billing $billing)
    {
        //
    }


    public function statusUpdate(Billing $billing, Request $request)
    {
        try {

            DB::beginTransaction();

            if ( $request->status !== 4 ) {

	            $billing->update( [ 'status' => $request->status ] );

	            $hospitalEmailSetting = HospitalEmailSetting::where( 'hospital_id', '=', 1 )->first();

	            $confirmMailComposition = [
		            'subject' => '請求確認メール',
		            'content' => '請求確認メール'
	            ];

	            $email = Mail::to( [
		            $hospitalEmailSetting->billing_email1,
		            $hospitalEmailSetting->billing_email2,
		            $hospitalEmailSetting->billing_email3,
		            $hospitalEmailSetting->billing_fax_number
	            ] )->send( new BillingConfirmationSendMail( $confirmMailComposition ) );

	            $billingMailHistory = new BillingMailHistory();

	            $billingMailHistory->create( [
		            'hospital_id' => $hospitalEmailSetting->hospital_id,
		            'to_address1' => $hospitalEmailSetting->billing_email1,
		            'to_address2' => $hospitalEmailSetting->billing_email2,
		            'to_address3' => $hospitalEmailSetting->billing_email3,
		            'cc_name'     => $hospitalEmailSetting->hospital->name,
		            'fax'         => $hospitalEmailSetting->billing_fax_number,
		            'mail_type'   => ( $hospitalEmailSetting->mail_type == 1 ) ? 1 : 2,
	            ] );

            }


            return redirect('billing')->with('success', trans('messages.updated', ['name' => trans('messages.names.billing')]));

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', trans('messages.update_error'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function destroy(Billing $billing)
    {
        //
    }
}
