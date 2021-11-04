<?php
/*
    osConcert Seat Booking Software
    http://www.osconcert.com
    Copyright (c) 2021 osConcert

    Released under the GNU General Public License
*/
// Set flag that this is a parent file
defined('_FEXEC') or die();
class productsMainpage
{
    var $pagination;
    var $splitResult;
    var $catid;
    function __construct() 
	{
        $this->pagination=false;
        $this->splitResult=false;
    }

    function doSort()
	{
        global $FREQUEST,$jsData,$FSESSION;
        $mode=$FREQUEST->getvalue('mode','','A');
        $sort='asc';
        if ($mode=="D") $sort="desc";
        $cat_query=tep_db_query("SELECT c.categories_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id=cd.categories_id order by cd.categories_name " . $sort);
        $order=1;
        while($cat_result=tep_db_fetch_array($cat_query))
		{
            tep_db_query("UPDATE " . TABLE_CATEGORIES . " set sort_order=$order where categories_id=" . $cat_result["categories_id"]);
            $order++;
        }
        $order=1;
        $product_query=tep_db_query("SELECT p.products_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id=pd.products_id and pd.language_id=" . $FSESSION->languages_id . " order by pd.products_name " . $sort);
        while($product_result=tep_db_fetch_array($product_query)){
            tep_db_query("UPDATE " . TABLE_PRODUCTS . " set products_sort_order=$order where products_id=" . $product_result["products_id"]);
            $order++;
        }
        $jsData->VARS["NUclearType"]=array("prd","cat");
        $this->doCategories();
    }
    function doCategoriesList($parent_id=0,$level=1,$where='',$search='')
	{
        global $FSESSION,$jsData;


        if($search!='')
{
	$products_sql='select pd.products_id,pd.products_name,p.products_status,ptc.categories_id from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION." pd, ".TABLE_PRODUCTS_TO_CATEGORIES." ptc where p.products_id=pd.products_id and ptc.products_id=pd.products_id and pd.language_id='" . $FSESSION->languages_id . "'and  pd.products_name like '%".$search."%'order by pd.products_name";
	$product_query=tep_db_query($products_sql);

	if(tep_db_num_rows($product_query))
	{
		while($product_array=tep_db_fetch_array($product_query))
		{
			$where.='|| cd.categories_id='.$product_array["categories_id"];

			$sql_query=tep_db_query("select parent_id from ".TABLE_CATEGORIES ." where categories_id= ".$product_array["categories_id"]);

			if(tep_db_num_rows($sql_query))
			{
				$sql_array=tep_db_fetch_array($sql_query);
				$where.='|| cd.categories_id='.$sql_array["parent_id"];
			}
		}
	}

	$c_query = tep_db_query("select categories_id from ". TABLE_CATEGORIES_DESCRIPTION . " where categories_name like'%".$search."%'");

	while($c_array=tep_db_fetch_array($c_query))
	{

        $sql_query_cate=tep_db_query("select parent_id from ". TABLE_CATEGORIES ." where categories_id= ".$c_array["categories_id"]);

        if(tep_db_num_rows($sql_query_cate))
        {
            $sql_array_cate=tep_db_fetch_array($sql_query_cate);
            $where.='|| cd.categories_id='.$sql_array_cate["parent_id"];
        }
	}

	}
	if($where!='')
	{
	$where.=' ) and ';
	}
        //print_r($FSESSION);
        //$categories_sql = "select c.categories_id, c.categories_is_printable, c.categories_status, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where ".$where ." c.categories_id = cd.categories_id and cd.language_id = '" . (int)$FSESSION->languages_id . "' and c.parent_id = '" . (int)$parent_id . "' order by c.sort_order";
		
		if(HIDE_ROW_CATS=='yes')
		{
		//$lock=" categories_products_lock=1 and ";
		$lock=" parent_id=0 and ";
		}else
		{
		$lock="";
		}

        //		print_r($FSESSION);
        $categories_sql = "select c.categories_products_lock, c.categories_GA, c.categories_shipping, c.categories_quantity_remaining, c.categories_id, cd.concert_venue, c.date_id, cd.concert_date, cd.concert_time, c.categories_is_printable, c.bg_height, c.categories_status, cd.categories_name, c.plan_id, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where " . $lock . " ".$where ." c.categories_id = cd.categories_id and cd.language_id = '" . (int)$FSESSION->languages_id . "'  and c.parent_id = '" . (int)$parent_id . "' order by c.sort_order";
        $categories_query = tep_db_query($categories_sql);
        if (tep_db_num_rows($categories_query)<=0) return 0;
        $template=getCategoriesListTemplate();

        $cnt=0;
        $pos=0;
        while($categories_result=tep_db_fetch_array($categories_query)){
            if($pos == 0)
                $this->catid = $categories_result["categories_id"];
				
				if ($categories_result["categories_status"]==1)
				{
					$red="red";
					$kill="true";
				}else{
					$red="";
					$kill="none";
				}
				if ($categories_result["categories_GA"]>0){
					$cqr='(' . $categories_result["categories_quantity_remaining"] . ')';
				}else{
					$cqr='';
				}
			if($categories_result["concert_date"]<1)
			{
			$concert_date='';
			}else{			
			$concert_date=$categories_result["concert_date"];
			}

            $rep_array=array(       "PAD_LEFT"=>$level*10,
                                    "CAT_ID"=>$categories_result["categories_id"],
                                    "CAT_PARENT"=>$parent_id,
                                    "CAT_NAME"=>$categories_result["categories_name"],
									"CAT_CATEGORIES_QUANTITY_REMAINING"=>$cqr,
									"CAT_VENUE"=>$categories_result["concert_venue"],
									"CAT_DATE"=>$concert_date,
									"CAT_TIME"=>$categories_result["concert_time"],
									"CAT_DATE_ID"=>$categories_result["date_id"],
									"CAT_PRINTABLE" => $categories_result["categories_is_printable"],
									"CAT_PLAN_ID" => $categories_result["plan_id"],
									"CAT_BG_HEIGHT" => $categories_result["bg_height"],
									"CAT_MAN_ID" => $categories_result["manufacturers_id"],
                                    "BULLET_IMAGE"=>tep_image(DIR_WS_IMAGES . 'layout/bullet_close.gif'),
                                    "UPDATING_ORDER"=>TEXT_UPDATING_ORDER,
                                    "IMAGE_PATH"=>DIR_WS_IMAGES,
                                    "UPDATE_RESULT"=>'doDisplayResult',
                                    "ROW_CLICK_GET"=>'CatInfoAndProducts',
                                    "FIRST_MENU_DISPLAY"=>"",
									"RED"=>$red,
									"KILL"=>$kill,
                                    "CAT_LEVEL"=>$level
            );
            echo mergeTemplate($rep_array,$template);
            $temp_cnt=$this->doCategoriesList($categories_result["categories_id"],$level+1);
            if ($temp_cnt>0) $cnt+=$temp_cnt;
            else $cnt++;
            if (isset($jsData->VARS["page"]))
			{
                $jsData->VARS["page"]["treeList"][$categories_result["categories_id"]]["pos"]=$pos;
                $jsData->VARS["page"]["treeList"][$categories_result["categories_id"]]["parent"]=$parent_id;
                $jsData->VARS["page"]["treeList"][$categories_result["categories_id"]]["level"]=$level;
            } else 
			{
                $jsData->VARS["storePage"]["treeList"][$categories_result["categories_id"]]["pos"]=$pos;
                $jsData->VARS["storePage"]["treeList"][$categories_result["categories_id"]]["parent"]=$parent_id;
                $jsData->VARS["storePage"]["treeList"][$categories_result["categories_id"]]["level"]=$level;
            }
            $pos++;
        }
        if (isset($jsData->VARS["page"]))
		{
            $jsData->VARS["page"]["treeList"]["level" . $level]=$cnt;
            $jsData->VARS["page"]["treeList"][$parent_id]["totalchilds"]=$cnt;
            $jsData->VARS["page"]["treeList"][$parent_id]["childs"]=$pos;
        } else {
            $jsData->VARS["storePage"]["treeList"]["level" . $level]=$cnt;
            $jsData->VARS["storePage"]["treeList"][$parent_id]["totalchilds"]=$cnt;
            $jsData->VARS["storePage"]["treeList"][$parent_id]["childs"]=$pos;
        }
        return $cnt;
    }
    function doCategorySort()
	{
        global $FREQUEST,$jsData;
        $mode=$FREQUEST->getvalue("mode","string","down");
        $category_id=$FREQUEST->getvalue("cID","int",0);
        $parent_id=$FREQUEST->getvalue("parent","int",0);

        $category_query=tep_db_query("SELECT sort_order from " . TABLE_CATEGORIES . " where categories_id=$category_id and parent_id=$parent_id");
        if (tep_db_num_rows($category_query)<=0)
		{
            echo "Err:"  . TEXT_CATEGORY_NOT_FOUND;
            return;
        }
        $category_result=tep_db_fetch_array($category_query);
        $current_order=(int)$category_result["sort_order"];

        if ($mode=="up") 
		{
            $category_sort_query=tep_db_query("select sort_order, categories_id from categories where parent_id=$parent_id and sort_order<$current_order order by sort_order desc limit 1");
        } else 
		{
            $category_sort_query=tep_db_query("select sort_order, categories_id from categories where parent_id=$parent_id and sort_order>$current_order order by sort_order limit 1");
        }
        if(tep_db_num_rows($category_sort_query)<=0)
		{
            echo "NOTRUNNED";
            return;
        }
        $categories_result=tep_db_fetch_array($category_sort_query);
        $prev_order=$categories_result['sort_order'];
        tep_db_query("UPDATE " . TABLE_CATEGORIES . " set sort_order='" . $current_order ."' where categories_id='" . (int)$categories_result['categories_id'] . "'");
        tep_db_query("UPDATE " . TABLE_CATEGORIES . " set sort_order='" . $prev_order . "' where categories_id=$category_id");
        echo "SUCCESS";
        $jsData->VARS['moveRows']=array('mode'=>$mode,'destID'=>$categories_result['categories_id']);
    }
    function doCategoryInfo($category_id)
	{
        global $FSESSION,$jsData;

        $category_query=tep_db_query("select c.parent_id,c.manufacturers_id, date_format(c.date_added,'%Y-%m-%d') as date_added, date_format(c.last_modified,'%Y-%m-%d') as last_modified,cd.categories_id,c.categories_image, c.categories_image_2,c.categories_image_3,c.categories_image_4, c.plan_id,c.categories_quantity, c.categories_GA,  c.categories_is_printable, c.categories_shipping, c.bg_height,c.categories_quantity_remaining, cd.categories_description,cd.categories_name from ".TABLE_CATEGORIES."  c, ".TABLE_CATEGORIES_DESCRIPTION." cd where c.categories_id=cd.categories_id and cd.language_id='".(int)$FSESSION->languages_id."' and c.categories_id='".(int)$category_id."' limit 1");

        //$category_query=tep_db_query("select c.parent_id,date_format(c.date_added,'%Y-%m-%d') as date_added, date_format(c.date_expires,'%Y-%m-%d') as date_expires, time(c.date_expires) as time_expires, date_format(c.last_modified,'%Y-%m-%d') as last_modified,cd.categories_id,c.categories_image,cd.categories_description,cd.categories_name,cd.concert_venue,cd.concert_date,cd.concert_time,c.plan_id from ".TABLE_CATEGORIES."  c, ".TABLE_CATEGORIES_DESCRIPTION." cd where c.categories_id=cd.categories_id and cd.language_id='".(int)$FSESSION->languages_id."' and c.categories_id='".(int)$category_id."' limit 1");
		
        if (tep_db_num_rows($category_query)>0)
		{
            $category_result=tep_db_fetch_array($category_query);
            $template=getCategoryInfoTemplate();
            $date_added="";
			
            if(format_date($category_result['date_added'])!="" && format_date($category_result["date_added"])!='00-00-0000')
            $date_added='<tr><Td class="main">' . TEXT_INFO_DATE_ADDED . ' : ' . format_date($category_result["date_added"]) . '</td></tr>';
		
			//delete image
			if(tep_not_null($category_result['categories_image']))
			{
			$date_modified=	'   <td class="main" align="left" valign="top" id="cat'.$category_id.'message_1"><img src="images/template/img_trash.gif" alt="Delete image" title="Delete image" style="cursor:pointer;cursor:hand;" onClick="javascript:doDeleteImg('.$category_id.');" onMouseOver="javascript:doImageHover(this,\'template/img_trash_hover.gif\');" onMouseOut="javascript:doImageHover(this,\'template/img_trash.gif\');" />&nbsp;&nbsp;'.TEXT_DELETE_INFO_IMAGE.' &nbsp;&nbsp;</td>';
			}
			
            if(format_date($category_result["last_modified"])!="")
			{
            $date_modified.='<tr><td class="main">' . TEXT_INFO_DATE_MODIFIED . ': ' . format_date($category_result["last_modified"]) . '</td></tr>';
			}

			$SERVER_DATE_TIME = getServerDate(true);

			//is shipping weight enabled for this category?
			if($category_result['categories_shipping']==1)	
			{
			$shipping_enabled='yes';	
			}else
			{
			$shipping_enabled='no';	
			}
			//shipping tag 						
			//$shipping_enabled=$category_result['categories_shipping'];
			$cat_date_expires.='<tr><td class="main">' . TEXT_SHIPPING . ': ' .$shipping_enabled. '</td></tr>';
			
         
            $rep_array=array(	    "CAT_DATE_ADDED"=>$date_added,
                                    "CAT_DATE_MODIFIED"=>$date_modified,
									"CAT_PLAN_ID"=>$category_result["plan_id"],
									"CAT_MAN_ID"=>$category_result["manufacturers_id"],
									"CAT_BG_HEIGHT"=>$category_result["bg_height"],
									"CAT_PRINTABLE"=>$category_result["categories_is_printable"],
                                    "CAT_DESCRIPTION"=>tep_db_prepare_input($category_result["categories_description"]),
                                    "CAT_IMAGE_WIDTH"=>SMALL_IMAGE_WIDTH,
                                    "CAT_IMAGE"=>"<span id=\"image_".$category_id."_cat\">".tep_display_image($category_result["categories_image"],$category_result["categories_name"],SMALL_IMAGE_WIDTH,'','',true)."</span>",
                                    "UPDATE_RESULT"=>'doDisplayResult'
            );
			if ($category_result["categories_GA"]>0)
			{//if ==1 then it's a GA category with master quantity
					$rep_array["CAT_CATEGORIES_QUANTITY"] = '<tr><td class="main">'.TEXT_CAT_QUANTITY.' : '.tep_db_prepare_input($category_result["categories_quantity"]).'</td></tr>';
					$rep_array["CAT_CATEGORIES_QUANTITY_REMAINING"] = '<tr><td class="main">'.TEXT_CAT_QUANTITY_REMAINING.' : '.tep_db_prepare_input($category_result["categories_quantity_remaining"]).'</td></tr>';
					
			} else 
			{
					$rep_array["CAT_CATEGORIES_QUANTITY"] = '';
					$rep_array["CAT_CATEGORIES_QUANTITY_REMAINING"] = '';
			}
            echo mergeTemplate($rep_array,$template);
			
        }
        $jsData->VARS["updateMenu"]=",normal,";
    }
	
