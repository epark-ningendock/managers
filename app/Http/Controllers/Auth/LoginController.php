<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginFormRequest;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\SessionGuard;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $staff_role = 'staffs';
    protected $hospital_staff_role = 'hospital_staffs';
    protected $staff_redirectTo = '/staff';
    protected $hospital_staff_redirectTo = '/hospital-staff';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function getLogin(Request $req)
    {
        return view('auth.login');
    }

    public function postLogin(LoginFormRequest $req)
    {
        $data = $req->all();

        // スタッフに該当するかの判定
        $is_staff = self::is_staff_login($data['login_id'], $data['password']);
        if ($is_staff) {
            return redirect($this->staff_redirectTo);
        }

        // 医療機関スタッフに該当するかの判定
        $is_hospital_staff = self::is_hospital_staff_login($data['login_id'], $data['password']);
        if ($is_hospital_staff) {
            return redirect($this->hospital_staff_redirectTo);
        }

        // 該当ユーザーが存在しない場合
        $validator = Validator::make([], []);
        $validator->errors()->add('fail_login', 'IDまたはpasswordが正しくありません');
        throw new ValidationException($validator);
        return redirect()->back();
    }

    // スタッフ認証処理
    public function is_staff_login($login_id, $password)
    {
        if (Auth::guard($this->staff_role)->attempt(['login_id' => $login_id, 'password' => $password])) {
            $staff = Auth::guard($this->staff_role)->user();
            session()->put('staffs', $staff->id);
            session()->put('staff_email', $staff->email);
            return true;
        }
        return false;
    }

    // 医療機関スタッフ認証処理
    public function is_hospital_staff_login($login_id, $password)
    {
        if (Auth::guard($this->hospital_staff_role)->attempt(['login_id' => $login_id, 'password' => $password])) {
            $hospital_staff = Auth::guard($this->hospital_staff_role)->user();
            session()->put('staffs', $hospital_staff->id);
            session()->put('staff_email', $hospital_staff->email);
            session()->put('hospital_id', $hospital_staff->hospital_id);
            return true;
        }
        return false;
    }
}
