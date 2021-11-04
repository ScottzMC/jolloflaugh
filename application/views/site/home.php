<!doctype html>
<html lang="en">

<head>
	<!-- Basic Page Needs =====================================-->
	<meta charset="utf-8">

	<!-- Mobile Specific Metas ================================-->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Site Title- -->
	<title>Welcome to Ticket Event Platform</title>

	<!-- CSS
   ==================================================== -->
	<!-- Bootstrap -->
	<link rel="stylesheet" href="<?php echo base_url('css/bootstrap.min.css'); ?>">

	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?php echo base_url('css/font-awesome.min.css'); ?>">

	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?php echo base_url('css/animate.css'); ?>">

	<!-- IcoFonts -->
	<link rel="stylesheet" href="<?php echo base_url('css/icofonts.css'); ?>">

	<!-- Owl Carousel -->
	<link rel="stylesheet" href="<?php echo base_url('css/owlcarousel.min.css'); ?>">

	<!-- slick -->
	<link rel="stylesheet" href="<?php echo base_url('css/slick.css'); ?>">

	<!-- navigation -->
	<link rel="stylesheet" href="<?php echo base_url('css/navigation.css'); ?>">

	<!-- magnific popup -->
	<link rel="stylesheet" href="<?php echo base_url('css/magnific-popup.css'); ?>">

	<!-- Style -->
	<link rel="stylesheet" href="<?php echo base_url('css/style.css'); ?>">
	<!-- Style -->
	<link rel="stylesheet" href="<?php echo base_url('css/colors/color-13.css'); ?>">

	<!-- Responsive -->
	<link rel="stylesheet" href="<?php echo base_url('css/responsive.css'); ?>">


	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

</head>

