<?php
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
/*
	
	The AJAX Seatplan Class for osConcert
	
	Copyright 2011-2012 by Martin T. Zeitler, Germany

	
*/

/*  MODIFIED /includes/classes/seatplan.php
	Changes by John Donovan - DCP Partners, LLC
	License: GNU General Public License - NO WARRANTY OR SUPPORT PROVIDED 
	Initial Version: 04 Mar 2013  
	Provides functionality for 'RESTRICT TO GROUPS' seat definitions.

	Current Limitations/Requirements:
	1) One or more "Restrict to Groups" Customer Groups are selected for each seat 'Product'.
	   I've defined a Customer Group "VIP SEATING" for this purpose. DO NOT use the 'Default' 
	   Customer Group that all new Customers are associated with - as this defeats the purpose.
	2) "Restrict to Customers" is not presently implemented.
	3) Select the appropriate "Customer Group" in the Customer record. Only a single Group may be selected.
	4) Users not logged-in, or logged-in users that are not associated with the corresponding Customer Group
	   will see seats associated with a "Restrict to Group" restriction as "gray", the label will include the 
	   seat number and "RESTRICTED", rather than the price, and the seat may not be selected for the cart basket.
	5) Logged-in users that *are* associated with a Customer Group selected in the seat's "Restrict to Group" list
	   will see the seat in its normal condition, the label will include the price, and the seat may be placed in the
	   cart basket.
	6) Seats that have been sold (products_status = 0) will be unavailable and the label will indicate "SOLD".
	7) In order to produce a seat map with the proper (Price, 'RESTRICTED', 'SOLD') labels, Seat Caching **MUST** be 
	   turned-off using the "Seat Plan Cache" under ShopSettings->Advanced->osConcert Settings. Seats will be restricted
	   from selection - but the less-descriptive label will be provided.
	   
	   
	   					// case 'Y':
						// /* line wrap */
						// if ($i != 0) {
							// $html .= "</ul>\n";
						// } //$i != 0
						// $html .= "<ul class='r ";
						  // //if the seat has been tagged with products_status = 6 (hidden), add 'hidden' class  -JMD 08-26-2013
						  // $html .= ($seat['products_status'] == 6 ? "hidden" : "");
						// $html .= "'>";
						// $i++;
						// break;
	
/*
	Functions modified:
	- tep_getSoldSeatsPerShow() 
	- tep_renderSeatplan()
	Functions added to the class:
	- tep_getCustomerGroupID() - returns the Customer Group ID associated with the currently logged-in user 
*/

