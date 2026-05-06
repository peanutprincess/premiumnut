<?php
  $username = $this->session->userdata('username');


?>
<html>
	<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Premium Nut Orders</title>

        <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap4.min.css" rel="stylesheet">

        <link href="<?php echo $this->config->base_url(); ?>/assets/css/theme.css" rel="stylesheet">
        <link href="<?php echo $this->config->base_url(); ?>/assets/css/style.css" rel="stylesheet">
        <link href="<?php echo $this->config->base_url(); ?>/assets/css/multiselect.css" rel="stylesheet">
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
        <link rel="shortcut icon" type="image/x-icon" href="https://images.squarespace-cdn.com/content/v1/56968a5740667a086de661b9/1516838192084-C3RMIGKXDUXLT18JD73U/favicon.ico">

</head>

	<body>
        <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
			<div class="d-flex flex-nowrap">
				  <a class="navbar-brand" href="<?php echo base_url(); ?>home">
				    <img src="https://order.premiumnut.com/assets/images/PremiumNutHeader2018.png" class="img-fluid" alt="GotFarm" style="max-height:70px;">
      		</a>
          <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
       			<span class="navbar-toggler-icon"></span>
         	</button>
			</div>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav">
                  <?php if(isset($username)) {?>

                    <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>order">Order Form</a></li>
                  
                  <?php } ?>

                  <!--ACCESS MENUS FOR ADMIN-->
                  <?php if($this->session->userdata('level')==='0'):?>
                    <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>admin/order-management">Orders</a></li>
                    <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle" href="<?php echo base_url(); ?>admin/product-management" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Products
                      </a>
                      <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?php echo base_url(); ?>admin/product-management">Product Management</a>
                        <a class="dropdown-item" href="<?php echo base_url(); ?>admin/products_bulk_price_update">Bulk Price Update</a>
                        <a class="dropdown-item" href="<?php echo base_url(); ?>admin/price_level_settings">Price Level Settings</a>
                      </div>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>admin/profile">Profile</a></li>

                    <!--
                    <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>djur/mina_djur">Mina djur</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>lamning">Lamning</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>stalljournal">Stalljournal</a></li>
                    -->
                  <!--ACCESS MENUS FOR CUSTOMERS-->
                  <?php elseif($this->session->userdata('level')>='1'):?>
                    <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>customer/myorders">My Orders</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>customer/profile">Profile</a></li>

                    <!--
                    <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>djur/mina_djur">Mina djur</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>lamning">Lamning</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>stalljournal">Stalljournal</a></li>
                    -->
                  <!--ACCESS MENUS FOR ???-->
                  <?php else:?>
                    
                  <?php endif;?>
                </ul>
                <?php if (isset($username)): ?>
                  <ul class="navbar-nav ml-md-auto">
                    <?php 
                    if(isset($_SESSION['cart_data'])){
                      $cart_count = '('.count($_SESSION['cart_data']).')';
                    }
                    else{
                      $cart_count = '';
                    }
                    

                    ?>
                    <li class="nav-item"><a class="nav-link" id="open_cart_menu" href="#">Cart <?php echo $cart_count; ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo site_url('page/logout');?>">Log out</a></li>
                  </ul>
                <?php endif; ?>
              </div>
        </nav>


