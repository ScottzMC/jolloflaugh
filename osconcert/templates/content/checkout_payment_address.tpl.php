<?php 
/*
	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License 
*/	

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
include(DIR_WS_INCLUDES.'http.js'); ?>
<?php
/*
echo '<hr>';
var_dump($_POST);
echo '<hr>';
var_dump($FSESSION);
echo '<hr>';
var_dump($FREQUEST->postvalue('address'));
echo '<hr>';
*/

$update = (isset($_GET['update_address']) && (bool)$_GET['update_address'] === true);

$real_address = ($FSESSION->is_registered('billto') ? $FSESSION->billto : $FSESSION->sendto);

if($FREQUEST->postvalue('address') !== '')
{
	$real_address = $FREQUEST->postvalue('address');
	$FSESSION->set('billto',(int)$FREQUEST->postvalue('address'));
}
if(!$update):
?>
<!--here because it works!-->
<script src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/assets/js/osconcert.js"></script>
<style>
	.cf-autocomplete{
		position:relative;
		z-index:1;
		padding:0;
		margin:0 auto;
	}
	
	.cf-autocomplete input{
		position:absolute;
		top:0;
		left:0;
		right:0;
		width:100%;
		z-index:0;
	}
	
	.cf-autocomplete .cf-autocomplete-resuts{
		position:absolute;
		top:29px;
		left:0;
		right:0;
		width:100%;
		background:#FFF;
		padding:0;
		margin:0 auto;
		list-style:none;
		border:#ddd 1px solid;
		box-shadow:#ddd 1px 1px 3px, #ddd -1px 1px 3px;
		z-index:100;
		display:none;
		height:auto;
		max-height:300px;
		overflow:auto;
		overflow-x:hidden;
	}
	
	.cf-autocomplete .cf-autocomplete-resuts > li{
		list-style:none;
		padding:0 5px;
		margin:0;
		display:block;
		text-align:left;
		color:#333;
	}
	
	.cf-autocomplete .cf-autocomplete-resuts > li:hover,
	.cf-autocomplete .cf-autocomplete-resuts > li:focus{
		background:#FFC;
		cursor:pointer;
	}
	
	.cf-autocomplete .cf-autocomplete-resuts > li.active{
		background:#FC3;
	}
	
	.cf-autocomplete .cf-autocomplete-resuts > li > div{

	}
	
	.cf-autocomplete .cf-autocomplete-resuts > li > .cf-autocomplete-resuts-title{
		font-weight:bold;
		padding-top:5px;
	}
	
	.cf-autocomplete .cf-autocomplete-resuts > li > .cf-autocomplete-resuts-value{
		font-weight:400;
		padding-left:10px;
		padding-bottom:5px;
	}
	.cf-autocomplete .cf-autocomplete-clean-button{
		position: absolute;
		right: 10px;
		padding: 0;
		margin: 0;
		top: 50%;
		transform: translate(0,-50%);
		-webkit-transform: translate(0,-50%);
		-moz-transform: translate(0,-50%);
		-ms-transform: translate(0,-50%);
		-o-transform: translate(0,-50%);
		display:none;
		z-index:50;
	}
	.cf-autocomplete:hover .cf-autocomplete-clean-button,
	.cf-autocomplete:focus .cf-autocomplete-clean-button{
		display: inline-flex;
	}
