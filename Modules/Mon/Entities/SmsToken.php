<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsToken extends Model
{
    protected $table = 'sms_token';
    protected $fillable = ['user_id', 'token', 'expired_at', 'used'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
