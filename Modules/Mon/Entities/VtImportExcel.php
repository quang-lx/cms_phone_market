<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Modules\Mon\Entities\VtImportProduct;
class VtImportExcel extends Model
{
    use  SoftDeletes;

    protected $table = 'vt_import_excel';

    const WAITING = 1;
    const ENTERED = 2;

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

    public function Shop()
    {
       return $this->belongsTo(Shop::class,'shop_id');
    }

    public function getStatusNameAttribute($value) {
		$statusName = '';
		switch ($this->status) {
			case self::WAITING:
				$statusName = 'Chờ nhập kho';
				break;
			case self::ENTERED:
				$statusName = 'Đã nhập kho';
				break;
		}
		return $statusName;
	}

	public function getStatusColorAttribute($value) {
		$statusColor = '';
		switch ($this->status) {
			case self::WAITING:
				$statusColor = 'red';
				break;
			case self::ENTERED:
				$statusColor = '#219653';
				break;
		}
		return $statusColor;
	}
}
