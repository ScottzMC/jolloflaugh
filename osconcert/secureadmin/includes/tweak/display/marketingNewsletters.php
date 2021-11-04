<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare

    Released under the GNU General Public License
*/
defined('_FEXEC') or die();
class marketingNewsletters
{
    var $pagination;
    var $splitResult;
    var $type;

    function __construct() {
        $this->pagination=false;
        $this->splitResult=false;
        $this->type='mnl';
    }

    function doLockChange()
    {
        global $FREQUEST,$jsData;
        $newsletter_id=$FREQUEST->getvalue('rID','int',0);
        $lock=$FREQUEST->getvalue('lock','int',0);
        tep_db_query("update ".TABLE_NEWSLETTERS." set locked='".$lock."' where newsletters_id='".tep_db_input($newsletter_id)."'");

        $jsData->VARS["replace"]=array($this->type. $newsletter_id . "bullet"=>(($lock==1)?tep_image(DIR_WS_IMAGES.'icons/locked.gif','Lock'):tep_image(DIR_WS_IMAGES.'icons/unlocked.gif','UnLock')));
        $jsData->VARS["prevAction"]=array('id'=>$newsletter_id,'get'=>'NewslettersInfo','type'=>$this->type,'style'=>'boxRow');
        $this->doNewslettersInfo($newsletter_id);
        if($lock==1)
        $jsData->VARS["updateMenu"]=",normal,";
        else
        $jsData->VARS["updateMenu"]=",unlock,";
    }
    function doMailUpdate()
    {
        global $PHP_SELF,$FREQUEST,$jsData,$FSESSION;

        $newsletter_id=$FREQUEST->postvalue('newsletter_id','int',0);

        $newsletter_query = tep_db_query("select newsletters_id, title, content, module from " . TABLE_NEWSLETTERS . " where newsletters_id = '" . (int)$newsletter_id . "'");
        $newsletter = tep_db_fetch_array($newsletter_query);
        $nInfo = new objectInfo($newsletter);

        include(DIR_WS_LANGUAGES . $FSESSION->language . '/modules/newsletters/' . $nInfo->module . substr($PHP_SELF, strrpos($PHP_SELF, '.')));
        include(DIR_WS_MODULES . 'newsletters/' . $nInfo->module . substr($PHP_SELF, strrpos($PHP_SELF, '.')));

        $module_name = $nInfo->module;

        $module = new $module_name($nInfo->title, $nInfo->content);
        tep_set_time_limit(0);
        flush();

        $module->send($nInfo->newsletters_id);?>
<table border="0" cellspacing="0" cellpadding="2" width="100%">
    <tr>
        <td class="main" valign="middle" height="50" align="center"><?php echo TEXT_FINISHED_SENDING_EMAILS; ?></td>
    </tr>
</table><?php
$jsData->VARS["updateMenu"]=",normal,";
}
function doNewslettersMail()
{
global $FREQUEST,$jsData,$FSESSION;

if($newsletter_id <= 0)$newsletter_id=$FREQUEST->getvalue("rID","int",0);

$newsletter_query = tep_db_query("select title, content, module from " . TABLE_NEWSLETTERS . " where newsletters_id = '" . (int)$newsletter_id . "'");
$newsletter = tep_db_fetch_array($newsletter_query);

$nInfo = new objectInfo($newsletter);
include(DIR_WS_LANGUAGES . $FSESSION->language . '/modules/newsletters/' . $nInfo->module . substr(FILENAME_NEWSLETTERS, strrpos(FILENAME_NEWSLETTERS, '.')));
include(DIR_WS_MODULES . 'newsletters/' . $nInfo->module . substr(FILENAME_NEWSLETTERS, strrpos(FILENAME_NEWSLETTERS, '.')));
$module_name = $nInfo->module;
$module = new $module_name($nInfo->title, $nInfo->content);
if ($module->show_choose_audience) { echo $module->choose_audience('ajax'); } else { echo $module->confirm('ajax'); }
echo tep_draw_hidden_field('newsletter_id',$newsletter_id);
$jsData->VARS["updateMenu"]=",display,";
}

function doDelete(){
global $FREQUEST,$jsData;
$newsletter_id=$FREQUEST->postvalue('newsletter_id','int',0);
if ($newsletter_id>0){
    tep_db_query("DELETE from " . TABLE_NEWSLETTERS . " where newsletters_id='".tep_db_input($newsletter_id)."'");

    $this->doNewsletters();
    $jsData->VARS["displayMessage"]=array('text'=>TEXT_NEWSLETTER_DELETE_SUCCESS);
    tep_reset_seo_cache('newsletters');
} else {
    echo "Err:" . TEXT_CUSTOMER_GROUPS_NOT_DELETED;
}

}

function doDeleteNewsletters(){
global $FREQUEST,$jsData;
$newsletter_id=$FREQUEST->getvalue('rID','int',0);

$delete_message='<p><span class="smallText">' . TEXT_INFO_DELETE_INTRO . '</span>';
?>
<form  name="<?php echo $this->type;?>DeleteSubmit" id="<?php echo $this->type;?>DeleteSubmit" action="customers_group.php" method="post" enctype="application/x-www-form-urlencoded">
    <input type="hidden" name="newsletter_id" value="<?php echo tep_output_string($newsletter_id);?>"/>
    <table border="0" cellpadding="2" cellspacing="0" width="100%">
        <tr>
            <td class="main" id="<?php echo $this->type . $newsletter_id;?>message">
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
                <a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $newsletter_id;?>,type:'<?php echo $this->type;?>',get:'Delete',result:doTotalResult,message:page.template['PRD_DELETING'],'uptForm':'<?php echo $this->type;?>DeleteSubmit','imgUpdate':false,params:''})"><?php echo tep_image_button_delete('button_delete.gif');?></a>&nbsp;
                <a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:<?php echo $newsletter_id;?>,type:'<?php echo $this->type;?>',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button_cancel('button_cancel.gif');?></a>
            </td>
        </tr>
        <tr>
            <td><hr/></td>
        </tr>
        <tr>
            <td valign="top" class="categoryInfo"><?php echo $this->doNewslettersInfo($newsletter_id);?></td>
        </tr>
    </table>
