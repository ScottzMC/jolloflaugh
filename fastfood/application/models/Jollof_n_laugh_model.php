<?php 

    class Jollof_n_laugh_model extends CI_Model {
        
        // Home
      
      public function display_meal_for_rice(){
          $this->db->where('category', 'Rice');
          $this->db->order_by('created_date', 'DESC');
          $query = $this->db->get('food')->result();
          return $query;
      }
      
      public function display_meal_for_stew(){
          $this->db->where('category', 'Stew');
          $this->db->order_by('created_date', 'DESC');
          $query = $this->db->get('food')->result();
          return $query;
      }
      
      public function display_meal_for_vegan(){
          $this->db->where('category', 'Vegan');
          $this->db->order_by('created_date', 'DESC');
          $query = $this->db->get('food')->result();
          return $query;
      }
      
      public function display_meal_for_side(){
          $this->db->where('category', 'Side');
          $this->db->order_by('created_date', 'DESC');
          $query = $this->db->get('food')->result();
          return $query;
      }
      
      public function display_meal_for_dessert(){
          $this->db->where('category', 'Dessert');
          $this->db->order_by('created_date', 'DESC');
          $query = $this->db->get('food')->result();
          return $query;
      }
      
      public function display_menu_options(){
          $this->db->order_by('category', 'ASC');
          $query = $this->db->get('menu')->result();
          return $query;
      }
      
      public function display_slider_by_home($category){
          $this->db->where('category', $category);
          $query = $this->db->get('slider')->result();
          return $query;
      }
      
      // End of Home
      
      // Account 
      
      public function create_user($data){
        $escape_data = $this->db->escape_str($data);
        $query = $this->db->insert('users', $escape_data);
        return $query;
      }
        
      public function validate($email, $password){
    	$escape_email = $this->db->escape_str($email);
        $escape_password = $this->db->escape_str($password);

	  	$this->db->where('email', $escape_email);
    	$query = $this->db->get('users');

    	if($query->num_rows() > 0){
          	$result = $query->row_array();
          	if($this->bcrypt->check_password($escape_password, $result['password'])){
    		    return $query->result();
          	}else{
        		return array();
          	}
	    }else{
        	return NULL;
    	}
  	   }
      	
      public function update_user_password($email, $password){
         $query = $this->db->query("UPDATE users SET password = '$password' WHERE email = '$email' ");
         return $query;
      }
      
      public function activate_user($code){
          $query = $this->db->query("UPDATE users SET status = 'Activated' WHERE code = '$code' ");
          return $query;
      }
      
      // End of Account 
      
      // Shopping
      
      public function update_cart($rowid, $qty, $price, $amount) {
 		   $data = array(
			   'rowid'   => $rowid,
			   'qty'     => $qty,
			   'price'   => $price,
			   'amount'  => $amount
		 );

		  $this->cart->update($data);
	    }
      
      public function display_my_account($email){
    	$this->db->where('email', $email);
    	$query = $this->db->get('users')->result();
    	return $query;
      }
    	
      public function display_my_order_items($email){
    	$this->db->order_by('created_date', 'DESC');
    	$this->db->where('email', $email);
    	$query = $this->db->get('order_items')->result();
    	return $query;
      }
      
      public function insert_order_items($data){
	    $query = $this->db->insert('order_items', $data);
    	  return $query;
      }
    	
      public function insert_order_details($data){
    	 $query = $this->db->insert('order_details', $data);
    	 return $query;
      }
      
      public function display_all_order_by_code($code){
          $this->db->where('order_id', $code);
          return $this->db->get('order_items')->result();
      }
    	
      public function cancel_order($id){
    	 $query = $this->db->query("UPDATE order_items SET status = 'Cancelled' WHERE id = '$id' ");
    	 return $query;
      }
    	
      public function delete_order($id){
        $query = $this->db->query("DELETE FROM order_items WHERE id = '$id' ");
      }
      
      public function update_my_details($data){
    	$query = $this->db->update('users', $data);
    	return $query;
      }
      
      public function display_messages($email){
        $this->db->where('email', $email);
        $query = $this->db->get('message')->result();
        return $query;
      }
      
      public function delete_message($id){
        $query = $this->db->query("DELETE FROM message WHERE id = '$id' ");
      }
      
      public function insert_message($data){
        $query = $this->db->insert('message', $data);
        return $query;
      }
      
      // End of Shopping
      
      // Food 
      
      public function record_food_category_count($category){
          $this->db->from('food');
          $this->db->where('category', $category);
          $query = $this->db->count_all_results();
          return $query;
        }

      public function fetch_food_category_data($limit, $start, $category){
         $this->db->limit($limit, $start);
         $this->db->order_by('created_date', 'DESC');
         $this->db->where('category', $category);
         $query = $this->db->get("food");
    
         if ($query->num_rows() > 0) {
             foreach ($query->result() as $row) {
                 $data[] = $row;
             }
             return $data;
         }
         return false;
       }
       
       public function record_food_all_count(){
          $this->db->from('food');
          //$this->db->where('category', $category);
          $query = $this->db->count_all_results();
          return $query;
        }

      public function fetch_food_all_data($limit, $start){
         $this->db->limit($limit, $start);
         $this->db->order_by('created_date', 'DESC');
         $query = $this->db->get("food");
    
         if ($query->num_rows() > 0) {
             foreach ($query->result() as $row) {
                 $data[] = $row;
             }
             return $data;
         }
         return false;
       }
       
       public function record_food_all_date_count($date){
          $this->db->like('date', $date);
          $this->db->from('food');
          //$this->db->where('category', $category);
          $query = $this->db->count_all_results();
          return $query;
       }
       
       public function record_food_category_date_count($category, $date){
          $this->db->like('date', $date);
          $this->db->from('food');
          $this->db->where('category', $category);
          $query = $this->db->count_all_results();
          return $query;
       }
       
       public function fetch_food_all_date_data($limit, $start, $date){
         //$imp_date = implode(',', $date);
         $this->db->limit($limit, $start);
         $this->db->like('date', $date);
         $this->db->order_by('created_date', 'DESC');
         $query = $this->db->get("food");
    
         if ($query->num_rows() > 0) {
             foreach ($query->result() as $row) {
                 $data[] = $row;
             }
             return $data;
         }
         return false;
       }
       
       public function fetch_food_category_date_data($limit, $start, $category, $date){
         //$imp_date = implode(',', $date);
         $this->db->limit($limit, $start);
         $this->db->like('date', $date);
         $this->db->where('category', $category);
         $this->db->order_by('created_date', 'DESC');
         $query = $this->db->get("food");
    
         if ($query->num_rows() > 0) {
             foreach ($query->result() as $row) {
                 $data[] = $row;
             }
             return $data;
         }
         return false;
       }
       
       public function fetch_food_data($date){
           $this->db->like('date', $date);
           $this->db->order_by('created_date', 'DESC');
           $query = $this->db->get("food")->result();
           return $query;
       }
      
      public function display_food_by_id($id){
          $this->db->where('id', $id);
          $query = $this->db->get('food')->result();
          return $query;
      }
      
      public function record_search_count() {
        $query = $this->db->count_all("food");
        return $query;
      }

     public function fetch_search_data($limit, $start, $title){
       $this->db->limit($limit, $start);
       $this->db->order_by('created_date', 'DESC');
       $query = $this->db->query("SELECT * FROM food WHERE title LIKE '%$title%' ")->result();
       return $query;
     }
      
      // End of Food 
    }

?>