    function doCategoryUpdate()
	{
		global $FREQUEST,$SERVER_DATE,$SERVER_DATE_TIME,$LANGUAGES,$jsData,$FSESSION,$COMMANDS;
		$update_array=array();
		$category_id=$FREQUEST->postvalue('category_id','int',0);
		$parent_query=tep_db_query("select parent_id from ".TABLE_CATEGORIES ." where categories_id= ".$category_id);	

		while($parent_result=tep_db_fetch_array($parent_query))
		{	
		$pi=$parent_result['parent_id'];
		}	   


		$categories_GA =$FREQUEST->postvalue('is_ga');
		$categories_quantity=$FREQUEST->postvalue('init_quan','int',NULL);
		$categories_quantity_remaining=$FREQUEST->postvalue('quan_left','int',NULL);
		$categories_plan_id=$FREQUEST->postvalue('categories_plan_id');
		$categories_bg_height=$FREQUEST->postvalue('categories_bg_height');
		$categories_row_colour=$FREQUEST->postvalue('categories_row_colour');
		$update_array['categories_is_printable']=$FREQUEST->postvalue('category_printable');
		$update_array['categories_shipping']=$FREQUEST->postvalue('categories_shipping');
		$update_array['categories_image']=$FREQUEST->postvalue('categories_image');
		$update_array['categories_image_2']=$FREQUEST->postvalue('categories_image_2');
		$update_array['categories_image_3']=$FREQUEST->postvalue('categories_image_3');
		$update_array['categories_image_4']=$FREQUEST->postvalue('categories_image_4');

        $cat_lang=array();
        if ($category_id>0)
		{
	
			if($FREQUEST->postvalue('delete_image')=='yes')
			{
			$update_array["categories_image"]=NULL;
			}

			if($FREQUEST->postvalue('delete_image2')=='yes')
			{
			$update_array["categories_image_2"]=NULL;
			}
			if($FREQUEST->postvalue('delete_image3')=='yes')
			{
			$update_array["categories_image_3"]=NULL;
			}
			if($FREQUEST->postvalue('delete_image4')=='yes')
			{
			$update_array["categories_image_4"]=NULL;
			}
            $update_array["last_modified"]=$SERVER_DATE;

			if ($FREQUEST->postvalue('categories_row_colour')=='xxx')
			{
				
			}else
			{
			//check if there has been a value posted for the row colour - if so update the products
			tep_db_query("UPDATE products p, products_to_categories p2c SET p.color_code= '".$categories_row_colour."' WHERE p.products_id=p2c.products_id and p2c.categories_id ='".$category_id."'");
			$update_array["color_code"]=$categories_row_colour;
			}
			
			if ($FREQUEST->postvalue('categories_plan_id')=='9')
			{
			$update_array["plan_id"]=9;	
			}else
			{
			//Hack to prevent original seat plan stylesheet being affected by Design Mode.
			//This will update a GA product but the index.php will prevent Design Mode
			
			if(($categories_plan_id>4)&&($categories_plan_id<9)){
			$update_array["manufacturers_id"]=$categories_plan_id;
			}else{
			$update_array["manufacturers_id"]='';	
			}
			$update_array["plan_id"]=$categories_plan_id;
			}
			
			if ($FREQUEST->postvalue('categories_bg_height')=='xxx')
			{
				
			}else
			{
			$update_array["bg_height"]=$categories_bg_height;
			}
			
			//add shipping weight for Reserved Seating only
			if ($FREQUEST->postvalue('categories_shipping')=='1')
			{
			//check if there has been a value posted for shipping - if so update the products
			$shipping_weight='0.1';
			tep_db_query("UPDATE products p, products_to_categories p2c SET p.products_weight= '".$shipping_weight."' WHERE p.products_id=p2c.products_id and p.product_type = 'P' and p.section_id ='".$category_id."'");
			$update_array["categories_shipping"]=1;	
			}else
			{
			//check if there has been a value posted for shipping - if so update the products
			$shipping_weight='0';
			tep_db_query("UPDATE products p, products_to_categories p2c SET p.products_weight= '".$shipping_weight."' WHERE p.products_id=p2c.products_id and p.product_type = 'P' and p.section_id ='".$category_id."'");
			$update_array["categories_shipping"]=0;
			}

			$update_array["categories_GA"]=$categories_GA;
			$update_array["categories_quantity"]=$categories_quantity;
			$update_array["categories_quantity_remaining"]=$categories_quantity_remaining;

            tep_db_perform(TABLE_CATEGORIES,$update_array,"update","categories_id="  . $category_id);
			///wtf??
            $lang_query=tep_db_query("SELECT language_id from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id=" . $category_id);
            while($lang_result=tep_db_fetch_array($lang_query)) 
			$cat_lang[$lang_result["language_id"]]=1;
        } 
		else 
		{
            $sort_query=tep_db_query("select max(sort_order) as sort_order from ".TABLE_CATEGORIES);
            $sort_order=1;
            if (tep_db_num_rows($sort_query)>0) 
			{
                $tmp_result=tep_db_fetch_array($sort_query);
                $sort_order=$tmp_result["sort_order"]+1;
            }
            $update_array["parent_id"]=$FREQUEST->postvalue('parent_id','int',0);
            $update_array["sort_order"]=$sort_order;
            $update_array["date_added"]=$SERVER_DATE_TIME;
			if(($categories_plan_id>4)&&($categories_plan_id<9)){
            $update_array["manufacturers_id"]=$categories_plan_id;
			}else{
			$update_array["manufacturers_id"]='';
			}
			$update_array["categories_GA"]=$categories_GA;
			$update_array["categories_quantity"]=$categories_quantity;
			$update_array["categories_quantity_remaining"]=$categories_quantity_remaining;
			$update_array["categories_shipping"]='0';
			$update_array["categories_is_printable"]='1';
			$update_array["date_id"]='';
			$update_array["color_code"]='';
			$update_array["plan_id"]='';//=$categories_plan_id;
			$update_array["bg_height"]='';//=$categories_bg_height;
			//$update_array["section_id"]='0';//$category_id;
	
            tep_db_perform(TABLE_CATEGORIES,$update_array);
            $insert_id=tep_db_insert_id();
        }
		
        $cat_name=$FREQUEST->getRefValue('categories_name','POST');
		$cat_venue=$FREQUEST->getRefValue('concert_venue','POST');
		//$cat_date=$FREQUEST->getRefValue('concert_date','POST');
		//$cat_time=$FREQUEST->getRefValue('concert_time','POST');
        $cat_title=$FREQUEST->getRefValue('categories_heading_title','POST');
        $cat_desc=$FREQUEST->getRefValue('categories_description','POST');
        for ($icnt=0,$n=count($LANGUAGES);$icnt<$n;$icnt++)
		{
            $lang_id=$LANGUAGES[$icnt]['id'];
            $update_array=array("categories_name"=>tep_db_prepare_input($cat_name[$lang_id]),
								"concert_venue"=>tep_db_prepare_input($cat_venue[$lang_id]),
								//"concert_date"=>tep_db_prepare_input($cat_date[$lang_id]),
								//"concert_time"=>tep_db_prepare_input($cat_time[$lang_id]),
								"categories_heading_title"=>tep_db_prepare_input($cat_title[$lang_id]),
								"categories_description"=>tep_db_prepare_input($cat_desc[$lang_id]),
            );
            if ($category_id>0 && isset($cat_lang[$LANGUAGES[$icnt]['id']]))
			{
                tep_db_perform(TABLE_CATEGORIES_DESCRIPTION,$update_array,"update","categories_id=" . (int)$category_id . " and language_id=" . (int)$lang_id);
            } else 
			{
                $update_array["language_id"]=$lang_id;
                $update_array["categories_id"]=(int)$insert_id;
				$update_array["venue_id"]='';
				$update_array["section_id"]='';//$category_id;
                tep_db_perform(TABLE_CATEGORIES_DESCRIPTION,$update_array);
            }
        }
        if ($category_id<=0) 
		{
            $category_id=$insert_id;
            $jsData->VARS["NUclearType"]=array("prd");
            $this->doCategories($insert_id);
        } else 
		{
            $jsData->VARS["replace"]=array("cat". $category_id ."title"=>$cat_name[$FSESSION->languages_id]);
            $this->doCatInfoAndProducts($category_id);
            $jsData->VARS["prevAction"]=array('id'=>$category_id,'get'=>'CatInfoAndProducts','type'=>'cat','style'=>'boxLevel1');
            $jsData->VARS["NUclearType"]=array("prd");
            $jsData->VARS["updateMenu"]=",normal,";
        }
    }
	##################################################
    # 2021 edited to also handle subcats and products
    ##################################################
    function doCategoryDelete() {
        global $FREQUEST, $jsData;
        $jsData->VARS[ "deleteRowMulti" ] = [];
        $jsData->VARS[ "reduceTreeMulti" ] = [];

        $category_id = $FREQUEST->getvalue( 'cID', 'int', 0 );

        // get all categories in the tree below the selected one
        $subcategories_array = array();
        $this->tep_get_subcategories_to_clone( $subcategories_array, $category_id );
        // add in the selected category
        $subcategories_array[ $category_id ] = 0;
        ksort( $subcategories_array );
        foreach ( $subcategories_array as $key => $value ) {

            $category_image_query = tep_db_query( "select categories_image from " . TABLE_CATEGORIES . " where categories_id = '" . ( int )$key . "'" );
            $category_image = tep_db_fetch_array( $category_image_query );

            $duplicate_image_query = tep_db_query( "select count(*) as total from " . TABLE_CATEGORIES . " where categories_image = '" . tep_db_input( $category_image[ 'categories_image' ] ) . "'" );
            $duplicate_image = tep_db_fetch_array( $duplicate_image_query );

            if ( $duplicate_image[ 'total' ] < 2 ) {
                if ( file_exists( DIR_FS_CATALOG_IMAGES . $category_image[ 'categories_image' ] ) ) {
                    @unlink( DIR_FS_CATALOG_IMAGES . $category_image[ 'categories_image' ] );
                }
            }

            tep_db_query("delete from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$key . "'");
            tep_db_query("delete from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$key . "'");
            $this -> tep_null_all_prods ($key);
            tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$key . "'");
            $jsData->VARS[ "deleteRowMulti" ][] = ( int )$key;
            $jsData->VARS[ "reduceTreeMulti" ][] = ( int )$key;

        }
         tep_reset_seo_cache('products');
         $this->doCategories(); 
    }
    function doCategoryMove()
	{
        global $FREQUEST,$jsData;
        $category_id=$FREQUEST->postvalue('category_id','int',0);
        $new_parent_id=$FREQUEST->postvalue('new_parent_id','int',0);
        if ($category_id<=0) {
            echo 'Err:' .ERROR_CANNOT_FIND_CATEGORY;
            return;
        }
        $path = explode('_', tep_get_generated_category_path_ids($new_parent_id));
        if (in_array($category_id, $path)) 
		{
            echo 'Err:' . '<font color="red">' . ERROR_CANNOT_MOVE_CATEGORY_TO_PARENT . '</font>';
            return;
        }
        tep_db_query("update " . TABLE_CATEGORIES . " set parent_id = " . $new_parent_id . ", last_modified = now() where categories_id = " . $category_id);
        $this->doCategories();
    }
    
    function doCategoryClone(){
        
    global $FREQUEST,$jsData;
    $clone_me=$FREQUEST->postvalue('category_id');
        
        if ( $clone_me == null || !is_numeric( $clone_me ) || $clone_me < 1 ) {
        return false;
        }
		
    // get all categories in the tree below the selected one

    $subcategories_array = array();
    $this->tep_get_subcategories_to_clone( $subcategories_array, $clone_me );

    // add in the selected category (parent=0, top category)
    $subcategories_array[ $clone_me ] = 0;
    asort( $subcategories_array );

    // copy the subcategory array as new_parent_id array

    $new_parent_id = $subcategories_array;
    $new_parent_id[ 0 ] = 0;

    foreach ( $subcategories_array as $key => $value ) {
        $new_cat = $this->tep_insert_cloned_category( $key, 1, $value );
        $this->tep_clone_all_prods( $key, $new_cat );
    }
     $this->doCategories();       
    }
#################################################################
// gets all products in one category and clones them into another
// using p2c table
#################################################################

function tep_clone_all_prods( $old_cat, $new_cat ) {
    $products_query_result = tep_db_query( "SELECT * from products_to_categories where categories_id = '" . $old_cat . "'" );

    while ( $product = tep_db_fetch_array( $products_query_result ) ) {
        $this->tep_clone_products( $product[ 'products_id' ], $new_cat );
    }
}
#################################################################
// gets all products in one category and changes quantity to zero
// and status to zero using p2c
#################################################################

function tep_null_all_prods( $cat_id ) {
    $products_query_result = tep_db_query( "SELECT * from products_to_categories where categories_id = '" . $cat_id . "'" );

    while ( $product = tep_db_fetch_array( $products_query_result ) ) {
         tep_db_query( "UPDATE products set products_status = 0, products_quantity = 0 where products_id = '" .$product[ 'products_id' ] . "'");
    }
}
#################################################################
// returns an array of all subcategories in the tree - formatted
// subcategories[cat_id] => parent_id 
#################################################################
function tep_get_subcategories_to_clone( & $subcategories_array, $parent_id = 0 ) {
   // echo "select categories_id, parent_id from " . TABLE_CATEGORIES . " where parent_id = '" . ( int )$parent_id . "'" . "<br>";
    $subcategories_query = tep_db_query( "select categories_id, parent_id from " . TABLE_CATEGORIES . " where parent_id = '" . ( int )$parent_id . "'" );
    while ( $subcategories = tep_db_fetch_array( $subcategories_query ) ) {
        $subcategories_array[ $subcategories[ 'categories_id' ] ] = $subcategories[ 'parent_id' ];
        if ( $subcategories[ 'categories_id' ] != $parent_id ) {
            $this->tep_get_subcategories_to_clone( $subcategories_array, $subcategories[ 'categories_id' ] );
        }
    }
}

#################################################################
// clones a new category based on $cat_to_clone_id
// returns the category_id for the newly cloned category
// also updates the parent_id array so that array[old_cat_id ] => new_cat_id
// this is used in the clone products function
#################################################################

function tep_insert_cloned_category( $cat_to_clone_id, $status = 1, $parent = 0 ) {

    global $new_parent_id;

    if ( $cat_to_clone_id == null || !is_numeric( $cat_to_clone_id ) || $cat_to_clone_id < 1 ) {
        return false;
    }
    if ($parent == 0){ $status = 0;} //remove this line if you want top category to be 'on'
    // copy the category
    tep_db_query( "DROP TABLE IF EXISTS temporary_table" );
    tep_db_query( "CREATE table temporary_table AS SELECT * FROM " . TABLE_CATEGORIES . " where categories_id = '" . ( int )$cat_to_clone_id . "'" );
    tep_db_query( "UPDATE temporary_table SET categories_status = '" . $status . "'" );
    tep_db_query( "UPDATE temporary_table SET parent_id = '" . $new_parent_id[ ( int )$parent ] . "'" );


    tep_db_query( "UPDATE temporary_table SET categories_id=NULL" );
    tep_db_query( "INSERT INTO " . TABLE_CATEGORIES . " SELECT * FROM temporary_table" );
    $new_category_id = tep_db_insert_id();
    $new_parent_id[ $cat_to_clone_id ] = $new_category_id;

    tep_db_query( "DROP TABLE temporary_table" );
    // copy the category description
    tep_db_query( "CREATE table temporary_table AS SELECT * FROM categories_description where categories_id = '" . ( int )$cat_to_clone_id . "'" );
    tep_db_query( "UPDATE temporary_table SET categories_id='" . $new_category_id . "'" );
    tep_db_query( "INSERT INTO categories_description SELECT * FROM temporary_table" );
    tep_db_query( "DROP TABLE temporary_table" );

    return $new_category_id;
}

#################################################################
// clones existing products into a new category
#################################################################

function tep_clone_products( $prod_id, $new_cat_id ) {
    
    set_time_limit(45); // may be needed for slow servers and/or big seatplans

    if ( $prod_id == null || !is_numeric( $prod_id ) || $prod_id < 1 ) {
        return false;
    }
    if ( $new_cat_id == null || !is_numeric( $new_cat_id ) || $new_cat_id < 1 ) {
        return false;
    }
    // copy the products
    tep_db_query( "DROP TABLE IF EXISTS temporary_table" );
    tep_db_query( "CREATE table temporary_table AS SELECT * FROM " . TABLE_PRODUCTS . " where products_id = '" . ( int )$prod_id . "'" );
    tep_db_query( "UPDATE temporary_table SET parent_id = '" . $new_cat_id . "'" );
    tep_db_query( "UPDATE temporary_table SET section_id = '" . $new_cat_id . "'" );
    tep_db_query( "UPDATE temporary_table SET products_id=NULL" );
    // copy the products_description table here
    tep_db_query( "INSERT INTO " . TABLE_PRODUCTS . " SELECT * FROM temporary_table" );
    $new_product_id = tep_db_insert_id();
    tep_db_query( "DROP TABLE temporary_table" );

    tep_db_query( "CREATE table temporary_table AS SELECT * FROM products_description where products_id = '" . ( int )$prod_id . "'" );
    tep_db_query( "UPDATE temporary_table SET products_id='" . $new_product_id . "'" );
    tep_db_query( "INSERT INTO products_description SELECT * FROM temporary_table" );
    tep_db_query( "DROP TABLE temporary_table" );

    // add an entry into P2C

    tep_db_query( "INSERT INTO `products_to_categories` (`products_id`, `categories_id`, `section_id`) VALUES ('" . ( int )$new_product_id . "', '" . ( int )$new_cat_id . "', '" . ( int )$new_cat_id . "');" );
}

    function doRowStatus()
		{
        global $FREQUEST,$jsData;
        $category_id=$FREQUEST->postvalue('category_id','int',0);
        $new_status_id=$FREQUEST->postvalue('new_status_id','int',0);
        if ($category_id<=0) 
		{
            echo 'Err:' .ERROR_CANNOT_FIND_CATEGORY;
            return;
        }
		
        tep_db_query("update " . TABLE_PRODUCTS . " set products_status = " . $new_status_id . " where product_type='P' AND manufacturers_id = " . $category_id);
        $this->doCategories();
    }
    function doProductDelete()
	{
        global $FREQUEST,$jsData;
        $product_id=$FREQUEST->postvalue('product_id','int',0);
        $category_id=$FREQUEST->postvalue('category_id','int',0);
        $last_flag=$FREQUEST->postvalue('lflag','int',0);
        $page=$FREQUEST->postvalue('page','int',0);
        if ($category_id>0){
			
            $jsData->VARS['storePage']['opened']['cat']=array("id"=> $category_id ,"get"=>"CatInfoAndProducts","result"=>"doDisplayResult","type"=>"cat","params"=>"cID=$category_id","style"=>"boxLevel1");
            }

        tep_set_time_limit(100);
        $product_categories = $FREQUEST->getRefValue('product_categories','POST');
        if ($product_id>0 && count($product_categories)>0)
		{
            $deleteRow=false;
            for ($icnt=0, $n=count($product_categories); $icnt<$n; $icnt++) 
			{
                if ($product_categories[$icnt]==$category_id) $deleteRow=true;
                tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id =$product_id and categories_id =" . (int)$product_categories[$icnt]);
            }
            $product_categories_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = $product_id");
            $product_categories = tep_db_fetch_array($product_categories_query);
            if ($product_categories['total'] == 0) 
			{
                tep_remove_product($product_id);

                tep_db_query("delete from ".TABLE_PRODUCTS_TO_CATEGORIES." where products_id='$product_id'");
            }
            if ($deleteRow){
                if ($last_flag==1 && $page>1)
				{
                    $page=$page-1;
                    $FREQUEST->setvalue('page',$page,'GET');
                }
                $this->doCategoryProducts($category_id);
            } else 
			{
                $jsData->VARS["updateMenu"]=",normal,";
                $jsData->VARS["closeRow"]=array("id"=>$product_id,"type"=>"prd");
            }
            $jsData->VARS["displayMessage"]=array('text'=>TEXT_PRODUCT_DELETE_SUCCESS);
            tep_reset_seo_cache('products');
        } else {
            echo "Err:" . TEXT_PRODUCT_NOT_DELETED;
        }

    }
    function doProductsList($where='',$category_id=0,$search='')
	{
        global $FSESSION,$FREQUEST,$jsData;
        $page=$FREQUEST->getvalue('page','int',1);
        if ($search!='')
		{
			$orderBy="order by p.products_sort_order,pd.products_id ASC";
        } else 
		{
			$orderBy="order by p.products_sort_order,pd.products_id ASC";
        }
        $query_split=false;
        $products_sql='select pd.products_id,pd.products_name,p.products_quantity,p.product_type,p.products_date_available,p.products_price,p.color_code,p.products_status,p.section_id,p.parent_id,ptc.categories_id from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION." pd, ".TABLE_PRODUCTS_TO_CATEGORIES." ptc where p.products_id=pd.products_id and ptc.products_id=pd.products_id and pd.language_id='" . $FSESSION->languages_id . "' $where $orderBy";
        $maxRows=$FSESSION->get('displayRowsCnt');
        if ($this->pagination && $maxRows!=-1)
		{
            $query_split=$this->splitResult = (new instance)->getSplitResult('PRDMAIN');
            $query_split->maxRows=$maxRows;
            $query_split->parse($page,$products_sql);
            if ($query_split->queryRows > 0)
			{
                if ($search!='')
				{
                    $query_split->pageLink="doPageAction({'id':-1,'type':'prd','get':'SearchProducts','result':doTotalResult,params:'search='". $search . "&page='+##PAGE_NO##,'message':'" . INFO_SEARCHING_DATA . "'})";
                } else 
				{
                    $query_split->pageLink="doPageAction({'id':-1,'type':'prd','pageNav':true,'closePrev':true,'get':'CategoryProducts','result':doTotalResult,params:'cID=". $category_id . "&page='+##PAGE_NO##,'message':'" . sprintf(INFO_LOADING_PRODUCTS,'##PAGE_NO##') . "'})";
                }
            }
        }
        $products_query=tep_db_query($products_sql);

        $found=false;
        $pCnt=tep_db_num_rows($products_query);
        if ($pCnt>0) $found=true;
		
		$products_result["section_id"]=$section_id;
		
        $template=getProductsListTemplate();
        while($products_result=tep_db_fetch_array($products_query))
		{
            
	if($products_result["product_type"]=='Q' || $products_result["product_type"]=='L')
		{
			$icon_status='';
		}else
		{
			 $icon_status='<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $products_result["products_id"] .",get:'ProductChangeStatus',result:doTotalResult,params:'pID=". $products_result["products_id"] . "&status=" .($products_result["products_status"]==1?0:1) ."&cID=".$products_result["categories_id"]. "','message':'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/' . ($products_result["products_status"]==1?'icon_active.gif':'icon_inactive.gif')) . '</a>';
		}

			$rep_array=array(	"PRD_ID"=>$products_result["products_id"],
                                    "ID"=>$products_result["products_id"],
                                    "TYPE"=>"prd",
                                    "CAT_ID"=>$products_result["categories_id"],
                                    "SEARCH_NEEDED"=>($search?"display:none":"display:normal"),
                                    "PRD_NAME"=>tep_db_prepare_input($products_result["products_name"]),
									"PRD_QTY"=>tep_db_prepare_input($products_result["products_quantity"]),
                                    "IMAGE_PATH"=>DIR_WS_IMAGES,
									"SECTION_ID"=>$products_result["section_id"],
                                    "UPDATING_ORDER"=>TEXT_UPDATING_ORDER,
                                    "PRD_STATUS"=>$icon_status,
                                    "UPDATE_RESULT"=>'doDisplayResult',
                                    "ROW_CLICK_GET"=>'ProductInfo',
                                    "FIRST_MENU_DISPLAY"=>($maxRows==-1 || $maxRows>=$query_split->queryRows)?'display:normal':'display:none',
                                    "FIRST_MENU_DISPLAY"=>($products_result["products_status"] == 1)?'display:normal':'display:none',
                                    "EDIT_MENU_DISPLAY"=>"",
									"CLR_BAND"=>$products_result["color_code"],
									"PRD_PRICE"=>$products_result["products_price"],
                                    "FLAG_ONE_RECORD"=>($pCnt==1?'&last_flag=1':'')
            );
            echo mergeTemplate($rep_array,$template);
        }
        if (!isset($jsData->VARS["page"]))
		{
            $jsData->VARS["NUclearType"][]="prd";
        }
        $jsData->VARS['extraParams']=array('page'=>$page,'search'=>$search);
        
        if ($found == false){
           // exit ('ss');
        }
        return $found;
    }
	//replaced original function nov 2013
    function doProductChangeStatus()
	{
        global $FREQUEST,$jsData;
        $product_id=$FREQUEST->getvalue("pID","int",0);
        $status=$FREQUEST->getvalue("status","int",0);
        $category_id=$FREQUEST->getvalue("cID","int",0);
        if ($product_id<=0) return;
		 $product_type_query = tep_db_query("select product_type from " . TABLE_PRODUCTS . " where products_id = '" . (int)$product_id . "'");
            $product_type_result=tep_db_fetch_array($product_type_query);
			$product_type=$product_type_result["product_type"];
		//if this product is for RESERVED SEATING
		if($product_type=='P')
		{
        if ($status!=0 && $status!=1) $status=0;//basically if not 1  or 0 its got  to  be  0
        tep_db_query("UPDATE " . TABLE_PRODUCTS . " set products_status=" . $status . ", products_quantity=" . $status . " where products_id=$product_id");
		}else //if its  not a reserved seat
		{
		if ($status!=0 && $status!=1) $status=0;//
        tep_db_query("UPDATE " . TABLE_PRODUCTS . " set products_status=" . $status . " where products_id=$product_id");	
		}
        if ($status==1){
            $result='<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $product_id .",get:'ProductChangeStatus',result:doSimpleResult,params:'pID=". $product_id . "&status=0',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_active.gif') . '</a>';
        } else 
		{
            $result='<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $product_id .",get:'ProductChangeStatus',result:doSimpleResult,params:'pID=". $product_id . "&status=1',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_inactive.gif') . '</a>';
        }
		
