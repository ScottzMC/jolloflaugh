<?php 

    class Kitchen extends CI_Controller{
        
        public function dashboard(){
          $email = $this->session->userdata('uemail');
          $role = $this->session->userdata('urole');
    
          if($role == "Kitchen"){
            $data['total_user_count'] = $this->Admin_model->display_user_count();
            $data['total_order_count'] = $this->Admin_model->display_order_count();
            $data['total_food_count'] = $this->Admin_model->display_food_count();
            $data['user_status'] = $this->Admin_model->display_all_users();
            $data['food'] = $this->Admin_model->display_all_food();
            $data['message'] = $this->Admin_model->display_all_message();

            $data['pending'] = $this->Admin_model->display_all_pending_orders();
            $data['delivering'] = $this->Admin_model->display_all_delivering_orders();
            $data['delivered'] = $this->Admin_model->display_all_delivered_orders();
    
            $this->load->view('kitchen/menu/nav');
            $this->load->view('kitchen/dashboard', $data);
            $this->load->view('kitchen/menu/footer');
          }else{
            redirect('home');
          }
        }
        
        // Message
    
    public function message_grid(){
      $email = $this->session->userdata('uemail');
      $role = $this->session->userdata('urole');
      
      $order_id = $this->input->post('order_id');
      $email = $this->input->post('email');

      if($role == "Kitchen"){
        $data['total_order_count'] = $this->Admin_model->display_order_count();
        $data['message'] = $this->Admin_model->display_message_grid();
        
        $btn_yes = $this->input->post('yes');
        $btn_no = $this->input->post('no');

        if(isset($btn_yes)){
          $this->Admin_model->complete_message($id); 
          
          $subject = "Order Notification";
          $body = "
            Dear Customer, for your Order ID - $order_id, we have made the necessary changes to the order upon delivery.
            ";
    
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
          
        ?>
          
          <script>
            alert('Message is sent');
            window.location.href="<?php echo site_url('kitchen/dashboard'); ?>";
          </script> 
  <?php }
  
        if(isset($btn_no)){
          $this->Admin_model->reject_message($id); 
          
          $subject = "Order Notification";
          $body = "
            Dear Customer, unfortunately we are unable to make changes to your order
            Order Code - $order_id
          ";
    
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
          
        ?>
          
          <script>
            alert('Message is sent');
            window.location.href="<?php echo site_url('kitchen/dashboard'); ?>";
          </script> 
  <?php }

        $this->load->view('kitchen/menu/nav');
        $this->load->view('kitchen/message_grid', $data);
        $this->load->view('kitchen/menu/footer');
      }else{
        redirect('home');
      }
    }
    
    public function pending_message($id){
       $email = $this->session->userdata('uemail');
       $role = $this->session->userdata('urole');
       
       $order_id = $this->input->post('order_id');
       $email = $this->input->post('email');

      if($role == "Kitchen"){
        $data['total_order_count'] = $this->Admin_model->display_order_count();
        $data['message'] = $this->Admin_model->display_message_pending_by_id($id);
        
        $btn_yes = $this->input->post('yes');
        $btn_no = $this->input->post('no');

        if(isset($btn_yes)){
          $this->Admin_model->complete_message($id); 
          
          $subject = "Order Notification";
          $body = "
            Dear Customer, for your Order ID - $order_id, we have made the necessary changes to the order upon delivery.
            ";
    
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
          
        ?>
          
          <script>
            alert('Message is sent');
            window.location.href="<?php echo site_url('kitchen/dashboard'); ?>";
          </script> 
  <?php }
  
        if(isset($btn_no)){
          $this->Admin_model->reject_message($id); 
          
          $subject = "Order Notification";
          $body = "
            Dear Customer, unfortunately we are unable to make changes to your order
            Order Code - $order_id
          ";
    
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
          
        ?>
          
          <script>
            alert('Message is sent');
            window.location.href="<?php echo site_url('kitchen/dashboard'); ?>";
          </script> 
  <?php }

        $this->load->view('kitchen/menu/nav');
        $this->load->view('kitchen/message/pending', $data);
        $this->load->view('kitchen/menu/footer');
      }else{
        redirect('home');
      } 
    }
    
    public function completed_message($id){
       $email = $this->session->userdata('uemail');
       $role = $this->session->userdata('urole');
       
       $order_id = $this->input->post('order_id');
       $email = $this->input->post('email');

      if($role == "Kitchen"){
        $data['total_order_count'] = $this->Admin_model->display_order_count();
        $data['message'] = $this->Admin_model->display_message_completed_by_id($id);
        
        $btn_yes = $this->input->post('yes');
        $btn_no = $this->input->post('no');

        if(isset($btn_yes)){
          $this->Admin_model->complete_message($id); 
          
          $subject = "Order Notification";
          $body = "
            Dear Customer, for your Order ID - $order_id, we have made the necessary changes to the order upon delivery.
            ";
    
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
          
        ?>
          
          <script>
            alert('Message is sent');
            window.location.href="<?php echo site_url('kitchen/dashboard'); ?>";
          </script> 
  <?php }
  
        if(isset($btn_no)){
          $this->Admin_model->reject_message($id); 
          
          $subject = "Order Notification";
          $body = "
            Dear Customer, unfortunately we are unable to make changes to your order
            Order Code - $order_id
          ";
    
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
          
        ?>
          
          <script>
            alert('Message is sent');
            window.location.href="<?php echo site_url('kitchen/dashboard'); ?>";
          </script> 
  <?php }

        $this->load->view('kitchen/menu/nav');
        $this->load->view('kitchen/message/completed', $data);
        $this->load->view('kitchen/menu/footer');
      }else{
        redirect('home');
      } 
    }
    
    public function rejected_message($id){
       $email = $this->session->userdata('uemail');
       $role = $this->session->userdata('urole');
       
       $order_id = $this->input->post('order_id');
       $email = $this->input->post('email');

      if($role == "Kitchen"){
        $data['total_order_count'] = $this->Admin_model->display_order_count();
        $data['message'] = $this->Admin_model->display_message_rejected_by_id($id);
        
        $btn_yes = $this->input->post('yes');
        $btn_no = $this->input->post('no');

        if(isset($btn_yes)){
          $this->Admin_model->complete_message($id); 
          
          $subject = "Order Notification";
          $body = "
            Dear Customer, for your Order ID - $order_id, we have made the necessary changes to the order upon delivery.
            ";
    
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
          
        ?>
          
          <script>
            alert('Message is sent');
            window.location.href="<?php echo site_url('kitchen/dashboard'); ?>";
          </script> 
  <?php }
  
        if(isset($btn_no)){
          $this->Admin_model->reject_message($id); 
          
          $subject = "Order Notification";
          $body = "
            Dear Customer, unfortunately we are unable to make changes to your order
            Order Code - $order_id
          ";
    
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
          
        ?>
          
          <script>
            alert('Message is sent');
            window.location.href="<?php echo site_url('kitchen/dashboard'); ?>";
          </script> 
  <?php }

        $this->load->view('kitchen/menu/nav');
        $this->load->view('kitchen/message/rejected', $data);
        $this->load->view('kitchen/menu/footer');
      }else{
        redirect('home');
      } 
    }
    
    public function complete_message(){
       $pid = $this->input->post('message_id');
       $this->Admin_model->complete_message($pid); 
    }
    
    public function delete_message(){
       $pid = $this->input->post('message_id');
       $this->Admin_model->delete_message($pid); 
    }
    
    // End of Message
    
    // Orders 
    
    public function view_order(){
      $email = $this->session->userdata('uemail');
      $role = $this->session->userdata('urole');

      if($role == "Kitchen"){
        $data['total_order_count'] = $this->Admin_model->display_order_count();
        $data['orders'] = $this->Admin_model->display_all_order_details();

        $this->load->view('kitchen/menu/nav');
        $this->load->view('kitchen/order/view', $data);
        $this->load->view('kitchen/menu/footer');
      }else{
        redirect('home');
      }
    }
    
    public function pending($id){
      $email = $this->session->userdata('uemail');
      $role = $this->session->userdata('urole');

      if($role == "Kitchen"){
        $data['total_order_count'] = $this->Admin_model->display_order_count();
        $data['pending'] = $this->Admin_model->display_all_pending_order_by_id($id);
        
        $order_id = $this->input->post('order_id');
        $title = $this->input->post('title');
        $price = $this->input->post('price');
        $quantity = $this->input->post('quantity'); 
        $customer_email = $this->input->post('customer_email');
        
        $btn_delivering = $this->input->post('delivering');
        $btn_delivered = $this->input->post('delivered');
        $btn_cancelled = $this->input->post('cancelled');

        if(isset($btn_delivering)){
          $this->Admin_model->delivering_order($id, "Delivering"); 
          
          $subject = "Order Notification";
          $body = "
            Dear Customer, please find your ordered products and the current order status
            Order Code - $order_id,
            Order Title - $title,
            Order Price - $price,
            Order Quantity - $quantity,
            Order Status - Delivering
          ";
    
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
         $this->email->to("scottphenix24@gmail.com");
         //$this->email->cc("testcc@domainname.com");
         $this->email->subject("$subject");
         $this->email->message("$body");
         $this->email->send();
          
        ?>
          
          <script>
            alert('Order is now delivering');
            window.location.href="<?php echo site_url('kitchen/dashboard'); ?>";
          </script> 
  <?php }
  
        if(isset($btn_delivered)){
          $this->Admin_model->delivered_order($id, "Delivered"); 
          
          $subject = "Order Notification";
          $body = "
            Dear Customer, please find your ordered products and the current order status
            Order Code - $order_id,
            Order Title - $title,
            Order Price - $price,
            Order Quantity - $quantity,
            Order Status - Delivered
          ";
    
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
         $this->email->to("scottphenix24@gmail.com");
         //$this->email->cc("testcc@domainname.com");
         $this->email->subject("$subject");
         $this->email->message("$body");
         $this->email->send();
          
        ?>
          
          <script>
            alert('Order is now delivered');
            window.location.href="<?php echo site_url('kitchen/dashboard'); ?>";
          </script> 
  <?php }
  
        if(isset($btn_cancelled)){
          $this->Admin_model->cancel_order($id, "Cancelled"); 
          
          $subject = "Order Notification";
          $body = "
            Dear Customer, please find your ordered products and the current order status
            Order Code - $order_id,
            Order Title - $title,
            Order Price - $price,
            Order Quantity - $quantity,
            Order Status - Cancelled
          ";
    
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
         $this->email->to("scottphenix24@gmail.com");
         //$this->email->cc("testcc@domainname.com");
         $this->email->subject("$subject");
         $this->email->message("$body");
         $this->email->send();
          
        ?>
          
          <script>
            alert('Order is has been cancelled');
            window.location.href="<?php echo site_url('kitchen/dashboard'); ?>";
          </script> 
  <?php }

        $this->load->view('kitchen/menu/nav');
        $this->load->view('kitchen/order/pending', $data);
        $this->load->view('kitchen/menu/footer');
      }else{
        redirect('home');
      }
    }
    
    public function delivering($id){
      $email = $this->session->userdata('uemail');
      $role = $this->session->userdata('urole');

      if($role == "Kitchen"){
        $data['total_order_count'] = $this->Admin_model->display_order_count();
        $data['delivering'] = $this->Admin_model->display_all_delivering_order_by_id($id);
        
        $order_id = $this->input->post('order_id');
        $title = $this->input->post('title');
        $price = $this->input->post('price');
        $quantity = $this->input->post('quantity'); 
        $customer_email = $this->input->post('customer_email');
        
        $btn_pending = $this->input->post('pending');
        $btn_delivered = $this->input->post('delivered');
        $btn_cancelled = $this->input->post('cancelled');

        if(isset($btn_pending)){
          $this->Admin_model->pending_order($id, "Pending"); 
          
          $subject = "Order Notification";
          $body = "
            Dear Customer, please find your ordered products and the current order status
            Order Code - $order_id,
            Order Title - $title,
            Order Price - $price,
            Order Quantity - $quantity,
            Order Status - Pending
          ";
    
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
         $this->email->to("scottphenix24@gmail.com");
         //$this->email->cc("testcc@domainname.com");
         $this->email->subject("$subject");
         $this->email->message("$body");
         $this->email->send();
          
        ?>
          
          <script>
            alert('Order is now pending');
            window.location.href="<?php echo site_url('kitchen/dashboard'); ?>";
          </script> 
  <?php }
  
        if(isset($btn_delivered)){
          $this->Admin_model->delivered_order($id, "Delivered"); 
          
          $subject = "Order Notification";
          $body = "
            Dear Customer, please find your ordered products and the current order status
            Order Code - $order_id,
            Order Title - $title,
            Order Price - $price,
            Order Quantity - $quantity,
            Order Status - Delivered
          ";
    
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
         $this->email->to("scottphenix24@gmail.com");
         //$this->email->cc("testcc@domainname.com");
         $this->email->subject("$subject");
         $this->email->message("$body");
         $this->email->send();
          
        ?>
          
          <script>
            alert('Order is now delivered');
            window.location.href="<?php echo site_url('kitchen/dashboard'); ?>";
          </script> 
  <?php }
  
        if(isset($btn_cancelled)){
          $this->Admin_model->cancel_order($id, "Cancelled"); 
          
          $subject = "Order Notification";
          $body = "
            Dear Customer, please find your ordered products and the current order status
            Order Code - $order_id,
            Order Title - $title,
            Order Price - $price,
            Order Quantity - $quantity,
            Order Status - Cancelled
          ";
    
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
         $this->email->to("scottphenix24@gmail.com");
         //$this->email->cc("testcc@domainname.com");
         $this->email->subject("$subject");
         $this->email->message("$body");
         $this->email->send();
          
        ?>
          
          <script>
            alert('Order is has been cancelled');
            window.location.href="<?php echo site_url('kitchen/dashboard'); ?>";
          </script> 
  <?php }

        $this->load->view('kitchen/menu/nav');
        $this->load->view('kitchen/order/delivering', $data);
        $this->load->view('kitchen/menu/footer');
      }else{
        redirect('home');
      }
    }
    
    public function delivered($id){
      $email = $this->session->userdata('uemail');
      $role = $this->session->userdata('urole');

      if($role == "Kitchen"){
        $data['total_order_count'] = $this->Admin_model->display_order_count();
        $data['delivered'] = $this->Admin_model->display_all_delivered_order_by_id($id);
        
        $order_id = $this->input->post('order_id');
        $title = $this->input->post('title');
        $price = $this->input->post('price');
        $quantity = $this->input->post('quantity'); 
        $customer_email = $this->input->post('customer_email');
        
        $btn_pending = $this->input->post('pending');
        $btn_delivered = $this->input->post('delivered');
        $btn_cancelled = $this->input->post('cancelled');

        if(isset($btn_pending)){
          $this->Admin_model->pending_order($id, "Pending"); 
          
          $subject = "Order Notification";
          $body = "
            Dear Customer, please find your ordered products and the current order status
            Order Code - $order_id,
            Order Title - $title,
            Order Price - $price,
            Order Quantity - $quantity,
            Order Status - Pending
          ";
    
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
         $this->email->to("scottphenix24@gmail.com");
         //$this->email->cc("testcc@domainname.com");
         $this->email->subject("$subject");
         $this->email->message("$body");
         $this->email->send();
          
        ?>
          
          <script>
            alert('Order is now pending');
            window.location.href="<?php echo site_url('admin/dashboard'); ?>";
          </script> 
  <?php }
  
        if(isset($btn_delivered)){
          $this->Admin_model->delivered_order($id, "Delivered"); 
          
          $subject = "Order Notification";
          $body = "
            Dear Customer, please find your ordered products and the current order status
            Order Code - $order_id,
            Order Title - $title,
            Order Price - $price,
            Order Quantity - $quantity,
            Order Status - Delivered
          ";
    
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
         $this->email->to("scottphenix24@gmail.com");
         //$this->email->cc("testcc@domainname.com");
         $this->email->subject("$subject");
         $this->email->message("$body");
         $this->email->send();
          
        ?>
          
          <script>
            alert('Order is now delivered');
            window.location.href="<?php echo site_url('kitchen/dashboard'); ?>";
          </script> 
  <?php }
  
        if(isset($btn_cancelled)){
          $this->Admin_model->cancel_order($id, "Cancelled"); 
          
          $subject = "Order Notification";
          $body = "
            Dear Customer, please find your ordered products and the current order status
            Order Code - $order_id,
            Order Title - $title,
            Order Price - $price,
            Order Quantity - $quantity,
            Order Status - Cancelled
          ";
    
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
         $this->email->to("scottphenix24@gmail.com");
         //$this->email->cc("testcc@domainname.com");
         $this->email->subject("$subject");
         $this->email->message("$body");
         $this->email->send();
          
        ?>
          
          <script>
            alert('Order is has been cancelled');
            window.location.href="<?php echo site_url('kitchen/dashboard'); ?>";
          </script> 
  <?php }

        $this->load->view('kitchen/menu/nav');
        $this->load->view('kitchen/order/delivered', $data);
        $this->load->view('kitchen/menu/footer');
      }else{
        redirect('home');
      }
    }
    
    /*public function delivering_order(){
      $pid = $this->input->post('order_id');
      $status = "Delivering";
      $this->Admin_model->delivering_order($pid, $status);
    }
    
    public function delivered_order(){
      $pid = $this->input->post('order_id');
      $status = "Delivered";
      $this->Admin_model->delivered_order($pid, $status);
    }

    public function cancel_order(){
      $pid = $this->input->post('order_id');
      $status = "Cancelled";
      $this->Admin_model->cancel_order($pid, $status);
    }
    */

    public function delete_order(){
      $did = $this->input->post('order_id');
      $this->Admin_model->delete_order($did);
    }
    
    // End of Orders 
        
  }

?>