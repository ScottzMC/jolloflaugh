<?php
/*

July 2013 GA ticket functions

*/
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();

############## find out if there is a mixed GA/reserved

function ga_check($product_id, $prod_quantity, $prod_type){

if($prod_type == 'B'){
 //do not check B type tickets
  return false;
 }
$result = array();
//take the product id, find the cPath, explode it and then run a check to see if any cat has a GA setting
$ga_path_array = array();
$ga_path_array = explode('_', tep_get_product_path($product_id));//n.b. tep_get_product path requires products_status=1
//$ga_path_array = array_reverse($ga_path_array);
$max = sizeof($ga_path_array);

			// $products_type_query=tep_db_query("select products_id, product_type from " . TABLE_PRODUCTS . "  where products_id='" . $product_id . " '");
//			  if (tep_db_num_rows($products_type_query)) { 
//						  while($product_t = tep_db_fetch_array($products_type_query)){
//							  $prod_type=$product_t['product_type'];
//						  }
//			  }

if ($max >0){
	for ($i=0; $i<$max; $i++) {
	
 $category_ga_query = tep_db_query("select categories_GA from " . TABLE_CATEGORIES . " where categories_id = '" . $ga_path_array[$i]. "' limit 1");
   			 if (tep_db_num_rows($category_ga_query) ) {
			 	$category_ga = tep_db_fetch_array($category_ga_query);
					if($category_ga['categories_GA'] > 0){

	//if ($category_ga['categories_GA'] == 1){oct 2015
					    $result[]=array('cat_id'=>$ga_path_array[$i],'ga_type'=>$category_ga['categories_GA'],'quantity'=>$prod_quantity);
						//add to session
						if(isset($_SESSION['ga_cat_id_'.$ga_path_array[$i]])){
						$_SESSION['ga_cat_id_'.$ga_path_array[$i]]=$_SESSION['ga_cat_id_'.$ga_path_array[$i]]+$prod_quantity;
																			}else{
						$_SESSION['ga_cat_id_'.$ga_path_array[$i]]=$prod_quantity;													
																			}
								//}oct 2015
					}
														}
								}
			}
return $result;

}//end function
######################
#October 2015 this function may now be obselete as it is being duplicated in checkout_process.php where it looks at family tickets
#########################
function ga_check_process($product_id, $prod_quantity, $prod_type){
$result = array();
//take the product id, find the cPath, explode it and then run a check to see if any cat has a GA setting
$ga_path_array = array();
$ga_path_array = explode('_', ga_get_product_path($product_id));//n.b. tep_get_product path requires products_status=1 
//$ga_path_array = array_reverse($ga_path_array);
$max = sizeof($ga_path_array);

if ($max >0){
	for ($i=0; $i<$max; $i++) {
//reset
$quantity_left_ga = 0;
// loop through the ga_path_array
	$category_ga_query = tep_db_query("select categories_GA,categories_quantity_remaining from " . TABLE_CATEGORIES . " where categories_id = '" . $ga_path_array[$i]. "' limit 1");
   			 if (tep_db_num_rows($category_ga_query)) { 			 
// some data pulled from database so work with it
			        $category_ga = tep_db_fetch_array($category_ga_query);
			 
			 	           if ( $category_ga['categories_GA'] == 2){//combo applies to all products
					$quantity_left_ga=(($category_ga['categories_quantity_remaining'])-($prod_quantity));
					 //update the master quantity
					 	tep_db_query("update " . TABLE_CATEGORIES . " set categories_quantity_remaining = '" .(($category_ga['categories_quantity_remaining'])-($prod_quantity)) . "' where categories_id = '" . $ga_path_array[$i] . "'");
			//update the individual GA quantity to match the overall qty if <
					
			//get all the sub_cats
			$new_cat_array = ga_get_children($ga_path_array[$i]);	
			//add in this one
			$new_cat_array[]=$ga_path_array[$i];
			
			//go look for products with type = G
				 $products_ga_query=tep_db_query("select p.products_id, p.product_type, p.products_quantity from " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_PRODUCTS . " p where p.products_id=p2c.products_id and p2c.categories_id in (".implode(",",$new_cat_array).") and p.product_type='G'");
			//this bit will reduce the remaining individual product numbers to match the overall quantity - it cannot be 
			//reversed using so should we keep it here????
				  if (tep_db_num_rows($products_ga_query)) { 
							  while($products_ga = tep_db_fetch_array($products_ga_query)){
								  
								  $prod_type=$products_ga['product_type'];
							  		if($products_ga['products_quantity'] > $quantity_left_ga){
								tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '".$quantity_left_ga."' where products_id = '" . $products_ga['products_id'] . "'");}
																						}
																						} 
					//OK so now we have updated the category what happens if master quantity <0 (i.e we are getting oversold?)
					 if($quantity_left_ga<=0){
					 	//master ticket quantity exceeded
						//get all products in the and sub categories category and turn them off
						
						 $products_ga_query=tep_db_query("select products_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id in (".implode(",",$new_cat_array).")");
						  if (tep_db_num_rows($products_ga_query)) { 
							  while($products_ga = tep_db_fetch_array($products_ga_query)){
							  	//echo "<br>product found =".$products_ga['products_id'];
								tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . $products_ga['products_id'] . "'");
																						}
																						} 
							  										
					 }// end  if($quantity_left_ga<1)			    	
					}//if ($category_ga['categories_GA'] == 2)
					
					       elseif(($prod_type=='G' && $category_ga['categories_GA'] == 1))
														{
			     //$category_ga = tep_db_fetch_array($category_ga_query);
			 	//if($category_ga['categories_GA']==1){//this is a GA category
					$quantity_left_ga=(($category_ga['categories_quantity_remaining'])-($prod_quantity));
					 //update the master quantity
					 	tep_db_query("update " . TABLE_CATEGORIES . " set categories_quantity_remaining = '" .$quantity_left_ga . "' where categories_id = '" . $ga_path_array[$i] . "'");
						#################################################
									 		  if(MODULE_PAYMENT_PAYPALIPN_RECORD_DATA=='True') {
		  $pid = "select categories_GA,categories_quantity_remaining from " . TABLE_CATEGORIES . " where categories_id = '" . $ga_path_array[$i]. "' limit 1\n update " . TABLE_CATEGORIES . " set categories_quantity_remaining = '" .$quantity_left_ga . "' where categories_id = '" . $ga_path_array[$i] . "'";
          $prod_quantity_o = $category_ga['categories_quantity_remaining'];
		  	notify_log("-----------------------------\n $product_id, $pid,\n $prod_quantity_o\n $prod_quantity -- $quantity_left_ga \n-----------------------------\n");
			                                                }
					//OK so now we have updated the category what happens if master quantity <0 (i.e we are getting oversold?)
					 if($quantity_left_ga<=0){
					 	//master ticket quantity exceeded

						 { 
							 {
							  	//echo "<br>product found =".$products_ga['products_id'];
								tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . $product_id . "'");
																						} //end while
							  										}//end if (tep_db_num_rows($products_ga_query))
					 }// end  if($quantity_left_ga<1)			    	
					//}//end this is a GA category
																				
														
														}


##												
														}
									}
						}
			}

