<?php

namespace App\Http\Controllers;
use App\Models\User;
use Twilio\Rest\Client;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public $successStatus = 200;
/**
 * login api
 *
 * @return \Illuminate\Http\Response
 */
    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('MyApp')->accessToken;
            return response()->json(['success' => $success,'Data' => $user], $this->successStatus);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }
    public function resLogin()
    {
        if (Auth::attempt(['name' => request('name'), 'password' => request('password')])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('MyApp')->accessToken;
            return response()->json(['success' => $success,'Data' => $user], $this->successStatus);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }
/**
 * Register api
 *
 * @return \Illuminate\Http\Response
 */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone'=> 'required|min:11|max:13',
            'password' => 'required|min:8',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $user_type = $request['user_type'];

        if($user_type == 'restaurant'){

            $last_restaurant_data = User::where('user_type','restaurant')
                    ->orderBy('id', 'desc')
                    ->first();
            

            $prefix = 'RES-';
            if ($last_restaurant_data !=''){
                $lastid = $last_restaurant_data['id'];
                $id = $lastid + 1;
                // $id = 000001;
                $restaurant_id = $id;
                $restaurant_show_id = $prefix . str_pad($id, 4, '0', STR_PAD_LEFT);
    
            }
            else{
                $restaurant_id = 1;
                $restaurant_show_id = $prefix . str_pad(1, 4, '0', STR_PAD_LEFT);
            }

            $user= new User;
            $user->name = request('name');
            $user->phone = request('phone');
            $user->user_type = request('user_type');
            $user->email = request('email');
            $user->password = bcrypt(request('password'));
            // $user->restaurant_id = $restaurant_id;
            $user->restaurant_show_id = $restaurant_show_id;
            $user->save();
            
            $success['token'] = $user->createToken('MyApp')->accessToken;
            $success['name'] = $user->name;
            

        }

        else if($user_type == 'admin'){

            $last_admin_data = User::where('user_type','admin')
                    ->orderBy('id', 'desc')
                    ->first();

            $prefix = 'ADM-';
            if ($last_admin_data !=''){
                $lastid = $last_admin_data['id'];
                $id = $lastid + 1;
                // $id = 000001;
                $admin_show_id = $prefix . str_pad($id, 4, '0', STR_PAD_LEFT);
    
            }
            else{
                $admin_show_id = $prefix . str_pad(1, 4, '0', STR_PAD_LEFT);
            }


            $user= new User;
            $user->name = request('name');
            $user->phone = request('phone');
            $user->user_type = request('user_type');
            $user->email = request('email');
            $user->password = bcrypt(request('password'));
            $user->admin_show_id = $admin_show_id;
            $user->save();
            
            $success['token'] = $user->createToken('MyApp')->accessToken;
            $success['name'] = $user->name;
            

        }

        else if($user_type == 'customer'){

            $last_cus_data = User::where('user_type','customer')
                    ->orderBy('id', 'desc')
                    ->first();

           
            $prefix = 'CUS-';
            if ($last_cus_data !=''){
                $lastid = $last_cus_data['id'];
                $id = $lastid + 1;
                // $id = 000001;
                $cus_show_id = $prefix . str_pad($id, 4, '0', STR_PAD_LEFT);
    
            }
            else{
                $cus_show_id = $prefix . str_pad(1, 4, '0', STR_PAD_LEFT);
            }

            $user= new User;
            $user->name = request('name');
            $user->phone = request('phone');
            $user->user_type = request('user_type');
            $user->email = request('email');
            $user->password = bcrypt(request('password'));
            $user->customer_show_id = $cus_show_id;
            $user->save();
            
            $success['token'] = $user->createToken('MyApp')->accessToken;
            $success['name'] = $user->name;
            

        }

        else{
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);
            $success['token'] = $user->createToken('MyApp')->accessToken;
            $success['name'] = $user->name;
            
        }
        return response()->json(['success' => $success,'Data' => $user], $this->successStatus);

        
    }
/**
 * details api
 *
 * @return \Illuminate\Http\Response
 */
    public function update(Request $request)
    {
        $id = Auth::id();
        $user = User::find($id);
        $rule = [

            'name'=>'required',
            'email'=>'required',
            'phone'=>'required',
            
        ];
        $validator = Validator::make($request->all(),$rule);
        if($validator->fails()){

            return response()->json($validator->errors(),400);
        }
        if(is_null($user)){
            return response()->json(['message' => 'Record not Found!'],404);
        }

        $image= $request->file('image');
        if($image !='')
        {
            

            $uploadFolder = 'user_images';
            $image_uploaded_path = $image->store($uploadFolder, 'public');
            $image_url = Storage::disk('public')->url($image_uploaded_path); 
            
            $input_data=array(
            
                'image' => $image_url,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'user_status' => $request->user_status,

            );
        }
        else{

            $input_data=array(
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'user_status' => $request->user_status,
            );

        }
        
        $user->update($input_data);
        return response()->json($user,200);
    }


    public function userbyID($id){
        $user = User::where('id',$id)->get();
        if(is_null($user)){
            return response()->json(['message' => 'Record not Found!'],404);
        }
        return response()->json($user,200);
    }

    // public function restaurantbyID($id){
    //     $user = User::where('restaurant_id',$id)->get();
    //     if(is_null($user)){
    //         return response()->json(['message' => 'Record not Found!'],404);
    //     }
    //     return response()->json($user,200);
    // }

    public function show()
    {
        $user=User::where('user_type','admin')
            ->orWhere('user_type','customer')
            ->get();
        return response()->json($user,200);
    }
    public function resShow()
    {
        $user=User::where('user_type','restaurant')
            ->get();
        return response()->json($user,200);
    }

    
    public function user_destroy($id)
    {
        $user = User::find($id);
        if(is_null($user)){
            return response()->json(['message' => 'Record not Found!'],404);
        }
        $user->delete();
        return response()->json(['message' => 'Successfully Deleted!'],200);
    }

    // public function sendOTP(Request $request){
        
    //     $phone = $request['phone'];
    //     $token = getenv("TWILIO_AUTH_TOKEN");
    //     $twilio_sid = getenv("TWILIO_SID");
    //     $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
    //     $twilio = new Client($twilio_sid, $token);
    //     $twilio->verify->v2->services($twilio_verify_sid)->verifications->create($phone, "sms");

    //     return response()->json(['message' => 'Verified SMS sent'],200);

    // }

    // public function verifyOTP(Request $request)
    // {
    //     $data = $request->validate([
    //         'verification_code' => ['required', 'numeric'],
    //         'phone' => ['required', 'string'],
    //     ]);
    //     /* Get credentials from .env */
    //     $token = getenv("TWILIO_AUTH_TOKEN");
    //     $twilio_sid = getenv("TWILIO_SID");
    //     $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
    //     $twilio = new Client($twilio_sid, $token);
    //     $verification = $twilio->verify->v2->services($twilio_verify_sid)
    //         ->verificationChecks
    //         ->create($data['verification_code'], array('to' => $data['phone']));
    //     if ($verification->valid) {
    //         $user = tap(User::where('phone', $data['phone']))->update(['isVerified' => true]);
    //         /* Authenticate user */
    //         // Auth::login($user->first());
    //         // return redirect()->route('home')->with(['message' => 'Phone number verified']);
    //         return response()->json(['message' => 'Phone number verified'],200);
    //     }
    //     // return back()->with(['phone' => $data['phone'], 'error' => 'Invalid verification code entered!']);
    //     return response()->json(['phone' => $data['phone'], 'error' => 'Invalid verification code entered!'],400);
    // }
}
