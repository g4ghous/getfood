<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemModel extends Model
{
    protected $table = "order_item";
    protected $fillable=['id','image','name','quantity','price','user_id','order_id','item_id'];


}
