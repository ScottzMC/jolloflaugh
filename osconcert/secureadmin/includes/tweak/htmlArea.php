<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License

*/
	defined('_FEXEC') or die();
	function textEditorLoadJS(){
?>
<script type="text/javascript" src="htmlarea/htmlarea.js"></script>
<script type="text/javascript" src="htmlarea/editor.js"></script>
<script type="text/javascript">

		function textEditorInit() {
			var icnt,n;
			if (page.editorLoaded) return;
			if (page.editorControls.length<=0) return;
			for (icnt=0,n=page.editorControls.length;icnt<n;icnt++){
			if (page.editorLoaded) return;
			for (icnt=0,n=page.editorControls.length;icnt<n;icnt++){
			 	if(document.getElementById(page.editorControls[icnt])){
			 		initEditor(page.editorControls[icnt]);
				}
			}
			page.editorLoaded=true;
			}
		}
		
		function textEditorSave() {
			textEditorRemove();
		}
		function textEditorHtmlContent(text) {
			editor.insertHTML(text);
			var icnt,n,ifr;
			if (!page.editorLoaded) return;
			//if (page.editorControls.length<=0) return;

			if(page.editorControls.length>0) {
				for (icnt=0,n=page.editorControls.length;icnt<n;icnt++){
					var ifr=document.getElementById('if_'+page.editorControls[icnt]);
					if(ifr) {
						document.getElementById(page.editorControls[icnt]).value=ifr.contentWindow.document.body.innerHTML;
					}
				}
			}
			else {
				var ifr=document.getElementById('if_'+page.editorControls[0]);
				if(ifr) {
					document.getElementById(page.editorControls[0]).value=ifr.contentWindow.document.body.innerHTML;
				}
			}
		}
		function textEditorRemove() {
			var icnt,n,ifr;
			if (!page.editorLoaded) return;
			if(page.editorControls.length > 0) {
				for (icnt=0,n=page.editorControls.length;icnt<n;icnt++){
					var ifr=document.getElementById('if_'+page.editorControls[icnt]);
					if(ifr) {
						document.getElementById(page.editorControls[icnt]).value=ifr.contentWindow.document.body.innerHTML;
					}
				}
			}
			else {
				var ifr=document.getElementById('if_'+page.editorControls[0]);
				if(ifr) {
					document.getElementById(page.editorControls[0]).value=ifr.contentWindow.document.body.innerHTML;
				}
			}
			page.editorControls=[];
			page.editorLoaded=false;
		}
</script>
<?php
	}
?>