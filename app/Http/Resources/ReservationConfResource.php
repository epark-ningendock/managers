<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ReservationConfResource extends JsonResource
{
    /**
     * 予約情報 resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // 予約日
        $reservation_date = $this->reservation_date;

        // キャンセル受付変更期限（日）
        $cancellation_deadline = intval($this->course->cancellation_deadline);

        // キャンセル可能日
        $cancellation_date = Carbon::create($reservation_date)->addDay($cancellation_deadline);
        $cancellation_date = date('Y/m/d', strtotime($cancellation_date));

        // 町村字番地/建物名分割
        $pieces = explode(' ', $this->customer->address2);

        return collect([])
            ->put('state', 0)
            ->put('result_code', $this->result_code)
            ->put('reservation_status', $this->reservation_status)
            ->put('course_id', $this->course_id)
            ->put('course_name', $this->course->name)
            ->put('reservation_date', $this->reservation_date)
            ->put('start_time_hour', $this->start_time_hour)
            ->put('start_time_min', $this->start_time_min)
            ->put('end_time_hour', $this->end_time_hour)
            ->put('end_time_min', $this->end_time_min)

            ->put('last_name', $this->customer->family_name)
            ->put('first_name', $this->customer->first_name)
            ->put('last_name_kana', $this->customer->family_name_kana)
            ->put('first_name_kana', $this->customer->first_name_kana)
            ->put('birthday', $this->customer->birthday)
            ->put('sex', $this->customer->sex)
            ->put('email', $this->customer->email)
            ->put('tel_no', $this->customer->tel)
            ->put('post_code', $this->customer->postcode)
            ->put('prov_code', $this->customer->prefecture_id)
            ->put('city', $this->customer->address1)
            ->put('district', $pieces[0])
            ->put('building_name', $pieces[1])
            ->put('registration_card_num', $this->customer->registration_card_number)

            ->put('user_message', $this->user_message)
            ->put('site_code', $this->site_code)
            ->put('epark_member_id', $this->epark_member_id)
            ->put('regist_member', $this->_regist_members($this))
            ->put('payment_flg', $this->is_payment)
            ->put('payment_status', $this->payment_status)
            ->put('trade_id', $this->trade_id)
            ->put('order_id', $this->order_id)
            ->put('card_payment_amount', $this->settlement_price)
            ->put('payment_method', $this->payment_method)
            ->put('cashpo_used_amount', $this->cashpo_used_price)
            ->put('amount_unsettled', $this->amount_unsettled)
            ->put('cancellation_deadline', $cancellation_date)
            ->put('facility_name', $this->hospital->name)
            ->put(
                'facility_addr',
                $this->hospital->district_code->prefecture->name . ' ' .
                    $this->hospital->district_code->name . ' ' .
                    $this->hospital->address1 . ' ' . $this->hospital->address2
            )
            ->put('facility_tel', $this->hospital->tel)
            ->put('course_price_tax', $this->course->price)
            ->put('option_array', $this->_reservation_options($this->reservation_options))
            ->put(
                'other_info',
                collect([
                    'second_date' => $this->second_date,
                    'third_date' => $this->third_date,
                    'choose_fg' => $this->is_choose,
                    'campaign_cd' => $this->campaign_code,
                    'tel_timezone' => $this->tel_timezone,
                    'insurance_assoc' => $this->insurance_assoc,
                ])
            )
            ->put('q_anser', $this->_reservation_answers($this->reservation_answers))
            ->toArray();
    }

    /**
     * 受診者情報要素追加
     *
     * @param  予約情報  $reservation
     * @return 受診者情報 
     */
    private function _regist_members($reservation)
    {
        $birthday = isset($reservation->customer->birthday) ?
            date('Y/m/d', strtotime($reservation->customer->birthday)) : '';
        $regist_members = collect([
            'last_name' => $reservation->customer->family_name ?? '',
            'first_name' => $reservation->customer->first_name ?? '',
            'last_name_kana' => $reservation->customer->family_name_kana ?? '',
            'first_name_kana' => $reservation->customer->first_name_kana ?? '',
            'birthday' => $birthday,
            'sex' => $reservation->customer->sex,
            'repeat_fg' => $reservation->is_repeat,
            'representative_fg' => $reservation->is_representative,
            'email' => $reservation->customer->email,
            'customer_id' => $reservation->customer->id,
            'reservation_accepted_date' => date('Y/m/d H:i:s', strtotime($reservation->created_at)),
        ]);
        return $regist_members;
    }

    /**
     * 予約オプション要素追加
     *
     * @param  予約オプション情報  $reservation_options
     * @return 予約オプション情報
     */
    private function _reservation_options($reservation_options)
    {
        $options = $reservation_options->map(function ($o) {
            if (!isset($o->option)) return;
            return [
                'option_cd' => $o->option->id ?? '',
                'option_name' => $o->option->name ?? '',
                'option_price_tax' => $o->option_price ?? '',
            ];
        });
        return $options;
    }

    /**
     * 予約回答要素追加
     *
     * @param  予約回答情報  $reservation_answers
     * @return 予約回答情報
     */
    private function _reservation_answers($reservation_answers)
    {
        $reservation_answers = $reservation_answers->map(function ($a) {
            $answers = [
                ['answer_title' => $a->question_answer01 ?? '', 'answer' => $a->answer01 ?? '',],
                ['answer_title' => $a->question_answer02 ?? '', 'answer' => $a->answer02 ?? '',],
                ['answer_title' => $a->question_answer03 ?? '', 'answer' => $a->answer03 ?? '',],
                ['answer_title' => $a->question_answer04 ?? '', 'answer' => $a->answer04 ?? '',],
                ['answer_title' => $a->question_answer05 ?? '', 'answer' => $a->answer05 ?? '',],
                ['answer_title' => $a->question_answer06 ?? '', 'answer' => $a->answer06 ?? '',],
                ['answer_title' => $a->question_answer07 ?? '', 'answer' => $a->answer07 ?? '',],
                ['answer_title' => $a->question_answer08 ?? '', 'answer' => $a->answer08 ?? '',],
                ['answer_title' => $a->question_answer09 ?? '', 'answer' => $a->answer09 ?? '',],
                ['answer_title' => $a->question_answer10 ?? '', 'answer' => $a->answer10 ?? '',],
            ];
            // array->object->collection
            $answers = collect(json_decode(json_encode($answers)))->filter(function ($q) {
                return isset($q) && $q->answer_title !== '';
            });
            return [
                'question_title' => $a->question_title ?? '',
                'answers' => $answers,
            ];
        });
        return $reservation_answers;
    }
}