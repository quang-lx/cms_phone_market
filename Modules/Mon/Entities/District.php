<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Mon\Entities\District
 *
 * @property int $id
 * @property string $name
 * @property int $province_id
 * @property string|null $lat
 * @property string|null $lng
 * @property string|null $code
 * @property string|null $type
 * @property-read \Modules\Mon\Entities\Province $province
 * @method static \Illuminate\Database\Eloquent\Builder|District newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|District newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|District query()
 * @method static \Illuminate\Database\Eloquent\Builder|District whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|District whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|District whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|District whereLng($value)
 * @method static \Illuminate\Database\Eloquent\Builder|District whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|District whereProvinceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|District whereType($value)
 * @mixin \Eloquent
 */
class District extends Model
{
    protected $table = 'district';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'name',
        'province_id',
        'lat',
        'lng',
        'code',
        'type'
    ];

    public function province()
    {
        return $this->belongsTo(Province::class,'province_id');
    }
}
