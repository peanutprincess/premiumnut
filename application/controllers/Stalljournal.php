<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stalljournal extends CI_Controller{
	public function __construct() {
		Parent::__construct();
		$this->load->model("djur_model");
    }
    
    public function index(){

		        
		if ($this->session->userdata('logged_in')) {
			$this->load->view('templates/header_datatables');
			$this->load->view('pages/stalljournal');
			$this->load->view('templates/footer_datatables');
		} else {
            redirect('/hem');
		}
    }

}


?>