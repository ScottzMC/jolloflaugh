<?php
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();

		//Do we use EVENTS_DATE_FORMAT?
		if(SHOW_EVENTS_DATE_FORMAT=='yes')
		{
			if(EVENTS_DATE_FORMAT=='d-m-Y')
			{
			//$heading_date = utf8_encode(strftime('%A, %d. %B', strtotime($categories_date)));//German style 1
			//$heading_date = utf8_encode(strftime('%a. %d. %B', strtotime($categories_date)));//German style 2
			//$heading_date = utf8_encode(strftime('%A %d %B', strtotime($categories_date)));//%A %d %B, %Y
			//$heading_date = date("D. j. F", strtotime($categories_date));
			$heading_date = date("l, jS F", strtotime($categories_date));//Thursday, 1st July
			}
			if(EVENTS_DATE_FORMAT=='m-d-Y')
			{
			//$heading_date = strftime('%A, %B %d', strtotime($categories_date));//
			//$heading_date = date("F jS, Y", strtotime($categories_date));//July 1st, 2021
			$heading_date = date("l, F jS", strtotime($categories_date));//Thursday, July 1st
			//$heading_date = strftime('%A, %B %d', strtotime($concert_date));//
			}
			if(EVENTS_DATE_FORMAT=='Y-m-d')
			{
			$heading_date = date("Y, jS F", strtotime($categories_date));//2021, 1st July
			
			}	
		}else
		{
			//use format from language file
			$heading_date = strftime(OSCONCERT_FORMAT, strtotime($categories_date));
		}
			
		if($categories_time>0)
		{	
			$time=strtotime($categories_time);
					if(TIME_FORMAT==12)
					{
					$heading_time = date('g:i a', $time);
					}else
					{
					$heading_time = date('H:ia', $time);//H:ia=24hr	
					//$new_heading_time = date('H:i', $time);//H:ia=24hr	
					}
					
					//German Uhr
					//$heading_time = $new_heading_time. ' Uhr';
					
		}
		if(!strtotime($categories_date))
		{
			// it's not in date format
			$heading_date = $categories_date;//gives concert_date
		}
		if(!strtotime($categories_time))
		{
			// it's not in date format
			$heading_time = $categories_time;//gives concert_date
		}

		?>