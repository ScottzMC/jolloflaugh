<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<title>Edit My Website Banners || Admin</title>
	</head>

	<?php foreach($total_order_count as $tot_ord_count){} ?>

  <body>

      <!-- Main Content -->
<div class="page-wrapper">
<div class="container-fluid">
<!-- Title -->
<div class="row heading-bg  bg-pink">
<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
<h5 class="txt-light">Edit My Website Banners</h5>
</div>
<!-- Breadcrumb -->
<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
<ol class="breadcrumb">
  <li><a href="<?php echo site_url('admin/dashboard'); ?>">Dashboard</a></li>
  <li><a href="#"><span>Edit Website</span></a></li>
  <li class="active"><span>Edit My Website Banners</span></li>
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
      <h6 class="panel-title txt-dark">Banners in Website</h6>
    </div>
    <div class="clearfix"></div>
  </div>

   <script>
	function delete_banner(id){
		var banner_id = id;
		if(confirm("Are you sure you want to delete this banner")){
		$.post('<?php echo base_url('admin/delete_banner'); ?>', {"banner_id": banner_id}, function(data){
			location.reload();
			$('#cte').html(data)
			});
		}
	}
	</script>
	<p id='cte'></p>

	<div class="panel-wrapper collapse in">
    <div class="panel-body">
      <p class="text-muted">Displays <code>Banners from the different parts on the website from here.</code> eg - Banners 1.</p>
      <div class="tags-default mt-40">
		<?php if(!empty($banner)){ foreach($banner as $ban){ ?>
			<br>
			<img style="width: 170px; height: 120px;" src="<?php echo base_url('uploads/banner/'.$ban->image); ?>" alt="<?php echo $ban->image; ?>">
			<button type="button" onclick="delete_banner(<?php echo $ban->id; ?>)" class="btn btn-danger btn-icon left-icon mr-10">
				<i class="fa fa-trash"></i>
			</button>
			<br>
			<h6><?php echo str_replace('-', ' ', $ban->title); ?></h6>
			<h6><?php echo str_replace('-', ' ', $ban->type); ?></h6>
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
      <h6 class="panel-title txt-dark">Add Banners in Website</h6>
    </div>
    <div class="clearfix"></div>
  </div>

  <form action="<?php echo base_url('admin/add_banner'); ?>" method="post" enctype="multipart/form-data" role="form">
	<div class="panel-wrapper collapse in">
    <div class="panel-body">
      <p class="text-muted">Add <code>Banners on the website from here.</code> eg - Slider 1.</p>
			<input type="file" name="fileBanner[]">
			<br>
          <div class="form-group">
             <label class="control-label mb-10">Add Title</label><br>
              <input type="text" name="title" style="color: black; width: 300px;" placeholder="Add Menu Title"/><br>
              <span><?php echo form_error('category'); ?></span>
              <br>
              <label class="control-label mb-10">Type</label>
            <select class="form-control" data-placeholder="Choose a Type" id="banner_type" name="type">
              <option>Select</option>
              <option value="Home">Home</option>
              <option value="Staff">Staff</option>
              <option value="jollof_n_laugh">Jollof N Laugh</option>
              <?php foreach($category_menu as $catmenu){ ?>
                <option value="<?php echo $catmenu->type; ?>"><?php echo $catmenu->type; ?></option>
              <?php }?>
            </select>
            <span><?php echo form_error('type'); ?></span>
            <p class="text-muted">Add <code>Banners Type for where the image would be displayed.</code> eg - Home, Fashion.</p>
          </div>

          <br>
         <button type="submit" class="btn btn-danger btn-icon left-icon mr-10">
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
      <h6 class="panel-title txt-dark">Edit Banners in Website</h6>
    </div>
    <div class="clearfix"></div>
  </div>

	<div class="panel-wrapper collapse in">
    <div class="panel-body">
      <p class="text-muted">Edit <code>Banners from the different parts on the website from here.</code> eg - Banners 1.</p>
      <div class="tags-default mt-40">
				<?php foreach($banner as $ban){ ?>
					<br>
					<img style="width: 170px; height: 120px;" src="<?php echo base_url('uploads/banner/'.$ban->image); ?>">
					<br>
					<br><h6><?php echo $ban->title; ?></h6>
					<br>
					<h6><?php echo str_replace('-', ' ', $ban->type); ?></h6>
					<br>
					<p class="text-muted">Edit <code>Where the Slider would show on the Website by Type.</code> eg - Fashion.</p>
					<br>
                    <a href="<?php echo site_url('admin/edit_banner/'.$ban->id); ?>" class="btn btn-danger btn-icon left-icon mr-10">
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
