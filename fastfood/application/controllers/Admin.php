<?php

  class Admin extends CI_Controller{

    public function dashboard(){
      $email = $this->session->userdata('uemail');
      $role = $this->session->userdata('urole');

      if($role == "Admin"){
        $data['total_user_count'] = $this->Admin_model->display_user_count();
        $data['total_order_count'] = $this->Admin_model->display_order_count();
        $data['total_food_count'] = $this->Admin_model->display_food_count();
        $data['user_status'] = $this->Admin_model->display_all_users();
        $data['food'] = $this->Admin_model->display_all_food();
        $data['message'] = $this->Admin_model->display_all_message();
        
        $data['pending'] = $this->Admin_model->display_all_pending_orders();
        $data['delivering'] = $this->Admin_model->display_all_delivering_orders();
        $data['delivered'] = $this->Admin_model->display_all_delivered_orders();
        $data['cancelled'] = $this->Admin_model->display_all_cancelled_orders();

        $this->load->view('admin/menu/nav');
        $this->load->view('admin/dashboard', $data);
        $this->load->view('admin/menu/footer');
      }else{
        redirect('home');
      }
    }
    
    // Food 
    
    public function view_food(){
      $email = $this->session->userdata('uemail');
      $role = $this->session->userdata('urole');

      if($role == "Admin"){
        $data['total_order_count'] = $this->Admin_model->display_order_count();

        $config['base_url'] = base_url()."admin/view_food";
        $total_row = $this->Admin_model->record_food_count();
        $config['total_rows'] = $total_row;
        $config['per_page'] = 8;
        $config['uri_segment'] = 3;
        $choice = $config['total_rows']/$config['per_page'];
        $config['num_links'] = round($choice);

        $config['full_tag_open'] = '<ul style="margin-left: 100px;" class="pagination">';
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

        $config['cur_tag_open'] = '<li class="active"><span><b>';
        $config['cur_tag_close'] = '</b></span></li>';

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3))? $this->uri->segment(3) : 0;

        $data["food"] = $this->Admin_model->fetch_food_data($config["per_page"], $page);

        $this->load->view('admin/menu/nav');
        $this->load->view('admin/food/view', $data);
        $this->load->view('admin/menu/footer');
      }else{
        redirect('home');
      }
    }
    
    public function jollof_n_laugh(){
      $email = $this->session->userdata('uemail');
      $role = $this->session->userdata('urole');

      if($role == "Admin"){
        $data['total_order_count'] = $this->Admin_model->display_order_count();

        $config['base_url'] = base_url()."admin/jollof_n_laugh";
        $total_row = $this->Admin_model->record_jollof_n_laugh_count();
        $config['total_rows'] = $total_row;
        $config['per_page'] = 8;
        $config['uri_segment'] = 3;
        $choice = $config['total_rows']/$config['per_page'];
        $config['num_links'] = round($choice);

        $config['full_tag_open'] = '<ul style="margin-left: 100px;" class="pagination">';
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

        $config['cur_tag_open'] = '<li class="active"><span><b>';
        $config['cur_tag_close'] = '</b></span></li>';

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3))? $this->uri->segment(3) : 0;

        $data["food"] = $this->Admin_model->fetch_jollof_n_laugh_data($config["per_page"], $page);

        $this->load->view('admin/menu/nav');
        $this->load->view('admin/food/jollof_n_laugh', $data);
        $this->load->view('admin/menu/footer');
      }else{
        redirect('home');
      }
    }
    
    public function edit_food($id){
      $email = $this->session->userdata('uemail');
      $role = $this->session->userdata('urole');

      if($role == "Admin"){
        //$data['total_order_count'] = $this->Admin_model->display_order_count();
        //$data['category_menu'] = $this->Admin_model->display_menu();
        $data['edit_food'] = $this->Admin_model->display_food_by_id($id);

        $this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('price', 'Price', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required|min_length[30]|max_length[1000]');

        $form_valid = $this->form_validation->run();
        
        $submit_btn = $this->input->post('upload');

        if($form_valid == FALSE){
          $this->load->view('admin/menu/nav');
          $this->load->view('admin/food/edit', $data);
          $this->load->view('admin/menu/footer');
        }
        
        if(isset($submit_btn)){
          $title = $this->input->post('title');
          $str_title = str_replace(' ', '-', $title);
          $price = $this->input->post('price');
          $type = $this->input->post('type');
          $category = $this->input->post('category');
          /*$description = $this->input->post('description');
          $side_meal = $this->input->post('side_meal');
          $side_drink = $this->input->post('side_drink');*/

          $str_category = str_replace(' ', '-', $category);
          
          /*$delivery_start = $this->input->post('delivery_start');
          $delivery_end = $this->input->post('delivery_end');
          
          $days = $this->input->post('days');
          $all = $this->input->post('all');*/
          $stock = $this->input->post('stock');
          
          //$meal_voucher = $this->input->post('meal_voucher');
          
          $food_array = array(
            'title' => $str_title,
            'price' => $price,
            'type' => $type,
            'category' => $str_category,
            'stock' => $stock
          );

          /*if(isset($all)){
            $food_array = array(
            'title' => $str_title,
            'description' => $description,
            'price' => $price,
            'type' => $type,
            'category' => $str_category,
            'side_meal' => $side_meal,
            'side_drink' => $side_drink,
            'meal_voucher' => $meal_voucher,
            'delivery_start' => $delivery_start,
            'date' => $all,
            'delivery_end' => $delivery_end,
            'stock' => $stock
          );  
          }else if(isset($days)){
           
           $date_days = implode(',', $days);

           $food_array = array(
            'title' => $str_title,
            'description' => $description,
            'price' => $price,
            'type' => $type,
            'category' => $str_category,
            'side_meal' => $side_meal,
            'side_drink' => $side_drink,
            'meal_voucher' => $meal_voucher,
            'delivery_start' => $delivery_start,
            'date' => $date_days,
            'delivery_end' => $delivery_end,
            'stock' => $stock
            );   
          }*/

          $update_food = $this->Admin_model->update_food($id, $food_array);

          if($update_food){ redirect('admin/jollof_n_laugh'); ?>
            <script>
                alert('Updated Successfully');
                window.location.href="<?php echo site_url('admin/view_food'); ?>";
            </script>
      <?php
          }else{
            $msgError = '<div class="alert alert-danger>Upload Failed</div>';
            $this->session->set_flashdata('msgError', $msgError); ?>
            <script>
                alert('Update Failed');
            </script>
      <?php $this->load->view('admin/menu/nav');
            $this->load->view('admin/food/edit', $data);
            $this->load->view('admin/menu/footer');
          }
        }
      }else{
        redirect('home');
      }
    }
    
    public function edit_image($id){
      $submit_btn = $this->input->post('upload_image');
      
      if(isset($submit_btn)){
          $files = $_FILES;
          $cpt1 = count($_FILES['userFiles1']['name']);
          $cpt2 = count($_FILES['userFiles2']['name']);
          $cpt3 = count($_FILES['userFiles3']['name']);
          $cpt4 = count($_FILES['userFiles4']['name']);
          $cpt5 = count($_FILES['userFiles5']['name']);
    
          for($i=0; $i<$cpt1; $i++){
            $_FILES['userFiles1']['name']= $files['userFiles1']['name'][$i];
            $_FILES['userFiles1']['type']= $files['userFiles1']['type'][$i];
            $_FILES['userFiles1']['tmp_name']= $files['userFiles1']['tmp_name'][$i];
            $_FILES['userFiles1']['error']= $files['userFiles1']['error'][$i];
            $_FILES['userFiles1']['size']= $files['userFiles1']['size'][$i];
            
            $_FILES['userFiles2']['name']= $files['userFiles2']['name'][$i];
            $_FILES['userFiles2']['type']= $files['userFiles2']['type'][$i];
            $_FILES['userFiles2']['tmp_name']= $files['userFiles2']['tmp_name'][$i];
            $_FILES['userFiles2']['error']= $files['userFiles2']['error'][$i];
            $_FILES['userFiles2']['size']= $files['userFiles2']['size'][$i];
            
            $_FILES['userFiles3']['name']= $files['userFiles3']['name'][$i];
            $_FILES['userFiles3']['type']= $files['userFiles3']['type'][$i];
            $_FILES['userFiles3']['tmp_name']= $files['userFiles3']['tmp_name'][$i];
            $_FILES['userFiles3']['error']= $files['userFiles3']['error'][$i];
            $_FILES['userFiles3']['size']= $files['userFiles3']['size'][$i];
            
            $_FILES['userFiles4']['name']= $files['userFiles4']['name'][$i];
            $_FILES['userFiles4']['type']= $files['userFiles4']['type'][$i];
            $_FILES['userFiles4']['tmp_name']= $files['userFiles4']['tmp_name'][$i];
            $_FILES['userFiles4']['error']= $files['userFiles4']['error'][$i];
            $_FILES['userFiles4']['size']= $files['userFiles4']['size'][$i];
            
            $_FILES['userFiles5']['name']= $files['userFiles5']['name'][$i];
            $_FILES['userFiles5']['type']= $files['userFiles5']['type'][$i];
            $_FILES['userFiles5']['tmp_name']= $files['userFiles5']['tmp_name'][$i];
            $_FILES['userFiles5']['error']= $files['userFiles5']['error'][$i];
            $_FILES['userFiles5']['size']= $files['userFiles5']['size'][$i];

            $config1 = array(
                'upload_path'   => "./uploads/food/",
                'allowed_types' => "gif|jpg|png|jpeg",
                'overwrite'     => TRUE,
                'max_size'      => "3000",  // Can be set to particular file size
                //'max_height'    => "768",
                //'max_width'     => "1024"
            );

            $this->load->library('upload', $config1);
            $this->upload->initialize($config1);

            $this->upload->do_upload('userFiles1');
            $fileName1 = $_FILES['userFiles1']['name'];
            
            $this->upload->do_upload('userFiles2');
            $fileName2 = $_FILES['userFiles2']['name'];
            
            $this->upload->do_upload('userFiles3');
            $fileName3 = $_FILES['userFiles3']['name'];
            
            $this->upload->do_upload('userFiles4');
            $fileName4 = $_FILES['userFiles4']['name'];
            
            $this->upload->do_upload('userFiles5');
            $fileName5 = $_FILES['userFiles5']['name'];
            //$images[] = $fileName1;
          }

          $update_image1 = $this->Admin_model->update_food_image1($id, $fileName1);
          /*$update_image2 = $this->Admin_model->update_food_image2($id, $fileName2);
          $update_image3 = $this->Admin_model->update_food_image3($id, $fileName3);
          $update_image4 = $this->Admin_model->update_food_image4($id, $fileName4);
          $update_image5 = $this->Admin_model->update_food_image5($id, $fileName5);*/

          if($update_image1 /* || $update_image2 || $update_image3 || $update_image4 || $update_image5*/){ ?>
            <script>
                alert('Updated Successfully');
                window.location.href="<?php echo site_url('admin/view_food'); ?>";
            </script>
      <?php
          }else{
            $msgError = '<div class="alert alert-danger>Upload Failed</div>';
            $this->session->set_flashdata('msgError', $msgError); ?>
            <script>
                alert('Update Failed');
            </script>
      <?php $this->load->view('admin/menu/nav');
            $this->load->view('admin/food/edit', $data);
            $this->load->view('admin/menu/footer');
          }  
      }
    }
    
    public function add_food(){
      $email = $this->session->userdata('uemail');
      $role = $this->session->userdata('urole');

      if($role == "Admin"){
        $data['total_order_count'] = $this->Admin_model->display_order_count();

        $this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('price', 'Price', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');

        $form_valid = $this->form_validation->run();
        $submit_btn = $this->input->post('add');
        
        if($form_valid == FALSE){
          $this->load->view('admin/menu/nav');
          $this->load->view('admin/food/add', $data);
          $this->load->view('admin/menu/footer');
        }

        if(isset($submit_btn)){
          $shuffle = str_shuffle("ABCDEFGUVXYZXCV");
          $unique = rand(000, 999);
          $code = $shuffle.$unique;

          $title = $this->input->post('title');
          $str_title = str_replace(' ', '-', $title);
          $price = $this->input->post('price');
          $type = $this->input->post('type');
          $category = $this->input->post('category');
          $stock = $this->input->post('stock');
          /*$description = $this->input->post('description');
          $side_meal = $this->input->post('side_meal');
          $side_drink = $this->input->post('side_drink');*/

          $str_category = str_replace(' ', '-', $category);
          
          /*$delivery_start = $this->input->post('delivery_start');
          $delivery_end = $this->input->post('delivery_end');
          
          $days = $this->input->post('days');
          $all = $this->input->post('all');
          $stock = $this->input->post('stock');
          
          $meal_voucher = $this->input->post('meal_voucher');*/
          
          $time = time();
          $date = date('Y-m-j H:i:s');

          $files = $_FILES;
          $cpt1 = count($_FILES['userFiles1']['name']);
          $cpt2 = count($_FILES['userFiles2']['name']);
          $cpt3 = count($_FILES['userFiles3']['name']);
          $cpt4 = count($_FILES['userFiles4']['name']);
          $cpt5 = count($_FILES['userFiles5']['name']);

          for($i=0; $i<$cpt1; $i++){
            $_FILES['userFiles1']['name']= $files['userFiles1']['name'][$i];
            $_FILES['userFiles1']['type']= $files['userFiles1']['type'][$i];
            $_FILES['userFiles1']['tmp_name']= $files['userFiles1']['tmp_name'][$i];
            $_FILES['userFiles1']['error']= $files['userFiles1']['error'][$i];
            $_FILES['userFiles1']['size']= $files['userFiles1']['size'][$i];
            
            $_FILES['userFiles2']['name']= $files['userFiles2']['name'][$i];
            $_FILES['userFiles2']['type']= $files['userFiles2']['type'][$i];
            $_FILES['userFiles2']['tmp_name']= $files['userFiles2']['tmp_name'][$i];
            $_FILES['userFiles2']['error']= $files['userFiles2']['error'][$i];
            $_FILES['userFiles2']['size']= $files['userFiles2']['size'][$i];
            
            $_FILES['userFiles3']['name']= $files['userFiles3']['name'][$i];
            $_FILES['userFiles3']['type']= $files['userFiles3']['type'][$i];
            $_FILES['userFiles3']['tmp_name']= $files['userFiles3']['tmp_name'][$i];
            $_FILES['userFiles3']['error']= $files['userFiles3']['error'][$i];
            $_FILES['userFiles3']['size']= $files['userFiles3']['size'][$i];
            
            $_FILES['userFiles4']['name']= $files['userFiles4']['name'][$i];
            $_FILES['userFiles4']['type']= $files['userFiles4']['type'][$i];
            $_FILES['userFiles4']['tmp_name']= $files['userFiles4']['tmp_name'][$i];
            $_FILES['userFiles4']['error']= $files['userFiles4']['error'][$i];
            $_FILES['userFiles4']['size']= $files['userFiles4']['size'][$i];
            
            $_FILES['userFiles5']['name']= $files['userFiles5']['name'][$i];
            $_FILES['userFiles5']['type']= $files['userFiles5']['type'][$i];
            $_FILES['userFiles5']['tmp_name']= $files['userFiles5']['tmp_name'][$i];
            $_FILES['userFiles5']['error']= $files['userFiles5']['error'][$i];
            $_FILES['userFiles5']['size']= $files['userFiles5']['size'][$i];

            $config1 = array(
                'upload_path'   => "./uploads/food/",
                'allowed_types' => "gif|jpg|png|jpeg",
                'overwrite'     => TRUE,
                'max_size'      => "3000",  // Can be set to particular file size
                //'max_height'    => "768",
                //'max_width'     => "1024"
            );

            $this->load->library('upload', $config1);
            $this->upload->initialize($config1);

            $this->upload->do_upload('userFiles1');
            $fileName1 = $_FILES['userFiles1']['name'];
            
            $this->upload->do_upload('userFiles2');
            $fileName2 = $_FILES['userFiles2']['name'];
            
            $this->upload->do_upload('userFiles3');
            $fileName3 = $_FILES['userFiles3']['name'];
            
            $this->upload->do_upload('userFiles4');
            $fileName4 = $_FILES['userFiles4']['name'];
            
            $this->upload->do_upload('userFiles5');
            $fileName5 = $_FILES['userFiles5']['name'];
            //$images[] = $fileName1;
          }
          
          $food_array = array(
            'code' => $code,  
            'title' => $str_title,
            'price' => $price,
            'type' => $type,
            'category' => $str_category,
            'image1' => $fileName1,
            'sold' => 0,
            'stock' => $stock,
            'created_time' => $time,
            'created_date' => $date
          );
          
          /*if(isset($all)){
            $food_array = array(
            'code' => $code,
            'title' => $str_title,
            'description' => $description,
            'price' => $price,
            'type' => $type,
            'category' => $str_category,
            'side_meal' => $side_meal,
            'side_drink' => $side_drink,
            'meal_voucher' => $meal_voucher,
            'delivery_start' => $delivery_start,
            'date' => $all,
            'delivery_end' => $delivery_end,
            'stock' => $stock,
            'image1' => $fileName1,
            'image2' => $fileName2,
            'image3' => $fileName3,
            'image4' => $fileName4,
            'image5' => $fileName5,
            'sold' => 0,
            'created_time' => $time,
            'created_date' => $date
          );  
          }else if(isset($days)){
           
           $date_days = implode(',', $days);

           $food_array = array(
            'code' => $code,
            'title' => $str_title,
            'description' => $description,
            'price' => $price,
            'type' => $type,
            'category' => $str_category,
            'side_meal' => $side_meal,
            'side_drink' => $side_drink,
            'meal_voucher' => $meal_voucher,
            'delivery_start' => $delivery_start,
            'date' => $date_days,
            'delivery_end' => $delivery_end,
            'stock' => $stock,
            'image1' => $fileName1,
            'image2' => $fileName2,
            'image3' => $fileName3,
            'image4' => $fileName4,
            'image5' => $fileName5,
            'sold' => 0,
            'created_time' => $time,
            'created_date' => $date
            );   
          }*/

          $insert_food = $this->Admin_model->insert_food($food_array);

          if($insert_food){ redirect('admin/jollof_n_laugh'); ?>
            <script>
                alert('Added Successfully');
                window.location.href="<?php echo site_url('admin/view_food'); ?>";
            </script>
    <?php }else{
            $msgError = '<div class="alert alert-danger>Upload Failed</div>';
            $this->session->set_flashdata('msgError', $msgError); ?>
            <script>
                alert('Food Failed');
            </script>
      <?php $this->load->view('admin/menu/nav');
            $this->load->view('admin/food/add', $data);
            $this->load->view('admin/menu/footer');
          }
        }
      }else{
        redirect('home');
      }
    }
    
    public function delete_food(){
      $pid = $this->input->post('del_id');

      $this->Admin_model->delete_food($pid);
    }
    
    public function resizeImage($filename){

      $source_path = $_SERVER['DOCUMENT_ROOT'] . '/uploads/food/' . $filename;

      $target_path = $_SERVER['DOCUMENT_ROOT'] . '/uploads/food/';

      $config_manip = array(

          'image_library' => 'gd2',

          'source_image' => $source_path,

          'new_image' => $target_path,

          'maintain_ratio' => TRUE,

          'width' => 500,

      );

  
      $this->load->library('image_lib', $config_manip);

      if (!$this->image_lib->resize()) {

          echo $this->image_lib->display_errors();

      }

      $this->image_lib->clear();

   }
    
    // End of Food 
    
    // Orders 
    
    public function view_order(){
      $email = $this->session->userdata('uemail');
      $role = $this->session->userdata('urole');

      if($role == "Admin"){
        $data['total_order_count'] = $this->Admin_model->display_order_count();
        $data['orders'] = $this->Admin_model->display_all_order_details();

        $this->load->view('admin/menu/nav');
        $this->load->view('admin/order/view', $data);
        $this->load->view('admin/menu/footer');
      }else{
        redirect('home');
      }
    }
    
    public function pending(){
      $email = $this->session->userdata('uemail');
      $role = $this->session->userdata('urole');

      if($role == "Admin"){
        $data['total_order_count'] = $this->Admin_model->display_order_count();
        $data['pending'] = $this->Admin_model->display_all_pending_orders();
        
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
         $this->email->to("$customer_email");
         //$this->email->cc("testcc@domainname.com");
         $this->email->subject("$subject");
         $this->email->message("$body");
         $this->email->send();
          
        ?>
          
          <script>
            alert('Order is now delivering');
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
         $this->email->to("$customer_email");
         //$this->email->cc("testcc@domainname.com");
         $this->email->subject("$subject");
         $this->email->message("$body");
         $this->email->send();
          
        ?>
          
          <script>
            alert('Order is now delivered');
            window.location.href="<?php echo site_url('admin/dashboard'); ?>";
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
         $this->email->to("$customer_email");
         //$this->email->cc("testcc@domainname.com");
         $this->email->subject("$subject");
         $this->email->message("$body");
         $this->email->send();
          
        ?>
          
          <script>
            alert('Order is has been cancelled');
            window.location.href="<?php echo site_url('admin/dashboard'); ?>";
          </script> 
  <?php }

        $this->load->view('admin/menu/nav');
        $this->load->view('admin/order/pending', $data);
        $this->load->view('admin/menu/footer');
      }else{
        redirect('home');
      }
    }
    
    public function delivering(){
      $email = $this->session->userdata('uemail');
      $role = $this->session->userdata('urole');

      if($role == "Admin"){
        $data['total_order_count'] = $this->Admin_model->display_order_count();
        $data['delivering'] = $this->Admin_model->display_all_delivering_orders();
        
        $order_id = $this->input->post('order_id');
        $title = $this->input->post('title');
        $price = $this->input->post('price');
        $quantity = $this->input->post('quantity'); 
        $customer_email = $this->input->post('customer_email');
        
        $btn_delivered = $this->input->post('delivered');
        $btn_cancelled = $this->input->post('cancelled');

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
            window.location.href="<?php echo site_url('admin/dashboard'); ?>";
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
            window.location.href="<?php echo site_url('admin/dashboard'); ?>";
          </script> 
  <?php }

        $this->load->view('admin/menu/nav');
        $this->load->view('admin/order/delivering', $data);
        $this->load->view('admin/menu/footer');
      }else{
        redirect('home');
      }
    }
    
    public function delivered(){
      $email = $this->session->userdata('uemail');
      $role = $this->session->userdata('urole');

      if($role == "Admin"){
        $data['total_order_count'] = $this->Admin_model->display_order_count();
        $data['delivered'] = $this->Admin_model->display_all_delivered_orders();
        
        $order_id = $this->input->post('order_id');
        $title = $this->input->post('title');
        $price = $this->input->post('price');
        $quantity = $this->input->post('quantity'); 
        $customer_email = $this->input->post('customer_email');
        
        $btn_cancelled = $this->input->post('cancelled');

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
            window.location.href="<?php echo site_url('admin/dashboard'); ?>";
          </script> 
  <?php }

        $this->load->view('admin/menu/nav');
        $this->load->view('admin/order/delivered', $data);
        $this->load->view('admin/menu/footer');
      }else{
        redirect('home');
      }
    }
    
    public function cancelled(){
      $email = $this->session->userdata('uemail');
      $role = $this->session->userdata('urole');

      if($role == "Admin"){
        $data['total_order_count'] = $this->Admin_model->display_order_count();
        $data['cancelled'] = $this->Admin_model->display_all_cancelled_orders();
        
        $order_id = $this->input->post('order_id');
        $title = $this->input->post('title');
        $price = $this->input->post('price');
        $quantity = $this->input->post('quantity'); 
        $customer_email = $this->input->post('customer_email');
        
        $this->load->view('admin/menu/nav');
        $this->load->view('admin/order/cancelled', $data);
        $this->load->view('admin/menu/footer');
      }else{
        redirect('home');
      }
    }
    
    public function refunded(){
      $email = $this->session->userdata('uemail');
      $role = $this->session->userdata('urole');

      if($role == "Admin"){
        $data['total_order_count'] = $this->Admin_model->display_order_count();
        $data['refunded'] = $this->Admin_model->display_all_refunded_orders();
        
        $order_id = $this->input->post('order_id');
        $title = $this->input->post('title');
        $price = $this->input->post('price');
        $quantity = $this->input->post('quantity'); 
        $customer_email = $this->input->post('customer_email');
        
        $this->load->view('admin/menu/nav');
        $this->load->view('admin/order/refunded', $data);
        $this->load->view('admin/menu/footer');
      }else{
        redirect('home');
      }
    }
    
    public function make_refund(){
        require_once('application/libraries/stripe-php/init.php');
        
        $charge_id = $this->input->post('charge_id');
            
        \Stripe\Stripe::setApiKey($this->config->item('stripe_secret'));
        \Stripe\Refund::create([
            'charge' => $charge_id
        ]);
        
        $array = array('status' => 'Refunded');
        
        $this->Admin_model->update_order_items_to_refund($charge_id, $array);
        ?>
        <script>
            alert("Refunded");
            window.location.href="<?php echo site_url('admin/refunded'); ?>";
        </script>
        <?php
        //redirect($_SERVER['HTTP_REFERER']);
   }
    
    public function delivering_order(){
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

    public function delete_order(){
      $id = $this->input->post('order_id');
      
      $query = $this->db->query("SELECT order_id FROM order_items WHERE id = '$id' ")->result();
      foreach($query as $qry){
          $order_id = $qry->order_id;
      }
      
      $this->Admin_model->delete_order($id);
      $this->Admin_model->delete_order_details($order_id);
    }
    
    function delete_all(){
        if($this->input->post('checkbox_value')){
            $id = $this->input->post('checkbox_value');
            for($count = 0; $count < count($id); $count++){
                $this->Admin_model->delete_order($id[$count]);
            }
        }    
    }
    
    function deliver_all(){
        if($this->input->post('checkbox_value')){
            $id = $this->input->post('checkbox_value');
            $status = "Delivering";
            for($count = 0; $count < count($id); $count++){
                $this->Admin_model->delivering_order($id[$count], $status);
            }
        }    
    }
    
    function delivered_all(){
        if($this->input->post('checkbox_value')){
            $id = $this->input->post('checkbox_value');
            $status = "Delivered";
            for($count = 0; $count < count($id); $count++){
                $this->Admin_model->delivered_order($id[$count], $status);
            }
        }    
    }
    
    function cancelled_all(){
        if($this->input->post('checkbox_value')){
            $id = $this->input->post('checkbox_value');
            $status = "Cancelled";
            for($count = 0; $count < count($id); $count++){
                $this->Admin_model->cancel_order($id[$count], $status);
            }
        }    
    }
    
    // End of Orders 
    
    // Vouchers 
    
    public function view_voucher(){
       $email = $this->session->userdata('uemail');
       $role = $this->session->userdata('urole');

      if($role == "Admin"){
        $data['total_order_count'] = $this->Admin_model->display_order_count();
        $data['vouchers'] = $this->Admin_model->display_all_vouchers();

        $this->load->view('admin/menu/nav');
        $this->load->view('admin/voucher/view', $data);
        $this->load->view('admin/menu/footer');
      }else{
        redirect('home');
      } 
    }
    
    public function add_voucher(){
       $email = $this->session->userdata('uemail');
       $role = $this->session->userdata('urole');
       
       $this->form_validation->set_rules('title', 'Title', 'trim|required');
       $this->form_validation->set_rules('price', 'Price', 'trim|required');
       $this->form_validation->set_rules('description', 'Description', 'trim|required');
       $this->form_validation->set_rules('discount', 'Discount', 'trim|required');

       $form_valid = $this->form_validation->run();
       $submit_btn = $this->input->post('add');

      if($role == "Admin"){
        $data['total_order_count'] = $this->Admin_model->display_order_count();
        $data['vouchers'] = $this->Admin_model->display_all_vouchers();
        
        if(isset($submit_btn)){
          $code = $this->input->post('code');
          $title = $this->input->post('title');
          $price = $this->input->post('price');
          $company = $this->input->post('company');
          $type = $this->input->post('type');
          $description = $this->input->post('description');
          $bulk = $this->input->post('bulk');
          $discount = $this->input->post('discount');
          $quantity = $this->input->post('quantity');
          
          $voucher_array = array(
            'code' => $code,
            'title' => $title,
            'description' => $description,
            'price' => $price,
            'discount' => $discount,
            'bulk' => $bulk,
            'company' => $company,
            'type' => $type,
            'quantity' => $quantity
          );

          $insert_voucher = $this->Admin_model->insert_voucher($voucher_array);

          if($insert_voucher){ ?>
            <script>
                alert('Added Successfully');
                window.location.href="<?php echo site_url('admin/view_voucher'); ?>";
            </script>
    <?php }else{
            $msgError = '<div class="alert alert-danger>Upload Failed</div>';
            $this->session->set_flashdata('msgError', $msgError); ?>
            <script>
                alert('Upload Failed');
            </script>
      <?php $this->load->view('admin/menu/nav');
            $this->load->view('admin/voucher/add', $data);
            $this->load->view('admin/menu/footer');
          }
        }

        $this->load->view('admin/menu/nav');
        $this->load->view('admin/voucher/add', $data);
        $this->load->view('admin/menu/footer');
      }else{
        redirect('home');
      }  
    }
         
    public function edit_voucher($id){
       $email = $this->session->userdata('uemail');
       $role = $this->session->userdata('urole');

      if($role == "Admin"){
        $data['total_order_count'] = $this->Admin_model->display_order_count();
        $data['edit_vouchers'] = $this->Admin_model->display_vouchers_by_id($id);
        
        $this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('price', 'Price', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required|min_length[30]|max_length[1000]');

        $form_valid = $this->form_validation->run();
        
        $submit_btn = $this->input->post('edit');

        if($form_valid == FALSE){
          $this->load->view('admin/menu/nav');
          $this->load->view('admin/voucher/edit', $data);
          $this->load->view('admin/menu/footer');
        }
        
        if(isset($submit_btn)){
          $code = $this->input->post('code');
          $title = $this->input->post('title');
          $price = $this->input->post('price');
          $discount = $this->input->post('discount');
          $quantity = $this->input->post('quantity');
          $bulk = $this->input->post('bulk');
          $company = $this->input->post('company');
          $type = $this->input->post('type');
          $description = $this->input->post('description');

          $voucher_array = array(
            'code' => $code,
            'title' => $title,
            'description' => $description,
            'price' => $price,
            'discount' => $discount,
            'bulk' => $bulk,
            'company' => $company,
            'type' => $type,
            'quantity' => $quantity
          );

          $update_voucher = $this->Admin_model->update_voucher($id, $voucher_array);

          if($update_voucher){ ?>
            <script>
                alert('Updated Successfully');
                window.location.href="<?php echo site_url('admin/view_voucher'); ?>";
            </script>
      <?php
          }else{
            $msgError = '<div class="alert alert-danger>Upload Failed</div>';
            $this->session->set_flashdata('msgError', $msgError); ?>
            <script>
                alert('Update Failed');
            </script>
      <?php $this->load->view('admin/menu/nav');
            $this->load->view('admin/voucher/edit', $data);
            $this->load->view('admin/menu/footer');
          }
        }
        
      }else{
        redirect('home');
      }  
    }
            
    public function delete_voucher(){
      $did = $this->input->post('del_id');
      $this->Admin_model->delete_voucher($did);
    }
    
    // End of Vouchers   
    
    // Edit Website      
        
         // Seating
        
        public function seating(){
          $email = $this->session->userdata('uemail');
          $role = $this->session->userdata('urole');
    
          if($role == "Admin"){
            $data['total_order_count'] = $this->Admin_model->display_order_count();
            $data['seating'] = $this->Admin_model->display_seating();
    
            $this->load->view('admin/menu/nav', $data);
            $this->load->view('admin/website/seating', $data);
            $this->load->view('admin/menu/footer');
          }else{
            redirect('home');
          }
        }
        
        public function add_seating(){
          $data['total_order_count'] = $this->Admin_model->display_order_count();
          $data['seating'] = $this->Admin_model->display_seating();
    
          $this->form_validation->set_rules('title', 'Seating', 'trim|required');
          $form_valid = $this->form_validation->run();
    
          if($form_valid == FALSE){
            $this->load->view('admin/menu/nav', $data);
            $this->load->view('admin/website/seating', $data);
            $this->load->view('admin/menu/footer');
          }else{
            $title = $this->input->post('title');
    
            $add = array('title' => $title);
            $insert = $this->Admin_model->insert_seating($add);
    
            if($insert){?>
              <script>
                  alert('Added Successfully');
                  window.location.href="<?php echo site_url('admin/seating'); ?>";
              </script>
      <?php }else{
              $statusMsg = '<div class="alert alert-danger" role="alert">Error!!. Try Again</div>';
              $this->session->set_flashdata('msgMenuError', $statusMsg);
              $this->load->view('admin/menu/nav', $data);
              $this->load->view('admin/website/seating', $data);
              $this->load->view('admin/menu/footer');
            }
          }
        }
        
        public function edit_seating($id){
          $data['total_order_count'] = $this->Admin_model->display_order_count();
          $data['edit_seating'] = $this->Admin_model->display_seating_by_id($id);
    
          $this->form_validation->set_rules('seating', 'Seating', 'trim|required');
          $form_valid = $this->form_validation->run();
          $submit = $this->input->post('edit');
    
          if($form_valid == FALSE){
            $this->load->view('admin/menu/nav', $data);
            $this->load->view('admin/website/edit_seating', $data);
            $this->load->view('admin/menu/footer');
          }
          if(isset($submit)){
            $title = $this->input->post('title');

            $update = $this->Admin_model->update_seating($id, $title);
    
            if($update){
              ?>
              <script>
                  alert('Edited Successfully');
                  window.location.href="<?php echo site_url('admin/seating'); ?>";
              </script>
      <?php }else{
              $statusMsg = '<div class="alert alert-danger" role="alert">Error!!. Try Again</div>';
              $this->session->set_flashdata('msgEditError', $statusMsg);
              $this->load->view('admin/menu/nav', $data);
              $this->load->view('admin/website/edit_seating', $data);
              $this->load->view('admin/menu/footer');
            }
          }
        }
        
        public function delete_seating(){
          $id = $this->input->post('id');
          $this->Admin_model->delete_seating($id);
        }
        
        // End of Seating
        
        
    
    // End of Edit Website 
    
    // Other 
    
    // Summer note

    public function saveFile(){
      if ($_FILES['image']['name']) {
       if (!$_FILES['image']['error']) {
           $ext = explode('.', $_FILES['image']['name']);
           $filename = underscore($ext[0]) . '.' . $ext[1];
           $destination = './uploads/food/' . $filename; //change path of the folder...
           $location = $_FILES["image"]["tmp_name"];
           move_uploaded_file($location, $destination);
           echo base_url() . 'uploads/food/' . $filename;
       } else {
           echo $message = 'The following error occured:  ' . $_FILES['image']['error'];
       }
     }
   }

    // End of Summer note

    public function get_banner_menu(){
       $type = $this->input->post('banner_type');
       $category = $this->Admin_model->display_menu();
       if(count($category) > 0){
         $category_select = '';
         $category_select.= '<option value="">Select Category</option>';
         foreach($category as $cat){
           $category_select .='<option value="'.$cat->category.'">'.$cat->category.'</option>';
         }
         echo json_encode($category_select);
       }
    }
    
    // End of Other 
    
  }

?>
