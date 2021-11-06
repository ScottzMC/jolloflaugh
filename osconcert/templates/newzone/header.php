<?php 

defined('_FEXEC') or die();
$arrows = "&raquo;";

if (DOWN_FOR_MAINTENANCE == 'true')
	{
	$maintenance_on_at_time_raw = tep_db_query("select last_modified from " . TABLE_CONFIGURATION . " WHERE configuration_key = 'DOWN_FOR_MAINTENANCE'");
	$maintenance_on_at_time = tep_db_fetch_array($maintenance_on_at_time_raw);
	define('TEXT_DATE_TIME', $maintenance_on_at_time['last_modified']);
	}
	
	if(DOWN_FOR_MAINTENANCE_HEADER_OFF == 'false')
	{
		if (SHOW_TOP_BAR == 'yes') 
		{
		require_once(DIR_WS_TEMPLATES.TEMPLATE_NAME.'/content/topbar.php');
		//require_once(DIR_WS_TEMPLATES.TEMPLATE_NAME.'/content/header.php');
		} 
		if ((substr(basename($PHP_SELF), 0, 5) != 'index') or ($cPath>0) or ($stcPath>0))
		{ 
		?>
	<header id="header" class="d-flex align-items-center" style="height:<?php 
	echo HEADER_HEIGHT; ?>px">
	<div class="container d-flex justify-content-between align-items-center">
	<div id="logo" class="flexy">
	<?php if(TEXT_LOGO=='yes'){	?>
	<h1><a href="index.php"><?php echo TEXT_LOGO1; ?><span><?php echo TEXT_LOGO2; ?></span></a></h1>
	<?php //use an image
	}else{ $url = '<a href="index.php">' . tep_image(DIR_WS_TEMPLATES . DEFAULT_TEMPLATE . '/' . DIR_WS_IMAGES . COMPANY_LOGO, STORE_NAME, '', '') . '</a>';
	echo $url;} ?></div>
	<nav id="navbar" class="navbar">
	<ul>
	<?php
	if (SHOW_MAIN_FEATURED_CATEGORIES == 'true')
	{
	echo '<li><a href="' . tep_href_link(FILENAME_FEATURED_CATEGORIES) . '">' . HEADING_FEATURED_CATEGORIES . '</a></li>' . "\n";
	} 
	if (HIDE_SEARCH_EVENTS == 'no')
	{
	echo '<li><a href="' . tep_href_link(FILENAME_SEARCH_EVENTS) . '">' . HEADER_TITLE_SEARCH . '</a></li>' . "\n";
	} 
	$looking_for = 'bor.php'; // is Box Office Reservations installed?

	if (defined('MODULE_PAYMENT_INSTALLED') && tep_not_null(MODULE_PAYMENT_INSTALLED) && $_SESSION['customer_country_id']==999) 
	{
		$modules_installed = explode(';', MODULE_PAYMENT_INSTALLED);
		if (in_array($looking_for, $modules_installed))
		{
		echo "<li><a href=\"" . tep_href_link('bor_listings.php') . "\">" . HEADER_VIEW_BOR . '</a></li>' . "\n";
		}
	}
	echo "<li><a href=\"" . tep_href_link(FILENAME_SHOPPING_CART) . "\">" . HEADER_TITLE_CART_CONTENTS . '</a></li>' . "\n"; 
	echo "<li class=\"menu-active\"><a href=\"" . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . "\">" . HEADER_TITLE_CHECKOUT . '</a></li>' . "\n"; ?>
	<?php
	if (!isset($_COOKIE['customer_is_guest']))
	{ //PWA 
	echo '<li>'.$log_page.'</li>' . "\n"; ?>
	<?php
	} 
	?>
	<!-- header shopping cart -->
	<?php //let's find out if there is a left column shopping cart?	
	if (SHOW_CART_IN_HEADER == 'yes') 
	{
		if (!isset($_SESSION['box_office_refund'])) 
		{
			$shopping_cart_query = tep_db_query('select infobox_display, infobox_file_name from ' . TABLE_INFOBOX_CONFIGURATION . ' where template_id = ' . tep_db_input(TEMPLATE_ID));

				while ($shopping_cart = tep_db_fetch_array($shopping_cart_query)) 
				{
					if (($shopping_cart['infobox_display'] == 'no')&&($shopping_cart['infobox_file_name'] == 'shopping_cart.php')) 
					{
					require_once (DIR_WS_TEMPLATES.TEMPLATE_NAME.'/shopping_cart_new.php');
					
					$top="_top";
					}
				}
		}
	}
	?>
	</ul><i class="bi bi-list mobile-nav-toggle"></i>
	</nav><!-- .navbar -->
    </div>
    </header><!-- End Header -->
	<br class="clearfloat">
	<?php 
	}else{
	?>
	<!-- ======= Header ======= -->
    <header id="header" class="d-flex align-items-center" style="height:<?php 
	echo HEADER_HEIGHT; ?>px">
    <div class="container-fluid container-md d-flex align-items-center">

    <div id="logo" class="me-auto flexy">
	<?php if(TEXT_LOGO=='yes'){	?>
	<h1><a href="index.php"><?php echo TEXT_LOGO1; ?><span><?php echo TEXT_LOGO2; ?></span></a></h1>
	<?php //use an image
	}else{ $url = '<a href="#body">' . tep_image(DIR_WS_TEMPLATES . DEFAULT_TEMPLATE . '/' . DIR_WS_IMAGES . COMPANY_LOGO, STORE_NAME, '', '') . '</a>';
	echo $url;} ?>
	</div>
	<nav id="navbar" class="navbar">
	<ul>
	<?php
	if (SHOW_MAIN_FEATURED_CATEGORIES == 'true')
	{
	echo '<li><a href="' . tep_href_link(FILENAME_FEATURED_CATEGORIES) . '">' . HEADING_FEATURED_CATEGORIES . '</a></li>' . "\n";
	} 
	if (HIDE_SEARCH_EVENTS == 'no')
	{
	echo '<li><a href="' . tep_href_link(FILENAME_SEARCH_EVENTS) . '">' . HEADER_TITLE_SEARCH . '</a></li>' . "\n";
	} 
	?>
	<?php
	$looking_for = 'bor.php'; // is Box Office Reservations installed?
	
	if (defined('MODULE_PAYMENT_INSTALLED') && tep_not_null(MODULE_PAYMENT_INSTALLED) && $_SESSION['customer_country_id']==999) 
	{
	$modules_installed = explode(';', MODULE_PAYMENT_INSTALLED);
		if (in_array($looking_for, $modules_installed))
		{
		echo "<li><a href=\"" . tep_href_link('bor_listings.php') . "\">" . HEADER_VIEW_BOR . '</a></li>' . "\n";
		}
	}
	echo "<li><a href=\"" . tep_href_link(FILENAME_SHOPPING_CART) . "\">" . HEADER_TITLE_CART_CONTENTS . '</a></li>' . "\n"; ?>
	
	<?php
	echo "<li class=\"menu-active\"><a href=\"" . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . "\">" . HEADER_TITLE_CHECKOUT . '</a></li>' . "\n"; ?>
	<?php
	if (!isset($_COOKIE['customer_is_guest']))
	{ //PWA 
	?>
	<?php echo "<li>".$log_page."</li>"; 
	} 
	?>
	<!-- header shopping cart -->
	<?php //let's find out if there is a left column shopping cart?	
	if (SHOW_CART_IN_HEADER == 'yes') 
	{
		$shopping_cart_query = tep_db_query('select infobox_display, infobox_file_name from ' . TABLE_INFOBOX_CONFIGURATION . ' where template_id = ' . tep_db_input(TEMPLATE_ID));

		while ($shopping_cart = tep_db_fetch_array($shopping_cart_query)) 
		{
			if (($shopping_cart['infobox_display'] == 'no')&&($shopping_cart['infobox_file_name'] == 'shopping_cart.php')) 
			{
			require_once (DIR_WS_TEMPLATES.TEMPLATE_NAME.'/shopping_cart_new.php');
			}
		}
	}
	?>
	</ul>
	<i class="bi bi-list mobile-nav-toggle"></i>
	</nav><!-- #navbar-container -->
	</div><!-- .container -->
	</header><!-- #header -->
	<?php
		if (SHOW_HEADER_PANE == 'yes') 
		{
		require_once(DIR_WS_TEMPLATES.TEMPLATE_NAME.'/content/headerbar.php');
		//require_once(DIR_WS_TEMPLATES.TEMPLATE_NAME.'/content/header.php');
		} 
		else
		{
		echo '<br class="clearfloat">';
		//require_once(DIR_WS_TEMPLATES.TEMPLATE_NAME.'/content/header.php');
		}
	}
} 
?>