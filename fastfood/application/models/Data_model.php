<?php

  class Data_model extends CI_Model{
      
      // Account
      
      public function create_user($data){
        $escape_data = $this->db->escape_str($data);
        $query = $this->db->insert('users', $escape_data);
        return $query;
      }
      
      public function create_scheduler($data){
         $query = $this->db->insert('scheduler', $data);
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
      
      // Home
      
      public function display_meal_day(){
          $this->db->limit('8');
          //$this->db->where('type', 'meal-of-the-day');
          $this->db->order_by('created_date', 'DESC');
          $query = $this->db->get('food')->result();
          return $query;
      }
      
      public function display_family_meal(){
          $this->db->limit('8');
          $this->db->order_by('created_date', 'DESC');
          //$this->db->where('type', 'family-orders');
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
      
      public function display_banner_by_home($type){
          $this->db->limit('3');
          $this->db->where('type', $type);
          $query = $this->db->get('banner')->result();
          return $query;
      }
      
      public function display_slider_by_staff(){
          $this->db->where('category', "Staff");
          $query = $this->db->get('slider')->result();
          return $query;
      }
      
      public function display_banner_by_staff($type){
          $this->db->limit('3');
          $this->db->where('type', $type);
          $query = $this->db->get('banner')->result();
          return $query;
      }
      
      public function display_schedule_date($email){
          $this->db->where('email', $email);
          $query = $this->db->get('scheduler')->result();
          return $query;
      }
      
      public function display_schedule_time(){
          date_default_timezone_set('Europe/London');
          $date = date('H:i:s');
          //$query = $this->db->query("SELECT time FROM schedule_hour WHERE time >= '$date' ")->result();
          $query = $this->db->query("SELECT time FROM schedule_hour")->result(); // By ND
          return $query;
      }
      
      public function update_schedule_date($data, $email){
          //$query = $this->db->query("UPDATE scheduler SET delivery_date = '$date' WHERE email = '$email' ");
          $this->db->where('email', $email);
          $query = $this->db->update('scheduler', $data);
          return $query;
      }
      
      // End of Home 
      
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
      
      // Shopping 
      
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
      
      public function insert_wishlist($data){
        $query = $this->db->insert('wishlist', $data);
        return $query; 
      }
      
      public function display_wishlist($email){
         $this->db->where('email', $email);
         $query = $this->db->get('wishlist')->result();
         return $query;
      }
      
      public function update_user_scheduler($array, $email){
          $this->db->where('email', $email);
          $query = $this->db->update('scheduler', $array);
          return $query;
      }
      
      // End of Shopping 
      
      /// Staff 
      
        // Account
        
        public function create_staff($data){
          $escape_data = $this->db->escape_str($data);
          $query = $this->db->insert('staff', $escape_data);
          return $query;
        }
        
        public function validate_staff($email, $password){
    	  $escape_email = $this->db->escape_str($email);
          $escape_password = $this->db->escape_str($password);

	  	  $this->db->where('email', $escape_email);
    	  $query = $this->db->get('staff');

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
      	
          public function update_staff_password($email, $password){
             $query = $this->db->query("UPDATE staff SET password = '$password' WHERE email = '$email' ");
             return $query;
          }
      
          public function activate_staff($code){
              $query = $this->db->query("UPDATE staff SET status = 'Activated' WHERE code = '$code' ");
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
    	
    	public function display_my_staff_account($email){
    	   $this->db->where('email', $email);
    	   $query = $this->db->get('staff')->result();
    	   return $query;
    	}
    	
    	public function update_my_staff_details($data){
    	   $query = $this->db->update('staff', $data);
    	   return $query;
    	}
    	
    	public function display_my_staff_order_items($email){
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
    	
    	public function cancel_order($id){
    	   $query = $this->db->query("UPDATE order_items SET status = 'Cancelled' WHERE id = '$id' ");
    	   return $query;
    	}
    	
    	public function delete_order($id){
            $query = $this->db->query("DELETE FROM order_items WHERE id = '$id' ");
        }

        // End of Shopping
        
        // Voucher 
        
        public function add_temp_vouchers($data){
            $query = $this->db->insert('temp_vouchers', $data);
            return $query;
        }
        
        public function add_voucher_details($data){
            $query = $this->db->insert('voucher_details', $data);
            return $query;
        }
        
        public function add_meal_voucher_details($data){
            $query = $this->db->insert('meal_voucher_details', $data);
            return $query;
        }
        
        public function display_all_vouchers(){
          $query = $this->db->get('vouchers')->result();
          return $query;
        }
        
        public function display_all_meal_vouchers(){
          $query = $this->db->get('meal_vouchers')->result();
          return $query;
        }
        
        public function display_vouchers_by_id($company, $id){
          $query = $this->db->query("SELECT * FROM vouchers WHERE company = '$company' AND id = '$id' ")->result();
          return $query;
        }
        
        public function display_meal_vouchers_by_id($company, $id){
          $query = $this->db->query("SELECT * FROM meal_vouchers WHERE company = '$company' AND id = '$id' ")->result();
          return $query;
        }
        
        public function update_staff_voucher($code){
            $query = $this->db->query("UPDATE voucher_details SET quantity = quantity -1 WHERE code = '$code' ");
            return $query;
        }
        
        public function remove_staff_voucher($id){
            $query = $this->db->query("DELETE FROM temp_vouchers WHERE id = '$id' ");
            return $query;
        }
        
        public function update_staff_meal_voucher($code){
            $query = $this->db->query("UPDATE meal_voucher_details SET quantity = quantity -1 WHERE code = '$code' ");
            return $query;
        }
        
        public function remove_staff_meal_voucher($id){
            $query = $this->db->query("DELETE FROM temp_vouchers WHERE id = '$id' ");
            return $query;
        }
        
        public function display_my_staff_vouchers($email){
    	    $this->db->where('email', $email);
    	    $query = $this->db->get('voucher_details')->result();
    	    return $query;
    	}
    	
    	public function display_my_staff_meal_vouchers($email){
    	    $this->db->where('email', $email);
    	    $query = $this->db->get('meal_voucher_details')->result();
    	    return $query;
    	}
        
        // End of Voucher
      
      /// End of Staff 
  }

?>
