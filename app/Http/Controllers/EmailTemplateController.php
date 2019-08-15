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
        return view('email_template.index', [ 'email_templates' => EmailTemplate::paginate(20) ]);
    }

    public function create()
    {
        return view('email_template.create');
    }

    public function store(EmailTemplateFormRequest $request)
    {
        $email_template = new EmailTemplate($request->all());
        # TODO：ログインユーザーの医療機関を紐づける
        $email_template->hospital_id = 1;
        $email_template->save();
        
        return redirect('email-template')->with('success', trans('messages.created', ['name' => trans('messages.names.email_template')]));
    }

    public function edit($id)
    {
        $email_template = EmailTemplate::findOrFail($id);

        return view('email_template.edit', compact('email_template'));
    }

    public function update(EmailTemplateFormRequest $request, $id)
    {

        try {

            $email_template = EmailTemplate::findOrFail($id);
            $inputs = request()->all();
            $email_template->update($inputs);


            if ( $request->billing_email_flg == '1' ) {

                ReceptionEmailSetting::updateOrCreate(['hospital_id' => session('hospital_id')], [
                    'billing_email_flg' => (int) $request->billing_email_flg,
                    'billing_email1' => $request->billing_email1,
                    'billing_email2' => $request->billing_email2,
                    'billing_email3' => $request->billing_email3,
                    'billing_fax_number' => $request->billing_fax_number,
                ]);

            }

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
        $email_template->delete();

        return redirect('email-template')->with('success', trans('messages.deleted', ['name' => trans('messages.names.email_template')]));
    }
}
