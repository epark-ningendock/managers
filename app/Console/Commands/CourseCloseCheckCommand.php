<?php

namespace App\Console\Commands;

use App\Course;
use App\Enums\WebReception;
use App\Mail\Course\CourseCloseCheckMail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CourseCloseCheckCommand extends Command
{
    protected $signature = 'course-close-check';
    protected $description = '2ヶ月以内に非公開となるコース情報をお知らせする';

    /**
     * 2ヶ月以内に非公開となるコース情報をお知らせする
     *
     * @return バッチ実行成否
     */
    public function handle()
    {
        // 対象データ取得
//        $receptionCloseCourses = $this->getReceptionCloseCourseData();
        $publishCloseCourses = $this->getCloseCourseData();

//        if (!$receptionCloseCourses && !$publishCloseCourses) {
//            return 1;
//        }

        if (!$publishCloseCourses) {
            return 1;
        }

        // 予約情報をジョブにてメール送信
//        $receptionCloseCourses = $this->createOutputCourseInfo($receptionCloseCourses);
        $publishCloseCourses = $this->createOutputCourseInfo($publishCloseCourses);
        $this->sendCourseCloseCheckMail($publishCloseCourses);
    }

    /**
     * 2ヶ月以内掲載終了コース情報取得
     */
    protected function getCloseCourseData() {

        $fromDate = Carbon::today();
        $toDate = Carbon::today();
        $toDate = $toDate->addMonth(2)->startOfMonth();
        $courseDates = Course::where('publish_end_date', '>=', $fromDate)
            ->where('publish_end_date', '<', $toDate)
            ->orderBy('hospital_id', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return $courseDates;
    }

    /**
     * 2ヶ月以内受付終了コース情報取得
     */
    protected function getReceptionCloseCourseData() {

        $fromDate = Carbon::today();
        $toDate = Carbon::today();
        $toDate = $toDate->addMonthsNoOverflow(2)->startOfMonth();
        $courseDates = Course::where('web_reception', '=', WebReception::ACCEPT)
            ->where('reception_end_date', '>=', $fromDate)
            ->where('reception_end_date', '<', $toDate)
            ->orderBy('hospital_id', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return $courseDates;
    }

    /**
     * メール出力内容を生成して返す
     * @param $courses
     */
    protected function createOutputCourseInfo($courses) {

        $results = [];
        foreach ($courses as $course) {
            $result = [
                'id' => $course->id,
                'hospital_name' => $course->hospital->name,
                'course_name' => $course->name,
                'publish_start_date' => Carbon::parse($course->publish_start_date)->format('Y/m/d'),
                'publish_end_date' => Carbon::parse($course->publish_end_date)->format('Y/m/d'),
            ];

            $results = $result;
        }

        return $results;
    }

    /**
     * WEB受付、掲載終了確認メール送信
     * @param $publishCloseCourses
     */
    public function sendCourseCloseCheckMail($publishCloseCourses)
    {
        $mailContext = [
//            'receptionCloseCourses' => $receptionCloseCourses,
            'publishCloseCourses' => $publishCloseCourses
        ];
        $to = config('mail.to.admin_all');
        Mail::to($to)->send(new CourseCloseCheckMail($mailContext));
    }
}
