<?php
//Box Office Refund
defined('_FEXEC') or die();
if ($_SESSION['customer_country_id']==999) 
{
 if (!defined('BOX_HEADING_BOX_OFFICE')) 
 {
        define('BOX_HEADING_BOX_OFFICE', 'Box Office');
    }
    //check for a POST value on refund mode		  
    if (isset($_POST['box_office_switch'])) 
	{
        switch ($_POST['box_office_switch']) 
		{
            case 'yes':
                $_SESSION['box_office_refund'] = 'yes';
                $cart->reset(true);
                unset($_POST['box_office_switch']);
                break;
            case 'no':
                unset($_SESSION['box_office_refund']);
                $cart->reset(true);
                unset($_POST['box_office_switch']);
                break;
        }
    } 			
	//change style
    if (isset($_POST['box_office_refund_product'])) 
	{
?>
	<script type="text/javascript">
	    window.box_office_refund_product = '#<?php echo $_POST['box_office_refund_product']; ?>';
	</script>
<?php
	}
	 //check for post value on order_id
    if ($_POST['box_office_search'] == "" || ($_POST['box_office_search'] != '' && !is_numeric($_POST['box_office_search']))) 
	{
        $boxofficesearchresult = '';
    } else 
	{
        $customer_info_query = tep_db_query("select customers_id from " . TABLE_ORDERS . " where orders_id = '" . $_POST['box_office_search'] . "'");
        $customer_infoq      = tep_db_fetch_array($customer_info_query);
        if ($customer_infoq['customers_id'] != $FSESSION->customer_id) 
		{
            $boxofficesearchresult = ''.BO_ORDER_NUMBER.':' . $_POST['box_office_search'] . ' '.BO_NOT_PLACED;
        } else 
		{
            //search for the order details
            require_once(DIR_WS_CLASSES . 'order.php');
            $order = new order($_POST['box_office_search']);
            //get a seatplan - may be already running
            require_once(DIR_WS_CLASSES . 'seatplan.php');
            $sp_box = new seatplan;
            $boxofficesearchresult = '
			<div class="spBox">';
            for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) 
			{
                //get category
                $the_cpath = tep_get_product_path_refund($order->products[$i]['id']);
                if (tep_not_null($the_cpath)) 
				{
                    $the_cpath_array         = tep_parse_category_path($the_cpath);
                    $the_current_category_id = $the_cpath_array[0];
                } else {
                    $the_current_category_id = 0;
                }
                $boxofficesearchresult .= '<div class="ot">';
                //$boxofficesearchresult .= $order->products[$i]['concert_venue'] . ' ';
                $boxofficesearchresult .= $order->products[$i]['concert_date'] . ' ';
                $boxofficesearchresult .= $order->products[$i]['concert_time'] . ' ';
                $boxofficesearchresult .= $order->products[$i]['name'];
                $boxofficesearchresult .= '</div><div>';
				
                //check for REFUNDED in name
                if (!stristr($order->products[$i]['name'], REFUNDED)) 
				{
                    $boxofficesearchresult .= tep_draw_form('box_office_refund_redirect_' . $i, 'index.php?cPath=' . $the_current_category_id) . tep_draw_hidden_field('box_office_search', $_POST['box_office_search']) . tep_draw_hidden_field('box_office_refund_product', 's' . $order->products[$i]['id']) . "<span style=\"cursor:pointer\" onClick='document.forms[\"box_office_refund_redirect_$i\"].submit()'>&nbsp;<button class=\"btn btn-primary btn-sm\" style=\"padding:0\">". BO_LOCATE ."</button></span></form>";
                }
                $boxofficesearchresult .= '</div>';
            }
            $boxofficesearchresult .= '</div>';
						//is it a refunded order?
			if($order->info['status_id']==5)
			{
				$boxofficesearchresult = '<br />'.BO_ORDER_NUMBER.':' . $_POST['box_office_search'] . ' '. BO_REFUND_TYPE_ORDER; 
			}
        }
 
    }
	//$boxofficesearch = tep_draw_form('box_office_refund_query', basename($PHP_SELF) . '?' . tep_get_all_get_params($parameters)) . '<br />' . TEXT_ORDER_NUMBER . tep_draw_input_field('box_office_search') . "<span style=\"cursor:pointer\" onClick='document.forms[\"box_office_refund_query\"].submit()'>&nbsp;<strong>" . BO_SEARCH . "</strong></span></form>";
	
	$boxofficesearch = tep_draw_form('box_office_refund_query', basename($PHP_SELF) . '?' . tep_get_all_get_params($parameters)) . '' . TEXT_ORDER_NUMBER . tep_draw_input_field('box_office_search') . tep_template_image_search('', IMAGE_BUTTON_SEARCH);
	
	
	
	
	if (isset($_SESSION['box_office_refund'])) 
	{
         //$boxofficeboxcontent = tep_draw_form('box_office_refund_start', 'index.php?' . tep_get_all_get_params($parameters)) . tep_draw_hidden_field('box_office_switch', 'no') . "<span onClick='document.forms[\"box_office_refund_start\"].submit()'>&nbsp;<button class=\"btn btn-primary btn-sm\">" . TEXT_LEGEND_REFUND_CANCEL . ":</button></span></form>";
        $boxofficeboxcontent .= $boxofficesearchresult;
    } else 
	{
        //offer option to switch back out of refund mode (also do this at checkout_success)
        $boxofficeboxcontent = BO_FIND_SEATS ."";
    }
	
				echo '<div class="container-fluid">';
				echo '<div class="row">';
				echo '<div class="col-md-6">';
				echo $boxofficesearch;
				echo '</div>';
				echo '<div class="col-md-6">';
				echo $boxofficeboxcontent;
				echo '</div>';
				echo '</div>';
				echo '</div>';

				//echo '<br class="clearfloat">';
				
}else
{
}
//function
function tep_get_product_path_refund($products_id)
{
    $cPath = '';
    //same as the function for live products but we have removed p.products_status=1
    $category_query = tep_db_query("select p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = '" . (int) $products_id . "'  and p.products_id = p2c.products_id limit 1");
    if (tep_db_num_rows($category_query)) 
	{
        $category   = tep_db_fetch_array($category_query);
        $categories = array();
        tep_get_parent_categories($categories, $category['categories_id']);
        $categories = array_reverse($categories);
        $cPath      = implode('_', $categories);
        if (tep_not_null($cPath))
            $cPath .= '_';
        $cPath .= $category['categories_id'];
    }
    return $cPath;
}
?>