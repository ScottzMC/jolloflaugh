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

  	Copyright (c) 2009-2020 osConcert 
	
	Released under the GNU General Public License 
*/ 
//require('includes/german.php');
?>
<div class="card box-shadow mb-3 mx-auto" style="max-width: 50rem;" id="txr_terms">
<div class="card-header"><?php echo LICENSING; ?></div>
<div class="card-body">
	<p class="card-text"><?php echo TEXT_GNU; ?></p>
	<p class="card-text"><?php echo TEXT_COPYR; ?></p>
	<div id="install"  style="visibility:hidden">
	<?php echo TEXT_AGREE; ?> &nbsp; <input type="checkbox" name="chk_agree" id="chk_agree" onclick="javascript:do_check(this);">
	</div>
	<a class="btn btn-info pull-right" id="img_install" style="visibility:hidden" href="install.php?mode=compat_test" role="button"><?php echo TEXT_INSTALL; ?></a>
	</div>
	<a class="btn btn-info pull-right" id="img_install"  target="_blank" href="https://www.osconcert.com/installation.pdf" role="button"><?php echo TEXT_OPEN; ?></a>
</div>
<script>
function do_check(obj){
	if(obj.checked)
		document.getElementById("img_install").style.visibility="visible";
	else 
		document.getElementById("img_install").style.visibility="hidden";
}
	var readTerms=false;
	var readInterval;
	function checkRead(){
		if (readTerms) return;
		var element=document.getElementById("txr_terms");
		if (parseInt(element.scrollHeight)==0) return;
		//alert((parseInt(element.scrollHeight)-parseInt(element.style.height))-parseInt(element.scrollTop));
		//if ((parseInt(element.scrollHeight)-parseInt(element.style.height))-parseInt(element.scrollTop)<=50)
		{
			readTerms=true;
			document.getElementById("install").style.visibility="visible";
			clearInterval(readInterval);
		}
	}
	readInterval=setInterval("checkRead()",10);
	document.getElementById('chk_agree').checked=false;
</script>