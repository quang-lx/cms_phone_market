<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Mon\Entities\Phoenix
 *
 * @property int $id
 * @property string $name
 * @property int $district_id
 * @property string|null $code
 * @property string|null $type
 * @property-read \Modules\Mon\Entities\District $district
 * @method static \Illuminate\Database\Eloquent\Builder|Phoenix newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Phoenix newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Phoenix query()
 * @method static \Illuminate\Database\Eloquent\Builder|Phoenix whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Phoenix whereDistrictId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Phoenix whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Phoenix whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Phoenix whereType($value)
 * @mixin \Eloquent
 */
class Phoenix extends Model
{
    protected $table = 'phoenix';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'code',
        'name',
        'district_id',
        'type'
    ];
    public function district()
    {
        return $this->belongsTo(District::class,'district_id');
    }
}
