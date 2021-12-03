<?php 
    
    class Jollof_n_laugh extends CI_Controller{
        
        // Home
        
        public function index($status = null){
            
          if(!$this->cart->contents()){
    		$data['message'] = '<p><div class="alert alert-danger" role="alert">Your cart is empty!</div></p>';
    	  }else{
    		$data['message'] = $this->session->flashdata('message');
    	  }
		
	  if(empty($status)){
	    $data['status'] = '<p><div class="alert alert-success" role="alert">Added to cart</div></p>';
	  }else{
	    $data['status'] = $this->session->flashdata('message');
	  }
    	  
    	  $email = $this->session->userdata('uemail');
    	  
          $data['menu'] = $this->Jollof_n_laugh_model->display_menu_options();
          
          $data['rice'] = $this->Jollof_n_laugh_model->display_meal_for_rice();    
          $data['stews'] = $this->Jollof_n_laugh_model->display_meal_for_stew();  
          $data['vegan'] = $this->Jollof_n_laugh_model->display_meal_for_vegan();  
          $data['side'] = $this->Jollof_n_laugh_model->display_meal_for_side();  
          $data['dessert'] = $this->Jollof_n_laugh_model->display_meal_for_dessert();  
          
          $data['slider'] = $this->Jollof_n_laugh_model->display_slider_by_home("jollof_n_laugh");

          $this->load->view('jollof_n_laugh/view', $data);
        }
        
        // End of Home
        
        // Account 
        
        public function login(){
            $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
          
            $form_valid = $this->form_validation->run();
            $submit_btn = $this->input->post('login');
            
            if($form_valid == FALSE){
                $data['menu'] = $this->Jollof_n_laugh_model->display_menu_options();
                
                $this->load->view('jollof_n_laugh/account/login', $data);
            }
            
            if(isset($submit_btn)){
                $email = $this->input->post('email');
                $password = $this->input->post('password');
                
                $query = $this->db->query("SELECT email, status FROM users WHERE email = '$email'")->result();
                foreach($query as $qry){
                    $query_email = $qry->email;
                    $query_status = $qry->status;
                }
                
                $uresult = $this->Jollof_n_laugh_model->validate($email, $password);
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
                      window.location.href="<?php echo site_url('jollof_n_laugh'); ?>";
                  </script> 
                  <?php
                  /*if(isset($_SERVER['HTTP_REFERER'])){
                    redirect($_SERVER['HTTP_REFERER']);
                  }*/
              }else if(empty($query_email) || empty($query_status) || $query_status == "Deactivated" || $query_status == "Blocked"){
                $statusMsg = '<span class="text-danger">Email needs to be activated!</span>';
                $this->session->set_flashdata('msgError', $statusMsg);
                
                $data['menu'] = $this->Jollof_n_laugh_model->display_menu_options();
                $this->load->view('jollof_n_laugh/account/login', $data);  
              }else{
                $statusMsg = '<span class="text-danger">Wrong Email-ID or Password!</span>';
                $this->session->set_flashdata('msgError', $statusMsg);
                
                $data['menu'] = $this->Jollof_n_laugh_model->display_menu_options();
                $this->load->view('jollof_n_laugh/account/login', $data);
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
                $data['menu'] = $this->Jollof_n_laugh_model->display_menu_options();

                $this->load->view('jollof_n_laugh/account/register', $data);
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
                
                $add_user = $this->Jollof_n_laugh_model->create_user($register_array);

                if($add_user && $this->email->send()){ ?>
                    <script>
                        alert('Account has been created successfully. Please Activate your Account in your Inbox');
                        window.location.href="<?php echo site_url('jollof_n_laugh/login'); ?>";
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
          redirect('jollof_n_laugh');
        }
        
        public function activate_user($code){
            //$code = $_GET['code'];
            $this->Jollof_n_laugh_model->activate_user($code); ?>
            <script>
                alert('Activated Successfully');
                window.location.href="<?php echo site_url('jollof_n_laugh'); ?>";
            </script>
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
                window.location.href="<?php echo base_url('jollof_n_laugh/login'); ?>";
              </script>    
            <?php }else{ ?>
                <script>
                    alert("Email does not exist ");
                    window.location.href="<?php echo site_url('jollof_n_laugh/login'); ?>";
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
          
          $this->load->view('jollof_n_laugh/account/reset', $data);
          
          if(isset($submit)){
            if(!empty($query_email) && $query_email == $email){
            $password = $this->input->post('password');
            $hashed_password = $this->bcrypt->hash_password($password);
            
            $update_detail = $this->Jollof_n_laugh_model->update_user_password($query_email, $hashed_password);
    
            if($update_detail){ ?>
              <script>
                alert('Account updated successfully');
                window.location.href="<?php echo base_url('jollof_n_laugh/login'); ?>";
              </script> 
      <?php }else{ ?>
              <script>
                alert('Reset Password Failed');
                window.location.href="<?php echo base_url('jollof_n_laugh/login'); ?>";
              </script> 
        <?php }
           }else{ ?>
              <script>
                alert("Email does not exist");
                window.location.href="<?php echo site_url('jollof_n_laugh/login'); ?>";
              </script>
           <?php }
          }
         }
        
        // End of Account 
        
        // User Profile 
        
        public function my_account(){
            $login = $this->session->userdata('login');
            $email = $this->session->userdata('uemail');

            if(!$this->cart->contents()){
			    $data['message'] = '<p><div class="alert alert-danger" role="alert">Your cart is empty!</div></p>';
		    }else{
		    	$data['message'] = $this->session->flashdata('message');
		    }
		  
		    $data['users'] = $this->Data_model->display_my_account($email);
		    $data['order_items'] = $this->Data_model->display_my_order_items($email);
		    $data['menu'] = $this->Data_model->display_menu_options();
		    $data['message'] = $this->Data_model->display_messages($email);

		    $this->form_validation->set_rules('firstname', 'First Name', 'trim|required');
            $this->form_validation->set_rules('lastname', 'Last Name', 'trim|required');
            $this->form_validation->set_rules('postcode', 'Post Code', 'trim|required');
            $this->form_validation->set_rules('town', 'Town', 'trim|required');
          
            $form_valid = $this->form_validation->run();
            $submit_btn = $this->input->post('update');
            
            if(empty($email)){
                redirect('jollof_n_laugh');
            }

            if($form_valid == FALSE){
                $this->load->view('jollof_n_laugh/shopping/view_account', $data);
            }else{
                redirect('jollof_n_laugh');
            }
            
            if(isset($submit_btn)){
                $firstname = $this->input->post('firstname');
                $lastname = $this->input->post('lastname');
                $telephone = $this->input->post('telephone');
                $address = $this->input->post('address');
                $postcode = $this->input->post('postcode');
                $town = $this->input->post('town');

                $update_array = array(
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'telephone' => $telephone,
                    'address' => $address,
                    'postcode' => $postcode,
                    'town' => $town,
                );
                
                $update_user = $this->Jollof_n_laugh_model->update_my_details($update_array);
                
                if($update_user){ ?>
                    <script>
                        alert('Account Updated Successfully');
                        window.location.href="<?php echo site_url('my_account'); ?>";
                    </script>
          <?php }else{ ?>
                   <script>
                        alert('Update Failed');
                        window.location.href="<?php echo site_url('my_account'); ?>";
                    </script> 
          <?php }
            }    
		      
        }
        
        // End of User Profile 
        
        // Food 
        
        public function detail($id, $title){
            
            if(!$this->cart->contents()){
    		    $data['message'] = '<p><div class="alert alert-danger" role="alert">Your cart is empty!</div></p>';
    	    }else{
    		    $data['message'] = $this->session->flashdata('message');
    	    }
    	    
    	    $email = $this->session->userdata('uemail');
    	  
            $data['detail'] = $this->Jollof_n_laugh_model->display_food_by_id($id);

            $this->load->view('jollof_n_laugh/food/detail', $data);
        }
        
        // End of Food
        
        // Shopping
        
        public function view_cart(){
          $email = $this->session->userdata('uemail');
          $user_company = $this->session->userdata('ucompany');

          if(!$this->cart->contents()){
			$data['message'] = '<p><div class="alert alert-danger" role="alert">Your cart is empty!</div></p>';
		  }else{
			$data['message'] = $this->session->flashdata('message');
		  }
          
          $data['menu'] = $this->Jollof_n_laugh_model->display_menu_options();

          $this->load->view('jollof_n_laugh/shopping/view_cart', $data);
        }
        
        public function add_cart(){
          $insert_items = array(
            'id' => $this->input->post('id'),
            'name' => $this->input->post('title'),
            'price' => $this->input->post('price'),
            'qty' => 1,
            'code' => $this->input->post('code'),
            'category' => $this->input->post('category'),
            'image' => $this->input->post('image')
          );
	 
	 $status = "Success";
    
         $this->cart->insert($insert_items);
	 redirect('jollof_n_laugh/'.$status);
	 /*if(!$this->cart->contents()){
    		$data['message'] = '<p><div class="alert alert-danger" role="alert">Your cart is empty!</div></p>';
    	  }else{
    		$data['message'] = $this->session->flashdata('message');
    	  }
    	  
    	  $email = $this->session->userdata('uemail');
    	  
          $data['menu'] = $this->Jollof_n_laugh_model->display_menu_options();
          
          $data['rice'] = $this->Jollof_n_laugh_model->display_meal_for_rice();    
          $data['stews'] = $this->Jollof_n_laugh_model->display_meal_for_stew();  
          $data['vegan'] = $this->Jollof_n_laugh_model->display_meal_for_vegan();  
          $data['side'] = $this->Jollof_n_laugh_model->display_meal_for_side();  
          $data['dessert'] = $this->Jollof_n_laugh_model->display_meal_for_dessert();  
          
          $data['slider'] = $this->Jollof_n_laugh_model->display_slider_by_home("jollof_n_laugh");

          $this->load->view('jollof_n_laugh/view', $data);*/
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
          redirect('jollof_n_laugh');
        }
    
        public function clear(){
          $this->cart->destroy();
          redirect('jollof_n_laugh');
        }
    
        public function update_cart(){
          foreach($_POST['cart'] as $id => $cart){
           $price = $cart['price'];
           $amount = $price * $cart['qty'];
           $this->Jollof_n_laugh_model->update_cart($cart['rowid'], $cart['qty'], $price, $amount);
        }
    
          redirect('jollof_n_laugh/view_cart');
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
        
        public function checkout(){
          $email = $this->session->userdata('uemail');

          //$data['users'] = $this->Jollof_n_laugh_model->display_my_account($email);


		  
		  $this->load->view('jollof_n_laugh/shopping/view_checkout', $data);
        }
        
        public function place_order(){
          $email = $this->session->userdata('uemail');

          $submit_btn = $this->input->post('order'); 
      
      //if(isset($submit_btn)){
          
            $shuffle = str_shuffle("ABCDEFGH-TUVXY");
            $unique = rand(00110, 90099);
            $order_code = $shuffle.$unique;
            $firstname = $this->input->post('firstname');
            $email = $this->input->post('email');
            $lastname = $this->input->post('lastname');
            $seat_type = $this->input->post('seat_type');
            $order_notes = $this->input->post('order_notes');

            if($cart = $this->cart->contents()):
    			foreach ($cart as $item):
			       $order_array = array(
                        'order_id' => $order_code,
                        'firstname' => $firstname,
                        'lastname' => $lastname,
                        'email' => $email,
                        'title' => $item['name'],
            			'price' => $item['price'],
                        'quantity' => $item['qty'],
                        'image' => $item['image'],
                        'order_notes' => $order_notes,
                        'side_meal' => "none",
                        'side_drink' => "none",
                        'status' => 'Pending',
                        'seat_type' => $seat_type,
                        'created_time' => time(),
                        'created_date'  => date('Y-m-j H:i:s')
                    );
                    
                $order_items = $this->Jollof_n_laugh_model->insert_order_items($order_array);

                endforeach;
    		endif;
            
            $order_details = $this->Jollof_n_laugh_model->insert_order_details($order_details_array);
            //$this->cart->destroy();
            
            if($order_items){ ?>
                <script>
                    //alert('Order Successfully');
                    window.location.href="<?php echo site_url('jollof_n_laugh/make_order_payment/'.$email.'/'.strtolower($order_code)); ?>";
                    //window.location.href="<?php echo site_url('jollof_n_laugh'); ?>";
                </script>
      <?php }else{ ?>
                <script>
                    alert('Could not process order');
                    window.location.href="<?php echo site_url('jollof_n_laugh'); ?>";
                </script> 
    <?php } 
        //}
      }
      
      public function make_order_payment($email, $order_code){

           if(!$this->cart->contents()){
			 $data['message'] = '<p><div class="alert alert-danger" role="alert">Your cart is empty!</div></p>';
		   }else{
			 $data['message'] = $this->session->flashdata('message');
		   }
          
           $data['menu'] = $this->Jollof_n_laugh_model->display_menu_options();
           $data['order_item'] = $this->Jollof_n_laugh_model->display_all_order_by_email($email, $order_code);

           $this->load->view('jollof_n_laugh/shopping/payment', $data);

        }
        
        public function stripe_order_post(){
            require_once('application/libraries/stripe-php/init.php');
            
    		$cart_total = $this->cart->total();
            
            \Stripe\Stripe::setApiKey($this->config->item('stripe_secret'));
            \Stripe\Charge::create ([
                "amount" => $cart_total * 100,
                "currency" => "gbp",
                "source" => $this->input->post('stripeToken'),
                "description" => "Test payment from Fast Food" 
            ]);
            
             $order_id = $this->input->post('order_id');
             $title = $this->input->post('title');
             $price = $this->input->post('price');
             $quantity = $this->input->post('quantity'); 
             $email = $this->input->post('customer_email');

             $subject = "Order Notification";
             $order_item = $this->Jollof_n_laugh_model->display_all_order_by_email($customer_email, $order_id);
             
             if($cart = $this->cart->contents()):
    			foreach ($cart as $item):
    		 $title = $item['name'];
    		 $price = $item['price'];
    		 $qty = $item['qty'];

             $body = "
                Dear Customer, please find your ordered products and the current order status
                Order Code - $order_id,
                Order Title - $title,
                Order Price - £$price,
                Order Quantity - $qty,
                Order Status - Pending
              ";
            //}
        
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
             $this->email->send();
            
                endforeach;
    		endif;
            
             $this->cart->destroy();

            //$this->session->set_flashdata('success', 'Payment made successfully.');
            ?>
            
            <script>
                //alert('Order was successful');
                window.location.href="<?php echo site_url('jollof_n_laugh/success'); ?>";
            </script>
    <?php }
    
        public function success(){
            if(!$this->cart->contents()){
			 $data['message'] = '<p><div class="alert alert-danger" role="alert">Your cart is empty!</div></p>';
		   }else{
			 $data['message'] = $this->session->flashdata('message');
		   }
          
           $data['menu'] = $this->Jollof_n_laugh_model->display_menu_options();
           
           $this->load->view('jollof_n_laugh/shopping/success', $data);
        }
        
        public function cancel_order(){
          $id = $this->input->post('ord_id');
          $this->Jollof_n_laugh_model->cancel_order($id);  
        }
        
        public function delete_order(){
            $did = $this->input->post('del_id');
            $this->Jollof_n_laugh_model->delete_order($did);
        }
        
        public function delete_message(){
            $did = $this->input->post('del_id');
            $this->Jollof_n_laugh_model->delete_message($did);
        }
        
        // End of Shopping 
    }
    
?>
