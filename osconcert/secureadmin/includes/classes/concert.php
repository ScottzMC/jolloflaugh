<?php
	
	/*		Concert Class for osConcert		*/
	/*	2011 by Martin Zeitler, Germany	*/
	/*							*/
	
	
class concert {

	function tep_renderGrid($page, $rows, $order_by, $sort_order, $search, $lang=1){
		$plan=2;
		//get top categories only
		$sql = "
			SELECT
				*
			FROM ".
				TABLE_CATEGORIES_DESCRIPTION." cd, ".
				TABLE_CATEGORIES." c
			WHERE
				cd.categories_id = c.categories_id
			AND
				c.plan_id > " . $plan . "
            AND
                c.parent_id>=0
			AND
				cd.language_id = ".(int)$lang."
			ORDER BY 
				".$order_by." ".$sort_order."
			LIMIT
				".($page*$rows-$rows).",".(int)$rows;
			
		$arr =  array(
			'page' => $page,
			'total'=> $this->tep_getPageCount($rows)
		);
		
		$i=0;
		$result = tep_db_query($sql);
		
		while($concert = tep_db_fetch_array($result)){
			$arr['rows'][$i]['id'] = $concert['categories_id'];
			$arr['rows'][$i]['cell'] = array(
				$concert['venue_id'],
				$concert['sort_order'],
				($concert['categories_status']==0 ? 'yes':'no'),
				$concert['categories_name'],
				$concert['categories_heading_title'],
				$concert['categories_description'],
				$concert['concert_venue'],
				$concert['concert_date'],
				$concert['concert_time'],
				$concert['date_id'],
				$concert['categories_quantity'],
				''
			);
			$i++;
		}
		

		return $arr;
	}

	function tep_renderSubGrid($section_id, $order_by, $sort_order){
		
		$sql = "
			SELECT DISTINCTROW
				color_code,
				products_quantity,
				products_ordered,
				products_price,
				count(color_code) AS seats,
				product_type
			FROM ".
				TABLE_PRODUCTS."
			WHERE
				parent_id = ".(int)$section_id."
			AND
				color_code NOT LIKE ''
			AND
				product_type IN ('P','G','F')
			AND
				products_model NOT LIKE ''
			GROUP BY
				color_code
			ORDER BY ".
				$order_by." ".$sort_order;
				
		$arr =  array(
			'page' => 1,
			'total'=> 1,
			'rom_total_remaining'=>0,//
			'rom_sold'=>0
		);
		
		$i=0;
		$result = tep_db_query($sql);
		while($prices = tep_db_fetch_array($result)){
			
			/* regular shows */
			if($prices['product_type']=='P'){
				$locked = $this->tep_getLockedSeats($section_id,$prices['color_code'],'P');
				$sold = $this->tep_getSoldSeats($section_id,$prices['color_code'],'P');
				$arr['rows'][$i]['id'] = $section_id.'_'.$prices['color_code'];
				$arr['rows'][$i]['cell'] = array(
					$prices['color_code'],
					$prices['products_price'],
					$prices['seats'],
					$locked,
					$sold
				);
				$arr['rom_total_remaining'] = $arr['rom_total_remaining'] + $prices['seats'];
				$arr['rom_sold']  = $arr['rom_sold']  + $sold;
			}
			/* general admission shows */
			elseif($prices['product_type']=='G')
			{
				$locked = $this->tep_getLockedSeats($section_id,'','G');
				$sold = $this->tep_getSoldSeats($section_id,$prices['color_code'],'G');
				$arr['rows'][$i]['id'] = $section_id.'_'.$prices['color_code'];
				$arr['rows'][$i]['cell'] = array(
					$prices['color_code'],
					$prices['products_price'],
					$prices['products_quantity'],
					$locked,
					($prices['products_ordered'])
				);
				//error_reporting(1);
				$arr['rom_total_remaining'] = $arr['rom_total_remaining'] + $prices['products_quantity'];
				$arr['rom_sold']  = $arr['rom_sold']  + $prices['products_ordered'];
			}
			elseif($prices['product_type']=='F'){
				$locked = $this->tep_getLockedSeats($section_id,'','F');
				$sold = $this->tep_getSoldSeats($section_id,$prices['color_code'],'F');
				$arr['rows'][$i]['id'] = $section_id.'_'.$prices['color_code'];
				$arr['rows'][$i]['cell'] = array(
					$prices['color_code'],
					$prices['products_price'],
					$prices['products_quantity'],
					$locked,
					($prices['products_ordered']*FAMILY_TICKET_QTY)
				);
				$arr['rom_total_remaining'] = $arr['rom_total_remaining'] + $prices['products_quantity']*FAMILY_TICKET_QTY;
				$arr['rom_sold']  = $arr['rom_sold']  + $prices['products_ordered']*FAMILY_TICKET_QTY;
			}
			$i++;
		}
//*FAMILY_TICKET_QTY
		return $arr;
	}
	
