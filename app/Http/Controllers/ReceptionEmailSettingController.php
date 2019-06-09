<?php

namespace App\Http\Controllers;

use App\ReceptionEmailSetting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ReceptionEmailSettingRequest;
use Illuminate\Support\Facades\Session;

class ReceptionEmailSettingController extends Controller
{
    public function index()
    {
        // TODO: セッションの医療機関IDに変更する
        // return view('reception_email_setting.index', [ 'reception_email_setting' => ReceptionEmailSetting::where('hospital_id', 1)->first() ]);
        return view('reception_email_setting.index', [ 'reception_email_setting' => ReceptionEmailSetting::findOrFail(1) ]);
    }

    public function update(ReceptionEmailSettingRequest $request, $id)
    {
        $reception_email_setting = ReceptionEmailSetting::findOrFail($id);
        $inputs = request()->all();
        $reception_email_setting->update($inputs);

        return redirect('reception-email-setting')->with('success', trans('messages.updated', ['name' => trans('messages.names.reception_email_setting')]));
    }
}
