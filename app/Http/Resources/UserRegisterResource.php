<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserRegisterResource extends JsonResource
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
            // 'id'=>$this->id,
            'firstname'=>$this->firstname,
            'lastname'=>$this->lastname,
            'username'=>$this->username,
            'email'=>$this->email,
            'created_at'=>$this->created_at->format('d M, yy'),
            'token' =>  $this->createToken('token')->accessToken,
          ];
    }
}
