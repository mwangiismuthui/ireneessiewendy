<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\UsesUUID;
class Post extends Model
{
    use SoftDeletes;
    //
}
