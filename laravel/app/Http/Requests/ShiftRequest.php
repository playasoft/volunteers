<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ShiftRequest extends Request
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
        return
        [
            'department_id' => 'required|integer',
            'name' => 'required',
            'start' => 'required|date_format:h:i a',
            'end' => 'required|date_format:h:i a',
            'duration' => 'required|date_format:h:i',
        ];
    }
}
