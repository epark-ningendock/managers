<?php

namespace App\Http\Controllers;

use App\Staff;
use App\HospitalStaff;
use App\Mail\HospitalStaff\RegisteredMail;
use App\Mail\HospitalStaff\PasswordResetMail;
use App\Mail\HospitalStaff\PasswordResetConfirmMail;
use App\Http\Requests\HospitalStaffFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Log;

class HospitalStaffController extends Controller
{
    public function index()
    {
        $hospital_staffs = HospitalStaff::where('hospital_id', session()->get('hospital_id'));
        return view('hospital_staff.index', [ 'hospital_staffs' => $hospital_staffs->paginate(10)]);
    }

    public function create()
    {
        return view('hospital_staff.create');
    }

    public function store(HospitalStaffFormRequest $request)
    {
        $this->hospitalStaffLoginIdValidation($request->login_id);
        $this->hospitalStaffEmailValidation($request->email);

        try {
            DB::beginTransaction();
            $request->request->add([
                'hospital_id' => session()->get('hospital_id'),
            ]);

            $hospital_staff_data = $request->only([
                'login_id',
                'email',
                'hospital_id',
                'password',
                'password_confirmation',
            ]);
            $hospital_staff = new HospitalStaff($hospital_staff_data);
            $hospital_staff->password = bcrypt($hospital_staff_data['password']);
            $hospital_staff->save();

            $data = [
                'hospital_staff' => $hospital_staff,
                'password' => $hospital_staff_data['password']
            ];
            
            // 登録メールを送信する
            Mail::to($hospital_staff->email)
                ->send(new RegisteredMail($data));
            
            DB::commit();
            return redirect('hospital-staff')->with('success', trans('messages.created', ['name' => trans('messages.names.hospital_staff')]));
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(trans('messages.staff_create_error'))->withInput();
        }
    }

    public function edit($id)
    {
        $hospital_staff = HospitalStaff::findOrFail($id);

        return view('hospital_staff.edit', compact('hospital_staff'));
    }

    public function update(HospitalStaffFormRequest $request, $id)
    {
        $this->hospitalStaffLoginIdValidation($request->login_id);
        $this->hospitalStaffEmailValidation($request->email);
        
        $request->request->add([
            'hospital_id' => session()->get('hospital_id'),
        ]);

        $hospital_staff     = HospitalStaff::findOrFail($id);
        $inputs             = request()->all();
        $hospital_staff->update($inputs);

        return redirect('hospital-staff')->with('success', trans('messages.updated', ['name' => trans('messages.names.hospital_staff')]));
    }

    public function destroy($id)
    {
        $hospital_staff = HospitalStaff::findOrFail($id);
        $hospital_staff->delete();

        return redirect('hospital-staff')->with('error', trans('messages.deleted', ['name' => trans('messages.names.hospital_staff')]));
    }

    public function editPassword(Request $request)
    {
        $hospital_staff = HospitalStaff::where('email', $request->session()->get('staff_email'))->first();
        return view('hospital_staff.edit-password', compact('hospital_staff'));
    }

    public function updatePassword($hospital_staff_id, Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'min:8|max:20|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:8|max:20'
        ]);

        $hospital_staff = HospitalStaff::findOrFail($hospital_staff_id);

        if (Hash::check($request->old_password, $hospital_staff->password)) {
            $hospital_staff->password = bcrypt($request->password);
            $hospital_staff->save();
            app('App\Http\Controllers\Auth\LoginController')->is_hospital_staff_login($hospital_staff->login_id, $request->password);
            return redirect('hospital-staff')->with('success', trans('messages.hospital_staff_update_passoword'));
        } else {
            $validator = Validator::make([], []);
            $validator->errors()->add('old_password', '現在のパスワードが正しくありません');
            throw new ValidationException($validator);
            return redirect()->back();
        }
    }

    public function showPasswordResetsMail()
    {
        return view('hospital_staff.send-password-reset-mail');
    }

    // スタッフ、医療機関スタッフ共通のメソッド
    public function sendPasswordResetsMail(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);
        
        $staff = Staff::where('email', $request->email)->first();
        if (!$staff) {
            $staff = HospitalStaff::where('email', $request->email)->first();
        }

        if ($staff) {
            $reset_token = str_random(32);
            $staff->reset_token_digest = bcrypt($reset_token);
            $staff->reset_sent_at = Carbon::now();
            $staff->save();
            $data = array(
                'staff'  => $staff,
                'reset_token'   => $reset_token
            );
            Mail::to($request->email)
                ->send(new PasswordResetMail($data));
            return redirect('/login')->with('success', "メールを送信しました。\nメールに記載されたURLを開き、パスワード初期化手続きを続行してください。");
        } else {
            $validator = Validator::make([], []);
            $validator->errors()->add('email', 'メールアドレスが存在しません。');
            throw new ValidationException($validator);
            return redirect()->back();
        }
    }

    public function showResetPassword($reset_token, $email)
    {
        $staff = Staff::where('email', $email)->first();
        if (!$staff) {
            $staff = HospitalStaff::where('email', $email)->first();
        }
        $expired_date = new Carbon($staff->reset_sent_at);
        if (!($expired_date->addHour(1)->gt(Carbon::now()))) {
            return redirect('/login')->with('error', trans('messages.token_expired'));
        } elseif (!$staff) {
            return redirect('/login')->with('error', 'スタッフが存在しません');
        } elseif (!(Hash::check($reset_token, $staff->reset_token_digest))) {
            return redirect('/login')->with('error', trans('messages.incorrect_token'));
        } else {
            return view('hospital_staff.reset-password', ['email' => $staff->email]);
        }
    }

    public function resetPassword($email, Request $request)
    {
        $this->validate($request, [
            'password' => 'min:8|max:20|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:8|max:20'
        ]);
                
        $staff = Staff::where('email', $email)->first();
        if (!$staff) {
            $staff = HospitalStaff::where('email', $email)->first();
        }

        $staff->password = bcrypt($request->password);
        $staff->save();
        Mail::to($staff->email)
            ->send(new PasswordResetConfirmMail());
        return redirect('/login')->with('success', 'パスワードを更新しました。');
    }

    public function hospitalStaffEmailValidation($email)
    {
        $staff = Staff::where('email', $email)->first();

        if ($staff) {
            $validator = Validator::make([], []);
            $validator->errors()->add('email', '指定のメールアドレスは既に使用されています。');
            throw new ValidationException($validator);
            return redirect()->back();
        }
    }

    public function hospitalStaffLoginIdValidation($login_id)
    {
        $staff = Staff::where('login_id', $login_id)->first();

        if ($staff) {
            $validator = Validator::make([], []);
            $validator->errors()->add('login_id', '指定のログインIDは既に使用されています。');
            throw new ValidationException($validator);
            return redirect()->back();
        }
    }
}
