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
	<title>View All Pending Orders || Admin Fast Food</title>

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
						<li class="active"><span>Pending Orders</span></li>
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
									<h6 class="panel-title txt-dark">Pending orders</h6>
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
													    <th></th>
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
                                                    <?php if(!empty($pending)){ foreach($pending as $pend){ ?>
                                                    <!--<form action="<?php echo base_url('admin/pending/'.$pend->id); ?>" method="POST">
                                                    
                                                    <input type="hidden" name="order_id" value="<?php echo $pend->order_id; ?>">
                                                    <input type="hidden" name="title" value="<?php echo $pend->title; ?>">
                                                    <input type="hidden" name="price" value="<?php echo $pend->price; ?>">
                                                    <input type="hidden" name="quantity" value="<?php echo $pend->quantity; ?>">
                                                    <input type="hidden" name="customer_email" value="<?php echo $pend->email; ?>">-->
													<tr>
													    <td><input type="checkbox" class="checkbox" value="<?php echo $pend->id; ?>" /></td>
														<td>#<?php echo $pend->order_id; ?></td>
														<td><?php echo $pend->charge_id; ?></td>
														<td><?php echo $pend->firstname; ?> <?php echo $pend->lastname; ?></td>
                                                        <!--<td><?php echo $pend->telephone; ?></td>
                                                        <td><?php echo $pend->address; ?></td>
                                                        <td><?php echo $pend->postcode; ?></td>
                                                        <td><?php echo $pend->town; ?></td>-->
                            							<td><?php echo str_replace('-', ' ', $pend->title); ?></td>
                                                        <td>&pound;<?php echo $pend->price; ?></td>
                                                        <td style="text-align: center;"><?php echo $pend->quantity; ?></td>
                                                        <td>&pound;<?php echo $pend->quantity * $pend->price; ?></td>
                            							<td>
                                                          <span class="label label-info">
                                                            <?php echo $pend->status; ?>
                                                          </span>
														</td>
														<td><?php echo $pend->order_notes; ?></td>
														<!--<td><?php echo $pend->delivery_category; ?></td>-->
                                                        <td><?php echo convertToOrderFormat($pend->created_time); ?></td>
                                                        <td><?php echo date("j M Y", strtotime($pend->created_date)); ?></td>
                                                        <td>
													     <button type="button" name="delivering" onclick="deliveringorder(<?php echo $pend->id; ?>)" class="btn btn-info btn-icon-anim btn-square" title="Delivering Order">
                                                           <i class="icon-check"></i>
                                                         </button>
                                                        </td>
                                                        <td>
													     <button type="button" name="delivered" onclick="deliveredorder(<?php echo $pend->id; ?>)" class="btn btn-success btn-icon-anim btn-square" title="Delivered Order">
                                                           <i class="icon-check"></i>
                                                         </button>
                                                        </td>
                                                        <td>
													     <button type="button" name="cancelled" onclick="cancelorder(<?php echo $pend->id; ?>)" class="btn btn-danger btn-icon-anim btn-square" title="Cancelled Order">
                                                            <i class="icon-trash"></i>
                                                          </button>
                                                        </td>
                                                        <td>
                                                            <form action="<?php echo base_url('admin/make_refund'); ?>" method="POST">
                                                                <input type="hidden" name="charge_id" value="<?php echo $pend->charge_id; ?>">
    													        <button class="btn btn-info btn-icon-anim btn-square" type="submit" title="Refunded">
                                                                    <i class="icon-check"></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                        <td>
                            							  <button type="button" class="btn btn-danger btn-icon-anim btn-square" onclick="deleteorder(<?php echo $pend->id; ?>)" title="Delete Order">
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
											<div style="padding-bottom: 50px;">
    											<button type="button" name="deliver_all" id="deliver_all" style="width: 150px;" class="btn btn-warning btn-icon-anim btn-square" title="Delivering">
                                                    Delivering
                                                </button>
                                                <button type="button" name="delivered_all" id="delivered_all" style="width: 150px;" class="btn btn-success btn-icon-anim btn-square" title="Delivered">
                                                    Delivered
                                                </button>
                                                <button type="button" name="cancelled_all" id="cancelled_all" style="width: 150px;" class="btn btn-danger btn-icon-anim btn-square" title="Cancelled">
                                                    Cancelled
                                                </button>
                                                <button type="button" name="delete_all" id="delete_all" style="width: 150px;" class="btn btn-danger btn-icon-anim btn-square" title="Delete">
                                                    Delete
                                                </button>
                                            </div>
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
    
    <style>
    .removeRow{
     background-color: #FF0000;
     color:#FFFFFF;
    }
    </style>
    
    <script>
    $(document).ready(function(){
     
     $('.checkbox').click(function(){
      if($(this).is(':checked')){
       $(this).closest('tr').addClass('removeRow');
      }
      else{
       $(this).closest('tr').removeClass('removeRow');
      }
     });
    
     $('#delete_all').click(function(){
      var checkbox = $('.checkbox:checked');
      if(checkbox.length > 0){
       var checkbox_value = [];
       $(checkbox).each(function(){
        checkbox_value.push($(this).val());
       });
       
       $.ajax({
        url:"<?php echo base_url(); ?>admin/delete_all",
        method:"POST",
        data:{checkbox_value:checkbox_value},
        success:function(){
         $('.removeRow').fadeOut(1500);
        }
       })
      }else{
       alert('Select at least one order item');
      }
     });
     
     $('#deliver_all').click(function(){
      var checkbox = $('.checkbox:checked');
      if(checkbox.length > 0){
       var checkbox_value = [];
       $(checkbox).each(function(){
        checkbox_value.push($(this).val());
       });
       
       $.ajax({
        url:"<?php echo base_url(); ?>admin/deliver_all",
        method:"POST",
        data:{checkbox_value:checkbox_value},
        success:function(){
         $('.removeRow').fadeOut(1500);
        }
       })
      }else{
       alert('Select at least one order item');
      }
     });
     
     $('#delivered_all').click(function(){
      var checkbox = $('.checkbox:checked');
      if(checkbox.length > 0){
       var checkbox_value = [];
       $(checkbox).each(function(){
        checkbox_value.push($(this).val());
       });
       
       $.ajax({
        url:"<?php echo base_url(); ?>admin/delivered_all",
        method:"POST",
        data:{checkbox_value:checkbox_value},
        success:function(){
         $('.removeRow').fadeOut(1500);
        }
       })
      }else{
       alert('Select at least one order item');
      }
     });
     
     $('#cancelled_all').click(function(){
      var checkbox = $('.checkbox:checked');
      if(checkbox.length > 0){
       var checkbox_value = [];
       $(checkbox).each(function(){
        checkbox_value.push($(this).val());
       });
       
       $.ajax({
        url:"<?php echo base_url(); ?>admin/cancelled_all",
        method:"POST",
        data:{checkbox_value:checkbox_value},
        success:function(){
         $('.removeRow').fadeOut(1500);
        }
       })
      }else{
       alert('Select at least one order item');
      }
     });
    
    });
    </script>

</body>

</html>
