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
<div>
<?php
// BOF: Lango Added for template MOD
$FSESSION->remove('box_office_reservation');
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
<h3><?php echo HEADING_TITLE; ?></h3>
<?php

}else{
$header_text = HEADING_TITLE;
}

?>


<?php
  if ($messageStack->size('borlisting') > 0) {
?>
      <div><?php echo $messageStack->output('borlisting'); ?></div>
<?php
  }
  //start edit section
  if (isset($_GET['edit']) && is_numeric($_GET['edit'])){
?>
  
   <h4><?php echo BOR_EDIT_TITLE. $_GET['edit']; ?></h4>
      
	  <table width="100%" cellspacing="1" cellpadding="2" class="infoBox" style="display:true">
          <tr class="">
            <td>
            <table width="100%" cellpadding="2">
<?php

// now load order info

	  $bor_query = tep_db_query("select * from " . TABLE_ORDERS . " 
									where bor_datetime > 0
									AND orders_id = '".$_GET['edit']."'
									AND orders_status = '".MODULE_PAYMENT_BOR_ORDER_STATUS_ID."'
									LIMIT 1");
		if (tep_db_num_rows($bor_query) == 0 ) { 
			echo "<tr><td>";
			echo BOR_NIL;
			echo "</td><tr></table></td></tr></table></div>";
		}else{
//create header row
     ?>
     <tr>
     	<td><?php echo BOR_ORDER; ?></td>
        <td><?php echo BOR_ORDER_PIN; ?></td>
        <td><?php echo BOR_EXPIRY; ?></td>
        <td><?php echo BOR_TICKETS; ?></td>
     </tr>
     
     <?php	

    
 	while ($bor_results = tep_db_fetch_array($bor_query)) {

?>
            <tr>
                
                <td valign="top">
                    <?php echo $bor_results[orders_id].' - <b>'.$bor_results[billing_name].'</b><br>'.$bor_results[billing_company]; ?>
                </td>
                <td valign="top">
               		 <?php echo $bor_results[bor_random_id]; ?>
                </td>
                <td valign="top">
                    <?php echo $bor_results[bor_datetime]; ?>
                </td>
                <td>
                </td>
                    
          </tr>
          <tr>
             <td colspan="3" valign="top"  >
             <?php //order comments here
			 				$comments = "ORDER COMMENTS:<br>";
							$sql3 = "
								SELECT 
									comments 
								FROM
									orders_status_history
								WHERE
									orders_id = '".$_GET['edit']."'
							    AND
								    TRIM(comments) <> ''
								ORDER BY
									orders_status_history_id ASC";
									
								$comments_query = tep_db_query($sql3); 
								
								;
								
		                        $comments_count = tep_db_num_rows($comments_query);
		                       if ($comments_count > 0 ){
       
	                                while($comments_result = tep_db_fetch_array($comments_query)){
									
										$comments .= $comments_result['comments'];
										$comments .=  '<br>';
										
										
									
									   }
									}else{
									  $comments = 'TEXT_NO ORDER COMMENTS';
									}
									
									echo $comments;
					?>
             
             
             </td>
             <td valign="top">
                 <?php    //////////////////////// tickets //////////////
		 $bor_tickets_query = tep_db_query("select * from " . TABLE_ORDERS_PRODUCTS . " 
									where orders_id = '".$_GET['edit']."'");
		if (tep_db_num_rows($bor_tickets_query) == 0 ) { 
		
				echo BOR_NIL_TICKETS;
			}else{?>
                     <script type="text/javascript">
						<!--
						function confirmation() {
							var answer = confirm("<?php echo BOR_CONFIRM_RESERVATIONS; ?>")
							if (answer){
								return true
							}
							else{
								return false
							}
						}
						//-->
						</script>

            <form action="checkout_process_reservation.php" method="post" id = "bor" name = "bor" onsubmit = "return confirmation();">
            <input type="hidden" name = "orders_id" value = "<?php echo $_GET['edit'];?>" />
            <table>
            	<tr>
                	<td></td>
                    <td align="center"><?php echo BOR_CONFIRM; ?></td><td> </td>
                    <td align="center"><?php echo BOR_RESTOCK; ?></td>
                </tr>
              <?php 	while ($bor_ticket_results = tep_db_fetch_array($bor_tickets_query)) {?>
                  <tr>
                  	<td valign="top"><?php echo $bor_ticket_results ['categories_name']
				              .' '
					          .$bor_ticket_results ['products_name']
							  .'<br><span style ="font-size: 10px">'
							  .$bor_ticket_results ['concert_date']
							  .' '
							  .$bor_ticket_results ['concert_time']
							  .'</span>';
						?>
				    </td>
                    <td valign="top" align="center">
                     <input type="checkbox" id = "chk1<?php echo $bor_ticket_results['orders_products_id'];?>" name="confirm_list[]" checked = "checked" 
                      	value="<?php echo $bor_ticket_results['orders_products_id'];?>"
                        onclick="javascript:document.getElementById('chk2<?php echo $bor_ticket_results['orders_products_id'];?>').checked =!document.getElementById('chk1<?php echo $bor_ticket_results['orders_products_id'];?>').checked;"
                        >
					</td><td> </td>
                    <td valign="top" align="center">
                      <input type="checkbox" id = "chk2<?php echo $bor_ticket_results['orders_products_id'];?>" name="restock_list[]" 
                        value="<?php echo $bor_ticket_results['orders_products_id'];?>"
                        onclick="javascript:document.getElementById('chk1<?php echo $bor_ticket_results['orders_products_id'];?>').checked =! document.getElementById('chk2<?php echo $bor_ticket_results['orders_products_id'];?>').checked;">
					</td>
                  </tr>			 
			<?php } ?>
               </table>

<input  type="submit" class="btn btn-primary" value="<?php echo TEXT_SUBMIT; ?>" />
</form>
            <?php }	 ?>

                    </td>
                  </tr>


<?php
  }
?>
            </table>
           </td>
          
          
          </tr>
        </table>

 </div>
  
<?php  
  }
  }else{
  
  //end edit section
?>
      <h4><?php echo BOR_TITLE; ?></h4>
      
	  <table width="100%" cellspacing="1" cellpadding="2" class="infoBox" style="display:true">
          <tr class="">
            <td>
            <table width="100%" cellpadding="2">
<?php

// now load current orders
		if(BOR_AGENT_ONLY=='true'){
			$agent_only="AND customers_id = '" . $FSESSION->get("customer_id") . "'";
		}else{
			$agent_only="";
		}
		

	  $bor_query = tep_db_query("select * from " . TABLE_ORDERS . " 
									where bor_datetime > 0
									AND orders_status = '".MODULE_PAYMENT_BOR_ORDER_STATUS_ID."' $agent_only");
		if (tep_db_num_rows($bor_query) == 0 ) { 
		
			echo "<tr><td>";
			echo BOR_NIL;
			echo "</td><tr></table></div>";
		}else{
//create header row
     ?>
     <tr>
     	<td><?php echo BOR_ORDER; ?></td>
        <td><?php echo BOR_ORDER_PIN; ?></td>
        <td><?php echo BOR_EXPIRY; ?></td>
        <td><?php echo BOR_SELECT; ?></td>
     </tr>
     
     <?php	}




 	while ($bor_results = tep_db_fetch_array($bor_query)) {

?>
              <tr>
                
                <td>
                    <?php echo $bor_results[orders_id].' - <b>'.$bor_results[billing_name].'</b>'; ?>
                </td>
                <td>
               		 <?php echo $bor_results[bor_random_id]; ?>
                </td>
                <td>
                    <?php echo $bor_results[bor_datetime]; ?>
                </td>
                    <td >
					   <?php echo '<a href="' . tep_href_link('bor_listings.php', 'edit=' . $bor_results[orders_id], 'SSL') . '">' .   tep_template_image_button_basic('small_edit.gif', SMALL_IMAGE_BUTTON_EDIT) . '</a>';
                            
                        ?>
                    </td>
                  </tr>


<?php
  }
?>
            </table></td>
          </tr>
        </table>
		

 </div>
 
 <?php // end main table display
 }
 ?>