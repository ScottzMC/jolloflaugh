<?php 
    
    /*function getDistance($addressFrom, $addressTo, $unit = ''){
        // Google API key
        $apiKey = 'AIzaSyALsc0dOYYaHaiXImBpuy09vWaMsu0zaxA';
        
        // Change address format
        $formattedAddrFrom    = str_replace(' ', '+', $addressFrom);
        $formattedAddrTo     = str_replace(' ', '+', $addressTo);
        
        // Geocoding API request with start address
        $geocodeFrom = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddrFrom.'&sensor=false&key='.$apiKey);
        $outputFrom = json_decode($geocodeFrom);
        if(!empty($outputFrom->error_message)){
            return $outputFrom->error_message;
        }
        
        // Geocoding API request with end address
        $geocodeTo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddrTo.'&sensor=false&key='.$apiKey);
        $outputTo = json_decode($geocodeTo);
        if(!empty($outputTo->error_message)){
            return $outputTo->error_message;
        }
        
        // Get latitude and longitude from the geodata
        $latitudeFrom    = $outputFrom->results[0]->geometry->location->lat;
        $longitudeFrom    = $outputFrom->results[0]->geometry->location->lng;
        $latitudeTo        = $outputTo->results[0]->geometry->location->lat;
        $longitudeTo    = $outputTo->results[0]->geometry->location->lng;
        
        // Calculate distance between latitude and longitude
        $theta    = $longitudeFrom - $longitudeTo;
        $dist    = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
        $dist    = acos($dist);
        $dist    = rad2deg($dist);
        $miles    = $dist * 60 * 1.1515;
        
        // Convert unit and return distance
        $unit = strtoupper($unit);
        if($unit == "K"){
            return round($miles * 1.609344, 2).' km';
        }elseif($unit == "M"){
            return round($miles * 1609.344, 2).' meters';
        }else{
            return round($miles, 2).' miles';
        }
    }*/
    
    foreach($distance as $dist){}
    
