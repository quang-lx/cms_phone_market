<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Shop extends Model
{
    use  SoftDeletes, Userstamps;

    protected $table = 'shop';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'phone',
        'name',
        'address',
        'email',
        'status',
        'company_id',
        'created_by',
        'updated_by',
        'deleted_at',
        'created_at',
        'updated_at',
    ];
    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }
}
