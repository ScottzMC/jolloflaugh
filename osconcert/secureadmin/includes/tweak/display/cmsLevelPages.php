<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare

    Released under the GNU General Public License

*/
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
class cmsLevelPages{
    var $pagination;
    var $splitResult;
    function __construct() {
        $this->pagination=false;
        $this->splitResult=false;
    }
    function doSort(){
        global $FREQUEST,$jsData,$FSESSION;
        $mode=$FREQUEST->getvalue('mode','','A');
        $sort='asc';
        if ($mode=="D") $sort="desc";
        $page_query=tep_db_query("SELECT m.page_id from " . TABLE_MAINPAGE . " m, " . TABLE_MAINPAGE_DESCRIPTIONS . " md where m.page_id=md.page_id order by md.page_name " . $sort);
        $order=1;
        while($page_result=tep_db_fetch_array($page_query)){
            tep_db_query("UPDATE " . TABLE_MAINPAGE . " set sort_order=$order where page_id=" . $page_result["page_id"]);
            $order++;
        }
        $jsData->VARS["NUclearType"]=array("supage","mpage");
        $this->doMainpage();
    }
    function doSearchSubpage(){
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
            <table border="0" cellpadding="2" cellspacing="0" width="100%" id="supageTable">
                <?php
                $found=$this->doSubpageList(" and (mpd.page_name like'%$search_db%' or mpd.description like '%$search_db%')",0,$search);
                if (!$found){
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
            <a href="javascript:void(0);" onClick="javascript:doSubpageSearch('reset');"><?php echo tep_image_button('button_reset.gif',IMAGE_RESET);?></a>
        </td>
    </tr>
</table>
<?php
$jsData->VARS["NUclearType"]=array("supage","mpage");
}
function doMainpage($mpage_id=0){
global $jsData;
?>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <Td class="main"><b><?php echo HEADING_FIRST_LEVEL; ?></b></Td>
    </tr>
    <tr><td><?php echo tep_draw_separator('pixel_trans.gif','',10); ?></td></tr>
    <tr>
        <td><table border="0" cellpadding="2" cellspacing="0" width="100%" id="mpageTable">
                <?php	$template=getMainpageListTemplate();
                $rep_array=array("PAD_LEFT"=>0,
                                            "MPAGE_ID"=>-1,
                                            "CAT_NAME"=>TEXT_NEW_PAGE,
                                            "BULLET_IMAGE"=>'',
                                            "CAT_STATUS"=>tep_image(DIR_WS_IMAGES . 'template/plus_add1.gif'),
                                            "IMAGE_PATH"=>DIR_WS_IMAGES,
                                            "UPDATE_RESULT"=>'doTotalResult',
                                            "ROW_CLICK_GET"=>'MainpageEdit',
                                            "FIRST_MENU_DISPLAY"=>'display:none'
                );
                echo mergeTemplate($rep_array,$template);
                $this->doMainpageList();
                ?>
        </table></td>
    </tr>
</table>
<?php 
if (!isset($jsData->VARS["page"])){
    $jsData->VARS["NUclearType"]=array("supage","mpage");
}
}
function doMainpageList($parent_id=0,$level=1) {
global $FSESSION,$jsData,$FREQUEST;


if($FREQUEST->getvalue("mode"))
{
$mode=$FREQUEST->getvalue("mode","string","down");
$mpage_id=$FREQUEST->getvalue("cID","int",0);
$parent_id=$FREQUEST->getvalue("parent","int",0);

$category_query=tep_db_query("SELECT sort_order from " . TABLE_MAINPAGE . " where page_id='" . $mpage_id . "' and parent_id='" . $parent_id . "'");
if (tep_db_num_rows($category_query)<=0){
    echo "Err:"  . TEXT_CATEGORY_NOT_FOUND;
    return;
}
$category_result=tep_db_fetch_array($category_query);
$current_order=(int)$category_result["sort_order"];

if ($mode=="up") {
    $category_sort_sql="select sort_order, page_id from main_pages where parent_id='" . $parent_id . "' and sort_order<$current_order and page_status = 1 order by sort_order desc limit 1";
} else {
    $category_sort_sql="select sort_order, page_id from main_pages where parent_id='" . $parent_id . "' and sort_order>$current_order and page_status = 1 order by sort_order limit 1";
}
$category_sort_query=tep_db_query($category_sort_sql);
if(tep_db_num_rows($category_sort_query)<=0){
    echo "NOTRUNNED";
    return;
}
$categories_result=tep_db_fetch_array($category_sort_query);
$prev_order=$categories_result['sort_order'];
tep_db_query("UPDATE " . TABLE_MAINPAGE . " set sort_order='" . $current_order ."' where page_id='" . (int)$categories_result['page_id'] . "'");
tep_db_query("UPDATE " . TABLE_MAINPAGE . " set sort_order='" . $prev_order . "' where page_id='" . $mpage_id . "'");

//$jsData->VARS['moveRows']=array('mode'=>$mode,'destID'=>$categories_result['page_id']);
}

 $main_page_sql="select mp.page_id,mp.page_status,mpd.page_name,mp.sort_order from ".TABLE_MAINPAGE." mp, ".TABLE_MAINPAGE_DESCRIPTIONS." mpd where mp.page_id=mpd.page_id and mpd.language_id='" . (int)$FSESSION->languages_id . "' and mp.parent_id='" . tep_db_input($parent_id) . "' order by mp.page_status desc,mp.sort_order";
$main_page_query = tep_db_query($main_page_sql);
if (tep_db_num_rows($main_page_query)<=0) return 0;
$template=getMainpageListTemplate();

$cnt=0;
$pos=0;
while($mainpage_result=tep_db_fetch_array($main_page_query)){
      $cur_order = (int)$mainpage_result["sort_order"];
       $sql_next = "select * from ".TABLE_MAINPAGE." mp, ".TABLE_MAINPAGE_DESCRIPTIONS." mpd where mp.page_id=mpd.page_id and mpd.language_id='" . (int)$FSESSION->languages_id . "' and mp.parent_id='" . tep_db_input($parent_id) . "' and mp.sort_order>$cur_order and mp.page_status = '1'  order by mp.page_status DESC,mp.sort_order limit 1";
       $info_field_next=tep_db_query($sql_next);

       if(tep_db_num_rows($info_field_next)>0)
            $lastactive = 1;
        else
            $lastactive = 0;

      $sql_before = "select * from ".TABLE_MAINPAGE." mp, ".TABLE_MAINPAGE_DESCRIPTIONS." mpd where mp.page_id=mpd.page_id and mpd.language_id='" . (int)$FSESSION->languages_id . "' and mp.parent_id='" . tep_db_input($parent_id) . "' and mp.sort_order<$cur_order and mp.page_status = '1'  order by mp.page_status DESC,mp.sort_order desc limit 1";
       $info_field_before=tep_db_query($sql_before);

       if(tep_db_num_rows($info_field_before)>0)
            $firstactive = 1;
        else
            $firstactive = 0;
    $rep_array=array("PAD_LEFT"=>$level*10,
                    "MPAGE_ID"=>$mainpage_result["page_id"],
                    "CAT_PARENT"=>$parent_id,
                    "CAT_NAME"=>$mainpage_result["page_name"],
                    "UPDATING_ORDER"=>TEXT_UPDATING_ORDER,
                    "BULLET_IMAGE"=>tep_image(DIR_WS_IMAGES . 'layout/bullet_close.gif'),
                    "CAT_STATUS"=>'<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $mainpage_result["page_id"] .",get:'MainpageChangeStatus',result:doSimpleResult,params:'cID=". $mainpage_result["page_id"] . "&status=" .($mainpage_result["page_status"]==1?0:1) . "','message':'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/' . ($mainpage_result["page_status"]==1?'icon_active.gif':'icon_inactive.gif')) . '</a>',
                    "IMAGE_PATH"=>DIR_WS_IMAGES,
                    "IMGUP"=>($cnt ==0 && $firstactive ==0)?'':'<img src="'.DIR_WS_IMAGES.'/template/img_arrow_up.gif"  title="Up" align="absmiddle" />',
                    "IMGDOWN"=>($lastactive == 0)?'':'<img src="'.DIR_WS_IMAGES.'/template/img_arrow_down.gif" title="Down"/>',
                    "UPDATE_RESULT"=>'doDisplayResult',
                    "ROW_CLICK_GET"=>'MainpageInfoAndSubpage',
                    "FIRST_MENU_DISPLAY"=>($mainpage_result["page_status"] == 1)?'display:normal':'display:none',
                    "CAT_LEVEL"=>$level
    );
    echo mergeTemplate($rep_array,$template);
    //$temp_cnt=$this->doMainpageList($mainpage_result["page_id"],$level+1);
    //if ($temp_cnt>0) $cnt+=$temp_cnt;
    //else
    $cnt++;
    if (isset($jsData->VARS["page"])){
        $jsData->VARS["page"]["treeList"][$mainpage_result["page_id"]]["pos"]=$pos;
        $jsData->VARS["page"]["treeList"][$mainpage_result["page_id"]]["parent"]=$parent_id;
        $jsData->VARS["page"]["treeList"][$mainpage_result["page_id"]]["level"]=$level;
    } else {
        $jsData->VARS["storePage"]["treeList"][$mainpage_result["page_id"]]["pos"]=$pos;
        $jsData->VARS["storePage"]["treeList"][$mainpage_result["page_id"]]["parent"]=$parent_id;
        $jsData->VARS["storePage"]["treeList"][$mainpage_result["page_id"]]["level"]=$level;
    }
    $pos++;
} 
if (isset($jsData->VARS["page"])){
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
function doMainpageInfoAndSubpage($mpage_id=0) {
global $FREQUEST,$FSESSION;
if ($mpage_id==0) $mpage_id=$FREQUEST->getvalue("cID","int",0);
?>
<table border="0" cellpadding="1" cellspacing="0" width="100%">
    <tr>
        <td valign="top" style="border-top:solid 1px #C6CEEA;height:5px" class="smallText">&nbsp;</td>
    </tr>
    <tr>
        <td valign="top" class="mainpageInfo">
            <?php
            echo $this->doMainpageInfo($mpage_id);
            ?>
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr height="20">
                    <td valign="top">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                                <td class="bulletTitle" valign="middle">
                                    <?php echo tep_image(DIR_WS_IMAGES . 'layout/bullet1.gif','','','','align=absmiddle') . '&nbsp;' . TITLE_SUB_PAGES;?>
                                </td>
                                <td class="main" width="100">
                                    <?php
                                    if ($this->pagination) {
                                        for ($icnt=MAX_DISPLAY_SEARCH_RESULTS,$n=MAX_DISPLAY_SEARCH_RESULTS*5;$icnt<=$n;$icnt+=MAX_DISPLAY_SEARCH_RESULTS){
                                            $pg_rows[]=array('id'=>$icnt,'text'=>$icnt);
                                        }
                                        $pg_rows[]=array('id'=>-1,'text'=>TEXT_ALL);
                                        echo TEXT_SHOW . tep_draw_pull_down_menu('totalRows',$pg_rows,$FSESSION->displayRowsCnt,'onChange="javascript:doPageAction({id:'. $mpage_id . ',type:\'supage\',get:\'MainpageSubpage\',closePrev:true,pageNav:true,result:doTotalResult,params:\'cID='. $mpage_id .'&rowsCnt=\'+this.value,message:page.template[\'INFO_LOADING_SUBPAGES\']});"');
                                    }
                                    ?>
                                </td>
                                <td width="20"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-left:10px;">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="boxLevel2">
                        <tr height="4">
                            <td class="topleft"></td>
                            <td></td>
                            <td class="topright"></td>
                        </tr>
                        <tr>
                            <td width="4"></td>
                            <td style="padding:5px">
                                <div id="supagetotalContentResult">
                                    <?php
                                    echo $this->doMainpageSubpage($mpage_id);
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
function doMainpageInfo($mpage_id){
global $FSESSION,$jsData;
$main_page_query=tep_db_query("select mp.page_id,mp.page_status,mpd.description,mpd.page_name from ".TABLE_MAINPAGE." mp, ".TABLE_MAINPAGE_DESCRIPTIONS." mpd where mp.page_id=mpd.page_id and mpd.language_id='" . (int)$FSESSION->languages_id . "' and mp.page_id='" . tep_db_input($mpage_id) . "' order by mp.sort_order");
if (tep_db_num_rows($main_page_query)>0){
    $mainpage_result=tep_db_fetch_array($main_page_query);
    $template=getMainpageInfoTemplate();
    $rep_array=array("MAINPAGE_DESCRIPTION"=>$mainpage_result["description"],
                        "UPDATE_RESULT"=>'doDisplayResult'
    );
    echo mergeTemplate($rep_array,$template);
}
$jsData->VARS["updateMenu"]=",normal,";
}
function doMainpageEdit() {	
global $FREQUEST,$LANGUAGES,$CAT_TREE,$jsData;
$languages=&$LANGUAGES;
$mpage_id=$FREQUEST->getvalue('cID','int',0);
$mode=$FREQUEST->getvalue('cID','string','new');
$jsData->VARS['doFunc']=array('type'=>'mpage','data'=>'doMainpageEditor');
if ($mpage_id<=0) $mode="new";
if ($mpage_id>0){
    $mainpage_sql="select mp.page_id,mp.parent_id,mp.page_status,mpd.page_name,mpd.description,mpd.language_id from ".TABLE_MAINPAGE." mp, ".TABLE_MAINPAGE_DESCRIPTIONS." mpd where mp.page_id=mpd.page_id and mp.page_id='" . tep_db_input($mpage_id) . "' order by mpd.language_id";
    $mainpage_query=tep_db_query($mainpage_sql);
    $mpageInfo=array();
    $langInfo=array();
    while($mainpage_result=tep_db_fetch_array($mainpage_query)){
        $langInfo[$mainpage_result["language_id"]]=array("name"=>$mainpage_result["page_name"],"description"=>$mainpage_result["description"]);
        if (count($mpageInfo)==0) {
            $mpageInfo=$mainpage_result;
            unset($mpageInfo["page_name"]);
            unset($mpageInfo["description"]);
        }
    }
}
?>
<form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="mpageSubmit" id="mpageSubmit">
    <table border="0" cellpadding="4" cellspacing="0" width="100%" class="mainpageEdit" style="padding-left:20px">
    <tr>
        <td width="50%" valign="top">
            <table border="0" cellpadding="2" cellspacing="0" width="100%">
                <?php
                for ($icnt=0,$n=count($languages); $icnt<$n; $icnt++) {
                    ?>
                <tr>
                    <td class="main" valign="top" width="130"><?php if ($icnt == 0) echo TEXT_PAGE_NAME ; ?></td>
                    <td class="main" valign="top"><?php  echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$icnt]['directory'] . '/images/' . $languages[$icnt]['image'], $languages[$icnt]['name']) . '&nbsp;' . tep_draw_input_field('mainpage_name[' . $languages[$icnt]['id'] . ']', isset($langInfo[$languages[$icnt]['id']]["name"])?stripslashes($langInfo[$languages[$icnt]['id']]["name"]):'','maxlength="32" id="mainpage_name[' . $languages[$icnt]['id'] . ']"'); ?></td>
                </tr>
                <?php } ?>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <div style='width:100%;height:300px;overflow:auto;'>
                <table border="0" cellspacing="0" cellpadding="3">
                    <?php  for ($icnt=0,$count=count($languages); $icnt<$n; $icnt++) { ?>
                    <tr>
                        <td class="main" valign="top"><?php if ($icnt == 0) echo TEXT_PAGE_DESCRIPTION; ?></td>
                        <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$icnt]['directory'] . '/images/' . $languages[$icnt]['image'], $languages[$icnt]['name'],'','','align="top"'); ?>&nbsp;
                          
                        <?php echo tep_draw_textarea_field('mainpage_description[' . $languages[$icnt]['id'] . ']', 'soft', '120', '35', isset($langInfo[$languages[$icnt]['id']]['description'])? stripslashes($langInfo[$languages[$icnt]['id']]['description']) : '',' id="mainpage_description[' . $languages[$icnt]['id'] . ']"'); ?></td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </td>
    </tr>
    <input type="hidden" id="mpage_id" name="mpage_id" value="<?php echo tep_output_string($mpage_id);?>"/>
</form>
<tr>
    <td class="main" id="mpage<?php echo $mpage_id;?>message"></td>
</tr>
</table>
<?php
$jsData->VARS["updateMenu"]=",update,";
}
function doMainpageUpdate(){
global $FREQUEST,$SERVER_DATE,$SERVER_DATE_TIME,$LANGUAGES,$jsData,$FSESSION,$COMMANDS;
$languages=&$LANGUAGES;
$update_array=array();
$mpage_id=$FREQUEST->postvalue('mpage_id','int',0);
$cat_lang=array();

if((int)$mpage_id>0){
    $jsData->VARS['doFunc']=array('type'=>'mpage','data'=>'doUpdateJump');
} else{
    $jsData->VARS['doFunc']=array('type'=>'mpage','data'=>'doAddJump');
}

if ($mpage_id>0){
    $update_array["date_modified"]=$SERVER_DATE;
    tep_db_perform(TABLE_MAINPAGE,$update_array,"update","page_id="  . $mpage_id);
    $lang_query=tep_db_query("SELECT language_id from " . TABLE_MAINPAGE_DESCRIPTIONS . " where page_id=" . $mpage_id);
    while($lang_result=tep_db_fetch_array($lang_query)) $cat_lang[$lang_result["language_id"]]=1;
} else {
    $sort_query=tep_db_query("select max(sort_order) as sort_order from ".TABLE_MAINPAGE);
    $sort_order=1;
    if (tep_db_num_rows($sort_query)>0) {
        $tmp_result=tep_db_fetch_array($sort_query);
        $sort_order=$tmp_result["sort_order"]+1;
    }
    //	$update_array["parent_id"]=$FREQUEST->postvalue('parent_id','int',0);
    $update_array["sort_order"]=$sort_order;
    $update_array["date_created"]=$SERVER_DATE_TIME;
    $update_array["page_status"]=1;
    tep_db_perform(TABLE_MAINPAGE,$update_array);
    $insert_id=tep_db_insert_id();
}
$mainpage_name=$FREQUEST->getRefValue('mainpage_name','POST');
$mainpage_desc=$FREQUEST->getRefValue('mainpage_description','POST');
for ($icnt=0,$n=count($languages);$icnt<$n;$icnt++){
    $lang_id=$languages[$icnt]['id'];
    $update_array=array("page_name"=>tep_db_prepare_input($mainpage_name[$lang_id]),
                            "description"=>tep_db_prepare_input($mainpage_desc[$lang_id])
    );
    if ($mpage_id>0) {
        if(isset($cat_lang[$languages[$icnt]['id']]))
        tep_db_perform(TABLE_MAINPAGE_DESCRIPTIONS,$update_array,"update","page_id=" . $mpage_id . " and language_id=" . $lang_id);
        else {
            $update_array["language_id"]=$lang_id;
            $update_array["page_id"]=$mpage_id;
            tep_db_perform(TABLE_MAINPAGE_DESCRIPTIONS,$update_array);
        }

    } else {
        $update_array["language_id"]=$lang_id;
        $update_array["page_id"]=$insert_id;
        tep_db_perform(TABLE_MAINPAGE_DESCRIPTIONS,$update_array);
    }
}?>
    <input type="hidden" name="last_jump" id="last_jump" value="<?php echo (($mpage_id!='')?($mpage_id):''); ?>">
	<input type="hidden" name="newly_updated" id="newly_updated" value="<?php echo $mainpage_name[$FSESSION->languages_id] ."#".$insert_id; ?>">
<?php if ($mpage_id<=0) {
    $mpage_id=$insert_id;
    $jsData->VARS["NUclearType"]=array("mpage");
    $this->doMainpage($insert_id);
} else {
    $cms_query=tep_db_query("select page_status from " . TABLE_MAINPAGE . " where parent_id=0 and page_id='" . $mpage_id . "'");
    $cms_result=tep_db_fetch_array($cms_query);
    if ($cms_result['page_status']==1){
        $result='<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $ID .",get:\'MainpageChangeStatus\',result:doSimpleResult,params:\'cID=". $ID . "&status=0\'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_active.gif') . '</a>';
    } else {
        $result='<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $ID .",get:\'MainpageChangeStatus\',result:doSimpleResult,params:\'cID=". $ID . "&status=1\'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_inactive.gif') . '</a>';
    }
    $jsData->VARS["replace"]=array("mpage". $mpage_id ."title"=>$mainpage_name[$FSESSION->languages_id],"mpage". $mpage_id ."bullet"=>$result);
    $this->doMainpageInfoAndSubpage($mpage_id);
    $jsData->VARS["prevAction"]=array('id'=>$mpage_id,'get'=>'MainpageInfoAndSubpage','type'=>'mpage','style'=>'boxLevel1');
    $jsData->VARS["NUclearType"]=array("mpage");
    $jsData->VARS["updateMenu"]=",normal,";
}
$jsData->VARS["page"]['editorLoaded']='false';
}
function doMainpageSubpage($mpage_id=0){
global $FREQUEST;
if ($mpage_id==0)
$mpage_id=$FREQUEST->getValue('cID','int',0);
$template=getSubpageListTemplate();
$rep_array=array(	"SUPAGE_ID"=>-1,
                        "MPAGE_ID"=>$mpage_id,
                        "CAT_PARENT"=>0,
                        "TYPE"=>"supage",
                        "ID"=>-1,
                        "PRD_NAME"=>TEXT_NEW_SUBPAGE,
                        "IMAGE_PATH"=>DIR_WS_IMAGES,
                        "SEARCH_NEEDED"=>"display:normal",
                        "UPDATING_ORDER"=>TEXT_UPDATING_ORDER,
                        "PRD_STATUS"=>tep_image(DIR_WS_IMAGES . 'template/plus_add2.gif'),
                        "UPDATE_RESULT"=>'doTotalResult',
                        "ROW_CLICK_GET"=>'SubpageEdit',
                        "FIRST_MENU_DISPLAY"=>"display:none",
                        "EDIT_MENU_DISPLAY"=>"display:none",
                        "FLAG_ONE_RECORD"=>''
);
?>
<div class="main" id="supage-1message"></div>
<table border="0" width="100%" height="100%" id="supageTable">
    <?php 	echo mergeTemplate($rep_array,$template);
    $this->doSubpageList(" and mp.parent_id=$mpage_id",$mpage_id);
    ?>
</table>
<?php
if ($this->splitResult && $this->splitResult->queryRows>0){ ?>
<table border="0" width="100%" height="100%">
    <?php echo $this->splitResult->pgLinksCombo(); ?>
</table><?php 
}
}
function doMainpageDeleteDisplay(){
global $FREQUEST,$FSESSION;
$mpage_id=$FREQUEST->getvalue('cID','int',0);
$last_flag=$FREQUEST->getvalue('lflag','int',0);

$delete_message='';
if ($mpage_id>0){
    $subpage_query=tep_db_query("Select count(*) as rec_count from " . TABLE_MAINPAGE . " where parent_id='" . $mpage_id . "' group by parent_id");
    $subpage_result=tep_db_fetch_array($subpage_query);
    if($subpage_result['rec_count']>0) {
        $delete_message.=sprintf(TEXT_DELETE_WARNING_SUBPAGE, $subpage_result['rec_count']) . '<br><br>';
    } else {
        $delete_message=TEXT_DELETE_PAGE_INTRO . '<p>' . '<a href="javascript:void(0);" onClick="javascript:doSimpleAction({id:' . $mpage_id .",type:'mpage','refresh':'1',get:'MainpageDelete',result:doSimpleResult,message:'" . tep_output_string(TEXT_DELETING_PAGES) . "',params:'cID=" . $mpage_id . "&lflag=" . $last_flag . "'})\">" . tep_image_button_delete('button_delete.gif') . '</a>&nbsp;';
    }
    $delete_message.='<a href="javascript:void(0);" onClick="javascript:doCancelAction({id:' . $mpage_id .",type:'mpage',get:'closeRow','style':'boxLevel1'})\">" . tep_image_button_cancel('button_cancel.gif') . '</a>';
}
?>
<table border="0" cellpadding="2" cellspacing="0" align="center" width="100%">
    <tr>
        <td class="main" align="left" id="mpage<?php echo $mpage_id;?>message">
            <?php echo $delete_message;?>
        </td>
    </tr>
    <tr>
        <td><hr/></td>
    </tr>
    <tr>
        <td valign="top" class="mainpageInfo"><?php echo $this->doMainpageInfo($mpage_id);?></td>
    </tr>
</table>
<?php
}
function doMainpageDelete(){
global $FREQUEST,$jsData,$LANGUAGES;
$mpage_id=$FREQUEST->getvalue('cID','int',0);

tep_db_query("Delete from " . TABLE_MAINPAGE . " where page_id='" . $mpage_id . "'");
for ($icnt=0,$n=count($LANGUAGES);$icnt<$n;$icnt++){
    $lang_id=$LANGUAGES[$icnt]['id'];
    tep_db_query("Delete from " . TABLE_MAINPAGE_DESCRIPTIONS . " where page_id='" . $mpage_id . "' and language_id='" . $lang_id . "'");
}$jsData->VARS['doFunc']=array('type'=>'mpage','data'=>'doDeleteJump#&#' . $mpage_id);
$jsData->VARS["deleteRow"]=array("id"=>$mpage_id,"type"=>"mpage");

}
/*function doMainpageMoveDisplay(){
global $FREQUEST,$jsData,$CAT_TREE,$FSESSION;
$mpage_id=$FREQUEST->getvalue('cID','int',0);
?>
<form action="javascript:void(0)" method="post" enctype="application/x-www-form-urlencoded" name="mpageMoveSubmit" id="mpageMoveSubmit">
    <input type="hidden" name="mpage_id" value="<?php echo $mpage_id; ?>"/>
    <table width="100%"  border="0" cellspacing="5" cellpadding="5" style="padding-left:20px;">
        <tr>
            <td class="main" id="mpage<?php echo $mpage_id;?>message"></td>
        </tr>
        <tr>
            <td  class="main" align="left"><?php echo TEXT_INFO_MOVE_TO_INTRO; ?></td>
        </tr>
        <tr>
            <td class="main" align="left"><?php echo tep_draw_pull_down_menu('new_parent_id', tep_get_static_pages_tree('','',$mpage_id), $mpage_id , ' id="new_parent_id"'); ?></td>
        </tr>
        <tr>
            <td class="main"><a href="javascript:void(0);" onclick="javascript:return doUpdateAction({id:<?php echo $mpage_id;?>,type:'mpage',style:'boxLevel1','get':'MainpageMove','result':doTotalResult,uptForm:'mpageMoveSubmit','imgUpdate':false,message:page.template['CAT_MOVING']});"><?php echo tep_image_button('button_move.gif');?></a>&nbsp;<a href="javascript:void(0)" onClick="javascript:return doCancelAction({id:<?php echo $mpage_id;?>,type:'mpage','get':'closeRow',style:'boxLevel1'});"><?php echo tep_image_button('button_cancel.gif');?></a></td>
        </tr>
    </table>
</form>
<?php
}
function doMainpageMove(){
global $FREQUEST,$jsData;
$mpage_id=$FREQUEST->postvalue('mpage_id','int',0);
$new_parent_id=$FREQUEST->postvalue('new_parent_id','int',0);
if ($mpage_id<=0) {
    echo 'Err:' .ERROR_CANNOT_FIND_CATEGORY;
    return;
}
tep_db_query("update " . TABLE_MAINPAGE . " set parent_id = " . $new_parent_id . ", date_modified = now() where page_id = " . $mpage_id);
$this->doMainpage();
}-*/
function doMainpageCopy(){
global $FREQUEST,$jsData,$FSESSION;
$mpage_id=$FREQUEST->postvalue('mpage_id','int',0);
$new_parent_id=$FREQUEST->postvalue('copy_to_mainpage_id','int',0);

if ($mpage_id==0){
    echo 'Err:' . TEXT_INVALID_DATA;
    return;
}

$sort_order=0;
$sort_order_query=tep_db_query("select max(sort_order) as sort_order from " .TABLE_MAINPAGE. " where parent_id='" . tep_db_input($new_parent_id) . "'");
if(tep_db_num_rows($sort_order_query)>0){
    $sort_order_array=tep_db_fetch_array($sort_order_query);
    $sort_order=$sort_order_array['sort_order'];
}

tep_db_query('insert into '.TABLE_MAINPAGE." (parent_id,date_created,page_status) values ('" . tep_db_input($new_parent_id) . "',now(),'1') ");
$insert_page_id=tep_db_insert_id();
$fetch_query=tep_db_query("select page_name,description,language_id from ".TABLE_MAINPAGE_DESCRIPTIONS." where page_id='" . tep_db_input($mpage_id) . "'");
while($fetch_array=tep_db_fetch_array($fetch_query)){
    $page_name=$fetch_array['page_name']; $description=$fetch_array['description']; $language_id=$fetch_array['language_id'];
    tep_db_query('insert into '.TABLE_MAINPAGE_DESCRIPTIONS." (page_id,page_name,description,language_id) values('" . tep_db_input($insert_page_id) . "','" . tep_db_input($page_name) . "','" . tep_db_input($description) . "','" . (int)$language_id . "')");
}
//$this->doMainpageSubpage($mpage_id);
$this->doMainpage();
$jsData->VARS["displayMessage"]=array('text'=>TEXT_PAGE_COPIED_SUCCESS);
}
function doMainpageCopyDisplay(){
global $FSESSION,$jsData,$FREQUEST;
$mpage_id=$FREQUEST->getvalue('cID','int',0);
?>
<form  name="mpageCopySubmit" id="mpageCopySubmit" action="cms_level_pages.php" method="post" enctype="application/x-www-form-urlencoded">
    <input type="hidden" name="mpage_id" value="<?php echo tep_output_string($mpage_id);?>"/>
    <table border="0" cellpadding="4" cellspacing="0" width="100%">
        <tr>
            <td class="main" id="mpage<?php echo $mpage_id;?>message">
            </td>
        </tr>
        <tr>
            <td class="inner_title"><?php echo TEXT_INFO_HEADING_COPY_PAGE;?></td>
        </tr>
        <tr>
            <td class="main"><?php echo TEXT_INFO_COPY_TO_INTRO;?></td>
        </tr>
        <tr>
            <td class="main"><?php echo  tep_draw_pull_down_menu('copy_to_mainpage_id', tep_get_static_pages_tree('','',$mpage_id), $mpage_id);?></td>
        </tr>
        <tr>
            <td class="main">
                <a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $mpage_id;?>,type:'mpage',get:'MainpageCopy',result:doTotalResult,message:'<?php echo tep_output_string(TEXT_PRODUCT_COPYING);?>','uptForm':'mpageCopySubmit','closePrev':true,'imgUpdate':false,params:''})"><?php echo tep_image_button('button_copy.gif');?></a>&nbsp;
                <a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:<?php echo $mpage_id;?>,type:'mpage',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button('button_cancel.gif');?></a>
            </td>
        </tr>
    </table>
