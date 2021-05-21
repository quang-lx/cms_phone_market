<?php namespace App\Console\Commands;

use App\Listeners\PushNotiAdminDelay;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Queue;
use Modules\Mon\Entities\FbNotification;

# Imports the Google Cloud client library

class SchedulerFbNoti extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fbnoti-scheduler:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "xu ly job lap lich";

    /**
     * Execute the console command.
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
         $records = FbNotification::query()->where('sent', 0)->whereNotNull('scheduled_at')->get();
         foreach ($records as $record) {
             $scheduledAt= Carbon::createFromFormat('Y-m-d H:i:s', $record->scheduled_at);
             if ($scheduledAt->addMinutes(5)->greaterThan(Carbon::now())) {
                 $job = new PushNotiAdminDelay($record->toArray());
                 Queue::later(Carbon::createFromFormat('Y-m-d H:i:s', $record->scheduled_at), $job);
                 $record->sent = 1;
                 $record->save();
             }
         }
    }

}
