<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>View Venue || Ticket Event</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
	<link rel="stylesheet" href="<?php echo base_url('vendor/chartist/css/chartist.min.css'); ?>">
    <link href="<?php echo base_url('vendor/bootstrap-select/dist/css/bootstrap-select.min.css'); ?>" rel="stylesheet">
	<link href="<?php echo base_url('vendor/datatables/css/jquery.dataTables.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('css/style.css'); ?>" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
</head>
<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <?php include 'menu/nav.php'; ?>
		
		<!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->
			<div class="container-fluid">
			    
			    <div class="page-titles">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="javascript:void(0)">Venue</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)">View All Venue</a></li>
					</ol>
                </div>

				<div class="d-flex flex-wrap mb-2 align-items-center justify-content-between">
					<!--<div class="mb-3">
						<h6 class="fs-16 text-black font-w600 mb-0">568 Total Orders</h6>
						<span class="fs-14">Based your preferences</span>
					</div>-->
					<div class="event-tabs mb-3">
						<ul class="nav nav-tabs" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" data-toggle="tab" href="#All" role="tab" aria-selected="true">
									All
								</a>
							</li>
						</ul>
					</div>
				</div>
				
				<script>
                  function deleteVenue(id){
                    var del_id = id;
                    if(confirm("Are you sure you want to delete this venue")){
                    $.post('<?php echo base_url('venue/delete'); ?>', {"del_id": del_id}, function(data){
                      location.reload();
                      $('#cte').html(data)
                      });
                    }
                  }
                </script>
                <p id='cte'></p>
				
				<div class="row">
					<div class="col-xl-12">	
						<div class="tab-content">
							<div id="All" class="tab-pane active">
								<div class="table-responsive fs-14">
									<table class="table mb-4 dataTablesCard no-hover card-table fs-14" id="example5">
										<thead>
											<tr>
												<th class="d-none d-lg-inline-block">ID</th>
												<th>Title</th>
												<th>Image1</th>
												<th>Image2</th>
												<th>Body</th>
												<th>Maps</th>
												<th>Action</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
										    <?php if(!empty($venue)){ foreach($venue as $ven){ ?>
											<tr>
												<td class="d-none d-lg-table-cell"><?php echo $ven->id; ?></td>
												<td class="d-none d-lg-table-cell"><h4 class="font-w600 mb-1 wspace-no"><a href="javascript:void(0)" class="text-black"><?php echo $ven->title; ?></a></h4></td>
												<td>
													<div class="media align-items-center">
														<img class="img-fluid rounded mr-3 d-none d-xl-inline-block" width="120" src="https://scottnnaghor.com/jollof_n_laugh/uploads/venue/<?php echo $ven->image1; ?>" alt="<?php echo $ven->title; ?>">
													</div>
												</td>
												<td>
													<div class="media align-items-center">
														<img class="img-fluid rounded mr-3 d-none d-xl-inline-block" width="120" src="https://scottnnaghor.com/jollof_n_laugh/uploads/venue/<?php echo $ven->image2; ?>" alt="<?php echo $ven->title; ?>">
													</div>
												</td>
												<td class="d-none d-lg-table-cell"><h4 class="font-w600 mb-1 wspace-no"><a href="javascript:void(0)" class="text-black"><?php echo $ven->body; ?></a></h4></td>
												<td class="d-none d-lg-table-cell"><h4 class="font-w600 mb-1 wspace-no"><a href="javascript:void(0)" class="text-black"><?php echo $ven->maps; ?></a></h4></td>
												<td>
													<div class="d-flex">
														<a href="<?php echo site_url('venue/edit/'.$ven->id); ?>" class="btn btn-info btn-sm light px-4">Edit</a>
													</div>
												</td>
												<td>
													<div class="d-flex">
														<button type="button" onclick="deleteVenue(<?php echo $ven->id; ?>)" class="btn btn-danger btn-sm light ml-2 px-4">Delete</button>
													</div>
												</td>
											</tr>
											<?php } } ?>
										</tbody>
									</table>
								</div>
							</div>
							
						</div>
					</div>
				</div>
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->

        <?php include 'menu/footer.php'; ?>

    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="<?php echo base_url('vendor/global/global.min.js'); ?>"></script>
	<script src="<?php echo base_url('vendor/bootstrap-select/dist/js/bootstrap-select.min.js'); ?>"></script>
	<script src="<?php echo base_url('vendor/chart.js/Chart.bundle.min.js'); ?>"></script>
    <script src="<?php echo base_url('js/custom.min.js'); ?>"></script>
	<script src="<?php echo base_url('js/deznav-init.js'); ?>"></script>
	<script src="<?php echo base_url('vendor/owl-carousel/owl.carousel.js'); ?>"></script>
	<!-- Datatable -->
	<script src="<?php echo base_url('vendor/datatables/js/jquery.dataTables.min.js'); ?>"></script>
	<script>
		(function($) {
			var table = $('#example5').DataTable({
				searching: false,
				paging:true,
				select: false,
				//info: false,         
				lengthChange:false 
				
			});
			var table = $('#example3').DataTable({
				searching: false,
				paging:true,
				select: false,
				//info: false,         
				lengthChange:false 
				
			});
			var table = $('#example2').DataTable({
				searching: false,
				paging:true,
				select: false,
				//info: false,         
				lengthChange:false 
				
			});
			var table = $('#example4').DataTable({
				searching: false,
				paging:true,
				select: false,
				//info: false,         
				lengthChange:false 
				
			});
			$('#example tbody').on('click', 'tr', function () {
				var data = table.row( this ).data();
				
			});
		})(jQuery);
	</script>
</body>
</html>