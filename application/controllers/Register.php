<?php 

    class Register extends CI_Controller{
        
        public function index(){
            $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email|is_unique[users.email]');
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
            //$this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|matches[password]');
          
            $form_valid = $this->form_validation->run();
            $submit_btn = $this->input->post('register');
            
            if($form_valid == FALSE){
                //$data['menu'] = $this->Data_model->display_menu_options();

                $this->load->view('site/account/register');
            }
            
            if(isset($submit_btn)){
                $email = $this->input->post('email');
                $password = $this->input->post('password');
                $hashed_password = $this->bcrypt->hash_password($password);
                $role = "User";
                $time = time();
                $date = date('Y-m-d H:i:s');
                
                $register_array = array(
                    'firstname' => "FirstName",
                    'lastname' => "LastName",
                    'email' => $email,
                    'password' => $password,
                    'role' => $role,
                    'created_time' => $time,
                    'created_date' => $date
                );
                
                $add_user = $this->Data_model->create_user($register_array);

                if($add_user){ ?>
                    <script>
                        alert('Account has been created successfully.');
                        window.location.href="<?php echo site_url('login'); ?>";
                    </script>
          <?php }else{ ?>
                   <script>
                        alert('Account has not been created');
                   </script> 
          <?php }
            }
        }
    }

?>