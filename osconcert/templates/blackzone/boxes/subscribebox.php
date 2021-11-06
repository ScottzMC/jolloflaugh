<?php

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();

?>


<?php

	if(!defined('BOX_HEADING_SUBSCRIBE'))define('BOX_HEADING_SUBSCRIBE', 'Subscribe');
	

    $boxcontent = "
            
			<form id=\"form\" name=\"form\" method=\"post\" action=\"mailinglist.php\">
				<div class=\"form-group\">
				<input type=\"text\" name=\"email_address\" class=\"form-control\" id=\"email\" placeholder=\"Name\" required>
				</div>
				<div class=\"form-group\">
				<input type=\"text\" name=\"email_address\" class=\"form-control\" id=\"email\" placeholder=\"Email\" required>
				</div>
				<div style=\"text-align:center;margin:20px\">
				" . tep_template_image_submit('', 'Submit') . "
				</div>
				</form>
              ";

		echo '<div class="card box-shadow">';
		echo '<div class="card-header">';
		echo '<h3>Keep in touch</h3><p>Subscribe to our mailing list to be the first to hear about our updates and promotions</p>';
		echo '</div>';
		echo '<div class="list-group">';
		echo '<div style="padding:5px 3px;">' . $boxcontent . '</div>';
		echo '</div>';
		echo '</div>';
		echo '<br class="clearfloat">';
?>