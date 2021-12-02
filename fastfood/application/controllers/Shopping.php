<?php 

    class Shopping extends CI_Controller{
        
        public function view_cart(){
          $email = $this->session->userdata('uemail');
          $user_company = $this->session->userdata('ucompany');

          if(!$this->cart->contents()){
			$data['message'] = '<p><div class="alert alert-danger" role="alert">Your cart is empty!</div></p>';
		  }else{
			$data['message'] = $this->session->flashdata('message');
		  }
          
          $data['menu'] = $this->Data_model->display_menu_options();
          $data['offer_meal'] = $this->Data_model->display_meal_day();    
          $data['family_order'] = $this->Data_model->display_family_meal();
          $data['schedule'] = $this->Data_model->display_schedule_date($email);

          $this->load->view('site/shopping/view_cart', $data);
        }
        
        public function add_cart(){
          $insert_items = array(
            'id' => $this->input->post('id'),
            'name' => $this->input->post('title'),
            'price' => $this->input->post('price'),
            'qty' => 1,
            'code' => $this->input->post('code'),
            'category' => $this->input->post('category'),
            'image' => $this->input->post('image'),
            'side_meal' => $this->input->post('side_meal'),
            'side_drink' => $this->input->post('side_drink')
          );
    
         $this->cart->insert($insert_items);
         redirect('shopping/view_cart');
        }
        
        public function add_wishlist(){
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
             window.location.href="<?php echo site_url('shopping/my_account'); ?>";
         </script>
    <?php 
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
          redirect('shopping/view_cart');
        }
    
        public function clear(){
          $this->cart->destroy();
          redirect('shopping/view_cart');
        }
    
        public function update_cart(){
          foreach($_POST['cart'] as $id => $cart){
           $price = $cart['price'];
           $amount = $price * $cart['qty'];
           $this->Data_model->update_cart($cart['rowid'], $cart['qty'], $price, $amount);
        }
    
          redirect('shopping/view_cart');
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

          $data['users'] = $this->Data_model->display_my_account($email);
          $data['schedule'] = $this->Data_model->display_schedule_date($email);

          if(!$this->cart->contents()){
			$data['message'] = '<p><div class="alert alert-danger" role="alert">Your cart is empty!</div></p>';
		  }else{
			$data['message'] = $this->session->flashdata('message');
		  }
		  
		  $this->load->view('site/shopping/view_checkout', $data);
        }
        
        public function place_order(){
          $email = $this->session->userdata('uemail');

          $submit_btn = $this->input->post('order'); 
          
          $query_sch = $this->db->query("SELECT * FROM scheduler WHERE email = '$email' ")->result();
          foreach($query_sch as $qry){
              $qry_date = $qry->delivery_date;
          }
          
          if(empty($qry_date)){ ?>
                <script>
                    alert('Please Schedule a delivery');
                    window.location.href="<?php echo site_url('home'); ?>";
                </script>
      <?php }
      
      //if(isset($submit_btn)){
          
            $shuffle = str_shuffle("ABCDEFGH-TUVXY");
            $unique = rand(00110, 90099);
            $order_code = $shuffle.$unique;
            $firstname = $this->input->post('firstname');
            $lastname = $this->input->post('lastname');
            $telephone = $this->input->post('telephone');
            $postcode = $this->input->post('postcode');
            $town = $this->input->post('town');
            $address = $this->input->post('address');
            $order_notes = $this->input->post('order_notes');
            
            $delivery_category = $this->input->post('delivery_category');
            $delivery_date = $this->input->post('delivery_date');

            if($cart = $this->cart->contents()):
    			foreach ($cart as $item):
			       $order_array = array(
                        'order_id' => $order_code,
                        'email' => $email,
                        'title' => $item['name'],
            			'price' => $item['price'],
                        'quantity' => $item['qty'],
                        'image' => $item['image'],
                        'order_notes' => $order_notes,
                        'side_meal' => "none",
                        'side_drink' => "none",
                        'delivery_date' => $delivery_date,
                        'delivery_category' => $delivery_category,
                        'status' => 'Pending',
                        'created_time' => time(),
                        'created_date'  => date('Y-m-j H:i:s')
                    ); 
                    
                    if(!empty($item['side_meal'])){
                      $order_array = array(
                        'order_id' => $order_code,
                        'email' => $email,
                        'title' => $item['name'],
            			'price' => $item['price'],
                        'quantity' => $item['qty'],
                        'image' => $item['image'],
                        'order_notes' => $order_notes,
                        'side_meal' => $item['side_meal'],
                        'side_drink' => $item['side_drink'],
                        'delivery_date' => $delivery_date,
                        'delivery_category' => $delivery_category,
                        'status' => 'Pending',
                        'created_time' => time(),
                        'created_date'  => date('Y-m-j H:i:s')
                      );   
                    }
                    
                    if(!empty($item['side_drink'])){
                      $order_array = array(
                        'order_id' => $order_code,
                        'email' => $email,
                        'title' => $item['name'],
            			'price' => $item['price'],
                        'quantity' => $item['qty'],
                        'image' => $item['image'],
                        'order_notes' => $order_notes,
                        'side_meal' => $item['side_meal'],
                        'side_drink' => $item['side_drink'],
                        'delivery_date' => $delivery_date,
                        'delivery_category' => $delivery_category,
                        'status' => 'Pending',
                        'created_time' => time(),
                        'created_date'  => date('Y-m-j H:i:s')
                      );   
                    }
                    
                    if(!empty($item['side_meal']) && !empty($item['side_drink'])){
                       $order_array = array(
                        'order_id' => $order_code,
                        'email' => $email,
                        'title' => $item['name'],
            			'price' => $item['price'],
                        'quantity' => $item['qty'],
                        'image' => $item['image'],
                        'order_notes' => $order_notes,
                        'side_meal' => $item['side_meal'],
                        'side_drink' => $item['side_drink'],
                        'delivery_date' => $delivery_date,
                        'delivery_category' => $delivery_category,
                        'status' => 'Pending',
                        'created_time' => time(),
                        'created_date'  => date('Y-m-j H:i:s')
                      );  
                    }
                    
                    $order_details_array = array(
                        'order_id' => $order_code,
                        'company' => "none",
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
            //$this->cart->destroy();
            
            //if($order_items && $order_details){ ?>
                <script>
                    //alert('Order Successfully');
                    window.location.href="<?php echo site_url('payment/make_order_payment'); ?>";
                </script>
      <?php /*}else{ ?>
                <script>
                    alert('Order Failed');
                    window.location.href="<?php echo site_url('shopping/my_account'); ?>";
                </script> 
    <?php } */
        //}
      }
        
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
		    $data['wishlist'] = $this->Data_model->display_wishlist($email);
            $data['schedule'] = $this->Data_model->display_schedule_date($email);

		    $this->form_validation->set_rules('firstname', 'First Name', 'trim|required');
            $this->form_validation->set_rules('lastname', 'Last Name', 'trim|required');
            $this->form_validation->set_rules('postcode', 'Post Code', 'trim|required');
            $this->form_validation->set_rules('town', 'Town', 'trim|required');
          
            $form_valid = $this->form_validation->run();
            $submit_btn = $this->input->post('update');
            
            if(empty($email)){
                redirect('home');
            }

            if($form_valid == FALSE){
                $this->load->view('site/shopping/view_account', $data);
            }else{
                redirect('home');
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
                
                $update_user = $this->Data_model->update_my_details($update_array);
                
                if($update_user){ ?>
                    <script>
                        alert('Account Updated Successfully');
                        window.location.href="<?php echo site_url('shopping/my_account'); ?>";
                    </script>
          <?php }else{ ?>
                   <script>
                        alert('Update Failed');
                        window.location.href="<?php echo site_url('shopping/my_account'); ?>";
                    </script> 
          <?php }
            }    
		      
        }
        
        public function send_ticket(){
           $email = $this->session->userdata('uemail');
           
           $query = $this->db->query("SELECT firstname, lastname FROM users WHERE email = '$email' ")->result();
           foreach($query as $qry){}
           
           $order_id = $this->input->post('order_id');
           $order_title = $this->input->post('order_title');
           $subject = $this->input->post('subject');
           $body = $this->input->post('body');
           
           $time = time();
           $date = date('Y-m-d H:i:s');
           
           $order_query = $this->db->query("SELECT status FROM order_items WHERE order_id = '$order_id' ")->result();
           foreach($order_query as $ord_qry){}
           
           $ticket_array = array(
                'order_id' => $order_id,
                'firstname' => $qry->firstname,
                'lastname' => $qry->lastname,
                'email' => $email,
                'order_title' => $order_title,
                'subject' => $subject,
                'body' => $body,
                'status' => 'Pending',
                'created_time' => $time,
                'created_date' => $date
           );
           
           $add_ticket = $this->Data_model->insert_message($ticket_array);
           
           if($add_ticket && $ord_qry->status == "Pending"){ ?>
             <script>
                alert('Message Sent');
                window.location.href="<?php echo site_url('shopping/my_account'); ?>";
             </script>
     <?php }else{ ?>
             <script>
                alert('Order has already been delivered');
                window.location.href="<?php echo site_url('shopping/my_account'); ?>";
             </script>  
     <?php }
        }
        
        public function cancel_order(){
          $id = $this->input->post('ord_id');
          $this->Data_model->cancel_order($id);  
        }
        
        public function delete_order(){
            $did = $this->input->post('del_id');
            $this->Data_model->delete_order($did);
        }
        
        public function delete_message(){
            $did = $this->input->post('del_id');
            $this->Data_model->delete_message($did);
        }
    }

?>