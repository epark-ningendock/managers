<?php

namespace App\Http\Controllers;

use App\Billing;
use App\BillingMailHistory;
use App\BillingOptionPlan;
use App\Enums\ReservationStatus;
use App\Exports\BillingExport;
use App\Filters\Billing\BillingFilters;
use App\HospitalEmailSetting;
use App\Mail\Billing\BillingConfirmationSendMail;
use App\Reservation;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
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

    /**
     * 請求管理画面初期表示
     * @param BillingFilters $billingFilters
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function index( BillingFilters $billingFilters ) {

		$selectedMonth = $this->getSelectedMonth();

		$dateFilter = billingDateFilter(str_replace('-', '', $selectedMonth));
		session()->put('hospital_id', null);

		$billings = Billing::filter( $billingFilters )
            ->where('billing_month', '=', str_replace('-', '', $selectedMonth))->has('contract_information')->paginate(20);

		return view( 'billing.index', [
			'billings'        => $billings,
			'startedDate'     => $dateFilter['startedDate'],
			'endedDate'       => $dateFilter['endedDate'],
			'selectBoxMonths' => $dateFilter['selectBoxMonths'],
		] );
	}


    /**
     * 請求管理エクセル出力
     * @param BillingFilters $billingFilters
     * @return Excel|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
	public function excelExport( BillingFilters $billingFilters ) {

	    $selectedMonth = $this->getSelectedMonth();
	    $yyyymm = str_replace('-', '', $selectedMonth);
	    $dateFilter = billingDateFilter(str_replace('-', '', $selectedMonth));
	    $billings = Billing::filter( $billingFilters )->where('billing_month', '=', $yyyymm)->has('contract_information')->get();



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
	 * 請求詳細登録
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store( Request $request ) {
        $billing_id = $request->input('billing_id');
        $billing = Billing::find($billing_id);
        $billing_option_plans = BillingOptionPlan::where('billing_id', $billing->id)->get();
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

        if ($billing_option_plans) {
            foreach ($billing_option_plans as $billing_option_plan) {
                $billing_option_plan->forceDelete();
            }
        }

        $this->registBillingOptionPlan($billing->id, 1, $dr_movie_adjustment_price);
        $this->registBillingOptionPlan($billing->id, 2, $access_movie_adjustment_price);
        $this->registBillingOptionPlan($billing->id, 3, $onemin_movie_adjustment_price);
        $this->registBillingOptionPlan($billing->id, 4, $tour_movie_adjustment_price);
        $this->registBillingOptionPlan($billing->id, 5, $exam_movie_adjustment_price);
        $this->registBillingOptionPlan($billing->id, 6, $special_movie_adjustment_price);

        return redirect()->route('billing.show', ['billing' => $billing]);
	}

    /**
     * @param $billing_id
     * @param $option_plan_id
     * @param $adjustment_price
     */
	private function registBillingOptionPlan($billing_id, $option_plan_id, $adjustment_price) {

        if (isset($adjustment_price) && is_numeric($adjustment_price)) {
            $billing_option_plan = new BillingOptionPlan([
                'billing_id' => $billing_id,
                'option_plan_id' => $option_plan_id,
                'adjustment_price' => $adjustment_price,
                'created_at' => Carbon::today(),
                'updated_at' => Carbon::today(),
            ]);
            $billing_option_plan->save();
        }
    }

	/**
	 * 請求詳細表示
	 * @param \App\Billing $billing
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show( Billing $billing ) {

        $hospital_id = session()->get('hospital_id');

        if (isset($hospital_id) && $hospital_id != $billing->hospital_id) {
            abort(404);
        }

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


    /**
     *
     *　請求ステータス更新
     * @param Billing $billing
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
	public function statusUpdate( Billing $billing, Request $request ) {
		if ($request->admin == 1) session()->put('hospital_id', null);

		$email_send =  ( $request->has('claim_check') || $request->has('claim_confirmation') ) ? true : false;

		$billing->update( [ 'status' => $request->status ] );

		if ( $email_send ) $this->claimEmailCheck($request, $billing, []);

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

    /**
     * 請求メール送信
     * @param $request
     * @param $billing
     * @param array $attributes
     */
    public function claimEmailCheck($request, $billing)
    {
			$selectedMonth = $this->getSelectedMonth();
			$dateFilter = billingDateFilter($billing->billing_month);

			$special_count = 0;
			foreach( $billing->hospital->reservationByCompletedDate($dateFilter['startedDate'], $dateFilter['endedDate']) as $reservation) {
				// 「完了」に変更する。
				$reservation->reservation_status = ReservationStatus::COMPLETED;
				$reservation->save();

				if ($reservation->site_code === 'special') $special_count++;
			}

			$total_price = $billing->hospital->hospitalPlanByDate($dateFilter['endedDate'])->contractPlan->monthly_contract_fee
				+ $billing->adjustment_price
				+ $billing->hospital->hpLinkMonthPrice()
				+ $billing->hospital->hospitalOptionPlanPrice($billing->id, $dateFilter['endedDate'])
				+ $billing->hospital->reservationByCompletedDate($dateFilter['startedDate'], $dateFilter['endedDate'])->pluck('fee')->sum();


			$pdf =  PDF::loadView( 'billing.claim-check-pdf', [
				'billing' => $billing,
				'startedDate'     => $dateFilter['startedDate'],
				'endedDate'      => $dateFilter['endedDate'],
				'today_date' => Carbon::now()->format('Y年m月d日'),
				'period' => $dateFilter['startedDate']->format('Y/m/d') . '〜' . $dateFilter['endedDate']->format('Y/m/d'),
				'special_count' => $special_count,
				'total_price' => $total_price
			] )->setPaper('legal', 'landscape')->output();


			$attributes = [
				'email_type' => $request->has('claim_check') ? 'claim_check' : 'claim_confirmation',
				'selectedMonth' => $selectedMonth,
				'today_date' => Carbon::now()->format('Y/m/d'),
				'period' => $dateFilter['startedDate']->format('Y/m/d') . '〜' . $dateFilter['endedDate']->format('Y/m/d'),
				'special_count' => $special_count,
				'total_price' => $total_price
			];


			if (session('hospital_id')){

				// gyoumuに送信
				$confirmMailComposition4Gyoumu = [
					'subject' => '【EPARK人間ドック】医療機関による請求確認完了のお知らせ',
					'billing' => $billing,
					'attachment_file_name' => '請求確認PDF',
				];

				Mail::to(env('MAIL_TO_GYOUMU_ADMIN_ADDRESS'))->send(new BillingConfirmationSendMail( $confirmMailComposition4Gyoumu, $pdf, false, $attributes));

			}else{

				$hospitalEmailSetting = HospitalEmailSetting::where( 'hospital_id', '=', (int)$request->hospital_id )
					->where('billing_email_flg', 1)
					->first();

				if ( $hospitalEmailSetting ) {

					$confirmMailComposition = [
						'subject' => $request->has('claim_check') ? '【EPARK人間ドック】請求内容ご確認のお願い' : '【EPARK人間ドック】請求内容確定のお知らせ',
						'billing' => $billing,
						'attachment_file_name' => $request->has('claim_check') ? '請求確認' : '請求確定',
					];

					$tos = [];
					if (!empty($hospitalEmailSetting->billing_email1)) $tos[] = $hospitalEmailSetting->billing_email1;
					if (!empty($hospitalEmailSetting->billing_email2)) $tos[] = $hospitalEmailSetting->billing_email2;
					if (!empty($hospitalEmailSetting->billing_email3)) $tos[] = $hospitalEmailSetting->billing_email3;
					if (!empty($hospitalEmailSetting->billing_email4)) $tos[] = $hospitalEmailSetting->billing_email4;
					if (!empty($hospitalEmailSetting->billing_fax_number)) $tos[] = $hospitalEmailSetting->billing_fax_number;

					$fax_flg = false;

					if (!empty($tos)) {
						foreach ($tos as $to) {
							if (strpos($to, 'fax') !== false) $fax_flg = true;
							Mail::to($to)->send( new BillingConfirmationSendMail( $confirmMailComposition, $pdf, $fax_flg, $attributes));
						}

						$billingMailHistory = new BillingMailHistory();
						$billingMailHistory->create( [
							'hospital_id' => $hospitalEmailSetting->hospital_id,
							'to_address1' => $hospitalEmailSetting->billing_email1,
							'to_address2' => $hospitalEmailSetting->billing_email2,
							'to_address3' => $hospitalEmailSetting->billing_email3,
							'to_address4' => $hospitalEmailSetting->billing_email4,
							'cc_name'     => $hospitalEmailSetting->hospital->name,
							'fax'         => $hospitalEmailSetting->billing_fax_number,
							'mail_type'   => ( $hospitalEmailSetting->mail_type == 1 ) ? 1 : 2,
						]);
					}
				}

			}

		}

    /**
     *
     * @param $request
     * @param $billing
     * @param array $attributes
     */
	public function claimEmailCheckForHospital($request, $billing, $attributes = [])
	    {
		    $selectedMonth = $this->getSelectedMonth();
	        $dateFilter = billingDateFilter($billing->billing_month);
	        $hospitalEmailSetting = HospitalEmailSetting::where( 'hospital_id', '=', session('hospital_id') )->first();

//	        if ( $hospitalEmailSetting ) {
//
//	            $pdf =  PDF::loadView( 'billing.claim-check-pdf', [
//	                'billing' => $billing,
//	                'startedDate'     => $billing->startedDate,
//	                'endedDate'      => $billing->endedDate,
//
//	            ] )->setPaper('legal', 'landscape');
//
//	            $hospitalEmailSetting = HospitalEmailSetting::where( 'hospital_id', '=', session('hospital_id') )->first();
//
//	            $confirmMailComposition = [
//	                'subject' => '【EPARK人間ドック】請求確認完了のお知らせ',
//	                'billing' => $billing,
//	                'attachment_file_name' => '請求確認PDF',
//	            ];
//
//	            $attributes = [
//	                'email_type' => $request->has('claim_check') ? 'claim_check' : 'claim_confirmation',
//		            'selectedMonth' => $billing->billing_month
//	            ];

//	            Mail::to( [
//	                config('mail.to.gyoumu'),
//	            ] )->send( new BillingConfirmationSendMail( $confirmMailComposition, $pdf, $attributes));
//	        }
		}

    /**
     * 医療機関請求管理表示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function hospitalBilling() {

		$hospital_id = session('hospital_id');
		$billings = Billing::where('hospital_id', '=', $hospital_id)->orderBy('billing_month', 'desc')->paginate(12);

		return view( 'billing.hospital-billing-listing', [
			'billings'        => $billings,
		] );		
	}
}
