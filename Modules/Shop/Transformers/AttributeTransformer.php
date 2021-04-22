<?php

namespace Modules\Shop\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class AttributeTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
	        'values' => $this->parseValue($this->attributeValues)

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