        echo 'SUCCESS';
         $jsData->VARS["doFunc"]=array('type'=>'display','data'=>'{"id":' . $category_id . ',"get":"CatInfoAndProducts","result":doDisplayResult,"type":"cat","params":"cID=' . $category_id . '","style":"boxLevel1"}');
        $jsData->VARS["replace"]=array("prd". $product_id ."bullet"=>$result);
    }
	############################################################################
	function doProductChangeRowStatus()
	{
        global $FREQUEST,$jsData;
        $product_id=$FREQUEST->getvalue("pID","int",0);
        $status=$FREQUEST->getvalue("status","int",0);
        $category_id=$FREQUEST->getvalue("cID","int",0);
        if ($product_id<=0) return;
		 $product_type_query = tep_db_query("select product_type from " . TABLE_PRODUCTS . " where products_id = '" . (int)$product_id . "'");
            $product_type_result=tep_db_fetch_array($product_type_query);
			$product_type=$product_type_result["product_type"];
		if($product_type=='P')
		{
        if ($status!=0 && $status!=1) $status=0;//basically if not 1  or 0 its got  to  be  0
        tep_db_query("UPDATE " . TABLE_PRODUCTS . " set products_status=" . $status . ", products_quantity=" . $status . " where products_id=$product_id");
		}else //if its  not a reserved seat
		{
		if ($status!=0 && $status!=1) $status=0;//
        tep_db_query("UPDATE " . TABLE_PRODUCTS . " set products_status=" . $status . " where products_id=$product_id");	
		}
        if ($status==1){
            $result='<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $product_id .",get:'ProductChangeStatus',result:doSimpleResult,params:'pID=". $product_id . "&status=0',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_active.gif') . '</a>';
        } else 
		{
            $result='<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $product_id .",get:'ProductChangeStatus',result:doSimpleResult,params:'pID=". $product_id . "&status=1',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_inactive.gif') . '</a>';
        }
        echo 'SUCCESS';
         $jsData->VARS["doFunc"]=array('type'=>'display','data'=>'{"id":' . $category_id . ',"get":"CatInfoAndProducts","result":doDisplayResult,"type":"cat","params":"cID=' . $category_id . '","style":"boxLevel1"}');
        $jsData->VARS["replace"]=array("prd". $product_id ."bullet"=>$result);
    }
	###########################################################################
    function doProductSort()
	{
        global $FREQUEST,$jsData;
        $product_id=$FREQUEST->getvalue("pID","int",0);
        $mode=$FREQUEST->getvalue("mode","string","down");
        $category_id=$FREQUEST->getvalue("cID","int",0);
        $product_query=tep_db_query("select products_sort_order from " . TABLE_PRODUCTS . " where products_id=$product_id");
        if (tep_db_num_rows($product_query)<=0) {
            echo "Err:" . TEXT_PRODUCT_NOT_FOUND;
            return;
        }
        $product=tep_db_fetch_array($product_query);
        $current_order=(int)$product["products_sort_order"];
        if ($mode=='up')
		{
            $products_sort_query=tep_db_query("select p.products_sort_order, p.products_id from products p, products_to_categories pc where pc.categories_id='$category_id' and pc.products_id=p.products_id and p.products_sort_order<" . $current_order . " order by products_sort_order desc limit 1");
        } else 
		{
            $products_sort_query=tep_db_query("select p.products_sort_order, p.products_id from products p, products_to_categories pc where pc.categories_id='$category_id' and pc.products_id=p.products_id and p.products_sort_order>" . $current_order . " and p.products_status = 1 order by products_sort_order limit 1");
        }
        if(tep_db_num_rows($products_sort_query)<=0)
		{
            echo "NOTRUN";
            return;
        }
        $products_sort_result=tep_db_fetch_array($products_sort_query);
        $prev_order=$products_sort_result['products_sort_order'];
        tep_db_query("UPDATE " . TABLE_PRODUCTS . " set products_sort_order='$current_order' where products_id=" . (int)$products_sort_result['products_id']);
        tep_db_query("UPDATE " . TABLE_PRODUCTS . " set products_sort_order='$prev_order' where products_id=$product_id");
        echo "SUCCESS";
        $jsData->VARS["moveRow"]=array("mode"=>$mode,"destID"=>$products_sort_result["products_id"]);
    }
    function doProductInfo($product_id=0)
	{
        global $FSESSION,$FREQUEST,$currencies;
        if ($product_id<=0) $product_id=$FREQUEST->getvalue("pID","int",0);
        $product_query = tep_db_query("select pd.products_id,pd.products_name, pd.products_description, p.products_quantity,p.products_price,products_tax_class_id,date_format(p.products_date_added,'%Y-%m-%d') as products_date_added, p.products_date_available,p.product_type,date_format(p.products_last_modified,'%Y-%m-%d') as products_last_modified,p.products_status,p.products_image_1,p.products_title_1,p.products_x,p.products_y,p.products_w,p.products_h,p.products_r,p.products_sx,p.products_sy,p.products_sort_order,p.master_quantity from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$product_id . "' and p.products_id = pd.products_id and pd.language_id = '" . (int)$FSESSION->languages_id . "'");
        if (tep_db_num_rows($product_query)>0)
		{
            $product_result=tep_db_fetch_array($product_query);
            $template=getProductInfoTemplate($product_id);
            $date_added="";
			$product_type=$product_result["product_type"];
            if(format_date($product_result["products_date_added"])!='00-00-0000' && format_date($product_result["products_date_added"])!='')
            $date_added='<tr><Td class="main">' . TEXT_INFO_DATE_ADDED . ' : ' . format_date($product_result["products_date_added"]) . '</td></tr>';
            $date_modified="";
            if(format_date($product_result["products_last_modified"])!='00-00-0000' && format_date($product_result["products_last_modified"])!='')
            $date_modified='<tr><td class="main">' . TEXT_INFO_DATE_MODIFIED . ' : ' . format_date($product_result["products_last_modified"]) . '</td></tr>';
		if($product_type=='P')
		{
			$masterq=1;
		}else
		{
			$masterq=$product_result["master_quantity"];
		}
            $rep_array=array(	"PRD_QTY"=>HEADING_QUANTITY . ':' . $product_result["products_quantity"],
			"MAS_QTY"=>'Master '.HEADING_QUANTITY . ':' . $masterq,
			"PRD_W"=>HEADING_WIDTH . ':' . $product_result["products_w"],
			"PRD_H"=>HEADING_HEIGHT . ':' . $product_result["products_h"],
			"PRD_R"=>HEADING_ROTATE . ':' . $product_result["products_r"],
			"PRD_SX"=>HEADING_SCALE_X . ':' . $product_result["products_sx"],
			"PRD_SY"=>HEADING_SCALE_Y . ':' . $product_result["products_sy"],
			"PRD_X"=>HEADING_X . ':' . $product_result["products_x"],
			"PRD_Y"=>HEADING_Y . ':' . $product_result["products_y"],
			"PRD_PRICE"=>HEADING_PRICE . ':' . $currencies->format(tep_get_rounded_amount(tep_add_tax($product_result["products_price"],tep_get_tax_rate($product_result["products_tax_class_id"])))),
			"PRD_DATE_ADDED"=>$date_added,
			"PRD_DATE_MODIFIED"=>$date_modified,
			"PRD_EXP"=>'Product Expiry: ' .$product_result["products_date_available"],
			"PRD_DESCRIPTION"=>tep_db_input($product_result["products_description"]),
			"PRD_ID"=>$product_result["products_id"],
			"PRD_IMAGE_WIDTH"=>SMALL_IMAGE_WIDTH,
			"PRD_IMAGE"=>tep_product_small_image($product_result["products_image_1"],$product_result["products_title_1"])
            );
            echo mergeTemplate($rep_array,$template);
            $jsData->VARS["updateMenu"]=",normal,";
        } else {
            echo 'Err:' . TEXT_PRODUCT_NOT_FOUND;
        }
    }
    function doProductUpdate()
	{
        global $FSESSION,$FREQUEST,$LANGUAGES,$jsData,$SERVER_DATE,$SERVER_DATE_TIME;
        $ID=$FREQUEST->postvalue("product_id","int",-1);
        $category_id=$FREQUEST->postvalue("category_id","int",-1);
        $insert=true;
        if ($ID>0) $insert=false;
        $sql_array=array();
        if ($insert)
		{
            if ($category_id>0)
			{			
			 $jsData->VARS['storePage']['opened']['cat']=array("id"=> $category_id ,"get"=>"CatInfoAndProducts","result"=>"doDisplayResult","type"=>"cat","params"=>"cID=$category_id","style"=>"boxLevel1");
            }
            $max_query=tep_db_query("select max(products_sort_order) as sort_order from " . TABLE_PRODUCTS);
            $max_result=tep_db_fetch_array($max_query);
            $sql_array["products_sort_order"]=((int)$max_result["sort_order"])+1;
        }
        $products_status=$sql_array["products_status"]=$FREQUEST->postvalue("products_status","int",0);
		
		$sql_array["products_date_available"]=$FREQUEST->postvalue("products_date_available");
        if ($sql_array["products_date_available"]=='') 
		{
            $sql_array["products_date_available"]='2030-01-01 00:00';//$SERVER_DATE_TIME;
        } else 
		{
            $sql_array["products_date_available"]=tep_convert_datetime_raw($sql_array["products_date_available"]);
        }

       // $sql_array["manufacturers_id"]=$FREQUEST->postvalue('manufacturers_id','int',0);
        $sql_array["restrict_to_groups"]=$FREQUEST->postvalue("restrict_to_groups");
        $sql_array["restrict_to_customers"]=$FREQUEST->postvalue("restrict_to_customers");
		$sql_array["products_season"]=$FREQUEST->postvalue("products_season");
		
        $product_type=$sql_array["product_type"]=$FREQUEST->postvalue('product_type','string','P');
	   
        switch($product_type)
		{
            case 'B':
                $sql_array["author_name"]="n/a";//$FREQUEST->postvalue("author_name");
				$sql_array["products_model"]=$FREQUEST->postvalue("products_model");
				$sql_array["section_id"]=$FREQUEST->postvalue("category_id","int",0);
				$sql_array["parent_id"]=$FREQUEST->postvalue("category_id","int",0);
				$sql_array["color_code"]=$FREQUEST->postvalue("color_code");
				$sql_array["products_sku"]=9;
                break;
			case 'L':
				$sql_array["section_id"]=$FREQUEST->postvalue("category_id","int",0);
				$sql_array["parent_id"]=$FREQUEST->postvalue("category_id","int",0);
				$sql_array["products_status"]=$FREQUEST->postvalue("products_status");
				$sql_array["color_code"]=$FREQUEST->postvalue("color_code");
				$sql_array["products_sku"]=0;
                break;
			case 'Q':
				$sql_array["section_id"]=$FREQUEST->postvalue("category_id","int",0);
				$sql_array["parent_id"]=$FREQUEST->postvalue("category_id","int",0);
				$sql_array["products_status"]=7;//$FREQUEST->postvalue("products_status");
				$sql_array["products_sku"]=0;
                break;
			case 'F':
                $sql_array["section_id"]=$FREQUEST->postvalue("category_id","int",0);
				$sql_array["parent_id"]=$FREQUEST->postvalue("category_id","int",0);
				$sql_array["products_model"]=$FREQUEST->postvalue("products_model");
				$sql_array["color_code"]=$FREQUEST->postvalue("color_code");
				$sql_array["products_sku"]=9;
				break;
			case 'C':
                $sql_array["section_id"]=$FREQUEST->postvalue("category_id","int",0);
				$sql_array["parent_id"]=$FREQUEST->postvalue("category_id","int",0);
				$sql_array["products_model"]=$FREQUEST->postvalue("products_model");
				$sql_array["color_code"]=$FREQUEST->postvalue("color_code");
				$sql_array["products_sku"]=6;
				break;
			case 'G':
				$sql_array["section_id"]=$FREQUEST->postvalue("category_id","int",0);
				$sql_array["parent_id"]=$FREQUEST->postvalue("category_id","int",0);
				$sql_array["products_model"]=$FREQUEST->postvalue("products_model");
				$sql_array["color_code"]=$FREQUEST->postvalue("color_code");
				
				$sql_array["products_sku"]=9;
                break;
            default:
                $sql_array["products_model"]=$FREQUEST->postvalue("products_model");
				$sql_array["color_code"]=$FREQUEST->postvalue("color_code");
				$sql_array["section_id"]=$FREQUEST->postvalue("category_id","int",0);
				$sql_array["parent_id"]=$FREQUEST->postvalue("category_id","int",0);
				$sql_array["products_sku"]=1;

						
            }
            $product_mode=$sql_array["product_mode"]=$FREQUEST->postvalue('product_mode','string','P');
            $sql_array["download_last_date"]==$sql_array["download_link"]='';
            $sql_array["downloads_per_customer"]=0;
			$sql_array["author_name"]="N";

            if ($product_mode=="V")
			{
                $sql_array["download_last_date"]=$FREQUEST->postvalue("download_last_date");
                if ($sql_array["download_last_date"]=='') 
				{
                    $sql_array["download_last_date"]=$SERVER_DATE;
                } else 
				{
                    $sql_array["download_last_date"]=tep_convert_date_raw($sql_array["download_last_date"]);
                }
                $sql_array["downloads_per_customer"]=$FREQUEST->postvalue("downloads_per_customer",int,0);
                $sql_array["download_link"]=$FREQUEST->postvalue("download_link");
				$sql_array["download_no_of_days"]=DOWNLOAD_MAX_DAYS;
                $sql_array["products_weight"]=0;
            } else 
			{
                $sql_array["products_weight"]=$FREQUEST->postvalue("products_weight",'float',0);
            }
            $sql_array["products_tax_class_id"]=$FREQUEST->postvalue("products_tax_class_id",'int',0);
            $sql_array["products_price"]=$FREQUEST->postvalue("products_price",'float',0);
            $sql_array["products_quantity"]=$FREQUEST->postvalue("products_quantity",'int',0);
			$sql_array["master_quantity"]=$FREQUEST->postvalue("master_quantity",'int',0);
           // $sql_array["products_sku"]=$FREQUEST->postvalue("products_sku");
			$sql_array["products_image_1"]=$FREQUEST->postvalue("products_image_1");
            $sql_array["products_title_1"]=$FREQUEST->postvalue("products_title_1");
			$sql_array["products_w"]=$FREQUEST->postvalue("products_w",'int',44);
			$sql_array["products_h"]=$FREQUEST->postvalue("products_h",'int',36);
			$sql_array["products_r"]=$FREQUEST->postvalue("products_r",'int',0);
			$sql_array["products_sx"]=$FREQUEST->postvalue("products_sx",'decimal',0);
			$sql_array["products_sy"]=$FREQUEST->postvalue("products_sy",'decimal',0);
			$sql_array["products_x"]=$FREQUEST->postvalue("products_x",'int',0);
			$sql_array["products_y"]=$FREQUEST->postvalue("products_y",'int',0);
            $sql_array["is_attributes"]=$FREQUEST->postvalue('is_attributes','string','N');
            $sql_array["products_price_break"]=$FREQUEST->postvalue('products_price_break','string','N');
			
			if($sql_array["products_sku"]==0 || $sql_array["product_type"]=='Q' || $sql_array["product_type"]=='L'){
				$sql_array["products_quantity"]=0;
				$sql_array["products_price"]=0;
				$sql_array["master_quantity"]=0;
			}
			
			//season_ticket
			if(strstr( $sql_array["products_season"], 'SEASON_'))
			{
				$sql_array["product_type"] = 'X';
				$sql_array["section_id"]=$FREQUEST->postvalue("category_id","int",0);
				$sql_array["parent_id"]=$FREQUEST->postvalue("category_id","int",0);
			}

            if ($insert)
			{
                $sql_array["products_date_added"]=$SERVER_DATE_TIME;
                tep_db_perform(TABLE_PRODUCTS,$sql_array);
                $ID=tep_db_insert_id();
            } else 
			{
                $sql_array["products_last_modified"]=$SERVER_DATE_TIME;
                tep_db_perform(TABLE_PRODUCTS,$sql_array,"update","products_id=$ID");
            }
			
            for ($icnt=0,$n=count($LANGUAGES);$icnt<$n;$icnt++)
			{
				
                $products_name=$FREQUEST->getRefValue("products_name","POST");
                $products_description=$FREQUEST->getRefValue("products_description","POST");
                $products_url=$FREQUEST->getRefValue("products_url","POST");
				$products_number=$FREQUEST->getRefValue("products_number","POST");
                $lang_id=$LANGUAGES[$icnt]["id"];
                $sql_array=array(	"products_name"=>tep_db_prepare_input($products_name[$lang_id]),
                                    "products_url"=>tep_db_prepare_input($products_url[$lang_id]),
									"products_number"=>tep_db_prepare_input($products_number[$lang_id]),
                                    "products_description"=>tep_db_prepare_input($products_description[$lang_id])
                );
                $desc_insert=true;
                if (!$insert)
				{
                    $check_query=tep_db_query("SELECT products_id from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id=$ID and language_id=$lang_id");
                    if (tep_db_num_rows($check_query)>0) $desc_insert=false;
                }
                if ($desc_insert)
				{
                    $sql_array["products_id"]=$ID;
                    $sql_array["language_id"]=$lang_id;
                    tep_db_perform(TABLE_PRODUCTS_DESCRIPTION,$sql_array);
                } else 
				{
                    tep_db_perform(TABLE_PRODUCTS_DESCRIPTION,$sql_array,"update","products_id=$ID and language_id=$lang_id");
                }

            }
            if (!$insert)
			{
                tep_db_query("delete from " . TABLE_PRODUCTS_PRICE_BREAK . " where products_id=$ID");
            }

            $price_breaks=$FREQUEST->getRefValue("priceBreaks","POST");
            for ($icnt=0,$n=count($price_breaks);$icnt<$n;$icnt++){
                $quan=tep_db_prepare_input($price_breaks[$icnt]["quan"]);
                $price=tep_db_prepare_input($price_breaks[$icnt]["price"]);
                $sql="INSERT into " .TABLE_PRODUCTS_PRICE_BREAK . "(products_id,quantity,discount_per_item) VALUES($ID,$quan,$price)";
                tep_db_query($sql);
            }

            $categories=$FREQUEST->getRefValue("categories_ids","POST");
            if (count($categories)>0)
			{
                if (!$insert) 
				{
                    tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id=$ID");
                }
                for ($icnt=0,$n=count($categories);$icnt<$n;$icnt++)
				{
                    $cat_id=tep_db_prepare_input($categories[$icnt]);
                    $sql="INSERT into " .TABLE_PRODUCTS_TO_CATEGORIES . "(products_id,categories_id,section_id) VALUES($ID,$cat_id,$cat_id)";
                    tep_db_query($sql);
                }
            }

            if ($insert) 
			{
                $this->doCategoryProducts($category_id);
            } else 
			{
                if ($products_status==1)
				{
                    $result='<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $ID .",get:\'ProductChangeStatus\',result:doSimpleResult,params:\'pID=". $ID . "&status=0\'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_active.gif') . '</a>';
                } else 
				{
                    $result='<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $ID .",get:\'ProductChangeStatus\',result:doSimpleResult,params:\'pID=". $ID . "&status=1\'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_inactive.gif') . '</a>';
                }
                $jsData->VARS["replace"]=array("prd" . $ID . "title"=>$products_name[$FSESSION->languages_id],"prd". $ID ."bullet"=>$result);
                $jsData->VARS["prevAction"]=array('id'=>$ID,'get'=>'ProductInfo','type'=>'prd','style'=>'boxRow');
                $this->doProductInfo($ID);
                $jsData->VARS["updateMenu"]=",normal,";
            }
        }
        function doCategories($category_id=0)
		{
            global $jsData;
            if ($category_id>0)
			{
                $jsData->VARS["doFunc"]=array('type'=>'display','data'=>'{"id":' . $category_id . ',"get":"catInfoAndProducts","result":doDisplayResult,"type":"cat","params":"cID=' . $category_id . '","style":"boxLevel1"}');
            }
            ?>

            <table border="0" cellpadding="2" cellspacing="0" width="100%" id="catTable">
                <?php
                $template=getCategoriesListTemplate();
                $rep_array=array(	"PAD_LEFT"=>0,
									"CAT_ID"=>-1,
									"CAT_NAME"=>TEXT_NEW_CATEGORY,
									"CAT_DATE"=>'',
									"CAT_TIME"=>'',
									"CAT_DATE_ID"=>'',
									"CAT_CATEGORIES_QUANTITY_REMAINING"=>'',
									"CAT_VENUE"=>'',
									"BULLET_IMAGE"=>tep_image(DIR_WS_IMAGES . 'template/plus_add1.gif'),
									"IMAGE_PATH"=>DIR_WS_IMAGES,
									"UPDATE_RESULT"=>'doTotalResult',
									"ROW_CLICK_GET"=>'CategoryEdit',
									"FIRST_MENU_DISPLAY"=>'display:none'
                );
                echo mergeTemplate($rep_array,$template);
                $this->doCategoriesList();
                ?>
            </table>

			<?php
			if (!isset($jsData->VARS["page"]))
			{
				$jsData->VARS["NUclearType"]=array("prd","cat");
			}
		}
		
		function doCatInfoAndProducts($category_id=0)
		{
			global $FREQUEST,$FSESSION;
			if ($category_id==0) $category_id=$FREQUEST->getvalue("cID","int",0);
			?>
			<table border="0" cellpadding="1" cellspacing="0" width="100%">
			<tr>
				<td valign="top" style="border-top:solid 1px #C6CEEA;height:5px" class="smallText">&nbsp;</td>
			</tr>
			<tr>
				<td valign="top" class="categoryInfo">
					<?php
					echo $this->doCategoryInfo($category_id);
					?>
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr height="20">
							<td valign="top">
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td class="bulletTitle" valign="middle">
									<?php echo tep_image(DIR_WS_IMAGES . 'layout/bullet1.gif','','','','align=absmiddle') . '&nbsp;' . TITLE_PRODUCTS;?>
								</td>
								<td class="main" width="100">
									<?php
									if ($this->pagination) 
									{
										for ($icnt=MAX_DISPLAY_SEARCH_RESULTS,$n=MAX_DISPLAY_SEARCH_RESULTS*5;$icnt<=$n;$icnt+=MAX_DISPLAY_SEARCH_RESULTS)
										{
											$pg_rows[]=array('id'=>$icnt,'text'=>$icnt);
										}
										$pg_rows[]=array('id'=>-1,'text'=>TEXT_ALL);
										echo TEXT_SHOW . tep_draw_pull_down_menu('totalRows',$pg_rows,$FSESSION->displayRowsCnt,'onChange="javascript:doPageAction({id:'. $category_id . ',type:\'prd\',get:\'CategoryProducts\',closePrev:true,pageNav:true,result:doTotalResult,params:\'cID='. $category_id .'&rowsCnt=\'+this.value,message:page.template[\'INFO_LOADING_PRODUCTS\']});"');
									}
									?>
								</td>
								<td width="20"></td>
							</tr>
						</table>
							</td>
						</tr>
						<tr>
							<td style="padding-right:10px">
							<table border="0" cellpadding="0" cellspacing="0" width="100%" class="boxLevel2">
								<tr height="4">
									<td class="topleft"></td>
									<td></td>
									<td class="topright"></td>
								</tr>
								<tr>
									<td width="4"></td>
									<td style="padding:5px">
										<div id="prdtotalContentResult">
											<?php
											echo $this->doCategoryProducts($category_id);
											?>
										</div>
									</td>
									<td width="4"></td>
								</tr>
								<tr height="4">
									<td class="botleft"></td>
									<td></td>
									<td class="botright"></td>
								</tr>
							</table>
							<td>
						</tr>
						<tr height="10">
							<td></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<?php
		}
		function doSearchProducts()
		{
		global $FREQUEST,$jsData;
		$search=$FREQUEST->getvalue('search');
		$search_db=tep_db_input($search);
		?>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td class="main">
					<b><?php echo TEXT_SEARCH_RESULTS;?></b>
				</td>
			</tr>
			<tr height="10">
				<td class="main">
				</td>
			</tr>
			<tr>
				<td>
					<table border="0" cellpadding="2" cellspacing="0" width="100%" id="catTable">
						<?php
						$found=$this->doCategoriesList(0,1," (cd.categories_name like'%$search_db%' ",$search);
						if (!$found)
						{
							?>
						<tr>
							<td class="main">
								<?php echo TEXT_NO_RECORDS_FOUND;?>
							</td>
						</tr>
						<?php
						}
					?>
					</table>
				</td>
			</tr>
			<tr height="10">
				<td class="main">
				</td>
			</tr>
			<tr>
				<td class="main">
					<a href="javascript:void(0);" onClick="javascript:doProductSearch('reset');"><?php echo tep_image_button('button_reset.gif',IMAGE_RESET);?></a>
				</td>
			</tr>
		</table>
		<?php
		$jsData->VARS["NUclearType"]=array("prd","cat");
		}
