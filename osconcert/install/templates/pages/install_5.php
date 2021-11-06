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
  $https_www_address = str_replace('http://', 'https://', $HTTP_POST_VARS['HTTP_WWW_ADDRESS']);
  $current_step="config";
?>

<div class="container" style="width:800px">
<div class="row">
		<div class="col-md-12">
		<ul class="list-group list-group-horizontal justify-content-center">
					  <li class="list-group-item"><strong><?php echo TEXT_SC; ?></strong></li>
					  <li class="list-group-item"><strong><?php echo TEXT_SSC; ?></strong></li>
					  <li class="list-group-item"><?php echo TEXT_DC; ?></li>
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
		<form name="install" action="install.php?step=6" method="post">
			<h3><?php echo TEXT_ENTER3; ?></h3>  
            <div style="width: 100%; float: left; padding-right:15px; margin: 10px 0px;">                        
                <div style="width: 30%; vertical-align: text-top; float: left;" ><?php echo TEXT_SECURE_WWW; ?></div>
                <div style="width: 70%; float: left;">
					<?php echo osc_draw_input_field('HTTPS_WWW_ADDRESS', $https_www_address,'','size=50'); ?>
					<?php $tooltip_web = TEXT_STLD ?>
					<br><i class="fa fa-question-circle" style="color:#117a8b" data-toggle="tooltip" data-placement="bottom" title="<?php echo $tooltip_web;?>">
						<?php echo TEXT_SECURE_FULL; ?>
					</i>    
                   </div>    
            </div>                
            <div style="width: 100%; float: left; padding-right:15px; margin: 10px 0px;">                        
                <div style="width: 30%; vertical-align: text-top; float: left;" ><?php echo TEXT_SECURE_CD; ?></div>
                <div style="width: 70%; float: left;" >
					<?php echo osc_draw_input_field('HTTPS_COOKIE_DOMAIN', $HTTP_POST_VARS['HTTP_COOKIE_DOMAIN'],'','size=50'); ?>
					<?php $tooltip_cookie = TEXT_SECURE_FULLTLD; ?>
					<br><i class="fa fa-question-circle" style="color:#117a8b" data-toggle="tooltip" data-placement="bottom" title="<?php echo $tooltip_cookie;?>">
						<?php echo TEXT_HTTPS_DOMAIN; ?> 
					</i>    
                </div>    
            </div>                
            <div style="width: 100%; float: left; padding-right:15px; margin: 10px 0px;">                        
                <div style="width: 30%; vertical-align: text-top; float: left;" ><?php echo TEXT_HTTPS_PATH; ?></div>
                <div style="width: 70%; float: left;">
					<?php echo osc_draw_input_field('HTTPS_COOKIE_PATH', $HTTP_POST_VARS['HTTP_COOKIE_PATH'],'','size=50'); ?>
					<?php $tooltip_path = TEXT_SECURE_WEB; ?>
					<br><i class="fa fa-question-circle" style="color:#117a8b" data-toggle="tooltip" data-placement="bottom" title="<?php echo $tooltip_path;?>">
						<?php echo TEXT_SECURE_PATH; ?>
					</i>						
                </div>    
            </div>           
			<?php
			$dir_fs_document_root = $HTTP_POST_VARS['DIR_FS_DOCUMENT_ROOT'];
            if (osc_is_writable($dir_fs_document_root) && osc_is_writable($dir_fs_document_root . 'admin')) {
            ?>
				<div style="width: 100%; float: left; padding-right:15px; margin: 10px 0px;">                        
					<div style="width: 30%; vertical-align: text-top; float: left;" >
					<?php echo TEXT_ADMIN_NAME; ?></div>
					<div style="width: 70%; float: left;" >
						<?php echo osc_draw_input_field('CFG_ADMIN_DIRECTORY', 'admin','','size=50'); ?>
						<?php $tooltip_admin = TEXT_THIS_ADMIN ?>
						<br><i class="fa fa-question-circle" style="color:#117a8b" data-toggle="tooltip" data-placement="bottom" title="<?php echo $tooltip_admin;?>">
							<?php echo TEXT_THIS; ?>
						</i>
					</div>    
				</div>  
	  <?php }?> 
	<?php
  reset($HTTP_POST_VARS);
  //while (list($key, $value) = each($HTTP_POST_VARS)) {
	foreach($HTTP_POST_VARS as $key => $value) {
    if (($key != 'x') && ($key != 'y')) {
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
			<a class="btn btn-info" style="float: left; margin-top: 10px;" href="index.php" role="button"><i class='fas fa-arrow-alt-circle-left' style='color:white'></i> <?php echo TEXT_CANCEL; ?></a>
			<button type="button" class="btn btn-info" style="float: right; margin-top: 10px;" onClick="this.form.submit();"><?php echo TEXT_CONTINUE; ?> <i class='fas fa-arrow-alt-circle-right' style='color:white'></i></button>
		</form>
		</div>
</div>
</div><br>
  