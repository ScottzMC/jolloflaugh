
<!-- Favicon -->
<link rel="shortcut icon" href="favicon.ico">
<link rel="icon" href="<?php echo base_url('favicon.ico'); ?>" type="image/x-icon">

<!-- vector map CSS -->
<link href="<?php echo base_url('vendors/vectormap/jquery-jvectormap-2.0.2.css'); ?>" rel="stylesheet" type="text/css"/>

<!-- Custom Fonts -->
<link href="<?php echo base_url('dist/css/font-awesome.min.css'); ?>" rel="stylesheet" type="text/css">

<!-- Data table CSS -->
<link href="<?php echo base_url('vendors/bower_components/datatables/media/css/jquery.dataTables.min.css'); ?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url('vendors/bower_components/jquery-toast-plugin/dist/jquery.toast.min.css'); ?>" rel="stylesheet" type="text/css">

<!-- Bootstrap Dropzone CSS -->
<link href="<?php echo base_url('vendors/bower_components/dropzone/dist/dropzone.css'); ?>" rel="stylesheet" type="text/css"/>

<link rel="stylesheet" href="<?php echo base_url('vendors/bower_components/summernote/dist/summernote.css'); ?>" />

<!-- Custom CSS -->
<link href="<?php echo base_url('dist/css/style.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('vendors/bower_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css'); ?>" rel="stylesheet" type="text/css"/>

<!--Preloader-->
<div class="preloader-it">
  <div class="la-anim-1"></div>
