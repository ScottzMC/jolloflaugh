<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Welcome to Jollof N Laugh</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('assets/images/favicon.png'); ?>">

    <!-- All CSS is here
	============================================ -->

    <link rel="stylesheet" href="<?php echo base_url('assets/css/vendor/bootstrap.min.css'); ?>">
    
    <link rel="stylesheet" href="<?php echo base_url('assets/css/vendor/cerebrisans.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/vendor/simple-line-icons.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/vendor/elegant.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/plugins/nice-select.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/plugins/slick.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style.min.css'); ?>">

    <!-- Use the minified version files listed below for better performance and remove the files listed above
    <link rel="stylesheet" href="assets/css/vendor/vendor.min.css">
    <link rel="stylesheet" href="assets/css/plugins/plugins.min.css">-->

</head>

<style>
    .select-time {
      -webkit-tap-highlight-color: transparent;
      background-color: #fff;
      border-radius: 5px;
      border: solid 1px #e8e8e8;
      box-sizing: border-box;
      clear: both;
      cursor: pointer;
      display: block;
      float: left;
      font-family: inherit;
      font-size: 14px;
      font-weight: normal;
      height: 42px;
      line-height: 40px;
      outline: none;
      padding-left: 18px;
      padding-right: 30px;
      position: relative;
      text-align: left !important;
      -webkit-transition: all 0.2s ease-in-out;
      transition: all 0.2s ease-in-out;
      -webkit-user-select: none;
         -moz-user-select: none;
          -ms-user-select: none;
              user-select: none;
      white-space: nowrap;
      width: auto; 
      margin-top: -42px;
      margin-left: 145px;
    }
 
</style>

