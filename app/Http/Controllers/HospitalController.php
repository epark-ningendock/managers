<?php

namespace App\Http\Controllers;

use App\ContractInformation;
use App\DistrictCode;
use App\Enums\HospitalEnums;
use App\Hospital;

use App\HospitalDetail;
use App\HospitalMajorClassification;
use App\HospitalMiddleClassification;
use App\HospitalMinorClassification;

use App\HospitalStaff;
use App\Http\Requests\HospitalCreateFormRequest;
use App\Http\Requests\HospitalFormRequest;
use App\MedicalExaminationSystem;
use App\MedicalTreatmentTime;
use App\Prefecture;
use App\Rail;
use App\Station;
use Illuminate\Auth\SessionGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Enums\Permission;


class HospitalController extends Controller
{
    public function __construct(Request $request)
    {
        request()->session()->forget('hospital_id');
        $this->middleware('permission.hospital.edit')->except('index');
    }
    
    public function index(HospitalFormRequest $request)
    {
        if (Auth::user()->staff_auth->is_hospital === Permission::None) {
            return view('staff.edit-password-personal');
        }

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
        return view('hospital.create-contract-form', [ 'contract_information' => $contractInformation[0] ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $prefectures = Prefecture::all();
        $district_codes = DistrictCode::all();
        $medical_examination_systems = MedicalExaminationSystem::all();
        $stations = Station::all();
        $rails = Rail::all();

        return view('hospital.create', [
            'prefectures' => $prefectures,
            'district_codes' => $district_codes,
            'medical_examination_systems' => $medical_examination_systems,
            'stations' => $stations,
            'rails' => $rails,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response\
     */
    public function store(HospitalCreateFormRequest $request)
    {
        $request->request->add([
            'hospital_staff_id' => auth()->user()->id,
	        'prefecture' => Prefecture::findOrFail($request->prefecture)->name
        ]);

        $hospital = Hospital::create($request->all());

        if (!empty(request()->medical_treatment_time)) {
            foreach (request()->medical_treatment_time as $mtt) {
                $mtt = array_merge($mtt, ['hospital_id' => $hospital->id]);
                MedicalTreatmentTime::create($mtt);
            }
        }

        return redirect('/hospital/image-information');
    }


    public function createImageInformation()
    {
        return view('hospital.create-image-form');
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
        return view('hospital.create-contract-form', ['hospital' => $hospital]);
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

    public function selectHospital(Request $request, $id)
    {
        $hospital_name = Hospital::findOrFail($id)->name;
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

        $hospitals = $query->orderBy('created_at', 'desc')->paginate(10)->appends(request()->query());

        return view('hospital.index', [ 'hospitals' => $hospitals ])->with('success', trans('messages.operation'));
    }

    public function createAttentionInformation()
    {
        $middles = HospitalMiddleClassification::all();
        $hospital = Hospital::findOrFail(1);
        
        return view('hospital.attention-information')
            ->with('hospital', $hospital)
            ->with('middles', $middles);
    }

    public function storeAttentionInformation(Request $request)
    {
        try {
            $this->saveAttentionInformation($request);
            $request->session()->flash('success', trans('messages.created', ['name' => trans('messages.names.attetion_information')]));
            return redirect('hospital');
        } catch (Exception $e) {
            return redirect()->back()->withErrors(trans('messages.create_error'))->withInput();
        }
    }

    protected function saveAttentionInformation(Request $request)
    {
        $this->validate($request, [
            'pvad' => 'digits_between:1,10'
        ]);

        try {
            DB::beginTransaction();

            $hospital = Hospital::findOrFail(1);
            $hospital->pvad = $request->get('pvad');
            if ($request->get('is_pickup')) {
                $hospital->is_pickup = 1;
            } else {
                $hospital->is_pickup = 0;
            }
            $hospital->save();

            $minor_ids = collect($request->input('minor_ids'), []);
            $minor_values = collect($request->input('minor_values'), []);

            if ($minor_ids->isNotEmpty()) {
                $minors = HospitalMinorClassification::whereIn('id', $minor_ids)->orderBy('order')->get();

                if ($minors->count() != count($minor_ids)) {
                    $request->session()->flash('error', trans('messages.invalid_minor_id'));
                    return redirect()->back();
                }

                $hospital->hospital_details()->forceDelete();

                foreach ($minors as $index => $minor) {
                    $input_index = $minor_ids->search(function ($id) use ($minor) {
                        return $minor->id == $id;
                    });

                    if ($input_index == -1 || ($minor->is_fregist == '1' && $minor_values[$input_index] == 0)
                        || ($minor->is_fregist == '0' && $minor_values[$input_index] == '')) {
                        continue;
                    }


                    $hospital_details = new HospitalDetail();
                    $hospital_details->hospital_id = $hospital->id;
                    $hospital_details->minor_classification_id = $minor->id;
                    if ($minor->is_fregist == '1') {
                        $hospital_details->select_status = 1;
                    } else {
                        $hospital_details->inputstring = $minor_values[$input_index];
                    }
                    $hospital_details->save();
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
