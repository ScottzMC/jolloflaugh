<?php 

    class Jollof_n_laugh extends CI_Controller{
        
        public function view(){
            $data['events'] = $this->Data_model->display_all_jollof_n_laugh();
            
            $this->load->view('admin/jollof_n_laugh/view', $data);
        }
        
        public function add(){
            $this->form_validation->set_rules('title', 'Title', 'trim|required');
            $this->form_validation->set_rules('body', 'Description', 'trim|required');
            $this->form_validation->set_rules('video', 'Video URL', 'trim|required');
            $this->form_validation->set_rules('maps', 'Google Maps', 'trim|required');
    
            $form_valid = $this->form_validation->run();
            $submit_btn = $this->input->post('submit');
            
            if($form_valid == FALSE){
              $this->load->view('admin/jollof_n_laugh/add');
            }
            
            if(isset($submit_btn)){
                $code = $shuffle.$unique;
                $title = $this->input->post('title');
                $description = $this->input->post('description');
                $video = $this->input->post('video');
                $maps = $this->input->post('maps');
                
                $req_age = $this->input->post('req_age');
                $req_dress_code = $this->input->post('req_dress_code');
                $req_last_entry = $this->input->post('req_last_entry');
                $req_id_verified = $this->input->post('req_id_verified');
                
                $date = date('Y-m-d H:i:s');
                
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
                'upload_path'   => "../uploads/events/",
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
                    'code' => $code,
                    'title' => $title,
                    'description' => $description,
                    'image' => $fileName,
                    'maps' => $maps,
                    'video' => $video,
                    'req_age' => $req_age,
                    'req_dress_code' => $req_dress_code,
                    'req_last_entry' => $req_last_entry,
                    'req_id_verified' => $req_id_verified,
                    'created_date' => $date
                );
                
                $insert_events = $this->Data_model->insert_jollof_n_laugh($add_array);

                if($insert_events){ ?>
                <script>
                    alert('Added Successfully');
                    window.location.href="<?php echo site_url('jollof_n_laugh/view'); ?>";
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
            $data['event'] = $this->Data_model->display_jollof_n_laugh_by_id($id);
            
            $submit_btn = $this->input->post('submit');
            
            $this->load->view('admin/jollof_n_laugh/edit', $data);
            
            if(isset($submit_btn)){
                $title = $this->input->post('title');
                $description = $this->input->post('description');
                $video = $this->input->post('video');
                $maps = $this->input->post('maps');
                
                $req_age = $this->input->post('req_age');
                $req_dress_code = $this->input->post('req_dress_code');
                $req_last_entry = $this->input->post('req_last_entry');
                $req_id_verified = $this->input->post('req_id_verified');
                
                $update_array = array(
                    'title' => $title,
                    'description' => $description,
                    'maps' => $maps,
                    'video' => $video,
                    'req_age' => $req_age,
                    'req_dress_code' => $req_dress_code,
                    'req_last_entry' => $req_last_entry,
                    'req_id_verified' => $req_id_verified
                );
                
                $update_data = $this->Data_model->update_jollof_n_laugh($id, $update_array);
                
                if($update_data){ ?>
                <script>
                    alert('Updated Successfully');
                    window.location.href="<?php echo site_url('jollof_n_laugh/view'); ?>";
                </script>
          <?php
              }else{ ?>
                <script>
                    alert('Update Failed');
                    window.location.href="<?php echo site_url('jollof_n_laugh/edit/'.$id); ?>";
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
                    'upload_path'   => "../uploads/events/",
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
    
              $update_image = $this->Data_model->update_jollof_n_laugh($id, $update_array);
    
              if($update_image){ ?>
                <script>
                    alert('Updated Successfully');
                    window.location.href="<?php echo site_url('jollof_n_laugh/view'); ?>";
                </script>
          <?php
              }else{
                $msgError = '<div class="alert alert-danger>Upload Failed</div>';
                $this->session->set_flashdata('msgError', $msgError); ?>
                <script>
                    alert('Update Failed');
                    window.location.href="<?php echo site_url('jollof_n_laugh/edit/'.$id); ?>";
                </script>
          <?php 
            }  
          }
        }
        
        public function delete(){
           $id = $this->input->post('del_id');
           $this->Data_model->delete_jollof_n_laugh($id); 
        }
    }

?>