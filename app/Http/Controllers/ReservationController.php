<?php

namespace App\Http\Controllers;

use App\Course;
use App\Customer;
use App\Enums\ReservationStatus;
use App\Holiday;
use App\Hospital;
use App\Http\Requests\ReservationCreateFormRequest;
use App\Http\Requests\ReservationFormRequest;
use App\Reservation;
use App\Services\ReservationExportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Auth;
use App\Enums\Permission;


class ReservationController extends Controller
{
    protected $reservation;
    protected $hospital;
    protected $customer;
    protected $course;
    protected $export_file;

    public function __construct(
        Request $request,
        Reservation $reservation,
        Hospital $hospital,
        Customer $customer,
        Course $course,
        ReservationExportService $export_file
    ) {
        request()->session()->forget('hospital_id');
        $this->middleware('permission.invoice.edit')->except(['index', 'reception', 'reception_csv', 'reservation_status']);
        $this->reservation = $reservation;
        $this->hospital = $hospital;
        $this->customer = $customer;
        $this->course = $course;
        $this->export_file = $export_file;
    }

    /**
     * 一覧表示.
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {

        if (isset(Auth::user()->staff_auth->is_invoice) && Auth::user()->staff_auth->is_invoice === Permission::None) {
            return view('staff.edit-password-personal');
        }

        $params = $request->all();

        $query = $this->reservation
            ->byRequest($request)
            ->with(['hospital', 'course', 'customer'])
	        ->where('hospital_id', session('hospital_id'))
            ->orderBy('created_at', 'desc');

        $reservations = $query->paginate(env('PAGINATE_NUMBER'));

        return view('reservation.index', compact('reservations', 'params', 'request'));
    }

    public function operation(Request $request)
    {
        return $this->export_file->operationCsv($request);
    }


    /**
     * build reception list query from request
     * @param Request $request
     * @return Reservation|\Illuminate\Database\Eloquent\Builder
     */
    protected function get_reception_list_query(Request $request)
    {
        $query = Reservation::with(['course', 'customer', 'reservation_options', 'reservation_options.option', 'course.course_questions']);

        if ($request->input('reservation_start_date', '') != '') {
            $query->whereDate('reservation_date', '>=', $request->input('reservation_start_date'));
        }

        if ($request->input('reservation_end_date', '') != '') {
            $query->whereDate('reservation_date', '<=', $request->input('reservation_end_date'));
        }

        if ($request->has('completed_start_date') && $request->input('completed_start_date', '') != '') {
            $query->whereDate('completed_date', '>=', $request->input('completed_start_date'));
        } elseif (!$request->has('completed_start_date')) {
            $query->whereDate('completed_date', '>=', Carbon::now());
        }

        if ($request->has('completed_end_date') && $request->input('completed_end_date', '') != '') {
            $query->whereDate('completed_date', '<=', $request->input('completed_end_date'));
        } elseif (!$request->has('completed_end_date')) {
            $query->whereDate('completed_date', '<=', Carbon::now());
        }

        if ($request->input('customer_name', '') != '') {
            $query->whereHas('Customer', function ($q) use ($request) {
                $q->where(DB::raw("concat(family_name, ' ', first_name)"), 'LIKE', '%' . $request->input('customer_name') . '%');
            });
        }

        if ($request->input('course_id', '') != '') {
            $query->where('course_id', $request->input('course_id'));
        }

        $status_filter = collect();

        if ($request->input('is_pending', '') != '') {
            $status_filter->push($request->input('is_pending'));
        }

        if ($request->input('is_reception_completed', '') != '') {
            $status_filter->push($request->input('is_reception_completed'));
        }

        if ($request->input('is_completed', '') != '') {
            $status_filter->push($request->input('is_completed'));
        }

        if ($request->input('is_cancelled', '') != '') {
            $status_filter->push($request->input('is_cancelled'));
        }

        if ($status_filter->isNotEmpty()) {
            $query->whereIn('reservation_status', $status_filter);
        }
        return $query;
    }

