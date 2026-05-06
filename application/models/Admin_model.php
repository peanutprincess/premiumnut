<?php
class Admin_model extends CI_Model{

     public function get_users(){
          $response = array();
          $this->db->select('*');
          $q = $this->db->get('users');
          $response = $q->result_array();
          return $response;
     }

     public function get_users_wo_admin(){
          $response = array();
          $this->db->select('*');
          $this->db->from('users');
          $this->db->where('id !=', 1 );
          $result = $this->db->get();
          return $result->result_array();
     }

     public function get_user_data($user_id){
          $this->db->select('*');
          $this->db->from('users');
          $this->db->where('id', $user_id );
          $result = $this->db->get();
          return $result->result_array();
     }

     public function get_all_orders(){
          $response = array();
          $this->db->select('*');
          $q = $this->db->get('orders');
          $response = $q->result_array();
          return $response;
     }
     
     public function get_single_order($order_id){
          $response = array();
          $q = $this->db->select('*');
          $q = $this->db->where('id', $order_id );
          $q = $this->db->get('orders');
          $response = $q->result_array();
          return $response;
     }

     public function get_price_level_info_by_id($prl_id){
          $this->db->select('*');
          $this->db->from('price_level');
          $this->db->where('id', $prl_id );
          $result = $this->db->get();
          return $result->row_array();
     }

     public function get_all_products(){
          $response = array();
          $this->db->select('*');
          $q = $this->db->get('products');
          $response = $q->result_array();
          return $response;
     }

     public function get_all_price_level(){
          $response = array();
          $this->db->select('*');
          $q = $this->db->get('price_level');
          $response = $q->result_array();
          return $response;
     }

     public function get_product_type(){
          $response = array();
          $this->db->select('*');
          $q = $this->db->get('product_type');
          $response = $q->result_array();
          return $response;
     }

     public function get_weight_list(){
          $response = array();
          $this->db->select('*');
          $q = $this->db->get('weight_type');
          $response = $q->result_array();
          return $response;
     }

     public function get_umlist_list(){
          $response = array();
          $this->db->select('*');
          $q = $this->db->get('um_list');
          $response = $q->result_array();
          return $response;
     }

     public function get_um_name_by_id($umid){
          $query = $this->db->query("SELECT name FROM um_list WHERE id = '$umid'");
          $results = $query->result();
          return $results[0]->name;
     }

     public function add_user_query($data = []){
          return $this->db->insert('users',$data);
     }

     public function add_product_query($data = []){
          return $this->db->insert('products',$data);
     }

     public function add_product_price_level_query($data = []){
          return $this->db->insert('product_price_level',$data);
     }

     public function add_price_level_query($data = []){
          return $this->db->insert('price_level',$data);
     }

     public function update_product_query($u_data){
          $this->db->set('item_name', $u_data['product_name']);
          $this->db->set('description', $u_data['description']);
          $this->db->set('u-m', $u_data['u_m']);
          $this->db->set('level1price', $u_data['price1']);
          $this->db->set('level2price', $u_data['price2']);
          $this->db->set('product_type', $u_data['product_type']);
          $this->db->set('weight_type', $u_data['weight_type']);
          $this->db->set('date_modified', time());
          $this->db->where('master_id', $u_data['master_id']);
          $this->db->update('products'); 
     }

     public function update_price_level_query($u_data){
          $this->db->set('name', $u_data['price_level_name']);
          $this->db->set('sort_order', $u_data['sort_order']);
          $this->db->where('id', $u_data['id']);
          $this->db->update('price_level'); 
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
          if($u_data['password'] != ''){
               $this->db->set('password', $u_data['password']);
          }

          $this->db->where('id', $u_data['user_id']);
          $this->db->update('users'); 
     }

     public function delete_product($product_id){
          $this->db->where('id', $product_id);
          $this->db->delete('products');
     }

     public function delete_all_price_levels_by_product_id($product_id){
          $this->db->where('product_id', $product_id);
          $this->db->delete('product_price_level');
     }
     
     public function delete_price_level($price_level_id){
          $this->db->where('id', $price_level_id);
          $this->db->delete('price_level');
     }

     public function delete_all_product_prices_by_price_level_id($price_level_id){
          $this->db->where('price_level_id', $price_level_id);
          $this->db->delete('product_price_level');
     }
     
     public function delete_user($user_id){
          $this->db->where('id', $user_id);
          $this->db->delete('users');
     }

     public function delete_order($order_id){
          $this->db->where('id', $order_id);
          $this->db->delete('orders');
     }

     public function dbpasscheck($user_id){
          $query = $this->db->query("SELECT password FROM users WHERE id = '$user_id'");
          $results = $query->result();
          return $results[0]->password;
     } 

}
?>