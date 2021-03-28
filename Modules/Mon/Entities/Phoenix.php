<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Phoenix extends Model
{
    protected $table = 'phoenix';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'code',
        'name',
        'district_id',
        'type'
    ];
    public function district()
    {
        return $this->belongsTo(District::class,'district_id');
    }
}
