<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::post('login', 'UserController@login');
Route::post('res_login', 'UserController@resLogin');
Route::post('register', 'UserController@register');
Route::get('user_show', 'UserController@show');
Route::group(['middleware' => 'auth:api'], function(){

    Route::post('user_update', 'UserController@update');
    Route::get('res_show', 'UserController@resShow');
    Route::get('user_show/{id}', 'UserController@userbyID');
    Route::delete('user_delete/{id}','UserController@user_destroy');

    Route::get('category_showbyid/{id}','category@categorybyID');
    Route::get('category_show/{restaurant_name}','category@show');
    Route::post('category_add','category@create');
    Route::post('category_edit/{id}','category@category_update');
    Route::delete('category_delete/{id}','category@category_destroy');

    Route::post('item_add','item@create');
    Route::post('item_edit/{id}','item@item_update');
    Route::delete('item_delete/{id}','item@item_destroy');
    Route::get('item_show/{restaurant_name}/{category_id}','item@show');
    Route::get('item_all/{restaurant_name}','item@allShow');
    Route::get('item_showbyid/{id}','item@itembyID');

    Route::get('orders_show','order@show');
    Route::get('orders_show/{ord_id}','order@orderbyID');
    Route::post('orders_add','order@create');
    Route::post('orders_edit/{id}','order@order_update');
    Route::delete('orders_delete/{id}','order@order_destroy');
    
    Route::get('orderitem_show','orderitem@show');
    Route::post('orderitem_add','orderitem@create');
    Route::get('orderitem_search/{order_id}','orderitem@search');
    // Route::get('orderitem_history','orderitem@previous_orders');
    Route::delete('orderitem_delete/{id}','orderitem@orderitem_destroy');


    
});
