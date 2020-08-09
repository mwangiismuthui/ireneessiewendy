<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Overtrue\LaravelLike\Traits\Likeable;
use App\Traits\UsesUUID;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;
use App\Share;
use App\Downlod;

class Post extends Model
{
    use SoftDeletes, UsesUUID;

    use Likeable;
    protected $dates = [
        'converted_for_streaming_at',
    ];

    protected $guarded = [];
    //
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sharesCount()
    {
        return $this->hasOne(Share::class);
    }
    public function downloadsCount()
    {
        return $this->hasOne(Downlod::class);
    }
}
