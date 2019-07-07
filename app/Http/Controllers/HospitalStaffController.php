<?php

namespace App\Http\Controllers;

use App\HospitalStaff;
use App\Mail\HospitalStaff\RegisteredMail;
use App\Mail\HospitalStaff\PasswordResetMail;
use App\Mail\HospitalStaff\PasswordResetConfirmMail;
use App\Http\Requests\HospitalStaffFormRequest;
use Illuminate\Http\Request;
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
        return view('hospital_staff.index', [ 'hospital_staffs' => HospitalStaff::paginate(20) ]);
    }

    public function create()
    {
        return view('hospital_staff.create');
    }

    public function store(HospitalStaffFormRequest $request)
    {
        $request->request->add([
            'hospital_id' => session()->get('hospital_id'),
        ]);

        $hospital_staff           = new HospitalStaff($request->all());
        $password = str_random(8);
        $hospital_staff->password = bcrypt($password);
        $hospital_staff->save();

        $data = [
            'hospital_staff' => $hospital_staff,
            'password' => $password
        ];
        
        // 登録メールを送信する
        Mail::to($hospital_staff->email)
            ->send(new RegisteredMail($data));
        
        return redirect('hospital-staff')->with('success', trans('messages.created', ['name' => trans('messages.names.hospital_staff')]));
    }

    public function edit($id)
    {
        $hospital_staff = HospitalStaff::findOrFail($id);

        return view('hospital_staff.edit', compact('hospital_staff'));
    }

    public function update(HospitalStaffFormRequest $request, $id)
    {
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

        return redirect('hospital-staff')->with('success', trans('messages.deleted', ['name' => trans('messages.names.hospital_staff')]));
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
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:6'
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

    public function sendPasswordResetsMail(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);
        $hospital_staff = HospitalStaff::where('email', $request->email)->first();
        if ($hospital_staff) {
            $reset_token = str_random(32);
            $hospital_staff->reset_token_digest = bcrypt($reset_token);
            $hospital_staff->reset_sent_at = Carbon::now();
            $hospital_staff->save();
            $data = array(
                'hospital_staff'  => $hospital_staff,
                'reset_token'   => $reset_token
            );
            Mail::to($request->email)
                ->send(new PasswordResetMail($data));
            return redirect('/login')->with('success', trans('messages.sent', ['mail' => trans('messages.mails.reset_passoword')]));
        } else {
            $validator = Validator::make([], []);
            $validator->errors()->add('email', 'メールアドレスが存在しません。');
            throw new ValidationException($validator);
            return redirect()->back();
        }
    }

    public function showResetPassword($reset_token, $email)
    {
        $hospital_staff = HospitalStaff::where('email', $email)->first();
        $expired_date = new Carbon($hospital_staff->reset_sent_at);
        if (!($expired_date->addHour(3)->gt(Carbon::now()))) {
            return redirect('/login')->with('error', trans('messages.token_expired'));
        } elseif (!$hospital_staff) {
            return redirect('/login')->with('error', trans('messages.hospital_staff_does_not_exist'));
        } elseif (!(Hash::check($reset_token, $hospital_staff->reset_token_digest))) {
            return redirect('/login')->with('error', trans('messages.incorrect_token'));
        } else {
            return view('hospital_staff.reset-password', ['hospital_staff_id' => $hospital_staff->id]);
        }
    }

    public function resetPassword($hospital_staff_id, Request $request)
    {
        $this->validate($request, [
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:6'
        ]);

        $hospital_staff = HospitalStaff::findOrFail($hospital_staff_id);
        $hospital_staff->password = bcrypt($request->password);
        $hospital_staff->save();
        Mail::to($hospital_staff->email)
            ->send(new PasswordResetConfirmMail());
        return redirect('/login')->with('success', trans('messages.hospital_staff_update_passoword'));
    }
}
