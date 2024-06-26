<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Shopping Checkout || Jollof N Laugh</title>
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
                            <a href="<?php echo site_url('jollof_n_laugh'); ?>">Home</a>
                        </li>
                        <li class="active">Shopping Checkout </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="checkout-main-area pt-120 pb-120">
            <div class="container">
                
                <?php foreach($users as $usr){} ?>

                <?php
                $cart_total = $this->cart->total();
                
                $session_email = $this->session->userdata('uemail');
                
                ?>
                
                <div class="checkout-wrap pt-30">
                  <form action="<?php echo base_url('jollof_n_laugh/place_order'); ?>" method="POST">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="billing-info-wrap mr-50">
                                <h3>Billing Details</h3>
                                <div class="row">
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
                                            <label>Email Address <abbr class="required" title="required">*</abbr></label>
                                            <input type="email" name="email" required>
                                            <span class="text-danger" style="color: red;"><?php echo form_error('email'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="billing-info mb-20">
                                            <label>Seat Type <abbr class="required" title="required">*</abbr></label>
                                            <select class="form-control" name="seat_type">
                                                <option>Select</option>
                                                <?php if(!empty($seating)){ foreach($seating as $seat){ ?>
                                                <option value="<?php echo $seat->title; ?>"><?php echo $seat->title; ?></option>
                                                <?php } } ?>
                                            </select>
                                            <span class="text-danger" style="color: red;"><?php echo form_error('seat_type'); ?></span>
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
                                                  <?php echo form_open('jollof_n_laugh/update_cart'); ?>
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
                                        
                                        <?php
                                        $cart_total = $this->cart->total();
                                        
                                        $query = $this->db->query("SELECT DISTINCT id, code, percent FROM temp_discount")->result(); 
                                        foreach($query as $qry){
                                            $current_voucher = $qry->code;
                                            $voucher_id = $qry->id;
                                            $current_voucher_percent = $qry->percent;
                                        }
                                        
                                        $sequel = $this->db->query("SELECT DISTINCT code FROM discount")->result(); 
                                        foreach($sequel as $sql){
                                            $discount = $sql->code;
                                        }
                                        
                                        ?>
                                        

                                        <div class="your-order-info order-shipping">
                                            <ul>
                                                <?php if(!empty($query) && $current_voucher == $discount){ ?>
                                                <?php $discount_price = $current_voucher_percent/100 * $cart_total; ?>
                                                <li>Total (via voucher) <span>£<?php echo $this->cart->total() - $discount_price; ?></span></li>
                                                <?php }else{ ?>
                                                <li>Total <span>£<?php echo $this->cart->total(); ?></span></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                        
                                    
                                    <script>
                                        function deleteVoucher(id){
                                        var del_id = id;
                                        if(confirm("Are you sure you want to remove this discount code")){
                                        $.post('<?php echo base_url('jollof_n_laugh/destroy_voucher'); ?>', {"del_id": del_id}, function(data){
                                          location.reload();
                                          $('#cte').html(data)
                                          });
                                        }
                                      }
                                    </script>
                                    <p id='cte'></p>
                                    
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