	function tep_getPageCount($rows){
		$sql = "
			SELECT
				COUNT(*) AS total
			FROM ".
				TABLE_CATEGORIES."
			WHERE
				plan_id > 1";
			
		$result = tep_db_query($sql);
		$arr = tep_db_fetch_array($result);
		return ceil($arr['total']/$rows);
	}

	function tep_getLockedSeats($section_id, $color='', $type='P'){
		$sql = "
			SELECT
				SUM(customers_basket_quantity) AS locks
			FROM
				".TABLE_CUSTOMERS_BASKET."
			LEFT JOIN
				products
			ON
				".TABLE_CUSTOMERS_BASKET.".products_id = ".TABLE_PRODUCTS.".products_id
			WHERE
				section_id = ".(int)$section_id;
		if($type=='P'){$sql .=" AND ".TABLE_PRODUCTS.".color_code LIKE '".$color."'";}
		$result = tep_db_query($sql);
		$arr = tep_db_fetch_array($result);
		$locks = $arr['locks'];
		
		$sql = "
			SELECT
				SUM(customers_basket_quantity) AS temp_locks
			FROM
				".TABLE_CUSTOMERS_TEMP_BASKET."
			LEFT JOIN
				".TABLE_PRODUCTS."
			ON
				".TABLE_CUSTOMERS_TEMP_BASKET.".products_id = ".TABLE_PRODUCTS.".products_id
			WHERE
				section_id = ".(int)$section_id;
				
		if($type=='P'){$sql .=" AND ".TABLE_PRODUCTS.".color_code LIKE '".$color."'";}
		$result = tep_db_query($sql);
		$arr = tep_db_fetch_array($result);
		$temp_locks = $arr['temp_locks'];
		
		//tep_db_free_result($result);
		return $locks+$temp_locks;
	}

	function tep_getSoldSeats($section_id, $color='', $type='P'){
		if(($type!='G')or($type!='F')){
			$sql = "
			SELECT
				COUNT(products_id) as sold
			FROM
				".TABLE_PRODUCTS."
			WHERE
				products_status = 0
			AND
				product_type = 'P'
			AND
				parent_id = ".(int)$section_id."
			AND
				color_code LIKE '".$color."'";
		}
		else {
			$sql = "
			SELECT
				products_ordered as sold,
				products_quantity as stock
			FROM
				".TABLE_PRODUCTS."
			WHERE
				product_type = 'G'
			AND
				section_id = ".(int)$section_id;
		}
		$result = tep_db_query($sql);
		$arr = tep_db_fetch_array($result);
		
		if($type!='G'){return $arr['sold'];}else{return $arr;}
	}
	// function tep_getSoldSeats2($section_id, $color='', $type='P')
	// {
		// if($type!='G')
		// {
			// $sql = "
			// SELECT
				// products_ordered as sold,
				// products_quantity as stock
			// FROM
				// ".TABLE_PRODUCTS."
			// WHERE
				// products_status = 0
			// AND
				// product_type = 'P'
			// AND
				// section_id = ".(int)$section_id."
			// AND
				// color_code LIKE '".$color."'";
		// }
	// }
	//section_id = ".(int)$section_id."
	function tep_resetShow($section_id)
	{
		$sql = "
			UPDATE 
				".TABLE_PRODUCTS."
			SET
				products_ordered = 0,
				products_status = 1,
				products_quantity = 1
			WHERE
				parent_id = ".(int)$section_id."
			AND
				products_sku = 1";
		tep_db_query($sql);

	
		$sql2= "
			UPDATE 
				".TABLE_PRODUCTS."
			SET
				products_ordered = 0,
				products_status = 1,
				products_quantity = master_quantity
			WHERE
				section_id = ".(int)$section_id."
			AND
				products_sku = 9";
		tep_db_query($sql2);

	
	$sql3 = "
			UPDATE 
				".TABLE_CATEGORIES."
			SET
				categories_quantity_remaining = categories_quantity
			WHERE
				categories_id = ".(int)$section_id."
			AND
				plan_id = 9";
		tep_db_query($sql3);
	} //section ID will reset the SHOW rather than the DAY
	
