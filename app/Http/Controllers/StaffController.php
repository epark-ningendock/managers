<?php

namespace App\Http\Controllers;

use App\Enums\Authority;
use App\Enums\StaffStatus;
use App\Http\Requests\StaffFormRequest;
use App\Http\Requests\StaffSearchFormRequest;
use App\Staff;
use App\HospitalStaff;
use App\StaffAuth;
use App\Department;
use App\Mail\Staff\RegisteredMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Exceptions\ExclusiveLockException;
use Illuminate\Support\Facades\Auth;
use App\Enums\Permission;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\In;
use Carbon\Carbon;

class StaffController extends Controller
{
    public function __construct(Request $request)
    {
        request()->session()->forget('hospital_id');
        $this->middleware('permission.staff.edit')->except(['index', 'editPersonalPassword', 'updatePersonalPassword']);
    }

    /**
     * スタッフ一覧
     * @param StaffSearchFormRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     */
    public function index(StaffSearchFormRequest $request)
    {
        if (Auth::user()->staff_auth->is_staff === Permission::NONE) {
            return view('staff.edit-password-personal');
        }

        $query = Staff::query();
        if ($request->input('name', '') != '') {
            $name = strtolower($request->input('name'));
            $query->whereRaw("UPPER(name) LIKE '%$name%'");
        }
        if ($request->input('login_id', '') != '') {
            $loginId = strtolower($request->input('login_id'));
            $query->whereRaw("UPPER(login_id) LIKE '%$loginId%'");
        }
        $query->where('status', $request->input('status', StaffStatus::VALID))->with(['staff_auth']);

        return view('staff.index', ['staffs' => $query->paginate(10)])
            ->with($request->input());
    }

    /**
     * スタッフ作成
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $departments = Department::all();
        return view('staff.create', ['departments' => $departments ]);
    }

    /**
     * スタッフ登録
     * @param StaffFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws ValidationException
     */
    public function store(StaffFormRequest $request)
    {
        if (intval($request->authority) === Authority::CONTRACT_STAFF) {
            $staff_auths = [
                'is_hospital' => 0,
                'is_staff' => 0,
                'is_cource_classification' => 0,
                'is_invoice' => 0,
                'is_pre_account' => 0,
                'is_contract' => 7
            ];
        } else {
            $this->validate($request, [
                'is_hospital' => ['required', Rule::in([0, 1, 3])],
                'is_staff' => ['required', Rule::in([0, 1, 3])],
                'is_cource_classification' => ['required', Rule::in([0, 1, 3, 7])],
                'is_invoice' => ['required', Rule::in([0, 1, 3, 7])],
                'is_pre_account' => ['required', Rule::in([0, 1, 3, 7])]
            ]);
            
            $staff_auths = [
                'is_hospital' => $request->is_hospital,
                'is_staff' => $request->is_staff,
                'is_cource_classification' => $request->is_cource_classification,
                'is_invoice' => $request->is_invoice,
                'is_pre_account' => $request->is_pre_account,
                'is_contract' => 0
            ];
        }

        $this->staffLoginIdValidation($request->login_id);
//        $this->staffEmailValidation($request->email);

        try {
            DB::beginTransaction();
            $staff_data = $request->only([
                'name',
                'login_id',
                'email',
                'password',
                'password_confirmation',
                'authority',
                'status',
                'department_id'
            ]);
            
            $staff = new Staff($staff_data);
            $staff->password = bcrypt($staff_data['password']);
            $staff->save();

            $staff_auth = new StaffAuth($staff_auths);
            $staff->staff_auth()->save($staff_auth);
            
            $data = [
                'staff' => $staff,
                'password' => $staff_data['password']
            ];
            
            Mail::to($staff->email)
                ->send(new RegisteredMail($data));

            $request->session()->flash('success', trans('messages.created', ['name' => trans('messages.names.staff')]));
            DB::commit();
            return redirect('staff');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(trans('messages.staff_create_error'))->withInput();
        }
    }

