<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Jollof N Laugh || Admin Dashboard</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
	<link href="<?php echo base_url('vendor/bootstrap-select/dist/css/bootstrap-select.min.css'); ?>" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('vendor/star-rating/star-rating-svg.css'); ?>">
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
            <div class="container-fluid">

                <div class="page-titles">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="javascript:void(0)">Jollof N Laugh</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)">All Jollof N Laugh</a></li>
					</ol>
                </div>
                <div class="row">
                    <?php if(!empty($events)){ foreach($events as $eve){ ?>
                    <div class="col-lg-12 col-xl-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="row m-b-30">
                                    <div class="col-md-5 col-xxl-12">
                                        <div class="new-arrival-product mb-4 mb-xxl-4 mb-md-0">
                                            <div class="new-arrivals-img-contnent">
                                                <img class="img-fluid" src="https://scottnnaghor.com/jollof_n_laugh/uploads/events/<?php echo $eve->image; ?>" 
                                                alt="<?php echo $eve->title; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-7 col-xxl-12">
                                        <div class="new-arrival-content position-relative">
                                            <h4><a href="<?php echo site_url('jollof_n_laugh/edit/'.$eve->id); ?>"><?php echo str_replace('-', ' ', $eve->title); ?></a></h4>
                                            <h4>Age: <a href="#"><?php echo $eve->req_age; ?></a></h4>
                                            <h4>Dress code: <a href="#"><?php echo $eve->req_dress_code; ?></a></h4>
                                            <h4>Last entry: <a href="#"><?php echo $eve->req_last_entry; ?></a></h4>
                                            <h4>ID requirements: <a href="#"><?php echo $eve->req_id_verified; ?></a></h4>
                                            <a style="color: #ff0000;" href="<?php echo site_url('jollof_n_laugh/edit/'.$eve->id); ?>">Edit</a>
                                            <p class="text-content"><?php echo character_limiter($eve->description, 100); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
					</div>
					<?php } } ?>
					
					<!-- review -->
					
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
    <script src="<?php echo base_url('js/custom.min.js'); ?>"></script>
	<script src="<?php echo base_url('js/deznav-init.js'); ?>"></script>
	

    <script src="<?php echo base_url('vendor/highlightjs/highlight.pack.min.js'); ?>"></script>
    <!-- Circle progress -->
	
	<!-- Rating -->
	<script src="<?php echo base_url('vendor/star-rating/jquery.star-rating-svg.js'); ?>"></script>

</body>

</html>