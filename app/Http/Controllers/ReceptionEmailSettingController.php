<?php

namespace App\Http\Controllers;

use App\ReceptionEmailSetting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ReceptionEmailSettingRequest;

class ReceptionEmailSettingController extends Controller
{
    public function index()
    {
        return view('reception_email_setting.index', [ 'reception_email_setting' => ReceptionEmailSetting::findOrFail(1) ]);
    }

    public function create()
    {
    }

    public function store()
    {
    }

    public function edit()
    {
    }

    public function update()
    {
    }

    public function destroy()
    {
    }
}
