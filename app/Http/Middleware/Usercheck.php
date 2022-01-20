<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Usercheck {

       /**
        * Handle an incoming request.
        *
        * @param  \Illuminate\Http\Request  $request
        * @param  \Closure  $next
        * @return mixed
        */
       public function handle($request, Closure $next) {
              $ac_status = Auth::user()->is_ac_status; //0= active, 1=deactive
              $is_blocked = Auth::user()->is_blocked; //	0 = Unblokes,1= Bloked
              if ($ac_status == 1) {
                     $response = array('status' => 0, 'msg' => 'Your account has been deactivated!');
//                     return json_encode($response);
                     return response()->json($response, 200);
              } else if ($is_blocked == 1) {
                     $response = array('status' => 0, 'msg' => 'User is blocked by admin');
//                     return json_encode($response);
                     return response()->json($response, 200);
              } else {
                     return $next($request);
              }
       }

}