//October 2015
//upgate ga_master_quantity new function
//called from checkout_process by paypal but may need incorporating into other payment modules
//the actual product quantity is updated in the checkout_process code
function ga_check_process_restock($product_id, $prod_quantity, $prod_type){
$result = array();
//take the product id, find the cPath, explode it and then run a check to see if any cat has a GA setting
$ga_path_array = array();
$ga_path_array = explode('_', ga_get_product_path($product_id));//n.b. tep_get_product path requires products_status=1 

$max = sizeof($ga_path_array);

if ($max >0){
	for ($i=0; $i<$max; $i++) {
##
// looping through the ga path array to find the categories that the quantity needs to be applied to.

		 $category_ga_query = tep_db_query("select categories_GA,categories_quantity_remaining from " . TABLE_CATEGORIES . " where categories_id = '" . $ga_path_array[$i]. "' limit 1");
   			 if (tep_db_num_rows($category_ga_query)) { 
			  $category_ga = tep_db_fetch_array($category_ga_query);
			 	if ( $category_ga['categories_GA'] == 2){
					$quantity_left_ga=(($category_ga['categories_quantity_remaining'])+($prod_quantity));
					 //update the master quantity
					 	tep_db_query("update " . TABLE_CATEGORIES . " set categories_quantity_remaining = '" .(($category_ga['categories_quantity_remaining'])+($prod_quantity)) . "' where categories_id = '" . $ga_path_array[$i] . "'");
			
					
			//get all the sub_cats
			//$new_cat_array = ga_get_children($ga_path_array[$i]);	
			//add in this one
			//$new_cat_array[]=$ga_path_array[$i];
			

					//OK so now we have updated the category what happens if master quantity is now positive but the products were previously turned off as it had reached zero
					/* the following block commented out Oct 2015 as not needed for the moment - be advised it is untested
					 if($quantity_left_ga > 0){
					 	//master ticket quantity positive
						//get all products in the and sub categories category and turn them back on
						//as this is working in a bottom up mode it will not turn on products in a lower category
						//e.g. if mixed seating and GA ticket being restocked is in a category directly under the top then
						//siblign categories wil be missed
						
						 $products_ga_query=tep_db_query("select products_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id  = '" . $ga_path_array[$i] . "'");
							  while($products_ga = tep_db_fetch_array($products_ga_query)){							  	
								tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '1' where products_id = '" . $products_ga['products_id'] . "' and products_quantity > 0");
																						}
						//the above will only do the categories above the product being restocked - how about this:
												// (there must be an easier way?)
						//use ga_get_product_path to get cPath
						$the_cpath = ga_get_product_path($product_id);
						//get top category form cPath
						$pieces = explode('_', $the_cpath);
						$the_top_category = $pieces[0];
						//use ga_get_children for an array of sub cats
						$the_whole_show = ga_get_children($the_top_category);
						//add in the top one
						$the_whole_show[] = $the_top_category;
						//traverse that array and run the update
						$max_size = sizeof($the_whole_show);

						if ($max_size >0){
							for ($ix=0; $ix<$max_size; $ix++) {
							    	$products_ga_query2 = tep_db_query("select products_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id  = '" . $the_whole_show[$ix] . "'");
							  while($products_ga2 = tep_db_fetch_array($products_ga_query2)){							  	
								tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '1' where products_id = '" . $products_ga2['products_id'] . "' and products_quantity > 0");
																						}
							
							}
							}
						
																						} 
					*/		  										
							    	
					}
					
					elseif(($prod_type=='G' && $category_ga['categories_GA'] == 1))
														{
	
					$quantity_left_ga=(($category_ga['categories_quantity_remaining'])+($prod_quantity));
					 //update the master quantity
					 	tep_db_query("update " . TABLE_CATEGORIES . " set categories_quantity_remaining = '" .(($category_ga['categories_quantity_remaining'])+($prod_quantity)) . "' where categories_id = '" .  $ga_path_array[$i] . "'");
					//OK so now we have updated the category what happens if master quantity >0 - reset products
					 if($quantity_left_ga > 0){
					 	tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '1' where products_id = '" . $product_id . "' and products_quantity > 0");
																						
							  					}
					 }
																				
														
														}


##												
																			}
													}
								}
			



