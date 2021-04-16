<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class HomeSetting extends Model
{
    use  SoftDeletes;

    const TYPE_BANNER = 'banner';
    const TYPE_BEST_SELL = 'best_sell';
    const TYPE_BUY_NOW = 'buy_now';
    const TYPE_CATEGORY = 'category';
    const TYPE_SERVICE = 'service';
    const TYPE_SUGGEST = 'suggest';
    protected $table = 'home_setting';
    protected $fillable = [
    	'title',
	    'type',
	    'content',
	    'order_',
    ];
}
