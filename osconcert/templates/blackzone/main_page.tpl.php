<?php
/*
osConcert Visual Seat Reservation Copyright (c) 2009-2021 https://www.osconcert.com
Bootstrap v5 2021
*/
defined('_FEXEC') or die();
define('DIR_WS_TEMPLATE_IMAGES', 'templates/blackzone/images/');
$template_file_name=$content;$con_page = $content;
require(DIR_WS_INCLUDES.'meta_tags.php');
require(DIR_WS_CLASSES.'seatplan.php');
$sp = new seatplan; 
require_once(DIR_WS_CLASSES.'ajax_cart.php');
$ajaxCart = new ajaxCart;
$template_query = tep_db_query("select include_column_left,include_column_right from " . TABLE_TEMPLATE . "  WHERE template_name='newzone'");
while ($template_values = tep_db_fetch_array($template_query)) 
	{
		if (($template_values['include_column_left']=='yes')or($template_values['include_column_right']=='yes'))
		{
			$has_column='left_column/';
		}
	}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php echo HTML_PARAMS; ?>>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
	<meta name="robots" content="nofollow">
	<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER).DIR_WS_CATALOG;?>">
	<title><?php echo STORE_NAME; ?> | <?php echo META_TAG_TITLE; ?></title>
	<meta name="description" content="<?php echo META_TAG_DESCRIPTION;?>">
	<meta name="keywords" content="<?php echo META_TAG_KEYWORDS;?>">
	<meta property="fb:app_id" content="<?php echo FB_APP_ID; ?>">
	<meta property="og:site_name" content="<?php echo FB_SITE_NAME; ?>">
	<meta property="og:url" content="<?php echo FB_URL; ?>">
	<meta property="og:type" content="<?php echo FB_TYPE; ?>">
	<meta property="og:locale" content="<?php echo FB_LOCALE; ?>">
	<meta property="og:title" content="<?php echo FB_TITLE; ?>">
	<meta property="og:description" content="<?php echo FB_DESCRIPTION; ?>">
	<meta property="og:image" content="<?php echo FB_IMAGE; ?>">
	<meta name="viewport" content="width=device-width">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<!-- Favicons -->
	<link rel="icon" type="image/ico" href="favicon.ico">
	<link rel="apple-touch-icon" sizes="57x57" href="apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
	<link rel="manifest" href="manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">
	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Raleway:300,400,500,700,800|Montserrat:300,400,700" rel="stylesheet">
	<!-- Libraries CSS Files -->
	<link href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/assets/vendor/aos/aos.css" rel="stylesheet">
	<link href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet">
	<!--<link href="<?php //echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">-->
	<link href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
	<link href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
	<link href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
	<link href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/assets/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<link href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/assets/css/flag-icon.css" rel="stylesheet">
	<!-- osConcert CSS Files -->
	<link href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/assets/css/osconcert.css" rel="stylesheet">  
	<link href="<?php 
	$css='seatplan'.$manufacturers_id; //manufacturers_id is Design ID ==5
	//$css="seatplan_lc"; 
	echo DIR_WS_TEMPLATES . TEMPLATE_NAME.'/assets/css/'.$css.'.css';?>" rel="stylesheet">
	<link href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/assets/css/jquery-ui.css" rel="stylesheet">
	<!-- Template Stylesheet Files -->
	<link href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/assets/css/style.css" rel="stylesheet">
	<link href="<?php echo DIR_WS_TEMPLATES.TEMPLATE_NAME.'/assets/css/themes/'.TEMPLATE_COLOR.'.css';?>" rel="stylesheet">
	<?php if(!defined('POPUP_DISCOUNT_WIDTH'))define('POPUP_DISCOUNT_WIDTH', '600');
	if(!defined('POPUP_DISCOUNT_HEIGHT'))define('POPUP_DISCOUNT_HEIGHT', '150');?>
	<style>
	div#ticket_discount{width: <?php echo POPUP_DISCOUNT_WIDTH;?>px!important;height: <?php echo POPUP_DISCOUNT_HEIGHT;?>px!important;}</style>
	<?php 
	define('LOG_PAGE','<a href="%s"><span>%s</span></a>');
	if($javascript) require(DIR_WS_JAVASCRIPT.$javascript); if(($FSESSION->is_registered('customer_first_name') && $FSESSION->is_registered('customer_id'))){ $user = tep_output_string_protected($FSESSION->customer_first_name); $url = tep_href_link(FILENAME_ACCOUNT,'','SSL'); define('USER', '<a href="%1$s">%2$s</a>'); $clink = sprintf(USER,$url,$user);define('USERLINK',$clink); $account = sprintf(WELCOME_CUSTOMER,tep_href_link(FILENAME_ACCOUNT, '', 'SSL')); $greet = sprintf(TEXT_GREETING_PERSONAL,USERLINK,'',''); $log_page = sprintf(LOG_PAGE,tep_href_link(FILENAME_LOGOFF),TEXT_LOGOFF);}else{ $greet = sprintf(WELCOME_GUEST, tep_href_link(FILENAME_LOGIN, '', 'SSL')); $log_page = sprintf(LOG_PAGE,tep_href_link(FILENAME_LOGIN.'','','SSL'),TEXT_LOGIN);} ?>
