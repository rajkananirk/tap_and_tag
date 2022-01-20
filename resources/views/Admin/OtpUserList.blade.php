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
                                          <h4 class="page-title">Otp List</h4>
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


                                          <table id="clients" class="table table-striped table-bordered">
                                                 <thead>
                                                        <tr>
                                                               <th>S.No.</th>
                                                               <th>Username</th>
                                                               <th>Email</th>
                                                               <th>Date</th>
                                                               <th>Otp</th>
                                                               <th>Action</th>
                                                        </tr>
                                                 </thead>

                                                 <tbody>

                                                        <?php $i = 1; ?>
                                                        @foreach ($tbl_user_otp as $value => $user)

                                                        <tr>
                                                               <td><?= $i++; ?></td>
                                                               <td>
                                                                      {{$user->username}}
                                                               </td>
                                                               <td>
                                                                      {{$user->email}}

                                                               </td>
                                                               <td>
                                                                      {{$user->created_at}}

                                                               </td>
                                                               <td>
                                                                      {{$user->temp_pass}}


                                                               </td>
                                                               <td>
                                                                      <span><a href="<?php
                                                                             $user_id = $user->otp_id;
                                                                             echo url("/update-otp") . "/$user_id";
                                                                             ?>"class="btn btn-info">Update</a>
                                                                      </span>


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
