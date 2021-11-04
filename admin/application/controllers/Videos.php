<?php 

    class Videos extends CI_Controller{
        
        public function view(){
            $data['videos'] = $this->Data_model->display_all_videos();
            
            $this->load->view('admin/videos/view', $data);
        }
        
        public function add(){
            $this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[3]');
            $this->form_validation->set_rules('type', 'Type', 'trim|required|min_length[3]');
            $this->form_validation->set_rules('url', 'URL', 'trim|required');
    
            $form_valid = $this->form_validation->run();
            $submit_btn = $this->input->post('submit');
            
            if($form_valid == FALSE){
              $this->load->view('admin/videos/add');
            }
            
            if(isset($submit_btn)){
                $title = $this->input->post('title');
                $type = $this->input->post('type');
                $url = $this->input->post('url');
                $playlist = $this->input->post('playlist');
                
                $add_array = array(
                    'title' => $title,
                    'type' => $type,
                    'url' => $url,
                    'playlist' => $playlist
                );
                
                $insert_videos = $this->Data_model->insert_videos($add_array);

                if($insert_videos){ ?>
                <script>
                    alert('Added Successfully');
                    window.location.href="<?php echo site_url('videos/view'); ?>";
                </script>
        <?php }else{ ?>
                <script>
                    alert('Upload Failed');
                    window.location.href="<?php echo site_url('videos/add'); ?>";
                </script>
          <?php 
              }
            }
        }
        
        public function edit($id){
            $data['videos'] = $this->Data_model->display_videos_by_id($id);
            
            $submit_btn = $this->input->post('submit');
            
            $this->load->view('admin/videos/edit', $data);
            
            if(isset($submit_btn)){
                $title = $this->input->post('title');
                $type = $this->input->post('type');
                $url = $this->input->post('url');
                $playlist = $this->input->post('playlist');
                
                $update_array = array(
                    'title' => $title,
                    'type' => $type,
                    'url' => $url,
                    'playlist' => $playlist
                );
                
                $update_data = $this->Data_model->update_videos($id, $update_array);
                
                if($update_data){ ?>
                <script>
                    alert('Updated Successfully');
                    window.location.href="<?php echo site_url('videos/view'); ?>";
                </script>
          <?php
              }else{ ?>
                <script>
                    alert('Update Failed');
                    window.location.href="<?php echo site_url('videos/edit/'.$id); ?>";
                </script>
          <?php 
               }  
            }
            
        }
        
        public function delete(){
           $id = $this->input->post('del_id');
           $this->Data_model->delete_videos($id); 
        }
    }

?>