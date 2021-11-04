<?php 

    class Venue extends CI_Controller{
        
        public function view(){
            $data['venue'] = $this->Data_model->display_all_venue();
            
            $this->load->view('admin/venue/view', $data);
        }
        
        public function add(){
            $this->form_validation->set_rules('title', 'Title', 'trim|required');
            $this->form_validation->set_rules('body', 'Description', 'trim|required');
            $this->form_validation->set_rules('maps', 'Google Maps', 'trim|required');
    
            $form_valid = $this->form_validation->run();
            $submit_btn = $this->input->post('submit');
            
            if($form_valid == FALSE){
              $this->load->view('admin/venue/add');
            }
            
            if(isset($submit_btn)){
                $title = $this->input->post('title');
                $body = $this->input->post('body');
                $maps = $this->input->post('maps');
                
                $date = date('Y-m-d H:i:s');
                
                $files = $_FILES;
                
                $cpt1 = count($_FILES['fileToUpload']['name']);
                $cpt2 = count($_FILES['fileUpload']['name']);
                
                for($i=0; $i<$cpt1; $i++){
                    $_FILES['fileToUpload']['name']= $files['fileToUpload']['name'][$i];
                    $_FILES['fileToUpload']['type']= $files['fileToUpload']['type'][$i];
                    $_FILES['fileToUpload']['tmp_name']= $files['fileToUpload']['tmp_name'][$i];
                    $_FILES['fileToUpload']['error']= $files['fileToUpload']['error'][$i];
                    $_FILES['fileToUpload']['size']= $files['fileToUpload']['size'][$i];
               }
               
               for($i=0; $i<$cpt2; $i++){
                    $_FILES['fileUpload']['name']= $files['fileUpload']['name'][$i];
                    $_FILES['fileUpload']['type']= $files['fileUpload']['type'][$i];
                    $_FILES['fileUpload']['tmp_name']= $files['fileUpload']['tmp_name'][$i];
                    $_FILES['fileUpload']['error']= $files['fileUpload']['error'][$i];
                    $_FILES['fileUpload']['size']= $files['fileUpload']['size'][$i];
               }
               
               $config = array(
                //'upload_path'   => "../uploads/banner/../../jollof_n_laugh/uploads/banner/",
                'upload_path'   => "../uploads/venue/",
                'allowed_types' => "gif|jpg|png|jpeg",
                'overwrite'     => TRUE,
                'max_size'      => "3000",  // Can be set to particular file size
                //'max_height'    => "768",
                //'max_width'     => "1024"
                );
    
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
    
                $this->upload->do_upload('fileToUpload');
                $this->upload->do_upload('fileUpload');
                
                $fileName = $_FILES['fileToUpload']['name'];
                $fileUpload = $_FILES['fileUpload']['name'];
                
                $add_array = array(
                    'title' => $title,
                    'body' => $body,
                    'image1' => $fileName,
                    'image2' => $fileUpload,
                    'maps' => $maps,
                    'created_date' => $date
                );
                
                $insert_venue = $this->Data_model->insert_venue($add_array);

                if($insert_venue){ ?>
                <script>
                    alert('Added Successfully');
                    window.location.href="<?php echo site_url('venue/view'); ?>";
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
            $data['venue'] = $this->Data_model->display_venue_by_id($id);
            
            $submit_btn = $this->input->post('submit');
            
            $this->load->view('admin/venue/edit', $data);
            
            if(isset($submit_btn)){
                $title = $this->input->post('title');
                $body = $this->input->post('body');
                $maps = $this->input->post('maps');
                
                $update_array = array(
                    'title' => $title,
                    'body' => $body,
                    'maps' => $maps
                );
                
                $update_data = $this->Data_model->update_venue($id, $update_array);
                
                if($update_data){ ?>
                <script>
                    alert('Updated Successfully');
                    window.location.href="<?php echo site_url('venue/view'); ?>";
                </script>
          <?php
              }else{ ?>
                <script>
                    alert('Update Failed');
                    window.location.href="<?php echo site_url('venue/edit/'.$id); ?>";
                </script>
          <?php 
               }  
            }
            
        }
        
        public function edit_image1($id){
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
               }
               
               $config = array(
                //'upload_path'   => "../uploads/banner/../../jollof_n_laugh/uploads/banner/",
                'upload_path'   => "../uploads/venue/",
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

              $update_array = array('image1' => $fileName);
    
              $update_image = $this->Data_model->update_venue($id, $update_array);
    
              if($update_image){ ?>
                <script>
                    alert('Updated Successfully');
                    window.location.href="<?php echo site_url('venue/view'); ?>";
                </script>
          <?php
              }else{
                $msgError = '<div class="alert alert-danger>Upload Failed</div>';
                $this->session->set_flashdata('msgError', $msgError); ?>
                <script>
                    alert('Update Failed');
                    window.location.href="<?php echo site_url('venue/edit/'.$id); ?>";
                </script>
          <?php 
            }  
          }
        }
        
        public function edit_image2($id){
          $submit_btn = $this->input->post('submit_image2');
          
          if(isset($submit_btn)){
              $files = $_FILES;
              $cpt2 = count($_FILES['fileUpload']['name']);
               
              for($i=0; $i<$cpt2; $i++){
                    $_FILES['fileUpload']['name']= $files['fileUpload']['name'][$i];
                    $_FILES['fileUpload']['type']= $files['fileUpload']['type'][$i];
                    $_FILES['fileUpload']['tmp_name']= $files['fileUpload']['tmp_name'][$i];
                    $_FILES['fileUpload']['error']= $files['fileUpload']['error'][$i];
                    $_FILES['fileUpload']['size']= $files['fileUpload']['size'][$i];
               }
               
               $config = array(
                //'upload_path'   => "../uploads/banner/../../jollof_n_laugh/uploads/banner/",
                'upload_path'   => "../uploads/venue/",
                'allowed_types' => "gif|jpg|png|jpeg",
                'overwrite'     => TRUE,
                'max_size'      => "3000",  // Can be set to particular file size
                //'max_height'    => "768",
                //'max_width'     => "1024"
                );
    
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
    
                $this->upload->do_upload('fileUpload');
                
                $fileUpload = $_FILES['fileUpload']['name'];
              
              $update_array = array('image2' => $fileUpload);
    
              $update_image = $this->Data_model->update_venue($id, $update_array);
    
              if($update_image){ ?>
                <script>
                    alert('Updated Successfully');
                    window.location.href="<?php echo site_url('venue/view'); ?>";
                </script>
          <?php
              }else{
                $msgError = '<div class="alert alert-danger>Upload Failed</div>';
                $this->session->set_flashdata('msgError', $msgError); ?>
                <script>
                    alert('Update Failed');
                    window.location.href="<?php echo site_url('venue/edit/'.$id); ?>";
                </script>
          <?php 
            }  
          }
        }
        
        public function delete(){
           $id = $this->input->post('del_id');
           $this->Data_model->delete_venue($id); 
        }
    }

?>