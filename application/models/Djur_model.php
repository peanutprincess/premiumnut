<?php

class Djur_model extends CI_Model
{

     public function get_djur(){
          $getppns = array();
          $newppns = '';

          $userid = $this->session->userdata('uid');
          $this->db->select('ppn_id');
          $this->db->where('ppn_user', $userid );
          $ppnsq = $this->db->get('ppns');
		$getppns = $ppnsq->result_array();
          foreach($getppns as $ppn){
               $newppns .= "'".$ppn['ppn_id']."' ,";
          }
          $newppns = mb_substr($newppns, 0, -1);
          //echo "SELECT * FROM animals WHERE ani_ppn_number IN ($ppns_ids_array) AND ani_owner_ppn = $logged_in_user_id";
          $query = $this->db->query("SELECT * FROM animals WHERE ani_owner_ppn IN ($newppns)");
          return $query;
     }

     public function get_bet_grupp(){
          $getppns = array();
          $newppns = '';
          $userid = $this->session->userdata('uid');
          $this->db->select('ppn_number');
          $this->db->where('ppn_user', $userid );
          $ppnsq = $this->db->get('ppns');
		$getppns = $ppnsq->result_array();
          foreach($getppns as $ppn){
               $newppns .= "'".$ppn['ppn_number']."' ,";
          }
          $newppns = mb_substr($newppns, 0, -1);
          //echo "SELECT * FROM matinggroup WHERE mat_ppn_number IN ($newppns)";
          $query = $this->db->query("SELECT * FROM matinggroup WHERE mat_ppn_number IN ($newppns)");
          return $query;
          //return $this->db->get('matinggroup');
     }

     public function animals_query(){
          $response = array();
		$this->db->select('*');
		$q = $this->db->get('animals');
		$response = $q->result_array();
          return $response;
     }

     public function get_childrens_list($ani_id){
          $query = $this->db->query("SELECT * FROM animals WHERE ani_mother = $ani_id OR ani_father = $ani_id");
          return $query->result_array();
     }


     public function logggedin_ppns_query(){
          $userid = $this->session->userdata('uid');
          $response = array();
          $this->db->select('*');
          $this->db->where('ppn_user', $userid );
		$q = $this->db->get('ppns');
		$response = $q->result_array();
		return $response;
     }

     public function matinggroup_query(){
          $response = array();
		$this->db->select('*');
		$q = $this->db->get('matinggroup');
		$response = $q->result_array();
          return $response;
     }

     public function matinggroup_query_id($matgrp_id){
          $this->db->select('*');
          $this->db->from('matinggroup');
          $this->db->where('mat_group_id', $matgrp_id );
          $result = $this->db->get();
          return $result->result_array();
     }

     public function get_ppn_id($ppnnumber){
          $this->db->select('ppn_id');
          $this->db->from('ppns');
          $this->db->where('ppn_number', $ppnnumber );
          $this->db->limit(1);
          $result = $this->db->get();
          return $result->row_array();
     }

     public function get_last_mat_id(){
          $this->db->select('*');
          $this->db->from('matinggroup');
          $this->db->order_by('mat_group_id', 'DESC');
          $this->db->limit(1);
          $result = $this->db->get();
          return $result->row_array();
     }

     public function get_last_ani_id(){
          $this->db->select('*');
          $this->db->from('animals');
          $this->db->order_by('ani_id', 'DESC');
          $this->db->limit(1);
          $result = $this->db->get();
          return $result->row_array();
     }

     public function get_mothers_list_addform(){
          $getppns = array();
          $newppns = '';
          $userid = $this->session->userdata('uid');
          $this->db->select('ppn_id');
          $this->db->where('ppn_user', $userid );
          $ppnsq = $this->db->get('ppns');
		$getppns = $ppnsq->result_array();
          foreach($getppns as $ppn){
               $newppns .= "'".$ppn['ppn_id']."' ,";
          }
          $newppns = mb_substr($newppns, 0, -1);
          $query = $this->db->query("SELECT * FROM animals WHERE ani_owner_ppn IN ($newppns) AND ani_mat_group IS NULL AND ani_gender = 'T' ");
          return $query->result_array();
     }

     public function get_mothers_list_editform(){
          $getppns = array();
          $newppns = '';
          $userid = $this->session->userdata('uid');
          $this->db->select('ppn_id');
          $this->db->where('ppn_user', $userid );
          $ppnsq = $this->db->get('ppns');
		$getppns = $ppnsq->result_array();
          foreach($getppns as $ppn){
               $newppns .= "'".$ppn['ppn_id']."' ,";
          }
          $newppns = mb_substr($newppns, 0, -1);
          $query = $this->db->query("SELECT * FROM animals WHERE ani_owner_ppn IN ($newppns) AND ani_gender = 'T' ");
          return $query->result_array();
     }

     public function get_fathers_list(){
          $userid = $this->session->userdata('uid');
          $getppns = array();
          $newppns = '';
          $userid = $this->session->userdata('uid');
          $this->db->select('ppn_id');
          $this->db->where('ppn_user', $userid );
          $ppnsq = $this->db->get('ppns');
		$getppns = $ppnsq->result_array();
          foreach($getppns as $ppn){
               $newppns .= "'".$ppn['ppn_id']."' ,";
          }
          $newppns = mb_substr($newppns, 0, -1);
          $query = $this->db->query("SELECT * FROM animals WHERE ani_owner_ppn IN ($newppns) AND ani_gender = 'B'");
          return $query->result_array();
     }

     public function get_animal_by_id($ani_id){
          $this->db->select('*');
          $this->db->from('animals');
          $this->db->where('ani_id', $ani_id );
          $result = $this->db->get();
          return $result->row_array();
     }

     public function get_animal_by_matg($matgrp_id){
          $this->db->select('*');
          $this->db->from('animals');
          $this->db->where('ani_mat_group', $matgrp_id );
          $result = $this->db->get();
          return $result->result_array();
     }

     public function get_djur_details($id_no = NULL){	 
          $this->db->select('*');
          $this->db->from('animals');
          $this->db->where('ani_id', $id_no );
          $query = $this->db->get();
          if ( $query->num_rows() > 0 )
          {
              $row = $query->row_array();
              return $row;
          }
     }


}

?>