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
                                <label>Password</label>
                                <input type="password" name="password" value="" class="form-control">
                                <small>Type new password to change old password or leave it blank</small>
                            </li>
                            <li>
                                <label>Confirm Password</label>
                                <input type="password" name="confirm_password" value="" class="form-control">
                                <small>Confirm new password or leave it blank</small>
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
                        </ul>
                        <div class="btn-container">
                            <input type="submit" value="Update" id="update_profile_submit" class="btn btn-primary">
                        </div>
                    <?php echo form_close(); ?>
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
