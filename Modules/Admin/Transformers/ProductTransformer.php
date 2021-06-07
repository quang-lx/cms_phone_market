<?php

namespace Modules\Admin\Transformers;


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
            'p_state_name' =>$this->state_name,
            'description' =>$this->description,
            'p_weight' =>$this->p_weight,
            's_long' =>$this->s_long,
            's_width' =>$this->s_width,
            's_height' => $this->s_height,
            'brand_id' => $this->brand_id,
            'brand_name' => optional($this->brand)->name,
            'company_id' => optional($this->company_id),
            'company' => $this->company,
            'created_at' => $this->created_at->format('d-m-Y'),
            'updated_at' => $this->updated_at->format('h:i d-m-Y'),
            'amount' => $this->amount,
            'price' => $this->price,
            'sale_price' => $this->sale_price,
            'category_id' => optional($this->pcategories)->pluck('id'),
            'category_name' => optional($this->pcategories)->pluck('name'),
            'thumbnail' => $this->thumbnail,
            'files' => $this->files,

            'problem_id' => $this->problems,
            'attribute_id' => $attribute,
            'warranty_time' => $this->warranty_time,
            'amount_name' => $this->amount_name,
            'pinformations' => $this->getInformations($this->productInformation),
            'fix_time' => $this->fix_time,
            'type' => $this->type,
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

    public function getInformations($asm) {
		$newValues = [];
		foreach ($asm as $value) {
			$newValues[] = [
				'id' => $value->information_id,
				'value' => $value->value,
				'title' => $value->pinformation->title,
			];


		}
		 return $newValues;
	}



}
