<?php

namespace App\Http\Resources;

class HospitalContentsResource extends HospitalContentBaseResource
{
    /**
     * 医療機関コンテンツ into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return
            collect([])
                ->merge(new HospitalBasicResource($this))
                ->put('exam_type', $this->getCategoryType())
                ->put('interview', $this->getInterview())
                ->put('hospital_photo', $this->getHospitalPhoto())
                ->toArray();
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
     * インタビュー情報取得
     */
    private function getInterview() {

        $categories = $this->hospital_categories->filter(function ($c) {
            return isset($c->image_order)
                && $c->image_order === 6;
        });

        $result = [];
        foreach ($categories as $category) {

            $interviews = $category->interview_details;
            $interview_flg = 0;
            if (!empty($interviews)) {
                $interview_flg = 1;
            }
            $interview_qa = [];
            foreach ($interviews as $interview) {
                $interview_qa[] = ['q' => $interview->question, 'a' => $interview->answer];
            }

            $result = ['img_url' => $category->hospital_image ? $category->hospital_image->path ?? '' : '',
                'title' => $category->title,
                'caption' => $category->caption,
                'qa_flg' => $interview_flg,
                'interview_qa' => $interview_qa];
        }

        return $result;
    }

    /**
     * 医療機関画像情報取得
     */
    private function getHospitalPhoto() {
        $categories = $this->hospital_categories->filter(function ($c) {
            return isset($c->image_order)
                && ($c->image_order === 8);
        });

        $results = [];
        foreach ($categories as $category) {

            $result = ['image_location_no' => $category->file_location_no,
                'img_url' => $category->hospital_image ? $category->hospital_image->path ?? '' : '',
                'img_desc' =>$category->hospital_image ? $category->hospital_image->memo2 ?? '' : ''];

            $results[] = $result;
        }

        return $results;
    }
}
