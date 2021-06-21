<?php namespace App\Console\Commands;

use App\Listeners\PushNotiAdminDelay;
use App\Models\CacheKey;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Modules\Mon\Entities\FbNotification;
use Modules\Mon\Entities\Orders;

# Imports the Google Cloud client library

class DoneOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:finish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "done don sau 3 ngay";

    /**
     * Execute the console command.
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
         $orders = Orders::query()->where('status', Orders::STATUS_ORDER_SENDING)
	         ->where('shop_done', 1)
	         ->whereDate('shop_done_at', '<=', now()->subDays(3))
	         ->update([
	         	'status' => Orders::STATUS_ORDER_DONE
	         ]);
    }

}