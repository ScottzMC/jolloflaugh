<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License

*/
	defined('_FEXEC') or die();
	function textEditorLoadJS(){
	//save_callback : "TinyMCE_Save",
?>
<script type="text/javascript" src="tiny_mce/tiny_mce.js"></script> 
<script type="text/javascript">
		tinyMCE.init({
			theme : "advanced",
			language : "en",
			mode : "specific_textareas",
			//relative_urls : false,
			relative_urls : true,
			document_base_url :"<?php echo HTTP_SERVER . DIR_WS_CATALOG;?>",
			remove_script_host : false,
			invalid_elements : "script,applet,iframe",
			theme_advanced_toolbar_location : "top",
			theme_advanced_source_editor_height : "600",
			theme_advanced_source_editor_width : "750",
			directionality: "ltr",
			force_br_newlines : "false",
			force_p_newlines : "true",
			debug : false,
			cleanup : true,
			cleanup_on_startup : false,
			safari_warning : false,
			plugins : "advlink, advimage,  preview, searchreplace, insertdatetime,  advhr,  table, layer, style, visualchars,  nonbreaking",
			theme_advanced_buttons2_add : ", preview, search,replace, insertdate, inserttime, emotions, insertlayer, moveforward, movebackward, absolute",
			theme_advanced_buttons3_add : ", advhr, flash, tablecontrols, fullscreen, styleprops, visualchars, media, nonbreaking",
			plugin_insertdate_dateFormat : "%Y-%m-%d",
			plugin_insertdate_timeFormat : "%H:%M:%S",
			plugin_preview_width : "750",
			plugin_preview_height : "600",
			apply_source_formatting : false,
//			extended_valid_elements : "a [id|class|style|title|dir<ltr?rtl|lang|xml::lang|onclick|ondblclick|"
//+ "onmousedown|onmouseup|onmouseover|onmousemove|onmouseout|onkeypress|"
//+ "onkeydown|onkeyup],a[rel|rev|charset|hreflang|tabindex|accesskey|type|"
//+ "name|href|target|title|class|onfocus|onblur],strong/b,em/i,strike,u,"
//+ "p[style],-ol[type|compact],-ul[type|compact],-li,br,img[longdesc|usemap|"
//+ "src|border|alt=|title|hspace|vspace|width|height|align],-sub,-sup,"
//+ "-blockquote,-table[border=0|cellspacing|cellpadding|width|frame|rules|"
//+ "height|align|summary|bgcolor|background|bordercolor],-tr[rowspan|width|"
//+ "height|align|valign|bgcolor|background|bordercolor],tbody,thead,tfoot,"
//+ "#td[colspan|rowspan|width|height|align|valign|bgcolor|background|bordercolor"
//+ "|scope],#th[colspan|rowspan|width|height|align|valign|scope],caption,-div,"
//+ "-span,-code,-pre,address,-h1,-h2,-h3,-h4,-h5,-h6,hr[size|noshade],-font[face"
//+ "|size|color],dd,dl,dt,cite,abbr,acronym,del[datetime|cite],ins[datetime|cite],"
//+ "object[classid|width|height|codebase|*],param[name|value|_value],embed[type|width"
//+ "|height|src|*],script[src|type],map[name],area[shape|coords|href|alt|target],bdo,"
//+ "button,col[align|char|charoff|span|valign|width],colgroup[align|char|charoff|span|"
//+ "valign|width],dfn,fieldset,form[action|accept|accept-charset|enctype|method],"
//+ "input[accept|alt|checked|disabled|maxlength|name|readonly|size|src|type|value],"
//+ "kbd,label[for],legend,noscript,optgroup[label|disabled],option[disabled|label|selected|value],"
//+ "q[cite],samp,select[disabled|multiple|name|size],small,"
//+ "textarea[cols|rows|disabled|name|readonly],tt,var,big",
			disk_cache : true,
			debug : false,
			fullscreen_settings : {
				theme_advanced_path_location : "top"
			}
		});
		function textEditorInit(){
			var icnt,n;

			if (page.editorLoaded) return;
			if (page.editorControls.length<=0) return;

			for (icnt=0,n=page.editorControls.length;icnt<n;icnt++){
				tinyMCE.execCommand("mceAddControl", true, page.editorControls[icnt]);
			}
			page.editorLoaded=true;
		}
		function textEditorHtmlContent(text) {
			tinyMCE.execCommand('mceFocus',false,'message_text');
			tinyMCE.execCommand('mceInsertContent',false,text);
		}

		function textEditorSave(){
			var icnt,n;
			if (!page.editorLoaded) return;
			tinyMCE.triggerSave();
			textEditorRemove();
		}
		function textEditorRemove(){
			var icnt,n;
			
			for (icnt=0,n=page.editorControls.length;icnt<n;icnt++){
				tinyMCE.execCommand("mceRemoveControl", false, page.editorControls[icnt]);
			}
			page.editorControls=[];
			page.editorLoaded=false;
		}
</script>
<?php
	}
?>