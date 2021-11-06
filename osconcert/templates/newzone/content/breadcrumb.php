<?php 

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
?>
<!--start breadcrumb-->
<div class="container mybreadcrumb">
  <div class="row">
    <div class="col-md-9">
	<?php echo $breadcrumb->trail(sprintf('&nbsp;%s&nbsp;', $arrows)); ?>
    </div>
	<?php
if (!isset($_COOKIE['customer_is_guest']))
{ //PWA
	?>
    <div class="col-md-3" style="text-align:right">
	<?php
	if ($_SESSION['customer_country_id']==999)
	{
	echo '<span class="alert alert-success" style="padding:0">' .BOX_OFFICE_USER.'</span>';
	}else
	{
		if(SHOW_CUSTOMER_GREETING=='yes')
		{
		echo $greet; 
		}
	echo $account;  
	}
	?>
    </div>
	<?php 
}
	?>
  </div>
</div>
<!--end breadcrumb-->