<?php

namespace App\Http\Requests;

class ReservationStoreRequest extends ReservationRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $flg = $this->input('payment_flg');

        return parent::rules() + [
            'process_kbn' => 'required|numeric|in:0,1', // charで0か1か
            'course_id' => 'required|numeric|exists:courses,id',
            'reservation_id' => 'required_if:process_kbn,1',
            'reservation_date' => 'required|date_format:"Y-m-d"',

            'last_name' => 'required|string|max:32',
            'first_name' => 'required|string|max:32',
            'last_name_kana' => 'string|max:32',
            'first_name_kana' => 'string|max:32',
            'birthday' => 'required|string|date_format:"Y-m-d"',
            'sex' => 'required|in:M,F',
            'email' => 'required|email',
            'tel_no' => 'string|min:9|max:15',
            'post_code' => 'regex:/^\d{3}-\d{4}$/',
            'prov_code' => 'numeric|digits:2|exists:prefectures,id',
            'city' => 'string|max:64',
            'district' => 'string|max:64',
            'building_name' => 'string|max:64',
            'registration_card_num' => 'string|max:32',
            'user_message' => 'string|max:255',
            'site_code' => 'string|size:8',
            'epark_member_id' => '',
            'customer_id' => 'exists:customers,id',
            'terminal_tp' => 'required|in:2,3',
            'time_selected' => 'numeric|in:0,1',

            'regist_member.*.last_name' => 'required|string|max:32',
            'regist_member.*.first_name' => 'required|string|max:32',
            'regist_member.*.representative_fg' => 'required|in:0,1',
            
            'facility_name' => 'string|max:200',
            'facility_addr' => 'string|max:256',
            'facility_tel' => 'string|max:30',
            'course_price_tax' => 'numeric',

            'option_array.*.option_cd' => 'numeric',
            'option_array.*.option_name' => 'string|max:40',
            'option_array.*.option_price_tax' => 'numeric',

            'other_info.second_date' => 'string|date_format:"Y-m-d"',
            'other_info.third_date' => 'string|date_format:"Y-m-d"',
            'other_info.choose_fg' => 'numeric|in:0,1',
            'other_info.campaign_cd' => 'string|max:50',
            'other_info.tel_timezone' => 'numeric|in:1,2,4,8',
            'other_info.insurer_assoc' => 'string|max:255',
            'other_info.insurer_number' => 'string|max:255',
            'other_info.insurance_card_symbol' => 'string|max:255',
            'other_info.insurance_card_number' => 'string|max:255',
            'other_info.office_name' => 'string|max:255',
            
            'payment_flg' => 'in:0,1',
            'payment_status' => $flg == '1' ? 'required|string' : 'string',
            'trade_id' => $flg == '1' ? 'required|string' : 'string',
            'order_id' => 'numeric',
            'card_payment_amount' => 'numeric',
            'payment_method' => $flg == '1' ? 'required|string' : 'string',
            'cashpo_used_amount' => 'numeric',
            'amount_unsettled' => 'numeric',
        ];
    }
}
