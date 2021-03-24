<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

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

}
