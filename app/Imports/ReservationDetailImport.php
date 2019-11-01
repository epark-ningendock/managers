<?php

namespace App\Imports;

use App\Reservation;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Row;

class ReservationDetailImport extends ImportBAbstract
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
        $row = $row->toArray();

        $old_id = sprintf('%s_%s',
            $this->hospital_no,
            $this->getValue($row, 'APPOINT_ID')
        );
        $reservation = Reservation::find($this->getIdForA('reservations', $old_id));

        if (is_null($reservation)) {
            Log::error(sprintf('Reservation[APPOINT_ID: %d] is missing.', $this->getValue($row, 'APPOINT_ID')));
            return;
        }

        $reservation->save([
            'channel' => $this->getValue($row, 'TERMINAL_TP'),
            'reservation_status' => $this->getValue($row, 'APPOINT_KBN'),
            'customer_id' => $this->getId('customers', $this->getValue($row, 'CUSTOMER_ID')),
            'epark_member_id' => $this->getValue($row, 'EPARK_MEMBER_ID'),
            'member_number' => $this->getValue($row, 'MEMBER_NO'),
            'terminal_type' => $this->getValue($row, 'TERMINAL_TP'),
            'tax_included_price' => $this->getValue($row, 'COURSE_PRICE_TAX'),
            'adjustment_price' => $this->getValue($row, 'ADJUSTMENTS'),
            'second_date' => $this->getValue($row, 'SECOND_DATE'),
            'third_date' => $this->getValue($row, 'THIRD_DATE'),
            'is_choose' => $this->getValue($row, 'CHOOSE_FG'),
            'campaign_code' => $this->getValue($row, 'CAMPAIGN_CD'),
            'tel_timezone' => $this->getValue($row, 'TEL_TIMEZONE'),
            'insurance_assoc' => $this->getValue($row, 'INSURANCE_ASSOC'),
            'is_payment' => $this->getValue($row, 'PAYMENT_FLG'),
            'y_uid' => $this->getValue($row, 'Y_UID'),
            'applicant_name' => $this->getValue($row, 'LAST_NAME') . $this->getValue($row, 'FIRST_NAME'),
            'applicant_name_kana' => $this->getValue($row, 'LAST_NAME_KANA') . $this->getValue($row, 'FIRST_NAME_KANA'),
            'applicant_tel' => substr(str_replace('-', '', $this->getValue($row, 'TEL_NO')), 0, 11),
        ]);

        $answer_json = str_replace(['\"', '\\\\'], ['"', '\\'], $this->getValue($row, 'Q_ANSWER'));
        $answer_json = str_replace('#comma#', ',', $answer_json);
        $questions = json_decode($answer_json, false, 512, JSON_OBJECT_AS_ARRAY);

        if (count($questions)) {
            dump($questions);
        }


        foreach ((array)$questions as $question) {
            $question_title = $question->question ?? $question->question_title ?? null;
//            $course_questions = $reservation
//                ->course
//                ->course_questions
//                ->where('is_question', 1)
//            ->where('question_title')
//            ->where('question_number');

//            if ($course_questions->count()) {
//                dump($course_questions->count());
//            }

//            if ($course_questions->count() === 1) {
//
//                $reservation->reservation_answers()->save(
//                    new ReservationAnswer([
//                        'question_title' => $question->question_title,
//                        'course_id' => $reservation->course->id,
//                        'course_question_id' => 1,
//                        'question_answer01' => $question->answer[0],
//                        'question_answer02' => $question->answer[1],
//                        'question_answer03' => $question->answer[2],
//                        'question_answer04' => $question->answer[3],
//                        'question_answer05' => $question->answer[4],
//                        'question_answer06' => $question->answer[5],
//                        'question_answer07' => $question->answer[6],
//                        'question_answer08' => $question->answer[7],
//                        'question_answer09' => $question->answer[8],
//                        'question_answer10' => $question->answer[9],
//                    ])
//                );
//
//            }
        }

//        $reservation->save();
    }
}
