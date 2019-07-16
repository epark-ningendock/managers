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
use Illuminate\Support\Facades\Auth;
use App\Enums\Permission;

class StaffController extends Controller
{
    public function __construct(Request $request)
    {
        request()->session()->forget('hospital_id');
        $this->middleware('permission.staff.edit')->except(['index', 'editPersonalPassword', 'updatePersonalPassword']);
    }

    public function index(StaffSearchFormRequest $request)
    {
        if (Auth::user()->staff_auth->is_staff === Permission::None) {
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
        $query->where('status', $request->input('status', StaffStatus::Valid))->with(['staff_auth']);

        return view('staff.index', ['staffs' => $query->paginate(20)])
            ->with($request->input());
    }

    public function create()
    {
        $departments = Department::all();
        return view('staff.create', ['departments' => $departments ]);
    }

    public function store(StaffFormRequest $request)
    {
        $this->staffLoginIdValidation($request->login_id);
        $this->staffEmailValidation($request->email);

        try {
            DB::beginTransaction();
            $staff_data = $request->only([
                'name',
                'login_id',
                'email',
                'authority',
                'status',
                'department_id'
            ]);
            
            $staff = new Staff($staff_data);
            $password = str_random(8);
            $staff->password = bcrypt($password);
            $staff->save();

            $staff_auth = new StaffAuth($request->only(['is_hospital', 'is_staff', 'is_cource_classification', 'is_invoice', 'is_pre_account', 'is_contract']));
            $staff->staff_auth()->save($staff_auth);
            
            $data = [
                'staff' => $staff,
                'password' => $password
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

    public function edit($id)
    {
        $staff = Staff::findOrFail($id);
        $departments = Department::all();
        return view('staff.edit', ['staff' => $staff, 'departments' => $departments]);
    }

    public function update(StaffFormRequest $request, $id)
    {
        $this->staffLoginIdValidation($request->login_id);
        $this->staffEmailValidation($request->email);

        try {
            DB::beginTransaction();
            $staff = Staff::findOrFail($id);
            $staff->update($request->only(['name', 'login_id', 'email', 'authority', 'status', 'department_id']));
            $staff->save();

            $staff->staff_auth()->update($request->only(['is_hospital', 'is_staff', 'is_cource_classification', 'is_invoice', 'is_pre_account', 'is_contract']));

            $request->session()->flash('success', trans('messages.updated', ['name' => trans('messages.names.staff')]));
            DB::commit();
            return redirect('staff');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(trans('messages.staff_create_error'))->withInput();
        }
    }

    public function destroy($id, Request $request)
    {
        $staff = Staff::find($id);
        $staff->status = StaffStatus::Deleted;
        $staff->save();
        $request->session()->flash('error', trans('messages.deleted', ['name' => trans('messages.names.staff')]));
        return redirect()->back();
    }

    public function editPassword($staff_id)
    {
        $staff = Staff::find($staff_id);
        $operator_staff = Staff::where('login_id', session()->get('login_id'))->first();
        // 自分のパスワード変更の場合、遷移先変更
        if ($staff->login_id == $operator_staff->login_id) {
            return view('staff.edit-password-personal');
        }
        return view('staff.edit-password', ['staff' => $staff]);
    }

    public function updatePassword($staff_id, Request $request)
    {
        $this->validate($request, [
            'password' => 'min:8|max:20|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:8|max:20'
        ]);

        $staff = Staff::findOrFail($staff_id);

        $staff->password = bcrypt($request->password);
        $staff->save();
        return redirect('staff')->with('success', 'パスワードを更新しました。');
    }

    public function editPersonalPassword()
    {
        return view('staff.edit-password-personal');
    }

    public function updatePersonalPassword(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'min:8|max:20|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:8|max:20'
        ]);

        $staff = Staff::findOrFail(Auth::user()->id);

        if (Hash::check($request->old_password, $staff->password)) {
            $staff->password = bcrypt($request->password);
            $staff->save();
            app('App\Http\Controllers\Auth\LoginController')->is_staff_login($staff->login_id, $request->password);
            return redirect('staff')->with('success', 'パスワードを更新しました。');
        } else {
            $validator = Validator::make([], []);
            $validator->errors()->add('old_password', '現在のパスワードが正しくありません。');
            throw new ValidationException($validator);
            return redirect()->back();
        }
    }

    public function staffEmailValidation($email)
    {
        $hospital_staff = HospitalStaff::where('email', $email)->first();

        if ($hospital_staff) {
            $validator = Validator::make([], []);
            $validator->errors()->add('email', '指定のメールアドレスは既に使用されています。');
            throw new ValidationException($validator);
            return redirect()->back();
        }
    }

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
