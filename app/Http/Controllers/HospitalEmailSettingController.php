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
    const EPARK_MAIL_ADDRESS = "dock_all@eparkdock.com";
    
    public function index()
    {
        self::is_staff();

        return view('hospital_email_setting.index', [
            'hospital_email_setting' => HospitalEmailSetting::where('hospital_id', session()->get('hospital_id'))->first()
        ]);
    }

    public function update(HospitalEmailSettingRequest $request, $id)
    {
        self::is_staff();
        
        try {
            DB::beginTransaction();

            $messages = [];

            // 受信希望者・院内受付メール送信設定
            if ($request->get('in_hospital_email_reception_flg') == '1'
                && ($request->get('in_hospital_confirmation_email_reception_flg') != '1'
                && $request->get('in_hospital_change_email_reception_flg') != '1'
                && $request->get('in_hospital_cancellation_email_reception_flg') != '1')) {
                $messages += array('hospital_reception_email_transmission_setting' => '院内受付メール送信設定を希望する場合は、1つ以上指定してください。');
            }

            // 受付メール受信アドレス設定
            if ($request->get('email_reception_flg') == '1'
                && ($request->get('in_hospital_reception_email_flg') != '1'
                && $request->get('web_reception_email_flg') != '1')) {
                $messages += array('reception_email_reception_address_setting' => '受付メール受信アドレス設定を受け取る場合は、1つ以上指定してください。');
            }
            
            // 受付メール受信アドレス設定
                if (($request->get('in_hospital_email_reception_flg') == '1'
                || $request->get('email_reception_flg') == '1')
                && ($request->get('reception_email1') == ''
                && $request->get('reception_email2') == ''
                && $request->get('reception_email3') == ''
                && $request->get('reception_email4') == ''
                && $request->get('reception_email5') == '')) {
                $messages += array('reception_email_group' => '受信メールアドレスを1つ以上入力してください。');
            }

            if (!empty($messages)) {
                DB::rollback();
                return redirect()->back()->withErrors($messages);
            }

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
            Mail::to(self::EPARK_MAIL_ADDRESS)->send(new HospitalEmailSettingOperationMail($data));

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
