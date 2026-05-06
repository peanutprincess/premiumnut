<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lamning extends CI_Controller{
	public function __construct() {
		Parent::__construct();
            $this->load->model("djur_model");
            $this->load->model("page_model");

    }
    
    public function view($page = NULL){
      if ($this->session->userdata('logged_in')) {

            $data['mating_group_list'] = $this->djur_model->matinggroup_query();
            $data['mothers_list_editform'] = $this->djur_model->get_mothers_list_editform();
            $data['mothers_list_addform'] = $this->djur_model->get_mothers_list_addform();

            $data['last_ani_id'] = $this->djur_model->get_last_ani_id();

            $data['username'] = $this->session->userdata('username');
            $data['userid'] = $this->session->userdata('uid');

            if($page == 'registrera_lamning'){
                  $getmid = $this->input->get("mid");
                  $data['motherdetails'] = $this->djur_model->get_animal_by_id($getmid);
            }

            if($page == 'bet_grupp'){
                  $data['ramlist'] = $this->djur_model->get_fathers_list();
                  $data['ppnslist'] = $this->djur_model->logggedin_ppns_query();
            }

            

            if($this->input->get("mid") == NULL && $page == 'registrera_lamning'){
                  redirect('djur/mina_djur');
            }
            else{
                  $this->load->view('templates/header_datatables');
                  $this->load->view('lamning/'.$page.'',$data);
                  $this->load->view('templates/footer_datatables');
            }


      } else {
            redirect('/hem');
      }

    }



}


?>