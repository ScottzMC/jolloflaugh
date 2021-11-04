<?php 

    class Banner extends CI_Controller{
        
        public function view(){
            $data['banner'] = $this->Data_model->display_all_banner();
            
            $this->load->view('admin/banner/view', $data);
        }
        
        public function add(){
            $this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[3]');
            $this->form_validation->set_rules('type', 'Type', 'trim|required');
            $this->form_validation->set_rules('category', 'Category', 'trim|required');
            $this->form_validation->set_rules('video', 'Video URL', 'trim|required');
    
            $form_valid = $this->form_validation->run();
            $submit_btn = $this->input->post('submit');
            
            if($form_valid == FALSE){
              $this->load->view('admin/banner/add');
            }
            
            if(isset($submit_btn)){
                $title = $this->input->post('title');
                $type = $this->input->post('type');
                $category = $this->input->post('category');
                $subcategory = $this->input->post('subcategory');
                $video = $this->input->post('video');
                
                $files = $_FILES;
                $cpt1 = count($_FILES['fileToUpload']['name']);
                
                for($i=0; $i<$cpt1; $i++){
                    $_FILES['fileToUpload']['name']= $files['fileToUpload']['name'][$i];
                    $_FILES['fileToUpload']['type']= $files['fileToUpload']['type'][$i];
                    $_FILES['fileToUpload']['tmp_name']= $files['fileToUpload']['tmp_name'][$i];
                    $_FILES['fileToUpload']['error']= $files['fileToUpload']['error'][$i];
                    $_FILES['fileToUpload']['size']= $files['fileToUpload']['size'][$i];
               }
               
               $config = array(
                'upload_path'   => "../uploads/banner/",
                'allowed_types' => "gif|jpg|png|jpeg",
                'overwrite'     => TRUE,
                'max_size'      => "3000",  // Can be set to particular file size
                //'max_height'    => "768",
                //'max_width'     => "1024"
                );
    
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
    
                $this->upload->do_upload('fileToUpload');
                $fileName = $_FILES['fileToUpload']['name'];
                
                $add_array = array(
                    'title' => $title,
                    'image' => $fileName,
                    'type' => $type,
                    'category' => $category,
                    'subcategory' => $subcategory,
                    'video' => $video
                );
                
                $insert_banner = $this->Data_model->insert_banner($add_array);

                if($insert_banner){ ?>
                <script>
                    alert('Added Successfully');
                    window.location.href="<?php echo site_url('banner/view'); ?>";
                </script>
        <?php }else{ ?>
                <script>
                    alert('Upload Failed');
                </script>
          <?php 
              }
            }
        }
        
        public function edit($id){
            $data['banner'] = $this->Data_model->display_banner_by_id($id);
            
            $submit_btn = $this->input->post('submit');
            
            $this->load->view('admin/banner/edit', $data);
            
            if(isset($submit_btn)){
                $title = $this->input->post('title');
                $type = $this->input->post('type');
                $category = $this->input->post('category');
                $subcategory = $this->input->post('subcategory');
                $video = $this->input->post('video');
                
                $update_array = array(
                    'title' => $title,
                    'type' => $type,
                    'category' => $category,
                    'subcategory' => $subcategory,
                    'video' => $video
                );
                
                $update_data = $this->Data_model->update_banner($id, $update_array);
                
                if($update_data){ ?>
                <script>
                    alert('Updated Successfully');
                    window.location.href="<?php echo site_url('banner/view'); ?>";
                </script>
          <?php
              }else{ ?>
                <script>
                    alert('Update Failed');
                    window.location.href="<?php echo site_url('banner/edit/'.$id); ?>";
                </script>
          <?php 
               }  
            }
            
        }
        
        public function edit_image($id){
          $submit_btn = $this->input->post('submit_image');
          
          if(isset($submit_btn)){
              $files = $_FILES;
              $cpt1 = count($_FILES['fileToUpload']['name']);
        
              for($i=0; $i<$cpt1; $i++){
                $_FILES['fileToUpload']['name']= $files['fileToUpload']['name'][$i];
                $_FILES['fileToUpload']['type']= $files['fileToUpload']['type'][$i];
                $_FILES['fileToUpload']['tmp_name']= $files['fileToUpload']['tmp_name'][$i];
                $_FILES['fileToUpload']['error']= $files['fileToUpload']['error'][$i];
                $_FILES['fileToUpload']['size']= $files['fileToUpload']['size'][$i];
    
                $config1 = array(
                    'upload_path'   => "../uploads/banner/",
                    'allowed_types' => "gif|jpg|png|jpeg",
                    'overwrite'     => TRUE,
                    'max_size'      => "3000",  // Can be set to particular file size
                    //'max_height'    => "768",
                    //'max_width'     => "1024"
                );
    
                $this->load->library('upload', $config1);
                $this->upload->initialize($config1);
    
                $this->upload->do_upload('fileToUpload');
                $fileName = $_FILES['fileToUpload']['name'];
              }
              
              $update_array = array('image' => $fileName);
    
              $update_image = $this->Data_model->update_banner($id, $update_array);
    
              if($update_image){ ?>
                <script>
                    alert('Updated Successfully');
                    window.location.href="<?php echo site_url('banner/view'); ?>";
                </script>
          <?php
              }else{
                $msgError = '<div class="alert alert-danger>Upload Failed</div>';
                $this->session->set_flashdata('msgError', $msgError); ?>
                <script>
                    alert('Update Failed');
                    window.location.href="<?php echo site_url('banner/edit/'.$id); ?>";
                </script>
          <?php 
            }  
          }
        }
        
        public function delete(){
           $id = $this->input->post('del_id');
           $this->Data_model->delete_banner($id); 
        }
    }

?>