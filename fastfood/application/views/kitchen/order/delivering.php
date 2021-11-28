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
	<title>View All Delivering Orders || Kitchen Fast Food</title>

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
						<li><a href="#"><span>Orders</span></a></li>
						<li class="active"><span>Delivering Orders</span></li>
					  </ol>
					</div>
					<!-- /Breadcrumb -->
				</div>
				<!-- /Title -->

                <script>
                      
                  function deleteorder(id){
                    var order_id = id;
                    if(confirm("Are you sure you want to delete your order")){
                    $.post('<?php echo base_url('kitchen/delete_order'); ?>', {"order_id": order_id}, function(data){
                      location.reload();
                      $('#ctd').html(data)
                      });
                    }
                  }
            
                </script>
            
                <p id='ctd'></p>

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
                                                        <th>Pending</th>
                                                        <th>Delivered</th>
                                                        <th>Cancel</th>
                                                        <th>Delete</th>
													</tr>
												</thead>
												
												 <tbody>
                                                    <?php if(!empty($delivering)){ foreach($delivering as $delving){} ?>
                                                    <form action="<?php echo base_url('kitchen/delivering/'.$delving->id); ?>" method="POST">
                                                    
                                                    <input type="hidden" name="order_id" value="<?php echo $delving->order_id; ?>">
                                                    <input type="hidden" name="title" value="<?php echo $delving->title; ?>">
                                                    <input type="hidden" name="price" value="<?php echo $delving->price; ?>">
                                                    <input type="hidden" name="quantity" value="<?php echo $delving->quantity; ?>">
                                                    <input type="hidden" name="customer_email" value="<?php echo $delving->email; ?>">
													<tr>
														<td>#<?php echo $delving->order_id; ?></td>
														<td><?php echo $delving->firstname; ?> <?php echo $delving->lastname; ?></td>
                                                        <td><?php echo $delving->telephone; ?></td>
                                                        <td><?php echo $delving->address; ?></td>
                                                        <td><?php echo $delving->postcode; ?></td>
                                                        <td><?php echo $delving->town; ?></td>
                            							<td><?php echo str_replace('-', ' ', $delving->title); ?></td>
                                                        <td>&pound;<?php echo $delving->price; ?></td>
                                                        <td style="text-align: center;"><?php echo $delving->quantity; ?></td>
                                                        <td>&pound;<?php echo $delving->quantity * $delving->price; ?></td>
                            							<td>
                                                        <?php if($delving->status == "Pending"){ ?>
                                                          <span class="label label-info">
                                                            <?php echo $delving->status; ?>
                                                          </span>
                                                        <?php }else if($delving->status == "Delivering"){ ?>
                                                          <span class="label label-warning">
                                                            <?php echo $delving->status; ?>
                                                          </span>
                                                        <?php }else if($delving->status == "Delivered"){ ?>
                                                          <span class="label label-success">
                                                            <?php echo $delving->status; ?>
                                                          </span>
                                                        <?php }else if($delving->status == "Cancelled"){ ?>
                                                          <span class="label label-danger">
                                                            <?php echo $delving->status; ?>
                                                          </span>
                                                        <?php } ?>
														</td>
														<td><?php echo $delving->order_notes; ?></td>
                                                        <td><?php echo convertToOrderFormat($delving->created_time); ?></td>
                                                        <td><?php echo date("j M Y", strtotime($delving->created_date)); ?></td>
                                                        <td>
													     <button type="submit" name="pending" class="btn btn-info btn-icon-anim btn-square" title="Pending Order">
                                                           <i class="icon-check"></i>
                                                         </button>
                                                        </td>
                                                        <td>
													     <button type="submit" name="delivered" class="btn btn-success btn-icon-anim btn-square" title="Delivered Order">
                                                           <i class="icon-check"></i>
                                                         </button>
                                                        </td>
                                                        <td>
													     <button type="submit" name="cancelled" class="btn btn-danger btn-icon-anim btn-square" title="Cancelled Order">
                                                            <i class="icon-trash"></i>
                                                          </button>
                                                        </td>
                                                        <td>
                            							  <button class="btn btn-danger btn-icon-anim btn-square" onclick="deleteorder(<?php echo $delving->id; ?>)" title="Delete Order">
                                                           <i class="icon-trash"></i>
                                                         </button>
                                                        </td>
													</tr>
											      </form>
                                                <?php }else{ echo ''; } ?>
                                                <a href="<?php echo site_url('kitchen/dashboard'); ?>" style="width: 150px;" class="btn btn-danger btn-icon-anim btn-square" title="Go back">
                                                    Go to Dashboard
                                                </a>
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
