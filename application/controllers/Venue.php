<?php 

    class Venue extends CI_Controller{
        
        public function index(){
            $data['venue'] = $this->Data_model->display_venue();
            
            $this->load->view('site/venue', $data);
        }
    
    }

?>