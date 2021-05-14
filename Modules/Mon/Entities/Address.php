<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
	use SoftDeletes;
    protected $table = 'user_address';
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'fullname',
        'phone',
        'address',
        'province_id',
        'district_id',
        'phoenix_id',
        'province_name',
        'district_name',
        'phoenix_name',
        'default',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class,'province_id');
    }
	public function district()
	{
		return $this->belongsTo(District::class,'district_id');
	}
	public function phoenix()
	{
		return $this->belongsTo(Phoenix::class,'phoenix_id');
	}
}
