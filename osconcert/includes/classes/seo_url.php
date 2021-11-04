<?php
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
/*

  class seo_url{
	var $lastError;
	var $enabled;
	
	function seo_url(){							
		// check for initial params
		global $joomla_include,$cookie_path;
		$this->enabled=true;
		$this->lastError="";
        if (strtolower(SEARCH_ENGINE_FRIENDLY_URLS) == 'true'){
		    if (strtolower(USE_CACHE) <> 'true'){
				$this->enabled=false;
				$this->lastError.='Use cache needs to be set to \'True\' in the Admin.';
			} else {
				if (!is_writable(DIR_FS_CACHE)){
					$this->enabled=false;
					$this->lastError='The cache directory \'' .DIR_FS_CACHE . '\' needs to be writable to use SEO URL\'s.';
				}
			}
	   		if (strtolower(SESSION_FORCE_COOKIE_USE) <> 'true'){
				$this->enabled=false;
				$this->lastError.='Force cookie use needs to be set to \'True\' in the Admin';
			} 
			if ($this->lastError!='') $this->lastError="SEO URL Error: " . $this->lastError;
		} else {
			$this->enabled=false;
		}
	}
    // Prepares URL characters
    function prepare_url($url) {
		  $url = strtr($url, '������������������������������������������������������������', 'SZszYAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy');
	
		  $url = str_replace(' ', '-', preg_replace('[^/[:space:]a-zA-Z0-9_-]/', '', $url));
	
		  while (strstr($url, '--')) $url = str_replace('--', '-', $url);
		  return $url;
    }

    // Select which pages to use SEO URLs on
    function pages($page) {
      $page_array = array(FILENAME_DEFAULT, FILENAME_PRODUCT_INFO,'link_index.php');
      return in_array($page, $page_array);
    }

    // Transform the URLs   
    function convert_seo_url($url) {
      global $FSESSION;		
      // Split the URL parts into an array
      $url_parts = parse_url($url);
      // Exit if the URL is not specified in the pages function
	  $tempPath=$url_parts['path'];
	  if (strlen(DIR_WS_HTTP_CATALOG)>1) $tempPath=trim(str_replace(DIR_WS_HTTP_CATALOG,'',$url_parts['path']));
	  else if (substr($url_parts['path'],0,1)=="/")
	     $tempPath=substr($url_parts['path'],1);
		 
	  $tempSplt=preg_split('///', $tempPath);
      if ((strpos($url, 'action')!==false || strpos($url, 'command')!==false) || (!$this->pages(current($url_array=explode("/",$tempPath)))))
			return $url;
	  // Remove the page name from the URL array
	  array_shift($url_array);
      $url_parts['path'] = rtrim(DIR_WS_HTTP_CATALOG, '/');

		$item_type='';
		$main_path='';
		$extra_path='';
      for ($i = 0; $i < sizeof($url_array); $i++) {
      	switch ($url_array[$i]) {
          case 'cPath':
            $i++;
            $item_type='products';
			
            $category_array = explode('_', $url_array[$i]);

            foreach ($category_array as $categories_id) {
              $category_query = tep_db_query("select distinct c.categories_id, cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$categories_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$FSESSION->languages_id . "'");
              $category_name = tep_db_fetch_array($category_query);
              $main_path .= '/' . $this->prepare_url($category_name['categories_name']);
            }

            break;

          case 'products_id':
            $i++;
            if (strpos($url, 'cPath') === false) {
              $item_type='products';
              $cat_array = array();
              $cat_query = tep_db_query("SELECT cd.categories_name as name, c.parent_id FROM categories c LEFT JOIN categories_description cd ON (c.categories_id = cd.categories_id) LEFT JOIN products_to_categories p2c ON (p2c.categories_id = c.categories_id) WHERE p2c.products_id = '" . (int)$url_array[$i] . "' AND p2c.categories_id = c.categories_id AND cd.language_id = '" . (int)$FSESSION->languages_id . "'");
              $cat_val = tep_db_fetch_array($cat_query);
              
              $cat_array[] = $cat_val['name'];
              
              //If this category has a parent, get the name
              if (is_numeric($cat_val['parent_id']) && $cat_val['parent_id'] != '0') {
                $parent_id = $cat_val['parent_id'];
                while ($parent_id != '0') {
                  $cat_query = tep_db_query("SELECT cd.categories_name as name, c.parent_id FROM categories c LEFT JOIN categories_description cd ON (c.categories_id = cd.categories_id) WHERE cd.categories_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$FSESSION->languages_id . "'");
                  $cat_val = tep_db_fetch_array($cat_query);
                  
                  $cat_array[] = $cat_val['name'];
                  $parent_id = $cat_val['parent_id'];
                }
              }
              
              //Reverse array order
              $cat_array = array_reverse($cat_array);
              
              for ($x = 0; $x <= sizeof($cat_array); $x++) {
                $main_path .= '/' . $this->prepare_url($cat_array[$x]);
              }
            }
            //ADD CATEGORY NAME MODIFICATION END
            
            $product_query = tep_db_query("select distinct products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$url_array[$i] . "' and language_id = '" . (int)$FSESSION->languages_id . "'");
            $product_name = tep_db_fetch_array($product_query);
            $main_path .= '/' . $this->prepare_url($product_name['products_name']);
            break;
          

          case 'manufacturers_id':
			$item_type='products';
            $i++;

            $manufacturer_query = tep_db_query("select distinct manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$url_array[$i] . "'");
            $manufacturer_name = tep_db_fetch_array($manufacturer_query);
            $main_path .= '/' . $this->prepare_url($manufacturer_name['manufacturers_name']);
            break;

          default:

            $extra_path .= '/' . $url_array[$i];

            break;
        }
      } 
	  if ($extra_path!='') $extra_path="/ex" . $extra_path;
	  	  
	  if ($item_type!='') $item_type="/" . $item_type;
	  
	  if ($main_path=="") {
	  	return $url;
	  }
      // Return the converted URL

      return $url_parts['scheme'] . '://' . $url_parts['host'] . ($url_parts['port']!=''?':' .$url_parts['port']:'') . $url_parts['path'] . $item_type . $main_path . $extra_path;

    }

	// Changes the names of URL's into intergers
	function encrypt_url($url) {
		//if( ($url!=' ' || trim($url)!='') ){
	    if( (trim(str_replace(array(" ","&nbsp;"),"",$url))!='' && $url!=' ') ){
			return sprintf('%u', crc32($this->prepare_url(strtolower($url))));
		}	
	}

	function restore_seo_url() {
		global $FREQUEST;
		
		$check_pos=strpos($FREQUEST->servervalue('REQUEST_URI'),"404;");
		if ($check_pos!==false){
			$FREQUEST->setvalue('REQUEST_URI',substr($FREQUEST->servervalue('REQUEST_URI'),$check_pos+4),'SERVER');
			$check_pos=strpos($FREQUEST->servervalue('REQUEST_URI'),DIR_WS_HTTP_CATALOG);
			$FREQUEST->setvalue('REQUEST_URI',substr($FREQUEST->servervalue('REQUEST_URI'),$check_pos),'SERVER');
		}
		// Exit if not being called from the SEO pages or contains 'action'
		if ((!$this->pages(basename($FREQUEST->servervalue('PHP_SELF')))) || ($FREQUEST->servervalue('REQUEST_URI') == '/') || (strpos($FREQUEST->servervalue('REQUEST_URI'), '.php')))
			return;
		$uri=str_replace(DIR_WS_HTTP_CATALOG,"/",$FREQUEST->servervalue('REQUEST_URI'));
		
		$request_url_array = explode('/', trim(trim($uri, '/'), $this->extention));
		
			$url_array = $this->cache_products_url();
		$extra_pos=false;
		
		for ($i = 0; $i < count($request_url_array); $i++) {
			if ($request_url_array[$i]=="ex") {
				$extra_pos=true;
				continue;
			}
			if (!$extra_pos){
				
				if ($url_array[$this->encrypt_url($request_url_array[$i])]['key'] == 'categories_id') 
				{
					 
						if ($FREQUEST->getvalue('cPath')=="") {
							$FREQUEST->setvalue('cPath',$url_array[$this->encrypt_url($request_url_array[$i])]['value'],'GET');
						} else {
							$temp_path=$FREQUEST->getvalue('cPath').'_' .$url_array[$this->encrypt_url($request_url_array[$i])]['value'];
							$FREQUEST->setvalue('cPath',$temp_path,'GET');
						}
					
				} else {
					$FREQUEST->setvalue($url_array[$this->encrypt_url($request_url_array[$i])]['key'] ,$url_array[$this->encrypt_url($request_url_array[$i])]['value'],'GET');
				}
			} else {
				$FREQUEST->setvalue($request_url_array[$i],$request_url_array[$i+1],'GET');
				$i++;
			}
		}
	}
	
 
    // Caches the URLs into a file that should be in the cache directory
    function cache_products_url() {
      global $refresh;

      if (($refresh == true) || !read_cache($url_array, 'url.products.cache')) {

        $categories_query = tep_db_query("select categories_id, categories_name from " . TABLE_CATEGORIES_DESCRIPTION);

        while ($categories = tep_db_fetch_array($categories_query)) {
          $url_array[$this->encrypt_url($categories['categories_name'])] = array('key' => 'categories_id', 'value' => $categories['categories_id']);
        }


        $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS);

        while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
          $url_array[$this->encrypt_url($manufacturers['manufacturers_name'])] = array('key' => 'manufacturers_id', 'value' => $manufacturers['manufacturers_id']);
        }


        $products_query = tep_db_query("select products_id, products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_name!='' ");

        while ($products = tep_db_fetch_array($products_query)) {
          if((trim($products['products_name']))!=''){
		  		$url_array[$this->encrypt_url($products['products_name'])] = array('key' => 'products_id', 'value' => $products['products_id']);
		  }
        }

        write_cache($url_array, 'url.products.cache');
      }
      return $url_array;
    }
	

   
  }
?>
