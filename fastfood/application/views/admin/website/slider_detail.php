<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<title>Edit My Website Sliders || Admin</title>
	</head>

	<?php foreach($total_order_count as $tot_ord_count){} ?>

  <body>

      <!-- Main Content -->
<div class="page-wrapper">
<div class="container-fluid">
<!-- Title -->
<div class="row heading-bg  bg-pink">
<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
<h5 class="txt-light">Edit My Website Sliders</h5>
</div>
<!-- Breadcrumb -->
<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
<ol class="breadcrumb">
  <li><a href="<?php echo site_url('admin/dashboard'); ?>">Dashboard</a></li>
  <li><a href="#"><span>Edit Website</span></a></li>
  <li class="active"><span>Edit My Website Sliders</span></li>
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
      <h6 class="panel-title txt-dark">Sliders in Website</h6>
    </div>
    <div class="clearfix"></div>
  </div>

   <script>
	function delete_slider(id){
		var slider_id = id;
		if(confirm("Are you sure you want to delete this slider")){
		$.post('<?php echo base_url('admin/delete_slider'); ?>', {"slider_id": slider_id}, function(data){
			location.reload();
			$('#cte').html(data)
			});
		}
	}
	</script>
	<p id='cte'></p>

	<div class="panel-wrapper collapse in">
    <div class="panel-body">
      <p class="text-muted">Displays <code>Sliders from the different parts on the website from here.</code> eg - Sliders 1.</p>
      <div class="tags-default mt-40">
		<?php if(!empty($slider)){ foreach($slider as $slid){ ?>
		    <br>
			<img style="width: 170px; height: 120px;" src="<?php echo base_url('uploads/slider/'.$slid->image); ?>" alt="<?php echo $slid->image; ?>">
		    <br>
		    <label class="control-label mb-10"><?php echo $slid->title; ?></label><br>
              <br>
            <label class="control-label mb-10"><?php echo $slid->subtitle; ?></label><br>
              <br>
              <label class="control-label mb-10"><?php echo $slid->category; ?></label>
			<br><br>
			<button type="button" onclick="delete_slider(<?php echo $slid->id; ?>)" class="btn btn-danger btn-icon left-icon mr-10">
				<i class="fa fa-trash"></i>
			</button>
			<br>
        <?php } }else{ echo ''; } ?>
      </div>
    </div>
  </div>

</div>
</div>

<div class="col-md-4">
<div class="panel panel-default card-view">
  <div class="panel-heading">
    <div class="pull-left">
      <h6 class="panel-title txt-dark">Add Sliders in Website</h6>
    </div>
    <div class="clearfix"></div>
  </div>

  <form action="<?php echo base_url('admin/add_slider'); ?>" method="post" enctype="multipart/form-data" role="form">
	<div class="panel-wrapper collapse in">
    <div class="panel-body">
      <p class="text-muted">Add <code>Sliders on the website from here.</code> eg - Slider 1.</p>
			<input type="file" name="fileSlider[]">
			<br>
          <div class="form-group">
            <label class="control-label mb-10">Sliders Category</label>
            <label class="control-label mb-10">Add Title</label><br>
              <input type="text" name="title" style="color: black;" placeholder="Add Menu Title"/><br>
              <span><?php echo form_error('title'); ?></span>
              <br>
            <label class="control-label mb-10">Add Subtitle</label><br>
              <input type="text" name="subtitle" style="color: black;" placeholder="Add Menu Subtitle"/><br>
              <span><?php echo form_error('subtitle'); ?></span>
              <br>
              <label class="control-label mb-10">Slider Category</label>
            <select class="form-control" name="category">
              <option>Select</option>
			  <option value="Home">Home</option>
			  <option value="Staff">Staff</option>
			  <option value="jollof_n_laugh">Jollof N Laugh</option>
            </select>
			<br>
            <p class="text-muted">Add <code>Sliders Type for where the image would be displayed.</code> eg - Home, Fashion.</p>
          </div>

          <br>
         <button type="submit" name="add" class="btn btn-danger btn-icon left-icon mr-10">
          <i class="fa fa-check"></i>
        </button>
    </div>
  </div>
</form>

<br>

  <?php
      echo $this->session->flashdata('msgAddedError');
  ?>

</div>
</div>

<div class="col-md-4">
<div class="panel panel-default card-view">
  <div class="panel-heading">
    <div class="pull-left">
      <h6 class="panel-title txt-dark">Edit Sliders in Website</h6>
    </div>
    <div class="clearfix"></div>
  </div>

	<div class="panel-wrapper collapse in">
    <div class="panel-body">
      <p class="text-muted">Edit <code>Sliders from the different parts on the website from here.</code> eg - SLiders 1.</p>
      <div class="tags-default mt-40">
				<?php foreach($slider as $slid){ ?>
					<br>
					<img style="width: 170px; height: 120px;" src="<?php echo base_url('uploads/slider/'.$slid->image); ?>">
					<br>
					<h6><?php echo str_replace('-', ' ', $slid->title); ?></h6>
					<br>
					<h6><?php echo str_replace('-', ' ', $slid->category); ?></h6>
					<br>
					<p class="text-muted">Edit <code>Where the Slider would show on the Website by Type.</code> eg - Home.</p>
					<br>
          <a href="<?php echo site_url('admin/edit_slider/'.$slid->id); ?>" class="btn btn-danger btn-icon left-icon mr-10">
            <i class="fa fa-check"></i>
          </a>
          <br>
        <?php } ?>
      </div>
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
							url: "<?php echo base_url('admin/banner/get_banner_menu'); ?>",
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
