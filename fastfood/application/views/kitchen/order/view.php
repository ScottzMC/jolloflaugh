<?php

function convertToOrderFormat($timestamp){
    $diffBtwCurrentTimeAndTimeStamp = (time() - $timestamp);
    $periodsString = ["sec", "min","hr","day","week","month","year","decade"];
    $periodNumbers = ["60" , "60" , "24" , "7" , "4.35" , "12" , "10"];
    for(@@$iterator = 0; $diffBtwCurrentTimeAndTimeStamp >= $periodNumbers[$iterator]; $iterator++)
        @@$diffBtwCurrentTimeAndTimeStamp /= $periodNumbers[$iterator];
        $diffBtwCurrentTimeAndTimeStamp = round($diffBtwCurrentTimeAndTimeStamp);

    if($diffBtwCurrentTimeAndTimeStamp != 1)  $periodsString[$iterator].="s";
        $output = "$diffBtwCurrentTimeAndTimeStamp $periodsString[$iterator]";
        echo "Ordered " .$output. " ago";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<title>View All Orders || Kitchen Fast Food</title>

</head>

<body>
        <!-- Main Content -->
		<div class="page-wrapper">
            <div class="container-fluid">
				<!-- Title -->
				<div class="row heading-bg bg-pink">
					<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
					  <h5 class="txt-light">View All Orders</h5>
					</div>
					<!-- Breadcrumb -->
					<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
					  <ol class="breadcrumb">
						<li><a href="<?php echo site_url('kitchen/dashboard'); ?>">Dashboard</a></li>
						<li><a href="#"><span>Food</span></a></li>
						<li class="active"><span>View Orders</span></li>
					  </ol>
					</div>
					<!-- /Breadcrumb -->
				</div>
				<!-- /Title -->

                <script>
                    function deliverorder(id){
                      var order_id = id;
                      if(confirm("Are you sure you this order has been delivered")){
                      $.post('<?php echo base_url('kitchen/deliver_order'); ?>', {"order_id": order_id}, function(data){
                        location.reload();
                        $('#cte').html(data)
                        });
                      }
                    }
                  </script>
                      <script>
                    function cancelorder(id){
                    var del_id = id;
                    if(confirm("Are you sure you want to cancel this order")){
                    $.post('<?php echo base_url('kitchen/cancel_order'); ?>', {"del_id": del_id}, function(data){
                      location.reload();
                      $('#cti').html(data)
                      });
                    }
                  }
                </script>
                <p id='cte'></p>
                <p id='cti'></p>

				<!-- Row -->
				<div class="row">
					<div class="col-sm-12">
						<div class="panel panel-default card-view">
							<div class="panel-heading">
								<div class="pull-left">
									<h6 class="panel-title txt-dark">Food orders</h6>
								</div>
								<div class="clearfix"></div>
							</div>
                            <div class="panel-wrapper collapse in">
								<div class="panel-body">
									<div class="table-wrap">
										<div class="table-responsive">
											<table class="table display product-overview mb-30" id="support_table">
												<thead>
													<tr>
														<th>OID</th>
														<th>FullName</th>
                                                        <th>Telephone</th>
                                                        <th>Address</th>
                                                        <th>Postcode</th>
                                                        <th>Town</th>
														<th>Food Name</th>
														<th>Price</th>
                                                        <th>Quantity</th>
                                                        <th>Total Price</th>
														<th>Order Status</th>
														<th>Order Notes</th>
                                                        <th>Time</th>
                                                        <th>Date</th>
                                                        <th>Deliver</th>
                                                        <th>Cancel</th>
													</tr>
												</thead>
												<tbody>
                                                    <?php if(!empty($orders)){ foreach($orders as $odow){ ?>
													<tr>
														<td>#<?php echo $odow->order_id; ?></td>
														<td><?php echo $odow->firstname; ?> <?php echo $odow->lastname; ?></td>
                                                        <td><?php echo $odow->telephone; ?></td>
                                                        <td><?php echo $odow->address; ?></td>
                                                        <td><?php echo $odow->postcode; ?></td>
                                                        <td><?php echo $odow->town; ?></td>
                            							<td><?php echo str_replace('-', ' ', $odow->title); ?></td>
                                                        <td>&pound;<?php echo $odow->price; ?></td>
                                                        <td style="text-align: center;"><?php echo $odow->quantity; ?></td>
                                                        <td>&pound;<?php echo $odow->quantity * $odow->price; ?></td>
                            							<td>
                                                          <?php if($odow->status == "Delivering"){ ?>
                                                          <span class="label label-warning">
                                                            <?php echo $odow->status; ?>
                                                          </span>
                                                        <?php }else if($odow->status == "Delivered"){ ?>
                                                          <span class="label label-success">
                                                            <?php echo $odow->status; ?>
                                                          </span>
                                                        <?php }else if($odow->status == "Cancelled"){ ?>
                                                          <span class="label label-danger">
                                                            <?php echo $odow->status; ?>
                                                          </span>
                                                        <?php } ?>
														</td>
														<td><?php echo $odow->order_notes; ?></td>
                                                        <td><?php echo convertToOrderFormat($odow->created_time); ?></td>
                                                        <td><?php echo date("j M Y", strtotime($odow->created_date)); ?></td>
                                                        <td>
													        <button class="btn btn-success btn-icon-anim btn-square" onclick="deliverorder(<?php echo $odow->id; ?>)" title="Delivered Order">
                                                                <i class="icon-check"></i>
                                                            </button>
                                                        </td>
                                                        <td>
													        <button class="btn btn-danger btn-icon-anim btn-square" onclick="cancelorder(<?php echo $odow->id; ?>)" title="Cancelled Order">
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

</body>

</html>
