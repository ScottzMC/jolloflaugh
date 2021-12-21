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
	<title>View All Delivered Orders || Admin Fast Food</title>

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
						<li class="active"><span>Delivered Orders</span></li>
					  </ol>
					</div>
					<!-- /Breadcrumb -->
				</div>
				<!-- /Title -->

                <script>
                      
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
                
                    <p id='ctc'></p>
                    <p id='ctd'></p>

				<!-- Row -->
				<div class="row">
					<div class="col-sm-12">
						<div class="panel panel-default card-view">
							<div class="panel-heading">
								<div class="pull-left">
									<h6 class="panel-title txt-dark">Delivered orders</h6>
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
                                                        <th>Cancel</th>
                                                        <th>Refund</th>
                                                        <th>Delete</th>
													</tr>
												</thead>
												
												 <tbody>
                                                    <?php if(!empty($delivered)){ foreach($delivered as $delved){ ?>
                                                    <!--<form action="<?php echo base_url('admin/delivered/'.$delved->id); ?>" method="POST">
                                                    
                                                    <input type="hidden" name="order_id" value="<?php echo $delved->order_id; ?>">
                                                    <input type="hidden" name="title" value="<?php echo $delved->title; ?>">
                                                    <input type="hidden" name="price" value="<?php echo $delved->price; ?>">
                                                    <input type="hidden" name="quantity" value="<?php echo $delved->quantity; ?>">
                                                    <input type="hidden" name="customer_email" value="<?php echo $delved->email; ?>">-->
													<tr>
													    <td><input type="checkbox" class="checkbox" value="<?php echo $delved->id; ?>" /></td>
														<td>#<?php echo $delved->order_id; ?></td>
														<td><?php echo $delved->charge_id; ?></td>
														<td><?php echo $delved->firstname; ?> <?php echo $delved->lastname; ?></td>
                                                        <!--<td><?php echo $delved->telephone; ?></td>
                                                        <td><?php echo $delved->address; ?></td>
                                                        <td><?php echo $delved->delivery_category; ?></td>
                                                        <td><?php echo $delved->postcode; ?></td>
                                                        <td><?php echo $delved->town; ?></td>-->
                            							<td><?php echo str_replace('-', ' ', $delved->title); ?></td>
                                                        <td>&pound;<?php echo $delved->price; ?></td>
                                                        <td style="text-align: center;"><?php echo $delved->quantity; ?></td>
                                                        <td>&pound;<?php echo $delved->quantity * $delved->price; ?></td>
                            							<td>
                                                          <span class="label label-success">
                                                            <?php echo $delved->status; ?>
                                                          </span>
														</td>
														<td><?php echo $delved->order_notes; ?></td>
                                                        <td><?php echo convertToOrderFormat($delved->created_time); ?></td>
                                                        <td><?php echo date("j M Y", strtotime($delved->created_date)); ?></td>
                                                        <td>
													     <button type="button" name="cancelled" onclick="cancelorder(<?php echo $delved->id; ?>)" class="btn btn-danger btn-icon-anim btn-square" title="Cancelled Order">
                                                            <i class="icon-trash"></i>
                                                          </button>
                                                        </td>
                                                        <td>
                                                            <form action="<?php echo base_url('admin/make_refund'); ?>" method="POST">
                                                                <input type="hidden" name="charge_id" value="<?php echo $delved->charge_id; ?>">
    													        <button class="btn btn-info btn-icon-anim btn-square" type="submit" title="Refunded">
                                                                    <i class="icon-check"></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                        <td>
                            							  <button class="btn btn-danger btn-icon-anim btn-square" onclick="deleteorder(<?php echo $delved->id; ?>)" title="Delete Order">
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