<body>
    
    <?php 
    $session_email = $this->session->userdata('uemail');
    ?>

    <div class="main-wrapper">
        <?php include 'menu/nav.php'; ?>

        <div class="slider-area">
            <div class="hero-slider-active-1 nav-style-1 dot-style-2 dot-style-2-position-2 dot-style-2-active-black">
                <?php if(!empty($slider)){ foreach($slider as $slid){ ?>
                <div class="single-hero-slider single-animation-wrap slider-height-2 custom-d-flex custom-align-item-center bg-img hm2-slider-bg res-white-overly-xs" 
                style="background:url(uploads/slider/<?php echo $slid->image; ?>); -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover; background-repeat: no-repeat;">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="hero-slider-content-4 slider-animated-1">
                                    <h1 class="animated" style="color: #fff;"><?php echo $slid->title; ?></h1>
                                    <p class="animated" style="color: #fff;"><?php echo $slid->subtitle; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } }else{ echo ''; } ?>
                
            </div>
            
        </div>
        
        <div class="product-area pt-115 pb-80" style="margin-top: -50px;">
            <div class="container">
                <div class="section-title-tab-wrap mb-55">
                    <div class="section-title-4">
                        <h2>Stews</h2>
                    </div>
                </div>
                <div class="tab-content jump">
                    
                    <div id="product-0" class="tab-pane active">
                        <div class="row">
                        <?php if(!empty($stews)){ foreach($stews as $stw){ ?>
                        
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="single-product-wrap mb-35">
                                    <div class="product-img product-img-zoom mb-15">
                                        <img height="270" width="420" src="<?php echo base_url('uploads/food/'.$stw->image1); ?>" alt="<?php echo $stw->title; ?>">
                                    </div>
                                    <div class="product-content-wrap-2 text-center">
                                        <h3><?php echo str_replace('-', ' ', $stw->title); ?></h3>
                                        <div class="product-price-2">
                                            <span>£<?php echo $stw->price; ?></span>
                                        </div>
                                        <?php // if(!empty($session_email)){ ?>
                                        <div class="pro-details-action-wrap">
                                            <div class="pro-add-to-cart">
                                                <form action="<?php echo base_url('jollof_n_laugh/add_cart'); ?>" method="POST">
                                                    <input type="hidden" name="id" value="<?php echo $stw->id; ?>">
                                                    <input type="hidden" name="code" value="<?php echo $stw->code; ?>">
                                                    <input type="hidden" name="title" value="<?php echo $stw->title; ?>">
                                                    <input type="hidden" name="category" value="<?php echo $stw->category; ?>">
                                                    <input type="hidden" name="price" value="<?php echo $stw->price; ?>">
                                                    <input type="hidden" name="image" value="<?php echo $stw->image1; ?>">
                                                    <button type="submit" title="Add to Cart">Add To Cart</button>
                                                 </form>
                                            </div>
                                        </div>
                                        <?php //} ?>
                                    </div>
                                    <div class="product-content-wrap-2 product-content-position text-center">
                                        <h3>
                                            <?php echo str_replace('-', ' ', $stw->title); ?>
                                        </h3>
                                        <div class="product-price-2">
                                            <span>£<?php echo $stw->price; ?></span>
                                        </div>
                                        <?php //if(!empty($session_email)){ ?>
                                        <div class="pro-details-action-wrap">
                                            <div class="pro-add-to-cart">
                                                <form action="<?php echo base_url('jollof_n_laugh/add_cart'); ?>" method="POST">
                                                    <input type="hidden" name="id" value="<?php echo $stw->id; ?>">
                                                    <input type="hidden" name="code" value="<?php echo $stw->code; ?>">
                                                    <input type="hidden" name="title" value="<?php echo $stw->title; ?>">
                                                    <input type="hidden" name="category" value="<?php echo $stw->category; ?>">
                                                    <input type="hidden" name="price" value="<?php echo $stw->price; ?>">
                                                    <input type="hidden" name="image" value="<?php echo $stw->image1; ?>">
                                                    <button type="submit" title="Add to Cart">Add To Cart</button>
                                                 </form>
                                            </div>
                                        </div>
                                        <?php //} ?>
                                    </div>
                                </div>
                            </div>
                        <?php } }else{ echo ''; } ?>
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>
        
        <div class="product-area pt-115 pb-80" style="margin-top: -50px;">
            <div class="container">
                <div class="section-title-tab-wrap mb-55">
                    <div class="section-title-4">
                        <h2>Vegan</h2>
                    </div>
                </div>
                <div class="tab-content jump">
                    
                    <div id="product-0" class="tab-pane active">
                        <div class="row">
                        <?php if(!empty($vegan)){ foreach($vegan as $veg){ ?>
                        
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="single-product-wrap mb-35">
                                    <div class="product-img product-img-zoom mb-15">
                                        <img height="270" width="420" src="<?php echo base_url('uploads/food/'.$veg->image1); ?>" alt="<?php echo $veg->title; ?>">
                                    </div>
                                    <div class="product-content-wrap-2 text-center">
                                        <h3><?php echo str_replace('-', ' ', $veg->title); ?></h3>
                                        <div class="product-price-2">
                                            <span>£<?php echo $veg->price; ?></span>
                                        </div>
                                        <?php //if(!empty($session_email)){ ?>
                                        <div class="pro-details-action-wrap">
                                            <div class="pro-add-to-cart">
                                                <form action="<?php echo base_url('jollof_n_laugh/add_cart'); ?>" method="POST">
                                                    <input type="hidden" name="id" value="<?php echo $veg->id; ?>">
                                                    <input type="hidden" name="code" value="<?php echo $veg->code; ?>">
                                                    <input type="hidden" name="title" value="<?php echo $veg->title; ?>">
                                                    <input type="hidden" name="category" value="<?php echo $veg->category; ?>">
                                                    <input type="hidden" name="price" value="<?php echo $veg->price; ?>">
                                                    <input type="hidden" name="image" value="<?php echo $veg->image1; ?>">
                                                    <button type="submit" title="Add to Cart">Add To Cart</button>
                                                 </form>
                                            </div>
                                        </div>
                                        <?php //} ?>
                                    </div>
                                    <div class="product-content-wrap-2 product-content-position text-center">
                                        <h3>
                                            <?php echo str_replace('-', ' ', $veg->title); ?>
                                        </h3>
                                        <div class="product-price-2">
                                            <span>£<?php echo $veg->price; ?></span>
                                        </div>
                                        <?php //if(!empty($session_email)){ ?>
                                        <div class="pro-details-action-wrap">
                                            <div class="pro-add-to-cart">
                                                <form action="<?php echo base_url('jollof_n_laugh/add_cart'); ?>" method="POST">
                                                    <input type="hidden" name="id" value="<?php echo $veg->id; ?>">
                                                    <input type="hidden" name="code" value="<?php echo $veg->code; ?>">
                                                    <input type="hidden" name="title" value="<?php echo $veg->title; ?>">
                                                    <input type="hidden" name="category" value="<?php echo $veg->category; ?>">
                                                    <input type="hidden" name="price" value="<?php echo $veg->price; ?>">
                                                    <input type="hidden" name="image" value="<?php echo $veg->image1; ?>">
                                                    <button type="submit" title="Add to Cart">Add To Cart</button>
                                                 </form>
                                            </div>
                                        </div>
                                        <?php //} ?>
                                    </div>
                                </div>
                            </div>
                        <?php } }else{ echo ''; } ?>
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>

        <div class="product-area pt-115 pb-80" style="margin-top: 0px;">
            <div class="container">
                <div class="section-title-tab-wrap mb-55">
                    <div class="section-title-4">
                        <h2>Rice</h2>
                    </div>
                </div>
                <div class="tab-content jump">
                    
                    <div id="product-0" class="tab-pane active">
                        <div class="row">
                        <?php if(!empty($rice)){ foreach($rice as $rc){ ?>
                        
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="single-product-wrap mb-35">
                                    <div class="product-img product-img-zoom mb-15">
                                        <img height="270" width="420" src="<?php echo base_url('uploads/food/'.$rc->image1); ?>" alt="<?php echo $rc->title; ?>">
                                    </div>
                                    <div class="product-content-wrap-2 text-center">
                                        <h3><?php echo str_replace('-', ' ', $rc->title); ?></h3>
                                        <div class="product-price-2">
                                            <span>£<?php echo $rc->price; ?></span>
                                        </div>
                                        <?php //if(!empty($session_email)){ ?>
                                        <div class="pro-details-action-wrap">
                                            <div class="pro-add-to-cart">
                                                <form action="<?php echo base_url('jollof_n_laugh/add_cart'); ?>" method="POST">
                                                    <input type="hidden" name="id" value="<?php echo $rc->id; ?>">
                                                    <input type="hidden" name="code" value="<?php echo $rc->code; ?>">
                                                    <input type="hidden" name="title" value="<?php echo $rc->title; ?>">
                                                    <input type="hidden" name="category" value="<?php echo $rc->category; ?>">
                                                    <input type="hidden" name="price" value="<?php echo $rc->price; ?>">
                                                    <input type="hidden" name="image" value="<?php echo $rc->image1; ?>">
                                                    <button type="submit" title="Add to Cart">Add To Cart</button>
                                                 </form>
                                            </div>
                                        </div>
                                        <?php //} ?>
                                    </div>
                                    <div class="product-content-wrap-2 product-content-position text-center">
                                        <h3>
                                            <?php echo str_replace('-', ' ', $rc->title); ?>
                                        </h3>
                                        <div class="product-price-2">
                                            <span>£<?php echo $rc->price; ?></span>
                                        </div>
                                        <?php //if(!empty($session_email)){ ?>
                                        <div class="pro-details-action-wrap">
                                            <div class="pro-add-to-cart">
                                                <form action="<?php echo base_url('jollof_n_laugh/add_cart'); ?>" method="POST">
                                                    <input type="hidden" name="id" value="<?php echo $rc->id; ?>">
                                                    <input type="hidden" name="code" value="<?php echo $rc->code; ?>">
                                                    <input type="hidden" name="title" value="<?php echo $rc->title; ?>">
                                                    <input type="hidden" name="category" value="<?php echo $rc->category; ?>">
                                                    <input type="hidden" name="price" value="<?php echo $rc->price; ?>">
                                                    <input type="hidden" name="image" value="<?php echo $rc->image1; ?>">
                                                    <button type="submit" title="Add to Cart">Add To Cart</button>
                                                 </form>
                                            </div>
                                        </div>
                                        <?php //} ?>
                                    </div>
                                </div>
                            </div>
                        <?php } }else{ echo ''; } ?>
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>

        <div class="product-area pt-115 pb-80" style="margin-top: -50px;">
            <div class="container">
                <div class="section-title-tab-wrap mb-55">
                    <div class="section-title-4">
                        <h2>Sides</h2>
                    </div>
                </div>
                <div class="tab-content jump">
                    
                    <div id="product-0" class="tab-pane active">
                        <div class="row">
                        <?php if(!empty($side)){ foreach($side as $sde){ ?>
                        
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="single-product-wrap mb-35">
                                    <div class="product-img product-img-zoom mb-15">
                                        <img height="270" width="420" src="<?php echo base_url('uploads/food/'.$sde->image1); ?>" alt="<?php echo $sde->title; ?>">
                                    </div>
                                    <div class="product-content-wrap-2 text-center">
                                        <h3><?php echo str_replace('-', ' ', $sde->title); ?></h3>
                                        <div class="product-price-2">
                                            <span>£<?php echo $sde->price; ?></span>
                                        </div>
                                        <?php //if(!empty($session_email)){ ?>
                                        <div class="pro-details-action-wrap">
                                            <div class="pro-add-to-cart">
                                                <form action="<?php echo base_url('jollof_n_laugh/add_cart'); ?>" method="POST">
                                                    <input type="hidden" name="id" value="<?php echo $sde->id; ?>">
                                                    <input type="hidden" name="code" value="<?php echo $sde->code; ?>">
                                                    <input type="hidden" name="title" value="<?php echo $sde->title; ?>">
                                                    <input type="hidden" name="category" value="<?php echo $sde->category; ?>">
                                                    <input type="hidden" name="price" value="<?php echo $sde->price; ?>">
                                                    <input type="hidden" name="image" value="<?php echo $sde->image1; ?>">
                                                    <button type="submit" title="Add to Cart">Add To Cart</button>
                                                 </form>
                                            </div>
                                        </div>
                                        <?php //} ?>
                                    </div>
                                    <div class="product-content-wrap-2 product-content-position text-center">
                                        <h3>
                                            <?php echo str_replace('-', ' ', $sde->title); ?>
                                        </h3>
                                        <div class="product-price-2">
                                            <span>£<?php echo $sde->price; ?></span>
                                        </div>
                                        <?php //if(!empty($session_email)){ ?>
                                        <div class="pro-details-action-wrap">
                                            <div class="pro-add-to-cart">
                                                <form action="<?php echo base_url('jollof_n_laugh/add_cart'); ?>" method="POST">
                                                    <input type="hidden" name="id" value="<?php echo $sde->id; ?>">
                                                    <input type="hidden" name="code" value="<?php echo $sde->code; ?>">
                                                    <input type="hidden" name="title" value="<?php echo $sde->title; ?>">
                                                    <input type="hidden" name="category" value="<?php echo $sde->category; ?>">
                                                    <input type="hidden" name="price" value="<?php echo $sde->price; ?>">
                                                    <input type="hidden" name="image" value="<?php echo $sde->image1; ?>">
                                                    <button type="submit" title="Add to Cart">Add To Cart</button>
                                                 </form>
                                            </div>
                                        </div>
                                        <?php //} ?>
                                    </div>
                                </div>
                            </div>
                        <?php } }else{ echo ''; } ?>
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>
        
        <div class="product-area pt-115 pb-80" style="margin-top: -50px;">
            <div class="container">
                <div class="section-title-tab-wrap mb-55">
                    <div class="section-title-4">
                        <h2>Dessert</h2>
                    </div>
                </div>
                <div class="tab-content jump">
                    
                    <div id="product-0" class="tab-pane active">
                        <div class="row">
                        <?php if(!empty($dessert)){ foreach($dessert as $des){ ?>
                        
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="single-product-wrap mb-35">
                                    <div class="product-img product-img-zoom mb-15">
                                        <img height="270" width="420" src="<?php echo base_url('uploads/food/'.$des->image1); ?>" alt="<?php echo $des->title; ?>">
                                    </div>
                                    <div class="product-content-wrap-2 text-center">
                                        <h3><?php echo str_replace('-', ' ', $des->title); ?></h3>
                                        <div class="product-price-2">
                                            <span>£<?php echo $des->price; ?></span>
                                        </div>
                                        <?php //if(!empty($session_email)){ ?>
                                        <div class="pro-details-action-wrap">
                                            <div class="pro-add-to-cart">
                                                <form action="<?php echo base_url('jollof_n_laugh/add_cart'); ?>" method="POST">
                                                    <input type="hidden" name="id" value="<?php echo $des->id; ?>">
                                                    <input type="hidden" name="code" value="<?php echo $des->code; ?>">
                                                    <input type="hidden" name="title" value="<?php echo $des->title; ?>">
                                                    <input type="hidden" name="category" value="<?php echo $des->category; ?>">
                                                    <input type="hidden" name="price" value="<?php echo $des->price; ?>">
                                                    <input type="hidden" name="image" value="<?php echo $des->image1; ?>">
                                                    <button type="submit" title="Add to Cart">Add To Cart</button>
                                                 </form>
                                            </div>
                                        </div>
                                        <?php //} ?>
                                    </div>
                                    <div class="product-content-wrap-2 product-content-position text-center">
                                        <h3>
                                            <?php echo str_replace('-', ' ', $des->title); ?>
                                        </h3>
                                        <div class="product-price-2">
                                            <span>£<?php echo $des->price; ?></span>
                                        </div>
                                        <?php //if(!empty($session_email)){ ?>
                                        <div class="pro-details-action-wrap">
                                            <div class="pro-add-to-cart">
                                                <form action="<?php echo base_url('jollof_n_laugh/add_cart'); ?>" method="POST">
                                                    <input type="hidden" name="id" value="<?php echo $des->id; ?>">
                                                    <input type="hidden" name="code" value="<?php echo $des->code; ?>">
                                                    <input type="hidden" name="title" value="<?php echo $des->title; ?>">
                                                    <input type="hidden" name="category" value="<?php echo $des->category; ?>">
                                                    <input type="hidden" name="price" value="<?php echo $des->price; ?>">
                                                    <input type="hidden" name="image" value="<?php echo $des->image1; ?>">
                                                    <button type="submit" title="Add to Cart">Add To Cart</button>
                                                 </form>
                                            </div>
                                        </div>
                                        <?php //} ?>
                                    </div>
                                </div>
                            </div>
                        <?php } }else{ echo ''; } ?>
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>
        
        <?php include 'menu/footer.php'; ?>
        
    </div>

    <!-- All JS is here
