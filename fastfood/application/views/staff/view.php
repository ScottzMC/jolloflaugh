<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Staff || Welcome to Food Delivery Collection</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('assets/images/favicon.png'); ?>">

    <!-- All CSS is here
	============================================ -->

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
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
    
    <?php $company = $this->session->userdata('ucompany'); ?>
    <?php 
    foreach($schedule as $sch){}
    $session_email = $this->session->userdata('uemail');
?>

    <div class="main-wrapper">
        <?php include 'menu/nav.php'; ?>

            <div class="slider-area">
              <div class="hero-slider-active-1 nav-style-1 dot-style-2 dot-style-2-position-2 dot-style-2-active-black">
                <?php if(!empty($slider)){ foreach($slider as $slid){ ?>
                <div class="single-hero-slider single-animation-wrap slider-height-2 custom-d-flex custom-align-item-center bg-img hm2-slider-bg res-white-overly-xs" 
                style="background-image: url(../../uploads/slider/<?php echo $slid->image; ?>); -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover; background-repeat: no-repeat;">
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
            
            <div class="service-area" style="width: auto; margin-left: 100px; margin-top: -100px;">
                <div class="container">
                    <!--<div class="service-wrap">-->
                        <div class="row">
                            <div class="col-lg-6" style="margin-left: 200px;">
                                <div class="categori-search-wrap categori-search-wrap-modify">
                                    
                                    <?php foreach($schedule as $sch){} ?>
                                    
                                    <div class="search-wrap-3">
                                        <form action="<?php echo base_url('staff/home/'.$company); ?>" method="POST">
                                            <input style="background: #fff;" placeholder="Enter Postcode..." type="text" name="postcode" value="<?php echo $sch->postcode; ?>" required>
                                            <select name="delivery_date" id="delivery_date" class="nice-select nice-select-style-1" style="width: 200px;">
                                                <?php for($i=1;$i<7;$i++):?>
                                                    <!--<option value="<?php echo date('Y-m-d'); ?>"><?php echo date('l, dS M'); ?></option>-->
                                                    <option value="<?php echo date('Y-m-d',strtotime("+".$i." day",time()));?>"><?php echo date('l, dS M Y',strtotime("+".$i." day",time()));?></option>
                                                <?php endfor;?>
                                            </select>
                                            
                                            <select name="num_time" id="num_time" class="select-time" style="width: 200px; margin-left: 220px;">
                                                <option value="">--select--</option>
                                            </select>
                                            <button class="button" type="submit" name="btn_schedule" style="font-size: 12px; margin-left: 10px;">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!--</div>-->
                </div>
            </div>
            
        </div>
        
        <?php if(!empty($session_email)){ ?>
        
        <?php 
            
            foreach($distance as $dist){}
            
            if($dist == '0 km' || $dist == '6.16 km' || $dist < '10.00 km'){
            ?>
            <li>
                <div class="alert alert-success alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                   Food can be Delivered
                </div>
            </li>
            <?php }else{ ?>
            <li>
                <div class="alert alert-danger alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    Food needs to be Collected
                </div>
            </li>
            <?php } ?>

        <?php } ?>

        <div class="banner-area padding-10-row-col pb-105" style="margin-top: 100px;">
            <div class="container">
                <div class="row">
                    <?php if(!empty($banner)){ foreach($banner as $ban){ ?>
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="banner-wrap mb-10">
                            <div class="banner-img banner-img-border banner-img-zoom">
                                <a href="<?php echo site_url('staff/food_all/'.$company); ?>">
                                    <img src="<?php echo base_url('uploads/banner/'.$ban->image); ?>" alt="<?php echo $ban->title; ?>" height="250" width="250">
                                </a>
                            </div>
                            <div class="banner-content-3">
                                <h2 style="color: #fff;"><?php echo $ban->title; ?></h2>
                            </div>
                        </div>
                    </div>
                    <?php } }else{ echo ''; } ?>
                </div>
            </div>
        </div>

        <div class="product-area">
            <div class="container">
                <div class="border-bottom-7 hm4-pb-100">
                    <div class="section-title-tab-wrap mb-55">
                        <div class="section-title-4">
                            <h2>Family Orders</h2>
                        </div>
                        <div class="tab-btn-wrap-2">
                            <div class="tab-style-5 nav">
                                <a class="active" href="<?php echo site_url('food/all'); ?>">See All </a>
                            </div>
                        </div>
                    </div>
                    <div class="tab-content jump">
                        <div id="product-family-0" class="tab-pane active">
                            <div class="product-slider-active-4 nav-style-4">
                             <?php if(!empty($family_order)){ foreach($family_order as $family){ ?>
                                <div class="product-plr-1">
                                    <div class="single-product-wrap mb-35">
                                        <div class="product-img product-img-zoom mb-15">
                                            <a href="<?php echo site_url('staff/food_detail/'.$company.'/'.$family->id.'/'.strtolower($family->title)); ?>">
                                                <img height="270" width="324" src="<?php echo base_url('uploads/food/'.$family->image1); ?>" alt="<?php echo $family->title; ?>">
                                            </a>
                                        </div>
                                        <div class="product-content-wrap-2 text-center">
                                            <h3>                                        
                                                <a href="<?php echo site_url('staff/food_detail/'.$company.'/'.$family->id.'/'.strtolower($family->title)); ?>">
                                                    <?php echo str_replace('-', ' ', $family->title); ?>
                                                </a>
                                            </h3>
                                            <div class="product-price-2">
                                                <span>£<?php echo $family->price; ?></span>
                                            </div>
                                        </div>
                                        <div class="product-content-wrap-2 product-content-position text-center">
                                            <h3>
                                                <a href="<?php echo site_url('staff/food_detail/'.$company.'/'.$family->id.'/'.strtolower($family->title)); ?>">
                                                    <?php echo str_replace('-', ' ', $family->title); ?>
                                                </a>
                                            </h3>
                                            <div class="product-price-2">
                                                <span>£<?php echo $family->price; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } }else{ echo ''; } ?>

                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        
        <div class="product-area">
            <div class="container">
                <div class="border-bottom-7 hm4-pb-100">
                    <div class="section-title-tab-wrap mb-55">
                        <div class="section-title-4">
                            <h2>Meal Deals</h2>
                        </div>
                        <div class="tab-btn-wrap-2">
                            <div class="tab-style-5 nav">
                                <a class="active" href="<?php echo site_url('food/all'); ?>">See All </a>
                            </div>
                        </div>
                    </div>
                    <div class="tab-content jump">
                        <div id="product-family-0" class="tab-pane active">
                            <div class="product-slider-active-4 nav-style-4">
                             <?php if(!empty($family_order)){ foreach($family_order as $family){ ?>
                                <div class="product-plr-1">
                                    <div class="single-product-wrap mb-35">
                                        <div class="product-img product-img-zoom mb-15">
                                            <a href="<?php echo site_url('staff/food_detail/'.$company.'/'.$family->id.'/'.strtolower($family->title)); ?>">
                                                <img height="270" width="324" src="<?php echo base_url('uploads/food/'.$family->image1); ?>" alt="<?php echo $family->title; ?>">
                                            </a>
                                        </div>
                                        <div class="product-content-wrap-2 text-center">
                                            <h3>                                        
                                                <a href="<?php echo site_url('staff/food_detail/'.$company.'/'.$family->id.'/'.strtolower($family->title)); ?>">
                                                    <?php echo str_replace('-', ' ', $family->title); ?>
                                                </a>
                                            </h3>
                                            <div class="product-price-2">
                                                <span>£<?php echo $family->price; ?></span>
                                            </div>
                                        </div>
                                        <div class="product-content-wrap-2 product-content-position text-center">
                                            <h3>
                                                <a href="<?php echo site_url('staff/food_detail/'.$company.'/'.$family->id.'/'.strtolower($family->title)); ?>">
                                                    <?php echo str_replace('-', ' ', $family->title); ?>
                                                </a>
                                            </h3>
                                            <div class="product-price-2">
                                                <span>£<?php echo $family->price; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } }else{ echo ''; } ?>

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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script src="<?php echo base_url('assets/js/vendor/modernizr-3.6.0.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/vendor/jquery-migrate-3.3.0.min.js'); ?>"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
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
