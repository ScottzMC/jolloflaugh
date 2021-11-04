<?php 
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();

//add new fields options IMPORTANT if these fields are empty...the empty fields will be omitted. (Apart from email/field_4)
define('NEW_FIELDS_HEADING','Please select the name and status of each ticket holder below.');
define('FIELD_1','TICKET HOLDER 1 STATUS');
define('ID_1A','child');
define('TEXT_1A','Child');
define('ID_1B','teen');
define('TEXT_1B','Teen');
define('ID_1C','adult');
define('TEXT_1C','Adult');
define('FIELD_1_VALIDATE', '1');//0 = no validation
define('FIELD_2','TICKET HOLDER 1 NAME');
define('FIELD_2_VALIDATE', '1');//0 = no validation

define('FIELD_3','TICKET HOLDER 2 STATUS');
define('ID_3A','child');
define('TEXT_3A','Child');
define('ID_3B','teen');
define('TEXT_3B','Teen');
define('ID_3C','adult');
define('TEXT_3C','Adult');
define('FIELD_3_VALIDATE', '1');//0 = no validation
define('FIELD_4','TICKET HOLDER 2 NAME');
define('FIELD_4_VALIDATE', '1');//0 = no validation

define('FIELD_5','TICKET HOLDER 3 STATUS');
define('ID_5A','child');
define('TEXT_5A','Child');
define('ID_5B','teen');
define('TEXT_5B','Teen');
define('ID_5C','adult');
define('TEXT_5C','Adult');
define('FIELD_5_VALIDATE', '1');//0 = no validation
define('FIELD_6','TICKET HOLDER 3 NAME');
define('FIELD_6_VALIDATE', '1');//0 = no validation

define('FIELD_7','TICKET HOLDER 4 STATUS');
define('ID_7A','child');
define('TEXT_7A','Child');
define('ID_7B','teen');
define('TEXT_7B','Teen');
define('ID_7C','adult');
define('TEXT_7C','Adult');
define('FIELD_7_VALIDATE', '1');//0 = no validation
define('FIELD_8','TICKET HOLDER 4 NAME');
define('FIELD_8_VALIDATE', '1');//0 = no validation

define('FIELD_9','TICKET HOLDER 5 STATUS');
define('ID_9A','child');
define('TEXT_9A','Child');
define('ID_9B','teen');
define('TEXT_9B','Teen');
define('ID_9C','adult');
define('TEXT_9C','Adult');
define('FIELD_9_VALIDATE', '1');//0 = no validation
define('FIELD_10','TICKET HOLDER 5 NAME');
define('FIELD_10_VALIDATE', '1');//0 = no validation

