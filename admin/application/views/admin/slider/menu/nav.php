        <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <a href="<?php echo site_url('dashboard'); ?>" class="brand-logo">
                <img class="logo-abbr" src="<?php echo base_url('images/logo.png'); ?>" alt="">
                <img class="logo-compact" src="<?php echo base_url('images/logo-text.png'); ?>" alt="">
                <img class="brand-title" src="<?php echo base_url('images/logo-text.png'); ?>" alt="">
            </a>

            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->
        
        <!--**********************************
            Header start
        ***********************************-->
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                            <div class="dashboard_bar">
								Admin Dashboard
                            </div>
                        </div>
                        <ul class="navbar-nav header-right">
                            <li class="nav-item dropdown header-profile">
                                <a class="nav-link" href="javascript:void(0)" role="button" data-toggle="dropdown">
                                    <img src="<?php echo base_url('images/profile/17.jpg'); ?>" width="20" alt=""/>
									<!-- <div class="header-info">
										<span class="text-black"><strong>Brian Lee</strong></span>
										<p class="fs-12 mb-0">Admin</p>
									</div> -->
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="#" class="dropdown-item ai-icon">
                                        <svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                        <span class="ml-2">Profile </span>
                                    </a>
                                    <a href="#" class="dropdown-item ai-icon">
                                        <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                        <span class="ml-2">Logout </span>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->
        
        <!--**********************************
            Sidebar start
        ***********************************-->
        <div class="deznav">
            <div class="deznav-scroll">
				<ul class="metismenu" id="menu">
                    <li>
                        <a href="https://scottnnaghor.com/ticket_event/jollof_n_laugh/home" target="_blank" aria-expanded="false">
							<i class="flaticon-381-networking"></i>
							<span class="nav-text">Back to Website</span>
						</a>
                    </li>
                    <li>
                        <a href="<?php echo site_url('dashboard'); ?>" aria-expanded="false">
							<i class="flaticon-381-networking"></i>
							<span class="nav-text">Dashboard</span>
						</a>
                    </li>
                    <li>
                        <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="flaticon-381-networking"></i>
							<span class="nav-text">Jollof N Laugh</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="<?php echo site_url('jollof_n_laugh/add'); ?>">Add Event</a></li>
							<li><a href="<?php echo site_url('jollof_n_laugh/view'); ?>">View Event</a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="flaticon-381-networking"></i>
							<span class="nav-text">Venue</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="<?php echo site_url('venue/add'); ?>">Add Venue</a></li>
							<li><a href="<?php echo site_url('venue/view'); ?>">View Venue</a></li>
                        </ul>
                    </li>
                    <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="flaticon-381-television"></i>
							<span class="nav-text">Media</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Banners</a>
                                <ul aria-expanded="false">
                                    <li><a href="<?php echo site_url('banner/add'); ?>">Add Banners</a></li>
									<li><a href="<?php echo site_url('banner/view'); ?>">View Banners</a></li>
                                </ul>
                            </li>
                            <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Videos</a>
                                <ul aria-expanded="false">
                                    <li><a href="<?php echo site_url('videos/add'); ?>">Add Videos</a></li>
									<li><a href="<?php echo site_url('videos/view'); ?>">View Videos</a></li>
                                </ul>
                            </li>
							<li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Sliders</a>
                                <ul aria-expanded="false">
                                    <li><a href="<?php echo site_url('slider/add'); ?>">Add Sliders</a></li>
									<li><a href="<?php echo site_url('slider/view'); ?>">View Sliders</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="<?php echo site_url('account/logout'); ?>" aria-expanded="false">
							<i class="flaticon-381-networking"></i>
							<span class="nav-text">Logout</span>
						</a>
                    </li>
                </ul>
				<div class="copyright">
					<p><strong>Admin Ticket Event</strong> © <?php echo date('Y'); ?> All Rights Reserved</p>
				</div>
			</div>
        </div>
        <!--**********************************
            Sidebar end
        ***********************************-->