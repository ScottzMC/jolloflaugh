<?php 

    class Payment extends CI_Controller{
        
        public function view($company, $id){
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
        
        public function stripe_post($company){
            require_once('application/libraries/stripe-php/init.php');
            
            $amount = 100;
            
            \Stripe\Stripe::setApiKey($this->config->item('stripe_secret'));
            \Stripe\Charge::create ([
                "amount" => $amount * 100,
                "currency" => "gbp",
                "source" => $this->input->post('stripeToken'),
                "description" => "Test payment from Fast Food" 
            ]);

            $this->session->set_flashdata('success', 'Payment made successfully.');
    
            redirect('payment/view/'.$company);
        }
        
        // Payment for Shopping 
        
        public function make_order_payment(){
           $email = $this->session->userdata('uemail');
           $role = $this->session->userdata('urole');
          
           if(!$this->cart->contents()){
			 $data['message'] = '<p><div class="alert alert-danger" role="alert">Your cart is empty!</div></p>';
		   }else{
			 $data['message'] = $this->session->flashdata('message');
		   }
          
           $data['menu'] = $this->Data_model->display_menu_options();
           $data['schedule'] = $this->Data_model->display_schedule_date($email);

           $this->load->view('site/shopping/payment', $data);

        }
        
        public function stripe_order_post(){
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
                window.location.href="<?php echo site_url('shopping/my_account'); ?>";
            </script>
    <?php }
        
        // End of Payment for Shopping 
    }

?>