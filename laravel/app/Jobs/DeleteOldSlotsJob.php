<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Carbon\Carbon;
use App\Models\Slot;

class DeleteOldSlotsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $now = Carbon::now();
        
        //delete all slots yesterday and beyond
        Slot::where('start_date', '<', $now->format('Y-m-d'))->delete();
        
        //delete all slots that already happened today
        Slot::where('start_date', $now->format('Y-m-d'))
                ->where('end_time', '<', $now->format('H:i:s'))
                ->delete();
    }
}
