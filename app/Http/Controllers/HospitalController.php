<?php

namespace App\Http\Controllers;

use App\DistrictCode;
use App\Enums\HospitalEnums;
use App\Hospital;
use App\HospitalDetail;
use App\HospitalMajorClassification;
use App\HospitalMiddleClassification;
use App\HospitalMinorClassification;
use App\ContractInformation;
use App\HospitalStaff;
use App\Http\Requests\HospitalCreateFormRequest;
use App\Http\Requests\HospitalFormRequest;
use App\MedicalExaminationSystem;
use App\MedicalTreatmentTime;
use App\Prefecture;
use App\Rail;
use App\Station;
use App\FeeRate;
use Illuminate\Auth\SessionGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Enums\Permission;
use Reshadman\OptimisticLocking\StaleModelLockingException;


class HospitalController extends Controller
{
    public function __construct(Request $request)
    {
        request()->session()->forget('hospital_id');
        // TODO: middlewareでindexだけにかけるものを作る
        $this->middleware('permission.hospital.edit')->except('index');
    }

    public function index(HospitalFormRequest $request)
    {
        if (Auth::user()->staff_auth->is_hospital === Permission::None) {
            if (Auth::user()->staff_auth->is_staff !== Permission::None) {
                return redirect('/staff');
            } elseif (Auth::user()->staff_auth->is_cource_classification !== Permission::None) {
                return redirect('/classification');
            } elseif (Auth::user()->staff_auth->is_invoice !== Permission::None) {
                return redirect('/reservation');
            } elseif (Auth::user()->staff_auth->is_pre_account !== Permission::None) {
                // TODO: 事前決済機能が出来次第実装する
                return redirect('/');
            } else {
                session()->flush();
                Auth::logout();
                return redirect('/login')->with('error', 'スタッフ権限がありません。');
            }
        }

        $query = Hospital::query();

        if ($request->get('s_text')) {
            $query->where('name', 'LIKE', "%" . $request->get('s_text') . "%");
        }

        if ($request->get('status') || ($request->get('status') === '0')) {
            $query->where('status', '=', $request->get('status'));
        }

        if (empty($request->get('s_text')) && empty($request->get('status')) && ($request->get('status') !== '0')) {
            $query->where('status', HospitalEnums::Public);
        }

        $hospitals = $query->orderBy('created_at', 'desc')->paginate(10)->appends(request()->query());
        return view('hospital.index', ['hospitals' => $hospitals]);
    }


    public function searchText(Request $request)
    {
        $hospitals = Hospital::select('name', 'address1')->where('name', 'LIKE', "%" . $request->get('s_text') . "%")->get();
        return response()->json($hospitals);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response\
     */
    public function store(HospitalCreateFormRequest $request)
    {

        try {
            DB::beginTransaction();

            $request->request->add([
                'hospital_staff_id' => auth()->user()->id,
            ]);

            $hospital = Hospital::create($request->all());

            if (!empty(request()->medical_treatment_time)) {
                foreach (request()->medical_treatment_time as $mtt) {
                    $mtt = array_merge($mtt, ['hospital_id' => $hospital->id]);
                    MedicalTreatmentTime::create($mtt);
                }
            }


            DB::commit();
            return redirect('/hospital/image-information');
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Hospital $hospital
     * @return \Illuminate\Http\Response
     */
    public function show(Hospital $hospital)
    {
        //
    }


    public function edit(Hospital $hospital)
    {
        $prefectures = Prefecture::all();
        $district_codes = DistrictCode::all();
        $medical_examination_systems = MedicalExaminationSystem::all();
        $medical_treatment_times = MedicalTreatmentTime::where('hospital_id', $hospital->id)->get();
        $stations = Station::all();
        $rails = Rail::all();

        return view('hospital.edit', [
            'hospital' => $hospital,
            'prefectures' => $prefectures,
            'district_codes' => $district_codes,
            'medical_examination_systems' => $medical_examination_systems,
            'stations' => $stations,
            'rails' => $rails,
            'medical_treatment_times' => $medical_treatment_times,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Hospital $hospital
     * @return \Illuminate\Http\Response
     */
    public function update(HospitalCreateFormRequest $request, Hospital $hospital)
    {

        try {
	        DB::beginTransaction();

	        $hospital = Hospital::findOrFail( $hospital->id );
	        $hospital->fill( $request->all() );
	        $hospital->touch();
	        $hospital->save();

	        if ( !empty( request()->medical_treatment_time ) ) {
		        foreach ( request()->medical_treatment_time as $mtt ) {
		            if ( isset($mtt['id']) && !empty($mtt['id']) ) {
                        $medical_treatment_times = MedicalTreatmentTime::findOrFail( $mtt['id'] );
                        $medical_treatment_times->update( MedicalTreatmentTime::getDefaultFieldValues( $mtt ) );
                    } else {
                        $mtt = array_merge($mtt, ['hospital_id' => $hospital->id]);
                        MedicalTreatmentTime::create($mtt);
                    }
		        }
	        }
	        DB::commit();
	        return redirect( '/hospital' )->with( 'success', '更新成功' );
        } catch (Exception $e) {
	        DB::rollback();
	        $request->session()->flash('error', trans('messages.update_error'));
	        return redirect()->back()->withInput();
        }  catch(StaleModelLockingException $e) {
	        $request->session()->flash('error', trans('messages.model_changed_error'));
	        return redirect()->back();
        }
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
            $query->where('name', 'LIKE', "%" . $request->get('s_text') . "%");
        }

        if ($request->get('status') || ($request->get('status') === '0')) {
            $query->where('status', '=', $request->get('status'));
        }

        if (empty($request->get('s_text')) && empty($request->get('status')) && ($request->get('status') !== '0')) {
            $query->where('status', HospitalEnums::Public);
        }

        $hospitals = $query->orderBy('created_at', 'desc')->paginate(10)->appends(request()->query());

        return view('hospital.index', ['hospitals' => $hospitals])->with('success', trans('messages.operation'));
    }
}
