<?php

namespace Modules\Shop\Http\Requests\TransferHistory;

use Illuminate\Foundation\Http\FormRequest;
use \App\Rules\CountProduct;
use \Illuminate\Validation\Validator;
use Modules\Mon\Entities\TransferHistory;
use Modules\Mon\Entities\VtShopProduct;
use Illuminate\Support\Facades\Auth;

class CreateTransferHistoryRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => 'required',
            'received_at' => 'required',
            'products' => new CountProduct,
            'vtProducts' => 'required'
        ];
    }

    public function withValidator(Validator $validator)
    {
        $vtProducts = $this->vtProducts;
        $shopId = Auth::user()->shop_id;
        $companyId = Auth::user()->company_id;
        $validator->after(function ($validator) use ($vtProducts, $shopId, $companyId) {
            if (intval($this->type) == TransferHistory::TYPE_MOVE){
                if (!$this->to_shop_id){
                    $validator->errors()->add('to_shop_id', 'Thông tin kho nhận là bắt buộc.');
                }
            }
            if (count($this->vtProducts)){
                foreach ($vtProducts as $vtProduct){
                    if (!$vtProduct['catId'] || !$vtProduct['id'] || !$vtProduct['count']){
                        $validator->errors()->add('vtproduct', 'Thông tin vật tư không được để trống.');
                    } else {
                        $vtShopProduct = VtShopProduct::query()->where('shop_id', $shopId)->where('company_id', $companyId)
                            ->where('vt_product_id', $vtProduct['id'])->first();
                        if (!$vtShopProduct || $vtShopProduct->amount < intval($vtProduct['count'])){
                            $msg = sprintf('Số lượng tồn kho của %s không đủ.', $vtProduct['listVtProduct'][0]['name']);
                            $validator->errors()->add('vtproduct', $msg);
                        }
                    }
                    
                }
            }

        });
        
        return $validator;                     
    }

    public function translationRules()
    {
        return [];
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'title.required' => 'Tên đơn là bắt buộc',
            'received_at.required' => 'Thời gian là bắt buộc',
            'vtProducts.required' => 'Thông tin vật tư là bắt buộc',
            
        ];
    }

    public function translationMessages()
    {
        return [];
    }
}
