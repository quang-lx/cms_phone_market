<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class TransferHistory extends Model
{
    use  SoftDeletes, Userstamps;

    protected $table = 'vt_transfer_history';
    protected $fillable = [
        'id',
        'title',
        'received_at',
        'shop_id',
        'company_id',
        'created_by',
        'updated_by',
        'deleted_at',
        'created_at',
        'updated_at',
        'status',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'vt_transfer_history_detail','vt_transfer_id','vt_product_id');
    }
}
