<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Problem extends Model
{
    use  SoftDeletes;

    protected $table = 'problems';
    protected $fillable = ['title'];
}
