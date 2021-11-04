<?php
/*
	osConcert Seat Booking Sofware
	Copyright (c) 2007-2021 https://www.osconcert.com

Released under the GNU General Public License
*/

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();

// Hide footer.php if not to show in maintenance mode

if (DOWN_FOR_MAINTENANCE_FOOTER_OFF =='false') 
{
?>
<!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="footer-top">
      <div class="container">
        <div class="row">

          <div class="col-lg-3 col-md-6 footer-info">
            <img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>osconcert.png" alt="osConcert">
            <p>In alias aperiam. Placeat tempore facere. Officiis voluptate ipsam vel eveniet est dolor et totam porro. Perspiciatis ad omnis fugit molestiae recusandae possimus. Aut consectetur id quis. In inventore consequatur ad voluptate cupiditate debitis accusamus repellat cumque.</p>
          </div>

          <div class="col-lg-3 col-md-6 footer-info">
            <h4>About osConcert</h4>
			<p><strong>Own Your Own Ticket Selling Software</strong><br>
				<strong>You control ALL your profit! </strong> <br>
				</p>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Useful Links</h4>
            <ul>
              <li><i class="fa fa-angle-right"></i> <a href="#">Home</a></li>
             <?php 
			 if (DISPLAY_COLUMN_LEFT == 'no') 
			 {
			require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes/footer_information.php');
			 }
			?>
			<li><i class="fa fa-angle-right"></i> <a href="account.php"><?php echo HEADER_ACCOUNT; ?></a></li>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-contact">
            <h4>Contact Us</h4>
            <p>
             <?php echo STORE_NAME_ADDRESS; ?>
			 <br>
              <strong>Phone:</strong> <?php if (STORE_OWNER_TELEPHONE!=''){echo STORE_OWNER_TELEPHONE;} ?><br>
              <strong>Email:</strong> <a href="mailto:<?php echo STORE_OWNER_EMAIL_ADDRESS; ?>"><?php echo STORE_OWNER_EMAIL_ADDRESS; ?></a><br>
            </p>

			<div class="social-links">
			<?php if (TWITTER_ID!=''){ ?>
			<a target="_blank" href="https://twitter.com/<?php echo TWITTER_ID; ?>"><i class="bi bi-twitter"></i></a>
			<?php } ?>
			<?php if (FACEBOOK_ID!=''){ ?>
			<a target="_blank" href="https://www.facebook.com/<?php echo FACEBOOK_ID; ?>"><i class="bi bi-facebook"></i></a>
			<?php } ?>
			<?php if (INSTAGRAM_ID!=''){ ?>
			<a target="_blank" href="https://instagram.com/<?php echo INSTAGRAM_ID; ?>"><i class="bi bi-instagram"></i></a>
			<?php } ?>
			<?php if (LINKEDIN_ID!=''){ ?>
			<a target="_blank" href="https://ph.linkedin.com/in/<?php echo LINKEDIN_ID; ?>"><i class="bi bi-linkedin"></i></a>
			<?php } ?>
            </div>

          </div>

        </div>
      </div>
    </div>

    <div class="container">
      <div class="copyright">
        <?php echo "Copyright &copy; 2007-".date('Y'); ?> <a href="/"><?php echo STORE_NAME; ?></a>. <?php echo FOOTER_MESSAGE; ?>
      </div>
	  <div class="credits">
	Powered by <a href="https://www.osconcert.com" target="_blank">osConcert</a>.
	</div>
      
    </div>
  </footer><!-- #footer -->
  <?php 
} 
?>