<?php

//use php simple dom class to display an osConcert page
//send the catId of the show that you want to display in the referring url
if (isset ($_GET['catId'])){
//specify the area of the seatplan you want to display 
//this could handle multiple instances if you were to codein another $_GET

$id='clear';// entire seatplan
$id='sp1';//seatplan only

//hardcode in the path to your osConcert installation

$url = '';

//now add the trailing part of the url to get a showplan

$url.= '/index.php?cPath='.$_GET['catId'];

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

//call the simple dom

include_once('includes/simple_html_dom.php');

//setup a variable

$html=file_get_html($url);

//rip out the code that breaks the ajax
foreach ($html->find('dom_remove') as $node)
    {
        $node->outertext = '';
    }

    $html->load($html->save());  
//now create a webpage consider changing ltr and en if reqd
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en">
<head>

<?php 
//start with the <head>
foreach($html->find('head') as $element1) {
       echo $element1;
	   }
// add in an opening <body> tag
?>
<body>
<?php //now show the div/section identified by $id
	   
foreach($html->find('div[id='.$id.']') as $element) {
       echo $element;
	   }
// finally some javascript to call the ajax updater
// n.b. this will only work on the same origin Ajax - so if you do not see a record of purchased
// seats make sure that you are using the same url as your main site - e.g. if this page is at:
// http://yoursite.com/static_page.php then it will only work if osConcert is available at http://yoursite.com
// if it is only available at http://www.yoursite.com then this script will fail
// If you wish to use this static_page.php on a different domain then it should be possible so long
//as your osConcert server is setup to allow CORS and you recode this script accordingly

?>
<script type="text/javascript" src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/js/seatplan_user.min.js"></script>
    <script type="text/javascript">
        function remoteloader() {
    $.ajax({
       // url: $.baseurl + "seatplan_ajax.php?mode=load" + $.ie + "&cPath=" + $.cPath,
	     url:"seatplan_ajax.php?mode=load&cPath=1",
        dataType: "json",
        success: function(e) {
            if (e.cart) {
                if (e.cart.length > 0 && typeof $.timer == "undefined") {
                    $("div#ajax_status").slideDown(200);
                    $.timer = setInterval(countdown, 1e3)
                }
                if (e.cart.length == 0 && typeof $.timer == "number") {
                    clearInterval($.timer);
                    delete $.timer;
                    $("div#ajax_status").slideUp(200).empty();
                    count = $.lifetime
                }
                $.each(e.cart, function(e, t) {
                    if ($("li#s" + t).hasClass("s")) {
                        $.cls = $("li#s" + t).attr("class").match(/(bl|rd|gr|or|fu|ye|sa|sb|te|th|pg)/gi);
                        if ($.cls != null) {
                            $("li#s" + t).removeClass("s").removeClass($.cls.toString()).addClass("y").addClass(flip($.cls.toString()))
                        }
                    }
                })
            }
            if (e.sold) {
                $.each(e.sold, function(e, t) {
                    $("li#s" + t).unbind("click").removeClass("s").removeClass("z").addClass("x")
                })
            }
            if (e.lock) {
                $.each(e.lock, function(e, t) {
                    $("li#s" + t).unbind("click").removeClass("s").addClass("z")
                })
            }
            if (e.prev) {
                $.each(e.prev, function(e, t) {
                    $("li#s" + t).unbind("click").removeClass("x").addClass("o")
                })
            }
            if (e.shopping_box) {
                $("#box_ajaxCart").html(e.shopping_box);
                alert(lng.tooslow)
            }
        }
    })
}

remoteloader();
    </script>
</body>
</html>
<?php
}
?>