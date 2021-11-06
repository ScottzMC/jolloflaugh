<?php 

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
?>
    <section id="triple_boxes" class="section-with-bg wow fadeInUp">
      <div class="container card" style="padding:5px">
		<?php if(ALLOW_BO_REFUND=='yes'){
			?>
        <div class="row">
          <div class="col-lg-8">
			<?php require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes/box_office.php'); ?>
          </div>

          <div class="col-lg-4">
			<?php 
			if (!isset($_SESSION['box_office_refund'])) 
		{
			$shopping_cart_query = tep_db_query('select  infobox_display, infobox_file_name from ' . TABLE_INFOBOX_CONFIGURATION . ' where template_id = ' . tep_db_input(TEMPLATE_ID));

				while ($shopping_cart = tep_db_fetch_array($shopping_cart_query)) 
				{
					if (($shopping_cart['infobox_display'] == 'no')&&($shopping_cart['infobox_file_name'] == 'shopping_cart.php')) 
					{
					require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes/shopping_cart.php');
					}
				}
		}
			
			 ?>
          </div>
        </div>
		<?php 
		}else{ echo '<h6>Not Activated</h6>';}
		?>
      </div>
	  <br>

    </section>