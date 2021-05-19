<?php

namespace Modules\Admin\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class FbNotificationTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'topic' => $this->topic,
            'scheduled_at' => $this->scheduled_at,
            'sent' => $this->sent,
            'sent_name' => $this->sent_name,
            'sent_color' => $this->sent_color,
            'updated_at' => $this->updated_at->format('H:i d/m/Y'),

             'urls' => [
                'delete_url' => route('api.fbnotification.destroy', $this->id),
            ],

        ];


        return $data;
    }


}
