<?php

namespace App\Http\Controllers;

use App\CalendarDay;
use App\Course;
use App\Customer;
use App\Enums\IsFreeHpLink;
use App\Enums\Permission;
use App\Enums\ReservationStatus;
use App\Enums\Status;
use App\Holiday;
use App\ContractInformation;
use App\Hospital;
use App\HospitalEmailSetting;
use App\HospitalPlan;
use App\Mail\Reservation\ReservationCheckMail;
use App\Mail\Reservation\ReservationOperationMail;
use App\Http\Requests\ReservationCreateFormRequest;
use App\Http\Requests\ReservationFormRequest;
use App\Http\Requests\ReservationUpdateFormRequest;
use App\Mail\ReservationCancelFaxToMail;
use App\Mail\ReservationCancelMail;
use App\Mail\ReservationChangeFaxToMail;
use App\Mail\ReservationChangeMail;
use App\Mail\ReservationReceptionCancelMail;
use App\Mail\ReservationReceptionCompleteFaxToMail;
use App\Mail\ReservationReceptionCompleteMail;
use App\Mail\ReservationReceptionFaxToMail;
use App\Mail\ReservationReceptionMail;
use App\Prefecture;
use App\Reservation;
use App\ReservationOption;
use App\Services\ReservationExportService;
use App\Services\ReservationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\TaxClass;


class ReservationController extends Controller
{
    protected $reservation;
    protected $hospital;
    protected $customer;
    protected $course;
    protected $export_file;
    private $_reservation_service;

    public function __construct(
        Reservation $reservation,
        Hospital $hospital,
        Customer $customer,
        Course $course,
        ReservationExportService $export_file,
        ReservationService $reservation_service
    )
    {
        $this->middleware('permission.hospital.edit')->except([
            'index',
            'reception',
            'reception_csv',
            'reservation_status',
        ]);
        $this->reservation = $reservation;
        $this->hospital = $hospital;
        $this->customer = $customer;
        $this->course = $course;
        $this->export_file = $export_file;
        $this->_reservation_service = $reservation_service;
    }

