<?php 

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
?>
<!-- ======= Top Bar ======= -->
  <section id="topbar" class="d-flex align-items-center">
    <div class="container d-flex justify-content-center justify-content-md-between">
      <div class="contact-info d-flex align-items-center">
        <i class="bi bi-envelope d-flex align-items-center"><a href="mailto:<?php echo STORE_OWNER_EMAIL_ADDRESS; ?>"><?php echo STORE_OWNER_EMAIL_ADDRESS;
	?></a></i>
        <i class="bi bi-phone d-flex align-items-center ms-4"><span><?php if (STORE_OWNER_TELEPHONE!=''){ ?><?php echo STORE_OWNER_TELEPHONE;} ?></span></i>
      <?php 
			if (SHOW_LANGUAGES_IN_HEADER == 'yes') 
			{
			//language selector
				if (!is_object($lng)) 
			{
				include(DIR_WS_CLASSES . 'language.php');
				$lng = new language;
			}
			
			if (getenv('HTTPS') == 'on') $connection = 'SSL';
			else $connection = 'NONSSL';
			
			$languages_string = '';
			$languages_active_string = '';
			$languages_grid = '';
			reset($lng->catalog_languages);
			foreach($lng->catalog_languages as $key => $value)
			{
				$languages_string .= '
				&nbsp;&nbsp;&nbsp;&nbsp;
				<span  class="badge rounded-pill bg-dark"><a 
				data-toggle="tooltip" 
				data-placement="bottom" 
				title="'. $value['name'] .'" 
				href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('language', 'currency')) . 'language=' . $key, $connection) . '">
				<i class="flag-icon flag-icon-' . $value['code'] . '"></i>
				</a></span>
				';
			}
			echo $languages_string;
			}
			?>

	  </div>
      <div class="social-links d-none d-md-flex align-items-center">
		<?php if (TWITTER_ID!=''){ ?>
		<a target="_blank" href="https://twitter.com/<?php echo TWITTER_ID; ?>">&nbsp;&nbsp;<i class="bi bi-twitter"></i></a>
		<?php } ?>
		<?php if (FACEBOOK_ID!=''){ ?>
		<a target="_blank" href="https://www.facebook.com/<?php echo FACEBOOK_ID; ?>">&nbsp;&nbsp;<i class="bi bi-facebook"></i></a>
		<?php } ?>

		<?php if (INSTAGRAM_ID!=''){ ?>
		<a target="_blank" href="https://instagram.com/<?php echo INSTAGRAM_ID; ?>">&nbsp;&nbsp;<i class="bi bi-instagram"></i></a>
		<?php } ?>
		<?php if (LINKEDIN_ID!=''){ ?>
		<a target="_blank" href="https://ph.linkedin.com/in/<?php echo LINKEDIN_ID; ?>">&nbsp;&nbsp;<i class="bi bi-linkedin"></i></a>
		<?php } ?>

      </div>
    </div>
  </section><!-- End Top Bar-->