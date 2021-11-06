<?php 

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
?>
<?php
if($cPath>119)
{
tep_redirect(tep_href_link('search_events.php', '', 'SSL'));
}
?>
<section id="performance">
    <div class="container-fluid">

		<div class="section-header">			
            <?php
			
			if($parent_id ==0)
			{
				$cPathminus=$cPath-70;
				$cPathplus=$cPath+70;
				if(NAVIGATE_7DAY_TABS=='yes')
				{
				echo "<h2><a href=\"index.php?cPath=" . $cPathminus . "\"><i class=\"fa fa-chevron-left\"></i></a> " . $concert_date . " <a href=\"index.php?cPath=" . $cPathplus . "\"><i class=\"fa fa-chevron-right\"></i> </a></h2>";
				}else
				{
				echo "<h2> " . $concert_date . " </h2>"; 	
				}	
			}		
			?>
		</div>
		<div class="row">

			<?php
			if($parent_id ==0)
			{
			?>
               <div class="w-100 pt-3">		 
                          <div class="wrapper">
                                <nav id="myTab" role="tablist">
                                    <ul class="nav nav-tabs">
									<li class="nav-item">
									<a class="nav-link active" data-toggle="tab" href="index.php?cPath=<?php echo $cPath?>" role="tab" aria-controls="public" aria-expanded="true">
									<?php echo strftime('%d. %b', strtotime($cdate));?></a>
									</li>
									<li class="nav-item">
                                    <a class="nav-link" href="index.php?cPath=<?php echo $cPath+10?>">
									<?php echo strftime('%d. %b', strtotime('+1 day',strtotime($cdate)));?></a>
									</li>									 
									<li class="nav-item">
                                    <a class="nav-link" href="index.php?cPath=<?php echo $cPath+20?>" role="tab" ><?php echo strftime('%d. %b', strtotime('+2 day',strtotime($cdate)));?></a>
									</li>									 
									<li class="nav-item">
                                    <a class="nav-link" href="index.php?cPath=<?php echo $cPath+30?>" role="tab" ><?php echo strftime('%d. %b', strtotime('+3 day',strtotime($cdate)));?></a>
									</li>									 
									<li class="nav-item">									
                                    <a class="nav-link" href="index.php?cPath=<?php echo $cPath+40?>" role="tab" ><?php echo strftime('%d. %b', strtotime('+4 day',strtotime($cdate)));?></a>
									</li>									 
									<li class="nav-item">
                                    <a class="nav-link" href="index.php?cPath=<?php echo $cPath+50?>" role="tab" ><?php echo strftime('%d. %b', strtotime('+5 day',strtotime($cdate)));?></a>
									</li>									 
									<li class="nav-item">
                                    <a class="nav-link" href="index.php?cPath=<?php echo $cPath+60?>" role="tab" ><?php echo strftime('%d. %b', strtotime('+6 day',strtotime($cdate)));?></a>
									</li>									 
									</ul>
                                </nav>
                          </div>
                     </div>
			<?php
			}
			 ?>
        </div>
     </div>
</section><!-- #performance -->