<body class="body-color">
	<?php include 'menu/nav.php'; ?>
	
	<style type="text/css">
       .mobileHide{ 
           display: inline;
       }
       /* Smartphone Portrait and Landscape */
       @media only screen and (min-device-width : 320px) and (max-device-width : 480px){  
        .mobileHide { display: none;}
        .item{ height: 350px;}
       }
    </style>
	
	<!-- block wrapper start-->
	<section class="block-wrapper p-30 section-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="ts-overlay-style featured-post owl-carousel" id="featured-slider-5">
						<?php if(!empty($slider)){ foreach($slider as $slid){ ?>
						<!-- item -->
						<div class="item" style="background-image:url(uploads/slider/<?php echo $slid->image; ?>); background-size: 100% 100%; height: 600px;">
							<div class="overlay-post-content">
								<div class="post-content">
									<h2 class="post-title lg">
										<a href="<?php echo site_url('event/detail'); ?>"><?php echo $slid->title; ?></a>
									</h2>
								</div>
							</div>
							<!--/ Featured post end -->
						</div>
						<!-- item end-->
                        <?php } } ?>
					</div>
					<!-- ts overlay style end-->
				</div>
			</div>
		</div>
	</section>
	<!-- block wrapper end-->

	<!-- block post area start-->
	<section class="block-wrapper mt-15">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 pr-0">
				    <?php if(!empty($main_section)){ foreach($main_section as $main_sect){} ?>
					<div class="ts-overlay-style featured-post featured-post-style">
						<div class="item" style="background-image:url(uploads/banner/<?php echo $main_sect->image; ?>)">
							<!--<div class="overlay-post-content">
								<div class="post-content">
									<h2 class="post-title lg">
										<a href="#">Clothing and Accessories for the Fashionable Crypto Trader</a>
									</h2>
								</div>
							</div>-->
							<!--/ Featured post end -->
						</div>
					</div>
					<?php } ?>
					<!-- ts overlay end-->
				</div>
				<!-- col end-->
				<div class="col-lg-4 ts-grid-style-3 featured-post p-1">
				    <?php if(!empty($top_section)){ foreach($top_section as $top_sect){ ?>
					<div class="ts-overlay-style ">
						<div class="item" style="background-image: url(uploads/banner/<?php echo $top_sect->image; ?>);">
							<!--<div class="overlay-post-content">
								<div class="post-content">
									<h3 class="post-title md">
										<a href="#">20 More Crypto Adoption Cases Throughout the World</a>
									</h3>
								</div>
							</div>-->
						</div>
						<!-- end item-->
					</div>
					<?php } } ?>
					<!-- ts overly end-->
					
				</div>
			</div>
		</div>
		<!-- container end-->
	</section>
	<!-- block area end-->
	
	<section class="block-wrapper mt-15 xs-mb-30" style="margin-top: -50px;">
		<div class="container">
			<div class="row">
				<div class="col-lg-9">
					<div class="ts-grid-box clearfix ts-category-title">
						<h2 class="ts-title float-left">Join Us On Sunday 5th December</h2>
					</div>
					<div class="row post-col-list-item">
						<?php if(!empty($join_us)){ foreach($join_us as $jus){ ?>
						<div class="col-lg-6 mb-30">
							<div class="ts-grid-box ts-grid-content">
								<!--<a class="post-cat ts-green-bg" href="#">Title</a>-->
								<div class="ts-post-thumb">
									<a href="<?php echo site_url('event/detail'); ?>">
										<img class="img-fluid" src="<?php echo base_url('uploads/banner/'.$jus->image); ?>" alt="<?php echo $jus->title; ?>">
									</a>
								</div>
								<div class="post-content">
									<h3 class="post-title md">
										<a href="<?php echo site_url('event/detail'); ?>">
										    <?php echo $jus->title; ?>
										</a>
									</h3>
								</div>
							</div>
						</div>
						<?php } } ?>
					</div>
				</div>
				
				<div class="col-lg-3">
					<div class="right-sidebar">
						
						<!-- Ads 3 -->
						<div class="widgets widget-banner">
							<a href="#">
								<img src="<?php echo base_url('uploads/banner/jnl_bg-01.jpg'); ?>" alt="Ads 3">
							</a>
						</div>
						<!-- End of Ads 3 -->
						
					</div>
				</div>
			</div>
			<!-- row end-->
		</div>
		<!-- container end-->
	</section>
	<!-- Block wraper end-->
	
	<section class="block-wrapper mt-15 xs-mb-30">
		<div class="container">
			<div class="row">
				<div class="col-lg-9">
					<div class="ts-grid-box clearfix ts-category-title">
						<h2 class="ts-title float-left">Videos</h2>
					</div>
					<div class="row post-col-list-item">
                        <?php if(!empty($videos)){ foreach($videos as $vd){ ?>
						<div class="col-lg-6 mb-30">
							<div class="ts-grid-box ts-grid-content">
								<!--<a class="post-cat ts-green-bg" href="#">Title</a>-->
								<div class="ts-post-thumb">
									<iframe width="400" height="315" src="<?php echo $vd->url; ?>?autoplay=1&mute=1&loop=1&list=<?php echo $vd->playlist; ?>&rel=0" 
									title="<?php echo $vd->title; ?>" frameborder="0" 
									allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
									allowfullscreen></iframe>
								</div>
								<div class="post-content">
									<h3 class="post-title md">
										<a href="<?php echo site_url('event/detail'); ?>">
										    <?php echo $vd->title; ?>
										</a>
									</h3>
								</div>
							</div>
						</div>
						<?php } } ?>
					</div>
				</div>
				
				<div class="col-lg-3">
					<div class="right-sidebar">
						
						<!-- Ads 3 -->
						<div class="widgets widget-banner">
							<a href="#">
								<img src="<?php echo base_url('uploads/banner/jnl_bg-01.jpg'); ?>" alt="Ads 3">
							</a>
						</div>
						<!-- End of Ads 3 -->
						
					</div>
				</div>
			</div>
			<!-- row end-->
		</div>
		<!-- container end-->
	</section>
	<!-- Block wraper end-->
	
	<?php if(!empty($eat_laugh)){ ?>
	<section class="block-wrapper mb-30" id="more-news-section">
		<div class="container">
			<div class="ts-grid-box ts-grid-box-heighlight">
				<h2 class="ts-title">Eat-Laugh-Dance</h2>

				<div class="owl-carousel" id="more-news-slider">
				    <?php foreach($eat_laugh as $eat){ ?>
					<!-- ts-overlay-style end-->
					<div class="ts-overlay-style">
					    <!-- item -->
						<div class="item">
							<div class="ts-post-thumb">
								<a href="<?php echo site_url('event/detail'); ?>">
									<img class="img-fluid" src="<?php echo base_url('uploads/banner/'.$eat->image); ?>" alt="<?php echo $eat->title; ?>">
								</a>
							</div>
							<div class="overlay-post-content">
								<div class="post-content">
									<h3 class="post-title">
										<a href="<?php echo site_url('event/detail'); ?>">
										   <?php echo $eat->title; ?>
										</a>
									</h3>
								</div>
							</div>
						</div>
						<!-- end item-->
					 </div>
					<!-- ts-overlay-style end-->
					<?php } ?>
				</div>
				<!-- most-populers end-->
			</div>
			<!-- ts-populer-post-box end-->
		</div>
		<!-- container end-->
	</section>
	<?php } ?>
	<!-- post wraper end-->
	
	<section class="block-wrapper mt-15 xs-mb-30">
		<div class="container">
			<div class="row">
				<div class="col-lg-9">
					<div class="ts-grid-box clearfix ts-category-title">
						<h2 class="ts-title float-left">Performers</h2>
					</div>
					<div class="row post-col-list-item">
                        <?php if(!empty($performers)){ foreach($performers as $perf){ ?>
						<div class="col-lg-6 mb-30">
							<div class="ts-grid-box ts-grid-content">
								<!--<a class="post-cat ts-green-bg" href="#">Title</a>-->
								<div class="ts-post-thumb">
									<a href="<?php echo site_url('event/detail'); ?>">
										<img class="img-fluid" src="<?php echo base_url('uploads/banner/'.$perf->image); ?>" alt="<?php echo $perf->title; ?>">
									</a>
								</div>
								<div class="post-content">
									<h3 class="post-title md">
										<a href="<?php echo site_url('event/detail'); ?>">
										    <?php echo $perf->title; ?>
										</a>
									</h3>
								</div>
							</div>
						</div>
						<?php } } ?>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="right-sidebar">
						
						<!-- Ads 3 -->
						<div class="widgets widget-banner">
							<a href="#">
								<img src="<?php echo base_url('uploads/banner/jnl_bg-01.jpg'); ?>" alt="Ads 3">
							</a>
						</div>
						<!-- End of Ads 3 -->
						
					</div>
				</div>
			</div>
			<!-- row end-->
		</div>
		<!-- container end-->
	</section>
	<!-- Block wraper end-->
	
	<?php include 'menu/footer.php'; ?>

	<!-- javaScript Files
	=============================================================================-->

	<!-- initialize jQuery Library -->
	<script src="<?php echo base_url('js/jquery.min.js'); ?>"></script>
	<!-- navigation JS -->
	<script src="<?php echo base_url('js/navigation.js'); ?>"></script>
	<!-- Popper JS -->
	<script src="<?php echo base_url('js/popper.min.js'); ?>"></script>

	<!-- magnific popup JS -->
	<script src="<?php echo base_url('js/jquery.magnific-popup.min.js'); ?>"></script>

	<!-- Bootstrap jQuery -->
	<script src="<?php echo base_url('js/bootstrap.min.js'); ?>"></script>
	<!-- Owl Carousel -->
	<script src="<?php echo base_url('js/owl-carousel.2.3.0.min.js'); ?>"></script>
	<!-- slick -->
	<script src="<?php echo base_url('js/slick.min.js'); ?>"></script>

	<!-- smooth scroling -->
	<script src="<?php echo base_url('js/smoothscroll.js'); ?>"></script>

	<script src="<?php echo base_url('js/home.js'); ?>"></script>
</body>

</html>