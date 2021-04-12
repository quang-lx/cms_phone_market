<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Wildside\Userstamps\Userstamps;

/**
 * Modules\Mon\Entities\User
 *
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property string|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $last_login
 * @property int $type
 * @property string $username
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Modules\Mon\Entities\Profile|null $profile
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Query\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @method static \Illuminate\Database\Query\Builder|User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|User withoutTrashed()
 * @mixin \Eloquent
 * @property int $finish_reg
 * @property int $status
 * @property string|null $fcm_token
 * @property string|null $lat
 * @property string|null $lng
 * @property string|null $phone
 * @property int|null $province_id
 * @property int|null $district_id
 * @property int|null $phoenix_id
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDistrictId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFcmToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFinishReg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLng($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhoenixId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProvinceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @property int|null $company_id
 * @property int|null $is_admin_company
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsAdminCompany($value)
 */
class User extends Authenticatable implements MustVerifyEmail, JWTSubject
{
    use Notifiable, HasRoles, SoftDeletes, Userstamps;
    const TYPE_ADMIN = 1;
    const TYPE_USER = 2;
    const TYPE_SHOP = 3;

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_LOCK = 2;

    const ROLE_SHOP_ADMIN = 'shop_admin';

    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'email_verified_at', 'activated', 'last_login', 'type', 'username', 'sms_verified_at', 'finish_reg',
        'fcm_token', 'lat', 'lng', 'status', 'phone','province_id', 'district_id', 'phoenix_id','is_admin_company','company_id','shop_id','rank','birthday'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * Lấy thông tin profile
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'id');
    }



    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'user_id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }


    public function shop()
    {
        return $this->belongsTo(Company::class,'shop_id');
    }

    public function getStatusNameAttribute($value) {
        $statusName = '';
        switch ($this->status) {
            case self::STATUS_LOCK:
                $statusName = 'Đã khóa';
                break;
            case self::STATUS_ACTIVE:
                $statusName = 'Hoạt động';
                break;
            case self::STATUS_INACTIVE:
                $statusName = 'Chưa hoạt động';
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
	        case self::STATUS_INACTIVE:
		        $statusColor = 'red';
		        break;
        }
        return $statusColor;
    }
	public function getAvatarAttribute()
	{
		return $this->filesByZone('avatar')->first();
	}

}
