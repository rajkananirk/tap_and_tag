@include('Admin.header')

@include('Admin.sidebar')


<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="content-page">
       <!-- Start content -->
       <div class="content">
              <div class="container">


                     <div class="row">
                            <div class="col-xs-12">
                                   <div class="page-title-box">
                                          <h4 class="page-title">Social Link List</h4>
                                          <div class="pull-right">
                                              <!--<a  class="btn btn-success pull-right" data-target="#_add" data-toggle="modal"><i class=" mdi mdi-playlist-plus"></i>&nbsp;Add Category</a>-->
                                          </div>
                                          <div class="clearfix"></div>
                                   </div>
                            </div>
                     </div>
                     <div class="row">
                            <div class="col-sm-12">
                                   <div class="card-box table-responsive">

                                          @if ($message = Session::get('success'))
                                          <div class="alert alert-success alert-block">
                                                 <button type="button" class="close" data-dismiss="alert">×</button>
                                                 <strong>{{ $message }}</strong>
                                          </div>
                                          @endif

                                          <table id="clients" class="table table-striped table-bordered">
                                                 <thead>
                                                        <tr>
                                                               <th>S.No.</th>
                                                               <th>Name</th>
                                                               <th>Icon</th>
                                                               <th>Link</th>
                                                               <th>Action</th>

                                                        </tr>
                                                 </thead>

                                                 <tbody>

                                                        <?php $i = 1; ?>
                                                        @foreach ($data as $value => $user)

                                                        @if($user->custom_link)
                                                        <tr>
                                                               <td><?= $i++; ?></td>
                                                               <td><?= $user['c_social_platform_name']; ?></td>
                                                               <td>
                                                                      <?php if ($user['c_social_platform_icon'] !== NULL) { ?>
                                                                             <img src="http://tapandtag.me/tapandtag/<?= $user['c_social_platform_icon']; ?>" class="img-thumbnail" alt="No Image" width="65" height="65">
                                                                      <?php } else { ?>
                                                                             <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTKfHVThC6NDvAo7W_aBedFmduYaNv6oXl-5T0lykgFHRoznpF85SfTb5c17nw9LqJVY94&usqp=CAU" class="img-thumbnail" alt="No Image" width="65" height="65">
                                                                      <?php } ?>
                                                               </td>
                                                               <td>
                                                                      <a href="<?= $user['open_link']; ?>" target="_blank"><img style="padding: 13px" src="{{asset('public/images/send.png')}}" class="img-thumbnail" alt="No Image" width="65" height="65"></a>
                                                               </td>
                                                               <td>
                                                                      <?php if ($user['is_link_blocked'] == 0) { ?>

                                                                             <span><a href="<?php
                                                                                    $user_id = $user['link_id'];
                                                                                    echo url("/block-link") . "/$user_id";
                                                                                    ?>"class="btn btn-info">Block</a>
                                                                             </span>

                                                                      <?php } else { ?>

                                                                             <span><a href="<?php
                                                                                    $user_id = $user['link_id'];
                                                                                    echo url("/unblock-link") . "/$user_id";
                                                                                    ?>" style="color:#000000" class="btn btn-warning">UnBlock</a>
                                                                             </span>

                                                                      <?php } ?>
                                                               </td>
                                                        </tr>
                                                        @else
                                                        <tr>
                                                               <td><?= $i++; ?></td>
                                                               <td><?= $user['social_platform_name']; ?></td>
                                                               <td>
                                                                      <img src="http://tapandtag.me/tapandtag/<?= $user['social_platform_icon']; ?>" class="img-thumbnail" alt="No Image" width="65" height="65">
                                                               </td>
                                                               <td>
                                                                      <a href="<?= $user['open_link']; ?>" target="_blank"><img style="padding: 13px" src="{{asset('public/images/send.png')}}" class="img-thumbnail" alt="No Image" width="65" height="65"></a>
                                                               </td>

                                                               <td>
                                                                      <?php if ($user['is_link_blocked'] == 0) { ?>

                                                                             <span><a href="<?php
                                                                                    $user_id = $user['link_id'];
                                                                                    echo url("/block-link") . "/$user_id";
                                                                                    ?>"class="btn btn-info">Block</a>
                                                                             </span>

                                                                      <?php } else { ?>

                                                                             <span><a href="<?php
                                                                                    $user_id = $user['link_id'];
                                                                                    echo url("/unblock-link") . "/$user_id";
                                                                                    ?>" style="color:#000000" class="btn btn-warning">UnBlock</a>
                                                                             </span>

                                                                      <?php } ?>
                                                               </td>
                                                        </tr>
                                                        @endif
                                                        @endforeach

                                                 </tbody>
                                          </table>
                                   </div>
                            </div>
                     </div>

              </div> <!-- container -->
       </div>
</div> <!-- content -->

@include('Admin.footer')
