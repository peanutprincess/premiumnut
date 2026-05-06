<div class="jumbotron jumbotron-fluid text-center">
       <h1 class="display-4 text-capitalize"><?php echo $page; ?></h1>
</div>

<div class="container">
       <div class="row"> 
              <div class="col-md-12 text-center"> 
                    <h4></h4>
              </div> 
       </div>          
       <div class="row text-center guides-nav">
            <div class="col-3 side_menu">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link active show" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home"
                     role="tab" aria-controls="v-pills-home" aria-selected="false">Home
                    </a>
                    <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile"
                     role="tab" aria-controls="v-pills-profile" aria-selected="false">Profile
                    </a>
                    <a class="nav-link " id="v-pills-users-tab" data-toggle="pill" href="#v-pills-users"
                     role="tab" aria-controls="v-pills-users" aria-selected="true">Users
                    </a>
                    <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" 
                    role="tab" aria-controls="v-pills-settings" aria-selected="false">More
                </a>
                </div>
                </div>
            <div class="col-9 main_contant">
            <div class="tab-content text-left" id="v-pills-tabContent">
                    <div class="tab-pane fade  active show" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                        <h4 class="tab_heading">
                            Welcome <?php echo $user_data['first_name']; ?> <?php echo $user_data['last_name']; ?>
                        </h4>
                        <p>
                            Choose your desired option from right menu.
                        </p>

                        <ul class="user_data_tabul" style="display:none;">
                            <li><?php echo $user_data['first_name']; ?></li>
                            <li><?php echo $user_data['last_name']; ?></li>
                            <li><?php echo $user_data['email']; ?></li>
                            <li><?php echo $user_data['company']; ?></li>
                            <li><?php echo $user_data['address']; ?></li>
                            <li><?php echo $user_data['postcode']; ?></li>
                            <li><?php echo $user_data['city']; ?></li>
                            <li><?php echo $user_data['country']; ?></li>
                            <li><?php echo $user_data['phone']; ?></li>
                            <li><?php echo $user_data['created']; ?></li>
                            <li><?php echo $user_data['level']; ?></li>

                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                    <h4 class="tab_heading">
                        Update Profile
                    </h4>
                    <?php echo form_open('pages/update_profile', array('id' => 'update_profile', 'class' => 'update_profile_controller')); ?>

                    <div class="loadersmall"></div>
                        <ul class="profile_ul">
                            <li>
                                <input type="hidden" name="user_id" value="<?php echo $user_data['id']; ?>">
                                <label>First Name</label>
                                <input type="text" name="first_name" value="<?php echo $user_data['first_name']; ?>" class="form-control">
                            </li>
                            <li>
                                <label>Last Name</label>
                                <input type="text" name="last_name" value="<?php echo $user_data['last_name']; ?>" class="form-control">
                            </li>
                            <li>
                                <label>Email</label>
                                <input type="text" name="email" disabled value="<?php echo $user_data['email']; ?>" class="form-control">
                            </li>
                            <li>
                                <label>Company</label>
                                <input type="text" name="company" value="<?php echo $user_data['company']; ?>" class="form-control">
                            </li>
                            <li>
                                <label>Address</label>
                                <input type="text" name="address" value="<?php echo $user_data['address']; ?>" class="form-control">
                            </li>
                            <li>
                                <label>Postcode</label>
                                <input type="text" name="postcode" value="<?php echo $user_data['postcode']; ?>" class="form-control">
                            </li>
                            <li>
                                <label>City</label>
                                <input type="text" name="city" value="<?php echo $user_data['city']; ?>" class="form-control">
                            </li>
                            <li>
                                <label>Country</label>
                                <input type="text" name="country" value="<?php echo $user_data['country']; ?>" class="form-control">
                            </li>
                            <li>
                                <label>Phone</label>
                                <input type="text" name="phone" value="<?php echo $user_data['phone']; ?>" class="form-control">
                            </li>
                            <li>
                                <label>Level</label>
                                <input type="text" value="<?php echo $user_data['level'];  if($user_data['level'] == 0 ){echo ' (Admin) '; } ; ?>" class="form-control" disabled="disabled">
                            </li>
                            <li>
                                
                            </li>
                            <li class="text-right">
                                <input type="submit" value="Update" id="update_profile_submit" class="btn btn-primary">
                            </li>
                        </ul>
                    <?php echo form_close(); ?>
                    </div>
                    <div class="tab-pane fade " id="v-pills-users" role="tabpanel" aria-labelledby="v-pills-users-tab">
                        <h4 class="tab_heading">
                            User Management
                            <a class="btn btn-primary add_user pull-right" href="#adduser">
                                Add User
                            </a>
                        </h4>
                        <table class="table user_table" width="100%"> 
                            <thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Level</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($users_list as $user){
                                    echo '<tr>';
                                        echo '<td>'.$user['id'].'</td>';
                                        echo '<td>'.$user['first_name'].' '.$user['last_name'].'</td>';
                                        echo '<td>'.$user['email'].'</td>';
                                        echo '<td>'.$user['level'].'</td>';
                                        echo '<td> 
                                        
                                        <a href="#edit_user" title="Edit" user-id="'.$user['id'].'" class="edit edit_user" user-id=""><i class="fa fa-pencil" aria-hidden="true"></i>
                                        </a>
                                        /
                                        <a href="#delete_user" title="Delete" class="delete delete_user" user-id="'.$user['id'].'" ><i class="fa fa-trash" aria-hidden="true"></i>
                                        </a>
                                        
                                        </td>';
                                    echo '<tr>';
                                } ?>
                            </tbody>
                            <tfoot></tfoot>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                        <h4 class="tab_heading">
                            More
                        </h4>
                        <p>
                            Future space for something really cool.
                        </p>
                    </div>
                </div>
            </div>
       </div>
