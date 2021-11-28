<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Shopping Cart || Fast Food</title>
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
                        <li class="active">Shopping Cart </li>
                    </ul>
                </div>
            </div>
        </div>
        
          <script>
            // Update item quantity
            function updateCartItem(obj, rowid){
                $.get("<?php echo base_url('shopping/updateItemQty'); ?>", {rowid:rowid, qty:obj.value}, function(resp){
                    window.location.href="<?php echo site_url('shopping/view_cart'); ?>";
                    /*if(resp == 'ok'){
                        location.reload();
                    }else{
                        alert('Cart update failed, please try again.');
                    }*/
                });
            }
           </script>

        <div class="cart-main-area pt-115 pb-120">
            <div class="container">
                <h3 class="cart-page-title">Your cart items</h3>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <form action="#">
                            <div class="table-content table-responsive cart-table-content">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Product Name</th>
                                            <th>Until Price</th>
                                            <th>Qty</th>
                                            <th>Subtotal</th>
                                            <th>action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <div><?php echo $message; ?></div>
                                        <?php if ($cart = $this->cart->contents()): ?>
                                          <?php $grand_total = 0; $i = 1; ?>
                                          <?php foreach($cart as $item):
                                              $check = array_slice(explode(',', $item['image']), 0, 1);
                        
                                              foreach($check as $image) {
                                                 $image;
                                              }
                                           ?>
                                          <?php
                                            echo form_hidden('cart['. $item['id'] .'][id]', $item['id']);
                                            echo form_hidden('cart['. $item['id'] .'][rowid]', $item['rowid']);
                                            echo form_hidden('cart['. $item['id'] .'][price]', $item['price']);
                                            echo form_hidden('cart['. $item['id'] .'][qty]', $item['qty']);
                                          ?>
                                        <tr>
                                            <td class="product-thumbnail">
                                                <a href="#"><img height="112" width="98" src="<?php echo base_url('uploads/food/'.$image); ?>" alt="<?php echo $item['name']; ?>"></a>
                                            </td>
                                            <td class="product-name">
                                                <a href="#">
                                                    <?php echo str_replace('-', ' ', $item['name']); ?> 
                                                </a>
                                            </td>
                                            <td class="product-price-cart"><span class="amount">£<?php echo $item['price']; ?> </span></td>
                                            <td class="product-quantity pro-details-quality">
                                                <!--<div class="cart-plus-minus">
                                                    <!--<input class="cart-plus-minus-box" type="text" name="qtybutton" value="2">-->
                                                    <?php // echo form_input('cart['. $item['id'] .'][qty]', $item['qty'], 'maxlength="3" size="1" style="width: 20%" '); ?>
                                                <!--</div>-->
                                                <input type="number" class="" value="<?php echo $item["qty"]; ?>" onchange="updateCartItem(this, '<?php echo $item["rowid"]; ?>')"></td>                                            </td>
                                            <td class="product-subtotal">£<?php echo $item['subtotal']; ?></td>
                                            <td class="product-remove">
                                                <a href="<?php echo site_url('shopping/remove_cart/'.$item['rowid']); ?>"><i class="icon_close"></i></a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="cart-shiping-update-wrapper">
                                        <div class="cart-shiping-update">
                                            <a href="<?php echo site_url('home'); ?>">Continue Shopping</a>
                                        </div>
                                        <div class="cart-clear">
                                            <!--<button>Update Cart</button>-->
                                            <a href="<?php echo site_url('shopping/clear_cart'); ?>">Clear Cart</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            
                            <div class="col-lg-4 col-md-12">
                                <div class="grand-totall">
                                    <div class="title-wrap">
                                        <h4 class="cart-bottom-title section-bg-gary-cart">Cart Total</h4>
                                    </div>
                                    <?php
                                    $cart_total = $this->cart->total();
                                    
                                    $session_email = $this->session->userdata('uemail');
                                    
                                    $query = $this->db->query("SELECT DISTINCT id, title, price FROM temp_vouchers WHERE email = '$session_email' ")->result(); 
                                    foreach($query as $qry){
                                        $current_voucher = $qry->title;
                                        $voucher_id = $qry->id;
                                        $voucher_price = $qry->price;
                                    }
                                    
                                    ?>
                                    <?php if(!empty($query)){ ?>
                                    <h5>Total (via voucher) <span>£<?php echo $this->cart->total() - $voucher_price; ?></span></h5>
                                    <?php }else{ ?>
                                    <h5>Total <span>£<?php echo $this->cart->total(); ?></span></h5>
                                    <?php } ?>
                                    <!--<div class="total-shipping">
                                        <h5>Total shipping</h5>
                                        <ul>
                                            <li><input type="checkbox"> Standard <span>$20.00</span></li>
                                            <li><input type="checkbox"> Express <span>$30.00</span></li>
                                        </ul>
                                    </div>-->
                                    <a href="<?php echo site_url('shopping/checkout'); ?>">Proceed to Checkout</a>
                                </div>
                            </div>
                            
                        </div>
                    </div>
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