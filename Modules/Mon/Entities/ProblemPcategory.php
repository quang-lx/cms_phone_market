<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Media\Traits\MediaRelation;

class ProblemPcategory extends Model
{

    protected $table = 'problem_pcategory';
    protected $fillable = [
        'id',
        'problem_id',
        'pcategory_id',
    ];
    public function pcategory()
    {
        return $this->belongsTo(Pcategory::class,'pcategory_id');
    }
}
