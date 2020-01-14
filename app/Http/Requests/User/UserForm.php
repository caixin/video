<?php

namespace App\Http\Requests\User;

use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;

final class UserForm extends FormRequest
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
                    'username' => 'required|telphone|unique:user,username',
                    'password' => 'required|min:6|max:12',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'username' => ['required','telphone',Rule::unique('user')->ignore($this->route('user'))],
                ];
            }
            default: break;
        }
    }
}
