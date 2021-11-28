<?php 
    
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
    
?>

<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Shopping Checkout || Fast Food</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('assets/images/favicon.png'); ?>">

    <!-- All CSS is here
	============================================ -->

    <link rel="stylesheet" href="<?php echo base_url('assets/css/vendor/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/vendor/signericafat.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/vendor/cerebrisans.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/vendor/simple-line-icons.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/vendor/elegant.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/vendor/linear-icon.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/plugins/nice-select.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/plugins/easyzoom.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/plugins/slick.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/plugins/animate.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/plugins/magnific-popup.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/plugins/jquery-ui.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">

    <!-- Use the minified version files listed below for better performance and remove the files listed above
    <link rel="stylesheet" href="assets/css/vendor/vendor.min.css">
    <link rel="stylesheet" href="assets/css/plugins/plugins.min.css">
    <link rel="stylesheet" href="assets/css/style.min.css"> -->

</head>

<body>
    
    <?php $company = $this->session->userdata('ucompany'); ?>

    <div class="main-wrapper">
        <?php include 'menu/nav.php'; ?>
        
        <div class="breadcrumb-area bg-gray">
            <div class="container">
                <div class="breadcrumb-content text-center">
                    <ul>
                        <li>
                            <a href="<?php echo site_url('staff/home/'.$company); ?>">Home</a>
                        </li>
                        <li class="active">Shopping Checkout </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="checkout-main-area pt-120 pb-120">
            <div class="container">
                
                <div class="customer-zone mb-20">
                    <p class="cart-page-title">Have a voucher? <a class="checkout-click3" href="#">Click here to enter your code</a></p>
                    <div class="checkout-login-info3">
                        <form action="<?php echo base_url('staff/use_voucher/'.$company); ?>" method="POST">
                            <!--<input type="text" name="voucher">-->
                            <select name="voucher">
                                <option>Select your available vouchers</option>
                                <?php if(!empty($vouchers)){ foreach($vouchers as $vouch){ ?>
                                <option><?php echo $vouch->title; ?></option>
                                <?php } }else{ ?>
                                <option>No available vouchers</option>
                                <?php } ?>
                            </select>
                            <br><button type="submit" name="submit" class="cart-btn-2">Apply Coupon</button>
                        </form>
                    </div>
                </div>
                
                <?php foreach($staff_detail as $stff_det){} ?>
                
                <?php
                $cart_total = $this->cart->total();
                
                $session_email = $this->session->userdata('uemail');
                
                $query = $this->db->query("SELECT DISTINCT id, code, title, price FROM temp_vouchers WHERE email = '$session_email' ")->result(); 
                foreach($query as $qry){
                    $current_voucher = $qry->title;
                    $voucher_id = $qry->id;
                    $voucher_code = $qry->code;
                    $voucher_price = $qry->price;
                }
                
                ?>
                
                <div class="checkout-wrap pt-30">
                  <form action="<?php echo base_url('staff/place_order/'.$company); ?>" method="POST">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="billing-info-wrap mr-50">
                                <h3>Billing Details</h3>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <div class="billing-info mb-20">
                                            <?php if(!empty($query)){ ?>
                                            <input type="hidden" name="voucher_id" value="<?php echo $voucher_id; ?>">
                                            <input type="hidden" name="voucher_code" value="<?php echo $voucher_code; ?>">
                                            <?php } ?>
                                            
                                            <label>First Name <abbr class="required" title="required">*</abbr></label>
                                            <?php if(!empty($stff_det->firstname)){ ?>
                                            <input type="text" name="firstname" value="<?php echo $stff_det->firstname; ?>">
                                            <?php }else{ ?>
                                            <input type="text" name="firstname">
                                            <?php } ?>
                                            <span class="text-danger" style="color: red;"><?php echo form_error('firstname'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="billing-info mb-20">
                                            <label>Last Name <abbr class="required" title="required">*</abbr></label>
                                            <?php if(!empty($stff_det->lastname)){ ?>
                                            <input type="text" name="lastname" value="<?php echo $stff_det->lastname; ?>">
                                            <?php }else{ ?>
                                            <input type="text" name="lastname">
                                            <?php } ?>
                                            <span class="text-danger" style="color: red;"><?php echo form_error('lastname'); ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-12">
                                        <div class="billing-info mb-20">
                                            <label>Address <abbr class="required" title="required">*</abbr></label>
                                            <?php $sequel = $this->db->query("SELECT DISTINCT delivery_address FROM company_address WHERE company = '$company' ORDER BY delivery_address ASC")->result(); 
                                            foreach($sequel as $sql){}
                                            ?>
                                            <?php if(!empty($stff_det->address)){ ?>
                                            <select name="address">
                                                <option>Select a delivery address</option>
                                                <?php foreach($sequel as $sql){ ?>
                                                <option value="<?php echo $sql->delivery_address; ?>"><?php echo $sql->delivery_address; ?></option>
                                                <?php } ?>
                                            </select>
                                            <?php }else{ ?>
                                            <select name="address">
                                                <option>Select a delivery address</option>
                                                <option value="<?php echo $stff_det->delivery_address; ?>"><?php echo $stff_det->address; ?></option>
                                            </select>
                                            <?php } ?>
                                            <span class="text-danger" style="color: red;"><?php echo form_error('address'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="billing-info mb-20">
                                            <label>Town / County <abbr class="required" title="required">*</abbr></label>
                                            <?php if(!empty($stff_det->town)){ ?>
                                            <input type="text" name="town" value="<?php echo $stff_det->town; ?>">
                                            <?php }else{ ?>
                                            <input type="text" name="town">
                                            <?php } ?>
                                            <span class="text-danger" style="color: red;"><?php echo form_error('town'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="billing-info mb-20">
                                            <label>Postcode <abbr class="required" title="required">*</abbr></label>
                                            <?php if(!empty($stff_det->postcode)){ ?>
                                            <input type="text" name="postcode" value="<?php echo $stff_det->postcode; ?>">
                                            <?php }else{ ?>
                                            <input type="text" name="postcode">
                                            <?php } ?>
                                            <span class="text-danger" style="color: red;"><?php echo form_error('postcode'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="billing-info mb-20">
                                            <label>Telephone number <abbr class="required" title="required">*</abbr></label>
                                            <?php if(!empty($stff_det->telephone)){ ?>
                                            <input type="text" name="telephone" value="<?php echo $stff_det->telephone; ?>">
                                            <?php }else{ ?>
                                            <input type="text" name="telephone">
                                            <?php } ?>
                                            <span class="text-danger" style="color: red;"><?php echo form_error('telephone'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="additional-info-wrap">
                                    <label>Order notes</label>
                                    <textarea name="order_notes" placeholder="Notes about your order, e.g. special notes for delivery.">none</textarea>
                                    <span class="text-danger" style="color: red;"><?php echo form_error('order_notes'); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-5">
                            <div class="your-order-area">
                                <h3>Your order</h3>
                                <div class="your-order-wrap gray-bg-4">
                                    <div class="your-order-info-wrap">
                                        <div class="your-order-info">
                                            <ul>
                                                <li>Food <span>Total</span></li>
                                            </ul>
                                        </div>
                                        <div class="your-order-middle">
                                            <ul>
                                                <div><?php echo $message; ?></div>
                                                  <?php if ($cart = $this->cart->contents()): ?>
                                                  <?php $grand_total = 0; $i = 1; ?>
                                                  <?php foreach($cart as $item):
                                                      $check = array_slice(explode(',', $item['image']), 0, 1);
                                
                                                      foreach($check as $image) {
                                                         $image;
                                                      }
                                                   ?>
                                                  <?php echo form_open('staff/update_cart'); ?>
                                                  <?php
                                                    echo form_hidden('cart['. $item['id'] .'][id]', $item['id']);
                                                    echo form_hidden('cart['. $item['id'] .'][rowid]', $item['rowid']);
                                                    echo form_hidden('cart['. $item['id'] .'][price]', $item['price']);
                                                    echo form_hidden('cart['. $item['id'] .'][qty]', $item['qty']);
                                                  ?>
                                                  <li><?php echo str_replace('-', ' ', $item['name']); ?> X <?php echo $item['qty']; ?> <span>£<?php echo $item['price']; ?> </span></li>
                                                 <?php form_close(); ?>
                                                <?php endforeach; ?>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                        
                                        <div class="your-order-info order-shipping">
                                            <ul>
                                                <li>Shipping <p>Free Shipping </p>
                                                </li>
                                            </ul>
                                        </div>
                                        
                                        <div class="your-order-info order-total">
                                            
                                            <ul>
                                                <?php if(!empty($query)){ 
                                                $cart_total = $this->cart->total() - $voucher_price;
                                                ?>
                                                <li>Total (via voucher) <span>£<?php echo $cart_total; ?></span></li>
                                                <?php }else{ ?>
                                                <li>Total <span>£<?php echo $this->cart->total(); ?></span></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                        
                                        <script>
                                            function deleteVoucher(id){
                                            var del_id = id;
                                            if(confirm("Are you sure you want to remove this voucher")){
                                            $.post('<?php echo base_url('staff/destroy_voucher'); ?>', {"del_id": del_id}, function(data){
                                              location.reload();
                                              $('#cte').html(data)
                                              });
                                            }
                                          }
                                        </script>
                                        <p id='cte'></p>
                                        
                                        <?php foreach($schedule as $sch){} ?>
                                        <div class="your-order-info order-shipping">
                                            <ul>
                                                <?php 
                                                //$addressFrom = 'M1 2GH';
                                                $addressFrom = $sch->postcode;
                                                //$addressFrom = 'IG11 9TR';
                                                $addressTo   = 'RM13 8NL';
                                                
                                                // Get distance in km
                                                $distance = getDistance($addressFrom, $addressTo, "K");
                                                
                                                if($distance <= '7.00 km'){
                                                ?>
                                                <li>
                                                    <div class="alert alert-success">
                                                       Food can be Delivered
                                                    </div>
                                                </li>
                                                <?php }else{ ?>
                                                <li>
                                                    <div class="alert alert-danger">
                                                        Food needs to be Collected
                                                    </div>
                                                </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                        
                                        <div class="your-order-info order-shipping">
                                            <?php if(!empty($query)){ ?>
                                            <ul>
                                                <li><?php echo $current_voucher; ?> 
                                                    <p>
                                                        <button type="button" onclick="deleteVoucher(<?php echo $voucher_id; ?>)" class="cart-btn-2">Remove Voucher</button> 
                                                    </p>
                                                </li>
                                            </ul>
                                            <?php }else{ echo ''; } ?>
                                        </div>
                                        
                                    </div>
                                    <div class="payment-method">
                                        <!--<div class="pay-top sin-payment">
                                            <input id="payment_method_1" class="input-radio" type="radio" value="cheque" checked="checked" name="payment_method">
                                            <label for="payment_method_1"> Direct Bank Transfer </label>
                                            <div class="payment-box payment_method_bacs">
                                                <p>Make your payment directly into our bank account. Please use your Order ID as the payment reference.</p>
                                            </div>
                                        </div>-->
                                        <div class="pay-top sin-payment">
                                            <input id="payment-method-2" class="input-radio" type="radio" value="cheque" name="payment_method">
                                            <label for="payment-method-2">Check payments</label>
                                            <div class="payment-box payment_method_bacs">
                                                <p>Make your payment directly into our bank account. Please use your Order ID as the payment reference.</p>
                                            </div>
                                        </div>
                                        <!--<div class="pay-top sin-payment">
                                            <input id="payment-method-3" class="input-radio" type="radio" value="cheque" name="payment_method">
                                            <label for="payment-method-3">Cash on delivery </label>
                                            <div class="payment-box payment_method_bacs">
                                                <p>Make your payment directly into our bank account. Please use your Order ID as the payment reference.</p>
                                            </div>
                                        </div>-->
                                        <!--<div class="pay-top sin-payment sin-payment-3">
                                            <input id="payment-method-4" class="input-radio" type="radio" value="cheque" name="payment_method">
                                            <label for="payment-method-4">PayPal <img alt="" src="assets/images/icon-img/payment.png"><a href="#">What is PayPal?</a></label>
                                            <div class="payment-box payment_method_bacs">
                                                <p>Make your payment directly into our bank account. Please use your Order ID as the payment reference.</p>
                                            </div>
                                        </div>-->
                                    </div>
                                </div>
                                <div class="Place-order">
                                    <button type="submit">Place Order</button>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    </form>
                    
                    <?php
                    	echo $this->session->flashdata('msg');
                    	echo $this->session->flashdata('msgError');
                    ?>
                </div>
            </div>
        </div>
        
        <?php include 'menu/footer.php'; ?>
        
    </div>

    <!-- All JS is here
============================================ -->

    <script src="<?php echo base_url('assets/js/vendor/modernizr-3.6.0.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/vendor/jquery-3.5.1.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/vendor/jquery-migrate-3.3.0.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/vendor/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/slick.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/jquery.syotimer.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/jquery.nice-select.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/wow.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/jquery-ui-touch-punch.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/jquery-ui.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/magnific-popup.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/sticky-sidebar.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/easyzoom.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/scrollup.js'); ?>"></script>

    <!-- Use the minified version files listed below for better performance and remove the files listed above
<script src="assets/js/vendor/vendor.min.js"></script>
<script src="assets/js/plugins/plugins.min.js"></script>  -->
    <!-- Main JS -->
    <script src="<?php echo base_url('assets/js/main.js'); ?>"></script>

</body>

</html>