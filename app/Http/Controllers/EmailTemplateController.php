<?php

namespace App\Http\Controllers;

use App\EmailTemplate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function index()
    {
        return view('email_template.index', [ 'email_templates' => EmailTemplate::paginate(20) ]);
    }

    public function create()
    {
        return view('email_template.create');
    }

    public function store(Request $request, $id)
    {
        return view('email_template.index');
    }

    public function edit($id)
    {
        return view('email_template.edit');
    }

    public function update(Request $request, $id)
    {
        return view('email_template.index');
    }

    public function destroy($id)
    {
        $email_template = EmailTemplate::findOrFail($id);
        $email_template->delete();

        return redirect('email-template')->with('success', trans('messages.deleted', ['name' => trans('messages.names.email_template')]));
    }
}
