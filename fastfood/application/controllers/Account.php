<?php 

    class Account extends CI_Controller{
        
        public function login(){
            $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
          
            $form_valid = $this->form_validation->run();
            $submit_btn = $this->input->post('login');
            
            if($form_valid == FALSE){
                $data['menu'] = $this->Data_model->display_menu_options();
                
                $this->load->view('site/account/login', $data);
            }
            
            if(isset($submit_btn)){
                $email = $this->input->post('email');
                $password = $this->input->post('password');
                
                $query = $this->db->query("SELECT email, status FROM users WHERE email = '$email'")->result();
                foreach($query as $qry){
                    $query_email = $qry->email;
                    $query_status = $qry->status;
                }
                
                $uresult = $this->Data_model->validate($email, $password);
                if(count($uresult) > 0 && $query_status == "Activated"){
                  $sess_data = array(
                  'login' => TRUE,
                  'uid' => $uresult[0]->id,
                  'uemail' => $uresult[0]->email,
                  'ufirstname' => $uresult[0]->firstname,
                  'ulastname' => $uresult[0]->lastname,
                  'urole' => $uresult[0]->role,
                  'ustatus' => $uresult[0]->status
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
              }else if(empty($query_email) || empty($query_status) || $query_status == "Deactivated" || $query_status == "Blocked"){
                $statusMsg = '<span class="text-danger">Email needs to be activated!</span>';
                $this->session->set_flashdata('msgError', $statusMsg);
                
                $data['menu'] = $this->Data_model->display_menu_options();
                $this->load->view('site/account/login', $data);  
              }else{
                $statusMsg = '<span class="text-danger">Wrong Email-ID or Password!</span>';
                $this->session->set_flashdata('msgError', $statusMsg);
                
                $data['menu'] = $this->Data_model->display_menu_options();
                $this->load->view('site/account/login', $data);
               }
            }
        }
        
        public function register(){
            $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email|is_unique[users.email]');
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
            $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|matches[password]');
          
            $form_valid = $this->form_validation->run();
            $submit_btn = $this->input->post('register');
            
            if($form_valid == FALSE){
                $data['menu'] = $this->Data_model->display_menu_options();

                $this->load->view('site/account/register', $data);
            }
            
            if(isset($submit_btn)){
                $email = $this->input->post('email');
                $password = $this->input->post('password');
                $hashed_password = $this->bcrypt->hash_password($password);
                $shuffle = "ABCDEFGHZXCQWE";
                $unique = rand(000, 999);
                $code = $shuffle.$unique;
                $role = "User";
                $status = "Activated";
                $time = time();
                $date = date('Y-m-d H:i:s');
                
                $subject = "Activate your Account";
                  $body = "
                    Welcome to FastFood and thank you for registering an account. Upon clicking the link, your account would be activated,
                    Your email is - $email
                    please click the link to activate the account - https://scottnnaghor.com/fastfood/account/activate_user/$code";
                  $time = time();
                  $date = date('Y-m-d H:i:s');
        
                  $config = Array(
                 'protocol' => 'smtp',
                 'smtp_host' => 'smtp.scottnnaghor.com',
                 'smtp_port' => 25,
                 'smtp_user' => 'admin@scottnnaghor.com', // change it to account email
                 'smtp_pass' => 'TigerPhenix100', // change it to account email password
                 'mailtype' => 'html',
                 'charset' => 'iso-8859-1',
                 'wordwrap' => TRUE
                 );
        
                 $this->load->library('email', $config);
                 //$this->load->library('encrypt');
                 $this->email->from('admin@scottnnaghor.com', "FastFood Team");
                 $this->email->to("$email");
                 //$this->email->cc("testcc@domainname.com");
                 $this->email->subject("$subject");
                 $this->email->message("$body");
                
                $register_array = array(
                    'code' => $code,
                    'firstname' => "FirstName",
                    'lastname' => "LastName",
                    'email' => $email,
                    'password' => $hashed_password,
                    'role' => $role,
                    'status' => $status,
                    'telephone' => "000",
                    'address' => "none",
                    'postcode' => "none",
                    'town' => "none",
                    'created_time' => $time,
                    'created_date' => $date
                );
                
                $array = array('email' => $email);
                
                $add_user = $this->Data_model->create_user($register_array);
                $add_scheduler = $this->Data_model->create_scheduler($array);
                
                if($add_user && $add_scheduler && $this->email->send()){ ?>
                    <script>
                        alert('Account has been created successfully. Please Activate your Account in your Inbox');
                        window.location.href="<?php echo site_url('account/login'); ?>";
                    </script>
          <?php }else{ ?>
                   <script>
                        alert('Account has not been created');
                    </script> 
          <?php }
            }
        }
        
        public function logout(){
          // destroy session
          $data = array('login' => '', 'uid' => '', 'ufirstname' => '', 'ulastname' => '', 'uemail' => '', 'urole' => '');
          $this->session->unset_userdata($data);
          $this->session->sess_destroy();
          #delete_cookie('remember_me_token', 'http://localhost/ClientProjects/Soup', '/');
          redirect('home');
        }
        
        public function activate_user($code){
            //$code = $_GET['code'];
            $this->Data_model->activate_user($code); ?>
            <script>
                alert('Activated Successfully');
                window.location.href="<?php echo site_url('account/login'); ?>";
            </script>
            //redirect('account/login');
  <?php }
  
        public function forgot_password(){

            $session_email = $this->session->userdata('uemail');
            
            $email = $this->input->post('email');
            $submit = $this->input->post('forgot');
            
            $this->load->view('site/account/forgot_password', $data);
            
            $query = $this->db->query("SELECT email FROM users WHERE email = '$email' ")->result();
              foreach($query as $qry){
                 $query_email = $qry->email;
              }
    
          if(isset($submit) && $email == $query_email){
              $code = str_shuffle("ABCDEFXJKZAG");
              $subject = "Reset Password";
              $body = "
                The reset code - $code
                Upon clicking the link, put your reset code and new password in the reset page. 
                If you want to reset your password, please click the link to reset the password - https://scottnnaghor.com/fastfood/account/reset";
              $type = "Forgot Password";
              $time = time();
              $date = date('Y-m-d H:i:s');
    
              $config = Array(
                 'protocol' => 'smtp',
                 'smtp_host' => 'smtp.scottnnaghor.com',
                 'smtp_port' => 25,
                 'smtp_user' => 'admin@scottnnaghor.com', // change it to account email
                 'smtp_pass' => 'TigerPhenix100', // change it to account email password
                 'mailtype' => 'html',
                 'charset' => 'iso-8859-1',
                 'wordwrap' => TRUE
              );
    
             $this->load->library('email', $config);
             //$this->load->library('encrypt');
             $this->email->from('admin@scottnnaghor.com', "FastFood Team");
             $this->email->to("$email");
             //$this->email->cc("testcc@domainname.com");
             $this->email->subject("$subject");
             $this->email->message("$body");
    
            if($this->email->send()){ ?>
            <script>
                alert('Mail sent successfully');
                window.location.href="<?php echo base_url('account/login'); ?>";
              </script>    
            <?php }else{ ?>
                <script>
                    alert("Email does not exist ");
                    window.location.href="<?php echo site_url('account/login'); ?>";
                </script>
       <?php }
           }
      }
      
      public function reset(){
          $submit_code = $this->input->post('code');
          $email = $this->input->post('email');
          $submit = $this->input->post('reset');
          
          $query = $this->db->query("SELECT email FROM users WHERE email = '$email' ")->result();
          foreach($query as $qry){
              $query_email = $qry->email;
          }
          
          $this->load->view('site/account/reset', $data);
          
          if(isset($submit)){
            if(!empty($query_email) && $query_email == $email){
            $password = $this->input->post('password');
            $hashed_password = $this->bcrypt->hash_password($password);
            
            $update_detail = $this->Data_model->update_user_password($query_email, $hashed_password);
    
            if($update_detail){ ?>
              <script>
                alert('Account updated successfully');
                window.location.href="<?php echo base_url('account/login'); ?>";
              </script> 
      <?php }else{ ?>
              <script>
                alert('Reset Password Failed');
                window.location.href="<?php echo base_url('account/login'); ?>";
              </script> 
        <?php }
           }else{ ?>
              <script>
                alert("Email does not exist");
                window.location.href="<?php echo site_url('account/login'); ?>";
              </script>
           <?php }
          }
         }
      
    }

?>