<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <?php foreach($edit_banner as $edt_ban){}?>
		<title>Edit <?php echo $edt_ban->title; ?> Banners || Admin</title>
	</head>

	<?php foreach($total_order_count as $tot_ord_count){} ?>

  <body>

      <!-- Main Content -->
<div class="page-wrapper">
<div class="container-fluid">
<!-- Title -->
<div class="row heading-bg  bg-pink">
<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
<h5 class="txt-light">Edit <?php echo $edt_ban->title; ?> Banners</h5>
</div>
<!-- Breadcrumb -->
<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
<ol class="breadcrumb">
  <li><a href="<?php echo site_url('admin/dashboard'); ?>">Dashboard</a></li>
  <li><a href="#"><span>Edit Website Details</span></a></li>
  <li><a href="<?php echo site_url('admin/banner'); ?>">Edit Banners</a></li>
  <li class="active"><span>Edit <?php echo $edt_ban->title; ?> Banners</span></li>
</ol>
</div>
<!-- /Breadcrumb -->
</div>
<!-- /Title -->

<!-- Row -->
<div class="row">

<div class="col-md-4">
<div class="panel panel-default card-view">
  <div class="panel-heading">
    <div class="pull-left">
      <h6 class="panel-title txt-dark">Edit Banners in Website</h6>
    </div>
    <div class="clearfix"></div>
  </div>

  <div class="panel-wrapper collapse in">
    <form action="<?php echo base_url('admin/edit_banner/'.$edt_ban->id); ?>" method="post" enctype="multipart/form-data" role="form">
    <div class="panel-body">
      <p class="text-muted">Edit <code>Banner from the different parts on the website from here.</code> eg - Banner 1.</p>
      <div class="tags-default mt-40">
        <?php if(!empty($edit_banner)){ foreach($edit_banner as $edt_ban){ ?>
          <br>
          <img style="width: 170px; height: 120px;" src="<?php echo base_url('uploads/banner/'.$edt_ban->image); ?>">
          <br>
          <input type="file" name="fileBanner[]">
          <br>
          <div class="form-group">
            <label class="control-label mb-10">Title</label><br>
              <input type="text" name="title" style="color: black; width: 350px;" placeholder="Title" value="<?php echo $edt_ban->title; ?>" /><br>
              <span><?php echo form_error('title'); ?></span>
              <br>
            <label class="control-label mb-10">Banner Type</label>
            <select class="form-control" name="type">
              <option>Select</option>
			  <option value="Home">Home</option>
			  <option value="Staff">Staff</option>
			  <option value="jollof_n_laugh">Jollof N Laugh</option>
            </select>
            <span><?php echo form_error('type'); ?></span>
          </div>
          <h6><?php echo $edt_ban->type; ?></h6>
          <p class="text-muted">Edit <code>Where the Banner would show on the Website by Type.</code> eg - Staff.</p>
          <br>
          <button type="submit" name="edit" class="btn btn-danger btn-icon left-icon mr-10">
            <i class="fa fa-check"></i>
          </button>
        <?php } }else{ echo ''; } ?>
      </div>
    </form>
		<?php
	      echo $this->session->flashdata('msgEditError');
	  ?>
    </div>
  </div>

</div>
</div>

</div>
<!-- /Row -->

</div>

    <script type="text/javascript">
			$(document).ready(function(){
				$('#banner_type').on('change', function(){
					var type = $(this).val();
					if(type == ''){
						$('#banner_category').prop('disabled', true);
					}else{
						$('#banner_category').prop('disabled', false);
						$.ajax({
							url: "<?php echo base_url('admin/edit/banner/get_banner_menu'); ?>",
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
