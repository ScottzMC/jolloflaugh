<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <?php foreach($edit_menu as $edt_menu){} ?>
		<title>Edit <?php echo str_replace('-', ' ', $edt_menu->category); ?> Menu || Admin</title>
	</head>

	<?php foreach($total_order_count as $tot_ord_count){} ?>

  <body>
      <!-- Main Content -->
<div class="page-wrapper">
<div class="container-fluid">
<!-- Title -->
<div class="row heading-bg  bg-pink">
<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
<h5 class="txt-light">Dasboard Edit <?php echo $edt_menu->category; ?> Menu</h5>
</div>
<!-- Breadcrumb -->
<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
<ol class="breadcrumb">
  <li><a href="<?php echo site_url('admin/dashboard'); ?>">Dashboard</a></li>
  <li><a href="#"><span>Edit Website</span></a></li>
  <li class="active"><span>Edit <?php echo str_replace('-', ' ', $edt_menu->category); ?> Menu</span></li>
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
      <h6 class="panel-title txt-dark">Edit Menu in Website</h6>
    </div>
    <div class="clearfix"></div>
  </div>

	<div class="panel-wrapper collapse in">
    <div class="panel-body">
      <form action="<?php echo base_url('admin/edit_menu/'.$edt_menu->id); ?>" method="post" role="form">
      <p class="text-muted">Add <code>Menu of the website.</code></p>
          <br>
          <label class="control-label mb-10">Add Menu Category</label><br>
          <input type="text" name="category" style="color: black;" value="<?php echo str_replace('-', ' ', $edt_menu->category); ?>"/><br>
          <span><?php echo form_error('category'); ?></span>
          <br><p class="text-muted">Add <code> Menu Category of the Website.</code>e.g - Shampoo</p>
          <br><br>

          <br>
         <button type="submit" name="edit" class="btn btn-danger btn-icon left-icon mr-10">
          <i class="fa fa-check"></i>
        </button>
      </form>
    </div>
  </div>

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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="<?php echo base_url('vendors/bower_components/jquery/dist/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('vendors/bower_components/bootstrap/dist/js/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo base_url('dist/js/init.js'); ?>"></script>

	</body>
</html>