</form>
<?php
$jsData->VARS["updateMenu"]="";
}
function doNewslettersList($where='',$newsletter_id=0,$search=''){
global $FSESSION,$FREQUEST,$jsData;
$page=$FREQUEST->getvalue('page','int',1);

$query_split=false;

$newsletters_query_sql="select newsletters_id,title,content,module,date_added,date_sent,status,locked from ".TABLE_NEWSLETTERS." order by title";

if ($this->pagination){
    $query_split=$this->splitResult = (new instance)->getSplitResult('NEWSLETTER');
    $query_split->maxRows=MAX_DISPLAY_SEARCH_RESULTS;
    $query_split->parse($page,$newsletters_query_sql);
    if ($query_split->queryRows > 0){
        $query_split->pageLink="doPageAction({'id':-1,'type':'" . $this->type ."','pageNav':true,'closePrev':true,'get':'Newsletters','result':doTotalResult,params:'page='+##PAGE_NO##,'message':'" . sprintf(INFO_LOADING_NEWSLETTERS,'##PAGE_NO##') . "'})";
    }
}
$newsletters_query=tep_db_query($newsletters_query_sql);
$found=false;
if (tep_db_num_rows($newsletters_query)>0) $found=true;
if($found)
{
    $template=getListTemplate();
    $icnt=1;
    while($newsletters_result=tep_db_fetch_array($newsletters_query)){
        if($newsletters_result['locked']==0)
        {
            $unlock='display:none';
            $lock='';
        }
        else
        {
            $unlock='';
            $lock='display:none';
        }
        $rep_array=array(	"ID"=>$newsletters_result["newsletters_id"],
                                "TYPE"=>$this->type,
                                "NAME"=>$newsletters_result["title"],
                                "IMAGE_PATH"=>DIR_WS_IMAGES,
                                "STATUS"=>(($newsletters_result['locked']==1)?tep_image(DIR_WS_IMAGES.'icons/locked.gif','Lock'):tep_image(DIR_WS_IMAGES.'icons/unlocked.gif','UnLock')),
                                "UPDATE_RESULT"=>'doDisplayResult',
                                "ALTERNATE_ROW_STYLE"=>($icnt%2==0?"listItemOdd":"listItemEven"),
                                "ROW_CLICK_GET"=>'NewslettersInfo',
                                "FIRST_MENU_DISPLAY"=>$unlock,
                                "LOCK"=>$lock
        );
        echo mergeTemplate($rep_array,$template);
        $icnt++;
    }}
else if($search=='')
{
    echo '<div align="center">'.TEXT_EMPTY_NEWSLETTER.'</div>';
}
if (!isset($jsData->VARS["Page"])){
    $jsData->VARS["NUclearType"][]=$this->type;
} 
return $found;			
}

