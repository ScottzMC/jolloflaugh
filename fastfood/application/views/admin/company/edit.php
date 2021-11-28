<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<?php foreach($edit_company as $edt_comp){} ?>
		<title>Edit <?php echo $edt_comp->title; ?> Company || Fast Food</title>
	</head>

	<?php //if(!empty($total_order_count)){ foreach($total_order_count as $tot_ord_count){} }else{ echo ''; } ?>

  <body>
          <!-- Main Content -->
  		<div class="page-wrapper">
              <div class="container-fluid">
  				<!-- Title -->
  				<div class="row heading-bg  bg-pink">
  					<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
  					  <h5 class="txt-light">Upload Company</h5>
  					</div>
  					<!-- Breadcrumb -->
  					<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
  					  <ol class="breadcrumb">
                            <li><a href="<?php echo site_url('admin/dashboard'); ?>">Dashboard</a></li>
  							<li><a href="<?php echo site_url('admin/view_company'); ?>"><span>Company</span></a></li>
  							<li class="active"><span>Edit <?php echo $edt_comp->title; ?> Company</span></li>
  					  </ol>
  					</div>
  					<!-- /Breadcrumb -->
  				</div>
  				<!-- /Title -->

					<!-- Row -->
					<div class="row">
						<div class="col-sm-12">
							<div class="panel panel-default card-view">
								<div class="panel-wrapper collapse in">
									<div class="panel-body">
										<div class="form-wrap">
											<form action="<?php echo base_url('admin/edit_company/'.$edt_comp->id); ?>" method="POST" enctype="multipart/form-data">
												<h6 class="txt-dark capitalize-font"><i class="icon-list mr-10"></i>about company</h6>
												<hr>
                                                <div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label mb-10">Code</label>
															<p><?php echo $edt_comp->code; ?></p>
                                                        </div>
													</div>
													
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label mb-10">Company Name</label>
															<input type="text" name="title" class="form-control" placeholder="" value="<?php echo $edt_comp->title; ?>">
                                                            <span class="text-danger"><?php echo form_error('title'); ?></span>
                                                        </div>
													</div>
													<!--/span-->

													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label mb-10">Delivery days</label>
                                                            <select class="form-control" data-placeholder="Choose a delvery day" name="delivery_days" required>
									                            <option value="<?php echo $edt_comp->delivery_days; ?>"><?php echo $edt_comp->delivery_days; ?></option>
															</select>
														</div>
													</div>
                                                    
                                                    <div class="col-md-6">
														<div class="form-group">
															<label class="control-label mb-10">Delivery hours</label>
                                                            <select class="form-control" data-placeholder="Choose a delivery hour" name="delivery_hours" required>
									                            <option value="<?php echo $edt_comp->delivery_hours; ?>"><?php echo $edt_comp->delivery_hours; ?></option>
															</select>
														</div>
													</div>
													
											    </div>
												<!--/row-->
												
													<!--/span-->

													<!--/span-->
												<div class="seprator-block"></div>
												<h6 class="txt-dark capitalize-font"><i class="icon-speech mr-10"></i>Main Address</h6>
												<hr>
												<div class="row">
													<div class="col-md-12">
														<div class="panel-wrapper collapse in">
															<div class="panel-body">
																<textarea id="editordata" class="summernote" rows="7" cols="15" name="main_address" required><?php echo $edt_comp->main_address; ?></textarea>
																<span class="text-danger"><?php echo form_error('main_address'); ?></span>
															</div>
														</div>
													</div>
												</div>
												<!--/row-->

													<!--/span-->

												<div class="form-actions">
													<button type="submit" name="edit" class="btn btn-success btn-icon left-icon mr-10">
                                                    <i class="fa fa-check"></i>
                                                    <span>Update</span>
                                                  </button>
												</div>
											</form>
                                            
                                            <?php echo $this->session->flashdata('msgError'); ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- /Row -->
				
				</div>

				</div>

			</div>
			<!-- /Main Content -->

			<script type="text/javascript">
				$(document).ready(function(){
					$('#banner_type').on('change', function(){
						var type = $(this).val();
						if(type == ''){
							$('#banner_category').prop('disabled', true);
						}else{
							$('#banner_category').prop('disabled', false);
							$.ajax({
								url: "<?php echo base_url('admin/get_banner_menu'); ?>",
								type: "post",
								data: {'type' : type},
								dataType: 'json',
								success: function(data){
									$('#banner_category').html(data);
								},
								error: function(data){
									alert('Error Occurred');
								}
							});
						}
					});
				});
			</script>
			
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="<?php echo base_url('vendors/bower_components/jquery/dist/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('vendors/bower_components/bootstrap/dist/js/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo base_url('dist/js/init.js'); ?>"></script>

	</body>
</html>
