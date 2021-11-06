<?php
/* 
	osCommerce, Open Source E-Commerce Solutions 
	http://www.oscommerce.com 
	
	Copyright (c) 2003 osCommerce 
	
	 
	
	Freeway eCommerce 
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
	osConcert, Online Seat Booking 
  	http://www.osconcert.com

  	Copyright (c) 2020 osConcert 
	
	Released under the GNU General Public License 
*/ 

$current_step="config";
?>
<div class="container" style="width:800px">
<div class="row">
		<div class="col-md-12">
		<ul class="list-group list-group-horizontal justify-content-center">
					  <li class="list-group-item"><strong><?php echo TEXT_SC; ?></strong></li>
					  <li class="list-group-item"><strong><?php echo TEXT_SSC; ?></strong></li>
					  <li class="list-group-item"><strong><?php echo TEXT_DC; ?></strong></li>
					  <li class="list-group-item"><?php echo TEXT_CD; ?></li>
					</ul> <br>
		</div>
</div>
<div class="row">
		<div class="col-md-12">
		<div class="progress progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">75%</div>
		</div>
</div>
<div class="row">
		<div class="col-md-12">
		<form name="install" action="install.php?step=7" method="post">
			<h3><?php echo TEXT_ENTER; ?></h3>  		
            <div style="width: 100%; float: left; padding-right:15px; margin: 10px 0px;">                        
                <div style="width: 30%; vertical-align: text-top; float: left;" >
				<?php echo TEXT_DBS; ?></div>
                <div style="width: 70%; float: left;">
					<?php echo osc_draw_input_field('DB_SERVER'); ?>
					<?php $tooltip_server = TEXT_SERVER; ?>
					<br><i class="fa fa-question-circle" style="color:#117a8b" data-toggle="tooltip" data-placement="bottom" title="<?php echo $tooltip_server;?>">
					<?php echo TEXT_HOSTNAME; ?>
					</i>    
                 </div>    
            </div>
             
             <div style="width: 100%; float: left; padding-right:15px; margin: 10px 0px;">
                <div style="width: 30%; vertical-align: text-top; float: left;" >
				<?php echo TEXT_USERNAME; ?></div>
                <div style="width: 70%; float: left;">
					<?php echo osc_draw_input_field('DB_SERVER_USERNAME'); ?>
					<?php $tooltip_name = TEXT_USERNAME_USED; ?>
					<br><i class="fa fa-question-circle" style="color:#117a8b" data-toggle="tooltip" data-placement="bottom" title="<?php echo $tooltip_name;?>">
					<?php echo TEXT_DB; ?>
					</i>
                </div>
            </div>
             
            <div style="width: 100%; float: left; padding-right:15px; margin: 10px 0px;">                        
                <div style="width: 30%; vertical-align: text-top; float: left;" >
				<?php echo TEXT_PASSWORD; ?></div>
                <div style="width: 70%; float: left;">
					<?php echo osc_draw_password_field('DB_SERVER_PASSWORD'); ?>
					<?php $tooltip_pass = TEXT_USER; ?>
					<br><i class="fa fa-question-circle" style="color:#117a8b" data-toggle="tooltip" data-placement="bottom" title="<?php echo $tooltip_pass;?>">
						<?php echo TEXT_DB_PASSWORD; ?>
					</i>
				</div>    
            </div>                            
             
            <div style="width: 100%; float: left; padding-right:15px; margin: 10px 0px;">                        
				<div style="width: 30%; vertical-align: text-top; float: left;" >
				<?php echo TEXT_DB_NAME; ?></div>
				<div style="width: 70%; float: left;">
					<?php echo osc_draw_input_field('DB_DATABASE'); ?>
					<?php $tooltip_db_name = "The database used to hold the data. An example database name is 'osConcert'." ?>
					<br><i class="fa fa-question-circle" style="color:#117a8b" data-toggle="tooltip" data-placement="bottom" title="<?php echo $tooltip_db_name;?>">
					<?php echo TEXT_DB_NAME2; ?>
					</i>
                </div>    
            </div> 
             
            <div style="width: 100%; float: left; padding-right:15px; margin: 10px 0px;">                        
                <div style="width: 30%; vertical-align: text-top; float: left;" >
				<?php echo TEXT_SS; ?></div>
                <div style="width: 70%; float: left;">
					<!--<div class="form-check-inline">
						<?php //echo osc_draw_radio_field('STORE_SESSIONS', 'files'); ?>
						Files
					</div>-->
					<div class="form-check-inline">
						<?php echo osc_draw_radio_field('STORE_SESSIONS', 'mysql','true'); ?>
						<?php echo TEXT_DB1; ?>
					</div>				
					<?php $tooltip_stor = TEXT_STORE_USER; ?>
					<i class="fa fa-question-circle" style="color:#117a8b" data-toggle="tooltip" data-placement="bottom" title="<?php echo $tooltip_stor;?>"></i>					
                 </div>    
            </div> 
			<a class="btn btn-info" style="float: left; margin-top: 10px;" href="index.php" role="button"><i class='fas fa-arrow-alt-circle-left' style='color:white'></i> <?php echo TEXT_CANCEL; ?></a>
			<button type="button" class="btn btn-info" style="float: right; margin-top: 10px;" onClick="this.form.submit();"><?php echo TEXT_CONTINUE; ?> <i class='fas fa-arrow-alt-circle-right' style='color:white'></i></button>
		<?php
			reset($HTTP_POST_VARS);
			//while (list($key, $value) = each($HTTP_POST_VARS)) {
				foreach($HTTP_POST_VARS as $key => $value)
				{
				if (($key != 'x') && ($key != 'y') && ($key != 'DB_SERVER') && ($key != 'DB_SERVER_USERNAME') && ($key != 'DB_SERVER_PASSWORD') && ($key != 'DB_DATABASE') && ($key != 'STORE_SESSIONS')) {
					if (is_array($value)) {
						for ($i=0; $i<sizeof($value); $i++) {
							echo osc_draw_hidden_field($key . '[]', $value[$i]);
						}
					} else {
						echo osc_draw_hidden_field($key, $value);
					}
				}
			}
		?>
		</form>
		</div>
</div>
</div>
<br>
