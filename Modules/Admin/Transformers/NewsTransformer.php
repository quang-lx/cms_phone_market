<?php

namespace Modules\Admin\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Modules\Mon\Entities\NewsTag;
use Spatie\Permission\Models\Permission;


class NewsTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->content,
            'category_id' => $this->category_id,
            'category' => $this->category,
            'status' => $this->status,
            'author' => $this->author,
            'slug' => $this->slug,

            'thumbnail' => $this->thumbnail,

            'updated_at'=> $this->updated_at->format('d-m-Y'),
            'created_by'=> optional($this->creator)->username,

            'type' => $this->type,

            'tags'=> $this->tags,
            'flag_hot'=> $this->flag_hot,
            'flag_featured'=> $this->flag_featured,
            'flag_most_read'=> $this->flag_most_read,
            'flag_video'=> $this->flag_video,
            'flag'=> $this->flag,
            'meta_keywords'=> $this->meta_keywords,
            'meta_title'=> $this->meta_title,
            'meta_description'=> $this->meta_description,
            'like'=> $this->like,
            'view'=> $this->view,
            'share'=> $this->share,
            'sort'=> $this->sort,

             'urls' => [
                'delete_url' => route('api.news.destroy', $this->id),
            ],

        ];


        return $data;
    }


}
