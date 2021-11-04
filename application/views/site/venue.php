<!doctype html>
<html lang="en">

<head>
	<!-- Basic Page Needs =====================================-->
	<meta charset="utf-8">

	<!-- Mobile Specific Metas ================================-->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Site Title- -->
	<?php foreach($venue as $ven){} ?>
	<title><?php echo $ven->title; ?> || Ticket Event</title>

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
	<link rel="stylesheet" href="<?php echo base_url('css/main.css'); ?>">
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
       .embed-youtube {
            position: relative;
            padding-bottom: 56.25%; /* - 16:9 aspect ratio (most common) */
            /* padding-bottom: 62.5%; - 16:10 aspect ratio */
            /* padding-bottom: 75%; - 4:3 aspect ratio */
            padding-top: 30px;
            height: 0;
            overflow: hidden;
        }
        
        .embed-youtube iframe,
        .embed-youtube object,
        .embed-youtube embed {
            border: 0;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>

	<!-- single post start -->
	<section class="single-post-wrapper post-layout-3">
		<div class="container">
			<div class="row">
				<div class="col-lg-9">
					<ol class="breadcrumb">
						<li>
							<a href="<?php echo site_url('home'); ?>">
								<i class="fa fa-home"></i>
								Home
							</a>
						</li>
						<li><?php echo $ven->title; ?></li>
					</ol>
					<!-- breadcump end-->
					<div class="ts-grid-box content-wrapper single-post">

						<!-- single post header end-->
						<div class="post-content-area">
							<div class="post-media post-featured-image">
								<a href="<?php echo base_url('uploads/venue/'.$ven->image1); ?>" class="gallery-popup">
									<img src="<?php echo base_url('uploads/venue/'.$ven->image1); ?>" class="img-fluid" alt="<?php echo $ven->title; ?>">
								</a>
							</div>
							<div class="entry-header">
							    <br>
								<h2 class="post-title lg"><?php echo $ven->title; ?></h2>
							</div>
							<div class="entry-content" style="color: #000;">
								<?php echo $ven->body; ?>
							</div>
							<div class="clearfix">
            					<a class="comments-btn btn btn-primary" href="https://scottnnaghor.com/jollof_n_laugh/osconcert/index.php?cPath=1">Book Event</a>
            				</div>
							<!-- entry content end-->
						</div>
						<!-- post content area-->
						
						<!-- post navigation end-->
					</div>
					<!--single post end -->
					
					<!-- Maps -->
					<div class="embed-youtube">
						<iframe src="<?php echo $ven->maps; ?>" 
						width="700" height="450" style="border:0;" 
						allowfullscreen="" loading="lazy"></iframe>
					</div>
					<br><br>
					<!-- End of Maps -->
					
					<div class="ts-grid-box content-wrapper single-post">

						<!-- single post header end-->
						<div class="post-content-area">
							<div class="post-media post-featured-image">
								<a href="<?php echo base_url('uploads/venue/'.$ven->image1); ?>" class="gallery-popup">
									<img src="<?php echo base_url('uploads/venue/'.$ven->image2); ?>" class="img-fluid" alt="<?php echo $ven->title; ?>">
								</a>
							</div>
						</div>
						<!-- post content area-->
						
						<!-- post navigation end-->
					</div>

					<div class="comments-form ts-grid-box">
						<div class="widgets ts-grid-box post-tab-list ts-col-box">
							<h3 class="widget-title">Book</h3>
							<!-- ts-overlay-style  end-->
                            <div class="clearfix">
								<a class="comments-btn btn btn-primary" href="https://scottnnaghor.com/jollof_n_laugh/osconcert/index.php?cPath=1">Book</a>
							</div>
						</div>
					</div>
					
				</div>
				<!-- col end -->
				<div class="col-lg-3">
					<div class="right-sidebar">
						<div class="widgets widget-banner">
							<a href="#">
								<img class="img-fluid" src="<?php echo base_url('uploads/events/bg-03.jpg'); ?>" alt="Venue">
							</a>
						</div>
						
					</div>
				</div>
				
				<!-- right sidebar end-->
				<!-- col end-->
			</div>
			<!-- row end-->
		</div>
		<!-- container-->
	</section>
	<!-- single post end-->

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

	<script src="<?php echo base_url('js/main.js'); ?>"></script>
</body>

</html>