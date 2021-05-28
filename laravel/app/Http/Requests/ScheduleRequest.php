<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ScheduleRequest extends Request
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
        // Ignore validation when preserved input is passed
        if($this->request->get('preserved-data'))
        {
            return [];
        }

        // Date & time fields are only required when date_enabled is true
        if($this->request->get('date_enabled'))
        {
            return
            [
                'department_id' => 'required|integer|exists:departments,id',
                'shift_id' => 'required|integer|exists:shifts,id',
                'dates' => 'required',
                'start_time' => 'required',
                'end_time' => 'required',
                'duration' => 'required',
                'volunteers' => 'required|integer|min:1',
            ];
        }

        return
        [
            'department_id' => 'required|integer|exists:departments,id',
            'shift_id' => 'required|integer|exists:shifts,id',
            'volunteers' => 'required|integer|min:1',
        ];
    }
}