</form>
<?php
$jsData->VARS["updateMenu"]="";
}
function doMainpageSort(){
global $FREQUEST,$jsData;
$mode=$FREQUEST->getvalue("mode","string","down");
$mpage_id=$FREQUEST->getvalue("cID","int",0);
$parent_id=$FREQUEST->getvalue("parent","int",0);

$category_query=tep_db_query("SELECT sort_order from " . TABLE_MAINPAGE . " where page_id='" . $mpage_id . "' and parent_id='" . $parent_id . "'");
if (tep_db_num_rows($category_query)<=0){
    echo "Err:"  . TEXT_CATEGORY_NOT_FOUND;
    return;
}
$category_result=tep_db_fetch_array($category_query);
$current_order=(int)$category_result["sort_order"];

if ($mode=="up") {
    $category_sort_sql="select sort_order, page_id from main_pages where parent_id='" . $parent_id . "' and sort_order<$current_order and page_status = 1 order by sort_order desc limit 1";
} else {
    $category_sort_sql="select sort_order, page_id from main_pages where parent_id='" . $parent_id . "' and sort_order>$current_order and page_status = 1 order by sort_order limit 1";
}
$category_sort_query=tep_db_query($category_sort_sql);
if(tep_db_num_rows($category_sort_query)<=0){
    echo "NOTRUNNED";
    return;
}
$categories_result=tep_db_fetch_array($category_sort_query);
$prev_order=$categories_result['sort_order'];
tep_db_query("UPDATE " . TABLE_MAINPAGE . " set sort_order='" . $current_order ."' where page_id='" . (int)$categories_result['page_id'] . "'");
tep_db_query("UPDATE " . TABLE_MAINPAGE . " set sort_order='" . $prev_order . "' where page_id='" . $mpage_id . "'");

$jsData->VARS['moveRows']=array('mode'=>$mode,'destID'=>$categories_result['page_id']);
}
function doMainpageChangeStatus(){
global $FREQUEST,$jsData;
$mpage_id=$FREQUEST->getvalue("cID","int",0);
$status=$FREQUEST->getvalue("status","int",0);
if ($mpage_id<=0) return;
if ($status!=0 && $status!=1) $status=0;
tep_db_query("UPDATE " . TABLE_MAINPAGE . " set page_status=" . $status . " where page_id='" . $mpage_id . "' and parent_id=0");
if ($status==1){
    $result='<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $mpage_id .",get:'MainpageChangeStatus',result:doSimpleResult,params:'cID=". $mpage_id . "&status=0',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_active.gif') . '</a>';
} else {
    $result='<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $mpage_id .",get:'MainpageChangeStatus',result:doSimpleResult,params:'cID=". $mpage_id . "&status=1',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_inactive.gif') . '</a>';
}
echo 'SUCCESS';
// $jsData->VARS["doFunc"]=array('type'=>'display','data'=>'{"id":' . $mpage_id . ',"get":"Mainpage","result":doSimpleResult,"type":"mpage","params":"cID=' . $mpage_id . '","style":"boxLevel1"}');

