<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\User;
use Response;
use Auth;
use App\Category;
use App\Product;
use App\Cart;

class ProductController extends Controller
{
    public function category_list()
    {
      $details  =  Category::get()->toArray();
      if($details)
      {
          return Response::json(['status'=>'success', 'data'=>$details], 200);
      }
      else{
          return Response::json(['status'=>'error','message'=>'Not Found'], 200);
        }
    }

     public function product_list()
    {
      $products  =  Product::with('category_detail')->get()->toArray();
      if($products)
      {
          return Response::json(['status'=>'success', 'data'=>$products], 200);
      }
      else{
          return Response::json(['status'=>'error','message'=>'Product Not Found'], 200);
        }
    }
    public function get_product_bycat_id(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'category_id' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                $failedRules = $validator->getMessageBag()->toArray();
                $errorMsg = "";
                if(isset($failedRules['category_id']))
                $errorMsg = $failedRules['category_id'][0];               
                return Response::json(['status'=>'error','message'=>$errorMsg]);
            }
            else
            {

                $data = Product::with('category_detail')->where('category_id',$request->category_id)->get()->toArray();
                if($data)
                {
                   
                   
                    return Response::json(['status'=> 'success','data'=>$data]);
                }
                else
                {
                     return Response::json(['status'=> 'error','message'=>'No Product Found']);
                }
            }
        }
        catch(QueryException $ex){ 
          return Response::json(['status'=> 'error','message'=>$ex->getMessage()]);
        }
    }

    public function add_to_cart(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                $failedRules = $validator->getMessageBag()->toArray();
                $errorMsg = "";
                if(isset($failedRules['product_id']))
                $errorMsg = $failedRules['product_id'][0];               
                return Response::json(['status'=>'error','message'=>$errorMsg]);
            }
            else
            {
                
                  $user_id=  Auth::user()->id;
                  // check already added
                  $check = Cart::where(['user_id'=>$user_id,'product_id'=>$request->product_id])->exists();
                  if($check)
                  {
                    return Response::json(['status'=> 'success','message'=>'Product Already added to cart']);
                  }
                  $cart = new Cart;
                  $cart->user_id = $user_id;
                  $cart->product_id = $request->product_id;
                    if($cart->save())
                    {
                       
                        return Response::json(['status'=> 'success','message'=>'Product Add to cart Successfully']);
                    }
                    else
                    {
                         return Response::json(['status'=> 'error','message'=>'Something get wrong']);
                    }
                
                
            }
        }
        catch(QueryException $ex){ 
          return Response::json(['status'=> 'error','message'=>$ex->getMessage()]);
        }
    }

        public function get_cart_detail(Request $request)
    {
        
        
        $user_id=  Auth::user()->id;
        $data = Cart::with('product_detail')->where('user_id',$user_id)->get()->toArray();
        if($data)
        {
           
            return Response::json(['status'=> 'success','data'=>$data]);
        }
        else
        {
             return Response::json(['status'=> 'error','message'=>'Cart is Empty']);
        }
                
    }
}
