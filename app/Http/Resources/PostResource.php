<?php

namespace App\Http\Resources;

use App\Post;
use App\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

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
        $owner = User::find($this->user_id);
        $post = Post::find($this->id);
        return [
            'id'=>$this->id,
            'user_id'=>$this->user_id,
            'status'=>$this->status,
            'type'=>$this->type,
            'text'=>$this->text,
            'tags'=>$this->tags,
            'location'=>$this->location,
            'profile_picture'=>url('UserProfilePics',$this->user->profile_pic_path),
            'views'=>$this->views,
            'has_link'=>$this->has_link,
            'background_color'=>$this->background_color,
            'backlink'=>$this->backlink,
            'thumbnails'=>$this->thumbnails,
            'videopreview'=>$this->videopreview,
            'likes'=>$this->likers()->count(),
            'shares'=>$this->sharesCount()->exists() ?  $this->sharesCount->pluck('shares')->first() : 0,
            'downloads'=>$this->downloadsCount()->exists() ?  $this->downloadsCount->pluck('downloads')->first() : 0,
            'is_following_owner'=> Auth::user()->isFollowing($owner) ? 1 : 0,
            'is_liked_post'=>Auth::user()->hasLiked($post) ? 1 : 0,
            'file_path'=> $this->type == 'video' ? url('Postvideos', $this->file_path) :  ($this->type == 'image' ? url('Postimages', $this->file_path) : null),
            'updated_at'=>$this->updated_at->format('d M, yy'),
         ];
    }
}
