	
	$(function () {
    $('[data-toggle="tooltip"]').tooltip()
    })

	//this only wants to work here!
	jQuery(function() {
		$('#checkout_agree').click(function() {
			if (jQuery(this).is(':checked')) {
				 jQuery('#checkout_button').removeAttr('disabled');
					   } else {
				 jQuery('#checkout_button').attr('disabled', 'disabled');
			}
		});
	});

	jQuery(document).ready(function() {
	  jQuery("form[name=checkout_confirmation]").on('submit',function(e){
	   
		var $form = jQuery(this);

		if ($form.data('submitted') === true) {
		  // Previously submitted - don't submit again
		   e.preventDefault();
		   jQuery('#checkout_button').attr('disabled', 'disabled');
		   
		 // alert('wait');
		} else {
		  // Mark it so that the next submit can be ignored
		  $form.data('submitted', true);
		}
	});

	jQuery(':input[data-loading-text]').click(function () {
		var btn = $(this);
		btn.button('loading');
	   })
	});
	
