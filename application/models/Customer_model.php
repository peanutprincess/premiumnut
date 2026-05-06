<?php
class Customer_model extends CI_Model{

    public function get_user_data($user_id){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id', $user_id );
        $result = $this->db->get();
        return $result->result_array();
   }

   public function get_all_orders_by_user($user_id){
        $response = array();
        $this->db->select('*');
        $this->db->where('user_id', $user_id );
        $q = $this->db->get('orders');
        $response = $q->result_array();
        return $response;
    }

    public function update_user_query($u_data){
        $this->db->set('first_name', $u_data['first_name']);
        $this->db->set('last_name', $u_data['last_name']);
        $this->db->set('company', $u_data['company']);
        $this->db->set('address', $u_data['address']);
        $this->db->set('postcode', $u_data['postcode']);
        $this->db->set('city', $u_data['city']);
        $this->db->set('country', $u_data['country']);
        $this->db->set('phone', $u_data['phone']);
        $this->db->set('level', $u_data['level']);

        $this->db->where('id', $u_data['user_id']);
        $this->db->update('users'); 
   }


}