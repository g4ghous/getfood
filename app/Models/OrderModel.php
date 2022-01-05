<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model
{
    protected $table = "order";
    protected $fillable=['id','status','total','name','ord_id','timer','user_id','table_no'];
}
