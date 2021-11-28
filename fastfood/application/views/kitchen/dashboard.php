<?php

function convertToAgoFormat($timestamp){
    $diffBtwCurrentTimeAndTimeStamp = (time() - $timestamp);
    $periodsString = ["sec", "min","hr","day","week","month","year","decade"];
    $periodNumbers = ["60" , "60" , "24" , "7" , "4.35" , "12" , "10"];
    for(@@$iterator = 0; $diffBtwCurrentTimeAndTimeStamp >= $periodNumbers[$iterator]; $iterator++)
        @@$diffBtwCurrentTimeAndTimeStamp /= $periodNumbers[$iterator];
        $diffBtwCurrentTimeAndTimeStamp = round($diffBtwCurrentTimeAndTimeStamp);

    if($diffBtwCurrentTimeAndTimeStamp != 1)  $periodsString[$iterator].="s";
        $output = "$diffBtwCurrentTimeAndTimeStamp $periodsString[$iterator]";
        echo "Posted " .$output. " ago";
}

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
	<title>Kitchen Dashboard || Fast Food</title>
</head>
<?php if(!empty($total_user_count)){ foreach($total_user_count as $tot_use_count){} }else{ echo ''; } ?>
<?php if(!empty($total_food_count)){foreach($total_food_count as $tot_fod_count){} }else{ echo ''; } ?>
<?php if(!empty($total_order_count)){foreach($total_order_count as $tot_ord_count){} }else{ echo ''; } ?>

