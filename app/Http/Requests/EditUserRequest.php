<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditUserRequest extends FormRequest
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
            'username'      => 'required',
            'name'          => 'required',
            'basic_salary'  => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'username.required'     => 'Chưa nhập tên tài khoản',
            'name.required'         => 'Chưa nhập tên nhân viên',
            'basic_salary.required' => 'Chưa nhập tiền lương',
            'basic_salary.numeric'  => 'Tiền lương phải ở dạng số'
        ];
    }
}
