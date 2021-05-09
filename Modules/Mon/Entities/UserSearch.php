<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class UserSearch extends Model
{

    protected $table = 'user_search';
    protected $fillable = [
        'user_id',
        'product_id',
        'fcm_token',
    ];


}
