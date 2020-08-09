<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\UsesUUID;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;

class LeaderBoard extends Model
{
    use SoftDeletes, UsesUUID;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}