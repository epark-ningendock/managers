<?php

namespace App\Console\Commands;

use App\Course;
use App\Enums\WebReception;
use App\Mail\Job\TemporaryReservationCheckMail;
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
        $receptionCloseCourses = $this->getReceptionCloseCourseData();
        $publishCloseCourses = $this->getCloseCourseData();

        if (!$receptionCloseCourses && !$publishCloseCourses) {
            return 1;
        }

        // 予約情報をジョブにてメール送信
        $receptionCloseCourses = $this->createOutputCourseInfo($receptionCloseCourses);
        $publishCloseCourses = $this->createOutputCourseInfo($publishCloseCourses);
        $this->sendCourseCloseCheckMail($receptionCloseCourses, $publishCloseCourses);
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
        $toDate = $toDate->addMonth(2)->startOfMonth();
        $courseDates = Course::where('web_reception', '=', WebReception::Accept)
            ->where('reception_end_date', '>=', $fromDate)
            ->where('reception_end_date', '<', $toDate)
            ->orderBy('hospital_id', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return $courseDates;
    }

    /**
     * メール出力内容を生成して返す
     * @param array $reservationDates
     */
    protected function createOutputCourseInfo(array $values) {

        $results = [];
        foreach ($values as $value) {
            $result = [
                'id' => $value->id,
                'hospital_name' => $value->hospital()->hospital_name,
                'course_name' => $value->name,
                'reception_start_date' => $value->reception_start_date,
                'reception_end_date' => $value->reception_end_date,
                'publish_start_date' => $value->publish_start_date,
                'publish_end_date' => $value->publish_end_date,
            ];

            $results = $result;
        }

        return $results;
    }

    /**
     * 仮受付確認メール送信
     * @param array $reservationDates
     */
    public function sendCourseCloseCheckMail(array $receptionCloseCourses, array $publishCloseCourses)
    {
        $mailContext = [
            'receptionCloseCourses' => $receptionCloseCourses,
            'publishCloseCourses' => $publishCloseCourses
        ];
        $to = config('mail.to.admin_all');
        Mail::to($to)->send(new CourseCloseCheckMail($mailContext));
    }
}
