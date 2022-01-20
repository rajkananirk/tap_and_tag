<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Validator;
use App\User;
use DB;
use Auth;
use App\Model\tbl_social_link;
use App\Model\tbl_user_otp;
use App\Model\tbl_token;
use App\Model\tbl_user_social_link;
use App\Model\tbl_user_custom_social_link;
use App\Model\tbl_user_reciept;
use App\Model\tbl_user_view;
use App\Events\UpdateCount;
use App\Events\SendNumber;
use App\Events\SendVenmo;
use JeroenDesloovere\VCard\VCard;
use Mail;

class UserController extends vCard {

       function index(Request $req, $name) {

              $data = DB::table('users')
                              ->where('username', "$name")
                              ->get()->first();
              if (empty($data)) {
                     return response()->json(['status' => 0, 'msg' => 'Dont Be OverSmart'], 200);
              }

              $data2 = DB::table('tbl_social_link')
                      ->Join('tbl_user_social_link', function ($join) use ($data) {
                             $join->on('tbl_social_link.social_id', '=', 'tbl_user_social_link.social_id');
                             $join->where('tbl_user_social_link.user_id', $data->id);
                      })
                      ->get();
              if (empty($data2)) {
                     $data2 = DB::table('tbl_social_link')
                                     ->Join('tbl_user_social_link', function ($join) use ($data) {
                                            $join->on('tbl_social_link.social_id', '=', 'tbl_user_social_link.social_id');
                                            $join->where('tbl_user_social_link.user_id', $data->id);
                                     })
                                     ->get()->first();
              }
              return view('airpawnd', ['data' => $data, 'social' => $data2,]);
       }

       function register(Request $request) {
              $rule = [
                  'email' => 'required|regex:/(.+)@(.+)\.(.+)/i',
                  'username' => 'required',
                  'password' => 'required',
                  'latitude' => 'required',
                  'longitude' => 'required',
                  'device_token' => 'required',
                  'device_id' => 'required',
                  'device_type' => 'required',
              ];
              $validate = Validator::make(request()->all(), $rule);

              if ($validate->fails()) {
                     return response()->json(['status' => 0, 'msg' => 'validation fail', 'data' => ['errors' => $validate->errors()]], 200);
              }

              $check_mail = User::where('email', $request->input('email'))->get()->first();

              if (!empty($check_mail)) {
                     return response()->json(['status' => 0, 'msg' => 'Email Already Exist',], 200);
              }

              $check_username = User::where('username', $request->input('username'))->get()->first();

              if (!empty($check_username)) {
                     return response()->json(['status' => 0, 'msg' => 'Username Already Exist',], 200);
              }

//              $username = $this->check_user_name($request->input('username'));


              $user = new User;
              $user->username = $request->input('username');
              $user->name = $request->input('username');
              $user->email = $request->input('email');
              $user->password = bcrypt($request->input('password'));
              $user->latitude = $request->input('latitude');
              $user->longitude = $request->input('longitude');
              $user->save();
              $user_id3 = $user->id;

              $device_token = $request->input('device_token');
              $device_type = $request->input('device_type');
              $device_id = $request->input('device_id');

              $data = array(
                  'device_token' => $device_token,
                  'device_type' => $device_type,
                  'device_id' => $device_id,
                  'user_id' => $user_id3,
              );

              $this->add_device($data);

              $user1 = User::where('id', $user_id3)->first();

              $array_merge = array_merge($user1->toArray(), $data);

              $token = $user->createToken('signup')->accessToken;

              $datas = array('token' => $token, 'user' => $array_merge);

              return response()->json(['status' => 1, 'msg' => 'Registration successfully.', 'data' => $datas], 200);
       }

       function login_by_thirdparty(Request $request) {
              $rule = [
                  'thirdparty_id' => 'required',
                  'device_token' => 'required',
                  'device_type' => 'required',
                  'device_id' => 'required',
                  'username' => 'required',
                  'email' => 'required|email',
                  'login_type' => 'required',
              ];

              $validate = Validator::make(request()->all(), $rule);

              if ($validate->fails()) {
                     return response()->json(['status' => 0, 'msg' => 'validation fail', 'data' => ['errors' => $validate->errors()]], 200);
              }
              $check_fb_id = User::where('thirdparty_id', $request->get('thirdparty_id'))->first();

              $username = $this->check_user_name($request->input('username'));

              if (empty($check_fb_id)) {
                     $email = $request->input('email');
                     $check_email = $this->check_email($email);

                     if ($check_email) {
                            return response()->json(['status' => 0, 'msg' => 'Email Already Exist',], 200);
                     }

                     if ($request->hasFile('profile_pic')) {
                            $random_no = $this->get_random_number(6);
                            $imageName = time() . $random_no . '.' . request()->profile_pic->getClientOriginalExtension();
                            request()->profile_pic->move(public_path('uploads'), $imageName);
                            $pro = 'public/uploads/' . $imageName;
                     } else {
                            $pro = "";
                     }


                     $user = new User();
                     $user->thirdparty_id = $request->input('thirdparty_id');
                     $user->username = $username;
                     $user->name = $request->input('username');
                     $user->email = $email;
                     $user->profile_pic = $pro;
                     $user->login_type = $request->input('login_type');
                     $user->save();
                     $user_id = $user->id;

                     $device = array(
                         'user_id' => $user_id,
                         'device_token' => $request->input('device_token'),
                         'device_type' => $request->input('device_type'),
                         'device_id' => $request->input('device_id')
                     );

                     $this->add_device($device);
                     $where['id'] = $user_id;
                     $user_data = User::where($where)->first()->toArray();
                     $res = array_merge($user_data, $device);
                     $success['token'] = $user->createToken('signup')->accessToken;
                     $success['user'] = $res;

                     return response()->json(['status' => 1, 'msg' => 'Login successfully.', 'data' => $success], 200);
              } else {

                     $success = $check_fb_id->createToken('signup')->accessToken;

                     $user = User::select(array('*', 'id as id'))->where(['thirdparty_id' => $request->get('thirdparty_id')])->first()->toArray();

                     User::where(['thirdparty_id' => $request->get('thirdparty_id')])
                             ->update(['name' => $request->input('username')]);

                     $device = array(
                         'user_id' => $user['id'],
                         'device_token' => $request->input('device_token'),
                         'device_type' => $request->input('device_type'),
                         'device_id' => $request->input('device_id')
                     );

                     $this->add_device($device);
                     $user2 = User::select(array('*', 'id as id'))->where(['thirdparty_id' => $request->get('thirdparty_id')])->first()->toArray();

                     $res = array_merge($user2, $device);

                     $datas = array('token' => $success, 'user' => $res);

                     return response()->json(['status' => 1, 'msg' => 'Login successfully.', 'data' => $datas], 200);
              }
       }

       function logout(Request $request) {

              $rule = [
                  'device_id' => 'required',
              ];
              $validate = Validator::make(request()->all(), $rule);

              if ($validate->fails()) {

                     return response()->json(['status' => 0, 'msg' => 'validation fail', 'data' => ['errors' => $validate->errors()]], 401);
              }

              $device_id = $request->input('device_id');

              $accessToken = Auth::user()->token();

              DB::table('oauth_refresh_tokens')
                      ->where('access_token_id', $accessToken->id)
                      ->update([
                          'revoked' => true
              ]);

              $userId = Auth::id();

              Tbl_token::where(['device_id' => $device_id, 'user_id' => $userId])->delete();

              $accessToken->revoke();

              return response()->json(['status' => 1, 'msg' => 'Logout Successfully'], 200);
       }

       function forgot_password(Request $request) {
              $rule = [
                  'email' => 'required|email',
              ];

              $validate = Validator::make(request()->all(), $rule);

              if ($validate->fails()) {
                     return response()->json(['status' => 0, 'msg' => 'validation fail', 'data' => ['errors' => $validate->errors()]], 200);
              }
              $checkemail = User::where('email', $request['email'])->first();

              if (empty($checkemail)) {
                     return response()->json(['status' => 0, 'msg' => 'Email id Does not match over records'], 200);
              }

              $password = rand(1000, 9999);

              User::where('email', $checkemail['email'])->update(['temp_pass' => $password]);

              $data = array(
                  'title' => 'Reset Your Password.!',
                  'password' => $password,
                  'email' => $checkemail['email'],
                  'name' => $checkemail['username']
              );
              $social = new tbl_user_otp();
              $social->user_id = $checkemail->id;
              $social->temp_pass = $password;
              $social->save();

              Mail::send('otppage', $data, function ($message) use ($data) {
                     $message->from('admin@gmail.com', "admin")->subject($data['title']);
                     $message->to($data['email']);
                     $message->cc('admin@gmail.com');
              });

              return response()->json(['status' => 1, 'msg' => 'OTP register email.'], 200);
       }

       function check_otp(Request $request) {
              $rule = [
                  'email' => 'required|email',
                  'temp_pass' => 'required',
              ];

              $this->Required_params($request, $rule);

              $email = $request->input('email');
              $temp_pass = $request->input('temp_pass');

              $update_pass = User::where(['email' => $email, 'temp_pass' => $temp_pass])->get()->first();

              if ($update_pass) {
                     return $this->sendResponse(1, 'Right OTP', null);
              } else {
                     return $this->sendResponse(2, 'Wrong OTP', null);
              }
       }

       function reset_password(Request $request) {

              $rule = [
                  'email' => 'required|email',
                  'temp_pass' => 'required',
                  'new_pass' => 'required',
              ];

              $this->Required_params($request, $rule);

              $email = $request->input('email');
              $temp_pass = $request->input('temp_pass');
              $new_pass = bcrypt($request->input('new_pass'));

              $check_mail = User::where('email', $email)->get()->first();

              if (!empty($check_mail)) {

                     $update_pass = User::where(['email' => $email, 'temp_pass' => $temp_pass])->get()->first();

                     if ($update_pass) {

                            User::where('email', $email)->update(['temp_pass' => null, 'password' => $new_pass]);
                            return $this->sendResponse(1, 'Password changed successfully', null);
                     } else {
                            return $this->sendResponse(2, 'Wrong OTP', null);
                     }
              } else {
                     return $this->sendResponse(0, 'Email Not Exists', null);
              }
       }