$jsData->VARS["replace"]=array("mpage". $mpage_id ."bullet"=>$result);
}
function doSubpageList($where='',$mpage_id=0,$search=''){
global $FSESSION,$FREQUEST,$jsData;
$page=$FREQUEST->getvalue('page','int',1);
if ($search!=''){
    $orderBy="order by mpd.page_name";
} else {
    $orderBy="order by mp.sort_order";
} 
$query_split=false;
$sub_page_sql="select mp.page_id,mp.parent_id,mp.page_status,mpd.page_name from ".TABLE_MAINPAGE." mp, ".TABLE_MAINPAGE_DESCRIPTIONS." mpd where mp.page_id=mpd.page_id and mpd.language_id='" . (int)$FSESSION->languages_id  . "' $where $orderBy";
$maxRows=$FSESSION->get('displayRowsCnt');
if ($this->pagination && $maxRows!=-1){
    $query_split=$this->splitResult = (new instance)->getSplitResult('PRDMAIN');
    $query_split->maxRows=$maxRows;
    $query_split->parse($page,$sub_page_sql);
    if ($query_split->queryRows > 0){
        if ($search!=''){
            $query_split->pageLink="doPageAction({'id':-1,'type':'supage','get':'SearchSubpages','result':doTotalResult,params:'search='". $search . "&page='+##PAGE_NO##,'message':'" . INFO_SEARCHING_DATA . "'})";
        } else {
            $query_split->pageLink="doPageAction({'id':-1,'type':'supage','pageNav':true,'closePrev':true,'get':'MainpageSubpage','result':doTotalResult,params:'cID=". $mpage_id . "&page='+##PAGE_NO##,'message':'" . sprintf(TEXT_LOADING_DATA,'##PAGE_NO##') . "'})";
        }
    }
}
$sub_page_query=tep_db_query($sub_page_sql);

$found=false;
$pCnt=tep_db_num_rows($sub_page_query);
if ($pCnt>0) $found=true;

$template=getSubpageListTemplate();
while($sub_page_result=tep_db_fetch_array($sub_page_query)){
    $rep_array=array(	"SUPAGE_ID"=>$sub_page_result["page_id"],
                            "ID"=>$sub_page_result["page_id"],
                            "TYPE"=>"supage",
                            "MPAGE_ID"=>$sub_page_result["parent_id"],
                            "SEARCH_NEEDED"=>($search?"display:none":"display:normal"),
                            "PRD_NAME"=>tep_db_prepare_input($sub_page_result["page_name"]),
                            "IMAGE_PATH"=>DIR_WS_IMAGES,
                            "UPDATING_ORDER"=>TEXT_UPDATING_ORDER,
                            "PRD_STATUS"=>'<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $sub_page_result["page_id"] .",get:'SubpageChangeStatus',result:doSimpleResult,params:'pID=". $sub_page_result["page_id"] . "&status=" .($sub_page_result["page_status"]==1?0:1) . "','message':'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/' . ($sub_page_result["page_status"]==1?'icon_active.gif':'icon_inactive.gif')) . '</a>',
                            "UPDATE_RESULT"=>'doDisplayResult',
                            "ROW_CLICK_GET"=>'SubpageInfo',
                            "FIRST_MENU_DISPLAY"=>($maxRows==-1 || $maxRows>=$query_split->queryRows)?'display:normal':'display:none',
                            "EDIT_MENU_DISPLAY"=>"",
                            "FLAG_ONE_RECORD"=>($pCnt==1?'&last_flag=1':'')
    );
    echo mergeTemplate($rep_array,$template);
}
if (!isset($jsData->VARS["page"])){
    $jsData->VARS["NUclearType"][]="supage";
}
$jsData->VARS['extraParams']=array('page'=>$page,'search'=>$search);
return $found;			
}	
function doSubpageInfo($supage_id=0){
global $FSESSION,$FREQUEST,$currencies;
if ($supage_id<=0) $supage_id=$FREQUEST->getvalue("pID","int",0);
$sub_page_sql="select mp.page_id,mp.parent_id,mp.page_status,mpd.page_name,mpd.description from ".TABLE_MAINPAGE." mp, ".TABLE_MAINPAGE_DESCRIPTIONS." mpd where mp.page_id=mpd.page_id and mpd.language_id='" . (int)$FSESSION->languages_id . "' and mp.page_id='" . tep_db_input($supage_id) . "' order by mp.sort_order";
$sub_page_query=tep_db_query($sub_page_sql);
if (tep_db_num_rows($sub_page_query)>0){
    $subpage_result=tep_db_fetch_array($sub_page_query);
    $template=getSubpageInfoTemplate($supage_id);
    $rep_array=array("SUBPAGE_DESCRIPTION"=>$subpage_result["description"],
                        "SUPAGE_ID"=>$subpage_result["page_id"]);
    echo mergeTemplate($rep_array,$template);
    $jsData->VARS["updateMenu"]=",normal,";
} else {
    echo 'Err:' . TEXT_PRODUCT_NOT_FOUND;
}
}
function doSubpageEdit() {
global $FREQUEST,$LANGUAGES,$CAT_TREE,$jsData;
$languages=&$LANGUAGES;
$supage_id=$FREQUEST->getvalue('pID','int',0);
$mpage_id=$FREQUEST->getvalue('cID','int',0);
$mode=$FREQUEST->getvalue('cID','string','new');
if ($mpage_id<=0) $mode="new";

$jsData->VARS['doFunc']=array('type'=>'supage','data'=>'doSubpageEditor');
if ($mpage_id>0){
    $subpage_sql="select mp.page_id,mp.parent_id,mp.page_status,mpd.page_name,mpd.description,mpd.language_id from ".TABLE_MAINPAGE." mp, ".TABLE_MAINPAGE_DESCRIPTIONS." mpd where mp.page_id=mpd.page_id and mp.page_id='" . tep_db_input($supage_id) . "' and mp.parent_id='" . tep_db_input($mpage_id) . "'order by mpd.language_id";
    $subpage_query=tep_db_query($subpage_sql);
    $supageInfo=array();
    $langInfo=array();
    while($subpage_result=tep_db_fetch_array($subpage_query)){
        $langInfo[$subpage_result["language_id"]]=array("name"=>tep_db_prepare_input($subpage_result["page_name"]),"description"=>tep_db_prepare_input($subpage_result["description"]));
        if (count($supageInfo)==0) {
            $supageInfo=$subpage_result;
            unset($supageInfo["page_name"]);
            unset($supageInfo["description"]);
        }
    }
}
?>
<form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="supageSubmit" id="supageSubmit">
    <table  border="0" cellpadding="4" cellspacing="0" width="100%" class="mainpageEdit" style="padding-left:20px">
    <tr>
        <td width="50%" valign="top">
            <table border="0" cellpadding="2" cellspacing="0" width="100%">
                <?php
                for ($icnt=0,$n=count($languages); $icnt<$n; $icnt++) {
                    ?>
                <tr>
                    <td class="main" valign="top" width="130"><?php if ($icnt == 0) echo TEXT_PAGE_NAME ; ?></td>
                    <td class="main" valign="top"><?php  echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$icnt]['directory'] . '/images/' . $languages[$icnt]['image'], $languages[$icnt]['name']) . '&nbsp;' . tep_draw_input_field('subpage_name[' . $languages[$icnt]['id'] . ']', isset($langInfo[$languages[$icnt]['id']]["name"])?stripslashes($langInfo[$languages[$icnt]['id']]["name"]):'','maxlength="32" id="subpage_name[' . $languages[$icnt]['id'] . ']"'); ?></td>
                </tr>
                <?php } ?>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <div style='width:100%;height:300px;overflow:auto;'>
                <table border="0" cellspacing="0" cellpadding="3">
                    <?php  for ($icnt=0,$count=count($languages); $icnt<$n; $icnt++) { ?>
                    <tr>
                        <td class="main" valign="top"><?php if ($icnt == 0) echo TEXT_PAGE_DESCRIPTION; ?></td>
                        <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$icnt]['directory'] . '/images/' . $languages[$icnt]['image'], $languages[$icnt]['name'],'','','align="top"'); ?>&nbsp;
                        <?php echo tep_draw_textarea_field('subpage_description[' . $languages[$icnt]['id'] . ']', 'soft', '70', '15', isset($langInfo[$languages[$icnt]['id']]['description'])? stripslashes($langInfo[$languages[$icnt]['id']]['description']) : '', ' id="subpage_description[' . $languages[$icnt]['id'] . ']"'); ?></td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </td>
    </tr>
    <input type="hidden" id="mpage_id" name="mpage_id" value="<?php echo tep_output_string($mpage_id);?>"/>
    <input type="hidden" id="supage_id" name="supage_id" value="<?php echo tep_output_string($supage_id);?>"/>
