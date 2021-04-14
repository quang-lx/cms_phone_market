<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class HomeSetting extends Model
{
    use  SoftDeletes;

    protected $table = 'home_setting';
    protected $fillable = [
    	'title',
	    'type',
	    'content',
	    'order_',
    ];
}
