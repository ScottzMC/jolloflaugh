<?php 

    class Custom404 extends CI_Controller{
        
        public function index(){
            $this->output->set_status_header('404');
            $this->data['content'] = 'custom404view'; // View name
            $this->load->view('site/custom404');
        }
    }

?>