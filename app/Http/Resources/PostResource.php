<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'user_id'=>$this->user_id,
            'status'=>$this->status,
            'type'=>$this->type,
            'tags'=>$this->tags,
            'location'=>$this->location,
            'views'=>$this->views,
            'has_link'=>$this->has_link,
            'background_color'=>$this->background_color,
            'backlink'=>$this->backlink,
            'thumbnails'=>$this->thumbnails,
            'videopreview'=>$this->videopreview,
            'is_following_owner'=>$this->videopreview,
            'is_liked_post'=>$this->videopreview,
            'file_path'=> $this->type == 'video' ? url('Postvideos', $this->file_path) :  ($this->type == 'image' ? url('Postimages', $this->file_path) : null),
            'updated_at'=>$this->updated_at->format('d M, yy'),
         ];
    }
}
