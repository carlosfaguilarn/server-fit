<?php
  namespace App\Http\Middleware;
  use Closure;

  class CorsMiddleware{
    public function handle($request, Closure $next){
       return $next($request)
         //->header('Access-Control-Allow-Origin','https://roan-6bec8.firebaseapp.com')
         //->header('Access-Control-Allow-Headers', 'Authorization, X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Allow-Request-Method')
         //->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE')
         ->header('Allow', 'GET, POST, OPTIONS, PUT, DELETE');
   }
}
