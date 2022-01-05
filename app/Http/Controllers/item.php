<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemModel;
use Illuminate\Support\Facades\Storage;
use Validator;

class item extends Controller
{
    public function create(Request $request)
    {
        $rule = [
            'i_image'=>'required|image:jpeg,png,jpg,gif,svg|max:2048',
            'i_name'=>'required',
            'price'=>'required',
            'category_id'=>'required',
            'restaurant_name'=>'required',
            
        ];
        $validator = Validator::make($request->all(),$rule);
        if($validator->fails()){

            return response()->json($validator->errors(),400);
        }
        $image= $request->file('i_image');
        $uploadFolder = 'item_images';
        
        $image_uploaded_path = $image->store($uploadFolder, 'public');
        $image_url = Storage::disk('public')->url($image_uploaded_path);

        $item= new ItemModel;
        $item->i_image = $image_url;
        $item->i_name = request('i_name');
        $item->price = request('price');
        $item->category_id = request('category_id');
        $item->restaurant_name = request('restaurant_name');

        $item->save();
        
        return response()->json($item,201);
    }

    public function item_update(Request $request, $id)
    {
        $item = ItemModel::find($id);
        $rule = [
            'i_image'=>'image:jpeg,png,jpg,gif,svg|max:2048',
            'i_name'=>'required',
            'price'=>'required',
            'category_id'=>'required',
            'restaurant_name'=>'required',
            
        ];
        $validator = Validator::make($request->all(),$rule);
        if($validator->fails()){

            return response()->json($validator->errors(),400);
        }
        if(is_null($item)){
            return response()->json(['message' => 'Record not Found!'],404);
        }

        $image= $request->file('i_image');
        if($image !='')
        {
            

            $uploadFolder = 'item_images';
        
              $image_uploaded_path = $image->store($uploadFolder, 'public');
             $image_url = Storage::disk('public')->url($image_uploaded_path);
            
            $input_data=array(
               
                'i_image' => $image_url,
                'i_name' => $request->i_name,
                'price'=>$request->price,
                'category_id'=>$request->category_id,
                'restaurant_name'=>$request->restaurant_name,
                
    
            );
        }
        else{

            $input_data=array(
                'i_name' => $request->i_name,
                'price'=>$request->price,
                'category_id'=>$request->category_id,
                'restaurant_name'=>$request->restaurant_name,
            );

        }
        $item->update($input_data);
        return response()->json($item,200);
    }

    public function show($restaurant_name,$category_id)
    {
        $item= ItemModel::where('restaurant_name',$restaurant_name)
                        ->where('category_id',$category_id)->get();
        return response()->json($item,200);
    }
    public function allShow($restaurant_name)
    {
        $item= ItemModel::where('restaurant_name',$restaurant_name)
                        ->get();
        return response()->json($item,200);
    }


    public function itembyID($id){
        $item = ItemModel::find($id);
        if(is_null($item)){
            return response()->json(['message' => 'Record not Found!'],404);
        }
        return response()->json($item,200);
    }

    public function item_destroy(request $request,$id)
    {
        $item = ItemModel::find($id);
        if(is_null($item)){
            return response()->json(['message' => 'Record not Found!'],404);
        }
        $item->delete();
        return response()->json(['message' => 'Successfully Deleted!'],200);
    }

}
