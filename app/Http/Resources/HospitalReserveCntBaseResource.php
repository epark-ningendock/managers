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

        return collect([])
            ->put('status', 0)
            ->merge($this->createResult())
            ->toArray();

    }

    private function createResult() {
        $hospital_code = explode(',', $this['hospital_code']);
        $results = [];

        $i = 0;
        foreach ($hospital_code as $code) {
            $courses = $this->getCourseReserveCnt($code);

            if (! $courses) {
                continue;
            }

            $result = [];
            $j = 0;
            foreach ($courses as $course) {
                $data = ['course_no' => $course->id, 'r_vol' => $this->getReserveCnt($course->id)];
                $result[$j] = $data;
                $j += 1;
            }
            $d = [$code, [$result]];
            $results[$i] = $d;
            $i += 1;
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
