<?php

namespace App\Listeners;

use App\Events\AdminNotiCreated;
use App\Services\FcmService;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\Jobs\Job;
use Kreait\Firebase\Messaging;
use Kreait\Laravel\Firebase\FirebaseProjectManager;
use Modules\Mon\Entities\FbNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PushNotiAdminDelay extends Job implements  ShouldQueue
{
    use  InteractsWithQueue,  SerializesModels;

    protected $notiService;
    protected $data;
    public function __construct($data)
    {
$this->data = $data;
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle()
    {
        $this->notiService = app(NotificationService::class);
        $notiData = $this->data;
        $topic = $notiData['topic'];
        $notificationData = ['title' => $notiData['title'], 'body' => $notiData['content']];
        $dataInNoti = ['type' => $notiData['link_type'], 'content' => $notiData['link_content']];
        switch ($notiData['link_type']) {
            case 'Link':
                $dataInNoti['type'] = FbNotification::TYPE_CMS_LINK;
                break;
            case 'News':
                $dataInNoti['type'] = FbNotification::TYPE_CMS_NEWS_ID;
                break;
            default:
                $dataInNoti['type'] = FbNotification::TYPE_CMS;
                break;
        }
        if ($topic == 'android') {
            $this->notiService->sendNotificationToTopic($topic, $notificationData, $dataInNoti);
        } else if ($topic == 'ios') {
            $this->notiService->sendNotificationToTopic($topic, $notificationData, $dataInNoti);
        } else {
            $this->notiService->sendNotificationToTopic('android', $notificationData, $dataInNoti);
            $this->notiService->sendNotificationToTopic('ios', $notificationData, $dataInNoti);
        }


    }


    /**
     * Get the job identifier.
     *
     * @return string
     */
    public function getJobId()
    {
        return $this->uuid();
    }

    /**
     * Get the raw body of the job.
     *
     * @return string
     */
    public function getRawBody()
    {
        // TODO: Implement getRawBody() method.
    }
}
