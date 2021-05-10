<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Modules\Mon\Entities\VtImportProduct;
class VtImportExcel extends Model
{
    use  SoftDeletes;

    protected $table = 'vt_import_excel';
    protected $fillable = [
        'id',
        'filepath',
        'number_product',
        'status',
        'company_id',
        'shop_id'
    ];

    public function vtImportProduct()
    {
       return $this->hasMany(VtImportProduct::class,'vt_import_excel_id');
    }
}
