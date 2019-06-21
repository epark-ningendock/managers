<?php

namespace App\Http\Controllers;

use App\Reservation;
use App\Hospital;
use App\Customer;
use App\Course;
use App\Services\ReservationExportService;
//use App\CourseOption;
//use App\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Enums\ReservationStatus;
use Illuminate\Support\Facades\Session;

class ReservationController extends Controller
{
    protected $reservation;
    protected $hospital;
    protected $customer;
    protected $course;
    protected $export_file;

    public function __construct(
        Reservation $reservation,
        Hospital $hospital,
        Customer $customer,
        Course $course,
        ReservationExportService $export_file
    ) {
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
        $params = $request->all();

        $query = $this->reservation
            ->byRequest($request)
            ->with(['hospital', 'course', 'customer'])
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
        $query = Reservation::with(['course', 'customer', 'reservation_options', 'reservation_options.option']);

        if($request->input('reservation_start_date', '') != '') {
            $query->whereDate('reservation_date', '>=', $request->input('reservation_start_date'));
        }

        if($request->input('reservation_end_date', '') != '') {
            $query->whereDate('reservation_date', '<=', $request->input('reservation_end_date'));
        }

        if($request->input('completed_start_date', '') != '') {
            $query->whereDate('completed_date', '>=', $request->input('completed_start_date'));
        }

        if($request->input('completed_end_date', '') != '') {
            $query->whereDate('completed_date', '<=', $request->input('completed_end_date'));
        }

        if($request->input('customer_name', '') != '') {
            $query->whereHas('Customer', function ($q) use ($request) {
                $q->where(DB::raw("concat(name_seri, name_mei)"), 'LIKE', '%'.$request->input('customer_name').'%');
                $q->orWhere(DB::raw("concat(name_kana_seri, name_kana_mei)"), 'LIKE', '%'.$request->input('customer_name').'%');
            });
        }

        if($request->input('course_id', '') != '') {
            $query->where('course_id', $request->input('course_id'));
        }

        $status_filter = collect();

        if($request->input('is_pending', '') != '') {
            $status_filter->push($request->input('is_pending'));
        }

        if($request->input('is_reception_completed', '') != '') {
            $status_filter->push($request->input('is_reception_completed'));
        }

        if($request->input('is_completed', '') != '') {
            $status_filter->push($request->input('is_completed'));
        }

        if($request->input('is_cancelled', '') != '') {
            $status_filter->push($request->input('is_cancelled'));
        }

        if($status_filter->isNotEmpty()) {
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
        ]);

        $page_per_record = $request->input('record_per_page', 10);

        $query = $this->get_reception_list_query($request);
        $reservations = $query->paginate($page_per_record)
            ->appends($request->query());
        $courses = Course::all();

        return view('reservation.reception', compact('reservations', 'courses'))
            ->with($request->input());
    }

    /**
    * CSV download
    * @param array $columnNames
    * @param array $rows
    * @param string $fileName
    * @return \Symfony\Component\HttpFoundation\StreamedResponse
    */
    protected function get_csv($columnNames, $rows, $fileName) {
        $headers = [
            "Content-Encoding" => "UTF-8",
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=" . $fileName,
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];
        $callback = function() use ($columnNames, $rows ) {
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
        $option_count = 1;

        //TODO to check performance and if slow, to change to use SQL directly
        $reservations = $query->get()->map(function($reservation) use (&$option_count){
            if ($reservation->reservation_options->count() > $option_count) {
                $option_count = $reservation->reservation_options->count();
            }

            $fee = $reservation->course->price + $reservation->adjustment_price;
            $options = collect();

            foreach($reservation->reservation_options as $reservation_option) {
                $options->push($reservation_option->option->name);
                $options->push($reservation_option->option->price);
                $fee += $reservation_option->option->price;
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
                $reservation->billing_price,
                $reservation->acceptance_number,
                Reservation::getChannel($reservation->channel),
                $reservation->course_question,
                $reservation->reservation_memo_crypt,
                $reservation->todays_memo_crypt
            ];

            return array_merge($result, $options->toArray());
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
            '決済ステータス',
            'カード決済額',
            'キャシュポ利用額',
            '現地支払い額',
            '受付番号',
            '受付形態',
            '受付質問',
            '受付・予約メモ',
            '医療機関備考'
        ];

        for($i = 0; $i < $option_count; $i++) {
            $headers = array_merge($headers,['オプション', 'オプション金額']);
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
            $reservation->reservation_status = ReservationStatus::ReceptionCompleted;
            $reservation->save();
            Session::flash('success', trans('messages.reservation.accept_success'));
            DB::commit();
            return redirect('reception');
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
            $reservation->reservation_status = ReservationStatus::Cancelled;
            $reservation->save();
            Session::flash('success', trans('messages.reservation.cancel_success'));
            return redirect('reception');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(trans('messages.reservation.cancel_error'))->withInput();
        }
    }

}
