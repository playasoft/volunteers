<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    // Helper function to generate slots based on shift information
    static public function generate($shift)
    {
        // Delete all existing slots for this shift
        Slot::where('shift_id', $shift->id)->delete();

        // Start looping from the start time
        
        dd($shift->start);

        // Have we reached the time of the last shift?
    }
}
