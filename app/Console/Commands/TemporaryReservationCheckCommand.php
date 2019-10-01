<?php

namespace App\Console\Commands;

use App\Enums\ReservationStatus;
use App\Mail\Job\TemporaryReservationCheckMail;
use App\Reservation;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TemporaryReservationCheckCommand extends Command
{
    protected $signature = 'temporary-reservation-check';
    protected $description = '仮受付で3日間経過している受付情報をお知らせする';

    /**
     * 3日経過している仮受付情報をお知らせする
     *
     * @return バッチ実行成否
     */
    public function handle()
    {
        // 対象データ取得
        $reservationDates = $this->getReservationData();
        if (!$reservationDates) {
            return 1;
        }

        // 予約情報をジョブにてメール送信
        $reservations = $this->createTemporaryReservationData($reservationDates);
        $this->sendReceptionCheckMail($reservations);
    }

    /**
     * 仮受付予約データ取得
     */
    protected function getReservationData() {

        $date = Carbon::today();
        $date->subDay(config('constant.pv_aggregate_day'));
        $reservationDates = Reservation::where('status', '=', ReservationStatus::PENDING)
            ->where('created_at', '<=', $date)
            ->orderBy('hospital_id', 'asc')
            ->orderBy('reservation_date', 'asc')
            ->get();

        return $reservationDates;
    }

    /**
     * 仮受付予約データを生成して返す
     * @param array $reservationDates
     */
    protected function createTemporaryReservationData(array $reservationDates) {

        $results = [];
        foreach ($reservationDates as $reservationData) {
            $result = [
                'id' => $reservationData->id,
                'hospital_name' => $reservationData->hospital()->hospital_name,
                'reservation_date' => $reservationData->reservation_date,
                'completed_date' => $reservationData->completed_date,
                'customer_name' => $reservationData->customer()->family_name . $reservationData->customer()->first_name,
            ];

            $results = $result;
        }

        return $results;
    }

    /**
     * 仮受付確認メール送信
     * @param array $reservationDates
     */
    public function sendReceptionCheckMail(array $reservationDates)
    {
        $mailContext = [
            'reservation_dates' => $reservationDates
        ];
        $to = config('mail.to.admin_reservation');
        Mail::to($to)->send(new TemporaryReservationCheckMail($mailContext));
    }
}
