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

  	Copyright (c) 2021 osConcert 
	
	Released under the GNU General Public License 
*/ 
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>osConcert Ticketing Software</title>
	<meta charset="utf-8">
	<meta name="ROBOTS" content="NOFOLLOW">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="templates/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="templates/main_page/stylesheet.css" rel="stylesheet" >
	<script src="templates/js/jquery.slim.min.js"></script>	
	<script language="javascript" src="templates/main_page/javascript.js"></script> 
	<link href="templates/font-awesome/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow fixed-top">
  <div class="container">
    <a class="navbar-brand" href="#"><?php echo $page_title;?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
  </div>
</nav>

<!-- Page Content -->
	<section class="py-5"></section>
	<div class="container-fluid">
		<div class="row h-100 align-items-center">
			<div class="col-12 align-items-center">
				<?php
					require('templates/pages/' . $page_contents); 
					if (!isset($current_step)){ $current_step="install";}					
				?>
			</div>
		</div>  
	</div>
</body>
</html>