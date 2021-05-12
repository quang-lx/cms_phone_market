<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Mon\Entities\Province
 *
 * @property int $id
 * @property string $name
 * @property string|null $code
 * @property string|null $type
 * @property string|null $phone_code
 * @method static \Illuminate\Database\Eloquent\Builder|Province newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Province newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Province query()
 * @method static \Illuminate\Database\Eloquent\Builder|Province whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Province whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Province whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Province wherePhoneCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Province whereType($value)
 * @mixin \Eloquent
 */
class Province extends Model
{

    protected $table = 'province';
    public $timestamps = false;
    public $translatedAttributes = [];
    protected $fillable = [
        'id',
        'name',
        'code',
        'type',
        'phone_code',
    ];
    public static function getProvinceCached($id) {

    }

}
