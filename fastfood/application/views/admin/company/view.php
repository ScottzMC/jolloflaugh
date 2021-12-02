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
	<title>View All Company || Admin Fast Food</title>

</head>

<body>
        <!-- Main Content -->
		<div class="page-wrapper">
            <div class="container-fluid">
				<!-- Title -->
				<div class="row heading-bg bg-pink">
					<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
					  <h5 class="txt-light">View All Company</h5>
					</div>
					<!-- Breadcrumb -->
					<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
					  <ol class="breadcrumb">
						<li><a href="<?php echo site_url('admin/dashboard'); ?>">Dashboard</a></li>
						<li><a href="#"><span>Company</span></a></li>
						<li class="active"><span>View Company</span></li>
					  </ol>
					</div>
					<!-- /Breadcrumb -->
				</div>
				<!-- /Title -->

                 <script>
                    function deleteCompany(id){
                    var del_id = id;
                    if(confirm("Are you sure you want to delete this company")){
                    $.post('<?php echo base_url('admin/delete_company'); ?>', {"del_id": del_id}, function(data){
                      location.reload();
                      $('#cti').html(data)
                      });
                    }
                  }
                </script>
                <p id='cti'></p>

				<!-- Row -->
				<div class="row">
					<div class="col-sm-12">
						<div class="panel panel-default card-view">
							<div class="panel-heading">
								<div class="pull-left">
									<h6 class="panel-title txt-dark">Company</h6>
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
														<th>ID</th>
														<th>Code</th>
														<th>Title</th>
														<th>Delivery days</th>
														<th>Delivery hours</th>
														<th>Main Address</th>
                                                        <th>Edit</th>
                                                        <th>Delete</th>
													</tr>
												</thead>
												<tbody>
                                                    <?php if(!empty($company)){ foreach($company as $comp){ ?>
													<tr>
														<td>#<?php echo $comp->id; ?></td>
														<td><?php echo $comp->code; ?></td>
                            							<td><?php echo $comp->title; ?></td>
                            							<td><?php echo $comp->delivery_days; ?></td>
                            							<td><?php echo $comp->delivery_hours; ?></td>
                            							<td><?php echo $comp->main_address; ?></td>
                                                        <td>
                                                            <a href="<?php echo site_url('admin/edit_company/'.$comp->id); ?>" class="btn btn-info btn-icon-anim btn-square" title="Edit Company">
                                                                <i class="icon-check"></i>
                                                            </a>  
                                                        </td>
                                                        <td>
													        <button class="btn btn-danger btn-icon-anim btn-square" onclick="deleteCompany(<?php echo $comp->id; ?>)" title="Delete Company">
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
