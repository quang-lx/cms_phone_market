<?php

namespace Modules\Shop\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Mon\Entities\AttributeValue;


class ProductTransformer extends JsonResource
{


    public function toArray($request)
    {
    	$attribute = $this->getAttributeId($this->attributes->first());
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'sku' => $this->sku,
            'status' => $this->status,
            'status_color' =>$this->status_color,
            'status_name' =>$this->status_name,
            'p_state' =>$this->p_state,
            'description' =>$this->description,
            'p_weight' =>$this->p_weight,
            's_long' =>$this->s_long,
            's_width' =>$this->s_width,
            's_height' => $this->s_height,
            'brand_id' => $this->brand_id,
            'company_id' => $this->company_id,
            'company_name' => optional($this->company)->name,
            'created_at' => $this->created_at->format('d-m-Y'),
            'updated_at' => $this->updated_at->format('d-m-Y'),
            'amount' => $this->amount,
            'price' => $this->price,
            'category_id' => optional($this->pcategories)->pluck('id'),
            'category_name' => optional($this->pcategories)->pluck('name'),
            'thumbnail' => $this->thumbnail,

			'category_id' => $this->pcategories->pluck('id'),
            'problem_id' => $this->problems->pluck('id'),
            'attribute_id' => $attribute,
            'attribute_selected' => $attribute? $this->getAttributeValues($this->attributes->first(), $this->productAttributeValues): null,
            'value' => $this->value, //trả về thêm value dùng cho Autocomplete search tạo mới voucher
             'urls' => [
                'delete_url' => route('api.product.destroy', $this->id),
            ],

        ];


        return $data;
    }

    public function getAttributeId($attribute) {
    	return optional($attribute)->id;
    }
	public function getAttributeValues($attribute, $attributeValues) {
    	$newValues = [];
		foreach ($attributeValues as $value) {
			$attributeValue = AttributeValue::query()->find($value->value_id);
			$value['id']= $value->value_id;
			if ($attributeValue) {
				$value['name']= $attributeValue->name;
				$newValues[] = $value;
			}



		}
		$attibute = new \stdClass();
		$attibute->id = optional($attribute)->id;
		$attibute->name = optional($attribute)->name;
		$attibute->values = $newValues;
		return $attibute;
	}


}
