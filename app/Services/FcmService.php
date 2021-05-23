<?php


namespace App\Services;

use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

use Kreait\Firebase\Messaging;
use Kreait\Firebase\ServiceAccount;

class FcmService implements NotificationService
{
    /**
     * @var Messaging
     */
    protected $messaging;
    public function __construct(Messaging $messaging)
    {
        $factory = (new Factory())->withServiceAccount('/home/isoft/www/server_data/phonemarket_fb.json');
	    $this->messaging  = $factory->createMessaging();

    }

    public function sendNotification($deviceToken, $notificationData, $data)
    {
        try {
            $notification = Notification::fromArray([
                'title' => $notificationData['title'] ?? null,
                'body' => $notificationData['body'] ?? null,
                'image' => $notificationData['image'] ?? null,
            ]);
            $message = CloudMessage::withTarget('token', $deviceToken)
                ->withNotification($notification) // optional
                ->withData($data); // optional
            $this->messaging->send($message);
        } catch (\Exception $exception) {
            Log::error('sendNotification: '. $exception->getMessage(), $data);
        }

    }
    public function sendNotificationMultipleDevice($deviceTokens, $notificationData, $data = [])
    {
        Log::info($notificationData);
        Log::info($data);
        try {
            $notification = Notification::fromArray([
                'title' => $notificationData['title'] ?? null,
                'body' => $notificationData['body'] ?? null
            ]);
            $listRegistToken = [];
            foreach ($deviceTokens as $token) {
                $listRegistToken[] = Messaging\RegistrationToken::fromValue($token);
            }
            $message =  CloudMessage::new()->withNotification($notification)->withData($data);
            $result = $this->messaging->sendMulticast($message, $listRegistToken);

            Log::info(json_encode($result));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

    public function sendNotificationToTopic($topic, $notificationData, $data)
    {
        try {
            $notification = Notification::fromArray([
                'title' => $notificationData['title'] ?? null,
                'body' => $notificationData['body'] ?? null,
                'image' => $notificationData['image'] ?? null,
            ]);
            $message = CloudMessage::withTarget('topic', $topic)
                ->withNotification($notification) // optional
                ->withData($data); // optional
            $this->messaging->send($message);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }

    }

}
