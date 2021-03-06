<?php

namespace Modules\Admin\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class AccountTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'birthday' => $this->birthday,
            'gender' => $this->gender,
            'avatar' => $this->avatar,
            'email' => $this->email,
            'phone' => $this->phone,
            'rank_id' => $this->rank_id,
            'rank_point' => $this->rank_point,
            'rank_name' => isset($this->rank)?$this->rank->name:'',
            'created_at' => $this->created_at->format('H:i d/m/Y'),
            'status' => $this->status,
            'status_name' => $this->status_name,
            'status_color' => $this->status_color,
            'updated_by' => optional($this->editor)->name,
            'updated_at' => $this->updated_at->format('H:i d/m/Y'),
            'create_category' => 'Tạo Chuyên mục ',
            'update_category' => 'Cập nhật',


             'urls' => [
                'delete_url' => route('api.account.destroy', $this->id),
            ],

        ];


        return $data;
    }


}
