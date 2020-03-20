<?php

namespace App\Http\Controllers;

use App\EmailTemplate;
use App\Http\Controllers\Controller;
use App\ReceptionEmailSetting;
use Illuminate\Http\Request;
use App\Http\Requests\EmailTemplateFormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Reshadman\OptimisticLocking\StaleModelLockingException;
use function Matrix\trace;

class EmailTemplateController extends Controller
{
    public function index()
    {
        $email_templates = EmailTemplate::where('hospital_id', session()->get('hospital_id'))->orderBy('id', 'DESC')->paginate(20);

        return view('email_template.index', [ 'email_templates' => $email_templates ]);
    }

    public function create()
    {
        if (EmailTemplate::where('hospital_id', session()->get('hospital_id'))->count() >= 20) {
            return redirect('email-template.index')->with('error', trans('messages.email-template-limit-exceed.'));
        }
        return view('email_template.create');
    }

    public function store(EmailTemplateFormRequest $request)
    {
        $email_template = new EmailTemplate($request->all());
        $email_template->hospital_id = session()->get('hospital_id');
        $email_template->save();
        
        return redirect('email-template')->with('success', trans('messages.created', ['name' => trans('messages.names.email_template')]));
    }

    public function edit($id)
    {
        $email_template = EmailTemplate::findOrFail($id);
        $hospital_id = session()->get('hospital_id');
        if (isset($hospital_id) && $hospital_id != $email_template->hospital_id) {
            abort(404);
        }

        return view('email_template.edit', compact('email_template'));
    }

    public function update(EmailTemplateFormRequest $request, $id)
    {
        try{
            DB::beginTransaction();
            $email_template = EmailTemplate::findOrFail($id);
            $inputs = request()->all();
            $email_template->update($inputs);
            DB::commit();
            return redirect('email-template')->with('success', trans('messages.updated', ['name' => trans('messages.names.email_template')]));
        } catch (StaleModelLockingException $e) {
            DB::rollback();
            return redirect()->back()->with('error', trans('messages.model_changed_error'));
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', trans('messages.update_error'));
        }
    }

    public function destroy($id)
    {
        $email_template = EmailTemplate::findOrFail($id);
        $hospital_id = session()->get('hospital_id');
        if (isset($hospital_id) && $hospital_id != $email_template->hospital_id) {
            abort(404);
        }
        $email_template->delete();

        return redirect('email-template')->with('success', trans('messages.deleted', ['name' => trans('messages.names.email_template')]));
    }

    /**
     * email template copy
     * @param $id
     * @return mixed
     */
    public function copy($id)
    {
        if (EmailTemplate::where('hospital_id', session()->get('hospital_id'))->count() >= 20) {
            return redirect('email-template.index')->with('error', trans('messages.email-template-limit-exceed.'));
        }
        $email_template = EmailTemplate::findOrFail($id);
        return $this->create()->with('email_template', $email_template);
    }
}
