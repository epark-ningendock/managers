<?php

namespace App\Http\Controllers\Auth;

use App\HospitalStaff;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginFormRequest;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\SessionGuard;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use App\Hospital;
use App\Enums\Authority;
use App\Enums\StaffStatus;
use App\Enums\Permission;

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
    protected $staff_redirectTo = '/hospital'; // スタッフ
    protected $contract_staff_redirectTo = '/contract'; // 契約管理者
    protected $hospital_staff_role = 'hospital_staffs'; // 医療機関スタッフ
    protected $hospital_staff_redirectTo = '/hospital-staff';
    protected $staff_first_login_redirectTo = '/staff/edit-password-personal';
    protected $hospital_staff_first_login_redirectTo = '/hospital-staff/edit-password';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout()
    {
        session()->flush();
        Auth::logout();
        return redirect('/login');
    }

    public function getLogin(Request $req)
    {
        return view('auth.login');
    }

    public function postLogin(LoginFormRequest $req)
    {
        $data = $req->all();

        $is_staff = self::is_staff_login($data['login_id'], $data['password']);
        if ($is_staff) {
            // スタッフの権限が契約管理者だった場合、契約管理に遷移する
            if (Auth::user()->authority->value === Authority::ContractStaff) {
                if (!Auth::user()->first_login_at) {
                    return redirect($this->staff_first_login_redirectTo);
                }
                return redirect($this->contract_staff_redirectTo);
            } else {
                // staff_auths権限によって遷移先を変える
                if (Auth::user()->staff_auth->is_hospital === Permission::None) {
                    if (Auth::user()->staff_auth->is_staff !== Permission::None) {
                        if (!Auth::user()->first_login_at) {
                            return redirect($this->staff_first_login_redirectTo);
                        }
                        return redirect('/staff');
                    } elseif (Auth::user()->staff_auth->is_cource_classification !== Permission::None) {
                        if (!Auth::user()->first_login_at) {
                            return redirect($this->staff_first_login_redirectTo);
                        }
                        return redirect('/classification');
                    } elseif (Auth::user()->staff_auth->is_invoice !== Permission::None) {
                        if (!Auth::user()->first_login_at) {
                            return redirect($this->staff_first_login_redirectTo);
                        }
                        return redirect('/staff');
                    } elseif (Auth::user()->staff_auth->is_pre_account !== Permission::None) {
                        if (!Auth::user()->first_login_at) {
                            return redirect($this->staff_first_login_redirectTo);
                        }
                        return redirect('/staff');
                    } else {
                        session()->flush();
                        Auth::logout();
                        return redirect('/login')->with('error', 'スタッフ権限がありません。');
                    }
                }
                if (!Auth::user()->first_login_at) {
                    return redirect($this->staff_first_login_redirectTo);
                }
                return redirect($this->staff_redirectTo);
            }
        }

        $is_hospital_staff = self::is_hospital_staff_login($data['login_id'], $data['password']);
        if ($is_hospital_staff) {
            // 初回ログイン時は、遷移先を変える
            $hospital_staff = HospitalStaff::findOrFail(session()->get('staffs'));
            if (!$hospital_staff->first_login_at) {
                return redirect($this->hospital_staff_first_login_redirectTo);
            }
            return redirect($this->hospital_staff_redirectTo);
        }

        $validator = Validator::make([], []);
        $validator->errors()->add('fail_login', 'ログインIDまたはパスワードが正しくありません。');
        throw new ValidationException($validator);
        return redirect()->back();
    }

    // スタッフ認証処理
    public function is_staff_login($login_id, $password)
    {
        if (Auth::guard($this->staff_role)->attempt(['login_id' => $login_id, 'password' => $password])) {
            $staff = Auth::guard($this->staff_role)->user();
            session()->put('staffs', $staff->id);
            session()->put('login_id', $staff->login_id);
            session()->put('staff_email', $staff->email);
            // 1:Validのユーザーのみログイン
            if ($staff->status->value == StaffStatus::Valid) {
                return true;
            } else {
                $validator = Validator::make([], []);
                $validator->errors()->add('fail_login', 'スタッフが無効または、削除されています。');
                throw new ValidationException($validator);
                return redirect()->back();
            }
        }

        return false;
    }

    // 医療機関スタッフ認証処理
    public function is_hospital_staff_login($login_id, $password)
    {
        if (Auth::guard($this->hospital_staff_role)->attempt(['login_id' => $login_id, 'password' => $password])) {
            $hospital_staff = Auth::guard($this->hospital_staff_role)->user();
            session()->put('staffs', $hospital_staff->id);
            session()->put('login_id', $hospital_staff->login_id);
            session()->put('staff_email', $hospital_staff->email);
            session()->put('hospital_id', $hospital_staff->hospital_id);
            session()->put('hospital_name', Hospital::findOrFail($hospital_staff->hospital_id)->name);
            return true;
        }
        return false;
    }
}
