<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<title>Add New Meal Voucher || Fast Food</title>
	</head>

	<?php //if(!empty($total_order_count)){ foreach($total_order_count as $tot_ord_count){} }else{ echo ''; } ?>

  <body>
          <!-- Main Content -->
  		<div class="page-wrapper">
              <div class="container-fluid">
  				<!-- Title -->
  				<div class="row heading-bg  bg-pink">
  					<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
  					  <h5 class="txt-light">Upload Meal Voucher</h5>
  					</div>
  					<!-- Breadcrumb -->
  					<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
  					  <ol class="breadcrumb">
                            <li><a href="<?php echo site_url('admin/dashboard'); ?>">Dashboard</a></li>
  							<li><a href="<?php echo site_url('admin/view_voucher'); ?>"><span>Meal Voucher</span></a></li>
  							<li class="active"><span>Upload a Meal Voucher</span></li>
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
											<form action="<?php echo base_url('admin/add_meal_voucher'); ?>" method="POST" enctype="multipart/form-data">
												<h6 class="txt-dark capitalize-font"><i class="icon-list mr-10"></i>Upload Meal Voucher</h6>
												<hr>
                                                <div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label mb-10">Title</label>
															<input type="text" name="title" class="form-control" placeholder="">
                                                            <span class="text-danger"><?php echo form_error('title'); ?></span>
                                                        </div>
													</div>
													<!--/span-->

													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label mb-10">Company</label>
															<!--<select class="form-control" data-placeholder="Choose a Type" tabindex="1" id="type" name="type" onchange="changeProduct('type', 'category', 'subcategory', 'model', 'year', 'location')">-->
                                                            <select class="form-control" data-placeholder="Choose a Type" name="company" required>
                                                                <option>Select</option>
																<?php
																$query = $this->db->query("SELECT DISTINCT title FROM company")->result();
																if(!empty($query)){
																foreach($query as $qry){ ?>
																<option value="<?php echo $qry->title; ?>"><?php echo str_replace('-', ' ', $qry->title); ?></option>
									                            <?php } }else{ ?>
									                            <option>No Data</option>
									                            <?php } ?>
															</select>
														</div>
													</div>
													
											    </div>
												<!--/row-->
												
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label mb-10">Price</label>
															<div class="input-group">
																<div class="input-group-addon"><i>&pound;</i></div>
																<input type="text" name="price" class="form-control" placeholder="1000" required>
                                                                <span class="text-danger"><?php echo form_error('price'); ?></span>
															</div>
														</div>
													</div>
													
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label mb-10">Bulk</label>
                                                            <select class="form-control" data-placeholder="Choose a Bulk" name="bulk" required>
                                                                <option>Select</option>
									                            <option value="Yes">Yes</option>
									                            <option value="No">No</option>
															</select>
														</div>
													</div>
													
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label mb-10">Quantity</label>
															<div class="input-group">
																<div class="input-group-addon"><i>Qty</i></div>
																<input type="text" name="quantity" class="form-control" placeholder="1" required>
                                                                <span class="text-danger"><?php echo form_error('quantity'); ?></span>
															</div>
														</div>
													</div>
													
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label mb-10">Type</label>
                                                            <select class="form-control" data-placeholder="Choose a Voucher Type" name="type" required>
                                                                <option>Select</option>
									                            <option value="Large">Large</option>
									                            <option value="Regular">Regular</option>
															</select>
														</div>
													</div>
													
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label mb-10">Category</label>
                                                            <select class="form-control" data-placeholder="Choose a Voucher Type" name="category" required>
                                                                <option>Select</option>
									                            <option value="Offer Meals">Offer Meals</option>
									                            <option value="Family Meals">Family Meals</option>
															</select>
														</div>
													</div>
													
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label mb-10">Discount</label>
															<div class="input-group">
																<div class="input-group-addon"><i>%</i></div>
																<input type="text" name="discount" class="form-control" placeholder="10" required>
                                                                <span class="text-danger"><?php echo form_error('discount'); ?></span>
															</div>
														</div>
													</div>
													
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label mb-10">Voucher Code</label>
															<div class="input-group">
																<div class="input-group-addon"><i>#</i></div>
																<input type="text" name="code" class="form-control" placeholder="VVV" required>
                                                                <span class="text-danger"><?php echo form_error('code'); ?></span>
															</div>
														</div>
													</div>
													
												</div>	
													<!--/span-->

													<!--/span-->
												<div class="seprator-block"></div>
												<h6 class="txt-dark capitalize-font"><i class="icon-speech mr-10"></i>Description</h6>
												<hr>
												<div class="row">
													<div class="col-md-12">
														<div class="panel-wrapper collapse in">
															<div class="panel-body">
																<textarea id="editordata" class="summernote" rows="7" cols="15" name="description" required></textarea>
																<span class="text-danger"><?php echo form_error('description'); ?></span>
															</div>
														</div>
													</div>
												</div>
												<!--/row-->

													<!--/span-->

												<div class="form-actions">
													<button type="submit" name="add" class="btn btn-success btn-icon left-icon mr-10">
                                                    <i class="fa fa-check"></i>
                                                    <span>Upload</span>
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
