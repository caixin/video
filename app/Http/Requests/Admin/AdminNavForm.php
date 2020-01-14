<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\FormRequest;

final class AdminNavForm extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'  => 'required',
            'route' => 'required',
        ];
    }

    /**
     * 獲取已定義驗證規則的錯誤消息。
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required'  => '导航名称 不可空白',
            'route.required' => '主路由 不可空白',
        ];
    }
}