<body>
    
     <!-- Main Content -->
		<div class="page-wrapper">
            <div class="container-fluid">

				<!-- Title -->
				<div class="row heading-bg  bg-blue">
					<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
						<h5 class="txt-light">Kitchen Dashboard</h5>
					</div>
					<!-- Breadcrumb -->
					<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
						<ol class="breadcrumb">
							<li><a href="<?php echo site_url('kitchen/dashboard'); ?>">Kitchen Dashboard</a></li>
							<li class="active"><span>Dashboard</span></li>
						</ol>
					</div>
					<!-- /Breadcrumb -->
				</div>

				<!-- /Title -->
				<!-- Row -->
				<div class="row">
					<div class="col-lg-3 col-md-4 col-sm-5 col-xs-12">
						<div class="panel panel-default card-view pa-0">
							<div class="panel-wrapper collapse in">
								<div class="panel-body pa-0">
									<div class="sm-data-box bg-red">
										<div class="row ma-0">
											<div class="col-xs-5 text-center pa-0 icon-wrap-left">
												<i class="icon-briefcase txt-light"></i>
											</div>
											<div class="col-xs-7 text-center data-wrap-right">
												<h6 class="txt-light">Total Foods</h6>
												<span class="txt-light counter counter-anim"><?php echo $tot_fod_count->food_count; ?></span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="panel panel-default card-view">
								<div class="panel-wrapper collapse in">
									<div class="panel-body">
										<div class="sm-progress-box">
											<i class="icon-emotsmile mb-15 block"></i>
											<span class="font-12 head-font txt-dark">Order Rate
                                                <?php $order_rate = 0 * 30/100; ?>
                                                <span class="pull-right"><?php echo $order_rate; ?>%
                                                  <span class="pl-5"><i class="fa fa-arrow-up txt-success font-12"></i></span>
                                                </span>
                                              </span>
											<div class="progress mt-10">
												<div class="progress-bar progress-bar-success" aria-valuenow="<?php echo $order_rate; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $order_rate; ?>%" role="progressbar">
                                                    <span class="sr-only"><?php echo $order_rate; ?>% Complete (success)</span> </div>
											</div>
										</div>
									</div>
								</div>
						</div>
					</div>

					<!-- /Bordered Table -->
          <div class="col-lg-4 col-md-6 col-sm-12">
						<div class="panel panel-success card-view">
							<div class="panel-heading mb-20">
								<div class="pull-left">
									<h6 class="panel-title txt-light pull-left">User Register Status</h6>
								</div>
								<div class="pull-right">
									<a class="txt-light" href="javascript:void(0);"><i class="ti-plus"></i></a>
								</div>
								<div class="clearfix"></div>
							</div>

                     <?php if(!empty($user_status)){ foreach($user_status as $usrow){ ?>
							<div class="panel-wrapper collapse in">
								<div class="panel-body">
									<ul class="chat-list-wrap">
										<li class="chat-list">
											<div class="chat-body">
												<a class="" href="#">
													<div class="chat-data">
														<img class="user-img img-circle" src="<?php echo base_url('uploads/original.jpg'); ?>"  alt="<?php echo $usrow->firstname; ?>"/>
														<div class="user-data">
															<span class="name block capitalize-font"><?php echo $usrow->firstname; ?> <?php echo $usrow->lastname; ?></span>
															<span class="time block txt-grey"><?php echo convertToRegisterFormat($usrow->created_time); ?></span>
														</div>
														<div class="status online"></div>
														<div class="clearfix"></div>
													</div>
												</a>
											</div>
										</li>
									</ul>
								</div>
							</div>
            <?php  } }else{ echo ''; } ?>
						</div>
					</div>

          <div class="col-lg-4 col-md-6 col-sm-12">
						<div class="panel panel-success card-view">
							<div class="panel-heading mb-20">
								<div class="pull-left">
									<h6 class="panel-title txt-light pull-left">User Food Status</h6>
								</div>
								<div class="pull-right">
									<a class="txt-light" href="javascript:void(0);"><i class="ti-plus"></i></a>
								</div>
								<div class="clearfix"></div>
							</div>
              <?php if(!empty($food)){ foreach($food as $fd){ ?>
							<div class="panel-wrapper collapse in">
								<div class="panel-body">
									<ul class="chat-list-wrap">
                    <li class="chat-list">
											<div class="chat-body">
												<a class="" href="#">
													<div class="chat-data">
                            <img class="user-img img-circle" src="<?php echo base_url('uploads/food/'.$fd->image1); ?>"  alt="<?php echo $fd->title; ?>"/>
														<div class="user-data">
                              <span class="name block capitalize-font">&pound;<?php echo $fd->price; ?></span>
                              <span class="time block txt-grey"><?php echo str_replace('-', ' ', $fd->title); ?></span>
                              <span class="time block txt-grey"><?php echo date('j M Y', strtotime($fd->created_date)); ?></span>
														</div>
														<div class="status online"></div>
														<div class="clearfix"></div>
													</div>
												</a>
											</div>
										</li>
									</ul>
								</div>
							</div>
            <?php } }else{ echo ''; } ?>
						</div>
					</div>
				</div>

				<!-- Row -->
					<div class="row">
						<div class="col-sm-12">
							<div class="panel panel-default card-view">
								<div class="panel-heading">
									<div class="pull-left">
										<h6 class="panel-title txt-dark">Status - Per Month Info</h6>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="panel-wrapper collapse in">
									<div class="panel-body pb-0">
										<div class="row">
											<div class="col-sm-3 col-xs-6 mb-15">
												<span id="pie_chart_1" class="easypiechart skill-circle" data-percent="<?php echo $tot_use_count->user_count * 30/100; ?>">
													<span class="percent head-font"><?php echo $tot_use_count->user_count * 30/100; ?></span>
												</span>
												<span class="skill-head mt-20">Users</span>
											</div>
											<div class="col-sm-3 col-xs-6 mb-15">
												<span id="pie_chart_2" class="easypiechart skill-circle" data-percent="<?php echo $tot_fod_count->food_count * 30/100; ?>">
													<span class="percent head-font"><?php echo $tot_fod_count->food_count * 30/100; ?></span>
												</span>
												<span class="skill-head mt-20">Foods</span>
											</div>
											<div class="col-sm-3 col-xs-6 mb-15">
												<span id="pie_chart_3" class="easypiechart skill-circle" data-percent="<?php echo $tot_ord_count->order_count * 30/100; ?>">
													<span class="percent head-font"><?php echo $tot_ord_count->order_count * 30/100; ?></span>
												</span>
												<span class="skill-head mt-20">Order Rate</span>
											</div>
										</div>
									</div>
								</div>
							</div>

						</div>
					</div>
					<!-- /Row -->

                    <script>
                      function completeMessage(id){
                        var message_id = id;
                        if(confirm("Are you sure you this ticket is completed")){
                        $.post('<?php echo base_url('kitchen/complete_message'); ?>', {"message_id": message_id}, function(data){
                          location.reload();
                          $('#cte').html(data)
                          });
                        }
                      }
                      
                      function deleteMessage(id){
                        var message_id = id;
                        if(confirm("Are you sure you want to delete your ticket")){
                        $.post('<?php echo base_url('kitchen/delete_message'); ?>', {"message_id": message_id}, function(data){
                          location.reload();
                          $('#ctj').html(data)
                          });
                        }
                      }
                
                    </script>
                
                    <p id='cte'></p>
                    <p id='ctj'></p>


				<!-- Row -->
				<div class="row">
					<div class="col-sm-12">
						<div class="panel panel-default card-view">
							<div class="panel-heading">
								<div class="pull-left">
									<h6 class="panel-title txt-dark">Message</h6>
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
														<th>Email</th>
														<th>Food Name</th>
                                                        <th>Subject</th>
														<th>Message</th>
														<th>Status</th>
                                                        <th>Time</th>
                                                        <th>Date</th>
                                                        <th>Action</th>
													</tr>
												</thead>
												<tbody>
                                                <?php if(!empty($message)){ foreach($message as $mess){ ?>
													<tr>
													    <?php if($mess->status == "Pending"){ ?>
														<td style="color: red;">#<?php echo $mess->order_id; ?></td>
														<td style="color: red;"><?php echo $mess->firstname; ?> <?php echo $mess->lastname; ?></td>
														<td style="color: red;"><?php echo $mess->email; ?></td>
														<td style="color: red;"><?php echo str_replace('-', ' ', $mess->order_title); ?></td>
                                                        <td style="color: red;"><?php echo $mess->subject; ?></td>
                                                        <td style="color: red;"><?php echo $mess->body; ?></td>
                                                        <?php }else{ ?>
                                                        <td>#<?php echo $mess->order_id; ?></td>
														<td><?php echo $mess->firstname; ?> <?php echo $mess->lastname; ?></td>
														<td><?php echo $mess->email; ?></td>
														<td><?php echo str_replace('-', ' ', $mess->order_title); ?></td>
                                                        <td><?php echo $mess->subject; ?></td>
                                                        <td><?php echo $mess->body; ?></td>
                                                        <?php } ?>
                            							<td>
                                                          <?php if($mess->status == "Pending"){ ?>
                                                          <span class="label label-info">
                                                            <?php echo $mess->status; ?>
                                                          </span>
                                                            <?php }else if($mess->status == "Completed"){ ?>
                                                          <span class="label label-success">
                                                            <?php echo $mess->status; ?>
                                                          </span>
                                                            <?php }else if($mess->status == "Rejected"){ ?>
                                                          <span class="label label-danger">
                                                            <?php echo $mess->status; ?>
                                                          </span>
                                                            <?php } ?>
                            							</td>
                            							<?php if($mess->status == "Pending"){ ?>
                                                        <td style="color: red;"><?php echo convertToOrderFormat($mess->created_time); ?></td>
                                                        <td style="color: red;"><?php echo date("j M Y", strtotime($mess->created_date)); ?></td>
                                                        <?php }else{ ?>
                                                        <td><?php echo convertToOrderFormat($mess->created_time); ?></td>
                                                        <td><?php echo date("j M Y", strtotime($mess->created_date)); ?></td>
                                                        <?php } ?>
                                                        <?php if($mess->status == "Pending"){ ?>
                                                        <td>
                                                         <a href="<?php echo site_url('kitchen/pending_message/'.$mess->id); ?>" class="btn btn-info btn-icon-anim btn-square" title="View Pending Message">
                                                           <i class="icon-check"></i>
                                                         </a>
                                                        </td> 
                                                        <?php }else if($mess->status == "Completed"){ ?>
                                                        <td>
                                                         <a href="<?php echo site_url('kitchen/completed_message/'.$mess->id); ?>" class="btn btn-success btn-icon-anim btn-square" title="View Completed Message">
                                                           <i class="icon-check"></i>
                                                         </a>
                                                        </td>
                                                        <?php }else if($mess->status == "Rejected"){ ?>
                                                        <?php } ?>
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
				
				    <script>
                      function deliveringorder(id){
                        var order_id = id;
                        if(confirm("Are you sure you to deliver this order")){
                        $.post('<?php echo base_url('kitchen/delivering_order'); ?>', {"order_id": order_id}, function(data){
                          location.reload();
                          $('#cta').html(data)
                          });
                        }
                      }
                      
                      function deliveredorder(id){
                        var order_id = id;
                        if(confirm("Are you sure you this order is delivered")){
                        $.post('<?php echo base_url('kitchen/delivered_order'); ?>', {"order_id": order_id}, function(data){
                          location.reload();
                          $('#ctb').html(data)
                          });
                        }
                      }
                      
                      function cancelorder(id){
                        var order_id = id;
                        if(confirm("Are you sure you this cancel is order")){
                        $.post('<?php echo base_url('kitchen/cancel_order'); ?>', {"order_id": order_id}, function(data){
                          location.reload();
                          $('#ctc').html(data)
                          });
                        }
                      }
                      
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
									<h6 class="panel-title txt-dark">Pending Orders</h6>
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
														<th>Food Name</th>
														<th>Price</th>
                                                        <th>Quantity</th>
														<th>Order Status</th>
                                                        <th>Order Notes</th>
                                                        <th>Time</th>
                                                        <th>Date</th>
                                                        <th>Action</th>
                                                        <th>Action</th>
													</tr>
												</thead>
												<tbody>
                                                <?php if(!empty($pending)){ foreach($pending as $pend){ ?>
													<tr>
														<td>#<?php echo $pend->order_id; ?></td>
														<td><?php echo $pend->firstname; ?> <?php echo $pend->lastname; ?></td>
														<td><?php echo str_replace('-', ' ', $pend->title); ?></td>
                                                        <td>&pound;<?php echo $pend->price; ?></td>
                                                        <td style="text-align: center;"><?php echo $pend->quantity; ?></td>
                            							<td>
                                                          <?php if($pend->status == "Pending"){ ?>
                                                          <span class="label label-info">
                                                            <?php echo $pend->status; ?>
                                                          </span>
                                                          <?php }else if($pend->status == "Delivering"){ ?>
                                                          <span class="label label-warning">
                                                            <?php echo $pend->status; ?>
                                                          </span>
                                                            <?php }else if($pend->status == "Delivered"){ ?>
                                                          <span class="label label-success">
                                                            <?php echo $pend->status; ?>
                                                          </span>
                                                            <?php }else if($pend->status == "Cancelled"){ ?>
                                                          <span class="label label-danger">
                                                            <?php echo $pend->status; ?>
                                                          </span>
                                                            <?php } ?>
                            							</td>
                            							<td><?php echo $pend->order_notes; ?></td>
                                                        <td><?php echo convertToOrderFormat($pend->created_time); ?></td>
                                                        <td><?php echo date("j M Y", strtotime($pend->created_date)); ?></td>
                                                        <td>
													     <a href="<?php echo site_url('kitchen/pending/'.$pend->id); ?>" class="btn btn-info btn-icon-anim btn-square" title="View Order">
                                                           <i class="icon-check"></i>
                                                         </a>
                                                        </td>
                                                        <td>
                            							  <button class="btn btn-danger btn-icon-anim btn-square" onclick="deleteorder(<?php echo $pend->id; ?>)" title="Delete Order">
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
				
				<!-- Row -->
				<div class="row">
					<div class="col-sm-12">
						<div class="panel panel-default card-view">
							<div class="panel-heading">
								<div class="pull-left">
									<h6 class="panel-title txt-dark">Delivering Orders</h6>
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
														<th>Food Name</th>
														<th>Price</th>
                                                        <th>Quantity</th>
														<th>Order Status</th>
														<th>Order Notes</th>
                                                        <th>Time</th>
                                                        <th>Date</th>
                                                        <th>Action</th>
                                                        <th>Action</th>
													</tr>
												</thead>
												<tbody>
                                                <?php if(!empty($delivering)){ foreach($delivering as $delving){ ?>
													<tr>
														<td>#<?php echo $delving->order_id; ?></td>
														<td><?php echo $delving->firstname; ?> <?php echo $delving->lastname; ?></td>
														<td><?php echo str_replace('-', ' ', $delving->title); ?></td>
                                                        <td>&pound;<?php echo $delving->price; ?></td>
                                                        <td style="text-align: center;"><?php echo $delving->quantity; ?></td>
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
													     <a href="<?php echo site_url('kitchen/delivering/'.$delving->id); ?>" class="btn btn-info btn-icon-anim btn-square" title="View Order">
                                                           <i class="icon-check"></i>
                                                         </a>
                                                        </td>
                                                        <td>
                            							  <button class="btn btn-danger btn-icon-anim btn-square" onclick="deleteorder(<?php echo $delving->id; ?>)" title="Delete Order">
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

				<!-- Row -->
				<div class="row">
					<div class="col-sm-12">
						<div class="panel panel-default card-view">
							<div class="panel-heading">
								<div class="pull-left">
									<h6 class="panel-title txt-dark">Delivered Orders</h6>
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
														<th>Food Name</th>
														<th>Price</th>
                                                        <th>Quantity</th>
														<th>Order Status</th>
														<th>Order Notes</th>
                                                        <th>Time</th>
                                                        <th>Date</th>
                                                        <th>Action</th>
                                                        <th>Action</th>
													</tr>
												</thead>
												<tbody>
                                                <?php if(!empty($delivered)){ foreach($delivered as $delved){ ?>
													<tr>
														<td>#<?php echo $delved->order_id; ?></td>
														<td><?php echo $delved->firstname; ?> <?php echo $delved->lastname; ?></td>
														<td><?php echo str_replace('-', ' ', $delved->title); ?></td>
                                                        <td>&pound;<?php echo $delved->price; ?></td>
                                                        <td style="text-align: center;"><?php echo $delved->quantity; ?></td>
                            							<td>
                                                          <?php if($delved->status == "Pending"){ ?>
                                                          <span class="label label-info">
                                                            <?php echo $delved->status; ?>
                                                          </span>
                                                          <?php }else if($delved->status == "Delivering"){ ?>
                                                          <span class="label label-warning">
                                                            <?php echo $delved->status; ?>
                                                          </span>
                                                            <?php }else if($delved->status == "Delivered"){ ?>
                                                          <span class="label label-success">
                                                            <?php echo $delved->status; ?>
                                                          </span>
                                                            <?php }else if($delved->status == "Cancelled"){ ?>
                                                          <span class="label label-danger">
                                                            <?php echo $delved->status; ?>
                                                          </span>
                                                            <?php } ?>
                            							</td>
                            							<td><?php echo $delved->order_notes; ?></td>
                                                        <td><?php echo convertToOrderFormat($delved->created_time); ?></td>
                                                        <td><?php echo date("j M Y", strtotime($delved->created_date)); ?></td>
                                                        <td>
													     <a href="<?php echo site_url('kitchen/delivered/'.$delved->id); ?>" class="btn btn-info btn-icon-anim btn-square" title="View Order">
                                                           <i class="icon-check"></i>
                                                         </a>
                                                        </td>
                                                        <td>
                            							  <button class="btn btn-danger btn-icon-anim btn-square" onclick="deleteorder(<?php echo $delved->id; ?>)" title="Delete Order">
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

    <!-- /#wrapper -->
    
</body>
</html>