//cumulative quantity checks for shopping cart
function ga_cart_check(){
global $quantity_error;
//July 2013 new cumultive totals
//any ga_cat_id_x session that gets here should be negative but double check
$quantity_error=TEXT_GA_CART_HEADING;
$over_cat_id=array();
  foreach ($_SESSION as $key => $value) {
    if (substr($key, 0, 9) == "ga_cat_id") {
        if($_SESSION[$key]<0){
		  //we have a category with a negative quantity as a result of this order
		   $over_cat_id[]=substr($key, 10); // returns cat_id and pops it into a new array
		       $max = sizeof($over_cat_id);
		}
    }
	}
if ($max >0){
	for ($i=0; $i<$max; $i++) {
		   		$GA_query = tep_db_query("select c.categories_quantity_remaining, cd.categories_name from " . TABLE_CATEGORIES. " c, categories_description cd   where c.categories_id=cd.categories_id and c.categories_id = '" . $over_cat_id[$i]."' ");
		$GA = tep_db_fetch_array($GA_query);		

			$quantity_error.= $GA['categories_name'].TEXT_GA_CART_CENTRE.$GA['categories_quantity_remaining'].TEXT_GA_CART_RIGHT;
		}//end of while array loop	
		}	

	return $quantity_error;
}

//cumulative quantity checks for shopping cart box office refund
function ga_refund_cart_check(){
     global $cart,$messageStack;
	//for each item in cart
  		$products = $cart->get_products();//exit('<pre>	'.var_dump($products));
		
		for ($i=0, $n=sizeof($products); $i<$n; $i++) {
			if($products[$i]['old_orders_id'] > 0){
			//ga product with an order id
			// compare cart quantity with quantity remaining (using the old waitlist_orders_id)
		  $quan_query=tep_db_query("select products_quantity, waitlist_orders_id from orders_products where orders_id = '".$products[$i]['old_orders_id']."' and products_id ='".$products[$i]['id']."' limit 1");
		  $quan_result= tep_db_fetch_array($quan_query);
		  $quan_orig=$quan_result['products_quantity'];
		  $quan_refund=$quan_result['waitlist_orders_id'];
		  $quan_avail=$quan_orig-$quan_refund; //exit ('.'.$quan_avail);
		  //if quan_avail < qty then exit
		      if($products[$i]['qty'] > $quan_avail){
			       $messageStack->add_session('header', 'You are trying to refund too many tickets - please check the quantities', 'error');
                    tep_redirect(tep_href_link('index.php'));
					exit();
				}
			  }
			}
		
	
	//if old_order_id >0
}