</div>
<!--/Preloader-->
  <div class="wrapper">
    <!-- Top Menu Items -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <a id="toggle_nav_btn" style="margin-top: 20px;" class="toggle-left-nav-btn inline-block mr-20 pull-left" href="javascript:void(0);"><i class="fa fa-bars"></i></a>
      <a href="<?php echo site_url('home'); ?>"><img class="brand-img pull-left" height="50" src="<?php echo base_url('assets/images/menu/logo/1.png'); ?>" alt="brand"/></a>
    </nav>
    <!-- /Top Menu Items -->

    <!-- Left Sidebar Menu -->
    <div class="fixed-sidebar-left">
      <ul class="nav navbar-nav side-nav nicescroll-bar">
        <li>
          <a href="<?php echo site_url('home'); ?>">
            <i class="icon-grid mr-10"></i>Back To Shop
          </a>
        </li>
        <li>
          <a class="" href="<?php echo site_url('admin/dashboard'); ?>">
            <i class="icon-picture mr-10"></i>Dashboard
          </a>
        </li>

        <li>
          <a href="javascript:void(0);" data-toggle="collapse" data-target="#ecom_dr">
            <i class="icon-basket-loaded mr-10"></i>Food Details
            <span class="pull-right">
              <i class="fa fa-fw fa-angle-down"></i>
            </span>
          </a>
          <ul id="ecom_dr" class="collapse collapse-level-1">
            <li>
              <a href="<?php echo site_url('admin/view_food'); ?>">View Foods</a>
            </li>
            <li>
              <a href="<?php echo site_url('admin/jollof_n_laugh'); ?>">Jollof N Laugh</a>
            </li>
            <li>
              <a href="<?php echo site_url('admin/add_food'); ?>">Add Foods</a>
            </li>
          </ul>
        </li>
        
        <li>
          <a href="javascript:void(0);" data-toggle="collapse" data-target="#vouc_dr">
            <i class="icon-grid mr-10"></i>Meal Vouchers
            <span class="pull-right">
              <i class="fa fa-fw fa-angle-down"></i>
            </span>
          </a>
          <ul id="vouc_dr" class="collapse collapse-level-1">
            <li>
              <a href="<?php echo site_url('admin/view_meal_voucher'); ?>">View</a>
            </li>
            <li>
              <a href="<?php echo site_url('admin/add_meal_voucher'); ?>">Add</a>
            </li>
          </ul>
        </li>
        
        <li>
          <a href="javascript:void(0);" data-toggle="collapse" data-target="#vouc_dr">
            <i class="icon-grid mr-10"></i>Vouchers
            <span class="pull-right">
              <i class="fa fa-fw fa-angle-down"></i>
            </span>
          </a>
          <ul id="vouc_dr" class="collapse collapse-level-1">
            <li>
              <a href="<?php echo site_url('admin/view_voucher'); ?>">View</a>
            </li>
            <li>
              <a href="<?php echo site_url('admin/add_voucher'); ?>">Add</a>
            </li>
          </ul>
        </li>
        
        <li>
          <a href="javascript:void(0);" data-toggle="collapse" data-target="#comp_dr">
            <i class="icon-grid mr-10"></i>Company
            <span class="pull-right">
              <i class="fa fa-fw fa-angle-down"></i>
            </span>
          </a>
          <ul id="comp_dr" class="collapse collapse-level-1">
            <li>
              <a href="<?php echo site_url('admin/view_company_address'); ?>">Delivery Address</a>
            </li>
            <li>
              <a href="<?php echo site_url('admin/view_company'); ?>">View</a>
            </li>
            <li>
              <a href="<?php echo site_url('admin/add_company'); ?>">Add</a>
            </li>
          </ul>
        </li>

        <li>
          <a href="javascript:void(0);" data-toggle="collapse" data-target="#edit_dr">
            <i class="icon-grid mr-10"></i>Edit Website
            <span class="pull-right">
              <i class="fa fa-fw fa-angle-down"></i>
            </span>
          </a>
          <ul id="edit_dr" class="collapse collapse-level-1">
            <li>
              <a href="<?php echo site_url('admin/menu'); ?>">Edit Menu</a>
            </li>
            <li>
              <a href="<?php echo site_url('admin/banner'); ?>">Edit Banners</a>
            </li>
            <li>
              <a href="<?php echo site_url('admin/slider'); ?>">Edit Sliders</a>
            </li>
            <li>
              <a href="<?php echo site_url('admin/side_meal'); ?>">Edit Side Meal</a>
            </li>
            <li>
              <a href="<?php echo site_url('admin/side_drink'); ?>">Edit Side Drink</a>
            </li>
            <li>
              <a href="<?php echo site_url('admin/seating'); ?>">Edit Seating</a>
            </li>
            <!--<li>
              <a href="<?php echo site_url('admin/edit/faq'); ?>">Edit FAQ</a>
            </li>
            <li>
              <a href="<?php echo site_url('admin/edit/policy'); ?>">Edit Returns Policy</a>
            </li>
            <li>
              <a href="<?php echo site_url('admin/edit/sorting'); ?>">Edit Sorting</a>
            </li>
            <li>
              <a href="<?php echo site_url('admin/edit/social'); ?>">Edit Social Links</a>
            </li>
            <li>
              <a href="<?php echo site_url('admin/edit/footer'); ?>">Edit Footer</a>
            </li>-->
          </ul>
        </li>
        
         <li>
          <a href="javascript:void(0);" data-toggle="collapse" data-target="#order_dr">
            <i class="icon-grid mr-10"></i>Orders
            <span class="pull-right">
              <i class="fa fa-fw fa-angle-down"></i>
            </span>
          </a>
          <ul id="order_dr" class="collapse collapse-level-1">
            <li>
              <a href="<?php echo site_url('admin/pending'); ?>">Pending Orders</a>
            </li>
            <li>
              <a href="<?php echo site_url('admin/delivering'); ?>">Delivering Orders</a>
            </li>
            <li>
              <a href="<?php echo site_url('admin/delivered'); ?>">Delivered Orders</a>
            </li>
            <li>
              <a href="<?php echo site_url('admin/cancelled'); ?>">Cancelled Orders</a>
            </li>
            <li>
              <a href="<?php echo site_url('admin/refunded'); ?>">Refunded Orders</a>
            </li>
          </ul>
        </li>
        
        <!--<li>
          <a href="<?php echo site_url('admin/message_grid'); ?>">
            <i class="icon-grid mr-10"></i>Message Grid
          </a>
        </li>-->

        <li>
          <a href="<?php echo site_url('admin/user_grid'); ?>">
            <i class="icon-grid mr-10"></i>User Grid
          </a>
        </li>
        
        <li>
          <a href="<?php echo site_url('admin/staff_grid'); ?>">
            <i class="icon-grid mr-10"></i>Staff Grid
          </a>
        </li>

        <li>
          <a href="<?php echo base_url('account/logout'); ?>">
            <i class="icon-layers mr-10"></i>Logout
          </a>
        </li>
      </ul>
    </div>
  <!-- /Left Sidebar Menu -->
