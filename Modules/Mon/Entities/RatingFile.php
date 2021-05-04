<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Modules\Media\Traits\MediaRelation;

class RatingFile extends Model
{

    protected $table = 'rating';
    protected $fillable = [
        'id',
        'rating_id',
        'filename',
        'file_type',
        'file_ext',
    ];

}
