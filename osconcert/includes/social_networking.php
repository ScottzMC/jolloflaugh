<?php 

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
?>
<div class="social" style="width:200px">
<div style="float:left"><a href="http://twitter.com/home?status=<?php echo SHAREPAGE_TITLE; ?>+<?php echo SHAREPAGE; ?>" title="Share to Twitter"><img src="<?php echo DIR_WS_TEMPLATES.TEMPLATE_NAME."/images/tweet-icon.png";?>" alt="Twitter" width="55" height="23" /></a>
<a href="http://www.facebook.com/sharer.php?u=<?php echo SHAREPAGE; ?>" title="Share to Facebook"><img src="<?php echo DIR_WS_TEMPLATES.TEMPLATE_NAME."/images/share_facebook.gif";?>" alt="Facebook" width="60" height="24" /></a>
</div>
<!-- enable FB Share from https://developers.facebook.com/docs/plugins/share-button/ -->
<!--<div style="float:right" class="fb-share-button" data-href="<?php echo SHAREPAGE; ?>" data-layout="button_count" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="<?php echo SHAREPAGE; ?>">Share</a></div> -->
</div>

<div class="fb-share-button" data-href="<?php echo FB_URL; ?>" data-layout="<?php echo FB_DATA_LAYOUT; ?>" data-size="<?php echo FB_DATA_SIZE; ?>" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo FB_URL; ?>&amp;src=sdkpreparse">Share</a></div>
