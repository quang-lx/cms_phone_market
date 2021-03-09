<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use SoftDeletes;
    protected $fillable = ['user_id' ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
