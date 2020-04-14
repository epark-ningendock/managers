<?php

namespace App\Imports;

use App\ContractPlan;
use App\ConvertedIdString;
use App\Course;
use App\CourseDetail;
use App\CourseMeta;
use App\DistrictCode;
use App\Hospital;
use App\HospitalDetail;
use App\HospitalMeta;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Row;

class HospitalMetaImport extends ImportAbstract implements WithChunkReading
{
    /**
     * 旧システムのインポート対象テーブルのプライマリーキーを返す
     * @return string
     */
    public function getOldPrimaryKeyName(): string
    {
        return 'no';
    }

    /**
     * 新システムの対象クラス名を返す
     * @return string
     */
    public function getNewClassName(): string
    {
        return HospitalMeta::class;
    }

    /**
     * @param Row $row
     * @throws \Exception
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $hospitals = Hospital::all();

        foreach ($hospitals as $hospital) {

            $hospital_meta = HospitalMeta::where('hospital_id', $hospital->id)->first();
            if (!$hospital_meta) {
                $hospital_meta = new HospitalMeta();
                $hospital_meta->hospital_id = $hospital->id;
            }
            $hospital_details = HospitalDetail::where('hospital_id', $hospital->id)
                ->get();

            if ($hospital_details) {
                foreach ($hospital_details as $detail) {
                    if ($detail->minor_classification_id == 5 and !empty($detail->inputstring)) {
                        $hospital_meta->credit_card_flg = 1;
                    }

                    if ($detail->minor_classification_id == 1 and $detail->select_status == 1) {
                        $hospital_meta->parking_flg = 1;
                    }

                    if ($detail->minor_classification_id == 3 and $detail->select_status == 1) {
                        $hospital_meta->pick_up_flg = 1;
                    }

                    if ($detail->minor_classification_id == 16 and $detail->select_status == 1) {
                        $hospital_meta->children_flg = 1;
                    }

                    if ($detail->minor_classification_id == 19 and $detail->select_status == 1) {
                        $hospital_meta->dedicate_floor_flg = 1;
                    }
                }
                $hospital_meta->save();
            }

            $courses = Course::where('hospital_id', $hospital->id)->get();

            $course_name = '';
            $category_exam_names = '';
            $category_disease_names = '';

            if ($courses) {
                foreach ($courses as $course) {
                    $course_meta = CourseMeta::where('course_id', $course->id)->first();
                    if (!$course_meta) {
                        $course_meta = new CourseMeta();
                        $course_meta->hospital_id = $hospital->id;
                        $course_meta->course_id = $course->id;
                    }

                    $course_name = $course_name . ' ' . $course->name;

                    $category_exam_name = '';
                    $category_disease_name = '';
                    $category_part_name = '';
                    $category_exam = '';
                    $category_disease = '';
                    $category_part = '';
                    $meal_flg = 0;
                    $pear_flg = 0;
                    $female_doctor_flg = 0;

                    $course_details = CourseDetail::where('course_id', $course->id)->get();
                    if ($course_details) {
                        foreach ($course_details as $detail) {
                            if ($detail->major_classification_id == 13 && $detail->select_status == 1) {
                                $category_exam_name = $category_exam_name . ' '. $detail->minor_classification->name;
                                $category_exam = $category_exam . ' '. $detail->minor_classification_id;
                            }

                            if ($detail->major_classification_id == 25 && $detail->select_status == 1) {
                                $category_disease_name = $category_disease_name . ' '. $detail->minor_classification->name;
                                $category_disease = $category_disease . ' '. $detail->minor_classification_id;
                            }

                            if (($detail->major_classification_id == 2 || $detail->major_classification_id == 3 || $detail->major_classification_id == 4)
                                && $detail->select_status == 1) {
                                $category_part_name = $category_part_name . ' '. $detail->minor_classification->name;
                                $category_part = $category_part . ' '. $detail->minor_classification_id;
                            }

                            if ($detail->minor_classification_id == 256 && $detail->select_status == 1) {
                                $meal_flg = 1;
                            }

                            if ($detail->minor_classification_id == 132 && $detail->select_status == 1) {
                                $pear_flg = 1;
                            }

                            if ($detail->minor_classification_id == 126 && $detail->select_status == 1) {
                                $female_doctor_flg = 1;
                            }
                        }
                    }

                    $category_exam_names = $category_exam_names . ' ' . $category_exam_name;
                    $category_disease_names = $category_disease_names . ' ' . $category_disease_name;

                    $course_meta->course_name = $course->name;
                    $course_meta->category_exam_name = $category_exam_name;
                    $course_meta->category_exam = $category_exam;
                    $course_meta->category_disease_name = $category_disease_name;
                    $course_meta->category_disease = $category_disease;
                    $course_meta->category_part_name = $category_part_name;
                    $course_meta->category_part = $category_part;
                    $course_meta->meal_flg = $meal_flg;
                    $course_meta->pear_flg = $pear_flg;
                    $course_meta->female_doctor_flg = $female_doctor_flg;
                    $course_meta->save();
                }
            }
            $hospital_meta->course_name = $course_name;
            $hospital_meta->category_exam_name = $category_exam_names;
            $hospital_meta->category_disease_name = $category_disease_names;
            $hospital_meta->save();
        }
    }

    public function batchSize(): int
    {
        return 10000;
    }
    public function chunkSize(): int
    {
        return 10000;
    }
}
