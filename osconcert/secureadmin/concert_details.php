<?php
	// flag this as a parent file
	define( '_FEXEC', 1 );
	require('includes/application_top.php');
	define('HEADING_TITLE', 'CONCERT_DETAILS');
	
	/* get a handle */
	require(DIR_WS_CLASSES.'concert.php');	
	$con = new concert;
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
		<title><?php echo TITLE; ?></title>
		<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
		<link rel="stylesheet" type="text/css" href="includes/javascript/ui_themes/cupertino/jquery-ui-1.8.13.custom.css">
		<link rel="stylesheet" type="text/css" href="includes/javascript/jqGrid/css/ui.jqgrid.css">
		<!--[if IE]><style type="text/css" media="all">@import "includes/javascript/jqGrid/css/ui.jqgrid_ie.css";</style><![endif]-->
		<script src="includes/javascript/jquery-1.7.1.min.js"></script>
		<script src="includes/javascript/jquery-ui-1.8.13.custom.min.js"></script>
		<script src="includes/javascript/jqGrid/js/i18n/grid.locale-en.js"></script>
		<script src="includes/javascript/jqGrid/js/jquery.jqGrid.src.js"></script>
		<script type="text/javascript">$.jgrid.no_legacy_api = true;$.jgrid.useJSON = true;</script>
        <script src="includes/javascript/jqGrid.js"></script>

		<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
		<?php
		if(SHOW_OSCONCERT_HELP=='yes')
		{
		?>
		<div class="osconcert_message"><?php echo TEXT_IMPORTANT; ?></div>
		<?php
		}
		?>
		<?php
		if(SHOW_ADMIN_DATATOOLS=='yes')
		{
		?>
        <div>
		<button><a href="design_data.php"><h3>Design Mode Data</h3></a></button>
		<button><a href="categories_data.php"><h3>Categories Data</h3></a></button>
		<button><a href="products_data.php"><h3>Products Data</h3></a></button>
		<button><a href="products_only_data.php"><h3>Products TABLE Data</h3></a></button>
		<button><a href="orders_only_data.php"><h3>Orders TABLE Data</h3></a></button>
		</div><br>
		<?php
		}
		
		?>
		<?php
			/* display a language selector on demand */
			if($con->tep_isMultilanguage())
			{
				$arr = $con->tep_getLanguages();
				echo '<select id="language" style="width:90px;margin-top:10px;margin-left:20px;">';
				foreach($arr as $lang){echo '<option id="'.$lang['id'].'">'.$lang['name'].'</option>';}
				echo '</select>';
			}
		?>
		<?php if(AUTOFILL_DATEID=='yes'){
			echo '<div style="text-align:center;font-family:Arial"><b>' . TEXT_DATE_REFRESH.  '</b></div>';
		}
		?>
		<table id="concert_grid"></table>
		<div id="concert_pager"></div>
		<?php //include(DIR_WS_INCLUDES . 'meals.php'); ?>
		<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
	</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>