</div>




<!-- Modal -->
<div class="modal fade" id="add_user_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    <?php echo form_open('admin/add_user', array('id' => 'add_user_form', 'class' => 'update_profile_controller')); ?>


      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Add new user </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        
            <ul class="profile_ul">
                <li>
                    <label>First Name</label>
                    <input type="text" name="first_name" value="" class="form-control">
                </li>
                <li>
                    <label>Last Name</label>
                    <input type="text" name="last_name" value="" class="form-control">
                </li>
                <li>
                    <label>Email*</label>
                    <input type="text" name="email" value="" class="form-control" required>
                </li>
                <li>
                    <label>Password*</label>
                    <input type="password" name="password" value="" class="form-control" required>
                </li>
                <li>
                    <label>Confirm Password*</label>
                    <input type="password" name="confirm_password" value="" class="form-control" required>
                </li>
                <li>
                    <label>Company</label>
                    <input type="text" name="company" value="" class="form-control">
                </li>
                <li>
                    <label>Address</label>
                    <input type="text" name="address" value="" class="form-control">
                </li>
                <li>
                    <label>Postcode</label>
                    <input type="text" name="postcode" value="" class="form-control">
                </li>
                <li>
                    <label>City</label>
                    <input type="text" name="city" value="" class="form-control">
                </li>
                <li>
                    <label>Country</label>
                    <input type="text" name="country" value="" class="form-control">
                </li>
                <li>
                    <label>Phone</label>
                    <input type="text" name="phone" value="" class="form-control">
                </li>
                <li class="level_list">
                    <label>Level*</label>
                    <select name="level" class="form-control" required>
                        <option value="">Select</option>

                        <?php foreach($price_level as $price_level_item){ ?>

                        <option value="<?php echo $price_level_item['id']; ?>"><?php echo $price_level_item['name']; ?></option>
                        
                        <?php } ?>

                    </select>
                </li>
            </ul>
      </div>
      <div class="modal-footer">
        <input type="submit" value="Add User" id="add_user_submit" class="btn btn-primary">

        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>

      <?php echo form_close(); ?>

    </div>
  </div>
</div>

<div class="modal fade" id="update_user_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
  <?php echo form_open('admin/update_user', array('id' => 'update_user_form', 'class' => 'update_user_controller')); ?>

    <div class="modal-content">


      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Update User - <span id="user_first_name"></span> </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <input type="submit" value="Update User" id="update_user_submit" class="btn btn-primary">

        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>

    <?php echo form_close(); ?>

  </div>
</div>



<!-- Delete Modal -->
<div class="modal fade" id="delete_user_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">

  <?php echo form_open('admin/delete_user', array('id' => 'delete_user_form', 'class' => 'delete_user_controller')); ?>

    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Delete User # <span id="user_data_id"></span></h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this User ?</p>
        <input type="hidden" name="delete_user_id" id="delete_user_id" value="">
      </div>
      <div class="modal-footer">
        <input type="submit" value="Delete User" id="Delete_user_submit" class="btn btn-primary">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>

    <?php echo form_close(); ?>

  </div>
</div>
