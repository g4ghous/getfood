<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderItemModel;
use App\Models\OrderModel;
use Illuminate\Support\Facades\Storage;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class orderitem extends Controller
{
    public function create(Request $request)
    {
        $rule = [
            'image'=>'required',
            'name'=>'required',
            'quantity'=>'required',
            'price'=>'required',
            'order_id'=>'required',
            'item_id'=>'required',
            
            
        ];
        $validator = Validator::make($request->all(),$rule);
        if($validator->fails()){

            return response()->json($validator->errors(),400);
        }
        
        $id = Auth::id();
        $orderitem= new OrderItemModel;
        $orderitem->image = request('image');
        $orderitem->name = request('name');
        $orderitem->quantity = request('quantity');
        $orderitem->price = request('price');
        $orderitem->order_id = request('order_id');
        $orderitem->item_id = request('item_id');
        $orderitem->user_id = $id;

        $orderitem->save();
        return response()->json($orderitem,201);
    }

    
    public function show()
    {
        return response()->json(OrderItemModel::get(),200);
    }

    public function search($order_id){
        $orderitem = OrderItemModel::where("order_id",$order_id)->get();
        
        if(is_null($orderitem)){
            return response()->json(['message' => 'Record not Found!'],404);
        }
        return response()->json($orderitem,200);
    }
  
   
    public function orderitem_destroy(request $request,$id)
    {
        $orderitem = OrderItemModel::find($id);
        if(is_null($orderitem)){
            return response()->json(['message' => 'Record not Found!'],404);
        }
        $orderitem->delete();
        return response()->json(['message' => 'Successfully Deleted!'],200);
    }
}
