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
	<title>View All Messages || Fast Food</title>
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
						<li class="active"><span>Message Grid</span></li>
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
									<h6 class="panel-title txt-dark">All Messages</h6>
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
														<th>OID</th>
														<th>FullName</th>
														<th>Email</th>
														<th>Food Name</th>
                                                        <th>Subject</th>
														<th>Message</th>
														<th>Status</th>
                                                        <th>Time</th>
                                                        <th>Date</th>
                                                        <th>Completed</th>
                                                        <th>Deleted</th>
													</tr>
    											</thead>
    											<tbody>
                                                <?php if(!empty($message)){ foreach($message as $mess){ ?>
													<tr>
														<td>#<?php echo $mess->order_id; ?></td>
														<td><?php echo $mess->firstname; ?> <?php echo $mess->lastname; ?></td>
														<td><?php echo $mess->email; ?></td>
														<td><?php echo str_replace('-', ' ', $mess->order_title); ?></td>
                                                        <td><?php echo $mess->subject; ?></td>
                                                        <td><?php echo $mess->body; ?></td>
                            							<td>
                                                          <?php if($mess->status == "Pending"){ ?>
                                                          <span class="label label-warning">
                                                            <?php echo $mess->status; ?>
                                                          </span>
                                                            <?php }else if($mess->status == "Completed"){ ?>
                                                          <span class="label label-success">
                                                            <?php echo $mess->status; ?>
                                                          </span>
                                                            <?php } ?>
                            							</td>
                                                        <td><?php echo convertToOrderFormat($mess->created_time); ?></td>
                                                        <td><?php echo date("j M Y", strtotime($mess->created_date)); ?></td>
                                                        <td>
													     <button class="btn btn-info btn-icon-anim btn-square" onclick="completeMessage(<?php echo $mess->id; ?>)" title="Completed">
                                                           <i class="icon-check"></i>
                                                         </button>
                                                        </td>
                                                        <td>
                            							  <button class="btn btn-danger btn-icon-anim btn-square" onclick="deleteMessage(<?php echo $mess->id; ?>)" title="Delete">
                                                           <i class="icon-trash"></i>
                                                         </button>
                                                        </td>
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
