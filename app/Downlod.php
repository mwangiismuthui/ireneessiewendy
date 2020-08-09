<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


use App\Traits\UsesUUID;
use Illuminate\Database\Eloquent\SoftDeletes;

class Downlod extends Model
{
    use SoftDeletes, UsesUUID;
}
