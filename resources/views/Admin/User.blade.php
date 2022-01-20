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
                                          <h4 class="page-title">User List</h4>
                                          <div class="pull-right">
                                              <!--<a  class="btn btn-success pull-right" data-target="#_add" data-toggle="modal"><i class=" mdi mdi-playlist-plus"></i>&nbsp;Add Category</a>-->
                                          </div>
                                          <div class="clearfix"></div>
                                   </div>
                            </div>
                     </div>
                     <!-- end row -->


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
                                                               <th>Email</th>
                                                               <th>location</th>
                                                               <th>phone_no</th>
                                                               <th>about</th>
                                                               <th>is_active</th>
                                                               <th>profile_pic</th>
                                                               <th>Action</th>

                                                        </tr>
                                                 </thead>

                                                 <tbody>

                                                        <?php $i = 1; ?>
                                                        @foreach ($data as $value => $user)
                                                        <tr>
                                                               <td><?= $i++; ?></td>
                                                               <td><?= $user['username']; ?></td>
                                                               <td><?= $user['email']; ?></td>
                                                               <td><?= $user['location']; ?></td>
                                                               <td><?= $user['phone_number']; ?></td>
                                                               <td><?php echo mb_strimwidth($user['user_bio'], 0, 30, "..."); ?></td>
                                                               <td>
                                                                      @if($user->is_business_profile == 1)
                                                                      True
                                                                      @else
                                                                      False
                                                                      @endif
                                                               </td>

                                                               <td>
                                                                      <?php if ($user['profile_pic'] !== NULL) { ?>
                                                                             <img src="http://tapandtag.me/tapandtag/<?= $user['profile_pic']; ?>" class="img-thumbnail" alt="No Image" width="65" height="65">
                                                                      <?php } else { ?>
                                                                             <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTKfHVThC6NDvAo7W_aBedFmduYaNv6oXl-5T0lykgFHRoznpF85SfTb5c17nw9LqJVY94&usqp=CAU" class="img-thumbnail" alt="No Image" width="65" height="65">
                                                                      <?php } ?>
                                                               </td>

                                                               <td>
                                                                      <span><a href="<?php
                                                                             $user_id = $user['id'];
                                                                             echo url("/social-link") . "/$user_id";
                                                                             ?>" style="color:#000000" class="btn btn-brown">Social Link</a>
                                                                      </span>

                                                                      <?php if ($user['is_blocked'] == 0) { ?>

                                                                             <span><a href="<?php
                                                                                    $user_id = $user['id'];
                                                                                    echo url("/Block_user") . "/$user_id";
                                                                                    ?>"class="btn btn-info">Block</a>
                                                                             </span>

                                                                      <?php } else { ?>

                                                                             <span><a href="<?php
                                                                                    $user_id = $user['id'];
                                                                                    echo url("/UnBlock_user") . "/$user_id";
                                                                                    ?>" style="color:#000000" class="btn btn-warning">UnBlock</a>
                                                                             </span>

                                                                      <?php } ?>
                                                               </td>
                                                        </tr>
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
