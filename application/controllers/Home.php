<?php 

    class Home extends CI_Controller{
        
        public function index(){
            $data['slider'] = $this->Data_model->display_slider();
            $data['performers'] = $this->Data_model->display_banner_performers();
            
            $data['main_section'] = $this->Data_model->display_top_section_banner_main();
            $data['top_section'] = $this->Data_model->display_top_section_banner();

            $data['videos'] = $this->Data_model->display_videos();
            $data['eat_laugh'] = $this->Data_model->display_eat_laugh_banner();
            $data['join_us'] = $this->Data_model->display_join_us();
            
            $this->load->view('site/home', $data);
        }
    }

?>