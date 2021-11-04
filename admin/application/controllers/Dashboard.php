<?php 

    class Dashboard extends CI_Controller{
        
        public function index(){
            $session_role = $this->session->userdata('urole');
            
            //if($session_role == "Admin"){
                $this->load->view('admin/dashboard');   
            //}else{
              //  redirect('login');
            //}
        }
    }

?>