	function tep_updateShow($id, $date_id, $desc, $title, $name, $date, $time, $venue, $venue_id, $hidden, $lang=1){
		
		#### code for unix timestamp
		#### assumes incoming date is dd-mm-yyyy
		#### ignores time field for the moment 
		#### add 18 hours so that it is 6pm
		#### set timezone to UTC - this will override
		#### php.ini only for this script
		#### need to also ensure jquery in front end does the same

		if(EXPIRE_DATE_TIME=='no')
		{
		$unix_pieces = explode("-", $date);
		$date_unix = mktime(0, 0, 0, $unix_pieces[1], $unix_pieces[0], $unix_pieces[2]);
		}

		if(AUTOFILL_DATEID=='yes')
		{
		$date_id=$date.' '.$time;
		}else{
		$date_id=$date_id;	
		}

		if(EXPIRE_DATE_TIME=='yes')
		{
		$date_unix = strtotime($date_id);
		}

		
		/* table categories //date_id = '".$date_id."',//DEF*/
		$sql = "
			UPDATE ".
				TABLE_CATEGORIES."
			SET
				date_id = '".$date_id."',
				concert_date_unix = '".$date_unix."',
				categories_status = ".(int)$hidden."
			WHERE
				categories_id = ".(int)$id."
			AND
				plan_id > 0";
		
		tep_db_query($sql);
		
		
		/* table categories_description */
		$sql = "
			UPDATE ".
				TABLE_CATEGORIES_DESCRIPTION."
			SET
				categories_name = '".$name."',
				categories_heading_title = '".$title."',
				categories_description = '".$desc."',
				concert_venue = '".$venue."',
				concert_date = '".$date."',
				concert_time = '".$time."',
				venue_id = '".$venue_id."'
			WHERE
				categories_id = ".(int)$id."
			AND
				language_id = ".(int)$lang;
		
		tep_db_query($sql);
		
		/* table products */
		$sql = "
			UPDATE ".
				TABLE_PRODUCTS."
			SET
				products_model  = '".$date_id."'
			WHERE
				parent_id = ".(int)$id." 
			AND
				color_code !=''";
		
		tep_db_query($sql);
	}//parent_id?? section ID will set DATE ID for the SHOW rather than the DAY

	function tep_updatePrice($section_id, $color, $price){
		$sql = "
			UPDATE 
				".TABLE_PRODUCTS."
			SET
				products_price = ".$price."
			WHERE
				parent_id = ".(int)$section_id."
			AND
				color_code = '".$color."'";
		
		tep_db_query($sql);
	} //section ID will set prices for the SHOW rather than the DAY

	function tep_isMultilanguage(){
		
		$sql = "
			SELECT
				COUNT(".TABLE_LANGUAGES.".languages_id) as languages
			FROM
				".TABLE_LANGUAGES;
		
		$result = tep_db_query($sql);
		$arr = tep_db_fetch_array($result);
		//tep_db_free_result($result);
		
		if ($arr['languages'] ==1){return false;}else{return true;}
	}

	function tep_getLanguages(){
				
		$sql = "
			SELECT
				*
			FROM ".
				TABLE_LANGUAGES."
			ORDER BY 
				".TABLE_LANGUAGES.".sort_order";
				
		$result = tep_db_query($sql);
		
		$i=0;
		$arr = array();
		while($item = tep_db_fetch_array($result)){
			$arr[$i]['id'] = $item['languages_id'];
			$arr[$i]['name'] = $item['name'];
			$i++;
		}
		
		//tep_db_free_result($result);
		return $arr;
	}

	function tep_updateSortOrder($order){
		
		$sql ="
			SELECT
				categories_id,
				sort_order
			FROM
				".TABLE_CATEGORIES."
			WHERE
				categories_id
			IN
				(".$order.")
			ORDER by
				categories_id
			ASC
			";
		
		$i=0;
		$arr = explode(',',$order);
		$result = tep_db_query($sql);
		while($data = tep_db_fetch_array($result)){
			if($data['sort_order'] != $arr[$i]){
				$sql = "UPDATE ".TABLE_CATEGORIES." SET sort_order = ".(int)$data['categories_id']." WHERE categories_id = ".(int)$arr[$i];
				tep_db_query($sql);
			}
			$i++;
		}
	}

