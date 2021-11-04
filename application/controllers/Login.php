<?php 

    class Login extends CI_Controller{
        
        public function index(){
            $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
          
            $form_valid = $this->form_validation->run();
            $submit_btn = $this->input->post('login');
            
            if($form_valid == FALSE){
                //$data['menu'] = $this->Data_model->display_menu_options();
                
                $this->load->view('site/account/login');
            }
            
            if(isset($submit_btn)){
                $email = $this->input->post('email');
                $password = $this->input->post('password');
                
                $query = $this->db->query("SELECT email FROM users WHERE email = '$email'")->result();
                foreach($query as $qry){
                    $query_email = $qry->email;
                }
                
                $uresult = $this->Data_model->validate($email, $password);
                if(count($uresult) > 0){
                  $sess_data = array(
                  'login' => TRUE,
                  'uid' => $uresult[0]->id,
                  'uemail' => $uresult[0]->email,
                  'ufirstname' => $uresult[0]->firstname,
                  'ulastname' => $uresult[0]->lastname,
                  'urole' => $uresult[0]->role
                 );
        
                  $this->session->set_userdata($sess_data);
                  $status = $this->session->userdata('ustatus'); ?>
                  <script>
                      alert('Login successfully');
                      window.location.href="<?php echo site_url('home'); ?>";
                  </script> 
                  <?php
                  /*if(isset($_SERVER['HTTP_REFERER'])){
                    redirect($_SERVER['HTTP_REFERER']);
                  }*/
              }else if(empty($query_email)){
                //$statusMsg = '<span class="text-danger">Email needs to be activated!</span>';
                //$this->session->set_flashdata('msgError', $statusMsg);
                
                //$data['menu'] = $this->Data_model->display_menu_options();
                $this->load->view('site/account/login');  
              }else{
                $statusMsg = '<span class="text-danger">Wrong Email-ID or Password!</span>';
                $this->session->set_flashdata('msgError', $statusMsg);
                
                //$data['menu'] = $this->Data_model->display_menu_options();
                $this->load->view('site/account/login');
               }
            }
        }
    }

?>