<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller{

	public function __construct() {
		Parent::__construct();
        $this->load->model("admin_model");
		$this->load->model("page_model");
	}

	public function view($page){
		if(!file_exists(APPPATH.'views/admin/'.$page.'.php')){
			show_404();
		}

		$data['users_list'] = $this->admin_model->get_users_wo_admin();
		$data['user_data_q'] = $this->admin_model->get_user_data(1);
		$data['user_data'] = $data['user_data_q'][0];

		$data['orders_list'] = $this->admin_model->get_all_orders();
		$data['products_list'] = $this->admin_model->get_all_products();
		$data['price_level'] = $this->admin_model->get_all_price_level();

		foreach($data['products_list'] as &$product){
			$um_id = $product['u-m'];
			if(is_numeric($um_id)){
				$product['u-m'] = $this->admin_model->get_um_name_by_id($um_id);
			}
		}
		
		$data['product_type'] = $this->admin_model->get_product_type();
		$data['weight_type'] = $this->admin_model->get_weight_list();
		$data['umlist'] = $this->admin_model->get_umlist_list();

		$data['level'] = $this->session->userdata('level');
		
		$data['title'] = ucfirst($page);
		$data['page'] = $page;

		if ($this->session->userdata('logged_in') && $this->session->userdata('level') == 0) {
			$this->load->view('templates/header');
			$this->load->view('admin/'.$page, $data);
			$this->load->view('templates/footer');
		} else {
			redirect('/home');
		}
	}

	public function get_users_all(){
		$data = $this->admin_model->get_users();
		echo json_encode($data);
	}

	public function get_user_edit_info(){
			$pro_id = array_keys($_GET);
			$userdata = $this->admin_model->get_user_data($pro_id[0]);
			$ulevel = $this->session->userdata('level');
			if( $ulevel == '0' ){
				
				$userdataArray = array(
					'id' => $userdata[0]['id'],
					'first_name' => $userdata[0]['first_name'],
					'last_name' => $userdata[0]['last_name'],
					'email' =>  $userdata[0]['email'],
					'company' =>  $userdata[0]['company'],
					'password' =>  $userdata[0]['password'],
					'address' =>  $userdata[0]['address'],
					'postcode' =>  $userdata[0]['postcode'],
					'city' =>  $userdata[0]['city'],
					'country' => $userdata[0]['country'],
					'phone' => $userdata[0]['phone'],
					'level' => $userdata[0]['level'],
				);
				echo json_encode($userdataArray);
			 }
			else{
				die(header("HTTP/1.0 404 Not Found")); 
			}
			exit();
		}

	public function add_user(){
		if ($this->session->userdata('logged_in') && $this->session->userdata('level') == 0) {
			$data_get = $_POST;
			$new_user_data = array(
				'id' => '',
				'first_name' => $data_get['first_name'],
				'last_name' => $data_get['last_name'],
				'email' => $data_get['email'],
				'password' => sha1($data_get['password']),
				'company' => $data_get['company'],
				'address' => $data_get['address'],
				'postcode' => $data_get['postcode'],
				'city' => $data_get['city'],
				'country' => $data_get['country'],
				'phone' => $data_get['phone'],
				'created' => '',
				'modified' => date('Y-m-d H:i:s'),
				'status' => 1,
				'level' => $data_get['level']
			);
			echo json_encode($new_user_data);
			$this->admin_model->add_user_query($new_user_data);
			redirect('/admin/profile#userstab');
		}
		else {
			echo 'Not Authorized!';
			redirect('/home');
		}
	}

	public function update_user(){
		$user_data = $_POST;
		//var_dump($_POST); 
		$getoldpass = $this->admin_model->dbpasscheck($_POST['user_id']);

		if($_POST['password'] != '' 
		&& $_POST['password'] == $_POST['confirm_password']){
			unset($user_data['confirm_password']);
			$user_data['password'] = sha1($_POST['password']);
		}
		else{
			$user_data['password'] = $getoldpass;
		}

		if(isset($_POST['user_id']) && $_POST['user_id'] != '' && $this->session->userdata('level') == '0'){
			$update_data = $this->admin_model->update_user_query( $user_data );
			echo json_encode($update_data);
			redirect('/admin/profile#userstab');
		}
		else{
			die(header("HTTP/1.0 404 Not Found")); 
		}
		exit();
    }

	public function add_product(){
		if ($this->session->userdata('logged_in') && $this->session->userdata('level') == 0) {
			$data_get = $_POST;
			$new_product_data = array(
				'id' => '',
				'item_name' => $data_get['product_name'],
				'master_id' => $data_get['master_id'],
				'description' => $data_get['description'],
				'u-m' => $data_get['u_m'],
				'level1price' => $data_get['price1'],
				'level2price' => $data_get['price2'],
				'product_type' => $data_get['product_type'],
				'weight_type' => $data_get['weight_type'],
				'date_added' => date('Y-m-d H:i:s'),
				'date_modified' => date('Y-m-d H:i:s'),
				'status' => 1
			);
			echo json_encode($new_product_data);
			$this->admin_model->add_product_query($new_product_data);

			$price_level_list = $this->admin_model->get_all_price_level();

			foreach($price_level_list as $price_level){
				$plname = $price_level['name'];
				$plid = $price_level['id'];

				if(isset($data_get['price'.$plid]) && $data_get['price'.$plid] != null){
					$productdata = $this->page_model->get_product_by_master_id_query($data_get['master_id']);
					$new_product_price_level_data = array(
						'id' => '',
						'product_id' => $productdata[0]->id,
						'price_level_id' => $plid,
						'price' => $data_get['price'.$plid],
						'status' => 1
					);
					echo json_encode($new_product_price_level_data);
					$this->admin_model->add_product_price_level_query($new_product_price_level_data);

				}
			}

			redirect('/admin/product-management');
		}
		else {
			echo 'Not Authorized!';
			redirect('/home');
		}
	}

    public function update_product(){
		$product_data = $_POST;
		if(isset($_POST['master_id']) && $_POST['master_id'] != '' && $this->session->userdata('level') == '0'){
			echo json_encode($product_data);
			$update_data = $this->admin_model->update_product_query( $product_data );
			echo json_encode($update_data);
			$price_level_list = $this->admin_model->get_all_price_level();
			foreach( $price_level_list  as $price_level_item){

				$price_level_name = 'price'.$price_level_item['name'];
				$product_id_query = $this->page_model->get_product_id_by_master_id_query($product_data['master_id']);

				$product_price_level_data['id'] = '';
				$product_price_level_data['product_id'] = $product_id_query[0]->id;
				$product_price_level_data['price_level_id']  = $price_level_item['id'];
				$product_price_level_data['price'] = $_POST[$price_level_name];
				$product_price_level_data['status'] = 1;

				//echo '<br><br>$price_level_name:'.$price_level_name;
				//echo '<br>price_level_id:'.$product_price_level_data['price_level_id'];
				//echo '<br>product_id:'.$product_price_level_data['product_id'];
				//echo '<br>$price:'.$product_price_level_data['price'];

				$product_price_level = $this->page_model->get_product_price_level_by_productid_pricelevel($product_price_level_data);
				
				if($product_price_level){
					//echo '<br>found update';
					$update_price_level_data = $this->page_model->update_product_price_level_by_productid($product_price_level_data);
				}
				else{
					//echo '<br>add';
					$add_price_level_data = $this->admin_model->add_product_price_level_query($product_price_level_data);					
				}

				
			}
			redirect('/admin/product-management');
		}
		else{
			die(header("HTTP/1.0 404 Not Found")); 
		}
		exit();
    }

	public function update_price_level(){
		$price_level_data = $_POST;
		if(isset($_POST['id']) && $_POST['id'] != '' && $this->session->userdata('level') == '0'){
			$update_data = $this->admin_model->update_price_level_query( $price_level_data );
			echo json_encode($update_data);
			redirect('/admin/price_level_settings');
		}
		else{
			die(header("HTTP/1.0 404 Not Found")); 
		}
		exit();
    }

	public function get_price_level_edit_info(){
		$pro_id = array_keys($_GET);
        $productdata = $this->admin_model->get_price_level_info_by_id($pro_id[0]);
		$ulevel = $this->session->userdata('level');

		if( $ulevel >= '0' ){
			$productdataArray = array(
				'id' => $productdata['id'],
				'name' => $productdata['name'],
				'sort_order' => $productdata['sort_order'],
				'status' => $productdata['status']
			);
			echo json_encode($productdataArray);
	 	}
		else{
			die(header("HTTP/1.0 404 Not Found")); 
		}
		exit();
    }

	public function delete_product(){
		$product_id = $_POST['delete_product_id'];
		if( $this->session->userdata('level') == '0' ){
            $delete_data = $this->admin_model->delete_product( $product_id );
			$delete_all_price_levels = $this->admin_model->delete_all_price_levels_by_product_id( $product_id );

            echo json_encode($delete_all_price_levels);
			echo json_encode($delete_data);

			redirect('/admin/product-management');
	 	}
		else{
			die(header("HTTP/1.0 404 Not Found")); 
		}
		exit();
    }

	public function add_price_level(){
		if ($this->session->userdata('logged_in') && $this->session->userdata('level') == 0) {
			$data_get = $_POST;
			$new_price_level_data = array(
				'id' => '',
				'name' => $data_get['price_level_name'],
				'sort_order' => $data_get['sort_order'],
				'status' => 1
			);
			echo json_encode($new_price_level_data);
			$this->admin_model->add_price_level_query($new_price_level_data);
			redirect('/admin/price_level_settings');
		}
		else {
			echo 'Not Authorized!';
			redirect('/home');
		}
	}

	public function delete_order(){
		$order_id = $_POST['delete_order_id'];
		if( $this->session->userdata('level') == '0' ){
            $delete_data = $this->admin_model->delete_order( $order_id );
            echo json_encode($delete_data);
			redirect('/admin/order-management');
	 	}
		else{
			die(header("HTTP/1.0 404 Not Found")); 
		}
		exit();
    }

	public function delete_user(){
		$user_id = $_POST['delete_user_id'];
		if( $this->session->userdata('level') == '0' ){
            $delete_data = $this->admin_model->delete_user( $user_id );
            echo json_encode($delete_data);
			redirect('/admin/profile#userstab');
	 	}
		else{
			die(header("HTTP/1.0 404 Not Found")); 
		}
		exit();
    }

	public function delete_price_level(){
		$price_level_id = $_POST['delete_price_level_id'];
		echo 'price_level_id: '.$price_level_id;
		if( $this->session->userdata('level') == '0' ){
            $delete_price_level_data = $this->admin_model->delete_price_level( $price_level_id );
			$delete_product_prices_data = $this->admin_model->delete_all_product_prices_by_price_level_id( $price_level_id );

			echo json_encode($delete_price_level_data);
			echo json_encode($delete_product_prices_data);

			redirect('/admin/price_level_settings');
	 	}
		else{
			die(header("HTTP/1.0 404 Not Found")); 
		}
		exit();
    }


}


?>