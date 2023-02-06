<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
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
            'name'                  => 'required|string|max:255',
            'address'               => 'required|string|max:255',
            'qualification'         => 'required|string|max:255',
            'phoneNumber'           => 'required|string|max:255',
            'joiningDate'           => 'required|date|max:255',
            'email'                 => 'required|string|email|max:255|unique:users',
            'password'              => 'required|string|min:6',
            'confirm_password'      => 'required|same:password',
            'designation'           => 'required',
            'cnic'                  => 'integer',
            'ntn'                   => 'integer',
            'account_no'            => 'integer'
        ];
    }
}