function doNewsletters(){
global $FREQUEST,$jsData;

$template=getListTemplate();
$rep_array=array(	"TYPE"=>$this->type,
                            "ID"=>-1,
                            "NAME"=>HEADING_NEW_TITLE,
                            "DISCOUNT"=>'',
                            "IMAGE_PATH"=>DIR_WS_IMAGES,
                            "STATUS"=>tep_image(DIR_WS_IMAGES . 'template/plus_add2.gif'),
                            "UPDATE_RESULT"=>'doTotalResult',
                            "ROW_CLICK_GET"=>'NewslettersEdit',
                            "FIRST_MENU_DISPLAY"=>"display:none",
                            "LOCK"=>"display:none"
);

?>
<div class="main" id="mnl-1message"></div>
<table border="0" width="100%" height="100%" id="<?php echo $this->type;?>Table">
    <tr>
        <td><?php 	echo mergeTemplate($rep_array,$template); ?>
        </td>
    </tr>
    <tr>
        <td>
            <table border="0" width="100%" cellpadding="0" cellspacing="0" height="100%">
                <tr class="dataTableHeadingRow">
                    <td valign="top">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr  >
                                <td class="main" width="47%">
                                    <b><?php echo  HEADING_TITLE;?></b>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div align="center"><?php $this->doNewslettersList();?></div>
                    </td>
                </tr>
            </Table>
        </td>
    </tr>
</table>
<?php if (is_object($this->splitResult)){?>
<table border="0" width="100%" height="100%">
    <?php echo $this->splitResult->pgLinksCombo(); ?>
</table>
<?php }


}
function doNewslettersEdit()
{
global $FREQUEST,$jsData;
$newsletter_id=$FREQUEST->getvalue("rID","int",0);
$jsData->VARS['doFunc']=array('type'=>'mnl','data'=>'doProductEditor');
$newsletters_info=array();
$newsletters_edit_query=tep_db_query("select title,content,module from ".TABLE_NEWSLETTERS." where newsletters_id='" . tep_db_input($newsletter_id) . "'");
if(tep_db_num_rows($newsletters_edit_query)>0) $newsletters_info=tep_db_fetch_array($newsletters_edit_query);


$file_extension = substr(FILENAME_NEWSLETTERS, strrpos(FILENAME_NEWSLETTERS, '.')); 
$directory_array = array(); 
if ($dir = dir(DIR_WS_MODULES . 'newsletters/')) {
while ($file = $dir->read()) { 
    if (!is_dir(DIR_WS_MODULES . 'newsletters/' . $file)) {
        if (substr($file, strrpos($file, '.')) == $file_extension) {
            $directory_array[] = $file;
        }
    }
} 
sort($directory_array);
$dir->close();
}

for ($i=0, $n=sizeof($directory_array); $i<$n; $i++) {
$modules_array[] = array('id' => substr($directory_array[$i], 0, strrpos($directory_array[$i], '.')), 'text' => substr($directory_array[$i], 0, strrpos($directory_array[$i], '.')));
}

echo tep_draw_form('save_newsletters',FILENAME_NEWSLETTERS,'','post');
?>
<input type="hidden" name="newsletter_id" id="newsletter_id" value="<?php echo tep_output_string($newsletter_id); ?>" />
<table width="100%" class="main" cellpadding="2" cellspacing="2" border="0">
    <tr>
        <td width="200" align="right"><?php echo TEXT_NEWSLETTER_MODULE; ?></td>
        <td><?php echo tep_draw_pull_down_menu('edit_modules',$modules_array,$newsletters_info['module']);  ?></td>
    </tr>
    <tr>
        <td align="right"><?php echo TEXT_NEWSLETTER_TITLE; ?></td>
        <td><?php echo tep_draw_input_field('edit_title',$newsletters_info['title'],'',true); ?></td>
    </tr>
    <tr>
        <td align="right" valign="top"><?php echo TEXT_NEWSLETTER_CONTENT; ?></td>
        <td>
            <?php echo tep_draw_textarea_field('message_text', 'soft', '80', '24', $newsletters_info['content'],'id="message_text"'); ?>
        </td>
    </tr>
</table>
</form><?php 
$jsData->VARS["updateMenu"]=",update,";
$display_mode_html=' style="display:none"';

}	

