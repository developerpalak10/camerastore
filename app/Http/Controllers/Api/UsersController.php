<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Response;
use Auth;

class UsersController extends Controller
{
    public function register(Request $request)
    {
       try
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required|regex:/^[a-zA-Z\s]*$/|max:255',
                'email'=>'required|email|unique:users,email',
                'password' => 'required|min:2|max:16',
                'confirm_password' =>'required|same:password',
            ]);
            if ($validator->fails()) {
                $failedRules = $validator->getMessageBag()->toArray();
                $errorMsg = "";
                if(isset($failedRules['name']))
                $errorMsg = $failedRules['name'][0];
                if(isset($failedRules['email']))
                $errorMsg = $failedRules['email'][0];
                if(isset($failedRules['password']))
                $errorMsg = $failedRules['password'][0];
                if(isset($failedRules['confirm_password']))
                $errorMsg = $failedRules['confirm_password'][0];               
                return Response::json(['status'=>'error','message'=>$errorMsg]);
            }
            else
            {

                $input = $request->all();
                $input['password'] = bcrypt($input['password']);
                $user = User::create($input);
                if($user)
                {
                   
                    $token = $user->createToken('registertoken')->accessToken;
                    return Response::json(['status'=> 'success','message'=>'Registered Successfully']);
                }
                else
                {
                     return Response::json(['status'=> 'error','message'=>'Something server error please try again']);
                }
            }
        }
        catch(QueryException $ex){ 
          return Response::json(['status'=> 'error','message'=>$ex->getMessage()]);
        }
    }

       public function login(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);
            if ($validator->fails()) {
                $failedRules = $validator->getMessageBag()->toArray();
                $errorMsg = "";
                 if(isset($failedRules['email']))
                $errorMsg = $failedRules['email'][0];
                if(isset($failedRules['password']))
                $errorMsg = $failedRules['password'][0];
                return Response::json(['status'=>'error','message'=>$errorMsg]);
            }
            else
            {

                if(Auth::attempt(['email' => request('email'), 'password' => request('password')]))
                {
                    $user = Auth::user();
                    Auth::user()->AauthAcessToken()->delete();
                    $token =  $user->createToken('MyApp')->accessToken;
                    $message = 'Logged in successfully';
                    $details = User::select('*')->where('id',$user->id)->first();
                    return Response::json(
                        ['status'=>'success','token' => $token,'message'=>'Logged in successfully','data'=>$details]);
                    
                   
                }
                else{
                    return Response::json(['status'=>'error','message'=>'Invalid credentials. Please try again.']);
                }
            }
        }
        catch(QueryException $ex){ 
          return Response::json(['status'=> 'error','message'=>$ex->getMessage()]);
        }
    }

}
