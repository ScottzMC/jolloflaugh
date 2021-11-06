<?php
/* 
	osCommerce, Open Source E-Commerce Solutions 
	http://www.oscommerce.com 
	
	Copyright (c) 2003 osCommerce 
	
	 
	
	Freeway eCommerce 
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
	osConcert, Online Seat Booking 
  	https://www.osconcert.com

  	Copyright (c) 2021 osConcert 
	
	Released under the GNU General Public License 
*/ 
$current_step="install";
?>
	<!--install-->
	<div class="container" style="width:800px">
	<div class="row">
		<div class="col-md-12">
		<ul class="list-group list-group-horizontal justify-content-center">
					
					  <li class="list-group-item"><?php echo TEXT_CT; ?></li>
					  <li class="list-group-item"><strong><?php echo TEXT_IO; ?></strong></li>
					  <li class="list-group-item"><?php echo TEXT_DI; ?></li>
					  <li class="list-group-item"><?php echo TEXT_TC; ?></li>
					</ul> <br>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
		<div class="progress progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%">25%</div>
		</div><br>
		</div>

	<div class="row">
		<div class="col-md-12">
				<form name="install" action="install.php?step=2" method="post" class="form-group">
			<h3 class="text-center text-info"><?php echo TEXT_CUSTOM; ?></h3>					
					<div>
						<div>
							&nbsp;
						</div>
						<?php
						if (isset($HTTP_POST_VARS["compat_test_pass"]))
						{ 
						$DB_SERVER = $DB_SERVER_USERNAME = $DB_SERVER_PASSWORD = "";
						echo osc_draw_hidden_field('DB_SERVER',$DB_SERVER) .
						osc_draw_hidden_field('DB_SERVER_USERNAME',$DB_SERVER_USERNAME) .
						osc_draw_hidden_field('DB_SERVER_PASSWORD',$DB_SERVER_PASSWORD) .
						'<input type="hidden" name="compat_test_pass" value="1"/>';
						}
						?>			
						<div><?php echo TEXT_IMPORT; ?></div>
						<div>
							<?php echo osc_draw_checkbox_field('install[]', 'database', true); ?>
							<?php $tooltip_db = "Install the database and add the sample data \n Checking this box will import the database structure, required data, and some sample data. (required for first time installations)" ?>
							<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="<?php echo $tooltip_db;?>">
								<?php echo TEXT_ITB; ?>
							</i>
						</div>
					</div>
				   <div>
						<div>
							&nbsp;
						</div>
						<div><?php echo TEXT_SC; ?></div>
						<div>
							<?php echo osc_draw_checkbox_field('install[]', 'configure', true); ?>
							<?php $tooltip_config = TEXT_SAVE_CONFIG; ?>
							<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="<?php echo $tooltip_config;?>">
								<?php echo TEXT_SCV; ?>
							</i>
						</div>    
					</div>     
			<a class="btn btn-info" style="float: left; margin-top: 10px;" href="index.php" role="button"><i class='fas fa-arrow-alt-circle-left' style='color:white'></i> <?php echo TEXT_CANCEL; ?></a>
			<button type="button" class="btn btn-info" style="float: right; margin-top: 10px;" onClick="this.form.submit();"><?php echo TEXT_CONTINUE; ?><i class='fas fa-arrow-alt-circle-right' style='color:white'></i></button>
		</form>
		</div>
	</div>
	</div>
	