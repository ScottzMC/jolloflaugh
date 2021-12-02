<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<title>Add New Food || Fast Food</title>
	</head>

	<?php //if(!empty($total_order_count)){ foreach($total_order_count as $tot_ord_count){} }else{ echo ''; } ?>

  <body>
          <!-- Main Content -->
  		<div class="page-wrapper">
              <div class="container-fluid">
  				<!-- Title -->
  				<div class="row heading-bg  bg-pink">
  					<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
  					  <h5 class="txt-light">Upload Food</h5>
  					</div>
  					<!-- Breadcrumb -->
  					<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
  					  <ol class="breadcrumb">
                            <li><a href="<?php echo site_url('admin/dashboard'); ?>">Dashboard</a></li>
  							<li><a href="<?php echo site_url('admin/view_food'); ?>"><span>All Foods</span></a></li>
  							<li class="active"><span>Upload a Food</span></li>
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
											<form action="<?php echo base_url('admin/add_food'); ?>" method="POST" enctype="multipart/form-data">
												<h6 class="txt-dark capitalize-font"><i class="icon-list mr-10"></i>about food</h6>
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
															<label class="control-label mb-10">Type</label>
															<!--<select class="form-control" data-placeholder="Choose a Type" tabindex="1" id="type" name="type" onchange="changeProduct('type', 'category', 'subcategory', 'model', 'year', 'location')">-->
                                                            <select class="form-control" data-placeholder="Choose a Type" name="type" required>
                                                                <option>Select</option>
                                                                <option value="All">All</option>
                                                                <option value="jollof_n_laugh">Jollof N Laugh</option>
															</select>
														</div>
													</div>

													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label mb-10">Category</label>
															<!--<select class="form-control" data-placeholder="Choose a Type" tabindex="1" id="type" name="type" onchange="changeProduct('type', 'category', 'subcategory', 'model', 'year', 'location')">-->
                                                            <select class="form-control" data-placeholder="Choose a Type" name="category" required>
                                                                <option>Select</option>
																<?php
																$query = $this->db->query("SELECT DISTINCT category FROM menu")->result();
																if(!empty($query)){
																foreach($query as $qry){ ?>
																<option value="<?php echo $qry->category; ?>"><?php echo str_replace('-', ' ', $qry->category); ?></option>
									                            <?php } }else{ ?>
									                            <option>No Data</option>
									                            <?php } ?>
															   </select>
															
    															<br><label class="control-label mb-10">Price</label>
    															<div class="input-group">
    																<div class="input-group-addon"><i>&pound;</i></div>
    																<input type="text" name="price" class="form-control" placeholder="1000" required>
                                                                    <span class="text-danger"><?php echo form_error('price'); ?></span>
    															</div>
														</div>
													</div>
													
											    </div>
												<!--/row-->
												
												<div class="row">
													<div class="col-md-6">
														<!--<div class="form-group">
															<label class="control-label mb-10">Price</label>
															<div class="input-group">
																<div class="input-group-addon"><i>&pound;</i></div>
																<input type="text" name="price" class="form-control" placeholder="1000" required>
                                                                <span class="text-danger"><?php echo form_error('price'); ?></span>
															</div>
														</div>-->
														<!--<div class="form-group">
															<label class="control-label mb-10">Available Days</label>
															<div class="input-group">
																<input type="checkbox" name="all" class="" value="Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday"> All<br>
																<input type="checkbox" name="days[]" class="" value="Monday"> Monday<br>
																<input type="checkbox" name="days[]" class="" value="Tuesday"> Tuesday<br>
																<input type="checkbox" name="days[]" class="" value="Wednesday"> Wednesday<br>
																<input type="checkbox" name="days[]" class="" value="Thursday"> Thursday<br>
																<input type="checkbox" name="days[]" class="" value="Friday"> Friday<br>
																<input type="checkbox" name="days[]" class="" value="Saturday"> Saturday<br>
																<input type="checkbox" name="days[]" class="" value="Sunday"> Sunday<br>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label mb-10">Stock</label>
															<div class="input-group">
																<input type="radio" name="stock" class="" value="Yes"> Yes<br>
																<input type="radio" name="stock" class="" value="No"> No<br>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label mb-10">Meal Voucher</label>
															<div class="input-group">
																<input type="radio" name="meal_voucher" class="" value="Yes"> Yes<br>
																<input type="radio" name="meal_voucher" class="" value="No"> No<br>
															</div>
														</div>-->
													</div>
													<!--<div class="col-md-6">
														<div class="form-group">
															<label class="control-label mb-10">Side Meal</label>
															<!--<select class="form-control" data-placeholder="Choose a Type" tabindex="1" id="type" name="type" onchange="changeProduct('type', 'category', 'subcategory', 'model', 'year', 'location')">-->
                                                            <!--<select class="form-control" data-placeholder="Choose a Side Meal" name="side_meal" required>
                                                                <option>Select</option>
                                                                <option value="Yes">Yes</option>
                                                                <option value="No">No</option>
															</select>
														</div>
														
														<div class="form-group">
															<label class="control-label mb-10">Side Drinks</label>
															<!--<select class="form-control" data-placeholder="Choose a Type" tabindex="1" id="type" name="type" onchange="changeProduct('type', 'category', 'subcategory', 'model', 'year', 'location')">-->
                                                            <!--<select class="form-control" data-placeholder="Choose a Side Drinks" name="side_drink" required>
                                                                <option>Select</option>
                                                                <option value="Yes">Yes</option>
                                                                <option value="No">No</option>
															</select>
														</div>
														
														<div class="form-group">
															<label class="control-label mb-10">Availablilty</label>
															<select class="form-control" data-placeholder="Choose Availability Start" name="delivery_start" required>
                                                                <option>Select Start Time</option>
                                                                <option value="09:00">9:00am</option>
                                                                <option value="09:30">9:30am</option>
                                                                <option value="10:00">10:00am</option>
                                                                <option value="10:30">10:30am</option>
                                                                <option value="11:00">11:00am</option>
                                                                <option value="11:30">11:30am</option>
                                                                <option value="12:00">12:00pm</option>
                                                                <option value="12:30">12:30pm</option>
                                                                <option value="13:00">01:00pm</option>
                                                                <option value="13:30">01:30pm</option>
                                                                <option value="14:30">02:00pm</option>
                                                                <option value="14:30">02:30pm</option>
                                                                <option value="15:00">03:00pm</option>
                                                                <option value="15:30">03:30pm</option>
                                                                <option value="16:00">04:00pm</option>
                                                                <option value="16:30">04:30pm</option>
                                                                <option value="17:00">05:00pm</option>
                                                                <option value="17:30">05:30pm</option>
                                                                <option value="18:00">06:00pm</option>  
															</select>
															<br>
															<select class="form-control" data-placeholder="Choose Availability End" name="delivery_end" required>
                                                                <option>Select End Time</option>
                                                                <option value="09:00">9:00am</option>
                                                                <option value="09:30">9:30am</option>
                                                                <option value="10:00">10:00am</option>
                                                                <option value="10:30">10:30am</option>
                                                                <option value="11:00">11:00am</option>
                                                                <option value="11:30">11:30am</option>
                                                                <option value="12:00">12:00pm</option>
                                                                <option value="12:30">12:30pm</option>
                                                                <option value="13:00">01:00pm</option>
                                                                <option value="13:30">01:30pm</option>
                                                                <option value="14:30">02:00pm</option>
                                                                <option value="14:30">02:30pm</option>
                                                                <option value="15:00">03:00pm</option>
                                                                <option value="15:30">03:30pm</option>
                                                                <option value="16:00">04:00pm</option>
                                                                <option value="16:30">04:30pm</option>
                                                                <option value="17:00">05:00pm</option>
                                                                <option value="17:30">05:30pm</option>
                                                                <option value="18:00">06:00pm</option>  
															</select>
														</div>
													  </div>-->

													<!--/span-->
												</div>
												
													<!--/span-->
												<!--<hr>
												<h6 class="txt-dark capitalize-font"><i class="icon-speech mr-10"></i>Description</h6>
												<hr>-->
												<!--<div class="row">
													<div class="col-md-12">
														<div class="panel-wrapper collapse in">
															<div class="panel-body">
																<textarea id="editordata" class="summernote" rows="7" cols="15" name="description" required></textarea>
															</div>
														</div>
													</div>
												</div>
												<!--/row-->
												<!--/span-->

												<div class="seprator-block"></div>
												<h6 class="txt-dark capitalize-font"><i class="icon-picture mr-10"></i>upload image</h6>
												<hr>
												<div class="row">
													<div class="col-lg-12">
														<label>Image 1</label>
														<input type="file" name="userFiles1[]" class="filestyle" data-buttonname="btn-primary">
														<br>
														<!--<label>Image 2</label>
														<input type="file" name="userFiles2[]" class="filestyle" data-buttonname="btn-primary">
														<br>
														<label>Image 3</label>
														<input type="file" name="userFiles3[]" class="filestyle" data-buttonname="btn-primary">
														<br>
														<label>Image 4</label>
														<input type="file" name="userFiles4[]" class="filestyle" data-buttonname="btn-primary">
														<br>
														<label>Image 5</label>
														<input type="file" name="userFiles5[]" class="filestyle" data-buttonname="btn-primary">-->
													 </div>
													</div>
												</div>
												<div class="seprator-block"></div>
												<hr>

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