</style>
<?php
endif;
	echo tep_draw_form('checkout_address', tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL'), 'post', 'onSubmit="return check_form_optional(checkout_address);"'); ?>
	
	<div class="section-header">
		<h2><?php echo HEADING_TITLE; ?></h2>
	</div>

<?php
  if ($messageStack->size('checkout_address') > 0) 
  {
?>		<div><?php echo $messageStack->output('checkout_address'); ?></div>
<?php
  }

  if ($process == false && !$update) 
  {
?>
      
	  <br><table width="100%" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo TABLE_HEADING_PAYMENT_ADDRESS; ?></b></td>
          </tr>
        </table>

          <table width="100%" cellpadding="2">
              <tr>
                <td class="main" width="50%" valign="top"><?php echo TEXT_SELECTED_PAYMENT_DESTINATION; ?></td>
                <td align="right" width="50%" valign="top">
				
				<table cellpadding="2">
                  <tr>
                    <td class="main" align="center" valign="top"><?php echo '<b>' . TITLE_PAYMENT_ADDRESS . '</b><br>' . tep_image(DIR_WS_IMAGES . 'arrow_south_east.gif'); ?></td>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
					
					
                    <td class="main" valign="top" id="changeAddress"><?php
					// echo tep_address_label($FSESSION->customer_id, $FSESSION->billto, true, ' ', '<br>');
					$addresses_query = tep_db_query("select address_book_id, entry_firstname as firstname, entry_lastname as lastname, entry_customer_email as customer_email, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where address_book_id = '" . (int)$real_address . "'");
					
					$addresses = tep_db_fetch_array($addresses_query);
					if($addresses!==NULL)
					{
						$format_id = tep_get_address_format_id($addresses['country_id']);
						$addresses = array_map('ucfirst',$addresses);
						$addresses['customer_email'] = strtolower($addresses['customer_email']);
						echo tep_address_format($format_id, $addresses, true, '<br>', ' ');
					}
					?></td>
                    
                  </tr>
                  <tr>
                    <td class="main" align="center" valign="top"></td>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" valign="top" id="changeAddress"><a href="<?php echo HTTP_SERVER . DIR_WS_HTTP_CATALOG; ?>checkout_payment_address.php?update_address=true&id=<?php echo $addresses['address_book_id']; ?>" id="editAddress">[ <?php echo EDIT_ADDRESS; ?> ]</a></td>
                  </tr>
                </table>
				</td></tr>
            
        </table>

<?php

				if($FSESSION->get("customer_country_id") == 999)
			{
		?>
			  <table width="100%" cellpadding="2">
				  <tr>
					<td class="main"><b><?php echo TABLE_HEADING_ADDRESS_BOOK_ENTRIES; ?></b></td>
				  </tr>
				</table>
					
					
					<table width="100%" cellpadding="2">
					  <tr>
						
						<td class="main" width="50%" valign="top"><?php echo TEXT_SELECT_OTHER_PAYMENT_DESTINATION; ?></td>
						<td class="main" width="50%" valign="top" align="right"><?php echo '<b>' . TITLE_PLEASE_SELECT . '</b><br>' . tep_image(DIR_WS_IMAGES . 'arrow_east_south.gif'); ?></td>
					   
					  </tr>
		<?php
			  $radio_buttons = 0;

			   ?>
			  <tr>
				<td colspan="4">

				
			<?php if(!$update): ?>
				<div class="cf-autocomplete" id="input-address" data-default="<?php echo $real_address; ?>">
					<input type="text" name="address" class="form-control">
				</div>
			<?php endif; ?>
			
					</td>
				</tr>
					</table>
		<?php
			}
  }

  if ($addresses_count < MAX_ADDRESS_BOOK_ENTRIES || true) 
  {
?>
      <br><br>
	  <table width="100%" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo ( !$update ? TABLE_HEADING_NEW_PAYMENT_ADDRESS : TABLE_HEADING_EDIT_PAYMENT_ADDRESS ); ?></b></td>
          </tr>
        </table>
		
				
				
				<div><?php echo ( !$update ? TEXT_CREATE_NEW_PAYMENT_ADDRESS : TEXT_CREATE_EDIT_PAYMENT_ADDRESS ); ?>
				</div>
				
	
				<table width="100%" cellpadding="2">
                  <tr>
                    
                    <td><?php 
					if($update)
						require(DIR_WS_MODULES . 'checkout_edit_address.php');
					else
						require(DIR_WS_MODULES . 'checkout_new_address.php');
					?></td>
                    
                  </tr>
                </table>
				

<?php
  }
?>

			
			<table width="100%" cellpadding="2">
              <tr>
               
                <td class="main">
				<?php echo '<b>' . TITLE_CONTINUE_CHECKOUT_PROCEDURE . '</b><br>' . TEXT_CONTINUE_CHECKOUT_PROCEDURE; ?></td>
                <td class="main" align="right">
				<?php echo tep_draw_hidden_field('action', 'submit') . tep_template_image_submit('', IMAGE_BUTTON_CONTINUE); ?>
				</td>
                
              </tr>
            </table></form>
			

<?php
  if ($process == true) 
  {
?>
      <?php echo '<div style="float:left"><a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL') . '">' . tep_template_image_button('', IMAGE_BUTTON_BACK) . '</a></div>'; ?>
<?php
  }
