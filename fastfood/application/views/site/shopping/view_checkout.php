<?php 
    
    foreach($distance as $dist){}
    
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
    
    <div class="main-wrapper">
        <?php include 'menu/nav.php'; ?>
        
        <div class="breadcrumb-area bg-gray">
            <div class="container">
                <div class="breadcrumb-content text-center">
                    <ul>
                        <li>
                            <a href="<?php echo site_url('home'); ?>">Home</a>
                        </li>
                        <li class="active">Shopping Checkout </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="checkout-main-area pt-120 pb-120">
            <div class="container">
                
                <?php foreach($users as $usr){} ?>
                <?php foreach($schedule as $sch){} ?>
                
                <?php
                $cart_total = $this->cart->total();
                
                $session_email = $this->session->userdata('uemail');
                
                ?>
                
                <div class="checkout-wrap pt-30">
                  <form action="<?php echo base_url('shopping/place_order'); ?>" method="POST">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="billing-info-wrap mr-50">
                                <h3>Billing Details</h3>
                                <div class="row">
                                    <?php foreach($schedule as $sch){}?>
                                    <input type="hidden" name="delivery_date" value="<?php echo $sch->delivery_date; ?>">
                                    <div class="col-lg-6 col-md-6">
                                        <div class="billing-info mb-20">
                                            <label>First Name <abbr class="required" title="required">*</abbr></label>
                                            <?php if(!empty($usr->firstname)){ ?>
                                            <input type="text" name="firstname" value="<?php echo $usr->firstname; ?>" required>
                                            <?php }else{ ?>
                                            <input type="text" name="firstname" required>
                                            <?php } ?>
                                            <span class="text-danger" style="color: red;"><?php echo form_error('firstname'); ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-6 col-md-6">
                                        <div class="billing-info mb-20">
                                            <label>Last Name <abbr class="required" title="required">*</abbr></label>
                                            <?php if(!empty($usr->lastname)){ ?>
                                            <input type="text" name="lastname" value="<?php echo $usr->lastname; ?>" required>
                                            <?php }else{ ?>
                                            <input type="text" name="lastname" required>
                                            <?php } ?>
                                            <span class="text-danger" style="color: red;"><?php echo form_error('lastname'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="billing-info mb-20">
                                            <label>Delivery Category<abbr class="required" title="required">*</abbr></label>
                                            <select name="delivery_category" required>
                                                <?php 
                                                    if($dist == '0 km' || $dist == '6.16 km' || $dist < '10.00 km'){
                                                ?>
                                                <option value="Delivery">Delivery</option>
                                                <?php }else{ ?>
                                                <option value="Collection">Collection</option>
                                                <?php } ?>
                                            </select>
                                            <span class="text-danger" style="color: red;"><?php echo form_error('address'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="billing-info mb-20">
                                            <label>Address <abbr class="required" title="required">*</abbr></label>
                                            <input type="text" name="address" value="<?php echo $usr->address; ?>" required>
                                            <span class="text-danger" style="color: red;"><?php echo form_error('address'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="billing-info mb-20">
                                            <label>Town / County <abbr class="required" title="required">*</abbr></label>
                                            <?php if(!empty($usr->town)){ ?>
                                            <input type="text" name="town" value="<?php echo $usr->town; ?>" required>
                                            <?php }else{ ?>
                                            <input type="text" name="town" required>
                                            <?php } ?>
                                            <span class="text-danger" style="color: red;"><?php echo form_error('town'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="billing-info mb-20">
                                            <label>Postcode <abbr class="required" title="required">*</abbr></label>
                                            <?php if(!empty($sch->postcode)){ ?>
                                            <input type="text" name="postcode" value="<?php echo $sch->postcode; ?>" required>
                                            <?php }else{ ?>
                                            <input type="text" name="postcode" required>
                                            <?php } ?>
                                            <span class="text-danger" style="color: red;"><?php echo form_error('postcode'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="billing-info mb-20">
                                            <label>Telephone number <abbr class="required" title="required">*</abbr></label>
                                            <?php if(!empty($usr->telephone)){ ?>
                                            <input type="text" name="telephone" value="<?php echo $usr->telephone; ?>" required>
                                            <?php }else{ ?>
                                            <input type="text" name="telephone" required>
                                            <?php } ?>
                                            <span class="text-danger" style="color: red;"><?php echo form_error('telephone'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="additional-info-wrap">
                                    <label>Order notes</label>
                                    <textarea name="order_notes" placeholder="Notes about your order, e.g. special notes for delivery." required>none</textarea>
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
                                                <li>Shipping <p>Free Shipping </p></li>
                                            </ul>
                                        </div>
                                        
                                        <div class="your-order-info order-shipping">
                                            <ul>
                                                <?php 
                                                if($dist == '0 km' || $dist == '6.16 km' || $dist < '10.00 km'){
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
                                            <ul>
                                                <li>Total <p>£<?php echo $this->cart->total(); ?></p></li>
                                            </ul>
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
                                    <button type="submit" name="order">Place Order</button>
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