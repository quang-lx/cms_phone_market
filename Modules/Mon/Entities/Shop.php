<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use  SoftDeletes;

    protected $table = 'shop';
    protected $fillable = [
        'id',
        'phone',
        'name',
        'address',
        'email',
        'status',
    ];
    public function district()
    {
        return $this->belongsTo(District::class,'district_id');
    }
}
