<?php
/*		Seatplan AJAX for osConcert		*/
/*	2011 by Martin Zeitler, Germany	*/
/*																	*/
/*							*/

/* hook osCommerce */
define( '_FEXEC', 1 );
require('includes/application_top.php');
		//March 2013 a new function
		function tep_check_carts_united($customers_id){
		global $cart, $FSESSION,$new_box;
		$force_refresh='no';
		$new_box='';
		     //there are three sql queries here that could be rolled into one but, for the moment laid out so that the flow is clear
			//get duplicates from the carts_united table
				$sql = "select cu.products_id from carts_united cu, products p
						where cu.products_id=p.products_id
						and p.product_type='P'
						group by cu.products_id 
						having count(cu.products_id) > 1
						order by cu.products_id asc";
			   $result = tep_db_query($sql);
			   
			
			//now you have the results - any duplicates product_ids are here 
			//now a while() loop on each of these - select all records by date asc, skip the first one using LIMIT
			while($result_string = tep_db_fetch_array($result)){
					$sql2= "SELECT customers_id  
					FROM carts_united  
					WHERE products_id='".$result_string['products_id']."' 
					ORDER BY customers_basket_date_added ASC 
					LIMIT 1,999999";
							$result2 = tep_db_query($sql2);
								   
								while($result_string2 = tep_db_fetch_array($result2)){		
								//so there you now have the customer_ids for the duplicate products MINUS the first one - so delete any for this customer - if you delete any for other customers then their cart session will not change so THEY CAN STILL BUY!
								{
								//delete from cust baskets
								//this customer
			
			if(isset($_SESSION['customer_id']) && $_SESSION['customer_id']==$result_string2['customers_id']){
				tep_db_query("DELETE FROM ".TABLE_CUSTOMERS_BASKET." where customers_id = ".(int)$_SESSION['customer_id']." AND products_id = ".$result_string['products_id']);
				$cid = $_SESSION['customer_id'];
				$tmp = '';
				if(tep_db_affected_rows()>0){
				$cart->remove($result_string['products_id']);
				$force_refresh='yes';}
			}
			elseif( $FSESSION->ID ==$result_string2['customers_id']) {
				tep_db_query("DELETE FROM ".TABLE_CUSTOMERS_TEMP_BASKET." where customers_id = '" . tep_db_input($FSESSION->ID) . "' AND products_id = ".$result_string['products_id']);
				$cid = 0;
				$tmp = ' temp';
				if(tep_db_affected_rows()>0){
				$cart->remove($result_string['products_id']);
				$force_refresh='yes';}
			}
								
}
								
	}
	}
				//refresh
			if($force_refresh=='yes'){
				require_once(DIR_WS_CLASSES . 'ajax_cart.php');
	            $ajaxCart = new ajaxCart;
				$new_box=$ajaxCart->getCart('html');
					
			}
			return $new_box;		
	}
/* get a handle */
require(DIR_WS_CLASSES.'seatplan.php');
$sp = new seatplan;

