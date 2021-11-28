<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>All Food || Fast Food</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.png">

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
    <link rel="stylesheet" href="<?php echo base_url('assets/css/vendor/vendor.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/plugins/plugins.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style.min.css'); ?>"> -->

</head>

<body>

    <div class="main-wrapper">
        
        <?php include 'menu/nav.php'; ?>
        
        <?php foreach($all as $al){} ?>
        
        <div class="breadcrumb-area bg-gray">
            <div class="container">
                <div class="breadcrumb-content text-center">
                    <ul>
                        <li>
                            <a href="<?php echo site_url('home'); ?>">Home</a>
                        </li>
                        <li class="active">All Foods</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="shop-area pt-120 pb-120">
            <div class="container">
                <div class="row flex-row-reverse">
                    <div class="col-lg-9">
                        <div class="shop-topbar-wrapper">
                            <div class="shop-topbar-left">
                                <div class="view-mode nav">
                                    <a class="active" href="#shop-1" data-toggle="tab"><i class="icon-grid"></i></a>
                                </div>
                            </div>
                            <!--<div class="product-sorting-wrapper">
                                <div class="product-show shorting-style">
                                    <label>Sort by delivery date:</label>
                                    <select>
                                        <option value="">Monday</option>
                                        <option value="">Tuesday</option>
                                        <option value="">Wednesday</option>
                                        <option value="">Friday</option>
                                        <option value="">Saturday</option>
                                        <option value="">Sunday</option>
                                    </select>
                                </div>
                            </div>-->
                        </div>
                        <div class="shop-bottom-area">
                            <div class="tab-content jump">
                                <div id="shop-1" class="tab-pane active">
                                    <div class="row">
                                        <?php if(!empty($all)){ foreach($all as $al){ ?>
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                                            <div class="single-product-wrap mb-35">
                                                <div class="product-img product-img-zoom mb-15">
                                                    <a href="<?php echo site_url('food/detail/'.$al->id.'/'.strtolower($al->title)); ?>">
                                                        <img height="270" width="324" src="<?php echo base_url('uploads/food/'.$al->image1); ?>" alt="<?php echo $al->title; ?>">
                                                    </a>
                                                    <!--<div class="product-action-2 tooltip-style-2">
                                                        <button title="Quick View" data-toggle="modal" data-target="#exampleModal"><i class="icon-size-fullscreen icons"></i></button>
                                                    </div>-->
                                                </div>
                                                <div class="product-content-wrap-2 text-center">
                                                    <h3><a href="<?php echo site_url('food/detail/'.$al->id.'/'.strtolower($al->title)); ?>"><?php echo str_replace('-', ' ', $al->title); ?></a></h3>
                                                    <div class="product-price-2">
                                                        <span>£<?php echo $al->price; ?></span>
                                                    </div>
                                                </div>
                                                <div class="product-content-wrap-2 product-content-position text-center">
                                                    <h3><a href="<?php echo site_url('food/detail/'.$al->id.'/'.strtolower($al->title)); ?>"><?php echo str_replace('-', ' ', $al->title); ?></a></h3>
                                                    <div class="product-price-2">
                                                        <span>£<?php echo $al->price; ?></span>
                                                    </div>
                                                    <?php
                                                        /*date_default_timezone_set('Europe/London');
                                                        
                                                        $currentHour = date("H:i");
                                                        //$openTime = "09:00";
                                                        //$closeTime = "18:00";
                                                        //$dow = date("N");
                                                        
                                                        $startTime = $cat->delivery_start;
                                                        $endTime = $cat->delivery_end;
                                                        
                                                        if($currentHour >= $startTime && $currentHour < $endTime){ 
                                                    ?> 
                                                    <div class="pro-add-to-cart">
                                                        <form action="<?php echo base_url('shopping/add_cart'); ?>" method="POST">
                                                            <input type="hidden" name="id" value="<?php echo $cat->id; ?>">
                                                            <input type="hidden" name="code" value="<?php echo $cat->code; ?>">
                                                            <input type="hidden" name="title" value="<?php echo $cat->title; ?>">
                                                            <input type="hidden" name="category" value="<?php echo $cat->category; ?>">
                                                            <input type="hidden" name="price" value="<?php echo $cat->price; ?>">
                                                            <input type="hidden" name="image" value="<?php echo $cat->image1; ?>">
                                                            <button type="submit" title="Add to Cart">Add To Cart</button>
                                                         </form>
                                                    </div>
                                                    <?php }else{ echo ''; } */ ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } }else{ echo ''; } ?>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="pro-pagination-style text-center mt-10">
                                <?php echo $this->pagination->create_links(); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="sidebar-wrapper sidebar-wrapper-mrg-right">
                            <div class="sidebar-widget mb-40">
                                <h4 class="sidebar-widget-title">Search </h4>
                                <div class="sidebar-search">
                                    <form class="sidebar-search-form" action="<?php echo base_url('food/search'); ?>" method="POST">
                                        <input type="text" name="search_query" placeholder="Search here...">
                                        <button type="submit">
                                            <i class="icon-magnifier"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="sidebar-widget shop-sidebar-border mb-35 pt-40">
                                <h4 class="sidebar-widget-title">Categories </h4>
                                <div class="shop-catigory">
                                    <ul>
                                        <?php if(!empty($menu)){ foreach($menu as $mu){ ?>
                                            <li><a href="<?php echo site_url('food/category/'.strtolower($mu->category)); ?>"><?php echo str_replace('-', ' ', $mu->category); ?></a></li>
                                        <?php } }else{ echo ''; } ?>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="sidebar-widget shop-sidebar-border mb-35 pt-40">
                                <h4 class="sidebar-widget-title">Sort by date </h4>
                                <div class="shop-catigory">
                                    <ul>
                                        <li><a href="<?php echo site_url('food/sort_date/'.strtolower('monday')); ?>">Monday</a></li>
                                        <li><a href="<?php echo site_url('food/sort_date/'.strtolower('tuesday')); ?>">Tuesday</a></li>
                                        <li><a href="<?php echo site_url('food/sort_date/'.strtolower('wednesday')); ?>">Wednesday</a></li>
                                        <li><a href="<?php echo site_url('food/sort_date/'.strtolower('thursday')); ?>">Thursday</a></li>
                                        <li><a href="<?php echo site_url('food/sort_date/'.strtolower('friday')); ?>">Friday</a></li>
                                        <li><a href="<?php echo site_url('food/sort_date/'.strtolower('saturday')); ?>">Saturday</a></li>
                                        <li><a href="<?php echo site_url('food/sort_date/'.strtolower('sunday')); ?>">Sunday</a></li>
                                    </ul>
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
    <script src="<?php echo base_url('assets/js/plugins/ajax-mail.js'); ?>"></script>

    <!-- Use the minified version files listed below for better performance and remove the files listed above  
<script src="assets/js/vendor/vendor.min.js"></script>
<script src="assets/js/plugins/plugins.min.js"></script>  -->
    <!-- Main JS -->
    <script src="<?php echo base_url('assets/js/main.js'); ?>"></script>

</body>

</html>