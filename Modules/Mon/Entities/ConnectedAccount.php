<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Mon\Entities\ConnectedAccount
 *
 * @property int $id
 * @property int $user_id
 * @property string $account_id
 * @property string|null $token
 * @property string|null $token_expire_time
 * @property string|null $email
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $birth_date
 * @property string|null $gender
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $provider
 * @property-read \Modules\Mon\Entities\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Mon\Entities\ConnectedAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Mon\Entities\ConnectedAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Mon\Entities\ConnectedAccount query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Mon\Entities\ConnectedAccount whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Mon\Entities\ConnectedAccount whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Mon\Entities\ConnectedAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Mon\Entities\ConnectedAccount whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Mon\Entities\ConnectedAccount whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Mon\Entities\ConnectedAccount whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Mon\Entities\ConnectedAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Mon\Entities\ConnectedAccount whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Mon\Entities\ConnectedAccount whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Mon\Entities\ConnectedAccount whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Mon\Entities\ConnectedAccount whereTokenExpireTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Mon\Entities\ConnectedAccount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Mon\Entities\ConnectedAccount whereUserId($value)
 * @mixin \Eloquent
 */
class ConnectedAccount extends Model
{
	protected $fillable = ['user_id', 'token', 'account_id', 'token_expire_time', 'email', 'first_name', 'last_name', 'birth_date',
		'gender', 'provider'];

	const SERVICE_FACEBOOK = 'facebook';
	const SERVICE_GOOGLE = 'google';

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id','id');
	}
}
