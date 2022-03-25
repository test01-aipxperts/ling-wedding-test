<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['title','description','image','status'];

    public function getImageAttribute($value){
        if(file_exists(public_path('storage/'.$value)) && $value){
            return asset('storage/'.$value);     
        }
        return '';
    }

    public function pickupproduct(){
        return $this->hasMany(UserProduct::class,'product_id');
    }
}
