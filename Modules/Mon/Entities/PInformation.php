<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PInformation extends Model
{
    use  SoftDeletes;
    protected $table = 'p_information';
    protected $fillable = ['id', 'title', 'shop_id', 'company_id', 'type'];
    public function Shop()
    {
        return $this->belongsTo(Shop::class);
    }
    public function Company()
    {
        return $this->belongsTo(Company::class);
    }
}
