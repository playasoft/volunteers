<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UserRequest extends Request
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
     * Dynamically apply rules based on what type of request is being made
     * 
     * @return array
     */
    public function rules()
    {
        switch(Request::path())
        {
            case "login":
                $rules =
                [
                    'name' => 'required|min:3|exists:users,name',
                    'password' => 'required|min:8|hashed'
                ];
            break;

            // Default to registration requirements
            default:
                $rules =
                [
                    'name' => 'required|min:3|unique:users',
                    'email' => 'required|email|unique:users',
                    'password' => 'required|min:8|confirmed'
                ];
            break;
        }

        return $rules;
    }
}
