<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\EmailLog;

class SentMailController extends Controller
{
    public function index(Request $request)
		{
			define('ROWPERPAGE', 20);

			$query = EmailLog::query()->orderBy('date', 'DESC');

			if ($request->input('date_from')){
				$query->where('date', '>=', Carbon::parse($request->input('date_from'))->format('Y-m-d H:i'));
			}

			if ($request->input('date_to')){
				$query->where('date', '<=', Carbon::parse($request->input('date_to'))->format('Y-m-d H:i'));
			}

			$strEmailQuery = '';
			if ($request->input('email_account') && $request->input('email_domain')){
				$strEmailQuery = "%{$request->input('email_account')}%@{$request->input('email_domain')}%";
			}elseif ($request->input('email_account') && !$request->input('email_domain')){
				$strEmailQuery = "%{$request->input('email_account')}%@%";
			}elseif (!$request->input('email_account') && $request->input('email_domain')){
				$strEmailQuery = "%@{$request->input('email_domain')}%";
			}

			if ($strEmailQuery){
				$query->where('to', 'LIKE', $strEmailQuery);
			}

			if ($request->input('freeword')){
				$query->where(function($query) use ($request){
					$query->where('subject', 'LIKE', "%{$request->input('freeword')}%")
						->orWhere('body', 'LIKE', "%{$request->input('freeword')}%");
				});
			}

			$data = $query->paginate(ROWPERPAGE);

			foreach($data as $d){
				$d->attachments = ($d->attachments != '');
			}

			return view('sentmail.index')->with([
				'request' => $request,
				'data' => $data
			]);
		}


		public function find($id)
		{
			try{
				$emailLog = EmailLog::findOrFail($id);

				if ($emailLog->attachments != ''){
					$file = preg_split('/^\r\n/m', $emailLog->attachments);
					$headers = preg_split('/\r\n/m', $file[0]);
					$mime = str_replace('Content-Type: ', '', $headers[0]);
					$emailLog->attachments = "data:{$mime}base64,{$file[1]}";
				}

				return response()->json($emailLog);
			}catch(\Exception $e){}
		}
}
