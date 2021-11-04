<?php

/*

 osCommerce, Open Source E-Commerce Solutions 
http://www.oscommerce.com 

Copyright (c) 2003 osCommerce 

 

Released under the GNU General Public License

Freeway eCommerce
http://www.openfreeway.org
Copyright (c) 2007 ZacWare
*/
// Set flag that this is a parent file
	define( '_FEXEC', 1 );
  require('includes/application_top.php');
  require(DIR_WS_CLASSES . 'currencies.php');
  require(DIR_FS_ADMIN . 'includes/languages/'. $FSESSION->language .'/admin_letters.php');
  $server_date = getServerDate(true);	
  $currencies = new currencies();
  
 // $query=tep_db_query('select content from ')
  
  $command=$FREQUEST->getvalue('command');
  $page=$FREQUEST->getvalue('page');
  $oID=$FREQUEST->getvalue('oID');
  $id=$FREQUEST->getvalue('id');

	
 if($command!=''){
     if($command=='preview'){
	 $coupon_id=$FREQUEST->postvalue('coupon_id');
	 		 if ((!$FREQUEST->postvalue('customers_email_address')) && (!$FREQUEST->postvalue('email_to')) ) {
			  	   echo ERROR_NO_CUSTOMER_SELECTED;
				  exit;
  				}

				
			switch ($FREQUEST->postvalue('customers_email_address')) {
			  case '***':
			  	$customer_name = TEXT_ALL_CUSTOMERS;
				$mail_sent_to = TEXT_ALL_CUSTOMERS;
				break;
			  case '**D':
			  	$customer_tname = TEXT_NEWSLETTER_CUSTOMERS;
				$mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
				break;
			  default:
				$mail_sent_to = $FREQUEST->postvalue('customers_email_address');
				$customers_array=array();
				$customers_query=tep_db_query("select customers_id,customers_firstname,customers_lastname from ".TABLE_CUSTOMERS." where customers_email_address like '".tep_db_input($FREQUEST->postvalue('customers_email_address'))."'");
				if(tep_db_num_rows($customers_query)){
					$customers_array=tep_db_fetch_array($customers_query);
					$customers_id=$customers_array['customers_id'];
					$coupon_user_per_user_query=tep_db_query("select uses_per_user,coupon_flag from ".TABLE_COUPONS." where coupon_id='".tep_db_input($coupon_id)."'");
					$coupon_user_per_user_array=tep_db_fetch_array($coupon_user_per_user_query);
					
					$coupon_discount_query=tep_db_query("select count(*) as total from ".TABLE_COUPON_DISCOUNT_EMAIL." where customer_id='".tep_db_input($customers_id) ."' and coupon_id='" .tep_db_input($coupon_id) ."'");
					$coupon_discount_array=tep_db_fetch_array($coupon_discount_query);					
					$coupon_redeem_query=tep_db_query("select count(*) as total from ".TABLE_COUPON_REDEEM_TRACK." where customer_id='".tep_db_input($customers_id). "' and coupon_id='". tep_db_input($coupon_id) ."'");
					$coupon_redeem_array=tep_db_fetch_array($coupon_redeem_query);

					if($coupon_user_per_user_array['coupon_flag']=='C' || $coupon_user_per_user_array['coupon_flag']=='N' || $coupon_user_per_user_array['uses_per_user']<($coupon_redeem_array['total']+$coupon_discount_array['total']) ){
					echo 'Customer_Coupon_Exceed^^';
					exit;
					}
					
					
					$customer_name=addslashes($customers_array['customers_firstname']).'&nbsp;'.addslashes($customers_array['customers_lastname']);
					
				}
				if ($FREQUEST->postvalue('email_to')) {
				  $mail_sent_to = $FREQUEST->postvalue('email_to');
				}
				break;
			}
echo 'preview';

$coupon_code = create_coupon_code($FREQUEST->postvalue('email_to')); $coupon_amount=0;
	$coupon_amount_query=tep_db_query("select coupon_amount from ".TABLE_COUPONS." where coupon_id='".(int)$coupon_id."'");
	if($coupon_amount_array=tep_db_fetch_array($coupon_amount_query)){
		$coupon_amount=$coupon_amount_array['coupon_amount'];
	}
$store_url = HTTP_SERVER  . DIR_WS_CATALOG . 'gv_redeem.php' . '?gv_no=xxxxxx';
$search_array=array('%%Customer Name%%','%%Store Name%%','%%Coupon Value%%','%%Store Owner%%','%%Store Address%%','%%Store URL%%','%%Coupon Code%%');
$replace_array=array($customer_name,STORE_NAME,$currencies->format($coupon_amount),STORE_OWNER,nl2br(STORE_NAME_ADDRESS),$store_url,'xxxxxx');
?>
          <tr>
            <td>
				<table border="0" width="100%" cellpadding="0" cellspacing="2">
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_CUSTOMER; ?></b><br><?php echo $mail_sent_to; ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_FROM; ?></b><br><?php echo htmlspecialchars(stripslashes($FREQUEST->postvalue('from'))); ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_SUBJECT; ?></b><br><?php echo htmlspecialchars(stripslashes($FREQUEST->postvalue('subject'))) ; ?> </td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php if (HTML_AREA_WYSIWYG_DISABLE_EMAIL == 'Enable') { echo str_replace($search_array,$replace_array,$FREQUEST->postvalue('message')); } else { echo str_replace($search_array,$replace_array,$FREQUEST->postvalue('message')); } ?></b></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); 

				 ?></td>
              </tr>
              <tr>
                <td>
<?php
/* Re-Post all POST'ed variables */
    reset($FPOST);
   // while (list($key, $value) = each($FPOST)) {
		foreach($FPOST as $key => $value)
		{
		//FOREACH
      if (!is_array($FREQUEST->postvalue($key))) 
	  {
        echo tep_draw_hidden_field($key.'_h', htmlspecialchars(stripslashes($value)));
      }
    }
?>
                <table border="0" width="100%" cellpadding="0" cellspacing="2">
                  <tr>
                    <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_GV_MAIL,'page=' . $page . '&id=' . $id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a> ' .'<a href="javascript:send_mail()">' . tep_image_button('button_send_email.gif', IMAGE_SEND_EMAIL) .'</a>'; ?></td>
                    </tr>
                    <td class="smallText">
                <?php if (HTML_AREA_WYSIWYG_DISABLE_EMAIL == 'Disable'){echo tep_image_button('button_back.gif', IMAGE_BACK, 'name="back"');
                } ?><?php if (HTML_AREA_WYSIWYG_DISABLE_EMAIL == 'Disable') {echo(TEXT_EMAIL_BUTTON_HTML);
                 } else { 
				 			 echo TEXT_EMAIL_BUTTON_TEXT ; 
						} ?>
                    </td>
                  </tr>
                </table></td>
             </tr>
            </table></td>
          </tr>
<?php
	 }else if($command=='send_email_to_user'){
		$coupon_id=$FREQUEST->postvalue('coupon_id_h');
	 	echo 'mail_sent';
		switch ($FREQUEST->postvalue('customers_email_address_h')) {
		  case '***':
			$mail_query = tep_db_query("select customers_id,customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS);
			$mail_sent_to = TEXT_ALL_CUSTOMERS;
			tep_db_query("update ".TABLE_COUPONS." set coupon_flag='C' where coupon_id='".tep_db_input($coupon_id)."'");
			//tep_db_query("update ".TABLE_COUPONS." set uses_per_user='-1' where coupon_id='$coupon_id'");
			break;
		  case '**D':
			$mail_query = tep_db_query("select customers_id,customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_newsletter = '1'");
			$mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
			tep_db_query("update ".TABLE_COUPONS." set coupon_flag='N' where coupon_id='".tep_db_input($coupon_id)."'");
			//tep_db_query("update ".TABLE_COUPONS." set uses_per_user='-1' where coupon_id='$coupon_id'");
			break;
		  default:
		  	tep_db_query("update ".TABLE_COUPONS." set coupon_flag='U' where coupon_id='".tep_db_input($coupon_id)."'");
			$customers_email_address = $FREQUEST->postvalue('customers_email_address_h');
			$mail_query = tep_db_query("select customers_id,customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($customers_email_address) . "'");
			$mail_sent_to = $FREQUEST->postvalue('customers_email_address_h');
			
			if ($FREQUEST->postvalue('email_to_h')) {
			  $mail_sent_to = $FREQUEST->postvalue('email_to_h');
			}
			break;
		}
			echo $mail_sent_to;
			$from = $FREQUEST->postvalue('from_h');
			$subject = $FREQUEST->postvalue('subject_h');

			while ($mail = tep_db_fetch_array($mail_query)) { 
			  $customers_id=$mail['customers_id'];
			  $coupon_code = create_coupon_code($mail['customers_email_address']);
			  // ticket 16
			  $customer_name=addslashes($mail['customers_firstname']). '&nbsp;' . addslashes($mail['customers_lastname']);
			  
			  $coupon_check_query=tep_db_query("select count(*) as total from ".TABLE_COUPON_DISCOUNT_EMAIL." where customer_id='".tep_db_input($customers_id)."' and coupon_id='".tep_db_input($coupon_id) . "'");
			  $coupon_check_array=tep_db_fetch_array($coupon_check_query);
			  
			 // if($coupon_check_array['total']<2){
			  $coupon_amount=0; $coupon_tax_class_id=0;
			  $coupon_query=tep_db_query("select coupon_amount,coupon_tax_class_id from ".TABLE_COUPONS." where coupon_id='".tep_db_input($coupon_id)."'");
			  if($coupon_array=tep_db_fetch_array($coupon_query)){
			  $coupon_amount=$coupon_array['coupon_amount'];
			  $coupon_tax_class_id=$coupon_array['coupon_tax_class_id'];
			  }
			  

			  $message = $FREQUEST->postvalue('message_h');
			  $message_format = $FREQUEST->postvalue('message_format_h');

			  if (SEARCH_ENGINE_FRIENDLY_URLS == 'true')
			  		$store_url ='<a href="' .HTTP_SERVER  . DIR_WS_CATALOG . 'gv_redeem.php' . '?gv_no=xxxxxx' . '">' .  HTTP_SERVER  . DIR_WS_CATALOG . 'gv_redeem.php' . '/gv_no,'.$coupon_code . '</a>';
			  else
			  		$store_url ='<a href="' .HTTP_SERVER  . DIR_WS_CATALOG . 'gv_redeem.php' . '?gv_no=xxxxxx' . '">'  .  HTTP_SERVER  . DIR_WS_CATALOG . 'gv_redeem.php' . '?gv_no='.$coupon_code . '</a>';
			  $search_array=array('%%Customer Name%%','%%Store Name%%','%%Coupon Value%%','%%Store Owner%%','%%Store Address%%','%%Store URL%%','%%Coupon Code%%');
			  $replace_array=array($customer_name,STORE_NAME,$currencies->format($coupon_amount),STORE_OWNER,nl2br(STORE_NAME_ADDRESS),$store_url,$coupon_code);
			  $message = str_replace($search_array,$replace_array,$message);
			  //added
				$message_text=strip_tags($details['html_text'],'<br>');
				$message_text=str_replace(array('<br />','<br>','<BR>','<BR />','<br/>','<BR/>'),chr(13). chr(10),$message);
				
				//print_r ($message);

			  //Let's build a message object using the email class
			  $mimemessage = new email(array('X-Mailer: osCommerce bulk mailer'));
			  // add the message to the object
			  //added
			  if($message_format!='T')
				  $mimemessage->add_html($message,$message_text);
				 else 
			  $mimemessage->add_text($message);
			  
			  $mimemessage->build_message();

			  $mimemessage->send(addslashes($mail['customers_firstname']) . ' ' . addslashes($mail['customers_lastname']), $mail['customers_email_address'], '', $from, $subject);
			  
			  
			  tep_db_query("insert into ".TABLE_COUPON_DISCOUNT_EMAIL." (coupon_id,customer_id,discount_coupon_code,date_sent,amount,tax,content) values('$coupon_id','$customers_id','$coupon_code','$server_date','$coupon_amount','$coupon_tax_class_id','$message') ");
			  
			  //} //  Coupon Check Condition
			}
			

			 }else if($command=='tbl_write'){
			 		echo 'tbl_write';
					table_write();
			 }
	 
	 exit;
 }

  if ($FREQUEST->getvalue('mail_sent_to')) {
    $messageStack->add(sprintf(NOTICE_EMAIL_SENT_TO, $FREQUEST->getvalue('mail_sent_to')), 'notice');
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/http.js"></script>
<script type="text/javascript" src="htmlarea/htmlarea.js"></script>
<script type="text/javascript" src="htmlarea/editor.js"></script>
</head>
<script language="JavaScript">
<?php 
$coupon_id=$FREQUEST->getvalue('id'); 
if($coupon_id==''){
	echo 'location.href="'.tep_href_link(FILENAME_DISCOUNT_COUPONS).'";';
}else{
	$uses_per_coupon=0; $customers_use_coupon=0; $coupon_redeem_total=0;
	$coupon_use_query=tep_db_query("select uses_per_coupon,coupon_flag from ".TABLE_COUPONS." where coupon_id='".tep_db_input($coupon_id)."'");
	if($coupon_use_array=tep_db_fetch_array($coupon_use_query))
		$uses_per_coupon=$coupon_use_array['uses_per_coupon'];
	$customers_use_coupon_query=tep_db_query("select count(*) as total from ".TABLE_COUPON_DISCOUNT_EMAIL." where coupon_id='" .tep_db_input($coupon_id) ."'");
	if($customers_use_coupon_array=tep_db_fetch_array($customers_use_coupon_query))
		$customers_use_coupon=$customers_use_coupon_array['total'];
		$coupon_redeem_total_query=tep_db_query("select count(*) as total from ".TABLE_COUPON_REDEEM_TRACK." where coupon_id='".tep_db_input($id)."'");
	if($coupon_redeem_total_array=tep_db_fetch_array($coupon_redeem_total_query))
		$coupon_redeem_total=$coupon_redeem_total_array['total'];

}
?>

<!-- Begin
       function init() {
define('customers_email_address', 'string', 'Customer or Newsletter Group');
} <!-- start of validaton.js -->
// Generic Form Validation
// Jacob Hage (jacob@hage.dk)
var checkObjects        = new Array();
var errors                = "";
var returnVal                = false;
var language                = new Array();
language["header"]        = ""
language["start"]        = "Please Select a ";
language["field"]        = "";
language["require"]        = " from the drop down list to proceed.";
language["min"]                = " and must consist of at least ";
language["max"]                = " and must not contain more than ";
language["minmax"]        = " and no more than ";
language["chars"]        = " characters";
language["num"]                = " and must contain a number";
language["email"]        = " must contain a valid e-mail address";
// -----------------------------------------------------------------------------
// define - Call this function in the beginning of the page. I.e. onLoad.
// n = name of the input field (Required)
// type= string, num, email (Required)
// min = the value must have at least [min] characters (Optional)
// max = the value must have maximum [max] characters (Optional)
// d = (Optional)
// -----------------------------------------------------------------------------
function define(n, type, HTMLname, min, max, d) { 
var p;
var i;
var x;
if (!d) d = document;
if ((p=n.indexOf("?"))>0&&parent.frames.length) {
d = parent.frames[n.substring(p+1)].document;
n = n.substring(0,p);
}
if (!(x = d[n]) && d.all) x = d.all[n];
for (i = 0; !x && i < d.forms.length; i++) {
x = d.forms[i][n];
}
for (i = 0; !x && d.layers && i < d.layers.length; i++) {
x = define(n, type, HTMLname, min, max, d.layers[i].document);
return x;
}
eval("V_"+n+" = new formResult(x, type, HTMLname, min, max);");
checkObjects[eval(checkObjects.length)] = eval("V_"+n);
}
function formResult(form, type, HTMLname, min, max) {
this.form = form;
this.type = type;
this.HTMLname = HTMLname;
this.min  = min;
this.max  = max;
}
function validate() {  
if (checkObjects.length > 0) {
errorObject = "";
for (i = 0; i < checkObjects.length; i++) {
validateObject = new Object();
validateObject.form = checkObjects[i].form;
validateObject.HTMLname = checkObjects[i].HTMLname;
validateObject.val = checkObjects[i].form.value;
validateObject.len = checkObjects[i].form.value.length;
validateObject.min = checkObjects[i].min;
validateObject.max = checkObjects[i].max;
validateObject.type = checkObjects[i].type;
if (validateObject.type == "num" || validateObject.type == "string") {
if ((validateObject.type == "num" && validateObject.len <= 0) || (validateObject.type == "num" && isNaN(validateObject.val))) { errors += language['start'] + language['field'] + validateObject.HTMLname + language['require'] + language['num'] + "\n";
} else if (validateObject.min && validateObject.max && (validateObject.len < validateObject.min || validateObject.len > validateObject.max)) { errors += language['start'] + language['field'] + validateObject.HTMLname + language['require'] + language['min'] + validateObject.min + language['minmax'] + validateObject.max+language['chars'] + "\n";
} else if (validateObject.min && !validateObject.max && (validateObject.len < validateObject.min)) { errors += language['start'] + language['field'] + validateObject.HTMLname + language['require'] + language['min'] + validateObject.min + language['chars'] + "\n";
} else if (validateObject.max && !validateObject.min &&(validateObject.len > validateObject.max)) { errors += language['start'] + language['field'] + validateObject.HTMLname + language['require'] + language['max'] + validateObject.max + language['chars'] + "\n";
} else if (!validateObject.min && !validateObject.max && validateObject.len <= 0) { errors += language['start'] + language['field'] + validateObject.HTMLname + language['require'] + "\n";
   }
} else if(validateObject.type == "email") {
// Checking existense of "@" and ".".
// Length of must >= 5 and the "." must
// not directly precede or follow the "@"
if ((validateObject.val.indexOf("@") == -1) || (validateObject.val.charAt(0) == ".") || (validateObject.val.charAt(0) == "@") || (validateObject.len < 6) || (validateObject.val.indexOf(".") == -1) || (validateObject.val.charAt(validateObject.val.indexOf("@")+1) == ".") || (validateObject.val.charAt(validateObject.val.indexOf("@")-1) == ".")) { errors += language['start'] + language['field'] + validateObject.HTMLname + language['email'] + "\n"; }
      }
   }
}
if (errors) {
alert(language["header"].concat("\n" + errors));
errors = "";
returnVal = false;
} else {
document.getElementById('message').value=document.getElementById('if_message').contentWindow.document.body.innerHTML;
command="";
command="<?php echo tep_href_link(FILENAME_GV_MAIL,'command=preview');?>" + '&page=' + '<?php echo $FREQUEST->getvalue('page');?>' + '&id=' + '<?php echo $FREQUEST->getvalue('id');?>';
//alert(document.getElementById('customers_email_address').value);
do_post_command('mail',command);

//returnVal = true;
   }
}

function send_mail(){
command="";
command="<?php echo tep_href_link(FILENAME_GV_MAIL,'command=send_email_to_user');?>"+ '&page=' + '<?php echo $page;?>' + '&id=' + '<?php echo $id;?>';
//alert (command);
do_post_command('mail',command);

}

function do_result(result){
document.getElementById('error_tag').style.display='none';
document.getElementById('error_message').innerHTML='';
	var token=result.split('^^');
	if(token[0]=='Customer_Coupon_Exceed'){
	document.getElementById("error_message").innerHTML='<?php echo TEXT_CUSTOMER_COUPON_EXCEEDS; ?>';	
	document.getElementById('error_tag').style.display='';
	}

		if(result.substr(0,7)=='preview'){
			if(document.getElementById('preview')) {
					result = result.substr(7);
					if(result.indexOf("#")!= -1){ 
						var error;
						error = result.substr(1,35);
						result=result.substr(35);
						document.getElementById('error').innerHTML=error;
						document.getElementById('error').style.display='';
						document.getElementById('error').className='main';
						document.getElementById('preview').innerHTML=result;
						document.getElementById('preview').style.display='';
						document.getElementById('phase1').style.display='none';
					}else {
						document.getElementById('preview').innerHTML=result;
						document.getElementById('preview').style.display='';
						document.getElementById('phase1').style.display='none';
					}
			}
		} 
		else  if(result.substr(0,9)=='mail_sent') { 
				/*document.getElementById('sent_to').value = result.substr(9);
				command ="<?php echo tep_href_link(FILENAME_GV_MAIL,'command=tbl_write');?>"+ '&page=' + '<?php echo $page;?>' + '&id=' + '<?php echo $id;?>';
				do_get_command(command);*/
				location.href="<?php echo tep_href_link(FILENAME_DISCOUNT_COUPONS,'coupon_id='.$id); ?>";
		}
		else if(result.substr(0,9)=='tbl_write'){
			if(document.getElementById('phase1')) {
					document.getElementById('phase1').innerHTML='';
					document.getElementById('phase1').innerHTML=result.substr(9);
					document.getElementById('phase1').style.display='';
					document.getElementById('preview').style.display='none';
					document.getElementById('error').innerHTML="<?php echo 'Notice: Email sent to: '; ?>"+ document.getElementById('sent_to').value;
					document.getElementById('error').className='main';
					document.getElementById('error').style.display='';
					initEditor('message');
					init();
				}
		}else if(result.substr(0,5)=='Error'){
		 document.getElementById('error').innerHTML = result;
		 document.getElementById('error').className='main';
		 }
}
	function coupon_warning(){
	if(document.mail.customers_email_address.value=='***' || document.mail.customers_email_address.value=='**D')
		document.getElementById('coupon_warning').style.display='';
	else
		document.getElementById('coupon_warning').style.display='none';
	}

//  End -->
</script>
</head>
<body OnLoad="init()" marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->


<?php echo tep_draw_form('mail', FILENAME_GV_MAIL, 'action=preview').tep_draw_hidden_field('coupon_id',$coupon_id); ?>
<table border="0" width="100%" cellspacing="2" cellpadding="2">
<tr style="display:none;padding:10px;" id="error_tag"><td><table width="100%" border="0" cellpacing="0" cellpadding="2" class="formArea">
<tr><td class="main" id="error_message" style="color:#FF0000;"></td></tr>
 </table></td>
</tr>
  <tr> 
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
	  <tr><td id="error"></td>
	  <tr><td id="preview"></td> </tr>
	 
      <tr>
	  <input type="hidden" id="sent_to">
        <td id="phase1"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if (($FREQUEST->getvalue('action') == 'preview') && ($FREQUEST->postvalue('customers_email_address') || $FREQUEST->postvalue('email_to')) ) {} else { echo table_write(); }
  
  function table_write(){
  global $page,$id,$uses_per_coupon,$customers_use_coupon,$FREQUEST;
?>	<table width="100%" border="0" cellpadding="0" cellspacing="2">
          <tr>
            <td><table border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
<?php
    $customers = array();
    $customers[] = array('id' => '', 'text' => TEXT_SELECT_CUSTOMER);
    $customers[] = array('id' => '***', 'text' => TEXT_ALL_CUSTOMERS);
    $customers[] = array('id' => '**D', 'text' => TEXT_NEWSLETTER_CUSTOMERS);
    $mail_query = tep_db_query("select customers_email_address, customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " order by customers_lastname");
    while($customers_values = tep_db_fetch_array($mail_query)) {
      $customers[] = array('id' => $customers_values['customers_email_address'],
                           'text' => $customers_values['customers_lastname'] . ', ' . $customers_values['customers_firstname'] . ' (' . $customers_values['customers_email_address'] . ')');
    }
$email_query=tep_db_query("select message_subject,message_text,message_format from ".TABLE_EMAIL_MESSAGES." where message_type like 'SGV'");
$email_array=array();
	if(tep_db_num_rows($email_query)>0)
		$email_array=tep_db_fetch_array($email_query);

?>
              <tr>
                <td class="main"><?php echo TEXT_CUSTOMER; ?></td>
                <td><?php echo tep_draw_pull_down_menu('customers_email_address', $customers, $FREQUEST->getvalue('customer'),'onChange="javascript: coupon_warning();"');?>&nbsp;&nbsp;<span id="coupon_warning" class="smallText" style="color:#FF0000;display:none">Coupon Warning</span></td>
				<td>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
             <tr>
                <td class="main"><?php echo TEXT_FROM; ?></td>
                <td colspan="2"><?php echo tep_draw_input_field('from', EMAIL_FROM); ?></td>
              </tr>
              <tr>
                <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo TEXT_SUBJECT; ?></td>
                <td><?php echo tep_draw_input_field('subject',$email_array['message_subject']); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
              <td valign="top" class="main"><?php echo TEXT_MESSAGE; ?></td>
              <td><?php echo tep_draw_textarea_field('message', 'soft', '80', '24',$email_array['message_text'],'id="message"'); ?></td>
			  <td style="background:ButtonFace;" valign="bottom" align="center">
			<?php 
			$array_result=array();
			$array_result=array( array('id'=>'%%Customer Name%%','text'=>'Customer Name'),
								  array('id'=>'%%Coupon Code%%','text'=>'Coupon code'),
								  array('id'=>'%%Coupon Value%%','text'=>'Coupon Value'),
								  array('id'=>'%%Store Name%%','text'=>'Store Name'),
								  array('id'=>'%%Store Address%%','text'=>'Store Address'),
								  array('id'=>'%%Store Owner%%','text'=>'Store Owner'),
								  array('id'=>'','text'=>'Store URL link'));
			  	$format_array=array(array('id'=>'T','text'=>TEXT_FORMAT_TEXT),
							array('id'=>'H','text'=>TEXT_FORMAT_HTML),
							array('id'=>'B','text'=>TEXT_FORMAT_BOTH)
							);
					  
			echo "<div class='main'><b>" . TEXT_MERGE_FIELDS . '</b></div><br>';
			echo tep_draw_pull_down_menu('fields',$array_result,'','style="height:' .((strpos($_SERVER['HTTP_USER_AGENT'],"MSIE")>0)?'225':'241').'" size=15 ondblClick="AddField()"');
			?><script>initEditor('message');</script>
			</td>
              </tr>
              <tr>
                <td class="main"><?php echo TEXT_MESSAGE_FORMAT; ?></td>
                <td><?php echo tep_draw_pull_down_menu('message_format', $format_array, $email_array['message_format']);?></td>
				<td>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
			  	<td align="left"><?php echo '<a href="'. tep_href_link(FILENAME_DISCOUNT_COUPONS,'page=' . $page . '&id=' . $id ). '">' . tep_image_submit('button_back.gif',IMAGE_BACK) . '</a>';?></td>
                 <td align="right">
                 <?php 
				 	//if($uses_per_coupon > $customers_use_coupon){
						if (HTML_AREA_WYSIWYG_DISABLE_EMAIL == 'Enable'){ 
						echo tep_image_submit('button_preview.gif', IMAGE_SEND_EMAIL, 'onClick="validate();return returnVal;"');
						} else {
						echo tep_image_submit('button_preview.gif', IMAGE_SEND_EMAIL, 'onClick="validate();return returnVal;"'); 
						}
					//}
				?>
                </td>
              </tr>
            </table></td>
         </tr></table>
<?php
  }
?>
<!-- body_text_eof //-->
        </table></td>
      </tr>
    </table></td>
  </tr>
</table></form>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

<script language="javascript">
	function AddField(){ 
	 var select_field;
	 var select_id;
	
	 if (document.mail.fields.selectedIndex<=-1) return;
	 select_id=document.mail.fields.options[document.mail.fields.selectedIndex].value;

	 
//	 if (select_id.length>3) return;
	
	 select_field="%%" + document.mail.fields.options[document.mail.fields.selectedIndex].innerHTML + "%%";
	 select_field=select_field.replace("&nbsp;&nbsp;","");
	 
     editor.insertHTML(select_field);
	}
	<?php if($coupon_user_array['coupon_flag']=='U' &&  $uses_per_coupon < ($customers_use_coupon+$coupon_redeem_total)){ ?>
	document.getElementById("error_message").innerHTML='<?php echo TEXT_USERS_COUPON_EXCEEDS; ?>';	
	document.getElementById('error_tag').style.display='';
	<?php } ?>
</script>
