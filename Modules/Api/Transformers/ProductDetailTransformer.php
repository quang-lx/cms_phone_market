<?php


namespace Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Modules\Mon\Entities\Product;
use Modules\Mon\Entities\Rating;

class ProductDetailTransformer extends JsonResource {


	public function toArray($request) {
		list ($attribute, $attributeValue ) = $this->getProductAttribute($this->attributes, $this->attributeValues);
		$data = [
			'id' => $this->id,
			'sku' => $this->sku,
			'name' => $this->name,
			'price' => $this->price,
			'sale_price' => $this->price - ($this->sale_price * $this->price)/100,
			'discount' => $this->sale_price,
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
			'shop' => $this->shop ? new ShopFullTransformer($this->shop) : null,
			'attribute_title' => optional($attribute)->name,
			'attributes' => $attributeValue,
			'detail_information' => $this->getDetailInformation($this->pinformation),

			'files' => $this->files ? MediaShortTransformer::collection($this->files) : null,
			'product_related' => $this->getProductRelated($this->id),
			'product_suggested' => $this->getProductSuggested($this->id),
			'rating_avg' => $this->rating_avg,
			'rating_user' => $this->rating_user,
			'ratings' => $this->getRating($this->id),
			'rating_group' => $this->ratingGroup($this->id)
		];


		return $data;
	}

	public function getProductAttribute($productAttributes,$productValues) {
		$result = [];
		$attribute = null;
		foreach ($productAttributes as $item) {
			$attribute = $item;
		}
		if ($attribute) {
			foreach ($productValues as $item) {
				if ($item->attribute_id == $attribute->id) {
					$result[] = [
						'product_attribute_value_id' => $item->pivot->id,
						'value_id' => $item->id,
						'value_name' => $item->name,
						'price' => $item->pivot->price,
						'sale_price' => $item->pivot->price - ($item->pivot->price*$item->pivot->sale_price/100),
						'amount' => $item->pivot->amount,
					];
				}

			}
		}

		return [$attribute, $result];
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

	public function statisticRating($id) {
		$query = Rating::query();
		$query->where('product_id', $id)
			->groupBy('rating')
			->orderBy('rating')
			->select('rating', DB::raw('count(1) as total'));
		$data = $query->get();
		return $data;
	}
	public function statisticRatingFile($id) {
		$query = Rating::query();
		$query->where('product_id', $id)->whereHas('files');
		$data = $query->count();
		return $data;
	}
	public function ratingGroup($id) {
		$group=[];
		$total = Rating::query()->where('product_id', $id)->count();
		$group[] = [
			'rating' => 'T???t c???',
			'total' => $total
		];
		$mediaRating = $this->statisticRatingFile($id);
		$group[] = [
			'rating' => 'media',
			'total' => $mediaRating
		];
		$ratings = $this->statisticRating($id);
		for ($i=1; $i<=5; $i++) {
			if (!$ratings->contains('rating', $i)) {
				$ratings->push(['rating'=> $i, 'total'=> 0]);
			}
		}
		$ratingsGroup = $ratings->sortByDesc('rating')->values()->all();
		return array_merge($group, $ratingsGroup);

	}
}
