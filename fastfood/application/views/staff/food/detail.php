<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php if(!empty($detail)){ foreach($detail as $det){} ?>
    <title><?php echo str_replace('-', ' ', $det->title); ?> || Fast Food</title>
    <?php }else{ ?>
    <title>No Food || Fast Food</title>
    <?php } ?>
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
                        <li>
                            <a href="<?php echo site_url('staff/food_category/'.$company.'/'.strtolower($det->category)); ?>">
                                <?php echo str_replace('-', ' ', $det->category); ?>
                            </a>
                        </li>
                        <li class="active"><?php echo str_replace('-', ' ', $det->title); ?></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="product-details-area pt-120 pb-115">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <div class="product-details-tab">
                            <div class="product-dec-right pro-dec-big-img-slider">
                                <div class="easyzoom-style">
                                    <div class="easyzoom easyzoom--overlay">
                                        <a href="<?php echo site_url('uploads/food/'.$det->image1); ?>">
                                            <img height="500" width="500" src="<?php echo base_url('uploads/food/'.$det->image1); ?>" alt="<?php echo $det->title; ?>">
                                        </a>
                                    </div>
                                    <a class="easyzoom-pop-up img-popup" href="<?php echo base_url('uploads/food/'.$det->image1); ?>"><i class="icon-size-fullscreen"></i></a>
                                </div>
                                <div class="easyzoom-style">
                                    <div class="easyzoom easyzoom--overlay">
                                        <a href="<?php echo base_url('uploads/food/'.$det->image2); ?>">
                                            <img height="500" width="500" src="<?php echo base_url('uploads/food/'.$det->image2); ?>" alt="<?php echo $det->title; ?>">
                                        </a>
                                    </div>
                                    <a class="easyzoom-pop-up img-popup" href="<?php echo base_url('uploads/food/'.$det->image2); ?>"><i class="icon-size-fullscreen"></i></a>
                                </div>
                                <div class="easyzoom-style">
                                    <div class="easyzoom easyzoom--overlay">
                                        <a href="<?php echo base_url('uploads/food/'.$det->image3); ?>">
                                            <img height="500" width="500" src="<?php echo base_url('uploads/food/'.$det->image3); ?>" alt="<?php echo $det->title; ?>">
                                        </a>
                                    </div>
                                    <a class="easyzoom-pop-up img-popup" href="<?php echo base_url('uploads/food/'.$det->image3); ?>"><i class="icon-size-fullscreen"></i></a>
                                </div>
                                <div class="easyzoom-style">
                                    <div class="easyzoom easyzoom--overlay">
                                        <a href="<?php echo base_url('uploads/food/'.$det->image4); ?>">
                                            <img height="500" width="500" src="<?php echo base_url('uploads/food/'.$det->image4); ?>" alt="<?php echo $det->title; ?>">
                                        </a>
                                    </div>
                                    <a class="easyzoom-pop-up img-popup" href="<?php echo base_url('uploads/food/'.$det->image4); ?>"><i class="icon-size-fullscreen"></i></a>
                                </div>
                                <div class="easyzoom-style">
                                    <div class="easyzoom easyzoom--overlay">
                                        <a href="<?php echo base_url('uploads/food/'.$det->image5); ?>">
                                            <img height="500" width="500" src="<?php echo base_url('uploads/food/'.$det->image5); ?>" alt="<?php echo $det->title; ?>">
                                        </a>
                                    </div>
                                    <a class="easyzoom-pop-up img-popup" href="<?php echo base_url('uploads/food/'.$det->image5); ?>"><i class="icon-size-fullscreen"></i></a>
                                </div>
                            </div>
                            <div class="product-dec-left product-dec-slider-small-2 product-dec-small-style2">
                                <div class="product-dec-small active">
                                    <img src="<?php echo base_url('uploads/food/'.$det->image1); ?>" alt="<?php echo $det->title; ?>">
                                </div>
                                <div class="product-dec-small">
                                    <img src="<?php echo base_url('uploads/food/'.$det->image2); ?>" alt="<?php echo $det->title; ?>">
                                </div>
                                <div class="product-dec-small">
                                    <img src="<?php echo base_url('uploads/food/'.$det->image3); ?>" alt="<?php echo $det->title; ?>">
                                </div>
                                <div class="product-dec-small">
                                    <img src="<?php echo base_url('uploads/food/'.$det->image4); ?>" alt="<?php echo $det->title; ?>">
                                </div>
                                <div class="product-dec-small">
                                    <img src="<?php echo base_url('uploads/food/'.$det->image5); ?>" alt="<?php echo $det->title; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="product-details-content pro-details-content-mt-md">
                            <h2><?php echo str_replace('-', ' ', $det->title); ?></h2>
                            <p><?php echo $det->description; ?></p>
                            <p>Availabile to Order: <?php echo $det->delivery_start; ?> - <?php echo $det->delivery_end; ?></p>
                            <br>
                            <h4>Available Dates</h4>
                            <?php 
                              $check = explode(',', $det->date);
                        
                              foreach($check as $date) {
                            
                            ?>
                            <p><?php echo $date; ?></p>
                            <?php } ?>
                            <div class="pro-details-price">
                                <span class="new-price">£<?php echo $det->price; ?></span>
                            </div>
                            <!--<div class="pro-details-size">
                                <span>Size:</span>
                                <div class="pro-details-size-content">
                                    <ul>
                                        <li><a href="#">XS</a></li>
                                        <li><a href="#">S</a></li>
                                        <li><a href="#">M</a></li>
                                        <li><a href="#">L</a></li>
                                        <li><a href="#">XL</a></li>
                                    </ul>
                                </div>
                            </div>-->
                            <!--<div class="pro-details-quality">
                                <span>Quantity:</span>
                                <div class="cart-plus-minus">
                                    <input class="cart-plus-minus-box" type="text" name="qtybutton" value="1">
                                </div>
                            </div>-->
                            
                            <?php $side_meal = $this->db->query("SELECT title FROM side_meal WHERE category = '$det->category' ")->result(); ?>
                            <?php if(!empty($side_meal)){ ?>
                            <div class="pro-details-quality">
                                
                                <span>Side Meal</span>
                                <select name="side_meal">
                                        <option>Select a Side Meal</option>
                                    <?php foreach($side_meal as $side_ml){ ?>
                                        <option value="<?php echo $side_ml->title; ?>"><?php echo $side_ml->title; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <?php } ?>
                            
                            <br>
                            
                            <?php $side_drink = $this->db->query("SELECT title FROM side_drink WHERE category = '$det->category' ")->result(); ?>
                            <?php if(!empty($side_drink)){ ?>
                            <div class="pro-details-quality">
                                <span>Side drinks</span>
                                <select>
                                        <option>Select a Side Drink</option>
                                    <?php foreach($side_drink as $side_dr){ ?>
                                        <option value="<?php echo $side_dr->title; ?>"><?php echo $side_dr->title; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <?php } ?>
                            <br>
                            <?php
                                date_default_timezone_set('Europe/London');
                                
                                if(!empty($schedule)){ foreach($schedule as $sch){}
                                
                                $currentHour = date("H:i");
                                //$openTime = "09:00";
                                //$closeTime = "18:00";
                                $dow = date("N");
                                
                                $startTime = $det->delivery_start;
                                $endTime = $det->delivery_end;
                                
                                $delivery_day = $sch->delivery_day;
                                $num_time = $sch->num_time;
                                
                                /*$day1 = $det->day1;
                                $day2 = $det->day2;
                                $day3 = $det->day3;
                                $day4 = $det->day4;
                                $day5 = $det->day5;
                                $day6 = $det->day6;
                                $day7 = $det->day7;*/
                                
                                //$startDate = $det->start_date;
                                //$endDate = $det->end_date;
                                
                                foreach($check as $date) {
                                
                                //if($currentHour >= $startTime && $currentHour < $endTime){
                                //if($currentHour >= $startTime && $currentHour < $endTime && $num_date >= $startDate && $num_date <= $endDate){
                                //if($num_date >= $startDate && $num_date < $endDate && $num_time >= $startTime && $num_time < $endTime){
                            ?> 
                            <div class="pro-details-action-wrap">
                                <div class="pro-add-to-cart">
                                    <form action="<?php echo base_url('staff/add_cart/'.$company); ?>" method="POST">
                                        <input type="hidden" name="id" value="<?php echo $det->id; ?>">
                                        <input type="hidden" name="code" value="<?php echo $det->code; ?>">
                                        <input type="hidden" name="title" value="<?php echo $det->title; ?>">
                                        <input type="hidden" name="category" value="<?php echo $det->category; ?>">
                                        <input type="hidden" name="price" value="<?php echo $det->price; ?>">
                                        <input type="hidden" name="image" value="<?php echo $det->image1; ?>">
                                        <?php if(!empty($side_meal)){ ?>
                                        <input type="hidden" name="side_meal" value="<?php echo $side_ml->title; ?>">
                                        <?php } ?>
                                        <?php if(!empty($side_drink)){ ?>
                                        <input type="hidden" name="side_drink" value="<?php echo $side_dr->title; ?>">
                                        <?php } ?>
                                        <p><?php echo $date; ?></p>
                                        <?php if($date == $delivery_day && $num_time <= $endTime){ ?>
                                        <button type="submit" title="Add to Cart">Add To Cart</button>
                                        <br>
                                        <?php }else{ ?>
                                        <div class="alert alert-danger">The Food is not available to order</div>
                                        <?php } ?>
                                     </form>
                                </div>
                            </div>
                                <?php } ?>
                            
                            <?php }else{ echo ''; } ?>
                            <br>
                            
                            <div class="pro-details-action-wrap">
                                <div class="pro-add-to-cart">
                                    <form action="<?php echo base_url('staff/add_wishlist/'.$company); ?>" method="POST">
                                        <input type="hidden" name="food_id" value="<?php echo $det->id; ?>">
                                        <input type="hidden" name="title" value="<?php echo $det->title; ?>">
                                        <input type="hidden" name="category" value="<?php echo $det->category; ?>">
                                        <input type="hidden" name="price" value="<?php echo $det->price; ?>">
                                        <button type="submit" title="Add to Wishlist">Add To Wishlist</button>
                                     </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="description-review-wrapper pb-110">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="dec-review-topbar nav mb-45">
                            <a class="active" data-toggle="tab" href="#des-details1">Description</a>
                        </div>
                        <div class="tab-content dec-review-bottom">
                            <div id="des-details1" class="tab-pane active">
                                <div class="description-wrap">
                                    <p><?php echo $det->description; ?></p>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php 
        $related = $this->db->query("SELECT * FROM food WHERE category = '$det->category' ")->result();
        ?>
        
        <?php if(!empty($related)){ ?>
        <div class="related-product pb-115">
            <div class="container">
                <div class="section-title mb-45 text-center">
                    <h2>Related Food</h2>
                </div>
                <div class="related-product-active">
                    <?php foreach($related as $rel){ ?>
                    <div class="product-plr-1">
                        <div class="single-product-wrap">
                            <div class="product-img product-img-zoom mb-15">
                                <a href="<?php echo site_url('staff/food_detail/'.$company.'/'.$rel->id.'/'.strtolower($rel->title)); ?>">
                                    <img height="270" width="324" src="<?php echo base_url('uploads/food/'.$rel->image1); ?>" alt="<?php echo $rel->title; ?>">
                                </a>
                                <!--<div class="product-action-2 tooltip-style-2">
                                    <button title="Quick View" data-toggle="modal" data-target="#exampleModal"><i class="icon-size-fullscreen icons"></i></button>
                                </div>-->
                            </div>
                            <div class="product-content-wrap-2 text-center">
                                <h3>
                                    <a href="<?php echo site_url('staff/food_detail/'.$company.'/'.$rel->id.'/'.strtolower($rel->title)); ?>">
                                        <?php echo str_replace('-', ' ', $rel->title); ?>
                                    </a>
                                </h3>
                                <div class="product-price-2">
                                    <span>£<?php echo $rel->price; ?></span>
                                </div>
                            </div>
                            <div class="product-content-wrap-2 product-content-position text-center">
                                <h3>
                                    <a href="<?php echo site_url('staff/food_detail/'.$company.'/'.$rel->id.'/'.strtolower($rel->title)); ?>">
                                        <?php echo str_replace('-', ' ', $rel->title); ?>
                                    </a>
                                </h3>
                                <div class="product-price-2">
                                    <span>£<?php echo $rel->price; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php }else{ echo ''; } ?>
        
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