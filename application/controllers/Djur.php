<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Djur extends CI_Controller{

	public function __construct() {
		Parent::__construct();
        $this->load->model("djur_model");
	}

	public function view($page){
		if(!file_exists(APPPATH.'views/djur/'.$page.'.php')){
			show_404();
		}

		$djur = $this->djur_model->get_djur();                               

		$data['title'] = ucfirst($page);
		$data['djur'] = $djur->result();
		$data['page'] = $page;

		if($page == 'djur_detaljer'){

			if($this->input->get("id") != NULL){
				$id = $this->input->get("id");
				$data['djurdetail'] = $this->djur_model->get_djur_details($id);

				//Children
				$data['childrenlist'] = $this->djur_model->get_childrens_list($id);
				
				//Parents
				$data['motherdetail'] = $this->djur_model->get_animal_by_id($data['djurdetail']['ani_mother']);
				$data['fatherdetail'] = $this->djur_model->get_animal_by_id($data['djurdetail']['ani_father']);
				
				//Grand Parents

				$data['mothergmdetail'] = $this->djur_model->get_animal_by_id($data['motherdetail']['ani_mother']);
				$data['mothergfdetail'] = $this->djur_model->get_animal_by_id($data['motherdetail']['ani_father']);

				$data['fathergmdetail'] = $this->djur_model->get_animal_by_id($data['fatherdetail']['ani_mother']);
				$data['fathergfdetail'] = $this->djur_model->get_animal_by_id($data['fatherdetail']['ani_father']);

				//Great Grand Mother

				$data['m_gm_ggm_detail'] = $this->djur_model->get_animal_by_id($data['mothergmdetail']['ani_mother']);
				$data['m_gm_ggf_detail'] = $this->djur_model->get_animal_by_id($data['mothergmdetail']['ani_father']);

				$data['m_gf_ggm_detail'] = $this->djur_model->get_animal_by_id($data['fathergmdetail']['ani_mother']);
				$data['m_gf_ggf_detail'] = $this->djur_model->get_animal_by_id($data['fathergmdetail']['ani_father']);

				//Great Grand Father

				$data['f_gm_ggm_detail'] = $this->djur_model->get_animal_by_id($data['fathergmdetail']['ani_mother']);
				$data['f_gm_ggf_detail'] = $this->djur_model->get_animal_by_id($data['fathergmdetail']['ani_father']);

				$data['f_gf_ggm_detail'] = $this->djur_model->get_animal_by_id($data['fathergfdetail']['ani_mother']);
				$data['f_gf_ggf_detail'] = $this->djur_model->get_animal_by_id($data['fathergfdetail']['ani_father']);



				if($data['djurdetail'] == NULL){
					//redirect('djur/mina_djur'); // if id not found in database
				}
			}
			else{
				//redirect('djur/mina_djur'); // if id not found in url
			}

		}
		
		if ($this->session->userdata('logged_in')) {
			$this->load->view('templates/header_datatables');
			$this->load->view('djur/'.$page, $data);
			$this->load->view('templates/footer_datatables');
		} else {
			redirect('/home');
		}
	}

	public function get_animal_all(){
		$data = $this->djur_model->animals_query();
		echo json_encode($data);
	}

	public function get_mating_all(){
		$data = $this->djur_model->matinggroup_query();
		echo json_encode($data);
	}




}


?>