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
 * @property int $id
 * @property int $user_id
 * @property string $token
 * @property string|null $expired_at
 * @property int|null $used
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SmsToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SmsToken whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SmsToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SmsToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SmsToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SmsToken whereUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SmsToken whereUserId($value)
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