    /**
     * reception list
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'reservation_created_start_date' => 'nullable|date',
            'reservation_created_end_date' => 'nullable|date',
            'reservation_start_date' => 'nullable|date',
            'reservation_end_date' => 'nullable|date',
            'customer_name' => 'nullable|max:64',
        ]);

        $page_per_record = $request->input('record_per_page', 10);

        $query = $this->get_reception_list_query($request);
        $reservations = $query->paginate($page_per_record)
            ->appends($request->query());
        $courses = Course::where('hospital_id', session()->get('hospital_id'))->get();

        $params = $request->input();

        // for initial default value if it has not been set empty purposely
        if (!$request->has('reservation_start_date')) {
            $params['reservation_start_date'] = '';
        }
        if (!$request->has('reservation_end_date')) {
            $params['reservation_end_date'] = '';
        }

        return view('reservation.index', compact('reservations', 'courses'))
            ->with($params);
    }

    public function operation(Request $request)
    {
        return $this->export_file->operationCsv($request);
    }

    /**
     * build reception list query from request
     *
     * @param Request $request
     *
     * @return Reservation|\Illuminate\Database\Eloquent\Builder
     */
    protected function get_reception_list_query(Request $request)
    {
        // dd($request->all());
        $query = Reservation::where('hospital_id', session('hospital_id'))->with([
            'course',
            'customer',
            'reservation_options',
            'reservation_options.option',
            'course.course_questions',
        ]);

        if ($request->input('reservation_created_start_date', '') != '') {
            $query->whereDate('created_at', '>=', $request->input('reservation_created_start_date'));
        }

        if ($request->input('reservation_created_end_date', '') != '') {
            $query->whereDate('created_at', '<=', $request->input('reservation_created_end_date'));
        }

        Log::info('受診日（開始）:'. $request->input('reservation_start_date', '') );
        if ($request->input('reservation_start_date', '') != '') {
            $query->whereDate('reservation_date', '>=', $request->input('reservation_start_date'));
        }

        if ($request->input('reservation_end_date', '') != '') {
            $query->whereDate('reservation_date', '<=', $request->input('reservation_end_date'));
        }


        if ($request->input('customer_name', '') != '') {
            $query->whereHas('Customer', function ($q) use ($request) {
                $q->where(DB::raw("concat(family_name, first_name)"), 'LIKE', '%' . $request->input('customer_name') . '%');
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

        $query->orderBy('reservation_date', 'desc');

        return $query;
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
            "Content-type" => "text/csv;charset=UTF-8",
            "Content-Disposition" => "attachment; filename=" . $fileName,
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];
        $callback = function () use ($columnNames, $rows) {
            $file = fopen('php://output', 'w');
            stream_filter_prepend($file,'convert.iconv.utf-8/cp932');
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
            'reservation_created_start_date' => 'nullable|date',
            'reservation_created_end_date' => 'nullable|date',
            'reservation_start_date' => 'nullable|date',
            'reservation_end_date' => 'nullable|date',
        ]);
        $query = $this->get_reception_list_query($request);

        $reservations = $query->get();

        $option_count = $reservations->max(function ($reservation) {
            return $reservation->reservation_options->count();
        });

        $question_count = $reservations->max(function ($reservation) {
            return $reservation->reservation_answers->count();
        });


        $reservations = $reservations->map(function ($reservation) use (&$question_count, $option_count) {
            $fee = $reservation->tax_included_price + $reservation->adjustment_price;
            $options = collect();

            foreach ($reservation->reservation_options as $reservation_option) {
                $options->push($reservation_option->option->name);
                $options->push($reservation_option->option_price);
                $fee += $reservation_option->option_price;
            }

            // fill to fix maximum option count
            for ($i = $reservation->reservation_options->count(); $i < $option_count; $i++) {
                $options = $options->merge(['', '']);
            }

            $result = [
                $reservation->id,
                $reservation->reservation_date ? $reservation->reservation_date->format('Y/m/d') : '',
                $reservation->start_time_hour,
                $reservation->created_at->format('Y/m/d'),
                $reservation->customer->name,
                $reservation->reservation_status ? $reservation->reservation_status->description : '',
                $reservation->course->name,
                $reservation->tax_included_price,
                $reservation->adjustment_price,
                $fee,
                $reservation->payment_status ? $reservation->payment_status->description : '',
                $reservation->settlement_price,
                $reservation->cashpo_used_price,
                $reservation->acceptance_number,
                Reservation::getChannel($reservation->channel),
                $reservation->todays_memo,
                $reservation->internal_memo,
                $reservation->cancellation_reason,
            ];

            $questions = collect();

            foreach ($reservation->reservation_answers as $answer) {
                if (empty($answer->question_title)) {
                    continue;
                }
                $questions->push($answer->question_title);
                $answers = collect();
                for($i = 1; $i <= 10; $i++) {
                    $temp = $answer['answer'.($i != 10 ? '0' : '').$i];
                    if (!is_null($temp) && $temp == '1') {
                        $answers->push($answer['question_answer'.($i != 10 ? '0' : '').$i]);
                    }

                }

                $questions->push($answers->implode(", "));
            }

            // fill to fix maximum question count
            for ($i = $reservation->reservation_answers->count(); $i < $question_count; $i++) {
                $questions = $questions->merge(['', '']);
            }

            return array_merge($result, $questions->toArray(), $options->toArray());
        });

        $headers = [
            '予約ID',
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
            '医療機関備考',
            'キャンセル理由',
        ];

        for ($i = 0; $i < $question_count; $i++) {
            $headers = array_merge($headers, [
                '受付質問',
                '回答'
            ]);
        }

        for ($i = 0; $i < $option_count; $i++) {
            $headers = array_merge($headers, ['オプション', 'オプション金額']);
        }


        return $this->get_csv($headers, $reservations, 'reception.csv');
    }

    /**
     * Accept reservation
     *
     * @param $id Reservatoin ID
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function accept($id)
    {
        try {
            DB::beginTransaction();
            $reservation = Reservation::findOrFail($id);
            if (!$reservation->reservation_status->is(ReservationStatus::PENDING)) {
                return redirect()->back()->withErrors(trans('messages.reservation.invalid_reservation_status'))->withInput();
            }
            $reservation->reservation_status = ReservationStatus::RECEPTION_COMPLETED;
            $reservation->save();

            $this->sendReservationCheckMail(Hospital::find(session('hospital_id')), $reservation, $reservation->customer, '受付ステータス変更');

            Session::flash('success', trans('messages.reservation.accept_success'));
            DB::commit();
            $this->reservation_mail_send($reservation, false);

        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();

            return redirect()->back()->withErrors(trans('messages.reservation.accept_error'))->withInput();
        }

        // 予約履歴api更新
        try {
            if (!empty($reservation->epark_member_id)) {
                $this->_reservation_service->request($reservation);
            }

        } catch (\Exception $e) {
            Log::error('予約履歴api更新に失敗しました。');
        }

        return redirect()->back();
    }

    /**
     * Cancel reservation
     *
     * @param $id Reservatoin ID
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cancel($id)
    {
        try {
            DB::beginTransaction();
            $reservation = Reservation::findOrFail($id);
            if ($reservation->reservation_status->is(ReservationStatus::CANCELLED)) {
                return redirect()->back()->withErrors(trans('messages.reservation.invalid_reservation_status'))->withInput();
            }
            $reservation->reservation_status = ReservationStatus::CANCELLED;
            $reservation->cancel_date = Carbon::now();
            $reservation->cancellation_reason = request()->input('cancellation_reason');
            $reservation->save();
            // カレンダーの予約数を1つ減らす
            $this->_reservation_service->registReservationToCalendar($reservation, -1);

            $this->sendReservationCheckMail(Hospital::find(session('hospital_id')), $reservation, $reservation->customer, '受付ステータス変更');

            Session::flash('success', trans('messages.reservation.cancel_success'));
            DB::commit();

            $this->reservation_mail_send($reservation, false);

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->withErrors(trans('messages.reservation.cancel_error'))->withInput();
        }

        // 予約履歴api更新
        try {
            if (!empty($reservation->epark_member_id)) {
                $this->_reservation_service->request($reservation);
            }

        } catch (\Exception $e) {
            Log::error('予約履歴api更新に失敗しました。');
        }

        return redirect()->back();
    }

    /**
     * Complete reservation
     *
     * @param $id Reservatoin ID
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function complete($id)
    {
        try {
            DB::beginTransaction();
            $reservation = Reservation::findOrFail($id);
            if (!$reservation->reservation_status->is(ReservationStatus::RECEPTION_COMPLETED)) {
                return redirect()->back()->withErrors(trans('messages.reservation.invalid_reservation_status'))->withInput();
            }
            $reservation->reservation_status = ReservationStatus::COMPLETED;
            $reservation->completed_date = Carbon::now();
            $reservation->save();

            $this->sendReservationCheckMail(Hospital::find(session('hospital_id')), $reservation, $reservation->customer, '受付ステータス変更');

            Session::flash('success', trans('messages.reservation.complete_success'));
            DB::commit();

            $this->reservation_mail_send($reservation, false);

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->withErrors(trans('messages.reservation.complete_error'))->withInput();
        }

        // 予約履歴api更新
        try {
            if (!empty($reservation->epark_member_id)) {
                $this->_reservation_service->request($reservation);
            }

        } catch (\Exception $e) {
            Log::error('予約履歴api更新に失敗しました。');
        }
        return redirect()->back();

    }

    /**
     * @param $reservation
     * @param $customer_flg
     */
    private function reservation_mail_send($reservation, $change_flg) {

        $reservation->process_kbn = '1';
        // 確定メール送信（受診者）
        if (!empty($reservation->customer) && !empty($reservation->customer->email)) {
            $to = $reservation->customer->email;
            if ($change_flg) {
                Mail::to($to)->send(new ReservationChangeMail($reservation, true));
            } elseif ($reservation->reservation_status == ReservationStatus::CANCELLED) {
                Mail::to($to)->send(new ReservationReceptionCancelMail($reservation, true));
            } elseif ($reservation->reservation_status == ReservationStatus::RECEPTION_COMPLETED) {
                Mail::to($to)->send(new ReservationReceptionCompleteMail($reservation, true));
            } elseif ($reservation->reservation_status == ReservationStatus::PENDING) {
                Mail::to($to)->send(new ReservationReceptionMail($reservation, true));
            }
        }

        // 確定メール送信（施設）
        $query = HospitalEmailSetting::where('hospital_id', $reservation->hospital_id)
            ->where('in_hospital_email_reception_flg', 1);

        if (!$change_flg && $reservation->reservation_status == ReservationStatus::RECEPTION_COMPLETED) {
            $query->where('in_hospital_confirmation_email_reception_flg', '1');
        }
        if (!$change_flg && $reservation->reservation_status == ReservationStatus::CANCELLED) {
            $query->where('in_hospital_cancellation_email_reception_flg', '1');
        }
        if ($change_flg) {
            $query->where('in_hospital_change_email_reception_flg', '1');
        }
        $hospital_email_setting = $query->first();
        $hospital_mails = [];
        $hospital_fax = [];
        if ($hospital_email_setting) {
            if (strpos($hospital_email_setting->reception_email1, 'fax') === false) {
                $hospital_mails[] = $hospital_email_setting->reception_email1;
            } else {
                $hospital_fax[] = $hospital_email_setting->reception_email1;
            }
            if (strpos($hospital_email_setting->reception_email2, 'fax') === false) {
                $hospital_mails[] = $hospital_email_setting->reception_email2;
            } else {
                $hospital_fax[] = $hospital_email_setting->reception_email2;
            }
            if (strpos($hospital_email_setting->reception_email3, 'fax') === false) {
                $hospital_mails[] = $hospital_email_setting->reception_email3;
            } else {
                $hospital_fax[] = $hospital_email_setting->reception_email3;
            }
            if (strpos($hospital_email_setting->reception_email4, 'fax') === false) {
                $hospital_mails[] = $hospital_email_setting->reception_email4;
            } else {
                $hospital_fax[] = $hospital_email_setting->reception_email4;
            }
            if (strpos($hospital_email_setting->reception_email5, 'fax') === false) {
                $hospital_mails[] = $hospital_email_setting->reception_email5;
            } else {
                $hospital_fax[] = $hospital_email_setting->reception_email5;
            }
        }

        $gyoumu_mail = config('mail.to.gyoumu');
        if ($change_flg) {
            Mail::to($gyoumu_mail)->send(new ReservationChangeMail($reservation, false));
        } elseif ($reservation->reservation_status == ReservationStatus::CANCELLED) {
            Mail::to($gyoumu_mail)->send(new ReservationReceptionCancelMail($reservation, false));
        } elseif ($reservation->reservation_status == ReservationStatus::RECEPTION_COMPLETED) {
            Mail::to($gyoumu_mail)->send(new ReservationReceptionCompleteMail($reservation, false));
        } elseif ($reservation->reservation_status == ReservationStatus::PENDING) {
            Mail::to($gyoumu_mail)->send(new ReservationReceptionMail($reservation, false));
        }

        $tos = [];
        if (!empty($hospital_mails)) {
            foreach ($hospital_mails as $m) {
                if (!empty($m)) {
                    $tos[] = $m;
                }
            }
            // 医療機関へメール送信
            if ($change_flg) {
                Mail::to($tos)->send(new ReservationChangeMail($reservation, false));
            } elseif ($reservation->reservation_status == ReservationStatus::CANCELLED) {
                Mail::to($tos)->send(new ReservationReceptionCancelMail($reservation, false));
            } elseif ($reservation->reservation_status == ReservationStatus::RECEPTION_COMPLETED) {
                Mail::to($tos)->send(new ReservationReceptionCompleteMail($reservation, false));
            } elseif ($reservation->reservation_status == ReservationStatus::PENDING) {
                Mail::to($tos)->send(new ReservationReceptionMail($reservation, false));
            }
        }

        try {
            $fax_tos = [];
            if (!empty($hospital_fax)) {
                foreach ($hospital_fax as $fax_to) {
                    if (!empty($fax_to)) {
                        $fax_tos[] = $fax_to;
                    }
                }
                // 医療機関へFAX送信
                if ($reservation->reservation_status == ReservationStatus::RECEPTION_COMPLETED) {
                    Mail::to($fax_tos)->send(new ReservationReceptionCompleteFaxToMail($reservation));
                }

//                if ($change_flg) {
//                    Mail::to($fax_tos)->send(new ReservationChangeFaxToMail($reservation));
//                } elseif ($reservation->reservation_status == ReservationStatus::CANCELLED) {
//                    Mail::to($fax_tos)->send(new ReservationCancelFaxToMail($reservation));
//                } elseif ($reservation->reservation_status == ReservationStatus::RECEPTION_COMPLETED) {
//                    Mail::to($fax_tos)->send(new ReservationReceptionCompleteFaxToMail($reservation));
//                } elseif ($reservation->reservation_status == ReservationStatus::PENDING) {
//                    Mail::to($fax_tos)->send(new ReservationReceptionFaxToMail($reservation));
//                }
            }
        } catch (\Exception $e) {
            Log::error($e);
        }

    }

    /**
     * bulk reservation status update
     *
     * @param ReservationFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function reservation_status(ReservationFormRequest $request)
    {
        try {
            DB::beginTransaction();
            $ids = $request->input('ids');
            $reservation_status = ReservationStatus::getInstance($request->input('reservation_status'));
            $update_data = ['reservation_status' => $reservation_status->value];
            $update_query = Reservation::whereIn('id', $ids);
            if ($reservation_status->is(ReservationStatus::RECEPTION_COMPLETED)) {
                $update_query->where('reservation_status', ReservationStatus::PENDING);
            } elseif ($reservation_status->is(ReservationStatus::COMPLETED)) {
                $update_data['completed_date'] = Carbon::now();
                $update_query->where('reservation_status', ReservationStatus::RECEPTION_COMPLETED);
            } elseif ($reservation_status->is(ReservationStatus::CANCELLED)) {
                $update_data['cancel_date'] = Carbon::now();
                $update_query->where('reservation_status', ReservationStatus::PENDING)
                    ->orWhere('reservation_status', ReservationStatus::RECEPTION_COMPLETED);
            }
            $update_query->update($update_data);
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
        $courses = Course::where('hospital_id', session()->get('hospital_id'))->get();
        $prefectures = Prefecture::all();

        return view('reservation.create')->with(['courses' => $courses, 'prefectures' => $prefectures]);
    }


    public function store(ReservationCreateFormRequest $request)
    {

        try {
            DB::beginTransaction();
            $today = Carbon::today();

            $course = Course::find($request->course_id);
            $reservation_date = Carbon::parse($request->reservation_date);

            //TODO to check pessimistic locking is require or not
            $last_acceptance_number = Reservation::whereDate('reservation_date', $reservation_date)
                ->max('acceptance_number');
            $acceptance_number = isset($last_acceptance_number) ? ($last_acceptance_number + 1) : 1;

            $calendar_day = $course->calendar->calendar_days()
                ->whereDate('date', [$reservation_date])->get()->first();

            $holiday = Holiday::where('hospital_id', session()->get('hospital_id'))
                ->where('is_holiday', 1)
                ->whereDate('date', $reservation_date)->get()->first();

            if(isset($holiday) || (isset($calendar_day) && $calendar_day->is_reservation_acceptance != '0') && $calendar_day->reservation_frames == 0) {
                DB::rollback();
                return redirect()->back()->with('error', trans('messages.reservation.not_reservable'))->withInput();
            }

            if (isset($calendar_day) && $calendar_day->reservation_frames > 0) {
                $count = Reservation::join('courses', 'courses.id', '=', 'reservations.course_id')
                    ->whereDate('reservation_date', '=', $reservation_date)
                    ->where('courses.calendar_id', $course->calendar_id)
                    ->count();

                if($count >= $calendar_day->reservation_frames) {
                    DB::rollback();
                    return redirect()->back()->with('error', trans('messages.reservation.limit_exceed'))->withInput();
                }
            }

            request()->merge([
                'hospital_id' => session('hospital_id'),
                'reservation_status' => ReservationStatus::RECEPTION_COMPLETED,
                'is_repeat' => false
            ]);

            $reservation = new Reservation(request()->all());
            $reservation->applicant_name = "$request->family_name $request->first_name";
            $reservation->applicant_name_kana = "$request->family_name_kana $request->first_name_kana";
            $reservation->applicant_tel = str_replace(['－', '-', '‐', '−', '‒', '—', '–', '―', 'ー', 'ｰ', '─', '━', '一'], '', $request->tel);
            $reservation->acceptance_number = $acceptance_number;

            $reservation->tax_included_price = $course->is_price == '1' ? $course->price : 0;
            $reservation->fee = 0;
            $reservation->channel = '2';

            $fee_rate = HospitalPlan::where('hospital_id', session()->get('hospital_id'))
                ->whereDate('from', '<=', Carbon::today())
                ->where(function($q) {
                    $q->whereDate('to', '>=', Carbon::today())
                        ->orWhere('to', '=', null);
                })->get()->first()->contractPlan;

            if (isset($fee_rate)) {
                $reservation->fee_rate = $fee_rate->fee_rate;
                $reservation->fee = (
                        $reservation->tax_included_price +
                        $this->calculateCourseOptionTotalPrice($request) +
                        $request->input('adjustment_price', 0)
                    ) * ($fee_rate->fee_rate / 100);
            }

            if ($request->customer_id) {
                $customer = Customer::findOrFail($request->customer_id);
                $customer->first_name = $request->first_name;
                $customer->family_name = $request->family_name;
                $customer->first_name_kana = $request->first_name_kana;
                $customer->family_name_kana = $request->family_name_kana;
                $customer->tel = $request->tel;
                $customer->sex = $request->sex;
                $customer->email = $request->email;
                $customer->postcode = $request->postcode1 . $request->postcode2;
                $customer->prefecture_id = $request->prefecture_id;
                $customer->address1 = $request->address1;
                $customer->address2 = $request->address2;
                $customer->birthday = $request->birthday;
                $customer->memo = $request->memo;
                $customer->registration_card_number = $request->registration_card_number;
                if (Reservation::where('customer_id', $request->customer_id)->count() > 0) {
                    $reservation->is_repeat = true;
                }
            } else {
                $customer = new Customer([
                    'first_name' => $request->first_name,
                    'family_name' => $request->family_name,
                    'first_name_kana' => $request->first_name_kana,
                    'family_name_kana' => $request->family_name_kana,
                    'tel' => $request->tel,
                    'sex' => $request->sex,
                    'email' => $request->email,
                    'postcode' => $request->postcode1 . $request->postcode2,
                    'prefecture_id' => $request->prefecture_id,
                    'address1' => $request->address1,
                    'address2' => $request->address2,
                    'birthday' => $request->birthday,
                    'memo' => $request->memo,
                    'registration_card_number' => $request->registration_card_number,
                    'hospital_id' => session()->get('hospital_id'),
                ]);
                $customer->save();
            }

            $reservation->customer_id = $customer->id;
            if (!empty($customer->epark_member_id)) {
                $reservation->epark_member_id = $customer->epark_member_id;
            }
            $reservation->save();
            // カレンダーの予約数を1つ増やす
            $this->_reservation_service->registReservationToCalendar($reservation, 1);

            $this->reservationCourseOptionSaveOrUpdate($request, $reservation);
            $this->reservationAnswerCreate($request, $reservation);

//            $this->sendReservationCheckMail(Hospital::find(session('hospital_id')), $reservation, $customer, '登録');

            DB::commit();
            $this->reservation_mail_send($reservation, false);

        } catch (\Exception $i) {
            DB::rollback();

            return redirect()->back()->with('error', trans('messages.reservation.complete_error'))->withInput();
        }

        // 予約履歴api更新
        try {
            if (!empty($reservation->epark_member_id)) {
                $this->_reservation_service->request($reservation);
            }

        } catch (\Exception $e) {
            Log::error('予約履歴api更新に失敗しました。');
        }
        return redirect('reservation')->with('success', trans('messages.reservation.complete_success'));

    }

    protected function reservationCourseOptionSaveOrUpdate($request, $reservation)
    {
        if (!empty($request->course_options) && isset($request->course_options)) {

            $options = [];
            foreach ($request->course_options as $key => $option) {
                $options[] = [
                    'reservation_id' => $reservation->id,
                    'option_id' => $key,
                    'option_price' => $option,
                ];
            }

            if (!empty($options)) {
                $reservation->reservation_options()->createMany($options);
            }
        }
    }

    protected function calculateCourseOptionTotalPrice($request)
    {
        $total = 0;
        if (!empty($request->course_options) && isset($request->course_options)) {
            foreach ($request->course_options as $key => $option) {
                $total += $option;
            }
        }
        return $total;
    }

    protected function reservationAnswerCreate($request, $reservation)
    {
        if (isset(request()->course_id) && !empty(request()->course_id)) {

            $course = Course::find($request->course_id);

            if (isset($course->course_questions) && !empty($course->course_questions)) {

                $reservation_option_values = [];

                foreach ($course->course_questions as $question) {

                    $question_id_values = collect(request()->get('questions_' . $question->id));
                    $answer_columns = [
                        'answer01',
                        'answer02',
                        'answer03',
                        'answer04',
                        'answer05',
                        'answer06',
                        'answer07',
                        'answer08',
                        'answer09',
                        'answer10',
                    ];
                    foreach ($answer_columns as $answer_column) {
                        $answer_values[$answer_column] = ($question_id_values->get($answer_column)) ? 1 : 0;
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


                if (isset($reservation_option_values) && !empty($reservation_option_values)) { // make it verified it has value
                    $reservation->reservation_answers()->createMany($reservation_option_values);
                }


            }

        }
    }

    public function edit(Reservation $reservation)
    {
        $hospital_id = session()->get('hospital_id');

        if (isset($hospital_id) && $hospital_id != $reservation->hospital_id) {
            abort(404);
        }

        $courses = Course::where('hospital_id', $reservation->hospital_id)->get();
        $reservation_answers = $reservation->reservation_answers;

        $course_question_ids = [];
        $questions = [];
        $i = 1;
        if ($reservation_answers) {
            foreach ($reservation_answers as $reservation_answer) {
                $course_question_ids[] = $reservation_answer->course_question_id;

                $qa = [];
                while ($i <= 10) {
                    $number = ($i < 10) ? '0' . $i : 10;
                    $answer_fieldname = "answer$number";
                    $question_answer_fieldname = "question_answer$number";
                    if ($reservation_answer->$answer_fieldname) {
                        $qa['answer' . $number] = $reservation_answer->$question_answer_fieldname;
                    }
                    $i++;
                }

                $questions["questions_" . $reservation_answer->course_question_id] = $qa;
                $i = 0;

            }

        }

        $course_options = [];
        if ($reservation->reservation_options) {
            $reservation_options = $reservation->reservation_options;
            foreach ($reservation_options as $key => $option) {
                $course_options[$option->option_id] = (string)$option->option_price;
            }
        }

        return view('reservation.edit', [
            'reservation' => $reservation,
            'courses' => $courses,
            'course_options' => $course_options,
            'course_question_ids' => $course_question_ids,
            'questions' => $questions,
        ]);

    }

    public function update(ReservationUpdateFormRequest $request, Reservation $reservation)
    {
        try {
            DB::beginTransaction();
            $today = Carbon::today();
            $old_reservation = Reservation::find($reservation->id);
            $old_reservation_options = ReservationOption::where('reservation_id', $reservation->id)->get();

            $course = Course::find($request->course_id);
            $reservation_date = Carbon::parse($request->reservation_date);
            $old_reservation_date = $reservation->reservation_date;

            $calendar_day = $course->calendar->calendar_days()
                ->whereDate('date', [$reservation_date])->get()->first();

            $holiday = Holiday::where('hospital_id', session()->get('hospital_id'))
                ->where('is_holiday', 1)
                ->whereDate('date', $reservation_date)->get()->first();

            if(isset($holiday) || (isset($calendar_day) && $calendar_day->is_reservation_acceptance != '0') && (isset($calendar_day) && $calendar_day->reservation_frames == 0)) {
                DB::rollback();
                return redirect()->back()->with('error', trans('messages.reservation.not_reservable'))->withInput();
            }

            // only checking reservation frame for different course select
            if ($course->calendar_id != $reservation->course->calendar_id && isset($calendar_day) && $calendar_day->reservation_frames > 0) {
                $count = Reservation::join('courses', 'courses.id', '=', 'reservations.course_id')
                    ->whereDate('reservation_date', '=', $reservation_date)
                    ->where('courses.calendar_id', $course->calendar_id)
                    ->count();

                if($count >= $calendar_day->reservation_frames) {
                    DB::rollback();
                    return redirect()->back()->with('error', trans('messages.reservation.limit_exceed'))->withInput();
                }
            }

            $params = $request->all();

            $params['tax_included_price'] = $course->is_price == '1' ? $course->price : 0;
            $hospital = Hospital::find(session()->get('hospital_id'));

            if ($reservation->site_code == 'HP') {
                $params['fee'] = $this->_reservation_service->getHpfee($hospital);
                if ($reservation->fee > 0) {
                    $params['is_free_hp_link'] = IsFreeHpLink::FREE;
                } else {
                    $params['is_free_hp_link'] = IsFreeHpLink::FEE;
                }
            } else {
                $params['fee'] = floor((
                        $params['tax_included_price'] +
                        $this->calculateCourseOptionTotalPrice($request) +
                        $request->input('adjustment_price', 0)
                    ) * ($reservation->fee_rate / 100));
                $params['is_free_hp_link'] = IsFreeHpLink::FEE;
            }

            $reservation->update($params);

            if (!$calendar_day) {
                $calendar_day = new CalendarDay();
                $calendar_day->date = $reservation_date;
                $calendar_day->is_holiday = 0;
                $calendar_day->is_reservation_acceptance = 0;
                $calendar_day->reservation_frames = 1;
                $calendar_day->reservation_count = 1;
                $calendar_day->calendar_id = $course->calendar->id;
                $calendar_day->status = Status::VALID;
                $calendar_day->created_at = Carbon::today();
                $calendar_day->updated_at = Carbon::today();
                $calendar_day->save();
            }

            if (!$reservation_date->eq($old_reservation_date)) {
                // カレンダーの予約数を1つ減らす
                $reservation->reservation_date = $old_reservation_date;
                $this->_reservation_service->registReservationToCalendar($reservation, -1);
                // カレンダーの予約数を1つ増やす
                $reservation->reservation_date = $reservation_date;
                $this->_reservation_service->registReservationToCalendar($reservation, 1);
            }

            $reservation->reservation_options()->forceDelete();
            $this->reservationCourseOptionSaveOrUpdate($request, $reservation);

            $reservation->reservation_answers()->forceDelete();

            $this->reservationAnswerCreate($request, $reservation);

//            $this->sendReservationCheckMail(Hospital::find(session('hospital_id')), $reservation, $reservation->customer, '変更');

            DB::commit();

            if ($this->is_send_mail($reservation, $old_reservation, $request->course_options, $old_reservation_options)) {
                $this->reservation_mail_send($reservation, true);
            }

        } catch (\Exception $i) {
            Log::error($i);
            DB::rollback();

            return redirect()->back()->with('error', trans('messages.reservation.status_update_error'))->withInput();
        }

        // 予約履歴api更新
        try {
            if (!empty($reservation->epark_member_id)) {
                $this->_reservation_service->request($reservation);
            }

        } catch (\Exception $e) {
            Log::error('予約履歴api更新に失敗しました。');
        }

        return redirect('reservation')->with('success', trans('messages.reservation.update_success'));
    }

    private function is_send_mail($reservation, $old_reservation, $options, $old_options) {
        // コースチェック
        if ($reservation->course_id != $old_reservation->course_id) {
            return true;
        }

        // 調整額
        if (!empty($reservation->adjustment_price) && empty($old_reservation->adjustment_price)) {
            return true;
        } elseif (empty($reservation->adjustment_price) && !empty($old_reservation->adjustment_price)) {
            return true;
        } elseif ($reservation->adjustment_price != $old_reservation->adjustment_price) {
            return true;
        }

        // 受診日
        if ($reservation->reservation_date != $old_reservation->reservation_date) {
            return true;
        }

        // うけつ時間
        if (!empty($reservation->start_time_hour) && empty($old_reservation->start_time_hour)) {
            return true;
        } elseif (empty($reservation->start_time_hour) && !empty($old_reservation->start_time_hour)) {
            return true;
        } elseif ($reservation->start_time_hour != $old_reservation->start_time_hour) {
            return true;
        }


        if (!empty($reservation->start_time_min) && empty($old_reservation->start_time_min)) {
            return true;
        } elseif (empty($reservation->start_time_min) && !empty($old_reservation->start_time_min)) {
            return true;
        } elseif ($reservation->start_time_min != $old_reservation->start_time_min) {
            return true;
        }

        // オプション
        if ((empty($options) && !$old_options->isEmpty())
            || (!empty($options) && $old_options->isEmpty())) {
            return true;
        }

        if (empty($options) && $old_options->isEmpty()) {
            return false;
        }

        if (count($options) != $old_options->count()) {
            return true;
        }

        foreach ($old_options as $o) {
            if (!array_key_exists($o->id, $options)) {
                return true;
            }
        }

        return false;
    }

    /**
     * 受付確認メール送信
     * @param array $reservationDates
     */
    public function sendReservationCheckMail($hospital, $reservation, $customer, $processing)
    {
//        $contract_information = ContractInformation::where('hospital_id', $hospital->id)->first();

        $mailContext = [
            'staff_name' => Auth::user()->name,
            'processing' => $processing,
            'hospital_name' => $hospital->name,
            'reservation' => $reservation
        ];

        Mail::to(config('mail.to.gyoumu'))->send(new ReservationCheckMail($mailContext));

//        if (isset($customer->email)) {
//            Mail::to($customer->email)->send(new ReservationCheckMail($mailContext));
//        }

//        if (isset($contract_information)) {
//            Mail::to($contract_information->email)->send(new ReservationCheckMail($mailContext));
//        }
    }

}
