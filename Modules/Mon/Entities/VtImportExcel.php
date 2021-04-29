<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class VtImportExcel extends Model
{
    use  SoftDeletes;

    protected $table = 'vt_import_excel';
    protected $fillable = [
        'id',
        'filepath',
        'number_product',
        'status',
    ];
}