</form>
<tr>
    <td class="main" id="supage<?php echo $supage_id;?>message"></td>
</tr>
</table>
<?php
$jsData->VARS["updateMenu"]=",update,";
}
function doSubpageUpdate() {
global $FSESSION,$FREQUEST,$LANGUAGES,$jsData,$SERVER_DATE,$SERVER_DATE_TIME;
$ID=$FREQUEST->postvalue("supage_id","int",-1);
$mpage_id=$FREQUEST->postvalue("mpage_id","int",-1);
$insert=true;
if ($ID>0) $insert=false;

   if ($mpage_id>0 && $insert==true){
      				$jsData->VARS['storePage']['opened']['mpage']=array("id"=> $mpage_id ,"get"=>"MainpageInfoAndSubpage","result"=>"doDisplayResult","type"=>"mpage","params"=>"cID=$mpage_id","style"=>"boxLevel1");
                   
      			}

$sql_array=array();
if ($insert){
    $max_query=tep_db_query("select max(sort_order) as sort_order from " . TABLE_MAINPAGE);
    $max_result=tep_db_fetch_array($max_query);
    $sql_array["sort_order"]=((int)$max_result["sort_order"])+1;
}
//	$page_status=$sql_array["page_status"]=$FREQUEST->postvalue("page_status","int",0);

if ($insert){
    $sql_array["date_created"]=$SERVER_DATE_TIME;
    $sql_array["parent_id"]=$mpage_id;
    $sql_array['page_status']=1;
    tep_db_perform(TABLE_MAINPAGE,$sql_array);
    $ID=tep_db_insert_id();
} else {
    $sql_array["date_modified"]=$SERVER_DATE_TIME;
    tep_db_perform(TABLE_MAINPAGE,$sql_array,"update","page_id=$ID");
}
for ($icnt=0,$n=count($LANGUAGES);$icnt<$n;$icnt++){
    $subpage_name=$FREQUEST->getRefValue("subpage_name","POST");
    $subpage_description=$FREQUEST->getRefValue("subpage_description","POST");
    $lang_id=$LANGUAGES[$icnt]["id"];
    $sql_array=array(	"page_name"=>tep_db_prepare_input($subpage_name[$lang_id]),
                            "description"=>tep_db_prepare_input($subpage_description[$lang_id])
    );
    $desc_insert=true;
    if (!$insert){
        $check_query=tep_db_query("SELECT page_id from " . TABLE_MAINPAGE_DESCRIPTIONS . " where page_id=$ID and language_id=$lang_id");
        if (tep_db_num_rows($check_query)>0) $desc_insert=false;
    }
    if ($desc_insert){
        $sql_array["page_id"]=$ID;
        $sql_array["language_id"]=$lang_id;
        tep_db_perform(TABLE_MAINPAGE_DESCRIPTIONS,$sql_array);
    } else {
        tep_db_perform(TABLE_MAINPAGE_DESCRIPTIONS,$sql_array,"update","page_id=$ID and language_id=$lang_id");
    }
}
if ($insert) {
    $this->doMainpageSubpage($mpage_id);
} else {
    $cms_subpage_query=tep_db_query("select page_status from " . TABLE_MAINPAGE . "  where parent_id='" . $mpage_id . "' and page_id='" . $ID . "'");
    $cms_subpage_result=tep_db_fetch_array($cms_subpage_query);
    if ($cms_subpage_result['page_status']==1){
        $result='<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $ID .",get:'SubpageChangeStatus',result:doSimpleResult,params:'pID=". $ID . "&status=0'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_active.gif') . '</a>';
    } else {
        $result='<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $ID .",get:'SubpageChangeStatus',result:doSimpleResult,params:'pID=". $ID . "&status=1'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_inactive.gif') . '</a>';
    }
    $jsData->VARS["replace"]=array("supage" . $ID . "title"=>$subpage_name[$FSESSION->languages_id],"supage". $ID ."bullet"=>$result);
    $jsData->VARS["prevAction"]=array('id'=>$ID,'get'=>'SubpageInfo','type'=>'supage','style'=>'boxRow');
    $this->doSubpageInfo($ID);
    $jsData->VARS["updateMenu"]=",normal,";
}
$jsData->VARS["page"]['editorLoaded']='false';
}
function doSubpageDeleteDisplay(){
global $FREQUEST,$jsData,$FSESSION;
$supage_id=$FREQUEST->getvalue('pID','int',0);
$mpage_id=$FREQUEST->getvalue('cID','int',0);
$last_flag=$FREQUEST->getvalue('lflag','int',0);
$delete_message='';

$delete_message='<p><span class="smallText">' . TEXT_DELETE_PAGE_INTRO . '</span>';
?>
<form  name="supageDeleteSubmit" id="supageDeleteSubmit" action="cms_level_pages.php" method="post" enctype="application/x-www-form-urlencoded">
    <input type="hidden" name="supage_id" value="<?php echo tep_output_string($supage_id);?>"/>
    <input type="hidden" name="mpage_id" value="<?php echo tep_output_string($mpage_id);?>"/>
    <table border="0" cellpadding="2" cellspacing="0" width="100%">
        <tr>
            <td class="main" id="supage<?php echo $supage_id;?>message">
            </td>
        </tr>
        <tr>
            <td class="main">
                <?php echo $delete_message;?>
            </td>
        </tr>
        <tr height="40">
            <td class="main" style="vertical-align:bottom">
                <p>
                <a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $supage_id;?>,type:'supage',get:'SubpageDelete',result:doTotalResult,message:page.template['PRD_DELETING'],'uptForm':'supageDeleteSubmit','imgUpdate':false,params:'lflag=<?php echo $last_flag;?>'})"><?php echo tep_image_button('button_delete.gif');?></a>&nbsp;
                <a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:<?php echo $supage_id;?>,type:'supage',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button('button_cancel.gif');?></a>
            </td>
        </tr>
        <tr>
            <td><hr/></td>
        </tr>
        <tr>
            <td valign="top" class="mainpageInfo"><?php echo $this->doSubpageInfo($supage_id);?></td>
        </tr>
    </table>
