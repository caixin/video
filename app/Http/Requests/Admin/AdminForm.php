<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;

final class AdminForm extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                return [
                    'username'     => ['required',Rule::unique('admin')->ignore($this->route('admin'))],
                    'password'     => 'required|min:6|max:12',
                    'roleid'       => 'required',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'username'     => ['required',Rule::unique('admin')->ignore($this->route('admin'))],
                    'roleid'       => 'required',
                ];
            }
            default: break;
        }
    }
}
