<?php

namespace App\Http\Controllers;

use App\Enums\HospitalEnums;
use App\Hospital;
use Illuminate\Http\Request;

class HospitalController extends Controller
{

    public function index(Request $request)
    {
    	$query = Hospital::query();

    	if ( $request->get('s_text')  ) {
    		$query->where('name', 'LIKE', "%". $request->get('s_text') . "%");
	    }

	    if ( $request->get('status') || ($request->get('status') === '0')   ) {
		    $query->where('status','=',$request->get('status'));
	    }

	    if ( empty($request->get('s_text')) && empty($request->get('status')) && ( $request->get('status') !== '0')) {
    	    $query->where('status', HospitalEnums::Public);
	    }

	    $hospitals = $query->orderBy('created_id', 'desc')->paginate(10)->appends(request()->query());

		return view( 'hospital.index', [ 'hospitals' => $hospitals ] );
    }


	public function searchText(Request $request) {
		$hospitals = Hospital::select('name', 'address1')->where('name', 'LIKE', "%" .$request->get('s_text') . "%" )->get();
		return response()->json($hospitals);
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
     * @param  \App\Hospital  $hospital
     * @return \Illuminate\Http\Response
     */
    public function show(Hospital $hospital)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Hospital  $hospital
     * @return \Illuminate\Http\Response
     */
    public function edit(Hospital $hospital)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Hospital  $hospital
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hospital $hospital)
    {
        //
    }


    public function destroy(Hospital $hospital)
    {

    }
}