function ga_kill_sessions(){
	  foreach ($_SESSION as $key => $value) {
    if (substr($key, 0, 9) == "ga_cat_id") {
        unset($_SESSION[$key]);
    }
	}
	}
	
// function ga_get_one_level($catId){
    // $query=tep_db_query("SELECT categories_id FROM categories WHERE parent_id='".$catId."'");
    // $cat_id=array();
    // if(tep_db_num_rows($query)>0){
        // while($result=tep_db_fetch_array($query)){
            // $cat_id[]=$result['categories_id'];
        // }
    // }   
    // return $cat_id;
// }

// function ga_get_children($parent_id, $tree_string=array()) {
    // $tree = array();
    // // getOneLevel() returns a one-dimensional array of child ids        
    // $tree = ga_get_one_level($parent_id);     
    // if(count($tree)>0 && is_array($tree)){      
        // $tree_string=array_merge($tree_string,$tree);
    // }
    // foreach ($tree as $key => $val) {
        // ga_get_children($val, $tree_string);
    // }   
    // return $tree_string;
// }
    // function ga_get_product_path($products_id) {
    // $cPath = '';	
	// //echo "select p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = '" . (int)$products_id . "' and p.products_status = '1' and p.products_id = p2c.products_id limit 1";

    // $category_query = tep_db_query("select p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = '" . (int)$products_id . "'  and p.products_id = p2c.products_id limit 1");
    // if (tep_db_num_rows($category_query)) {
      // $category = tep_db_fetch_array($category_query);

      // $categories = array();
      // tep_get_parent_categories($categories, $category['categories_id']);

      // $categories = array_reverse($categories);

      // $cPath = implode('_', $categories);

      // if (tep_not_null($cPath)) $cPath .= '_';
      // $cPath .= $category['categories_id'];
    // }

    // return $cPath;
  // }
  
  //this next function to find out if categoires_GA == 2 anywhere in the cPath and will return a fee is approp
		function is_this_ga($product_id){
		//global $sum_res;
		$sum_res=0;
$result = array();
//take the product id, find the cPath, explode it and then run a check to see if any cat has a GA setting
$ga_path_array = array();
$ga_path_array = explode('_', ga_get_product_path($product_id));//n.b. tep_get_product path requires products_status=1 
//$ga_path_array = array_reverse($ga_path_array);
$max = sizeof($ga_path_array);

if ($max >0)
{
	for ($i=0; $i<$max; $i++) 
	{
##
//reduce ga_amount by this product's quantity

		 $category_ga_query = tep_db_query("select categories_GA from " . TABLE_CATEGORIES . " where categories_id = '" . $ga_path_array[$i]. "' limit 1");
   			 if (tep_db_num_rows($category_ga_query)) 
			 { 
			  $category_ga = tep_db_fetch_array($category_ga_query);
			 	// if ( $category_ga['categories_GA'] == 2 && $category_ga['categories_GA_fee']>0){
					// $fee=$category_ga['categories_GA_fee'];
					// break;
					// }
					}
			}
			return $fee;
	}
	}
?>