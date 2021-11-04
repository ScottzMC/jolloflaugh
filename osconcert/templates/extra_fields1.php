<?php 
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
?>
	<!--Add Extra Fields begin-->
	<script language="JavaScript">
	function echeck(str) {
			var at="@"
			var dot="."
			var lat=str.indexOf(at)
			var lstr=str.length
			var ldot=str.indexOf(dot)
			if (str.indexOf(at)==-1){
			   alert("Invalid E-mail ID")
			   return false
			}
			if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
			   alert("Invalid E-mail ID")
			   return false
			}
			if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
				alert("Invalid E-mail ID")
				return false
			}
			 if (str.indexOf(at,(lat+1))!=-1){
				alert("Invalid E-mail ID")
				return false
			 }
			 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
				alert("Invalid E-mail ID")
				return false
			 }
			 if (str.indexOf(dot,(lat+2))==-1){
				alert("Invalid E-mail ID")
				return false
			 }
			 if (str.indexOf(" ")!=-1){
				alert("Invalid E-mail ID")
				return false
			 }
			 return true
		}
		function checkvalidate()
		{
			<?php //check for this field
							if (tep_not_null(FIELD_1)) 
							{
							?>
			if (document.checkout_payment.field_1.value.length < <?php echo FIELD_1_VALIDATE; ?>) {
							alert("<?php echo PLEASE_ENTER_YOUR; ?><?php echo FIELD_1; ?>");
							document.checkout_payment.field_1.focus();
							return false;
							}
			<?php } ?>			
			<?php //check for this field
							if (tep_not_null(FIELD_2)) 
							{
							?>
			if (document.checkout_payment.field_2.value.length < <?php echo FIELD_2_VALIDATE; ?>) {
							alert("<?php echo PLEASE_ENTER_YOUR; ?><?php echo FIELD_2; ?>");
							document.checkout_payment.field_2.focus();
							return false;
							}
			<?php } ?>
			<?php //check for this field
							if (tep_not_null(FIELD_3)) 
							{
							?>
			if (document.checkout_payment.field_3.value.length < <?php echo FIELD_3_VALIDATE; ?>) {
							alert("<?php echo PLEASE_ENTER_YOUR; ?><?php echo FIELD_3; ?>");
							document.checkout_payment.field_3.focus();
							return false;
							}
			<?php } ?>
			<?php //check for this field
							if (tep_not_null(FIELD_4)) {
							?>
			var emailID=document.checkout_payment.field_4;
			if ((emailID.value==null)||(emailID.value=="")){
				alert("<?php echo PLEASE_ENTER_YOUR; ?>email address");
				document.checkout_payment.field_4.focus();
				return false;
				}
			if (echeck(emailID.value)==false){
				emailID.value="";
				emailID.focus();
				return false;
			}
			<?php } ?>
			<?php //check for this field
							if (tep_not_null(FIELD_5)) 
							{
							?>
			if (document.checkout_payment.field_5.value.length < <?php echo FIELD_5_VALIDATE; ?>) {
							alert("<?php echo PLEASE_ENTER_YOUR; ?><?php echo FIELD_5; ?>");
							document.checkout_payment.field_5.focus();
							return false;
							}
			<?php 
			} ?>
		}
	</script>
		<?php //now the heading
			//$product_id_list=$cart->get_check_id_list();
				//echo $product_id_list;
				?>
				<br>
				<h4><?php echo NEW_FIELDS_HEADING; ?></h4>
				 <?php //OK now the HTML for the fields?> 
	 
		<div class="container-fluid">
			<!-- start -->
					<?php
					if (tep_not_null(FIELD_1)) 
					{
					?>
					<div class="row">
						<div class="col-md-6">
						<strong><?php echo FIELD_1; ?>: </strong>
						</div>
						<div class="col-md-6">
						<input type="text" size="40" name="field_1" id="field_1" value="<?php echo $FSESSION->get('field_1');?>" maxlength="60">
						</div>
					</div><br>
					<?php 
					}
					?>
			<!-- end -->
			<!-- start -->
					<?php
					if (tep_not_null(FIELD_2)) 
					{
					?>
					<div class="row">
						<div class="col-md-6">
						<strong><?php echo FIELD_2; ?>: </strong>
						</div>
						<div class="col-md-6">
						<input type="text" size="40" name="field_2" id="field_2" value="<?php echo $FSESSION->get('field_2');?>" maxlength="60">
						</div>
					</div><br>
					<?php 
					}
					?>
					<?php 
					if (tep_not_null(FIELD_3)) 
					{
					?>
					<div class="row">
						<div class="col-md-6">
						<strong><?php echo FIELD_3; ?>: </strong>
						</div>
						<div class="col-md-6">
						<input type="text" size="40" name="field_3" id="field_3" value="<?php echo $FSESSION->get('field_3');?>" maxlength="60">
						</div>
					</div><br>
					<?php 
					}
					?>
			<!-- end -->

			<!-- start -->
					<?php
					if (tep_not_null(FIELD_4)) 
					{
					?>
					<div class="row">
						<div class="col-md-6">
						<strong><?php echo FIELD_4; ?>: </strong>
						</div>
						<div class="col-md-6">
						<input type="text" size="40" name="field_4" id="field_4" value="<?php echo $FSESSION->get('field_4');?>" maxlength="60">
						</div>
					</div><br>
					<?php 
					}
					?>
					<?php 
					if (tep_not_null(FIELD_5)) 
					{
					?>
					<div class="row">
						<div class="col-md-6">
						<strong><?php echo FIELD_5; ?>: </strong>
						</div>
						<div class="col-md-6">
						<?php echo tep_draw_textarea_field('other', 'soft', '60', '5'); ?>
						</div>
					</div><br>
					<?php 
					}
					?>
			<!-- end -->
		</div>
		<!--Add Extra Fields end-->