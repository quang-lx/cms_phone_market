<?php

namespace App\Listeners;

use App\Events\ShopNotiCreated;
use App\Services\FcmService;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
 use Kreait\Firebase\Messaging;
use Kreait\Laravel\Firebase\FirebaseProjectManager;
use Modules\Mon\Entities\FbNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PushNotiWhenSHopNotiCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $notiService;

    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle(ShopNotiCreated $event)
    {
        $this->notiService = app(NotificationService::class);
        $notiData = $event->data;
        $notificationData = ['title' => $notiData['title'], 'body' => $notiData['content']];
        $dataInNoti = [
            'type' => $notiData['type'],
            'order_id' => $notiData['order_id'],
        ];
        $deviceToken = $notiData['fcm_token'];
        if ($deviceToken) {
			$this->notiService->sendNotification($deviceToken, $notificationData, $dataInNoti);

		}


    }


}
