<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Media\Traits\MediaRelation;

class BrandPcategory extends Model
{
  
    protected $table = 'brand_pcategory';
    protected $fillable = [
        'id',
        'brand_id',
        'pcategory_id',
    ];
    public function Pcategory()
    {
        return $this->belongsTo(Pcategory::class,'pcategory_id');
    }
}
