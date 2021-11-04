<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <?php foreach($venue as $ven){} ?>
    <title>Edit <?php echo $ven->title; ?> Venue || Admin Dashboard</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <!-- Custom Stylesheet -->
	<link href="<?php echo base_url('vendor/bootstrap-select/dist/css/bootstrap-select.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('css/style.css" rel="stylesheet'); ?>">
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
						<li class="breadcrumb-item"><a href="javascript:void(0)">Venue</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)">Edit <?php echo $ven->title; ?></a></li>
					</ol>
                </div>
                <!-- row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Edit Venue</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-validation">
                                    <form class="form-valide" action="<?php echo base_url('venue/edit/'.$ven->id); ?>" method="POST" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="val-title">Title</label>
                                                    <div class="col-lg-6">
                                                        <input type="text" class="form-control" name="title" placeholder="Enter a Title" value="<?php echo str_replace('-', ' ', $ven->title); ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="val-video">Description</label>
                                                    <div class="col-lg-6">
                                                        <textarea id="summernote" class="form-control" name="body"><?php echo $ven->body; ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="val-video">Maps</label>
                                                    <div class="col-lg-6">
                                                        <textarea col="7" rows="7" class="form-control" name="maps"><?php echo $ven->maps; ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-lg-8 ml-auto">
                                                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Edit Venue First Image</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-validation">
                                    <form class="form-valide" action="<?php echo base_url('venue/edit_image1/'.$ven->id); ?>" method="POST" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                   <label class="col-lg-4 col-form-label" for="val-confirm-password">Upload Image 
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-6">
                                                        <div class="custom-file">
                                                            <input type="file" name="fileToUpload[]" class="custom-file-input">
                                                            <label class="custom-file-label">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-lg-8 ml-auto">
                                                        <button type="submit" name="submit_image" class="btn btn-primary">Submit</button>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Edit Venue Second Image</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-validation">
                                    <form class="form-valide" action="<?php echo base_url('venue/edit_image2/'.$ven->id); ?>" method="POST" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                   <label class="col-lg-4 col-form-label" for="val-confirm-password">Upload Image 
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-6">
                                                        <div class="custom-file">
                                                            <input type="file" name="fileUpload[]" class="custom-file-input">
                                                            <label class="custom-file-label">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-lg-8 ml-auto">
                                                        <button type="submit" name="submit_image2" class="btn btn-primary">Submit</button>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
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

    <!-- Jquery Validation -->
    <script src="<?php echo base_url('vendor/jquery-validation/jquery.validate.min.js'); ?>"></script>
    <!-- Form validate init -->
    <script src="<?php echo base_url('js/plugins-init/jquery.validate-init.js'); ?>"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    
    <script>
    $(document).ready(function() {
        $('#summernote').summernote({
            tabsize: 2,
            height: 200
        });
    });
    </script>

</body>

</html>