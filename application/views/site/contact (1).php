<!doctype html>
<html lang="en">

<head>
	<!-- Basic Page Needs =====================================-->
	<meta charset="utf-8">

	<!-- Mobile Specific Metas ================================-->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Site Title- -->
	<title>Contact Us || Jollof N Laugh</title>

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

	<!-- post wraper start-->
	<section class="block-wrapper mt-15">
		<div class="container">
			<div class="row">
				<div class="col-lg-9">
					<div class="contact-box ts-grid-box">
						<h3>Contact Us</h3>
							<p>Would you like to advertise your brand or product on our website? Or would you like your brand to feature on our live recorded shows? We work with a range of influencers 
							and partners. Please get in touch and we help work out the right solution for you  </p>

							<div class="widget contact-info">

								<div class="contact-info-box">
									<div class="contact-info-box-content">
										<h4>Mail Us</h4>
										<p>bookings@jollofnlaugh.com</p>
									</div>
								</div>

								<div class="contact-info-box">
									<div class="contact-info-box-content">
										<h4>Call Us</h4>
										<p>UK = +447444265568</p>
									</div>
								</div>

							</div><!-- Widget end -->

							<h3>Contact Form</h3>
							<form id="contact-form" action="<?php echo base_url('contact'); ?>" method="post" role="form">
								<div class="error-container"></div>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label>Full Name</label>
										<input class="form-control form-control-name" name="fullname" placeholder="" type="text" required>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>Email</label>
											<input class="form-control form-control-email" name="email" placeholder="" type="email" required>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>Subject</label>
											<input class="form-control form-control-subject" name="subject" placeholder="" required>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label>Message</label>
									<textarea class="form-control form-control-message" name="message" placeholder="" rows="10" required></textarea>
								</div>
								<div class="g-recaptcha" data-sitekey="<?php echo $this->config->item('google_key') ?>"></div>
								<div class="text-right"><br>
									<button class="btn btn-primary solid blank" type="submit" name="submit">Send Message</button> 
								</div>
							</form>
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
    <script src='https://www.google.com/recaptcha/api.js'></script>
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