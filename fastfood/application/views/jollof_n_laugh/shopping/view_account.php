<?php

function convertToOrderFormat($timestamp){
    $diffBtwCurrentTimeAndTimeStamp = (time() - $timestamp);
    $periodsString = ["sec", "min","hr","day","week","month","year","decade"];
    $periodNumbers = ["60" , "60" , "24" , "7" , "4.35" , "12" , "10"];
    for(@@$iterator = 0; $diffBtwCurrentTimeAndTimeStamp >= $periodNumbers[$iterator]; $iterator++)
        @@$diffBtwCurrentTimeAndTimeStamp /= $periodNumbers[$iterator];
        $diffBtwCurrentTimeAndTimeStamp = round($diffBtwCurrentTimeAndTimeStamp);

    if($diffBtwCurrentTimeAndTimeStamp != 1)  $periodsString[$iterator].="s";
        $output = "$diffBtwCurrentTimeAndTimeStamp $periodsString[$iterator]";
        echo "Ordered " .$output. " ago";
}

function convertToMessageFormat($timestamp){
    $diffBtwCurrentTimeAndTimeStamp = (time() - $timestamp);
    $periodsString = ["sec", "min","hr","day","week","month","year","decade"];
    $periodNumbers = ["60" , "60" , "24" , "7" , "4.35" , "12" , "10"];
    for(@@$iterator = 0; $diffBtwCurrentTimeAndTimeStamp >= $periodNumbers[$iterator]; $iterator++)
        @@$diffBtwCurrentTimeAndTimeStamp /= $periodNumbers[$iterator];
        $diffBtwCurrentTimeAndTimeStamp = round($diffBtwCurrentTimeAndTimeStamp);

    if($diffBtwCurrentTimeAndTimeStamp != 1)  $periodsString[$iterator].="s";
        $output = "$diffBtwCurrentTimeAndTimeStamp $periodsString[$iterator]";
        echo "Sent " .$output. " ago";
}


?>

