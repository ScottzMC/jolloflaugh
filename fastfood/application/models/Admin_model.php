<?php

  class Admin_model extends CI_Model{

    // Dashboard

    public function display_user_count(){
      $query = $this->db->query("SELECT COUNT(*) AS user_count FROM users")->result();
      return $query;
    }

    public function display_order_count(){
      $query = $this->db->query("SELECT COUNT(*) AS order_count FROM order_items")->result();
      return $query;
    }

    public function display_food_count(){
      $query = $this->db->query("SELECT COUNT(*) AS food_count FROM food")->result();
      return $query;
    }

    public function display_all_users(){
      $query = $this->db->query("SELECT * FROM users ORDER BY created_date DESC LIMIT 5")->result();
      return $query;
    }

    public function display_all_food(){
      $query = $this->db->query("SELECT * FROM food ORDER BY created_date DESC LIMIT 5")->result();
      return $query;
    }
    
    public function display_all_message(){
      $this->db->limit('8');    
      $query = $this->db->get('message')->result();
      return $query;
    }
    
    public function display_message_grid(){
      $query = $this->db->get('message')->result();
      return $query;
    }
    
    public function display_message_pending_by_id($id){
      $this->db->where('id', $id);
      $this->db->where('status', 'Pending');
      $query = $this->db->get('message')->result();
      return $query;
    }
    
    public function display_message_completed_by_id($id){
      $this->db->where('id', $id);
      $this->db->where('status', 'Completed');
      $query = $this->db->get('message')->result();
      return $query;
    }
    
    public function display_message_rejected_by_id($id){
      $this->db->where('id', $id);
      $this->db->where('status', 'Rejected');
      $query = $this->db->get('message')->result();
      return $query;
    }
    
    public function complete_message($id){
      $query = $this->db->query("UPDATE message SET status = 'Completed' WHERE id = '$id' ");
      return $query;
    }
    
    public function reject_message($id){
      $query = $this->db->query("UPDATE message SET status = 'Rejected' WHERE id = '$id' ");
      return $query;
    }
    
    public function delete_message($id){
        $query = $this->db->query("DELETE FROM message WHERE id = '$id' ");
        return $query;
    }

    // End of Dashboard

    // Food

    public function record_food_count() {
        $query = $this->db->count_all("food");
        return $query;
    }
    
    public function record_jollof_n_laugh_count() {
        $this->db->where('type', 'jollof_n_laugh');
        $query = $this->db->count_all("food");
        return $query;
    }

    public function fetch_food_data($limit, $start){
        $this->db->limit($limit, $start);
        $this->db->order_by('created_date', 'DESC');
        $query = $this->db->get("food");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   }
   
   public function fetch_jollof_n_laugh_data($limit, $start){
        $this->db->limit($limit, $start);
        $this->db->where('type', 'jollof_n_laugh');
        $this->db->order_by('created_date', 'DESC');
        $query = $this->db->get("food");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   }

   public function display_food_by_id($id){
     $query = $this->db->query("SELECT * FROM food WHERE id = '$id' ")->result();
     return $query;
   }

   public function insert_food($data){
     $query = $this->db->insert('food', $data);
     return $query;
   }
   
   public function update_food($id, $data){
      $this->db->where('id', $id);
      $query = $this->db->update('food', $data);
      return $query;
    }

   public function update_food_image1($id, $image1){
     $query = $this->db->query("UPDATE food SET image1 = '$image1' WHERE id = '$id' ");
     return $query;
   }
   
   public function update_food_image2($id, $image2){
     $query = $this->db->query("UPDATE food SET image2 = '$image2' WHERE id = '$id' ");
     return $query;
   }
   
   public function update_food_image3($id, $image3){
     $query = $this->db->query("UPDATE food SET image3 = '$image3' WHERE id = '$id' ");
     return $query;
   }
   
   public function update_food_image4($id, $image4){
     $query = $this->db->query("UPDATE food SET image4 = '$image4' WHERE id = '$id' ");
     return $query;
   }
   
   public function update_food_image5($id, $image5){
     $query = $this->db->query("UPDATE food SET image5 = '$image5' WHERE id = '$id' ");
     return $query;
   }

   public function delete_food($id){
      $query = $this->db->query("DELETE FROM food WHERE id = '$id' ");
   }

    // End of Food
    
    // User Grid

    public function display_user_grid(){
      $query = $this->db->query("SELECT * FROM users ORDER BY created_date DESC ")->result();
      return $query;
    }

    // End of User Grid

    // Orders
    
    public function cancel_order($id, $status){
      $query = $this->db->query("UPDATE order_items SET status = '$status' WHERE id = '$id' ");
    }

    public function delivering_order($id, $status){
      $query = $this->db->query("UPDATE order_items SET status = '$status' WHERE id = '$id' ");
    }
    
    public function pending_order($id, $status){
      $query = $this->db->query("UPDATE order_items SET status = '$status' WHERE id = '$id' ");
    }

    public function delivered_order($id, $status){
      $query = $this->db->query("UPDATE order_items SET status = '$status' WHERE id = '$id' ");
    }

    public function delete_order($id){
      $query = $this->db->query("DELETE FROM order_items WHERE id = '$id' ");
    }
    
    public function delete_order_details($order_id){
      $query = $this->db->query("DELETE FROM order_details WHERE order_id = '$order_id' ");
    }

    public function display_all_pending_orders(){
      $this->db->order_by('created_date', 'ASC');
      $this->db->where('status', 'Pending');
      $query = $this->db->get('order_items')->result();
      return $query;
    }
    
    public function display_all_pending_order_by_id($id){
      $this->db->order_by('created_date', 'ASC');
      $this->db->where('status', 'Pending');
      $this->db->where('id', $id);
      $query = $this->db->get('order_items')->result();
      return $query;
    }
    
    public function display_all_delivering_orders(){
      $this->db->order_by('created_date', 'ASC');
      $this->db->where('status', 'Delivering');
      $query = $this->db->get('order_items')->result();
      return $query;
    }
    
    public function display_all_delivering_order_by_id($id){
      $this->db->order_by('created_date', 'ASC');
      $this->db->where('status', 'Delivering');
      $this->db->where('id', $id);
      $query = $this->db->get('order_items')->result();
      return $query;
    }
    
    public function display_all_delivered_orders(){
      $this->db->order_by('created_date', 'ASC');
      $this->db->where('status', 'Delivered');
      $query = $this->db->get('order_items')->result();
      return $query;
    }
    
    public function display_all_delivered_order_by_id($id){
      $this->db->order_by('created_date', 'ASC');
      $this->db->where('status', 'Delivered');
      $this->db->where('id', $id);
      $query = $this->db->get('order_items')->result();
      return $query;
    }
    
    public function display_all_cancelled_orders(){
      $this->db->order_by('created_date', 'ASC');
      $this->db->where('status', 'Cancelled');
      $query = $this->db->get('order_items')->result();
      return $query;
    }
    
    public function display_all_refunded_orders(){
      $this->db->order_by('created_date', 'ASC');
      $this->db->where('status', 'Refunded');
      $query = $this->db->get('order_items')->result();
      return $query;
    }
    
    public function update_order_items_to_refund($id, $array){
        $this->db->where('charge_id', $id);
        $query = $this->db->update('order_items', $array);
        return $query;
    }
    
    public function display_all_order_details(){
      $query = $this->db->query("SELECT order_items.id, order_items.order_id, order_items.email, order_items.title, order_items.price, order_items.quantity, order_items.seat_type, order_items.delivery_category,
      order_items.status, order_items.order_notes, order_items.created_time, order_items.created_date, order_details.order_id, order_details.firstname, order_details.lastname, order_details.telephone, 
      order_details.address, order_details.town, order_details.postcode FROM order_items INNER JOIN order_details ON order_items.order_id = order_details.order_id WHERE order_items.status = 'Delivered' 
      ORDER BY order_items.created_date ASC")->result();
      return $query;
    }

    // End of Orders
    
    // Vouchers 
    
    public function display_all_vouchers(){
        $query = $this->db->get('vouchers')->result();
        return $query;
    }
    
    public function display_all_meal_vouchers(){
        $query = $this->db->get('meal_vouchers')->result();
        return $query;
    }
    
    public function display_vouchers_by_id($id){
        $this->db->where('id', $id);
        $query = $this->db->get('vouchers')->result();
        return $query;
    }
    
    public function display_meal_vouchers_by_id($id){
        $this->db->where('id', $id);
        $query = $this->db->get('meal_vouchers')->result();
        return $query;
    }
    
    public function insert_voucher($data){
        $query = $this->db->insert('vouchers', $data);
        return $query;
    }
    
    public function insert_meal_voucher($data){
        $query = $this->db->insert('meal_vouchers', $data);
        return $query;
    }
    
    public function update_voucher($id, $data){
        $this->db->where('id', $id);
        $query = $this->db->update('vouchers', $data);
        return $query;
    }
    
    public function update_meal_voucher($id, $data){
        $this->db->where('id', $id);
        $query = $this->db->update('meal_vouchers', $data);
        return $query;
    }
    
    public function update_meal_voucher_details($code, $data){
        $this->db->where('code', $code);
        $query = $this->db->update('meal_voucher_details', $data);
        return $query;
    }
    
    public function delete_voucher($id){
      $query = $this->db->query("DELETE FROM vouchers WHERE id = '$id' ");
    }
    
    public function delete_meal_voucher($id){
      $query = $this->db->query("DELETE FROM meal_vouchers WHERE id = '$id' ");
    }
    
    // End of Vouchers 
    
    // Company 
    
    public function display_all_company(){
      $this->db->order_by('title', 'ASC');
      $query = $this->db->get("company")->result();
      return $query;
    }
    
    public function display_company_by_id($id){
        $this->db->where('id', $id);
        $query = $this->db->get('company')->result();
        return $query;
    }
    
    public function update_company($id, $data){
        $this->db->where('id', $id);
        $query = $this->db->update('company', $data);
        return $query;
    }
    
    public function update_business($id, $data){
        $this->db->where('id', $id);
        $query = $this->db->update('business', $data);
        return $query;
    }
    
    public function update_company_address($id, $data){
        $this->db->where('id', $id);
        $query = $this->db->update('company_address', $data);
        return $query;
    }
    
    public function display_all_company_address(){
      $this->db->order_by('company', 'ASC');
      $query = $this->db->get("company_address")->result();
      return $query;
    }
    
    public function display_company_address_by_id($id){
        $this->db->where('id', $id);
        $query = $this->db->get('company_address')->result();
        return $query;
    }
    
    public function insert_company($data){
      $query = $this->db->insert('company', $data);
      return $query;
    }
    
    public function insert_company_address($data){
      $query = $this->db->insert('company_address', $data);
      return $query;
    }
    
    public function delete_company($id){
      $query = $this->db->query("DELETE FROM company WHERE id = '$id' ");
    }
    
    public function delete_company_address($id){
      $query = $this->db->query("DELETE FROM company_address WHERE id = '$id' ");
    }
    
    // End of Company 
    
    // Staff
    
    public function display_staff_grid(){
      $query = $this->db->query("SELECT * FROM staff ORDER BY created_date DESC ")->result();
      return $query;
    }
    
    // End of Staff

     // Edit Banners 

    public function get_menu_banner_category(){
      //$this->db->where('type', $type);
      $query = $this->db->get('menu')->result();
      return $query;
    }

    public function display_banners(){
      $query = $this->db->get('banner')->result();
      return $query;
    }

    public function display_banners_by_id($id){
      $this->db->where('id', $id);
      $query = $this->db->get('banner')->result();
      return $query;
    }

    public function update_banner_image($id, $image){
      $query = $this->db->query("UPDATE banner SET image = '$image' WHERE id = '$id' ");
      return $query;
    }

    public function update_banner_title($id, $title){
      $query = $this->db->query("UPDATE banner SET title = '$title' WHERE id = '$id' ");
      return $query;
    }
    
    public function update_banner_type($id, $type){
      $query = $this->db->query("UPDATE banner SET type = '$type' WHERE id = '$id' ");
      return $query;
    }

    public function insert_banner($data){
      $query = $this->db->insert('banner', $data);
      return $query;
    }

    public function delete_banner($id){
      $query = $this->db->query("DELETE FROM banner WHERE id = '$id' ");
      return $query;
    }

     // End of Edit Banners
     
      // Edit Sliders 

    public function get_menu_slider_category(){
      //$this->db->where('type', $type);
      $query = $this->db->get('menu')->result();
      return $query;
    }

    public function display_sliders(){
      $query = $this->db->get('slider')->result();
      return $query;
    }

    public function display_slider_by_id($id){
      $this->db->where('id', $id);
      $query = $this->db->get('slider')->result();
      return $query;
    }

    public function update_slider_image($id, $image){
      $query = $this->db->query("UPDATE slider SET image = '$image' WHERE id = '$id' ");
      return $query;
    }

    public function update_slider_category($id, $category){
      $query = $this->db->query("UPDATE slider SET category = '$category' WHERE id = '$id' ");
      return $query;
    }
    
    public function update_slider_subtitle($id, $subtitle){
      $query = $this->db->query("UPDATE slider SET subtitle = '$subtitle' WHERE id = '$id' ");
      return $query;
    }
    
    public function update_slider_title($id, $title){
      $query = $this->db->query("UPDATE slider SET title = '$title' WHERE id = '$id' ");
      return $query;
    }

    public function insert_slider($data){
      $query = $this->db->insert('slider', $data);
      return $query;
    }

    public function delete_slider($id){
      $query = $this->db->query("DELETE FROM slider WHERE id = '$id' ");
      return $query;
    }

     // End of Edit Sliders

     // Edit Menu

     public function display_menu(){
      $query = $this->db->get('menu')->result();
      return $query;
    }

    public function display_menu_by_id($id){
      $this->db->where('id', $id);
      $query = $this->db->get('menu')->result();
      return $query;
    }

    public function insert_menu($data){
      $query = $this->db->insert('menu', $data);
      return $query;
    }

    public function update_category_info($id, $category){
      $query = $this->db->query("UPDATE menu SET category = '$category' WHERE id = '$id' ");
      return $query;
    }

    public function delete_menu($id){
      $this->db->where('id', $id);
      $query = $this->db->delete("menu");
      return $query;
    }

     // End of Edit Menu
     
     // Seating 
     
     public function display_seating(){
      $query = $this->db->get('seating')->result();
      return $query;
    }

    public function display_seating_by_id($id){
      $this->db->where('id', $id);
      $query = $this->db->get('seating')->result();
      return $query;
    }

    public function insert_seating($data){
      $query = $this->db->insert('seating', $data);
      return $query;
    }
    
    public function update_seating($id, $title){
      $query = $this->db->query("UPDATE seating SET title = '$title' WHERE id = '$id' ");
      return $query;
    }
    
    public function delete_seating($id){
      $this->db->where('id', $id);
      $query = $this->db->delete("seating");
      return $query;
    }
     
     // End of Seating
     
     // Edit Side Meal
     
     public function display_side_meal_menu(){
       $query = $this->db->query("SELECT DISTINCT category FROM side_meal")->result();
       return $query;  
     }

     public function display_side_meal(){
      $query = $this->db->get('side_meal')->result();
      return $query;
    }

    public function display_side_meal_by_id($id){
      $this->db->where('id', $id);
      $query = $this->db->get('side_meal')->result();
      return $query;
    }

    public function insert_side_meal($data){
      $query = $this->db->insert('side_meal', $data);
      return $query;
    }

    public function update_side_meal_category($id, $data){
      //$query = $this->db->query("UPDATE side_meal SET category = '$category' WHERE id = '$id' ");
      $this->db->where('id', $id);
      $query = $this->db->update('side_meal', $data);
      return $query;
    }

    public function delete_side_meal($id){
      $this->db->where('id', $id);
      $query = $this->db->delete("side_meal");
      return $query;
    }

     // End of Edit Side Meal
     
     // Edit Side Drink
     
     public function display_side_drink_menu(){
       $query = $this->db->query("SELECT DISTINCT category FROM side_drink")->result();
       return $query;  
     }

     public function display_side_drink(){
      $query = $this->db->get('side_drink')->result();
      return $query;
    }

    public function display_side_drink_by_id($id){
      $this->db->where('id', $id);
      $query = $this->db->get('side_drink')->result();
      return $query;
    }

    public function insert_side_drink($data){
      $query = $this->db->insert('side_drink', $data);
      return $query;
    }

    public function update_side_drink_category($id, $data){
      //$query = $this->db->query("UPDATE side_meal SET category = '$category' WHERE id = '$id' ");
      $this->db->where('id', $id);
      $query = $this->db->update('side_drink', $data);
      return $query;
    }

    public function delete_side_drink($id){
      $this->db->where('id', $id);
      $query = $this->db->delete("side_drink");
      return $query;
    }

     // End of Edit Side Drink

     // Edit Sorting
     /*

     public function display_sorting(){
      $query = $this->db->get('sorting')->result();
      return $query;
    }

    public function display_sorting_by_id($id){
      $this->db->where('id', $id);
      $query = $this->db->get('sorting')->result();
      return $query;
    }

    public function update_sorting_type_info($id, $type){
      $this->db->where('id', $id);
      $query = $this->db->query("UPDATE sorting SET type = '$type' ");
      return $query;
    }

    public function update_sorting_info($id, $sorting){
      $query = $this->db->query("UPDATE sorting SET sort = '$sorting' WHERE id = '$id' ");
      return $query;
    }

    public function update_options_info($id, $options){
      $query = $this->db->query("UPDATE sorting SET options = '$options' WHERE id = '$id' ");
      return $query;
    }

    public function insert_sorting($data){
      $query = $this->db->insert('sorting', $data);
      return $query;
    }

     public function delete_sorting($id){
       $this->db->where('id', $id);
       $query = $this->db->delete("sorting");
       return $query;
     }

     // End of Sorting

     // Edit About Us

     public function display_about_content(){
      $query = $this->db->get('about_content')->result();
      return $query;
    }

    public function display_about_content_by_id($id){
      $this->db->where('id', $id);
      $query = $this->db->get('about_content')->result();
      return $query;
    }

    public function delete_about_content($id){
      $this->db->where('id', $id);
      $query = $this->db->delete("about_content");
      return $query;
    }

    public function insert_about_content($data){
      $query = $this->db->insert('about_content', $data);
      return $query;
    }

    public function update_about_content_info($id, $body){
      $this->db->where('id', $id);
      $query = $this->db->query("UPDATE about_content SET body = '$body' ");
      return $query;
    }

    public function update_about_title_info($id, $title){
      $this->db->where('id', $id);
      $query = $this->db->query("UPDATE about_content SET title = '$title' ");
      return $query;
    }

     // End Edit About Us

     // Edit Faq

     public function display_faq_content(){
      $query = $this->db->get('faq_content')->result();
      return $query;
    }

    public function display_faq_content_by_id($id){
      $this->db->where('id', $id);
      $query = $this->db->get('faq_content')->result();
      return $query;
    }

    public function delete_faq_content($id){
      $this->db->where('id', $id);
      $query = $this->db->delete("faq_content");
      return $query;
    }

    public function insert_faq_content($data){
      $query = $this->db->insert('faq_content', $data);
      return $query;
    }

    public function update_faq_content_info($id, $body){
      $this->db->where('id', $id);
      $query = $this->db->query("UPDATE faq_content SET body = '$body' ");
      return $query;
    }

    public function update_faq_title_info($id, $title){
      $this->db->where('id', $id);
      $query = $this->db->query("UPDATE faq_content SET title = '$title' ");
      return $query;
    }

     // End Edit Faq

     // Edit Policy

     public function display_policy_content(){
      $query = $this->db->get('return_policy')->result();
      return $query;
    }

    public function display_policy_content_by_id($id){
      $this->db->where('id', $id);
      $query = $this->db->get('return_policy')->result();
      return $query;
    }

    public function delete_policy_content($id){
      $this->db->where('id', $id);
      $query = $this->db->delete("return_policy");
      return $query;
    }

    public function insert_policy_content($data){
      $query = $this->db->insert('return_policy', $data);
      return $query;
    }

    public function update_policy_content_info($id, $body){
      $this->db->where('id', $id);
      $query = $this->db->query("UPDATE return_policy SET body = '$body' ");
      return $query;
    }

    public function update_policy_title_info($id, $title){
      $this->db->where('id', $id);
      $query = $this->db->query("UPDATE return_policy SET title = '$title' ");
      return $query;
    }

     // End Edit Policy

     // Edit Footer

     public function display_footer(){
      $query = $this->db->get('footer')->result();
      return $query;
    }

    public function display_footer_by_id($id){
      $this->db->where('id', $id);
      $query = $this->db->get('footer')->result();
      return $query;
    }

    public function insert_footer($data){
      $query = $this->db->insert('footer', $data);
      return $query;
    }

    public function update_address_info($id, $address){
      $this->db->where('id', $id);
      $query = $this->db->query("UPDATE footer SET address = '$address' ");
      return $query;
    }

    public function update_telephone_info($id, $telephone){
      $this->db->where('id', $id);
      $query = $this->db->query("UPDATE footer SET telephone = '$telephone' ");
      return $query;
    }

    public function update_email_info($id, $email){
      $this->db->where('id', $id);
      $query = $this->db->query("UPDATE footer SET email = '$email' ");
      return $query;
    }

    public function delete_footer($id){
      $this->db->where('id', $id);
      $query = $this->db->delete("footer");
      return $query;
    }

     // End of Footer

     // Social

     public function display_social(){
      $query = $this->db->get('social_link')->result();
      return $query;
    }

    public function display_social_by_id($id){
      $this->db->where('id', $id);
      $query = $this->db->get('social_link')->result();
      return $query;
    }

    public function insert_social($data){
      $query = $this->db->insert('social_link', $data);
      return $query;
    }

    public function update_social_info($id, $social){
      $this->db->where('id', $id);
      $query = $this->db->query("UPDATE social_link SET social = '$social' ");
      return $query;
    }

    public function update_url_info($id, $url){
      $this->db->where('id', $id);
      $query = $this->db->query("UPDATE social_link SET url = '$url' ");
      return $query;
    }

    public function delete_social($id){
      $this->db->where('id', $id);
      $query = $this->db->delete("social_link");
      return $query;
    }

     // End of Social
    */
  }

?>
