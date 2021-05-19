<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class FbNotification extends Model
{
    use  SoftDeletes;
    const SENT_UNSENT = 0;
    const SENT_SENT = 1;

    protected $table = 'notifications';
    protected $fillable = [
        'id',
        'title',
        'content',
        'topic',
        'scheduled_at',
        'sent'
    ];

    public function getsentNameAttribute($value) {
        $sentName = '';
        switch ($this->sent) {
            case self::SENT_UNSENT:
                $sentName = 'Chưa gửi';
                break;
            case self::SENT_SENT:
                $sentName = 'Đã gửi';
                break;
        }
        return $sentName;
    }
    public function getsentColorAttribute($value) {
        $sentColor = '';
        switch ($this->sent) {
            case self::SENT_UNSENT:
                $sentColor = '#F5ABAB';
                break;
            case self::SENT_SENT:
                $sentColor = '#219653';
                break;
        }
        return $sentColor;
    }
}
