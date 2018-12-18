<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\User;
use Illuminate\Http\Request;
use Hash;
use Auth;

class UserController extends BaseController{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function login(Request $request){
      if(Auth::attempt(['email' => $request['email'], 'password'=>$request['password']])){
        $user = User::where('email', $request['email'])->first();
        return response()->json([
          "user" => $user,
          "nutrientes_diarios" => User::calculoNutrimental($user)
        ], 200);
      }else{
        return response()->json(["error" => "Usuario o contraseña incorrecta"], 404);
      }
    }

    public function register(Request $request){
      $data = $request->all();
      $data['password'] = Hash::make($request->input('password'));
      $user = User::create($data);

      if($user){
        return response()->json(["user" => $user], 200);
      }else{
        return response()->json(["error" => "No se creó el usuario"], 403);
      }
    }

    public function peso(Request $request){
      $user = User::find($request->id);
      $user->peso = $request->input('peso');
      if($user->save()){
        $nutrientes = User::calculoNutrimental($user);
        return response()->json(["nutrientes_diarios" => $nutrientes], 200);
      }else{
        return response()->json(["error" => "No se pudo actualizar el peso"], 500);
      }
    }
}