function doCategoryProducts($category_id=0)
{
global $FREQUEST,$jsData;
if ($category_id==0)
$category_id=$FREQUEST->getValue('cID','int',0);
	if ($FREQUEST->getvalue('new')=='new_products')
	{
	$jsData->VARS["doFunc"]=array('type'=>'display','data'=>'{"id":-1,"get":"ProductEdit","result":doDisplayResult,"type":"prd","params":"cID=' . $category_id . '","style":"boxRow"}');
	}
             
	$template=getProductsListTemplate();
	$rep_array=array(	"PRD_ID"=>-1,
						"CAT_ID"=>$category_id,
						"CAT_PARENT"=>0,
						"SECTION_ID"=>$section_id,
						"TYPE"=>"prd",
						"ID"=>-1,
						"PRD_NAME"=>TEXT_NEW_PRODUCT,
						"IMAGE_PATH"=>DIR_WS_IMAGES,
						"SEARCH_NEEDED"=>"display:normal",
						"UPDATING_ORDER"=>TEXT_UPDATING_ORDER,
						"PRD_STATUS"=>tep_image(DIR_WS_IMAGES . 'template/plus_add2.gif'),
						"UPDATE_RESULT"=>'doTotalResult',
						"ROW_CLICK_GET"=>'ProductEdit',
						"FIRST_MENU_DISPLAY"=>"display:none",
						"EDIT_MENU_DISPLAY"=>"display:none",
						"FLAG_ONE_RECORD"=>''
	);
	?>
	<div class="main" id="prd-1message"></div>
	<table border="0" width="100%" height="100%" id="prdTable">
		<?php 	echo mergeTemplate($rep_array,$template);
		$this->doProductsList(" and ptc.categories_id=$category_id",$category_id);
		?>
	</table>
	<?php
	if ($this->splitResult && $this->splitResult->queryRows>0)
	{ 
	?>
	<table border="0" width="100%" height="100%">
		<?php echo $this->splitResult->pgLinksCombo(); ?>
	</table><?php 
	}
}

    function doCategoryDeleteImg()
	{
        global $FREQUEST,$jsData;
		$category_id=$FREQUEST->getvalue('cID','int',0);
		$last_flag=$FREQUEST->getvalue('lflag','int',0);
		    tep_db_query("update " . TABLE_CATEGORIES . " set categories_image = '' where categories_id = ".$category_id."");

			 $jsData->VARS["deleteRowImg"]=array("id"=>$category_id,"type"=>"cat");
    }

	function doDeleteImg()
	{
	////////////////////
	 global $FREQUEST,$jsData;
	global $FREQUEST,$FSESSION;
	$category_id=$FREQUEST->getvalue('cID','int',0);
	$last_flag=$FREQUEST->getvalue('lflag','int',0);


	$delete_message='';
	{

		$delete_message=TEXT_DELETE_CONFIRM_INFO_IMAGE . '<p>' . '<a href="javascript:void(0);" onClick="javascript:doSimpleAction({id:' . $category_id .",type:'cat',get:'CategoryDeleteImg',result:doSimpleResult,message:'" . tep_output_string(TEXT_DELETING_CATEGORY_IMAGE) . "',params:'cID=" . $category_id . "&lflag=" . $last_flag . "'})\">" . tep_image_button_delete('button_delete.gif') . '</a>&nbsp;';
		
		$delete_message.='<a href="javascript:void(0);" onClick="javascript:doCancelAction({id:' . $category_id .",type:'cat',get:'closeRow','style':'boxLevel1'})\">" . tep_image_button_cancel('button_cancel.gif') . '</a>';
	}
	?>
	<table border="0" cellpadding="2" cellspacing="0" align="center" width="100%">
		<tr>
			<td class="main" align="center" id="cat<?php echo $category_id;?>message">
				<?php echo $delete_message;?>
				<hr />
			</td>
		</tr>

		<tr>
			<td valign="top" class="categoryInfo"><?php echo $this->doCategoryInfo($category_id);?></td>
		</tr>
	</table>
	<?php
	/////////////////////
	}

    function doCategoryDeleteImg2()
	{
        global $FREQUEST,$jsData;
		$category_id=$FREQUEST->getvalue('cID','int',0);
		$last_flag=$FREQUEST->getvalue('lflag','int',0);
		    tep_db_query("update " . TABLE_CATEGORIES . " set categories_image_2 = '' where categories_id = ".$category_id."");
			 $jsData->VARS["deleteRowImg2"]=array("id"=>$category_id,"type"=>"cat");
    }
	function doDeleteImg2()
	{
	////////////////////
	global $FREQUEST,$jsData;
	global $FREQUEST,$FSESSION;
	$category_id=$FREQUEST->getvalue('cID','int',0);
	$last_flag=$FREQUEST->getvalue('lflag','int',0);


	$delete_message='';
	{

    $delete_message=TEXT_DELETE_CONFIRM_INFO_IMAGE . '<p>' . '<a href="javascript:void(0);" onClick="javascript:doSimpleAction({id:' . $category_id .",type:'cat',get:'CategoryDeleteImg2',result:doSimpleResult,message:'" . tep_output_string(TEXT_DELETING_CATEGORY_IMAGE_2) . "',params:'cID=" . $category_id . "&lflag=" . $last_flag . "'})\">" . tep_image_button_delete('button_delete.gif') . '</a>&nbsp;';
    
    $delete_message.='<a href="javascript:void(0);" onClick="javascript:doCancelAction({id:' . $category_id .",type:'cat',get:'closeRow','style':'boxLevel1'})\">" . tep_image_button_cancel('button_cancel.gif') . '</a>';
	}
	?>
	<table border="0" cellpadding="2" cellspacing="0" align="center" width="100%">
		<tr>
			<td class="main" align="center" id="cat<?php echo $category_id;?>message">
				<?php echo $delete_message;?>
				<hr />
			</td>
		</tr>

		<tr>
			<td valign="top" class="categoryInfo"><?php echo $this->doCategoryInfo($category_id);?></td>
		</tr>
	</table>
	<?php

	/////////////////////
	}
	
	function doCategoryDeleteImg3()
	{
        global $FREQUEST,$jsData;
		$category_id=$FREQUEST->getvalue('cID','int',0);
		$last_flag=$FREQUEST->getvalue('lflag','int',0);
		    tep_db_query("update " . TABLE_CATEGORIES . " set categories_image_2 = '' where categories_id = ".$category_id."");
			 $jsData->VARS["deleteRowImg2"]=array("id"=>$category_id,"type"=>"cat");
    }
	function doDeleteImg3()
	{
	////////////////////
	global $FREQUEST,$jsData;
	global $FREQUEST,$FSESSION;
	$category_id=$FREQUEST->getvalue('cID','int',0);
	$last_flag=$FREQUEST->getvalue('lflag','int',0);


	$delete_message='';
	{

    $delete_message=TEXT_DELETE_CONFIRM_INFO_IMAGE . '<p>' . '<a href="javascript:void(0);" onClick="javascript:doSimpleAction({id:' . $category_id .",type:'cat',get:'CategoryDeleteImg3',result:doSimpleResult,message:'" . tep_output_string(TEXT_DELETING_CATEGORY_IMAGE_3) . "',params:'cID=" . $category_id . "&lflag=" . $last_flag . "'})\">" . tep_image_button_delete('button_delete.gif') . '</a>&nbsp;';
    
    $delete_message.='<a href="javascript:void(0);" onClick="javascript:doCancelAction({id:' . $category_id .",type:'cat',get:'closeRow','style':'boxLevel1'})\">" . tep_image_button_cancel('button_cancel.gif') . '</a>';
	}
	?>
	<table border="0" cellpadding="2" cellspacing="0" align="center" width="100%">
		<tr>
			<td class="main" align="center" id="cat<?php echo $category_id;?>message">
				<?php echo $delete_message;?>
				<hr />
			</td>
		</tr>

		<tr>
			<td valign="top" class="categoryInfo"><?php echo $this->doCategoryInfo($category_id);?></td>
		</tr>
	</table>
	<?php

	/////////////////////
	}
	
	
	function doCategoryDeleteImg4()
	{
        global $FREQUEST,$jsData;
		$category_id=$FREQUEST->getvalue('cID','int',0);
		$last_flag=$FREQUEST->getvalue('lflag','int',0);
		    tep_db_query("update " . TABLE_CATEGORIES . " set categories_image_4 = '' where categories_id = ".$category_id."");
			 $jsData->VARS["deleteRowImg2"]=array("id"=>$category_id,"type"=>"cat");
    }
	function doDeleteImg4()
	{
	////////////////////
	global $FREQUEST,$jsData;
	global $FREQUEST,$FSESSION;
	$category_id=$FREQUEST->getvalue('cID','int',0);
	$last_flag=$FREQUEST->getvalue('lflag','int',0);


	$delete_message='';
	{

    $delete_message=TEXT_DELETE_CONFIRM_INFO_IMAGE . '<p>' . '<a href="javascript:void(0);" onClick="javascript:doSimpleAction({id:' . $category_id .",type:'cat',get:'CategoryDeleteImg4',result:doSimpleResult,message:'" . tep_output_string(TEXT_DELETING_CATEGORY_IMAGE_4) . "',params:'cID=" . $category_id . "&lflag=" . $last_flag . "'})\">" . tep_image_button_delete('button_delete.gif') . '</a>&nbsp;';
    
    $delete_message.='<a href="javascript:void(0);" onClick="javascript:doCancelAction({id:' . $category_id .",type:'cat',get:'closeRow','style':'boxLevel1'})\">" . tep_image_button_cancel('button_cancel.gif') . '</a>';
	}
	?>
	<table border="0" cellpadding="2" cellspacing="0" align="center" width="100%">
		<tr>
			<td class="main" align="center" id="cat<?php echo $category_id;?>message">
				<?php echo $delete_message;?>
				<hr />
			</td>
		</tr>

		<tr>
			<td valign="top" class="categoryInfo"><?php echo $this->doCategoryInfo($category_id);?></td>
		</tr>
	</table>
	<?php

	/////////////////////
	}
