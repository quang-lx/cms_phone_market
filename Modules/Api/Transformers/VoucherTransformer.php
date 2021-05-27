<?php


namespace Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Modules\Mon\Entities\Voucher;

class VoucherTransformer extends JsonResource
{


    public function toArray($request)
    {
        $listProduct = null;
        if ($this->type == Voucher::TYPE_DISCOUNT_PRODUCT) {
            $listProduct = $this->listProductId($this->id);
        }

        $data = [
            'id' => $this->id,
            'shop_id' => $this->shop_id,
            'type' => $this->type,
            'title' => $this->title,
            'code' => $this->code,
            'discount_type' => $this->discount_type,
            'discount_amount' => $this->discount_amount,
            'require_min_amount' => $this->require_min_amount,
            'products' => $listProduct

        ];


        return $data;
    }
    public function listProductId($voucherId) {
        DB::table('voucher_product')->where('voucher_id', $voucherId)->select('product_id')->pluck('product_id')->all();
    }

}
