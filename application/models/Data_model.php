<?php 

    class Data_model extends CI_Model{
        
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
        
        // End of Account
        
        // Home 
        
        public function display_slider(){
            $this->db->order_by('id', 'ASC');
            $query = $this->db->get('slider')->result();
            return $query;
        }
        
        public function display_banner_performers(){
            $this->db->order_by('id', 'ASC');
            $this->db->where('type', 'Home');
            $this->db->where('category', 'Performers');
            $query = $this->db->get('banner')->result();
            return $query;
        }
        
        public function display_top_section_banner_main(){
            $this->db->order_by('id', 'ASC');
            $this->db->limit('1');
            $this->db->where('type', 'Home');
            $this->db->where('category', 'Top Section');
            $this->db->where('subcategory', 'Main');
            $query = $this->db->get('banner')->result();
            return $query;
        }
        
        public function display_top_section_banner(){
            $this->db->order_by('id', 'ASC');
            $this->db->limit('2');
            $this->db->where('type', 'Home');
            $this->db->where('category', 'Top Section');
            $this->db->where('subcategory', 'None');
            $query = $this->db->get('banner')->result();
            return $query;
        }
        
        public function display_eat_laugh_banner(){
            $this->db->order_by('id', 'ASC');
            $this->db->where('type', 'Home');
            $this->db->where('category', 'Eat-Laugh-Dance');
            $query = $this->db->get('banner')->result();
            return $query;
        }
        
        public function display_join_us(){
            $this->db->order_by('id', 'ASC');
            $this->db->where('type', 'Home');
            $this->db->where('category', 'Join-Us');
            $query = $this->db->get('banner')->result();
            return $query;
        }
        
        public function display_videos(){
            $this->db->order_by('id', 'ASC');
            //$this->db->limit('1');
            $this->db->where('type', 'Home');
            $query = $this->db->get("videos")->result();
            return $query;
        }
        
        // End of Home 
        
        // Booking 
        
        public function display_booking_by_detail(){
            $query = $this->db->get('booking')->result();
            return $query;
        }
        
        // End of Booking 
        
        // Venue 
        
        public function display_venue(){
            $query = $this->db->get('venue')->result();
            return $query;
        }
        
        // End of Venue
        
        // Events 
        
        public function display_event_by_detail($code){
            $this->db->where('code', $code);
            $query = $this->db->get('events')->result();
            return $query;
        }
        
        public function display_related_events($category){
            $this->db->order_by('created_date', 'DESC');
            $this->db->where('category', $category);
            $query = $this->db->get('events')->result();
            return $query; 
        }
        
        public function record_event_category_count($category){
          $this->db->from('events');
          $this->db->where('category', $category);
          $query = $this->db->count_all_results();
          return $query;
        }
        
        public function record_event_subcategory_count($category, $subcategory){
          $this->db->from('events');
          $this->db->where('subcategory', $subcategory);
          $this->db->where('category', $category);
          $query = $this->db->count_all_results();
          return $query;
        }
        
        public function record_event_search_count(){
          $query = $this->db->count_all('events');
          return $query;
        }
        
        public function fetch_search_data($limit, $start, $title){
           $this->db->limit($limit, $start);
           $query = $this->db->query("SELECT * FROM events WHERE title LIKE '%$title%' ")->result();
           return $query;
         }
    
        public function fetch_event_category_data($category, $limit, $start){
         $this->db->limit($limit, $start);
         $this->db->order_by('created_date', 'DESC');
         $this->db->where('category', $category);
         $query = $this->db->get("events");
    
         if ($query->num_rows() > 0) {
             foreach ($query->result() as $row) {
                 $data[] = $row;
             }
             return $data;
          }
          
           return false;
        }
         
        public function fetch_event_subcategory_data($category, $subcategory, $limit, $start){
            $this->db->limit($limit, $start);
            $this->db->order_by('created_date', 'DESC');
            $this->db->where('category', $category);
            $this->db->where('subcategory', $subcategory);
            $query = $this->db->get("events");
    
            if($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    $data[] = $row;
                }
                return $data;
             }
             
             return false;
        }
        
        // End of Events 
        
    }

?>