function doCategoryEdit()
{
	global $FSESSION, $FREQUEST,$LANGUAGES,$CAT_TREE,$jsData;
	$languages=&$LANGUAGES;
	$category_id=$FREQUEST->getvalue('cID','int',0);
	$mode=$FREQUEST->getvalue('cID','string','new');
	if ($category_id<=0) $mode="new";

	$jsData->VARS["template"]=array("ERR_CAT_SELECT_PARENT"=>ERR_CATEGORY_SELECT_PARENT,
									"ERR_CAT_NAME_EMPTY"=>ERR_CATEGORY_NAME_EMPTY,
									"ERR_CAT_TITLE_EMPTY"=>ERR_CATEGORY_TITLE_EMPTY
			);

	if ($category_id>0)
	{
		$category_query = tep_db_query("select c.manufacturers_id, c.parent_id,c.categories_id, cd.categories_name,cd.categories_heading_title,c.plan_id,c.categories_is_printable, c.categories_shipping,cd.categories_description,c.color_code,c.bg_height, c.categories_image,c.categories_image_2,c.categories_image_3,c.categories_image_4,concert_venue,concert_date,concert_time, c.categories_GA, c.categories_quantity, c.categories_quantity_remaining, language_id from  ".TABLE_CATEGORIES."  c,  ".TABLE_CATEGORIES_DESCRIPTION."  cd where c.categories_id = cd.categories_id and c.categories_id='".(int)$category_id."' order by cd.language_id");
		$catInfo=array();
		$langInfo=array();
		while($cat_result=tep_db_fetch_array($category_query))
		{
			$langInfo[$cat_result["language_id"]]=array(
			"name"=>tep_db_prepare_input($cat_result["categories_name"]),
			"title"=>tep_db_prepare_input($cat_result["categories_heading_title"]),
			"venue"=>tep_db_prepare_input($cat_result["concert_venue"]),
			"date"=>tep_db_prepare_input($cat_result["concert_date"]),
			"plan_id"=>tep_db_prepare_input($cat_result["plan_id"]),
			"bg_height"=>tep_db_prepare_input($cat_result["bg_height"]),
			"color_code" => tep_db_prepare_input($cat_result["color_code"]),
			"description"=>tep_db_prepare_input($cat_result["categories_description"]));
			if (count($catInfo)==0) 
			{
				$catInfo=$cat_result;
				unset($catInfo["categories_name"]);
				unset($catInfo["categories_heading_title"]);
				unset($catInfo["categories_description"]);
				unset($catInfo["concert_venue"]);
				unset($catInfo["concert_date"]);
				unset($catInfo["color_code"]);
				unset($catInfo["plan_id"]);
				unset($catInfo["bg_height"]);
			}
				$cat_man_id=$cat_result["manufacturers_id"];
				//$cat_plan_id=$cat_result["plan_id"];
				//$cat_bg_height=$cat_result["bg_height"];
				//$date_time=$cat_result["concert_date"] . ' ' . $cat_result["concert_time"];
		}
	}
	?>
	<form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="catSubmit" id="catSubmit">
    <table border="0" cellpadding="4" cellspacing="0" width="100%" class="categoryEdit" style="padding-left:20px">
    <?php if ($category_id<=0)
	{ 
	?>
    <tr>
        <td>
            <table border="0" cellpadding="2" cellspacing="0" width="100%">
                <tr>
                    <td class="main" width="120" valign="top"><?php echo TEXT_PARENT_CATEGORY;?></td>
                    <td class="main">
                        <?php
                        if (count($CAT_TREE)<=0) $CAT_TREE=tep_get_category_tree();
                        echo tep_draw_pull_down_menu2("parent_id",$CAT_TREE,0,'size=10');
                        ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <?php 
	} 
	?>
    <tr>
        <td width="50%" valign="top">
            <table border="0" cellpadding="2" cellspacing="0" width="100%">
                <?php
                for ($icnt=0,$n=count($languages); $icnt<$n; $icnt++) 
				{
                ?>
                <tr>
                    <td class="main" valign="top" width="160"><?php if ($icnt == 0) echo TEXT_EDIT_CATEGORIES_NAME ; ?></td>
                    <td class="main" valign="top"><?php  echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$icnt]['directory'] . '/images/' . $languages[$icnt]['image'], $languages[$icnt]['name']) . '&nbsp;' . tep_draw_input_field('categories_name[' . $languages[$icnt]['id'] . ']', isset($langInfo[$languages[$icnt]['id']]["name"])?stripslashes($langInfo[$languages[$icnt]['id']]["name"]):'','maxlength="128"'); ?></td>
                </tr>
                <?php 
				} 
				?>
            </table>

            <table border="0" cellpadding="2" cellspacing="0" width="100%" bgcolor="">
                <?php
                for ($icnt=0,$n=count($languages); $icnt<$n; $icnt++) 
				{
                ?>
                <tr>
                    <td class="main" valign="top" width="160"><?php if ($icnt == 0) echo TEXT_EDIT_CATEGORIES_VENUE ; ?></td>
                    <td class="main" valign="top"><?php  echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$icnt]['directory'] . '/images/' . $languages[$icnt]['image'], $languages[$icnt]['name']) . '&nbsp;' . tep_draw_input_field('concert_venue[' . $languages[$icnt]['id'] . ']', isset($langInfo[$languages[$icnt]['id']]["venue"])?stripslashes($langInfo[$languages[$icnt]['id']]["venue"]):'','maxlength="128"'); ?></td>
                </tr>
                <?php 
				} 
				?>
            </table>
            <table border="0" cellpadding="2" cellspacing="0" width="100%">
                <?php

		for ($icnt=0,$n=count($languages); $icnt<$n; $icnt++) 
		{
			?>
		<tr>
			<td class="main" valign="top" width="160"><?php if ($icnt == 0) echo TEXT_EDIT_CATEGORIES_DATE ; ?></td>
			<td class="main" valign="top">
			<?php  echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$icnt]['directory'] . '/images/' . $languages[$icnt]['image'], $languages[$icnt]['name']) . '&nbsp;' . tep_draw_input_field('concert_date[' . $languages[$icnt]['id'] . ']', isset($langInfo[$languages[$icnt]['id']]["date"])?stripslashes($langInfo[$languages[$icnt]['id']]["date"]):'','disabled maxlength="64"'); ?></td>
		</tr>
		<?php 
		} 
		?>
	</table><br>
            <table border="0" cellpadding="2" cellspacing="0" width="100%">

				<?php				
				//printable 
						//check if new category and make default to printable
						if($_GET['cID'] == -1)
						{
						$catInfo["categories_is_printable"] = 1;
						}
					  ?>
                 <tr>
                    <td class="main"  valign="top" width="160"><?php echo TEXT_ALL_TICKETS_PRINTABLE; ?></td>
                    <td class="main" valign="top"><?php  echo '&nbsp;' . tep_draw_checkbox_field('category_printable', 1, ($catInfo["categories_is_printable"] == 1?true:false),0,'onClick="javascript:toggle_visibility(\'show_me\');toggle_visibility(\'hide_me\');"'); ?>
                    <?php // a little display to jog memory
						if($catInfo["categories_is_printable"] == 1)
						{
						    $show_me = TICKETS_MAY_BE_PRINTED;
							$hide_me = NO_TICKETS_MAY_BE_PRINTED;
						}else{
						    $hide_me = TICKETS_MAY_BE_PRINTED;
							$show_me =  NO_TICKETS_MAY_BE_PRINTED;
						}		?>					   
                    <span id="show_me"><?php echo $show_me;?></span>
					<span id="hide_me" style = "display:none"><?php echo $hide_me;?></span>
                    </td>
                </tr>
				<tr>
                    <td class="main"  valign="top" width="160"><?php echo TEXT_ALL_TICKETS_SHIPPING; ?></td>
                    <td class="main" valign="top"><?php  echo '&nbsp;' . tep_draw_checkbox_field('categories_shipping', 1, ($catInfo["categories_shipping"] == 1?true:false),'','onClick="javascript:toggle_visibility(\'show_me2\');toggle_visibility(\'hide_me2\');"'); ?>
                    <?php // a little display to jog memory
						if($catInfo["categories_shipping"] == 1)
						{
						    $show_me2 = SHIPPING;
							$hide_me2 = NO_SHIPPING;
						}else{
						    $hide_me2 = SHIPPING;
							$show_me2 =  NO_SHIPPING;
						}		
						?>					   
                    <span id="show_me2"><?php echo $show_me2;?></span><span id="hide_me2" style = "display:none"><?php echo $hide_me2;?></span>
                    </td>
                </tr>
				
            </table>

        <td valign="top">
            <table border="0" cellspacing="3" cellpadding="3" width="100%">
                <?php  for ($icnt=0,$n=count($languages); $icnt<$n; $icnt++) 
				{ ?>
                <tr>
                    <td class="main" valign="top" width="150">
					<?php if ($icnt == 0) echo TEXT_EDIT_CATEGORIES_HEADING_TITLE; ?></td>
                    <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$icnt]['directory'] . '/images/' . $languages[$icnt]['image'], $languages[$icnt]['name']) . '&nbsp;' . tep_draw_input_field('categories_heading_title[' . $languages[$icnt]['id'] . ']',  isset($langInfo[$languages[$icnt]['id']]["title"])?stripslashes($langInfo[$languages[$icnt]['id']]["title"]):''); ?></td>
                </tr>
                <?php } ?>
				<?php 
		if ($category_id>0)
		{
				
				for ($icnt=0,$n=count($languages); $icnt<$n; $icnt++) 
				{ 
		
			?>
				    <tr <?php echo $the_style;?>>
                    <td class="main"  valign="top" width="150">
					<?php echo TEXT_EDIT_CATEGORIES_ROW_COLOUR; ?> 
					<?php //echo TEXT_CATEGORY_ROWS_ONLY; ?></td>
                    <td class="main" valign="top">
					<?php 
				
					$please_select=TEXT_SELECT_COLOR;
			
					$colours_available=array();
					$colours_available[]=array('id'=>'xxx','text'=>$please_select);
					$colours_available[]=array('id'=>'blue','text'=>'Blue');
					$colours_available[]=array('id'=>'fuchsia','text'=>'Fuchsia');
					$colours_available[]=array('id'=>'green','text'=>'Green');
					$colours_available[]=array('id'=>'orange','text'=>'Orange');
					$colours_available[]=array('id'=>'palegreen','text'=>'Pale Green');
					$colours_available[]=array('id'=>'green','text'=>'Green');
					$colours_available[]=array('id'=>'red','text'=>'Red');
					$colours_available[]=array('id'=>'salmon','text'=>'Salmon');
					$colours_available[]=array('id'=>'skyblue','text'=>'Sky Blue');
					$colours_available[]=array('id'=>'teal','text'=>'Teal');
					$colours_available[]=array('id'=>'thistle','text'=>'Thistle');
					$colours_available[]=array('id'=>'yellow','text'=>'Yellow');
					
				    echo tep_draw_pull_down_menu('categories_row_colour',$colours_available, isset($langInfo[$languages[$icnt]['id']]["color_code"])?stripslashes($langInfo[$languages[$icnt]['id']]["color_code"]):'xxx','size="1" ',''); 
					?>
				
					</td>
                </tr>
				<?php  
				}
				?>
				<?php //end row colour?>
                <tr>
                <td class="main" valign="top" width="150"><?php echo TEXT_CAT_ID; ?></td>
                <td class="main" valign="top"><?php echo $category_id; ?> <?php
				//One day we will have a way to put all products out of stock in this category
				//echo '<div class="title">' . TEXT_PRODUCTS_STATUS . '</div>';
			   // echo tep_draw_radio_field('products_status', '1', $in_status) . '&nbsp;' . TEXT_PRODUCT_AVAILABLE . '&nbsp;' . tep_draw_radio_field('products_status', '0', $out_status) . '&nbsp;' . TEXT_PRODUCT_NOT_AVAILABLE;
				?></td>
				</tr>
                 <tr>
                    <td class="main"  valign="top" width="130"><?php echo TEXT_PLAN; ?></td>
					<td>
                <?php 
  
				for ($icnt=0,$n=count($languages); $icnt<$n; $icnt++) 
				{ 
					$please_select=TEXT_SELECT_PLAN;
					$the_plan_id=array();
					$the_plan_id[]=array('id'=>'xxx','text'=>$please_select);
					$the_plan_id[]=array('id'=>'5','text'=>DESIGN_MODE5);
					$the_plan_id[]=array('id'=>'6','text'=>DESIGN_MODE6);
					$the_plan_id[]=array('id'=>'7','text'=>DESIGN_MODE7);
					$the_plan_id[]=array('id'=>'8','text'=>DESIGN_MODE8);
					//$the_plan_id[]=array('id'=>'4','text'=>'Seat Plan Top(4)');
					$the_plan_id[]=array('id'=>'3','text'=>NORMAL_SEATPLAN_MODE.'(3)');
					$the_plan_id[]=array('id'=>'2','text'=>SEATPLAN_SUB.'(2)');
					$the_plan_id[]=array('id'=>'9','text'=>GA_MODE.'(9)');
					
					echo tep_draw_pull_down_menu('categories_plan_id',$the_plan_id, isset($langInfo[$languages[$icnt]['id']]["plan_id"])?stripslashes($langInfo[$languages[$icnt]['id']]["plan_id"]):'xxx','size="1" ','');
				}	
					//echo tep_draw_pull_down_menu('categories_plan_id', $the_plan_id, '','size="1" ','');
				?>
                </td>
                </tr>  
            </table>
			<table border="0" cellpadding="2" cellspacing="0" width="100%">
			<tr>
			<td class="main" valign="top" width="160"><?php echo TEXT_EDIT_BG_HEIGHT ; ?></td>
			<td class="main" valign="top">
				<?php 
					
				for ($icnt=0,$n=count($languages); $icnt<$n; $icnt++) 
				{ 
					$please_select=TEXT_SELECT_BG;
					$cat_bg_height=array();
					$cat_bg_height[]=array('id'=>'xxx','text'=>$please_select);
					$cat_bg_height[]=array('id'=>'300','text'=>300);
					$cat_bg_height[]=array('id'=>'400','text'=>400);
					$cat_bg_height[]=array('id'=>'500','text'=>500);
					$cat_bg_height[]=array('id'=>'600','text'=>600);
					$cat_bg_height[]=array('id'=>'800','text'=>800);
					$cat_bg_height[]=array('id'=>'1000','text'=>1000);
					$cat_bg_height[]=array('id'=>'1200','text'=>1200);
					$cat_bg_height[]=array('id'=>'1500','text'=>1500);
					$cat_bg_height[]=array('id'=>'1800','text'=>1800);
					$cat_bg_height[]=array('id'=>'2000','text'=>2000);
					$cat_bg_height[]=array('id'=>'2200','text'=>2200);
					
					echo tep_draw_pull_down_menu('categories_bg_height',$cat_bg_height, isset($langInfo[$languages[$icnt]['id']]["bg_height"])?stripslashes($langInfo[$languages[$icnt]['id']]["bg_height"]):'xxx','size="1" ',''); 
				}
					//echo tep_draw_pull_down_menu('categories_bg_height', $cat_bg_height, 300,'size="1" ','');
	}
	?>
		</td>
		</tr>
	</table>
        </td>
    </tr>
    <tr>
    <td colspan="2">       
    <!--start-->
    <table border="0" cellpadding="2" cellspacing="0" width="100%">
		<tr>
	 <td colspan="4"  valign="top">
	 <hr />
	 </td>
	 </tr>
		<tr>
		  <td width="50%" valign="top">
			<!--Now starts the GA Master Quantity-->
			<table border="0" cellpadding="2" cellspacing="0" width="100%" style="display:true">
			<?php // GA display?>
			<tr><td colspan="4"  valign="top">
			<?php 
		    //echo $cat_plan_id;
			if($cat_plan_id==9) 
			//if($catInfo['plan_id'] ==2) 
			{
			$the_style=' style="display:true;"';
			}else{
			$the_style=' style="display:none;"';
			}
			?>  
			<!--new table begin-->
            <table border="0" cellpadding="2" cellspacing="0" width="100%" <?php echo $the_style;?>>
		  	<tr class="cat_quan">
			<td class="main" title="top" width="50%"><?php echo TEXT_CAT_GA; ?></td>
			<td nowrap  class="main">
          		  <?php 
					$selected1=$selected2='';
					if($catInfo["categories_GA"]==1)
					{
					$selected1=" selected ";
					}
					//echo $catInfo["categories_GA"];
				?>
			 <select name="is_ga" >
			    <option value="0" name ="" ><?php echo PLEASE_SELECT; ?></option>
			 	<option value="1" name ="GA_only" <?php echo $selected1;?>><?php echo TEXT_GA_ONLY; ?></option>
			 </select>
		     <?php echo TEXT_CAT_GA_TEXT; ?>
		   </td>
		   </tr>
		   <tr <?php echo $the_style;?> id="hidden_2">
			<td class="main" title="top" >
			<?php echo TEXT_CAT_QUANTITY; ?></td>
			<td nowrap class="main">
			<?php echo tep_draw_input_field('init_quan',$catInfo["categories_quantity"],' size="10"');?>
			<?php echo TEXT_CAT_QUANTITY_TEXT; ?>
			</td>
			</tr>
			<tr <?php echo $the_style;?> id="hidden_1">
			<td class="main" title="top">
			<?php echo TEXT_CAT_QUANTITY_REMAINING; ?></td>
			<td nowrap  class="main">
			<?php echo tep_draw_input_field('quan_left',$catInfo["categories_quantity_remaining"],' size="10"');?>
			<?php echo TEXT_CAT_QUANTITY_REMAINING_TEXT; ?>
		   </td>
		   </tr>
		   </table>
		   </td>
			<tr>
			<td colspan="4" valign="top">
			<hr />
			</td>
			</tr>
		   </table>
			</td>
			</tr><?php //end GA Master Quantity ?>
      </table>
        
            <div style="width:100%;height:150px;overflow:auto;border: 0px solid black">
                <table border="0" cellspacing="0" cellpadding="3">
                    <?php  for ($icnt=0,$count=count($languages); $icnt<$n; $icnt++) { ?>
                    <tr>
                        <td class="main" valign="top"><?php if ($icnt == 0) echo TEXT_EDIT_CATEGORIES_DESCRIPTION; ?></td>
                        <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$icnt]['directory'] . '/images/' . $languages[$icnt]['image'], $languages[$icnt]['name'],'','','align="top"'); ?>&nbsp;
                        <?php echo tep_draw_textarea_field('categories_description[' . $languages[$icnt]['id'] . ']', 'soft', '70', '8', isset($langInfo[$languages[$icnt]['id']]['description'])? $langInfo[$languages[$icnt]['id']]['description'] : ''); ?></td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </td>
    </tr>
	</table>
	
	<style type="text/css">
	.tg  {border-collapse:collapse;border-spacing:0;}
	.tg td{border-color:black;border-style:solid;border-width:0px;font-family:Arial, sans-serif;font-size:14px;
	  overflow:hidden;padding:10px 5px;word-break:normal;}
	.tg th{border-color:black;border-style:solid;border-width:0px;font-family:Arial, sans-serif;font-size:14px;
	  font-weight:normal;overflow:hidden;padding:10px 5px;word-break:normal;}
	.tg .tg-fymr{border-color:inherit;font-weight:bold;text-align:left;vertical-align:top}
	.tg .tg-0lax{text-align:left;vertical-align:top}
	</style>
<table class="tg">
<tbody>
  <tr>
  <input type="hidden" id="categories_image" name="categories_image" value="<?php echo tep_output_string($catInfo["categories_image"]);?>"/>
    <input type="hidden" id="category_id" name="category_id" value="<?php echo tep_output_string($category_id);?>"/>
    <td class="tg-0lax">           
	<table border="0" cellpadding="2" cellspacing="0" width="100%">
                <tr>
                    <td class="main" align="left" valign="top"><strong><?php echo TEXT_EDIT_CATEGORIES_IMAGE; ?></strong></td>
                    <td class="main" align="left" valign="top">
						<?php echo tep_display_image($catInfo["categories_image"],$catInfo["categories_name"],SMALL_IMAGE_WIDTH,'','',true);?>
					</td>
				</tr>
				<?php if(tep_not_null($catInfo["categories_image"])){?>
				<tr>
                    <td class="main" align="left" valign="top"><?php echo TEXT_EDIT_CATEGORIES_IMAGE_DELETE; ?></td>
                    <td class="main" align="left" valign="top">
						 <input name="delete_image" type="checkbox" value="yes" />
					</td>
			    </tr>
				<?php }?>
				<tr>
                    <td class="main" align="left" valign="top"><?php echo TEXT_EDIT_CATEGORIES_IMAGE_NEW; ?></td>
                    <td class="main" align="left" valign="top">
					    <?php
                        echo '<div id="categories_image_file_container">';
                        echo tep_draw_file_field('categories_image_file') . '<br>' . tep_draw_separator('pixel_trans.gif','10','1') . '&nbsp;' . $catInfo["categories_image"];
                        echo '</div>';
                        ?>			
					</td>      
              </tr>     
          </table></td>
		  <!--ticket image upload-->
		    <input type="hidden" id="categories_image_2" name="categories_image_2" value="<?php echo tep_output_string($catInfo["categories_image_2"]);?>"/>
			<td class="tg-0lax">           
			<table border="0" cellpadding="2" cellspacing="0" style="display:true">
                <tr>
                    <td class="main" align="left" valign="top"><strong><?php echo TEXT_EDIT_TICKET_IMAGE; ?></strong></td>
                    <td class="main" align="left" valign="top">
						<?php echo tep_display_image($catInfo["categories_image_2"],$catInfo["categories_name"],SMALL_IMAGE_WIDTH,'','',true);?>
					</td>
				</tr>
				<?php if(tep_not_null($catInfo["categories_image_2"])){?>
				<tr>
                    <td class="main" align="left" valign="top"><?php echo TEXT_EDIT_TICKET_IMAGE_DELETE; ?></td>
                    <td class="main" align="left" valign="top">
						 <input name="delete_image2" type="checkbox" value="yes" />
					</td>
			    </tr>
				<?php }?>
				<tr>
                    <td class="main" align="left" valign="top"><?php echo TEXT_EDIT_TICKET_IMAGE_NEW; ?></td>
                    <td class="main" align="left" valign="top">
					    <?php
                        echo '<div id="categories_image_file_container_2">';
                        echo tep_draw_file_field('categories_image_file_2') . '<br>' . tep_draw_separator('pixel_trans.gif','10','1') . '&nbsp;' . $catInfo["categories_image_2"];
                        echo '</div>';
                        ?>			
					</td>
        <!--eof ticket image upload-->
				</tr>
			</table>
			</td>
		  </tr>
		   <tr>
			<td class="tg-0lax"><hr size="1" color="#D2D8E7"/></td>
			<td class="tg-0lax"><hr size="1" color="#D2D8E7"/></td>
		  </tr>
		  <tr>
  		<?php
		if(DESIGN_MODE=='no')
			{
			$hide=" style=\"display:none\"";	
			}
		?>
		 <!--background image upload-->
		    <input type="hidden" id="categories_image_3" name="categories_image_3" value="<?php echo tep_output_string($catInfo["categories_image_3"]);?>"/>
			<input type="hidden" id="category_id" name="category_id" value="<?php echo tep_output_string($category_id);?>"/>
			<td class="tg-0lax">           
			<table border="0" cellpadding="2" cellspacing="0" <?php echo $hide; ?>>
                <tr>
                    <td class="main" align="left" valign="top"><strong><?php echo TEXT_EDIT_CATEGORIES_IMAGE_3; ?></strong></td>
                    <td class="main" align="left" valign="top">
						<?php echo tep_display_image($catInfo["categories_image_3"],$catInfo["categories_name"],SMALL_IMAGE_WIDTH,'','',true);?>
					</td>
				</tr>
				<?php if(tep_not_null($catInfo["categories_image_3"])){?>
				<tr>
                    <td class="main" align="left" valign="top"><?php echo TEXT_EDIT_DESIGN_IMAGE_DELETE; ?></td>
                    <td class="main" align="left" valign="top">
						 <input name="delete_image3" type="checkbox" value="yes" />
					</td>
			    </tr>
				<?php }?>
				<tr>
                    <td class="main" align="left" valign="top"><?php echo TEXT_EDIT_DESIGN_IMAGE_NEW; ?></td>
                    <td class="main" align="left" valign="top">
					    <?php
                        echo '<div id="categories_image_file_container_3">';
                        echo tep_draw_file_field('categories_image_file_3') . '<br>' . tep_draw_separator('pixel_trans.gif','10','1') . '&nbsp;' . $catInfo["categories_image_3"];
                        echo '</div>';
                        ?>			
					</td>      
              </tr>     
          </table>

		  </td>
		  <!--stage image upload-->
		    <input type="hidden" id="categories_image_4" name="categories_image_4" value="<?php echo tep_output_string($catInfo["categories_image_4"]);?>"/>
			<td class="tg-0lax">           
			<table border="0" cellpadding="2" cellspacing="0" <?php echo $hide; ?>>
                <tr>
                    <td class="main" align="left" valign="top"><strong><?php echo TEXT_EDIT_CATEGORIES_IMAGE_4; ?></strong></td>
                    <td class="main" align="left" valign="top">
						<?php echo tep_display_image($catInfo["categories_image_4"],$catInfo["categories_name"],SMALL_IMAGE_WIDTH,'','',true);?>
					</td>
				</tr>
				<?php if(tep_not_null($catInfo["categories_image_4"])){?>
				<tr>
                    <td class="main" align="left" valign="top"><?php echo TEXT_EDIT_STAGE_IMAGE_DELETE; ?></td>
                    <td class="main" align="left" valign="top">
						 <input name="delete_image4" type="checkbox" value="yes" />
					</td>
			    </tr>
				<?php }?>
				<tr>
                    <td class="main" align="left" valign="top"><?php echo TEXT_EDIT_STAGE_IMAGE_NEW; ?></td>
                    <td class="main" align="left" valign="top">
					    <?php
                        echo '<div id="categories_image_file_container_4">';
                        echo tep_draw_file_field('categories_image_file_4') . '<br>' . tep_draw_separator('pixel_trans.gif','10','1') . '&nbsp;' . $catInfo["categories_image_4"];
                        echo '</div>';
                        ?>			
					</td>
        <!--eof stage image upload-->
				</tr>
			</table>
		</form>
		</td>
		</tr>
		</tbody>
		</table>
		
		<table>
		<tr>
		<td class="main" id="cat<?php echo $category_id;?>message"></td>
		</tr>
		</table>
		<?php
		$jsData->VARS["updateMenu"]=",update,";
		}
		function doCategoryPrint()
		{
		global $FREQUEST,$FSESSION;
		$category_id=$FREQUEST->getvalue('cID','int',0);
		$print_message='<p>'.TEXT_CREATE_TICKETS.'</p><br><br><a   href="products_ticket_print.php?cat_print_id=' . $category_id .'"> '. tep_image_button_create('button_create.gif') . '</a>&nbsp;';
		$print_message.='&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onClick="javascript:doCancelAction({id:' . $category_id .",type:'cat',get:'closeRow','style':'boxLevel1'})\">" . tep_image_button_cancel('button_cancel.gif') . '</a>';
		?>
		<table border="0" cellpadding="2" cellspacing="0" align="center" width="100%">
			<tr>
				<td class="main" align="center" id="cat<?php echo $category_id;?>message">
					<?php echo $print_message;?>
				</td>
			</tr>
		</table>
<?php
}

function doCategoryDeleteDisplay()
{
global $FREQUEST,$FSESSION;
$category_id=$FREQUEST->getvalue('cID','int',0);
$last_flag=$FREQUEST->getvalue('lflag','int',0);

$delete_message='';
//if ($category_id>0){
    $category_childs = tep_childs_in_category_count($category_id);
    $category_products =tep_products_in_category_count($category_id);
  //  if ($category_childs>0 || $category_products>0){

   //     if ($category_childs > 0) $delete_message.=sprintf(TEXT_DELETE_WARNING_CHILDS, $category_childs) . '<br>';
   //     if ($category_products > 0) $delete_message.=sprintf(TEXT_DELETE_WARNING_PRODUCTS, $category_products).'<br><br>';
  //  } else {
        $delete_message=TEXT_DELETE_CONFIRM_INFO . '<p>' . '<a href="javascript:void(0);" onClick="javascript:doSimpleAction({id:' . $category_id .",type:'cat',get:'CategoryDelete',result:doSimpleResult,message:'" . tep_output_string(TEXT_DELETING_CATEGORY) . "',params:'cID=" . $category_id . "&lflag=" . $last_flag . "'})\">" . tep_image_button_delete('button_delete.gif') . '</a>&nbsp;';
  //  }
    $delete_message.='<a href="javascript:void(0);" onClick="javascript:doCancelAction({id:' . $category_id .",type:'cat',get:'closeRow','style':'boxLevel1'})\">" . tep_image_button_cancel('button_cancel.gif') . '</a>';
//}
?>
<table border="0" cellpadding="2" cellspacing="0" align="center" width="100%">
    <tr>
        <td class="main" align="center" id="cat<?php echo $category_id;?>message">
            <?php echo $delete_message;?>
        </td>
    </tr>
    <tr>
        <td><hr/></td>
    </tr>
    <tr>
        <td valign="top" class="categoryInfo"><?php echo $this->doCategoryInfo($category_id);?></td>
    </tr>
</table>
<?php
}
function doRowStatusDisplay()
{
global $FREQUEST,$jsData,$ROW_TREE,$FSESSION;
$category_id=$FREQUEST->getvalue('cID','int',0);
if (count($ROW_TREE)<=0) $ROW_TREE=tep_get_row_status_tree('0', '',$category_id);
$category_name=tep_get_category_name($category_id,$FSESSION->languages_id);
?>
<form action="javascript:void(0)" method="post" enctype="application/x-www-form-urlencoded" name="catMoveSubmit" id="catMoveSubmit">
    <input type="hidden" name="category_id" value="<?php echo $category_id; ?>"/>
    <table width="100%"  border="0" cellspacing="5" cellpadding="5" style="padding-left:20px;">
        <tr>
            <td class="main" id="cat<?php echo $category_id;?>message"></td>
        </tr>
        <tr>
            <td class="main"><b><?php echo sprintf(TEXT_ROW_STATUS_INTRO, $category_name);?></b></td>
        </tr>
        <tr>
            <td class="main" align="left"><?php echo sprintf(TEXT_CHANGE, $category_name) . '&nbsp;' . tep_draw_pull_down_menu('new_status_id', $ROW_TREE);?></td>
        </tr>
        <tr>
            <td class="main"><a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $category_id;?>,type:'cat',style:'boxLevel1','get':'RowStatus','result':doTotalResult,uptForm:'catMoveSubmit','imgUpdate':false,message:page.template['CAT_MOVING']});"><?php echo tep_image_button_modify('button_modify.gif');?></a>&nbsp;<a href="javascript:void(0)" onClick="javascript:return doCancelAction({id:<?php echo $category_id;?>,type:'cat','get':'closeRow',style:'boxLevel1'});"><?php echo tep_image_button_cancel('button_cancel.gif');?></a></td>
        </tr>
    </table>
