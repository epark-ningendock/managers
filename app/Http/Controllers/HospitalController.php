<?php

namespace App\Http\Controllers;

use App\Enums\HospitalEnums;
use App\Hospital;
use App\HospitalDetail;
use App\HospitalMajorClassification;
use App\HospitalMiddleClassification;
use App\HospitalMinorClassification;
use Illuminate\Http\Request;
use App\Http\Requests\HospitalFormRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Auth\SessionGuard;
use Illuminate\Support\Facades\DB;

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