</form>
<?php
$jsData->VARS["updateMenu"]="";
}
function doSubpageDelete(){
global $FREQUEST,$jsData,$LANGUAGES;
$supage_id=$FREQUEST->postvalue('supage_id','int',0);
$mpage_id=$FREQUEST->postvalue('mpage_id','int',0);
 if ($mpage_id>0 ){
      				$jsData->VARS['storePage']['opened']['mpage']=array("id"=> $mpage_id ,"get"=>"MainpageInfoAndSubpage","result"=>"doDisplayResult","type"=>"mpage","params"=>"cID=$mpage_id","style"=>"boxLevel1");
                   
      			}
$last_flag=$FREQUEST->postvalue('lflag','int',0);
$page=$FREQUEST->postvalue('page','int',0);
if ($supage_id>0){
    $deleteRow=false;
    tep_db_query("Delete from " . TABLE_MAINPAGE . " where page_id='" . $supage_id . "' and parent_id='" . $mpage_id . "'");
    for ($icnt=0,$n=count($LANGUAGES);$icnt<$n;$icnt++){
        $lang_id=$LANGUAGES[$icnt]['id'];
        $deleteRow=true;
        tep_db_query("Delete from " . TABLE_MAINPAGE_DESCRIPTIONS . " where page_id='" . $supage_id . "'  and language_id='" . $lang_id . "'");
    }
    if ($deleteRow){
        if ($last_flag==1 && $page>1){
            $page=$page-1;
            $FREQUEST->setvalue('page',$page,'GET');
        }
        $this->doMainpageSubpage($mpage_id);
    } else {
        $jsData->VARS["updateMenu"]=",normal,";
        $jsData->VARS["closeRow"]=array("id"=>$supage_id,"type"=>"supage");
    }
    $jsData->VARS["displayMessage"]=array('text'=>TEXT_PAGE_DELETE_SUCCESS);
} else {
    echo "Err:" . TEXT_PRODUCT_NOT_DELETED;
}

}
function doSubpageMove(){
global $FREQUEST,$jsData,$FSESSION;
$supage_id=$FREQUEST->postvalue('supage_id','int',0);
$old_parent_id=$FREQUEST->postvalue('mpage_id','int',0);
$new_parent_id=$FREQUEST->postvalue('move_to_category_id','int',0);

if($supage_id>0) {
    tep_db_query("update " . TABLE_MAINPAGE . " set parent_id = " . $new_parent_id . ", date_modified = now() where page_id = " . $supage_id);
    $this->doMainpage();
   if($new_parent_id!=$old_parent_id) 
     $jsData->VARS["displayMessage"]=array("text"=>TEXT_PAGE_MOVED_SUCCESS);

} else {
    echo 'Err:' . TEXT_PRODUCT_ALREADY_LINKED;
    $jsData->VARS["updateMenu"]=",normal,";
}
}
function doSubpageMoveDisplay(){
global $FSESSION,$jsData,$FREQUEST;
$mpage_id=$FREQUEST->getvalue('cID','int',0);
$supage_id =$FREQUEST->getvalue('pID','int',0);
?>
<form  name="supageMoveSubmit" id="supageMoveSubmit" action="cms_level_pages.php" method="post" enctype="application/x-www-form-urlencoded">
<input type="hidden" name="supage_id" value="<?php echo tep_output_string($supage_id);?>"/>
<input type="hidden" name="mpage_id" value="<?php echo tep_output_string($mpage_id);?>"/>
<table border="0" cellpadding="4" cellspacing="0" width="100%">
    <tr>
        <td class="main" id="supage<?php echo $supage_id;?>message">
        </td>
    </tr>
    <tr>
        <td class="inner_title"><?php echo TEXT_INFO_HEADING_MOVE_PAGE;?></td>
    </tr>
    <tr>
        <td class="main" align="left"><?php echo TEXT_INFO_MOVE_TO_INTRO; ?></td>
    </tr>
    <tr>
        <td class="main" align="left"><?php echo 'Move to :'.tep_draw_pull_down_menu('move_to_category_id', tep_get_static_pages_tree('0','',$mpage_id)); ?></td>
    </tr>
    <tr>
        <td class="main">
            <a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $mpage_id;?>,type:'mpage',get:'SubpageMove',result:doTotalResult,message:page.template['PRD_MOVING'],'uptForm':'supageMoveSubmit','closePrev':true,'imgUpdate':false,params:''})"><?php echo tep_image_button('button_move.gif');?></a>&nbsp;
            <a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:<?php echo $mpage_id;?>,type:'mpage',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button('button_cancel.gif');?></a>
        </td>
    </tr>
