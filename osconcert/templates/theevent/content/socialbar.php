<?php 

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
?>
<!-- START SOCIAL MEDIA WIDGET -->
<section id="topbar" class="d-none d-lg-block">
<div class="container clearfix">
  <div class="contact-info float-left">
	<i class="fa fa-envelope-o"></i><a href="mailto:<?php echo STORE_OWNER_EMAIL_ADDRESS; ?>"><?php echo STORE_OWNER_EMAIL_ADDRESS; ?></a>
	<i class="fa fa-phone"></i> <?php if (STORE_OWNER_TELEPHONE!=''){echo STORE_OWNER_TELEPHONE;} ?>
  </div>
  <div class="social-links float-right">
	<?php if (TWITTER_ID!=''){ ?>
	<a target="_blank" href="https://twitter.com/<?php echo TWITTER_ID; ?>"><i class="fa fa-twitter"></i></a>
	<?php } ?>
	<?php if (FACEBOOK_ID!=''){ ?>
	<a target="_blank" href="https://www.facebook.com/<?php echo FACEBOOK_ID; ?>"><i class="fa fa-facebook"></i></a>
	<?php } ?>
	<?php if (INSTAGRAM_ID!=''){ ?>
	<a href="#" class="instagram"><i class="fa fa-instagram"></i></a>
	<?php } ?>
	<?php if (GOOGLEPLUS_ID!=''){ ?>
	<a target="_blank" href="https://plus.google.com/u/0/<?php echo GOOGLEPLUS_ID; ?>"><i class="fa fa-google-plus"></i></a>
	<?php } ?>
	<?php if (LINKEDIN_ID!=''){ ?>
	<a target="_blank" href="https://ph.linkedin.com/in/<?php echo LINKEDIN_ID; ?>"><i class="fa fa-linkedin"></i></a>
	<?php } ?>
  </div>
</div>
</section>                                                        
<!-- END SOCIAL MEDIA WIDGET -->