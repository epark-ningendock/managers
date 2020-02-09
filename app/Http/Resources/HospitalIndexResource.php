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
            ->put('non_consultation', $this->consultation_note ?? '')
            ->put('non_consultation_note', $this->memo ?? '')
            ->put('public_status', $this->status)
            ->put('update_at', Carbon::parse($this->updated_at)->format('Y-m-d'))
            ->merge(new HospitalContentBaseResource($this))
            ->put('courses', CoursesBaseResource::collection($this->courses))
            ->toArray();
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

    /**
     * @return array
     */
    private function getStaff() {

        $result = [];
        foreach ($this->hospital_categories as $category) {

            if (isset($category->image_order) && $category->image_order == 7) {
                $staff = ['img_url' => $category->hospital_image->path,
                    'img_alt' => '',
                    'name' => $category->name,
                    'bio' => $category->career,
                    'comment' => $category->memo];
                $result[] = $staff;
            }
        }

        return $result;
    }

    /**
     * サブメイン画像取得
     *
     * @param  医療機関カテゴリ
     * @return サブメイン画像
     */
    private function getImgSub($hospital_categories) {

        if(!isset($hospital_categories)) return '';

        foreach ($hospital_categories as $category) {
            if (isset($category->image_order)
                && ($category->image_order === 2)
                && ($category->file_location_no == 1)
                && !empty($category->hospital_image->path)) {
                return $category->hospital_image->path;
            }
        }

        return '';
    }

    /**
     * @return string
     */
    private function getClosedDay() {
        $medical_treatment_times = MedicalTreatmentTime::where('hospital_id', $this->id)->get();

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

        return rtrim($result, '・');
    }

    /**
     * @return string
     */
    private function getSociety() {
        foreach ($this->hospital_details as $detail) {
            if ($detail->minor_classification_id == 10 and $detail->select_status == 1) {
                return '1';
            }
        }
        return '0';
    }

    /**
     * @return array
     */
    private function getHospitalCategory() {

        $category = [];
        foreach ($this->hospital_details as $detail) {
            if ($detail->select_status == 1 and $detail->minor_classification->is_icon == '1') {
                $result = ['id' => $detail->minor_classification_id, 'title' => $detail->minor_classification->icon_name];
                $category[] = $result;
            }
        }

        return $category;
    }

    /**
     * @return array
     */
    private function getTopdata() {

        $top = [];
        $top[0] = '';
        $top[1] = '';
        foreach ($this->hospital_categories as $category) {

            if (isset($category->image_order) && $category->image_order == 3) {
                $top[0] = $category->title;
                $top[1] = $category->caption;
            }
        }

        return $top;
    }

    /**
     * @return array
     */
    private function getPoint() {

        $points = [];
        foreach ($this->hospital_categories as $category) {

            if (isset($category->image_order) && $category->image_order == 5) {
                $point = ['img_url' => $category->hospital_image->path,
                    'img_alt' => $category->title,
                    'title' => $category->title,
                    'desc' => $category->caption,
                    'img_pos' => $category->file_location_no];
                $points[] = $point;
            }
        }

        return $points;
    }

    /**
     * @return string
     */
    private function getPayment() {
        foreach ($this->hospital_details as $detail) {
            if ($detail->minor_classification_id == 5) {
                return isset($detail->inputstring) ? $detail->inputstring : '';
            }
        }
        return '';
    }

    /**
     * @return array
     */
    private function getMovieInfo() {

        $access_movie = $this->_hospital_movie();
        $one_minutes = $this->getOneMinutes($this->hospital_categories);
        $tour = $this->getTourInterview()[0];
        $interview = $this->getTourInterview()[1];

        return ['access'=>$access_movie, 'oneMinute'=>$one_minutes, 'tour'=>$tour, 'interview'=>$interview];
    }

    /**
     * アクセス動画URL
     *
     * @param  医療機関カテゴリ
     * @return 医療施設メイン
     */
    private function _hospital_movie()
    {
        $categories = $this->hospital_categories->filter(function ($c) {
            return isset($c->image_order)
                && $c->image_order === 4;
        });

        foreach ($categories as $category) {
            if (!empty($category->hospital_image->memo1)) {
                return $category->hospital_image->memo1;
            }
        }

        return '';
    }

    /**
     * 1分動画
     *
     * @param  医療機関カテゴリ
     * @return
     */
    private function getOneMinutes($hospital_categories) {

        if(!isset($hospital_categories)) {
            return '';
        }

        $el = $hospital_categories->first(function ($v) {
            return isset($v->image_order)
                && $v->image_order === 3
                && isset($v->file_location_no)
                && $v->file_location_no === 1;
        });

        if (isset($el) && isset($el->caption)) {
            $strs = explode(' ', $el->caption);
            foreach ($strs as $str) {
                if (strstr($str, 'src=') === false) {
                    continue;
                }
                $str = mb_ereg_replace('\"', '\'', $str);

                $one_minute_url = ltrim($str, 'src=\'');

                return rtrim($one_minute_url,'\'');
            }
        } else {
            return "";
        }
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    private function getTourInterview() {

        $free_area_strs = explode(' ', $this->free_area);
        $tour = '';
        $interview = '';

        foreach ($free_area_strs as $str) {
            if (preg_match('/src/', $str) && preg_match('/grooon/', $str)) {
                $str = ltrim($str, 'src=');
                $str = mb_ereg_replace('\'', '', $str);
                $tour = mb_ereg_replace('\"', '', $str);
            }
            if (preg_match('/src/', $str) && !preg_match('/grooon/', $str)) {
                $str = ltrim($str, 'src=');
                $str = mb_ereg_replace('\'', '', $str);
                $interview = mb_ereg_replace('\"', '', $str);
            }
        }

        return collect([$tour, $interview]);
    }

    /**
     * @return array
     */
    private function getCategoryType() {

        $results = [];
        $sort_key = [];
        $courses = $this->courses;

        foreach ($courses as $course) {
            foreach ($course->course_details as $detail) {
                if ($detail->major_classification_id == 13
                    && $detail->select_status == 1
                    && $detail->status == '1'
                ) {
                    $result = ['id' => $detail->minor_classification_id, 'title' => $detail->minor_classification->name];
                    $results[] = $result;
                    $sort_key[] = $detail->minor_classification_id;
                }
            }
        }

        $sort_key = array_unique($sort_key);
        $results = array_unique($results, SORT_REGULAR);
        array_multisort($sort_key, SORT_NATURAL, $results);
        return $results;
    }

    /**
     * @return int
     */
    private function hasCourse() {

        if (isset($this->courses)) {
            return 1;
        }

        return 0;
    }
}
