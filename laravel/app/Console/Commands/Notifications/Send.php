<?php

namespace App\Console\Commands\Notifications;

use Illuminate\Console\Command;
use DB;
use Mail;
use App\Jobs\SendUserMailJob;
use App\Models\Notification;
use App\Models\User;
use App\Models\Slot;

class Send extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send queued notifications';

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
        SendUserMailJob::dispatchNow();
    }
}
