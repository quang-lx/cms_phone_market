<?php

namespace Modules\Shop\Http\Requests\TransferHistory;

use Illuminate\Foundation\Http\FormRequest;
use \App\Rules\CountProduct;
use \Illuminate\Validation\Validator;

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
        $validator->after(function ($validator) use ($vtProducts) {
            if (intval($this->type) == 2){
                if (!$this->to_shop_id){
                    $validator->errors()->add('to_shop_id', 'Thông tin kho nhận là bắt buộc');
                }
            }
            if (count($this->vtProducts)){
                foreach ($vtProducts as $vtProduct){
                    if (!$vtProduct['catId'] || !$vtProduct['id'] || !$vtProduct['count']){
                        $validator->errors()->add('vtproduct', 'Thông tin vật tư không được để trống');
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
