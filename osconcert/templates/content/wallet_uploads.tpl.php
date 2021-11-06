<?php 
/*
	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License 
*/	
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
?>
<div class="section-header">
<h2><?php echo HEADING_TITLE; ?></h2>
</div>    
	
	<table class="main-table">


<?php
  $upload_total = tep_count_wallet_uploads();

  if ($upload_total > 0) {
    
?>
          <table width="100%" cellpadding="2">
            <tr>
              <td class="main" width="20%" align="left"><?php echo '<b>' . TEXT_NAME . '</b>' ;?></td>
              <td class="main" width="20%" align="left"><?php echo '<b>' . TEXT_PAYMENT_DATE . '</b> '  ; ?></td>
			  <td class="main" width="25%" align="left"><?php echo '<b>' . TEXT_PAYMENT_METHOD . '</b>' ; ?></td>
              <td class="main" width="20%" align="left"><?php echo '<b>' . TEXT_PAYMENT_STATUS . '</b>' ; ?></td>
			  <td class="main" width="15%" align="left"><?php echo '<b>' . TEXT_WALLET_AMOUNT . '</b> ' ; ?></td>
            </tr>
          </table>
		<?php	
			$upload_query_raw = "select wu.*,c.customers_id,c.customers_firstname,c.customers_lastname,s.orders_status_id,s.orders_status_name from " . TABLE_CUSTOMERS . " c, " . TABLE_WALLET_UPLOADS . " wu, " . TABLE_ORDERS_STATUS . " s where c.customers_id = '" . (int)$FSESSION->customer_id . "' and c.customers_id = wu.customers_id and  s.orders_status_id =  wu.payment_status and s.language_id = '" . (int)$FSESSION->languages_id . "' order by wu.wallet_id desc";
			
		    $upload_split = new splitPageResults($upload_query_raw, MAX_DISPLAY_ORDER_HISTORY);
		    $upload_query = tep_db_query($upload_split->sql_query);
			while($upload = tep_db_fetch_array($upload_query)) 
			{  ?>
          <table align="center" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
            <tr class="infoBoxContents">
              <td>
			  
			  <table width="100%" cellspacing="2" cellpadding="4">
                <tr>
                  <td class="main" width="20%" align="left"><?php echo  $upload['customers_firstname'] . ' ' . $upload['customers_lastname'] ; ?></td>
                  <td class="main" width="20%" align="left"><?php echo  format_date($upload['payment_date'])  ; ?></td>
				  <td class="main" width="25%" align="left"><?php echo  $upload['payment_method']  ; ?></td>
                  <td class="main" width="20%" align="left"><?php echo  $upload['orders_status_name'] ; ?></td>
                  <td class="main" width="15%" align="right"><?php echo  $currencies->format($upload['amount'])  ; ?></td>
                </tr>
              </table>
			  
			  </td>
            </tr>
          </table>
          
<?php
    }
  } else {
?>
          <table width="100%" cellspacing="1" cellpadding="2" class="infoBox">
            <tr class="infoBoxContents">
              <td>
			  
			  <table width="100%" cellspacing="2" cellpadding="4">
                <tr>
                  <td class="main"><?php echo TEXT_NO_PURCHASES; ?></td>
                </tr>
              </table>
			  
			  </td>
            </tr>
          </table>
<?php
  }
?>
        </td>
      </tr>

<?php
  if ($upload_total > 0) {
?>
      <tr>
        <td>
		
		<table width="100%" cellpadding="2">
          <tr>
            <td class="smallText" valign="top"><?php echo $upload_split->display_count(TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
            <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $upload_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table>
		
		</td>
      </tr>
<?php
  }
?>

      <tr>
        <td>
		
		<table width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td>
			
			<table width="100%" cellpadding="2">
              <tr>
               
                <td><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
             
              </tr>
            </table>
			
			</td>
          </tr>
        </table>
		
		</td>
      </tr>
    </table>