switch($_GET['mode']){
	
	case 'free':					header('Content-type: application/json; Charset=utf8;');
	case 'free_ie':			    	$arr = $sp->tep_getAvailableSeatsPerShow($_GET['cPath']);
												echo json_encode($arr);
												break;
		
	case 'load':					header('Content-type: application/json; Charset=utf8;');
	case 'load_ie':				$arr = array();
												$arr['sold'] = $sp->tep_getSoldSeatsPerShow($_GET['cPath']);
											
												/* if logged in */
												if($_SESSION['customer_id']){
											    	
													$arr1 = $sp->tep_getForeignCarts($_SESSION['customer_id'],$_GET['cPath']);
													$arr2 = $sp->tep_getForeignTempCarts($_GET['cPath']);
													$arr['lock'] = array_merge($arr1,$arr2);
													$arr['cart'] = $sp->tep_getOwnCart($_SESSION['customer_id']);
													$arr['prev'] = $sp->tep_getSoldSeatsPerCustomer($_GET['cPath'],$_SESSION['customer_id']);
													$arr['shopping_box']=tep_check_carts_united($_SESSION['customer_id']);//graeme
													
												}
												else {
													/* if not logged in */
													
													$arr1 = $sp->tep_getForeignCarts(0,$_GET['cPath']);
													$arr2 = $sp->tep_getForeignTempCarts($_GET['cPath']);
													$arr['lock'] = array_merge($arr1,$arr2);
													$arr['cart'] = $sp->tep_getTempCart($FSESSION->ID);
													$arr['prev'] = array();													
													$arr['shopping_box']=tep_check_carts_united($FSESSION->ID);//graeme
												}
												echo json_encode($arr);
												break;
	
	case 'terminate':			header('Content-type: application/json; Charset=utf8;');
	case 'terminate_ie':	/* if logged in */
												if($_SESSION['customer_id']){
													$arr = $sp->tep_clearCart($_SESSION['customer_id'],$_GET['cPath']);
												}
												else {
													/* if not logged in */
													$arr = $sp->tep_clearTempCart($_GET['cPath']);
												}
												echo json_encode($arr);
												break;
	
	case 'add_seat':			header('Content-type: application/json; Charset=utf8;');
	case 'add_seat_ie':		$arr = $sp->tep_addSeat((int)$_GET['products_id'],(int)$_GET['cPath'],(int)$_GET['products_orig_price']);
												echo json_encode($arr);
												break;
												
	case 'show_seat':			header('Content-type: application/json; Charset=utf8;');
	case 'show_seat_ie':		$arr = $sp->tep_showSeat((int)$_GET['products_id'],(int)$_GET['cPath'],(int)$_GET['products_orig_price']);
												echo json_encode($arr);
												break;											
	
	case 'remove_seat':		header('Content-type: application/json; Charset=utf8;');
	case 'remove_seat_ie':$arr = $sp->tep_removeSeat((int)$_GET['products_id'],(int)$_GET['cPath']);
												echo json_encode($arr);
												break;
	
	case 'ga':						header('Content-type: application/json; Charset=utf8;');
	case 'ga_ie':					$arr = $sp->tep_general_admission((int)$_GET['products_id'],(int)$_GET['quantity'],(int)$_GET['cPath'],(int)$_GET['discount_id']);
												echo json_encode($arr);
												break;
	//bix office refund
	case 'ga_refund':						header('Content-type: application/json; Charset=utf8;');
	case 'ga_refund_ie':					$arr = $sp->tep_general_admission_refund((int)$_GET['products_id'],(int)$_GET['quantity'],(int)$_GET['cPath'],(int)$_GET['discount_id'],(int)$_GET['order_id']);
												echo json_encode($arr);
												break;
	####### march 2013 live discounts
		case 'live_discount':					header('Content-type: application/json; Charset=utf8;');
	    case 'live_discount_ie':				 $arr = $sp->tep_live_discount((int)$_GET['products_id'],(int)$_GET['discount_id'],(int)$_GET['cPath'],(int)$_GET['new_price']);
												echo json_encode($arr);
												break;
	
	
												
		case 'block_indiv':					header('Content-type: application/json; Charset=utf8;');
	    case 'block_indiv_ie':				    $arr = $sp->tep_block_indiv($_GET['cPath'], $_GET['name']);
												echo json_encode($arr);
												break;
												
		case 'unblock_indiv':					header('Content-type: application/json; Charset=utf8;');
	    case 'unblock_indiv_ie':				$arr = $sp->tep_unblock_indiv($_GET['cPath'], $_GET['name']);
												echo json_encode($arr);
												break;
												
		case 'load_block':					header('Content-type: application/json; Charset=utf8;');
	    case 'load_block_ie':				    $arr = $sp->tep_renderSeatplanBlock($_GET['cPath']);
												echo json_encode($arr);
												break;												
	default:							header('Content-type: application/json; charset=utf8;');
	tep_check_carts_united($_SESSION['customer_id']);
	tep_check_carts_united($FSESSION->ID);
												$arr = array('error' => $_GET['mode']);
												echo json_encode($arr);
}
?>