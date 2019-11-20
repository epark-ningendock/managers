<?php

namespace App\Http\Controllers;

use App\Billing;
use App\BillingMailHistory;
use App\Exports\BillingExport;
use App\Filters\Billing\BillingFilters;
use App\HospitalEmailSetting;
use App\Mail\Billing\BillingConfirmationSendMail;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Excel;

class BillingController extends Controller {
	private $excel;

	public function __construct( Excel $excel ) {
		$this->excel = $excel;
	}

	public function getSelectedMonth() {
        $default_month = (now()->day < 21 ? now()->subMonthNoOverflow(1)->format('Y-m') : now()->format('Y-m'));
        return ( request('billing_month') ) ? request('billing_month') : $default_month;
	}

	public function index( BillingFilters $billingFilters ) {

		$selectedMonth = $this->getSelectedMonth();

		$dateFilter = billingDateFilter(str_replace('-', '', $selectedMonth));
		session()->put('hospital_id', null);

		$billings = Billing::filter( $billingFilters )->where('billing_month', '=', str_replace('-', '', $selectedMonth))->paginate(20);

		return view( 'billing.index', [
			'billings'        => $billings,
			'startedDate'     => $dateFilter['startedDate'],
			'endedDate'       => $dateFilter['endedDate'],
			'selectBoxMonths' => $dateFilter['selectBoxMonths'],
		] );
	}


