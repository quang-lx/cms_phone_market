<?php


namespace App\Services;


interface NotificationService
{

    public function sendNotificationToTopic($topic, $notificationData, $data);
    public function sendNotification($deviceToken, $notificationData, $data);
    public function sendNotificationMultipleDevice($deviceTokens, $notificationData, $data);
}
