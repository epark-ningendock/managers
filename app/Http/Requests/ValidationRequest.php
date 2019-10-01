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
                'message' => trans('validation.for_api.hospital_code.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'hospital_code.alpha_num' => json_encode([
                'message' => trans('validation.for_api.hospital_code.alpha_num'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            'hospital_code.exists' => json_encode([
                'message' => trans('validation.for_api.hospital_code.exists'),
                'error_no' => '01',
                'detail_code' => '13',
            ]),
            'course_no.required' => json_encode([
                'message' => trans('validation.for_api.course_no.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'course_no.numeric' => json_encode([
                'message' => trans('validation.for_api.course_no.numeric'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            'course_no.exists' => json_encode([
                'message' => trans('validation.for_api.course_no.exists'),
                'error_no' => '01',
                'detail_code' => '13',
            ]),
            'place_code.required' => json_encode([
                'message' => trans('validation.for_api.place_code.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'place_code.numeric' => json_encode([
                'message' => trans('validation.for_api.place_code.numeric'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            'place_code.min' => json_encode([
                'message' => trans('validation.for_api.place_code.min'),
                'error_no' => '07',
                'detail_code' => '01',
            ]),
            'place_code.max' => json_encode([
                'message' => trans('validation.for_api.place_code.max'),
                'error_no' => '07',
                'detail_code' => '11',
            ]),
            // データ範囲エラー
            'rail_no.required' => json_encode([
                'message' => trans('validation.for_api.rail_no.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'rail_no.numeric' => json_encode([
                'message' => trans('validation.for_api.rail_no.numeric'),
                'error_no' => '01',
                'detail_code' => '11',
            ]),
            // 日付範囲エラー
            'get_yyyymm_from.date_format' => json_encode([
                'message' => trans('validation.for_api.get_yyyymm_from.date_format'),
                'error_no' => '07',
                'detail_code' => '01',
            ]),
            'get_yyyymm_to.date_format' => json_encode([
                'message' => trans('validation.for_api.get_yyyymm_to.date_format'),
                'error_no' => '07',
                'detail_code' => '01',
            ]),
            'get_yyyymmdd_from.date_format' => json_encode([
                'message' => trans('validation.for_api.get_yyyymmdd_from.date_format'),
                'error_no' => '07',
                'detail_code' => '01',
            ]),
            'get_yyyymmdd_to.date_format' => json_encode([
                'message' => trans('validation.for_api.get_yyyymmdd_to.date_format'),
                'error_no' => '07',
                'detail_code' => '01',
            ]),

            // 条件付必須項目未設定エラー
            'return_flag.required' => json_encode([
                'message' => trans('validation.for_api.return_flag.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'return_flag.numeric' => json_encode([
                'message' => trans('validation.for_api.return_flag.numeric'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            'return_from.required_if' => json_encode([
                'message' => trans('validation.for_api.return_from.required_if'),
                'error_no' => '04',
                'detail_code' => '01',
            ]),
            'return_to.required_if' => json_encode([
                'message' => trans('validation.for_api.return_to.required_if'),
                'error_no' => '04',
                'detail_code' => '01',
            ]),
            'return_from.numeric' => json_encode([
                'message' => trans('validation.for_api.return_from.numeric'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            'return_to.numeric' => json_encode([
                'message' => trans('validation.for_api.return_to.numeric'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            'search_count_only_flag.required' => json_encode([
                'message' => trans('validation.for_api.search_count_only_flag.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'search_count_only_flag.numeric' => json_encode([
                'message' => trans('validation.for_api.search_count_only_flag.numeric'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            'course_price_sort.required' => json_encode([
                'message' => trans('validation.for_api.course_price_sort.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'course_price_sort.numeric' => json_encode([
                'message' => trans('validation.for_api.course_price_sort.numeric'),
                'error_no' => '06',
                'detail_code' => '01',
            ]),
            'search_condition_return_flag.required' => json_encode([
                'message' => trans('validation.for_api.search_condition_return_flag.required'),
                'error_no' => '03',
                'detail_code' => '01',
            ]),
            'search_condition_return_flag.numeric' => json_encode([
                'message' => trans('validation.for_api.search_condition_return_flag.numeric'),
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
