<!doctype html>
<html lang="en">

<head>
	<!-- Basic Page Needs =====================================-->
	<meta charset="utf-8">

	<!-- Mobile Specific Metas ================================-->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Site Title- -->
	<title>Create an Account || Ticket Event</title>

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

	<!-- navigation -->
	<link rel="stylesheet" href="<?php echo base_url('css/navigation.css'); ?>">

	<!-- magnific popup -->
	<link rel="stylesheet" href="<?php echo base_url('css/magnific-popup.css'); ?>">

	<!-- Style -->
	<link rel="stylesheet" href="<?php echo base_url('css/style.css'); ?>">

	<!-- Responsive -->
	<link rel="stylesheet" href="<?php echo base_url('css/responsive.css'); ?>">


	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	
	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-KN3SJZ3');</script>
	<!-- End Google Tag Manager -->

</head>

<body class="body-color">
	<?php include 'menu/nav.php'; ?>
	
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KN3SJZ3"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	
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

	<!-- post wraper start-->
	<section class="block-wrapper">
		<div class="container">
			<div class="row">
				<div class="col-lg-5 mx-auto">
					<div class="ts-grid-box">
						<div class="login-page">
							<h3 class="log-sign-title text-center mb-25">Please <a href="<?php echo site_url('login'); ?>">Log In</a></h3>
							
							<form action="<?php echo base_url('register'); ?>" method="POST" role="form">
								<div class="form-group">
								    <label for="inputUsernameEmail">Email Address</label>
								    <input type="email" name="email" class="form-control" id="inputUsernameEmail">
								</div>
								
								<div class="form-group">
								    <label for="inputPassword">Password</label>
								    <input type="password" class="form-control" id="inputPassword">
								</div>
								
								<div class="checkbox pull-right">
								    <label><a href="<?php echo site_url('login'); ?>">Already have an account?</a></label>
								</div>
								<button type="submit" name="register" class="btn btn btn-primary">Register</button>
							</form>
							<?php
                            	echo $this->session->flashdata('msg');
                            	echo $this->session->flashdata('msgError');
                            ?>
						</div>
					</div><!-- grid box end -->
				</div>
				<!-- col end-->

			</div>
			<!-- row end-->
		</div>
		<!-- container end-->
	</section>
	<!-- post wraper end-->

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

	<script src="<?php echo base_url('js/main.js'); ?>"></script>
</body>

</html>
