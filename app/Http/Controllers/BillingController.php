<?php

namespace App\Http\Controllers;

use App\Billing;
use App\Exports\BillingExport;
use App\Filters\Billing\BillingFilters;
use Illuminate\Http\Request;
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
        $billings = Billing::filter($billingFilters)->whereDate('from', '<', now()->addDay(21))->paginate(10);          

        return view('billing.index', ['billings' => $billings]);
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


    public function statusUpdate(Request $request)
    {
        dd('here');
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
