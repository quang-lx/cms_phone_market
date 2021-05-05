<?php
namespace Modules\Admin\Transformers\Auth;
use Illuminate\Http\Resources\Json\JsonResource;


class UserFullTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'username' => $this->username,
            'name' => $this->name,
            'email' => $this->email,
            'type' => $this->type,
            'status' => $this->status,
            'shop_id' => $this->shop_id,
            'phone' => optional($this->profile)->phone,

            'created_at' => optional($this->created_at)->format('d-m-Y'),
            'updated_at' =>optional($this->updated_at)->format('d-m-Y'),
            'roles' => $this->getCheckedRoles(),
             'urls' => [
                    'delete_url' => route('api.users.destroy', $this->id),
                ],
        ];


        return $data;
    }
    private function getCheckedRoles(){
        return $this->roles()->get()->pluck('id');
    }
}
