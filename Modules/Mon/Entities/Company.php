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
class Company extends Model {
    use  SoftDeletes, Userstamps,MediaRelation;

    const STATUS_LOCK = 0;
    const STATUS_ACTIVE = 1;

    const LEVEL_1 = 1;
    const LEVEL_2 = 2;

    const APPROVE_STATUS_WAITTING= 0;
    const APPROVE_STATUS_APPROVED= 1;
    const APPROVE_STATUS_REJECT= 2;

    protected $table = 'company';
    protected $fillable = [
        'name', 'description', 'status', 'level', 'province_id', 'district_id', 'phoenix_id', 'created_by', 'deleted_by','address','phone','email','updated_by','approve_status', 'priority'
    ];


    public function users() {
        return $this->hasMany(User::class, 'company_id');
    }
    public function adminUser() {
        return $this->users->where('is_admin_company', 1)->first();
    }

    public function getStatusNameAttribute($value) {
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
    public function getStatusColorAttribute($value) {
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
    public function getLevelNameAttribute($value) {
        $statusName = '';
        switch ($this->status) {
            case self::LEVEL_1:
                $statusName = 'C???p 1';
                break;
            case self::LEVEL_2:
                $statusName = 'C???p 2';
                break;
        }
        return $statusName;
    }

    public function shop() {
        return $this->hasMany(Shop::class, 'company_id');
    }

    public function countShop() {
        return $this->hasMany(Shop::class, 'company_id')->count();
    }

    public function getThumbnailAttribute()
    {
        return $this->filesByZone('thumbnail')->first();
    }
}
