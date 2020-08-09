<?php

namespace App\Http\Resources;

use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaderBoardResource extends JsonResource
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
        $user = User::find($this->user_id);
        $followers_count = $user->followers()->count();
        $likes_count = 0;
        foreach($user->posts as $post){
            $likes_count += $post->likers()->count();
        }
        return [
            'user_id'=>$user->id,
            'user_firstname'=>$user->firstname,
            'user_lastname'=>$user->lastname,
            'user_username' => $user->username,
            'leaderboard_id'=>$this->id,
            'lati'=>$this->lati,
            'longi'=>$this->longi,
            'likes_count' => $likes_count,
            'followers_count' => $followers_count,
            'board_score' =>($followers_count * 3) + $likes_count,
        ];
    }
}
