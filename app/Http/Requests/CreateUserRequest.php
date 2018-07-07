<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'email'         => 'required|email|unique:users',
            'phone'         => 'required',
            'basic_salary'  => 'required',
            'password'      => 'required|min:6',
            'start_date'    => 'required'
        ];
    }

    public function messages()
    {
        return [
            'username.required'     => 'Chưa nhập tên tài khoản',
            'name.required'         => 'Chưa nhập tên nhân viên',
            'email.required'        => 'Chưa nhập email',
            'email.email'           => 'Email chưa đúng định dạng',
            'email.unique'          => 'Đã có tài khoản dùng email này',
            'phone.required'        => 'Chưa nhập số điện thoại',
            'basic_salary.required' => 'Chưa nhập tiền lương',
            'password.required'     => 'Chưa nhập mật khẩu',
            'password.min'          => 'Mật khẩu phải lớn hơn 6 kí tự',
            'start_date.required'   => 'Chưa nhập ngày bắt đầu làm việc'
        ];
    }
}
