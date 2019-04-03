<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Helpers\DBCommonColumns;
use App\Staff;
use Illuminate\Http\Request;

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

    public function destroy($id)
    {
        $staff = Staff::find($id);
        $staff->status = Status::Deleted()->value;
        $staff->save();
        return redirect()->back();
    }
}
