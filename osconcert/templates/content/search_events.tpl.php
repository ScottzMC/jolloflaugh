<?php 
/*
	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License 
*/	
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
?>
	<div class="section-header">
	<h2><?php echo HEADING_TITLE; ?></h2>
	</div>


<?php 

################### start of actual code that may be needed to transfer to infobox

#### if the language file has not been included then you may need to define some of the  
#### language files 

if (!defined(TEXT_NO_FEATURED_CATEGORIES_START_SEEN))
{
define('TEXT_NO_FEATURED_CATEGORIES_START_SEEN', 'No start date given, please re-enter.');
}
//$EVENTS_DATE_FORMAT=EVENTS_DATE_FORMAT;
if (EVENTS_DATE_FORMAT=='d-m-Y')
{
	$EVENTS_DATE_FORMAT="dd-mm-yy";
}
if (EVENTS_DATE_FORMAT=='m-d-Y')
{
	$EVENTS_DATE_FORMAT="mm-dd-yy";
}
if (EVENTS_DATE_FORMAT=='Y-m-d')
{
	$EVENTS_DATE_FORMAT="yy-mm-dd";
}
?>


 <?php
{
  
  //end edit section start (optional?) input fields
  //this may be added  or removed if infobox in place?
?>
 
	<form id = "date_input" action="featured_categories_bydate.php">
      <div class="container">
	    <div class="row">
          <div class="col-md-4">
            <div>
              <input type="text" class = "date_time" id="dt1" name ="date_start">
			<input type="hidden" class = "date_time_unix" id="dt1_unix" name ="date_start_unix">
            </div>
          </div>
          <div class="col-md-4">
            <div>
              <input type="text" class = "date_time" id="dt2" name = "date_end">
			<input type="hidden" class = "date_time_unix" id="dt2_unix" name ="date_end_unix">
            </div>
          </div>
          <div class="col-md-4">
            <div>
             <input type="submit" class="btn btn-primary" value="<?php echo IMAGE_BUTTON_SEARCH; ?>" />
            </div>
          </div>
        </div>
		</div>
	</form>
    
 
 <?php // end main table display
 }
 ?>
 <script>

document.addEventListener("DOMContentLoaded", function(event) { 


$(document).ready(function () {
    $("#dt1").datepicker({
        dateFormat: '<?php echo $EVENTS_DATE_FORMAT; ?>',
		minDate: 0,
		altField: "#dt1_unix",
        altFormat: "@",
        onSelect: function () {
            var dt2 = $('#dt2');
            var startDate = $(this).datepicker('getDate');
            //add 30 days to selected date
            startDate.setDate(startDate.getDate() + 30);
            var minDate = $(this).datepicker('getDate');
            var dt2Date = dt2.datepicker('getDate');
            //difference in days. 86400 seconds in day, 1000 ms in second
            var dateDiff = (dt2Date - minDate)/(86400 * 1000);

            //dt2 not set or dt1 date is greater than dt2 date
            if (dt2Date == null || dateDiff < 0) {
                    dt2.datepicker('setDate', minDate);
            }
            //dt1 date is 30 days under dt2 date
            else if (dateDiff > 30){
                    dt2.datepicker('setDate', startDate);
            }
            //sets dt2 maxDate to the last day of 30 days window
            dt2.datepicker('option', 'maxDate', startDate);
            //first day which can be selected in dt2 is selected date in dt1
            dt2.datepicker('option', 'minDate', minDate);
        }
    }).datepicker("setDate", "0");
    $('#dt2').datepicker({
        dateFormat: "<?php echo $EVENTS_DATE_FORMAT; ?>",
        minDate: 0,
		altField: "#dt2_unix",
        altFormat: "@"
    });
});

$(document).ready(function(e){
    $('#date_input').on('submit',function(e){
		 e.preventDefault();
		var end = $('#dt2').val();
		
		var x = new Date()
		var y = parseInt( x.getTimezoneOffset()*60*1000);
		var a = parseInt($('#dt1_unix').val());
		var b = parseInt($('#dt2_unix').val());
		
		var start_unix = (Math.floor((a + y)/1000)); 
		var end_unix =   (Math.floor((b - y)/1000));
		//alert (b + "    " + y + "    " + end_unix); return;
		 if(($.isNumeric(start_unix) != true) || (start_unix == 0)){ 
			alert ('<?php echo TEXT_NO_FEATURED_CATEGORIES_START_SEEN;?>');
			return;
		}
		 if(($.isNumeric(end_unix) != true) ){ 
			end_unix = 0;
		}
		 
         $('#dt1_unix').val(start_unix);
		 $('#dt2_unix').val(end_unix);
		 
		 this.submit();
    });
});
});
</script>
