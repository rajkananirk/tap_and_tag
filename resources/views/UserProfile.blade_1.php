+<!DOCTYPE html>
<html>
       <head>

              <title>Tap And Tag</title>
              <meta name="viewport" content="width=device-width, initial-scale=1.0">
              <link rel="stylesheet" type="text/css" href="{{asset('public/css/index-2.css')}}">
              <link rel="stylesheet" type="text/css" href="{{asset('public/css/style_1.css')}}">
              <link rel="icon" href="{{asset('public/css/Icon/ttlogo.png')}}" type="image/gif" sizes="16x16">

              <link rel="preconnect" href="https://fonts.googleapis.com">
              <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
              <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
              <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

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

              </style>
       </head>
       <body>

              <main>
                     <a rel="noopener" class="banner" target="_blank" href="https://tapandtag.co">
                            <div class="text-center sticky" style="background: linear-gradient(
                                 360deg
                                 , rgb(20 175 219) 0%, rgb(33 239 161) 9%, rgba(0,255,170,1) 9%, rgba(39,236,159,1) 11%, rgba(19,172,223,1) 91%, rgb(19 173 222) 92%);">🛍 Tap here to get your Tapandtag</div></a>
                     <section class="profile-section" style="padding-top: 52px;">
                            <h3>
                                   <img src="{{asset('public/css/Icon/namelogo.png')}}" width="60" alt="button image">
                            </h3>
                     </section>

                     @if($data->is_business_profile == 110)
                     <section style="position: relative;
                              height: 250px;
                              overflow: hidden;">
                            <img src="http://tapandtag.me/tapandtag/{{$data->business_profile_pic}}" width="100%" >
                     </section>
                     <!-- end feature image section -->
                     <section style="margin-top: 10px;padding-left: 15px;">
                            <div style="margin-top: 27px;">
                                   <i class="fa fa-user" style="width: 25px;
                                      font-size: 24px;
                                      height: 25px;
                                      text-align: center;
                                      border-radius: 38px;
                                      padding: 9px;
                                      box-shadow: 0px 0px 10px grey;">

                                   </i>
                                   <i class="fa fa-envelope1" style="margin-left: 9px;">
                                          <span style="font-size: 16px;
                                                font-family: Poppins, sans-serif;
                                                font-weight: 600;"> @if(!empty($data->business_name))
                                                 {{$data->business_name}}
                                                 @else
                                                 N/A
                                                 @endif</span><br>
                                          <span style="font-family: Poppins, sans-serif;
                                                font-weight: 500;
                                                color: grey;">name</span>


                                   </i>

                            </div>
                            <div style="margin-top: 27px;">
                                   <i class="fa fa-envelope" style="
                                      font-size: 27px;
                                      border-radius: 28px;
                                      padding: 9px;
                                      box-shadow: 0px 0px 10px grey;">

                                   </i>
                                   <i class="fa fa-envelope1" style="margin-left: 9px;">
                                          <span style="font-size: 16px;
                                                font-family: Poppins, sans-serif;
                                                font-weight: 600;">@if(!empty($data->business_email))
                                                 {{$data->business_email}}
                                                 @else
                                                 N/A
                                                 @endif</span><br>
                                          <span style="font-family: Poppins, sans-serif;
                                                font-weight: 500;
                                                color: grey;">email</span>


                                   </i>

                            </div>
                            <div style="margin-top: 27px;">
                                   <i class="fa fa-phone" style="    vertical-align: middle;
                                      font-size: 27px;
                                      border-radius: 33px;
                                      padding: 9px;
                                      text-align: center;
                                      width: 25px;
                                      height: 25px;
                                      box-shadow: 0px 0px 10px grey;">

                                   </i>
                                   <i class="fa fa-envelope1" style="margin-left: 9px;">
                                          <span style="font-size: 16px;
                                                font-family: Poppins, sans-serif;
                                                font-weight: 600;">
                                                 @if(!empty($data->business_phone))
                                                 {{$data->business_phone}}
                                                 @else
                                                 N/A
                                                 @endif</span><br>
                                          <span style="font-family: Poppins, sans-serif;
                                                font-weight: 500;
                                                color: grey;">mobile</span>


                                   </i>

                            </div>
                            <div style="margin-top: 27px;">
                                   <i class="fa fa-globe" style="    vertical-align: middle;
                                      font-size: 26px;
                                      border-radius: 33px;
                                      padding: 9px;
                                      text-align: center;
                                      width: 25px;
                                      height: 25px;
                                      box-shadow: 0px 0px 10px grey;">

                                   </i>
                                   <i class="fa fa-envelope1" style="margin-left: 9px;">
                                          <span style="font-size: 16px;
                                                font-family: Poppins, sans-serif;
                                                font-weight: 600;">   @if(!empty($data->business_website))
                                                 {{$data->business_website}}
                                                 @else
                                                 N/A
                                                 @endif</span><br>
                                          <span style="font-family: Poppins, sans-serif;
                                                font-weight: 500;
                                                color: grey;">company website</span>


                                   </i>

                            </div>


                     </section>
                     <section class="container" style="margin-top: 25px">
                            <div class="row">


                                   @if(empty($contact_info))
                                   <button type="button" style="margin-right: 5px;vertical-align: baseline;" class="text-center profile-btn" name="button"> <img width="20" src="{{asset('public/css/Icon/ic_tap&tag.png')}}" alt=""><span style="margin-left: 5px;vertical-align: super;">{{$data->total_tapandtag}}</span></button>




                                   @else
                                   <button type="button" style="margin-right: 5px;vertical-align: baseline;" class="text-center profile-btn" name="button"> <img width="20" src="{{asset('public/css/Icon/ic_tap&tag.png')}}" alt=""><span style="margin-left: 5px;vertical-align: super;">{{$data->total_tapandtag}}</span></button>


                                   <button onclick="window.location.href = {{$contact_info->vcard_link}}" type="button" class="text-center profile-btn" name="button" style="margin-left: 5px;"><a href="{{$contact_info->vcard_link}}" style="text-decoration: none;color: white;" target="_blank" >Add contact</a></button>
                                   @endif


                            </div>

                     </section>
                     @else
                     <!-- feature image section -->
                     <section class="secondary-section">
                            <div class="p-r text-center m-auto">
                                <!-- <img class="profile-image" src="{{$data->profile_pic}}" alt="" height="150" width="150"> -->
                                   <div style="margin-top: 20px;">
                                          <div class="circular--portrait">
                                                 <img src="http://tapandtag.me/tapandtag/{{$data->profile_pic}}" style="height: 150px;"/>
                                          </div>
                                          <!-- <img src="{{asset('public/images/assets/activate.png')}}" class="profile-icon" alt=""> -->

                                   </div>

                            </div>
                     </section>

                     <!-- end feature image section -->
                     <section class="profile-section">
                            <h3>
                                   @if(!empty($data->name))
                                   {{$data->name}}
                                   @else
                                   N/A
                                   @endif
                            </h3>
                            <p>
                                   @if(!empty($data->email))
                                   {{$data->email}}
                                   @else
                                   N/A
                                   @endif
                            </p>
                            <p>
                                   @if(!empty($data->phone_number))
                                   {{$data->phone_number}}
                                   @else
                                   N/A
                                   @endif
                            </p>
                            <p> <a target="_blank" href="">
                                          @if(!empty($data->business_website))
                                          {{$data->business_website}}
                                          @else
                                          N/A
                                          @endif
                                   </a> </p>
                            <p class="eachcap">
                                   @if(!empty($data->user_bio))
                                   {{$data->user_bio}}
                                   @else
                                   N/A
                                   @endif
                            </p>



                            <section class="container" style="margin-top: 25px">
                                   <div class="row">


                                          @if(empty($contact_info))
                                          <button type="button" style="margin-right: 5px;vertical-align: baseline;" class="text-center profile-btn" name="button"> <img width="20" src="{{asset('public/css/Icon/ic_tap&tag.png')}}" alt=""><span style="margin-left: 5px;vertical-align: super;">{{$data->total_tapandtag}}</span></button>




                                          @else
                                          <button type="button" style="margin-right: 5px;vertical-align: baseline;" class="text-center profile-btn" name="button"> <img width="20" src="{{asset('public/css/Icon/ic_tap&tag.png')}}" alt=""><span style="margin-left: 5px;vertical-align: super;">{{$data->total_tapandtag}}</span></button>


                                          <button onclick="window.location.href = {{$contact_info->vcard_link}}" type="button" class="text-center profile-btn" name="button" style="margin-left: 5px;"><a href="{{$contact_info->vcard_link}}" style="text-decoration: none;color: white;" target="_blank" >Add contact</a></button>
                                          @endif


                                   </div>

                            </section>
                     </section>
                     @endif
                     <!-- profile section -->
                     <div class="containerraj">
                            <div class="fDiv" id="grad1">

                                   <div class="fDiv"></div>

                                   <div class="myDiv clearfix">
                                          <h2 style="padding-top: 8px;">This is a heading in a div element</h2>

                                   </div>
                            </div>
                            <img class="profileimg" src="https://www.w3schools.com/howto/img_avatar.png" alt="Avatar">

                            <img class="profileimg2" src="https://www.w3schools.com/howto/img_avatar.png" alt="Avatar">
                     </div>



                     <!-- end profile section -->
                     <?php
                     for ($i = 0; $i < count($social); $i++) {
                            ?>

                            <div class="row margin-zactra" style="displa">
                                   @for ($j = 0; $j <= 1; $j++)
                                   @if(($i+$j) < count($social))
                                   @if($social[$i+$j]->custom_link)
                                   <div class="col6">

                                          <div class="social-div">
                                                 <a href="{{$social[$i+$j]->c_social_platforn_url}}"><img style="    border-style: solid;
                                                                                                          border-width: 20px;
                                                                                                          border-color: white;
                                                                                                          position: relative;
                                                                                                          width: 80px;
                                                                                                          height: 80px;
                                                                                                          overflow: hidden;" width="100%" src="http://tapandtag.me/tapandtag/{{$social[$i+$j]->c_social_platform_icon}}"
                                                                                                          alt=""></a>
                                          </div>
                                   </div>
                                   @else
                                   <div class="col6">
                                          <div class="social-div">
                                                 <a href="{{$social[$i+$j]->open_link}}"><img width="100%" src="http://tapandtag.me/tapandtag/{{$social[$i+$j]->social_platform_icon}}"
                                                                                              alt=""></a>
                                          </div>
                                   </div>
                                   @endif

                                   @endif
                                   @endfor
                            </div>

                            <?php
                            $i++;
//                            $i++;
                     }
                     ?>
                     <section class="container" style="text-align: center;margin-top: 35px">
                            <div class="center">


                                   <form action="">
                                          <input type="hidden" value="" id="id" name="id">

                                          <button type="submit" style="margin-right: 5px;vertical-align: baseline;" class="text-center profile-btn">
                                                 Create Your Own Profile
                                          </button>
                                   </form>


                            </div>

                            <div style="text-align: center;margin-top: 28px;">


                            </div>

                     </section>
              </main>
       </body>
</html>