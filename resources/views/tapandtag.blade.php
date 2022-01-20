<!DOCTYPE html>
<html>
       <head>

              <title>Tap And Tag</title>
              <meta name="viewport" content="width=device-width, initial-scale=1">
              <link rel="stylesheet" type="text/css" href="{{asset('public/css/index-2.css')}}">
              <link rel="stylesheet" type="text/css" href="{{asset('public/css/style_1.css')}}">
              <link rel="icon" href="{{asset('public/css/Icon/ttlogo.png')}}" type="image/gif" sizes="16x16">
              <link rel="preconnect" href="https://fonts.googleapis.com">
              <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
              <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
              <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
              <meta property="og:image" content="{{asset('public/css/Icon/bg_latest.PNG')}}">
              <meta property="og:image:type" content="image/png">
              <meta property="og:image:width" content="1024">
              <meta property="og:image:height" content="1024">
              <style>
                     .f-contact i {
                            width: 40px;
                            height: 40px;
                            background: #fff;
                            line-height: 40px;
                            color: #95a1e4 !important;
                            text-align: center;
                            border-radius: 50%;
                            float: left;
                     }
                     .ourprofiletag{
                            background: -webkit-linear-gradient(#eee, #333);
                            -webkit-background-clip: text;
                            -webkit-text-fill-color: #1bc5c6;
                            font-size: 18px;
                            font-weight: bold;
                            text-align: center;
                     }
                     #grad1 {

                            background-color: red;
                            /* For browsers that do not support gradients */
                            background-image: linear-gradient(#2AF696, #13A5E7);
                     }

                     .myDiv2 {
                            /* text-align: center;
                            display: flex;
                            justify-content: center; */
                            border-radius: 180px;
                            padding-top: 10px;
                            padding-bottom: 10px;
                            width: 640px;
                            height: 90px;
                            background-color: blue;
                     }

                     .myDivRound {
                            /* text-align: center;
                            display: flex;
                            justify-content: center; */
                            border-radius: 180px;
                            padding-top: 10px;
                            padding-bottom: 10px;
                            width: 90px;
                            height: 90px;
                            background-color: green;
                            ;
                     }

                     .myDiv {
                            text-align: center;
                            position: absolute;
                            display: flex;
                            justify-content: center;
                            border-radius: 180px;
                            width: 100%;
                            height: 80px;
                            background-color: white;
                            margin-right: 10px;
                     }

                     .fDiv {
                            position: absolute;
                            display: flex;
                            justify-content: center;
                            text-align: center;
                            border-radius: 180px;
                            padding-top: 5px;
                            padding-bottom: 5px;
                            width: 100%;
                            height: 80px;

                     }

                     .clearfix::before {
                            content: "";
                            clear: both;
                            display: table;
                     }

                     .containerraj {
                            width: 100%;
                            height: 90px;
                            position: absolute;
                            padding-top: 10px;
                            padding-bottom: 10px;
                            /*margin: 20px;*/
                            /* background-color: green; */


                     }

                     .profileimg {
                            border-radius: 50%;
                            width: 100px;
                            position: absolute;
                            top: 6px;
                            left: 0;

                     }

                     .profileimg2 {
                            border-radius: 50%;
                            width: 50px;
                            top: 0;

                            right: 21px;
                            float: right;
                            margin-top: 20px;
                            clear: right;
                            position: relative;

                     }

                     /*Media query use only*/
                     .containerrajmm {
                            width: 100%;
                            height: 67px;
                            position: absolute;
                            padding-top: 10px;
                            padding-bottom: 10px;
                            /* margin: 20px; */
                            /* background-color: green; */
                     }
                     .fDivmm {
                            position: absolute;
                            display: flex;
                            justify-content: center;
                            text-align: center;
                            border-radius: 180px;
                            padding-top: 5px;
                            padding-bottom: 5px;
                            width: 100%;
                            height: 57px;
                     }
                     .fDivmm {
                            position: absolute;
                            display: flex;
                            justify-content: center;
                            text-align: center;
                            border-radius: 180px;
                            padding-top: 5px;
                            padding-bottom: 5px;
                            width: 100%;
                            height: 57px;
                     }
                     .myDivmm {
                            text-align: center;
                            position: absolute;
                            display: flex;
                            justify-content: center;
                            border-radius: 180px;
                            width: 100%;
                            height: 57px;
                            background-color: white;
                            margin-right: 9px;
                     }

                     element.stylemm {
                            padding-top: 16px;
                            font-size: 11px;
                            text-align: center;
                     }
                     .profileimgmm {
                            border-radius: 50%;
                            width: 76px;
                            position: absolute;
                            top: 6px;
                            left: 0;
                     }
                     .profileimg2mm {
                            border-radius: 50%;
                            width: 45px;
                            top: 0;
                            right: 13px;
                            float: right;
                            margin-top: 11px;
                            clear: right;
                            position: relative;
                     }
                     .container {
                            position: relative;
                            text-align: center;
                            color: white;
                     }

                     .bottom-left {
                            position: absolute;
                            left: 16px;
                            top: 50%;
                            left: 50%;
                            transform: translate(-50%, -50%);
                     }

                     .btn_muneshs {
                            background-image: linear-gradient(to top, #13aedc 30%, #14f6a5 100%);
                            border-radius: 40px;
                            box-sizing: border-box;
                            color: #00a84f;
                            display: block;
                            font: 1.125rem 'Oswald', Arial, sans-serif;
                            height: 60px;
                            letter-spacing: 1px;
                            margin: 0 auto;
                            padding: 4px;
                            position: relative;
                            text-decoration: none;
                            text-transform: uppercase;
                            width: 100%;
                            pointer-events: none;
                            z-index: 2;
                     }

                     .btn_muneshs:hover {
                            color: #fff;
                     }

                     .btn_muneshs span {
                            align-items: center;
                            background: #fff;
                            border-radius: 40px;
                            display: flex;
                            font-family: Poppins, sans-serif;
                            justify-content: center;
                            text-transform: capitalize;
                            font-weight: 500;
                            height: 100%;
                            transition: background .5s ease;
                            width: 100%;
                     }

                     .btn_muneshs:hover span {
                            background: transparent;
                     }
                     .profileimg_munesh {
                            border-radius: 50%;
                            width: 65px;
                            position: absolute;
                            margin-top: -3px;
                            left: 16px;
                            box-shadow: -1px 1px 8px #6b6969;
                            z-index: 3;
                     }
                     .profileimg2_munesh{
                            border-radius: 50%;
                            width: 50px;
                            top: 19px;
                            right: 30px;
                            float: right;
                            margin-top: 20px;
                            clear: right;
                            position: absolute;
                            z-index: 9;
                     }
                     .profileimg2_muneshv1{
                            width: 30px;
                            margin-top: -45px;
                            right: 50px;
                            float: right;
                            clear: right;
                            position: absolute;
                            z-index: 9;
                     }
                     .mb-5{
                            margin: 25px;
                     }
                     .c_icon{
                            border-radius: 50%;
                            width: 65px;
                            position: absolute;
                            margin-top: -3px;
                            left: 16px;
                            box-shadow: -1px 1px 8px #6b6969;
                            z-index: 3;
                            height: 65px;
                     }
                     #raj:hover {
                            background-color: white;
                     }

                     .circular--portrait {
                            position: relative;
                            width: 150px;
                            height: 150px;
                            overflow: hidden;
                            border-radius: 50%;
                            margin-left: 54px;
                     }
                     .circular--portrait img {
                            width: 100%;
                            height: auto;
                     }
              </style>
       </head>
       <body>

              <main>
                     <a rel="noopener" class="banner" target="_blank" href="https://tapandtag.co">
                            <div class="text-center sticky" style="  background: linear-gradient(0deg, #27eaa1,#14b0db) ;">🛍 Tap here to get your Tap and Tag</div>
                     </a>
                     <section class="profile-section" style="padding-top: 52px;">
                            <h3>
                                   <img src="{{asset('public/css/Icon/namelogo.png')}}" width="60" alt="button image">
                            </h3>
                     </section>

                     @if($data->is_business_profile == 1)
                     <section class="secondary-section">
                            <div class="p-r text-center m-auto">
                                   <div style="margin-top: 20px;">
                                          <div class="circular--portrait">
                                                 <img src="http://tapandtag.me/tapandtag/{{$data->business_profile_pic}}">
                                          </div>

                                   </div>
                            </div>
                     </section>
                     <section class="profile-section">
                            <h3>
                                   @if(!empty($data->business_name))
                                   {{$data->business_name}}
                                   @else
                                   @endif
                            </h3>

                            <p class="eachcap" style="text-transform: lowercase;">
                                   @if(!empty($data->business_email))
                                   {{$data->business_email}}
                                   @else
                                   @endif
                            </p>

                            <p class="eachcap">
                                   @if(!empty($data->business_phone))
                                   {{$data->business_phone}}
                                   @else
                                   @endif
                            </p>

                            <p class="eachcap" style="text-transform: lowercase;">
                                   @if(!empty($data->business_website))
                                   {{$data->business_website}}
                                   @else
                                   @endif
                            </p>
                     </section>



              </section>
              <section class="container" style="margin-top: 25px">


                     @if(empty($contact_info))
                     <div class="row" style="display: block;">
                            <button type="button" style="margin-right: 5px;vertical-align: baseline;" class="text-center profile-btn" name="button"> <img width="20" src="{{asset('public/css/Icon/ic_tap&tag.png')}}" alt=""><span style="margin-left: 5px;vertical-align: super;">{{$data->total_tapandtag}}</span></button>



                     </div>

                     @else
                     <div class="row">
                            <button type="button" style="margin-right: 5px;vertical-align: baseline;" class="text-center profile-btn" name="button"> <img width="20" src="{{asset('public/css/Icon/ic_tap&tag.png')}}" alt=""><span style="margin-left: 5px;vertical-align: super;">{{$data->total_tapandtag}}</span></button>


                            <a href="{{$contact_info->vcard_link}}"  style="text-decoration: none;color: white;" target="_blank" class="text-center profile-btn" name="button" style="margin-left: 5px;">Add contact</a>
                     </div>
                     @endif



              </section>

              <div style="margin-bottom: 100px;">


                     @foreach ($social as $value => $user)

                     @if($user->custom_link)
                     <div class="mb-5">
                            <div class="">
                                   <img class="c_icon" src="http://tapandtag.me/tapandtag/{{$user->c_social_platform_icon}}" alt="Avatar">
                            </div>
                            <a class="btn_muneshs" href="#">
                                   <span>{{$user->c_social_platform_name}}</span>
                            </a>
                            <div class="">
                                   <a id="raj" href="{{$user->open_link}}">
                                          <img class="profileimg2_muneshv1" src="{{asset('public/social_icon/direct.png')}}" alt="Avatar">

                                   </a>
                            </div>
                     </div>
                     @else
                     <div class="mb-5">
                            <div class="">
                                   <img class="profileimg_munesh" src="http://tapandtag.me/tapandtag/{{$user->social_platform_icon}}" alt="Avatar">
                            </div>
                            <a class="btn_muneshs" href="#">
                                   <span>{{$user->social_platform_name}}</span>
                            </a>
                            <div class="">
                                   <a id="raj" href="{{$user->open_link}}">
                                          <img class="profileimg2_muneshv1" src="{{asset('public/social_icon/direct.png')}}" alt="Avatar">

                                   </a>
                            </div>
                     </div>
                     @endif
                     @endforeach
              </div>

              @else
              <!-- feature image section -->
              <section class="secondary-section">
                     <div class="p-r text-center m-auto">

                            <section class="secondary-section">
                                   <div class="p-r text-center m-auto">
                                          <div style="margin-top: 20px;">
                                                 <div class="circular--portrait">
                                                        <img src="http://tapandtag.me/tapandtag/{{$data->profile_pic}}">
                                                 </div>

                                          </div>
                                   </div>
                            </section>


                     </div>
              </section>

              <!-- end feature image section -->
              <section class="profile-section">
                     <h3>
                            @if(!empty($data->name))
                            {{$data->name}}
                            @else
                            @endif
                     </h3>

                     <p class="eachcap">
                            @if(!empty($data->user_bio))
                            {{$data->user_bio}}
                            @else
                            @endif
                     </p>



                     <section class="container" style="margin-top: 25px">
                            <div class="row">

                                   @if(empty($contact_info))
                                   <div class="row" style="display: block;">
                                          <button type="button" style="margin-right: 5px;vertical-align: baseline;" class="text-center profile-btn" name="button"> <img width="20" src="{{asset('public/css/Icon/ic_tap&tag.png')}}" alt=""><span style="margin-left: 5px;vertical-align: super;">{{$data->total_tapandtag}}</span></button>



                                   </div>

                                   @else
                                   <div class="row">
                                          <button type="button" style="margin-right: 5px;vertical-align: baseline;" class="text-center profile-btn" name="button"> <img width="20" src="{{asset('public/css/Icon/ic_tap&tag.png')}}" alt=""><span style="margin-left: 5px;vertical-align: super;">{{$data->total_tapandtag}}</span></button>


                                          <a href="{{$contact_info->vcard_link}}"  style="text-decoration: none;color: white;" target="_blank" class="text-center profile-btn" name="button" style="margin-left: 5px;">Add contact</a>
                                   </div>
                                   @endif

                            </div>

                     </section>
              </section>

              <div style="margin-bottom: 100px;">

                     @foreach ($social as $value => $user)

                     @if($user->is_premium == 0)

                     @if($user->custom_link)
                     <div class="mb-5">
                            <div class="">
                                   <img class="c_icon" src="http://tapandtag.me/tapandtag/{{$user->c_social_platform_icon}}" alt="Avatar">
                            </div>
                            <a class="btn_muneshs" href="#">
                                   <span>{{$user->c_social_platform_name}}</span>
                            </a>
                            <div class="">
                                   <a id="raj" href="{{$user->open_link}}">
                                          <img class="profileimg2_muneshv1" src="{{asset('public/social_icon/direct.png')}}" alt="Avatar">

                                   </a>
                            </div>
                     </div>
                     @else
                     <div class="mb-5">
                            <div class="">
                                   <img class="profileimg_munesh" src="http://tapandtag.me/tapandtag/{{$user->social_platform_icon}}" alt="Avatar">
                            </div>
                            <a class="btn_muneshs" href="#">
                                   <span>{{$user->social_platform_name}}</span>
                            </a>
                            <div class="">
                                   <a id="raj" href="{{$user->open_link}}">
                                          <img class="profileimg2_muneshv1" src="{{asset('public/social_icon/direct.png')}}" alt="Avatar">

                                   </a>
                            </div>
                     </div>
                     @endif

                     @else
                     @endif
                     @endforeach


              </div>
              @endif


              <div>
                     <section class="container" style="text-align: center;margin-top: 35px;line-height: 44px;
                              height: 45px;
                              font-size: 16px;
                              text-align: center;
                              position: fixed;
                              font-weight: 600;
                              bottom: 0;
                              width: auto;
                              background-color: white;
                              left: 0;
                              padding-top: 10px;
                              right: 0;
                              z-index: 999999999 !important;
                              padding: 5px;
                              color: #fff;">
                            <div class="center">


                                   <form action="https://apps.apple.com/in/app/tap-and-tag/id1572988532">
                                          <button type="submit" style="margin-right: 5px;vertical-align: baseline;" class="text-center profile-btn">
                                                 Create Your Own Profile
                                          </button>

                                   </form>


                            </div>

                            <div style="text-align: center;margin-top: 28px;">


                            </div>

                     </section>

              </div>
       </main>
</body>
</html>