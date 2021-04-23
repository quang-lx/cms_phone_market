<?php

namespace Modules\Shop\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class AttributeTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'code' => $this->code,
	        'values' => $this->parseValue($this->attributeValues),
	        'company_id' => $this->company_id,
            'shop_id' => $this->shop_id,
            'list_attribute_value' =>$this->attributeValues()->select('name')->get(),
             'urls' => [
                'delete_url' => route('api.attribute.destroy', $this->id),
            ],

        ];


        return $data;
    }
    public function parseValue($values) {
    	foreach ($values as $value) {
    		$value['price']=0;
    		$value['sale_price']=0;
    		$value['amount']=0;
	    }
    	return $values;
    }


}
