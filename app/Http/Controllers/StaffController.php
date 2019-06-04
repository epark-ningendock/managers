<?php

namespace App\Http\Controllers;

use App\Enums\Authority;
use App\Enums\StaffStatus;
use App\Http\Requests\StaffFormRequest;
use App\Staff;
use App\StaffAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class StaffController extends Controller
{
    public function __construct()
    {
//        $this->authorizeResource(Staff::class);
    }

    /**
     * スタッフ一覧の表示
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Staff::query();
        if ($request->input('name', '') != '') {
            $name = strtolower($request->input('name'));
            $query->whereRaw("UPPER(name) LIKE '%$name%'");
        }
        if ($request->input('login_id', '') != '') {
            $query->where('login_id', $request->input('login_id'));
        }
        $query->where('status', $request->input('status', StaffStatus::Valid));

        return view('staff.index', ['staffs' => $query->paginate(20)])
            ->with($request->input());
    }

    /**
     * Display staff form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('staff.create');
    }

    /**
     * Create staff
     * @param StaffFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(StaffFormRequest $request)
    {
        try {
            DB::beginTransaction();
            $staff_data = $request->only(['name', 'login_id', 'email', 'password', 'status']);
            $staff_data['password'] = bcrypt($staff_data['password']);
            $staff_data['authority'] = Authority::Admin;
            $staff = new Staff($staff_data);
            $staff->save();

            $staff_auth = new StaffAuth($request->only(['is_hospital', 'is_staff', 'is_item_category', 'is_invoice', 'is_pre_account']));
            $staff->staff_auth()->save($staff_auth);

            $request->session()->flash('success', trans('messages.created', ['name' => trans('messages.names.staff')]));
            DB::commit();
            return redirect('staff');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(trans('messages.staff_create_error'))->withInput();
        }
    }

    /**
     * Display staff edit form to edit
     * @param $id Staff ID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $staff = Staff::findOrFail($id);
        return view('staff.edit', ['staff' => $staff]);
    }

    /**
     * Update staff
     * @param StaffFormRequest $request
     * @param $id Staff ID
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(StaffFormRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $staff = Staff::findOrFail($id);
            $staff->update($request->only(['name', 'login_id', 'email', 'status']));
            $staff->save();

            $staff->staff_auth()->update($request->only(['is_hospital', 'is_staff', 'is_item_category', 'is_invoice', 'is_pre_account']));

            $request->session()->flash('success', trans('messages.updated', ['name' => trans('messages.names.staff')]));
            DB::commit();
            return redirect('staff');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(trans('messages.staff_create_error'))->withInput();
        }
    }

    /**
     * Update Staff status to Deleted
     * @param $id Staff ID
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id, Request $request)
    {
        $staff = Staff::find($id);
        $staff->status = StaffStatus::Deleted;
        $staff->save();
        $request->session()->flash('success', trans('messages.deleted', ['name' => trans('messages.names.staff')]));
        return redirect()->back();
    }

    public function editPassword($staff_id)
    {
        return view('staff.edit-password', ['staff_id' => $staff_id]);
    }

    public function updatePassword($staff_id, Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:6'
        ]);

        $staff = Staff::findOrFail($staff_id);

        if (Hash::check($request->old_password, $staff->password)) {
            $staff->password = bcrypt($request->password);
            $staff->save();
            return redirect('staff')->with('success', trans('messages.updated', ['name' => trans('messages.names.password')]));
        } else {
            $validator = Validator::make([], []);
            $validator->errors()->add('old_password', '現在のパスワードが正しくありません');
            throw new ValidationException($validator);
            return redirect()->back();
        }
    }
}
