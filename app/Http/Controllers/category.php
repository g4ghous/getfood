<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoryModel;
use Illuminate\Support\Facades\Storage;
use Validator;

class category extends Controller
{
    public function create(Request $request)
    {
        $rule = [
            'c_image'=>'required|image:jpeg,png,jpg,gif,svg|max:2048',
            'c_name'=>'required',
            'restaurant_name'=>'required'
 
            
        ];
        $validator = Validator::make($request->all(),$rule);
        if($validator->fails()){

            return response()->json($validator->errors(),400);
        }
        $uploadFolder = 'category_images';
        $image= $request->file('c_image');
        $image_uploaded_path = $image->store($uploadFolder, 'public');
        $image_url = Storage::disk('public')->url($image_uploaded_path);

        $category= new CategoryModel;
        $category->c_image = $image_url;
        $category->c_name = request('c_name');
        $category->restaurant_name = request('restaurant_name');

        $category->save();
        return response()->json($category,201);
    }

    public function category_update(Request $request, $id)
    {
        

        $category = CategoryModel::find($id);
        $rule = [
            'c_image'=>'required|image:jpeg,png,jpg,gif,svg|max:2048',
            'c_name'=>'required',

        ];
        $validator = Validator::make($request->all(),$rule);
        if($validator->fails()){

            return response()->json($validator->errors(),400);
        }
        if(is_null($category)){
            return response()->json(['message' => 'Record not Found!'],404);
        }

        $image= $request->file('c_image');
        if($image !='')
        {
            
            $uploadFolder = 'category_images';
            $image_uploaded_path = $image->store($uploadFolder, 'public');
            $image_url = Storage::disk('public')->url($image_uploaded_path); 
            
            $input_data=array(
               
                'c_image' => $image_url,
                'c_name' => $request->c_name,
                'restaurant_name' => $request->restaurant_name,

    
            );
        }
        else{

            $input_data=array(
                'c_name' => $request->c_name,
                'restaurant_name' => $request->restaurant_name,
            );

        }
        
        $category->update($input_data);
        return response()->json($category,200);
    }

    public function show($restaurant_name)
    {
        $category= CategoryModel::where('restaurant_name',$restaurant_name)->get();
        return response()->json($category,200);
    }

    public function categorybyID($id){
        $category = CategoryModel::find($id);
        if(is_null($category)){
            return response()->json(['message' => 'Record not Found!'],404);
        }
        return response()->json($category,200);
    }

    public function category_destroy(request $request,$id)
    {
        $category = CategoryModel::find($id);
        if(is_null($category)){
            return response()->json(['message' => 'Record not Found!'],404);
        }
        $category->delete();
        return response()->json(['message' => 'Successfully Deleted!'],200);
    }
}
