
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page_model extends CI_Model
{

    public function update_profile_info($u_data){
        $this->db->set('first_name', $u_data['first_name']);
        $this->db->set('last_name', $u_data['last_name']);
        $this->db->set('company', $u_data['company']);
        $this->db->set('address', $u_data['address']);
        if($u_data['password'] != '' && $u_data['confirm_password'] != '' && $u_data['password'] == $u_data['confirm_password']){
            //echo '<br>password: ' . $u_data['password'];
            //echo '<br>confirm_password: ' . $u_data['confirm_password'];
            $this->db->set('password', sha1($u_data['password']));
        }
        $this->db->set('postcode', $u_data['postcode']);
        $this->db->set('city', $u_data['city']);
        $this->db->set('country', $u_data['country']);
        $this->db->set('phone', $u_data['phone']);

        $this->db->where('id', $this->session->userdata('uid'));
        $this->db->update('users'); 
    }

    public function get_session_user_data($user_id){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id', $user_id );
        $result = $this->db->get();
        return $result->result_array();
    }

    public function get_products_guide($weight_type_id){
        $response = array();
        $this->db->select('*');
        $this->db->like('weight_type', $weight_type_id);
        $q = $this->db->get('products');
        $response = $q->result_array();
        return $response;
   }

    public function get_product_by_master_id_query($master_id){
        $query=$this->db->query("SELECT * FROM products WHERE master_id = '$master_id'");
	    return $query->result();
    }

    public function get_product_by_description_query($description){
        $description = str_replace("'","","description");
        $description = str_replace('"','','description');

        $query=$this->db->query("SELECT * FROM products WHERE description LIKE '%$description%' ");
	    return $query->result();
    }

    public function get_product_id_by_master_id_query($master_id){
        $query=$this->db->query("SELECT id FROM products WHERE master_id = '$master_id'");
	    return $query->result();
    }

    public function get_product_by_product_id($product_id){
        $query=$this->db->query("SELECT * FROM products WHERE id = '$product_id'");
	    return $query->result();
    }

    public function get_weight_type_by_name($weight_type){
        $query = $this->db->query("SELECT * FROM weight_type WHERE name = '$weight_type'");
	    $results = $query->result();
        return $results[0]->id;
    }

    public function get_product_type_by_name($product_type){
        $query=$this->db->query("SELECT * FROM product_type WHERE name = '$product_type'");
        $results = $query->result();
        return $results[0]->id;
    }

    public function get_weight_name_by_id($weight_type_id){
        $query = $this->db->query("SELECT * FROM weight_type WHERE id = '$weight_type_id'");
	    $results = $query->result();
        return $results[0]->name;
    }

    public function get_product_description_by_id($id){
        $query = $this->db->query("SELECT * FROM products WHERE id = '$id'");
	    $results = $query->result();
        if(isset($results[0]->description)){
            return $results[0]->description;
        }
        else{
            return null;
        }
    }

    public function get_product_masterid_by_id($id){
        $query = $this->db->query("SELECT * FROM products WHERE id = '$id'");
	    $results = $query->result();
        if(isset($results[0]->master_id)){
            return $results[0]->master_id;
        }
        else{
            return null;
        }
    }

    public function get_product_price_level_by_productid_pricelevel($product_price_level_data){
        $productid = $product_price_level_data['product_id'];
        $pricelevel = $product_price_level_data['price_level_id'];
        $query=$this->db->query("SELECT * FROM product_price_level WHERE product_id = '$productid' AND price_level_id = '$pricelevel' ");
	    return $query->result();
    }

    public function update_product_price_level_by_productid($product_price_level_data){
        $this->db->set('price', $product_price_level_data['price']);
        $this->db->where('product_id', $product_price_level_data['product_id']);
        $this->db->where('price_level_id', $product_price_level_data['price_level_id']);
        $this->db->update('product_price_level'); 
    }
    
    public function update_product_prices($pro_data){
        $this->db->set('level1price', $pro_data['price1']);
        $this->db->set('level2price', $pro_data['price2']);
        $this->db->where('master_id', $pro_data['master_id']);
        $this->db->update('products'); 
    }
    
    public function update_product_by_master_id_query($item_master_id,$weight_type,$product_type){
        $this->db->set('weight_type', $weight_type);
        $this->db->set('product_type', $product_type);
        $this->db->where('master_id', $item_master_id);
        $this->db->update('products'); 
    }

}
?>