<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Modules\Media\Entities\Media;
use Modules\Media\Traits\MediaRelation;

class RatingShop extends Model
{

    protected $table = 'rating_shop';
    protected $fillable = [
        'id',
        'shop_id',
        'user_id',
        'rating',
        'comment',
        'parent_id',
    ];

    public function ratingFiles() {
    	return $this->hasMany(RatingShopFile::class, 'shop_rating_id', 'id');
    }
    public function user() {
    	return $this->belongsTo(User::class, 'user_id');
    }
    public function files() {
    	return $this->belongsToMany(Media::class, 'rating_file', 'rating_id', 'file_id');
    }
}
