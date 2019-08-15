<?php

namespace App\Http\Controllers;

use App\ReceptionEmailSetting;
use App\Http\Requests\ReceptionEmailSettingRequest;
use Illuminate\Support\Facades\DB;
use Reshadman\OptimisticLocking\StaleModelLockingException;

class ReceptionEmailSettingController extends Controller
{
    public function index()
    {
        return view('reception_email_setting.index', [
        	'reception_email_setting' => ReceptionEmailSetting::where('hospital_id', session()->get('hospital_id'))->first()
        ]);
    }

    public function update(ReceptionEmailSettingRequest $request, $id)
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

            $reception_email_setting = ReceptionEmailSetting::findOrFail($id);
            $inputs = request()->all();
            $reception_email_setting->update($inputs);
            DB::commit();
            return redirect('reception-email-setting')->with('success', trans('messages.updated', ['name' => trans('messages.names.reception_email_setting')]));
        } catch (StaleModelLockingException $e) {
            DB::rollback();
            return redirect()->back()->with('error', trans('messages.model_changed_error'));
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', trans('messages.update_error'));
        }
    }
}
