<?php 
    
    class Forgot_password extends CI_Controller{
        
        public function index(){
            $this->load->view('site/account/forgot_password');
        }
    }

?>