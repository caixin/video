<?php

namespace App\Http\Requests\System;

use App\Http\Requests\FormRequest;

final class SysconfigForm extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'skey'   => 'required|unique:sysconfig,skey',
            'svalue' => 'required',
            'info'   => 'required',
        ];
    }
}
