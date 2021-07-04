<?php

namespace Modules\Mon\Entities;

use App\Jobs\UpdateUserPoint;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;


/**
 * Modules\Mon\Entities\Company
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $gift_id
 * @property int|null $used
 * @property int|null $used_at
 * @property int|null $point
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @mixin \Eloquent
 */
class UserGift extends Model
{

    const STATUS_LOCK = 0;
    const STATUS_ACTIVE = 1;

    protected $table = 'user_gifts';
    protected $fillable = [
        'user_id', 'gift_id', 'point', 'used', 'used_at'
    ];

    public function gift() {
    	return $this->belongsTo(Gift::class, 'gift_id');
	}

}