?>

		<div class="bs-stepper">
            <div class="bs-stepper-header">
              <div class="step" data-target="#delivery">
			   <a href="<?php echo tep_href_link('checkout_shipping.php', '', 'SSL'); ?>">
                <button type="button" class="btn step-trigger">
                  <span class="bs-stepper-circle">1</span>
                  <span class="bs-stepper-label"><?php echo CHECKOUT_BAR_DELIVERY; ?></span>
                </button>
				</a>
              </div>
              <div class="line"></div>
              <div class="step active" data-target="#payment">
                <button type="button" class="btn step-trigger">
                  <span class="bs-stepper-circle">2</span>
                  <span class="bs-stepper-label"><?php echo CHECKOUT_BAR_PAYMENT; ?></span>
                </button>
              </div>
              <div class="line"></div>
              <div class="step" data-target="#confirm">
                <button type="button" class="btn step-trigger" disabled="disabled">
                  <span class="bs-stepper-circle">3</span>
                  <span class="bs-stepper-label"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></span>
                </button>
              </div>
            </div>
          </div>
		  


<?php if(!$update): ?>
<script>
(function($){
	$.fn.cfAutocomplete = function(options){
		var $this = this,
			init = $.extend({
				id				: $this.attr('id'),
				url				: "",
				autofill		: false,
				limit			: 5,
				delay			: 300,
				min_length		: 3,
				width_offset	: 0,
				height_offset	: 0,
				width			: null,
				height			: null,
				remove_icon		: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKBAMAAAB/HNKOAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkY0RUVCNzg3QjhDRjExRTI5QUVERjNBQTYyOEFBQkNGIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkY0RUVCNzg4QjhDRjExRTI5QUVERjNBQTYyOEFBQkNGIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6RjRFRUI3ODVCOENGMTFFMjlBRURGM0FBNjI4QUFCQ0YiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6RjRFRUI3ODZCOENGMTFFMjlBRURGM0FBNjI4QUFCQ0YiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz4qOi4KAAAAHlBMVEV/f39/f39/f39/f39/f39/f39/f39/f39/f39/f3+kUy17AAAACnRSTlMAKDM0OW5we3yARTngsgAAADBJREFUCNdjEHVgYAlkyCxmMJ/G4D5dqbKEgbmyY7oBA4P5zGIGKAkRgchCVIJ1AQCz/w6dkmu2HwAAAABJRU5ErkJggg==',
				input			: $this.find('input'),
				label	 		: $.extend({
					not_found 		: '<?php echo NOT_FOUND; ?>',
					placeholder 	: '<?php echo SEARCH_ADDRESS; ?>',
				},options.label)
			},options),
			name = init.input.attr('name'),
			defaults = ($this.attr('data-default') || null),
			debounce,
			changeSize = function(){
				
				$this.css({
					height : '',
					width : '',
				});
				
				var height = init.width || init.input.height(),
					width = init.height || init.input.width();
				// Set size
				$this.css({
					height : (height + init.height_offset) + 'px',
					width : (width + init.width_offset) + 'px',
				});
			};
			
		if(null === defaults)
			dataDefault = '';
		else
			dataDefault = ' value="' + defaults + '"';
		
		changeSize();
		$(window).resize(changeSize);
		
		// Clean and create dropdown container
		$this.find('.cf-autocomplete-resuts').remove();
		init.input.attr({
			'placeholder':init.label.placeholder,
			'name':'cf-autocomplete-' + name
		});
		init.input.after('<a href="javascript:void(0);" class="cf-autocomplete-clean-button" rel="nofollow"><img src="' + init.remove_icon + '"></a>\
							<ul class="cf-autocomplete-resuts" data-id="' + init.id + '"></ul>\
							<input type="hidden" name="' + name + '"' + dataDefault + '>');
		
		// Autofill default
		if(null !== defaults && init.autofill===true)
		{
			$.post(init.url, {limit : 1, search : defaults}).done(function(data){
				if(null !== data)
				{
					$this.find('input[name^="cf-autocomplete-' + name + '"]').val(data[0].label);
				}
			});
		}
		// Detect input changes
		$this.find('input[name^="cf-autocomplete-' + name + '"]').on('paste keyup keydown click touchstart',function(e){
			
			var input = $(this),
				terms = encodeURIComponent(input.val());
			
			clearTimeout(debounce);			
			debounce = setTimeout(function() {
				
				if(null === terms || '' === terms || typeof terms == 'undefined' || (typeof terms != 'undefined' && isNaN(terms) && terms.length < (init.min_length)))
				{
					$('[data-id^="' + init.id + '"]:not(:empty)').html('').hide();
					return false;
				}
				
				$.post(init.url, {
					limit : init.limit,
					search : terms,
				}).done(function(data){
					clearTimeout(debounce);
					if(null!==data)
					{
						var list = [], l=0, i;
		
						for(i=0; i < data.length; i++)
						{
							list[l]='<li data-value="' + data[i].value + '">' +
								'<div class="cf-autocomplete-resuts-title">' + data[i].optgroup + '</div>' +
								'<div class="cf-autocomplete-resuts-value">' + data[i].label + '</div>' +
							'<li>';
							l++;
						}
						
						if(list.length===0)
						{
							list[0]='<li><div class="cf-autocomplete-resuts-title">' + init.label.not_found + '</div><li>';
						}
						
						$('ul[data-id^="' + init.id + '"]').html(list.join("\r\n")).css({
							top : (init.input.height() + init.height_offset) + 'px'
						}).show();
						
						
						// Select -------------------------------------------------------------
						$this.find('li[data-value]').on('click touchstart', function(e){
							e.preventDefault();
							
							var selects = $(this),
								value = selects.attr('data-value'),
								label = selects.find('.cf-autocomplete-resuts-value').text();
							
							clearTimeout(debounce);
							
							$this.find('input[name^="cf-autocomplete-' + name + '"]').val(label);
							$this.find('input[name^="' + name + '"]').val(value);
							$("#editAddress").attr('href', '<?php echo HTTP_SERVER . DIR_WS_HTTP_CATALOG; ?>checkout_payment_address.php?update_address=true&id=' + value);
							$('[data-id^="' + init.id + '"]:not(:empty)').html('').hide();

							$("#changeAddress").html(label);
						});
					}
					else
					{
						$('ul[data-id^="' + init.id + '"]').html('<li><div class="cf-autocomplete-resuts-title">' + init.label.not_found + '</div><li>').css({
							top : (init.input.height() + init.height_offset) + 'px'
						}).show();
					}
				}).fail(function( jqXHR, textStatus ) {
  					console.log( "%cRequest failed: " + textStatus, "color:#cc000; font-size:14px;");
					clearTimeout(debounce);
				});
			
			}, init.delay);
		});
		
		// Hide on click out of element
		$this.parents().on('click touchstart vclick tap', function(){
			clearTimeout(debounce);
			$('ul[data-id^="' + init.id + '"]').html('').hide();
		});
		
		// Clean button
		$this.find('.cf-autocomplete-clean-button').on('click touchstart vclick tap', function(e){
			e.preventDefault();
			clearTimeout(debounce);
			$('ul[data-id^="' + init.id + '"]').html('').hide();
			$this.find('input[name^="cf-autocomplete-' + name + '"]').val('');
		});
	};

//initiate
$("#input-address").cfAutocomplete({
	url : '<?php echo HTTP_SERVER . DIR_WS_HTTP_CATALOG; ?>ajax_checkout_payment_address.php',
	width_offset : 5,
	height_offset : 5,
	label : {
		placeholder : '<?php echo SEARCH_ADDRESS; ?>',
		not_found : '<?php echo NOT_FOUND; ?>'
	}
});

}(window.jQuery || window.Zepto));
</script>
<?php endif; ?>