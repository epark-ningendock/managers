<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Log;

class ValidationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    public function messages()
    {
        return [
            'hospital_code.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'hospital_code.regex' => json_encode([
                'message' => trans('validation.for_api.regex'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            'hospital_code.exists' => json_encode([
                'message' => trans('validation.for_api.exists'),
                'error_no' => '01',
                'detail_code' => '13',
            ]),
            'hospital_no.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'hospital_no.numeric' => json_encode([
                'message' => trans('validation.for_api.numeric'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            'hospital_no.exists' => json_encode([
                'message' => trans('validation.for_api.exists'),
                'error_no' => '01',
                'detail_code' => '13',
            ]),
            'course_code.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'course_code.numeric' => json_encode([
                'message' => trans('validation.for_api.numeric'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            'course_code.exists' => json_encode([
                'message' => trans('validation.for_api.exists'),
                'error_no' => '01',
                'detail_code' => '13',
            ]),
            'course_no.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'course_no.numeric' => json_encode([
                'message' => trans('validation.for_api.numeric'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            'course_no.exists' => json_encode([
                'message' => trans('validation.for_api.exists'),
                'error_no' => '01',
                'detail_code' => '13',
            ]),
            'place_code.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'place_code.numeric' => json_encode([
                'message' => trans('validation.for_api.numeric'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            'place_code.min' => json_encode([
                'message' => trans('validation.for_api.min'),
                'error_no' => '07',
                'detail_code' => '01',
            ]),
            'place_code.max' => json_encode([
                'message' => trans('validation.for_api.max'),
                'error_no' => '07',
                'detail_code' => '11',
            ]),
            // データ範囲エラー
            'rail_no.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'rail_no.numeric' => json_encode([
                'message' => trans('validation.for_api.numeric'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            // 日付範囲エラー
            'get_yyyymm_from.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'get_yyyymm_from.date_format' => json_encode([
                'message' => trans('validation.for_api.date_format'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            'get_yyyymm_to.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'get_yyyymm_to.date_format' => json_encode([
                'message' => trans('validation.for_api.date_format'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            'get_yyyymmdd_from.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'get_yyyymmdd_from.date_format' => json_encode([
                'message' => trans('validation.for_api.date_format'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            'get_yyyymmdd_from.numeric' => json_encode([
                'message' => trans('validation.for_api.numeric'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            'get_yyyymmdd_from.regex' => json_encode([
                'message' => trans('validation.for_api.regex'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            'get_yyyymmdd_to.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'get_yyyymmdd_to.date_format' => json_encode([
                'message' => trans('validation.for_api.date_format'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            'get_yyyymmdd_to.numeric' => json_encode([
                'message' => trans('validation.for_api.numeric'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            'get_yyyymmdd_to.regex' => json_encode([
                'message' => trans('validation.for_api.regex'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            // 必須項目未設定エラー
            'return_flag.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'return_flag.numeric' => json_encode([
                'message' => trans('validation.for_api.numeric'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            'return_flag.in' => json_encode([
                'message' => trans('validation.for_api.in'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            // 条件付必須項目未設定エラー
            'return_from.required' => json_encode([
                'message' => trans('validation.for_api.return_from.required'),
                'error_no' => '04',
                'detail_code' => '01',
            ]),
            // 必須項目未設定エラー
            'return_from.numeric' => json_encode([
                'message' => trans('validation.for_api.return_from.numeric'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            'return_from.min' => json_encode([
                'message' => trans('validation.for_api.min'),
                'error_no' => '07',
                'detail_code' => '01',
            ]),

            // 条件付必須項目未設定エラー
            'return_to.required' => json_encode([
                'message' => trans('validation.for_api.return_to.required'),
                'error_no' => '04',
                'detail_code' => '01',
            ]),
            // 必須項目未設定エラー
            'return_to.numeric' => json_encode([
                'message' => trans('validation.for_api.return_to.numeric'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            'return_to.min' => json_encode([
                'message' => trans('validation.for_api.min'),
                'error_no' => '07',
                'detail_code' => '01',
            ]),
            // 必須項目未設定エラー
            'search_count_only_flag.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'search_count_only_flag.numeric' => json_encode([
                'message' => trans('validation.for_api.numeric'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            'search_count_only_flag.in' => json_encode([
                'message' => trans('validation.for_api.in'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            'course_price_sort.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'course_price_sort.numeric' => json_encode([
                'message' => trans('validation.for_api.numeric'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            'course_price_sort.in' => json_encode([
                'message' => trans('validation.for_api.in'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            'search_condition_return_flag.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'search_condition_return_flag.numeric' => json_encode([
                'message' => trans('validation.for_api.numeric'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            'search_condition_return_flag.in' => json_encode([
                'message' => trans('validation.for_api.in'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),


            // 予約登録API
            'shopownner_id.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'menu_id.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'staff_id.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'start_datetime.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'last_name.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'first_name.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'birthday.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'sex.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'email.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'terminal_tp.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'regist_member.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'last_name.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'first_name.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'representative_fg.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'order_no.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),

            'start_date.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            // 決済ステータス
            'payment_status.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            // 取引特定
            'trade_id.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            // 支払方法
            'payment_method.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),

            // EPARK会員ID
            'epark_member_id.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'epark_member_id.numeric' => json_encode([
                'message' => trans('validation.for_api.numeric'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            'epark_member_id.exists' => json_encode([
                'message' => trans('validation.for_api.epark_member_id.exists'),
                'error_no' => '01',
                'detail_code' => '13',
            ]),
            // メール情報配信
            'mail_info_delivery.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'mail_info_delivery.enum_value' => json_encode([
                'message' => trans('validation.for_api.enum_value'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            // ニックネーム使用
            'nick_use.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'nick_use.enum_value' => json_encode([
                'message' => trans('validation.for_api.enum_value'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            // 連絡先登録
            'contact.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'contact.enum_value' => json_encode([
                'message' => trans('validation.for_api.enum_value'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            // 連絡先名称
            'contact_name.max' => json_encode([
                'message' => trans('validation.for_api.max'),
                'error_no' => '07',
                'detail_code' => '11',
            ]),
            // 状態
            'status.required' => json_encode([
                'message' => trans('validation.for_api.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'status.enum_value' => json_encode([
                'message' => trans('validation.for_api.enum_value'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),

            // 受診希望日
            'reservation_dt.date_format' => json_encode([
                'message' => trans('validation.for_api.date_format'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            'price_upper_limit.numeric' => json_encode([
                'message' => trans('validation.for_api.numeric'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            'price_lower_limit.numeric' => json_encode([
                'message' => trans('validation.for_api.numeric'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            'string_limit_size.numeric' => json_encode([
                'message' => trans('validation.for_api.numeric'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
        ];
    }

    /**
     * [Override] バリデーション失敗時
     * ※laravel ver5.5以上のやり方
     *
     * @param Validator $validator
     * @throw HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $callback = $this->input('callback');
        $status = 1;
        $error_no = '03';
        $detail_code = '01';

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
