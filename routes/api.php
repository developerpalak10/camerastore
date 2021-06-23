<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', 'Api\UsersController@register');
Route::post('login', 'Api\UsersController@login');
Route::get('all-category', 'Api\ProductController@category_list');
Route::get('all-product', 'Api\ProductController@product_list');
Route::post('get-product-specfic', 'Api\ProductController@get_product_bycat_id');

Route::group(['middleware' => 'auth:api'], function(){

Route::post('add-to-cart', 'Api\ProductController@add_to_cart');
Route::post('get-cart-specfic-user', 'Api\ProductController@get_cart_detail');

});
