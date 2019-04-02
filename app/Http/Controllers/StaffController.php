<?php

namespace App\Http\Controllers;

use App\Helpers\DBCommonColumns;
use App\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    use DBCommonColumns;
    /**
     * スタッフ一覧の表示
     *
     * @param  
     * @return Response
     */
    public function index () 
    {
        return view('staff.index', ['staffs' => Staff::paginate(20)]);
    }
}
