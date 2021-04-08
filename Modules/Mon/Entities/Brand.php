<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use  SoftDeletes;

    protected $table = 'admin__brands';
    protected $fillable = [];
}
