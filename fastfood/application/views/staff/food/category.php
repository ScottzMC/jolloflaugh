<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php if(!empty($category)){ foreach($category as $cat){} ?>
    <title><?php echo str_replace('-', ' ', $cat->category); ?> || Fast Food</title>
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
    <link rel="stylesheet" href="<?php echo base_url('assets/css/vendor/vendor.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/plugins/plugins.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style.min.css'); ?>"> -->

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
                        <li class="active"><?php echo str_replace('-', ' ', $cat->category); ?></li>
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
                                    <label>Sort by :</label>
                                    <select>
                                        <option value="">Default</option>
                                        <option value=""> Name</option>
                                        <option value=""> price</option>
                                    </select>
                                </div>
                            </div>-->
                        </div>
                        <div class="shop-bottom-area">
                            <div class="tab-content jump">
                                <div id="shop-1" class="tab-pane active">
                                    <div class="row">
                                        <?php if(!empty($category)){ foreach($category as $cat){ ?>
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                                            <div class="single-product-wrap mb-35">
                                                <div class="product-img product-img-zoom mb-15">
                                                    <a href="<?php echo site_url('staff/food_detail/'.$company.'/'.$cat->id.'/'.strtolower($cat->title)); ?>">
                                                        <img height="270" width="324" src="<?php echo base_url('uploads/food/'.$cat->image1); ?>" alt="<?php echo $cat->title; ?>">
                                                    </a>
                                                    <!--<div class="product-action-2 tooltip-style-2">
                                                        <button title="Quick View" data-toggle="modal" data-target="#exampleModal"><i class="icon-size-fullscreen icons"></i></button>
                                                    </div>-->
                                                </div>
                                                <div class="product-content-wrap-2 text-center">
                                                    <h3>
                                                        <a href="<?php echo site_url('staff/food_detail/'.$company.'/'.$cat->id.'/'.strtolower($cat->title)); ?>">
                                                            <?php echo str_replace('-', ' ', $cat->title); ?>
                                                        </a>
                                                    </h3>
                                                    <div class="product-price-2">
                                                        <span>£<?php echo $cat->price; ?></span>
                                                    </div>
                                                </div>
                                                <div class="product-content-wrap-2 product-content-position text-center">
                                                    <h3>
                                                        <a href="<?php echo site_url('staff/food_detail/'.$company.'/'.$cat->id.'/'.strtolower($cat->title)); ?>">
                                                            <?php echo str_replace('-', ' ', $cat->title); ?>
                                                        </a>
                                                    </h3>
                                                    <div class="product-price-2">
                                                        <span>£<?php echo $cat->price; ?></span>
                                                    </div>
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
                                    <form class="sidebar-search-form" action="<?php echo base_url('staff/food_search/'.$company); ?>" method="POST">
                                        <input type="text" name="search_query" placeholder="Search here...">
                                        <!--<button type="submit">
                                            <i class="icon-magnifier"></i>
                                        </button>-->
                                    </form>
                                </div>
                            </div>
                            <div class="sidebar-widget shop-sidebar-border mb-35 pt-40">
                                <h4 class="sidebar-widget-title">Categories </h4>
                                <div class="shop-catigory">
                                    <ul>
                                        <?php if(!empty($menu)){ foreach($menu as $mu){ ?>
                                            <li>
                                                <a href="<?php echo site_url('staff/food_category/'.$company.'/'.strtolower($mu->category)); ?>">
                                                    <?php echo str_replace('-', ' ', $mu->category); ?>
                                                </a>
                                            </li>
                                        <?php } }else{ echo ''; } ?>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="sidebar-widget shop-sidebar-border mb-35 pt-40">
                                <h4 class="sidebar-widget-title">Sort by date </h4>
                                <div class="shop-catigory">
                                    <ul>
                                        <li><a href="<?php echo site_url('staff/food_category_sort_date/'.$company.'/'.strtolower($cat->category).'/'.strtolower('monday')); ?>">Monday</a></li>
                                        <li><a href="<?php echo site_url('staff/food_category_sort_date/'.$company.'/'.strtolower($cat->category).'/'.strtolower('tuesday')); ?>">Tuesday</a></li>
                                        <li><a href="<?php echo site_url('staff/food_category_sort_date/'.$company.'/'.strtolower($cat->category).'/'.strtolower('wednesday')); ?>">Wednesday</a></li>
                                        <li><a href="<?php echo site_url('staff/food_category_sort_date/'.$company.'/'.strtolower($cat->category).'/'.strtolower('thursday')); ?>">Thursday</a></li>
                                        <li><a href="<?php echo site_url('staff/food_category_sort_date/'.$company.'/'.strtolower($cat->category).'/'.strtolower('friday')); ?>">Friday</a></li>
                                        <li><a href="<?php echo site_url('staff/food_category_sort_date/'.$company.'/'.strtolower($cat->category).'/'.strtolower('saturday')); ?>">Saturday</a></li>
                                        <li><a href="<?php echo site_url('staff/food_category_sort_date/'.$company.'/'.strtolower($cat->category).'/'.strtolower('sunday')); ?>">Sunday</a></li>
                                    </ul>
                                </div>
                            </div>
                            
                            <!--<div class="sidebar-widget shop-sidebar-border mb-40 pt-40">
                                <h4 class="sidebar-widget-title">Color </h4>
                                <div class="sidebar-widget-list">
                                    <ul>
                                        <li>
                                            <div class="sidebar-widget-list-left">
                                                <input type="checkbox" value=""> <a href="#">Green <span>7</span> </a>
                                                <span class="checkmark"></span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="sidebar-widget-list-left">
                                                <input type="checkbox" value=""> <a href="#">Cream <span>8</span> </a>
                                                <span class="checkmark"></span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="sidebar-widget-list-left">
                                                <input type="checkbox" value=""> <a href="#">Blue <span>9</span> </a>
                                                <span class="checkmark"></span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="sidebar-widget-list-left">
                                                <input type="checkbox" value=""> <a href="#">Black <span>3</span> </a>
                                                <span class="checkmark"></span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>-->
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php include 'menu/footer.php'; ?>
        
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-5 col-md-6 col-12 col-sm-12">
                                <div class="tab-content quickview-big-img">
                                    <div id="pro-1" class="tab-pane fade show active">
                                        <img src="<?php echo base_url('uploads/food/food_burger1.jpg'); ?>" alt="">
                                    </div>
                                    <div id="pro-2" class="tab-pane fade">
                                        <img src="<?php echo base_url('uploads/food/food_burger1.jpg'); ?>" alt="">
                                    </div>
                                    <div id="pro-3" class="tab-pane fade">
                                        <img src="<?php echo base_url('uploads/food/food_burger1.jpg'); ?>" alt="">
                                    </div>
                                    <div id="pro-4" class="tab-pane fade">
                                        <img src="<?php echo base_url('uploads/food/food_burger1.jpg'); ?>" alt="">
                                    </div>
                                </div>
                                <div class="quickview-wrap mt-15">
                                    <div class="quickview-slide-active nav-style-6">
                                        <a class="active" data-toggle="tab" href="#pro-1"><img src="<?php echo base_url('uploads/food/food_burger1.jpg'); ?>" alt=""></a>
                                        <a data-toggle="tab" href="#pro-2"><img height="70" width="90" src="<?php echo base_url('uploads/food/food_burger2.jpg'); ?>" alt=""></a>
                                        <a data-toggle="tab" href="#pro-3"><img height="70" width="90" src="<?php echo base_url('uploads/food/food_burger1.jpg'); ?>" alt=""></a>
                                        <a data-toggle="tab" href="#pro-4"><img height="70" width="90" src="<?php echo base_url('uploads/food/food_burger2.jpg'); ?>" alt=""></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-7 col-md-6 col-12 col-sm-12">
                                <div class="product-details-content quickview-content">
                                    <h2>Big Mac Burger</h2>
                                    <p>Enjoy the best meals.</p>
                                    <div class="pro-details-price">
                                        <span class="new-price">£5.72</span>
                                        <span class="old-price">£7.72</span>
                                    </div>
                                    <!--<div class="pro-details-color-wrap">
                                        <span>Color:</span>
                                        <div class="pro-details-color-content">
                                            <ul>
                                                <li><a class="dolly" href="#">dolly</a></li>
                                                <li><a class="white" href="#">white</a></li>
                                                <li><a class="azalea" href="#">azalea</a></li>
                                                <li><a class="peach-orange" href="#">Orange</a></li>
                                                <li><a class="mona-lisa active" href="#">lisa</a></li>
                                                <li><a class="cupid" href="#">cupid</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="pro-details-size">
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
                                    <div class="pro-details-quality">
                                        <span>Quantity:</span>
                                        <div class="cart-plus-minus">
                                            <input class="cart-plus-minus-box" type="text" name="qtybutton" value="1">
                                        </div>
                                    </div>
                                    <div class="pro-details-action-wrap">
                                        <div class="pro-details-add-to-cart">
                                            <a title="Add to Cart" href="#">Add To Cart </a>
                                        </div>
                                        <div class="pro-details-action">
                                            <a class="social" title="Social" href="#"><i class="icon-share"></i></a>
                                            <div class="product-dec-social">
                                                <a class="facebook" title="Facebook" href="#"><i class="icon-social-facebook"></i></a>
                                                <a class="twitter" title="Twitter" href="#"><i class="icon-social-twitter"></i></a>
                                                <a class="instagram" title="Instagram" href="#"><i class="icon-social-instagram"></i></a>
                                                <a class="pinterest" title="Pinterest" href="#"><i class="icon-social-pinterest"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal end -->
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