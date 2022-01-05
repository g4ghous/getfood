<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\OrderModel;
use Validator;
use App\Models\User;
class order extends Controller
{
    public function create(Request $request)
    {
        $rule = [
           
            'status'=>'required',
            'total'=>'required',
            'name'=>'required',
            'table_no'=>'required',
            
            
            
        ];
        $validator = Validator::make($request->all(),$rule);
        if($validator->fails()){

            return response()->json($validator->errors(),400);
        }
        $lastdata = OrderModel::orderBy('id', 'desc')->first();
        $prefix = 'EASE-';
        if ($lastdata !=''){
            $lastid = $lastdata['id'];
            $id = $lastid + 1;
            // $id = 000001;
            $exportId = $prefix . str_pad($id, 4, '0', STR_PAD_LEFT);

        }
        else{
            $exportId = $prefix . str_pad(000001, 4, '0', STR_PAD_LEFT);
        }
        
        
        $id = Auth::id();
       
        $order= new OrderModel;
        $order->ord_id = $exportId;
        $order->status = request('status');
        $order->total =request('total');
        $order->name = request('name');
        $order->table_no =request('table_no');
        $order->user_id = $id;
        $order->save();
         
        return response()->json($order,201);
       
    }

    public function order_update(Request $request, $id)
    {
        $order = OrderModel::find($id);
        $rule = [
            
            'status'=>'required',
            'timer'=>'required',
        ];
        $validator = Validator::make($request->all(),$rule);
        if($validator->fails()){

            return response()->json($validator->errors(),400);
        }
        if(is_null($order)){
            return response()->json(['message' => 'Record not Found!'],404);
        }
        
        $id = Auth::id();
      
            
        $input_data=array(
            
            'status' =>  $request->status,
            'timer' =>  $request->timer,
            
        );
        $order->update($input_data);
        return response()->json($order,200);

    }

    public function show()
    {
       
        return response()->json(OrderModel::get() ,200);
        // return response()->json(OrderModel::get(),200);
    }

    public function orderbyID($ord_id){
        $order = OrderModel::where('ord_id',$ord_id)->get();
        if(is_null($order)){
            return response()->json(['message' => 'Record not Found!'],404);
        }
        return response()->json($order,200);
    }


    public function order_destroy(request $request,$id)
    {
        $order = OrderModel::find($id);
        if(is_null($order)){
            return response()->json(['message' => 'Record not Found!'],404);
        }
        $order->delete();
        return response()->json(['message' => 'Successfully Deleted!'],200);
    }
}
