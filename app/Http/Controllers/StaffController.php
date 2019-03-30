<?php

namespace App\Http\Controllers;

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
    public function index () 
    {
        return view('staff.index', ['staffs' => Staff::all()]);
    }
}
