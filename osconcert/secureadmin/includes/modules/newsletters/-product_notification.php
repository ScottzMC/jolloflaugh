<?php
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
/*

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  
  

  Released under the GNU General Public License
  
  Freeway eCommerce from ZacWare
http://www.openfreeway.org

Copyright 2007 ZacWare Pty. Ltd
*/

  class product_notification {
    var $show_choose_audience, $title, $content;

    function product_notification($title, $content) {
      $this->show_choose_audience = true;
      $this->title = $title;
      $this->content = $content;
    }

    function choose_audience($status='') {
      global $languages_id,$FREQUEST,$FSESSION;
	  
	     $page=$FREQUEST->getvalue('page');
		 $nID=$FREQUEST->getvalue('nID');


      $products_array = array();
      $products_query = tep_db_query("select pd.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.language_id = '" . (int)$FSESSION->languages_id . "' and pd.products_id = p.products_id and p.products_status = '1' order by pd.products_name");
      while ($products = tep_db_fetch_array($products_query)) {
        $products_array[] = array('id' => $products['products_id'],
                                  'text' => $products['products_name']);
      }

$choose_audience_string = '<script language="javascript"><!--
function mover(move) {
  if (move == \'remove\') {
    for (x=0; x<(document.notifications.products.length); x++) {
      if (document.notifications.products.options[x].selected) {
        with(document.notifications.elements[\'chosen[]\']) {
          options[options.length] = new Option(document.notifications.products.options[x].text,document.notifications.products.options[x].value);
        }
        document.notifications.products.options[x] = null;
        x = -1;
      }
    }
  }
  if (move == \'add\') {
    for (x=0; x<(document.notifications.elements[\'chosen[]\'].length); x++) {
      if (document.notifications.elements[\'chosen[]\'].options[x].selected) {
        with(document.notifications.products) {
          options[options.length] = new Option(document.notifications.elements[\'chosen[]\'].options[x].text,document.notifications.elements[\'chosen[]\'].options[x].value);
        }
        document.notifications.elements[\'chosen[]\'].options[x] = null;
        x = -1;
      }
    }
  }
  return true;
}

function selectAll(FormName, SelectBox) {
  temp = "document." + FormName + ".elements[\'" + SelectBox + "\']";
  Source = eval(temp);

  for (x=0; x<(Source.length); x++) {
    Source.options[x].selected = "true";
  }

  if (x<1) {
    alert(\'' . JS_PLEASE_SELECT_PRODUCTS . '\');
    return false;
  } else {
    return true;
  }
}
//--></script>';
$global_button=''; $cancel_button=''; $choose_audience_string='';

      $global_button = '<script language="javascript"><!--' . "\n" .
                       'document.write(\'<input type="button" value="' . BUTTON_GLOBAL . '" style="width: 8em;" onclick="document.location=\\\'' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $page . '&nID=' . $nID . '&action=confirm&global=true') . '\\\'">\');' . "\n" .
                       '//--></script><noscript><a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $page . '&nID=' . $nID . '&action=confirm&global=true') . '">[ ' . BUTTON_GLOBAL . ' ]</a></noscript>';
	  if($status!='ajax'){
      $cancel_button = '<script language="javascript"><!--' . "\n" .
                       'document.write(\'<input type="button" value="' . BUTTON_CANCEL . '" style="width: 8em;" onclick="document.location=\\\'' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $page . '&nID=' . $nID) . '\\\'">\');' . "\n" .
                       '//--></script><noscript><a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $page . '&nID=' . $nID) . '">[ ' . BUTTON_CANCEL . ' ]</a></noscript>';
	  }
	  if($status=='ajax'){
      $choose_audience_string .= '<form name="notifications" action="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $page . '&nID=' . $nID . '&action=confirm') . '" method="post" onSubmit="return selectAll(\'notifications\', \'chosen[]\')"><table border="0" width="100%" cellspacing="0" cellpadding="2">' . "\n" .
                                 '  <tr>' . "\n" .
                                 '    <td align="center" class="main"><b>' . TEXT_PRODUCTS . '</b><br>' . tep_draw_pull_down_menu('products', $products_array, '', 'size="20" style="width: 20em;" multiple') . '</td>' . "\n" .
                                 '    <td align="center" class="main">&nbsp;<br>' . $global_button . '<br><br><br><input type="button" value="' . BUTTON_SELECT . '" style="width: 8em;" onClick="mover(\'remove\');"><br><br><input type="button" value="' . BUTTON_UNSELECT . '" style="width: 8em;" onClick="mover(\'add\');"><br><br><br>' . $cancel_button . '</td>' . "\n" .
                                 '    <td align="center" class="main"><b>' . TEXT_SELECTED_PRODUCTS . '</b><br>' . tep_draw_pull_down_menu('chosen[]', array(), '', 'size="20" style="width: 20em;" multiple') . '</td>' . "\n" .
                                 '  </tr>' . "\n" .
                                 '</table></form>';
	  } else {
	  $choose_audience_string .= '<form name="notifications" action="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $page . '&nID=' . $nID . '&action=confirm') . '" method="post" onSubmit="return selectAll(\'notifications\', \'chosen[]\')"><table border="0" width="100%" cellspacing="0" cellpadding="2">' . "\n" .
                                 '  <tr>' . "\n" .
                                 '    <td align="center" class="main"><b>' . TEXT_PRODUCTS . '</b><br>' . tep_draw_pull_down_menu('products', $products_array, '', 'size="20" style="width: 20em;" multiple') . '</td>' . "\n" .
                                 '    <td align="center" class="main">&nbsp;<br>' . $global_button . '<br><br><br><input type="button" value="' . BUTTON_SELECT . '" style="width: 8em;" onClick="mover(\'remove\');"><br><br><input type="button" value="' . BUTTON_UNSELECT . '" style="width: 8em;" onClick="mover(\'add\');"><br><br><br><input type="submit" value="' . BUTTON_SUBMIT . '" style="width: 8em;"><br><br>' . $cancel_button . '</td>' . "\n" .
                                 '    <td align="center" class="main"><b>' . TEXT_SELECTED_PRODUCTS . '</b><br>' . tep_draw_pull_down_menu('chosen[]', array(), '', 'size="20" style="width: 20em;" multiple') . '</td>' . "\n" .
                                 '  </tr>' . "\n" .
                                 '</table></form>';
	  }

      return $choose_audience_string;
    }

    function confirm() {
      global $FREQUEST;
	  
	   $page=$FREQUEST->getvalue('page');
	   $nID=$FREQUEST->getvalue('nID');
   	  $global=$FREQUEST->getvalue('global');

      $audience = array();

      if ($global!='' && $global== 'true') {
        $products_query = tep_db_query("select distinct customers_id from " . TABLE_PRODUCTS_NOTIFICATIONS);
        while ($products = tep_db_fetch_array($products_query)) {
          $audience[$products['customers_id']] = '1';
        }

        $customers_query = tep_db_query("select customers_info_id from " . TABLE_CUSTOMERS_INFO . " where global_product_notifications = '1'");
        while ($customers = tep_db_fetch_array($customers_query)) {
          $audience[$customers['customers_info_id']] = '1';
        }
      } else { 
        $chosen = $FREQUEST->postvalue('chosen');

        $ids = implode(',', $chosen);

        $products_query = tep_db_query("select distinct customers_id from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id in (" . tep_db_input($ids) . ")");
        while ($products = tep_db_fetch_array($products_query)) {
          $audience[$products['customers_id']] = '1';
        }

        $customers_query = tep_db_query("select customers_info_id from " . TABLE_CUSTOMERS_INFO . " where global_product_notifications = '1'");
        while ($customers = tep_db_fetch_array($customers_query)) {
          $audience[$customers['customers_info_id']] = '1';
        }
      }

      $confirm_string = '<table border="0" cellspacing="0" cellpadding="2">' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main"><font color="#ff0000"><b>' . sprintf(TEXT_COUNT_CUSTOMERS, sizeof($audience)) . '</b></font></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main"><b>' . $this->title . '</b></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main"><tt>' . nl2br($this->content) . '</tt></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . tep_draw_form('confirm', FILENAME_NEWSLETTERS, 'page=' . $page . '&nID=' . $nID . '&action=confirm_send') . "\n" .
                        '    <td align="right">';
      if (sizeof($audience) > 0) {
        if ($global!='' && ($global == 'true')) {
          $confirm_string .= tep_draw_hidden_field('global', 'true');
        } else {
          for ($i = 0, $n = sizeof($chosen); $i < $n; $i++) {
            $confirm_string .= tep_draw_hidden_field('chosen[]', $chosen[$i]);
          }
        }
        $confirm_string .= tep_image_submit('button_send.gif', IMAGE_SEND) . ' ';
      }
      $confirm_string .= '<a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $page . '&nID=' . $nID . '&action=send') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a> <a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $page . '&nID=' . $nID) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a></td>' . "\n" .
                         '  </tr>' . "\n" .
                         '</table>';

      return $confirm_string;
    }

    function send($newsletter_id) {
      global $FREQUEST;

      $audience = array();
		$global=$FREQUEST->postvalue('global');
      if ($global!='' && ($global == 'true')) {
        $products_query = tep_db_query("select distinct pn.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address from " . TABLE_CUSTOMERS . " c, " . TABLE_PRODUCTS_NOTIFICATIONS . " pn where c.customers_id = pn.customers_id");
        while ($products = tep_db_fetch_array($products_query)) {
          $audience[$products['customers_id']] = array('firstname' => $products['customers_firstname'],
                                                       'lastname' => $products['customers_lastname'],
                                                       'email_address' => $products['customers_email_address']);
        }

        $customers_query = tep_db_query("select c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address from " . TABLE_CUSTOMERS . " c, " . TABLE_CUSTOMERS_INFO . " ci where c.customers_id = ci.customers_info_id and ci.global_product_notifications = '1'");
        while ($customers = tep_db_fetch_array($customers_query)) {
          $audience[$customers['customers_id']] = array('firstname' => $customers['customers_firstname'],
                                                        'lastname' => $customers['customers_lastname'],
                                                        'email_address' => $customers['customers_email_address']);
        }
      } else { global $FREQUEST;
        
		//	$chosen[]=array();

		$chosen[]=$FREQUEST->getvalue('chosen');

        $ids = implode(',', $chosen);

        $products_query = tep_db_query("select distinct pn.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address from " . TABLE_CUSTOMERS . " c, " . TABLE_PRODUCTS_NOTIFICATIONS . " pn where c.customers_id = pn.customers_id and pn.products_id in (" . tep_db_input($ids) . ")");
        while ($products = tep_db_fetch_array($products_query)) {
          $audience[$products['customers_id']] = array('firstname' => $products['customers_firstname'],
                                                       'lastname' => $products['customers_lastname'],
                                                       'email_address' => $products['customers_email_address']);
        }

        $customers_query = tep_db_query("select c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address from " . TABLE_CUSTOMERS . " c, " . TABLE_CUSTOMERS_INFO . " ci where c.customers_id = ci.customers_info_id and ci.global_product_notifications = '1'");
        while ($customers = tep_db_fetch_array($customers_query)) {
          $audience[$customers['customers_id']] = array('firstname' => $customers['customers_firstname'],
                                                        'lastname' => $customers['customers_lastname'],
                                                        'email_address' => $customers['customers_email_address']);
        }
      }

      $mimemessage = new email(array('X-Mailer: osCommerce bulk mailer'));

// MaxiDVD Added Line For WYSIWYG HTML Area: BOF (Send TEXT Product Notifications v1.7 when WYSIWYG Disabled)
      if (HTML_AREA_WYSIWYG_DISABLE_NEWSLETTER == 'Disable') {
      $mimemessage->add_text($this->content);
      } else {
      $mimemessage->add_html($this->content);
// MaxiDVD Added Line For WYSIWYG HTML Area: EOF (Send TEXT Product Notifications v1.7 when WYSIWYG Enabled)
      }
      
      $mimemessage->build_message();

      reset($audience);
      while (list($key, $value) = each ($audience)) {
        $mimemessage->send($value['firstname'] . ' ' . $value['lastname'], $value['email_address'], '', EMAIL_FROM, $this->title);
      }

      $newsletter_id = tep_db_prepare_input($newsletter_id);
      tep_db_query("update " . TABLE_NEWSLETTERS . " set date_sent = now(), status = '1' where newsletters_id = '" . tep_db_input($newsletter_id) . "'");
    }
  }
?>
