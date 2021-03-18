<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements MustVerifyEmail, JWTSubject
{
    use Notifiable, HasRoles, SoftDeletes;
    const TYPE_ADMIN = 1;
    const TYPE_USER = 2;
    const TYPE_SHOP = 3;

    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'email_verified_at', 'activated', 'last_login', 'type', 'username', 'sms_verified_at', 'finish_reg',
        'fcm_token', 'lat', 'lng'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function tokens()
    {
        return $this->hasMany(UserToken::class, 'user_id', 'id');
    }

    /**
     * Lấy thông tin profile
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'id');
    }

    /**
     * Ứng dụng, tài khoản social được kết nối
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function connectedAccounts()
    {
        return $this->hasMany(ConnectedAccount::class, 'user_id', 'id');
    }

    /**
     * Các thông tin đã đc xác thực của tài khoản
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function verifiedInformation()
    {
        return $this->hasOne(VerifiedInformation::class, 'user_id', 'id');
    }

    public function getFirstToken()
    {
        return $this->tokens()->first();
    }

    public function getFirstTokenKey()
    {
        $userToken = $this->tokens()->first();

        if ($userToken === null) {
            return '';
        }

        return $userToken->access_token;
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
}
