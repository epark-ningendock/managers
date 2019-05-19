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
    protected $redirectTo = '/home';
    protected $facility_staff_redirectTo = '/hospital-staff';
    protected $staff_redirectTo = '/staff';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // リクエストパラメータからログインしてきたユーザーの認証をかける
    public function login(Request $req) {
      $data = $req->all();

      // 医療機関スタッフの場合
      $is_facility_staff = self::is_facility_staff_login($data['login_id'], $data['password']);
      // 医療機関スタッフ→ 画面遷移
      if($is_facility_staff) {
        return redirect($this->facility_staff_redirectTo);
      }

      // スタッフの場合
      $is_staff = self::is_staff_login($data['login_id'], $data['password']);
      if($is_staff) {
        return redirect($this->staff_redirectTo);
      }

      // ログイン情報が該当しない場合
      return redirect($this->redirectTo);
    }

    public function is_facility_staff_login($login_id, $password) {
      $role = 'facility_staffs';
      if(Auth::guard($role)->attempt(['login_id' => $login_id, 'password' => $password])) {
          $facility_staff = Auth::guard($role)->user();
          session()->put('facility_staff', $facility_staff->id);
          session()->put('user_name', $facility_staff->name);
          session()->put('user_role', $role);
          return true;
      }
      return false;
    }

    public function is_staff_login($login_id, $password) {
      $role = 'staffs';
      if(Auth::guard($role)->attempt(['login_id' => $login_id, 'password' => $password])) {
          $staff = Auth::guard($role)->user();
          session()->put('facility_staff', $staff->id);
          session()->put('user_name', $staff->name);
          session()->put('user_role', $role);
          return true;
      }
      return false;
    }


}