<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>My Account || Jollof N Laugh</title>
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
                        <li class="active">my account </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- my account wrapper start -->
        <div class="my-account-wrapper pt-120 pb-120">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- My Account Page Start -->
                        <div class="myaccount-page-wrapper">
                            <!-- My Account Tab Menu Start -->
                            <div class="row">
                                <div class="col-lg-3 col-md-4">
                                    <div class="myaccount-tab-menu nav" role="tablist">
                                        <a href="#dashboad" class="active" data-toggle="tab"><i class="fa fa-dashboard"></i>
                                            Dashboard</a>
                                        <a href="#orders" data-toggle="tab"><i class="fa fa-cart-arrow-down"></i> Orders</a>
                                        <a href="#messages" data-toggle="tab"><i class="fa fa-cart-arrow-down"></i> Messages</a>
                                        <a href="#address" data-toggle="tab"><i class="fa fa-map-marker"></i> Address</a>
                                        <a href="#account-info" data-toggle="tab"><i class="fa fa-user"></i> Account Details</a>
                                        <a href="<?php echo site_url('jollof_n_laugh/logout'); ?>"><i class="fa fa-sign-out"></i> Logout</a>
                                    </div>
                                </div>
                                <!-- My Account Tab Menu End -->
                                
                                    <script>
                                     function cancelOrder(id){
                                       var ord_id = id;
                                       if(confirm("Are you sure you want to cancel this order")){
                                       $.post('<?php echo base_url('jollof_n_laugh/cancel_order'); ?>', {"ord_id": ord_id}, function(data){
                                         location.reload();
                                         $('#cte').html(data)
                                         });
                                       }
                                     }
                                     
                                    function deleteOrder(id){
                                      var del_id = id;
                                      if(confirm("Are you sure you want to delete this order")){
                                         $.post('<?php echo base_url('jollof_n_laugh/delete_order'); ?>', {"del_id": del_id}, function(data){
                                         location.reload();
                                         $('#cti').html(data)
                                        });
                                      }
                                    }
                                    
                                    function deleteMessage(id){
                                      var del_id = id;
                                      if(confirm("Are you sure you want to delete this ticket")){
                                         $.post('<?php echo base_url('jollof_n_laugh/delete_message'); ?>', {"del_id": del_id}, function(data){
                                         location.reload();
                                         $('#ctm').html(data)
                                        });
                                      }
                                    }
                                    
                                    </script>
                                   
                                   <p id='cti'></p>
                                   <p id='cte'></p>
                                   <p id='ctm'></p>
                                
                                <?php foreach($users as $usr){} ?>
                                
                                <!-- My Account Tab Content Start -->
                                <div class="col-lg-9 col-md-8">
                                    <div class="tab-content" id="myaccountContent">
                                        <!-- Single Tab Content Start -->
                                        <div class="tab-pane fade show active" id="dashboad" role="tabpanel">
                                            <div class="myaccount-content">
                                                <h3>Dashboard</h3>
                                                <div class="welcome">
                                                    <p>Hello, <strong><?php echo $usr->firstname; ?> <?php echo $usr->lastname; ?></strong></p>
                                                </div>
                                                <p class="mb-0">From your account dashboard. you can easily check & view your recent orders, 
                                                manage your shipping and billing addresses and edit your password and account details.</p>
                                            </div>
                                        </div>
                                        <!-- Single Tab Content End -->
                                        
                                        <!-- Single Tab Content Start -->
                                        <div class="tab-pane fade" id="orders" role="tabpanel">
                                            <div class="myaccount-content">
                                                <h3>Orders</h3>
                                                <div class="myaccount-table table-responsive text-center">
                                                    <table class="table table-bordered">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>Order ID</th>
                                                                <th>Food</th>
                                                                <th>Time</th>
                                                                <th>Date</th>
                                                                <th>Status</th>
                                                                <th>Total</th>
                                                                <th>Action</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if(!empty($order_items)){ foreach($order_items as $order){ ?>
                                                            <tr>
                                                                <td><?php echo $order->order_id; ?></td>
                                                                <td><?php echo str_replace('-', ' ', $order->title); ?></td>
                                                                <td><?php echo convertToOrderFormat($order->created_time); ?></td>
                                                                <td><?php echo date('j M Y', strtotime($order->created_date)); ?></td>
                                                                <td><?php echo $order->status; ?></td>
                                                                <td>£<?php echo $order->price; ?></td>
                                                                <td><a class="check-btn sqr-btn" onclick="cancelOrder(<?php echo $order->id; ?>)">Cancel</a></td>
                                                                <td><a class="check-btn sqr-btn" onclick="deleteOrder(<?php echo $order->id; ?>)">Delete</a></td>
                                                            </tr>
                                                            <?php } }else{ echo ''; } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Single Tab Content End -->
                                        
                                        <!-- Single Tab Content Start -->
                                        <div class="tab-pane fade" id="messages" role="tabpanel">
                                            <div class="myaccount-content">
                                                <h3>Orders</h3>
                                                <div class="myaccount-table table-responsive text-center">
                                                    <table class="table table-bordered">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>Order ID</th>
                                                                <th>Food</th>
                                                                <th>Subject</th>
                                                                <th>Body</th>
                                                                <th>Time</th>
                                                                <th>Date</th>
                                                                <th>Status</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if(!empty($message)){ foreach($message as $mess){ ?>
                                                            <tr>
                                                                <td><?php echo $mess->order_id; ?></td>
                                                                <td><?php echo str_replace('-', ' ', $mess->order_title); ?></td>
                                                                <td><?php echo $mess->subject; ?></td>
                                                                <td><?php echo $mess->body; ?></td>
                                                                <td><?php echo convertToMessageFormat($mess->created_time); ?></td>
                                                                <td><?php echo date('j M Y', strtotime($mess->created_date)); ?></td>
                                                                <td><?php echo $mess->status; ?></td>
                                                                <td><a class="check-btn sqr-btn" onclick="deleteMessage(<?php echo $mess->id; ?>)">Delete</a></td>
                                                            </tr>
                                                            <?php } }else{ echo ''; } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            
                                            <div class="myaccount-content">
                                                <h3>Send Ticket</h3>
                                                <div class="account-details-form">
                                                    <form action="<?php echo base_url('jollof_n_laugh/send_ticket'); ?>" method="POST">
                                                        <?php 
                                                        $order_query = $this->db->query("SELECT title FROM order_items WHERE order_id = '$order->order_id' ")->result();
                                                        foreach($order_query as $ord_qry){}
                                                        ?>
                                                        <input type="hidden" name="order_title" value="<?php echo $ord_qry->title; ?>">
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="single-input-item">
                                                                    <label for="first-name" class="required">Order ID</label>
                                                                    <?php if(!empty($order_query)){ ?>
                                                                    <select name="order_id">
                                                                        <option>Select your Order ID</option>
                                                                        <?php foreach($order_query as $ord_qry){ ?>
                                                                        <option value="<?php echo $order->order_id; ?>"><?php echo $order->order_id; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                    <?php }else{ ?>
                                                                    <p><div class="alert alert-danger">No Ordered Food</div></p>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="single-input-item">
                                                                    <label for="last-name" class="required">Subject</label>
                                                                    <input type="text" id="last-name" name="subject" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="single-input-item">
                                                                    <label for="company-code" class="required">Message</label>
                                                                    <textarea name="body"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="single-input-item">
                                                            <button class="check-btn sqr-btn" type="submit" name="send">Send Ticket</button>
                                                        </div>
                                                    </form>
                                                    <?php
                                                    	echo $this->session->flashdata('msg');
                                                    	echo $this->session->flashdata('msgError');
                                                    ?>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <!-- Single Tab Content End -->
                                        
                                        <!-- Single Tab Content Start -->
                                        <div class="tab-pane fade" id="address" role="tabpanel">
                                            <div class="myaccount-content">
                                                <h3>Billing Address</h3>
                                                <address>
                                                    <p><strong><?php echo $usr->firstname; ?> <?php echo $usr->lastname; ?></strong></p>
                                                    <p><?php echo $usr->address; ?> <br>
                                                    <?php echo $usr->town; ?> <?php echo $usr->postcode; ?></p>
                                                    <p>Mobile: <?php echo $usr->telephone; ?></p>
                                                </address>
                                            </div>
                                        </div>
                                        <!-- Single Tab Content End -->
                                    
                                        <!-- Single Tab Content Start -->
                                        <div class="tab-pane fade" id="account-info" role="tabpanel">
                                            <div class="myaccount-content">
                                                <h3>Account Details</h3>
                                                <div class="account-details-form">
                                                    <form action="<?php echo base_url('jollof_n_laugh/my_account'); ?>" method="POST">
                                                        
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="single-input-item">
                                                                    <label for="first-name" class="required">First Name</label>
                                                                    <input type="text" id="first-name" name="firstname" value="<?php echo $usr->firstname; ?>" />
                                                                    <span class="text-danger" style="color: red;"><?php echo form_error('firstname'); ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="single-input-item">
                                                                    <label for="last-name" class="required">Last Name</label>
                                                                    <input type="text" id="last-name" name="lastname" value="<?php echo $usr->lastname; ?>" />
                                                                    <span class="text-danger" style="color: red;"><?php echo form_error('lastname'); ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="single-input-item">
                                                                    <label for="email" class="required">Email Address</label>
                                                                    <p><?php echo $usr->email; ?></p>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="single-input-item">
                                                                    <label for="telephone" class="required">Telephone Number</label>
                                                                    <input type="text" id="telephone" name="telephone" value="<?php echo $usr->telephone; ?>" />
                                                                <span class="text-danger" style="color: red;"><?php echo form_error('telephone'); ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="single-input-item">
                                                                    <label for="company-code" class="required">Post Code</label>
                                                                    <input type="text" id="last-name" name="postcode" value="<?php echo $usr->postcode; ?>" />
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="single-input-item">
                                                                    <label for="company-name" class="required">County/Town</label>
                                                                    <input type="text" id="last-name" name="town" value="<?php echo $usr->town; ?>" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="single-input-item">
                                                            <label for="address" class="required">Delivery Address</label>
                                                            <textarea name="address"><?php echo $usr->address; ?></textarea>
                                                        </div>
                                                        
                                                        
                                                        <div class="single-input-item">
                                                            <button class="check-btn sqr-btn" type="submit" name="update">Save Changes</button>
                                                        </div>
                                                    </form>
                                                    <?php
                                                    	echo $this->session->flashdata('msg');
                                                    	echo $this->session->flashdata('msgError');
                                                    ?>
                                                </div>
                                            </div>
                                        </div> <!-- Single Tab Content End -->
                                        
                                    </div>
                                </div> <!-- My Account Tab Content End -->
                            </div>
                        </div> <!-- My Account Page End -->
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