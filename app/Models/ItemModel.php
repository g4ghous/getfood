<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemModel extends Model
{
    protected $table = "item";
    protected $fillable=['id','i_image','i_name','restaurant_name','price','category_id'];
}