</head>
<body id="body">
  <!--==========================
    Header
  ============================-->
	<?php require(DIR_WS_INCLUDES . 'warnings.php'); ?>	
	<?php require(DIR_FS_CATALOG.DIR_WS_TEMPLATES.TEMPLATE_NAME. '/header.php'); ?>
	<?php 
	//<!-- errors -->
	if ($FREQUEST->getvalue('error_message')) 
	{ 
	?>
	<div class="alert-warning" style="text-align:center"></div>
	<?php 
	} 
	//<!-- end errors -->
	?>
<main id="main">
<div class="container clear">
	<?php 
	if ((substr(basename($PHP_SELF), 0, 5) != 'index') or ($cPath>0))
	{ 
		if (SHOW_BREADCRUMB == 'yes')
		{
			require_once(DIR_WS_TEMPLATES.TEMPLATE_NAME.'/content/breadcrumb.php');
		} 	
	}
	?>
	<div class="row">
		<?php 
		if (DISPLAY_COLUMN_LEFT == 'yes') 
		{
		if (DOWN_FOR_MAINTENANCE =='false' || DOWN_FOR_MAINTENANCE_COLUMN_LEFT_OFF =='false') 
		{ 
		?>
		<div class="col-md-3 order-md-first order-last">
		<?php require_once(DIR_WS_INCLUDES.'column_left.php'); ?>
		</div><!-- end: sidebar-left -->
		<?php
		}
		?>
		<div id="content" class="col-md">	
		<?php 
		}elseif (DISPLAY_COLUMN_RIGHT == 'yes') 
		{
		?>
		<div class="col-md-3 order-12 order-md-12 col-a">
		<?php require_once(DIR_WS_INCLUDES.'column_left.php'); ?>
		</div><!-- end: sidebar-left -->
		<div id="content" class="col-md-9 order-1 order-md-1 col-b">
		<?php
		}elseif ((DISPLAY_COLUMN_LEFT == 'no') && (DISPLAY_COLUMN_RIGHT == 'no'))
		{
		?>
		<div class="col-md-12">
		<?php
		}
		if ($FREQUEST->getvalue('error_message')) 
		{ 
		?>
		<div class="headerError"><?php echo htmlspecialchars(urldecode($FREQUEST->getvalue('error_message')));?></div>
		<?php 
		}

		if (isset($content_template)) 
		{ 
		require(DIR_WS_CONTENT . $content_template);
		}
		else if (isset($GLOBALS["TPL_CONTENT"]))
		{
		echo $GLOBALS["TPL_CONTENT"];
		}
		///////////////////////////////////////////////////////////////////
		else
		{
		if (file_exists(DIR_FS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME . "/content/" . $content . '.tpl.php'))
		{
		require(DIR_FS_CATALOG.DIR_WS_TEMPLATES.TEMPLATE_NAME."/content/".$content.'.tpl.php');
		}
		else
		{
		require(DIR_WS_CONTENT.$content.'.tpl.php');
		//require_once(DIR_WS_MODULES .'double_boxes.php');
		}
		}
		///////////////////////////////////////////////////////////////////
		?>
		</div>
		</div>
	</div>
