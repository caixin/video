<?php

namespace App\Http\Requests\System;

use App\Http\Requests\FormRequest;

final class AdsForm extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type'  => 'required',
            'name'  => 'required',
            'image' => 'required',
            'url'   => 'required',
        ];
    }
}
