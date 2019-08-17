<?php

namespace App\Http\Controllers;

use App\HospitalEmailSetting;
use App\Http\Requests\HospitalEmailSettingRequest;
use Illuminate\Support\Facades\DB;
use Reshadman\OptimisticLocking\StaleModelLockingException;

class HospitalEmailSettingController extends Controller
{
    public function index()
    {
        return view('hospital_email_setting.index', [
        	'hospital_email_setting' => HospitalEmailSetting::where('hospital_id', session()->get('hospital_id'))->first()
        ]);
    }

    public function update(HospitalEmailSettingRequest $request, $id)
    {

        try {
            DB::beginTransaction();

            if ($request->get('in_hospital_email_reception_flg') == '1'
                && ($request->get('in_hospital_confirmation_email_reception_flg') != '1'
                    && $request->get('in_hospital_change_email_reception_flg') != '1'
                && $request->get('in_hospital_cancellation_email_reception_flg') != '1')) {
                DB::rollback();

                $message = trans('validation.required', ['attribute' => trans('validation.attributes.hospital_reception_email_transmission_setting')]);
                return redirect()->back()->withErrors(['hospital_reception_email_transmission_setting' => $message ]);
            }

            $hospital_email_setting = HospitalEmailSetting::findOrFail($id);
            $inputs = request()->all();
            $hospital_email_setting->update($inputs);
            DB::commit();
            return redirect('hospital-email-setting')->with('success', trans('messages.updated', ['name' => trans('messages.names.hospital_email_setting')]));
        } catch (StaleModelLockingException $e) {
            DB::rollback();
            return redirect()->back()->with('error', trans('messages.model_changed_error'));
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', trans('messages.update_error'));
        }
    }
}
