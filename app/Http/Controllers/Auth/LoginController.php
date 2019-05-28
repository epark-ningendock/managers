<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\SessionGuard;
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
    protected $facility_staff_role = 'facility_staffs';
    protected $redirectTo = '/home';
    // TODO スタッフログイン先確定次第変更
    protected $staff_redirectTo = '/staff';
    // TODO 医療機関スタッフログイン先確定次第変更
    protected $facility_staff_redirectTo = '/hospital-staff';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $req) {
      $data = $req->all();

      // スタッフに該当するかの判定
      $is_staff = self::is_staff_login($data['login_id'], $data['password']);
      if($is_staff) {
        return redirect($this->staff_redirectTo);
      }

      // 医療機関スタッフに該当するかの判定
      $is_facility_staff = self::is_facility_staff_login($data['login_id'], $data['password']);
      if($is_facility_staff) {
        return redirect($this->facility_staff_redirectTo);
      }

      // ログイン情報が該当しない場合
      return redirect($this->redirectTo);
    }

    // スタッフ認証処理
    public function is_staff_login($login_id, $password) {
      if(Auth::guard($this->staff_role)->attempt(['login_id' => $login_id, 'password' => $password])) {
          $staff = Auth::guard($this->staff_role)->user();
          session()->put('staffs', $staff->id);
          session()->put('staff_email', $staff->email);
          return true;
      }
      return false;
    }

    // 医療機関スタッフ認証処理
    public function is_facility_staff_login($login_id, $password) {
      if(Auth::guard($this->facility_staff_role)->attempt(['login_id' => $login_id, 'password' => $password])) {
          $facility_staff = Auth::guard($this->facility_staff_role)->user();
          session()->put('staffs', $facility_staff->id);
          session()->put('staff_email', $facility_staff->email);
          return true;
      }
      return false;
    }
}
