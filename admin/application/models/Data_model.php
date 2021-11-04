<?php 

    class Data_model extends CI_Model{
        
        // Account 
            
          public function validate($email, $password){
      	      $this->db->where('email', $email);
      	      $query = $this->db->get('users');
      	      
      	      $result = $query->row_array();
      	      
      	      if($this->bcrypt->check_password($password, $result['password'])){
      	          return $query->result();
      	      }else{
      	          return $query->result();
      	      }
      	  }
          	
          public function update_user_password($email, $password){
             $query = $this->db->query("UPDATE users SET password = '$password' WHERE email = '$email' ");
             return $query;
          }
        
        // End of Account
        
        // Banner 
        
        public function display_all_banner(){
            $query = $this->db->get('banner')->result();
            return $query;
        }
        
        public function display_banner_by_id($id){
            $this->db->where('id', $id);
            $query = $this->db->get('banner')->result();
            return $query;
        }
        
        public function insert_banner($data){
            $query = $this->db->insert('banner', $data);
            return $query;
        }
        
        public function update_banner($id, $data){
            $this->db->where('id', $id);
            $query = $this->db->update('banner', $data);
            return $query;
        }
        
        public function delete_banner($id){
            $query = $this->db->query("DELETE FROM banner WHERE id = '$id' ");
            return $query;
        }
        
        // End of Banner
        
        // Videos 
        
        public function display_all_videos(){
            $query = $this->db->get('videos')->result();
            return $query;
        }
        
        public function display_videos_by_id($id){
            $this->db->where('id', $id);
            $query = $this->db->get('videos')->result();
            return $query;
        }
        
        public function insert_videos($data){
            $query = $this->db->insert('videos', $data);
            return $query;
        }
        
        public function update_videos($id, $data){
            $this->db->where('id', $id);
            $query = $this->db->update('videos', $data);
            return $query;
        }
        
        public function delete_videos($id){
            $query = $this->db->query("DELETE FROM videos WHERE id = '$id' ");
            return $query;
        }
        
        // End of Videos 
        
        // Slider 
        
        public function display_all_slider(){
            $query = $this->db->get('slider')->result();
            return $query;
        }
        
        public function display_slider_by_id($id){
            $this->db->where('id', $id);
            $query = $this->db->get('slider')->result();
            return $query;
        }
        
        public function insert_slider($data){
            $query = $this->db->insert('slider', $data);
            return $query;
        }
        
        public function update_slider($id, $data){
            $this->db->where('id', $id);
            $query = $this->db->update('slider', $data);
            return $query;
        }
        
        public function delete_slider($id){
            $query = $this->db->query("DELETE FROM slider WHERE id = '$id' ");
            return $query;
        }
        
        // End of Slider 
        
        // Events 
        
        public function display_all_events(){
            $query = $this->db->get('events')->result();
            return $query;
        }
        
        public function display_events_by_id($id){
            $this->db->where('id', $id);
            $query = $this->db->get('events')->result();
            return $query;
        }
        
        public function insert_events($data){
            $query = $this->db->insert('events', $data);
            return $query;
        }
        
        public function update_events($id, $data){
            $this->db->where('id', $id);
            $query = $this->db->update('events', $data);
            return $query;
        }
        
        public function delete_events($id){
            $query = $this->db->query("DELETE FROM events WHERE id = '$id' ");
            return $query;
        }
        
        // End of Events 
        
        // Venue 
        
        public function display_all_venue(){
            $query = $this->db->get('venue')->result();
            return $query;
        }
        
        public function display_venue_by_id($id){
            $this->db->where('id', $id);
            $query = $this->db->get('venue')->result();
            return $query;
        }
        
        public function insert_venue($data){
            $query = $this->db->insert('venue', $data);
            return $query;
        }
        
        public function update_venue($id, $data){
            $this->db->where('id', $id);
            $query = $this->db->update('venue', $data);
            return $query;
        }
        
        public function delete_venue($id){
            $query = $this->db->query("DELETE FROM venue WHERE id = '$id' ");
            return $query;
        }
        
        // End of Venue
        
        // Jollof N Laugh 
        
        public function display_all_jollof_n_laugh(){
            $query = $this->db->get('booking')->result();
            return $query;
        }
        
        public function display_jollof_n_laugh_by_id($id){
            $this->db->where('id', $id);
            $query = $this->db->get('booking')->result();
            return $query;
        }
        
        public function insert_jollof_n_laugh($data){
            $query = $this->db->insert('booking', $data);
            return $query;
        }
        
        public function update_jollof_n_laugh($id, $data){
            $this->db->where('id', $id);
            $query = $this->db->update('booking', $data);
            return $query;
        }
        
        public function delete_jollof_n_laugh($id){
            $query = $this->db->query("DELETE FROM booking WHERE id = '$id' ");
            return $query;
        }
        
        // End of Jollof N Laugh
        
    }

?>