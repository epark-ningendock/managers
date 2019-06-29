<?php

namespace App\Http\Controllers;

use App\Enums\HospitalEnums;
use App\Hospital;
use Illuminate\Http\Request;
use App\Http\Requests\HospitalFormRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Auth\SessionGuard;

class HospitalController extends Controller
{
    public function index(HospitalFormRequest $request)
    {
        $query = Hospital::query();

        if ($request->get('s_text')) {
            $query->where('name', 'LIKE', "%". $request->get('s_text') . "%");
        }

        if ($request->get('status') || ($request->get('status') === '0')) {
            $query->where('status', '=', $request->get('status'));
        }

        if (empty($request->get('s_text')) && empty($request->get('status')) && ($request->get('status') !== '0')) {
            $query->where('status', HospitalEnums::Public);
        }

        $hospitals = $query->orderBy('created_id', 'desc')->paginate(10)->appends(request()->query());

        return view('hospital.index', [ 'hospitals' => $hospitals ]);
    }


    public function searchText(Request $request)
    {
        $hospitals = Hospital::select('name', 'address1')->where('name', 'LIKE', "%" .$request->get('s_text') . "%")->get();
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

    public function selectHospital(HospitalFormRequest $request, $id)
    {
        $hospital_name = Hospital::findOrFail(intval($id))->name;
        session()->put('hospital_id', $id);
        session()->put('hospital_name', $hospital_name);

        $query = Hospital::query();

        if ($request->get('s_text')) {
            $query->where('name', 'LIKE', "%". $request->get('s_text') . "%");
        }

        if ($request->get('status') || ($request->get('status') === '0')) {
            $query->where('status', '=', $request->get('status'));
        }

        if (empty($request->get('s_text')) && empty($request->get('status')) && ($request->get('status') !== '0')) {
            $query->where('status', HospitalEnums::Public);
        }

        $hospitals = $query->orderBy('created_id', 'desc')->paginate(10)->appends(request()->query());

        return view('hospital.index', [ 'hospitals' => $hospitals ])->with('success', trans('messages.created', ['name' => trans('messages.names.email_template')]));
    }

    public function showAttentionInformation()
    {
        /* TODO 他の医療機関情報と結合するときに、引数を変更する */
        $hospital = Hospital::findOrFail(1);

        return view('hospital.attention-information', [ 'hospital' => $hospital ]);
    }

    public function storeAttentionInformation(Request $request)
    {
        dd($request->all());

        /* TODO 以下、indexと同じ処理なので、共通メソッドにしたい */
        $query = Hospital::query();

        if ($request->get('s_text')) {
            $query->where('name', 'LIKE', "%". $request->get('s_text') . "%");
        }

        if ($request->get('status') || ($request->get('status') === '0')) {
            $query->where('status', '=', $request->get('status'));
        }

        if (empty($request->get('s_text')) && empty($request->get('status')) && ($request->get('status') !== '0')) {
            $query->where('status', HospitalEnums::Public);
        }

        $hospitals = $query->orderBy('created_id', 'desc')->paginate(10)->appends(request()->query());

        return view('hospital.index', [ 'hospitals' => $hospitals ]);
    }
}
