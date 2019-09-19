<?php

namespace App\Http\Requests;

use App\Enums\HospitalEnums;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HospitalCreateFormRequest extends FormRequest
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
        $status = HospitalEnums::getValues();
        return  [
            'status' => ['required', Rule::in($status)],
            'latitude' => 'latitude',
            'longitude' => 'longitude',
            'kana' => [
                'required',
                'max:50',
                function ($attribute, $value, $fail) {
                    if (preg_match('/[^ぁ-んー]/u', $value) !== 0) {
                        return $fail('よみはひらがなで入力してください');
                    }
                },
            ],
            'station1' => 'required_with:access1',
            'station2' => 'required_with:access2',
            'station3' => 'required_with:access3',
            'station4' => 'required_with:access4',
            'station5' => 'required_with:access5',
            'name' => 'required|max:50',
            'postcode' => 'regex:/^\d{3}-?\d{4}$/',
            'address1' => 'max:256',
            'address2' => 'max:256',
            'tel' => 'regex:/^\d{2,4}-?\d{2,4}-?\d{3,4}$/',
            'paycall' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (preg_match('/^[0-9]{2,4}-[0-9]{2,4}-[0-9]{3,4}$/', $value) == false) {
                        return $fail('半角ハイフン付きの電話番号で入力してください');
                    }
                },
            ],
            'consultation_note' => 'max:256',
            'medical_treatment_time.*.start' => 'nullable|date_format:H:i',
            'medical_treatment_time.*.end' => 'nullable|date_format:H:i|after:medical_treatment_time.*.start',
        ];
    }


    public function messages()
    {
        return [
            'station1.required_with' => '駅が未選択のため入力できません。',
            'station2.required_with' => '駅が未選択のため入力できません。',
            'station3.required_with' => '駅が未選択のため入力できません。',
            'station4.required_with' => '駅が未選択のため入力できません。',
            'station5.required_with' => '駅が未選択のため入力できません。',
            'medical_treatment_time.*.start.date_format' => '時間はHH：MMにしてください',
            'medical_treatment_time.*.end.date_format' => '時間はHH：MMにしてください',
	        'medical_treatment_time.*.end.after' => trans('messages.time_invalid'),
            'latitude.latitude' => 'フォーマットが正しくありません。',
            'latitude.longitude' => 'フォーマットが正しくありません。',
        ];
    }
}