</main>
<?php require(DIR_FS_CATALOG.DIR_WS_TEMPLATES.TEMPLATE_NAME. '/footer.php'); ?>  
	<!-- START Bootstrap-Cookie-Alert -->
	<div class="alert text-center cookiealert" role="alert">
		<?php echo TEXT_COOKIES ?>
		<button type="button" class="btn btn-primary btn-sm acceptcookies" aria-label="<?php echo TEXT_CLOSE ?>">
		<?php echo TEXT_UNDERSTAND ?>
		</button>
	</div>
	<!-- END Bootstrap-Cookie-Alert -->
	<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
	 
	<!-- Vendor JS Files -->
	<script src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/assets/vendor/jquery/jquery.min.js"></script>
	<script src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/assets/vendor/aos/aos.js"></script>
	<script src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/assets/vendor/jquery_ui/jquery-ui.min.js"></script>
	<script src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/assets/vendor/glightbox/js/glightbox.min.js"></script>
	<script src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
	<script src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/assets/vendor/swiper/swiper-bundle.min.js"></script>
	<script src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/assets/vendor/waypoints/noframework.waypoints.js"></script>
	<script defer src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/assets/js/cookiealert.min.js"></script>
	<script defer src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/assets/js/easytooltip.min.js"></script>
	<script src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/assets/js/main.js"></script>
	<?php ################# server load
	if(SEATPLAN_LOGIN_ENFORCED=='true' && !$FSESSION->is_registered('customer_id'))
	{
	?>
	<?php if(file_exists(DIR_WS_TEMPLATES.'login-modal.php')) require(DIR_WS_TEMPLATES.'login-modal.php');?>
	<script>
	$(document).ready(function(){
		$(".seatplan").click(function(){
			$("html, body").animate({ scrollTop: 0 }, "slow");
			$("#LoginModal").modal("show");
		});
	});
	</script>
	<?php 
	
	///// remove this section to display a default seatplan
	$js.='<script>$.cPath='.(($cPath)? $cPath:0).';$.cht="'.$sp->cat_name($cPath).'";$.refresh=1000000;</script>'."\n";
	$sym_left = $currencies->currencies[$currency=$FSESSION->currency]['symbol_left'];$sym_right = $currencies->currencies[$currency=$FSESSION->currency]['symbol_right'];$dec_point = $currencies->currencies[$currency=$FSESSION->currency]['decimal_point'];$thou_point = $currencies->currencies[$currency=$FSESSION->currency]['thousands_point'];$js.='<script>var lng={"expiry":"'.SP_EXPIRY.'","expired":"'.SP_EXPIRED.'","cleared":"'.SP_CLEARED.'","tooslow":"'.SP_TOOSLOW.'","toomany":"'.SP_TOOMANY.'","discount":"'.SP_DISCOUNT.'","seat":"'.ITEM.'","seats":"'.ITEMS.'", "thou_point":"'.$thou_point.'", "dec_point":"'.$dec_point.'","sym_left":"'.$sym_left.'","sym_right":"'.$sym_right.'"};</script>'."\n";
	if($FSESSION->is_registered('customer_id')){$spt='user.min';}else{if(SEATPLAN_LOGIN_ENFORCED=='true'){$spt='public';}else{$spt='user.min';}}
	$js.='<script src="'.DIR_WS_TEMPLATES.TEMPLATE_NAME.'/assets/js/seatplan_'.$spt.'.js"></script>'."\n";
	echo $js;///// end of section to remove
	}else{
	$js='';/* checking for the age of the cart in order to initialize the timeout */ if($_SESSION['customer_id']) { $cart_age = $sp->tep_getCartAge($_SESSION['customer_id']); }else { $cart_age = $sp->tep_getTempCartAge(); }/* check if the cart isn't already expired yet */ if($cart_age > (int)SEATPLAN_TIMEOUT) { if($_SESSION['customer_id']){ $sp->tep_clearCart($_SESSION['customer_id'],$cPath); }else{/* if not logged in */ $sp->tep_clearTempCart($cPath); } }else {/* pass the remaining seconds as a jQuery variable */ if($cart_age != -1){ $remaining = ((int)SEATPLAN_TIMEOUT)-$cart_age; $timeout = 'true';}else{ $remaining=(int)SEATPLAN_TIMEOUT; $timeout = 'false';} if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) { $baseurl = HTTPS_SERVER.DIR_WS_HTTPS_CATALOG; }else{$baseurl = HTTP_SERVER.DIR_WS_HTTP_CATALOG;} $js.='<script>$.cPath='.(($cPath)? $cPath:0).';$.cht="'.$sp->cat_name($cPath).'";$.refresh='.SEATPLAN_REFRESH.';$.lifetime='.SEATPLAN_TIMEOUT.';$.remaining='.$remaining.';$.timeout='.$timeout.';$.baseurl="'.$baseurl.'";$.thank="'.SP_NOTHANKYOU.'";</script>'."\n"; } $sym_left = $currencies->currencies[$currency=$FSESSION->currency]['symbol_left']; $sym_right = $currencies->currencies[$currency=$FSESSION->currency]['symbol_right']; $dec_point = $currencies->currencies[$currency=$FSESSION->currency]['decimal_point']; $thou_point = $currencies->currencies[$currency=$FSESSION->currency]['thousands_point']; $js.='<script>var lng={"expiry":"'.SP_EXPIRY.'","expired":"'.SP_EXPIRED.'","cleared":"'.SP_CLEARED.'","tooslow":"'.SP_TOOSLOW.'","toomany":"'.SP_TOOMANY.'","discount":"'.SP_DISCOUNT.'","seat":"'.ITEM.'","seats":"'.ITEMS.'","thou_point":"'.$thou_point.'","dec_point":"'.$dec_point.'","sym_left":"'.$sym_left.'","sym_right":"'.$sym_right.'"};</script>'."\n"; if($FSESSION->is_registered('customer_id')){$spt='user.min';}else{if(SEATPLAN_LOGIN_ENFORCED=='true'){$spt='public';}else{$spt='user.min';}} if($_SESSION['customer_country_id']==999 && isset($_SESSION['box_office_refund'] )){$spt='refund';} $js.='<script src="'.DIR_WS_TEMPLATES.TEMPLATE_NAME.'/assets/js/seatplan_'.$spt.'.js"></script>'."\n"; echo $js;}
	?>

	<?php if(GOOGLE_ANALYTICS != ''){?><!-- start: Google Analytics -->
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
	  ga('create', '<?php echo GOOGLE_ANALYTICS; ?>', 'auto');
	  ga('send', 'pageview');
	</script><!-- end: Google Analytics --><?php }?>
	<?php if(file_exists(DIR_WS_TEMPLATES.'discount-modal.php')) require(DIR_WS_TEMPLATES.'discount-modal.php');?>