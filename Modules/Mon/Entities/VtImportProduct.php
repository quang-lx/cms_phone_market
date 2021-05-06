<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class VtImportProduct extends Model
{
    use  SoftDeletes;

    protected $table = 'vt_import_product';
    protected $fillable = [
        'id',
        'vt_import_excel_id',
        'vt_product_id',
        'vt_product_name',
        'amount',
    ];
}
