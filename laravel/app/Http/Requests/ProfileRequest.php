<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class ProfileRequest extends Request
{
    // An array which defines all of the rules for various profile edit types
    private $rules =
    [
        'account' =>
        [
            'name' => 'required|min:3|unique:users',
            'email' => 'required|email|unique:users',
            'current_password' => 'required|min:8|hashed'
        ],

        'password' =>
        [
            'password' => 'required|min:8|hashed',
            'new_password' => 'required|min:8|confirmed'
        ],

        'data' =>
        [
            'full_name' => 'required',
            'birthday' => 'date_format:Y-m-d',
        ]
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Ensure only valid edit types are used
        $type = $this->request->get('type');

        if(isset($this->rules[$type]))
        {
            return true;
        }
        
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // Dynamically change validation rules based on the edit type being used
        $type = $this->request->get('type');
        $rules = $this->rules[$type];

        // Append your current user ID to the name and email rules to prevent duplicate warnings for yourself
        if($type == 'account')
        {
            $rules['name'] .= ',name,' . Auth::user()->id;
            $rules['email'] .= ',email,' . Auth::user()->id;
        }
        
        return $rules;
    }
}
