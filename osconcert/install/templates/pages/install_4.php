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
  //$cookie_path = substr(dirname(getenv('SCRIPT_NAME')), 0, -7);
  $script_filename = getenv('PATH_TRANSLATED');
  if (empty($script_filename)) $script_filename = getenv('SCRIPT_FILENAME');
  if (empty($script_filename)) $script_filename = $_SERVER["SCRIPT_FILENAME"];
  
  $script_filename = str_replace('\\', '/', $script_filename);
  $script_filename = str_replace('//', '/', $script_filename);
  
  $script_name=$_SERVER['PHP_SELF'];   
    
  $cookie_path = substr(dirname($script_name), 0, -7);
  
  $www_location = 'http://' . $_SERVER['HTTP_HOST'] . $script_name;  
  $www_location = substr($www_location, 0, strpos($www_location, '/install')) ."/";
  
   
  $dir_fs_www_root_array = explode('/', dirname($script_filename));
  $dir_fs_www_root = array();
  for ($i=0, $n=sizeof($dir_fs_www_root_array)-1; $i<$n; $i++)
  	$dir_fs_www_root[] = $dir_fs_www_root_array[$i];
  $dir_fs_www_root = implode('/', $dir_fs_www_root) . '/';  
  $current_step="config";
?>

<div class="container" style="width:800px">
<div class="row">
		<div class="col-md-12">
		<ul class="list-group list-group-horizontal justify-content-center">
					  <li class="list-group-item"><strong><?php echo TEXT_SC; ?></strong></li>
					  <li class="list-group-item"><?php echo TEXT_SSC; ?></li>
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
				<form name="install" action="install.php?step=5" method="post">
			<h3><?php echo TEXT_ENTER2; ?></h3>  
			<div style="width: 100%; float: left; padding-right:15px; margin: 10px 0px;"> 
				<div style="width: 30%; vertical-align: text-top; float: left;" ><?php echo TEXT_WWW_ADDRESS; ?></div>
				<div style="width: 70%; float: left;">
					<?php echo osc_draw_input_field('HTTP_WWW_ADDRESS', $www_location,'','size=50'); ?>
					<?php $tooltip_web = TEXT_WEB_ADDRESS; ?>
					<i class="fa fa-question-circle" style="color:#117a8b" data-toggle="tooltip" data-placement="bottom" title="<?php echo $tooltip_web;?>">
						<?php echo TEXT_FULL_ADDRESS; ?>
					</i>    
				</div>			
			</div>
			<div style="width: 100%; float: left; padding-right:15px; margin: 10px 0px;"> 			
				<div style="width: 30%; vertical-align: text-top; float: left;" ><?php echo TEXT_WRD; ?></div>
				<div style="width: 70%; float: left;">
					<?php echo osc_draw_input_field('DIR_FS_DOCUMENT_ROOT', $dir_fs_www_root,'','size=50'); ?>
					<?php $tooltip_root = TEXT_DIRECTORY; ?>
					<i class="fa fa-question-circle" style="color:#117a8b" data-toggle="tooltip" data-placement="bottom" title="<?php echo $tooltip_root;?>">
						<?php echo TEXT_PATH; ?>
					</i>
				</div>
			</div>
			<div style="width: 100%; float: left; padding-right:15px; margin: 10px 0px;"> 						
				<div style="width: 30%; vertical-align: text-top; float: left;" ><?php echo TEXT_HTTP_COOKIE; ?></div>
				<div style="width: 70%; float: left;">
					<?php echo osc_draw_input_field('HTTP_COOKIE_DOMAIN', $_SERVER['HTTP_HOST'],'','size=50'); ?>
					<?php $tooltip_cookie = TEXT_TLD; ?>
					<i class="fa fa-question-circle" style="color:#117a8b" data-toggle="tooltip" data-placement="bottom" title="<?php echo $tooltip_cookie;?>">
						<?php echo TEXT_PTSC; ?>
					</i>
				</div>    
            </div>
			<div style="width: 100%; float: left; padding-right:15px; margin: 10px 0px;"> 									
				<div style="width: 30%; vertical-align: text-top; float: left;" ><?php echo TEXT_HTTP_PATH; ?></div>
				<div style="width: 70%; float: left;">
					<?php echo osc_draw_input_field('HTTP_COOKIE_PATH', $cookie_path,'','size=50'); ?>
					<?php $tooltip_cookie_path = TEXT_EXAMPLE; ?>
					<i class="fa fa-question-circle" style="color:#117a8b" data-toggle="tooltip" data-placement="bottom" title="<?php echo $tooltip_cookie_path;?>">
						<?php echo TEXT_PTSC; ?>
					</i>
				</div>    
            </div>  
			<div style="width: 100%; float: left; padding-right:15px; margin: 10px 0px;"> 												
				<div style="width: 30%; vertical-align: text-top; float: left;" ><?php echo TEXT_HOMEPAGE; ?></div>
				<div style="width: 70%; float: left;">
					<?php echo osc_draw_input_field('HTTP_HOME_URL',$www_location,'','size=50'); ?>
					<?php $tooltip_home = TEXT_HOMEURL ?>
					<i class="fa fa-question-circle" style="color:#117a8b" data-toggle="tooltip" data-placement="bottom" title="<?php echo $tooltip_home;?>">
						<?php echo TEXT_HOMELINK; ?>
					</i>
				</div>
            </div>
			<div style="width: 100%; float: left; padding-right:15px; margin: 10px 0px;"> 												
				<div style="width: 30%; vertical-align: text-top; float: left;" ><?php echo TEXT_SSL; ?></div>
				<div style="width: 70%; float: left;">
					<?php echo osc_draw_checkbox_field('ENABLE_SSL', 'true', 'true'); ?>
					<?php $tooltip_home = TEXT_ENABLESSL; ?>
					<i class="fa fa-question-circle" style="color:#117a8b" data-toggle="tooltip" data-placement="bottom" title="<?php echo $tooltip_home;?>">
						<?php echo TEXT_ENABLESSL2; ?>
					</i>
				</div>
            </div>
			<a class="btn btn-info" style="float: left; margin-top: 10px;" href="index.php" role="button"><i class='fas fa-arrow-alt-circle-left' style='color:white'></i> <?php echo TEXT_CANCEL; ?></a>
			<button type="button" class="btn btn-info" style="float: right; margin-top: 10px;" onClick="this.form.submit();"><?php echo TEXT_CONTINUE; ?> <i class='fas fa-arrow-alt-circle-right' style='color:white'></i></button>
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
			echo osc_draw_hidden_field('install[]', 'configure');
			?>  
		</form>
		</div>
</div>
</div><br><br>