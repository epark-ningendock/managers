<?php

namespace App\Http\Controllers;

use App\EmailTemplate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function index()
    {
        return view('email_template.index');
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
        return view('email_template.create');
    }

    public function update(Request $request, $id)
    {
        return view('email_template.index');
    }

    public function destroy(Request $request, $id)
    {
        return view('email_template.index');
    }
}
