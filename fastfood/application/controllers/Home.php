<?php

  class Home extends CI_Controller{

    public function index(){
        
      if(!$this->cart->contents()){
		$data['message'] = '<p><div class="alert alert-danger" role="alert">Your cart is empty!</div></p>';
	  }else{
		$data['message'] = $this->session->flashdata('message');
	  }
	  
	  $email = $this->session->userdata('uemail');
		  
      $data['menu'] = $this->Data_model->display_menu_options();
      $data['offer_meal'] = $this->Data_model->display_meal_day();    
      $data['family_order'] = $this->Data_model->display_family_meal();   
      $data['slider'] = $this->Data_model->display_slider_by_home("home");
      $data['banner'] = $this->Data_model->display_banner_by_home("home");
      
      $data['schedule'] = $this->Data_model->display_schedule_date($email);
      //$data['time'] = $this->Data_model->display_schedule_time();
      
      $btn_submit = $this->input->post('btn_schedule');
      
      if(isset($btn_submit)){
         $delivery_date = $this->input->post('delivery_date'); 
         $num_time = $this->input->post('num_time'); 
         $postcode = $this->input->post('postcode');
         
         $array = array(
            'delivery_date' => date('dS M Y',strtotime($delivery_date)),    
            'delivery_day' => date('l',strtotime($delivery_date)),    
            'num_time' => $num_time,
            'postcode' => $postcode     
         );
         
         $delivery_data = $this->Data_model->update_schedule_date($array, $email);
         
         $data['miles'] = number_format($distance["miles"], 2);
        
        if($delivery_data){ 
         ?>
         <script>
             alert('Delivery date has been set');
             window.location.href="<?php echo site_url('home'); ?>";
         </script>
    <?php }else{ ?>
        <script>
             alert('Failed');
         </script>
    <?php } ?>
  <?php }
  
      //$addressFrom = $sch->postcode;
      $addressTo   = 'RM13 8NL';
  
      $data['distance'] = $this->getDistance($postcode, $addressTo, "K");
        
      $this->load->view('site/view', $data);
    }
    
    function getDistance($addressFrom, $addressTo, $unit = ''){
        // Google API key
        $apiKey = 'AIzaSyALsc0dOYYaHaiXImBpuy09vWaMsu0zaxA';
        
        // Change address format
        $formattedAddrFrom    = str_replace(' ', '+', $addressFrom);
        $formattedAddrTo     = str_replace(' ', '+', $addressTo);
        
        // Geocoding API request with start address
        $geocodeFrom = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddrFrom.'&sensor=false&key='.$apiKey);
        $outputFrom = json_decode($geocodeFrom);
        if(!empty($outputFrom->error_message)){
            return $outputFrom->error_message;
        }
        
        // Geocoding API request with end address
        $geocodeTo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddrTo.'&sensor=false&key='.$apiKey);
        $outputTo = json_decode($geocodeTo);
        if(!empty($outputTo->error_message)){
            return $outputTo->error_message;
        }
        
        // Get latitude and longitude from the geodata
        $latitudeFrom    = $outputFrom->results[0]->geometry->location->lat;
        $longitudeFrom    = $outputFrom->results[0]->geometry->location->lng;
        $latitudeTo        = $outputTo->results[0]->geometry->location->lat;
        $longitudeTo    = $outputTo->results[0]->geometry->location->lng;
        
        // Calculate distance between latitude and longitude
        $theta    = $longitudeFrom - $longitudeTo;
        $dist    = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
        $dist    = acos($dist);
        $dist    = rad2deg($dist);
        $miles    = $dist * 60 * 1.1515;
        
        // Convert unit and return distance
        $unit = strtoupper($unit);
        if($unit == "K"){
            return round($miles * 1.609344, 2).' km';
        }elseif($unit == "M"){
            return round($miles * 1609.344, 2).' meters';
        }else{
            return round($miles, 2).' miles';
        }
    }
    
  }

?>