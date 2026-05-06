
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends CI_Model
{

    public function get_products_list(){
        $response = array();
        $this->db->select('*');
        $q = $this->db->get('products');
        $response = $q->result_array();
        return $response;
   }

   public function get_product_info_by_id($pro_id){
        $this->db->select('*');
        $this->db->from('products');
        $this->db->where('id', $pro_id );
        $result = $this->db->get();
        return $result->row_array();
    }

    public function get_order_info_by_id($pro_id){
        $this->db->select('*');
        $this->db->from('orders');
        $this->db->where('id', $pro_id );
        $result = $this->db->get();
        return $result->row_array();
    }

    
    public function get_last_order_id(){
        $query = $this->db->query("SELECT id FROM orders ORDER BY id DESC LIMIT 1");
        $results = $query->result();
        return $results[0]->id;
    }


    public function get_um_id_by_name($um_name){
        $query = $this->db->query("SELECT id FROM um_list WHERE name = '$um_name'");
        $results = $query->result();
        if($results){
            return $results[0]->id;
        }
        else{
            return NULL;
        }
   }
   public function get_um_name_by_id($umid){
        $query = $this->db->query("SELECT name FROM um_list WHERE id = '$umid'");
        $results = $query->result();
        return $results[0]->name;
    }

    public function insert_order($data = [])
	{	 
        return $this->db->insert('orders',$data);
    }

    public function insert_product($data = [])
    {	 
        return $this->db->insert('products',$data);
    }   
 
    public function delete_product($mgid){	 
        $this->db->where('id', $mgid);
        return $this->db->delete('products');
    }
    


}
?>