function doNewslettersUpdate(){
global $FREQUEST,$jsData,$SERVER_DATE;
$newsletter_id=$FREQUEST->postvalue("newsletter_id","int",-1);
$insert=true;
if ($newsletter_id>0) $insert=false;



$edit_modules=tep_db_prepare_input($FREQUEST->postvalue('edit_modules'));
$message_text=$FREQUEST->postvalue('message_text');
$edit_title=tep_db_prepare_input($FREQUEST->postvalue('edit_title'));



$sql_data = array('module' => $edit_modules,
                    'content' =>$message_text,
                    'title' =>$edit_title
);	

if ($insert){
    $sql_data = array('module'=> $edit_modules,
                        'content'=>$message_text,
                        'title' =>$edit_title,
                        'date_added'=>$SERVER_DATE
    );

    tep_db_perform(TABLE_NEWSLETTERS,$sql_data);
    $newsletter_id=tep_db_insert_id();
} else {
    $sql_data = array(  'module' => $edit_modules,
                            'content' =>$message_text,
                            'title' =>$edit_title
    );
    tep_db_perform(TABLE_NEWSLETTERS, $sql_data, 'update', "newsletters_id = '" .$newsletter_id . "'");
}
if ($insert) {
    $this->doNewsletters();
} else {
    $jsData->VARS["replace"]=array($this->type. $newsletter_id . "name"=>tep_db_input($edit_title));
    $jsData->VARS["prevAction"]=array('id'=>$newsletter_id,'get'=>'NewslettersInfo','type'=>$this->type,'style'=>'boxRow');
    $this->doNewslettersInfo($newsletter_id);
    $jsData->VARS["updateMenu"]=",normal,";
}
}

