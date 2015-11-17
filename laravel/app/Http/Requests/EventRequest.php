<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use App\Http\Requests\Request;

class EventRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(GateContract $gate)
    {
//        return $gate->allows('create-event');
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
            'name' => 'required',
            'photo' => 'mimes:jpeg,gif,png',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d'
        ];
    }
}
