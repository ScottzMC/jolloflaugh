

$( document ).ready(function() {

$("#checkAll").click(function () {
    $(".check").prop('checked', $(this).prop('checked'));
});

$('.nextbtn').prop('disabled', true);
$(".step2").hide();

$('.check').change(function(){
    $('.nextbtn').prop('disabled', $('.check:checked').length == 0);
});

$(".nextbtn").click(function () {
				
					$.allchecked = []
				$("input[name='cats_avail[]']:checked").each(function ()
				{
					$.allchecked.push(parseInt($(this).val()));
				});		
    
				if($.allchecked.length === 0){alert("No categories selected. Please do so"); $(".step1").show(); return}				
				$(".step1").hide();
				$(".step1a").show();
					$.ajax({
					url: "seatplan_ajax.php"
					, data: "mode=load_block" + $.ie + "&cPath=" + $.allchecked[0]
					, dataType: "json"
					, success: function (a) {
	
						$(".step2").html(a);
						bindTriggers();
						$(".step1a").hide();
						$(".step2").show();
						
						}
				})      
});
});