</table>
<?php
$jsData->VARS["updateMenu"]="";
}
function doSubpageCopy(){
global $FREQUEST,$jsData,$FSESSION;
$supage_id=$FREQUEST->postvalue('supage_id','int',0);
$mpage_id=$FREQUEST->postvalue('mpage_id','int',0);
$new_parent_id=$FREQUEST->postvalue('copy_to_category_id','int',0);

if ($supage_id==0 || $mpage_id==0){
    echo 'Err:' . TEXT_INVALID_DATA;
    return;
}

$sort_order=0;
$sort_order_query=tep_db_query("select max(sort_order) as sort_order from " .TABLE_MAINPAGE. " where parent_id='" . tep_db_input($new_parent_id) . "'");
if(tep_db_num_rows($sort_order_query)>0){
    $sort_order_array=tep_db_fetch_array($sort_order_query);
    $sort_order=$sort_order_array['sort_order'];
}

tep_db_query('insert into '.TABLE_MAINPAGE." (parent_id,date_created,page_status) values ('" . tep_db_input($new_parent_id) . "',now(),'1') ");
$insert_page_id=tep_db_insert_id();
$fetch_query=tep_db_query("select page_name,description,language_id from ".TABLE_MAINPAGE_DESCRIPTIONS." where page_id='" . tep_db_input($supage_id) . "'");
while($fetch_array=tep_db_fetch_array($fetch_query)){
    $page_name=$fetch_array['page_name']; $description=$fetch_array['description']; $language_id=$fetch_array['language_id'];
    tep_db_query('insert into '.TABLE_MAINPAGE_DESCRIPTIONS." (page_id,page_name,description,language_id) values('" . tep_db_input($insert_page_id) . "','" . tep_db_input($page_name) . "','" . tep_db_input($description) . "','" . (int)$language_id . "')");
}
//$this->doMainpageSubpage($mpage_id);
$this->doMainpage();
$jsData->VARS["displayMessage"]=array('text'=>TEXT_PAGE_COPIED_SUCCESS);
}
function doSubpageCopyDisplay(){
global $FSESSION,$jsData,$FREQUEST;
$mpage_id=$FREQUEST->getvalue('cID','int',0);
$supage_id=$FREQUEST->getvalue('pID','int',0);
?>
<form  name="supageCopySubmit" id="supageCopySubmit" action="cms_level_pages.php" method="post" enctype="application/x-www-form-urlencoded">
    <input type="hidden" name="supage_id" value="<?php echo tep_output_string($supage_id);?>"/>
    <input type="hidden" name="mpage_id" value="<?php echo tep_output_string($mpage_id);?>"/>
    <table border="0" cellpadding="4" cellspacing="0" width="100%">
        <tr>
            <td class="main" id="supage<?php echo $supage_id;?>message">
            </td>
        </tr>
        <tr>
            <td class="inner_title"><?php echo TEXT_INFO_HEADING_COPY_PAGE;?></td>
        </tr>
        <tr>
            <td class="main"><?php echo TEXT_INFO_COPY_TO_INTRO;?></td>
        </tr>
        <tr>
            <td class="main"><?php echo  tep_draw_pull_down_menu('copy_to_category_id', tep_get_static_pages_tree('','',$supage_id), $supage_id);?></td>
        </tr>
        <tr>
            <td class="main">
                <a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $mpage_id;?>,type:'mpage',get:'SubpageCopy',result:doTotalResult,message:'<?php echo tep_output_string(TEXT_PRODUCT_COPYING);?>','uptForm':'supageCopySubmit','closePrev':true,'imgUpdate':false,params:''})"><?php echo tep_image_button('button_copy.gif');?></a>&nbsp;
                <a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:<?php echo $mpage_id;?>,type:'mpage',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button('button_cancel.gif');?></a>
            </td>
        </tr>
    </table>
