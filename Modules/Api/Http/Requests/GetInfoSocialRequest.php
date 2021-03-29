<?php

namespace Modules\Api\Http\Requests;

class GetInfoSocialRequest extends RequestAPI
{
    public function rules()
    {
        return [
            'token' => 'required',
            'provider' => 'required'
        ];
    }
}
