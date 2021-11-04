<?php
	// flag this as a parent file
	define( '_FEXEC', 1 );
	require('includes/application_top.php');
	if(SEATPLAN_LOGGING=='true'){
		define('HEADING_TITLE', 'Seatplan Channels (enabled)');
	}
	else {
		define('HEADING_TITLE', 'Seatplan Channels (disabled)');
	}
	
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
		<link rel="stylesheet" type="text/css" href="includes/seatplan_channels.css">
		<script src="includes/javascript/jquery-1.7.1.min.js"></script>
		<script src="includes/javascript/jquery-ui-1.8.13.custom.min.js"></script>
		<?php
		/* load JS on demand */
		if(SEATPLAN_LOGGING=='true'){?>
		<script src="includes/javascript/seatplan_channels.js"></script>
		<?php
			}
		require(DIR_WS_INCLUDES . 'header.php'); ?>
		<?php
			if(SEATPLAN_LOGGING=='true'){?>
				<div id="wrap">
					<div id="tabs">
						<ul id="seatplans">
							<?php
								/* looping the tab buttons */
								$con->tep_listItems('seatplan');
							?>
						</ul>
						<ul id="channels">
							<?php
								/* looping the channel tabs */
								$con->tep_listItems('channel');
							?>
						</ul>
					</div>
				</div>
		<?php
			}
			else {?>
				<div id="spc_notice" class="ui-widget ui-widget-content ui-corner-all" style="height:120px;padding:10px;width:700px;">
				<span class="ui-icon ui-icon-info" style="float:left;margin-top:1px;margin-right:8px;"></span>
					You can enable <b>Seatplan Live Logging</b> for seatplans in the <a style="text-decoration: underline;" href="configuration.php?top=1&mPath=400_417">Seat Plan Settings</a>.
					<br><br>
					However it is not really necessary, it is NOT a feature but a tool for testing and logging the LIVE seat plan selection process.
					<br>For experienced users only interested in scrutinizing the seat plan technology.
				</div>
		<?php
			}
		?>
		<br><br><br><br><br><br><br><br><br><br>
		<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
	</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>