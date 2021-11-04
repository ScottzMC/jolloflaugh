<?php
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
 ?>
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script> 
<script type="text/javascript" src="includes/aim.js"></script>  
<script language="javascript" src="includes/http.js"></script>
<script language="JavaScript" src="includes/date-picker.js"></script>
<script languages="javascript">		
	var cat_expand;
	var count = 0;
	var id = new Array();
	var category_list = new Array();
	var default_category = '';
	var panel_expand;
	var panel_expand_id;
	var ids = new Array();
	var page='<?php echo $page;?>';
	var order='<?php echo $order;?>';
	var prev_value;
 	var img_src="<?php echo HTTP_SERVER.DIR_WS_ADMIN.'images/';?>"; 	
 	var lang=Array();
 	<?php 
 	  $languages = tep_get_languages();
 	  $n=count($languages);
 	  for($i=0;$i<$n;$i++){ ?>
 		lang[<?php echo $i;?>]="<?php echo $languages[$i]['id'];?>";	  		
 <?php } ?>
 	function go_search(e){
      var kc=0;
   	  if (window.event)
	    kc=window.event.keyCode;
	  else if (e){
	 	kc=e.which;
	  }else
		kc=0;	 
	  //to check in IE  value 10 is assigned to e;
	  if((kc==1 || kc==13 || e==10) && document.getElementById("search") && document.getElementById("search").value!="") {
	  	 command="<?php echo tep_href_link($product_filename,'command=search_details');?>&search="+document.getElementById("search").value;
	  	 do_get_command(command);
	  }
 	}
 	 	     
 	function startCallback_cms(){
 	  // make something useful before submit (onStart)
	  var error_result="";
	  var categories_image="";
	  var action_type="";
	  var frm=document.new_product; 	  	
 	  var lang_len=lang.length;
 	  var cID="";
	  if(document.getElementById("action_type")) action_type=document.getElementById("action_type").value;
	  if(document.getElementById("categories_image")) categories_image=document.getElementById("categories_image").value;
	  if(document.getElementById("product_image")) product_image=document.getElementById("product_image").value;
	  if(document.getElementById("product_image_two")) product_image_two=document.getElementById("product_image_two").value;
	  if(document.getElementById("product_image_three")) product_image_three=document.getElementById("product_image_three").value;
	  if(document.getElementById("catagory_status")) var status=document.getElementById("catagory_status");
	  var validate_form=false;
	  var image_types=Array(".png",".jpg",".jpeg",".gif");	  
	  if(action_type=='new_category' || action_type=='edit_category'){
		  if(categories_image!="" && !check_mime_type(document.getElementById("categories_image"),image_types)){
				error_result="* <?php echo ERR_IMAGE_UPLOAD_TYPE;?>\n";
			}	
		  else if(categories_image=="")
				validate_form=true;
		  for(i=0;i<lang_len;i++){
 	 	  	 if( (document.getElementById("categories_name["+lang[i]+"]").value=="") || (str_trim(document.getElementById("categories_name["+lang[i]+"]").value)=="") )
 	 	 			error_result="<?php echo ERR_CATEGORY_NAME;?>";	
 	 	  }
	  }
	  if(action_type=='new' || action_type=='edit'){
			if (document.new_product.elements["product_name["+lang[0]+"]"]){
			  for (icnt=0;icnt<lang.length;icnt++){ 
				if (document.new_product.elements["product_name["+lang[icnt]+"]"].value=="" || str_trim(document.new_product.elements["product_name["+lang[icnt]+"]"].value)==''){
				  error_result+="<?php echo ERR_ARTICLE_NAME; ?>";break;
				}
			  } 
			} else {
			  if (document.new_product.product_name.value=="" || str_trim(document.new_product.product_name.value)=='')  error_result+="<?php echo ERR_ARTICLE_NAME; ?>";
			}
		  if(product_image!="" && !check_mime_type(document.getElementById("product_image"),image_types))
		  	  error_result="* <?php echo ERR_IMAGE_UPLOAD_TYPE;?>\n";		  
		  if(product_image_two!="" && !check_mime_type(document.getElementById("product_image_two"),image_types))
		  	  error_result=+"* <?php echo ERR_IMAGE_TWO_UPLOAD_TYPE;?>\n";		  
		  if(product_image_three!="" && !check_mime_type(document.getElementById("product_image_three"),image_types))
		  	  error_result=+"* <?php echo ERR_IMAGE_THREE_UPLOAD_TYPE;?>\n";		  
	  	  if(product_image=="" && product_image_two=="" && product_image_three=="")
		  	  validate_form=true;	
	  	/*  for(i=0;i<lang_len;i++){
 	 	 	if( (document.getElementById("product_name["+lang[i]+"]")) && ( (document.getElementById("product_name["+lang[i]+"]").value=="") || (str_trim(document.getElementById("product_name["+lang[i]+"]").value)=="") ) )
				error_result="<?php echo ERR_ARTICLE_NAME;?>"; 	 	 				 	 	
 	  	  } 	*/ 	  	 	
 	  	  if( (document.getElementById("product_date_available")) && ( (document.getElementById("product_date_available").value=="") || (str_trim(document.getElementById("product_date_available").value)=="") ) )
 	 	 		error_result+="<?php echo ERR_ARTICLE_START_DATE;?>";	  
	  }
 	  if(action_type=='delete_category' || action_type=='move_category' || action_type=='delete' || action_type=='copy' || action_type=='move') validate_form=true;	 	
	  if(error_result=="" && validate_form) {
	  	 ValidateForm();
	  	 return false;
	  }else if(error_result!=""){
	  	alert(error_result);
	  	return false;
	  }else if(error_result=="" && !validate_form) return true;
 	}
 	
 	function completeCallback_cms(response){
		ValidateForm(response);
 	}
 var action_type;	 	
 	 function ValidateForm(response){
 	 	 var frm=document.new_product;
 	 	 action_type=frm.action_type.value; 	 	  	 	  	 	
 	 	 var cID="";		 
 	 	 if(action_type!='new_category' && document.getElementById("cPath") && document.getElementById("cPath").value>0) cID=document.getElementById("cPath").value;
 	 	 if(response && action_type=='new_category') {
 	 	 	cID=response;
 	 	 	action_type='edit_category';	
 	 	 } 
 	 	 var pID="";
 	 	 if(action_type!='new' && !pID && frm.product_id && frm.product_id.value>0) pID=frm.product_id.value; 	 	 
 	 	 if(response>0 && action_type=='new'){
 	 	 	pID=response;
 	 	 	action_type='edit';
 	 	 } 	 	  	 	 	 	 	 
 	 	 command="<?php echo tep_href_link($product_filename);?>?cID="+cID+"&pID="+pID;
 	 	 	if(action_type=='new_category' || action_type=='edit_category')
 	 	 		command+="&command=update_category"; 	 	 	
 	 	 	if(action_type=='delete_category' || action_type=='move_category') 
 	 	 		command+="&command="+action_type+"_confirm";			 	 	 			
 	 	 	if(action_type=='new' || action_type=='edit')
				command+="&command=update";
 	 	 	if(action_type=='delete' || action_type=='copy' || action_type=='move'){
				if(action_type=='move') action_type='move_product';
				command+="&command="+action_type+"_confirm";
 	 	 	}
 	 	 if(command!="") do_post_command('new_product',command);	 	 	 	 	 	  
 	 	 return false;
 	 }	
	function getdetails(page,orders){
		order=orders;
		if(document.getElementById("load_details")) document.getElementById("load_details").style.display="";
		command="<?php echo tep_href_link($product_filename,'command=fetch_details');?>&page="+ page + "&panel="+panel_expand+ '&cID='+panel_expand_id + '&order=' +order;
		do_get_command(command);
	}		
	function get_details(type,cID,pID,page,params){		
		if(document.getElementById("load_details")) document.getElementById("load_details").style.display="";
		if(type=='PM')
			command="<?php echo tep_href_link($product_filename,'command=fetch_details');?>&page="+ page + "&panel="+panel_expand+ '&cID='+panel_expand_id+'&pID='+pID ;
		do_get_command(command);
	}
	function display_popup(){
		if(document.getElementById("display_menu"))document.getElementById("display_menu").style.display = "";
	}
	function display_null(){
		if(document.getElementById("display_menu"))document.getElementById("display_menu").style.display = "none";
	}		
	function do_jump_list(nopen_ids){
		if (open_ids.join(",")==nopen_ids) return;
		nopen_idsb=nopen_ids.split(",");
		nopen_ids=nopen_ids.split(",");
		for (icnt=0,n=nopen_ids.length;icnt<n;icnt++){
			if(icnt>=open_ids.length || nopen_ids[icnt]!=open_ids[icnt]){
				open_cat=nopen_ids[icnt];
				nopen_ids=nopen_ids.slice(icnt);
				break;
			}
		}
		if (open_ids[icnt]) hide_cat_panel(open_ids[icnt]);
		if(icnt<=open_ids.length && open_ids[nopen_ids.length-1]==nopen_ids[nopen_ids.length-1]) {
			hide_cat_panel(open_ids[nopen_ids.length]);
			open_ids=nopen_idsb;
		} else {
			open_ids=nopen_idsb;
			show_cat_panel(open_cat);
			do_page_fetch('fetch_sub_list_open','',open_cat,1,icnt);
		}
	}	
	function do_page_fetch(cmd,params,category,page,level){			
		command="<?php echo tep_href_link($product_filename,'do=1');?>&command="+cmd;
		if(document.getElementById("img_new") && category) document.getElementById("img_new").onclick=function () {expand_category(category,2);}		
		if(cmd=="fetch_sub_list" && document.getElementById("cPath") && document.getElementById("product_id")) {
			c_ids=document.getElementById("cPath").value;
			p_ids=document.getElementById("product_id").value;
			if(document.getElementById("data_"+p_ids+"_"+c_ids)) document.getElementById("data_"+p_ids+"_"+c_ids).style.display="";
			if(document.getElementById("head_"+p_ids+"_"+c_ids)) document.getElementById("head_"+p_ids+"_"+c_ids).style.display="none";			
			if(c_ids && document.getElementById("category_details_"+c_ids)) document.getElementById("category_details_"+c_ids).style.display="none";		
		}				
		if(document.getElementById("cPath")) document.getElementById("cPath").value=category; 
		if (cmd=="fetch_sub_list" || cmd=="fetch_sub_list_open"){
			command=command+"&cID="+category+"&level="+ level;
			if (cmd=="fetch_sub_list_open") command=command+"&open_ids="+open_ids.join(",");
			document.getElementById("panel_"+category+"_content").innerHTML=document.getElementById("ajax_load_img").innerHTML;
			
			if(document.getElementById("new_category")){
			   document.getElementById("new_category").innerHTML="";	
			   document.getElementById('img_new').firstChild.src=document.getElementById('img_new').firstChild.src.replace('_hover.gif','.gif');
			}
		} else if (cmd=="fetch_all_list"){
			//show_order.value=params;
			command=command+"&cID=-1&page=1&level=1&open_ids="+open_ids.join(",");
			hide_cpopup(true);
			document.getElementById("panel_"+open_ids[0]+"_content").innerHTML=document.getElementById("ajax_load_img").innerHTML;
		} else {
			if (page=='next') {
				pgCombo=document.getElementById("page_"+category);
				selIndex=pgCombo.selectedIndex+1;
				if (selIndex>=pgCombo.options.length) return;
				pgCombo.selectedIndex=selIndex;
			} else if (page=='prev'){ 
				pgCombo=document.getElementById("page_"+category);
				selIndex=pgCombo.selectedIndex-1;
				if (selIndex<0) return;
				pgCombo.selectedIndex=selIndex;
			}  
			page=document.getElementById("page_"+category).value;
			command=command+'&page='+page+'&'+params;
			document.getElementById("panel_"+category+"_contentp").innerHTML=document.getElementById("ajax_load_img").innerHTML;
		}
		do_get_command(command);
		
	}	
    /*ajax action code start */	
	function expand_category(id,category,category_id){
	    if(category==2){
		  document.getElementById('img_new').firstChild.src==img_src+'template/img_new_hover.gif'; 
		//document.getElementById('img_new').firstChild.src=document.getElementById('img_new').firstChild.src.replace('.gif','_hover.gif');
	}
	if(category==1){
	document.getElementById('img_new').firstChild.src=document.getElementById('img_new').firstChild.src.replace('_hover.gif','.gif');
	}
		var res_display=document.getElementById("res_display");		
		if(document.getElementById("image_type")) document.getElementById("image_type").value='';
		
		if(id==0 && category==2 && document.getElementById("cPath") && document.getElementById("cPath").value>0) id=document.getElementById("cPath").value;		
		if(((category>=3 && category<=5) || (category>=11 && category<=13)) && document.getElementById("cPath") && document.getElementById("product_id") && document.getElementById("data_"+document.getElementById("product_id").value+"_"+document.getElementById("cPath").value) && document.getElementById("head_"+document.getElementById("product_id").value+"_"+document.getElementById("cPath").value)) {			
			document.getElementById("data_"+document.getElementById("product_id").value+"_"+document.getElementById("cPath").value).style.display="";
			document.getElementById("head_"+document.getElementById("product_id").value+"_"+document.getElementById("cPath").value).style.display="none";	
		}
		if(category==1 && category_id && document.getElementById("cPath") && document.getElementById("cPath").value!=category_id) toggle_category_panel(document.getElementById("cPath").value,0,true);						
		var c_obj=document.getElementById("cPath");
 	 	if(category_id>0 && document.new_product && document.new_product.product_id){
			var old_ids=document.new_product.product_id.value;
			var old_cate_id=c_obj.value;
			var pre_data_id=document.getElementById("data_"+old_ids+"_"+old_cate_id);
			var pre_head=document.getElementById("head_"+old_ids+"_"+old_cate_id);
			if(category!=11 && document.getElementById("span_details_"+category_id)) {
				document.getElementById("span_details_"+category_id).innerHTML="";
				if(document.getElementById("img_save_"+category_id)) document.getElementById("img_save_"+category_id).style.display="none";
			}
			var loadings=document.getElementById("loading");
			if(loadings) loadings.style.display="none";
			if(category_id>0 && category==1 && pre_head && pre_head.innerHTML!='') pre_head.innerHTML="";
		}
		if(category_id>0 && id>0 && document.new_product && document.new_product.product_id && document.new_product.product_id.value=="") document.new_product.product_id.value=id;
		else if(id>0 && (category>=2 || category<=5) && document.new_product && c_obj && c_obj.value=="") c_obj.value=id;
			if(category_id>0 && id>0 && document.new_product.product_id.value>0 && document.new_product.product_id.value!=id){
			   var head=document.getElementById("head_"+id+"_"+old_cate_id);
			   if(head && head.style.display=="") head.style.display="none";
			   if(category!=12 &&  category!=13 && pre_data_id && pre_data_id.style.display=="none") pre_data_id.style.display="";
			   document.new_product.product_id.value=id;
		}
		if(category_id>0 && category<11 || category>15 && c_obj) c_obj.value=category_id;
		if(!category_id && document.getElementById("span_details_"+id)) {
			var img_div=document.getElementById("img_save_"+id);
			document.getElementById("span_details_"+id).innerHTML="<img src='images/24-1.gif' alt='Loading...' title='Loading...'>";		
		}
 	 	if(category){
 	 		if(!category_id && c_obj) var cid=c_obj.value;
 	 		var span_details=document.getElementById("span_details");
			var data_id=document.getElementById("data_"+id+"_"+category_id);
			var head=document.getElementById("head_"+id+"_"+category_id);
			var action_type=document.getElementById("action_type");
			var img_save=document.getElementById("img_save");
			var img_new=document.getElementById("img_new");
			var command="";
			if(span_details && category_id>0 && category<11 || category>15) span_details.innerHTML="<img src='images/24-1.gif' alt='Loading...' title='Loading...'>";
 	 		if(category>=2 && category<=5) if(img_div) img_div.style.display="";
 	 	 	if(img_new && category>=3 && category<=5 || category==11 || category==12 || category==13){
				
 	 			img_new.href="javascript:expand_category("+((category==11)?category_id:id)+",2);";
 	 		}else if(category_id>0 && img_new && (category==1 && category_id>0) || category>=6 && category<=10 && category==14 && category==15){ 	 			 	 		
				
 	 			img_new.href="javascript:expand_category("+id+",6,"+category_id+");";
			}
 	 		switch(category){
 	 			case 1:
					if(category_id=='reset'){ 
					command="<?php echo tep_href_link($product_filename,'command=reset');?>";					 
					document.getElementById("search").value="";
					}
					var tr1=document.getElementById("img_ids");
					if(document.getElementById("image_type")) document.getElementById("image_type").value="true";
					var imgsrc;
					var node;
					if(tr1)
					for(var j=0;j<tr1.cells.length;j++){
						td=tr1.cells[j];
						imgsrc='';
						for(var i=0;i<td.childNodes.length;i++){
							node=td.childNodes[i];
							if(node.tagName=='IMG'){
								imgsrc=node.src;
								if(imgsrc==img_src+'template/img_edit_hover.gif')
								   td.childNodes[i].src = img_src+'template/img_edit.gif';
								else if(imgsrc==img_src+'template/img_trash_hover.gif')
								   td.childNodes[i].src = img_src+'template/img_trash.gif';
								else if(imgsrc==img_src+'template/img_copy_hover.gif')
								   td.childNodes[i].src = img_src+'template/img_copy.gif';
								else if(imgsrc==img_src+'template/img_move_hover.gif')
								   td.childNodes[i].src = img_src+'template/img_move.gif';
								else if(imgsrc==img_src+'template/img_attrib_hover.gif')
								   td.childNodes[i].src = img_src+'template/img_attrib.gif';
								break;
							}
						}
					}
				
				 if(head && span_details){
 	 			   if(head.style.display==""){
 	 			   	  if(span_details.style.display==""){
 	 			   	    head.style.display="none";
 	 			   	  	data_id.style.display="";
 	 			   	  }else { 
 	 			   	  	if(span_edit.style.display=="") span_edit.style.display="none";
 	 			   	  	if(span_delete.style.display=="") span_delete.style.display="none";
 	 			   	  	if(span_copy.style.display=="") span_copy.style.display="none";
 	 			   	  	if(span_move.style.display=="") span_move.style.display="none"; 	 			   	  	 	 			   	  
 	 			   	  	span_details.style.display=""; 	 			   	  	 
						if(img_save) img_save.style.display="none";
						if(document.getElementById("img_ids")){
 	 			   	  		var tr_obj=document.getElementById("img_ids");
 	 			   	  		if(tr_obj.childNodes[1].childNodes[0].src==img_src+'template/img_edit_hover.gif')	
 	 			   	  			tr_obj.childNodes[1].childNodes[0].src=img_src+'template/img_edit.gif';
 	 			   	  		else if(tr_obj.childNodes[2].childNodes[0].src==img_src+'template/img_trash_hover.gif')	
 	 			   	  			tr_obj.childNodes[2].childNodes[0].src=img_src+'template/img_trash.gif';	
 	 			   	  		else if(tr_obj.childNodes[3].childNodes[0].src==img_src+'template/img_copy_hover.gif')	
 	 			   	  			tr_obj.childNodes[3].childNodes[0].src=img_src+'template/img_copy.gif';	 	 			   	  			  	 			   	  		
  	 			   	  		else if(tr_obj.childNodes[4].childNodes[0].src==img_src+'template/img_move_hover.gif')	
		 			   	  		tr_obj.childNodes[4].childNodes[0].src=img_src+'template/img_move.gif';		
 	 			   	  	} 	 			   	  	
 	 			   	  }	 	
 	 			   }else {
	 	 			 	if(head) head.style.display=="";
	 	 			 	if(pre_data_id && pre_data_id.style.display=="none") pre_data_id.style.display="none";
	 	 			 	if(!category_id) {
	 	 			 		if(img_div) img_div.style.display="none";
	 	 			 		command="<?php echo tep_href_link($product_filename,'command=get_category_details');?>&cID="+id;
	 	 			 	}else if(category_id>0) command="<?php echo tep_href_link($product_filename,'command=get_details');?>&pID="+id+"&cID="+category_id;		 	 			 	
 	 			   }
 	 			 }else {
 	 			 	if(pre_head && pre_head.style.display=="") pre_head.style.display="none"; 	 			 	
 	 			 	if(data_id && data_id.style.display=="")   data_id.style.display="none";
 	 			 	if(head) {
 	 			 	 	head.style.display=""; 	 			 	 	 	 			 	  	 			
 	 			 		head.innerHTML="<img src='images/24-1.gif' alt='Loading...' title='Loading...'>";	 			 		
 	 			 	} 	 			 	 				
					if(!category_id) {
						if(img_div) img_div.style.display="none";
						command="<?php echo tep_href_link($product_filename,'command=get_category_details');?>&cID="+id;
					}else if(category_id>0) command="<?php echo tep_href_link($product_filename,'command=get_details');?>&pID="+id+"&cID="+category_id;						
 	 			 }
 	 			break;
 	 			case 2: 	
 	 					if(action_type) action_type.value='new_category'; 	 					 	 					  	 			
 	 					if(cid && document.getElementById("img_save_"+cid)) 
							document.getElementById("img_save_"+cid).style.display="";	
 	 					command="<?php echo tep_href_link($product_filename,'command=edit_category');?>&chk_ID="+cid; 
 	 			break; 	 			
 	 			case 3:	 
 	 					if(action_type) action_type.value='edit_category'; 	 					 	 					 	 				
 	 					command="<?php echo tep_href_link($product_filename,'command=edit_category');?>&cID="+id; 
						
 	 			break;
 	 			case 4:	 	 				 	 				
 	 					if(action_type) action_type.value='delete_category'; 	 					 	 		 	 				 	 					 	 					
 	 					command="<?php echo tep_href_link($product_filename,'command=delete_category');?>&cID="+id; 	 	 	 					 	 			
 	 			break;
				case 5:	 	 				 	 				
 	 					if(action_type) action_type.value='move_category'; 	 					
 	 					command="<?php echo tep_href_link($product_filename,'command=move_category');?>&cID="+id; 	 	 	 					 	 			
 	 			break;
 	 			case 6:	 	
 	 					if(action_type) action_type.value='new';
 						command="<?php echo tep_href_link($product_filename,'command=edit_product');?>&cID="+category_id;
 	 			break; 	 			 	 	
 	 			case 7:	 	 				 	 				
 	 					if(action_type) action_type.value='edit';
 	 					command="<?php echo tep_href_link($product_filename,'command=edit_product');?>&pID="+id+"&cID="+category_id; 	 	 	 					 	 			
 	 			break;
 	 			case 8:	 	 				 	 				
 	 					if(action_type) action_type.value='delete';
 	 					command="<?php echo tep_href_link($product_filename,'command=delete_product');?>&pID="+id+"&cID="+category_id; 	 	 	 					 	 			
 	 			break;
 	 			case 9:	 	 				 	 				
 	 					if(action_type) action_type.value='copy';
 	 					command="<?php echo tep_href_link($product_filename,'command=copy_product');?>&pID="+id+"&cID="+category_id; 	 	 	 					 	 			
 	 			break;
 	 			case 10:	 	 				 	 				
 	 					if(action_type) action_type.value='move';
 	 					command="<?php echo tep_href_link($product_filename,'command=move_product');?>&pID="+id+"&cID="+category_id; 	 	 	 					 	 			
 	 			break;									
				case 11:
					if(img_src && id){ 	 					 	 					
 	 					if(img_src+"template/icon_active.gif"==id.src){
 	 						id.src=img_src+"template/icon_inactive.gif"; 	 						 	 						
 	 						command="<?php echo tep_href_link($product_filename,'command=setflag');?>&cID="+category_id+"&flag=0";
 	 					}else if(img_src+"template/icon_inactive.gif"==id.src){
 	 						id.src=img_src+"template/icon_active.gif";
 	 						command="<?php echo tep_href_link($product_filename,'command=setflag');?>&cID="+category_id+"&flag=1";
 	 					} 	 						
 	 				}
				break;													
				case 12:				
				case 13:
					var s_order=document.new_product.sort_order;
					if(head) var tbl_tr_obj=head.parentNode.parentNode;
					var pos_cnt=0;					
					pos="pos_up";
					if(category==13) pos="pos_down";
					command="<?php echo tep_href_link($product_filename);?>?cID="+id+"&pos="+category_id+"&command="+pos;					
					if(s_order) s_order.value=pos;
					if(tbl_tr_obj && (tbl_tr_obj.rowIndex==0 && pos=='pos_up' || tbl_tr_obj.parentNode.rows.length==tbl_tr_obj.rowIndex+1 && pos=='pos_down')) return;
				break;
				case 14:
					if(img_src && id){ 	 					 	 					
 	 					if(c_obj) cat_id=c_obj.value; 	 					
 	 					var data_id=document.getElementById("data_"+category_id+"_"+cat_id); 	 					 	 					
 	 					if(data_id) var img=data_id.childNodes[0]; 	 					 	 				
 	 					if(img_src+"template/icon_active.gif"==id.src){
 	 						img.src=id.src=img_src+"template/icon_inactive.gif"; 	 						 	 						
 	 						command="<?php echo tep_href_link($product_filename,'command=setflag');?>&pID="+category_id+"&flag=0";
 	 					}else if(img_src+"template/icon_inactive.gif"==id.src){
 	 						img.src=id.src=img_src+"template/icon_active.gif";
 	 						command="<?php echo tep_href_link($product_filename,'command=setflag');?>&pID="+category_id+"&flag=1";
 	 					} 	 						
 	 				}
				break;
				case 15:
					if(img_src && id){ 	 					 	 					 	 					 	 				 	 					
 	 					if(img_src+"template/icon_active.gif"==id.src){
 	 						id.src=img_src+"template/icon_inactive.gif"; 	 						 	 						
 	 						command="<?php echo tep_href_link($product_filename,'command=setflag_sticky');?>&pID="+category_id+"&flag=0";
 	 					}else if(img_src+"template/icon_inactive.gif"==id.src){
 	 						id.src=img_src+"template/icon_active.gif";
 	 						command="<?php echo tep_href_link($product_filename,'command=setflag_sticky');?>&pID="+category_id+"&flag=1";
 	 					} 	 						
 	 				}
				break;		
 	 		}
 	 		if(command!="") do_get_command(command);
 	 	} 	 
	  }
		function do_result(result){ 
			var token="";
			var token_cat_new="";
			var pre_result="";
		
			token=result.split('^^^^');
			pre_result=token[0];
		
			switch(token[0]){
				case 'new_news':
				document.getElementById("action_type").value='new';
				result=token[1];
				break;
			}
			if (result=="") return; 
			if(result.substr(0,7)=='command'){
				result_splt=result.split("{}");			
				command="";
				category_id="";
				for (icnt=0;icnt<result_splt.length;icnt++){
					command_splt=result_splt[icnt].split("||");
					if(command_splt[1]=='search_details' && document.getElementById("search_details"))  document.getElementById("search_details").innerHTML=result_splt[1];						
					switch (command_splt[0]){
						case 'category':
							category_id=command_splt[1];
							break;
						case 'command':
							command=command_splt[1];
							break;
						case 'page':
							document.getElementById("panel_"+category_id+"_pgno").value=command_splt[1];
							break;
						case 'result':
							if (command=="fetch_sub_products"){
								document.getElementById("panel_"+category_id+"_contentp").innerHTML=command_splt[1];
							} else {
								document.getElementById("panel_"+category_id+"_content").innerHTML=command_splt[1];
							}				    				  	 				  						  			
					}
				}
			}else {
				var res_display=document.getElementById("res_display");
				var span_product="";
				var span_category="";
				var action_type=""; 
				
				if(result!="" && pre_result!="" && pre_result=='cat'){ 
					result_cat=result.split('^^^^');
					//document.getElementById("new_category").innerHTML=result_cat[1];
					//document.getElementById("new_category").style.display='';
				}
				
				if(document.getElementById("cPath")) var category_id=document.getElementById("cPath").value;
				if(document.getElementById("product_id").value) var pID=document.getElementById("product_id").value;
				if(document.getElementById("action_type"))  action_type=document.getElementById("action_type").value;
				if(document.getElementById("span_details_"+category_id)) span_category=document.getElementById("span_details_"+category_id);
				if(document.getElementById("span_details")) span_product=document.getElementById("span_details");
				//
				if(!span_category  && result!="" && document.getElementById("new_category")){
					document.getElementById("new_category").innerHTML=result;
					document.getElementById("new_category").style.display='';
				}
				
				if(result.substr(0,11)=='get_details'){ chk_faq_flag=1; print_details('get_details',result.substr(11));  }
				else if(result.substr(0,17)=='new_main_category') print_details('new_main_category',result.substr(17));
				else if(result.substr(0,12)=='new_category') print_details('new_category',result.substr(12));
				else if(result.substr(0,13)=='edit_category') print_details('edit_category',result.substr(13));
				else if(result.substr(0,21)=='move_category_success') print_details('move_category_success',result.substr(21));
				else if(result.substr(0,18)=='sort_order_success') print_details('sort_order_success',result.substr(18));
				else if(result.substr(0,16)=='sort_order_error') print_details('sort_order_error',result.substr(16));
				else if(result.substr(0,23)=='delete_category_success') print_details('delete_category_success',result.substr(23));		 	
				else if(result.substr(0,11)=='new_product') print_details('new_product',result.substr(22));
				else if(result.substr(0,12)=='copy_details') print_details('copy_details',result.substr(12));
				else if(result.substr(0,20)=='move_product_success') print_details('move_product_success',result.substr(20));
				else if(result.substr(0,14)=='delete_success') print_details('delete_success',result.substr(14));
				else if(result!="" && (action_type=='new' && !document.getElementById("data_"+pID+"_"+category_id)) || action_type=='new_category' || action_type=='edit_category' || action_type=='delete_category' || action_type=='move_category') {
					if(result.substr(0,3)=='new') {			
						if(document.getElementById("action_type")) document.getElementById("action_type").value='new';					
						else if(document.getElementById("img_save_"+category_id)) document.getElementById("img_save_"+category_id).style.display=""; 
						result=result.substr(3);
					}
					span_category.innerHTML=result;
				}else if(action_type=='new' || action_type=='edit' || action_type=='edit' || action_type=='delete' || action_type=='copy' || action_type=='move'){
					if(document.getElementById("img_save")) document.getElementById("img_save").style.display="";
					span_product.innerHTML=result;	 	 		
				}
			}	
		
		}
	  function print_details(category,result){
 	 	var no_result=""; 	 	
 	 	if(result.substr(0,15)=='No Record Found'){
 	 		no_result="No Record Found";
 	 	}else if(result!='' && result.length>0 && category!='edit' && result.substr(0,15)!='No Record Found'){ 	 		
 	 		splt_str=result.split("{}");
 	 		var  categories_id="";
 	 		var  categories_name=""; 
			var  sort_order="";
			var  catagory_status="";					 	 				
 	 		var  product_id="";
			var  product_name="";
			var  product_description="";
			var  product_date_added="";
			var  product_last_modified="";
			var  product_date_available="";
			var  product_image="";			
			var  product_status="";
			var  product_sticky="";
 	 		categories_id=splt_str[0];
 	 		categories_name=splt_str[1]; 
			sort_order=splt_str[2];
			catagory_status=splt_str[3];
 	 		product_id=splt_str[4];
			product_name=splt_str[5];
			newsdesk_description=splt_str[6];
			product_date_added=splt_str[7];
			product_last_modified=splt_str[8];
			product_date_available=splt_str[9];
			product_image=splt_str[10];
			product_status=splt_str[11];
			product_sticky=splt_str[12];
 	 	}
 	 	var c_obj=document.getElementById("cPath");
 		if(document.new_product && document.new_product.product_id) var id=document.new_product.product_id.value;
 		if(document.new_product && c_obj) var category_id=c_obj.value;
 		var span_details=document.getElementById("span_details");
 		var head=document.getElementById("head_"+id+"_"+category_id);
 		var data_id=document.getElementById("data_"+id+"_"+category_id);
 		var res_display=document.getElementById("res_display");
		var img_save=document.getElementById("img_save");
		if(img_save && category!='get_details') img_save.style.display="";
		if(c_obj) category_id=c_obj.value;	
 	 	switch(category){ 	 		 	 		 	 		
 	 		case 'get_details': 	  	 				 	 	 	 			 	 		 	 		 	 		
 	 		if(head  && no_result!="")
				head.innerHTML=no_result; 	 			 	 		
 	 		else if(head && no_result==""){
	 	 		if(head && data_id) {
					head.style.display="";
					data_id.style.display="none";
				}				
				if(data_id) data_id.innerHTML='<img src="images/template/'+((product_status==0)?'icon_inactive.gif':'icon_active.gif')+'" border="0"><img src="images/pixel_trans.gif" alt="" border="0" height="10" width="5">'+product_name;
				head.innerHTML="<table width='100%' border='0' cellspacing='0' cellpadding='0'>"+
				"<tr height='40' class='openContent_top'>"+
				  "<td width='20'></td>"+				  
				  "<td width='20' valign='middle' align='center'><img onclick='javascript:expand_category(this,14,"+product_id+");' src='images/template/"+((product_status==0)?"icon_inactive.gif":"icon_active.gif")+"' "+((product_status==0)?'alt=Inactive title=Inactive':'alt=Active title=Active')+"></td>"+				  
				  "<td align='left' width='20' valign='middle' class='main'><img onclick='javascript:expand_category(this,15,"+product_id+");' src='images/template/"+((product_sticky==0)?"icon_inactive.gif":"icon_active.gif")+"' "+((product_sticky==0)?'alt=Inactive title=Inactive':'alt=Active title=Active')+"></td>"+ 
				  "<td width='350' class='main'>"+((product_name.length>20)?product_name.substr(0,20)+'...':product_name)+"</td>"+
				  "<td width='470' align='left' class='main' valign='top'>"+
				  	"<table border='0' cellspacing='0' cellpadding='0' style='height:30px;display:inline;position:relative;'>"+
				  	 "<tr class='img_move' id='img_ids'>"+
						 "<td class='cell_bg_popm_left' width='10'>&nbsp;</td>"+
						 "<td width='10%' onclick='javascript:change_class(this,1);expand_category("+product_id+",7,"+categories_id+");' onmouseover='javascript:change_class(this,2);' onmouseout='javascript:change_class(this,3);'><img alt='Edit' title='Edit' src='images/template/img_edit.gif'></td>"+
						 "<td width='10%' onmouseover='javascript:change_class(this,2);' onmouseout='javascript:change_class(this,3);' onclick='javascript:change_class(this,1);expand_category("+product_id+",8,"+categories_id+");'><img alt='Delete' title='Delete' src='images/template/img_trash.gif'></td>"+
						 "<td width='10%' onmouseover='javascript:change_class(this,2);' onmouseout='javascript:change_class(this,3);' onclick='javascript:change_class(this,1);expand_category("+product_id+",9,"+categories_id+");'><img alt='Copy' title='Copy' id='img_copy' src='images/template/img_copy.gif'></td>"+					
						 "<td width='10%' onmouseover='javascript:change_class(this,2);' onmouseout='javascript:change_class(this,3);' onclick='javascript:change_class(this,1);expand_category("+product_id+",10,"+categories_id+");'><img alt='Move' title='Move' id='img_move' src='images/template/img_move.gif'></td>"+
						 "<td class='cell_bg_popm_right' width='10'>&nbsp;</td>"+
				  	 "</tr>"+
				  	"</table>"+
				  "</td>"+
				  "<td width='240' align='right' id='img_save' style='display:none'><input type='image' src='images/template/img_savel.gif' alt='save' title='save' border='0'>&nbsp;&nbsp;<a href='javascript:expand_category("+product_id+",1,"+categories_id+")'><img src='images/template/img_closel.gif' alt='close' title='close' border='0'></a>&nbsp;&nbsp;</td>"+
				"</tr>"+
				"<tr style='height:10'><td id='loading' style='display:none' colspan='5'><img src='images/24-1.gif' alt='Loading...' title='Loading...'></td></tr>"+				
				"<tr>"+
				  "<td colspan='8' class='main'><span style='display:none' id='span_edit'></span><span style='display:none' id='span_delete'></span><span id='span_details'>"+	"<table width='100%' border='0' cellspacing='0' cellpadding='0'>"+
				  		"<tr>"+
				  		   "<td width='30'>"+((product_image!="")?"<img width='100' height='100' src='../images/"+product_image+"'>":'')+"</td>"+				  			
				  			"<td width='200' valign='top'>"+
				  				"<table width='100%' border='0' cellspacing='5' cellpadding='0' class='main'>"+
				  					"<tr>"+
				  						"<td align='left' width='50%' valign='top'><?php echo TEXT_DATE_ADDED;?></td>"+
				  						"<td align='left' width='50%' valign='top'>"+product_date_added+"</td>"+
				  					"</tr>"+			  					
				  					"<tr>"+
				  						"<td align='left' width='50%'><?php echo TEXT_LAST_MODIFIED;?></td>"+
				  						"<td align='left' width='50%'>"+product_last_modified+"</td>"+
				  					"</tr>"+
				  					"<tr>"+
				  						"<td align='left' width='50%' nowrap='true'><?php echo TEXT_DATE_AVAILABLE;?></td>"+
				  						"<td align='left' width='50%' nowrap='true'>"+product_date_available+"</td>"+
				  					"</tr>"+				  								  									  								  								  			
				  				"</table>"+			  				
				  			"</td>"+
				  			"<td align='left' valign='top' class='main'><div style='width:690;height:100;overflow:auto;'>"+
				  			  	product_description+			  			  
				  			"</div></td>"+			  			
							"</tr><tr><td><img src='images/pixel_trans.gif' border='0' alt='' width='5' height='15'></td></tr>";
						"</table></span>"+			  			  		
				  "</td>"+
				"</tr>"+																													
			"</table>";
			 if(document.getElementById('action_type')) document.getElementById('action_type').value="";
 	 		} 	 	
 	 	break;
 	 	case 'edit':
			if(span_edit) span_edit.style.display="";
 	 		if(span_edit && span_details){
 	 			span_edit.innerHTML=result;
 	 			span_details.style.display='none';
 	 		}
       <?php 	$languages = tep_get_languages();
		   if (HTML_AREA_WYSIWYG_DISABLE=='Enable') {
			 for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { ?>
		 		initEditor('products_description[<?php echo $languages[$i]['id']; ?>]');
  	   <?php }
		  }?>
		toggle_panel('Cost','false','','0');
		toggle_panel('Stock','false','','0');		
		toggle_panel('Image','false','','0');
		toggle_panel('Attribute_Inventory_control','false','','0');
		change();			
		updateGross();
		init_page();		
 	 	break;
 	 	case 'delete': 	 				 	 	
 	 		if(span_delete) span_delete.style.display="";
 	 		if(span_delete){ 	 			
 	 			span_delete.innerHTML=result; 	 			
 	 			span_details.style.display=span_edit.style.display=span_copy.style.display='none';
 	 		} 	 								   			  
 	 	break; 	 	
 	 	case 'delete_success':			  	    	  	    	
			 if(head && data_id) {
			 	var tbl_obj=head.parentNode.parentNode.parentNode.parentNode;			 				 				 	
			 	if(tbl_obj) tbl_obj.deleteRow(head.parentNode.parentNode.rowIndex);
			 	data_id.innerHTML=head.innerHTML="";
			 	data_id.style.display=head.style.display="none";			 		
			 }			 				
			 if(res_display){ 	    	 	
 	    	 	res_display.innerHTML="<?php echo TEXT_PRODUCT_DELETED_SUCCESS;?>";
 	    	 	res_display.style.display="";
 	    	 }
 	 	break;
		case 'copy':			 	 	
 	 		if(span_copy) span_copy.style.display="";
 	 		if(span_copy){ 	 			
 	 			span_copy.innerHTML=result; 	 			
 	 			span_details.style.display=span_edit.style.display=span_delete.style.display=span_move.style.display='none';
 	 		} 	 										      
 	 	break; 
 	 	case 'copy_error':
 	 	case 'copy_success': 
 	    	 if(res_display){ 	    	 	
 	    	 	var res="";
 	    	 	if(category=='copy_error') {
 	    	 		res_display.style.display=""; 	    	 		
 	    	 		res_display.innerHTML="<?php echo ERROR_CANNOT_LINK_TO_SAME_PRODUCT_CATEGORY;?>";
 	    	 	}else if(category=='copy_success') {
 	    	 		res_display.innerHTML="<?php echo TEXT_PRODUCT_COPIED_SUCCESS;?>";
 	    	 		res_display.style.display="";
	  	 		    if(result==category_id){
						if(token[1])
	  	 		    	toggle_category_panel(result,0,true);	  	 		    	  	 		    	  	 		    	  	 		    
	  	 		    	toggle_category_panel(result,1,true);
	  	 		    }else
	  	 		    	toggle_category_panel(result,0);	  	 		    	  	 		    	  	 		    	  	 		    	  	 		    	
	  	 		    
 	    	 	}  	    	 	
 	    	 	if(head && data_id) {
 	    	 		head.style.display="none";
 	    	 		data_id.style.display="";
 	    	 	} 	    	 	 
 	    	 }
 	 	break;	 	
 	 	case 'move': 	 		 	 	
 	 		if(span_move) span_move.style.display="";
 	 		if(span_move){ 	 			
 	 			span_move.innerHTML=result; 	 			
 	 			span_details.style.display=span_edit.style.display=span_delete.style.display=span_copy.style.display='none';
 	 		} 	 											      
 	 	break;
 	    case 'move_product_success': 	    	 	    	  	    	 
 	    	 if(res_display){ 	    	 	 	    	 	
 	    	 	toggle_category_panel(result,0,true);	  	 		    	  	 		    	  	 		    	  	 		    
	  	 		toggle_category_panel(result,1,true);
 	    	 	if(result!==category_id){	  	 		
	  	 		    toggle_category_panel(category_id,0,true);
	  	 		    toggle_category_panel(result,0);
 	    	 	}
 	    	 	res_display.innerHTML="<?php echo TEXT_PRODUCT_MOVED_SUCCESS;?>";
 	    	 	res_display.style.display=""; 	    	 	
 	    	 } 	    	 
 	    break; 	    			
		case 'sort_order_success':
		case 'sort_order_error':
			if(document.new_product.sort_order) var s_order=document.new_product.sort_order.value;
			if(c_obj) var category_id=c_obj.value;
			if(category=='sort_order_success'){
				var up_href=document.getElementById("sort_order_up");
			    var down_href=document.getElementById("sort_order_down");
			    result_splt=result.split("{}");
			    if(result_splt[0]!="") var new_pos=result_splt[0];			    
			    if(up_href && down_href && new_pos){
					up_href.href="javascript:expand_category("+category_id+",12,"+new_pos+")";
					down_href.href="javascript:expand_category("+category_id+",13,"+new_pos+")";
			    }
				var x=1;
				if(s_order && s_order!="" && s_order=="pos_up") x=-1;
				//var tbl_obj=document.getElementById("search_details").childNodes[1];
				var tbl_obj=document.getElementById("panel_"+category_id+"_pgno").parentNode.parentNode;
				var row=tbl_obj;
				var index=row.rowIndex;				
				var tol_rows=tbl_obj.parentNode.rows.length;								
				if (x==-1 && index==0) return;
				if (x==1 && index==tol_rows-1) return;
				if (x==-1){
				   tbl_obj.parentNode.insertBefore(row,tbl_obj.parentNode.rows[index-1]);				   
				} else {
					if (index==tol_rows-2)
						tbl_obj.parentNode.appendChild(row);
					else{						
						tbl_obj.parentNode.insertBefore(row,tbl_obj.parentNode.rows[index+2]);
					}
				}
			}else if(category=='sort_order_error'){
				res_display.style.display=""; 	    	 	
			    res_display.innerHTML="<?php echo ERROR_IN_SORT_ORDER_TRY_AGAIN;?>"; 	    	 	 	    	 	 	    	 	 	    	 	 
			}
		break;		
		case 'new_product':						
			var pID=0;										
			if(document.getElementById("product_id")) pID=document.getElementById("product_id").value;								
			if(categories_id){																					
    						if(document.getElementById("id_product_list_"+categories_id) && document.getElementById("id_product_list_"+categories_id)){
								var tab_obj=document.getElementById("id_product_list_"+categories_id);
								var row=tab_obj.insertRow(tab_obj.rows.length);
								var row_cell=row.insertCell(0);
								row_cell.innerHTML='<div id="data_'+product_id+'_'+categories_id+'"><img src="images/template/'+((product_status==0)?'icon_inactive.gif':'icon_active.gif')+'" border="0"><img src="images/pixel_trans.gif" alt="" border="0" height="10" width="5">'+product_name+'</div><div class="openContent" id="head_'+product_id+'_'+categories_id+'" style="display: none;"></div>';												
							}
							if(row){
								row.onclick=function () {((document.getElementById("head_"+product_id+"_"+categories_id) && document.getElementById("head_"+product_id+"_"+categories_id).style.display=='none')?expand_category(product_id,1,categories_id):'')};
								row.style.height="15px";
								if(row.rowIndex%2==0) row.className="dataTableRowOdd";
								else row.className="dataTableRowEven";
								row.onmouseover=function (){rowOverEffect(this)};
								row.onmouseout=function (){rowOutEffect(this)};
							}
    						if(res_display){
					    	 	res_display.innerHTML="<?php echo TEXT_PRODUCT_INSERTED_SUCCESS;?>";
				 	    	 	res_display.style.display=""; 	    	 	
 	    	 				}
			}
			if(!document.getElementById("data_"+pID+"_"+categories_id)) expand_category(categories_id,1);
			else expand_category(pID,1,categories_id);
		break;
		case 'copy_details':			
			if(result=='copy_success') result="<?php echo TEXT_PRODUCT_COPIED_SUCCESS;?>";
			if(res_display){							
				res_display.innerHTML=result;
				res_display.style.display="";
			}
		break;
		case 'new_main_category':
		case 'new_category':
			if(result!="") result_splt=result.split('{}');			
			if(document.getElementById("new_category")) document.getElementById("new_category").style.display="none";
			var p_id="";
			var c_name="";
			var c_id="";
			if(result_splt[0]) p_id=result_splt[0];
			if(result_splt[1]) c_name=result_splt[1];
			if(result_splt[2]) c_id=result_splt[2];	
		//	alert(document.getElementById("search_details").tagName);		
			var tbl_obj=document.getElementById("search_details").childNodes[0];
	//		alert(tbl_obj);
			if(document.getElementById("search_details").childNodes.length>1) tbl_obj=document.getElementById("search_details").childNodes[1];
			if(category=='new_category' && c_id>0){
				var tbl_obj=document.getElementById("parent_"+c_id);
				if(document.getElementById("span_details_"+c_id)) document.getElementById("span_details_"+c_id).innerHTML="";	
			}
	//		alert(tbl_obj);
				var row_obj=tbl_obj.insertRow(tbl_obj.rows.length);								
				var row_cell=row_obj.insertCell(0);			
				row_cell.innerHTML='<input id="panel_'+p_id+'_pgno" value="1" type="hidden">'+
				'<table border="0" cellpadding="0" cellspacing="0" width="100%">'+
					'<tbody><tr height="20">'+
					'<td>'+
					'<table border="0" cellpadding="0" cellspacing="0" width="100%">'+
						'<tbody><tr>'+
							'<td class="contentTitle1" style="cursor: pointer;" onclick="javascript:toggle_category_panel('+p_id+',1);"><span id="span_category_'+p_id+'" style="background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; padding-right: 5px;">'+c_name+'</span></td><td align="right" width="15"><a href="javascript:toggle_category_panel('+p_id+',0);"><img src="images/template/panel_down.gif" id="panel_'+p_id+'_img" border="0"></a></td>'+
						'</tr>'+
					'</tbody></table>'+
					'</td>'+
				'</tr><tr height="5"><td></td>'+
				'</tr><tr><td style="padding-left: 15px;">'+
				'<div id="panel_'+p_id+'_content" style="display: none;"></div>'+				
				'</td></tr></tbody></table>';
				row_obj.id="tr_id_"+p_id;				
				if(category=='new_main_category' && category_id>0) toggle_category_panel(category_id,0,true);
				document.getElementById('img_new').firstChild.src=img_src+'template/img_new.gif';
		break;
		case 'edit_category':
			if(result!=""){
				result_splt=result.split("{}");
				if(result_splt[0] && document.getElementById("span_category_"+result_splt[0])) {															
					document.getElementById("span_category_"+result_splt[0]).innerHTML=result_splt[1];	
				}				
				if(document.getElementById('categories_name'))
					document.getElementById('categories_name').innerHTML=result_splt[1];
					if(result_splt[2]!="" && document.getElementById("span_details_"+result_splt[0])){
						document.getElementById("span_details_"+result_splt[0]).innerHTML=result_splt[2];
						if(document.getElementById("img_save_"+result_splt[0])){
							var obj=document.getElementById("img_save_"+result_splt[0]).parentNode;
							//obj.childNodes[7].innerHTML=result_splt[1];							
							document.getElementById("img_save_"+result_splt[0]).style.display="none";
						
							var tr1=document.getElementById("img_ids");
							if(document.getElementById("image_type")) document.getElementById("image_type").value="true";
							var imgsrc;
							var node;
							if(tr1)
							for(var j=0;j<tr1.cells.length;j++){
								td=tr1.cells[j];
								imgsrc='';
								for(var i=0;i<td.childNodes.length;i++){
									node=td.childNodes[i];
									if(node.tagName=='IMG'){
										imgsrc=node.src;
										if(imgsrc==img_src+'template/img_edit_hover.gif')
										   td.childNodes[i].src = img_src+'template/img_edit.gif';
										else if(imgsrc==img_src+'template/img_trash_hover.gif')
										   td.childNodes[i].src = img_src+'template/img_trash.gif';
										else if(imgsrc==img_src+'template/img_copy_hover.gif')
										   td.childNodes[i].src = img_src+'template/img_copy.gif';
										else if(imgsrc==img_src+'template/img_move_hover.gif')
										   td.childNodes[i].src = img_src+'template/img_move.gif';
										else if(imgsrc==img_src+'template/img_attrib_hover.gif')
										   td.childNodes[i].src = img_src+'template/img_attrib.gif';
										break;
									}
								}
							}
						
						}
					}
				}
			
		break;
		case 'delete_category_success':
			if(result!=""){			 	
			 	var tbl_obj=document.getElementById("tr_id_"+result).parentNode;															 				 				 				
			 	if(tbl_obj && result>0 && document.getElementById("tr_id_"+result)) tbl_obj.deleteRow(document.getElementById("tr_id_"+result).rowIndex);			 					 					 					 					 		
			 	if(res_display){ 	    	 	
 	    	 		res_display.innerHTML="<?php echo TEXT_CATEGORY_DELETED_SUCCESS;?>";
 	    	 		res_display.style.display="";
 	    	 	}	
			}
		break;
		case 'move_category_success':
 			if(result!=""){
			 	var category_id=0;
			 	if(c_obj) category_id=c_obj.value;
			 	var tbl_obj=document.getElementById("tr_id_"+category_id).parentNode;
				if(tbl_obj) tbl_obj.deleteRow(document.getElementById("tr_id_"+category_id).rowIndex);
				toggle_category_panel(result,0);
												 					 					 					 					 	
			 	if(res_display){ 	    	 	
 	    	 		res_display.innerHTML="<?php echo TEXT_CATEGORY_MOVED_SUCCESS;?>";
 	    	 		res_display.style.display="";
 	    	 	}	
			}
		break;		
 	 	} 	 	
		if(res_display.style.display=="") setTimeout("res_display.style.display='none'",2000); 	 	 	 	
 	 	clearTimeout();		
	  } 
		function change_class(obj,eve){
			var action_type=""; 
			var img_get_src=obj.childNodes[0];
			var ret_type=false;
			var img_type="";
			
			if(document.getElementById("action_type")) action_type=document.getElementById("action_type").value;		 			 		   		    		    
			
			if(document.getElementById("image_type").value=='true'){
				action_type='';
			}		
			
			if(img_get_src){
				if(img_get_src.src==img_src+'template/img_new.gif') {
					if((action_type=="edit" || action_type=="new_category" || action_type=="new") && eve==1 || eve==2)
					img_get_src.src=img_src+'template/img_new_hover.gif';
				}else if(eve==3 && img_get_src.src==img_src+'template/img_new_hover.gif'){ 	 
					if(action_type=="edit" || action_type=="new_category" || action_type=="new") return;
					else img_get_src.src=img_src+'template/img_new.gif';
				}	
					 
				if(img_get_src.src==img_src+'template/img_edit.gif') {
					if((action_type=="edit" || action_type=="edit_category") && eve==1 || eve==2)
					img_get_src.src=img_src+'template/img_edit_hover.gif';					
				}else if(eve==3 && img_get_src.src==img_src+'template/img_edit_hover.gif'){ 				
					if(action_type=="edit" || action_type=="edit_category") return;
					else img_get_src.src=img_src+'template/img_edit.gif';
				}				
				if(img_get_src.src==img_src+'template/img_trash.gif') {
					if((action_type=="delete" || action_type=="delete_category") && eve==1 || eve==2)
					img_get_src.src=img_src+'template/img_trash_hover.gif';					
				}else if(eve==3 && img_get_src.src==img_src+'template/img_trash_hover.gif'){ 				
					if(action_type=="delete" || action_type=="delete_category") return;
					else img_get_src.src=img_src+'template/img_trash.gif';
				}				
				if(img_get_src.src==img_src+'template/img_copy.gif') {
					if(action_type=="copy" && eve==1 || eve==2)
					img_get_src.src=img_src+'template/img_copy_hover.gif';					
				}else if(eve==3 && img_get_src.src==img_src+'template/img_copy_hover.gif'){ 				
					if(action_type=="copy") return;
					else img_get_src.src=img_src+'template/img_copy.gif';
				}
				if(img_get_src.src==img_src+'template/img_move.gif') {
					if((action_type=="move" || action_type=="move_category") && eve==1 || eve==2)
					img_get_src.src=img_src+'template/img_move_hover.gif';
				}else if(eve==3 && img_get_src.src==img_src+'template/img_move_hover.gif'){
					if(action_type=="move" || action_type=="move_category") return;
					else img_get_src.src=img_src+'template/img_move.gif';
				}
			}		 		 
		}	
</script>
<script language="javascript">
	var display_flag=1;
	function do_hover_change(element,mode){
	if(display_flag==1){
	if (element.mode && mode==element.mode) return;
	if (mode==2)
		element.firstChild.src=element.firstChild.src.replace("_hover.gif",".gif");
	else
		element.firstChild.src=element.firstChild.src.replace(".gif","_hover.gif");
	}
	element.mode=mode;
	}
	function change_new_image(element){
		display_flag=1;
		element.firstChild.src=element.firstChild.src.replace('_hover.gif','.gif');
	}
	
	 function do_close_new_category(){ 
	 	if(document.getElementById("new_category")) 
			document.getElementById("new_category").style.display='none';
		document.getElementById('img_new').firstChild.src=document.getElementById('img_new').firstChild.src.replace('_hover.gif','.gif');
		return false;
	 } 	     	 	  	 	  	 	 
	
</script>