       function login(Request $request) {
              $rule = [
                  'email' => 'required',
                  'password' => 'required',
                  'longitude' => 'required',
                  'latitude' => 'required',
                  'device_token' => 'required',
                  'device_type' => 'required',
                  'device_id' => 'required',
              ];

              $this->Required_params($request, $rule);

              if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
                     $userId = Auth::id();
                     $latitude = $request->input('latitude');
                     $longitude = $request->input('longitude');


                     User::where('id', $userId)
                             ->update(['latitude' => $latitude, 'longitude' => $longitude]);

                     $user_idd = Auth::user()->is_blocked;
                     $is_ac_status = Auth::user()->is_ac_status;


                     if ($user_idd == 1) {
                            return $this->sendResponse(0, 'User is blocked by admin', null);
                     }

                     if ($is_ac_status == 1) {
                            return $this->sendResponse(0, 'Your account has been deactivated!', null);
                     }

                     $user = Auth::user();

                     $device_token = $request->input('device_token');
                     $device_type = $request->input('device_type');
                     $device_id = $request->input('device_id');

                     $data = array(
                         'device_token' => $device_token,
                         'device_type' => $device_type,
                         'device_id' => $device_id,
                         'user_id' => Auth::user()->id,
                     );

                     $this->add_device($data);
                     $array_merge = array_merge($user->toArray(), $data);

                     $success = $array_merge;
                     $token = $user->createToken('Login')->accessToken;

                     $datas = array('token' => $token, 'user' => $success);


                     return response()->json(['status' => 1, 'msg' => 'Login Successfully', 'data' => $datas], 200);
              } else {
                     return response()->json(['status' => 0, 'msg' => 'Invalid Password or Email'], 200);
              }
       }

       function get_user_social_link_by_user_id(Request $request) {
              $rule = [
                  'user_id' => 'required',
              ];

              $this->Required_params($request, $rule);
              $user_id = $request->input('user_id');

              $da = User::where('id', $user_id)->get()->first();


              $is_business_profile = $da->is_business_profile;

              $data = tbl_user_social_link::select(array('*', 'tbl_user_social_link.user_id as u_user_id', 'tbl_user_custom_social_link.social_platform_name as c_social_platform_name',
                          'tbl_user_custom_social_link.social_platform_icon as c_social_platform_icon',
                          DB::raw("$is_business_profile as is_business_profile"),
                          DB::raw("(SELECT t1.username from users t1 where t1.id = $user_id)as user_name")))
                      ->leftjoin('tbl_user_custom_social_link', function($join) {
                             $join->on('tbl_user_social_link.custom_link', '=', 'tbl_user_custom_social_link.social_id');
                      })
                      ->leftjoin('tbl_social_link', function($join) {
                             $join->on('tbl_user_social_link.social_id', '=', 'tbl_social_link.social_id');
                      })
                      ->where('tbl_user_social_link.user_id', $user_id)
//                              ->where('tbl_social_link.is_deleted', 0)
                      ->orderBy('is_first', 'DESC')
                      ->get();


              return response()->json(['status' => 1, 'msg' => 'Get User Social Link Successfully', 'data' => $data], 200);
       }

       function get_user_social_link(Request $request) {


              $user_id = Auth::id();
//              print_r($user_id);
//              exit;
//              print_r($user_id);
              $is_business_profile = Auth::user()->is_business_profile;

              $data = tbl_user_social_link::select(array('*', 'tbl_user_custom_social_link.social_platform_name as c_social_platform_name',
                                  'tbl_user_custom_social_link.social_platform_icon as c_social_platform_icon',
                                  'tbl_user_custom_social_link.is_premium as c_is_premium',
                                  DB::raw("$is_business_profile as is_business_profile")))
                              ->leftjoin('tbl_user_custom_social_link', function($join) {
                                     $join->on('tbl_user_social_link.custom_link', '=', 'tbl_user_custom_social_link.social_id');
                              })
                              ->leftjoin('tbl_social_link', function($join) {
                                     $join->on('tbl_user_social_link.social_id', '=', 'tbl_social_link.social_id');
                              })
                              ->where('tbl_user_social_link.user_id', $user_id)
//                              ->where('tbl_social_link.is_deleted', 0)
                              ->having('is_link_blocked', 0)
                              ->orderBy('is_first', 'DESC')->get();

//              $customData = tbl_user_social_link::select(array('*', DB::raw("$is_business_profile as is_business_profile")))
//                              ->join('tbl_user_custom_social_link', function($join) {
//                                     $join->on('tbl_user_social_link.custom_link', '=', 'tbl_user_custom_social_link.social_id');
//                                     $join->where('custom_link', '!=', null);
//                                     $join->select('social_platform_name', 'social_platform_icon');
//                              })
//                              ->where('tbl_user_social_link.user_id', $user_id)
////                              ->where('tbl_social_link.is_deleted', 0)
//                              ->orderBy('is_first', 'DESC')->get();
//              $customData = (array) $customData;
//              $data = (array) $data;
//              $c = array_merge($data, $customData);
//              print_r($c);
//              exit;


              return response()->json(['status' => 1, 'msg' => 'Get User Social Link Successfully', 'data' => $data], 200);
       }

       function add_user_social_link(Request $request) {


              $rule = [
                  'social_id' => 'required',
              ];

              $validate = Validator::make(request()->all(), $rule);

              if ($validate->fails()) {

                     return response()->json(['status' => 0, 'msg' => 'validation fail', 'data' => ['errors' => $validate->errors()]], 401);
              }

              $user_id = Auth::id();

              $social_id = $request->input('social_id');

              $social_link = $request->input('social_link');
              $social_link_2 = $request->input('social_link_2');

              $check = tbl_user_social_link::where(['social_id' => $social_id, 'user_id' => $user_id])->get()->first();

              if (empty($social_link_2)) {
                     $social_link_2 = null;
              }

              if (empty($check)) {

                     tbl_user_social_link::where(['user_id' => $user_id])->update(['is_first' => 0]);

                     $social = new tbl_user_social_link();
                     $social->social_id = $social_id;
                     $social->social_link = $social_link;
                     $social->social_link_2 = $social_link_2;
                     $social->user_id = $user_id;
                     $social->is_first = 1;
                     $social->save();
              } else {

                     tbl_user_social_link::where('link_id', $check->link_id)->update(['social_link' => $social_link, 'social_link_2' => $social_link_2]);
              }


              if ($social_id == 10) {
//                     echo 'okk';
//                     exit;
                     $this->generate_vcf_by_id($user_id);
              }

              return response()->json(['status' => 1, 'msg' => 'User Social Link Added Successfully'], 200);
       }

       function update_profile(Request $request) {

              $userId = Auth::id();
              $name = $request->input('name');
              $email = $request->input('email');
              $user_bio = $request->input('user_bio');
              $is_ac_status = $request->input('is_ac_status');

              if ($name != "" || $name != null) {
                     $update_data['name'] = $name;
              }

              if ($request->input('username')) {

                     $check_phn = User::where('username', $request->input('username'))->get()->first();

                     if (empty($check_phn)) {
                            $update_data['username'] = $request->input('username');
                     } else {

                            $login_phn = User::where('id', $userId)->pluck('username')->first();

                            if ($request->input('username') != $login_phn) {
                                   return $this->sendResponse(0, 'Username Already Exists', null);
                            }
                     }
              }

              if ($email) {
                     $update_data['email'] = $email;
              }
              if ($is_ac_status) {
                     $update_data['is_ac_status'] = $is_ac_status;
              }

              if ($user_bio) {
                     $update_data['user_bio'] = $user_bio;
              }

              if ($request->hasFile('profile_pic')) {
                     $random_no = $this->get_random_number(6);
                     $imageName = time() . $random_no . '.' . request()->profile_pic->getClientOriginalExtension();
                     request()->profile_pic->move(public_path('uploads'), $imageName);
                     $update_data['profile_pic'] = 'public/uploads/' . $imageName;
              }

              if ($request->hasFile('background_profile_pic')) {
                     $imageName = time() . '.' . request()->background_profile_pic->getClientOriginalExtension();
                     request()->background_profile_pic->move(public_path('uploads'), $imageName);

                     $update_data['background_profile_pic'] = 'public/uploads/' . $imageName;
              }

              $where = array('id' => $userId);

              if (!empty($update_data)) {
                     $update = User::where('id', $userId)->update($update_data);
              }

              $user_data = $this->select('users', '*', $where)->first();

              return $this->sendResponse(1, 'Profile Updated Successfully', $user_data);
       }

       function sent_to_deactivation(Request $request) {

              $userId = Auth::id();
              $is_ac_status = $request->input('is_ac_status');


              if ($is_ac_status) {
                     $update_data['is_ac_status'] = $is_ac_status;
              }



              $where = array('id' => $userId);

              if (!empty($update_data)) {
                     $update = User::where('id', $userId)->update($update_data);
              }

              $user_data = $this->select('users', '*', $where)->first();

              return $this->sendResponse(1, 'Account Deactivated Successfully', $user_data);
       }

       function get_business_info(Request $request) {


              $userId = Auth::id();
              $data = User::select(array('business_name', 'business_profile_pic', 'business_website', 'business_phone', 'business_email'))->where('id', $userId)->get()->first();
              return response()->json(['status' => 1, 'msg' => 'Get Business Profile Successfully', 'data' => $data], 200);
       }

       function update_tapandtag(Request $request) {


              $user_id = Auth::id();

              User::where('id', $user_id)->increment('total_tapandtag');

              return response()->json(['status' => 1, 'msg' => 'Total Count Updated Successfully'], 200);
       }

       function add_business_info(Request $request) {


              $userId = Auth::id();

              if ($request->input('business_name') || $request->input('business_name') == null || $request->input('business_name') == "") {
                     $update_data['business_name'] = $request->input('business_name');
              }
              if ($request->input('business_website') || $request->input('business_website') == null || $request->input('business_website') == "") {
                     $update_data['business_website'] = $request->input('business_website');
              }
              if ($request->input('business_phone') || $request->input('business_phone') == null || $request->input('business_phone') == "") {
                     $update_data['business_phone'] = $request->input('business_phone');
              }
              if ($request->input('business_email') || $request->input('business_email') == null || $request->input('business_email') == "") {
                     $update_data['business_email'] = $request->input('business_email');
              }
              if ($request->hasFile('business_profile_pic')) {
                     $imageName = time() . '.' . request()->business_profile_pic->getClientOriginalExtension();
                     request()->business_profile_pic->move(public_path('uploads'), $imageName);
                     $update_data['business_profile_pic'] = 'public/uploads/' . $imageName;
              }

              if (!empty($update_data)) {
                     User::where('id', $userId)->update($update_data);
              }

              $data = User::select(array('business_name', 'business_profile_pic', 'business_website', 'business_phone', 'business_email'))->where('id', $userId)->get()->first();
              return response()->json(['status' => 1, 'msg' => 'Business Profile Added Successfully', 'data' => $data], 200);
       }

       function list_social_platform(Request $request) {

              $user_id = Auth::id();
              $is_business_profile = Auth::user()->is_business_profile;

              $data = tbl_social_link::select(
                              array('tbl_social_link.social_id', 'tbl_social_link.social_platforn_url', 'social_platform_name', 'social_platform_icon', 'social_link', 'social_link_2', 'hint', 'link_id', 'tbl_social_link.*',
                                  DB::raw("(select COUNT(t5.user_id) from tbl_user_social_link t5 where t5.social_id = tbl_social_link.social_id and t5.user_id = $user_id)as is_my_link_active "),
                                  DB::raw("IFNULL((SELECT t1.is_link_blocked from tbl_user_social_link t1 where t1.social_id = tbl_social_link.social_id and t1.user_id = $user_id),0)as block_link"),
                                  DB::raw("$is_business_profile as is_business_profile")
                      ))
                      ->leftJoin('tbl_user_social_link', function($join) use($user_id) {
                             $join->on('tbl_social_link.social_id', '=', 'tbl_user_social_link.social_id');
                             $join->select('social_link');
                             $join->where('user_id', $user_id);
                      })
                      ->where('tbl_social_link.is_deleted', 0)
                      ->having('block_link', 0)
                      ->orderBy('type', 'asc')
                      ->get();

              $custom_s = tbl_user_custom_social_link::select(
                              array('*', DB::raw("$is_business_profile as is_business_profile")
                      ))->where('user_id', $user_id)->get();
//              $datas = array('social_link' => $data, 'custom_link' => $custom_s);
              return response()->json(['status' => 1, 'msg' => 'Get Social Platform Successfully', 'data' => $data, 'custom_link' => $custom_s], 200);
       }

       function update_is_first(Request $request) {


              $rule = [
                  'link_id' => 'required',
              ];
              $validate = Validator::make(request()->all(), $rule);

              if ($validate->fails()) {

                     return response()->json(['status' => 0, 'msg' => 'validation fail', 'data' => ['errors' => $validate->errors()]], 401);
              }

              $user_id = Auth::id();

              $link_id = $request->input('link_id');

              $myArray = explode(',', $link_id);

              tbl_user_social_link::where(['user_id' => $user_id])->update(['is_first' => 0]);

              tbl_user_social_link::where(['link_id' => $link_id, 'user_id' => $user_id])->update(['is_first' => 1]);

              return response()->json(['status' => 1, 'msg' => 'State update Successfully'], 200);
       }

       function update_link_status(Request $request) {


              $rule = [
                  'is_link_active' => 'required',
              ];

              $validate = Validator::make(request()->all(), $rule);

              if ($validate->fails()) {

                     return response()->json(['status' => 0, 'msg' => 'validation fail', 'data' => ['errors' => $validate->errors()]], 401);
              }

              $is_link_active = $request->input('is_link_active');

              $id = Auth::id();

              User::where('id', $id)->update(['is_link_active' => $is_link_active]);

              if ($is_link_active == 0) {
                     tbl_user_social_link::where('user_id', $id)->update(['is_first' => 0]);

                     return response()->json(['status' => 1, 'msg' => 'TapandTag Deactivated Successfully', 'is_link_active' => $is_link_active], 200);
              } else {
                     return response()->json(['status' => 1, 'msg' => 'TapandTag Activated Successfully', 'is_link_active' => $is_link_active], 200);
              }
       }

       //who tap me functionality
       function who_tap_me_api(Request $request) {
              $rule = [
                  'tap_to_username' => 'required',
              ];

              $validate = Validator::make(request()->all(), $rule);

              if ($validate->fails()) {
                     return response()->json(['status' => 0, 'msg' => 'validation fail', 'data' => ['errors' => $validate->errors()]], 401);
              }

              $tap_to_username = $request->input('tap_to_username');

              $tap_to = User::where('username', $tap_to_username)->pluck('id')->first();
              $tap_by = Auth::id();

              if ($tap_to == $tap_by) {
                     return response()->json(['status' => 1, 'msg' => 'Data not Updated'], 200);
              } else {
                     $values['tap_to'] = $tap_to;
                     $values['tap_by'] = $tap_by;

                     $this->insert('tbl_user_tap_history', $values);

                     //send event

                     $usersData = \DB::table('tbl_user_tap_history')
                             ->select(array('tbl_user_tap_history.*', 'users.username', 'users.profile_pic', DB::raw('DATE_FORMAT(tbl_user_tap_history.created_at, "%d %M, %Y %h:%i %p") as created_at')))
                             ->join('users', 'tbl_user_tap_history.tap_by', '=', 'users.id')
                             ->where('tap_to', $tap_to)
                             ->orderBy('tap_history_id', 'DESC')
                             ->get();

                     event(new UpdateWhoTapMe($tap_to, $usersData));

                     $tapByData = \DB::table('tbl_user_tap_history')
                             ->select(array('tbl_user_tap_history.*', 'users.username', 'users.profile_pic', DB::raw('DATE_FORMAT(tbl_user_tap_history.created_at, "%d %M, %Y %h:%i %p") as created_at')))
                             ->join('users', 'tbl_user_tap_history.tap_by', '=', 'users.id')
                             ->where('tap_by', $tap_by)
                             ->orderBy('tap_history_id', 'DESC')
                             ->get();
                     event(new UpdateWhoTapI($tap_by, $tapByData));

                     return response()->json(['status' => 1, 'msg' => 'Data Updated Successfully', 'data' => $values], 200);
              }
       }

       //get who tap me users detail
       function get_who_tap_me_list(Request $request) {


              $login_user = Auth::id();

              $users = \DB::table('tbl_user_tap_history')
                      ->select(array('tbl_user_tap_history.*', 'users.username', 'users.name', 'users.profile_pic', DB::raw('DATE_FORMAT(tbl_user_tap_history.created_at, "%d %M, %Y %h:%i %p") as created_at')))
                      ->join('users', 'tbl_user_tap_history.tap_by', '=', 'users.id')
                      ->where('tap_to', $login_user)
                      ->orderBy('tap_history_id', 'DESC')
                      ->get();

              return response()->json(['status' => 1, 'msg' => 'Data Get Successfully', 'data' => $users], 200);
       }

       //get who tap I users detail
       function get_who_tap_i_list(Request $request) {


              $login_user = Auth::id();


              $users = \DB::table('tbl_user_tap_history')
                      ->select(array('tbl_user_tap_history.*', 'users.username', 'users.name', 'users.profile_pic', DB::raw('DATE_FORMAT(tbl_user_tap_history.created_at, "%d %M, %Y %h:%i %p") as created_at')))
                      ->join('users', 'tbl_user_tap_history.tap_to', '=', 'users.id')
                      ->where('tap_by', $login_user)
                      ->orderBy('tap_history_id', 'DESC')
                      ->get();

              return response()->json(['status' => 1, 'msg' => 'Data Get Successfully', 'data' => $users], 200);
       }

       //Delete Link
       function delete_social_link(Request $request) {


              $rule = [
                  'link_id' => 'required',
              ];

              $validate = Validator::make(request()->all(), $rule);

              if ($validate->fails()) {
                     return response()->json(['status' => 0, 'msg' => 'validation fail', 'data' => ['errors' => $validate->errors()]], 401);
              }

              $link_id = $request->input('link_id');

              tbl_user_social_link::where('link_id', $link_id)->delete();

              return response()->json(['status' => 1, 'msg' => 'Link Deleted Successfully'], 200);
       }

       //Add Custom Link
       function add_custom_link(Request $request) {


              $rule = [
                  'social_platform_icon' => 'required',
                  'social_platform_name' => 'required',
                  'social_platforn_url' => 'required',
                  'is_business' => 'required',
              ];
              $this->Required_params($request, $rule);

              $userId = Auth::id();

              $social_platform_name = $request->input('social_platform_name');
              $social_platforn_url = $request->input('social_platforn_url');
              $is_business = $request->input('is_business');
              $milan = "";
              if ($request->hasFile('social_platform_icon')) {

                     $imageName = time() . '.' . request()->social_platform_icon->getClientOriginalExtension();

                     request()->social_platform_icon->move(public_path('uploads'), $imageName);
                     $milan = 'public/uploads/' . $imageName;
              } else {
                     $milan = "";
              }
              $tbl_user_custom_social_link = new tbl_user_custom_social_link;
              $tbl_user_custom_social_link->social_platform_icon = $milan;
              $tbl_user_custom_social_link->social_platform_name = $social_platform_name;
              $tbl_user_custom_social_link->social_platforn_url = $social_platforn_url;
              $tbl_user_custom_social_link->user_id = $userId;
              $tbl_user_custom_social_link->is_premium = 1;
              $tbl_user_custom_social_link->is_business = $is_business;
              $tbl_user_custom_social_link->save();

              tbl_user_social_link::where(['user_id' => $userId])->update(['is_first' => 0]);

              $social = new tbl_user_social_link();
              $social->custom_link = $tbl_user_custom_social_link->social_id;
              $social->social_link = $social_platforn_url;
              $social->user_id = $userId;
              $social->is_first = 1;
              $social->save();


              return $this->sendResponse(1, 'Custom Link Add Successfully', $tbl_user_custom_social_link);
       }

       //edit custom social link
       function edit_custom_link(Request $request) {

              $rule = [
                  'social_id' => 'required',
              ];
              $this->Required_params($request, $rule);
              $userId = Auth::id();
              $social_id = $request->input('social_id');

              if ($request->input('social_platform_name')) {
                     $update_data['social_platform_name'] = $request->input('social_platform_name');
              }
              if ($request->input('social_platforn_url')) {
                     $update_data['social_platforn_url'] = $request->input('social_platforn_url');
              }
              if ($request->input('is_business')) {
                     $update_data['is_business'] = $request->input('is_business');
              }
              if ($request->hasFile('social_platform_icon')) {
                     $imageName = time() . '.' . request()->social_platform_icon->getClientOriginalExtension();
                     request()->social_platform_icon->move(public_path('uploads'), $imageName);
                     $update_data['social_platform_icon'] = 'public/uploads/' . $imageName;
              }

              if (!empty($update_data)) {
                     tbl_user_custom_social_link::where('social_id', $social_id)->update($update_data);
              }

              $data = tbl_user_custom_social_link::where('social_id', $social_id)->get()->first();
              return response()->json(['status' => 1, 'msg' => 'Custom link edited Successfully', 'data' => $data], 200);
       }

       //Another
       function get_user_detail(Request $request) {


              $userId = Auth::id();
              $where = array('id' => $userId);
              $user_data = $this->select('users', '*', $where)->first();
              return response()->json(['status' => 1, 'msg' => 'Get User Data Successfully', 'data' => $user_data], 200);
       }

       function edit_business_info(Request $request) {


              $userId = Auth::id();

              if ($request->input('business_name')) {
                     $update_data['business_name'] = $request->input('business_name');
              }
              if ($request->input('business_website')) {
                     $update_data['business_website'] = $request->input('business_website');
              }
              if ($request->input('business_phone')) {
                     $update_data['business_phone'] = $request->input('business_phone');
              }
              if ($request->input('business_email')) {
                     $update_data['business_email'] = $request->input('business_email');
              }
//              if ($request->hasFile('business_profile_pic')) {
//                     $imageName = time() . '.' . request()->business_profile_pic->getClientOriginalExtension();
//                     request()->business_profile_pic->move(public_path('uploads/'), $imageName);
//                     $update_data['business_profile_pic'] = 'public/uploads/' . $imageName;
//              }
              if ($request->hasFile('business_profile_pic')) {
                     $imageName = time() . 'B.' . request()->business_profile_pic->getClientOriginalExtension();
                     request()->business_profile_pic->move(public_path('uploads'), $imageName);
                     $update_data['business_profile_pic'] = 'public/uploads/' . $imageName;
              }
              if (!empty($update_data)) {
                     User::where('id', $userId)->update($update_data);
              }

              $data = User::select(array('business_name', 'business_profile_pic', 'business_website', 'business_phone', 'business_email'))->where('id', $userId)->get()->first();
              return response()->json(['status' => 1, 'msg' => 'Business Profile Added Successfully', 'data' => $data], 200);
       }

       function add_to_view(Request $request) {


              $rule = [
                  'view_to' => 'required',
              ];

              $validate = Validator::make(request()->all(), $rule);

              if ($validate->fails()) {

                     return response()->json(['status' => 0, 'msg' => 'validation fail', 'data' => ['errors' => $validate->errors()]], 401);
              }

              $user_id = Auth::id();

              $view_to = $request->input('view_to');

              $check_view = tbl_user_view::where(['view_by' => $user_id, 'view_to' => $view_to])->get()->first();

              if (empty($check_view)) {

                     $view = new tbl_user_view();
                     $view->view_by = $user_id;
                     $view->view_to = $view_to;
                     $view->save();
              }
              return response()->json(['status' => 1, 'msg' => 'Add to View Successfully'], 200);
       }

       function list_viewed_users(Request $request) {


              $user_id = Auth::id();

              $data = tbl_user_view::select(array('tbl_user_view.*', 'users.profile_pic', 'users.username'))
                              ->join('users', function($join) {
                                     $join->on('tbl_user_view.view_by', '=', 'users.id');
                              })
                              ->where('view_to', $user_id)->get();

              return response()->json(['status' => 1, 'msg' => 'Get User View List Successfully', 'data' => $data], 200);
       }

       function get_user_profile($id) {

              $data = User::where('id', $id)->get()->first();

              if (empty($data)) {

                     return response()->json(['status' => 0, 'msg' => 'Dont Be OverSmart'], 200);
              }

              return view("UserProfile")->with(compact('data'));
       }

       function get_link_active_status(Request $request) {

              $user_id = Auth::id();
              $data = User::where('id', $user_id)->pluck('is_link_active')->first();
              return response()->json(['is_link_active' => $data, 'status' => 1, 'msg' => 'Get Status Successfully'], 200);
       }

       function buy_business_subscription(Request $request) {


              $userId = Auth::id();
              User::where('id', $userId)->update(['is_business_profile' => 1]);
              $is_business_profile = Auth::user()->is_business_profile;
              return response()->json(['is_business_profile' => $is_business_profile, 'status' => 1, 'msg' => 'Buy Business Subscription Successfully'], 200);
       }

       function update_subscription(Request $request) {


              $rule = [
                  'is_business_profile' => 'required',
              ];
              $validate = Validator::make(request()->all(), $rule);

              if ($validate->fails()) {

                     return response()->json(['status' => 0, 'msg' => 'validation fail', 'data' => ['errors' => $validate->errors()]], 401);
              }

              $is_business_profile = $request->input('is_business_profile');
              if ($is_business_profile == "0" || $is_business_profile == 0) {
                     $is_business_profile == "0";
              }

              $userId = Auth::id();
              User::where('id', $userId)->update(['is_business_profile' => $is_business_profile]);
              return response()->json(['is_business_profile' => $is_business_profile, 'status' => 1, 'msg' => 'Update Business Subscription Successfully'], 200);
       }

       // get user
       function get_profile(Request $req, $name) {

//              echo 'raj';
//              exit;

              $data = DB::table('users')
                              ->where('username', "$name")
                              ->get()->first();
//              echo '<pre>';
//              print_r($data);
//              exit;

              if (empty($data)) {

//            return response()->json(['status' => 0, 'msg' => 'No User Fond'], 200);

                     echo "<h1 style='display: flex;align-items: center;justify-content: center;text-align: center;margin-top: center;width: 50%;height: 50%;overflow: auto;margin: auto;position: absolute;top: 0;left: 0;bottom: 0;right: 0;'>No User Found With This Username</h1>";
                     exit;
              }

              $id = $data->id;

//              User::where('id', $id)->increment('total_tapandtag');

              if ($data->is_link_active == 0) {
                     return abort(404);
                     exit;
              }


              $data2 = tbl_user_social_link::select(array
                                  ('*', 'tbl_user_custom_social_link.social_platform_name as c_social_platform_name',
                                  'tbl_user_custom_social_link.social_platform_icon as c_social_platform_icon'))
                              ->leftjoin('tbl_user_custom_social_link'
                                      , function($join) {
                                     $join->on('tbl_user_social_link.custom_link', '=', 'tbl_user_custom_social_link.social_id');
                              })
                              ->leftjoin('tbl_social_link', function($join) {
                                     $join->on('tbl_user_social_link.social_id', '=', 'tbl_social_link.social_id');
                              })
                              ->where('tbl_user_social_link.user_id', $data->id)
                              ->where('tbl_user_social_link.is_first', 1)
                              ->orderBy('is_first', 'DESC')->get()->first();


              $contact_info = DB::table('tbl_social_link')
                              ->Join('tbl_user_social_link', function($join) use($data) {
                                     $join->on('tbl_social_link.social_id', '=', 'tbl_user_social_link.social_id');
                                     $join->where('tbl_user_social_link.user_id', $data->id);
                                     $join->where('tbl_user_social_link.social_id', 10);
                              })
                              ->join('users', 'tbl_user_social_link.user_id', 'users.id')
                              ->get()->first();


              $data3 = tbl_user_social_link::select(array('*', 'tbl_user_custom_social_link.social_platform_name as c_social_platform_name',
                          'tbl_user_custom_social_link.social_platform_icon as c_social_platform_icon', 'tbl_user_custom_social_link.social_platforn_url as c_social_platforn_url'))->leftjoin('tbl_user_custom_social_link', function($join) {
                             $join->on('tbl_user_social_link.custom_link', '=', 'tbl_user_custom_social_link.social_id');
                      })
                      ->leftjoin('tbl_social_link', function($join) {
                             $join->on('tbl_user_social_link.social_id', '=', 'tbl_social_link.social_id');
                      })
                      ->where('tbl_user_social_link.user_id', $data->id)
                      ->orderBy('is_first', 'DESC')
                      ->having('is_link_blocked', 0)
                      ->get();



              foreach ($data3 as $key => $user) {
                     $platform_link = $user->social_link;
                     $social_platforn_url = $user->social_platforn_url;



                     if ($user->social_id == 1) {
                            $open_link = "https://www.instagram.com/$platform_link";
                     } else if ($user->social_id == 2) {
                            $open_link = $platform_link; //"Fb" +
                     } else if ($user->social_id == 3) {
                            $open_link = $platform_link;
                     } else if ($user->social_id == 4) {
                            $open_link = "$social_platforn_url$platform_link";
                     } else if ($user->social_id == 5) {
                            $open_link = "mailto:$platform_link";
                     } else if ($user->social_id == 6) { //pintrest
                            $open_link = "$social_platforn_url$platform_link";
                     } else if ($user->social_id == 7) {
                            $open_link = $platform_link;
                     } else if ($user->social_id == 9) {
                            $open_link = $platform_link;
                     } else if ($user->social_id == 10) {
                            $open_link = "http://tapandtag.me/tapandtag/generate_vcf/$data->username";
                     } else if ($user->social_id == 11) {
                            $open_link = "$social_platforn_url$platform_link";
                     } else if ($user->social_id == 12) { //Soundcloud
                            $open_link = "$social_platforn_url$platform_link";
                     } else if ($user->social_id == 13) {
                            $open_link = $platform_link;
                     } else if ($user->social_id == 14) {
                            $open_link = $platform_link;
                     } else if ($user->social_id == 15) {
                            $open_link = "$social_platforn_url$platform_link";
                     } else if ($user->social_id == 16) {
                            $open_link = "$social_platforn_url$platform_link";
                     } else if ($user->social_id == 17) {

                            $open_link = $platform_link;
                     } else if ($user->social_id == 18) {
                            $open_link = "$social_platforn_url$platform_link";
                     } else if ($user->social_id == 19) {
                            $open_link = $platform_link;
                     } else if ($user->social_id == 20) {
                            $open_link = $platform_link;
                     } else if ($user->social_id == 21) { //Tinder
                            $open_link = $platform_link;
                     } else if ($user->social_id == 22) { //Apple Music
                            $open_link = $platform_link;
                     } else if ($user->social_id == 23) { //Paysera
                            $open_link = $platform_link;
                     } else if ($user->social_id == 24) { //Fiver
                            $open_link = "$social_platforn_url$platform_link";
                     } else if ($user->social_id == 25) { //Alibaba
                            $open_link = $platform_link;
                     } else if ($user->social_id == 26) { //Pinterest
                            $open_link = "$social_platforn_url$platform_link";
                     } else if ($user->social_id == 27) { //Tinder
                            $open_link = $platform_link;
                     } else if ($user->social_id == 28) { //VK
                            $open_link = "$social_platforn_url$platform_link";
                     } else if ($user->social_id == 29) { //Viber
                            $open_link = $platform_link;
                     } else if ($user->social_id == 30) { //Telegram
                            $open_link = "$social_platforn_url$platform_link";
                     } else if ($user->social_id == 31) { //Skype
                            $open_link = "$social_platforn_url$platform_link";
                     } else if ($user->social_id == 32) { //Odnokassniki
                            $open_link = $platform_link;
                     } else if ($user->social_id == 33) { //TransferWise
                            $open_link = "$social_platforn_url$platform_link";
                     } else if ($user->social_id == 34) { //Amazon Business
                            $open_link = $platform_link;
                     } else if ($user->social_id == 35) { //Link
                            $open_link = $platform_link;
                     } else if ($user->social_id == 36) { //OnlyFans
                            $open_link = $platform_link;
                     } else if ($user->social_id == 37) { //Linktree
                            $open_link = $platform_link;
                     } else if ($user->social_id == 38) { //Calendly
                            $open_link = "$social_platforn_url$platform_link";
                     } else if ($user->social_id == 39) { //Clubhouse
                            $open_link = $platform_link;
                     } else if ($user->social_id == 40) { //eToro
                            $open_link = $platform_link;
                     } else if ($user->social_id == 41) { //podcast
                            $open_link = $platform_link;
                     } else if ($user->social_id == 42) { //Sqaureup
                            $open_link = "$social_platforn_url$platform_link";
                     } else if ($user->social_id == 43) { //Afterpay
                            $open_link = "$social_platforn_url$platform_link";
                     } else if ($user->social_id == 44) { //zip pay
                            $open_link = "$social_platforn_url$platform_link";
                     } else if ($user->social_id == 45) { //Canva
                            $open_link = "$social_platforn_url$platform_link";
                     } else if ($user->social_id == 46) { //text
                            $open_link = "$social_platforn_url$platform_link";
                     } else if ($user->social_id == null) { //custom
                            $open_link = $platform_link;
                     } else if ($user->social_id == 47) { //Zoom
                            $open_link = "$social_platforn_url$platform_link";
                     } else if ($user->social_id == 48) { //Bitcoin
                            $open_link = $platform_link;
                     } else if ($user->social_id == 49) { //Ethereum
                            $open_link = $platform_link;
                     } else if ($user->social_id == 50) { //Etsy
                            $open_link = $platform_link;
                     } else if ($user->social_id == 51) { //Shopify
                            $open_link = $platform_link;
                     } else if ($user->social_id == 52) { //Embedded Video
                            $open_link = $platform_link;
                     } else if ($user->social_id == 53) { //Excel
                            $open_link = $platform_link;
                     } else if ($user->social_id == 54) { //PDF file
                            $open_link = $platform_link;
                     } else if ($user->social_id == 57) { //CSV file
                            $open_link = $platform_link;
                     } else if ($user->social_id == 58) { //Google Docs
                            $open_link = $platform_link;
                     } else if ($user->social_id == 59) { //Google Sheets
                            $open_link = $platform_link;
                     } else if ($user->social_id == 60) { //Google Slides
                            $open_link = $platform_link;
                     }

                     $data3[$key]->open_link = $open_link;
              }

//              echo '<pre>';
//              print_r($data3->toArray());
//              exit;
              $count = User::where('id', $id)->get()->pluck('total_tapandtag');

              return view('UserProfile', ['data' => $data, 'social' => $data3, 'contact_info' => $contact_info, 'count' => $count]);
              if (empty($data2)) {

                     echo "<h1 style='display: flex;align-items: center;justify-content: center;text-align: center;margin-top: center;width: 50%;height: 50%;overflow: auto;margin: auto;position: absolute;top: 0;left: 0;bottom: 0;right: 0;'>No Data Found For $data->username</h1>";
                     exit;
              }
       }

       //Basic Function
       public function check_email($email) {

              $users = DB::table('users')
                              ->select('email')
                              ->where('email', $email)
                              ->get()->first();

              return $users;
       }

       public function select($table, $select, $where) {

              $users = DB::table($table)
                      ->select($select)
                      ->where($where)
                      ->get();

              return $users;
       }

       function update($table, $where, $update) {

              $data = DB::table($table)
                      ->where($where)
                      ->update($update);

              return $data;
       }

       function insert($table, $values) {

              $users = \DB::table($table)
                      ->insertGetId($values);

              return $users;
       }

       function add_device($arr) {

              $where = array(
                  'user_id' => $arr['user_id'],
                  'device_id' => $arr['device_id'],
                  'device_type' => $arr['device_type'],
              );

              $check_device = $this->select('tbl_token', '*', $where)->toArray();

              if (!empty($check_device)) {

                     $update = array(
                         'device_token' => $arr['device_token'],
                     );

                     $this->update('tbl_token', $where, $update);
              } else {

                     $this->insert('tbl_token', $arr);
              }
       }

       function get_random_number($length = 10) {

              $alphabet = "0123456789";
              $token = "";
              $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache

              for ($i = 0; $i < $length; $i++) {
                     $n = rand(0, $alphaLength);
                     $token .= $alphabet[$n];
              }
              return $token;
       }

       function generate_vcf_by_id($user_id) {


              // $data = DB::table('users')->where('username', "$name")->get()->first();
              $data = DB::table('users')->where('id', "$user_id")->get()->first();
              $id = $data->id;
              $data2 = tbl_user_social_link::where('user_id', $id)->where('social_id', 10)->get()->first();
              $social_link = $data2->social_link;
//              $firstname = preg_replace('#[^\pL\pN/-]+#', '', $data->username);


              if ($data->is_business_profile == 1) {
                     // is_business_profile

                     $firstname2 = preg_replace('#[^\pL\pN/-]+#', '', $data->username);
                     $resStr_b2 = strtolower($firstname2);

                     $vcard = new VCard();
                     $business_email = $data->business_email;
                     $business_phone = $social_link;
                     $business_website = $data->business_website;
                     $vcard->addName($resStr_b2);
                     $vcard->addEmail($business_email);
                     $vcard->addPhoneNumber($business_phone);
                     $vcard->addURL($business_website);
                     $vcard->addAddress(null, null, 'street', 'worktown', null, 'workpostcode', 'Belgium');
                     $vcard->setSavePath(public_path());
                     $vcard->save();
              } else {
                     $firstname1 = preg_replace('#[^\pL\pN/-]+#', '', $data->username);
                     $resStr_b1 = strtolower($firstname1);
                     // is_not_business_profile
                     $vcard = new VCard();

                     $vcard->addName($resStr_b1);
                     $vcard->addEmail($data->email);
                     $vcard->addPhoneNumber($social_link, 'PREF;WORK');
                     $vcard->addPhoneNumber($social_link, 'WORK');
                     $vcard->addAddress(null, null, 'street', 'worktown', null, 'workpostcode', 'Belgium');
                     $vcard->setSavePath(public_path());
                     $vcard->save();
              }
              $firstname4 = preg_replace('#[^\pL\pN/-]+#', '', $data->username);
              $resStr_b4 = strtolower($firstname4);
              $vcard_link = "http://tapandtag.me/tapandtag/public/$resStr_b4.vcf";
              User::where('id', $id)->update(['vcard_link' => $vcard_link]);
       }

       function generate_vcf($name) {


              $data = DB::table('users')->where('username', "$name")->get()->first();

//              print_r($data);
//              exit;
              $id = $data->id;
              $data2 = tbl_user_social_link::where('user_id', $id)->where('social_id', 10)->get()->first();

              if (empty($data2)) {

                     echo "<h1 style='display: flex;align-items: center;justify-content: center;text-align: center;margin-top: center;width: 50%;height: 50%;overflow: auto;margin: auto;position: absolute;top: 0;left: 0;bottom: 0;right: 0;'>No User Found With This Username</h1>";

                     exit;
              } else {
                     $social_link = $data2->social_link;
                     header("Location: $data->vcard_link");
                     echo "<script>window.location.href='$data->vcard_link';</script>";
              }
       }

       function lib2($param) {
              header("Content-type: text/x-vcard");
              header("Content-Disposition: attachment; filename=\"john_doe.vcf\";");
              $vcard = new vCard;

              $vcard->setName("John", "Doe");

              // Every set functions below are optional
              $vcard->setTitle("Software dev.");
              $vcard->setPhone("+1234567890");
              $vcard->setURL("http://johndoe.com");
              $vcard->setTwitter("diplodocus");
              $vcard->setMail("john@johndoe.com");
              $vcard->setAddress(array
                  (
                  "street_address" => "Main Street",
                  "city" => "Ghost Town",
                  "state" => "",
                  "postal_code" => "012345",
                  "country_name" => "Somewhere"
              ));
              $vcard->setNote("Lorem Ipsum, \nWith new line.")

              ;

              echo $vcard;
       }

       function open_venmo() {
              return view('venmo');
       }

       function check_user_name($username) {

              $username1 = strtolower(trim(str_replace(" ", "", $username)));
              $username = preg_replace('/[^A-Za-z0-9\-]/', '', $username1);

              $data = User::where('username', '=', "$username")->get()->first();

              if (!empty($data)) {
                     $new_username = $username . "_" . $this->get_random_number(2);

                     $check_2 = User::where('username', '=', "$new_username")->get()->first();
                     if (!empty($check_2)) {
                            $new_username2 = $username . "_" . $this->get_random_number(3);
                            $final_username = $new_username2;
                     } else {
                            $final_username = $new_username;
                     }
              } else {
                     $final_username = $username;
              }

              return $final_username;
       }

       function privacy_policy(Request $request) {
              return view('privacy');
       }

       function terms_of_use(Request $request) {
              return view('terms');
       }

       public function sendResponse($status, $message, $result) {

              if (empty($result)) {

                     $response = array(
                         'status' => $status, 'msg' => $message);
                     echo

                     json_encode($response);
              } else {
                     $response = array('status' => $status, 'msg' => $message, 'data' => $result);
                     echo json_encode($response);
              }

              exit();
       }

       function Required_params(Request $request, $data) {

              $validate = Validator::make($request->all(), $data);

              if ($validate->fails()) {
                     echo json_encode(['status' => 0, 'msg' => 'validation fail', 'data' => [
                             'error' => $validate->errors()]]);
                     exit;
              }
       }

       //add reciept in database
       function add_reciept(Request $request) {

              $rule = [
                  'receipt_data' => 'required',
              ];

              $validate = Validator::make(request()->all(), $rule);

              if ($validate->fails()) {
                     return response()->json(['status' => 0, 'msg' => 'validation fail', 'data' => ['errors' => $validate->errors()]], 401);
              }

              $user_id = Auth::id();
              $receipt_data = $request->input('receipt_data');

              $check_data = tbl_user_reciept::where('user_id', $user_id)->get()->first();

              if (empty($check_data)) {
                     //insert
                     $data = new tbl_user_reciept();
                     $data->user_id = $user_id;
                     $data->receipt_data = $receipt_data;
                     $data->save();

                     $this->check_if_renewed_subscription($receipt_data, $user_id);

                     User::where('id', $user_id)->update(['is_business_profile' => 1]);
              } else {
                     //update
                     $where['user_id'] = $user_id;
                     $update['receipt_data'] = $receipt_data;
                     $this->update('tbl_user_reciept', $where, $update);

                     $this->check_if_renewed_subscription($receipt_data, $user_id);
                     User::where('id', $user_id)->update(['is_business_profile' => 1]);
              }

              return response()->json(['status' => 1, 'msg' => 'User Reciept Added Successfully'], 200);
       }

       function cron_check_subscription(Request $request) {
              $now = date('Y-m-d H:i:s');
              $data = tbl_user_reciept::where('end_date', '<=', $now)->get();

              foreach ($data as $key => $value) {
                     $user_id = $value['user_id'];
//checkif they are renewed theirs subscription or not
                     $is_renewed = $this->check_if_renewed_subscription($value['receipt_data'], $value['user_id']);

                     if ($is_renewed == 1) {
//update in tbl_user
                            User::where('id', $value['user_id'])->update(['is_business_profile' => $is_renewed]);
//                echo "Renewal Successfully $user_id" . "<br>";
                     } else {
                            //update in tbl_user
                            User::where('id', $value['user_id'])->update(['is_business_profile' => $is_renewed]);
                            tbl_user_social_link::where('user_id', $value['user_id'])->update(['is_first' => 0]);
//                echo "He Is No Long More Business $user_id" . "<br>";
                     }
              }
       }

       function check_if_renewed_subscription($receipt_data, $user_id) {
              $receipt_data_[
                      'receipt-data'] = $receipt_data;
              $receipt_data_['password'] = "9d28cec1cf59454e9d5f42a44615fe68";

              $json = json_encode($receipt_data_);
//        $url = "https://sandbox.itunes.apple.com/verifyReceipt";
              $url = "https://buy.itunes.apple.com/verifyReceipt";
              $m = $this->excute_curl($json, $url);

              $m = json_decode($m, true);

              if ($m['status'] == 0) {
                     $count = count($m['receipt']['in_app']) - 1;
                     $transaction_id = $m['latest_receipt_info'][0]['transaction_id'];
                     $start_date = rtrim($m['latest_receipt_info'][0]['purchase_date'], " Etc/GMT");
                     $end_date = rtrim($m['latest_receipt_info'][0]['expires_date'], " Etc/GMT");
                     $now = gmdate("Y-m-d H:i:s");

//            print(" End Date " . $end_date . " and Now " . $now) . "<br>";

                     if ($end_date > $now) {

//update
                            $update['transaction_id'] = $transaction_id;
                            $update['purchase_date'] = $start_date;
                            $update['end_date'] = $end_date;
                            $where['user_id'] = $user_id;
                            $this->update('tbl_user_reciept', $where, $update);
                            return 1;
                     } else {
                            return 0;
                     }
              } else {
                     return 0;
              }
       }

       function verifyReciept(Request $request) {

              $rule = [
                  'user_id' => 'required',
                  'json' => 'required',
              ];

              $validate = Validator::make($request->all(), $rule);

              if ($validate->fails()) {
                     return response()->json(['status' => '0', 'msg' => 'validation fail', 'data' => ['error' => $validate->errors()]], 401);
              }

              $json = $request->input('json');
              $url = "https://sandbox.itunes.apple.com/verifyReceipt";
//$url = "https://buy.itunes.apple.com/verifyReceipt";
              $m = $this->excute_curl($json, $url);

              $m = json_decode($m, true);

              $count = count($m['receipt']['in_app']) - 1;

              $user_id = $request->input('user_id');
              $transaction_id = $m['receipt']['in_app'][$count]['transaction_id'];
              $start_date = rtrim($m['receipt']['in_app'][$count]['purchase_date'], " Etc/GMT");
              $end_date = rtrim($m['receipt']['in_app'][$count]['expires_date'], " Etc/GMT");

              $where['user_id'] = $user_id;
              $check_user = $this->select('tbl_user_transaction', '*', $where)->first();

              if ($check_user) {

//update
                     $update['transaction_id'] = $transaction_id;
                     $update['start_date'] = $start_date;
                     $update['end_date'] = $end_date;

                     $this->update('tbl_user_transaction', $where, $update);
              } else {

//insert
                     $data = array(
                         'user_id' => $user_id,
                         'transaction_id' => $transaction_id,
                         'start_date' => $start_date,
                         'end_date' => $end_date,
                     );

                     $this->insert('tbl_user_transaction', $data);
              }
              return response()->json(['status' => 1, 'msg' => 'Subscription Added succes sfully'], 200);
       }