</form>
<?php
}
function doCategoryMoveDisplay()
{
global $FREQUEST,$jsData,$CAT_TREE,$FSESSION;
$category_id=$FREQUEST->getvalue('cID','int',0);
if (count($CAT_TREE)<=0) $CAT_TREE=tep_get_category_tree('0', '',$category_id);
$category_name=tep_get_category_name($category_id,$FSESSION->languages_id);
?>
<form action="javascript:void(0)" method="post" enctype="application/x-www-form-urlencoded" name="catMoveSubmit" id="catMoveSubmit">
    <input type="hidden" name="category_id" value="<?php echo $category_id; ?>"/>
    <table width="100%"  border="0" cellspacing="5" cellpadding="5" style="padding-left:20px;">
        <tr>
            <td class="main" id="cat<?php echo $category_id;?>message"></td>
        </tr>
        <tr>
            <td class="main"><b><?php echo sprintf(TEXT_MOVE_CATEGORIES_INTRO, $category_name);?></b></td>
        </tr>
        <tr>
            <td class="main" align="left"><?php echo sprintf(TEXT_MOVE, $category_name) . '&nbsp;' . tep_draw_pull_down_menu('new_parent_id', $CAT_TREE);?></td>
        </tr>
        <tr>
            <td class="main"><a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $category_id;?>,type:'cat',style:'boxLevel1','get':'CategoryMove','result':doTotalResult,uptForm:'catMoveSubmit','imgUpdate':false,message:page.template['CAT_MOVING']});"><?php echo tep_image_button_move('button_move.gif');?></a>&nbsp;<a href="javascript:void(0)" onClick="javascript:return doCancelAction({id:<?php echo $category_id;?>,type:'cat','get':'closeRow',style:'boxLevel1'});"><?php echo tep_image_button_cancel('button_cancel.gif');?></a></td>
        </tr>
    </table>
</form>
<?php
}
    
function doCategoryCloneDisplay()
{
global $FREQUEST,$jsData,$CAT_TREE,$FSESSION;
$category_id=$FREQUEST->getvalue('cID','int',0);
if (count($CAT_TREE)<=0) $CAT_TREE=tep_get_category_tree('0', '',$category_id);
$category_name=tep_get_category_name($category_id,$FSESSION->languages_id);
?>
<form action="javascript:void(0)" method="post" enctype="application/x-www-form-urlencoded" name="catCloneSubmit" id="catCloneSubmit" onsubmit="alert('you submitted the form');">
    <input type="hidden" name="category_id" value="<?php echo $category_id; ?>"/>
    <table width="100%"  border="0" cellspacing="5" cellpadding="5" style="padding-left:20px;">
        <tr>
            <td class="main" id="cat<?php echo $category_id;?>message"></td>
        </tr>
        <tr>
            <td class="main"><b><?php echo sprintf(TEXT_CLONE_CATEGORIES_INTRO, $category_name);?></b></td>
        </tr>

        <tr>
            <td class="main"><span onClick="javascript:toggle_visibility('ajxLoad'); document.getElementById('ajxLoadMessage').innerHTML = '<?php echo TEXT_CLONE_CATEGORIES_WAIT;?>';"><a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $category_id;?>,type:'cat',style:'boxLevel1','get':'CategoryClone','result':doTotalResult,uptForm:'catCloneSubmit','imgUpdate':false,message:page.template['CAT_CLONE']});"><?php echo '<span class="btn btn-primary btn-sm">&nbsp;' . TEXT_CLONE_CATEGORIES_INTO . '&nbsp;</span>';?></a></span>&nbsp;<a href="javascript:void(0)" onClick="javascript:return doCancelAction({id:<?php echo $category_id;?>,type:'cat','get':'closeRow',style:'boxLevel1'});"><?php echo tep_image_button_cancel('button_cancel.gif');?></a></td>
        </tr>
    </table>
</form>
<?php
}
function doProductDeleteDisplay()
{
global $FREQUEST,$jsData,$FSESSION;
$product_id=$FREQUEST->getvalue('pID','int',0);
$category_id=$FREQUEST->getvalue('cID','int',0);
$product_categories = tep_generate_category_path($product_id, 'product');
$last_flag=$FREQUEST->getvalue('lflag','int',0);

$delete_message='';
$form_elements='';
if($total_count>0) 
{
    //$delete_message.=DELETE_ERR . '<p>';
} else 
{
    for ($icnt = 0, $n = count($product_categories); $icnt < $n; $icnt++) 
	{
        $category_path = '';
        for ($jcnt=0, $k = count($product_categories[$icnt]); $jcnt < $k; $jcnt++)
        $category_path .= $product_categories[$icnt][$jcnt]['text'] . '&nbsp;&gt;&nbsp;';
        $category_path = substr($category_path, 0, -16);
        $form_elements .= tep_draw_checkbox_field('product_categories[]', $product_categories[$icnt][count($product_categories[$icnt])-1]['id'], true) . '&nbsp;' . $category_path . '<br>';
    }
    $form_elements = substr($form_elements, 0, -4);
    $delete_message='<p><span class="smallText">' . TEXT_DELETE_PRODUCT_INTRO . '</span>';
}
?>
<form  name="prdDeleteSubmit" id="prdDeleteSubmit" action="products_mainpage.php" method="post" enctype="application/x-www-form-urlencoded">
    <input type="hidden" name="product_id" value="<?php echo tep_output_string($product_id);?>"/>
    <input type="hidden" name="category_id" value="<?php echo tep_output_string($category_id);?>"/>
    <table border="0" cellpadding="2" cellspacing="0" width="100%">
        <tr>
            <td class="main" id="prd<?php echo $product_id;?>message">
            </td>
        </tr>
        <tr>
            <td class="main">
                <?php echo $delete_message;?>
            </td>
        </tr>
        <?php if ($form_elements!='') { ?>
        <tr>
            <td class="main">
                <?php echo $form_elements;?>
            </td>
        </tr>
        <?php } ?>
        <tr height="40">
            <td class="main" style="vertical-align:bottom">
                <p>
                <?php if ($form_elements!='') { ?>
                <a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $product_id;?>,type:'prd',get:'ProductDelete',result:doTotalResult,message:page.template['PRD_DELETING'],'uptForm':'prdDeleteSubmit','imgUpdate':false,params:'lflag=<?php echo $last_flag;?>'})"><?php echo tep_image_button_delete('button_delete.gif');?></a>&nbsp;
                <?php } ?>
                <a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:<?php echo $product_id;?>,type:'prd',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button_cancel('button_cancel.gif');?></a>
            </td>
        </tr>
        <tr>
            <td><hr/></td>
        </tr>
        <tr>
            <td valign="top" class="categoryInfo"><?php echo $this->doProductInfo($product_id);?></td>
        </tr>
    </table>
</form>
<?php
$jsData->VARS["updateMenu"]="";
}

	function doProductCopyAttributesDisplay()
	{

	?>
	<?php
	$jsData->VARS["updateMenu"]="";
	}
	function doProductMove()
	{
	global $FREQUEST,$jsData,$FSESSION;
	$product_id=$FREQUEST->postvalue('product_id','int',0);
	$old_parent_id=$FREQUEST->postvalue('category_id','int',0);
	$new_parent_id=$FREQUEST->postvalue('move_to_category_id','int',0);
	//$product_type=$sql_array["product_type"]=$FREQUEST->postvalue('product_type','string','G');
	$type_query=tep_db_query("SELECT product_type from " . TABLE_PRODUCTS . " where products_id=$product_id");
	$type= tep_db_fetch_array($type_query);

	$duplicate_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = $product_id and categories_id = $new_parent_id ");
	$duplicate_check = tep_db_fetch_array($duplicate_check_query);
	if ($duplicate_check['total'] < 1) 
	{
		tep_db_query("update " . TABLE_PRODUCTS_TO_CATEGORIES . " set categories_id = $new_parent_id where products_id = $product_id and categories_id = $old_parent_id");
		if($type!='P')
		{
		//tep_db_query("update " . TABLE_PRODUCTS . " set section_id = $new_parent_id where products_id = $product_id ");
		//tep_db_query("update " . TABLE_PRODUCTS . " set parent_id = $new_parent_id where products_id = $product_id ");
		}
		//	$jsData->VARS["deleteRow"]=array('type'=>'prd','id'=>$product_id);
		$this->doCategoryProducts($old_parent_id);
		$jsData->VARS["displayMessage"]=array("text"=>TEXT_PRODUCT_MOVED_SUCCESS);
	} else {
		echo 'Err:' . TEXT_PRODUCT_ALREADY_LINKED;
		$jsData->VARS["updateMenu"]=",normal,";
	}
	}
function doProductMoveDisplay()
{
global $FSESSION,$jsData,$FREQUEST;
$category_id=$FREQUEST->getvalue('cID','int',0);
$product_id=$FREQUEST->getvalue('pID','int',0);
$products_name=tep_get_products_name($product_id);
?>
<form  name="prdMoveSubmit" id="prdMoveSubmit" action="products_mainpage.php" method="post" enctype="application/x-www-form-urlencoded">
<input type="hidden" name="product_id" value="<?php echo tep_output_string($product_id);?>"/>
<input type="hidden" name="category_id" value="<?php echo tep_output_string($category_id);?>"/>
<table border="0" cellpadding="4" cellspacing="0" width="100%">
    <tr>
        <td class="main" id="prd<?php echo $product_id;?>message">
        </td>
    </tr>
    <tr>
        <td class="inner_title"><?php echo TEXT_INFO_HEADING_MOVE_PRODUCT;?></td>
    </tr>
    <tr>
        <td class="main"><?php echo TEXT_INFO_CURRENT_CATEGORIES .'&nbsp;&nbsp;<b>' . tep_output_generated_category_path($category_id);?></td>
    </tr>
    <tr>
        <td class="main"><?php echo sprintf(TEXT_MOVE_PRODUCTS_INTRO, $products_name);?></td>
    </tr>
    <tr>
        <td class="main"><?php echo TEXT_MOVE_TO . '&nbsp;' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree('0','',0), $category_id);?></td>
    </tr>
    <tr>
        <td class="main">
            <a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $product_id;?>,type:'prd',get:'ProductMove',result:doTotalResult,message:page.template['PRD_MOVING'],'uptForm':'prdMoveSubmit','closePrev':true,'imgUpdate':false,params:''})"><?php echo tep_image_button_move('button_move.gif');?></a>&nbsp;
            <a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:<?php echo $product_id;?>,type:'prd',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button_cancel('button_cancel.gif');?></a>
        </td>
    </tr>
</table>
<?php
$jsData->VARS["updateMenu"]="";
}
function doProductCopy()
{
	global $FREQUEST,$jsData,$FSESSION;
	$product_id=$FREQUEST->postvalue('product_id','int',0);
	$category_id=$FREQUEST->postvalue('category_id','int',0);
	$new_parent_id=$FREQUEST->postvalue('copy_to_category_id','int',0);
	if ($product_id==0 || $category_id==0 || $new_parent_id==0)
	{
		echo 'Err:' . TEXT_INVALID_DATA;
		return;
	}
	$copy_as=$FREQUEST->postvalue('copy_as');
	$price_breaks=$FREQUEST->postvalue('chk_cp_pricebreaks','int',0);
	$images=$FREQUEST->postvalue('chk_cp_images','int',0);
	$section_id=0;
	if ($copy_as!="duplicate")
	{
		if ($category_id != $new_parent_id) 
		{
			$check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = $product_id and categories_id = $new_parent_id and section_id = $new_parent_id");
			$check = tep_db_fetch_array($check_query);
			if ($check['total'] < '1') 
			{
				tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id, section_id) values ($product_id,$new_parent_id,$new_parent_id)");
				$this->doCategoryProducts($category_id);
				$jsData->VARS["displayMessage"]=array('text'=>TEXT_PRODUCT_LINKED_SUCCESS);
			  //  $jsData->VARS["closeRow"]=array('type'=>'prd');
			} else 
			{
				echo 'Err:' . TEXT_PRODUCT_ALREADY_LINKED;
			}
		} else 
		{
			echo 'Err:' . ERROR_CANNOT_LINK_TO_SAME_CATEGORY;
		}
    return;
	}
	$fields="products_quantity,author_name,color_code,products_model,section_id,parent_id,products_sku,products_price,products_date_available,products_weight,products_status, products_season,products_tax_class_id,products_sort_order,manufacturers_id,restrict_to_groups,restrict_to_customers,product_type,product_mode,download_last_date,downloads_per_customer,download_link,master_quantity,products_x,products_y,products_w,products_h,products_r,products_sx,products_sy,products_season";
	if ($images==1) $fields.=",products_image_1,products_title_1";
	if ($price_breaks==1) $fields.=",products_price_break";

	$product_query = tep_db_query("select $fields from " . TABLE_PRODUCTS . " where products_id=$product_id");
	$product = tep_db_fetch_array($product_query);
	if ($price_breaks==1)
	{
		$product["products_price_break"]='N';
	}

	//Nov 2013 fix section id
	$product["section_id"]=$new_parent_id;
	$product["is_attributes"]='N';

	tep_db_perform(TABLE_PRODUCTS,$product);
	$newID=tep_db_insert_id();
	if ($newID<=0){
		echo "Err: " . ERR_PRODUCT_NOT_COPIED;
		return;
	}

	$description_query = tep_db_query("select * from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id =$product_id");

	while ($description = tep_db_fetch_array($description_query)) 
	{
		
	//tep_db_input($description['products_number'])
		
    tep_db_query("insert into " . TABLE_PRODUCTS_DESCRIPTION . " (products_id, html,language_id, products_name, products_description, products_number, products_url, products_viewed,section_id) 
	values ($newID, '','" . (int)$description['language_id'] . "', '" . tep_db_input($description['products_name']) . "', '" . tep_db_input($description['products_description']) . "',  '" . tep_db_input($description['products_number']) . "', '" . tep_db_input($description['products_url']) . "', '0','$new_parent_id')");
	}
	if($product['products_price_break']=='Y')
	{
		$price_break_query=tep_db_query("select * from " . TABLE_PRODUCTS_PRICE_BREAK . " where products_id=$product_id");
		while($price_break_result=tep_db_fetch_array($price_break_query))
		{
			tep_db_query("insert into " . TABLE_PRODUCTS_PRICE_BREAK . " (products_id,quantity,discount_per_item) values($newID,'" . $price_break_result['quantity'] . "','" .$price_break_result['discount_per_item'] . "')");
		}
	}
	tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id,section_id) values ($newID, $new_parent_id,$new_parent_id)");
	//echo "SUCCESS";
	//$jsData->VARS["updateMenu"]=",normal,";
	$this->doCategoryProducts($category_id);
	$jsData->VARS["displayMessage"]=array('text'=>TEXT_PRODUCT_COPIED_SUCCESS);
	//$jsData->VARS["closeRow"]='yes';
}
function doProductCopyDisplay()
{
	global $FSESSION,$jsData,$FREQUEST;
	$category_id=$FREQUEST->getvalue('cID','int',0);
	$product_id=$FREQUEST->getvalue('pID','int',0);
	?>
	<form  name="prdCopySubmit" id="prdCopySubmit" action="products_mainpage.php" method="post" enctype="application/x-www-form-urlencoded">
		<input type="hidden" name="product_id" value="<?php echo tep_output_string($product_id);?>"/>
		<input type="hidden" name="category_id" value="<?php echo tep_output_string($category_id);?>"/>
		<table border="0" cellpadding="4" cellspacing="0" width="100%">
			<tr>
				<td class="main" id="prd<?php echo $product_id;?>message">
				</td>
			</tr>
			<tr>
				<td class="inner_title"><?php echo TEXT_INFO_HEADING_COPY_PRODUCT;?></td>
			</tr>
			<tr>
				<td class="main"><?php echo TEXT_INFO_CURRENT_CATEGORIES .'&nbsp;&nbsp;<b>' . tep_output_generated_category_path($category_id) . '<p>';?></td>
			</tr>
			<tr>
				<td class="main"><?php echo TEXT_INFO_COPY_TO_INTRO;?></td>
			</tr>
			<tr>
				<td class="main"><?php echo TEXT_COPY_TO . tep_draw_pull_down_menu('copy_to_category_id', tep_get_category_tree('0','',0), $category_id);?></td>
			</tr>
			<tr>
				<td class="main">
					<?php
					echo TEXT_HOW_TO_COPY . '&nbsp;&nbsp;';
					//echo tep_draw_radio_field('copy_as', 'link', true,'','onClick="javascript:toggleView({id:\'copyProductView\',element:this,checkValue:\'duplicate\',prop:\'customCheck\'})"') . '&nbsp;' . TEXT_COPY_AS_LINK . '&nbsp;&nbsp;&nbsp;'.;
					
					 echo tep_draw_radio_field('copy_as', 'duplicate',true,'','onClick="javascript:toggleView({id:\'copyProductView\',element:this,checkValue:\'duplicate\',prop:\'customCheck\'});"') . '&nbsp;' . TEXT_COPY_AS_DUPLICATE . '<p>';
					echo '<div id="copyProductView" style="vertical-align:middle;display:true">';
				   // echo TEXT_COPY_ALSO . tep_draw_checkbox_field('chk_cp_pricebreaks',1) . TEXT_COPY_PRICEBREAKS . '&nbsp;';
					echo tep_draw_checkbox_field('chk_cp_images',1) . TEXT_COPY_IMAGES . '&nbsp;';

					echo '</div>';
					?>
				</td>
			</tr>
			<tr>
				<td class="main">
					<a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $product_id;?>,type:'prd',get:'ProductCopy',result:doTotalResult,message:'<?php echo tep_output_string(TEXT_PRODUCT_COPYING);?>','uptForm':'prdCopySubmit','closePrev':true,'imgUpdate':false,params:''})"><?php echo tep_image_button_copy('button_copy.gif');?></a>&nbsp;
					<a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:<?php echo $product_id;?>,type:'prd',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button_cancel('button_cancel.gif');?></a>
				</td>
			</tr>
		</table>
	</form>
	<?php
	$jsData->VARS["updateMenu"]="";
}
function doProductEdit()
{

	global $FREQUEST,$jsData,$CAT_TREE,$FSESSION,$LANGUAGES;
	$product_id=$FREQUEST->getvalue('pID','int',0);
	$category_id=$FREQUEST->getvalue('cID','int',0);

	for ($icnt=0,$n=count($LANGUAGES);$icnt<$n;$icnt++)
	{
		$products_name[$LANGUAGES[$icnt]['id']]='';
		$products_description[$LANGUAGES[$icnt]['id']]='';
		$products_url[$LANGUAGES[$icnt]['id']]='';
		//cartzone number
		$products_number[$LANGUAGES[$icnt]['id']]='';
	}
	if ($product_id>0)
	{
		$product_query = tep_db_query("select p.products_id,p.color_code,p.products_quantity, p.is_attributes,p.products_price_break,p.author_name,p.products_model,p.section_id,p.parent_id,p.products_sku, p.products_season,p.products_image_1,p.products_title_1,p.products_price, p.products_weight, p.products_sort_order, date_format(p.products_date_added,'%Y-%m-%d') as products_date_added, date_format(p.products_last_modified,'%Y-%m-%d') as products_last_modified, date_format(p.products_date_available, '%Y-%m-%d %H:%i') as products_date_available, p.products_status, p.products_tax_class_id,p.manufacturers_id,p.restrict_to_groups,p.restrict_to_customers,p.product_type,p.product_mode,p.download_link,p.downloads_per_customer,p.download_last_date,p.products_x,p.products_y,p.products_w,p.products_h,p.products_r,p.products_sx,p.products_sy,p.master_quantity from " . TABLE_PRODUCTS . " p where p.products_id =" . $product_id);

    $product_result=tep_db_fetch_array($product_query);
	
	
    $description_query=tep_db_query("SELECT products_name,products_number,products_url,products_description ,language_id from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id=" . $product_id);
    while($description=tep_db_fetch_array($description_query))
	{
        $products_name[$description['language_id']]=tep_db_input($description["products_name"]);
        $products_url[$description['language_id']]=tep_db_input($description["products_url"]);
		$products_number[$description['language_id']]=tep_db_input($description["products_number"]);
        $products_description[$description['language_id']]=tep_db_input($description["products_description"]);
    }
	
	
} else {
    $product_result=array('products_id'=>0,'products_quantity'=>'','is_attributes'=>'N','products_price_break'=>'N','author_name'=>'',"color_code"=>'','products_model'=>'','products_sku'=>'9', 'products_season'=>'',
                    'products_image_1'=>'','products_title_1'=>'',
                    'products_price'=>'','products_weight'=>'0','products_sort_order'=>'','products_date_available'=>'0','products_date_added'=>'','products_last_modified'=>'','products_status'=>'1', 'products_tax_class_id'=>'',
                    'manufacturers_id'=>'','restrict_to_groups'=>'','restrict_to_customers'=>'','product_type'=>'','product_mode'=>'P','download_link'=>'','downloads_per_customer'=>'','download_last_date'=>'','master_quantity'=>'','products_x'=>'','products_y'=>'','products_w'=>'','products_h'=>'','products_r'=>'','products_sx'=>'','products_sy'=>''
    );
}
$product=new objectInfo($product_result);
$tax_rate=tep_get_tax_rate($product->products_tax_class_id);

$manufacturers_array = array(array('id' => '', 'text' => TEXT_NONE));
$manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
    $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'],
                                 'text' => tep_db_prepare_input($manufacturers['manufacturers_name']));
}			

