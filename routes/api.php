<?php

use Illuminate\Http\Request;

Route::get('hola', function(){
  return response()->json([
    "response" => "Hola mundo"
  ], 200);
});

Route::post('registro', 'UserController@register');
Route::post('login', 'UserController@login');
Route::put('peso/{id}', 'UserController@peso');
