<?php
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();

$error = false;
  if ($FREQUEST->getvalue('action') == 'send') 
  {
    $name = tep_db_prepare_input($FREQUEST->postvalue('name'));
    $email_address = tep_db_prepare_input($FREQUEST->postvalue('email'));
    $enquiry = tep_db_prepare_input($FREQUEST->postvalue('enquiry'));

       tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, EMAIL_SUBJECT, $enquiry, $name, $email_address);
       tep_redirect(tep_href_link(FILENAME_DEFAULT, 'action=success', 'SSL'));
  }

	echo tep_draw_form('contact_us', tep_href_link(FILENAME_DEFAULT, 'action=send')); ?>

    <div class="section-header">
	<h2><?php echo HEADING_CONTACT; ?></h2>
	</div>

<?php
  if ($messageStack->size('contact') > 0) 
  {
?>
   <div><?php echo $messageStack->output('contact'); ?></div>
<?php
  }

  if (($FREQUEST->getvalue('action') == 'success')) 
  {
?>
<section id="contact">
<div class="container">
	<div class="row">
		<div class="col-md-12">
		<?php echo TEXT_SUCCESS; ?>
		</div>
	</div>
</div>
</section>
</form>
<?php
  } else {
?>
<?php   // cms_contact starting

$address =str_replace("\n","<br>",STORE_NAME_ADDRESS);
$query = "select * from email_messages where message_type = 'HCF'";
$query = tep_db_query($query);
$result = tep_db_fetch_array($query);
$name = tep_draw_input_field('name','',' required placeholder="'.TEXT_NAME.'"').'<br>';
$mail = tep_draw_input_field('email', '',' required placeholder="'.TEXT_EMAIL.'"'). '<br>';
$enquiry = tep_draw_textarea_field('enquiry', 'soft', 0, 0,0,' required placeholder="'.TEXT_MESSAGE.'"');
$continue = tep_template_image_submit('', IMAGE_BUTTON_SEND);
$reset = tep_template_image_button_reset('', IMAGE_BUTTON_RESET);
$firstname = '<b>'.ENTRY_NAME.'</b>';
$lastname = '<b>'.ENTRY_LASTNAME.'</b>';
$message = '<b>'.ENTRY_MESSAGE.'</b>';
$email = '<b>'.ENTRY_EMAIL.'</b>';
//$replace_array = array('%%Name_Box%%'=>$name,'%%Email_Box%%'=>$mail,'%%Message_Box%%'=>$enquiry,'%%Continue_Button%%'=>$continue,'%%Reset_Button%%'=>$reset);
$replace_array = array(TEXT_TN1=>$name,TEXT_TN2=>$mail,TEXT_TN3=>$enquiry,TEXT_BT1=>$continue,TEXT_BT2=>$reset,TEXT_TN4=>$subject,TEXT_TM=>$message,TEXT_FN=>$firstname,TEXT_LN=>$lastname,TEXT_EM=>$email);
$body='<body>';
$body1='</body>';
//$messages=html_entity_decode($result['message_text']);
if(strpos($messages,html_entity_decode($body),0)!==false)
{
	$pos1=strpos($messages,html_entity_decode($body),0)+6;
	$messages=substr($messages,$pos1);
	$pos2=strpos($messages,html_entity_decode($body1),$pos1);
	$messages=substr($messages,0,$pos2);
} else $messages=$result['message_text'];

foreach($replace_array as $key=>$value)
{ 
	$messages = str_replace('%%'.$key.'%%',$value,$messages);
}
?>     
<?php echo $messages; ?>

<?php
  }
?>