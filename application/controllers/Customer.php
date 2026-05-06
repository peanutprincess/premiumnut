<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer extends CI_Controller{

	public function __construct() {
		Parent::__construct();
        $this->load->model("customer_model");
	}

	public function view($page){
		if(!file_exists(APPPATH.'views/customer/'.$page.'.php')){
			show_404();
		}
		$data['id'] = $this->session->userdata('uid');
		$data['level'] = $this->session->userdata('level');

		$data['user_data_q'] = $this->customer_model->get_user_data($data['id']);
		$data['user_data'] = $data['user_data_q'][0];

        $data['user_order_list'] = $this->customer_model->get_all_orders_by_user($data['id']);
    
		
		//$data['product_type'] = $this->customer_model->get_product_type();
		//$data['weight_type'] = $this->customer_model->get_weight_list();
		//$data['umlist'] = $this->customer_model->get_umlist_list();

		
		$data['title'] = ucfirst($page);
		$data['page'] = $page;

		if ($this->session->userdata('logged_in') && $data['level'] >= 0) {
			$this->load->view('templates/header');
			$this->load->view('customer/'.$page, $data);
			$this->load->view('templates/footer');
		} else {
			redirect('/home');
		}
	}
}

?>