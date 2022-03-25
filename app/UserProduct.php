<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserProduct extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id','product_id','qty','price'];

    protected $dates = ['deleted_at'];

}
