<?php 

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
?>
    <section id="triple_boxes" class="section-with-bg wow fadeInUp">
      <div class="container card" style="padding:5px">

        <div class="row">
          <div class="col-lg-8">
			<?php require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes/box_office.php'); ?>
          </div>

          <div class="col-lg-4">
			<?php require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes/shopping_cart.php'); ?>
          </div>
        </div>

      </div>
	  <br>

    </section>