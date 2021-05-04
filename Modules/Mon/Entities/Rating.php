<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Modules\Media\Traits\MediaRelation;

class Rating extends Model
{

    protected $table = 'rating';
    protected $fillable = [
        'id',
        'product_id',
        'user_id',
        'rating',
        'comment',
        'parent_id',
    ];

    public function ratingFiles() {
    	return $this->hasMany(RatingFile::class, 'rating_id', 'id');
    }
    public function user() {
    	return $this->belongsTo(User::class, 'user_id');
    }
}
