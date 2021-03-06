<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;
use Modules\Media\Traits\MediaRelation;


/**
 * Modules\Mon\Entities\Company
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int|null $province_id
 * @property int|null $district_id
 * @property int|null $phoenix_id
 * @property int|null $created_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $phone
 * @property int|null $level
 * @property int|null $status
 * @method static \Illuminate\Database\Eloquent\Builder|Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Company newQuery()
 * @method static \Illuminate\Database\Query\Builder|Company onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Company query()
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereDistrictId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company wherePhoenixId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereProvinceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Company withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Company withoutTrashed()
 * @mixin \Eloquent
 */
class Brand extends Model
{
    use  SoftDeletes, Userstamps, MediaRelation;

    const STATUS_LOCK = 0;
    const STATUS_ACTIVE = 1;

    protected $table = 'brand';
    protected $fillable = [
        'name', 'type', 'status', 'created_by', 'deleted_by', 'updated_by'
    ];

    public function getStatusNameAttribute($value)
    {
        $statusName = '';
        switch ($this->status) {
            case self::STATUS_LOCK:
                $statusName = 'Kh??a';
                break;
            case self::STATUS_ACTIVE:
                $statusName = 'Ho???t ?????ng';
                break;
        }
        return $statusName;
    }
    public function getStatusColorAttribute($value)
    {
        $statusColor = '';
        switch ($this->status) {
            case self::STATUS_LOCK:
                $statusColor = '#F5ABAB';
                break;
            case self::STATUS_ACTIVE:
                $statusColor = '#219653';
                break;
        }
        return $statusColor;
    }
    public function getTypeNameAttribute($value)
    {
        $statusName = '';
        switch ($this->type) {
            case 'product':
                $statusName = 'H??ng h??a';
                break;
            case 'service':
                $statusName = 'D???ch v???';
                break;
        }
        return $statusName;
    }
    public function BrandPcategory()
    {
        return $this->hasMany(BrandPcategory::class, 'brand_id');
    }
    public function pcategories()
    {
        return $this->belongsToMany(Pcategory::class, 'brand_pcategory', 'brand_id', 'pcategory_id');
    }
}