	public function excelExport( BillingFilters $billingFilters ) {

    $selectedMonth = $this->getSelectedMonth();

		$dateFilter = billingDateFilter(str_replace('-', '', $selectedMonth));

    $billings = Billing::filter( $billingFilters )->where('billing_month', '=', $selectedMonth)->paginate(20);

		$yyyymm = str_replace('-', '', $selectedMonth);

		return $this->excel->download( new BillingExport( $billings, $dateFilter['startedDate'], $dateFilter['endedDate'], $selectedMonth ), "顧客請求対象_$yyyymm.xlsx" );

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store( Request $request ) {
        $billing_id = $request->input('billing_id');
        $billing = Billing::find($billing_id);
        $adjustment_price = $request->input('adjustment_price');
        $dr_movie_adjustment_price = $request->input('optionplanadjustmentprice_1');
        $access_movie_adjustment_price = $request->input('optionplanadjustmentprice_2');
        $onemin_movie_adjustment_price = $request->input('optionplanadjustmentprice_3');
        $tour_movie_adjustment_price = $request->input('optionplanadjustmentprice_4');
        $exam_movie_adjustment_price = $request->input('optionplanadjustmentprice_5');
        $special_movie_adjustment_price = $request->input('optionplanadjustmentprice_6');

        if (isset($adjustment_price) && is_numeric($adjustment_price)) {
            $billing->adjustment_price = $adjustment_price;
            $billing->save();
        }

        return route('billing.show', ['billing' => $billing]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param \App\Billing $billing
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show( Billing $billing ) {

        $dateFilter = billingDateFilter($billing->billing_month);

		return view( 'billing.show', [
		    'billing' => $billing,
            'startedDate'     => $dateFilter['startedDate'],
            'endedDate'      => $dateFilter['endedDate'],
        ] );
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param \App\Billing $billing
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit( Billing $billing ) {
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \App\Billing $billing
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update( Request $request) {

	    $billing_id = $request->input('billing_id');
	    $billing = Billing::find($billing_id);
	    $adjustment_price = $request->input('adjustment_price');
	    $dr_movie_adjustment_price = $request->input('optionplanadjustmentprice_1');
        $access_movie_adjustment_price = $request->input('optionplanadjustmentprice_2');
        $onemin_movie_adjustment_price = $request->input('optionplanadjustmentprice_3');
        $tour_movie_adjustment_price = $request->input('optionplanadjustmentprice_4');
        $exam_movie_adjustment_price = $request->input('optionplanadjustmentprice_5');
        $special_movie_adjustment_price = $request->input('optionplanadjustmentprice_6');

        if (isset($adjustment_price) && is_numeric($adjustment_price)) {
            $billing->adjustment_price = $adjustment_price;
            $billing->save();
        }

        return redirect()->route('billing.index');
	}


	public function statusUpdate( Billing $billing, Request $request ) {

        $email_send =  ( $request->has('claim_check') || $request->has('claim_confirmation') ) ? true : false;

        $billing->update( [ 'status' => $request->status ] );

        if ( $email_send ){

        	if ( session('hospital_id') ) {
        		$this->claimEmailCheckForHospital($request, $billing, []);
        	} else {
        		$this->claimEmailCheck($request, $billing, []);
        	}

        }
		if ($request->route()->getName() == "billing.status.update" ) {

			if ( session('hospital_id') ) {
				return redirect(route('hospital.billing'))->with( 'success', trans( 'messages.updated', [ 'name' => trans( 'messages.names.billing' ) ] ) );
			} else {
				return redirect('billing')->with( 'success', trans( 'messages.updated', [ 'name' => trans( 'messages.names.billing' ) ] ) );
			}

		} else {
			return back()->with( 'success', trans( 'messages.updated', [ 'name' => trans( 'messages.names.billing' ) ] ) );
		}
	}

    public function claimEmailCheck($request, $billing, $attributes = [])
    {
	    $selectedMonth = $this->getSelectedMonth();
        $dateFilter = billingDateFilter($billing->billing_month);
        $hospitalEmailSetting = HospitalEmailSetting::where( 'hospital_id', '=', (int)$request->hospital_id )->first();

        if ( $hospitalEmailSetting ) {

            $pdf =  PDF::loadView( 'billing.claim-check-pdf', [
                'billing' => $billing,
                'startedDate'     => $dateFilter['startedDate'],
                'endedDate'      => $dateFilter['endedDate'],
            ] )->setPaper('legal', 'landscape');

            $hospitalEmailSetting = HospitalEmailSetting::where( 'hospital_id', '=', (int)$request->hospital_id )->first();

            $confirmMailComposition = [
                'subject' => $request->has('claim_check') ? '【EPARK人間ドック】請求内容確認のお願い' : '【EPARK人間ドック】請求金額確定のお知らせ',
                'billing' => $billing,
                'attachment_file_name' => $request->has('claim_check') ? '請求確認' : '請求確定',
            ];

            $attributes = [
                'email_type' => $request->has('claim_check') ? 'claim_check' : 'claim_confirmation',
	            'selectedMonth' => $selectedMonth
            ];

            Mail::to( [
                $hospitalEmailSetting->billing_email1,
                $hospitalEmailSetting->billing_email2,
                $hospitalEmailSetting->billing_email3,
                $hospitalEmailSetting->billing_fax_number . '@faxmail.com',
            ] )->send( new BillingConfirmationSendMail( $confirmMailComposition, $pdf, $attributes));

            $billingMailHistory = new BillingMailHistory();

            $billingMailHistory->create( [
                'hospital_id' => $hospitalEmailSetting->hospital_id,
                'to_address1' => $hospitalEmailSetting->billing_email1,
                'to_address2' => $hospitalEmailSetting->billing_email2,
                'to_address3' => $hospitalEmailSetting->billing_email3,
                'cc_name'     => $hospitalEmailSetting->hospital->name,
                'fax'         => $hospitalEmailSetting->billing_fax_number . '@faxmail.com',
                'mail_type'   => ( $hospitalEmailSetting->mail_type == 1 ) ? 1 : 2,
            ] );
        }
	}

	public function claimEmailCheckForHospital($request, $billing, $attributes = [])
	    {
		    $selectedMonth = $this->getSelectedMonth();
	        $dateFilter = billingDateFilter($billing->billing_month);
	        $hospitalEmailSetting = HospitalEmailSetting::where( 'hospital_id', '=', session('hospital_id') )->first();

	        if ( $hospitalEmailSetting ) {

	            $pdf =  PDF::loadView( 'billing.claim-check-pdf', [
	                'billing' => $billing,
	                'startedDate'     => $billing->startedDate,
	                'endedDate'      => $billing->endedDate,
	            ] )->setPaper('legal', 'landscape');

	            $hospitalEmailSetting = HospitalEmailSetting::where( 'hospital_id', '=', session('hospital_id') )->first();

	            $confirmMailComposition = [
	                'subject' => '【EPARK人間ドック】請求確認完了のお知らせ',
	                'billing' => $billing,
	                'attachment_file_name' => '請求確認PDF',
	            ];

	            $attributes = [
	                'email_type' => $request->has('claim_check') ? 'claim_check' : 'claim_confirmation',
		            'selectedMonth' => $billing->billing_month
	            ];

	            Mail::to( [
	                env('DOCK_EMAIL_ADDRESS'),
	            ] )->send( new BillingConfirmationSendMail( $confirmMailComposition, $pdf, $attributes));
	        }
		}	

	public function hospitalBilling() {

		$hospital_id = session('hospital_id');
		$billings = Billing::where('hospital_id', '=', $hospital_id)->orderBy('billing_month', 'desc')->paginate(12);

		return view( 'billing.hospital-billing-listing', [
			'billings'        => $billings,
		] );		
	}
}
