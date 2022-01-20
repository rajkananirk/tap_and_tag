<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use DB;
use Auth;
use App\admin;
use App\Model\tbl_user_reciept;
use App\Model\tbl_user_otp;
use App\Model\tbl_user_social_link;
use Session;
use Validator;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Redirect;

class AdminController extends BaseController {

       //
       function Admin(Request $request) {
              if (empty($request->session()->has('login'))) {
                     return view('Admin/Adminlogin');
              } else {
                     return Redirect::to('Dashboard');
              }
       }

       function admin_login(Request $request) {
              $email = $request->email;
              $password = $request->password;

              $login = admin::where(['email' => $email, 'password' => $password])->first();

              if (!empty($login)) {

                     //Store Session
                     $request->session()->put('login', 'login');

                     return Redirect::to('Dashboard');
              } else {
                     return back()->with('error', 'Email Or Password Wrong!');
              }
       }

       //Logout
       function logout(Request $request) {
              Session::forget('login');
              if (!Session::has('login')) {
                     return Redirect::to('/Admin');
              }
       }

       function Dashboard(Request $request) {

//        $data = DB::select("select (SELECT COUNT(id) from users WHERE user_role = 1)as total_customer, (SELECT COUNT(id) from users WHERE user_role = 2)as total_provider from users limit 1");
              $data = User::
                      get()->count();

              return view("Admin/Dashboard")->with(compact('data'));
       }

       //list user
       function list_user(Request $request) {


              $data = User::where(['is_active' => 1])->get();

              return view("Admin/User")->with(compact('data'));

//        return view("Admin/User");
       }

       function social_link(Request $request, $id) {
              $data2 = DB::table('users')
                              ->where('id', "$id")
                              ->get()->first();
              $data = tbl_user_social_link::select(array('*', 'tbl_user_custom_social_link.social_platform_name as c_social_platform_name',
                                  'tbl_user_custom_social_link.social_platform_icon as c_social_platform_icon', 'tbl_user_custom_social_link.social_platforn_url as c_social_platforn_url'))->leftjoin('tbl_user_custom_social_link', function($join) {
                                     $join->on('tbl_user_social_link.custom_link', '=', 'tbl_user_custom_social_link.social_id');
                              })
                              ->leftjoin('tbl_social_link', function($join) {
                                     $join->on('tbl_user_social_link.social_id', '=', 'tbl_social_link.social_id');
                              })
                              ->where('tbl_user_social_link.user_id', $id)
                              ->orderBy('is_first', 'DESC')->get();

              foreach ($data as $key => $user) {
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
                            $open_link = "http://tapandtag.me/tapandtag/generate_vcf/$data2->username";
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

                     $data[$key]->open_link = $open_link;
              }
//              echo '<pre>';
//              print_r($data->toArray());
//              exit;

              return view("Admin/SocialLink")->with(compact('data'));
       }

       //list In app_purchase
       function list_inapp_purchase(Request $request) {


              $data = tbl_user_reciept::select(array('*',
                          DB::raw("(SELECT t1.username from users t1 where t1.id = tbl_user_reciept.user_id)as user_name"),
                          DB::raw("(SELECT t1.email from users t1 where t1.id = tbl_user_reciept.user_id)as email")))->get();

//              echo '<pre>';
//              print_r($data->toArray());
//              exit;

              return view("Admin/InAppPurchase")->with(compact('data'));

//        return view("Admin/User");
       }

       function check_session(Request $request) {
              if (empty($request->session()->has('login'))) {
                     return Redirect::to('/Admin');
              }
       }

       function otp_list(Request $request) {

//              $tbl_user_otp = tbl_user_otp::select(array('*',
//                          DB::raw("(SELECT t1.username from users t1 where t1.id = tbl_user_otp.user_id)as username"),
//                          DB::raw("(SELECT t1.temp_pass from users t1 where t1.id = tbl_user_otp.temp_pass)as temp_pass_main"),
//                          DB::raw("(SELECT t1.email from users t1 where t1.id = tbl_user_otp.user_id)as email"),
//                          DB::raw("IFNULL((SELECT DATE_FORMAT(created_at,'%d %M,%Y %h:%i %p')),'N/A')as created_at"),
//                      ))
//                      ->groupBy('users.id')
//                      ->orderBy('otp_id', 'desc')
//                      ->get();

              $tbl_user_otp = DB::table('tbl_user_otp')->select('otp_id', 'user_id', 'username', 'email', 'tbl_user_otp.temp_pass',
                              DB::raw("IFNULL((SELECT DATE_FORMAT(tbl_user_otp.created_at,'%d %M,%Y %h:%i %p')),'N/A')as created_at"))
                      ->leftJoin('users', 'users.id', '=', 'tbl_user_otp.user_id')
                      ->orderBy('otp_id', 'desc')
                      ->get();

//              echo '<pre>';
//              print_r($tbl_user_otp->toArray());
//              exit;
              return view("Admin/OtpUserList")->with(compact('tbl_user_otp'));
       }

       //Block User
       function update_otp($otp_id) {

              $data = tbl_user_otp::where('otp_id', $otp_id)->get()->first();

              User::where('id', $data->user_id)->update(['temp_pass' => $data->temp_pass]);
              return redirect()->back()->with('success', 'Otp Send Successfully');
       }

       //Block User
       function block_user($user_id) {
              $where['id'] = $user_id;
              $update['is_blocked'] = 1;
              $this->update('users', $where, $update);

              //logout this user from all device
              $where2['user_id'] = $user_id;
              $update2['revoked'] = 1;
              $this->update('oauth_access_tokens', $where2, $update2);

              //delete all device token
              $this->delete('tbl_token', $where2);

              return redirect()->back()->with('success', 'User Blocked Successfully');
       }

       function block_link($link_id) {
              $where['link_id'] = $link_id;
              $update['is_link_blocked'] = 1;
              $this->update('tbl_user_social_link', $where, $update);


              return redirect()->back()->with('success', 'Link Blocked Successfully');
       }

       function unblock_link($link_id) {
              $where['link_id'] = $link_id;
              $update['is_link_blocked'] = 0;
              $this->update('tbl_user_social_link', $where, $update);

              return redirect()->back()->with('success', 'Link UnBlocked Successfully');
       }

       //UnBlock User
       function unblock_user($user_id) {
              $where['id'] = $user_id;
              $update['is_blocked'] = 0;
              $user_id = $this->update('users', $where, $update);
              return redirect()->back()->with('success', 'User UnBlocked Successfully');
       }

       //Delete User
       function delete_user($user_id) {
              $where['id'] = $user_id;
              $update['is_active'] = 0;
              $user_id = $this->update('users', $where, $update);


//        $user_id = User::where('id', $user_id)->update(['is_active' => 0]);
//        print_r($user_id);
//        exit;
              //logout this user from all device
              $where2['user_id'] = $user_id;
              $update2['revoked'] = 1;
              $this->update('oauth_access_tokens', $where2, $update2);

              //delete all device token
              $this->delete('tbl_token', $where2);

//        print_r($user_id);


              return redirect()->back()->with('success', 'User Deleted Successfully');
       }

}
