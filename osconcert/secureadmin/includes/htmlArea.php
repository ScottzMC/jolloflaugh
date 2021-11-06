<script type="text/javascript" src="htmlarea/htmlarea.js"></script>
<script type="text/javascript" src="htmlarea/editor.js"></script>
<script type="text/javascript">
		function textEditorInit(elements){
			var icnt,n;
			if (!elements || elements.length<=0) return;
			if (page.editorLoaded) return;
			for (icnt=0,n=elements.length;icnt<n;icnt++){
			 	if(document.getElementById(elements[icnt])){
			 		initEditor(elements[icnt]);
				}
			}
			page.editorLoaded=true;
		}
		function textEditorSave(elements){
			var icnt,n,ifr;
			if (!elements || elements.length<=0) return;
			if (!page.editorLoaded) return;
			for(icnt=0,n=elements.length;icnt<n;icnt++){
				var ifr=document.getElementById('if_'+elements[icnt]);
				if(ifr) {
					document.getElementById(elements[icnt]).value=ifr.contentWindow.document.body.innerHTML;
				}
			}
			page.editorLoaded=false;
		}
		
</script>
<?php
	}
?>