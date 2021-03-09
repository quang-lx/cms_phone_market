<?php

namespace App\Services;


interface SendSmsService
{

    public function sendToPhone(
        $phone,
        $content
    );

}