if(!class_exists('seatplan')){
	class seatplan {
		
		function tep_getOwnCart($customers_id=1){
			$sql = "SELECT * FROM ".TABLE_CUSTOMERS_BASKET." WHERE customers_id = ".(int)$customers_id;
				$result = tep_db_query($sql);
			$arr = array();
			while($product = tep_db_fetch_array($result)){$arr[] = $product['products_id'];
			 
			 }
			tep_db_free_result($result);
			return $arr;
		} /* end: tep_getOwnCart() */
		
		function tep_clearCart($customers_id,$cPath){
			global $FSESSION, $cart;
			
			$cart->reset();
			tep_db_query("DELETE FROM ".TABLE_CUSTOMERS_BASKET." WHERE customers_id = ".(int)$customers_id);
			
			/* log an event - if live logging is enabled in options */
			if(SEATPLAN_LOGGING=='true'){$log = $this->tep_logSeatPlanEvent(1,(int)$cPath,0,(int)$customers_id,$FSESSION->ID,'cart timeout');}else{$log = 'disabled';}
			return array(
				'status' => 'terminated',
				'log' => $log
			);
		} /* end: tep_clearCart() */
		
		
		function tep_getForeignCarts($customers_id,$parent_id){
			$sql = "
				SELECT
					".TABLE_CUSTOMERS_BASKET.".products_id
				FROM
					".TABLE_CUSTOMERS_BASKET."
				LEFT JOIN
					".TABLE_PRODUCTS."
				ON
					".TABLE_CUSTOMERS_BASKET.".products_id = ".TABLE_PRODUCTS.".products_id
				WHERE
					".TABLE_CUSTOMERS_BASKET.".customers_id NOT LIKE ".(int)$customers_id."
				AND
				".TABLE_PRODUCTS.".parent_id = ".(int)$parent_id;
			$result = tep_db_query($sql);$arr = array();
			while($product = tep_db_fetch_array($result)){$arr[] = $product['products_id'];}
			tep_db_free_result($result);
			return $arr;
		} /* end: tep_getForeignCarts() */
		
		
		function tep_getTempCart($id){
			$sql = "SELECT * FROM ".TABLE_CUSTOMERS_TEMP_BASKET." WHERE customers_id = '".$id."'";
			$result = tep_db_query($sql);$arr = array();
			while($product = tep_db_fetch_array($result)){$arr[] = $product['products_id'];}
			tep_db_free_result($result);
			return $arr;
		} /* end: tep_getTempCart() */
		
		
		function tep_clearTempCart($cPath){
			global $FSESSION, $cart;
			tep_db_query("DELETE FROM ".TABLE_CUSTOMERS_TEMP_BASKET." WHERE customers_id = '".$FSESSION->ID."'");
			tep_db_query("DELETE FROM ".TABLE_SESSIONS." where sesskey = '".$FSESSION->ID."'");
			
			/* log an event if live-logging is enabled */
			if(SEATPLAN_LOGGING=='true'){$log = $this->tep_logSeatPlanEvent(1,(int)$cPath,0,0,$FSESSION->ID,'temp cart timeout');}else{$log = 'disabled';}
			
			$cart->reset();
			session_destroy();
			return array(
				'status' => 'terminated',
				'log' => $log
			);
		} /* end: tep_clearTempCart() */

		function tep_getForeignTempCarts($parent_id){
			
			/* if logged in: retrieve all the temp carts */
			if($_SESSION['customer_id']){
				$sql ="
					SELECT
						".TABLE_PRODUCTS.".products_id
					FROM ".
						TABLE_CUSTOMERS_TEMP_BASKET."
					LEFT JOIN
						".TABLE_PRODUCTS."
					ON
						".TABLE_PRODUCTS.".products_id = ".TABLE_CUSTOMERS_TEMP_BASKET.".products_id
					WHERE
						".TABLE_PRODUCTS.".parent_id = ".(int)$parent_id;
			}
			/* not logged in: retrieve only foreign temp-carts */
			else {
				global $FSESSION;
				$sql ="
					SELECT
						".TABLE_PRODUCTS.".products_id
					FROM ".
						TABLE_CUSTOMERS_TEMP_BASKET."
					LEFT JOIN
						".TABLE_PRODUCTS."
					ON
						".TABLE_PRODUCTS.".products_id = ".TABLE_CUSTOMERS_TEMP_BASKET.".products_id
					WHERE
						".TABLE_PRODUCTS.".parent_id = ".(int)$parent_id."
					AND
						".TABLE_CUSTOMERS_TEMP_BASKET.".customers_id NOT LIKE '" .$FSESSION->ID."'";
			}
			
			$result = tep_db_query($sql);$arr = array();
			while($product = tep_db_fetch_array($result)){$arr[] = $product['products_id'];}
			tep_db_free_result($result);
			return $arr;
		} /* end: tep_getForeignTempCarts() */


		//MODIFIED FUNCTION  
		// DCP Partners LLC  -jmd 04Mar2013
		function  tep_getSoldSeatsPerShow($parent_id)
		{	
			//include (as a 'sold seat') those seats that are restricted to a group
			// or have products_status = 0
			//
			$sql    = "
					SELECT
						products_id,
						restrict_to_groups,
						products_status
					FROM
						" . TABLE_PRODUCTS . "
					WHERE
						restrict_to_groups > ''
					OR
						products_status = 0
					AND
						product_type = 'P'
					AND
						parent_id = " . (int) $parent_id;
			$result = tep_db_query($sql);
			$arr    = array();

			//get the customerGroup for this customer - returns false if not logged-in
			$customerGroup = $this->tep_getCustomerGroupID();  

			while ($product = tep_db_fetch_array($result)) {
				//then while traversing the result set of 'likely' sold seats

				//create the array of 'restrict_to_groups' for this seat
				$arryRestrictToGroups = explode(',', $product['restrict_to_groups']);  

				//if no customerGroup defined ~or~ the customer's Group is NOT in the permitted RestrictToGroups array
				//if (!$customerGroup || !in_array($customerGroup, $arryRestrictToGroups) ) {  
				if (!$customerGroup || !in_array($customerGroup, $arryRestrictToGroups ) ||  $product['products_status'] == 0){ 
					//add it to the array of sold seats
					$arr[] = $product['products_id'];
				}

			} //$product = tep_db_fetch_array($result)
			tep_db_free_result($result);
			return $arr;
		}
		/* end: tep_getSoldSeatsPerShow() */

		// NEW FUNCTION  
		// DCP Partners LLC  -jmd 04Mar2013
		// returns the Customer Group ID associated with the currently logged-in user
		// returns BOOL false if no user logged-in or query failed

		function tep_getCustomerGroupID()
		{
			//first check to ensure that the customer is logged-in
			if (isset($_SESSION['customer_id'])) 
			{

			$query = "SELECT customers_groups_id 
						FROM customers
						WHERE customers_id = '".$_SESSION['customer_id']."'
						LIMIT 1";  // should only be one defined for this customer

				//if the query works		
				if ($result = tep_db_query($query)) {
					//get the result row
					$row = tep_db_fetch_array($result);
					//since we only need a single row, free the result ... just a good idea
					tep_db_free_result($result);
					//return the Customer Group ID
					return $row['customers_groups_id'];
				}
				return false; // no result	
			}
			else {
				return false; //not logged-in
			}
		}  // tep_getCustomerGroupID()

		function tep_checkLocks($product_id){
			$bool = false;
			$result = tep_db_query("SELECT * FROM carts_united WHERE products_id = ".(int)$product_id);
			$check = tep_db_fetch_array($result);
			if($check['products_id']){$bool = true;}
			tep_db_free_result($result);
			return $bool;
		} /* end: tep_checkLocks() */

		/* purging expired sessions & carts */
		function tep_purgeOldCarts(){			

		// we should have a customer session id in the basket table and, in the temp_basket table it is held by the customer_id field
$purge_query = tep_db_query("select * from " . TABLE_SESSIONS . " where expiry < '" . (time()) . "'"); 
	while ($purge_results = tep_db_fetch_array($purge_query)){
				
		tep_db_query("delete from " . TABLE_CUSTOMERS_TEMP_BASKET . " where customers_id = '" . $purge_results['sesskey'] . "'");
		tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where session_id = '" . $purge_results['sesskey'] . "'");
	
		tep_db_query("delete from " . TABLE_SESSIONS . " where sesskey = '" . $purge_results['sesskey'] . "'");

	}		
	
	   //2017 what about the odd instance where there is an entry in the baskets table but no session?
	   //this appears to occur at odd time????
	   
	   $purge_query = tep_db_query("SELECT * FROM " . TABLE_CUSTOMERS_BASKET . " WHERE session_id NOT IN (SELECT sesskey FROM " . TABLE_SESSIONS . ")"); 
	while ($purge_results = tep_db_fetch_array($purge_query)){
		tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where session_id = '" . $purge_results['session_id'] . "'");
	}	
	
	$purge_query = tep_db_query("SELECT * FROM " . TABLE_CUSTOMERS_TEMP_BASKET . " WHERE customers_id NOT IN (SELECT sesskey FROM " . TABLE_SESSIONS . ")"); 
	while ($purge_results = tep_db_fetch_array($purge_query)){
				
		tep_db_query("delete from " . TABLE_CUSTOMERS_TEMP_BASKET . " where customers_id = '" . $purge_results['customers_id'] . "'");
		}	

			return true;
		} /* end: tep_purgeOldCarts() */

		function tep_getSoldSeatsPerCustomer($parent_id, $customer_id){
				$sql = "
				SELECT DISTINCT
					".TABLE_PRODUCTS.".products_id,
					".TABLE_PRODUCTS.".section_id,
					".TABLE_PRODUCTS.".parent_id,
					".TABLE_PRODUCTS.".product_type,
					".TABLE_PRODUCTS.".products_status,
					".TABLE_PRODUCTS.".products_model,
					".TABLE_ORDERS.".customers_id
				FROM
					".TABLE_PRODUCTS."
				LEFT JOIN
					".TABLE_ORDERS_PRODUCTS."
				ON
					".TABLE_ORDERS_PRODUCTS.".products_id = ".TABLE_PRODUCTS.".products_id
				AND
									  
				    ".TABLE_ORDERS_PRODUCTS.".products_model = ".TABLE_PRODUCTS.".products_model
				
				LEFT JOIN
					".TABLE_ORDERS."
				ON
					".TABLE_ORDERS.".orders_id = ".TABLE_ORDERS_PRODUCTS.".orders_id
				LEFT JOIN
					".TABLE_CATEGORIES."
				ON
					".TABLE_CATEGORIES.".categories_id = ".$parent_id."
				WHERE
					".TABLE_PRODUCTS.".products_status = 0
				AND
					".TABLE_PRODUCTS.".product_type = 'P'
				AND
					".TABLE_ORDERS_PRODUCTS.".products_quantity > 0
				AND
					".TABLE_PRODUCTS.".parent_id = ".(int)$parent_id."
				AND
					".TABLE_ORDERS.".customers_id = ".(int)$customer_id;
			
				
			$result = tep_db_query($sql);$arr = array();
			while($product = tep_db_fetch_array($result)){$arr[] = $product['products_id'];}
			tep_db_free_result($result);
			return $arr;
		} /* end: tep_getSoldSeatsPerCustomer() */

		/* this function is only triggered once per minute, per client */
		function tep_getAvailableSeatsPerShow($parent_id){
			
			/* perform some housekeeping */
			$this->tep_purgeOldCarts((int)SEATPLAN_TIMEOUT);
			
			$sql = "
				SELECT
					".TABLE_PRODUCTS.".products_id
				FROM
					".TABLE_PRODUCTS."
				WHERE
					".TABLE_PRODUCTS.".products_status = 1
				AND
					".TABLE_PRODUCTS.".product_type = 'P'
				AND
					".TABLE_PRODUCTS.".parent_id = ".(int)$parent_id;
				
			$result = tep_db_query($sql);
			$arr1 = array();$arr2 = array();
			while($product = tep_db_fetch_array($result)){$arr1[] = $product['products_id'];}
			
			/* important: check if the seat exists in any cart */
			foreach($arr1 as $seat => $product_id){if(!$this->tep_checkLocks($product_id)){$arr2[] = $product_id;}}
			tep_db_free_result($result);
			return array('free' => $arr2);
		} /* end: tep_getAvailableSeatsPerShow() */
		
		function  tep_renderSeatplanCSS($cPath)
		{
					global $FSESSION;
					
					$sql    = "SELECT bg_height from categories WHERE categories_id=" . $cPath . "";
					$result = tep_db_query($sql); 
			
					while ($bg = tep_db_fetch_array($result)) 
					{
						$bgh=$bg['bg_height'];
					}
					
					echo '<style>';
					$sql    = "

					SELECT DISTINCT
						p.products_status,
						p.color_code,
						p.products_id,
						p.product_type,
						p.products_x,
						p.products_y,
						p.products_w,
						p.products_h,
						p.products_r,
						p.products_sx,
						p.products_sy

					FROM " . TABLE_PRODUCTS . " p

					JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd
					ON
						pd.products_id = p.products_id
					WHERE
						p.product_type='P'
					AND
						p.parent_id = '" . $cPath . "'
					OR
						p.product_type='L'
					AND
						p.parent_id = '" . $cPath . "'
					OR
						p.product_type='Q'
					AND
						p.parent_id = '" . $cPath . "'
					AND
						pd.language_id = '" . (int) $FSESSION->languages_id . "'
					ORDER BY
						p.products_id
					ASC";

			$result = tep_db_query($sql); 
			
			while ($seat = tep_db_fetch_array($result)) 
			{
				// draggable				
				$x=get_percentage(100,$seat['products_x']);
				$y=get_percentage(100,$seat['products_y']);
				
				$w=44;
				if ($seat['products_w'] >0){
				$w=get_percentage(100,$seat['products_w']);
				}
				$h=36;		
				if ($seat['products_h'] >0){
				$h=get_percentage(100,$seat['products_h']);	
				$f=get_percentage(160,$seat['products_h']);
				}
				$r=$seat['products_r'];
				$sx=get_percentage(100,$seat['products_sx']);
				$sy=get_percentage(100,$seat['products_sy']);

				$indiv_css = '';
				if(tep_not_null($seat['products_x'])){$indiv_css .= "left:".$x."px;";}
				if(tep_not_null($seat['products_y'])){$indiv_css .= "top:".$y."px;";}
				$indiv_css .= ''; 
				
				$size_css = '';
				if(tep_not_null($seat['products_w'])){$size_css .= "width:".$w."px;";}
				if(tep_not_null($seat['products_h'])){$size_css .= "height:".$h."px;";}
				$size_css .= ''; 
				
				$trans_css = '';
				if(tep_not_null($seat['products_r'])){$trans_css .= "transform:rotate(".$r."deg);";}
				if ($seat['products_sx'] >1){
				if(tep_not_null($seat['products_sx'])){$trans_css .= "transform:scale(".$sx.",".$sy.");";}	
				}
				$trans_css .= '';
				if ($seat['product_type']=='L'){
				$c='color:'.$seat['color_code'];
				}else{
				$c='';	
				}
				$trans_css .= '';
				//try setting Q to $f??
				echo '#s' . $seat['products_id'] . '{' . $indiv_css . $size_css . $trans_css . 'font-size:'.$f.'px;margin-top:-'.$h.'px;'.$c.'}' . "\n";
				if ($seat['product_type']=='L'){
				echo '.btn-ga{font-size:'.$h.'px}' . "\n";
				}
			}	
				if($bgh>0)
				{
				$bg_height=get_percentage(100,$bgh);
				echo 'div.seatplan{height:'.$bg_height.'px;}' . "\n";
				}
			
			//1200
			echo '@media (max-width: 1200px) {' . "\n";
			
			
			$result = tep_db_query($sql); 
			
			while ($seat = tep_db_fetch_array($result)) 
			{
				// draggable				
				$x=get_percentage(118,$seat['products_x']);
				$y=get_percentage(118,$seat['products_y']);
				if ($seat['products_w'] >0){
				$w=get_percentage(118,$seat['products_w']);
				}
				if ($seat['products_h'] >0){
				$f=get_percentage(200,$seat['products_h']);
				$h=get_percentage(118,$seat['products_h']);
				}
				$r=$seat['products_r'];
				$sx=get_percentage(100,$seat['products_sx']);
				$sy=get_percentage(100,$seat['products_sy']);

				$indiv_css = '';
				if(tep_not_null($seat['products_x'])){$indiv_css .= "left:".$x."px;";}
				if(tep_not_null($seat['products_y'])){$indiv_css .= "top:".$y."px;";}
				$indiv_css .= ''; 
				
				$size_css = '';
				if(tep_not_null($seat['products_w'])){$size_css .= "width:".$w."px;";}
				if(tep_not_null($seat['products_h'])){$size_css .= "height:".$h."px;";}
				$size_css .= ''; 
				
				$trans_css = '';
				if(tep_not_null($seat['products_r'])){$trans_css .= "transform:rotate(".$r."deg);";}
				if ($seat['products_sx'] >0)
				{
				if(tep_not_null($seat['products_sx'])){$trans_css .= "transform:scale(".$sx.",".$sy.");";}	
				}
				$trans_css .= '';
				echo '#s' . $seat['products_id'] . '{' . $indiv_css . $size_css . $trans_css . 'font-size:'.$f.'px;margin-top:-'.$h.'px;}' . "\n";
				if ($seat['product_type']=='L'){
				echo '.btn-ga{font-size:'.$h.'px}';
				}
			}
				if($bgh>0)
				{
				$bg_height=get_percentage(118,$bgh);
				echo 'div.seatplan{height:'.$bg_height.'px;}' . "\n";
				}				
			
			//992
			$result = tep_db_query($sql); 
			
			echo '@media (max-width: 992px) {' . "\n";

			while ($seat = tep_db_fetch_array($result)) 
			{
				// draggable				
				$x=get_percentage(162,$seat['products_x']);
				$y=get_percentage(162,$seat['products_y']);
				
				if ($seat['products_w'] >0){
				$w=get_percentage(162,$seat['products_w']);
				}
				if ($seat['products_h'] >0){
				$f=get_percentage(290,$seat['products_h']);
				$h=get_percentage(162,$seat['products_h']);
				}
				$r=$seat['products_r'];
				$sx=get_percentage(115,$seat['products_sx']);
				$sy=get_percentage(115,$seat['products_sy']);

				$indiv_css = '';
				if(tep_not_null($seat['products_x'])){$indiv_css .= "left:".$x."px;";}
				if(tep_not_null($seat['products_y'])){$indiv_css .= "top:".$y."px;";}
				$indiv_css .= ''; 
				
				$size_css = '';
				if(tep_not_null($seat['products_w'])){$size_css .= "width:".$w."px;";}
				if(tep_not_null($seat['products_h'])){$size_css .= "height:".$h."px;";}
				$size_css .= ''; 
				
				$trans_css = '';
				if(tep_not_null($seat['products_r'])){$trans_css .= "transform:rotate(".$r."deg);";}
				if ($seat['products_sx'] >0){
				if(tep_not_null($seat['products_sx'])){$trans_css .= "transform:scale(".$sx.",".$sy.");";}	
				}
				$trans_css .= '';
				echo '#s' . $seat['products_id'] . '{' . $indiv_css . $size_css . $trans_css . 'font-size:'.$f.'px;margin-top:-'.$h.'px;}' . "\n";
				if ($seat['product_type']=='L'){
				echo '.btn-ga{font-size:'.$h.'px}';
				}
			}	
				if($bgh>0)
				{
				$bg_height=get_percentage(162,$bgh);
				echo 'div.seatplan{height:'.$bg_height.'px;}' . "\n";
				}
			
			//767
			echo '@media (max-width: 768px) {' . "\n";
			
			$result = tep_db_query($sql); 
			
			while ($seat = tep_db_fetch_array($result)) 
			{
				// draggable				
				$x=get_percentage(220,$seat['products_x']);
				$y=get_percentage(220,$seat['products_y']);
				
				if ($seat['products_w'] >0){
				$w=get_percentage(220,$seat['products_w']);
				}
				if ($seat['products_h'] >0){
				$f=get_percentage(380,$seat['products_h']);
				$h=get_percentage(220,$seat['products_h']);
				}
				$r=$seat['products_r'];
				$sx=get_percentage(135,$seat['products_sx']);
				$sy=get_percentage(135,$seat['products_sy']);

				$indiv_css = '';
				if(tep_not_null($seat['products_x'])){$indiv_css .= "left:".$x."px;";}
				if(tep_not_null($seat['products_y'])){$indiv_css .= "top:".$y."px;";}
				$indiv_css .= ''; 
				
				$size_css = '';
				if(tep_not_null($seat['products_w'])){$size_css .= "width:".$w."px;";}
				if(tep_not_null($seat['products_h'])){$size_css .= "height:".$h."px;";}
				$size_css .= ''; 
				
				$trans_css = '';
				if(tep_not_null($seat['products_r'])){$trans_css .= "transform:rotate(".$r."deg);";}
				if ($seat['products_sx'] >0){
				if(tep_not_null($seat['products_sx'])){$trans_css .= "transform:scale(".$sx.",".$sy.");";}
				}
				$trans_css .= '';
				echo '#s' . $seat['products_id'] . '{' . $indiv_css . $size_css . $trans_css . 'font-size:'.$f.'px;margin-top:-'.$h.'px;}' . "\n";	
				if ($seat['product_type']=='L'){
				echo '.btn-ga{font-size:'.$h.'px}';
				}				
			}
				if($bgh>0)
				{
				$bg_height=get_percentage(220,$bgh);
				echo 'div.seatplan{height:'.$bg_height.'px;}' . "\n";
				}
			
			//480
			echo '@media (max-width: 576px) {' . "\n";
			
			$result = tep_db_query($sql); 
			
			while ($seat = tep_db_fetch_array($result)) 
			{
				// draggable				
				$x=get_percentage(324,$seat['products_x']);
				$y=get_percentage(324,$seat['products_y']);
				
				if ($seat['products_w'] >0){
				$w=get_percentage(324,$seat['products_w']);
				}
				if ($seat['products_h'] >0){
				$f=get_percentage(500,$seat['products_h']);
				$h=get_percentage(304,$seat['products_h']);
				}
				$r=$seat['products_r'];
				$sx=get_percentage(130,$seat['products_sx']);
				$sy=get_percentage(130,$seat['products_sy']);

				$indiv_css = '';
				if(tep_not_null($seat['products_x'])){$indiv_css .= "left:".$x."px;";}
				if(tep_not_null($seat['products_y'])){$indiv_css .= "top:".$y."px;";}
				$indiv_css .= ''; 
				
				$size_css = '';
				if(tep_not_null($seat['products_w'])){$size_css .= "width:".$w."px;";}
				if(tep_not_null($seat['products_h'])){$size_css .= "height:".$h."px;";}
				$size_css .= ''; 
				
				$trans_css = '';
				if(tep_not_null($seat['products_r'])){$trans_css .= "transform:rotate(".$r."deg);";}
				if ($seat['products_sx'] >0){
				if(tep_not_null($seat['products_sx'])){$trans_css .= "transform:scale(".$sx.",".$sy.");";}
				}
				$trans_css .= '';
				echo '#s' . $seat['products_id'] . '{' . $indiv_css . $size_css . $trans_css . 'font-size:'.$f.'px;margin-top:-'.$h.'px;}' . "\n";	
				if ($seat['product_type']=='L'){
				echo '.btn-ga{font-size:'.$h.'px}';
				}				
			}	
				if($bgh>0)
				{
				$bg_height=get_percentage(324,$bgh);
				echo 'div.seatplan{height:'.$bg_height.'px;}' . "\n";
				}				
			echo '</style>';
		}

		function  tep_renderSeatplanDesign($cPath)
		{
			global $FSESSION, $currencies, $manufacturers_id,$category_image_3, $category_image_4, $stage_image;
			
			
			if(DESIGN_MODE=='no')
			{
			$hide=" style=\"display:none\"";	
			}
			//set background image
			//if no database image use a default in the template images
			if($category_image_3=='')
			{
				$background_image=DIR_WS_TEMPLATE_IMAGES . 'design_bg1.png';
			}else{
				$background_image=DIR_WS_IMAGES . $category_image_3;
			}
			//
			if ($_SESSION["draggable"]=="yes" && SET_GRID_BACKGROUND=="yes")
			{
			echo '<style>div.seatplan {
				background-image: url('.DIR_WS_TEMPLATE_IMAGES.'grid_50.png),url('.$background_image.');
				background-repeat: repeat,no-repeat;
				background-size: 50px, 100% 100%;
				}' . "\n";
			echo '<span class="sp_text">'. DESIGN_NOTE. '</span>' . "\n";
			
			}else{
			echo '<style>div.seatplan {background-image: url('.$background_image.')}' . "\n";
			//testing
			// echo '<style>div.seatplan {
				// background-image: url('.DIR_WS_TEMPLATE_IMAGES.'grid_50.png),url('.$background_image.');
				// background-repeat: repeat,no-repeat;
				// background-size: 50px, 100% 100%;
				// }' . "\n";			
			}
			
			//set stage image
			//if no database image use a default in the template images
			//if(tep_not_null($category_image_4))
			if($category_image_4=='')
			{
				$stage_image=DIR_WS_TEMPLATE_IMAGES . 'design_stage.png';
			}else{
				$stage_image=DIR_WS_IMAGES . $category_image_4;
			}
			echo 'div#stage_label{background-image: url('.$stage_image.')}</style>';
			//echo '</style>';
			
			if ($_SESSION["draggable"]=="yes" && SET_GRID_BACKGROUND=="yes")
			{
				$stage_img=" style=\"background-image: url(".$stage_image.")\"";
			}

			if(tep_not_null($manufacturers_id))
			{
			$STAGE='';
			$STAGE=DESIGN_STAGE_NAME;
			if(tep_not_null($category_image_4))
			{
			$hasStage= "<div id=\"stage_label\" ".$stage_img.">" .  $STAGE . "</div>" . "\n";
			}
			$html   = '<!-- start: Design Mode '.$manufacturers_id.' -->' . "\n" . ''.$hasStage.'<div id="sp' . $cPath . '" class="seatplan" '.$hide.'><ul class="r">' . "\n";
			}
					
			$sql    = "

					SELECT DISTINCT
						p.products_status,
						p.color_code,
						p.products_id,
						p.products_model,
						pd.products_name,
						pd.products_number,
						pd.products_url,
						p.parent_id,
						p.products_ordered,
						p.products_tax_class_id,
						p.product_type,
						p.products_price,
						p.restrict_to_groups,
						p.products_x,
						p.products_y,
						p.products_w,
						p.products_r,
						p.products_sx,
						p.products_sy,
						p.products_h

					FROM " . TABLE_PRODUCTS . " p

					JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd
					ON
						pd.products_id = p.products_id
					WHERE
						p.product_type='P'
					AND
						p.parent_id = '" . $cPath . "'
					OR
						p.product_type='L'
					AND
						p.parent_id = '" . $cPath . "'
					OR
						p.product_type='Q'
					AND
						p.parent_id = '" . $cPath . "'
					AND
						pd.language_id = '" . (int) $FSESSION->languages_id . "'
					ORDER BY
						p.products_id
					DESC";

			$result = tep_db_query($sql);
			$i      = 0;

			//get the customerGroupID for the currently logged-in user. BOOL false if not logged-in.
			$customerGroup = $this->tep_getCustomerGroupID();
			
			$trans_css = '';
			$size_css = '';
			$indiv_css = '';
			

			while ($seat = tep_db_fetch_array($result)) 
			{
                
				// draggable
				if ($_SESSION['draggable']=="yes")
				{
				
				if(SCREEN_EDIT=='desktop'){
				$xpc=100;
				$ypc=100;
				$wpc=100;
				$hpc=100;
				$fpc=165;
				$w=44;
				$h=36;
				}
				
				if(SCREEN_EDIT=='ipad'){
				$xpc=159;
				$ypc=159;
				$wpc=159;
				$hpc=159;
				$fpc=260;
				$w=22;
				$h=21;
				}
								
				$x=get_percentage($xpc,$seat['products_x']);
				$y=get_percentage($ypc,$seat['products_y']);
						
				if ($seat['products_w'] >0){
				$w=get_percentage($wpc,$seat['products_w']);
				}
				if ($seat['products_h'] >0){
				$h=get_percentage($hpc,$seat['products_h']);
				}
				if ($seat['products_h'] >0){
				$f=get_percentage($fpc,$seat['products_h']);
				}
				$r=$seat['products_r'];
				$sx=$seat['products_sx'];
				$sy=$seat['products_sy'];
				$indiv_css = ' style="';
				if(tep_not_null($seat['products_x'])){$indiv_css .= "left:".$x."px;";}
				if(tep_not_null($seat['products_y'])){$indiv_css .= "top:".$y."px;";}
				$indiv_css .= '';
				$size_css = '';
				if(tep_not_null($seat['products_w'])){$size_css .= "width:".$w."px;";}
				if(tep_not_null($seat['products_h'])){$size_css .= "height:".$h."px;";}
				$size_css .= ''; 
				$trans_css = '';
				if(tep_not_null($seat['products_r'])){$trans_css .= "transform:rotate(".$r."deg);";}
				if ($seat['products_sx'] >1){
				if(tep_not_null($seat['products_sx'])){$trans_css .= "transform:scale(".$sx.",".$sy.");";}	
				}
				if ($seat['product_type']=='L')
				{
				$c='color:'.$seat['color_code'];
				}else{
				$c='';	
				}
				$trans_css .= ' font-size:'.$f.'px;margin-top:-'.$h.'px;'.$c.'"';
				}
				
				//get the array RestrictToGroups for this seat
				$arryRestrictToGroups = explode(',', $seat['restrict_to_groups']); 
				//set the seatRestricted flag for the seat, based on whether there's an element in the array
				$seatRestricted = ($arryRestrictToGroups[0] > "" ? true : false );
                                
                 //2015 the price is NET it does not include TAX or SPECIALS
                 //use the tax_rate to get these
                                
                $tax_rate=tep_get_tax_rate($seat["products_tax_class_id"]);
		
				if($new_price = tep_get_products_special_price($seat['products_id'])) 
				{
				$products_price=tep_add_tax($new_price, $tax_rate);
				$products_tot_price=$currencies->format($products_price);		
				$products_original_price=$new_price;
				}
				else {
				$products_price=tep_add_tax(tep_get_plain_products_price($seat['products_price']), $tax_rate);
				$products_tot_price=$currencies->format($products_price);
				$products_original_price=$seat['products_price'];
				}
				// discount is not shown - only specials price and tax
                // discount in next stage
				
				//if the seat's status != 0 (available) 
				if ( $seat['products_status'] != 0 ) 
				{    
					//if the seat is restricted AND the user belongs to RestrictToGroup
					if ($seatRestricted && in_array($customerGroup, $arryRestrictToGroups) ) {
						$titlePrice = $products_tot_price;
					} 
					//elseif the seat is restricted AND the user does not belong to the RestrictToGroup
					elseif ($seatRestricted && !in_array($customerGroup, $arryRestrictToGroups) ) {
						$titlePrice = " RESTRICTED ";
					}
					//otherwise seat is not restricted
					else {
						$titlePrice = $products_tot_price;
					}
				} //$seat['products_status'] != 0
				else {
					$titlePrice='' . SOLD . '';
				}
				//check something
				if(TEST_MODE=='true'){
					if ( $seat['products_ordered'] == 0 && $seat['products_status'] ==0) 
					{ 
						$titlePrice = ''  . NO_ORDER . '';
					}
					if ( $seat['products_ordered'] > 0 && $seat['product_type'] =='P') 
					{ 
						$titlePrice = ''  . NO_ORDER . '';
					}
				}

				switch ($seat['html']) 
				{	
					case 'D':
						/* line wrap */
						$html .= "<ul class='r'>";
						break;
					case 'Y':
						/* line wrap */
						if ($i != 0) {
							$html .= "</ul>\n";
						} //$i != 0
						$html .= "<ul class='r'>";
						$i++;
						break;
					case 'W':
						/* line wrap with seat plan text */
						if ($i != 0) {
							$html .= "</ul>";
							$html .= "<div class='sp_text'>". BALCONY_TEXT. "</div>\n";
						} //$i != 0
						$html .= "<ul class='r'>";
						$i++;
						break;
					case 'X':
						/* line wrap height (gap below) */
						if ($i != 0) {
							$html .= "</ul>\n";
						} //$i != 0
						$html .= "<ul id='gap' class='r'>";
						$i++;
						break;
				} //$seat['html']
				
				//Box Office Draggable - this will make all seats moveable that you add the $extraClass into
				//currently just added to unsold seats
				//note it will also remove class s
					if ($_SESSION['draggable']=="yes")
					{
						$extraClass="draggable ";				
					}else{
						$extraClass=" ";
					}

				switch ($seat['products_status']) 
				{
					
					case 0:
					//cancel fallthrough for box office:
						if(($_SESSION['customer_country_id']==999)&&(BO_POPOVER=='yes'))
						{
							//get the order number from the product_id  $seat['products_id']
							$sql2 = "
									SELECT
										orders_id 
									FROM
										orders_products
									WHERE
										products_id ='".$seat['products_id']."'
									AND 
										products_model='".$seat['products_model']."'
									LIMIT 1";
					
								$result2 = tep_db_query($sql2);
								$x_title = tep_db_fetch_array($result2);
								$x_order_id = $x_title['orders_id'];
							// get the customers name 
							$order_query = tep_db_query("select customers_id, customers_name from " . TABLE_ORDERS . " where orders_id = '" . (int)$x_order_id . "'");
							$order = tep_db_fetch_array($order_query);		
								
							$titlePrice='order #:'.$x_order_id.'<br />';
							$titlePrice.=$order['customers_name'].'<br />';
						}//end of Box Office

					//case 0:
					/* sold seats - falltrough */
					case 1:
						//hide the title pop up for editing
						if ($_SESSION['draggable']!="yes")
						{
						$title='title="' . $seat['products_name'] . " - " . $titlePrice . '"';
						}
						if ( $seat['products_ordered'] > 0 && $seat['product_type'] =='P') 
						{ 
						$title = 'title="'  . NO_ORDER . '"';
						}
						// available seats 
						$html .= '<li '. $indiv_css . $size_css . $trans_css .' class="s '.$extraClass . $this->color2class($seat['color_code']) . '" id="s' . $seat['products_id'] . '" data-rel="'.$products_original_price.'" '.$title.'>' . $seat['products_number'] . "</li>";	
						break;
					case 2:
						/* old blank seats changed to status 8*/
						//$html .= "<li class='b'></li>";
						break;
					case 3:
						/* social distancing? */
						//$html .= '<li class="sd">' . $seat['products_url'] . "</li>";
						//$html .= "<li class='sd'></li>";
						$html .= '<li '. $indiv_css . $size_css . $trans_css .' class="sd" id="s' . $seat['products_id'] . '" data-rel="'.$products_original_price.'" '.$title.'></li>';	
						break;
					case 4:
						/* row letters */
						$p=$seat['products_number']; //$p='<i class=\'fa fa-wheelchair fa-2x\'></i>';
						if ($seat['product_type']=='L' || $seat['product_type']=='Q')
						{	
						$di='id="s'.$seat['products_id'].'"'; 
						}						
						$html .= '<li ' . $di . $indiv_css . $size_css . $trans_css . ' class="'.$extraClass .'ltr">' . $p . "</li>";	
						break;
					case 5:
						$b='<a class="btn btn-ga scrollto" style="background-color:'.$seat['color_code'].'" href="#ga_'.$seat['products_url'].'">'.$seat['products_number'].'</a>';
						if ($seat['product_type']=='L')
						{	
						$di='id="s'.$seat['products_id'].'"'; 
						
						}						
						$html .= '<li ' . $di . $indiv_css . $size_css . $trans_css . ' class="'.$extraClass .'ltr">' . $b . "</li>";	
						break;
					case 7:
						/* alt blank */
						$p=$seat['products_number'];
						$html .= '<li id="s'.$seat['products_id'].'" '. $indiv_css . $size_css . $trans_css . ' class="'.$extraClass .'q">' . $p . "</li>";
						
						//$html .= "<li class='c'></li>";
						break;
					case 8:
						/* 2021 blank seats */
						//$html .= "<li class='b'></li>". "\n";	
						break;

				} //$seat['products_status']
			} //$seat = tep_db_fetch_array($result)
			/* closing the last ul and div#seatplan */
			$html .= "</ul>\n";
			$html .= "</div>\n";
			$html .= "<!-- end: AJAX Seatplan by  Martin Zeitler -->\n";
			tep_db_free_result($result);
			
			return $html;
		}
		/* end: tep_renderSeatplanDesign() */
		
		function  tep_renderSeatplan($cPath)
		{
			//PRO version or Seat Plan Integration Versions ONLY
			echo "<h4>".TEXT_DESIGN_MODE_ONLY."</h4>";
		}
		/* end: tep_renderSeatplan() */


		function tep_renderPricesBar($cPath, $title)
		{
			global $currencies, $cart, $FSESSION;
			$sql = "
				SELECT DISTINCTROW
					p.color_code,
					p.products_price,
					p.products_tax_class_id,
					p.products_status
				FROM ".
					TABLE_PRODUCTS." p
				WHERE
					p.parent_id = ".(int)$cPath."
				AND
					p.products_price NOT LIKE '0.0000'
				AND
					p.products_status = 1
				ORDER BY
					p.products_price
				DESC";
				
		
			$spacer = tep_draw_separator('pixel_trans.gif', 14, 14);
			$html   = '<div id="legend">' . TEXT_LEGEND . '<ul class="list-group list-group-horizontal">' . "\n";
			//Uncomment to use LEGEND Message below Legend
			//$html .= "	<li class=\" list-group-item\">" . LEGEND_DISCOUNT_MESSAGE . "</li>";
		
			$result = tep_db_query($sql);
			while ($show = tep_db_fetch_array($result)) 
			{
				//group discount??
					$query = tep_db_query("select g.customers_groups_discount from " . TABLE_CUSTOMERS_GROUPS . " g inner join  " . TABLE_CUSTOMERS  . " c on g.customers_groups_id = c.customers_groups_id and c.customers_id = '" . (int)$FSESSION->customer_id . "'");
					$query_result = tep_db_fetch_array($query);
					$customers_groups_discount = $query_result['customers_groups_discount'];
					$query = tep_db_query("select customers_discount from " . TABLE_CUSTOMERS . " where customers_id =  '" . (int)$FSESSION->customer_id . "'");
					$query_result = tep_db_fetch_array($query);
					$customer_discount = $query_result['customers_discount'];
					$customer_discount = $customer_discount + $customers_groups_discount;
					if ($customer_discount !== 0) 
					{
					 $show['products_price'] = $show['products_price'] - $show['products_price'] * abs($customer_discount) / 100;	
					}
		
			
				//add some text for each ticket type
				if ($show['color_code']=='blue') {
				$ticket_type=BLUE;
				}
				if ($show['color_code']=='yellow') {
				$ticket_type=YELLOW;
				}
				if ($show['color_code']=='green') {
				$ticket_type=GREEN;
				}
				if ($show['color_code']=='fuchsia') {
				$ticket_type=FUCHSIA;
				}
				if ($show['color_code']=='red') {
				$ticket_type=RED;
				}
				if ($show['color_code']=='skyblue') {
				$ticket_type=SKYBLUE;
				}
				if ($show['color_code']=='thistle') {
				$ticket_type=THISTLE;
				}
				if ($show['color_code']=='salmon') {
				$ticket_type=SALMON;
				}
				if ($show['color_code']=='palegreen') {
				$ticket_type=PALEGREEN;
				}
				if ($show['color_code']=='teal') {
				$ticket_type=TEAL;
				}
				if ($show['color_code']=='orange') {
				$ticket_type=ORANGE;
				}
				
				$spacer='';
				///Database Legend
				$html .= '	
				<li class="list-group-item"><strong>' . $ticket_type . '</strong>
				<span class="plan_' . $show['color_code'] . ' cube">' . $spacer . "</span>
				&nbsp;" . $currencies->format(tep_add_tax($show['products_price'], tep_get_tax_rate($show['products_tax_class_id']))) . "</li>\n";
				
			} //$show = tep_db_fetch_array($result)
			
			//TRY TO SET A MANUAL LEGEND (uncomment below) example:
			##############################################################################################################################
			//$html .= '	<li class="list-group-item"><strong>' . YELLOW . '</strong>&nbsp;<span class="plan_yellow cube">' . $spacer . "</span>&nbsp;$80</li>\n";
			//$html .= '	<li class="list-group-item"><strong>' . ORANGE . '</strong>&nbsp;<span class="plan_orange cube">' . $spacer . "</span>&nbsp;$80</li>\n";
			##############################################################################################################################
			if($_SESSION['customer_country_id']==999)
			{
				if(BOX_OFFICE_BLOCKING=='yes')
				{
				$html .= "
				   <li class=\"list-group-item\">
				   <button class=\"btn btn-primary btn-sm\"><a class=\"bo-block\" href=\"multi_block.php\">" . TEXT_BO_BLOCKER . "</a></button>
				   </li>\n";
				}
			}
			$html .= "</ul></div>\n";
			
			//2014 Box Office Refund
			//if we have a Box Office session 
			if($_SESSION['customer_country_id']==999)
			{		
			
			  //check for a POST value			  
			   if(isset($_POST['box_office_switch'] ))
			   {
			   
				    switch ($_POST['box_office_switch'] )
				    {
				   
					case 'yes':
						$_SESSION['box_office_refund']='yes';
						$FSESSION->remove('box_office_reservation');
						$FSESSION->remove('draggable');
						$cart->reset(true);
						break;
					case 'reserve':
						$_SESSION['box_office_reservation']='yes';
						$FSESSION->remove('box_office_refund');
						$FSESSION->remove('draggable');
						$cart->reset(true);
						break;
					case 'draggable':
					        $_SESSION['draggable']='yes';
						$FSESSION->remove('box_office_refund');
						$FSESSION->remove('box_office_reservation');
						$cart->reset(true);
						break;	
					case 'no':
						$FSESSION->remove('box_office_refund');
						$FSESSION->remove('box_office_reservation');
						$FSESSION->remove('draggable');
						$cart->reset(true);
						break;
						
					}
			   }
				//offer option to switch out of refund mode
				if(isset($_SESSION['box_office_refund'] ))
				{
				if(ALLOW_BO_REFUND=='yes'){  
				$html .= tep_draw_form('box_office_refund', 'index.php?'.tep_get_all_get_params($parameters)).
					   tep_draw_hidden_field('box_office_switch','no')."<ul><li class=\"list-group\" style=\"cursor:pointer\" onClick='document.forms[\"box_office_refund\"].submit()'>&nbsp;<button class=\"btn btn-primary btn-sm\">" . TEXT_LEGEND_REFUND_CANCEL . ":</button></li></ul></form>";
				}
				}
				
				//offer option to switch out of draggable mode
				elseif(isset($_SESSION['draggable'] ))
				{
				if(ALLOW_BO_DESIGN=='yes')
				{
				$html .= tep_draw_form('box_office_draggable', 'index.php?'.tep_get_all_get_params($parameters)).
						   tep_draw_hidden_field('box_office_switch','no')."<ul style=\"display: flex;
				  justify-content: center;\"><li class=\"list-group\" style=\"cursor:pointer\" onClick='document.forms[\"box_office_draggable\"].submit()'>&nbsp;<button class=\"btn btn-primary btn-sm\" style=\"width:400px;\">" . TEXT_LEGEND_DRAG_CANCEL . "</button></li></ul></form>";
										   
										   // draggable
											if ($_SESSION['draggable']=="yes")
											{
											echo "<style>@media (max-width: 1200px) {div#stage_label{display:none}div.seatplan{display:none}}</style>" . "\n";
											}
									}
								}
								
								//now do reservations
								elseif(isset($_SESSION['box_office_reservation'] ))
								{
								$looking_for = 'bor.php'; // is Box Office Reservations installed?
								$modules_installed = explode(';', MODULE_PAYMENT_INSTALLED);
								if (in_array($looking_for, $modules_installed))
								{
								$html .= tep_draw_form('box_office_reserve', 'index.php?'.tep_get_all_get_params($parameters)).
									   tep_draw_hidden_field('box_office_switch','no')."<ul style=\"display: flex;
				  justify-content: center;\"><li class=\"list-group\" style=\"cursor:pointer\" onClick='document.forms[\"box_office_refund\"].submit()'>&nbsp;<button class=\"btn btn-primary btn-sm\" style=\"width:400px;\">" . TEXT_LEGEND_RESERVATION_CANCEL . ":</button></li></ul></form>";
				}
				}
				else
				{
					if(ALLOW_BO_REFUND=='yes')
					{
					//offer option to switch to refund or reservation mode
					$html .= tep_draw_form('box_office_refund', 'index.php?'.tep_get_all_get_params($parameters)).
					   tep_draw_hidden_field('box_office_switch','yes')."
					   <ul>
					   <li class=\"list-group\" style=\"cursor:pointer\" onClick='document.forms[\"box_office_refund\"].submit()'>&nbsp;
					   <button class=\"btn btn-primary btn-sm\" style=\"width:400px;\">" . TEXT_LEGEND_REFUND . ":</button>
					   </li>
					   </ul>
					   </form>";
					}
					
					if(ALLOW_BO_DESIGN=='yes')
					{
					// GRT offer option to switch to draggable
					$html .= tep_draw_form('box_office_draggable', 'index.php?'.tep_get_all_get_params($parameters)).
					   tep_draw_hidden_field('box_office_switch','draggable')."
					   <ul style=\"display: flex;
  justify-content: center;\">
					   <li class=\"list-group\" style=\"cursor:pointer\" onClick='document.forms[\"box_office_draggable\"].submit()'>&nbsp;
					   <button class=\"btn btn-primary btn-sm\" style=\"width:400px;\">" . TEXT_LEGEND_DRAG . "</button>
					   </li>
					   </ul>
					   </form>";
					}
					   
					$looking_for = 'bor.php'; // is Box Office Reservations installed?

					$modules_installed = explode(';', MODULE_PAYMENT_INSTALLED);
					if (in_array($looking_for, $modules_installed))
					{
					$html .= tep_draw_form('box_office_reserve', 'index.php?'.tep_get_all_get_params($parameters)).
						   tep_draw_hidden_field('box_office_switch','reserve')."
						   <ul style=\"display: flex;
  justify-content: center;\">
						   <li class=\"list-group\" style=\"cursor:pointer\" onClick='document.forms[\"box_office_reserve\"].submit()'>&nbsp;
						   <button class=\"btn btn-primary btn-sm\" style=\"width:400px;\">" . TEXT_LEGEND_RESERVATION . ":</button>
						   </li>
						   </ul>
						   </form>";
					}
				}
			   
			}

			
			tep_db_free_result($result);
			return $html;
		} /* end: tep_renderPricesBar() */

			function color2class($color){
			if(!$color){return '';}
			switch($color){
				case 'blue':			$cls='bl';break;
				case 'red':				$cls='rd';break;
				case 'green':			$cls='gr';break;
				case 'fuchsia':			$cls='fu';break;
				case 'yellow':			$cls='ye';break;
				case 'salmon':			$cls='sa';break;
				case 'teal':			$cls='te';break;
				case 'skyblue':			$cls='sb';break;
				case 'orange':			$cls='or';break;
				case 'palegreen':		$cls='pg';break;
				case 'thistle':			$cls='th';break;
				default:				$cls='';
			}
			return $cls;
		} /* end: color2class() */
		
		//january 2018 show modal only
		function tep_showSeat($products_id, $cPath, $products_orig_price){
			global $FSESSION, $cart, $currencies;

			/* get cid & strings */
			if($_SESSION['customer_id']){$cid = $_SESSION['customer_id'];$tmp = '';}else{$cid = 0;$tmp = ' temp';}
			
			
				// add check for number of items in cart
					if ( is_numeric(MAX_IN_CART_AMOUNT) && (MAX_IN_CART_AMOUNT>0)) {//if we have a numeric value >0
						if ($cart->count_contents()>=MAX_IN_CART_AMOUNT){
						//grab actual cart age as below so that the time countdown 'freezes' for the customer
							if($cid != 0){
								$remaining = (int)SEATPLAN_TIMEOUT - $this->tep_getCartAge($cid);
										}
									else {
								$remaining = (int)SEATPLAN_TIMEOUT - $this->tep_getTempCartAge();
									}		
									
										$arr =array(
										'max' => (int)$products_id,
										'remaining' => (int)$remaining
										); 
										return $arr;
																				}
								}
							 //end quantity check
			
			/* this check might need to be changed when updating discounts... */
			if(!$this->tep_checkLocks($products_id)){	
				/* add a single seat to the cart */
				//$cart->add_cart($products_id, 1 );
				
				/* retrieving the actual cart age, probably needs to be moved */
				if($cid != 0){
					$remaining = (int)SEATPLAN_TIMEOUT - $this->tep_getCartAge($cid);
				}
				else {
					$remaining = (int)SEATPLAN_TIMEOUT - $this->tep_getTempCartAge();
				}
				
			
				$arr =array(
					//'granted' => (int)$products_id,
					//'remaining' => (int)$remaining
				);
				//March 2013 - let's create some code to look for a discount and, if found, pass an array through to the front end
				//(1) check for discount
				
				//$cPath is the topmost category - need to changethat!
						//March 2013 - this is not returning correct categories - it need to check the top category
						$cats=explode('_',(tep_get_product_path($products_id)));
						foreach($cats as $key=>$value){
						$cats1.='or sale_categories_all like \'%,'.$value.',%\' ';
						}
				
				$sDate=getServerDate();
 
                                
            //check for product_discount price
			$sale_query = tep_db_query("select sale_id, sale_name, sale_specials_condition, sale_deduction_value, sale_deduction_type,choice_text,choice_warning from " . TABLE_SALEMAKER_SALES . " where sale_discount_type='C' " . $has_option . " and ((sale_categories_all='' and sale_products_selected='')".$cats1." or sale_products_selected like '%," . tep_db_input($products_id) .",%') and sale_status = '1' and (sale_date_start <='" . tep_db_input($sDate) . "' or sale_date_start = '0000-00-00') and (sale_date_end >= '" . tep_db_input($sDate) . "' or sale_date_end = '0000-00-00') and (sale_pricerange_from <= " . $products_orig_price . " or sale_pricerange_from = '0') and (sale_pricerange_to >= " . $products_orig_price . " or sale_pricerange_to = '0') order by sale_deduction_value");

			if(tep_db_num_rows($sale_query) > 0){
				//we have a discount so let's create the arrays we need
				// create the display of the discount
						
				$discounts_available=array();
				while($sale=tep_db_fetch_array($sale_query)){
				// create the display of the discount
				//first get the original price, special price and tax rate - most of this code from product_info.php
				$sql = "
				select
					p.products_id,
					p.products_price_break,
					pd.products_name,
					pd.products_number,
					pd.products_description,
					pd.language_id,
					p.products_price,
					p.products_tax_class_id,
					p.product_type
				from
					".TABLE_PRODUCTS." p,
					".TABLE_PRODUCTS_DESCRIPTION." pd
				where
					p.products_status = '1'
				and
					p.products_id = '".tep_db_input($products_id)."'
				and
					pd.products_id = p.products_id
				and
					pd.language_id = '".(int)$FSESSION->languages_id."'".
					$check_restrict_customer;
			
			$product_info_query = tep_db_query($sql);
			$product_info=tep_db_fetch_array($product_info_query);
				
			$tax_rate=tep_get_tax_rate($product_info["products_tax_class_id"]);
		
		if($new_price = tep_get_products_special_price($product_info['products_id'])) {
			$products_price=tep_add_tax($new_price, $tax_rate);
			$products_original_price=$new_price;
		}
		else {
			$products_price=tep_add_tax(tep_get_plain_products_price($product_info['products_price']), $tax_rate);
			$products_original_price=$product_info['products_price'];
		}
		
		$special_price=$new_price;
                //these prices are in the default currency
				$currency_value=$currencies->currencies[$currency=$FSESSION->currency]['value'];
				//just in case we get a NULL return make it = 1
				 if($currency_value==0){$currency_value=1;}
				//we will use this in the provision to pass the value over in Ajax
				//there is no function in the currencies class to do this without adding in the symbols decimal points etc
                
		
					switch($sale['sale_deduction_type']){
						
						case 1://percentage reduction
							$choice_text=$sale["choice_text"] . " (" . number_format($sale["sale_deduction_value"],2). "%)";
							$discounted_price_net =$currency_value* ($products_original_price-($products_original_price*$sale["sale_deduction_value"])/100);
							$discounted_price= tep_add_tax($discounted_price_net, $tax_rate);
							break;
						
						case 0://subtract
						
						if ($sale["sale_name"] == "Popup"){
							$choice_text=$sale["choice_text"];
						}else{
							$choice_text=$sale["choice_text"] . " (<s><font color='red'>" . $currencies->format(tep_add_tax($products_original_price, $tax_rate)) . "</font></s>&nbsp;" . $currencies->format(tep_add_tax($products_original_price-$sale["sale_deduction_value"], $tax_rate)) . ")";
							
						}
							$discounted_price_net = $currency_value*($products_original_price-$sale["sale_deduction_value"]);
							$discounted_price= tep_add_tax($discounted_price_net, $tax_rate);
							break;
						
						case 2://new price added currency value 2015
							$choice_text=$sale["choice_text"] . " (" . $currencies->format(tep_add_tax($sale["sale_deduction_value"],$tax_rate)). ")";
							$discounted_price_net = $currency_value*($sale["sale_deduction_value"]);
							$discounted_price= tep_add_tax($discounted_price_net, $tax_rate);							
							break;
					}
				
				   $sale['description']=$choice_text;
                   $sale['discounted_price']=$discounted_price;
                   $discounts_available[]=$sale;
																														}
												}
				$arr['discounts']=$discounts_available;
				$arr['products_name']= $this->tep_product_to_name($products_id);
				$arr['products_price']= $currencies->format($products_price);
				$arr['products_description']= $product_info["products_description"];
				$arr['products_number']= $product_info["products_number"];
				//Popup
				$arr['show_name']= $this->cat_name($cPath);
				
				//end new code
			}

			/* log attempt - if live-logging is enabled */
			else {
				if(SEATPLAN_LOGGING=='true'){
					$msg = 'prevented a duplicate seat in'.$tmp.' cart';
					$log = $this->tep_logSeatPlanEvent(3,(int)$cPath,(int)$products_id,$cid,$FSESSION->ID,$msg);
				}
				$arr =array(
					'denied' => (int)$products_id
				);
			}
			return $arr;
			
		} /* end: tep_showSeat() */

		//adds seats to ajax cart
		function tep_addSeat($products_id, $cPath, $products_orig_price){
			global $FSESSION, $cart, $currencies;
			
			//2014 box office refund
			if($_SESSION['customer_country_id']==999 && isset($_SESSION['box_office_refund'] )){
			################################
				if($_SESSION['customer_id']){$cid = $_SESSION['customer_id'];$tmp = '';}else{$cid = 0;$tmp = ' temp';}
			
	
			/* this check might need to be changed when updating discounts... */

				/* add a single seat to the cart */
				$cart->add_cart_refund($products_id, 1 );
				

					$remaining = (int)SEATPLAN_TIMEOUT;
				
				
				/* log success - if live-logging is enabled */
				if(SEATPLAN_LOGGING=='true'){
					$msg = 'added to'.$tmp.'  Box Office Refund cart';
					$log = $this->tep_logSeatPlanEvent(2,(int)$cPath,(int)$products_id,$cid,$FSESSION->ID,$msg);
				}
				$arr =array(
			     	'refund'=>'yes',
					'granted' => (int)$products_id,
					'remaining' => (int)$remaining
				);


			return $arr;
		} 
		elseif($_SESSION['customer_country_id']==999 && isset($_SESSION['box_office_reservation'] )){
			################################
				if($_SESSION['customer_id']){$cid = $_SESSION['customer_id'];$tmp = '';}else{$cid = 0;$tmp = ' temp';}
			/* this check might need to be changed when updating discounts... */
				/* add a single seat to the cart */
				$cart->add_cart_reservation($products_id, 1 );
					$remaining = (int)SEATPLAN_TIMEOUT;
				/* log success - if live-logging is enabled */
				if(SEATPLAN_LOGGING=='true'){
					$msg = 'added to'.$tmp.'  Box Office Reservation cart';
					$log = $this->tep_logSeatPlanEvent(2,(int)$cPath,(int)$products_id,$cid,$FSESSION->ID,$msg);
				}
				$arr =array(
			     	'refund'=>'yes',
					'granted' => (int)$products_id,
					'remaining' => (int)$remaining
				);
			return $arr;
		} 
		
		#################################
			 else {
			// end 2014 box office refund
			/* get cid & strings */
			if($_SESSION['customer_id']){$cid = $_SESSION['customer_id'];$tmp = '';}else{$cid = 0;$tmp = ' temp';}
			
			
				// add check for number of items in cart
					if ( is_numeric(MAX_IN_CART_AMOUNT) && (MAX_IN_CART_AMOUNT>0)) {//if we have a numeric value >0
						if ($cart->count_contents()>=MAX_IN_CART_AMOUNT){
						//grab actual cart age as below so that the time countdown 'freezes' for the customer
							if($cid != 0){
								$remaining = (int)SEATPLAN_TIMEOUT - $this->tep_getCartAge($cid);
										}
									else {
								$remaining = (int)SEATPLAN_TIMEOUT - $this->tep_getTempCartAge();
									}		
									
										$arr =array(
										'max' => (int)$products_id,
										'remaining' => (int)$remaining
										); 
										return $arr;
																				}
								}
							 //end quantity check
			
			/* this check might need to be changed when updating discounts... */
			if(!$this->tep_checkLocks($products_id)){	
				/* add a single seat to the cart */
				$cart->add_cart($products_id, 1 );
				
				/* retrieving the actual cart age, probably needs to be moved */
				if($cid != 0){
					$remaining = (int)SEATPLAN_TIMEOUT - $this->tep_getCartAge($cid);
				}
				else {
					$remaining = (int)SEATPLAN_TIMEOUT - $this->tep_getTempCartAge();
				}
				
				/* log success - if live-logging is enabled */
				if(SEATPLAN_LOGGING=='true'){
					$msg = 'added to'.$tmp.' cart';
					$log = $this->tep_logSeatPlanEvent(2,(int)$cPath,(int)$products_id,$cid,$FSESSION->ID,$msg);
				}
				$arr =array(
					'granted' => (int)$products_id,
					'remaining' => (int)$remaining
				);
				//March 2013 - let's create some code to look for a discount and, if found, pass an array through to the front end
				//(1) check for discount
				
				//$cPath is the topmost category - need to changethat!
										//March 2013 - this is not returning correct categories - it need to check the top category
						$cats=explode('_',(tep_get_product_path($products_id)));
						foreach($cats as $key=>$value){
						$cats1.='or sale_categories_all like \'%,'.$value.',%\' ';
						}
				
				$sDate=getServerDate();
 
                                
            //check for product_discount price
			$sale_query = tep_db_query("select sale_id,sale_specials_condition, sale_deduction_value, sale_deduction_type,choice_text,choice_warning from " . TABLE_SALEMAKER_SALES . " where sale_discount_type='C' " . $has_option . " and ((sale_categories_all='' and sale_products_selected='')".$cats1." or sale_products_selected like '%," . tep_db_input($products_id) .",%') and sale_status = '1' and (sale_date_start <='" . tep_db_input($sDate) . "' or sale_date_start = '0000-00-00') and (sale_date_end >= '" . tep_db_input($sDate) . "' or sale_date_end = '0000-00-00') and (sale_pricerange_from <= " . $products_orig_price . " or sale_pricerange_from = '0') and (sale_pricerange_to >= " . $products_orig_price . " or sale_pricerange_to = '0') order by sale_deduction_value");

			if(tep_db_num_rows($sale_query) > 0)
			{
				//we have a discount so let's create the arrays we need
				// create the display of the discount
						
				$discounts_available=array();
				while($sale=tep_db_fetch_array($sale_query)){
				// create the display of the discount
				//first get the original price, special price and tax rate - most of this code from product_info.php
				$sql = "
				select
					p.products_id,
					p.products_price_break,
					pd.products_name,
					pd.products_description,
					pd.language_id,
					p.products_price,
					p.products_tax_class_id,
					p.product_type
				from
					".TABLE_PRODUCTS." p,
					".TABLE_PRODUCTS_DESCRIPTION." pd
				where
					p.products_status = '1'
				and
					p.products_id = '".tep_db_input($products_id)."'
				and
					pd.products_id = p.products_id
				and
					pd.language_id = '".(int)$FSESSION->languages_id."'".
					$check_restrict_customer;
			
			$product_info_query = tep_db_query($sql);
			$product_info=tep_db_fetch_array($product_info_query);
				
			$tax_rate=tep_get_tax_rate($product_info["products_tax_class_id"]);
		
		if($new_price = tep_get_products_special_price($product_info['products_id'])) {
			$products_price=tep_add_tax($new_price, $tax_rate);
			$products_original_price=$new_price;
		}
		else {
			$products_price=tep_add_tax(tep_get_plain_products_price($product_info['products_price']), $tax_rate);
			$products_original_price=$product_info['products_price'];
		}
		
		$special_price=$new_price;
                //these prices are in the default currency
                                $currency_value=$currencies->currencies[$currency=$FSESSION->currency]['value'];
                                //just in case we get a NULL return make it = 1
                                 if($currency_value==0){$currency_value=1;}
                                //we will use this in the provision to pass the value over in Ajax
                                //there is no function in the currencies class to do this without adding in the symbols decimal points etc
                
		
					switch($sale['sale_deduction_type']){
						
						case 1://percentage reduction
							$choice_text=$sale["choice_text"] . " (" . number_format($sale["sale_deduction_value"],2). "%)";
							$discounted_price_net =$currency_value* ($products_original_price-($products_original_price*$sale["sale_deduction_value"])/100);
							$discounted_price= tep_add_tax($discounted_price_net, $tax_rate);
							break;
						
						case 0://subtract
							$choice_text=$sale["choice_text"] . " (<s><font color='red'>" . $currencies->format(tep_add_tax($products_original_price, $tax_rate)) . "</font></s>&nbsp;" . $currencies->format(tep_add_tax($products_original_price-$sale["sale_deduction_value"], $tax_rate)) . ")";
							$discounted_price_net = $currency_value*($products_original_price-$sale["sale_deduction_value"]);
							$discounted_price= tep_add_tax($discounted_price_net, $tax_rate);
							break;
						
						case 2://new price added currency value 2015
							$choice_text=$sale["choice_text"] . " (" . $currencies->format(tep_add_tax($sale["sale_deduction_value"],$tax_rate)). ")";
							$discounted_price_net = $currency_value*($sale["sale_deduction_value"]);
							$discounted_price= tep_add_tax($discounted_price_net, $tax_rate);							
							break;
					}
				
				   $sale['description']=$choice_text;
                                    $sale['discounted_price']=$discounted_price;
                                    $discounts_available[]=$sale;
																														}
												}
				$arr['discounts']=$discounts_available;
				$arr['products_name']= $this->tep_product_to_name($products_id);
				$arr['show_name']= $this->cat_name($cPath);
				
				//end new code
			}

			/* log attempt - if live-logging is enabled */
			else {
				if(SEATPLAN_LOGGING=='true'){
					$msg = 'prevented a duplicate seat in'.$tmp.' cart';
					$log = $this->tep_logSeatPlanEvent(3,(int)$cPath,(int)$products_id,$cid,$FSESSION->ID,$msg);
				}
				$arr =array(
					'denied' => (int)$products_id
				);
			}
			//add a delay
			usleep(500000);
			return $arr;
			}//end box office refund
		} /* end: tep_addSeat() */
		
				//March 2013 ######################################################
		function tep_live_discount($products_id,$discount_id=0,$cPath,$new_price){
			global $FSESSION, $cart;
	
				$cart->contents[$products_id]['discount_id'] = $discount_id;
				if($_SESSION['customer_id']){
					tep_db_query("UPDATE ".TABLE_CUSTOMERS_BASKET." SET discount_id = ".(int)$discount_id." WHERE customers_id = ".(int)$_SESSION['customer_id']." AND products_id = ".(int)$products_id);
				}
				else {
					tep_db_query("UPDATE ".TABLE_CUSTOMERS_TEMP_BASKET." SET discount_id = ".(int)$discount_id." WHERE customers_id = '" . tep_db_input($FSESSION->ID) . "' AND products_id = ".(int)$products_id);
				}
				

				$arr =array(
					'granted' => (int)$products_id,
					'discount' => 'discount applied',
					'discount_id' => $discount_id,
					'products_name' => $this->tep_product_to_name($products_id),
					'show_name' => $this->cat_name($cPath),
					'ga_in_cart' =>1,
					'discount_price'=>$new_price/1000 //divide by 1000 to get correct value
					);

			return $arr;
			}

		function tep_removeSeat($products_id, $cPath){
			global $FSESSION, $cart;
			
			//box office refund
			// look at products_id for a hyphen
			
			if (stristr($products_id,'-')){
			   //GA product link to order
			   $refundArray = explode('-', $products_id);#[0]= pid. [1]= order_id
			   
			   tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id ='" . (int)$FSESSION->customer_id . "' and products_id= '" . tep_db_input($refundArray[0]) . "' and  old_orders_id= '" . tep_db_input($refundArray[1]) . "'");
			   
			   return $arr;
			   
			   }else{
			   //original code
			$cart->remove($products_id);
			if($_SESSION['customer_id']){
				tep_db_query("DELETE FROM ".TABLE_CUSTOMERS_BASKET." where customers_id = ".(int)$_SESSION['customer_id']." AND products_id = ".(int)$products_id);
				$cid = $_SESSION['customer_id'];
				$tmp = '';
			}
			else {
				tep_db_query("DELETE FROM ".TABLE_CUSTOMERS_TEMP_BASKET." where customers_id = '" . tep_db_input($FSESSION->ID) . "' AND products_id = ".(int)$products_id);
				$cid = 0;
				$tmp = ' temp';
			}
			/* log removal - if live-logging is enabled */
			$msg = 'removed from'.$tmp.' cart';
			if(SEATPLAN_LOGGING=='true'){$log = $this->tep_logSeatPlanEvent(2,(int)$cPath,(int)$products_id,$cid,$FSESSION->ID,$msg);}
			return $arr;
			}//end original code
		} /* end: tep_removeSeat() */

		function tep_getAvailableStock($products_id){
			
			$sql1 = 'SELECT products_quantity AS current_stock FROM products WHERE products_id = '.(int)$products_id;
			$result1 = tep_db_query($sql1);
			$arr1 = tep_db_fetch_array($result1);
			
			$sql2='SELECT SUM(customers_basket_quantity) AS currently_in_carts FROM carts_united WHERE products_id = '.(int)$products_id;
			$result2 = tep_db_query($sql2);
			$arr2 = tep_db_fetch_array($result2);
			
			$in_stock = $arr1['current_stock'];
			$in_cart = $arr2['currently_in_carts'];
			
			tep_db_free_result($result1);
			tep_db_free_result($result2);
			$available = ($in_stock-$in_cart);
			return $available;
		} /* end: tep_getAvailableStock() */

		function tep_general_admission($products_id, $quantity=0, $cPath=0, $discount_id=0){
			global $FSESSION, $cart;
			
			/* get strings for logging */
			if($_SESSION['customer_id']){$cid = $_SESSION['customer_id'];$tmp = '';}else{$cid = 0;$tmp = ' temp';}
            
            ## gift vouchers ##
             if ($cart->check_for_vouchers() == true){
                
                $arr =array('voucher' => (int)$products_id); 
                return $arr;
                exit();
                
             }
			############# start of new code May 2012 ######################
			//this function also used to add discount from the product_info page for P tickets
			//so we need to run a little check here to see if the P ticket is already in the cart and set a flag
			//so let's try this
			//grab the cart array 
			if($_SESSION['customer_id']){
			$sql = "SELECT * FROM ".TABLE_CUSTOMERS_BASKET." WHERE customers_id = ".(int)$_SESSION['customer_id'];
			$result = tep_db_query($sql);
			$my_cart_array = array();
			while($product = tep_db_fetch_array($result)){array_push($my_cart_array, $product['products_id']);}
			tep_db_free_result($result);
			}
			else {
			$sql = "SELECT * FROM ".TABLE_CUSTOMERS_TEMP_BASKET." WHERE customers_id = '".$FSESSION->ID."'";
			$result = tep_db_query($sql);
			$my_cart_array = array();
			while($product = tep_db_fetch_array($result)){array_push($my_cart_array, $product['products_id']);}
			tep_db_free_result($result);
			}
				
			//search the array for the products_id								
			// for P products only									
			$in_my_cart='';
			$is_seat = $this->tep_checkIfSeat($products_id);
			if(array_search($products_id,$my_cart_array)!== FALSE ){$in_my_cart=1;}

			// add check for number of items in cart for P tickets
					if ( is_numeric(MAX_IN_CART_AMOUNT) && (MAX_IN_CART_AMOUNT>0) ) {//if we have a numeric value >0
						if (($cart->count_contents()>=MAX_IN_CART_AMOUNT  && $in_my_cart!==1) //not sure if we need this one
						
						|| (($cart->count_contents()+(int)$quantity) > MAX_IN_CART_AMOUNT && $in_my_cart!==1) )
						
						{
									
										$arr =array(
										'max' => (int)$products_id,
											); 

										}
								
						}	 //end quantity check for P tickets
			{
			$available = $this->tep_getAvailableStock($products_id);
			$is_seat = $this->tep_checkIfSeat($products_id);
			
			if($available >= $quantity || $is_seat){
				$limited=false;
				/* add product to cart */
				if(!$is_seat){//GA tickets
				//we need to check max_in_cart and limit to that
					if ( is_numeric(MAX_IN_CART_AMOUNT) && (MAX_IN_CART_AMOUNT>0) ) {//if we have a numeric value >0
					 if(($cart->count_contents()+(int)$quantity) > MAX_IN_CART_AMOUNT ) {//limit quantity
					 	$quantity=MAX_IN_CART_AMOUNT - $cart->count_contents();
							
					 	$cart->add_cart($products_id, ($cart->get_quantity($products_id) + $quantity));
					 	$limited=true;
					 
					 }else{//allow quantity
					 
					 $cart->add_cart($products_id, ($cart->get_quantity($products_id) + $quantity));
					 }	
				
					}else{
					$cart->add_cart($products_id, ($cart->get_quantity($products_id) + $quantity));
					}
				}
				else {//P tickets
					$cart->add_cart($products_id, 1);
					$available=1;
				}
				
				/* setting the discount */
				$cart->contents[$products_id]['discount_id'] = $discount_id;
				if($_SESSION['customer_id']){
					tep_db_query("UPDATE ".TABLE_CUSTOMERS_BASKET." SET discount_id = ".(int)$discount_id." WHERE customers_id = ".(int)$_SESSION['customer_id']." AND products_id = ".(int)$products_id);
				}
				else {
					tep_db_query("UPDATE ".TABLE_CUSTOMERS_TEMP_BASKET." SET discount_id = ".(int)$discount_id." WHERE customers_id = '" . tep_db_input($FSESSION->ID) . "' AND products_id = ".(int)$products_id);
				}
				
				/* log success - if live logging is enabled */
				if(SEATPLAN_LOGGING=='true'){
					//$msg = 'GA added to'.$tmp.' cart ('.$cart->get_quantity($products_id) + $quantity.' in cart now)';
					$log = $this->tep_logSeatPlanEvent(2,(int)$cPath,(int)$products_id,$cid,$FSESSION->ID,$msg);
				}
				$arr =array(
					'granted' => (int)$products_id,
					'ga_in_cart' => $cart->get_quantity($products_id),
					'ga_added' => $quantity,
					'ga_available_stock' => ($available-$quantity),
					'discount_id' => $discount_id,
					'products_name' => $this->tep_product_to_name($products_id)
					);
					//little bit to pass limited
					if($limited==true){$arr['discount'] = 'discount applied';}
			}
			
			else {
				
				/* log attempt - if live logging is enabled */
				if(SEATPLAN_LOGGING=='true')
				{
					$msg = 'GA out of stock in'.$tmp.' cart (requested:'.$quantity.' / available:'.$available.')';
					$log = $this->tep_logSeatPlanEvent(3,(int)$cPath,(int)$products_id,$cid,$FSESSION->ID,$msg);
				}
				$arr =array(
					'denied' => (int)$products_id,
					'ga_available_stock' => $available);
			}
			}
			
			return $arr;
		} /* end: tep_general_admission() */
		
		function tep_general_admission_refund($products_id, $quantity=0, $cPath=0, $discount_id=0, $order_id=0)
		{
			global $FSESSION, $cart;
			//this function has to determine the origin of a GA ticket with respect to an order - so the order_id needs to be passed
			
			if($order_id <1)
			{
					$arr =array(
					'denied' => (int)$products_id);
			
			return $arr;
			exit();
			}
	
	     
			$cart->add_cart_refund($products_id, ($cart->get_quantity($products_id) + $quantity),$order_id);

				$arr =array(
					'granted' => (int)$products_id.'-'.$order_id,
					'ga_in_cart' => $cart->get_quantity($products_id),
					'order_id'=>$order_id,
					'product_id'=>(int)$products_id,
					'ga_added' => $quantity,
					'ga_available_stock' => ($available-$quantity),
					'discount_id' => $discount_id,
					'products_name' => $this->tep_product_to_name($products_id).' [order:'.$order_id.']'
					);
					//little bit to pass limited
					if($limited==true){$arr['discount'] = 'discount applied';}

			return $arr;
		} /* end: tep_general_admission_refund() */

		function tep_checkIfSeat($products_id)
		{
			$bool = false;
			$sql = "
			SELECT 
				product_type
			FROM ".
				TABLE_PRODUCTS."
			WHERE
				products_id = ".(int)$products_id;
			$result = tep_db_query($sql);
			$check = tep_db_fetch_array($result);
			if($check['product_type']=='P'){$bool = true;}
			tep_db_free_result($result);
			return $bool;
		} /* end: tep_checkIfSeat() */

		/* returns the age of a cart in seconds */
		function tep_getCartAge($customer_id){
			global $cart;
			if($cart->count_contents()==0){return -1;}
			$sql = "
				SELECT
					TIME_TO_SEC(TIMEDIFF(SYSDATE(),MIN(customers_basket_date_added))) AS age
				FROM ".
					TABLE_CUSTOMERS_BASKET."
				WHERE 
					customers_id LIKE ".(int)$customer_id;
			$result = tep_db_query($sql);
			$arr = tep_db_fetch_array($result);
			tep_db_free_result($result);
			return $arr['age'];
		}
		
		
		/* returns the age of a cart in seconds */
		function tep_getTempCartAge(){
			global $cart, $FSESSION;
			if($cart->count_contents()==0){return -1;}
			$sql = "
				SELECT
					TIME_TO_SEC(TIMEDIFF(SYSDATE(),MIN(customers_basket_date_added))) AS age
				FROM ".
					TABLE_CUSTOMERS_TEMP_BASKET."
				WHERE 
					customers_id LIKE '".$FSESSION->ID."'";
			$result = tep_db_query($sql);
			$arr = tep_db_fetch_array($result);
			tep_db_free_result($result);
			return $arr['age'];
		}
		
		
		function tep_logSeatPlanEvent($log_level,$cPath,$products_id,$customers_id,$sesskey,$event){
			if($products_id != 0){$products_name = $this->tep_product_to_name($products_id);}else{$products_name='&nbsp;';}
			$sql='
				INSERT INTO
					`seatplan_events`
				(`log_level`, `cPath`, `products_id`, `products_name`, `customers_id`, `sesskey`, `timestamp`, `event`)
				VALUES ('.(int)$log_level.', '.(int)$cPath.', '.(int)$products_id.', "'.$products_name.'", '.(int)$customers_id.', "'.$sesskey.'", NOW(), "'.$event.'")
			';
			tep_db_query($sql);
		} /* end: tep_logSeatPlanEvent() */
// we need to add the language here
		function tep_product_to_name($products_id)
		{
		    global $FSESSION;
			$sql = "SELECT products_name FROM ".TABLE_PRODUCTS_DESCRIPTION." WHERE products_id = ".(int)$products_id." AND
						language_id = " . (int) $FSESSION->languages_id ;
			$result = tep_db_query($sql);
			$row = tep_db_fetch_array($result);
			$products_name = $row['products_name'];
			tep_db_free_result($result);
			return $products_name;
		} /* end: tep_product_to_name() */
		
		function cat_name($cPath=0)
		{
			global $FSESSION;
			$sql= "SELECT categories_heading_title as cht FROM " . TABLE_CATEGORIES_DESCRIPTION . "  WHERE categories_id= '" . $cPath . "' and language_id = '" . (int)$FSESSION->languages_id . "'";
			$arr = tep_db_fetch_array(tep_db_query($sql));
			return $arr['cht'];
		}

		//October 2012 new function s
		// Get the total per show in the temp basket
		function  tep_get_show_total_temp($products_id, $customer_id)
		{
			$temp_basket_query_raw = "select cb.products_id, p.products_model, cb.customers_basket_quantity from " . TABLE_CUSTOMERS_TEMP_BASKET . " cb, " . TABLE_PRODUCTS . " p WHERE customers_id = '" . $customer_id . "' and p.products_id = cb.products_id";

			$temp_basket_query     = tep_db_query($temp_basket_query_raw);
			$show_total            = array();
			while ($result = tep_db_fetch_array($temp_basket_query)) 
			{
				if (isset($show_total[$result['products_model']])) 
				{
					$show_total[$result['products_model']] = $show_total[$result['products_model']] + $result['customers_basket_quantity'];

				} //isset($show_total[$result['products_model']])
				else {
					$show_total[$result['products_model']] = $result['customers_basket_quantity'];
				}
			} //$result = tep_db_fetch_array($temp_basket_query)
			return $show_total;
			//returns array in format date=> quantity
		}
		function  tep_get_show_total($products_id, $customer_id)
		{

			$basket_query_raw = "select cb.products_id, p.products_model, cb.customers_basket_quantity from " . TABLE_CUSTOMERS_BASKET . " cb, " . TABLE_PRODUCTS . " p WHERE customers_id = '" . $customer_id . "' and p.products_id = cb.products_id";

			$basket_query     = tep_db_query($basket_query_raw);
			$show_total       = array();
			while ($result = tep_db_fetch_array($basket_query)) 
			{
				if (isset($show_total[$result['products_model']])) 
				{
					$show_total[$result['products_model']] = $show_total[$result['products_model']] + $result['customers_basket_quantity'];
				} //isset($show_total[$result['products_model']])
				else {
					$show_total[$result['products_model']] = $result['customers_basket_quantity'];
				}
			} //$result = tep_db_fetch_array($basket_query)

			//OK now go get the stuff from the orders table
			$customers_query_raw = "select  c.customers_firstname, sum(op.products_quantity ) as ordersum, op.products_model from " . TABLE_CUSTOMERS . " c, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS . " o where c.customers_id = o.customers_id and o.orders_id = op.orders_id and c.customers_id='" . $customer_id . "' group by op.products_model order by op.products_model";

			$customers_query     = tep_db_query($customers_query_raw);
			while ($customers = tep_db_fetch_array($customers_query)) 
			{
				if (isset($show_total[$customers['products_model']]))
				{
					$show_total[$customers['products_model']] = $show_total[$customers['products_model']] + $customers['ordersum'];
				} //isset($show_total[$customers['products_model']])
				else {
					$show_total[$customers['products_model']] = $customers['ordersum'];
				}
			} //$customers = tep_db_fetch_array($customers_query)
			return $show_total;
		}
		function  tep_product_to_show($products_id)
		{
			$sql            = "SELECT products_model FROM " . TABLE_PRODUCTS . " WHERE products_id = " . (int) $products_id;
			$result         = tep_db_query($sql);
			$row            = tep_db_fetch_array($result);
			$products_model = $row['products_model'];
			tep_db_free_result($result);
			return $products_model;
		}
		
	function  tep_renderSeatplanRefund($cPath)
		{
			global $FSESSION, $currencies, $manufacturers_id;
			if(DESIGN_MODE=='no'){
			$hide=" style=\"display:none\"";	
			}
			if ($_SESSION["draggable"]=="yes" && SET_GRID_BACKGROUND=="yes")
			{echo "<style>div.seatplan {background:whitesmoke url(images/grid_20.png) repeat;}</style>" . "\n";
			echo '<span class="sp_text">'. DESIGN_NOTE. '</span>';}
			if(tep_not_null($manufacturers_id)){
			$html   = '<!-- start: '.$manufacturers_id.' DESIGN MODE -->' . "\n" . '<div id="sp' . $cPath . '" class="seatplan" '.$hide.'><ul class="r">' . "\n";
			}else
			{
			$html   = '<!-- start: SEAT PLAN MODE -->' . "\n" . '<div id="sp' . $cPath . '" class="seatplan">' . "\n";	
			}

			$sql    = "

					SELECT DISTINCT
						p.products_status,
						p.color_code,
						p.products_id,
						p.products_model,
						pd.products_name,
						pd.products_number,
						pd.html,
						p.parent_id,
						p.products_ordered,
						p.products_tax_class_id,
						p.product_type,
						p.products_price,
						p.restrict_to_groups,
						p.products_x,
						p.products_y,
						p.products_w,
						p.products_r,
						p.products_sx,
						p.products_sy,
						p.products_h

					FROM " . TABLE_PRODUCTS . " p

					JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd
					ON
						pd.products_id = p.products_id
					WHERE
						p.product_type='P'

					
					AND
						p.parent_id = '" . $cPath . "'
					AND
						pd.language_id = '" . (int) $FSESSION->languages_id . "'
					ORDER BY
						p.products_id
					ASC";

			$result = tep_db_query($sql);
			$i      = 0;

			//get the customerGroupID for the currently logged-in user. BOOL false if not logged-in.
			$customerGroup = $this->tep_getCustomerGroupID();   

			while ($seat = tep_db_fetch_array($result)) 
			{

				//get the array RestrictToGroups for this seat
				$arryRestrictToGroups = explode(',', $seat['restrict_to_groups']); 
				//set the seatRestricted flag for the seat, based on whether there's an element in the array
				$seatRestricted = ($arryRestrictToGroups[0] > "" ? true : false );

				//if the seat's status != 0 (available) 
					if ( $seat['products_status'] != 0 ) 
					{    
						//if the seat is restricted AND the user belongs to RestrictToGroup
						if ($seatRestricted && in_array($customerGroup, $arryRestrictToGroups) ) 
						{
							$titlePrice = $currencies->format($seat['products_price']);
						} 
						//elseif the seat is restricted AND the user does not belong to the RestrictToGroup
						elseif ($seatRestricted && !in_array($customerGroup, $arryRestrictToGroups) ) 
						{
							$titlePrice = " RESTRICTED ";
						}
						//otherwise seat is not restricted
						else {
							$titlePrice = $currencies->format($seat['products_price']);
						}
					} //$seat['products_status'] != 0
				else 
				{
					$titlePrice="" . SOLD . "";
				}
				if ( $seat['products_status'] == 3 ) 
				{  
					$seat['products_status']=2;
				}
				switch ($seat['html']) 
				{
					
					case 'Y':
						/* line wrap */
						if ($i != 0) {
							$html .= "</ul>\n";
						} //$i != 0
						$html .= "<ul class='r'>";
						$i++;
						break;

					case 'W':
						/* line wrap */
						if ($i != 0) {
							$html .= "</ul>";
							$html .= "<div class='sp_text'>". BALCONY_TEXT. "</div>\n";
						} //$i != 0
						$html .= "<ul class='r'>";
						$i++;
						break;
					case 'X':
						/* line wrap height (gap below) */
						if ($i != 0) {
							$html .= "</ul>\n";
						} //$i != 0
						$html .= "<ul id='gap' class='r'>";
						$i++;
						break;
					
				} //$seat['html']


				switch ($seat['products_status']) 
				{
					
					case 0:
					
					//cancel fallthrough for box office:
					if(($_SESSION['customer_country_id']==999)&&(BO_POPOVER=='yes'))
					{
					//get the order number from the product_id  $seat['products_id']
					$sql2 = "
							SELECT
								orders_id 
							FROM
								orders_products
							WHERE
								products_id ='".$seat['products_id']."'
							LIMIT 1";
				
							$result2 = tep_db_query($sql2);
							$x_title = tep_db_fetch_array($result2);
							$x_order_id = $x_title['orders_id'];
					// get the customers name 
					    $order_query = tep_db_query("select customers_id, customers_name from " . TABLE_ORDERS . " where orders_id = '" . (int)$x_order_id . "'");
	                    $order = tep_db_fetch_array($order_query);		
							
							$titlePrice='order #:'.$x_order_id.'<br />';
							$titlePrice.=$order['customers_name'].'<br />';
							
							
			}//end of Box Office
					$html .= '<li class="s ' . $this->color2class($seat['color_code']) . '" id="s' . $seat['products_id'] . '" title="' . $seat['products_name'] . " - " . $titlePrice . '">' . $seat['products_number'] . "</li>";						
					break;
					
					case 1:
						/* available seats */
					case 2:
						/* blank seats */
						$html .= "<li class='b'></li>";
						break;
					case 3:
						/* letters */
						$html .= '<li  title="' . $seat['products_name']  . '" class="s sd '.strrev($this->color2class($seat['color_code'])) .'" id="s' . $seat['products_id'] . '">' . $seat['products_number'] . "</li>";
						break;
					case 4:
						/* row letters */
						$html .= '<li class="ltr">' . $seat['products_number'] . "</li>";
						break;
					case 5:
						/* half-width seats */
						$html .= "<li class='h'></li>";
						break;
					case 7:
						/* alt blank */
						$html .= "<li class='c'></li>";
						break;
					case 8:
						/* 2021 blank seats */
						$html .= "<li class='b'></li>";
						break;

				} //$seat['products_status']
			} //$seat = tep_db_fetch_array($result)
			/* closing the last ul and div#seatplan */
			$html .= "	</ul>\n";
			$html .= "</div>\n";
			$html .= "<!-- end: AJAX Seatplan -->\n";
			tep_db_free_result($result);
			return $html;
		}
		/* end: tep_renderSeatplanRefund() */
		
	function canx_order($order_id, $note='')
	{
    global $FSESSION;
    // if we have the order_id then do stuff
    if (tep_not_null($order_id)) 
	{
        //grab customers name from order
        $cust_query = tep_db_query("select customers_name from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");
        if (tep_db_num_rows($cust_query) ) 
		{
            $cust_query_result = tep_db_fetch_array($cust_query);
            $cust_name = $cust_query_result['customers_name'];
		}
        //change the order status
        $sql_data_array = array('orders_status' => MODULE_PAYMENT_BOR_ORDER_CANX_STATUS_ID,
        'customers_name'=> 'Reservations-cancelled::'.$cust_name)
        ;
        tep_db_perform(TABLE_ORDERS, $sql_data_array, "update", "orders_id='".$order_id."'");
        $sql_data_array = array('orders_id' => $order_id,
        'orders_status_id' => MODULE_PAYMENT_BOR_ORDER_CANX_STATUS_ID,
        'date_added' => date('Y-m-d H:i:s',getServerDate(false)),
        'customer_notified' => 0,
        'comments' => $note );
        tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
		include_once('includes/functions/ga_tickets.php');
        //reset the products quantity and status
		//n.b. that in the orders_products table that the products_type field does not reflect the products_type field in
		//the products table - you need to use events_type
        $order_query = tep_db_query("select products_id, products_quantity, events_type from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . $order_id. "'");
        while ($order = tep_db_fetch_array($order_query)) 
		{
            tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = products_quantity + " . $order['products_quantity'] . ", products_ordered = products_ordered - " . $order['products_quantity'] . ", products_status='1' where products_id = '" . (int)$order['products_id'] . "'");
			//tep_db_query("update " . TABLE_ORDERS_PRODUCTS . " set orders_products_status = '".MODULE_PAYMENT_BOR_ORDER_CANX_STATUS_ID."' where products_id = '" . (int)$order['products_id'] . "'");
			tep_db_query("update " . TABLE_ORDERS_PRODUCTS . " set orders_products_status = '".MODULE_PAYMENT_BOR_ORDER_CANX_STATUS_ID."' where products_id = '" . (int)$order['products_id'] . "' AND orders_id = '".$order_id."'");
			if (function_exists('ga_check_process_restock'))
			{
			ga_check_process_restock((int)$order['products_id'], $order['products_quantity'], $order['events_type']);	
			}													
        }
        //give the order total a value of 0.00
        tep_db_query("delete from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . $order_id. "'");
        tep_db_query("update " . TABLE_ORDERS_PRODUCTS . " set products_quantity = '0' where orders_id = '" . $order_id . "'");
        tep_db_query("insert into " . TABLE_ORDERS_TOTAL. " (orders_id, title, text, value, class, sort_order) values ('" . $order_id . "', 'Total', '0.00', '0','ot_total', '99')");
        return false;
		}
	}
		function  tep_renderSeatplanReservation($cPath)
		{
			global $FSESSION, $currencies;
		// delete old orders
	    $time_now = date('Y-m-d H:i:s',getServerDate(false));
		  $bor_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " 
										where bor_datetime <= '" . $time_now . "' 
										AND bor_datetime > 0
										AND orders_status = '".MODULE_PAYMENT_BOR_ORDER_STATUS_ID."'");
			if (tep_db_num_rows($bor_query) > 0 ) 
			{ 
			while ($bor_results = tep_db_fetch_array($bor_query))
			{
				$this->canx_order($bor_results['orders_id'],'Order automatically restocked');
			}
			}
	
			$html   = '<!-- start: AJAX Seatplan Martin Zeitler -->' . "\n" . '<div id="sp' . $cPath . '" class="seatplan">' . "\n";
			$sql    = "

					SELECT DISTINCT
						p.products_status,
						p.color_code,
						p.products_id,
						pd.products_name,
						pd.products_number,
						pd.html,
						p.parent_id,
						p.product_type,
						p.products_price,
						p.manufacturers_id,
						p.restrict_to_groups

					FROM " . TABLE_PRODUCTS . " p
					JOIN " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c
					ON
						p2c.products_id = p.products_id
					JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd
					ON
						pd.products_id = p.products_id
					WHERE
						p.product_type='P'
					AND
						p.parent_id = '" . $cPath . "'
					AND
						pd.language_id = '" . (int) $FSESSION->languages_id . "'
					ORDER BY
						p.products_id
					ASC";

			$result = tep_db_query($sql);
			$i      = 0;

			//get the customerGroupID for the currently logged-in user. BOOL false if not logged-in.
			$customerGroup = $this->tep_getCustomerGroupID();   

			while ($seat = tep_db_fetch_array($result)) {

				//get the array RestrictToGroups for this seat
				$arryRestrictToGroups = explode(',', $seat['restrict_to_groups']); 
				//set the seatRestricted flag for the seat, based on whether there's an element in the array
				$seatRestricted = ($arryRestrictToGroups[0] > "" ? true : false );

				//if the seat's status != 0 (available) 
				if ( $seat['products_status'] != 0 ) 
				{    
					//if the seat is restricted AND the user belongs to RestrictToGroup
					if ($seatRestricted && in_array($customerGroup, $arryRestrictToGroups) ) {
						$titlePrice = $currencies->format($seat['products_price']);
					} 
					//elseif the seat is restricted AND the user does not belong to the RestrictToGroup
					elseif ($seatRestricted && !in_array($customerGroup, $arryRestrictToGroups) ) {
						$titlePrice = " RESTRICTED ";
					}
					//otherwise seat is not restricted
					else 
					{
						$titlePrice = $currencies->format($seat['products_price']);
					}
				} //$seat['products_status'] != 0
				else {
					$titlePrice="" . SOLD . "";
				}
				switch ($seat['html']) 
				{
					
					case 'Y':
						/* line wrap */
						if ($i != 0) {
							$html .= "</ul>\n";
						} //$i != 0
						$html .= "<ul class='r'>";
						$i++;
						break;

					case 'W':
						/* line wrap */
						if ($i != 0) {
							$html .= "</ul>";
							$html .= "<div class='sp_text'>". BALCONY_TEXT. "</div>\n";
						} //$i != 0
						$html .= "<ul class='r'>";
						$i++;
						break;
					case 'X':
						/* line wrap height (gap below) */
						if ($i != 0) {
							$html .= "</ul>\n";
						} //$i != 0
						$html .= "<ul id='gap' class='r'>";
						$i++;
						break;
				} //$seat['html']


				switch ($seat['products_status']) 
				{
					//all products sold have status = 0
					case 0:
					
					if(($_SESSION['customer_country_id']==999))
					{
						//get the order number from the product_id  $seat['products_id']
						$sql2 = "
							SELECT
								orders_id, bor_random_id, orders_products_status
							FROM
								orders_products
							WHERE
								products_id ='".$seat['products_id']."'
							AND
							   orders_products_status = '".MODULE_PAYMENT_BOR_ORDER_STATUS_ID."'
							
							LIMIT 1";
				
							$result2 = tep_db_query($sql2);
							$x_title = tep_db_fetch_array($result2);
							$x_order_id = $x_title['orders_id'];
							
							//order comments are wanted.....
							$comments = "ORDER COMMENTS: ";
							$sql3 = "
								SELECT 
									comments 
								FROM
									orders_status_history
								WHERE
									orders_id = '".$x_order_id."'
							    AND
								    TRIM(comments) <> ''
								ORDER BY
									orders_status_history_id ASC";
									
								$comments_query = tep_db_query($sql3); 
								
								
								
		                        $comments_count = tep_db_num_rows($comments_query);
		                       if ($comments_count > 0 )
							   {
								while($comments_result = tep_db_fetch_array($comments_query))
								{
									$comments .= $comments_result['comments'];
									$comments .=  '<br>';
								   }
								   //maybe reduce size of comments???
								}else
								{
								$comments = '';
								}
					
						$titlePrice='RESERVED: order #:'.$x_order_id.'<br />';
						//$titlePrice.= 'PIN: '.$x_title['bor_random_id'].'<br />';//now in comments
							
						if(	$x_title['orders_products_status'] == MODULE_PAYMENT_BOR_ORDER_STATUS_ID)
						{
						//display seat			
						$html .= '<a href="bor_listings.php?edit='.$x_order_id.'"><li class="s ' . $this->color2class($seat['color_code']) . '" id="s' . $seat['products_id'] . '" title="' . $seat['products_name'] . " - " . $titlePrice . $comments.'">' . $seat['products_number'] . "</li></a>";	
						}
						else
						{
							$html .= "<li class='b'></li>";
						}	
					
					}				
					break;
					case 1:
						/* available seats */
						
					case 2:
						/* blank seats */
						$html .= "<li class='b'></li>";
						break;
					case 3:
						/* letters */
						$html .= '<li  title="' . $seat['products_name']  . '" class="s sd '.strrev($this->color2class($seat['color_code'])) .'" id="s' . $seat['products_id'] . '">' . $seat['products_number'] . "</li>";
						break;
					case 4:
						/* row letters */
						$html .= '<li class="ltr">' . $seat['products_number'] . "</li>";
						break;
					case 5:
						/* half-width seats */
						$html .= "<li class='h'></li>";
						break;
					case 7:
						/* alt blank */
						$html .= "<li class='c'></li>";
						break;
					case 8:
						/* 2021 blank seats */
						$html .= "<li class='b'></li>";
						break;

				} //$seat['products_status']
			} //$seat = tep_db_fetch_array($result)
			/* closing the last ul and div#seatplan */
			$html .= "	</ul>\n";
			$html .= "</div>\n";
			$html .= "<!-- end: AJAX Seatplan -->\n";
			tep_db_free_result($result);
			return $html;
		}
		/* end: tep_renderSeatplanReservation() */
		
		
		function  tep_renderSeatplanBlock($cPath)
		{
			global $FSESSION, $currencies;
			$html   = '<!-- start: AJAX Seatplan -->' . "\n" . '<div id="sp' . $cPath . '" class="seatplan">' . "\n";

			$sql    = "

					SELECT DISTINCT
						p.products_status,
						p.color_code,
						p.products_id,
						p.products_model,
						pd.products_name,
						pd.products_number,
						pd.html,
						p.parent_id,
						p.products_ordered,
                        p.products_tax_class_id,
						p.product_type,
						p.products_price,
						p.manufacturers_id,
						p.restrict_to_groups

					FROM " . TABLE_PRODUCTS . " p

					JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd
					ON
						pd.products_id = p.products_id
					WHERE
						p.product_type='P'
					AND
						p.parent_id = '" . $cPath . "'
					AND
						pd.language_id = '" . (int) $FSESSION->languages_id . "'
					ORDER BY
						p.products_id
					ASC";

			$result = tep_db_query($sql);
			$i      = 0;

			//get the customerGroupID for the currently logged-in user. BOOL false if not logged-in.
			$customerGroup = $this->tep_getCustomerGroupID();   

			while ($seat = tep_db_fetch_array($result)) {

				//get the array RestrictToGroups for this seat
				$arryRestrictToGroups = explode(',', $seat['restrict_to_groups']); 
				//set the seatRestricted flag for the seat, based on whether there's an element in the array
				$seatRestricted = ($arryRestrictToGroups[0] > "" ? true : false );
                                
                 //2015 the price is NET it does not include TAX or SPECIALS
                 //use the tax_rate to get these
                                
                $tax_rate=tep_get_tax_rate($seat["products_tax_class_id"]);
		
		if($new_price = tep_get_products_special_price($seat['products_id'])) {
                            $products_price=tep_add_tax($new_price, $tax_rate);
			                $products_tot_price=$currencies->format($products_price);		
			                $products_original_price=$new_price;
		}
		else {
							$products_price=tep_add_tax(tep_get_plain_products_price($seat['products_price']), $tax_rate);
							$products_tot_price=$currencies->format($products_price);
							$products_original_price=$seat['products_price'];
		}
		// discount is not shown - only specials price and tax
                // discount in next stage
		

				//if the seat's status != 0 (available) 
				if ( $seat['products_status'] != 0 ) 
				{    
					//if the seat is restricted AND the user belongs to RestrictToGroup
					if ($seatRestricted && in_array($customerGroup, $arryRestrictToGroups) ) 
					{
						$titlePrice = $products_tot_price;
					} 
					//elseif the seat is restricted AND the user does not belong to the RestrictToGroup
					elseif ($seatRestricted && !in_array($customerGroup, $arryRestrictToGroups) ) 
					{
						$titlePrice = " RESTRICTED ";
					}
					//otherwise seat is not restricted
					else {
						$titlePrice = $products_tot_price;
					}
				} //$seat['products_status'] != 0
				else {
					$titlePrice="" . SOLD . "";
				}
				if ( $seat['products_status'] == 0 ) {  

				$seat['color_code']='orange';
				}
				switch ($seat['html']) {
					
					case 'Y':
						/* line wrap */
						if ($i != 0) {
							$html .= "</ul>\n";
						} //$i != 0
						$html .= "<ul class='r'>";
						$i++;
						break;

					case 'W':
						/* line wrap */
						if ($i != 0) {
							$html .= "</ul>";
							$html .= "<div class='sp_text'>". BALCONY_TEXT. "</div>\n";
						} //$i != 0
						$html .= "<ul class='r'>";
						$i++;
						break;
					case 'X':
						/* line wrap height (gap below) */
						if ($i != 0) {
							$html .= "</ul>\n";
						} //$i != 0
						$html .= "<ul id='gap' class='r'>";
						$i++;
						break;
				} //$seat['html']


				switch ($seat['products_status']) {
					
					case 0:
					//case 0:
					
					/* sold seats - falltrough */
					case 1:
						// available seats 
						$html .= '<li class="s klm ' . $this->color2class($seat['color_code']) . '" id="s' . $seat['products_id'] . '" data-rel="'.$products_original_price.'" title="' . $seat['products_name']  . '">' . $seat['products_number'] . "</li>";						
						break;
					case 2:
						/* blank seats */
						$html .= "<li class='b'></li>";
						break;
					case 3:
						/* letters */
						$html .= '<li  title="' . $seat['products_name']  . '" class="s sd '.strrev($this->color2class($seat['color_code'])) .'" id="s' . $seat['products_id'] . '">' . $seat['products_number'] . "</li>";
						break;
					case 4:
						/* row letters */
						$html .= '<li class="ltr">' . $seat['products_number'] . "</li>";
						break;
					case 5:
						/* half-width seats */
						$html .= "<li class='h'></li>";
						break;
					case 7:
						/* alt blank */
						$html .= "<li class='c'></li>";
						break;
					case 8:
						/* 2021 blank seats */
						$html .= "<li class='b'></li>";
						break;

				} //$seat['products_status']
			} //$seat = tep_db_fetch_array($result)
			/* closing the last ul and div#seatplan */
			$html .= "	</ul>\n";
			$html .= "</div>\n";
			$html .= "<!-- end: AJAX Seatplan by  Martin Zeitler -->\n";
			tep_db_free_result($result);
			
			return $html;
		}
		/* end: tep_renderSeatplan() */
		
		
			function tep_block_indiv($cats,$name='nothing_entered')
			{
			if($_SESSION['customer_country_id']==999 )
			{
				
			if ($name=='nothing_entered'){
			$sql = "			    
			    SELECT *
				FROM
					products p
				WHERE
					p.product_type = 'P'
				AND
				    p.parent_id IN (".$cats.")";
				
			}else{
			$sql = "
			    
			    SELECT *
				FROM
					products p,
                    products_description pd       
				WHERE
					p.product_type = 'P'
				AND
					p.products_id = pd.products_id
				AND 
					pd.products_name = '".$name."'
				AND
				    p.parent_id IN (".$cats.")";
			}
			$result = tep_db_query($sql);
			//tep_db_free_result($result);
			$arr = array();
			
			
			while($product = tep_db_fetch_array($result)){
				
				switch ($product['products_status']){
					
					case 1:
					  $sql = 
						"UPDATE
							products p 
						SET
							p.products_status = 3,
							p.products_quantity = 0
						WHERE						
							p.products_id = ".$product['products_id']."";
					$updaye = tep_db_query($sql);
			        tep_db_free_result($updaye);
					$arr['changed'][]=$product['parent_id'];
					$arr['blocked'][]=$product['products_id'];
					break;
					
					case 3:
						$arr['already'][]=$product['parent_id'];
					break;
					
					case 0:
						$arr['sold'][]=$product['parent_id'];
					break;
					
					$arr['unknown'][]=$product['parent_id'];
					
				}//end switch
				    
				
				
			}
			return $arr;
			}else{
				return;
			}

		}
	
		function tep_unblock_indiv($cats,$name='nothing_entered')
		{
			if($_SESSION['customer_country_id']==999 )
			{			
			
			if ($name=='nothing_entered'){
			$sql = "			    
			    SELECT *
				FROM
					products p
				WHERE
					p.product_type = 'P'
				AND
				    p.parent_id IN (".$cats.")";
				
			}else{
			$sql = "
			    
			    SELECT *
				FROM
					products p,
                    products_description pd       
				WHERE
					p.product_type = 'P'
				AND
					p.products_id = pd.products_id
				AND 
					pd.products_name = '".$name."'
				AND
				    p.parent_id IN (".$cats.")";
			}
			$result = tep_db_query($sql);
			//tep_db_free_result($result);
			$arr = array();			
			
			while($product = tep_db_fetch_array($result)){
				
				switch ($product['products_status']){
					
					case 3:
					  $sql = 
						"UPDATE
							products p 
						SET
							p.products_status = 1,
							p.products_quantity = 1
						WHERE						
							p.products_id = ".$product['products_id']."";
					$updaye = tep_db_query($sql);
			        tep_db_free_result($updaye);
					$arr['changed'][]=$product['parent_id'];
					$arr['unblocked'][]=$product['products_id'];
					break;
					
					case 1:
						$arr['already'][]=$product['parent_id'];
					break;
					
					case 0:
						$arr['sold'][]=$product['parent_id'];
					break;
					
					$arr['unknown'][]=$product['parent_id'];
					
				}//end switch
				    
			}
			
			return $arr;
			}else{
			return;
			
		}
			}
		}//end class
				
		}
?>