?>

        <header class="header-area">
            <div class="header-large-device section-padding-2">
                <div class="header-bottom">
                    <div class="container-fluid">
                        <div class="border-bottom-6">
                            <div class="row align-items-center">
                                <div class="col-xl-3 col-lg-2">
                                    <div class="logo">
                                        <a href="<?php echo site_url('home'); ?>"><img src="<?php echo base_url('assets/images/logo/logo.png'); ?>" alt="logo"></a>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-7">
                                    <div class="main-menu main-menu-padding-1 main-menu-lh-3 main-menu-hm4 main-menu-center">
                                        <nav>
                                            <ul>
                                                <!--<li><a class="active" style="font-size: 12px;" href="< ?php echo site_url('home'); ?>">HOME </a></li>-->
                                                <li><a style="font-size: 12px;" href="<?php echo site_url('food/all'); ?>">Food </a></li>
                                                <?php if($this->session->userdata('urole') == 'User'){ ?>
                                                    <!--<li><a style="font-size: 12px;" href="< ?php echo site_url('account/logout'); ?>">Logout </a></li>-->
                                                <?php }else if($this->session->userdata('urole') == 'Admin'){ ?>
                                                    <!--<li><a style="font-size: 12px;" href="< ?php echo site_url('account/logout'); ?>">Logout </a></li>-->
                                                <?php }else if($this->session->userdata('urole') == 'Kitchen'){ ?>
                                                    <!--<li><a style="font-size: 12px;" href="< ?php echo site_url('account/logout'); ?>">Logout </a></li>-->
                                                <?php }else{ ?>
                                                    <li><a style="font-size: 12px;" href="<?php echo site_url('account/login'); ?>">Login </a></li>
                                                    <li><a style="font-size: 12px;" href="<?php echo site_url('account/register'); ?>">Register </a></li>
                                                <?php } ?>
                                                
                                                <!--< ?php if(!empty($schedule)){ foreach($schedule as $sch){} ?>
                                                <li><a style="font-size: 12px;" href="#">Date - < ?php echo $sch->delivery_date; ?></a></li>
                                                <li><a style="font-size: 12px;" href="#">Time - < ?php echo $sch->num_time; ?></a></li>
                                                <li><a style="font-size: 12px;" href="#">Postcode - < ?php echo $sch->postcode; ?></a></li>
                                                < ?php } ?>-->
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3">
                                    <div class="header-action header-action-flex header-action-mrg-right">
                                        <div class="same-style-2 header-search-1">
                                            <a class="search-toggle" href="#">
                                                <i class="icon-magnifier s-open"></i>
                                                <i class="icon_close s-close"></i>
                                            </a>
                                            <div class="search-wrap-1">
                                                <form action="<?php echo base_url('food/search'); ?>" method="POST">
                                                    <input type="text" name="search_query" placeholder="Search here...">
                                                    <button type="submit">
                                                        <i class="icon-magnifier"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <?php 
                                        $session_email = $this->session->userdata('uemail');
                                        if(!empty($session_email)){ ?>
                                        <div class="same-style-2 same-style-2-font-inc">
                                            <a href="<?php echo site_url('shopping/my_account'); ?>"><i class="icon-user"></i></a>
                                        </div>
                                        <div class="same-style-2 same-style-2-font-inc header-cart">
                                            <a class="cart-active" href="#">
                                                <i class="icon-basket-loaded"></i><span class="pro-count black"><?php echo $this->cart->total_items(); ?></span>
                                            </a>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="header-small-device small-device-ptb-1 border-bottom-2">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-5">
                            <div class="mobile-logo">
                                <a href="<?php echo site_url('home'); ?>">
                                    <img alt="logo" src="<?php echo base_url('assets/images/logo/logo.png'); ?>">
                                </a>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="header-action header-action-flex">
                                <?php 
                                $session_email = $this->session->userdata('uemail');
                                if(!empty($session_email)){ ?>
                                <div class="same-style-2 same-style-2-font-inc">
                                    <a href="<?php echo site_url('shopping/my_account'); ?>"><i class="icon-user"></i></a>
                                </div>
                                <div class="same-style-2 same-style-2-font-inc header-cart">
                                    <a class="cart-active" href="#">
                                        <i class="icon-basket-loaded"></i><span class="pro-count black"><?php echo $this->cart->total_items(); ?></span>
                                    </a>
                                </div>
                                <?php } ?>
                                <div class="same-style-2 main-menu-icon">
                                    <a class="mobile-header-button-active" href="#"><i class="icon-menu"></i> </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- mobile header start -->
        <div class="mobile-header-active mobile-header-wrapper-style">
            <div class="clickalbe-sidebar-wrap">
                <a class="sidebar-close"><i class="icon_close"></i></a>
                <div class="mobile-header-content-area">
                    <div class="mobile-search mobile-header-padding-border-1">
                        <form action="<?php echo base_url('food/search'); ?>" method="POST">
                            <input type="text" name="search_query" placeholder="Search here...">
                            <button type="submit">
                                <i class="icon-magnifier"></i>
                            </button>
                        </form>
                    </div>
                    <div class="mobile-menu-wrap mobile-header-padding-border-2">
                        <!-- mobile menu start -->
                        <nav>
                            <ul class="mobile-menu">
                                <!--<li class="menu-item-has-children"><a href="< ?php echo site_url('home'); ?>">Home</a></li>-->
                                <li><a href="<?php echo site_url('food/all'); ?>">Food </a></li>
                            <!--< ?php if($this->session->userdata('urole') == 'User'){ ?>
                                <li><a style="font-size: 12px;" href="<?php echo site_url('account/logout'); ?>">Logout </a></li>
                            < ?php }else if($this->session->userdata('urole') == 'Admin'){ ?>
                                <li><a style="font-size: 12px;" href="<?php echo site_url('account/logout'); ?>">Logout </a></li>
                            < ?php }else if($this->session->userdata('urole') == 'Kitchen'){ ?>
                                <li><a style="font-size: 12px;" href="<?php echo site_url('account/logout'); ?>">Logout </a></li>
                            < ?php }else{ ?>
                                <li><a style="font-size: 12px;" href="<?php echo site_url('account/login'); ?>">Login </a></li>
                                <li><a style="font-size: 12px;" href="<?php echo site_url('account/register'); ?>">Register </a></li>
                            < ?php } ?>-->
                                
                                <!--< ?php if(!empty($schedule)){ foreach($schedule as $sch){} ?>
                                <li><a href="#">Date - <?php echo $sch->delivery_date; ?></a></li>
                                <li><a href="#">Time - <?php echo $sch->num_time; ?></a></li>
                                <li><a href="#">Postcode - <?php echo $sch->postcode; ?></a></li>
                                < ?php }else{ ?>
                                <li><a href="#">Date - </a></li>
                                <li><a href="#">Time - </a></li>
                                <li><a href="#">Postcode - </a></li>
                                < ?php } ?>-->
                            </ul>
                        </nav>
                        <!-- mobile menu end -->
                    </div>

                    <div class="mobile-contact-info mobile-header-padding-border-4">
                        <ul>
                            <li><i class="icon-phone "></i>07445 9034 993</li>
                            <li><i class="icon-envelope-open "></i> norda@domain.com</li>
                            <li><i class="icon-home"></i>869 General Village Apt. MX1 I99, Manchester, UK</li>
                        </ul>
                    </div>
                    <div class="mobile-social-icon">
                        <a class="facebook" href="#"><i class="icon-social-facebook"></i></a>
                        <a class="twitter" href="#"><i class="icon-social-twitter"></i></a>
                        <a class="pinterest" href="#"><i class="icon-social-pinterest"></i></a>
                        <a class="instagram" href="#"><i class="icon-social-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- mini cart start -->
        <div class="sidebar-cart-active">
            <div class="sidebar-cart-all">
                <a class="cart-close" href="#"><i class="icon_close"></i></a>
                <div class="cart-content">
                    <h3>Shopping Cart</h3>
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
                      <?php
                        echo form_hidden('cart['. $item['id'] .'][id]', $item['id']);
                        echo form_hidden('cart['. $item['id'] .'][rowid]', $item['rowid']);
                        echo form_hidden('cart['. $item['id'] .'][price]', $item['price']);
                        echo form_hidden('cart['. $item['id'] .'][qty]', $item['qty']);
                      ?>
                        <li class="single-product-cart">
                            <div class="cart-img">
                                <a href="<?php echo site_url('food/detail/'.$item['id'].'/'.strtolower($item['name'])); ?>">
                                    <img height="98" width="98" src="<?php echo base_url('uploads/food/'.$image); ?>" alt="<?php echo $item['name']; ?>">
                                </a>
                            </div>
                            <div class="cart-title">
                                <h4>
                                    <a href="<?php echo site_url('food/detail/'.$item['id'].'/'.strtolower($item['name'])); ?>">
                                        <?php echo str_replace('-', ' ', $item['name']); ?>
                                    </a>
                                </h4>
                                <span> <?php echo $item['qty']; ?> × £<?php echo $item['price']; ?> </span>
                            </div>
                            <div class="cart-delete">
                                <a href="<?php echo site_url('shopping/remove_cart/'.$item['rowid']); ?>">×</a>
                            </div>
                        </li>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                    <div class="cart-total">
                        <h4>Subtotal: <span>£<?php echo $this->cart->total(); ?></span></h4>
                    </div>
                    <?php 
                    $session_email = $this->session->userdata('uemail');
                    if(!empty($session_email)){ ?>
                    <div class="cart-total">
                        <h4>Delivery:
                        <?php 
                        
                        if($dist == '0 km' || $dist == '6.16 km' || $dist < '10.00 km'){
                        ?>
                        <span class="alert alert-success">Food can be Delivered</span>
                        <?php }else{ ?>
                        <span class="alert alert-danger">Food needs to be Collected</span>
                        <?php } ?>
                        </h4>
                        <br><br>
                    </div>
                    <?php } ?>
                    
                    <div class="cart-checkout-btn">
                        <a class="btn-hover cart-btn-style" href="<?php echo site_url('shopping/view_cart'); ?>">view cart</a>
                        <a class="no-mrg btn-hover cart-btn-style" href="<?php echo site_url('shopping/checkout'); ?>">checkout</a>
                    </div>
                </div>
            </div>
        </div>