============================================ -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script src="<?php echo base_url('assets/js/vendor/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/slick.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/jquery.syotimer.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/jquery.nice-select.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/wow.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/scrollup.js'); ?>"></script>

    <!-- Use the minified version files listed below for better performance and remove the files listed above
<script src="assets/js/vendor/vendor.min.js"></script>
<script src="assets/js/plugins/plugins.min.js"></script>  -->
    <!-- Main JS -->
    <script src="<?php echo base_url('assets/js/main.js'); ?>"></script>

    <script>
    var timeSlots = [];
    <?php 
    $time = strtotime('09:00');    
    for($t=0;$t<=18;$t++) { 
        $slot = date("H:i", strtotime('+'.(30*$t).' minutes', $time));
    ?>
        timeSlots[<?php echo $t;?>] = '<?php echo $slot; ?>';
    <?php } ?>
    $('#delivery_date').change(function(){
        $('#num_time').html('');
        var ddate = $(this).val();
        var todayObj = new Date();
        var ddateObj = new Date(ddate);
        var selDate = ddateObj.getMonth()+'-'+ddateObj.getDate()+'-'+ddateObj.getYear();
        var todayDate = todayObj.getMonth()+'-'+todayObj.getDate()+'-'+todayObj.getYear();
        var todayTime = todayObj.getHours() +':'+ todayObj.getMinutes();
        if(todayDate==selDate) {
            for(var t=0;t<timeSlots.length;t++) {
                if(todayTime<timeSlots[t]) {
                    $('#num_time').append($('<option>', { 
                        value: timeSlots[t],
                        text : timeSlots[t] 
                    }));
                }
            }
        }
        else if(selDate>todayDate) {
            for(var t=0;t<timeSlots.length;t++) {
                $('#num_time').append($('<option>', { 
                    value: timeSlots[t],
                    text : timeSlots[t] 
                }));
            }
        }
    });
    $('#delivery_date').trigger('change');
    </script>
</body>

</html>
