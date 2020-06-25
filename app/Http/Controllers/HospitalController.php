<?php

namespace App\Http\Controllers;

use App\DistrictCode;
use App\Enums\HospitalEnums;
use App\Enums\Permission;
use App\Hospital;
use App\HospitalMeta;
use App\Http\Requests\HospitalCreateFormRequest;
use App\Http\Requests\HospitalFormRequest;
use App\MedicalExaminationSystem;
use App\MedicalTreatmentTime;
use App\Prefecture;
use App\Rail;
use App\Station;
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

    /**
     * 医療機関一覧表示
     * @param HospitalFormRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
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

    /**
     * 検索窓テキスト検索
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
            $this->createHospitalMeta($hospital);

            DB::commit();
            return redirect('/hospital/image-information');
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * @param $hospital
     */
    private function createHospitalMeta($hospital) {
        $hospital_meta = HospitalMeta::where('hospital_id', $hospital->id)->first();
        if (!$hospital_meta) {
            $hospital_meta = new HospitalMeta();
            $hospital_meta->hospital_id = $hospital->id;
        }
        $district = DistrictCode::find($hospital->district_code_id);
        $area_station = '';
        if (!empty($hospital->prefecture_id)) {
            $prefecture = Prefecture::find($hospital->prefecture_id);
            if ($prefecture) {
                $area_station = $prefecture->name . ' ';
            }
        }
        if (!empty($hospital->district_code_id)) {
            $district = DistrictCode::find($hospital->district_code_id);
            if ($district) {
                $area_station = $area_station . $district->name . ' ';
            }
        }
        if (!empty($hospital->address1)) {
            $area_station = $area_station . $hospital->address1 . ' ';
        }

        if (!empty($hospital->rail1)) {
            $rail1 = Rail::find($hospital->rail1);
            if ($rail1) {
                $area_station = $area_station . $rail1->name . ' ';
                $hospital_meta->rail1 = $rail1->name;
            }
        }

        if (!empty($hospital->station1)) {
            $station1 = Station::find($hospital->station1);
            if ($station1) {
                $area_station = $area_station . $station1->name . ' ';
                $hospital_meta->station1 = $station1->name;
            }
        }

        if (!empty($hospital->rail2)) {
            $rail2 = Rail::find($hospital->rail2);
            if ($rail2) {
                $area_station = $area_station . $rail2->name . ' ';
                $hospital_meta->rail2 = $rail2->name;
            }
        }

        if (!empty($hospital->station2)) {
            $station2 = Station::find($hospital->station2);
            if ($station2) {
                $area_station = $area_station . $station2->name . ' ';
                $hospital_meta->station2 = $station2->name;
            }
        }

        if (!empty($hospital->rail3)) {
            $rail3 = Rail::find($hospital->rail3);
            if ($rail3) {
                $area_station = $area_station . $rail3->name . ' ';
                $hospital_meta->rail3 = $rail3->name;
            }
        }

        if (!empty($hospital->station3)) {
            $station3 = Station::find($hospital->station3);
            if ($station3) {
                $area_station = $area_station . $station3->name . ' ';
                $hospital_meta->station3 = $station3->name;
            }
        }

        if (!empty($hospital->rail4)) {
            $rail4 = Rail::find($hospital->rail4);
            if ($rail4) {
                $area_station = $area_station . $rail4->name . ' ';
                $hospital_meta->rail4 = $rail4->name;
            }
        }

        if (!empty($hospital->station4)) {
            $station4 = Station::find($hospital->station4);
            if ($station4) {
                $area_station = $area_station . $station4->name . ' ';
                $hospital_meta->station4 = $station4->name;
            }
        }

        if (!empty($hospital->rail5)) {
            $rail5 = Rail::find($hospital->rail5);
            if ($rail5) {
                $area_station = $area_station . $rail5->name . ' ';
                $hospital_meta->rail5 = $rail5->name;
            }
        }

        if (!empty($hospital->station5)) {
            $station5 = Station::find($hospital->station5);
            if ($station5) {
                $area_station = $area_station . $station5->name . ' ';
                $hospital_meta->station5 = $station5->name;
            }
        }

        $hospital_meta->district_code = $district->district_code;
        $hospital_meta->access1 = $hospital->access1;
        $hospital_meta->access2 = $hospital->access2;
        $hospital_meta->access3 = $hospital->access3;
        $hospital_meta->access4 = $hospital->access4;
        $hospital_meta->access5 = $hospital->access5;
        $hospital_meta->hospital_name = $hospital->name . ' ' . $hospital->kana;
        $hospital_meta->area_station = $area_station;

        $hospital_meta->save();
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

    /**
     * 医療機関編集表示
     * @param Hospital $hospital
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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
            $this->createHospitalMeta($hospital);
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

    /**
     * 医療機関操作ボタン押下処理
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
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
