<?php

namespace App\Http\Requests\System;

use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;

final class DomainSettingForm extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'domain'      => ['required',Rule::unique('domain_setting')->ignore($this->route('domain_setting'))],
            'title'       => 'required',
            'keyword'     => 'required',
            'description' => 'required',
        ];
    }
}
