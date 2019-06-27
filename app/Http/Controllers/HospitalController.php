<?php

namespace App\Http\Controllers;

use App\Enums\HospitalEnums;
use App\Hospital;
use App\ContractInformation;
use App\HospitalStaff;
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

        $hospitals = $query->orderBy('created_at', 'desc')->paginate(10)->appends(request()->query());

        return view('hospital.index', [ 'hospitals' => $hospitals ]);
    }


    public function searchText(Request $request)
    {
        $hospitals = Hospital::select('name', 'address1')->where('name', 'LIKE', "%" .$request->get('s_text') . "%")->get();
        return response()->json($hospitals);
    }

    // TODO この関数は全体的に見直し必要
    public function searchHospiralContractInfo(Request $request)
    {
        // returnさせる医療機関一覧
        $contractInformations = [];
        $inputText = $request->get('contract_info_search_word');
        // 医療機関名ID検索 or 医療機関名検索

        // ドグネット検索
        $contractInformation = ContractInformation::select()->where('code', 'LIKE', "%" .$inputText . "%")->get();
        // 医療機関名検索
        // TODO 複数該当する可能性がある
        // $hospitals = Hospital::select()->where('name', 'LIKE', "%" .$inputText . "%")->get();
        // 契約者名検索
        // TODO 複数該当する可能性がある
        // $contractInformations = ContractInformation::select()->where('contractor_name', 'LIKE', "%" .$inputText . "%")->get();

        // 関連する医療機関を追加
        // array_push($hospitals, $contractInformations->hospital);

        // 懸念点
        // 複数一致する条件があった場合、どうするのか？
        // 一旦、候補を複数検索させて、候補を表示する感じ？？
        // $hospitals = Hospital::where(id, $inputText);
        // $contractInformation = ContractInformation::find($inputText);

        // $hospitals = Hospital::select('name', 'address1')->where('name', 'LIKE', "%" .$request->get('s_text') . "%")->get();
        // return response()->json($responseJson);
        return view('hospital.contract-form', [ 'contract_information' => $contractInformation[0] ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('hospital.create');
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

    
    public function edit(Hospital $hospital)
    {
        return view('hospital.contract-form');
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
}
