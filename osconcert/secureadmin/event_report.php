<?php
/*
  Released under the GNU General Public License
  
Copyright (c) 2021 osConcert
*/
// Set flag that this is a parent file
  define( '_FEXEC', 1 );
  
// +++ 2020 adapt for daily report 
  
require('includes/application_top.php');   
  tep_set_time_limit(0);
  require('includes/classes/currencies.php');
  require('includes/classes/pdfTable.php');
  require('tfpdf/font/makefont/makefont.php');

  require(DIR_WS_CLASSES . 'split_page_results_report.php');
  define(BOX_WIDTH1,'125');
###########################################################
#
#  Language definitions here 
#
###########################################################
define(TEXT_FREE_SEATS,'Free Seats,');
define(TEXT_BOX_OFFICE_TICKETS,'Sold by Box Office,');
define(TEXT_SOLD_ONLINE,'Tickets Sold Online,');
define(TEXT_INVITATIONS,'Invitiations');
define(TEXT_SOLD_TOTAL,'Total Tickets Sold.');
define(TEXT_SALES_STATUS,'Sales Status at ');
###########################################################
#
# Get current list live shows parent_id=0 and date >= today
# setup an array for the pdf ($rom_report)
#
###########################################################  
echo '<!DOCTYPE html>';
echo '<html dir="ltr" lang="en">';
echo '<head>';
echo '<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">';
echo '<style>.event_report{padding:5px;border-top: 0px;border-left:0px;border-right:0px;border-bottom:2px;border-style: solid;width:100%;text-align:left;font-family:verdana}</style>';
echo '<script>function goBack() {window.history.back();}</script>';
echo '</head>';
echo '<body>';
echo "<h3>". TEXT_SALES_STATUS ." " . date(EVENTS_DATE_FORMAT) . " " . date("h:i") . '</h3>';
echo '<div style="border-top: 2px;border-left:2px;border-right:2px;border-bottom:0px;border-style: solid;margin-bottom:10px;">';
$rom_report = array();
$rom_array_index = 0;