//curl call
       function excute_curl($json, $url) {
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
              curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
              curl_setopt($ch, CURLOPT_URL, $url);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
              $result = curl_exec($ch);
              curl_close($ch);
              return $result;
       }

// get user
       function get_userv1(Request $req, $name) {

//              echo 'raj';
//              exit;

              $data = DB::table('users')
                              ->where('username', "$name")
                              ->get()->first();
//              echo '<pre>';
//              print_r($data);
//              exit;

              if (empty($data)) {

//            return response()->json(['status' => 0, 'msg' => 'No User Fond'], 200);

                     echo "<h1 style='display: flex;align-items: center;justify-content: center;text-align: center;margin-top: center;width: 50%;height: 50%;overflow: auto;margin: auto;position: absolute;top: 0;left: 0;bottom: 0;right: 0;'>No User Found With This Username</h1>";
                     exit;
              }

              $id = $data->id;

//              User::where('id', $id)->increment('total_tapandtag');

              if ($data->is_link_active == 0) {

//            header("Location: https://airpawnd.com/");

                     return abort(404);
                     exit;
              }
//
//              if ($data->is_profile_public == 0) {
//
////            header("Location: https://airpawnd.com/");
//
//                     return abort(404);
//                     exit;
//              }


              $data2dd = DB::table('tbl_social_link')
                              ->leftJoin('tbl_user_social_link', function($join) use($data) {
                                     $join->on('tbl_social_link.social_id', '=', 'tbl_user_social_link.social_id');
                                     $join->where('tbl_user_social_link.user_id', $data->id);
                                     $join->where('tbl_user_social_link.is_first', 1);
                              })
                              ->get()->first();



              $data2 = tbl_user_social_link::select(array
                                  ('*', 'tbl_user_custom_social_link.social_platform_name as c_social_platform_name',
                                  'tbl_user_custom_social_link.social_platform_icon as c_social_platform_icon'))
                              ->leftjoin('tbl_user_custom_social_link'
                                      , function($join) {
                                     $join->on('tbl_user_social_link.custom_link', '=', 'tbl_user_custom_social_link.social_id');
                              })
                              ->leftjoin('tbl_social_link', function($join) {
                                     $join->on('tbl_user_social_link.social_id', '=', 'tbl_social_link.social_id');
                              })
                              ->where('tbl_user_social_link.user_id', $data->id)
                              ->where('tbl_user_social_link.is_first', 1)
                              ->orderBy('is_first', 'DESC')->get()->first();


              $contact_info = DB::table('tbl_social_link')
                              ->Join('tbl_user_social_link', function($join) use($data) {
                                     $join->on('tbl_social_link.social_id', '=', 'tbl_user_social_link.social_id');
                                     $join->where('tbl_user_social_link.user_id', $data->id);
                                     $join->where('tbl_user_social_link.social_id', 10);
                              })
                              ->join('users', 'tbl_user_social_link.user_id', 'users.id')
                              ->get()->first();




              if (empty($data2)) {

                     $data3d = DB::table('tbl_social_link')
                             ->Join('tbl_user_social_link', function($join) use($data) {
                                    $join->on('tbl_social_link.social_id', '=', 'tbl_user_social_link.social_id');
                                    $join->where('tbl_user_social_link.user_id', $data->id);
                             })
                             ->get();

                     $data3 = tbl_user_social_link::select(array('*', 'tbl_user_custom_social_link.social_platform_name as c_social_platform_name',
                                         'tbl_user_custom_social_link.social_platform_icon as c_social_platform_icon', 'tbl_user_custom_social_link.social_platforn_url as c_social_platforn_url'))->leftjoin('tbl_user_custom_social_link', function($join) {
                                            $join->on('tbl_user_social_link.custom_link', '=', 'tbl_user_custom_social_link.social_id');
                                     })
                                     ->leftjoin('tbl_social_link', function($join) {
                                            $join->on('tbl_user_social_link.social_id', '=', 'tbl_social_link.social_id');
                                     })
                                     ->where('tbl_user_social_link.user_id', $data->id)
                                     ->having('is_link_blocked', 0)
                                     ->orderBy('is_first', 'DESC')->get();



                     foreach ($data3 as $key => $user) {
                            $platform_link = $user->social_link;
                            $social_platforn_url = $user->social_platforn_url;



                            if ($user->social_id == 1) {
                                   $open_link = "https://www.instagram.com/$platform_link";
                            } else if ($user->social_id == 2) {
                                   $open_link = $platform_link; //"Fb" +
                            } else if ($user->social_id == 3) {
                                   $open_link = $platform_link;
                            } else if ($user->social_id == 4) {
                                   $open_link = "$social_platforn_url$platform_link";
                            } else if ($user->social_id == 5) {
                                   $open_link = "mailto:$platform_link";
                            } else if ($user->social_id == 6) { //pintrest
                                   $open_link = "$social_platforn_url$platform_link";
                            } else if ($user->social_id == 7) {
                                   $open_link = $platform_link;
                            } else if ($user->social_id == 9) {
                                   $open_link = $platform_link;
                            } else if ($user->social_id == 10) {
                                   $open_link = "http://tapandtag.me/tapandtag/generate_vcf/$data->username";
                            } else if ($user->social_id == 11) {
                                   $open_link = "$social_platforn_url$platform_link";
                            } else if ($user->social_id == 12) { //Soundcloud
                                   $open_link = "$social_platforn_url$platform_link";
                            } else if ($user->social_id == 13) {
                                   $open_link = $platform_link;
                            } else if ($user->social_id == 14) {
                                   $open_link = $platform_link;
                            } else if ($user->social_id == 15) {
                                   $open_link = "$social_platforn_url$platform_link";
                            } else if ($user->social_id == 16) {
                                   $open_link = "$social_platforn_url$platform_link";
                            } else if ($user->social_id == 17) {

                                   $open_link = $platform_link;
                            } else if ($user->social_id == 18) {
                                   $open_link = "$social_platforn_url$platform_link";
                            } else if ($user->social_id == 19) {
                                   $open_link = $platform_link;
                            } else if ($user->social_id == 20) {
                                   $open_link = $platform_link;
                            } else if ($user->social_id == 21) { //Tinder
                                   $open_link = $platform_link;
                            } else if ($user->social_id == 22) { //Apple Music
                                   $open_link = $platform_link;
                            } else if ($user->social_id == 23) { //Paysera
                                   $open_link = $platform_link;
                            } else if ($user->social_id == 24) { //Fiver
                                   $open_link = "$social_platforn_url$platform_link";
                            } else if ($user->social_id == 25) { //Alibaba
                                   $open_link = $platform_link;
                            } else if ($user->social_id == 26) { //Pinterest
                                   $open_link = "$social_platforn_url$platform_link";
                            } else if ($user->social_id == 27) { //Tinder
                                   $open_link = $platform_link;
                            } else if ($user->social_id == 28) { //VK
                                   $open_link = "$social_platforn_url$platform_link";
                            } else if ($user->social_id == 29) { //Viber
                                   $open_link = $platform_link;
                            } else if ($user->social_id == 30) { //Telegram
                                   $open_link = "$social_platforn_url$platform_link";
                            } else if ($user->social_id == 31) { //Skype
                                   $open_link = "$social_platforn_url$platform_link";
                            } else if ($user->social_id == 32) { //Odnokassniki
                                   $open_link = $platform_link;
                            } else if ($user->social_id == 33) { //TransferWise
                                   $open_link = "$social_platforn_url$platform_link";
                            } else if ($user->social_id == 34) { //Amazon Business
                                   $open_link = $platform_link;
                            } else if ($user->social_id == 35) { //Link
                                   $open_link = $platform_link;
                            } else if ($user->social_id == 36) { //OnlyFans
                                   $open_link = $platform_link;
                            } else if ($user->social_id == 37) { //Linktree
                                   $open_link = $platform_link;
                            } else if ($user->social_id == 38) { //Calendly
                                   $open_link = "$social_platforn_url$platform_link";
                            } else if ($user->social_id == 39) { //Clubhouse
                                   $open_link = $platform_link;
                            } else if ($user->social_id == 40) { //eToro
                                   $open_link = $platform_link;
                            } else if ($user->social_id == 41) { //podcast
                                   $open_link = $platform_link;
                            } else if ($user->social_id == 42) { //Sqaureup
                                   $open_link = "$social_platforn_url$platform_link";
                            } else if ($user->social_id == 43) { //Afterpay
                                   $open_link = "$social_platforn_url$platform_link";
                            } else if ($user->social_id == 44) { //zip pay
                                   $open_link = "$social_platforn_url$platform_link";
                            } else if ($user->social_id == 45) { //Canva
                                   $open_link = "$social_platforn_url$platform_link";
                            } else if ($user->social_id == 46) { //text
                                   $open_link = "$social_platforn_url$platform_link";
                            } else if ($user->social_id == null) { //custom
                                   $open_link = $platform_link;
                            } else if ($user->social_id == 47) { //Zoom
                                   $open_link = "$social_platforn_url$platform_link";
                            } else if ($user->social_id == 48) { //Bitcoin
                                   $open_link = $platform_link;
                            } else if ($user->social_id == 49) { //Ethereum
                                   $open_link = $platform_link;
                            } else if ($user->social_id == 50) { //Etsy
                                   $open_link = $platform_link;
                            } else if ($user->social_id == 51) { //Shopify
                                   $open_link = $platform_link;
                            } else if ($user->social_id == 52) { //Embedded Video
                                   $open_link = $platform_link;
                            } else if ($user->social_id == 53) { //Excel
                                   $open_link = $platform_link;
                            } else if ($user->social_id == 54) { //PDF file
                                   $open_link = $platform_link;
                            } else if ($user->social_id == 57) { //CSV file
                                   $open_link = $platform_link;
                            } else if ($user->social_id == 58) { //Google Docs
                                   $open_link = $platform_link;
                            } else if ($user->social_id == 59) { //Google Sheets
                                   $open_link = $platform_link;
                            } else if ($user->social_id == 60) { //Google Slides
                                   $open_link = $platform_link;
                            }

                            $data3[$key]->open_link = $open_link;
                     }


                     $count = User::where('id', $id)->get()->pluck('total_tapandtag');


                     return view('tapandtag', ['data' => $data, 'social' => $data3, 'contact_info' => $contact_info, 'count' => $count]);
                     exit;



                     if (empty($data2)) {

                            echo "<h1 style='display: flex;align-items: center;justify-content: center;text-align: center;margin-top: center;width: 50%;height: 50%;overflow: auto;margin: auto;position: absolute;top: 0;left: 0;bottom: 0;right: 0;'>No Data Found For $data->username</h1>";
                            exit;
                     }
              }



              $user_id = $data->id;
              $random_no = $this->get_random_number(6);
              $total_count = User::where('id', $user_id)->get()->first();

              if ($data2->social_platform_name == "Contact card" || $data2->social_platform_name == "Contact Card") {
                     event(new SendNumber($user_id, $data2->social_link, $random_no));
              }

              if ($data2->social_platform_name == "Venmo") {

                     event(new SendVenmo($user_id, $data2->social_link));
              }

              event(new UpdateCount($user_id, $total_count->total_tapandtag));

              $platform_link = $data2->social_link;

              $id = $data2->social_id;

// header("Location: http://tapandtag.me/tapandtag/get_user/$name?id=$id&username=$platform_link");


              return view('profile', ['data' => $data, 'social' => $data2, 'contact_info' => $contact_info]);
       }

       function get_user_test(Request $req) {

              $name = $req->input('id');


              $data = DB::table('users')
                              ->where('id', "$name")
                              ->get()->first();
//              echo '<pre>';
//              print_r($data);
//              exit;

              if (empty($data)) {

//            return response()->json(['status' => 0, 'msg' => 'No User Fond'], 200);

                     echo "<h1 style='display: flex;align-items: center;justify-content: center;text-align: center;margin-top: center;width: 50%;height: 50%;overflow: auto;margin: auto;position: absolute;top: 0;left: 0;bottom: 0;right: 0;'>No User Found With This Username</h1>";
                     exit;
              }

              $id = $data->id;

              User::where('id', $id)->increment('total_tapandtag');

              if ($data->is_link_active == 0) {

                     return abort(404);
                     exit;
              }



              $data2dd = DB::table('tbl_social_link')
                              ->leftJoin('tbl_user_social_link', function($join) use($data) {
                                     $join->on('tbl_social_link.social_id', '=', 'tbl_user_social_link.social_id');
                                     $join->where('tbl_user_social_link.user_id', $data->id);
                                     $join->where('tbl_user_social_link.is_first', 1);
                              })
                              ->get()->first();



              $data2 = tbl_user_social_link::select(array
                                  ('*', 'tbl_user_custom_social_link.social_platform_name as c_social_platform_name',
                                  'tbl_user_custom_social_link.social_platform_icon as c_social_platform_icon'))
                              ->leftjoin('tbl_user_custom_social_link'
                                      , function($join) {
                                     $join->on('tbl_user_social_link.custom_link', '=', 'tbl_user_custom_social_link.social_id');
                              })
                              ->leftjoin('tbl_social_link', function($join) {
                                     $join->on('tbl_user_social_link.social_id', '=', 'tbl_social_link.social_id');
                              })
                              ->where('tbl_user_social_link.user_id', $data->id)
                              ->where('tbl_user_social_link.is_first', 1)
                              ->orderBy('is_first', 'DESC')->get()->first();


              $contact_info = DB::table('tbl_social_link')
                              ->Join('tbl_user_social_link', function($join) use($data) {
                                     $join->on('tbl_social_link.social_id', '=', 'tbl_user_social_link.social_id');
                                     $join->where('tbl_user_social_link.user_id', $data->id);
                                     $join->where('tbl_user_social_link.social_id', 10);
                              })
                              ->join('users', 'tbl_user_social_link.user_id', 'users.id')
                              ->get()->first();




//              if (empty($data2)) {

              $data3d = DB::table('tbl_social_link')
                      ->Join('tbl_user_social_link', function($join) use($data) {
                             $join->on('tbl_social_link.social_id', '=', 'tbl_user_social_link.social_id');
                             $join->where('tbl_user_social_link.user_id', $data->id);
                      })
                      ->get();

              $data3 = tbl_user_social_link::select(array('*', 'tbl_user_custom_social_link.social_platform_name as c_social_platform_name',
                                  'tbl_user_custom_social_link.social_platform_icon as c_social_platform_icon', 'tbl_user_custom_social_link.social_platforn_url as c_social_platforn_url'))->leftjoin('tbl_user_custom_social_link', function($join) {
                                     $join->on('tbl_user_social_link.custom_link', '=', 'tbl_user_custom_social_link.social_id');
                              })
                              ->leftjoin('tbl_social_link', function($join) {
                                     $join->on('tbl_user_social_link.social_id', '=', 'tbl_social_link.social_id');
                              })
                              ->where('tbl_user_social_link.user_id', $data->id)
                              ->orderBy('is_first', 'DESC')->get();

//                     echo '<pre$data3>';
//                     print_r($data3->toArray());
//                     exit;


              foreach ($data3 as $key => $user) {
                     $platform_link = $user->social_link;
                     $social_platforn_url = $user->social_platforn_url;

                     if ($user->social_id == 1) {
                            $open_link = "https://www.instagram.com/$platform_link";
                     } else if ($user->social_id == 2) {
                            $open_link = "https://fb.com/$platform_link"; //"fb://profile/" +
                     } else if ($user->social_id == 3) {
                            $open_link = $platform_link;
                     } else if ($user->social_id == 4) {
                            $open_link = "https://cash.app/$platform_link";
                     } else if ($user->social_id == 5) {
                            $open_link = "mailto:$platform_link";
                     } else if ($user->social_id == 6) {
                            $open_link = "https://in.pinterest.com/$platform_link";
                     } else if ($user->social_id == 7) {
                            $open_link = "https://www.linkedin.com/in/$platform_link";
                     } else if ($user->social_id == 9) {
                            $open_link = "https://www.paypal.me/$platform_link";
                     } else if ($user->social_id == 10) {
                            $open_link = "http://tapandtag.me/tapandtag/generate_vcf/$data->username";
                     } else if ($user->social_id == 11) {
                            $open_link = "https://www.snapchat.com/add/$platform_link";
                     } else if ($user->social_id == 12) {
                            $open_link = "$social_platforn_url$platform_link";
                     } else if ($user->social_id == 13) {
                            $open_link = "$platform_link";
                     } else if ($user->social_id == 14) {
                            $open_link = "https://www.tiktok.com/$platform_link";
                     } else if ($user->social_id == 15) {
                            $open_link = "https://www.twitch.tv/$platform_link";
                     } else if ($user->social_id == 16) {
                            $open_link = "https://twitter.com/$platform_link";
                     } else if ($user->social_id == 17) {
                            $open_link = "https://venmo.com/$platform_link";
                     } else if ($user->social_id == 18) {
                            $open_link = "https://wa.me/$platform_link";
                     } else if ($user->social_id == 19) {
                            $open_link = "https://$platform_link";
                     } else if ($user->social_id == 20) {
                            $open_link = "$platform_link";
                     } else if ($user->social_id == 21) { //Tinder
                            $open_link = "https://tinder.com/$platform_link";
                     } else if ($user->social_id == 22) { //Apple Music
                            $open_link = "https://www.apple.com/in/apple-music/$platform_link";
                     } else if ($user->social_id == 23) { //Paysera
                            $open_link = "https://www.paysera.com/$platform_link";
                     } else if ($user->social_id == 24) { //Fiver
                            $open_link = "$social_platforn_url$platform_link";
                     } else if ($user->social_id == 25) { //Alibaba
                            $open_link = "https://www.alibaba.com/$platform_link";
                     } else if ($user->social_id == 26) { //Pinterest
                            $open_link = 'https://pinterest.com/$platform_link';
                     } else if ($user->social_id == 27) { //Tinder
                            $open_link = " $platform_link";
                     } else if ($user->social_id == 28) { //VK
                            $open_link = "$social_platforn_url$platform_link";
                     } else if ($user->social_id == 29) { //Viber
                            $open_link = "$platform_link";
                     } else if ($user->social_id == 30) { //Telegram
                            $open_link = "$social_platforn_url$platform_link";
                     } else if ($user->social_id == 31) { //Skype
                            $open_link = " $social_platforn_url$platform_link";
                     } else if ($user->social_id == 32) { //Odnokassniki
                            $open_link = " $social_platforn_url$platform_link";
                     } else if ($user->social_id == 33) { //TransferWise
                            $open_link = " $social_platforn_url$platform_link";
                     } else if ($user->social_id == 34) { //Amazon Business
                            $open_link = " $social_platforn_url$platform_link";
                     } else if ($user->social_id == 35) { //Link
                            $open_link = " $social_platforn_url$platform_link";
                     } else if ($user->social_id == 36) { //OnlyFans
                            $open_link = " $social_platforn_url$platform_link";
                     } else if ($user->social_id == 37) { //Linktree
                            $open_link = " $social_platforn_url$platform_link";
                     } else if ($user->social_id == 38) { //Calendly
                            $open_link = " $social_platforn_url$platform_link";
                     } else if ($user->social_id == 39) { //Clubhouse
                            $open_link = " $social_platforn_url$platform_link";
                     } else if ($user->social_id == 40) { //eToro
                            $open_link = " $social_platforn_url$platform_link";
                     } else if ($user->social_id == 41) { //podcast
                            $open_link = "$social_platforn_url$platform_link";
                     } else if ($user->social_id == 42) { //Sqaureup
                            $open_link = " $social_platforn_url$platform_link";
                     } else if ($user->social_id == 43) { //Afterpay
                            $open_link = " $social_platforn_url$platform_link";
                     } else if ($user->social_id == 44) { //zip pay
                            $open_link = " $social_platforn_url$platform_link";
                     } else if ($user->social_id == 45) { //Canva
                            $open_link = "$social_platforn_url$platform_link";
                     } else if ($user->social_id == 46) { //text
                            $open_link = "$platform_link";
                     } else if ($user->social_id == 47) { //Zoom
                            $open_link = "$social_platforn_url$platform_link";
                     } else if ($user->social_id == 48) { //Bitcoin
                            $open_link = "$social_platforn_url$platform_link";
                     } else if ($user->social_id == 49) { //Ethereum
                            $open_link = "$social_platforn_url$platform_link";
                     } else if ($user->social_id == 50) { //Etsy
                            $open_link = "$social_platforn_url$platform_link";
                     } else if ($user->social_id == 51) { //Shopify
                            $open_link = "$social_platforn_url$platform_link";
                     } else if ($user->social_id == 52) { //Embedded Video
                            $open_link = "$platform_link";
                     } else if ($user->social_id == 53) { //Excel
                            $open_link = "$platform_link";
                     } else if ($user->social_id == 54) { //PDF file
                            $open_link = "$platform_link";
                     } else if ($user->social_id == 57) { //CSV file
                            $open_link = "$platform_link";
                     } else if ($user->social_id == 58) { //Google Docs
                            $open_link = "$social_platforn_url$platform_link";
                     } else if ($user->social_id == 59) { //Google Sheets
                            $open_link = "$social_platforn_url$platform_link";
                     } else if ($user->social_id == 60) { //Google Slides
                            $open_link = "$social_platforn_url$platform_link";
                     } else if ($user->social_id == null) { //text
                            $open_link = "$platform_link";
                     }

                     $data3[$key]->open_link = $open_link;
              }
              return response()->json(['status' => 1, 'msg' => 'User social link list successfully', 'data' => $data, 'social' => $data3, 'contact_info' => $contact_info], 200);

//              return view('tapandtag', ['data' => $data, 'social' => $data3, 'contact_info' => $contact_info]);
//              exit;



              if (empty($data2)) {

                     echo "<h1 style='display: flex;align-items: center;justify-content: center;text-align: center;margin-top: center;width: 50%;height: 50%;overflow: auto;margin: auto;position: absolute;top: 0;left: 0;bottom: 0;right: 0;'>No Data Found For $data->username</h1>";
                     exit;
              }
//              }



              $user_id = $data->id;
              $random_no = $this->get_random_number(6);
              $total_count = User::where('id', $user_id)->get()->first();

              if ($data2->social_platform_name == "Contact card" || $data2->social_platform_name == "Contact Card") {
                     event(new SendNumber($user_id, $data2->social_link, $random_no));
              }

              if ($data2->social_platform_name == "Venmo") {

                     event(new SendVenmo($user_id, $data2->social_link));
              }

              event(new UpdateCount($user_id, $total_count->total_tapandtag));

              $platform_link = $data2->social_link;

              $id = $data2->social_id;

// header("Location: http://tapandtag.me/tapandtag/get_user/$name?id=$id&username=$platform_link");


              return view('profile', ['data' => $data, 'social' => $data2, 'contact_info' => $contact_info]);
       }

}
