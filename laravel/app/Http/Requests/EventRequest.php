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
        return $gate->allows('create-event');
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
            'start_date' => 'required|min:10|max:10',
            'end_date' => 'required|min:10|max:10'
        ];
    }
}
