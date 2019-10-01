<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Log;
use function GuzzleHttp\json_encode;

class ReservationRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 医療機関ID
            'hospital_id' => 'required|numeric|exists:hospitals,id',
        ];
    }

    public function messages()
    {
        return [
            'hospital_id.required' => json_encode([
                'message' => trans('validation.for_api.hospital_id.required'),
                'error_no' => '01',
                'detail_code' => '01',
            ]),
            'hospital_id.numeric' => json_encode([
                'message' => trans('validation.for_api.hospital_id.numeric'),
                'error_no' => '01',
                'detail_code' => '02',
            ]),
            'hospital_id.exists' => json_encode([
                'message' => trans('validation.for_api.hospital_id.exists'),
                'error_no' => '01',
                'detail_code' => '03',
            ]),
            'appoint_id.required' => json_encode([
                'message' => trans('validation.for_api.appoint_id.required'),
                'error_no' => '02',
                'detail_code' => '11',
            ]),

            'appoint_id.numeric' => json_encode([
                'message' => trans('validation.for_api.appoint_id.numeric'),
                'error_no' => '02',
                'detail_code' => '12',
            ]),
            'appoint_id.exists' => json_encode([
                'message' => trans('validation.for_api.appoint_id.exists'),
                'error_no' => '02',
                'detail_code' => '13',
            ]),
            'reservation_id.required' => json_encode([
                'message' => trans('validation.for_api.reservation_id.required'),
                'error_no' => '02',
                'detail_code' => '11',
            ]),
            'reservation_id.numeric' => json_encode([
                'message' => trans('validation.for_api.reservation_id.numeric'),
                'error_no' => '02',
                'detail_code' => '12',
            ]),
            'reservation_id.exists' => json_encode([
                'message' => trans('validation.for_api.reservation_id.exists'),
                'error_no' => '02',
                'detail_code' => '13',
            ]),
            'mail_fg.required' => json_encode([
                'message' => trans('validation.for_api.mail_fg.required'),
                'error_no' => '03',
                'detail_code' => '11',
            ]),
            'mail_fg.numeric' => json_encode([
                'message' => trans('validation.for_api.mail_fg.numeric'),
                'error_no' => '03',
                'detail_code' => '12',
            ]),
            'process_kbn.required' => json_encode([
                'message' => trans('validation.for_api.process_kbn.required'),
                'error_no' => '02',
                'detail_code' => '01',
            ]),
            'process_kbn.numeric' => json_encode([
                'message' => trans('validation.for_api.process_kbn.numeric'),
                'error_no' => '02',
                'detail_code' => '02',
            ]),
            'process_kbn.in' => json_encode([
                'message' => trans('validation.for_api.process_kbn.in'),
                'error_no' => '02',
                'detail_code' => '02',
            ]),
            'course_id.required' => json_encode([
                'message' => trans('validation.for_api.course_id.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'course_id.numeric' => json_encode([
                'message' => trans('validation.for_api.course_id.numeric'),
                'error_no' => '03',
                'detail_code' => '02',
            ]),
            'course_id.exists' => json_encode([
                'message' => trans('validation.for_api.course_id.exists'),
                'error_no' => '03',
                'detail_code' => '03',
            ]),
            'reservation_date.required' => json_encode([
                'message' => trans('validation.for_api.reservation_date.required'),
                'error_no' => '04',
                'detail_code' => '01',
            ]),
            'reservation_date.date' => json_encode([
                'message' => trans('validation.for_api.reservation_date.date'),
                'error_no' => '04',
                'detail_code' => '02',
            ]),
            'last_name.required' => json_encode([
                'message' => trans('validation.for_api.last_name.required'),
                'error_no' => '05',
                'detail_code' => '01',
            ]),
            'last_name.string' => json_encode([
                'message' => trans('validation.for_api.last_name.string'),
                'error_no' => '05',
                'detail_code' => '02',
            ]),
            'last_name.max' => json_encode([
                'message' => trans('validation.for_api.last_name.max'),
                'error_no' => '05',
                'detail_code' => '02',
            ]),
            'first_name.required' => json_encode([
                'message' => trans('validation.for_api.first_name.required'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            'first_name.string' => json_encode([
                'message' => trans('validation.for_api.first_name.string'),
                'error_no' => '06',
                'detail_code' => '02',
            ]),
            'first_name.max' => json_encode([
                'message' => trans('validation.for_api.first_name.max'),
                'error_no' => '06',
                'detail_code' => '02',
            ]),
            'last_name_kana.string' => json_encode([
                'message' => trans('validation.for_api.last_name_kana.string'),
                'error_no' => '07',
                'detail_code' => '01',
            ]),
            'last_name_kana.max' => json_encode([
                'message' => trans('validation.for_api.last_name_kana.max'),
                'error_no' => '07',
                'detail_code' => '01',
            ]),
            'first_name_kana.string' => json_encode([
                'message' => trans('validation.for_api.first_name_kana.string'),
                'error_no' => '08',
                'detail_code' => '01',
            ]),
            'first_name_kana.max' => json_encode([
                'message' => trans('validation.for_api.first_name_kana.max'),
                'error_no' => '08',
                'detail_code' => '01',
            ]),
            'birthday.required' => json_encode([
                'message' => trans('validation.for_api.birthday.required'),
                'error_no' => '09',
                'detail_code' => '01',
            ]),
            'birthday.string' => json_encode([
                'message' => trans('validation.for_api.birthday.string'),
                'error_no' => '09',
                'detail_code' => '02',
            ]),
            'birthday.date_format' => json_encode([
                'message' => trans('validation.for_api.birthday.date_format'),
                'error_no' => '09',
                'detail_code' => '02',
            ]),
            'sex.required' => json_encode([
                'message' => trans('validation.for_api.sex.required'),
                'error_no' => '10',
                'detail_code' => '01',
            ]),
            'sex.in' => json_encode([
                'message' => trans('validation.for_api.sex.in'),
                'error_no' => '10',
                'detail_code' => '02',
            ]),
            'email.required' => json_encode([
                'message' => trans('validation.for_api.email.required'),
                'error_no' => '11',
                'detail_code' => '01',
            ]),
            'email.email' => json_encode([
                'message' => trans('validation.for_api.email.email'),
                'error_no' => '11',
                'detail_code' => '02',
            ]),
            'tel_no.string' => json_encode([
                'message' => trans('validation.for_api.tel_no.string'),
                'error_no' => '12',
                'detail_code' => '01',
            ]),
            'tel_no.min' => json_encode([
                'message' => trans('validation.for_api.tel_no.min'),
                'error_no' => '12',
                'detail_code' => '01',
            ]),
            'tel_no.max' => json_encode([
                'message' => trans('validation.for_api.tel_no.max'),
                'error_no' => '12',
                'detail_code' => '01',
            ]),
            'post_code.regex' => json_encode([
                'message' => trans('validation.for_api.post_code.regex'),
                'error_no' => '13',
                'detail_code' => '01',
            ]),
            'prov_code.numeric' => json_encode([
                'message' => trans('validation.for_api.prov_code.numeric'),
                'error_no' => '14',
                'detail_code' => '01',
            ]),
            'prov_code.digits' => json_encode([
                'message' => trans('validation.for_api.prov_code.digits'),
                'error_no' => '14',
                'detail_code' => '01',
            ]),
            'prov_code.exists' => json_encode([
                'message' => trans('validation.for_api.prov_code.exists'),
                'error_no' => '14',
                'detail_code' => '01',
            ]),

            'city.string' => json_encode([
                'message' => trans('validation.for_api.city.string'),
                'error_no' => '15',
                'detail_code' => '01',
            ]),
            'city.max' => json_encode([
                'message' => trans('validation.for_api.city.max'),
                'error_no' => '15',
                'detail_code' => '01',
            ]),
            'district.string' => json_encode([
                'message' => trans('validation.for_api.district.string'),
                'error_no' => '16',
                'detail_code' => '01',
            ]),
            'district.max' => json_encode([
                'message' => trans('validation.for_api.district.max'),
                'error_no' => '16',
                'detail_code' => '01',
            ]),
            'building_name.string' => json_encode([
                'message' => trans('validation.for_api.building_name.string'),
                'error_no' => '17',
                'detail_code' => '01',
            ]),
            'building_name.max' => json_encode([
                'message' => trans('validation.for_api.building_name.max'),
                'error_no' => '17',
                'detail_code' => '01',
            ]),
            'registration_card_num.string' => json_encode([
                'message' => trans('validation.for_api.registration_card_num.string'),
                'error_no' => '18',
                'detail_code' => '01',
            ]),
            'registration_card_num.max' => json_encode([
                'message' => trans('validation.for_api.registration_card_num.max'),
                'error_no' => '18',
                'detail_code' => '01',
            ]),
            'user_message.string' => json_encode([
                'message' => trans('validation.for_api.user_message.string'),
                'error_no' => '19',
                'detail_code' => '01',
            ]),
            'user_message.max' => json_encode([
                'message' => trans('validation.for_api.user_message.max'),
                'error_no' => '19',
                'detail_code' => '01',
            ]),
            'site_code.string' => json_encode([
                'message' => trans('validation.for_api.site_code.string'),
                'error_no' => '99',
                'detail_code' => '99',
            ]),
            'site_code.size' => json_encode([
                'message' => trans('validation.for_api.site_code.size'),
                'error_no' => '99',
                'detail_code' => '99',
            ]),
            'terminal_tp.required' => json_encode([
                'message' =>  trans('validation.for_api.terminal_tp.required'),
                'error_no' => '20',
                'detail_code' => '01',
            ]),
            'terminal_tp.in' => json_encode([
                'message' => trans('validation.for_api.terminal_tp.in'),
                'error_no' => '20',
                'detail_code' => '02',
            ]),
            'time_selected.numeric' => json_encode([
                'message' => trans('validation.for_api.time_selected.numeric'),
                'error_no' => '21',
                'detail_code' => '01',
            ]),
            'time_selected.in' => json_encode([
                'message' => trans('validation.for_api.time_selected.in'),
                'error_no' => '21',
                'detail_code' => '01',
            ]),
            'regist_member.required' => json_encode([
                'message' => trans('validation.for_api.regist_member.required'),
                'error_no' => '22',
                'detail_code' => '01',
            ]),
            'regist_member.*.last_name.required' => json_encode([
                'message' => trans('validation.for_api.regist_member.last_name.required'),
                'error_no' => '22',
                'detail_code' => '02',
            ]),
            'regist_member.*.last_name.string' => json_encode([
                'message' => trans('validation.for_api.regist_member.last_name.string'),
                'error_no' => '22',
                'detail_code' => '03',
            ]),
            'regist_member.*.last_name.max' => json_encode([
                'message' => trans('validation.for_api.regist_member.last_name.max'),
                'error_no' => '22',
                'detail_code' => '03',
            ]),
            'regist_member.*.first_name.required' => json_encode([
                'message' => trans('validation.for_api.regist_member.first_name.required'),
                'error_no' => '22',
                'detail_code' => '04',
            ]),
            'regist_member.*.first_name.string' => json_encode([
                'message' => trans('validation.for_api.regist_member.first_name.string'),
                'error_no' => '22',
                'detail_code' => '05',
            ]),
            'regist_member.*.first_name.max' => json_encode([
                'message' => trans('validation.for_api.regist_member.first_name.max'),
                'error_no' => '22',
                'detail_code' => '05',
            ]),
            'regist_member.*.last_name_kana.string' => json_encode([
                'message' => trans('validation.for_api.regist_member.last_name_kana.string'),
                'error_no' => '22',
                'detail_code' => '06',
            ]),
            'regist_member.*.last_name_kana.max' => json_encode([
                'message' => trans('validation.for_api.regist_member.last_name_kana.max'),
                'error_no' => '22',
                'detail_code' => '06',
            ]),
            'regist_member.*.first_name_kana.string' => json_encode([
                'message' => trans('validation.for_api.regist_member.first_name_kana.string'),
                'error_no' => '22',
                'detail_code' => '07',
            ]),
            'regist_member.*.first_name_kana.max' => json_encode([
                'message' => trans('validation.for_api.regist_member.first_name_kana.max'),
                'error_no' => '22',
                'detail_code' => '07',
            ]),
            'regist_member.*.birthday.string' => json_encode([
                'message' => trans('validation.for_api.regist_member.birthday.string'),
                'error_no' => '22',
                'detail_code' => '08',
            ]),
            'regist_member.*.birthday.date_format' => json_encode([
                'message' => trans('validation.for_api.regist_member.birthday.date_format'),
                'error_no' => '22',
                'detail_code' => '08',
            ]),
            'regist_member.*.sex.string' => json_encode([
                'message' => trans('validation.for_api.regist_member.sex.string'),
                'error_no' => '22',
                'detail_code' => '09',
            ]),
            'regist_member.*.sex.in' => json_encode([
                'message' => trans('validation.for_api.regist_member.sex.in'),
                'error_no' => '22',
                'detail_code' => '09',
            ]),
            'regist_member.*.representative_fg.required' => json_encode([
                'message' => trans('validation.for_api.regist_member.representative_fg.required'),
                'error_no' => '22',
                'detail_code' => '10',
            ]),
            'regist_member.*.representative_fg.in' => json_encode([
                'message' => trans('validation.for_api.regist_member.representative_fg.in'),
                'error_no' => '22',
                'detail_code' => '11',
            ]),
            'facility_name.string' => json_encode([
                'message' => trans('validation.for_api.facility_name.string'),
                'error_no' => '24',
                'detail_code' => '01',
            ]),
            'facility_name.max' => json_encode([
                'message' => trans('validation.for_api.facility_name.max'),
                'error_no' => '24',
                'detail_code' => '01',
            ]),
            'facility_addr.string' => json_encode([
                'message' =>  trans('validation.for_api.facility_addr.string'),
                'error_no' => '25',
                'detail_code' => '01',
            ]),
            'facility_addr.max' => json_encode([
                'message' => trans('validation.for_api.facility_addr.max'),
                'error_no' => '25',
                'detail_code' => '01',
            ]),
            'facility_tel.string' => json_encode([
                'message' => trans('validation.for_api.facility_tel.string'),
                'error_no' => '26',
                'detail_code' => '01',
            ]),
            'facility_tel.max' => json_encode([
                'message' => trans('validation.for_api.facility_tel.max'),
                'error_no' => '26',
                'detail_code' => '01',
            ]),
            'course_price_tax.numeric' => json_encode([
                'message' => trans('validation.for_api.fcourse_price_tax.numeric'),
                'error_no' => '27',
                'detail_code' => '01',
            ]),

            'option_array.*.option_cd.numeric' => json_encode([
                'message' => trans('validation.for_api.option_array.option_cd.numeric'),
                'error_no' => '28',
                'detail_code' => '01',
            ]),
            'option_array.*.option_name.string' => json_encode([
                'message' => trans('validation.for_api.option_array.option_name.string'),
                'error_no' => '28',
                'detail_code' => '02',
            ]),
            'option_array.*.option_name.max' => json_encode([
                'message' => trans('validation.for_api.option_array.option_name.max'),
                'error_no' => '28',
                'detail_code' => '02',
            ]),
            'option_array.*.option_price_tax.numeric' => json_encode([
                'message' => trans('validation.for_api.option_array.option_price_tax.numeric'),
                'error_no' => '28',
                'detail_code' => '03',
            ]),

            'other_info.second_date.string' => json_encode([
                'message' => trans('validation.for_api.other_info.second_date.string'),
                'error_no' => '29',
                'detail_code' => '01',
            ]),
            'other_info.second_date.date_format' => json_encode([
                'message' => trans('validation.for_api.other_info.second_date.date_format'),
                'error_no' => '29',
                'detail_code' => '01',
            ]),
            'other_info.third_date.string' => json_encode([
                'message' => trans('validation.for_api.other_info.third_date.string'),
                'error_no' => '30',
                'detail_code' => '01',
            ]),
            'other_info.third_date.date_format' => json_encode([
                'message' => trans('validation.for_api.other_info.third_date.date_format'),
                'error_no' => '30',
                'detail_code' => '01',
            ]),
            'other_info.choose_fg.numeric' => json_encode([
                'message' => trans('validation.for_api.other_info.choose_fg.numeric'),
                'error_no' => '31',
                'detail_code' => '01',
            ]),
            'other_info.choose_fg.in' => json_encode([
                'message' => trans('validation.for_api.other_info.choose_fg.in'),
                'error_no' => '31',
                'detail_code' => '01',
            ]),
            'other_info.campaign_cd.string' => json_encode([
                'message' => trans('validation.for_api.other_info.campaign_cd.string'),
                'error_no' => '32',
                'detail_code' => '01',
            ]),
            'other_info.campaign_cd.max' => json_encode([
                'message' => trans('validation.for_api.other_info.campaign_cd.max'),
                'error_no' => '32',
                'detail_code' => '01',
            ]),
            'other_info.tel_timezone.numeric' => json_encode([
                'message' => trans('validation.for_api.other_info.tel_timezone.numeric'),
                'error_no' => '33',
                'detail_code' => '01',
            ]),
            'other_info.tel_timezone.in' => json_encode([
                'message' => trans('validation.for_api.other_info.tel_timezone.in'),
                'error_no' => '33',
                'detail_code' => '01',
            ]),
            'other_info.insurer_assoc.string' => json_encode([
                'message' => trans('validation.for_api.other_info.insurer_assoc.string'),
                'error_no' => '34',
                'detail_code' => '01',
            ]),
            'other_info.insurer_assoc.max' => json_encode([
                'message' => trans('validation.for_api.other_info.insurer_assoc.max'),
                'error_no' => '34',
                'detail_code' => '01',
            ]),
            'other_info.insurer_number.string' => json_encode([
                'message' => trans('validation.for_api.other_info.insurer_number.string'),
                'error_no' => '35',
                'detail_code' => '01',
            ]),
            'other_info.insurer_number.max' => json_encode([
                'message' => trans('validation.for_api.other_info.insurer_number.max'),
                'error_no' => '35',
                'detail_code' => '01',
            ]),
            'other_info.insurance_card_symbol.string' => json_encode([
                'message' => trans('validation.for_api.other_info.insurance_card_symbol.string'),
                'error_no' => '36',
                'detail_code' => '01',
            ]),
            'other_info.insurance_card_symbol.max' => json_encode([
                'message' => trans('validation.for_api.other_info.insurance_card_symbol.max'),
                'error_no' => '36',
                'detail_code' => '01',
            ]),
            'other_info.insurance_card_number.string' => json_encode([
                'message' => trans('validation.for_api.other_info.insurance_card_number.string'),
                'error_no' => '37',
                'detail_code' => '01',
            ]),
            'other_info.insurance_card_number.max' => json_encode([
                'message' => trans('validation.for_api.other_info.insurance_card_number.max'),
                'error_no' => '37',
                'detail_code' => '01',
            ]),
            'other_info.office_name.string' => json_encode([
                'message' => trans('validation.for_api.other_info.office_name.string'),
                'error_no' => '38',
                'detail_code' => '01',
            ]),
            'other_info.office_name.max' => json_encode([
                'message' => trans('validation.for_api.other_info.office_name.max'),
                'error_no' => '38',
                'detail_code' => '01',
            ]),
            // 質問回答
            'q_anser.question_title.string' => json_encode([
                'message' => trans('validation.for_api.q_anser.question_title.string'),
                'error_no' => '39',
                'detail_code' => '01',
            ]),
            'q_anser.question_title.max' => json_encode([
                'message' => trans('validation.for_api.q_anser.question_title.max'),
                'error_no' => '39',
                'detail_code' => '01',
            ]),
            'q_anser.answers.*.answer.string' => json_encode([
                'message' => trans('validation.for_api.q_anser.answers.answer.string'),
                'error_no' => '40',
                'detail_code' => '01',
            ]),
            'q_anser.answers.*.answer.in' => json_encode([
                'message' => trans('validation.for_api.q_anser.answers.answer.in'),
                'error_no' => '40',
                'detail_code' => '01',
            ]),
            'payment_flg.in' => json_encode([
                'message' => trans('validation.for_api.payment_flg.in'),
                'error_no' => '41',
                'detail_code' => '01',
            ]),
            'payment_status.required' => json_encode([
                'message' => trans('validation.for_api.payment_status.required'),
                'error_no' => '42',
                'detail_code' => '01',
            ]),
            'payment_status.string' => json_encode([
                'message' => trans('validation.for_api.payment_status.string'),
                'error_no' => '43',
                'detail_code' => '01',
            ]),
            'trade_id.required' => json_encode([
                'message' => trans('validation.for_api.trade_id.required'),
                'error_no' => '44',
                'detail_code' => '01',
            ]),
            'trade_id.string' => json_encode([
                'message' => trans('validation.for_api.trade_id.string'),
                'error_no' => '44',
                'detail_code' => '02',
            ]),
            'order_id.numeric' => json_encode([
                'message' => trans('validation.for_api.order_id.numeric'),
                'error_no' => '45',
                'detail_code' => '01',
            ]),
            'card_payment_amount.numeric' => json_encode([
                'message' => trans('validation.for_api.card_payment_amount.numeric'),
                'error_no' => '46',
                'detail_code' => '01',
            ]),
            'payment_method.required' => json_encode([
                'message' => trans('validation.for_api.payment_method.required'),
                'error_no' => '47',
                'detail_code' => '01',
            ]),
            'payment_method.string' => json_encode([
                'message' => trans('validation.for_api.payment_method.string'),
                'error_no' => '48',
                'detail_code' => '01',
            ]),
            'cashpo_used_amount.numeric' => json_encode([
                'message' => trans('validation.for_api.cashpo_used_amount.numeri'),
                'error_no' => '49',
                'detail_code' => '01',
            ]),
            'amount_unsettled.numeric' => json_encode([
                'message' => trans('validation.for_api.amount_unsettled.numeric'),
                'error_no' => '50',
                'detail_code' => '01',
            ]),

        ];
    }

    /**
     * [Override] バリデーション失敗時
     *
     * @param Validator $validator
     * @throw HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $callback = $this->input('callback');
        $status = 1;
        $error_no = '99';
        $detail_code = '99';

        // [0]->最初のエラー
        $result = json_decode(array_values($validator->errors()->toArray())[0][0]);
        $error_no = $result->error_no ?? $error_no;
        $detail_code = $result->detail_code ?? $detail_code;
        $message = $result->message ?? $result;

        $response['status']  = $status;
        $response['code_number']  = $error_no;
        $response['code_detail']  = $detail_code;
        $response['message']  = $message;
        throw new HttpResponseException(
            response()->json($response, 400)->setCallback($callback)
        );
    }
}