$category_query = tep_db_query("select c.date_id,
									   c.categories_id,
									   c.categories_quantity,
									   c.categories_quantity_remaining,
									   c.plan_id,
									   c.concert_date_unix,
									   cd.categories_name, 
									   cd.concert_venue, 
									   cd.concert_date, 
									   cd.concert_time, 
									   cd.categories_heading_title, 
									   cd.categories_description, 
									   c.categories_image 
									   from 
									   " . TABLE_CATEGORIES . " c, 
									   " . TABLE_CATEGORIES_DESCRIPTION . " cd 
									   where
									   c.parent_id >= '0' 
									   and
									   cd.concert_time!=''
									   and 
									   c.categories_id = cd.categories_id
									   and 
									   date_format(str_to_date(cd.concert_date, '%d-%m-%Y'),'%Y-%m-%d')>='".date_format(date_create(getServerDate()),'Y-m-d')."' 
									   and
									   cd.language_id = '" . tep_db_input($FSESSION->languages_id) . "'
									   ");
									   
###########################################################
#
# You now have an array of live shows, traverse the array 
# 
###########################################################

while($category = tep_db_fetch_array($category_query)){
	//$arr = $rom->tep_renderSubGrid($category['categories_id'], $category['concert_date'], 'asc');
		
		
$seats_query = tep_db_query("select 
		distinct
		p.products_id, 
		sum(case when p.products_status = '0' and p.product_type = 'P'  then 1 else 0 end) as all_sold_P,
		sum(case when p.product_type = 'G' then p.products_ordered else 0 end ) as all_sold_G,
		sum(case when p.products_status = '1' then (p.products_quantity) else 0 end) as all_unsold,
		sum(p.products_ordered) as check_sold
		from 
			products p
		where
			p.parent_id = '" . $category['categories_id'] . "'
	    group by
		p.products_id"); 
	$ga_remaining = $all_seats = $all_sold = $all_sold_P = $all_sold_G = $all_box_office = $all_unsold = $all_invitati = $all_online = $check_sold = 0;
	

	while($seats = tep_db_fetch_array($seats_query))
	{	

		//$all_box_office = $all_box_office + $seats['box_office_seats'];
		
		$all_sold_P = $all_sold_P + $seats['all_sold_P'];
		$all_sold_G = $all_sold_G + $seats['all_sold_G']; 
		$all_unsold = $all_unsold + $seats['all_unsold'];
		$check_sold = $check_sold + $seats['check_sold'];
		
		
		$all_sold_query = tep_db_query("select 
			distinct
		p.products_id, op.products_id, op.orders_products_id,	
		sum(case when op.orders_products_status = '3' then (op.products_quantity) else 0 end) as all_sold,
		sum(case when o.customers_country = 'Box Office' and (op.final_price - op.coupon_amount > 0) and o.orders_status = 3 then (op.products_quantity) else 0 end) as box_office_seats,
		sum(case when op.orders_products_status = '3' and (op.final_price - op.coupon_amount = 0) then (op.products_quantity) else 0 end) as all_invitati
		from 
			products p
		left join
			orders_products op 
		on
			op.products_id = p.products_id
		left join
			orders o
		on
			o.orders_id = op.orders_id
		where
			p.products_id = '" . $seats['products_id'] . "'
	    group by
		p.products_id"); 
		
		while($all_sold_result = tep_db_fetch_array($all_sold_query)){	
		
		 $all_sold = $all_sold + (int)$all_sold_result['all_sold'];
		 $all_box_office = $all_box_office + $all_sold_result['box_office_seats'];
		 $all_invitati = $all_invitati + $all_sold_result['all_invitati'];
		}
	}

$date = date_create_from_format (EVENTS_DATE_FORMAT, $category['concert_date']); 	
$weekday =  $date->format('l'); # l for full week day name
$all_seats = $all_sold + $all_unsold;
$online = $all_sold - $all_box_office - $all_invitati;
#########################################################################
#
#   GA with quantities????
#
##########################################################################

if ($category['categories_quantity'] > 0) { //GA with limited addmissions
     $ga_remaining = $category['categories_quantity_remaining'];	
}
##########################
# screen display
##########################

$categories_date = $category['concert_date'];

			require(DIR_WS_FUNCTIONS.'/date_formats.php');

$categories_time = $category['concert_time'];

if($all_sold>0){
echo '<table class="event_report">';
echo '<tr>';
echo '<td colspan="2">';
echo $heading_date. "<br>";
echo '</td>';
echo '</tr>';
echo '<tr>';
echo '<td width="10%">';
echo "<strong>" . $heading_time . "</strong>";
echo '</td>';
echo '<td>';
echo "<strong>" . $category['categories_name'] . "</strong> [" . $category['concert_venue'] . "]<br>";
echo $all_unsold . " " . TEXT_FREE_SEATS . " " . $all_box_office . " ". TEXT_BOX_OFFICE_TICKETS ." " . $online . " ". TEXT_SOLD_ONLINE . " " . $all_invitati . " " . TEXT_INVITATIONS . " " . $all_sold . " " . TEXT_SOLD_TOTAL . "<br>";
echo '</td>';
echo '</tr>';
echo '</table>';

}

// echo $concert_date. "<br>";
// echo "----------------------------------------------<br>";
// //echo $weekday ." " . $category['concert_time'] ." " . $category['concert_date'] . "<br>";
// echo '<strong>' . $ctime . '</strong>';
// echo "   <b>" . $category['categories_name'] . "</b> [" . $category['concert_venue'] . "]<br>";
// echo "----------------------------------------------<br>";
// echo $all_unsold ." Free Seats, " . $all_box_office . " sold at Ticket Booth, " . $online . " tickets sold online, " . $all_invitati . " . $all_sold . " tickets sold in total<br>";
// echo "----------------------------------------------<br>";

//echo "<b>" . $category['categories_name'] . "</b><br>";
//echo "----------------------------------------------<br>";

// echo "All Box Office Sales: " . $all_box_office  . "<br>";
// echo "Invitati = " .$all_invitati . "<br>";
// echo "Online [all sold minus Box Office minus Invitati] = ". $online . "<br>";
// echo "----------------------------------------------<br>";
// echo "The following two should  match: <br>";
// echo "----------------------------------------------<br>";
// echo "All sold: ". $all_sold ."<br>";
// echo "Checksum sold: " . $check_sold ."<br>";
// //echo "All sold from Concert class: " . $arr ['rom_sold'] .  "<br>";
// echo "All sold P: ". $all_sold_P ."<br>";
// echo "All sold GA: ". $all_sold_G ."<br>";
// echo "----------------------------------------------<br>";
// echo "All unsold: ". $all_unsold ."<br>";
// echo "----------------------------------------------<br>";
// //echo "All tickets from Concert class: " . $arr ['rom_total_remaining'] ."<br>";
// echo "TOTAL [Unsold + sold]: " . ($all_seats)."<br>";
// if ((int)$ga_remaining > 0)
// {	echo "GA Seats remaining: " . $ga_remaining."<br>";
// }
//echo "<br><br>";
//echo "</td></tr></table>";
##########################
#  end screen display
##########################

	$rom_report[$rom_array_index] = array(
	 $weekday,							//day of the week
	 $category['concert_date'],			//date
	 $category['concert_time'],			//time
	 $category['categories_name'],		//name
	 $category['concert_venue'],		//venue
	 $all_box_office,					//box office tickets
	 $all_invitati, 					//free seats/invites
	 $all_sold,							//all tickets sold
	 $all_unsold,						//unsold
	 $online							//online sales

	);

	$rom_array_index ++;
}//end of while $category


##########################
# screen display
##########################

//exit ("<br>FINISHED<br><br><pre>" . var_dump($rom_report));
  
###########################################################
#
#  TODO next section remove?? all code after here is original
#
###########################################################

echo '</div><input type="button" class="btn btn-primary btn-lg text-center" value="PRINT" onclick="window.print();">&nbsp;';
echo '<button class="btn btn-primary btn-lg text-center" onclick="goBack()">Back</button>';
echo '</body>';
echo'</html>';
?>