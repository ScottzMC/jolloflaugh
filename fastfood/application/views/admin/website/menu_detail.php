<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<title>Edit My Website Menu || Admin Beauty Shop</title>
	</head>

	<?php foreach($total_order_count as $tot_ord_count){} ?>

  <body>
      <!-- Main Content -->
<div class="page-wrapper">
<div class="container-fluid">
<!-- Title -->
<div class="row heading-bg  bg-pink">
<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
<h5 class="txt-light">Edit My Website Menu</h5>
</div>
<!-- Breadcrumb -->
<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
<ol class="breadcrumb">
  <li><a href="<?php echo site_url('admin/dashboard'); ?>">Dashboard</a></li>
  <li><a href="#"><span>Edit Website</span></a></li>
  <li class="active"><span>Edit Menu</span></li>
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
      <h6 class="panel-title txt-dark">Menu in Website</h6>
    </div>
    <div class="clearfix"></div>
  </div>

  <script>

	function delete_menu(id){
    var menu_id = id;
    if(confirm("Are you sure you want to delete this Menu")){
    $.post('<?php echo base_url('admin/delete_menu'); ?>', {"menu_id": menu_id}, function(data){
      location.reload();
      $('#cta').html(data)
      });
    }
  }
  </script>
	<p id="cta"></p>

	<div class="panel-wrapper collapse in">
    <div class="panel-body">
      <p class="text-muted">Displays <code>Menu on the website from here.</code></p>
      <div class="tags-default mt-40">
			<?php if(!empty($menu)){ foreach($menu as $men){ ?>
			<br>
			<h5><?php echo str_replace('-', ' ', $men->category); ?></h5>
            <br>
          <button type="button" onclick="delete_menu(<?php echo $men->id; ?>)" class="btn btn-danger btn-icon left-icon mr-10">
            <i class="fa fa-trash"></i>
          </button>
          <br>
        <?php } }else{ echo '<div class="alert alert-danger" role="alert">No Menu</div>'; } ?>
      </div>
    </div>
  </div>

</div>
</div>

<div class="col-md-4">
<div class="panel panel-default card-view">
  <div class="panel-heading">
    <div class="pull-left">
      <h6 class="panel-title txt-dark">Add Menu in Website</h6>
    </div>
    <div class="clearfix"></div>
  </div>

	<div class="panel-wrapper collapse in">
    <div class="panel-body">
      <form action="<?php echo base_url('admin/add_menu'); ?>" method="post" role="form">
      <p class="text-muted">Add <code>Menu of the website.</code></p>
          <br>
          <label class="control-label mb-10">Add Menu Category</label><br>
          <input type="text" name="category" style="color: black;" placeholder="Add Menu Category"/><br>
          <span><?php echo form_error('category'); ?></span>
          <br><p class="text-muted">Add <code> Menu Category of the Website Store.</code>e.g - Chicken</p>
          <br>
         <button type="submit" class="btn btn-danger btn-icon left-icon mr-10">
          <i class="fa fa-check"></i>
        </button>
      </form>
    </div>
  </div>

  <?php
      echo $this->session->flashdata('msgMenuError');
  ?>

</div>
</div>

<div class="col-md-4">
<div class="panel panel-default card-view">
  <div class="panel-heading">
    <div class="pull-left">
      <h6 class="panel-title txt-dark">Edit Menu in Website</h6>
    </div>
    <div class="clearfix"></div>
  </div>

  <div class="panel-wrapper collapse in">
    <div class="panel-body">
      <p class="text-muted">Edit <code>Menu on the website in the text area.</code></p>
      <div class="tags-default mt-40">
		  <?php if(!empty($menu)){ foreach($menu as $men){ ?>
		  <br>
          <p><?php echo str_replace('-', ' ', $men->category); ?></p>
           <br>

          <a href="<?php echo site_url('admin/edit_menu/'.$men->id); ?>" class="btn btn-danger btn-icon left-icon mr-10">
            <i class="fa fa-check"></i>
          </a>
          <br>

        <?php } }else{ echo '<div class="alert alert-danger" role="alert">No Menu</div>'; } ?>
      </div>
    </div>
  </div>

<?php
  if($this->form_validation->run() == TRUE){
    echo $this->session->flashdata('msgMenuInfo');
    echo $this->session->flashdata('msgMenuInfoError');
  }
?>

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
