<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modules\Mon\Entities\SmsToken
 *
 * @property-read \Modules\Mon\Entities\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|SmsToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SmsToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SmsToken query()
 * @mixin \Eloquent
 */
class SmsToken extends Model
{
    protected $table = 'sms_token';
    protected $fillable = ['user_id', 'token', 'expired_at', 'used'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
