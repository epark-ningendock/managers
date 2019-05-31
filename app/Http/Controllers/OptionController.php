<?php

namespace App\Http\Controllers;

use App\Http\Requests\OptionformStore;
use App\Option;
use App\TaxClass;
use Illuminate\Http\Request;

class OptionController extends Controller
{

    public function index()
    {
    	    $pagination = config('epark.pagination.option_index');
			$options = Option::paginate($pagination);

			return view('option.index', ['options' => $options]);
    }


    public function create()
    {
    	$tax_classes = TaxClass::all();
        return view('option.create', ['tax_classes' => $tax_classes]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OptionformStore $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