$customer_groups_list=array();
$customer_group_query=tep_db_query('select customers_groups_id,customers_groups_name from '.TABLE_CUSTOMERS_GROUPS . ' where customers_groups_name!="" order by customers_groups_name');
while($customer_group=tep_db_fetch_array($customer_group_query)){
    $customer_groups_list[]=array('id'=>$customer_group['customers_groups_id'],'text'=>tep_db_prepare_input($customer_group['customers_groups_name']));
}

$get_selected_groups=array();
if($product->restrict_to_groups!="") 
{
	$str_splt=explode(",",$product->restrict_to_groups);
    for($icnt=0,$n=count($str_splt);$icnt<$n;$icnt++) 
	{
        $get_selected_groups[]=array('id'=>$str_splt[$icnt]);
    }
}
$customers_list=array();
$customer_query=tep_db_query("select customers_id,concat(customers_lastname,' ',customers_firstname) as customer_name from ".TABLE_CUSTOMERS . ' order by customers_lastname,customers_firstname');

while($customers=tep_db_fetch_array($customer_query)){
    $customers_list[]=array('id'=>$customers['customers_id'],'text'=>tep_db_prepare_input($customers['customer_name']));
}

$get_selected_customers=array();
if($product->restrict_to_customers!="") 
{
	$str_splt=explode(",",$product->restrict_to_customers);
    for($icnt=0,$n=count($str_splt);$icnt<$n;$icnt++) 
	{
        $get_selected_customers[]=array('id'=>$str_splt[$icnt]);
    }
}
$break_array=array();
$js_break_array=array();
if($product->products_price_break=='Y') 
{
    $break_query=tep_db_query("select * from " . TABLE_PRODUCTS_PRICE_BREAK . " where products_id='" . $product_id . "' order by quantity");
    while($break_result=tep_db_fetch_array($break_query))
	{
       // $new_price=tep_add_tax(($break_result['quantity']*($product->products_price-$break_result['discount_per_item'])),$tax_rate);
		    $new_price=tep_add_tax((($break_result['discount_per_item'])),$tax_rate);
        $break_array[]=array('id'=>$break_result['quantity'] . '##' . $break_result['price'], 'text'=>str_replace(array("##1##","##2##"),array($break_result['quantity'],$new_price),TEXT_PRICE_BREAK_OPTION_TEXT));
        $js_break_array[]=array("quan"=>$break_result['quantity'],"price"=>$break_result['discount_per_item']);
    }
}

if (count($CAT_TREE)<=0)$categories_array = tep_get_category_tree(0,'',0); 

if($product_id>0)
{ 
    $categories_query_selected = tep_db_query("select categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = " . $product_id);
    while ($categories = tep_db_fetch_array($categories_query_selected)){
        $categories_array_selected[] = array('id' => $categories['categories_id']);
    }
	//have to try and stop manufacters_id being changed
	if ($product->product_type=="P")
	{
        $manu_query=tep_db_query("select * from " . TABLE_PRODUCTS ." where products_id=" .$product_id);
        while($temp_result=tep_db_fetch_array($manu_query)){
            $manu_categories_array_selected[]=array('id' => $temp_result['parent_id']);
        }
    }
	

} else {
    $categories_array_selected[] = array('id' =>$category_id);
	$manu_categories_array_selected=array();
}

$language_id = 1;

$tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
$tax_class_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
while ($tax_class = tep_db_fetch_array($tax_class_query))
$tax_class_array[] = array('id' => $tax_class['tax_class_id'],
                           'text' => $tax_class['tax_class_title']);

	
if ($product->product_type=="L")
{
#############################################################	
	switch ($product->products_status) 
		{
		case '5': $t_status = false; $b_status = true; 
		break;
		case '4':
		default: $t_status = true; $b_status = false;
		}
################################################################
}else{
#############################################################	
	switch ($product->products_status) 
		{
		case '0': $in_status = false; $out_status = true; 
		break;
		case '1':
		default: $in_status = true; $out_status = false;
		}
################################################################				
}
			
			
			
			
			$current_date=getServerDate();
			
            $products_type=array(
				array('id'=>'P','text'=>TEXT_RESERVED_SEATING),				
				array('id'=>'G','text'=>TEXT_GENERAL_ADMISSION),
				array('id'=>'F','text'=>TEXT_FAMILY),
				array('id'=>'C','text'=>TEXT_GIFT),
                array('id'=>'B','text'=>TEXT_ADDON),
				array('id'=>'L','text'=>TEXT_ROW_TEXT),
				array('id'=>'Q','text'=>TEXT_PROPS)
            );

           
            $_array=array('d','m','Y');  
			$replace_array=array('DD','MM','YYYY'); 	
			$date_format=str_replace($_array,$replace_array,EVENTS_DATE_FORMAT);
            $panels=array('GENERAL','TYPE','DESCRIPTION','COST','STOCK','IMAGE');
            $default_panel='GENERAL';



             $jsData->VARS['storePage']=array(
			 'productPanel'=>$default_panel,
			 "NUpriceBreaks"=>$js_break_array,
			 "curAttrStock"=>array());
            $jsData->VARS["updateMenu"]=",update,";
            $display_mode_html=' style="display:none"';

            ?>
<table border="0" cellpadding="4" cellspacing="0" width="100%">
    <tr height="430px">
        <td valign="top" width="150">
            <table border="0" cellpadding="0" cellspacing="0" class="boxRoundPanel">
                <tr>
                    <td valign="top">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr height="7">
                                <td class="tleft"></td>
                                <td></td>
                                <td class="tright"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td valign="top" class="mright">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">


                        <?php for ($icnt=0,$n=count($panels);$icnt<$n;$icnt++)
						{ ?>
                            <tr>
                                <td class="<?php echo ($default_panel==$panels[$icnt]?"productPanelSelect":"productPanel");?>" onClick="javascript:showPanelContent({id:'<?php echo $panels[$icnt];?>',className:'productPanel','type':'productPanel','extraFunc':<?php echo $panels[$icnt]=="DESCRIPTION"?'doProductEditor':'false';?>});" id="productPanel<?php echo $panels[$icnt];?>menu">
                                    <?php
                                    echo "<a href='javascript:void(0);'><div>" . constant("HEADING_ITEM_" .$panels[$icnt]) . '</div></a>';
                                    $jsData->VARS["storePage"]["productPanelMenus"][$panels[$icnt]]=array('text'=>constant("HEADING_ITEM_" .$panels[$icnt]));
                                    ?>
                                </td>
                            </tr>
                            <?php 
						} ?>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmiddle">
                            <tr height="7">
                                <td class="bleft"></td>
                                <td></td>
                                <td class="bright"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
        <td valign="top">
		
            <form action="products_mainpage_ajax1.php" method="post" name="productSubmit" enctype="multipart/form-data" id="productSubmit">
                <input type="hidden" name="product_id" value="<?php echo tep_output_string($product_id);?>"/>
                <input type="hidden" name="category_id" value="<?php echo tep_output_string($category_id);?>"/>
                <input type="hidden" name="prev_attribute_stock_ids" value="<?php echo tep_output_string($prevStockValue);?>">
                <input type="hidden" name="prev_attribute_ids" value="<?php echo tep_output_string($prev_attribute);?>"/>
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr height="30">
                    <td class="headItemEdit" valign="top">
                        <?php echo tep_image(DIR_WS_IMAGES . '/template/arrow_down.gif','','','','align=absmiddle');?>
                        <span id="productPanelTitle">
                            <?php echo constant("HEADING_ITEM_" . $default_panel);?>
                        </span>
                        <hr size="1" color="#D2D8E7"/>
                    </td>
                </tr>
                <tr>
                <td valign="top">
				
				<?php 
