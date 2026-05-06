		<div class="container">
			<div class="row">
    <div class="col-md-12">
			
			<div class="row">
				<div class="col-md-12 col-lg-8">
					<div class="page-header">
						<h1>Om GotFarm</h1>
					</div>
					<p>Om GotFarm...</p>
					<p>
					   <?php
						   $test = $this->session->userdata('username');
						   echo "Du är ";
						   if (isset($test)) { echo "inloggad"; } else { echo "utloggad";} ; 
						   echo ".";
					   ?>
   					</p>
				</div>
				<div class="col-md-12 col-lg-4">
                	<div class="card border-primary h-100">
                        <div class="card-header">
                            <h4 class="card-title text-primary ng-binding" ng-bind="'Font ' + selected.palette.fonts.headings">Logga in</h4>
                        </div>
                    	<div class="card-body">
                            <form action="<?php echo site_url('login/auth');?>" method="post">
                            
                               <?php echo $this->session->flashdata('msg');?>
                               <div class="form-group">
                                   <label for="username" class="sr-only">E-post:</label>
                                   <input type="email" name="email" class="form-control" placeholder="Email" required autofocus>
								</div>
                            <div class="form-group">
                               <label for="password" class="sr-only">Lösenord:</label>
                               <input type="password" name="password" class="form-control" placeholder="Password" required>
                               </div>
                            <div class="form-group custom-control custom-checkbox">
                                 <input class="custom-control-input" type="checkbox" value="remember-me" checked="">
                                 <label class="custom-control-label" for="customCheck">Kom ihåg mig</label>
                               </div>
                               <div class="form-group">
                               	<button class="btn btn-primary mt-auto" type="submit">Logga in</button>
                              </div>
                             
                             </form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>