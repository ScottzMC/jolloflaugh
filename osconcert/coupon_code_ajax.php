<?php
// this code derived from the original Freeway code 
// number of lines seem to do nothing!
define( '_FEXEC', 1 );
require( 'includes/application_top.php' );

//error_reporting(E_ALL);
//ini_set('display_errors', '1');
require( DIR_WS_LANGUAGES . $FSESSION->language . '/modules/order_total/ot_coupon.php' );
require( DIR_WS_CLASSES . 'order.php' );
require( DIR_WS_FUNCTIONS . 'ga_tickets.php' );
$order = new order;

if ( isset( $_GET[ 'q' ] ) && $_GET[ 'q' ] != "" ) {
    $now_date = getServerDate();
    $value = $_GET[ "q" ];
    $error = '';
    // get some info from the coupon table

    $coupon_query = tep_db_query( "select * from " . TABLE_COUPONS . "
                                       where coupon_code='" . $value . "'
                                       and coupon_active='Y'" );

    if ( tep_db_num_rows( $coupon_query ) > 0 ) {
        $now_date = getServerDate();
        $date_query = tep_db_query( "select coupon_start_date from " . TABLE_COUPONS . "
									where coupon_start_date <= '" . $now_date . "' and
									coupon_code='" . $value . "'" );

        if ( tep_db_num_rows( $date_query ) == 0 ) {
            $error .= ERROR_INVALID_STARTDATE_COUPON;
        }
        $date_query = tep_db_query( "select coupon_expire_date,uses_per_user from " . TABLE_COUPONS . "
									where coupon_expire_date >= '" . $now_date . "' and
									coupon_code='" . $value . "'" );

        if ( tep_db_num_rows( $date_query ) == 0 ) {
            $error .= ERROR_INVALID_FINISDATE_COUPON;
        } else {
            $date_res = tep_db_fetch_array( $date_query );
            $expire_date = format_date( date( 'Y-m-d', strtotime( $date_res[ 'coupon_expire_date' ] ) ) );
        }
    }
    // no coupon on table checks email tracking
    else {
        $coupon_query = tep_db_query( "select c.coupon_id, ce.discount_coupon_code as coupon_code,ce.amount as coupon_amount, c.coupon_type, c.coupon_minimum_order,c.coupon_flag,
                                       c.uses_per_coupon, c.uses_per_user, c.uses_per_order, c.restrict_to_products,
                                       c.restrict_to_categories from " . TABLE_COUPONS . " c, " . TABLE_COUPONS_DISCOUNT_EMAIL . " ce 
                                       where ce.discount_coupon_code='" . $value . "' and c.coupon_id=ce.coupon_id and ce.customer_id='" . tep_db_input( $FSESSION->customer_id ) . "' 
                                       and coupon_active='Y'" );
        if ( tep_db_num_rows( $coupon_query ) > 0 ) {
            $now_date = getServerDate();
            $date_query = tep_db_query( "select c.coupon_start_date from " . TABLE_COUPONS . " c, " . TABLE_COUPONS_DISCOUNT_EMAIL . " ce 
											where c.coupon_start_date <= '" . $now_date . "' and c.coupon_id=ce.coupon_id and ce.customer_id='" . ( int )$FSESSION->customer_id . "'  and 
											ce.discount_coupon_code='" . $value . "'" );

            if ( tep_db_num_rows( $date_query ) == 0 ) {
                if ( !$error )$error = ERROR_INVALID_STARTDATE_COUPON;
            }
            $date_query = tep_db_query( "select c.coupon_expire_date,c.uses_per_user from " . TABLE_COUPONS . " c, " . TABLE_COUPONS_DISCOUNT_EMAIL . " ce 
											where c.coupon_expire_date >= '" . $now_date . "' and c.coupon_id=ce.coupon_id and ce.customer_id='" . ( int )$FSESSION->customer_id . "' and 
											ce.discount_coupon_code='" . $value . "'" );

            if ( tep_db_num_rows( $date_query ) == 0 ) {
                if ( !$error )$error = ERROR_INVALID_FINISDATE_COUPON;
            } else if ( tep_db_num_rows( $date_query ) ) {
                $date_res = tep_db_fetch_array( $date_query );
                $this->expire_date = format_date( date( 'Y-m-d', strtotime( $date_res[ 'coupon_expire_date' ] ) ) );
            }
            $email_coupon = true;
        } else {
            $error = ERROR_NO_INVALID_REDEEM_COUPON;
        }
    }

    $coupon_result = tep_db_fetch_array( $coupon_query );


    //we are compiling a list of ids in $_SESSION['coupon_codes'] so that we can compare

    $vals = array_count_values( $_SESSION[ 'coupon_codes' ] );
    if ( isset( $vals[ $coupon_result[ 'coupon_id' ] ] ) ) {
        $existing_coupon = $vals[ $coupon_result[ 'coupon_id' ] ];
    } else {
        $existing_coupon = 0;
    }
// [1]how many times has the coupon itself been used?
    $coupon_count = tep_db_query( "select coupon_id from " . TABLE_COUPON_REDEEM_TRACK . "
                                          where coupon_id = '" . tep_db_input( $coupon_result[ 'coupon_id' ] ) . "'" );
// [2]how many times has this customer used it?
    $coupon_count_customer = tep_db_query( "select coupon_id from " . TABLE_COUPON_REDEEM_TRACK . "
                                                   where coupon_id = '" . tep_db_input( $coupon_result[ 'coupon_id' ] ) . "' and
                                                   customer_id = '" . tep_db_input( $FSESSION->customer_id ) . "'" );
    
// [1]throws error if overall number of uses exceeded
    if ( tep_db_num_rows( $coupon_count ) >= $coupon_result[ 'uses_per_coupon' ] && $coupon_result[ 'uses_per_coupon' ] > 0 ) {
        {
            $error .= ERROR_INVALID_USES_COUPON . $coupon_result[ 'uses_per_coupon' ] . TIMES;
        }
    }

    if ( $coupon_result[ 'uses_per_user' ] ) {
        $uses_per_user = ( $coupon_result[ 'uses_per_user' ] - tep_db_num_rows( $coupon_count_customer ) - $existing_coupon );
        if ( $uses_per_user <= 0 ) {
            $uses_per_user = 'Nil';
        }
    } else {
        if ( $coupon_result[ 'uses_per_user' ] < 0 ) {
            $uses_per_user = 0;
        }
    }

    if ( ( tep_db_num_rows( $coupon_count_customer ) + $existing_coupon ) >= $coupon_result[ 'uses_per_user' ] && $coupon_result[ 'uses_per_user' ] > 0 && $coupon_result[ 'coupon_flag' ] == 'U' ) 
        {
            $error .= ERROR_INVALID_USES_USER_COUPON;
        } 
    
    if ( ( $existing_coupon  ) >= $coupon_result[ 'uses_per_order' ] && $coupon_result[ 'uses_per_order' ] > 0 && $coupon_result[ 'coupon_flag' ] == 'U' ) 
        {
            $error .= ERROR_INVALID_USES_USER_COUPON_ORDER;
        }
    


    if ( $coupon_result[ 'coupon_type' ] == 'S' ) {
        $coupon_amount = $order->info[ 'shipping_cost' ];
    } else {
        $coupon_amount = $currencies->format( $coupon_result[ 'coupon_amount' ] ) . ' ';
    }

    if ( $coupon_result[ 'coupon_type' ] == 'P' ) {
        $coupon_amount = $currencies->format( $order->info[ 'total' ] * ( $coupon_result[ 'coupon_amount' ] / 100 ) );
        $coupon_result[ 'coupon_amount' ] = $order->info[ 'total' ] * ( $coupon_result[ 'coupon_amount' ] / 100 );
    }
    if ( $coupon_result[ 'coupon_minimum_order' ] > 0 ) {
        if ( $order->info[ 'total' ] <= ( $coupon_result[ 'coupon_minimum_order' ] ) ) {
            $error .= sprintf( ERROR_LOW_ORDER_TOTAL, $currencies->format( $coupon_result[ 'coupon_minimum_order' ] ), $currencies->format( $order->info[ 'total' ] ) );
        } else {
            $coupon_amount .= TEXT_ON_ORDERS . $currencies->format( $coupon_result[ 'coupon_minimum_order' ] );

        }
    }
    #################### category restrictions



    $products = $cart->get_products();
    $coupon_get = tep_db_query( "select restrict_to_products,restrict_to_categories from " . TABLE_COUPONS . " where coupon_code='" . tep_db_input( $value ) . "' and (restrict_to_products!='' or restrict_to_categories!='')" );
    $get_result = tep_db_fetch_array( $coupon_get );
    $in_cat = true;
    $banned = '';
    $allowed = '';
    if ( $get_result[ 'restrict_to_categories' ] ) {

        $cat_ids = preg_split( "/[,]/", $get_result[ 'restrict_to_categories' ] );
        for ( $i = 0; $i < count( $cat_ids ); $i++ ) {
            for ( $j = 0; $j < count( $products ); $j++ ) {
                if ( $products[ $j ][ 'element_type' ] != 'P' ) continue;

                $ga_path_array = array();
                $ga_path_array = explode( '_', ga_get_product_path( $products[ $j ][ 'id' ] ) );

                if ( !in_array( $cat_ids[ $i ], $ga_path_array ) ) {
                    if ( $banned != '' ) {
                        $banned .= ' & ' . $products[ $j ][ 'name' ];
                    } else {
                        $banned .= ' ' . $products[ $j ][ 'name' ];
                    }
                }


                if ( in_array( $cat_ids[ $i ], $ga_path_array ) ) {
                    if ( $allowed != '' ) {
                        $allowed .= ' & ' . $products[ $j ][ 'name' ];
                    } else {
                        $allowed .= ' ' . $products[ $j ][ 'name' ];
                    }

                }

            }
        }
        if ( $banned != '' && $allowed == '' ) {
            $error .= TEXT_COUPON_RESTRICT1 . $banned;
        }

    }

    if ( $get_result[ 'restrict_to_products' ] ) {
        $found = '';
        $pr_ids = preg_split( "/[,]/", $get_result[ 'restrict_to_products' ] );
        for ( $i = 0; $i < count( $pr_ids ); $i++ ) {
            for ( $j = 0; $j < count( $products ); $j++ ) {
                if ( $products[ $j ][ 'element_type' ] != 'P' ) continue;
                if ( $products[ $j ][ 'id' ] == $pr_ids[ $i ] ) {
                    $found = 'yes';
                }
            }
        }
        if ( $found != 'yes' ) {
            $error .= TEXT_COUPON_RESTRICT_PRODUCT;
        }
    }









    #######################################enf 

    if ( $error == '' ) {
        $_SESSION[ 'coupon_codes' ][] = tep_db_input( $coupon_result[ 'coupon_id' ] );
        //need to know where in the array the coupon is for cancelling purposes
        $key = max( array_keys( $_SESSION[ 'coupon_codes' ], tep_db_input( $coupon_result[ 'coupon_id' ] ) ) );
        //$cancel =  tep_draw_checkbox_field('a'.$key, '0',false,'onclick=javascript:remove_coupon("'.$key.'")').TEXT_COUPON_CANCEL;
        $cancel = '';
        $_SESSION[ 'discount' ] = $_SESSION[ 'discount' ] + $coupon_result[ 'coupon_amount' ];
        $exp = '';
        if ( $_SESSION[ 'discount' ] > $order->info[ 'total' ] ) {
            $exp = '<br>' . ERROR_LESSTHAN_COUPON_TOTAL;
        }
        echo '<span id ="valid_code_' . $key . '">' . TEXT_COUPON_VALID . ' <strong>' . $value . '</strong> ' . TEXT_COUPON_VALID_AMOUNT . $coupon_amount . ' ' . $exp . '</span>';
    } else {
        echo '<strong>ERROR: ' . $value . '</strong> ' . $error;
    }
} //end isset q
################################################
if ( isset( $_GET[ 'x' ] ) && $_GET[ 'x' ] != "" ) {

    unset( $_SESSION[ 'coupon_codes' ][ $_GET[ 'x' ] ] );
    var_dump( $_SESSION[ 'coupon_codes' ] );
    exit();

} //end isset X

?>