define('FIELD_11','TICKET HOLDER 6 STATUS');
define('FIELD_11_VALIDATE', '1');//0 = no validation
define('FIELD_12','TICKET HOLDER 6 NAME');
define('FIELD_12_VALIDATE', '1');//0 = no validation
define('FIELD_13','TICKET HOLDER 7 STATUS');
define('FIELD_13_VALIDATE', '1');//0 = no validation
define('FIELD_14','TICKET HOLDER 7 NAME');
define('FIELD_14_VALIDATE', '1');//0 = no validation
define('FIELD_15','TICKET HOLDER 8 STATUS');
define('FIELD_15_VALIDATE', '1');//0 = no validation
define('FIELD_16','TICKET HOLDER 8 NAME');
define('FIELD_16_VALIDATE', '1');//0 = no validation
define('FIELD_17','TICKET HOLDER 9 STATUS');
define('FIELD_17_VALIDATE', '1');//0 = no validation
define('FIELD_18','TICKET HOLDER 9 NAME');
define('FIELD_18_VALIDATE', '1');//0 = no validation
define('FIELD_19','TICKET HOLDER 10 STATUS');
define('FIELD_19_VALIDATE', '1');//0 = no validation
define('FIELD_20','TICKET HOLDER 10 NAME');
define('FIELD_20_VALIDATE', '1');//0 = no validation
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
			<?php 
			} ?>
			<?php //check for this field
							if (tep_not_null(FIELD_4)) 
							{
							?>
			if (document.checkout_payment.field_4.value.length < <?php echo FIELD_4_VALIDATE; ?>) {
							alert("<?php echo PLEASE_ENTER_YOUR; ?><?php echo FIELD_4; ?>");
							document.checkout_payment.field_4.focus();
							return false;
							}
			<?php 
			} ?>
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
			<?php //check for this field
							if (tep_not_null(FIELD_6)) 
							{
							?>
			if (document.checkout_payment.field_6.value.length < <?php echo FIELD_6_VALIDATE; ?>) {
							alert("<?php echo PLEASE_ENTER_YOUR; ?><?php echo FIELD_6; ?>");
							document.checkout_payment.field_6.focus();
							return false;
							}
			<?php 
			} ?>
			<?php //check for this field
							if (tep_not_null(FIELD_7)) 
							{
							?>
			if (document.checkout_payment.field_7.value.length < <?php echo FIELD_7_VALIDATE; ?>) {
							alert("<?php echo PLEASE_ENTER_YOUR; ?><?php echo FIELD_7; ?>");
							document.checkout_payment.field_7.focus();
							return false;
							}
			<?php 
			} ?>
			<?php //check for this field
							if (tep_not_null(FIELD_8)) 
							{
							?>
			if (document.checkout_payment.field_8.value.length < <?php echo FIELD_8_VALIDATE; ?>) {
							alert("<?php echo PLEASE_ENTER_YOUR; ?><?php echo FIELD_8; ?>");
							document.checkout_payment.field_8.focus();
							return false;
							}
			<?php 
			} ?>
			<?php //check for this field
							if (tep_not_null(FIELD_9)) 
							{
							?>
			if (document.checkout_payment.field_9.value.length < <?php echo FIELD_9_VALIDATE; ?>) {
							alert("<?php echo PLEASE_ENTER_YOUR; ?><?php echo FIELD_9; ?>");
							document.checkout_payment.field_9.focus();
							return false;
							}
			<?php 
			} ?>
			<?php //check for this field
							if (tep_not_null(FIELD_10)) 
							{
							?>
			if (document.checkout_payment.field_10.value.length < <?php echo FIELD_10_VALIDATE; ?>) {
							alert("<?php echo PLEASE_ENTER_YOUR; ?><?php echo FIELD_10; ?>");
							document.checkout_payment.field_10.focus();
							return false;
							}
			<?php 
			} ?>
			<?php //check for this field
							if (tep_not_null(FIELD_11)) 
							{
							?>
			if (document.checkout_payment.field_11.value.length < <?php echo FIELD_11_VALIDATE; ?>) {
							alert("<?php echo PLEASE_ENTER_YOUR; ?><?php echo FIELD_11; ?>");
							document.checkout_payment.field_11.focus();
							return false;
							}
			<?php 
			} ?>
			<?php //check for this field
							if (tep_not_null(FIELD_12)) 
							{
							?>
			if (document.checkout_payment.field_12.value.length < <?php echo FIELD_12_VALIDATE; ?>) {
							alert("<?php echo PLEASE_ENTER_YOUR; ?><?php echo FIELD_12; ?>");
							document.checkout_payment.field_12.focus();
							return false;
							}
			<?php 
			} ?>
			<?php //check for this field
							if (tep_not_null(FIELD_13)) 
							{
							?>
			if (document.checkout_payment.field_13.value.length < <?php echo FIELD_13_VALIDATE; ?>) {
							alert("<?php echo PLEASE_ENTER_YOUR; ?><?php echo FIELD_13; ?>");
							document.checkout_payment.field_13.focus();
							return false;
							}
			<?php 
			} ?>
			<?php //check for this field
							if (tep_not_null(FIELD_14)) 
							{
							?>
			if (document.checkout_payment.field_14.value.length < <?php echo FIELD_14_VALIDATE; ?>) {
							alert("<?php echo PLEASE_ENTER_YOUR; ?><?php echo FIELD_14; ?>");
							document.checkout_payment.field_14.focus();
							return false;
							}
			<?php 
			} ?>
			<?php //check for this field
							if (tep_not_null(FIELD_15)) 
							{
							?>
			if (document.checkout_payment.field_15.value.length < <?php echo FIELD_15_VALIDATE; ?>) {
							alert("<?php echo PLEASE_ENTER_YOUR; ?><?php echo FIELD_15; ?>");
							document.checkout_payment.field_15.focus();
							return false;
							}
			<?php 
			} ?>
			<?php //check for this field
							if (tep_not_null(FIELD_16)) 
							{
							?>
			if (document.checkout_payment.field_16.value.length < <?php echo FIELD_16_VALIDATE; ?>) {
							alert("<?php echo PLEASE_ENTER_YOUR; ?><?php echo FIELD_16; ?>");
							document.checkout_payment.field_16.focus();
							return false;
							}
			<?php 
			} ?>
			<?php //check for this field
							if (tep_not_null(FIELD_17)) 
							{
							?>
			if (document.checkout_payment.field_17.value.length < <?php echo FIELD_17_VALIDATE; ?>) {
							alert("<?php echo PLEASE_ENTER_YOUR; ?><?php echo FIELD_17; ?>");
							document.checkout_payment.field_17.focus();
							return false;
							}
			<?php 
			} ?>
			<?php //check for this field
							if (tep_not_null(FIELD_18)) 
							{
							?>
			if (document.checkout_payment.field_18.value.length < <?php echo FIELD_18_VALIDATE; ?>) {
							alert("<?php echo PLEASE_ENTER_YOUR; ?><?php echo FIELD_18; ?>");
							document.checkout_payment.field_18.focus();
							return false;
							}
			<?php 
			} ?>
			<?php //check for this field
							if (tep_not_null(FIELD_19)) 
							{
							?>
			if (document.checkout_payment.field_19.value.length < <?php echo FIELD_19_VALIDATE; ?>) {
							alert("<?php echo PLEASE_ENTER_YOUR; ?><?php echo FIELD_19; ?>");
							document.checkout_payment.field_19.focus();
							return false;
							}
			<?php 
			} ?>
			<?php //check for this field
							if (tep_not_null(FIELD_20)) 
							{
							?>
			if (document.checkout_payment.field_20.value.length < <?php echo FIELD_20_VALIDATE; ?>) {
							alert("<?php echo PLEASE_ENTER_YOUR; ?><?php echo FIELD_20; ?>");
							document.checkout_payment.field_20.focus();
							return false;
							}
			<?php 
			} ?>
			

		}
	</script>
		<?php //now the heading
			//$product_id_list=$cart->get_check_id_list();
				//echo $product_id_list;
			
			if($cart->count_contents() > 0) 
			{
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
						<?php 
						function tep_cfg_pull_down_agent1($id,$key='')
						{
						$name = (($key) ? 'configuration[' . $key . ']' : 'field_1');
						$agent=array(
						array('id' => '','text' =>PLEASE_SELECT),
						array('id' => ID_1A,'text' =>TEXT_1A),
						array('id' => ID_1B,'text' =>TEXT_1B),
						array('id' => ID_1C,'text' =>TEXT_1C),
						);
						return tep_draw_pull_down_menu($name, $agent, $id);
						}
						echo tep_cfg_pull_down_agent1(', now())') ?>
					</div>
                    </div>
					<?php 
					}
					?>
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
			}
			?>
			<!-- end -->
			<?php
			if($cart->count_contents() > 1) 
			{
			?>
			<!-- start -->
					<?php
					if (tep_not_null(FIELD_3)) 
					{
					?>
					<div class="row">
					<div class="col-md-6">
					<strong><?php echo FIELD_3; ?>: </strong>
					</div>
					<div class="col-md-6">
						<?php 
						function tep_cfg_pull_down_agent3($id,$key='')
						{
						$name = (($key) ? 'configuration[' . $key . ']' : 'field_3');
						$agent=array(
						array('id' => '','text' =>PLEASE_SELECT),
						array('id' => ID_3A,'text' =>TEXT_3A),
						array('id' => ID_3B,'text' =>TEXT_3B),
						array('id' => ID_3C,'text' =>TEXT_3C),
						);
						return tep_draw_pull_down_menu($name, $agent, $id);
						}
						echo tep_cfg_pull_down_agent3(', now())') ?>
					</div>
                    </div>
					<?php 
					}
					?>
					<?php 
					if (tep_not_null(FIELD_4)) 
					{
					?>
					<div class="row">
						<div class="col-md-6">
						<strong><?php echo FIELD_4; ?>: </strong>
						</div>
						<div class="col-md-6">
						<input type="text" size="40" name="field_2" id="field_4" value="<?php echo $FSESSION->get('field_4');?>" maxlength="60">
						</div>
					</div><br>
					<?php 
					}
			}
			?>
			<!-- end -->
			<?php
			if($cart->count_contents() > 2) 
			{
			?>
			<!-- start -->
					<?php
					if (tep_not_null(FIELD_5)) 
					{
					?>
					<div class="row">
					<div class="col-md-6">
					<strong><?php echo FIELD_5; ?>: </strong>
					</div>
					<div class="col-md-6">
						<?php 
						function tep_cfg_pull_down_agent5($id,$key='')
						{
						$name = (($key) ? 'configuration[' . $key . ']' : 'field_5');
						$agent=array(
						array('id' => '','text' =>PLEASE_SELECT),
						array('id' => ID_5A,'text' =>TEXT_5A),
						array('id' => ID_5B,'text' =>TEXT_5B),
						array('id' => ID_5C,'text' =>TEXT_5C),
						);
						return tep_draw_pull_down_menu($name, $agent, $id);
						}
						echo tep_cfg_pull_down_agent5(', now())') ?>
					</div>
                    </div>
					<?php 
					}
					?>
					<?php 
					if (tep_not_null(FIELD_6)) 
					{
					?>
					<div class="row">
						<div class="col-md-6">
						<strong><?php echo FIELD_6; ?>: </strong>
						</div>
						<div class="col-md-6">
						<input type="text" size="40" name="field_6" id="field_6" value="<?php echo $FSESSION->get('field_6');?>" maxlength="60">
						</div>
					</div><br>
					<?php 
					}
			}
			?>
			<!-- end -->
			<?php
			if($cart->count_contents() > 3) 
			{
			?>
			<!-- start -->
					<?php
					if (tep_not_null(FIELD_7)) 
					{
					?>
					<div class="row">
					<div class="col-md-6">
					<strong><?php echo FIELD_7; ?>: </strong>
					</div>
					<div class="col-md-6">
						<?php 
						function tep_cfg_pull_down_agent7($id,$key='')
						{
						$name = (($key) ? 'configuration[' . $key . ']' : 'field_7');
						$agent=array(
						array('id' => '','text' =>PLEASE_SELECT),
						array('id' => ID_7A,'text' =>TEXT_7A),
						array('id' => ID_7B,'text' =>TEXT_7B),
						array('id' => ID_7C,'text' =>TEXT_7C),
						);
						return tep_draw_pull_down_menu($name, $agent, $id);
						}
						echo tep_cfg_pull_down_agent7(', now())') ?>
					</div>
                    </div>
					<?php 
					}
					?>
					<?php 
					if (tep_not_null(FIELD_8)) 
					{
					?>
					<div class="row">
						<div class="col-md-6">
						<strong><?php echo FIELD_8; ?>: </strong>
						</div>
						<div class="col-md-6">
						<input type="text" size="40" name="field_8" id="field_8" value="<?php echo $FSESSION->get('field_8');?>" maxlength="60">
						</div>
					</div><br>
					<?php 
					}
			}
			?>
			<!-- end -->
			<?php
			if($cart->count_contents() > 4) 
			{
			?>
			<!-- start -->
					<?php
					if (tep_not_null(FIELD_9)) 
					{
					?>
					<div class="row">
					<div class="col-md-6">
					<strong><?php echo FIELD_9; ?>: </strong>
					</div>
					<div class="col-md-6">
						<?php 
						function tep_cfg_pull_down_agent9($id,$key='')
						{
						$name = (($key) ? 'configuration[' . $key . ']' : 'field_9');
						$agent=array(
						array('id' => '','text' =>PLEASE_SELECT),
						array('id' => ID_9A,'text' =>TEXT_9A),
						array('id' => ID_9B,'text' =>TEXT_9B),
						array('id' => ID_9C,'text' =>TEXT_9C),
						);
						return tep_draw_pull_down_menu($name, $agent, $id);
						}
						echo tep_cfg_pull_down_agent9(', now())') ?>
					</div>
                    </div>
					<?php 
					}
					?>
					<?php 
					if (tep_not_null(FIELD_10)) 
					{
					?>
					<div class="row">
						<div class="col-md-6">
						<strong><?php echo FIELD_10; ?>: </strong>
						</div>
						<div class="col-md-6">
						<input type="text" size="40" name="field_10" id="field_10" value="<?php echo $FSESSION->get('field_10');?>" maxlength="60">
						</div>
					</div><br>
					<?php 
					}
			}
			?>
			<!-- end -->
			<?php
			if($cart->count_contents() > 5) 
			{
			?>
			<!-- start -->
					<?php
					if (tep_not_null(FIELD_11)) 
					{
					?>
					<div class="row">
					<div class="col-md-6">
					<strong><?php echo FIELD_11; ?>: </strong>
					</div>
					<div class="col-md-6">
						<?php 
						function tep_cfg_pull_down_agent11($id,$key='')
						{
						$name = (($key) ? 'configuration[' . $key . ']' : 'field_11');
						$agent=array(
						array('id' => '','text' =>PLEASE_SELECT),
						array('id' => ID_11A,'text' =>TEXT_11A),
						array('id' => ID_11B,'text' =>TEXT_11B),
						array('id' => ID_11C,'text' =>TEXT_11C),
						);
						return tep_draw_pull_down_menu($name, $agent, $id);
						}
						echo tep_cfg_pull_down_agent11(', now())') ?>
					</div>
                    </div>
					<?php 
					}
					?>
					<?php 
					if (tep_not_null(FIELD_12)) 
					{
					?>
					<div class="row">
						<div class="col-md-6">
						<strong><?php echo FIELD_12; ?>: </strong>
						</div>
						<div class="col-md-6">
						<input type="text" size="40" name="field_12" id="field_12" value="<?php echo $FSESSION->get('field_12');?>" maxlength="60">
						</div>
					</div><br>
					<?php 
					}
			}
			?>
			<!-- end -->
			<?php
			if($cart->count_contents() > 6) 
			{
			?>
			<!-- start -->
					<?php
					if (tep_not_null(FIELD_13)) 
					{
					?>
					<div class="row">
					<div class="col-md-6">
					<strong><?php echo FIELD_13; ?>: </strong>
					</div>
					<div class="col-md-6">
						<?php 
						function tep_cfg_pull_down_agent13($id,$key='')
						{
						$name = (($key) ? 'configuration[' . $key . ']' : 'field_13');
						$agent=array(
						array('id' => '','text' =>PLEASE_SELECT),
						array('id' => ID_13A,'text' =>TEXT_13A),
						array('id' => ID_13B,'text' =>TEXT_13B),
						array('id' => ID_13C,'text' =>TEXT_13C),
						);
						return tep_draw_pull_down_menu($name, $agent, $id);
						}
						echo tep_cfg_pull_down_agent13(', now())') ?>
					</div>
                    </div>
					<?php 
					}
					?>
					<?php 
					if (tep_not_null(FIELD_14)) 
					{
					?>
					<div class="row">
						<div class="col-md-6">
						<strong><?php echo FIELD_14; ?>: </strong>
						</div>
						<div class="col-md-6">
						<input type="text" size="40" name="field_14" id="field_14" value="<?php echo $FSESSION->get('field_14');?>" maxlength="60">
						</div>
					</div><br>
					<?php 
					}
			}
			?>
			<!-- end -->
			<?php
			if($cart->count_contents() > 7) 
			{
			?>
			<!-- start -->
					<?php
					if (tep_not_null(FIELD_15)) 
					{
					?>
					<div class="row">
					<div class="col-md-6">
					<strong><?php echo FIELD_15; ?>: </strong>
					</div>
					<div class="col-md-6">
						<?php 
						function tep_cfg_pull_down_agent15($id,$key='')
						{
						$name = (($key) ? 'configuration[' . $key . ']' : 'field_15');
						$agent=array(
						array('id' => '','text' =>PLEASE_SELECT),
						array('id' => ID_15A,'text' =>TEXT_15A),
						array('id' => ID_15B,'text' =>TEXT_15B),
						array('id' => ID_15C,'text' =>TEXT_15C),
						);
						return tep_draw_pull_down_menu($name, $agent, $id);
						}
						echo tep_cfg_pull_down_agent15(', now())') ?>
					</div>
                    </div>
					<?php 
					}
					?>
					<?php 
					if (tep_not_null(FIELD_16)) 
					{
					?>
					<div class="row">
						<div class="col-md-6">
						<strong><?php echo FIELD_16; ?>: </strong>
						</div>
						<div class="col-md-6">
						<input type="text" size="40" name="field_16" id="field_16" value="<?php echo $FSESSION->get('field_16');?>" maxlength="60">
						</div>
					</div><br>
					<?php 
					}
			}
			?>
			<!-- end -->
			<?php
			if($cart->count_contents() > 8) 
			{
			?>
			<!-- start -->
					<?php
					if (tep_not_null(FIELD_17)) 
					{
					?>
					<div class="row">
					<div class="col-md-6">
					<strong><?php echo FIELD_17; ?>: </strong>
					</div>
					<div class="col-md-6">
						<?php 
						function tep_cfg_pull_down_agent17($id,$key='')
						{
						$name = (($key) ? 'configuration[' . $key . ']' : 'field17');
						$agent=array(
						array('id' => '','text' =>PLEASE_SELECT),
						array('id' => ID_17A,'text' =>TEXT_17A),
						array('id' => ID_17B,'text' =>TEXT_17B),
						array('id' => ID_17C,'text' =>TEXT_17C),
						);
						return tep_draw_pull_down_menu($name, $agent, $id);
						}
						echo tep_cfg_pull_down_agent17(', now())') ?>
					</div>
                    </div>
					<?php 
					}
					?>
					<?php 
					if (tep_not_null(FIELD_18)) 
					{
					?>
					<div class="row">
						<div class="col-md-6">
						<strong><?php echo FIELD_18; ?>: </strong>
						</div>
						<div class="col-md-6">
						<input type="text" size="40" name="field_18" id="field_18" value="<?php echo $FSESSION->get('field_18');?>" maxlength="60">
						</div>
					</div><br>
					<?php 
					}
			}
			?>
			<!-- end -->
	
			<?php
			if($cart->count_contents() > 9) 
			{
			?>
			<!-- start -->
					<?php
					if (tep_not_null(FIELD_19)) 
					{
					?>
					<div class="row">
					<div class="col-md-6">
					<strong><?php echo FIELD_19; ?>: </strong>
					</div>
					<div class="col-md-6">
						<?php 
						function tep_cfg_pull_down_agent19($id,$key='')
						{
						$name = (($key) ? 'configuration[' . $key . ']' : 'field_19');
						$agent=array(
						array('id' => '','text' =>PLEASE_SELECT),
						array('id' => ID_19A,'text' =>TEXT_19A),
						array('id' => ID_19B,'text' =>TEXT_19B),
						array('id' => ID_19C,'text' =>TEXT_19C),
						);
						return tep_draw_pull_down_menu($name, $agent, $id);
						}
						echo tep_cfg_pull_down_agent19(', now())') ?>
					</div>
                    </div>
					<?php 
					}
					?>
					<?php 
					if (tep_not_null(FIELD_20)) 
					{
					?>
					<div class="row">
						<div class="col-md-6">
						<strong><?php echo FIELD_20; ?>: </strong>
						</div>
						<div class="col-md-6">
						<input type="text" size="40" name="field_20" id="field_20" value="<?php echo $FSESSION->get('field_20');?>" maxlength="60">
						</div>
					</div><br>
					<?php 
					}
			}
			?>
			<!-- end -->			
		</div>
		<!--Add Extra Fields end-->