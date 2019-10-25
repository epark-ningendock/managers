<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

use App\Course;
use App\Enums\ReservationStatus;
use App\Enums\Status;
use App\Hospital;
use App\Reservation;
use Carbon\Carbon;

class HospitalReserveCntBaseResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = $this['data'];

        return collect([])
            ->put('status', 0)
            ->merge($data)
            ->toArray();

    }

    private function createResult() {

        $from = Carbon::today()->subDay(30);
        $to = Carbon::today();

        $hospitals = Hospital::with([
            'courses' => function($q) {
                $q->where('publish_start_date', '<=', Carbon::today())
                    ->orWhereNull('publish_start_date');
                $q->where('publish_end_date', '>=', Carbon::today())
                    ->orWhereNull('publish_end_date');
            },
            'courses.reservations' => function ($q) use ($from, $to) {
                $q->where('reservation_status', '<>', ReservationStatus::CANCELLED);
                $q->whereBetween('reservation_date', [$from, $to]);
            }
            ])
            ->where('status', Status::VALID)
            ->get();
        $results = [];

        $i = 0;
        foreach ($hospitals as $hospital) {

            if (empty($hospital->courses)) {
                continue;
            }

            $result = [];
            foreach ($hospital->courses as $course) {
                if (empty($course->reservations)) {
                    $result[] = ['course_no' => $hospital->courses->id, 'r_vol' => 0];
                    continue;
                } else {
                    $result[] = ['course_no' => $hospital->courses->id, 'r_vol' => $course->reservations->count()];
                }
            }

            $results[$hospital->code] = $result;
        }

        return $results;
    }

    /**
     * 公開中コースの予約数取得
     */
    private function getCourseReserveCnt(string $code) {

        $hospital = Hospital::join('contract_informations', 'contract_informations.hospital_id', 'hospitals.id')
            ->where('contract_informations.code', $code)
            ->first();

        $target_date = Carbon::today();
        $target_date = $target_date->subMonth(1);

        return Course::where('courses.hospital_id', $hospital->id)
            ->where('courses.status', Status::VALID)
            ->where(function ($query) {
                $query->where('publish_start_date', '<=', Carbon::today())
                    ->orWhereNull('publish_start_date');
            })
            ->where(function ($query) {
                $query->where('publish_end_date', '>=', Carbon::today())
                    ->orWhereNull('publish_end_date');
            })
            ->get();
    }

    private function getReserveCnt(int $course_id) {

        $target_date = Carbon::today();
        $target_date = $target_date->subMonth(1);

        return Reservation::where('course_id', $course_id)
            ->where('reservation_status', '<>', ReservationStatus::CANCELLED)
            ->where('reservation_date', [$target_date, Carbon::today()])
            ->count();
    }
}
