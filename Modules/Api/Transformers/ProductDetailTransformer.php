<?php


namespace Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Mon\Entities\Product;
use Modules\Mon\Entities\Rating;

class ProductDetailTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'sku' => $this->sku,
            'name' => $this->name,
            'price' => $this->price,
            'sale_price' => $this->sale_price,
            'discount' => 15,
	        'amount' => $this->amount,
	        'description' => $this->description,
	        'p_weight' => $this->p_weight,
	        's_long' => $this->s_long,
	        's_width' => $this->s_width,
	        's_height' => $this->s_height,
	        'brand_id' => $this->brand_id,
	        'fix_time' => $this->fix_time,
	        'warranty_time' => $this->warranty_time,
	        'type' => $this->type,
	        'brand_name' => optional($this->brand)->name,
	        'shop' => $this->shop? new ShopTransformer($this->shop): null,
	        'attributes' => $this->getProductAttribute($this->attributeValues),
	        'detail_information' => $this->getDetailInformation($this->pinformation),

	        'files' => $this->files?  MediaShortTransformer::collection($this->files): null,
	        'product_related' => $this->getProductRelated($this->id),
	        'product_suggested' => $this->getProductSuggested($this->id),
	        'rating_avg' => $this->rating_avg,
	        'rating_user' => $this->rating_user,
	        'ratings' => $this->getRating($this->id),
        ];


        return $data;
    }
    public function getProductAttribute($productValues) {
		$result = [];
		foreach ($productValues as $item) {
			$result[] = [
				'value_id' => $item->id,
				'value_name' => $item->name,
				'price' => $item->pivot->price,
				'sale_price' => $item->pivot->sale_price,
				'amount' => $item->pivot->amount,
			];
		}
		return $result;
    }
	public function getDetailInformation($pinformation) {
		$result = [];
		foreach ($pinformation as $item) {
			$result[] = [
				'information_id' => $item->id,
				'name' => $item->title,
				'value' => $item->pivot->value,
			];
		}
		return $result;
	}
	public function getRating($id) {
    	$query = Rating::query();
    	$query->where('product_id', $id)
		    ->orderBy('created_at', 'desc')
		    ->limit(2);
    	$data = $query->get();
    	return RatingTransformer::collection($data);
	}
	//TODO
	public function getProductRelated($id) {
		$query = Product::query();
		$query->where('id', '!=', $id);
		$data = $query->limit(10)->get();
		return ProductTransformer::collection($data);
	}
	//TODO
	public function getProductSuggested($id) {
		$query = Product::query();
		$query->where('id', '!=', $id);
		$data = $query->limit(10)->get();
		return ProductTransformer::collection($data);
	}

}
