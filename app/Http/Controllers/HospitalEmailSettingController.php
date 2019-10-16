<?php

namespace App\Http\Controllers;

use App\HospitalStaff;
use App\HospitalEmailSetting;
use App\Http\Requests\HospitalEmailSettingRequest;
use Illuminate\Support\Facades\DB;
use Reshadman\OptimisticLocking\StaleModelLockingException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\HospitalEmailSetting\HospitalEmailSettingOperationMail;

class HospitalEmailSettingController extends Controller
{   
    public function index()
    {
        self::is_staff();

        return view('hospital_email_setting.index', [
            'hospital_email_setting' => HospitalEmailSetting::firstOrCreate(['hospital_id' => session()->get('hospital_id')])
        ]);
    }

    public function update(HospitalEmailSettingRequest $request, $id)
    {
        self::is_staff();
        
        try {
            DB::beginTransaction();
            
            $hospital_email_setting = HospitalEmailSetting::findOrFail($id);
            $inputs = request()->all();

            if ($request->get('billing_email_flg') == '0') {
                $inputs['billing_email1'] = null;
                $inputs['billing_email2'] = null;
                $inputs['billing_email3'] = null;
                $inputs['billing_fax_number'] = null;
                $hospital_email_setting->update($inputs);
            } else {
                $hospital_email_setting->update($inputs);
            }
            
            DB::commit();
            $data = [
                'hospital_email_setting' => $hospital_email_setting,
                'staff_name' => Auth::user()->name,
                'processing' => '更新'
                ];
            Mail::to(env('DOCK_EMAIL_ADDRESS'))->send(new HospitalEmailSettingOperationMail($data));

            return redirect('hospital-email-setting')->with('success', trans('messages.updated', ['name' => trans('messages.names.hospital_email_setting')]));
        } catch (StaleModelLockingException $e) {
            DB::rollback();
            return redirect()->back()->with('error', trans('messages.model_changed_error'));
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', trans('messages.update_error'));
        }
    }

    /**
     * EPARKスタッフ以外だった場合、医療機関スタッフ一覧に返す
     */
    public function is_staff() {
        if(Auth::user()->getTable() != "staffs") {
            $hospital_staffs = HospitalStaff::where('hospital_id', session()->get('hospital_id'));
            return view('hospital_staff.index', [ 'hospital_staffs' => $hospital_staffs->paginate(10)]);
        }
    }
}
