<?php

namespace App\Http\Controllers;

use App\Billing;
use App\Exports\BillingExport;
use App\Filters\Billing\BillingFilters;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel;

class BillingController extends Controller
{
    private $excel;

    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }

    public function index(BillingFilters $billingFilters)
    {


        if ( date('d') < 21 ) {

	        $startedDate = now()->setDate(date('Y'), date('m') - 2, 21);
	        $endedMonth = now()->setDate(date('Y'), date('m') - 1, 20);

        } else {

	        $endedMonth = now()->setDate(date('Y'), date('m'), 20);
	        $startedDate = now()->setDate(date('Y'), date('m') - 1, 21);

        }
dump($startedDate);
        dump($endedMonth);


	    $selectBoxMonths = [
	        $startedDate->copy()->subMonth(2)->format('Y-m'),
	        $startedDate->copy()->subMonth(1)->format('Y-m'),
	        $startedDate->format('Y-m'),
	        $startedDate->copy()->addMonth(1)->format('Y-m'),
	        $startedDate->copy()->addMonth(2)->format('Y-m'),
	        $startedDate->copy()->addMonth(3)->format('Y-m'),
	    ];

        $billings = Billing::filter($billingFilters)->whereBetween('created_at', [$startedDate->startOfDay(), $endedMonth->endOfDay()])->paginate(10);

        return view('billing.index', [
            'billings' => $billings,
            'startedDate' => $startedDate,
            'endedMonth' => $endedMonth,
            'selectBoxMonths' => $selectBoxMonths
        ]);
    }


    public function excelExport(BillingFilters $billingFilters)
    {

        $billings = Billing::filter($billingFilters)->whereDate('from', '<', now()->addDay(21))->get();

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

            $billing->update(['status' => $request->status]);


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