	/* live logging functions */
	function tep_listItems($mode, $lang=1){
		
		switch($mode){
			case 'seatplan':
				$sql = "
					SELECT
						c.categories_id,
						c.date_id
					FROM ".
						TABLE_CATEGORIES_DESCRIPTION." cd, ".
						TABLE_CATEGORIES." c
					WHERE
						cd.categories_id = c.categories_id
					AND
						c.parent_id = 0
					AND
						c.plan_id > 0
					AND 
						c.date_id !=''	
					AND
						cd.language_id = ".(int)$lang
				;
				
				$result = tep_db_query($sql);
				while($item = tep_db_fetch_array($result)){
					echo '<li class="seatplan" id="sp'.$item['categories_id'].'"><a href="#c'.$item['categories_id'].'">'.$item['date_id'].'</a></li>';
				}
				//tep_db_free_result($result);
				break;
			
			case 'channel':
				$sql = "
					SELECT
						c.categories_id,
						date_id
					FROM ".
						TABLE_CATEGORIES_DESCRIPTION." cd, ".
						TABLE_CATEGORIES." c
					WHERE
						cd.categories_id = c.categories_id
					AND
						c.parent_id = 0
					AND
						plan_id > 0
					AND 
						c.date_id !=''	
					AND
						cd.language_id = ".(int)$lang
				;
				
				$result = tep_db_query($sql);
				while($item = tep_db_fetch_array($result)){
					echo
						'<li class="channel">
								<div id="c'.$item['categories_id'].'">
									<div class="stats">loading stats for channel '.$item['date_id'].'...</div>
									<div class="log">'.$this->tep_listEvents($item['categories_id']).'</div>
							</div>
						</li>';
				}
				//tep_db_free_result($result);
				break;
		}

	}
	
	function tep_listEvents($cPath) {
		
		$sql = "
			SELECT
				seatplan_events.*,
				customers.customers_username
			FROM
				seatplan_events
			LEFT JOIN
				customers
			ON
				customers.customers_id = seatplan_events.customers_id
			WHERE
				cPath = ".(int)$cPath.'
			ORDER bY
				seatplan_events.timestamp
			DESC
			';
		
		$html ='';$i=0;$latest_id=0;
		$result = tep_db_query($sql);
		
		while($item = tep_db_fetch_array($result)){
			if($i==0){$latest_id = $item['event_id'];}
			$html .= '<div class="event_log lvl'.$item['log_level'].'" id="e'.$item['event_id'].'">
									<div class="pid">'.(($item['products_id']!=0) ? $item['products_id']:'&nbsp;').'</div>
									<div class="cid">'.(($item['customers_username']!=null)? $item['customers_username']:'Guest').'</div>
									<div class="sesskey">'.$item['sesskey'].'</div>
									<div class="timestamp">'.$item['timestamp'].'</div>
									<div class="pname">'.$item['products_name'].'</div>
									<div class="event">'.$item['event'].'</div>
								</div>'."\n";
			$i++;
		}
		$html .='<input id="last_id-'.$cPath.'" type="hidden" value="'.$latest_id.'" />';
		//tep_db_free_result($result);
		return $html;
	}

	function tep_getUpdates($cPath,$latest_id){
		
		$sql = '
			SELECT
				seatplan_events.*,
				customers.customers_username
			FROM
				seatplan_events
			LEFT JOIN
				customers
			ON
				customers.customers_id = seatplan_events.customers_id
			WHERE
				seatplan_events.cPath = '.(int)$cPath.'
			AND
				seatplan_events.event_id > '.(int)$latest_id.'
			ORDER by
				seatplan_events.timestamp
			DESC
			';
		
		$arr = array();$i=0;
		$result = tep_db_query($sql);
		while($item = tep_db_fetch_array($result)){
			if($i==0){$arr[0] = $item['event_id'];}
			$arr[] = $item;
			$i++;
		}
		//tep_db_free_result($result);
		return $arr;
	}
	
	
	function getThreads(){
		$sql ="SHOW STATUS LIKE 'Threads%'";
	}
	
}
?>