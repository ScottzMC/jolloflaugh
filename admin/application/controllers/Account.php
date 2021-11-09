<?php 

    class Account extends CI_Controller{
        
        public function login(){
            
            $submit_btn = $this->input->post('login');
            $this->load->view('admin/account/login');
            
            if(isset($submit_btn)){
                $email = $this->input->post('email');
                $password = $this->input->post('password');
                
                $sess_result = $this->Data_model->validate($email, $password);
                if($sess_result){
                  $session_data = array(
                  'login' => TRUE,
                  'uemail' => $sess_result[0]->email,
                  'ufirstname' => $sess_result[0]->firstname,
                  'ulastname' => $sess_result[0]->lastname,
                  'urole' => $sess_result[0]->role
                 );
        
                  $this->session->set_userdata($session_data); 
                  ?>
                  <script>
                      alert('Login successfully');
                      window.location.href="<?php echo site_url('dashboard'); ?>";
                  </script> 
                  <?php
              }else{ ?>
                <script>
                      alert('Login failed');
                      window.location.href="<?php echo site_url('account/login'); ?>";
                  </script> 
               <?}
            }
        }
        
        public function logout(){
          // destroy session
          $data = array('login' => '', 'uid' => '', 'ufirstname' => '', 'ulastname' => '', 'uemail' => '', 'urole' => '');
          $this->session->unset_userdata($data);
          $this->session->sess_destroy();
          redirect('account/login');
        }
  
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
      <?php } else{ ?>
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
