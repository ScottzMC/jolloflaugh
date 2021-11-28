<?php

function convertToRegisterFormat($timestamp){
    $diffBtwCurrentTimeAndTimeStamp = (time() - $timestamp);
    $periodsString = ["sec", "min","hr","day","week","month","year","decade"];
    $periodNumbers = ["60" , "60" , "24" , "7" , "4.35" , "12" , "10"];
    for(@@$iterator = 0; $diffBtwCurrentTimeAndTimeStamp >= $periodNumbers[$iterator]; $iterator++)
        @@$diffBtwCurrentTimeAndTimeStamp /= $periodNumbers[$iterator];
        $diffBtwCurrentTimeAndTimeStamp = round($diffBtwCurrentTimeAndTimeStamp);

    if($diffBtwCurrentTimeAndTimeStamp != 1)  $periodsString[$iterator].="s";
        $output = "$diffBtwCurrentTimeAndTimeStamp $periodsString[$iterator]";
        echo "Registered " .$output. " ago";
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<title>View All Users || Fast Food</title>
</head>

<body>

        <!-- Main Content -->
		<div class="page-wrapper">
            <div class="container-fluid">
				<!-- Title -->
				<div class="row heading-bg bg-pink">
					<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
					  <h5 class="txt-light">View All Users</h5>
					</div>
					<!-- Breadcrumb -->
					<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
					  <ol class="breadcrumb">
						<li><a href="<?php echo site_url('admin/dashboard'); ?>">Dashboard</a></li>
						<li class="active"><span>User Grid</span></li>
					  </ol>
					</div>
					<!-- /Breadcrumb -->
				</div>
				<!-- /Title -->

				<!-- Row -->
				<div class="row">
					<div class="col-sm-12">
						<div class="panel panel-default card-view">
							<div class="panel-heading">
								<div class="pull-left">
									<h6 class="panel-title txt-dark">All Users</h6>
								</div>
								<div class="clearfix"></div>
							</div>
							<div class="panel-wrapper collapse in">
    							<div class="panel-body">
    								<div class="table-wrap">
    									<div class="table-responsive">
    										<table class="table display responsive product-overview mb-30" id="myTable">
    											<thead>
    												<tr>
    												<th>Full Name</th>
    												<th>Email</th>
    												<th>Telephone</th>
                                                    <th>Address</th>
    												<th>Postcode</th>
                                                    <th>Town</th>
                                                    <th>Time</th>
                                                    <th>Date</th>
    												</tr>
    											</thead>
    											<tbody>
                                                <?php if(!empty($users)){ foreach($users as $usrow){ ?>
    												<tr>
    													<td><?php echo $usrow->firstname; ?> <?php echo $usrow->lastname; ?></td>
                                                        <td><?php echo $usrow->email; ?></td>
    													<td><?php echo $usrow->telephone; ?></td>
                                                        <td><?php echo $usrow->address; ?></td>
    													<td><?php echo $usrow->postcode; ?></td>
                                                        <td><?php echo $usrow->town; ?></td>
                                                        <td><?php echo convertToRegisterFormat($usrow->created_time); ?></td>
                                                        <td><?php echo date("j M Y", strtotime($usrow->created_date)); ?></td>
                                                    </tr>
                                                <?php } }else{ echo ''; } ?>
    											</tbody>
    										</table>
    									</div>
    								</div>
    							</div>
								
							</div>
						</div>
					</div>
				</div>
				<!-- /Row -->

			</div>

		</div>
        <!-- /Main Content -->
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="<?php echo base_url('vendors/bower_components/jquery/dist/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('vendors/bower_components/bootstrap/dist/js/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo base_url('dist/js/init.js'); ?>"></script>

</body>

</html>
