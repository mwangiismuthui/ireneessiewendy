<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\UsesUUID;
use Illuminate\Database\Eloquent\SoftDeletes;
class Post extends Model
{
    use SoftDeletes, UsesUUID;

    protected $dates = [
        'converted_for_streaming_at',
    ];
 
    protected $guarded = [];
    //
}
