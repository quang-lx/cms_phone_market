<?php namespace App\Console\Commands;

use App\Listeners\PushNotiAdminDelay;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Queue;
use Modules\Mon\Entities\FbNotification;
use Modules\Mon\Entities\Warning;

# Imports the Google Cloud client library

class DeleteExpiredWarning extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'warning:delete-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Xoa canh bao het han";

    /**
     * Execute the console command.
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
         $warnings = Warning::query()->where('created_at', '<', now()->subDays(1)->format('Y-m-d H:i:s'))->delete();

    }

}
