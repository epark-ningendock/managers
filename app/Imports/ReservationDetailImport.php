<?php

namespace App\Imports;

use App\CourseQuestion;
use App\Enums\Status;
use App\Hospital;
use App\OldOption;
use App\Reservation;
use App\ReservationAnswer;
use App\ReservationOption;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Row;

class ReservationDetailImport extends ImportBAbstract implements WithChunkReading
{

    /**
     * 旧システムのインポート対象テーブルのプライマリーキーを返す
     * Returns the primary key of the import target table of the old system
     * @return string
     */
    public function getOldPrimaryKeyName(): string
    {
        return 'ID';
    }

    /**
     * 新システムの対象クラス名を返す
     * Returns the target class name of the new system
     * @return string
     */
    public function getNewClassName(): string
    {
        return Reservation::class;
    }

    /**
     * @param Row $row
     * @return mixed
     */
    public function onRow(Row $row)
    {
        try {

            $row = $row->toArray();

            $hospital = Hospital::query()->where('old_karada_dog_id', $this->hospital_no)
                ->first();

            if (is_null($hospital)) {
                Log::error(sprintf('Hospitals に %s が存在しません。', $this->hospital_no));
                return;
            }

            $reservation_id = $this->getId('reservations', $this->getValue($row, 'APPOINT_ID'));
            $customer_id = $this->getId('customers', $this->getValue($row, 'CUSTOMER_ID'));
            $course_id = $this->getId('courses', $this->getValue($row, 'LINEGROUP_ID'));
            $reservation = Reservation::find($reservation_id);

//            $comp_flg = $this->getValue($row, 'ACCEPTANCE_COMPLETE_FG');
//            $comp_date = null;
//            if ($comp_flg == 1) {
//                $comp_date = Carbon::today();
//            }
//            $cancel_flg = $this->getValue($row, 'CANCEL_FG');
//            $cancel_date = null;
//            if ($cancel_flg == 1) {
//                $cancel_date = Carbon::today();
//            }
//
//            $fee = 0;
//            $fee_rate = 0;
//            $member_id = $this->getValue($row, 'EPARK_MEMBER_ID');
//            if (isset($member_id)) {
//                if (isset($hospital->hospital_plan) && isset($hospital->hospital_plan->contract_plan)) {
//                    $fee_rate = $hospital->hospital_plan->contract_plan->fee_rate;
//                } else {
//                    $fee_rate = 10;
//                }
//                $course_price = intval($this->getValue($row, 'COURSE_PRICE_TAX'));
//                $option_price = intval($this->getValue($row, 'OPTION_PRICE_TAX'));
//                $adjusts = intval($this->getValue($row, 'ADJUSTMENTS'));
//                $fee = floor(($course_price + $option_price + $adjusts) * ($fee_rate / 100));
//            }
//
//            $memo = '';
//            if (!empty($this->getValue($row, 'MEMO_APPOINT'))) {
//                $memo = mb_substr($this->getValue($row, 'MEMO_APPOINT'), 0, 250);
//            }
//
//            $reservation->hospital_id = $hospital->id;
//            $reservation->course_id = $course_id;
//            $reservation->channel = $this->getValue($row, 'TERMINAL_TP');
//            $reservation->reservation_status = $this->getValue($row, 'APPOINT_KBN');
//            $reservation->completed_date = $comp_date;
//            $reservation->cancel_date = $cancel_date;
//            $reservation->user_message = $memo;
//            $reservation->site_code = $this->getValue($row, 'SITE_CODE');
//            $reservation->customer_id = $customer_id;
//            $reservation->epark_member_id = $this->getValue($row, 'EPARK_MEMBER_ID');
//            $reservation->member_number = $this->getValue($row, 'MEMBER_NO');
//            $reservation->terminal_type = $this->getValue($row, 'TERMINAL_TP');
//            $reservation->tax_included_price = $this->getValue($row, 'COURSE_PRICE_TAX');
//            $reservation->adjustment_price = $this->getValue($row, 'ADJUSTMENTS');
//
//            $reservation->is_choose = $this->getValue($row, 'CHOOSE_FG');
//            $reservation->campaign_code = $this->getValue($row, 'CAMPAIGN_CD');
//            $reservation->tel_timezone = $this->getValue($row, 'TEL_TIMEZONE');
//            $reservation->insurance_assoc = $this->getValue($row, 'INSURANCE_ASSOC');
//            $reservation->is_payment = $this->getValue($row, 'PAYMENT_FLG');
//            $reservation->reservation_memo = $memo;
//            $reservation->todays_memo = $this->getValue($row, 'MEMO_NOW');
//            $reservation->internal_memo = $this->getValue($row, 'MEMO_NOW');
//            $reservation->y_uid = $this->getValue($row, 'Y_UID');
//            $reservation->applicant_name = $this->getValue($row, 'LAST_NAME') . $this->getValue($row, 'FIRST_NAME');
//            $reservation->applicant_name_kana = $this->getValue($row, 'LAST_NAME_KANA') . $this->getValue($row,
//                    'FIRST_NAME_KANA');
//            $reservation->applicant_tel = substr(str_replace('-', '', $this->getValue($row, 'TEL_NO')), 0, 11);
//            $reservation->fee_rate = $fee_rate;
//            $reservation->fee = $fee;
//            $reservation->is_free_hp_link = 0;
//            $reservation->is_health_insurance = 0;
//            $reservation->save();

//            $answer_json = str_replace(['\"', '\\\\'], ['"', '\\'], $this->getValue($row, 'Q_ANSWER'));
//            $answer_json = str_replace('#comma#', ',', $answer_json);
//            $questions = json_decode($answer_json, false, 512, JSON_OBJECT_AS_ARRAY);

            if (!empty($reservation->course_id)) {
                $target = $this->getValue($row, 'Q_ANSWER');
                $target = str_replace('[', '', $target);
                $target = str_replace('{', '', $target);
                $target = str_replace('\"', '', $target);
                $tmp_strs = explode('#comma#', $target);
                $tmp_answers = [];
                $tim_ans = [];
                $tmp_title = '';
                foreach ($tmp_strs as $k => $tmp_str) {
                    if (strpos($tmp_str, 'question_title') !== false) {
                        $tmp_title = str_replace('question_title', '', $tmp_str);
                        $tmp_title = str_replace(':', '', $tmp_title);
                        if ($k != 0) {
                            $tmp_answers[] = [$tmp_title, $tim_ans];
                            $tim_ans = [];
                        }
                    } else {
                        $tim_ans[] = $tmp_str;
                    }
                }

                $tmp_answers[] = [$tmp_title, $tim_ans];

                foreach ($tmp_answers as $tmp_answer) {

                    if (empty($tmp_answer[0])) {
                        continue;
                    }

                    $course_questions = CourseQuestion::where('course_id', $course_id)
                        ->get();
                    if (is_null($course_questions) || count($course_questions) == 0) {
                        Log::error('reservation に course_questionsレコードが存在しません。');
                    }

                    foreach ($course_questions as $c) {
                        $target = mb_substr($tmp_answer[0], 0, 3);
                        Log::error('コース質問あり');
                        if (strpos($c->question_title, $target) !== false) {
                            Log::error('コース質問マッチング');
                            $reservation_answers = new ReservationAnswer();
                            $reservation_answers->question_title = $tmp_answer[0];
                            $reservation_answers->course_id = $course_id;
                            $reservation_answers->course_question_id = $c->id;

                            $regist_flg = false;
                            foreach ($tmp_answer as $i => $ans) {
                                $tmp_a = explode('|', $ans);
                                if (count($tmp_a) < 2) {
                                    continue;
                                }
                                $a = 0;
                                if (strpos($tmp_a[0], '1')) {
                                    $a = 1;
                                }

                                $reservation_answers->{'question_answer' . sprintf('%02d', $i)} = $tmp_a[1];
                                $reservation_answers->{'answer' . sprintf('%02d', $i)} = $a;
                                $regist_flg = true;
                            }
                            if ($regist_flg) {
                                $reservation_answers->save();
                            }
                        }
                    }

                }

            } else {
                Log::error('reservation がありません。。');
            }

//            $options = explode('|', $this->getValue($row, 'OPTION_CD'));
//            $option_prices = explode('|', $this->getValue($row, 'OPTION_PRICE_TAX'));
//
//            for ($i = 0; $i < count($options); $i++) {
//
//                $hospital_no = trim($this->hospital_no);
//                $option_cd = $options[$i];
//                $option_group_cd = $this->getValue($row, 'OPTION_GROUP_CD');
//
//                $old_option = OldOption::query()->where('hospital_no', $hospital_no)
//                    ->where('option_cd', $option_cd)
//                    ->where('option_group_cd', $option_group_cd)
//                    ->first();
//                if (!$old_option) {
//                    continue;
//                }
//                $reservation_option = new ReservationOption();
//                $reservation_option->reservation_id = $reservation->id;
//                $reservation_option->option_id = $old_option->option_id;
//                $reservation_option->option_price = $option_prices[$i];
//                $reservation_option->status = Status::VALID;
//                $reservation_option->save();
//
//            }

        } catch (\Throwable $e) {
            Log::error($e->getMessage());
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
