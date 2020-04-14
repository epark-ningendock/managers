<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;
use App\MedicalTreatmentTime;

class HospitalIndexResource extends Resource
{
    /**
     * 医療機関情報 into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return collect([])
            ->merge(new HospitalBasicResource($this))
            ->put('open', $this->getMedicalTreatmentTime())
            ->put('closed_day', $this->getClosedDay())
            ->put('non_consultation', $this->consultation_note ?? '')
            ->put('non_consultation_note', $this->memo ?? '')
            ->put('public_status', $this->status)
            ->put('update_at', Carbon::parse($this->updated_at)->format('Y年m月d日'))
            ->merge(new HospitalContentBaseResource($this))
            ->put('courses', CoursesBaseResource::collection($this->courses))
            ->toArray();
    }

    /**
     * @return array
     */
    private function getClosedDay() {
        $medical_treatment_times = $this->medical_treatment_times;

        $mon_flg = false;
        $tue_flg = false;
        $wed_flg = false;
        $thu_flg = false;
        $fri_flg = false;
        $sat_flg = false;
        $sun_flg = false;
        $hol_flg = false;

        foreach ($medical_treatment_times as $entity) {
            if ($entity->mon == 1) {
                $mon_flg = true;
            }
            if ($entity->tue == 1) {
                $tue_flg = true;
            }
            if ($entity->wed == 1) {
                $wed_flg = true;
            }
            if ($entity->thu == 1) {
                $thu_flg = true;
            }
            if ($entity->fri == 1) {
                $fri_flg = true;
            }
            if ($entity->sat == 1) {
                $sat_flg = true;
            }
            if ($entity->sun == 1) {
                $sun_flg = true;
            }
            if ($entity->hol == 1) {
                $hol_flg = true;
            }
        }

        $result = '';
        if (!$mon_flg) {
            $result = '月・';
        }
        if (!$tue_flg) {
            $result = $result . '火・';
        }
        if (!$wed_flg) {
            $result = $result . '水・';
        }
        if (!$thu_flg) {
            $result = $result . '木・';
        }
        if (!$fri_flg) {
            $result = $result . '金・';
        }
        if (!$sat_flg) {
            $result = $result . '土・';
        }
        if (!$sun_flg) {
            $result = $result . '日・';
        }
        if (!$hol_flg) {
            $result = $result . '祝・';
        }

        $result = rtrim($result, '・');

        if (empty($result)) {
            $result = 'なし';
        }

        return $result;
    }

    /**
     * @return array
     */
    private function getMedicalTreatmentTime() {
        $medical_treatment_times = $this->medical_treatment_times;
        $week_data = [];
        foreach ($medical_treatment_times as $entity) {
            if ($entity->start == '-' && $entity->end == '-') {
                continue;
            }
            $week_data[] = [
                'start' => $entity->start ?? '',
                'end' => $entity->end ?? '',
                'mon' => $entity->mon,
                'tue' => $entity->tue,
                'wed' => $entity->wed,
                'thu' => $entity->thu,
                'fri' => $entity->fri,
                'sat' => $entity->sat,
                'sun' => $entity->sun,
                'hol' => $entity->hol
            ];

        }

       return $week_data;
    }
}
