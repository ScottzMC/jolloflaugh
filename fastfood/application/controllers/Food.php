<?php

  class Food extends CI_Controller{

    public function all(){
      $data['menu'] = $this->Data_model->display_menu_options();
      
      	  $email = $this->session->userdata('uemail');

      if(!$this->cart->contents()){
    	 $data['message'] = '<p><div class="alert alert-danger" role="alert">Your cart is empty!</div></p>';
      }else{
    	 $data['message'] = $this->session->flashdata('message');
      }

      $config['base_url'] = base_url('food/all');
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
      $data['schedule'] = $this->Data_model->display_schedule_date($email);

      if(!empty($data['all'])){
        $this->load->view('site/food/all', $data);
      }else{
        $this->custom404();
      }
    }
    
    public function sort_date($date){
      $data['menu'] = $this->Data_model->display_menu_options();
      
      	  $email = $this->session->userdata('uemail');

      if(!$this->cart->contents()){
    	 $data['message'] = '<p><div class="alert alert-danger" role="alert">Your cart is empty!</div></p>';
      }else{
    	 $data['message'] = $this->session->flashdata('message');
      }

      $config['base_url'] = base_url('food/sort_date/'.$date);
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
      $data['schedule'] = $this->Data_model->display_schedule_date($email);

      if(!empty($data['all'])){
        $this->load->view('site/food/sort_date', $data);
      }else{
        $this->custom404();
      }
    }
    
    public function category($category){
      $data['menu'] = $this->Data_model->display_menu_options();
      
      	  $email = $this->session->userdata('uemail');

      if(!$this->cart->contents()){
    	 $data['message'] = '<p><div class="alert alert-danger" role="alert">Your cart is empty!</div></p>';
      }else{
    	 $data['message'] = $this->session->flashdata('message');
      }

      $config['base_url'] = base_url('food/category/'.$category);
      $total_row = $this->Data_model->record_food_category_count($category);
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

      $data['category'] = $this->Data_model->fetch_food_category_data($config["per_page"], $page, $category);
      $data['schedule'] = $this->Data_model->display_schedule_date($email);

      //if(!empty($data['category'])){
        $this->load->view('site/food/category', $data);
      //}else{
        //$this->custom404();
      //}
    }
    
    public function detail($id, $title){
        if(!$this->cart->contents()){
		    $data['message'] = '<p><div class="alert alert-danger" role="alert">Your cart is empty!</div></p>';
	    }else{
		    $data['message'] = $this->session->flashdata('message');
	    }
	    
	    $email = $this->session->userdata('uemail');
	  
        $data['detail'] = $this->Data_model->display_food_by_id($id);
        $data['schedule'] = $this->Data_model->display_schedule_date($email);
        
        $this->load->view('site/food/detail', $data);
    }
    
    public function search(){
      if(!$this->cart->contents()){
	    $data['message'] = '<p><div class="alert alert-danger" role="alert">Your cart is empty!</div></p>';
	  }else{
		$data['message'] = $this->session->flashdata('message');
	  }
	  
      $search_query = $this->input->post('search_query');

      $config['base_url'] = base_url()."food/search";
      $total_row = $this->Data_model->record_search_count();
      $config['total_rows'] = $total_row;
      $config['per_page'] = 12;
      $config['uri_segment'] = 4;
      $choice = $config['total_rows']/$config['per_page'];
      $config['num_links'] = round($choice);

      $config['full_tag_open'] = '<ul class="kenne-pagination-box primary-color">';
      $config['full_tag_close'] = '</ul>';

      $config['first_tag_open'] = '<li><a>';
      $config['last_tag_open'] = '<li><a>';

      $config['next_tag_open'] = '<li><a>';
      $config['prev_tag_open'] = '<li><a>';

      $config['num_tag_open'] = '<li><a>';
      $config['num_tag_close'] = '</a></li>';

      $config['first_tag_close'] = '</a></li>';
      $config['last_tag_close'] = '</a></li>';

      $config['next_tag_close'] = '</a></li>';
      $config['prev_tag_close'] = '</a></li>';

      $config['cur_tag_open'] = '<li><a>';
      $config['cur_tag_close'] = '</a></li>';

      $this->pagination->initialize($config);

      $page = ($this->uri->segment(4))? $this->uri->segment(4) : 0;
      
      $email = $this->session->userdata('uemail');

      $data["search"] = $this->Data_model->fetch_search_data($config["per_page"], $page, $search_query);
      //$data['schedule'] = $this->Data_model->display_schedule_date($email);
      //$data['menu'] = $this->Data_model->display_menu_options();

      if(!empty($data['search'])){
        $this->load->view('site/food/search', $data);
      }else{
        $this->custom404();
      }
    }
    
    public function custom404(){
        $this->load->view('site/custom404');
    }
    
  }

?>