if ($product->product_type=="L" || $product->product_type=="Q")
				{
				$disabled='disabled';
				}else{
				$disabled='';
				}
				?>
				
                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="productEditCol">
                    <tr id="productPanelGENERALview">
                        <td width="50%" valign="top">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td class="main">
                                        <div style="padding-bottom:5px"><b><?php echo TEXT_PRODUCT_NAME;?></b></div>
                                        <div style="padding-left:20px">
                                            <?php
                                            for ($icnt=0, $n=count($LANGUAGES); $icnt<$n; $icnt++) {
                                                echo tep_image(DIR_WS_CATALOG_LANGUAGES . $LANGUAGES[$icnt]['directory'] . '/images/' . $LANGUAGES[$icnt]['image'], $LANGUAGES[$icnt]['name'],'','','valign="top"');
                                                echo '&nbsp;' . tep_draw_input_field('products_name[' . $LANGUAGES[$icnt]['id'] . ']', tep_db_prepare_input($products_name[$LANGUAGES[$icnt]['id']]),'maxlength="64"') . '<br>';
                                            }
                                            ?>
                                        </div>
                                    </td>
                                </tr>
								<?php
								if ($product->product_type=="L"){
								?>
                                <tr>
                                    <td class="main">
                                        <?php
                                        echo '<div class="title">' . TEXT_PRODUCTS_STATUS . '</div>';
                                        echo tep_draw_radio_field('products_status', '4', $t_status) . '&nbsp;' . TEXT_PRODUCT_TEXT . '&nbsp;' . tep_draw_radio_field('products_status', '5', $b_status) . '&nbsp;' . TEXT_PRODUCT_BUTTON;
                                        ?>
                                    </td>
                                </tr>
								<?php
								}else{
								?>
								<tr>
                                    <td class="main">
                                        <?php
                                        echo '<div class="title">' . TEXT_PRODUCTS_STATUS . '</div>';
                                        echo tep_draw_radio_field('products_status', '1', $in_status) . '&nbsp;' . TEXT_PRODUCT_AVAILABLE . '&nbsp;' . tep_draw_radio_field('products_status', '0', $out_status) . '&nbsp;' . TEXT_PRODUCT_NOT_AVAILABLE;
                                        ?>
                                    </td>
                                </tr>
								<?php
								}		
								?>
                                <!--try section ID-->
                                <tr>
                                    <td class="main">
                                        <?php
                                        echo '<div class="title">'. TEXT_SECTION_ID .'</div>';
                                        echo $product->section_id;
                                        ?>
                                    </td>
                                </tr>
						<tr <?php if ($product->products_h==0)
									{ 
									//echo "style=\"display:none\"";
									}
									?>>
						<td class="main">
						<?php
						
						echo '<div class="title">' . TEXT_PRODUCTS_WIDTH .'&nbsp;&nbsp;<br>'.tep_draw_input_field('products_w',(tep_not_null($product->products_w)?$product->products_w:44),'onKeyPress="return keyRestrict(event);" maxlength=4') .'&nbsp;<br></div>' ;
						
						?>
						</td>
						<td class="main">
						<?php
						
						echo '<div class="title">' . TEXT_PRODUCTS_HEIGHT .'&nbsp;&nbsp;<br>'.tep_draw_input_field('products_h',(tep_not_null($product->products_h)?$product->products_h:36),'onKeyPress="return keyRestrict(event);"  maxlength=4') .'&nbsp;<br></div>' ;
						
						?>
						</td>
						</tr>
						<tr <?php if ($product->products_h==0)
									{ 
									//echo "style=\"display:none\"";
									}
									?>>
						<td class="main">
						<?php
						
						echo '<div class="title">' . TEXT_PRODUCTS_ROTATE .'&nbsp;&nbsp;<br>'.tep_draw_input_field('products_r',(tep_not_null($product->products_r)?$product->products_r:0),'onKeyPress="return keyRestrict(event, true);" maxlength=4') .'&nbsp;<br></div>' ;
						
						?>
						</td>
						<td class="main">
						<?php
						
						echo '<div class="title">' . TEXT_PRODUCTS_SCALE_X .'&nbsp;&nbsp;<br>'.tep_draw_input_field('products_sx',(tep_not_null($product->products_sx)?$product->products_sx:0),'size="10" maxlength="15"') .'&nbsp;<br></div>' ;
						echo '<div class="title">' . TEXT_PRODUCTS_SCALE_Y .'&nbsp;&nbsp;<br>'.tep_draw_input_field('products_sy',(tep_not_null($product->products_sy)?$product->products_sy:0),'size="10" maxlength="15"') .'&nbsp;<br></div>' ;
						
						?>
						</td>
						</tr>
						
						<tr <?php if ($product->products_r==0)
									{ 
									echo "style=\"display:none\"";
									}
									?>>
						<td class="main">
						<?php
						
						echo '<div class="title">' . TEXT_PRODUCTS_X .'&nbsp;&nbsp;<br>'.tep_draw_input_field('products_x',(tep_not_null($product->products_x)?$product->products_x:0),'onKeyPress="return keyRestrict(event, true);" maxlength=4') .'&nbsp;<br></div>' ;
						
						?>
						</td>
						<td class="main">
						<?php
						
						echo '<div class="title">' . TEXT_PRODUCTS_Y .'&nbsp;&nbsp;<br>'.tep_draw_input_field('products_y',(tep_not_null($product->products_y)?$product->products_y:0),'size="10" maxlength="15"') .'&nbsp;<br></div>' ;
						
						?>
						</td>
						</tr>
						
						
                                <tr>
                                    <td class="main" <?php if ($product->product_type=="P")
									{ 
									echo "style=\"display:none\"";
									}
									?>>
                                        <?php
                                        echo '<div class="title">' . TEXT_CATEGORIES . '</div>';
                                        echo tep_draw_mselect_checkbox('categories_ids[]',$categories_array,$categories_array_selected);
                                        ?>
                                    </td>
                                </tr>
                               <!-- <tr>
                                    <td class="main">
								<?php
                                //echo '<div class="title">' . TEXT_PRODUCTS_MANUFACTURER . '</div>';
                                //echo tep_draw_pull_down_menu('manufacturers_id', $manufacturers_array, $product->manufacturers_id);
								?>
                                    </td>
                                </tr>-->
                            </table>
                        </td>
                        <td>
                            <table border="0" cellpadding="2" cellspacing="0" width="100%">
                                <tr>
                                    <td class="main">
                                        <?php 
							
										
										
										echo '<div style="padding-bottom:5px"><b>' . TEXT_PRODUCTS_NUMBER . '</b></div>';
										
										?>
                                        <div  style="padding-left:10px">
                                            <?php
                                            for ($icnt=0, $n=count($LANGUAGES); $icnt<$n; $icnt++) 
											{
                                                echo tep_image(DIR_WS_CATALOG_LANGUAGES . $LANGUAGES[$icnt]['directory'] . '/images/' . $LANGUAGES[$icnt]['image'], $LANGUAGES[$icnt]['name'],'','','valign="top"');
                                                echo '&nbsp;' . tep_draw_input_field('products_number[' . $LANGUAGES[$icnt]['id'] . ']', tep_db_prepare_input($products_number[$LANGUAGES[$icnt]['id']]),'style="width:320px" maxlength="64"') . '<br>';
                                            }
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="main" >
                                       <?php 

										if (($product->product_type=="L")&&($product->products_status==5))
											{
											$vdisabled='';
											}else
											{
											 $vdisabled='disabled';
											}

									   echo '<div style="padding-bottom:5px"><b>' . TEXT_BUTTON_URL . '</b></div>'?>
                                       <div  style="padding-left:10px">
                                            <?php
                                            for ($icnt=0, $n=count($LANGUAGES); $icnt<$n; $icnt++) 
											{
                                                //echo $LANGUAGES[$icnt]['name'],'';
                                                echo '&nbsp;' . tep_draw_input_field('products_url[' . $LANGUAGES[$icnt]['id'] . ']', tep_db_prepare_input($products_url[$LANGUAGES[$icnt]['id']]),'style="width:300px;" '.$vdisabled.' maxlength="128"') . '<br>';
                                            }
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                                  
                               <tr>
                                    <td class="main">
                                        
                                        <?php
                                        echo '<div class="title">' . TEXT_PRODUCTS_DATE_AVAILABLE . '<small>(' . $date_format . ')</small></div>';
                                        echo tep_draw_input_field("products_date_available",(tep_not_null($product->products_date_available)?format_datetime($product->products_date_available):format_date($current_date)),'size=20 '.$disabled.' onclick="callstrt();"',false,'text',false);
                                        ?>
                                    </td>
                                    
                                </tr>
                                <tr>
                                <td class="main">
                                <?php
                                echo '<div class="title">' .  TEXT_RESTRICT_GROUPS . '</div>';
                                echo tep_draw_mselect_checkbox('restrict_to_groups[]',$customer_groups_list,$get_selected_groups);
                                ?>
                            </table>
                        </td>
                    </tr>
                    <tr id="productPanelDESCRIPTIONview" style="display:none">
                        <td colspan=2 valign="top">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td valign="top" class="main" width="100%">
                                        <div  style="padding-left:10px">
                                            <?php
                                            for ($icnt=0, $n=count($LANGUAGES); $icnt<$n; $icnt++) {
                                                echo tep_image(DIR_WS_CATALOG_LANGUAGES . $LANGUAGES[$icnt]['directory'] . '/images/' . $LANGUAGES[$icnt]['image'], $LANGUAGES[$icnt]['name'],'','','valign="top"');
                                                echo '&nbsp;' . tep_draw_textarea_field('products_description[' . $LANGUAGES[$icnt]['id'] . ']', 'soft', '80', '24', tep_db_prepare_input($products_description[$LANGUAGES[$icnt]['id']])). '<br><br>';
                                            }
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr id="productPanelTYPEview" style="display:none">
                        <td width="50%">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td class="main">
                                        <div style="padding-bottom:5px">
                                            <b><?php echo TEXT_PRODUCT_TYPE;?></b>
                                        </div>
                                        <?php
	
										
                                        if (@strpos("GFBLQ",$product->product_type)===false) $default;
                                        $jsData->VARS["storePage"]["ptypePanel"]=$product->product_type;
                                        echo tep_draw_pull_down_menu('product_type',$products_type,$product->product_type,'onChange="javascript:showPanelContent({id:this.value,type:\'ptypePanel\'});"');
                                        ?>
                                    </td>
                                </tr>
                                <tr id="ptypePanelGview" <?php 
				echo $product->product_type=="B" || $product->product_type=="G" || $product->product_type=="F" || $product->product_type=="P" || $product->product_type=="L"?'':$display_mode_html;?>>
                                <td class="main">
                                  <?php // change products model to color code
                                    echo '<div class="title">' . TEXT_COLOR_CODE . '</div>';

									$color_array=array();
									$color_array[] = array('id'=>'red','text'=>'Red');
									$color_array[] = array('id'=>'blue','text'=>'Blue');
									$color_array[] = array('id'=>'green','text'=>'Green');
									$color_array[] = array('id'=>'yellow','text'=>'Yellow');
									$color_array[] = array('id'=>'fuchsia','text'=>'Fuchsia');
									$color_array[] = array('id'=>'salmon','text'=>'Salmon');
									$color_array[] = array('id'=>'teal','text'=>'Teal');
									$color_array[] = array('id'=>'orange','text'=>'Orange');
									$color_array[] = array('id'=>'palegreen','text'=>'Pale green');
									$color_array[] = array('id'=>'skyblue','text'=>'Sky blue');
									$color_array[] = array('id'=>'thistle','text'=>'Thistle'	);
									
									echo tep_draw_pull_down_menu('color_code',$color_array,$product->color_code,'');
									
									
									$display="true"; 
										
                                    echo '<div style="display:' . $display . '"><div class="title">' . TEXT_PRODUCTS_MODEL . '</div>';
                                    echo tep_draw_input_field('products_model',$product->products_model,''.$disabled.' maxlength="128"');
									echo '</div>';
									echo '<div style="display:' . $display . '"><div class="title">' . TEXT_PRODUCTS_SEASON . '</div>';
                                    echo tep_draw_input_field('products_season',$product->products_season,''.$disabled.' maxlength="128"');
									echo '</div>';

									
                                    //echo tep_draw_input_field('products_model',(tep_not_null($product->products_model)?$product->products_model:$date_id),'maxlength="128"');
                                    ?></td>
                                </tr>
   
                            </table>
                        </td>
                        <td>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td class="main">
                                        <?php //cartzone take out product mode
                                       // if (strpos("PV",$product->product_mode)===false) 
										//$product->product_mode="P";
                                        $mode_array[]=array("id"=>"P","text"=>"Physical");
                                        $mode_array[]=array("id"=>"V","text"=>"Downloadable");
                                        $jsData->VARS['storePage']['pmodePanel']=$product->product_mode;
                                        echo '<div class="title">' . TEXT_PRODUCT_MODE . '</div>';
                                        echo tep_draw_pull_down_menu("product_mode",$mode_array,$product->product_mode,'onchange="javascript:showPanelContent({id:this.value,type:\'pmodePanel\'});"');
                                        ?>
                                    </td>
                                </tr>
                                <tr id="pmodePanelPview" <?php echo $product->product_mode=="P"?'':$display_mode_html;?>>
                                    <td class="main">
                                    </td>
                                </tr>
                                <tr id="pmodePanelVview" <?php echo $product->product_mode=="V"?'':$display_mode_html;?>>
                                    <td class="main">
                                        <?php
                                        echo '<div class="title">' . TEXT_DOWNLOAD_DATE . '</div>';
                                        echo tep_draw_input_field("download_last_date",(tep_not_null($product->download_last_date)?format_date($product->download_last_date):format_date($current_date)),"size=10",false,'text',false);
                                        tep_create_calendar("productSubmit.download_last_date",$date_format);
                                        echo '<div class="title">' . TEXT_DOWNLOADS_PER_CUSTOMERS . '</div>';
                                        echo tep_draw_input_field('downloads_per_customer',$product->downloads_per_customer,'size=6');
                                        echo '<div class="title">' . TEXT_DOWNLOAD_LINK . '</div>';
                                        echo tep_draw_hidden_field('download_link',$product->download_link);
                                        echo '<div id="download_link_file_container">';
                                        echo tep_draw_file_field('download_link_file') . '&nbsp;' . $product->download_link;
                                        echo "</div>";
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr id="productPanelCOSTview" style="display:none">

                        <td width="30%">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td class="main">
                                    <?php
                                    echo '<div class="title">' . TEXT_PRODUCTS_TAX_CLASS . '</div>';
                                    echo tep_draw_pull_down_menu('products_tax_class_id',$tax_class_array,$product->products_tax_class_id,'onchange="javascript:updateGross();doPriceBreaks({cmd:\'refresh\'});" ');
                                    ?>
                                </tr>
                                <tr>
                                    <td class="main">
                                        <?php
										if (!tep_not_null($product->products_price)) $product->products_price=0;
                                        echo '<div class="title">' . TEXT_PRODUCTS_PRICE_NET . '<div>';
                                        echo tep_draw_input_field("products_price",$product->products_price,' '.$disabled.' maxlength="12" onKeyUp="javascript:updateGross();" onblur="javascript:doPriceBreaks({cmd:\'refresh\'});"');
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="main">
                                        <?php
                                        echo '<div class="title">' . TEXT_PRODUCTS_PRICE_GROSS . '</div>';
                                        echo tep_draw_input_field('products_price_gross',tep_add_tax($product->products_price,$tax_rate), 'onKeyUp="javascript:updateNet();" onblur="javascript:doPriceBreaks({cmd:\'refresh\'});"');
                                        ?>
                                    </td>
                                </tr>

                            </table>
                        </td>
                        <td>
                          <!--<table style="display:none;visibility:hidden;" border="0" cellpadding="0" cellspacing="0" width="100%">-->
                          <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                              <td class="main">
                                <?php
                                echo '<div class="title">' . tep_draw_checkbox_field('products_price_break','Y',($product->products_price_break=='Y'?'Checked':''),'','onClick="javascript:toggleView({id:\'priceBreakView\',prop:\'display\'});"') . '&nbsp;' . EXT_PRICE_BREAK . '</div>';
                                ?>
                                <div id="priceBreakView" <?php echo $product->products_price_break=='Y'?'':$display_mode_html;?>>
                                    <table border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                        <td class="main" colspan="2">
                                            <?php
                                            echo '<div class="title">' . TEXT_PRODUCT_QUANTITY . '</div>';
                                            echo tep_draw_input_field('pbk_quantity','','size="7" maxlength="6" onKeyUp="javascript:doPriceBreaks({cmd:\'priceChange\'});"  onKeyPress="return keyRestrict(event);"');
                                            ?>
                                        </td>
                                        <tr>
                                        <tr>
                                            <td class="main" colspan="2">
                                                <?php
                                                echo '<div class="title">' . TEXT_DISCOUNT_PRICE . '</div>';
                                                echo tep_draw_input_field('pbk_discount_price','','size="10" maxlength="15" onKeyUp="javascript:doPriceBreaks({cmd:\'priceChange\'});"');
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="main" colspan="2">
                                                <?php
                                                echo '<div class="title">' . TEXT_FINAL_PRICE . '</div>';
                                                echo '<div id="pbk_calc_price">&nbsp;</div>';
                                                ?>
                                            </td>
                                        </tr>
                                        <tr><td height="10"></td></tr>
                                        <tr>
                                            <td class="main">
                                                <?php
                                                echo tep_draw_pull_down_menu('pbk_list',$break_array,'',' size="7" style="width:350px;" onClick="doPriceBreaks({cmd:\'select\'})"');
                                                ?>
                                            </td>
                                            <td class="main" width="20" align="right">
                                                <img src="images/template/img_new.gif" alt="<?php  echo IMAGE_ADD;?>" title="<?php  echo IMAGE_ADD;?>"  style="cursor:pointer;cursor:hand;" onClick="javascript:doPriceBreaks({cmd:'addPrice'});" onMouseOver="javascript:doImageHover(this,'template/img_new_hover.gif');" id="img_add" onMouseOut="javascript:doImageHover(this,'template/img_new.gif');" />
                                                <img src="images/template/img_edit.gif" alt="<?php  echo IMAGE_UPDATE;?>" title="<?php  echo IMAGE_UPDATE;?>" style="cursor:pointer;cursor:hand;" onClick="javascript:doPriceBreaks({cmd:'updatePrice'});" onMouseOver="javascript:doImageHover(this,'template/img_edit_hover.gif');" onMouseOut="javascript:doImageHover(this,'template/img_edit.gif');" />
                                                <img src="images/template/img_trash.gif" alt="<?php  echo IMAGE_DELETE;?>" title="<?php  echo IMAGE_DELETE;?>" style="cursor:pointer;cursor:hand;" onClick="javascript:doPriceBreaks({cmd:'deletePrice'});" onMouseOver="javascript:doImageHover(this,'template/img_trash_hover.gif');" onMouseOut="javascript:doImageHover(this,'template/img_trash.gif');" />
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                              </td>
                            </tr>
                          </table>
                        </td>
                    </tr>
                    <tr id="productPanelATTRIBUTESview" style="display:none">
                        <td class="main">
                           <div style="display:none;"> <?php echo tep_draw_checkbox_field("is_attributes",'Y',($product->is_attributes=='Y'?false:false),'',"onClick='javascript:toggleView({id:\"attributesView\",prop:\"display\"}); nonEditable();'") .'&nbsp;'. TEXT_IS_ATTRIBUTES;?></div>

                            <div id="attributesView" <?php echo ($product->is_attributes=='Y'?'':$display_mode_html);?>>

                            </div>
                        </td>
                    </tr>
                    <?php
                    //Stock Extension starts here...................
                    ?>
                    <tr id="productPanelSTOCKview" style="display:none">
                        <td>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="padding-bottom:15px;">
                                <tr>
                                    <td class="main">
                                        <?php 
										
										echo '<div class="title">' . TEXT_MASTER_QUANTITY .'&nbsp;&nbsp;'.tep_draw_input_field('master_quantity',(tep_not_null($product->master_quantity)?$product->master_quantity:1),'onKeyPress="return keyRestrict(event);"
										 maxlength=4') .'&nbsp;<br></div><div class="title">' . TEXT_PRODUCTS_QUANTITY .'&nbsp;&nbsp;'.tep_draw_input_field('products_quantity',(tep_not_null($product->products_quantity)?$product->products_quantity:1),'onKeyPress="return keyRestrict(event);" maxlength=4') .'&nbsp;<br><br>&nbsp;'. TEXT_PRODUCTS_SKU .'&nbsp;&nbsp;'.tep_draw_input_field('products_sku',$product->products_sku,'size=20  maxlength=' . (SKU_LENGTH=='false'?'20':SKU_COUNT)).'&nbsp;<br><br>&nbsp;'. TEXT_PRODUCTS_WEIGHT.'&nbsp;&nbsp;'.tep_draw_input_field('products_weight',(SHOP_WEIGHT_UNIT=="OZ"?floor($product->products_weight) . "":$product->products_weight),'maxlength="6"') . "&nbsp;" . tep_get_unit_name(). '</div>' ;
                                        ?>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <tr id="productPanelIMAGEview" style="display:none">
                        <td width="50%" class="main">
						<!--<h2>Products Image 1 ONLY!</h2>-->
                            <?php
                            for ($icnt=1;$icnt<=1;$icnt++){
                                $image_name=$product->{"products_image_" . $icnt};
                                echo '<div class="title">' .sprintf(TEXT_PRODUCTS_IMAGE,$icnt) . '</div>';
                                echo "<div id=\"products_image_{$icnt}_file_container\">" . tep_draw_file_field('products_image_' . $icnt ."_file") . $image_name . '</div>';
                                echo tep_draw_hidden_field('products_image_' . $icnt,$image_name);
                            }
                            ?>
                        </td>
                        <td class="main">
                            <?php
                            for ($icnt=1;$icnt<=1;$icnt++){
                                $title_name=$product->{"products_title_" . $icnt};
                                echo '<div class="title">' .sprintf(TEXT_PRODUCTS_TITLE,$icnt) . '</div>';
                                echo tep_draw_input_field('products_title_' . $icnt,$title_name,'max_length="100"');
                            }
                            ?>
                        </td>

                    </tr>
                </table>
            </form>
        </td>
    </tr>
</table>
</td>
</tr>
<tr>
    <td class="main" id="prd<?php echo $product_id;?>message"></td>
</tr>
</table>
            <?php
}
 }//end class
    function getCategoriesListTemplate(){
        ob_start();
        ?>
<tr id="cat##CAT_ID##row" style="display:true;">
    <td style="padding-left:##PAD_LEFT##px">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" id="cat##CAT_ID##" class="boxLevel1" onMouseOver="javascript:doMouseOverOut([{callFunc:changeItemRow,params:{element:this,'className':'boxLevel1','changeStyle':'Hover'}}]);" onMouseOut="javascript:doMouseOverOut([{callFunc:changeItemRow,params:{element:this,'className':'boxLevel1'}}]);">
            <tr>
                <td class="head" valign="middle" height="25px">
                    <table border="0" cellpadding="2" cellspacing="0" width="100%">
                        <tr>
                            <td width="20" align="center" style="cursor:pointer;cursor:hand" onClick="javascript:doDisplayAction({'id':##CAT_ID##,'get':'##ROW_CLICK_GET##','result':doDisplayResult,'type':'cat','params':'cID=##CAT_ID##','style':'boxLevel1'});" id="cat##CAT_ID##bullet">##BULLET_IMAGE##</td>
                            <td width="31" align="center" class="boxRowMenu">

                            </td>
                            <td class="main" onClick="javascript:doDisplayAction({'id':##CAT_ID##,'get':'##ROW_CLICK_GET##','result':doDisplayResult,'type':'cat','params':'cID=##CAT_ID##','style':'boxLevel1'});" style="cursor:pointer;cursor:hand" id="cat##CAT_ID##title">
							<?php if(HIDE_ROW_CATS=='yes')
							{
								echo "<span class='##RED##'>##CAT_NAME##   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(##CAT_VENUE##)</span>";
							}else
							{
								//echo "<span class='##RED##'>##CAT_NAME## ##CAT_DATE_ID##</span>";
								//echo "<span class='##RED##'>##CAT_NAME##&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;##CAT_DATE## ##CAT_TIME##</span>";
								echo "<span class='##RED##'>##CAT_NAME## ##CAT_DATE## ##CAT_CATEGORIES_QUANTITY_REMAINING##</span>";
							}
                            ?>    
                            </td>
                            <td align="right" id="cat##CAT_ID##menu" class="boxRowMenu">
                                <span id="cat##CAT_ID##mnormal" style="##FIRST_MENU_DISPLAY##">
                                    <a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':##CAT_ID##,'get':'CategoryPrint','result':doDisplayResult,'style':'boxLevel1','type':'cat','params':'cID=##CAT_ID##'});"><img src="##IMAGE_PATH##template/blue_print.gif" title="Create PDF (Print All Tickets)"/></a>&nbsp;&nbsp;&nbsp;
                                    <a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':##CAT_ID##,'get':'CategoryEdit','result':doDisplayResult,'style':'boxLevel1','type':'cat','params':'cID=##CAT_ID##'});"><img src="##IMAGE_PATH##template/edit_blue.gif" title="Edit"/></a>
                                    <img src="##IMAGE_PATH##template/img_bar.gif"/>
                                    <a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':##CAT_ID##,'get':'CategoryDeleteDisplay','result':doDisplayResult,'style':'boxLevel1','type':'cat','params':'cID=##CAT_ID##'});"><img src="##IMAGE_PATH##template/delete_blue.gif" title="Delete"/></a>
                                    <img src="##IMAGE_PATH##template/img_bar.gif"/>
									<!--MOVE-->
                                    <a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':##CAT_ID##,'get':'CategoryMoveDisplay','result':doDisplayResult,'style':'boxLevel1','type':'cat','params':'cID=##CAT_ID##'});"><img src="##IMAGE_PATH##template/img_move.gif" title="Move"/></a>
									<!--CLONE-->
                                    <a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':##CAT_ID##,'get':'CategoryCloneDisplay','result':doDisplayResult,'style':'boxLevel1','type':'cat','params':'cID=##CAT_ID##'});"><img src="##IMAGE_PATH##template/img_attrib.gif" title="Clone"/></a>                                    
                                    
									<?php

									?>
									<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':##CAT_ID##,'get':'RowStatusDisplay','result':doDisplayResult,'style':'boxLevel1','type':'cat','params':'cID=##CAT_ID##'});"><img src="##IMAGE_PATH##template/table_selection_row.png" title="Change"/></a>
									<?php

									?>
                                </span>
								<span id="cat##CAT_ID##mupdate" style="display:none">
                                    <a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##CAT_ID##,'get':'CategoryUpdate','imgUpdate':true,'type':'cat','style':'boxLevel1','validate':categoryValidate,'uptForm':'catSubmit','result':##UPDATE_RESULT##,'get':'CategoryUpdate',message:page.template['UPDATE_IMAGE'],message1:page.template['UPDATE_DATA']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
                                    <img src="##IMAGE_PATH##template/img_bar.gif"/>
                                    <a href="javascript:void(0)" onClick="javascript:return doCancelAction({'id':##CAT_ID##,'get':'CategoryEdit','type':'cat','style':'boxLevel1'});"><img src="##IMAGE_PATH##template/img_close_blue.gif" title="Cancel"/></a>
                                </span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>
<?php
$contents=ob_get_contents();
ob_end_clean();
return $contents;
}
function getCategoryInfoTemplate()
{
ob_start();
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td valign="top" width="##CAT_IMAGE_WIDTH##"><div style="width:100%;height:100px;overflow:hidden">##CAT_IMAGE##</div></td>
        <td width="10">&nbsp;</td>
        <td valign="top" width="500">
            <table class="main" border="0" cellpadding="1" cellspacing="0" width="100%">
                ##CAT_DATE_ADDED##
                ##CAT_DATE_MODIFIED##
				<td class="main">Plan ID:##CAT_PLAN_ID##</td>
				<td class="main">Design ID:##CAT_MAN_ID##</td>
				##CAT_CATEGORIES_QUANTITY##
				##CAT_CATEGORIES_QUANTITY_REMAINING##
            </table>
        </td>
        <td valign="top" class="smallText" align="justify">
            <div style="width:500px;height:100px;overflow:auto">
                ##CAT_DESCRIPTION##
            </div>
        </td>
</table>
<?php
$contents=ob_get_contents();
ob_end_clean();
return $contents;
}
function getProductsListTemplate()
{
ob_start();
getTemplateRowTop();
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%" id="prd##PRD_ID##">
    <tr>
        <td>
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td width="30" id="prd##PRD_ID##sort" class="boxRowMenu">
                        <span style="##FIRST_MENU_DISPLAY##">
                            <a href="javascript:void(0);" onClick="javascript:doSimpleAction({'id':##PRD_ID##,'get':'ProductSort','result':doSimpleResult,mode:'up',type:'prd',params:'pID=##PRD_ID##&mode=up&cID=##CAT_ID##',validate:sortProductValidate,'style':'boxRow','message':'##UPDATING_ORDER##'})"><img src="##IMAGE_PATH##template/img_arrow_up.gif" title="Up" align="absmiddle"/></a>
                            <a href="javascript:void(0);" onClick="javascript:doSimpleAction({'id':##PRD_ID##,'get':'ProductSort','result':doSimpleResult,mode:'down',type:'prd',params:'pID=##PRD_ID##&mode=down&cID=##CAT_ID##',validate:sortProductValidate,'style':'boxRow','message':'##UPDATING_ORDER##'})"><img src="##IMAGE_PATH##template/img_arrow_down.gif" title="Down" align="absmiddle"/></a>
                        </span>
                    </td>
                    <td width="15" id="prd##PRD_ID##bullet">##PRD_STATUS##</td>
                    <td class="main" onClick="javascript:doDisplayAction({'id':##PRD_ID##,'get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'prd','params':'pID=##PRD_ID##&cID=##CAT_ID##'});" id="prd##PRD_ID##title">##PRD_NAME##<span title="##PRD_PRICE##" class="cube colorband" style="background-color:##CLR_BAND##"></span></td>
                    <td id="prd##PRD_ID##menu" align="right" class="boxRowMenu">
                        <span id="prd##PRD_ID##mnormal" style="##EDIT_MENU_DISPLAY##">
                            <a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':##PRD_ID##,'get':'ProductEdit','result':doDisplyResult,'style':'boxRow','type':'prd','params':'pID=##PRD_ID##&cID=##CAT_ID##','backupMenu':true})"><img src="##IMAGE_PATH##template/edit_blue.gif" title="Edit"/></a>
                            <img src="##IMAGE_PATH##template/img_bar.gif"/>

                            <a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':##PRD_ID##,'get':'ProductDeleteDisplay','result':doDisplayResult,'style':'boxRow','type':'prd','params':'pID=##PRD_ID##&cID=##CAT_ID####FLAG_ONE_RECORD##'});"><img src="##IMAGE_PATH##template/delete_blue.gif" title="Delete"/></a>
                            <img src="##IMAGE_PATH##template/img_bar.gif"/>
                            <span style="##SEARCH_NEEDED##">
                                <a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':##PRD_ID##,'get':'ProductMoveDisplay','result':doDisplayResult,'style':'boxRow','type':'prd','params':'pID=##PRD_ID##&cID=##CAT_ID##'});"><img src="##IMAGE_PATH##template/img_move.gif" title="Move"/></a>
                                <img src="##IMAGE_PATH##template/img_bar.gif"/>
                                <a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':##PRD_ID##,'get':'ProductCopyDisplay','result':doDisplayResult,'style':'boxRow','type':'prd','params':'pID=##PRD_ID##&cID=##CAT_ID##'});"><img src="##IMAGE_PATH##template/copy_blue.gif" title="Copy"/></a>
                                <img src="##IMAGE_PATH##template/img_bar.gif"/>
                            </span>
                        </span>
                        <span id="prd##PRD_ID##mupdate" style="display:none">
                            <a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##PRD_ID##,'get':'ProductUpdate','imgUpdate':true,'type':'prd','style':'boxRow','validate':productValidate,'uptForm':'productSubmit','customUpdate':doProductUpdate,'result':##UPDATE_RESULT##,'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
                            <img src="##IMAGE_PATH##template/img_bar.gif"/>
                            <a href="javascript:void(0)" onClick="javascript:return doCancelAction({'id':##PRD_ID##,'get':'ProductEdit','type':'prd','style':'boxRow',extraFunc:textEditorRemove});"><img src="##IMAGE_PATH##template/img_close_blue.gif" title="Cancel"/></a>
                        </span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<?php
getTemplateRowBottom();
$contents=ob_get_contents();
ob_end_clean();
return $contents;
}
function getProductInfoTemplate()
{
ob_start();
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td valign="top" width="##PRD_IMAGE_WIDTH##"><div style="width:100%;height:100px;overflow:hidden">##PRD_IMAGE##</div></td>
        <td width="10">&nbsp;</td>
        <td valign="top" width="250">
            <table border="0" cellpadding="3" cellspacing="0" width="100%">
                ##PRD_DATE_ADDED##
                ##PRD_DATE_MODIFIED##
                <tr>
                    <td class="main">Products ID: ##PRD_ID##</td>
                </tr>
				<tr>
                    <td class="main">##MAS_QTY##</td>
                </tr>
				<tr>
                    <td class="main">##PRD_QTY##</td>
                </tr>
                <tr>
                    <td class="main">##PRD_PRICE##</td>
                </tr>
				<tr>
                    <td class="main">##PRD_EXP##</td>
                </tr>
            </table>
        </td>
        <td valign="top" class="smallText" align="justify">
            <div style="width:500px;height:100px;overflow:auto">
                ##PRD_DESCRIPTION##
            </div>
        </td>
    </tr>
    <tr height="10">
        <td>&nbsp;</td>
    </tr>
</table>
<?php
$contents=ob_get_contents();
ob_end_clean();
return $contents;
}
?>
