<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TrendingUsersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id'=>$this->id,
            'firstname'=>$this->firstname,
            'lastname'=>$this->lastname,
            'username' => $this->username,
            'profile_pic_path'=> url('UserProfilePics', $this->profile_pic_path), 
            'is_verified'=>$this->is_verified,
            'post_count' => $this->posts->count(),
        ];
    }
}