    /**
     * スタッフ編集
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $staff = Staff::findOrFail($id);
        $departments = Department::all();
        return view('staff.edit', ['staff' => $staff, 'departments' => $departments]);
    }

    /**
     * スタッフ更新
     * @param StaffFormRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws ExclusiveLockException
     * @throws ValidationException
     */
    public function update(StaffFormRequest $request, $id)
    {
        if (intval($request->authority) === Authority::CONTRACT_STAFF) {
            $staff_auths = [
                'is_hospital' => 0,
                'is_staff' => 0,
                'is_cource_classification' => 0,
                'is_invoice' => 0,
                'is_pre_account' => 0,
                'is_contract' => 7
            ];
        } else {
            $this->validate($request, [
                'is_hospital' => ['required', Rule::in([0, 1, 3])],
                'is_staff' => ['required', Rule::in([0, 1, 3])],
                'is_cource_classification' => ['required', Rule::in([0, 1, 3, 7])],
                'is_invoice' => ['required', Rule::in([0, 1, 3, 7])],
                'is_pre_account' => ['required', Rule::in([0, 1, 3, 7])]
            ]);
            
            $staff_auths = [
                'is_hospital' => $request->is_hospital,
                'is_staff' => $request->is_staff,
                'is_cource_classification' => $request->is_cource_classification,
                'is_invoice' => $request->is_invoice,
                'is_pre_account' => $request->is_pre_account,
                'is_contract' => 0
            ];
        }

        $this->staffLoginIdValidation($request->login_id);
//        $this->staffEmailValidation($request->email);
        
        $staff = Staff::findOrFail($id);
        
        try {
            DB::beginTransaction();
            if ($staff->updated_at > $request['updated_at']) {
                throw new ExclusiveLockException;
            }

            $staff->update($request->only(['name', 'login_id', 'email', 'authority', 'status', 'department_id']));
            $staff->save();
            
            $staff->staff_auth()->update($staff_auths);

            $request->session()->flash('success', trans('messages.updated', ['name' => trans('messages.names.staff')]));
            DB::commit();
            return redirect('staff');
        } catch (ExclusiveLockException $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * スタッフ削除
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id, Request $request)
    {
        $staff = Staff::find($id);
        $staff->status = StaffStatus::DELETED;
        $staff->save();
        $request->session()->flash('error', trans('messages.deleted', ['name' => trans('messages.names.staff')]));
        return redirect()->back();
    }

    /**
     * パスワード編集
     * @param $staff_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editPassword($staff_id)
    {
        $staff = Staff::find($staff_id);
        $operator_staff = Staff::where('login_id', session()->get('login_id'))->first();

        // 自分のパスワード変更の場合、遷移先変更
        if ($staff->login_id == $operator_staff->login_id) {
            return view('staff.edit-password-personal', ['staff' => $staff]);
        }
        return view('staff.edit-password', ['staff' => $staff]);
    }

    /**
     * パスワード更新
     * @param $staff_id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws ExclusiveLockException
     */
    public function updatePassword($staff_id, Request $request)
    {
        $this->validate($request, [
            'password' => 'min:8|max:20|required_with:password_confirmation|same:password_confirmation|regex:/^[-_@\.a-zA-Z0-9]+$/',
            'password_confirmation' => 'min:8|max:20|regex:/^[-_@\.a-zA-Z0-9]+$/'
        ]);

        $staff = Staff::findOrFail($staff_id);

        try {
            DB::beginTransaction();

            if ($staff->updated_at > $request['updated_at']) {
                throw new ExclusiveLockException;
            }

            $password = bcrypt($request->password);
            $staff->update(['password' => $password]);

            DB::commit();

            return redirect('staff')->with('success', 'パスワードを更新しました。');
        } catch (ExclusiveLockException $e) {
            DB::rollback();

            throw $e;
        }
    }

    /**
     * パスワード変更
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editPersonalPassword()
    {
        $staff = Staff::findOrFail(Auth::user()->id);

        return view('staff.edit-password-personal', ['staff' => $staff]);
    }

    /**
     * パスワード更新
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws ExclusiveLockException
     * @throws ValidationException
     */
    public function updatePersonalPassword(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'min:8|max:20|required_with:password_confirmation|different:old_password|same:password_confirmation|regex:/^[-_@\.a-zA-Z0-9]+$/',
            'password_confirmation' => 'min:8|max:20|regex:/^[-_@\.a-zA-Z0-9]+$/'
        ]);

        $staff = Staff::findOrFail(Auth::user()->id);

        if (Hash::check($request->old_password, $staff->password)) {
            try {
                DB::beginTransaction();
                if ($staff->updated_at > $request['updated_at']) {
                    throw new ExclusiveLockException;
                }
                
                $password = bcrypt($request->password);

                if (!$staff->first_login_at) {
                    $staff->update([
                        'password' => $password,
                        'first_login_at' => Carbon::now()
                    ]);
                } else {
                    $staff->update(['password' => $password]);
                }

                app('App\Http\Controllers\Auth\LoginController')->is_staff_login($staff->login_id, $request->password);

                DB::commit();

                return redirect('staff')->with('success', 'パスワードを更新しました。');
            } catch (ExclusiveLockException $e) {
                DB::rollback();
                throw $e;
            }
        } else {
            $validator = Validator::make([], []);
            $validator->errors()->add('old_password', '現在のパスワードが正しくありません。');
            throw new ValidationException($validator);
            return redirect()->back();
        }
    }

//    public function staffEmailValidation($email)
//    {
//        $hospital_staff = HospitalStaff::where('email', $email)->first();
//
//        if ($hospital_staff) {
//            $validator = Validator::make([], []);
//            $validator->errors()->add('email', '指定のメールアドレスは既に使用されています。');
//            throw new ValidationException($validator);
//            return redirect()->back();
//        }
//    }

    /**
     * ログインバリデーション
     * @param $login_id
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function staffLoginIdValidation($login_id)
    {
        $hospital_staff = HospitalStaff::where('login_id', $login_id)->first();

        if ($hospital_staff) {
            $validator = Validator::make([], []);
            $validator->errors()->add('login_id', '指定のログインIDは既に使用されています。');
            throw new ValidationException($validator);
            return redirect()->back();
        }
    }
}