</form>
<?php
$jsData->VARS["updateMenu"]="";
}
function doSubpageSort(){
global $FREQUEST,$jsData;
$supage_id=$FREQUEST->getvalue("pID","int",0);
$mode=$FREQUEST->getvalue("mode","string","down");
$mpage_id=$FREQUEST->getvalue("cID","int",0);
$page_query=tep_db_query("select sort_order from " . TABLE_MAINPAGE . " where parent_id='" . $mpage_id . "'");
$page_result=tep_db_fetch_array($page_query);
if (tep_db_num_rows($page_query)<=0) {
    echo "Err:" . TEXT_PAGE_NOT_FOUND;
    return;
}
$current_order=(int)$page_result["sort_order"];
if ($mode=="up") {
    $subpage_sort_sql="select sort_order, page_id, parent_id from main_pages where parent_id='" . $mpage_id . "' and sort_order< '". $current_order ."' order by sort_order desc limit 1 ";
} else {
    $subpage_sort_sql="select sort_order, page_id, parent_id from main_pages where parent_id='" . $mpage_id . "' and sort_order> '". $current_order ."' and page_status = 1 order by sort_order limit 1 ";
}
$subpage_query=tep_db_query($subpage_sort_sql);
if(tep_db_num_rows($subpage_query)<=0){
    echo "NOTRUNNED";
    return;
}
$subpage_result=tep_db_fetch_array($subpage_query);
$prev_order=$subpage_result['sort_order'];
tep_db_query("UPDATE " . TABLE_MAINPAGE . " set sort_order='" . $current_order . "' where page_id='" . (int)$subpage_result['page_id'] . "' and parent_id='" . $subpage_result['parent_id'] . "'");
tep_db_query("UPDATE " . TABLE_MAINPAGE . " set sort_order='" . $prev_order . "' where page_id='" . $supage_id . "' and parent_id='" . $mpage_id . "'");
echo "SUCCESS";
$jsData->VARS["moveRow"]=array("mode"=>$mode,"destID"=>$subpage_result["page_id"]);
}
function doSubpageChangeStatus(){
global $FREQUEST,$jsData;
$supage_id=$FREQUEST->getvalue("pID","int",0);
$status=$FREQUEST->getvalue("status","int",0);
if ($supage_id<=0) return;
if ($status!=0 && $status!=1) $status=0;
tep_db_query("UPDATE " . TABLE_MAINPAGE . " set page_status=" . $status . " where page_id=$supage_id");
if ($status==1){
    $result='<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $supage_id .",get:'SubpageChangeStatus',result:doSimpleResult,params:'pID=". $supage_id . "&status=0',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_active.gif') . '</a>';
} else {
    $result='<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $supage_id .",get:'SubpageChangeStatus',result:doSimpleResult,params:'pID=". $supage_id . "&status=1',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_inactive.gif') . '</a>';
}
echo 'SUCCESS';
$jsData->VARS["replace"]=array("supage". $supage_id ."bullet"=>$result);
}
}	
function getMainpageListTemplate() {
ob_start();
?>
<tr id="mpage##MPAGE_ID##row">
    <td style="padding-left:##PAD_LEFT##px">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" id="mpage##MPAGE_ID##" class="boxLevel1" onmouseover="javascript:doMouseOverOut([{callFunc:changeItemRow,params:{element:this,'className':'boxLevel1','changeStyle':'Hover'}}]);" onmouseout="javascript:doMouseOverOut([{callFunc:changeItemRow,params:{element:this,'className':'boxLevel1'}}]);">
            <tr>
                <td class="head" valign="middle" height="25px">
                    <table border="0" cellpadding="2" cellspacing="0" width="100%">
                        <tr>
                            <td width="20" id="mpage##MPAGE_ID##arrow" align="center" style="cursor:pointer;cursor:hand" onClick="javascript:doDisplayAction({'id':##MPAGE_ID##,'get':'##ROW_CLICK_GET##','result':doDisplayResult,'type':'mpage','params':'cID=##MPAGE_ID##','style':'boxLevel1'});">##BULLET_IMAGE##</td>
                            <td width="30" align="center" class="boxRowMenu">
                                <span style="##FIRST_MENU_DISPLAY##">
                                    <a href="javascript:void(0);" onClick="javascript:doSimpleAction({'id':##MPAGE_ID##,'get':'Mainpage','result':doTotalResult,mode:'up',type:'mpage',params:'cID=##MPAGE_ID##&mode=up&parent=##CAT_PARENT##',validate:sortMpageValidate,'style':'boxLevel1','message':'##UPDATING_ORDER##'})">##IMGUP##</a>
                                    <a href="javascript:void(0);" onClick="javascript:doSimpleAction({'id':##MPAGE_ID##,'get':'Mainpage','result':doTotalResult,mode:'down',type:'mpage',params:'cID=##MPAGE_ID##&mode=down&parent=##CAT_PARENT##',validate:sortMpageValidate,'style':'boxLevel1','message':'##UPDATING_ORDER##'})">##IMGDOWN##</a>
                                </span>
                            </td>
                            <td width="15" id="mpage##MPAGE_ID##bullet">##CAT_STATUS##</td>
                            <td class="main" onClick="javascript:doDisplayAction({'id':##MPAGE_ID##,'get':'##ROW_CLICK_GET##','result':doDisplayResult,'type':'mpage','params':'cID=##MPAGE_ID##','style':'boxLevel1'});" style="cursor:pointer;cursor:hand" id="mpage##MPAGE_ID##title">
                                ##CAT_NAME##
                            </td>
                            <td align="right" id="mpage##MPAGE_ID##menu" class="boxRowMenu">
                                <span id="mpage##MPAGE_ID##mnormal" style="##EDIT_MENU_DISPLAY##">
                                    <a href="javascript:void(0)" onclick="javascript:return doDisplayAction({'id':##MPAGE_ID##,'get':'MainpageEdit','result':doDisplayResult,'style':'boxLevel1','type':'mpage','params':'cID=##MPAGE_ID##'});"><img src="##IMAGE_PATH##template/edit_blue.gif" title="Edit"/></a>
                                    <img src="##IMAGE_PATH##template/img_bar.gif"/>
                                    <a href="javascript:void(0)" onclick="javascript:return doDisplayAction({'id':##MPAGE_ID##,'get':'MainpageDeleteDisplay','result':doDisplayResult,'style':'boxLevel1','type':'mpage','params':'cID=##MPAGE_ID##'});"><img src="##IMAGE_PATH##template/delete_blue.gif" title="Delete"/></a>
                                    <img src="##IMAGE_PATH##template/img_bar.gif"/>
                                   
                                    <a href="javascript:void(0)" onclick="javascript:return doDisplayAction({'id':##MPAGE_ID##,'get':'MainpageCopyDisplay','result':doDisplayResult,'style':'boxRow','type':'mpage','params':'cID=##MPAGE_ID##'});"><img src="##IMAGE_PATH##template/copy_blue.gif" title="Copy"/></a>
                                </span>
                                <span id="mpage##MPAGE_ID##mupdate" style="display:none">
                                    <a href="javascript:void(0)" onclick="javascript:return doUpdateAction({'id':##MPAGE_ID##,'get':'MainpageUpdate','imgUpdate':true,'type':'mpage','refresh':'1','style':'boxLevel1','validate':mainpageValidate,'uptForm':'mpageSubmit',extraFunc:textEditorRemove,'result':##UPDATE_RESULT##,'get':'MainpageUpdate',message:page.template['UPDATE_IMAGE'],message1:page.template['UPDATE_DATA']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
                                    <img src="##IMAGE_PATH##template/img_bar.gif"/>
                                    <a href="javascript:void(0)" onclick="javascript:return doCancelAction({'id':##MPAGE_ID##,'get':'MainpageEdit','type':'mpage','style':'boxLevel1',extraFunc:textEditorRemove});"><img src="##IMAGE_PATH##template/img_close_blue.gif" title="Cancel"/></a>
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
function getMainpageInfoTemplate(){
ob_start();
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td width="10">&nbsp;</td>
        <td valign="top" class="smallText" align="justify">
            <div style="width:700px;height:100px;overflow:auto">
                ##MAINPAGE_DESCRIPTION##
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
function getSubpageListTemplate() {	
ob_start();
getTemplateRowTop();
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%" id="supage##SUPAGE_ID##">
    <tr>
        <td>
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td width="30" id="supage##SUPAGE_ID##sort" class="boxRowMenu">
                        <span style="##FIRST_MENU_DISPLAY##">
                            <a href="javascript:void(0);" onClick="javascript:doSimpleAction({'id':##SUPAGE_ID##,'get':'SubpageSort','result':doSimpleResult,mode:'up',type:'supage',params:'pID=##SUPAGE_ID##&mode=up&cID=##MPAGE_ID##',validate:sortSubpageValidate,'style':'boxRow','message':'##UPDATING_ORDER##'})"><img src="##IMAGE_PATH##template/img_arrow_up.gif" title="Up" align="absmiddle"/></a>
                            <a href="javascript:void(0);" onClick="javascript:doSimpleAction({'id':##SUPAGE_ID##,'get':'SubpageSort','result':doSimpleResult,mode:'down',type:'supage',params:'pID=##SUPAGE_ID##&mode=down&cID=##MPAGE_ID##',validate:sortSubpageValidate,'style':'boxRow','message':'##UPDATING_ORDER##'})"><img src="##IMAGE_PATH##template/img_arrow_down.gif" title="Down" align="absmiddle"/></a>
                        </span>
                    </td>
                    <td width="15" id="supage##SUPAGE_ID##bullet">##PRD_STATUS##</td>
                    <td class="main" onclick="javascript:doDisplayAction({'id':##SUPAGE_ID##,'get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'supage','params':'pID=##SUPAGE_ID##&cID=##MPAGE_ID##'});" id="supage##SUPAGE_ID##title">##PRD_NAME##</td>
                    <td id="supage##SUPAGE_ID##menu" align="right" class="boxRowMenu">
                        <span id="supage##SUPAGE_ID##mnormal" style="##EDIT_MENU_DISPLAY##">
                            <a href="javascript:void(0)" onclick="javascript:return doDisplayAction({'id':##SUPAGE_ID##,'get':'SubpageEdit','result':doDisplayResult,'style':'boxRow','type':'supage','params':'pID=##SUPAGE_ID##&cID=##MPAGE_ID##','backupMenu':true});"><img src="##IMAGE_PATH##template/edit_blue.gif" title="Edit"/></a>
                            <img src="##IMAGE_PATH##template/img_bar.gif"/>
                            <a href="javascript:void(0)" onclick="javascript:return doDisplayAction({'id':##SUPAGE_ID##,'get':'SubpageDeleteDisplay','result':doDisplayResult,'style':'boxRow','type':'supage','params':'pID=##SUPAGE_ID##&cID=##MPAGE_ID####FLAG_ONE_RECORD##'});"><img src="##IMAGE_PATH##template/delete_blue.gif" title="Delete"/></a>
                            <img src="##IMAGE_PATH##template/img_bar.gif"/>
                            <span style="##SEARCH_NEEDED##">
                                <a href="javascript:void(0)" onclick="javascript:return doDisplayAction({'id':##SUPAGE_ID##,'get':'SubpageMoveDisplay','result':doDisplayResult,'style':'boxRow','type':'supage','params':'pID=##SUPAGE_ID##&cID=##MPAGE_ID##'});"><img src="##IMAGE_PATH##template/img_move.gif" title="Move"/></a>
                                <img src="##IMAGE_PATH##template/img_bar.gif"/>
                                <a href="javascript:void(0)" onclick="javascript:return doDisplayAction({'id':##SUPAGE_ID##,'get':'SubpageCopyDisplay','result':doDisplayResult,'style':'boxRow','type':'supage','params':'pID=##SUPAGE_ID##&cID=##MPAGE_ID##'});"><img src="##IMAGE_PATH##template/copy_blue.gif" title="Copy"/></a>
                                <img src="##IMAGE_PATH##template/img_bar.gif"/>
                            </span>
                        </span>
                        <span id="supage##SUPAGE_ID##mupdate" style="display:none">
                            <a href="javascript:void(0)" onclick="javascript:return doUpdateAction({'id':##SUPAGE_ID##,'get':'SubpageUpdate','imgUpdate':true,'type':'supage','style':'boxRow','validate':subpageValidate,'uptForm':'supageSubmit','customUpdate':doSubpageUpdate,extraFunc:textEditorRemove,'result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
                            <img src="##IMAGE_PATH##template/img_bar.gif"/>
                            <a href="javascript:void(0)" onclick="javascript:return doCancelAction({'id':##SUPAGE_ID##,'get':'SubpageEdit','type':'supage','style':'boxRow',extraFunc:textEditorRemove});"><img src="##IMAGE_PATH##template/img_close_blue.gif" title="Cancel"/></a>
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
function getSubpageInfoTemplate(){
ob_start();
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td width="10">&nbsp;</td>
        <td valign="top" class="smallText" align="justify">
            <div style="width:500px;height:100px;overflow:auto">
                ##SUBPAGE_DESCRIPTION##
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