    /**
     * reception list
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function reception(Request $request)
    {
        $this->validate($request, [
            'reservation_start_date' => 'nullable|date',
            'reservation_end_date' => 'nullable|date',
            'completed_start_date' => 'nullable|date',
            'completed_end_date' => 'nullable|date',
            'customer_name' => 'nullable|max:64'
        ]);

        $page_per_record = $request->input('record_per_page', 10);

        $query = $this->get_reception_list_query($request);
        $reservations = $query->paginate($page_per_record)
            ->appends($request->query());
        $courses = Course::all();

        $params = $request->input();

        // for initial default value if it has not been set empty purposely
        if (!$request->has('completed_start_date')) {
            $params['completed_start_date'] = Carbon::now()->format('Y/m/d');
        }
        if (!$request->has('completed_end_date')) {
            $params['completed_end_date'] = Carbon::now()->format('Y/m/d');
        }

        return view('reservation.reception', compact('reservations', 'courses'))
            ->with($params);
    }

    /**
     * CSV download
     * @param array $columnNames
     * @param array $rows
     * @param string $fileName
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    protected function get_csv($columnNames, $rows, $fileName)
    {
        $headers = [
            "Content-Encoding" => "UTF-8",
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=" . $fileName,
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];
        $callback = function () use ($columnNames, $rows) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columnNames);
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function reception_csv(Request $request)
    {
        $this->validate($request, [
            'reservation_start_date' => 'nullable|date',
            'reservation_end_date' => 'nullable|date',
            'completed_start_date' => 'nullable|date',
            'completed_end_date' => 'nullable|date',
        ]);
        $query = $this->get_reception_list_query($request);
        $question_count = 0;

        $reservations = $query->get();

        $option_count = $reservations->max(function ($reservation) {
            return $reservation->reservation_options->count();
        });


        $reservations = $reservations->map(function ($reservation) use (&$question_count, $option_count) {
            $fee = $reservation->course->price + $reservation->adjustment_price;
            $options = collect();

            foreach ($reservation->reservation_options as $reservation_option) {
                $options->push($reservation_option->option->name);
                $options->push($reservation_option->option->price);
                $fee += $reservation_option->option->price;
            }

            // fill to fix maximum option count
            for ($i = $reservation->reservation_options->count(); $i <= $option_count; $i++) {
                $options->merge(['', '']);
            }

            $result = [
                $reservation->completed_date->format('Y/m/d'),
                $reservation->start_time_hour,
                $reservation->reservation_date->format('Y/m/d'),
                $reservation->customer->name,
                $reservation->reservation_status->description,
                $reservation->course->name,
                $reservation->course->tax_included_price,
                $reservation->adjustment_price,
                $fee,
                $reservation->payment_status->description,
                $reservation->settlement_price,
                $reservation->cashpo_used_price,
                $reservation->acceptance_number,
                Reservation::getChannel($reservation->channel),
                $reservation->reservation_memo,
                $reservation->todays_memo
            ];

            $questions = collect();
            $q_count = 0;

            foreach ($reservation->course->course_questions as $course_question) {
                if (empty($course_question->question_title)) {
                    continue;
                }
                $questions->push($course_question->question_title);
                $questions->push($course_question->answer01);
                $questions->push($course_question->answer02);
                $questions->push($course_question->answer03);
                $questions->push($course_question->answer04);
                $questions->push($course_question->answer05);
                $questions->push($course_question->answer06);
                $questions->push($course_question->answer07);
                $questions->push($course_question->answer08);
                $questions->push($course_question->answer09);
                $questions->push($course_question->answer10);
                $q_count++;
            }

            if ($q_count > $question_count) {
                $question_count = $q_count;
            }

            return array_merge($result, $options->toArray(), $questions->toArray());
        });

        $headers = [
            '受診日',
            '受診時間',
            '予約日',
            '受診者名',
            '受付ステータス',
            '検査コース',
            'コース料金',
            '調整額',
            '合計金額',
            '決済ステータス',
            'カード決済額',
            'キャシュポ利用額',
            '受付番号',
            '受付形態',
            '受付・予約メモ',
            '医療機関備考'
        ];

        for ($i = 0; $i < $option_count; $i++) {
            $headers = array_merge($headers, ['オプション', 'オプション金額']);
        }

        for ($i = 0; $i < $question_count; $i++) {
            $headers = array_merge($headers, [
                '受付質問',
                '回答01',
                '回答02',
                '回答03',
                '回答04',
                '回答05',
                '回答06',
                '回答07',
                '回答08',
                '回答09',
                '回答10'
            ]);
        }


        return $this->get_csv($headers, $reservations, 'reception.csv');
    }

    /**
     * Accept reservation
     * @param $id Reservatoin ID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function accept($id)
    {
        try {
            DB::beginTransaction();
            $reservation = Reservation::findOrFail($id);
            if (!$reservation->reservation_status->is(ReservationStatus::Pending)) {
                return redirect()->back()->withErrors(trans('messages.reservation.invalid_reservation_status'))->withInput();
            }
            $reservation->reservation_status = ReservationStatus::ReceptionCompleted;
            $reservation->save();
            Session::flash('success', trans('messages.reservation.accept_success'));
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(trans('messages.reservation.accept_error'))->withInput();
        }
    }

    /**
     * Cancel reservation
     * @param $id Reservatoin ID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cancel($id)
    {
        try {
            DB::beginTransaction();
            $reservation = Reservation::findOrFail($id);
            if (!$reservation->reservation_status->is(ReservationStatus::ReceptionCompleted) &&
                !$reservation->reservation_status->is(ReservationStatus::Pending)) {
                return redirect()->back()->withErrors(trans('messages.reservation.invalid_reservation_status'))->withInput();
            }
            $reservation->reservation_status = ReservationStatus::Cancelled;
            $reservation->save();
            Session::flash('success', trans('messages.reservation.cancel_success'));
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(trans('messages.reservation.cancel_error'))->withInput();
        }
    }

    /**
     * Complete reservation
     * @param $id Reservatoin ID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function complete($id)
    {
        try {
            DB::beginTransaction();
            $reservation = Reservation::findOrFail($id);
            if (!$reservation->reservation_status->is(ReservationStatus::ReceptionCompleted)) {
                return redirect()->back()->withErrors(trans('messages.reservation.invalid_reservation_status'))->withInput();
            }
            $reservation->reservation_status = ReservationStatus::Completed;
            $reservation->save();
            Session::flash('success', trans('messages.reservation.complete_success'));
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(trans('messages.reservation.complete_error'))->withInput();
        }
    }

    /**
     * bulk reservation status update
     * @param ReservationFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function reservation_status(ReservationFormRequest $request)
    {
        try {
            DB::beginTransaction();
            $ids = $request->input('ids');
            $reservation_status = ReservationStatus::getInstance($request->input('reservation_status'));
            $update_query = Reservation::whereIn('id', $ids);
            if ($reservation_status->is(ReservationStatus::ReceptionCompleted)) {
                $update_query->where('reservation_status', ReservationStatus::Pending);
            } elseif ($reservation_status->is(ReservationStatus::Completed)) {
                $update_query->where('reservation_status', ReservationStatus::ReceptionCompleted);
            } elseif ($reservation_status->is(ReservationStatus::Cancelled)) {
                $update_query->where('reservation_status', ReservationStatus::Pending)
                    ->orWhere('reservation_status', ReservationStatus::ReceptionCompleted);
            }
            $update_query->update([ 'reservation_status' => $reservation_status->value ]);
            Session::flash('success', trans('messages.reservation.status_update_success'));
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(trans('messages.reservation.status_update_error'))->withInput();
        }
    }


    /**
     * create form for reservation
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $courses = Course::all();

        return view('reservation.create')->with(['courses' => $courses ]);
    }



    public function store(ReservationCreateFormRequest $request)
    {

    	try {
		    DB::beginTransaction();

		    request()->merge([
			    'hospital_id' => session()->get('hospital_id'),
			    'reservation_status' => ReservationStatus::Pending,
			    'terminal_type' => 1,
			    'is_repeat' => 0,
			    'is_representative' => 0,
			    'timezone_pattern_id' => 3233,
			    'timezone_id' => 3322,
			    'order' => 231,
			    'mail_type' => 0,
			    'payment_status' => 0,
			    'trade_id' => 'mbxrfidstwzvaheonugckljypq',
			    'payment_method' => '現金',
		    ]);

		    $reservation = new Reservation();
		    $reservation = $reservation->create(request()->all());

		    DB::commit();

		    if ( !empty($request->course_options) && isset($request->course_options) ) {
			    $options = [];
			    foreach( $request->course_options as $key => $option ) {
				    $options[] = [
					    'reservation_id' =>$reservation->id,
					    'option_id' => $key,
					    'option_price' => $option

				    ];
			    }

			    if ( !empty($options ) ) {
				    $reservation->reservation_options()->createMany($options);
			    }
		    }


            if ( isset(request()->course_id) && !empty(request()->course_id) ) {

                $course = Course::find($request->course_id);

                if ( isset($course->course_questions) && !empty($course->course_questions) ) {

                    $reservation_option_values = [];

                    foreach( $course->course_questions as $question  ) {

                        $question_id_values = collect(request()->get('questions_'. $question->id));
                        $answer_columns = ['answer01', 'answer02', 'answer03', 'answer04', 'answer05', 'answer06', 'answer07', 'answer08', 'answer09', 'answer10'];
                        foreach( $answer_columns as $answer_column ) {
                            $answer_values[$answer_column] = (  $question_id_values->get($answer_column) ) ? 1 : 0;
                        }

                        $reservation_option_values[] = array_merge([
                            'reservation_id' => $reservation->id,
                            'course_id' => request()->course_id,
                            'course_question_id' => $question->id,
                            'question_title' => $question->question_title,
                            'question_answer01' => $question->answer01,
                            'question_answer02' => $question->answer02,
                            'question_answer03' => $question->answer03,
                            'question_answer04' => $question->answer04,
                            'question_answer05' => $question->answer05,
                            'question_answer06' => $question->answer06,
                            'question_answer07' => $question->answer07,
                            'question_answer08' => $question->answer08,
                            'question_answer09' => $question->answer09,
                            'question_answer10' => $question->answer10,

                        ], $answer_values);

                    }


                    if ( isset($reservation_option_values) && !empty($reservation_option_values) ) { // make it verified it has value
                        $reservation->reservation_answers()->createMany($reservation_option_values);
                    }


                }

            }

		    return redirect('reservation')->with('success', trans('messages.reservation.complete_success'));

	    } catch (\Exception $i) {
		    DB::rollback();
		    return redirect()->back()->with('error', trans('messages.reservation.complete_error'))->withInput();
	    }

    }


	public function edit(Reservation $reservation) {

		$courses = Course::all();
    	return view('reservation.edit', ['reservation' => $reservation, 'courses' => $courses]);

    }
    
}