function doNewslettersInfo($newsletter_id=0){
global $FREQUEST,$jsData;

if($newsletter_id <= 0)$newsletter_id=$FREQUEST->getvalue("rID","int",0);

$newsletters_query=tep_db_query("select newsletters_id,title,content,module,date_added,date_sent,status,locked from ".TABLE_NEWSLETTERS." where newsletters_id='" . tep_db_input($newsletter_id) . "'");
if (tep_db_num_rows($newsletters_query)>0){
    $newsletters_result=tep_db_fetch_array($newsletters_query);
    $template=getInfoTemplate($newsletter_id);

    $rep_array=array(	"TYPE"=>$this->type,
                            "ENT_MODULE"=>TEXT_NEWSLETTER_MODULE ,
                            "MODULE"=> $newsletters_result["module"],
                            "ENT_DATE"=>TEXT_NEWSLETTER_DATE_ADDED,
                            "DATE"=>format_date($newsletters_result["date_added"]),
                            //"ENT_CONTENT"=>TEXT_NEWSLETTER_CONTENT,
                            //"CONTENT"=>$newsletters_result["content"],
							//cartzone
							 "ENT_CONTENT"=> stripslashes(TEXT_NEWSLETTER_CONTENT),
							 "CONTENT"=> stripslashes($newsletters_result["content"]),
							
                            "ENT_SENT"=>TABLE_HEADING_SENT,
                            "SENT"=>($newsletters_result['status']==1)?tep_image(DIR_WS_IMAGES.'icons/tick.gif'):tep_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS),
                            "ID"=>$newsletters_result["newsletters_id"],
    );

    echo mergeTemplate($rep_array,$template);
    if($newsletters_result['locked']==1)
    $jsData->VARS["updateMenu"]=",normal,";
    else
    $jsData->VARS["updateMenu"]=",unlock,";
}
else {
    echo 'Err:' . TEXT_LOCATION_NOT_FOUND;
}

}			

}function getListTemplate(){
ob_start();
getTemplateRowTop();
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%" id="##TYPE####ID##">
    <tr>
        <td>
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td width="15" id="mnl##ID##bullet">##STATUS##</td>
                    <td width="79%" class="main" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});" id="##TYPE####ID##name">##NAME##</td>
                    <td  width="20%" id="##TYPE####ID##menu" align="right" class="boxRowMenu">
                        <span id="##TYPE####ID##mnormal" style="##FIRST_MENU_DISPLAY##">
                            <a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'NewslettersEdit','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/edit_blue.gif" title="Edit"/></a>
                            <img src="##IMAGE_PATH##template/img_bar.gif"/>
                            <a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'DeleteNewsletters','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/delete_blue.gif" title="Delete"/></a>
                            <img src="##IMAGE_PATH##template/img_bar.gif"/>
                            <a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'LockChange','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##&lock=0'});"><img src="##IMAGE_PATH##icons/locked.gif" title="Lock"/></a>
                            <img src="##IMAGE_PATH##template/img_bar.gif"/>
                            <a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'NewslettersMail','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/mail.gif" title="Mail"/></a>
                            <img src="##IMAGE_PATH##template/img_bar.gif"/>
                        </span>
                        <span id="##TYPE####ID##mupdate" style="display:none">
                            <a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##ID##,'get':'NewslettersUpdate','imgUpdate':false,'type':'##TYPE##','style':'boxRow','validate':NewsletterValidate,'uptForm':'save_newsletters',extraFunc:textEditorRemove,'customUpdate':doNewsUpdate,'result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
                            <img src="##IMAGE_PATH##template/img_bar.gif"/>
                            <a href="javascript:void(0)" onClick="javascript:return doCancelAction({'id':##ID##,'get':'NewslettersEdit','type':'##TYPE##',extraFunc:textEditorRemove,'style':'boxRow'});"><img src="##IMAGE_PATH##template/img_close_blue.gif" title="Cancel"/></a>
                        </span>
                        <span id="##TYPE####ID##mdisplay" style="display:none">
                            <a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##ID##,'get':'MailUpdate','imgUpdate':false,'type':'##TYPE##','style':'boxRow','validate':MailValidate,'uptForm':'notifications','customUpdate':doMailUpdate,'result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
                            <img src="##IMAGE_PATH##template/img_bar.gif"/>
                            <a href="javascript:void(0)" onClick="javascript:return doCancelAction({'id':##ID##,'get':'NewslettersEdit','type':'##TYPE##','style':'boxRow'});"><img src="##IMAGE_PATH##template/img_close_blue.gif" title="Cancel"/></a>
                        </span>
                        <span id="##TYPE####ID##munlock" style="##LOCK##">
                            <a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'LockChange','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##&lock=1'});"><img src="##IMAGE_PATH##icons/unlocked.gif" title="Unlock"/></a>
                            <img src="##IMAGE_PATH##template/img_bar.gif"/>
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
function getInfoTemplate(){
ob_start();
?>
<table border="0" cellpadding="4" cellspacing="0" width="100%">
    <div class="hLineGray"></div>
    <tr><td colspan="6" height="20"></td></tr>
    <tr>
        <td width="10%" align="right" nowrap="nowrap" style="overflow:hidden;" class="main"><b>##ENT_MODULE##</b></td>
        <td width="30%" align="left" style="overflow:hidden;"  class="main">##MODULE##</td>
        <td width="10%" align="right" nowrap="nowrap" style="overflow:hidden" class="main"><b>##ENT_DATE##</b></td>
        <td width="20%"  align="left" style="overflow:hidden" class="main">##DATE##</td>
        <td width="10%" align="right" style="overflow:hidden" class="main"><b>##ENT_SENT##</b></td>
        <td width="20%"  align="left" style="overflow:hidden" class="main">##SENT##</td>
    </tr>
    <tr>
        <td width="10%"  height="50" align="right" nowrap="nowrap" style="overflow:hidden;" class="main"><b>##ENT_CONTENT##</b></td>
        <td width="90%" height="50" colspan="5"align="left" style="overflow:hidden"  class="main">##CONTENT##</td>
        <td></td>
    </tr>
</table>
<?php
$contents=ob_get_contents();
ob_end_clean();
return $contents;
}


?>