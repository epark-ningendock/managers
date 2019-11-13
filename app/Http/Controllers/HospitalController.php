<?php

namespace App\Http\Controllers;

use App\DistrictCode;
use App\Enums\HospitalEnums;
use App\Enums\Permission;
use App\Hospital;
use App\Http\Requests\HospitalCreateFormRequest;
use App\Http\Requests\HospitalFormRequest;
use App\MedicalExaminationSystem;
use App\MedicalTreatmentTime;
use App\Prefecture;
use App\Rail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Reshadman\OptimisticLocking\StaleModelLockingException;


class HospitalController extends Controller
{
    public function __construct(Request $request)
    {
        request()->session()->forget('hospital_id');
        $this->middleware('permission.hospital.edit')->except('index');
    }

    public function index(HospitalFormRequest $request)
    {
        if (Auth::user()->staff_auth->is_hospital === Permission::NONE) {
            if (Auth::user()->staff_auth->is_staff !== Permission::NONE) {
                return redirect('/staff');
            } elseif (Auth::user()->staff_auth->is_cource_classification !== Permission::NONE) {
                return redirect('/classification');
            } elseif (Auth::user()->staff_auth->is_invoice !== Permission::NONE) {
                return redirect('/reservation');
            } elseif (Auth::user()->staff_auth->is_pre_account !== Permission::NONE) {
                // TODO: 事前決済機能が出来次第実装する
                return redirect('/');
            } else {
                session()->flush();
                Auth::logout();
                return redirect('/login')->with('error', 'スタッフ権限がありません。');
            }
        }

        $query = Hospital::query()
            ->with(['contract_information', 'prefecture', 'districtCode']);

        if ($request->get('s_text')) {
            $query->where(function ($query) use ($request) {
                $query->whereHas('contract_information', function ($query) use ($request) {
                    $query->where('customer_no', 'like', '%' . $request->get('s_text') . '%');
                });
                $query->orWhere('name', 'LIKE', "%" . $request->get('s_text') . "%");
            });
        }

        if ($request->get('status') || ($request->get('status') === '0')) {
            $query->where('status', '=', $request->get('status'));
        }

        if (empty($request->get('s_text')) && empty($request->get('status')) && ($request->get('status') !== '0')) {
            $query->where('status', HospitalEnums::PUBLIC);
        }

        $hospitals = $query->orderBy('created_at', 'desc')->paginate(10)->appends(request()->query());
        return view('hospital.index', ['hospitals' => $hospitals, 'request' => $request]);
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
        $rails = [];
        if ($hospital->prefecture_id) {
            $rails = Prefecture::find($hospital->prefecture_id)->rails()->get();
        }
        $five_stations = [];
        for ($i = 1; $i <= 5; $i++) {
            if ($hospital->{'rail' . $i}) {
                if (!is_null(Rail::find($hospital->{'rail' . $i}))) {
                    array_push($five_stations, Rail::find($hospital->{'rail' . $i})->stations()->get());
                } else {
                    array_push($five_stations, []);
                }
            } else {
                array_push($five_stations, []);
            }
        }

        return view('hospital.edit', [
            'hospital' => $hospital,
            'prefectures' => $prefectures,
            'district_codes' => $district_codes,
            'medical_examination_systems' => $medical_examination_systems,
            'five_stations' => $five_stations,
            'rails' => $rails,
            'medical_treatment_times' => $medical_treatment_times,
        ]);
    }

    /**
     * 都道府県にひもづく、線路を取得
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function findRails(Request $request)
    {
        $response = array();
        $response["status"] = \Illuminate\Http\Response::HTTP_OK;
        $response["data"] = Prefecture::find($request->prefecture_id)->rails()->get();
        return response()->json($response);
    }

    /**
     * 線路にひもづく、駅を取得
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function findStations(Request $request)
    {
        $response = array();
        $response["status"] = \Illuminate\Http\Response::HTTP_OK;
        $response["data"] = Rail::find($request->rail_id)->stations()->get();
        return response()->json($response);
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

            $hospital = Hospital::findOrFail($hospital->id);
            $hospital->fill($request->all());
            $hospital->touch();
            $hospital->save();

            if (!empty(request()->medical_treatment_time)) {
                foreach (request()->medical_treatment_time as $mtt) {
                    if (isset($mtt['id']) && !empty($mtt['id'])) {
                        $medical_treatment_times = MedicalTreatmentTime::findOrFail($mtt['id']);
                        $medical_treatment_times->update(MedicalTreatmentTime::getDefaultFieldValues($mtt));
                    } else {
                        $mtt = array_merge($mtt, ['hospital_id' => $hospital->id]);
                        MedicalTreatmentTime::create($mtt);
                    }
                }
            }
            DB::commit();
            return redirect()->route('hospital.edit', ['id' => $hospital->id])->with('success', trans('messages.updated', ['name' => trans('messages.basic_information')]));
        } catch (Exception $e) {
            DB::rollback();
            $request->session()->flash('error', trans('messages.update_error'));
            return redirect()->back()->withInput();
        } catch (StaleModelLockingException $e) {
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
            $query->where('status', HospitalEnums::PUBLIC);
        }

        $hospitals = $query->orderBy('created_at', 'desc')->paginate(10)->appends(request()->query());
        $hospitals = $query->orderBy('created_at', 'desc')->paginate(10)->appends(request()->query());

        return redirect()->route('reservation.index');
    }
}
