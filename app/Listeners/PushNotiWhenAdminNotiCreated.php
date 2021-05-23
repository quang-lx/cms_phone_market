<?php

namespace App\Listeners;

use App\Events\AdminNotiCreated;
use App\Services\FcmService;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
 use Kreait\Firebase\Messaging;
use Kreait\Firebase\ServiceAccount;
use Kreait\Laravel\Firebase\FirebaseProjectManager;
use Modules\Mon\Entities\FbNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PushNotiWhenAdminNotiCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $notiService;

    public function __construct(  )
    {
        $serviceAccount = ServiceAccount::fromValue('/home/isoft/www/server_data/phonemarket_fb.json');
        dd($serviceAccount);
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle(AdminNotiCreated $event)
    {
        $notiData = $event->data;
        $topic = $notiData['topic'];
        $notificationData = ['title' => $notiData['title'], 'body' => $notiData['content']];
        $dataInNoti = [];

        if ($topic == 'android') {
            $this->notiService->sendNotificationToTopic($topic, $notificationData, $dataInNoti);
        } else if ($topic == 'ios') {
            $this->notiService->sendNotificationToTopic($topic, $notificationData, $dataInNoti);
        } else {
            $this->notiService->sendNotificationToTopic('android', $notificationData, $dataInNoti);
            $this->notiService->sendNotificationToTopic('ios', $notificationData, $dataInNoti);
        }


    }


}
