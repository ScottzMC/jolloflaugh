<?php
/*
	osCommerce, Open Source E-Commerce Solutions 
	http://www.oscommerce.com 
	
	Copyright (c) 2003 osCommerce 
	
	 
	
	Freeway eCommerce 
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
	osConcert, Online Seat Booking 
  	https://www.osconcert.com

  	Copyright (c) 2020 osConcert

	Released under the GNU General Public License
*/

// Set flag that this is a parent file
define( '_FEXEC', 1 );
if(
	isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
	&& !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
	&& (strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])==="xmlhttprequest")
)
{
	require('includes/application_top.php');
	class ajax_search_address_book
	{
		/*
		* Return Fields
		*/
		private $limit = 5;
		private $fields = '
				address_book_id,
				entry_firstname AS firstname,
				entry_lastname AS lastname,
				entry_company AS company,
				entry_customer_email AS customer_email,
				entry_street_address AS street_address,
				entry_suburb AS suburb,
				entry_city AS city,
				entry_postcode AS postcode,
				entry_state AS state,
				entry_zone_id AS zone_id,
				entry_country_id AS	country_id
				';
		
		function __construct(){
			
			$this->limit = $this->post('limit','string', 5);
			
			header("Content-Type: application/json;charset=utf-8");
			header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			
			echo $this->results();
		}
		
		/*
		* Get all results 
		*/
		private function results(){
			$return = array();
			
			$search_by_id = $this->search_by_id();
			if(count($search_by_id) < 1)
			{			
				$search_by_name = $this->search_by_name();
				if(count($search_by_name)>0)
					$return = array_merge($return, $search_by_name);
					
				$search_by_company = $this->search_by_company();
				if(count($search_by_company)>0)
					$return = array_merge($return, $search_by_company);
				
				$search_by_email = $this->search_by_email();
				if(count($search_by_email)>0)
					$return = array_merge($return, $search_by_email);
					
				$search_by_street_address = $this->search_by_street_address();
				if(count($search_by_street_address)>0)
					$return = array_merge($return, $search_by_street_address);
					
				$search_by_city = $this->search_by_city();
				if(count($search_by_city)>0)
					$return = array_merge($return, $search_by_city);
					
				$search_by_state = $this->search_by_state();
				if(count($search_by_state)>0)
					$return = array_merge($return, $search_by_state);
					
				$search_by_post_code = $this->search_by_post_code();
				if(count($search_by_post_code)>0)
					$return = array_merge($return, $search_by_post_code);
					
				$search_by_suburb = $this->search_by_suburb();
				if(count($search_by_suburb)>0)
					$return = array_merge($return, $search_by_suburb);
					
				$search_by_country_id = $this->search_by_country_id();
				if(count($search_by_country_id)>0)
					$return = array_merge($return, $search_by_country_id);
					
				$search_by_zone_id = $this->search_by_zone_id();
				if(count($search_by_zone_id)>0)
					$return = array_merge($return, $search_by_zone_id);
			}
			else
				$return = array_merge($return, $search_by_id);
				
			sort($return);
			
			$returns = array();
			$taken = array();
			$i=0;
			foreach($return as $r)
			{
				if(!in_array($r['value'],$taken))
				{
					$returns[$i]=$r;
					array_push($taken,$r['value']);
					$i++;
				}
			}
			
			$return=$returns;
			/*
			$taken = array();
	
			foreach($return as $key => $item) {
				if(in_array($return[$key]['value'], $taken)!==true) {
					$taken[] = $return[$key]['value'];
				} else {
					
					unset($return[$key]);
				}
			}*/
			//$return = array_map("unserialize", array_unique(array_map("serialize", $return)));
			$json = json_encode($return, JSON_UNESCAPED_UNICODE);
			$json = str_replace('\u','u',$json);
			$json = preg_replace('/u([\da-fA-F]{4})/', '&#x\1;', $json);
			$json = html_entity_decode($json);
			
			return $json;
		}
		
		
		/*
		* Search by id
		*/
		private function search_by_id(){
			$return=array();
			$search = $this->search();
			
			if(false !== $search && count($search) > 0)
			{
				$terms=array();
				
				foreach($search as $val){
					$terms[]="address_book_id = '{$val}'";
				}
				
				$query = tep_db_query("SELECT {$this->fields} FROM " . TABLE_ADDRESS_BOOK . " WHERE (".join(" AND ",$terms).") GROUP BY address_book_id ORDER BY entry_firstname ASC, entry_lastname ASC LIMIT 0, {$this->limit}");			
				
				$return = $this->generate_results($query);
			}
			
			return $return;
		}
		
		/*
		* Search by name
		*/
		private function search_by_name(){
			$return=array();
			$search = $this->search();
			
			if(false !== $search && count($search) > 0)
			{
				$terms=array();
				
				foreach($search as $val){
					$terms[]="CONCAT(entry_firstname,' ', entry_lastname) LIKE '%{$val}%'";
				}
				
				$query = tep_db_query("SELECT {$this->fields} FROM " . TABLE_ADDRESS_BOOK . " WHERE (".join(" AND ",$terms).") GROUP BY address_book_id ORDER BY entry_firstname ASC, entry_lastname ASC LIMIT 0, {$this->limit}");			
				
				$return = $this->generate_results($query);
			}
			
			return $return;
		}
		
		/*
		* Search by company
		*/
		private function search_by_company(){
			$return=array();
			$search = $this->search();
			
			if(false !== $search && count($search) > 0)
			{
				$terms=array();
				
				foreach($search as $val){
					$terms[]="entry_company LIKE '%{$val}%'";
				}
				
				$query = tep_db_query("SELECT {$this->fields} FROM " . TABLE_ADDRESS_BOOK . " WHERE (".join(" AND ",$terms).") GROUP BY address_book_id ORDER BY entry_firstname ASC, entry_lastname ASC LIMIT 0, {$this->limit}");			
				
				$return = $this->generate_results($query);
			}
			
			return $return;
		}
		
		/*
		* Search by customer email
		*/
		private function search_by_email(){
			$return=array();
			$search = $this->search();
			
			if(false !== $search && count($search) > 0)
			{
				$terms=array();
				
				foreach($search as $val){
					$terms[]="entry_customer_email LIKE '%{$val}%'";
				}
				
				$query = tep_db_query("SELECT {$this->fields} FROM " . TABLE_ADDRESS_BOOK . " WHERE (".join(" AND ",$terms).") GROUP BY address_book_id ORDER BY entry_firstname ASC, entry_lastname ASC LIMIT 0, {$this->limit}");			
				
				$return = $this->generate_results($query);
			}
			
			return $return;
		}
		
		/*
		* Search by street address
		*/
		private function search_by_street_address(){
			$return=array();
			$search = $this->search();
			
			if(false !== $search && count($search) > 0)
			{
				$terms=array();
				
				foreach($search as $val){
					$terms[]="entry_street_address LIKE '%{$val}%'";
				}
				
				$query = tep_db_query("SELECT {$this->fields} FROM " . TABLE_ADDRESS_BOOK . " WHERE (".join(" AND ",$terms).") GROUP BY address_book_id ORDER BY entry_firstname ASC, entry_lastname ASC LIMIT 0, {$this->limit}");			
				
				$return = $this->generate_results($query);
			}
			
			return $return;
		}
		
		/*
		* Search by city
		*/
		private function search_by_city(){
			$return=array();
			$search = $this->search();
			
			if(false !== $search && count($search) > 0)
			{
				$terms=array();
				
				foreach($search as $val){
					$terms[]="entry_city LIKE '%{$val}%'";
				}
				
				$query = tep_db_query("SELECT {$this->fields} FROM " . TABLE_ADDRESS_BOOK . " WHERE (".join(" AND ",$terms).") GROUP BY address_book_id ORDER BY entry_firstname ASC, entry_lastname ASC LIMIT 0, {$this->limit}");			
				
				$return = $this->generate_results($query);
			}
			
			return $return;
		}
		
		/*
		* Search by post code
		*/
		private function search_by_post_code(){
			$return=array();
			$search = $this->search();
			
			if(false !== $search && count($search) > 0)
			{
				$terms=array();
				
				foreach($search as $val){
					$terms[]="entry_postcode LIKE '%{$val}%'";
				}
				
				$query = tep_db_query("SELECT {$this->fields} FROM " . TABLE_ADDRESS_BOOK . " WHERE (".join(" AND ",$terms).") GROUP BY address_book_id ORDER BY entry_firstname ASC, entry_lastname ASC LIMIT 0, {$this->limit}");			
				
				$return = $this->generate_results($query);
			}
			
			return $return;
		}
		
		/*
		* Search by state
		*/
		private function search_by_state(){
			$return=array();
			$search = $this->search();
			
			if(false !== $search && count($search) > 0)
			{
				$terms=array();
				
				foreach($search as $val){
					$terms[]="entry_state LIKE '%{$val}%'";
				}
				
				$query = tep_db_query("SELECT {$this->fields} FROM " . TABLE_ADDRESS_BOOK . " WHERE (".join(" AND ",$terms).") GROUP BY address_book_id ORDER BY entry_firstname ASC, entry_lastname ASC LIMIT 0, {$this->limit}");			
				
				$return = $this->generate_results($query);
			}
			
			return $return;
		}
		
		/*
		* Search by suburb
		*/
		private function search_by_suburb(){
			$return=array();
			$search = $this->search();
			
			if(false !== $search && count($search) > 0)
			{
				$terms=array();
				
				foreach($search as $val){
					$terms[]="entry_suburb LIKE '%{$val}%'";
				}
				
				$query = tep_db_query("SELECT {$this->fields} FROM " . TABLE_ADDRESS_BOOK . " WHERE (".join(" AND ",$terms).") GROUP BY address_book_id ORDER BY entry_firstname ASC, entry_lastname ASC LIMIT 0, {$this->limit}");			
				
				$return = $this->generate_results($query);
			}
			
			return $return;
		}
		
		/*
		* Search by country ID
		*/
		private function search_by_country_id(){
			$return=array();
			$search = $this->search();
			
			if(false !== $search && count($search) > 0)
			{
				$terms=array();
				
				foreach($search as $val){
					$terms[]="entry_country_id LIKE '%{$val}%'";
				}
				
				$query = tep_db_query("SELECT {$this->fields} FROM " . TABLE_ADDRESS_BOOK . " WHERE (".join(" AND ",$terms).") GROUP BY address_book_id ORDER BY entry_firstname ASC, entry_lastname ASC LIMIT 0, {$this->limit}");			
				
				$return = $this->generate_results($query);
			}
			
			return $return;
		}
		
		/*
		* Search by zone ID
		*/
		private function search_by_zone_id(){
			$return=array();
			$search = $this->search();
			
			if(false !== $search && count($search) > 0)
			{
				$terms=array();
				
				foreach($search as $val){
					$terms[]="entry_zone_id LIKE '%{$val}%'";
				}
				
				$query = tep_db_query("SELECT {$this->fields} FROM " . TABLE_ADDRESS_BOOK . " WHERE (".join(" AND ",$terms).") GROUP BY address_book_id ORDER BY entry_firstname ASC, entry_lastname ASC LIMIT 0, {$this->limit}");			
				
				$return = $this->generate_results($query);
			}
			
			return $return;
		}
		
		/*
		* Generate results
		*/
		private function generate_results($query){
			$return=array();
			while ($addresses = tep_db_fetch_array($query)){
				$format_id = tep_get_address_format_id($addresses['country_id']);
				$return[(int)$addresses['address_book_id']]=array(
					'value'		=>	(int)$addresses['address_book_id'],
					'optgroup'		=>	tep_output_string_protected($addresses['firstname'] . ' ' . $addresses['lastname']),
					'label'	=>	tep_address_format($format_id, $addresses, true, ' ', ', '),
					'selected'	=>	(($addresses['address_book_id'] == $FSESSION->sendto || $addresses['address_book_id'] == $this->post('selected','int')) ? true : false)
				);
			}
			return $return;
		}
		
		/*
		* Generate search words
		*/
		private function search(){
			$search = $this->post('search');
			if(!empty($search))
			{
				$search = strtolower($search);
				$search = str_replace('\u','u',$search);
				$search = preg_replace('/u([\da-fA-F]{4})/', '&#x\1;', $search);
				$search = html_entity_decode($search);
				$search = preg_replace("/[^\p{L}\p{N}\s]+/uUi"," ",$search);
				$search = preg_replace("/\s+\s/Ui"," ",$search);
				$search = explode(' ',$search);
				
				if(count($search) > 0){
					return $search;
				}
			}
			return false;
		}
		
		/* 
		* Generate and clean POST
		* @name          GET name
		* @option        string, int, float, bool, html, encoded, url, email
		* @default       default value
		*/
		private function post($name, $option="string", $default=''){
			$option = trim((string)$option);
			if(isset($_POST[$name]) && !empty($_POST[$name]))
			{        
				if(is_array($_POST[$name]))
					$is_array=true;
				else
					$is_array=false;
				
				$sanitize = array(
					'email'	=>	FILTER_SANITIZE_STRING,
					'string'    =>    FILTER_SANITIZE_STRING,
					'bool'	=>	FILTER_SANITIZE_STRING,
					'int'	=>	FILTER_SANITIZE_NUMBER_INT,
					'float'	=>	FILTER_SANITIZE_NUMBER_FLOAT,
					'html'	=>	FILTER_SANITIZE_SPECIAL_CHARS,
					'encoded'    =>    FILTER_SANITIZE_ENCODED,
					'url'	=>	FILTER_SANITIZE_URL,
					'none'	=>	'none',
					'false'	=>	'none'
				);
				
				if(is_numeric($option))
					$sanitize[$option]='none';
				
				
				if($sanitize[$option] == 'none')
				{
					if($is_array)
						$input = array_map("trim",$_POST[$name]);
					else
						$input = trim($_POST[$name]);
				}
				else
				{
					if($is_array)
					{
						$input = filter_input(INPUT_POST, $name, $sanitize[$option], FILTER_REQUIRE_ARRAY);
					}
					else
					{
						$input = filter_input(INPUT_POST, $name, $sanitize[$option]);
					}
				}
				
				
				if($is_array)
				{
					$input = array_map("rawurldecode",$input);
				}
				else
				{
					$input = rawurldecode($input);
				}
				
				switch($option)
				{
					default:
					case 'string':
					case 'html':
						$set=array(
							'options' => array('default' => $default)
						);
						if($is_array) $set['flags']=FILTER_REQUIRE_ARRAY;
						
						return filter_var($input, FILTER_SANITIZE_STRING, $set);
					break;
					case 'encoded':
						return (!empty($input)?$input:$default);
					break;
					case 'url':
						$set=array(
							'options' => array('default' => $default)
						);
						if($is_array) $set['flags']=FILTER_REQUIRE_ARRAY;
						
						return filter_var($input, FILTER_VALIDATE_URL, $set);
					break;
					case 'email':
						$set=array(
							'options' => array('default' => $default)
						);
						if($is_array) $set['flags']=FILTER_REQUIRE_ARRAY;
						
						return filter_var($input, FILTER_VALIDATE_EMAIL, $set);
					break;
					case 'int':
						$set=array(
							'options' => array('default' => $default, 'min_range' => 0)
						);
						if($is_array) $set['flags']=FILTER_FLAG_ALLOW_OCTAL | FILTER_REQUIRE_ARRAY;
						
						return filter_var($input, FILTER_VALIDATE_INT, $set);
					break;
					case 'float':
						$set=array(
							'options' => array('default' => $default)
						);
						if($is_array) $set['flags']=FILTER_REQUIRE_ARRAY;
						
						return filter_var($input, FILTER_VALIDATE_FLOAT, $set);
					break;
					case 'bool':
						$set=array(
							'options' => array('default' => $default)
						);
						if($is_array) $set['flags']=FILTER_REQUIRE_ARRAY;
						
						return filter_var($input, FILTER_VALIDATE_BOOLEAN, $set);
					break;
					case 'none':
						return $input;
					break;
				}
			}
			else
			{
				return $default;
			}
		}
		/* 
		* Generate and clean GET
		* @name          GET name
		* @option        string, int, float, bool, html, encoded, url, email
		* @default       default value
		*/
		private function get($name, $option="string", $default=''){
			$option = trim((string)$option);
			if(isset($_GET[$name]) && !empty($_GET[$name]))
			{           
				if(is_array($_GET[$name]))
					$is_array=true;
				else
					$is_array=false;
				
				$sanitize = array(
					'email'	=>	FILTER_SANITIZE_STRING,
					'string'    =>    FILTER_SANITIZE_STRING,
					'bool'	=>	FILTER_SANITIZE_STRING,
					'int'	=>	FILTER_SANITIZE_NUMBER_INT,
					'float'	=>	FILTER_SANITIZE_NUMBER_FLOAT,
					'html'	=>	FILTER_SANITIZE_SPECIAL_CHARS,
					'encoded'    =>    FILTER_SANITIZE_ENCODED,
					'url'	=>	FILTER_SANITIZE_URL,
					'none'	=>	'none',
					'false'	=>	'none'
				);
				
				if(is_numeric($option))
					$sanitize[$option]='none';
				
				
				if($sanitize[$option] == 'none')
				{
					if($is_array)
						$input = array_map("trim",$_GET[$name]);
					else
						$input = trim($_GET[$name]);
				}
				else
				{
					if($is_array)
					{
						$input = filter_input(INPUT_GET, $name, $sanitize[$option], FILTER_REQUIRE_ARRAY);
					}
					else
					{
						$input = filter_input(INPUT_GET, $name, $sanitize[$option]);
					}
				}
				
				if($is_array)
				{
					$input = array_map("rawurldecode",$input);
				}
				else
				{
					$input = rawurldecode($input);
				}
				
				switch($option)
				{
					default:
					case 'string':
					case 'html':
						$set=array(
							'options' => array('default' => $default)
						);
						if($is_array) $set['flags']=FILTER_REQUIRE_ARRAY;
						
						return filter_var($input, FILTER_SANITIZE_STRING, $set);
					break;
					case 'encoded':
						return (!empty($input)?$input:$default);
					break;
					case 'url':
						$set=array(
							'options' => array('default' => $default)
						);
						if($is_array) $set['flags']=FILTER_REQUIRE_ARRAY;
						
						return filter_var($input, FILTER_VALIDATE_URL, $set);
					break;
					case 'email':
						$set=array(
							'options' => array('default' => $default)
						);
						if($is_array) $set['flags']=FILTER_REQUIRE_ARRAY;
						
						return filter_var($input, FILTER_VALIDATE_EMAIL, $set);
					break;
					case 'int':
						$set=array(
							'options' => array('default' => $default, 'min_range' => 0)
						);
						if($is_array) $set['flags']=FILTER_FLAG_ALLOW_OCTAL | FILTER_REQUIRE_ARRAY;
						
						return filter_var($input, FILTER_VALIDATE_INT, $set);
					break;
					case 'float':
						$set=array(
							'options' => array('default' => $default)
						);
						if($is_array) $set['flags']=FILTER_REQUIRE_ARRAY;
						
						return filter_var($input, FILTER_VALIDATE_FLOAT, $set);
					break;
					case 'bool':
						$set=array(
							'options' => array('default' => $default)
						);
						if($is_array) $set['flags']=FILTER_REQUIRE_ARRAY;
	
						
						return filter_var($input, FILTER_VALIDATE_BOOLEAN, $set);
					break;
					case 'none':
						return $input;
					break;
				}
			}
			else
			{
				return $default;
			}
		}
	}
	new ajax_search_address_book();
}
else
{
	header("Location: http://".$_SERVER['HTTP_HOST']);
}