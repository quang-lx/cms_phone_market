<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

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
