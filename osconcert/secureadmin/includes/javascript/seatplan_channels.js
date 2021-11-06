/*
       Seatplan Live Logging
  for osConcert, Online Seat Booking
  2011 by Martin Zeitler, Germany
*/

$(function() {
	$("#tabs").tabs();
	setInterval('tickerTick()',$.spd);
});

function tickerTick(){
	$.tab_index = $("#tabs").tabs('option', 'selected');
	$.cPath = parseInt($('ul#channels li:nth-child('+($.tab_index+1)+')').children('div').attr('id').replace('c',''));
	$.last_id = $('input#last_id-'+$.cPath).val();
	$.ajax({
		url:'seatplan_channels_ajax.php?mode=update'+$.ie+'&cPath='+$.cPath+'&id='+$.last_id,
		dataType:'json',
		success:
			function(json){
				$.html ='';$.i=0;
				if(json.length > 0){
					$.each(json,function(i,v){
						if($.i==0){
							$('input#last_id-'+$.cPath).val(v);
						}
						else {
							$.html += '<div class="event_log lvl'+v['log_level']+' new" id="e'+v['event_id']+'">'+
													'<div class="pid">'+((v['products_id']!=0) ? v['products_id']:'&nbsp;')+'</div>'+
													'<div class="cid">'+((v['customers_username']!=null)? v['customers_username']:'Guest')+'</div>'+
													'<div class="sesskey">'+v['sesskey']+'</div>'+
													'<div class="timestamp">'+v['timestamp']+'</div>'+
													'<div class="pname">'+v['products_name']+'</div>'+
													'<div class="event">'+v['event']+'</div>'+
												'</div>'+"\n";
						}
						$.i++;
					});
					$('div#c'+$.cPath).children('.log').prepend($.html);
					$('div#c'+$.cPath).children('.log').children('.new').effect('slide',{},$.spd/5,function(){$(this).removeClass('new');});
				}
				else {
					// console.info('No updates for Channel '+$.cPath);
				}
			}
	});
}

if($.browser.msie){$.ie='_ie';}else{$.ie='';}
$.spd = 2000;