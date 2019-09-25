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
        $data = $this->validationData();
        $status = HospitalEnums::getValues();
        return  [
            'status' => ['required', Rule::in($status)],
            'latitude' => 'nullable|latitude',
            'longitude' => 'nullable|longitude',
            'kana' => [
                'required',
                'max:50',
                function ($attribute, $value, $fail) {
                    if (preg_match('/[^ぁ-んー]/u', $value) !== 0) {
                        return $fail('よみはひらがなで入力してください');
                    }
                },
            ],
            'access1' => [
                function ($attribute, $value, $fail) {
                    $data = $this->validationData();
                    if(!empty($value) && is_null($data['station1'])) {
                        return $fail('駅を選択してください');
                    }
                },
            ],
            'access2' => [
                function ($attribute, $value, $fail) {
                    $data = $this->validationData();
                    if(!empty($value) && is_null($data['station2'])) {
                        return $fail('駅を選択してください');
                    }
                },
            ],
            'access3' => [
                function ($attribute, $value, $fail) {
                    $data = $this->validationData();
                    if(!empty($value) && is_null($data['station3'])) {
                        return $fail('駅を選択してください');
                    }
                },
            ],
            'access4' => [
                function ($attribute, $value, $fail) {
                    $data = $this->validationData();
                    if(!empty($value) && is_null($data['station4'])) {
                        return $fail('駅を選択してください');
                    }
                },
            ],
            'access5' => [
                function ($attribute, $value, $fail) {
                    $data = $this->validationData();
                    if(!empty($value) && is_null($data['station5'])) {
                        return $fail('駅を選択してください');
                    }
                },
            ],
            'station1' => 'required_with:rail1',
            'station2' => 'required_with:rail2',
            'station3' => 'required_with:rail3',
            'station4' => 'required_with:rail4',
            'station5' => 'required_with:rail5',
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
            'medical_treatment_time.*.start.date_format' => '時間はHH：MMにしてください',
            'medical_treatment_time.*.end.date_format' => '時間はHH：MMにしてください',
	        'medical_treatment_time.*.end.after' => trans('messages.time_invalid'),
            'latitude.latitude' => 'フォーマットが正しくありません。',
            'latitude.longitude' => 'フォーマットが正しくありません。',
        ];
    }
    /**
     * 項目名
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'station1' => '駅',
            'rail1' => '路線',
            'station2' => '駅',
            'rail2' => '路線',
            'station3' => '駅',
            'rail3' => '路線',
            'station4' => '駅',
            'rail4' => '路線',
            'station5' => '駅',
            'rail5' => '路線',
        ];
    }
}
