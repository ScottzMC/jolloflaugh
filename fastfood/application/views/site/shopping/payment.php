<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Order Payment || Fast Food</title>
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
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />

    
    <!-- Use the minified version files listed below for better performance and remove the files listed above
    <link rel="stylesheet" href="assets/css/vendor/vendor.min.css">
    <link rel="stylesheet" href="assets/css/plugins/plugins.min.css">
    <link rel="stylesheet" href="assets/css/style.min.css"> -->

</head>

    <style type="text/css">
    .panel-title{
        display: inline;
        font-weight: bold;
    }
    
    .display-table{
        display: table;
    }
    
    .display-tr{
        display: table-row;
    }

    .display-td {
        display: table-cell;
        vertical-align: middle;
        width: 61%;
    }

    </style>

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
                        <li>
                            <a href="<?php echo site_url('staff/view_cart'); ?>">Shopping Cart</a>
                        </li>
                        <li class="active">Order Payment </li>
                    </ul>
                </div>
            </div>
        </div>
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
                                            <th>Unit Price</th>
                                            <th>Qty</th>
                                            <th>Total</th>
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
                                          <?php echo form_open('staff/update_cart'); ?>
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
                                                <span class="amount"><?php echo $item['qty']; ?> </span>
                                                <!--<div class="cart-plus-minus">
                                                    <!--<input class="cart-plus-minus-box" type="text" name="qtybutton" value="2">-->
                                                    <?php // echo form_input('cart['. $item['id'] .'][qty]', $item['qty'], 'maxlength="3" size="1" style="width: 20%" '); ?>
                                                <!--</div>-->
                                            </td>
                                            <td class="product-subtotal">£<?php echo $this->cart->total(); ?></td>
                                        </tr>
                                        <?php form_close(); ?>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!--<div class="row">
                                <div class="col-lg-12">
                                    <div class="cart-shiping-update-wrapper">
                                        <div class="cart-shiping-update">
                                            <a href="<?php echo site_url('staff/home/'.$company); ?>">Continue Shopping</a>
                                        </div>
                                    </div>
                                </div>
                            </div>-->
                            
                        </form>
                        
                        <br><br>
                        
                    </div>
                    
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="panel panel-default credit-card-box">
                            <div class="panel-heading display-table">
                                <div class="row display-tr">
                                    <h3 class="panel-title display-td" style="width: auto;">Payment Details</h3>
                                </div>                    
                            </div>
            
                            <div class="panel-body">
                                <?php if($this->session->flashdata('success')){ ?>
                                <div class="alert alert-success text-center">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                                    <p><?php echo $this->session->flashdata('success'); ?></p>
                                </div>
                                <?php } ?>
            
                                <form role="form" action="<?php echo base_url('payment/stripe_order_post'); ?>" method="post" 
                                    class="require-validation" data-cc-on-file="false" data-stripe-publishable-key="<?php echo $this->config->item('stripe_key') ?>" id="payment-form">
                                    <?php $session_email = $this->session->userdata('uemail'); ?>

                                    <div class='form-row row'>
                                        <div class='col-xs-12 form-group required'>
                                            <label class='control-label'>Name on Card</label> 
                                            <input class='form-control' size='4' type='text'>
                                        </div>
                                    </div>
            
                                    <div class='form-row row'>
                                        <div class='col-xs-12 form-group card required'>
                                            <label class='control-label'>Card Number</label> 
                                            <input autocomplete='off' class='form-control card-number' size='20' type='text'>
                                        </div>
                                    </div>
            
                                    <div class='form-row row'>
                                        <div class='col-xs-12 col-md-4 form-group cvc required'>
                                            <label class='control-label'>CVC</label> 
                                            <input autocomplete='off' class='form-control card-cvc' placeholder='ex. 311' size='4' type='text'>
                                        </div>
            
                                        <div class='col-xs-12 col-md-4 form-group expiration required'>
                                            <label class='control-label'>Expiration Month</label> 
                                            <input class='form-control card-expiry-month' placeholder='MM' size='2' type='text'>
                                        </div>
            
                                        <div class='col-xs-12 col-md-4 form-group expiration required'>
                                            <label class='control-label'>Expiration Year</label> 
                                            <input class='form-control card-expiry-year' placeholder='YYYY' size='4' type='text'>
                                        </div>
                                    </div>
                                    
                                    <div class='form-row row'>
                                        <div class='col-md-12 error form-group hide'>
                                            <div class='alert-danger alert'>Please correct the errors and try again.</div>
                                        </div>
                                    </div>
            
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <button class="btn btn-primary btn-lg btn-block" type="submit">Pay Now (&pound;<?php echo $this->cart->total(); ?>)</button>
                                        </div>
                                    </div>
                                </form>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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
    
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    
    <script type="text/javascript">
    $(function(){
        var $form = $(".require-validation");
        
        $('form.require-validation').bind('submit', function(e){
            var $form = $(".require-validation"),
            inputSelector = ['input[type=email]', 
                            'input[type=password]',
                            'input[type=text]', 
                            'input[type=file]',
                             'textarea'].join(', '),
    
            $inputs = $form.find('.required').find(inputSelector),
            $errorMessage = $form.find('div.error'),
            valid = true;
            $errorMessage.addClass('hide');
            $('.has-error').removeClass('has-error');
    
        $inputs.each(function(i, el){
          var $input = $(el);
          
          if($input.val() === '') {
            $input.parent().addClass('has-error');
            $errorMessage.removeClass('hide');
            e.preventDefault();
          }
        });
    
        if(!$form.data('cc-on-file')){
            e.preventDefault();
            
            Stripe.setPublishableKey($form.data('stripe-publishable-key'));
            Stripe.createToken({
                number: $('.card-number').val(),
                cvc: $('.card-cvc').val(),
                exp_month: $('.card-expiry-month').val(),
                exp_year: $('.card-expiry-year').val()
            }, stripeResponseHandler);
        }
      });
    
      function stripeResponseHandler(status, response){
        if(response.error){
            $('.error')
                .removeClass('hide')
                .find('.alert')
                .text(response.error.message);
        }else{
            var token = response['id'];
            $form.find('input[type=text]').empty();
            $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
            $form.get(0).submit();
        }
    
       }
    
    });
    
    </script>

</body>

</html>