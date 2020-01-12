<?php

namespace App\Http\Resources;

class HospitalAccessResource extends HospitalContentBaseResource
{
    /**
     * 医療機関アクセス into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return
            collect([])
                ->merge(new HospitalBasicResource($this))
                ->put('medical_treatment_time', $this->getMedicalTreatmentTime()[1])
                ->put('closed_day', $this->getMedicalTreatmentTime()[0])
                ->toArray();
    }

    /**
     * @return array
     */
    private function getMedicalTreatmentTime() {
        $medical_treatment_times = $this->medical_treatment_times;

        $mon_flg = false;
        $tue_flg = false;
        $wed_flg = false;
        $thu_flg = false;
        $fri_flg = false;
        $sat_flg = false;
        $sun_flg = false;
        $hol_flg = false;
        $mon = [];
        $tue = [];
        $wed = [];
        $thu = [];
        $fri = [];
        $sat = [];
        $sun = [];
        $hol = [];
        foreach ($medical_treatment_times as $entity) {
            if ($entity->mon == 1) {
                $mon_flg = true;
                $mon[] = ($entity->start ?? '') . '〜' . ($entity->end ?? '');
            }
            if ($entity->tue == 1) {
                $tue_flg = true;
                $tue[] = ($entity->start ?? '') . '〜' . ($entity->end ?? '');
            }
            if ($entity->web == 1) {
                $wed_flg = true;
                $wed[] = ($entity->start ?? '') . '〜' . ($entity->end ?? '');
            }
            if ($entity->thu == 1) {
                $thu_flg = true;
                $thu[] = ($entity->start ?? '') . '〜' . ($entity->end ?? '');
            }
            if ($entity->fri == 1) {
                $fri_flg = true;
                $fri[] = ($entity->start ?? '') . '〜' . ($entity->end ?? '');
            }
            if ($entity->sat == 1) {
                $sat_flg = true;
                $sat[] = ($entity->start ?? '') . '〜' . ($entity->end ?? '');
            }
            if ($entity->sun == 1) {
                $sun_flg = true;
                $sun[] = ($entity->start ?? '') . '〜' . ($entity->end ?? '');
            }
            if ($entity->hol == 1) {
                $hol_flg = true;
                $hol[] = ($entity->start ?? '') . '〜' . ($entity->end ?? '');
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

        if (!empty($this->consultation_note)) {
            $result = $result . '（'. $this->consultation_note . '）';
        }

        $medical_treatment_time = ['mon' => $mon,
            'tue' => $tue,
            'wed' => $wed,
            'thu' => $thu,
            'fri' =>$fri,
            'sat' => $sat,
            'sun' => $sun,
            'hol' =>$hol];

        return [$result, $medical_treatment_time];
    }
}
