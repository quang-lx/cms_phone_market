<?php


namespace Modules\Mon\Traits;


trait CommonModel {
	public function scopeActive($query)
	{
		return $query->where('status', self::STATUS_ACTIVE);
	}

}
