<?php

namespace App\Http\Requests;

use App\EmailTemplate;
use Illuminate\Foundation\Http\FormRequest;

class EmailTemplateFormRequest extends FormRequest
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
        return [
            'title' => 'required|max:255',
            'text' => 'max:20000'
        ];
    }

    public function attributes()
    {
        $attributes = [
            'title' => 'テンプレート名（件名）'
        ];
        return $attributes;
    }

}
