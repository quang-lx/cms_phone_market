<?php

namespace App\Listeners;

use App\Events\NeedCreateUserSmsToken;
use App\Services\SendSmsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Modules\Mon\Entities\SmsToken;

class SendSmsToUserRegistered implements ShouldQueue
{
    public $smsClient;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(SendSmsService  $sendSmsService)
    {
        $this->smsClient = $sendSmsService;
    }


    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(NeedCreateUserSmsToken $event)
    {
        $userId = $event->user_id;
        $userPhone = $event->user_phone;
        $smsToken = gen_sms_token();
        $smsTokenData = [
            'user_id' => $userId,
            'token' => $smsToken,
            'expired_at' => now()->addSeconds(env('TOKEN_EXPIRED', 120))

        ];
        SmsToken::create($smsTokenData);
        $content = sprintf("%s la ma xac thuc cua ban tren My Lane. Ma co hieu luc trong vong 3 phut.", $smsToken);
        $content = sprintf("%s la ma xac minh dang ky Baotrixemay cua ban", $smsToken);
        $result = $this->smsClient->sendToPhone($userPhone, $content);
        Log::info('sms token result', (array)$result);
    }
}
