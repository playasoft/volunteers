<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\DeleteOldSlotsJob;

class DeleteOldSlots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:slot:old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes slots that have already passed';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DeleteOldSlotsJob::dispatchNow();
    }
}
