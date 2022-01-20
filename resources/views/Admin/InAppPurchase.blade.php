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
                                          <h4 class="page-title">App Purchase List</h4>
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
                                                               <th>Reciept ID</th>
                                                               <th>Name</th>
                                                               <th>Email</th>
                                                               <th>Renewal</th>
                                                        </tr>
                                                 </thead>

                                                 <tbody>

                                                        <?php $i = 1; ?>
                                                        @foreach ($data as $value => $user)
                                                        <tr>
                                                               <td><?= $i++; ?></td>
                                                               <td>#<?= $user['reciept_id']; ?></td>
                                                               <td><?= $user['user_name']; ?></td>
                                                               <td><?= $user['email']; ?></td>
                                                               <td>
                                                                      @if($user->is_auto_renewal == 1)
                                                                      ON
                                                                      @else
                                                                      OFF
                                                                      @endif
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
