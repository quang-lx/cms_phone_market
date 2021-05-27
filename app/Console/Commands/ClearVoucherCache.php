<?php namespace App\Console\Commands;

use App\Listeners\PushNotiAdminDelay;
use App\Models\CacheKey;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Modules\Mon\Entities\FbNotification;

# Imports the Google Cloud client library

class ClearVoucherCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voucher:clear-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Xoa cache voucher";

    /**
     * Execute the console command.
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
         Cache::tags(CacheKey::TAG_VOUCHER)->flush();
    }

}
