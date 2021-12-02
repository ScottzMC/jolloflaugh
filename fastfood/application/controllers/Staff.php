<?php

    class Staff extends CI_Controller{
        
        // Account 
        
        public function login(){
            $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
          
            $form_valid = $this->form_validation->run();
            $submit_btn = $this->input->post('login');
            
            if($form_valid == FALSE){
                $data['menu'] = $this->Data_model->display_menu_options();
                
                $this->load->view('staff/account/login', $data);
            }
            
            if(isset($submit_btn)){
                $email = $this->input->post('email');
                $password = $this->input->post('password');
                
                $query = $this->db->query("SELECT email, status FROM staff WHERE email = '$email'")->result();
                foreach($query as $qry){
                    $query_email = $qry->email;
                    $query_status = $qry->status;
                }
                
                $uresult = $this->Data_model->validate_staff($email, $password);
                if(count($uresult) > 0 && $query_status == "Activated"){
                  $sess_data = array(
                  'login' => TRUE,
                  'uid' => $uresult[0]->id,
                  'uemail' => $uresult[0]->email,
                  'ufirstname' => $uresult[0]->firstname,
                  'ulastname' => $uresult[0]->lastname,
                  'urole' => $uresult[0]->role,
                  'ustaff_code' => $uresult[0]->staff_code,
                  'ucompany' => $uresult[0]->company,
                  'ustatus' => $uresult[0]->status
                 );
        
                  $this->session->set_userdata($sess_data);
                  
                  $status = $this->session->userdata('ustatus'); 
                  $company = $this->session->userdata('ucompany');
                  ?>
                  <script>
                      alert('Login successfully');
                      window.location.href="<?php echo site_url('staff/home/'.strtolower($company)); ?>";
                  </script> 
                  <?php
                  /*if(isset($_SERVER['HTTP_REFERER'])){
                    redirect($_SERVER['HTTP_REFERER']);
                  }*/
              }else if(empty($query_email) || empty($query_status) || $query_status == "Deactivated" || $query_status == "Blocked"){
                $statusMsg = '<span class="text-danger">Email needs to be activated!</span>';
                $this->session->set_flashdata('msgError', $statusMsg);
                
                $data['menu'] = $this->Data_model->display_menu_options();
                $this->load->view('staff/account/login', $data);  
              }else{
                $statusMsg = '<span class="text-danger">Wrong Email-ID or Password!</span>';
                $this->session->set_flashdata('msgError', $statusMsg);
                
                $data['menu'] = $this->Data_model->display_menu_options();
                $this->load->view('staff/account/login', $data);
               }
            } 
        }
        
        public function register(){
            $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email|is_unique[users.email]');
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
            $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|matches[password]');
            
            $staff_code = $this->input->post('staff_code');
          
            $form_valid = $this->form_validation->run();
            $submit_btn = $this->input->post('register');
            
            if($form_valid == FALSE){
                $data['menu'] = $this->Data_model->display_menu_options();

                $this->load->view('staff/account/register', $data);
            }
            
            if(isset($submit_btn)){
                $email = $this->input->post('email');
                $password = $this->input->post('password');
                $hashed_password = $this->bcrypt->hash_password($password);
                $shuffle = "DEFGHZXC";
                $unique = rand(000, 999);
                $code = $shuffle.$unique;
                $staff_code = $this->input->post('staff_code');
                $role = "Staff";
                $status = "Deactivated";
                $time = time();
                $date = date('Y-m-d H:i:s');
                
                $sequel = $this->db->query("SELECT * FROM business WHERE code = '$staff_code' ")->result();
                foreach($sequel as $sql){
                    $stf_code = $sql->code;
                    $stf_company = $sql->company_name;
                }
                
                if(!empty($staff_code)){
                      $subject = "Activate your Account";
                      $body = "
                        Welcome to FastFood and thank you for registering an account. Upon clicking the link, your account would be activated,
                        Your email is - $email
                        please click the link to activate the account - https://scottnnaghor.com/fastfood/staff/activate_staff/$code";
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
                     
                     $query = $this->db->query("SELECT main_address FROM company WHERE code = '$staff_code' ")->result();
                     foreach($query as $qry){
                        $address = $qry->main_address;
                     }
                     
                     if($staff_code == $stf_code){
                       $address = $qry->main_address;  
                     }else{
                       $address = "none";
                     }
                    
                    $register_array = array(
                        'code' => $code,
                        'staff_code' => $staff_code,
                        'company' => $stf_company,
                        'firstname' => "FirstName",
                        'lastname' => "LastName",
                        'email' => $email,
                        'password' => $hashed_password,
                        'role' => $role,
                        'status' => $status,
                        'telephone' => "000",
                        'address' => $address,
                        'postcode' => "none",
                        'town' => "none",
                        'created_time' => $time,
                        'created_date' => $date
                    );
                    
                    $array = array('email' => $email);
                    
                    $add_staff = $this->Data_model->create_staff($register_array);
                    $add_scheduler = $this->Data_model->create_scheduler($array);
    
                    if($add_staff && $staff_code == $stf_code && $this->email->send()){ ?>
                        <script>
                            alert('Staff has been created successfully. Please Activate your Account in your Inbox');
                            window.location.href="<?php echo site_url('staff/login'); ?>";
                        </script>
              <?php }else if($staff_code != $stf_code){ ?>
                         <script>
                            alert('Staff creation failed. Please input the right company code');
                            window.location.href="<?php echo site_url('staff/register'); ?>";
                        </script>
              <?php }else{ ?>
                       <script>
                            alert('Staff has not been created');
                        </script> 
              <?php }
                }else{ ?>
                  <script>
                    alert("Please input Company Code to Register");
                    window.location.href="<?php echo site_url('staff/register'); ?>";
                </script>  
          <?php }
               
            } 
        }
        
        public function forgot_password(){

            $session_email = $this->session->userdata('uemail');
            
            $email = $this->input->post('email');
            $submit = $this->input->post('forgot');
            
            $this->load->view('staff/account/forgot_password', $data);
            
            $query = $this->db->query("SELECT email FROM staff WHERE email = '$email' ")->result();
              foreach($query as $qry){
                 $query_email = $qry->email;
              }
    
          if(isset($submit) && $email == $query_email){
              $code = str_shuffle("ABCDEFXJKZAG");
              $subject = "Reset Password";
              $body = "
                The reset code - $code
                Upon clicking the link, put your reset code and new password in the reset page. 
                If you want to reset your password, please click the link to reset the password - https://scottnnaghor.com/fastfood/staff/reset";
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
                window.location.href="<?php echo base_url('staff/login'); ?>";
              </script>    
            <?php }else{ ?>
                <script>
                    alert("Email does not exist ");
                    window.location.href="<?php echo site_url('staff/login'); ?>";
                </script>
       <?php }
           }
      }
      
      public function reset(){
          $submit_code = $this->input->post('code');
          $email = $this->input->post('email');
          $submit = $this->input->post('reset');
          
          $query = $this->db->query("SELECT email FROM staff WHERE email = '$email' ")->result();
          foreach($query as $qry){
              $query_email = $qry->email;
          }
          
          $this->load->view('staff/account/reset', $data);
          
          if(isset($submit)){
            if(!empty($query_email) && $query_email == $email){
            $password = $this->input->post('password');
            $hashed_password = $this->bcrypt->hash_password($password);
            
            $update_detail = $this->Data_model->update_staff_password($query_email, $hashed_password);
    
            if($update_detail){ ?>
              <script>
                alert('Account updated successfully');
                window.location.href="<?php echo base_url('staff/login'); ?>";
              </script> 
      <?php }else{ ?>
              <script>
                alert('Reset Password Failed');
                window.location.href="<?php echo base_url('staff/login'); ?>";
              </script> 
        <?php }
           }else{ ?>
              <script>
                alert("Email does not exist");
                window.location.href="<?php echo site_url('staff/login'); ?>";
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
          redirect('staff/login');
        }
        
        // End of Account 
        
        // Home 
        
        public function home($company){
          $email = $this->session->userdata('uemail');
          $role = $this->session->userdata('urole');
          
          $query = $this->db->query("SELECT company FROM staff WHERE email = '$email' ")->result();
          foreach($query as $qry){
            $user_company = $qry->company;
          }
          
          if(!$this->cart->contents()){
			$data['message'] = '<p><div class="alert alert-danger" role="alert">Your cart is empty!</div></p>';
		  }else{
			$data['message'] = $this->session->flashdata('message');
		  }
          
          $data['menu'] = $this->Data_model->display_menu_options();
          $data['offer_meal'] = $this->Data_model->display_meal_day();    
          $data['family_order'] = $this->Data_model->display_family_meal();  
          $data['slider'] = $this->Data_model->display_slider_by_staff();
          $data['banner'] = $this->Data_model->display_banner_by_staff("Staff");
          
          $data['schedule'] = $this->Data_model->display_schedule_date($email);
          
          $btn_submit = $this->input->post('btn_schedule');
      
          if(isset($btn_submit)){
             $delivery_date = $this->input->post('delivery_date'); 
             $num_time = $this->input->post('num_time'); 
             $postcode = $this->input->post('postcode');
            
            $array = array(
                'delivery_date' => date('dS M Y',strtotime($delivery_date)),    
                'delivery_day' => date('l',strtotime($delivery_date)),    
                'num_time' => $num_time,
                'postcode' => $postcode     
            );
            
             $delivery_data = $this->Data_model->update_schedule_date($array, $email);
             
            $data['miles'] = number_format($distance["miles"], 2);
            
            if($delivery_data){ 
             ?>
             <script>
                 alert('Delivery date has been set');
                 window.location.href="<?php echo site_url('staff/home/'.$company); ?>";
             </script>
        <?php }else{ ?>
            <script>
                 alert('Failed');
             </script>
        <?php } ?>
      <?php }
    
          if($role == "Staff" && $user_company == $company){
             $addressTo   = 'RM13 8NL';
  
            $data['distance'] = $this->getDistance($postcode, $addressTo, "K");
      
            $this->load->view('staff/view', $data);
          }else{
            redirect('home');
          }
        }
        
        public function activate_staff($code){
          //$code = $_GET['code'];
          $this->Data_model->activate_staff($code); ?>
           <script>
              alert('Activated Successfully');
              window.location.href="<?php echo site_url('staff/login'); ?>";
           </script>
  <?php }
        
        // End of Home 
        
        // Food 
        
        public function food_detail($company, $id, $title){
            $user_company = $this->session->userdata('ucompany');
            $role = $this->session->userdata('urole');
            
            $email = $this->session->userdata('uemail');
          
            $data['menu'] = $this->Data_model->display_menu_options();
            $data['detail'] = $this->Data_model->display_food_by_id($id);
            $data['schedule'] = $this->Data_model->display_schedule_date($email);
            
            if(!$this->cart->contents()){
    		  $data['message'] = '<p><div class="alert alert-danger" role="alert">Your cart is empty!</div></p>';
    		}else{
    		  $data['message'] = $this->session->flashdata('message');
    		}
    		
    		if($role == "Staff" && $user_company == $company){
               $this->load->view('staff/food/detail', $data);
            }else{
               redirect('home');
            }
        }
        
        public function food_all($company){
            $user_company = $this->session->userdata('ucompany');
            $role = $this->session->userdata('urole');
            $email = $this->session->userdata('uemail');
            
            $data['menu'] = $this->Data_model->display_menu_options();
            $data['schedule'] = $this->Data_model->display_schedule_date($email);

            if(!$this->cart->contents()){
    		  $data['message'] = '<p><div class="alert alert-danger" role="alert">Your cart is empty!</div></p>';
    		}else{
    		  $data['message'] = $this->session->flashdata('message');
    		}
    		
    		if($role == "Staff" && $user_company == $company){
    		  $config['base_url'] = base_url('staff/food_category/'.$company);
              $total_row = $this->Data_model->record_food_all_count();
              $config['total_rows'] = $total_row;
              $config['per_page'] = 12;
              $config['uri_segment'] = 4;
              $choice = $config['total_rows']/$config['per_page'];
              $config['num_links'] = round($choice);
        
              $config['full_tag_open'] = '<ul>';
              $config['full_tag_close'] = '</ul>';
        
              $config['first_tag_open'] = '<li>';
              $config['last_tag_open'] = '<li>';
        
              $config['next_tag_open'] = '<li>';
              $config['prev_tag_open'] = '<li>';
        
              $config['num_tag_open'] = '<li>';
              $config['num_tag_close'] = '</li>';
        
              $config['first_tag_close'] = '</li>';
              $config['last_tag_close'] = '</li>';
        
              $config['next_tag_close'] = '</li>';
              $config['prev_tag_close'] = '</li>';
        
              $config['cur_tag_open'] = '<li><a class="active">';
              $config['cur_tag_close'] = '</a></li>';
        
              $this->pagination->initialize($config);
        
              $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        
              $data['all'] = $this->Data_model->fetch_food_all_data($config["per_page"], $page);
        
              //if(!empty($data['all'])){
                $this->load->view('staff/food/all', $data);
              //}else{
                //show_404();
              //}
              
            }else{
               redirect('staff/home/'.$company);
            }
        }
        
        public function food_sort_date($company, $date){
            $user_company = $this->session->userdata('ucompany');
            $role = $this->session->userdata('urole');
            $email = $this->session->userdata('uemail');
            
            $data['menu'] = $this->Data_model->display_menu_options();
            $data['schedule'] = $this->Data_model->display_schedule_date($email);

            if(!$this->cart->contents()){
    		  $data['message'] = '<p><div class="alert alert-danger" role="alert">Your cart is empty!</div></p>';
    		}else{
    		  $data['message'] = $this->session->flashdata('message');
    		}
    		
    		if($role == "Staff" && $user_company == $company){
    		  $config['base_url'] = base_url('staff/food_sort_date/'.$company.'/'.$date);
              $total_row = $this->Data_model->record_food_all_date_count($date);
              $config['total_rows'] = $total_row;
              $config['per_page'] = 12;
              $config['uri_segment'] = 4;
              $choice = $config['total_rows']/$config['per_page'];
              $config['num_links'] = round($choice);
        
              $config['full_tag_open'] = '<ul>';
              $config['full_tag_close'] = '</ul>';
        
              $config['first_tag_open'] = '<li>';
              $config['last_tag_open'] = '<li>';
        
              $config['next_tag_open'] = '<li>';
              $config['prev_tag_open'] = '<li>';
        
              $config['num_tag_open'] = '<li>';
              $config['num_tag_close'] = '</li>';
        
              $config['first_tag_close'] = '</li>';
              $config['last_tag_close'] = '</li>';
        
              $config['next_tag_close'] = '</li>';
              $config['prev_tag_close'] = '</li>';
        
              $config['cur_tag_open'] = '<li><a class="active">';
              $config['cur_tag_close'] = '</a></li>';
        
              $this->pagination->initialize($config);
        
              $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        
              $data['all'] = $this->Data_model->fetch_food_all_date_data($config["per_page"], $page, $date);
        
              if(!empty($data['all'])){
                $this->load->view('staff/food/sort_date', $data);
              }else{
                show_404();
              }
              
            }else{
               redirect('staff/home/'.$company);
            }
        }
        
        public function food_category($company, $category){
            $user_company = $this->session->userdata('ucompany');
            $role = $this->session->userdata('urole');
            $email = $this->session->userdata('uemail');
            
            $data['menu'] = $this->Data_model->display_menu_options();
            $data['schedule'] = $this->Data_model->display_schedule_date($email);
            
            if(!$this->cart->contents()){
    		  $data['message'] = '<p><div class="alert alert-danger" role="alert">Your cart is empty!</div></p>';
    		}else{
    		  $data['message'] = $this->session->flashdata('message');
    		}
    		
    		if($role == "Staff" && $user_company == $company){
    		  $config['base_url'] = base_url('staff/food_category/'.$company.'/'.$category);
              $total_row = $this->Data_model->record_food_category_count($category);
              $config['total_rows'] = $total_row;
              $config['per_page'] = 12;
              $config['uri_segment'] = 5;
              $choice = $config['total_rows']/$config['per_page'];
              $config['num_links'] = round($choice);
        
              $config['full_tag_open'] = '<ul>';
              $config['full_tag_close'] = '</ul>';
        
              $config['first_tag_open'] = '<li>';
              $config['last_tag_open'] = '<li>';
        
              $config['next_tag_open'] = '<li>';
              $config['prev_tag_open'] = '<li>';
        
              $config['num_tag_open'] = '<li>';
              $config['num_tag_close'] = '</li>';
        
              $config['first_tag_close'] = '</li>';
              $config['last_tag_close'] = '</li>';
        
              $config['next_tag_close'] = '</li>';
              $config['prev_tag_close'] = '</li>';
        
              $config['cur_tag_open'] = '<li><a class="active">';
              $config['cur_tag_close'] = '</a></li>';
        
              $this->pagination->initialize($config);
        
              $page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
        
              $data['category'] = $this->Data_model->fetch_food_category_data($config["per_page"], $page, $category);
        
              //if(!empty($data['category'])){
                $this->load->view('staff/food/category', $data);
              //}else{
                //show_404();
              //}
              
            }else{
               redirect('staff/home/'.$company);
            }
        }
        
        public function food_category_sort_date($company, $category, $date){
            $user_company = $this->session->userdata('ucompany');
            $role = $this->session->userdata('urole');
            $email = $this->session->userdata('uemail');
            
            $data['menu'] = $this->Data_model->display_menu_options();
            $data['schedule'] = $this->Data_model->display_schedule_date($email);
            
            if(!$this->cart->contents()){
    		  $data['message'] = '<p><div class="alert alert-danger" role="alert">Your cart is empty!</div></p>';
    		}else{
    		  $data['message'] = $this->session->flashdata('message');
    		}
    		
    		if($role == "Staff" && $user_company == $company){
    		  $config['base_url'] = base_url('staff/food_category_sort_date/'.$company.'/'.$category.'/'.$date);
              $total_row = $this->Data_model->record_food_category_count($category);
              $config['total_rows'] = $total_row;
              $config['per_page'] = 12;
              $config['uri_segment'] = 5;
              $choice = $config['total_rows']/$config['per_page'];
              $config['num_links'] = round($choice);
        
              $config['full_tag_open'] = '<ul>';
              $config['full_tag_close'] = '</ul>';
        
              $config['first_tag_open'] = '<li>';
              $config['last_tag_open'] = '<li>';
        
              $config['next_tag_open'] = '<li>';
              $config['prev_tag_open'] = '<li>';
        
              $config['num_tag_open'] = '<li>';
              $config['num_tag_close'] = '</li>';
        
              $config['first_tag_close'] = '</li>';
              $config['last_tag_close'] = '</li>';
        
              $config['next_tag_close'] = '</li>';
              $config['prev_tag_close'] = '</li>';
        
              $config['cur_tag_open'] = '<li><a class="active">';
              $config['cur_tag_close'] = '</a></li>';
        
              $this->pagination->initialize($config);
        
              $page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
        
              $data['category'] = $this->Data_model->fetch_food_category_date_data($config["per_page"], $page, $category, $date);
        
              //if(!empty($data['category'])){
                $this->load->view('staff/food/sort_category_date', $data);
              //}else{
                //show_404();
              //}
              
            }else{
               redirect('staff/home/'.$company);
            }
        }
        
        public function food_search($company){
            $user_company = $this->session->userdata('ucompany');
            $role = $this->session->userdata('urole');
            $email = $this->session->userdata('uemail');
            
            $data['menu'] = $this->Data_model->display_menu_options();
            $data['schedule'] = $this->Data_model->display_schedule_date($email);
            
            if(!$this->cart->contents()){
    		  $data['message'] = '<p><div class="alert alert-danger" role="alert">Your cart is empty!</div></p>';
    		}else{
    		  $data['message'] = $this->session->flashdata('message');
    		}
    		
    		$search_query = $this->input->post('search_query');
    		
    		if($role == "Staff" && $user_company == $company){
    		  $config['base_url'] = base_url('staff/food_search/'.$company);
              $total_row = $this->Data_model->record_search_count();
              $config['total_rows'] = $total_row;
              $config['per_page'] = 12;
              $config['uri_segment'] = 3;
              $choice = $config['total_rows']/$config['per_page'];
              $config['num_links'] = round($choice);
        
              $config['full_tag_open'] = '<ul>';
              $config['full_tag_close'] = '</ul>';
        
              $config['first_tag_open'] = '<li>';
              $config['last_tag_open'] = '<li>';
        
              $config['next_tag_open'] = '<li>';
              $config['prev_tag_open'] = '<li>';
        
              $config['num_tag_open'] = '<li>';
              $config['num_tag_close'] = '</li>';
        
              $config['first_tag_close'] = '</li>';
              $config['last_tag_close'] = '</li>';
        
              $config['next_tag_close'] = '</li>';
              $config['prev_tag_close'] = '</li>';
        
              $config['cur_tag_open'] = '<li><a class="active">';
              $config['cur_tag_close'] = '</a></li>';
        
              $this->pagination->initialize($config);
        
              $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
              $data['search'] = $this->Data_model->fetch_search_data($config["per_page"], $page, $search_query);
        
              //if(!empty($data['search'])){
                $this->load->view('staff/food/search', $data);
              //}else{
                //show_404();
              //}
              
            }else{
               redirect('staff/home/'.$category);
            }
        }
        
        // End of Food 
        
        // Shopping 
        
        public function view_cart(){
          $email = $this->session->userdata('uemail');
          $user_company = $this->session->userdata('ucompany');
          $role = $this->session->userdata('urole');
          
          if(!$this->cart->contents()){
			$data['message'] = '<p><div class="alert alert-danger" role="alert">Your cart is empty!</div></p>';
		  }else{
			$data['message'] = $this->session->flashdata('message');
		  }
          
          $data['menu'] = $this->Data_model->display_menu_options();
          $data['offer_meal'] = $this->Data_model->display_meal_day();    
          $data['family_order'] = $this->Data_model->display_family_meal();
          $data['vouchers'] = $this->Data_model->display_my_staff_vouchers($email);
          $data['schedule'] = $this->Data_model->display_schedule_date($email);

          if($role == "Staff"){
            $this->load->view('staff/shopping/view_cart', $data);
          }else{
            redirect('home');
          } 
        }
        
        public function add_cart($company){
          $insert_items = array(
            'id' => $this->input->post('id'),
            'name' => $this->input->post('title'),
            'price' => $this->input->post('price'),
            'qty' => 1,
            'code' => $this->input->post('code'),
            'category' => $this->input->post('category'),
            'image' => $this->input->post('image')
          );
    
         $this->cart->insert($insert_items);
         redirect('staff/view_cart/'.$company);
        }
        
        public function add_wishlist($company){
          $session_email = $this->session->userdata('uemail');     
            
          $insert_items = array(
            'food_id' => $this->input->post('food_id'),  
            'email' => $session_email,
            'title' => $this->input->post('title'),
            'category' => $this->input->post('category'),
            'price' => $this->input->post('price'),
            'created_time' => time(),
            'created_date'  => date('Y-m-j H:i:s')
          );
    
         $this->Data_model->insert_wishlist($insert_items); 
         ?>
         <script>
             alert('Added to Wishlist');
             window.location.href="<?php echo site_url('staff/my_account/'.$company); ?>";
         </script>
    <?php 
        }
        
        function updateItemQty(){
            $update = 0;
            
            // Get cart item info
            $rowid = $this->input->get('rowid');
            $qty = $this->input->get('qty');
            
            // Update item in the cart
            if(!empty($rowid) && !empty($qty)){
                $data = array(
                    'rowid' => $rowid,
                    'qty'   => $qty
                );
                $update = $this->cart->update($data);
            }
            
            // Return response
            echo $update?'ok':'err';
        }
    
        public function remove_cart($rowid){
          
          if($rowid=="all"){
             $this->cart->destroy();
          }else{
            $data = array(
              'rowid'   => $rowid,
              'qty'     => 0
            );
    
            $this->cart->update($data);
          }
          redirect('staff/view_cart');
        }
    
        public function clear_cart($company){
          $this->cart->destroy();
          redirect('staff/view_cart/'.$company);
        }
    
        public function update_cart($company){
          foreach($_POST['cart'] as $id => $cart){
           $price = $cart['price'];
           $amount = $price * $cart['qty'];
           $this->Data_model->update_cart($cart['rowid'], $cart['qty'], $price, $amount);
        }
    
          redirect('staff/view_cart/'.$company);
        }
        
        public function checkout($company){
          $email = $this->session->userdata('uemail');
          $user_company = $this->session->userdata('ucompany');
          $user_staff_code = $this->session->userdata('ustaff_code');
          $role = $this->session->userdata('urole');
          
          $data['staff_detail'] = $this->Data_model->display_my_staff_account($email);
		  $data['vouchers'] = $this->Data_model->display_my_staff_vouchers($email);
          $data['schedule'] = $this->Data_model->display_schedule_date($email);
          
          if(!$this->cart->contents()){
			$data['message'] = '<p><div class="alert alert-danger" role="alert">Your cart is empty!</div></p>';
		  }else{
			$data['message'] = $this->session->flashdata('message');
		  }
		  
		  if($role == "Staff" && $user_company == $company){
		    $this->load->view('staff/shopping/view_checkout', $data);
          }else{
            redirect('home');
          }
        }
        
        public function place_order($company){
          $email = $this->session->userdata('uemail');
          $user_company = $this->session->userdata('ucompany');
          $user_staff_code = $this->session->userdata('ustaff_code');
          
          $role = $this->session->userdata('urole');
          
          //$submit_btn = $this->input->post('order'); 
          
          //if(isset($submit_btn)){
            $voucher_id = $this->input->post('voucher_id');
            $voucher_code = $this->input->post('voucher_code');
            
            $shuffle = str_shuffle("ABCDTUVXY");
            $unique = rand(000, 999);
            $order_code = $shuffle.$unique;
            $firstname = $this->input->post('firstname');
            $lastname = $this->input->post('lastname');
            $telephone = $this->input->post('telephone');
            $postcode = $this->input->post('postcode');
            $town = $this->input->post('town');
            $address = $this->input->post('address');
            $order_notes = $this->input->post('order_notes');

            if($cart = $this->cart->contents()):
    			foreach ($cart as $item):
    			    if(!empty($voucher_code)){
                        $order_array = array(
                            'order_id' => $order_code,
                            'voucher_code' => $voucher_code,
                            'email' => $email,
                            'title' => $item['name'],
                			'price' => $item['price'],
                            'quantity' => $item['qty'],
                            'image' => $item['image'],
                            'order_notes' => $order_notes,
                            'status' => 'Delivering',
                            'created_time' => time(),
                            'created_date'  => date('Y-m-j H:i:s')
                        );
    			    }else{
    			       $order_array = array(
                            'order_id' => $order_code,
                            'voucher_code' => "none",
                            'email' => $email,
                            'title' => $item['name'],
                			'price' => $item['price'],
                            'quantity' => $item['qty'],
                            'image' => $item['image'],
                            'order_notes' => $order_notes,
                            'status' => 'Pending',
                            'created_time' => time(),
                            'created_date'  => date('Y-m-j H:i:s')
                        ); 
    			    }
                    
                    $order_details_array = array(
                        'order_id' => $order_code,
                        'staff_code' => $user_staff_code,
                        'company' => $user_company,
                        'firstname' => $firstname,
                        'lastname' => $lastname,
                        'telephone' => $telephone,
                        'address' => $address,
                        'postcode' => $postcode,
                        'town' => $town
                    );
                    
                $order_items = $this->Data_model->insert_order_items($order_array);

                endforeach;
    		endif;
    		
    		$order_details = $this->Data_model->insert_order_details($order_details_array);
            
            $update_voucher = $this->Data_model->update_staff_voucher($voucher_code);
            $remove_voucher = $this->Data_model->remove_staff_voucher($voucher_id);
            
            $update_meal_voucher = $this->Data_model->update_staff_meal_voucher($voucher_code);
            $remove_meal_voucher = $this->Data_model->remove_staff_meal_voucher($voucher_id);
            
            //$this->cart->destroy();
            
            //if($order_items && $order_details){ ?>
                <script>
                    //alert('Order Successfully');
                    window.location.href="<?php echo site_url('staff/make_order_payment/'.$company); ?>";
                </script>
      <?php /*}else{ ?>
               <script>
                    alert('Order Failed');
                    window.location.href="<?php echo site_url('staff/my_account/'.$company); ?>";
                </script> 
      <?php }*/
          //} 
        }
        
        public function my_account($company){
          $login = $this->session->userdata('login');
          $email = $this->session->userdata('uemail');
          $user_company = $this->session->userdata('ucompany');
          $role = $this->session->userdata('urole');
          
          if(!$this->cart->contents()){
			$data['message'] = '<p><div class="alert alert-danger" role="alert">Your cart is empty!</div></p>';
		  }else{
			$data['message'] = $this->session->flashdata('message');
		  }
		  
		  $data['staff_detail'] = $this->Data_model->display_my_staff_account($email);
		  $data['order_items'] = $this->Data_model->display_my_staff_order_items($email);
		  $data['wishlist'] = $this->Data_model->display_wishlist($email);
		  $data['meal_vouchers'] = $this->Data_model->display_my_staff_meal_vouchers($email);
		  $data['vouchers'] = $this->Data_model->display_my_staff_vouchers($email);
		  $data['menu'] = $this->Data_model->display_menu_options();
          $data['schedule'] = $this->Data_model->display_schedule_date($email);
		  
		  if($role == "Staff" && $user_company == $company){
		    
		    $this->form_validation->set_rules('firstname', 'First Name', 'trim|required');
            $this->form_validation->set_rules('lastname', 'Last Name', 'trim|required');
            $this->form_validation->set_rules('postcode', 'Post Code', 'trim|required');
            $this->form_validation->set_rules('town', 'Town', 'trim|required');
          
            $form_valid = $this->form_validation->run();
            $submit_btn = $this->input->post('update');

            if($form_valid == FALSE){
                $this->load->view('staff/shopping/view_account', $data);
            }
            
            if(isset($submit_btn)){
                $firstname = $this->input->post('firstname');
                $lastname = $this->input->post('lastname');
                $telephone = $this->input->post('telephone');

                $update_array = array(
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'telephone' => $telephone
                );
                
                $update_staff = $this->Data_model->update_my_staff_details($update_array);
                
                if($update_staff){ ?>
                    <script>
                        alert('Account Updated Successfully');
                        window.location.href="<?php echo site_url('staff/my_account/'.$company); ?>";
                    </script>
          <?php }else{ ?>
                   <script>
                        alert('Update Failed');
                        window.location.href="<?php echo site_url('staff/my_account/'.$company); ?>";
                    </script> 
          <?php }
            }    
		      
          }else if(empty($login)){
            $this->load->view('staff/shopping/view_account', $data);
          }else{
            redirect('home');
          } 
        }
        
        public function cancel_order(){
          $id = $this->input->post('ord_id');
          $this->Data_model->cancel_order($id);  
        }
        
        // End of Shopping 
        
        // Payment 
        
        public function make_order_payment($company){
           $email = $this->session->userdata('uemail');
           $role = $this->session->userdata('urole');
          
           if(!$this->cart->contents()){
			 $data['message'] = '<p><div class="alert alert-danger" role="alert">Your cart is empty!</div></p>';
		   }else{
			 $data['message'] = $this->session->flashdata('message');
		   }
		   
		   $query = $this->db->query("SELECT company FROM staff WHERE email = '$email' ")->result();
           foreach($query as $qry){
              $user_company = $qry->company;
           }
          
           $data['menu'] = $this->Data_model->display_menu_options();
           $data['schedule'] = $this->Data_model->display_schedule_date($email);

           if($role == "Staff" && $user_company == $company){
            $this->load->view('staff/shopping/payment', $data);
           }else{
            redirect('home');
          } 
        }
        
        public function stripe_order_post($company){
            require_once('application/libraries/stripe-php/init.php');
            
            $email = $this->session->userdata('uemail');
            
            //$cart = $this->cart->contents();
    		//foreach ($cart as $item){}
    		
    		$cart_total = $this->cart->total();
            
            \Stripe\Stripe::setApiKey($this->config->item('stripe_secret'));
            \Stripe\Charge::create ([
                "amount" => $cart_total * 100,
                "currency" => "gbp",
                "source" => $this->input->post('stripeToken'),
                "description" => "Test payment from Fast Food" 
            ]);
            
             $array = array(
                'delivery_date' => "",
                'delivery_day' => "",
                'num_time' => "",
                'postcode' => "",
            );
            
            $this->cart->destroy();
            $this->Data_model->update_user_scheduler($array, $email);
            
            //$this->session->set_flashdata('success', 'Payment made successfully.');
            ?>
            
            <script>
                alert('Order was successful');
                window.location.href="<?php echo site_url('staff/my_account/'.$company); ?>";
            </script>
    <?php }
        
        // End of Payment 
        
        // Voucher 
        
        public function voucher($company){
           $email = $this->session->userdata('uemail');
           $role = $this->session->userdata('urole');
          
           if(!$this->cart->contents()){
			 $data['message'] = '<p><div class="alert alert-danger" role="alert">Your cart is empty!</div></p>';
		   }else{
			 $data['message'] = $this->session->flashdata('message');
		   }
		   
		   $query = $this->db->query("SELECT company FROM staff WHERE email = '$email' ")->result();
           foreach($query as $qry){
              $user_company = $qry->company;
           }
          
           $data['menu'] = $this->Data_model->display_menu_options();
           $data['voucher'] = $this->Data_model->display_all_vouchers();    
           $data['schedule'] = $this->Data_model->display_schedule_date($email);

           if($role == "Staff" && $user_company == $company){
            $this->load->view('staff/voucher/view', $data);
           }else{
            redirect('home');
          } 
        }
        
        public function meal_voucher($company){
           $email = $this->session->userdata('uemail');
           $role = $this->session->userdata('urole');
          
           if(!$this->cart->contents()){
			 $data['message'] = '<p><div class="alert alert-danger" role="alert">Your cart is empty!</div></p>';
		   }else{
			 $data['message'] = $this->session->flashdata('message');
		   }
		   
		   $query = $this->db->query("SELECT company FROM staff WHERE email = '$email' ")->result();
           foreach($query as $qry){
              $user_company = $qry->company;
           }
          
           $data['menu'] = $this->Data_model->display_menu_options();
           $data['meal_voucher'] = $this->Data_model->display_all_meal_vouchers();    
           $data['schedule'] = $this->Data_model->display_schedule_date($email);

           if($role == "Staff" && $user_company == $company){
            $this->load->view('staff/meal_voucher/view', $data);
           }else{
            redirect('home');
          } 
        }
        
        public function make_payment($company, $id){
           $email = $this->session->userdata('uemail');
           $role = $this->session->userdata('urole');
          
           if(!$this->cart->contents()){
			 $data['message'] = '<p><div class="alert alert-danger" role="alert">Your cart is empty!</div></p>';
		   }else{
			 $data['message'] = $this->session->flashdata('message');
		   }
		   
		   $query = $this->db->query("SELECT company FROM staff WHERE email = '$email' ")->result();
           foreach($query as $qry){
              $user_company = $qry->company;
           }
          
           $data['menu'] = $this->Data_model->display_menu_options();
           $data['voucher'] = $this->Data_model->display_vouchers_by_id($company, $id);    
           $data['schedule'] = $this->Data_model->display_schedule_date($email);

           if($role == "Staff" && $user_company == $company){
            $this->load->view('staff/payment/view', $data);
           }else{
            redirect('home');
          } 
        }
        
        public function make_meal_payment($company, $id){
           $email = $this->session->userdata('uemail');
           $role = $this->session->userdata('urole');
          
           if(!$this->cart->contents()){
			 $data['message'] = '<p><div class="alert alert-danger" role="alert">Your cart is empty!</div></p>';
		   }else{
			 $data['message'] = $this->session->flashdata('message');
		   }
		   
		   $query = $this->db->query("SELECT company FROM staff WHERE email = '$email' ")->result();
           foreach($query as $qry){
              $user_company = $qry->company;
           }
          
           $data['menu'] = $this->Data_model->display_menu_options();
           $data['meal_voucher'] = $this->Data_model->display_meal_vouchers_by_id($company, $id);    
           $data['schedule'] = $this->Data_model->display_schedule_date($email);

           if($role == "Staff" && $user_company == $company){
            $this->load->view('staff/payment/meal_view', $data);
           }else{
            redirect('home');
          } 
        }
        
        public function stripe_post($company, $id){
            require_once('application/libraries/stripe-php/init.php');
            
            $query = $this->db->query("SELECT price FROM vouchers WHERE id = '$id' ")->result();
            foreach($query as $qry){
                $amount = $qry->price; 
            }
            
            $code = $this->input->post('code');
            $email = $this->input->post('email');
            $title = $this->input->post('title');
            $description = $this->input->post('description');
            $discount = $this->input->post('discount');
            $company = $this->input->post('company');
            $type = $this->input->post('type');
            $price = $this->input->post('price');
            $quantity = $this->input->post('quantity');
            
            $time = time();
            $date = date('Y-m-d H:i:s');
            
            $voucher_array = array(
                'code' => $code,
                'email' => $email,
                'title' => $title,
                'description' => $description,
                'discount' => $discount,
                'company' => $company,
                'type' => $type,
                'price' => $price,
                'quantity' => $quantity,
                'created_time' => $time,
                'created_date' => $date
            );
            
            $add_voucher = $this->Data_model->add_voucher_details($voucher_array);
            
            \Stripe\Stripe::setApiKey($this->config->item('stripe_secret'));
            \Stripe\Charge::create ([
                "amount" => $amount * 100,
                "currency" => "gbp",
                "source" => $this->input->post('stripeToken'),
                "description" => "Test payment from Fast Food" 
            ]);
            
            //$this->session->set_flashdata('success', 'Payment made successfully.');
            ?>
            
            <script>
                alert('Payment made successfully');
                window.location.href="<?php echo site_url('staff/my_account/'.$company); ?>";
            </script>
    <?php }
    
        public function stripe_meal_post($company, $id){
            require_once('application/libraries/stripe-php/init.php');
            
            $query = $this->db->query("SELECT price FROM meal_vouchers WHERE id = '$id' ")->result();
            foreach($query as $qry){
                $amount = $qry->price; 
            }
            
            $code = $this->input->post('code');
            $email = $this->input->post('email');
            $title = $this->input->post('title');
            $description = $this->input->post('description');
            $discount = $this->input->post('discount');
            $company = $this->input->post('company');
            $type = $this->input->post('type');
            $category = $this->input->post('category');
            $price = $this->input->post('price');
            $quantity = $this->input->post('quantity');
            
            $time = time();
            $date = date('Y-m-d H:i:s');
            
            $voucher_array = array(
                'code' => $code,
                'email' => $email,
                'title' => $title,
                'description' => $description,
                'discount' => $discount,
                'company' => $company,
                'type' => $type,
                'category' => $category,
                'price' => $price,
                'quantity' => $quantity,
                'created_time' => $time,
                'created_date' => $date
            );
            
            $add_voucher = $this->Data_model->add_meal_voucher_details($voucher_array);
            
            \Stripe\Stripe::setApiKey($this->config->item('stripe_secret'));
            \Stripe\Charge::create ([
                "amount" => $amount * 100,
                "currency" => "gbp",
                "source" => $this->input->post('stripeToken'),
                "description" => "Test payment from Fast Food" 
            ]);
            
            //$this->session->set_flashdata('success', 'Payment made successfully.');
            ?>
            
            <script>
                alert('Payment made successfully');
                window.location.href="<?php echo site_url('staff/my_account/'.$company); ?>";
            </script>
    <?php }
        
        public function use_voucher($company){
            $voucher = $this->input->post('voucher');
            $session_email = $this->session->userdata('uemail');
            
            $btn_submit = $this->input->post('submit');
            
            if(isset($btn_submit)){
                $query = $this->db->query("SELECT * FROM vouchers WHERE title = '$voucher' ")->result();
                foreach($query as $qry){
                    $voucher_code = $qry->code;
                    $voucher_title = $qry->title;
                    $voucher_company = $qry->company;
                    $voucher_price = $qry->price;
                    $voucher_type = $qry->type;
                    $voucher_discount = $qry->discount;
                    $voucher_quantity = $qry->quantity;
                }
                
                $voucher_array = array(
                    'email' => $session_email,
                    'code' => $voucher_code,
                    'title' => $voucher,
                    'price' => $voucher_price,
                    'company' => $voucher_company,
                    'type' => $voucher_type,
                    'discount' => $voucher_discount,
                    'quantity' => $voucher_quantity
                );
                
                $apply_voucher = $this->Data_model->add_temp_vouchers($voucher_array);
                
                if($apply_voucher){ ?>
                    <script>
                        alert('Applied Voucher');
                        window.location.href="<?php echo site_url('staff/view_cart/'.$company); ?>";
                    </script>
          <?php }else{ ?>
                  <script>
                    alert('Voucher was not added');
                    window.location.href="<?php echo site_url('staff/view_cart/'.$company); ?>";
                  </script>
         <?php }   
            }
        }
        
        public function use_meal_voucher($company){
            $voucher = $this->input->post('voucher');
            $session_email = $this->session->userdata('uemail');
            
            $btn_submit = $this->input->post('submit');
            
            $cart = $this->cart->contents();
            foreach($cart as $item){
                $item['category'];
            }
            
            if(isset($btn_submit)){
                $query = $this->db->query("SELECT * FROM meal_vouchers WHERE title = '$voucher' ")->result();
                foreach($query as $qry){
                    $voucher_code = $qry->code;
                    $voucher_title = $qry->title;
                    $voucher_company = $qry->company;
                    $voucher_price = $qry->price;
                    $voucher_type = $qry->type;
                    $voucher_category = $qry->category;
                    $voucher_discount = $qry->discount;
                    $voucher_quantity = $qry->quantity;
                }
                
                $voucher_array = array(
                    'email' => $session_email,
                    'code' => $voucher_code,
                    'title' => $voucher,
                    'price' => $voucher_price,
                    'company' => $voucher_company,
                    'type' => $voucher_type,
                    'category' => $voucher_category,
                    'discount' => $voucher_discount,
                    'quantity' => $voucher_quantity
                );
                
                $apply_voucher = $this->Data_model->add_temp_vouchers($voucher_array);
                
                if($apply_voucher && $voucher_category == $item['category']){ ?>
                    <script>
                        alert('Applied Voucher');
                        window.location.href="<?php echo site_url('staff/view_cart/'.$company); ?>";
                    </script>
          <?php }else{ ?>
                  <script>
                    alert('Voucher was not added');
                    window.location.href="<?php echo site_url('staff/view_cart/'.$company); ?>";
                  </script>
         <?php }   
            }
        }
        
        public function destroy_voucher(){
            $did = $this->input->post('del_id');
            $this->Data_model->remove_staff_voucher($did);
        }
        
        public function delete_order(){
            $did = $this->input->post('del_id');
            $this->Data_model->delete_order($did);
        }
        
        // End of Voucher 
        
        // Geolocation 
        
        function getDistance($addressFrom, $addressTo, $unit = ''){
            // Google API key
            $apiKey = 'AIzaSyALsc0dOYYaHaiXImBpuy09vWaMsu0zaxA';
            
            // Change address format
            $formattedAddrFrom    = str_replace(' ', '+', $addressFrom);
            $formattedAddrTo     = str_replace(' ', '+', $addressTo);
            
            // Geocoding API request with start address
            $geocodeFrom = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddrFrom.'&sensor=false&key='.$apiKey);
            $outputFrom = json_decode($geocodeFrom);
            if(!empty($outputFrom->error_message)){
                return $outputFrom->error_message;
            }
            
            // Geocoding API request with end address
            $geocodeTo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddrTo.'&sensor=false&key='.$apiKey);
            $outputTo = json_decode($geocodeTo);
            if(!empty($outputTo->error_message)){
                return $outputTo->error_message;
            }
            
            // Get latitude and longitude from the geodata
            $latitudeFrom    = $outputFrom->results[0]->geometry->location->lat;
            $longitudeFrom    = $outputFrom->results[0]->geometry->location->lng;
            $latitudeTo        = $outputTo->results[0]->geometry->location->lat;
            $longitudeTo    = $outputTo->results[0]->geometry->location->lng;
            
            // Calculate distance between latitude and longitude
            $theta    = $longitudeFrom - $longitudeTo;
            $dist    = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
            $dist    = acos($dist);
            $dist    = rad2deg($dist);
            $miles    = $dist * 60 * 1.1515;
            
            // Convert unit and return distance
            $unit = strtoupper($unit);
            if($unit == "K"){
                return round($miles * 1.609344, 2).' km';
            }elseif($unit == "M"){
                return round($miles * 1609.344, 2).' meters';
            }else{
                return round($miles, 2).' miles';
            }
        }
        
        // End of Geolocation 
    }

?>