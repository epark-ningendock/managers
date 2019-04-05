<?php

namespace App\Http\Controllers;

use App\Enums\Authority;
use App\Enums\Status;
use App\Http\Requests\StaffFormRequest;
use App\Staff;
use App\StaffAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    /**
     * スタッフ一覧の表示
     *
     * @param
     * @return Response
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
        $query->where('status', $request->input('status', Status::Valid()->value));

        return view('staff.index', [ 'staffs' => $query->paginate(10)])
            ->with($request->input());
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create() {
        return view( 'staff.create' );
    }

    public function store(StaffFormRequest $request) {
        DB::transaction(function() use ($request) {
            $staff_data = $request->only(['name', 'login_id', 'email', 'password', 'status']);
            $staff_data['password'] = bcrypt($staff_data['password']);
            $staff_data['authority'] = Authority::Admin()->value;
            $staff = new Staff($staff_data);
            $staff->save();

            $staff_auth = new StaffAuth($request->only(['is_hospital', 'is_staff', 'is_item_category', 'is_invoice', 'is_pre_account']));
            $staff->staff_auth()->save($staff_auth);

            $request->session()->flash('success', trans('messages.created', ['name' => trans('messages.names.staff')]));
        });
        return redirect('staff');
    }

    public function edit($id) {
        $staff = Staff::findOrFail($id);
        return view( 'staff.edit',  ['staff' => $staff]);
    }

    public function update(StaffFormRequest $request, $id) {
        DB::transaction(function() use ($id, $request){
            $staff = Staff::findOrFail($id);
            $staff->update($request->only(['name', 'login_id', 'email', 'status']));
            $staff->save();

            $staff->staff_auth()->update($request->only(['is_hospital', 'is_staff', 'is_item_category', 'is_invoice', 'is_pre_account']));

            $request->session()->flash('success', trans('messages.updated', ['name' => trans('messages.names.staff')]));
        });
        return redirect('staff');

    }

    public function destroy($id, Request $request)
    {
        $staff = Staff::find($id);
        $staff->status = Status::Deleted()->value;
        $staff->save();
        $request->session()->flash('success', trans('messages.deleted', ['name' => trans('messages.names.staff')]));
        return redirect()->back();
    }
}
