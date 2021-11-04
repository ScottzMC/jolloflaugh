<?php 

    class Event extends CI_Controller{
        
        public function detail(){
            $data['detail'] = $this->Data_model->display_booking_by_detail();
            
            $this->load->view('site/detail', $data);
        }
    
    }

?>