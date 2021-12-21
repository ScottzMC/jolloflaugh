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
	<title>View All Refunded Orders || Admin Fast Food</title>

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
						<li><a href="<?php echo site_url('admin/dashboard'); ?>">Dashboard</a></li>
						<li><a href="#"><span>Orders</span></a></li>
						<li class="active"><span>Refunded Orders</span></li>
					  </ol>
					</div>
					<!-- /Breadcrumb -->
				</div>
				<!-- /Title -->

                <script>
                      function deliveringorder(id){
                        var order_id = id;
                        if(confirm("Are you sure you to deliver this order")){
                        $.post('<?php echo base_url('admin/delivering_order'); ?>', {"order_id": order_id}, function(data){
                          location.reload();
                          $('#cta').html(data)
                          });
                        }
                      }
                      
                      function deliveredorder(id){
                        var order_id = id;
                        if(confirm("Are you sure you this order is delivered")){
                        $.post('<?php echo base_url('admin/delivered_order'); ?>', {"order_id": order_id}, function(data){
                          location.reload();
                          $('#ctb').html(data)
                          });
                        }
                      }
                      
                      function cancelorder(id){
                        var order_id = id;
                        if(confirm("Are you sure you this cancel is order")){
                        $.post('<?php echo base_url('admin/cancel_order'); ?>', {"order_id": order_id}, function(data){
                          location.reload();
                          $('#ctc').html(data)
                          });
                        }
                      }
                      
                      function deleteorder(id){
                        var order_id = id;
                        if(confirm("Are you sure you want to delete your order")){
                        $.post('<?php echo base_url('admin/delete_order'); ?>', {"order_id": order_id}, function(data){
                          location.reload();
                          $('#ctd').html(data)
                          });
                        }
                      }
                
                    </script>
                
                    <p id='cta'></p>
                    <p id='ctb'></p>
                    <p id='ctc'></p>
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
														<th>Charge ID</th>
														<th>FullName</th>
                                                        <!--<th>Telephone</th>
                                                        <th>Address</th>
                                                        <th>Postcode</th>
                                                        <th>Town</th>-->
														<th>Food Name</th>
														<th>Price</th>
                                                        <th>Quantity</th>
                                                        <th>Total Price</th>
														<th>Order Status</th>
														<th>Order Notes</th>
														<!--<th>Delivery Category</th>-->
                                                        <th>Time</th>
                                                        <th>Date</th>
                                                        <th>Delivering</th>
                                                        <th>Delivered</th>
                                                        <th>Cancel</th>
                                                        <th>Refund</th>
                                                        <th>Delete</th>
													</tr>
												</thead>
												
												 <tbody>
                                                    <?php if(!empty($refunded)){ foreach($refunded as $refund){ ?>
													<tr>
														<td>#<?php echo $refund->order_id; ?></td>
														<td><?php echo $refund->charge_id; ?></td>
														<td><?php echo $refund->firstname; ?> <?php echo $refund->lastname; ?></td>
                                                        <!--<td><?php echo $refund->telephone; ?></td>
                                                        <td><?php echo $refund->address; ?></td>
                                                        <td><?php echo $refund->postcode; ?></td>
                                                        <td><?php echo $refund->town; ?></td>-->
                            							<td><?php echo str_replace('-', ' ', $refund->title); ?></td>
                                                        <td>&pound;<?php echo $refund->price; ?></td>
                                                        <td style="text-align: center;"><?php echo $refund->quantity; ?></td>
                                                        <td>&pound;<?php echo $refund->quantity * $refund->price; ?></td>
                            							<td>
                                                          <span class="label label-info">
                                                            <?php echo $refund->status; ?>
                                                          </span>
														</td>
														<td><?php echo $refund->order_notes; ?></td>
														<!--<td><?php echo $refund->delivery_category; ?></td>-->
                                                        <td><?php echo convertToOrderFormat($refund->created_time); ?></td>
                                                        <td><?php echo date("j M Y", strtotime($refund->created_date)); ?></td>
                                                        <td>
													     <button type="button" name="delivering" onclick="deliveringorder(<?php echo $refund->id; ?>)" class="btn btn-info btn-icon-anim btn-square" title="Delivering Order">
                                                           <i class="icon-check"></i>
                                                         </button>
                                                        </td>
                                                        <td>
													     <button type="button" name="delivered" onclick="deliveredorder(<?php echo $refund->id; ?>)" class="btn btn-success btn-icon-anim btn-square" title="Delivered Order">
                                                           <i class="icon-check"></i>
                                                         </button>
                                                        </td>
                                                        <td>
													     <button type="button" name="cancelled" onclick="cancelorder(<?php echo $refund->id; ?>)" class="btn btn-danger btn-icon-anim btn-square" title="Cancelled Order">
                                                            <i class="icon-trash"></i>
                                                          </button>
                                                        </td>
                                                        <td>
                                                            <form action="<?php echo base_url('admin/make_refund'); ?>" method="POST">
                                                                <input type="hidden" name="charge_id" value="<?php echo $refund->charge_id; ?>">
    													        <button class="btn btn-info btn-icon-anim btn-square" type="submit" title="Refunded">
                                                                    <i class="icon-check"></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                        <td>
                            							  <button type="button" class="btn btn-danger btn-icon-anim btn-square" onclick="deleteorder(<?php echo $refund->id; ?>)" title="Delete Order">
                                                           <i class="icon-trash"></i>
                                                         </button>
                                                        </td>
													</tr>
											      <!--</form>-->
                                                <?php } }else{ echo ''; } ?>
                                                <a href="<?php echo site_url('admin/dashboard'); ?>" style="width: 150px;" class="btn btn-danger btn-icon-anim btn-square" title="Go back">
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
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="<?php echo base_url('vendors/bower_components/jquery/dist/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('vendors/bower_components/bootstrap/dist/js/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo base_url('dist/js/init.js'); ?>"></script>

</body>

</html>
