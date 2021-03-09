<?php

namespace Modules\Admin\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;


class CategoryTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'updated_at'=> $this->updated_at->format('d-m-Y'),

             'type'=> $this->type,
            'slug'=> $this->slug,
            'parent_id'=> $this->parent_id,
            'order'=> $this->order,
            'description'=> $this->description,
            'status'=> $this->status,
            'thumbnail' => $this->thumbnail,
            'urls' => [
                'delete_url' => route('api.category.destroy', $this->id),
            ],

        ];


        return $data;
    }


}
