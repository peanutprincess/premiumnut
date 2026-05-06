    <div class="jumbotron jumbotron-fluid text-center">
       <h1 class="display-4">Welcome to Premium Nut's Order System</h1>
       	<p class="lead">
		   	Place orders with our user friendly Order Management System Just log in below to start. View past orders and place new ones. Update contact and shipping information. Place orders with PO and Notes.
			<br><br>
			All prices are approximate.  Prices subject to change.  Availability may vary.
		</p>
    </div>

<?php 
$logincheck = $this->session->userdata('username');

if(!isset($logincheck)) {?>
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-lg-8">
					<div class="page-header">
				<h1>Our system</h1>
			</div>
					<p>Login to system and manage your premiumnut.com order, create new order, use order history to reorder.</p>
					<p>
					   <?php
						   echo "You are ";
						   if (isset($logincheck)) { echo "logged in"; } else { echo "logged out";} ; 
						   echo ".";
					   ?>
   					</p>
				</div>
				<div class="col-md-12 col-lg-4">
                	<div class="card border-primary h-100">
                        <div class="card-header">
                            <h4 class="card-title text-primary ng-binding" ng-bind="'Font ' + selected.palette.fonts.headings">Log in</h4>
                        </div>
                    	<div class="card-body">
                            <form action="<?php echo site_url('login/auth');?>" method="post">
                            
                               <?php echo $this->session->flashdata('msg');?>
                               <div class="form-group">
                                   <label for="username" class="sr-only">Email:</label>
                                   <input type="email" name="email" class="form-control" placeholder="Email" required autofocus>
								</div>
                            <div class="form-group">
                               <label for="password" class="sr-only">Password:</label>
                               <input type="password" name="password" class="form-control" placeholder="Password" required>
                               </div>
                            <div class="form-group custom-control custom-checkbox">
                                 <input class="custom-control-input" type="checkbox" value="remember-me" checked="">
                                 <label class="custom-control-label" for="customCheck">Remember me</label>
                               </div>
                               <div class="form-group">
                               	<button class="btn btn-primary mt-auto" type="submit">Log in</button>
                              </div>
                             
                             </form>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php } ?>

	</div>

