<!DOCTYPE html>
<html lang="zxx">

<head>
        <!-- meta tag -->
        <meta charset="utf-8">
        <title><?php _e($title);?></title>
        <meta name="keywords" content="<?php print $keywords;?>" />
        <meta name="description" content="<?php print $description;?>">
        <!-- responsive tag -->
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- favicon -->
        <link rel="apple-touch-icon" href="<?php _e($__url_attimgs.get_option('favicon')); ?>">
        <link rel="shortcut icon" type="image/x-icon" href="<?php _e($__url_attimgs.get_option('favicon')); ?>">
        <!-- bootstrap v4 -->
        <link rel="stylesheet" type="text/css" href="<?php _e(_root_); ?>assets/css/bootstrap.min.css">
        <!-- font-awesome css -->
        <link rel="stylesheet" type="text/css" href="<?php _e(_root_); ?>assets/css/font-awesome.min.css">
        <!-- animate css -->
        <link rel="stylesheet" type="text/css" href="<?php _e(_root_); ?>assets/css/animate.css">
        <!-- timetable css -->
        <link rel="stylesheet" type="text/css" href="<?php _e(_root_); ?>assets/css/timetable.css">
        <!-- owl.carousel css -->
        <link rel="stylesheet" type="text/css" href="<?php _e(_root_); ?>assets/css/owl.carousel.css">
        <!-- slick css -->
        <link rel="stylesheet" type="text/css" href="<?php _e(_root_); ?>assets/css/slick.css">
        <!-- slick-theme css -->
        <link rel="stylesheet" type="text/css" href="<?php _e(_root_); ?>assets/css/slick-theme.css">
        <!-- rsmenu CSS -->
        <link rel="stylesheet" type="text/css" href="<?php _e(_root_); ?>assets/css/rsmenu-main.css">
        <!-- rsmenu transitions CSS -->
        <link rel="stylesheet" type="text/css" href="<?php _e(_root_); ?>assets/css/rsmenu-transitions.css">
        <!-- magnific popup css -->
        <link rel="stylesheet" type="text/css" href="<?php _e(_root_); ?>assets/css/magnific-popup.css">
        <!-- style css -->
        <link rel="stylesheet" type="text/css" href="<?php _e(_root_); ?>assets/style.css">
        <!-- responsive css -->
        <link rel="stylesheet" type="text/css" href="<?php _e(_root_); ?>assets/css/responsive.css">
    </head>
    <body class="home1">
        <!--Preloader area start here
        <div class="preloader-area">
            <div class="box">
                <div class="loader8"></div>
            </div>
        </div>
        Preloader area End here-->

        <!--Full width header Start-->
		<div class="full-width-header">
			<!-- Toolbar Start -->
			<div class="rs-toolbar hidden-md">
				<div class="container">
					<div class="row">
						<div class="col-md-6">
							<div class="rs-toolbar-left">
								<div class="welcome-message">
									<i class="fa fa-home"></i><span>Welcome to <?php _e( get_option('cname') ); ?></span> 
								</div>
							</div>
						</div><!-- .rs-toolbar-left end-->
						<div class="col-md-6">
							<div class="rs-toolbar-right">
								<div class="toolbar-share-icon">
									<ul>
									    <?php $scl_fb = get_social('fb');
										if( $scl_fb ){ ?><li><a href="<?php _e( $scl_fb ); ?>"><i class="fa fa-facebook"></i></a></li><?php } ?>
										
										<?php $scl_tw = get_social('tw');
                                        if( $scl_tw ){ ?><li><a href="<?php _e( $scl_tw ); ?>"><i class="fa fa-twitter"></i></a></li><?php } ?>
                                        
										<?php $scl_gram = get_social('gram');
                                        if( $scl_gram ){ ?><li><a href="<?php _e( $scl_gram ); ?>"><i class="fa fa-instagram"></i></a></li><?php } ?>
                                        
										<?php $scl_in = get_social('in');
                                        if( $scl_in ){ ?><li><a href="<?php _e( $scl_in ); ?>"><i class="fa fa-linkedin-square"></i></a></li><?php } ?>
									</ul>
								</div>
							</div><!-- .rs-toolbar-right end-->
						</div>
					</div>
				</div>
			</div>
			<!-- Toolbar End -->
			
			<!--Header Start-->
			<header id="rs-header" class="rs-header rs-defult-header">
				<div class="menu-area menu-sticky">
					<div class="container">
						<div class="main-menu">
							<div class="row">
								<div class="col-lg-12">
                                    <!-- logo-area star-->
									<div class="logo-area">
										<a href="<?php _e(_root_); ?>"><img src="<?php _e($__url_attimgs.get_option('logo')); ?>" alt="<?php _e( get_option('cname') ); ?>"></a>
									</div><!-- .logo-area end-->
                                    
                                    <!-- mainmenu-area start -->
                                    <div class="mainmenu-area">
                                        <a class="rs-menu-toggle"><i class="fa fa-bars"></i>Menu</a>
                                        <nav class="rs-menu">
                                            <ul class="nav-menu">
                                                <!-- Home -->
                                                <li class="current-menu-item"> <a href="<?php _e(_root_); ?>" class="home">Home</a></li>
                                                <!-- End Home --> 

                                                <li> <a href="<?php _e(_root_); ?>about">About</a></li>
                                                
                                                <!-- Drop Down -->
                                                <li class="menu-item-has-children"> <a href="<?php get_prd_link(); ?>">Products</a>
                                                    <ul class="sub-menu">
                                                        <?php $menu_cat_query = mysql_query("SELECT * FROM `categories` WHERE `parent`=0 AND `show`='y' ORDER BY `rank` ASC");
					                                    if( mysql_num_rows($menu_cat_query) > 0 ){ ?>
					                                    <?php while( $RS_cat = mysql_fetch_array($menu_cat_query) ){ ?>
                                                        <li class="menu-item-has-children"> <a href="<?php _e( get_first_subcat_link($RS_cat['id']) ); ?>"<?php if( does_category_has_subs($RS_cat['id']) ){ _e( ' class="has_drop"'); } ?>><?php _e( $RS_cat['name'] ); ?></a>
                                                            <?php $menu_cat_sub_query = mysql_query("SELECT * FROM `categories` WHERE `parent`=".$RS_cat['id']." AND `show`='y' ORDER BY `rank` ASC");
										                    if( mysql_num_rows($menu_cat_sub_query) > 0 ){
										                    ?>
                                                            <ul class="sub-menu">
                                                                <?php while( $RS_cat_sub = mysql_fetch_array($menu_cat_sub_query) ){ ?>
										                            <li><a href="<?php _e( _root_."products/".$RS_cat['slug']."/".$RS_cat_sub['slug'] ); ?>"<?php if( does_category_has_subs($RS_cat_sub['id']) ){ _e( ' class="has_drop"'); } ?>><?php _e( $RS_cat_sub['name'] ); ?></a></li>
										                        <?php } ?>
                                                            </ul>
                                                            <?php } ?>
                                                        </li>
                                                        <?php } } ?>
                                                    </ul>
                                                </li>
                                                <!--End Icons -->
                                                <li> <a href="<?php _e(_root_); ?>basket">Inquiry</a> </li>
                                                <!--blog Menu End-->
                                                <li> <a href="<?php _e(_root_); ?>contacts">Contact</a> </li>
                                            </ul>
                                        </nav> 
                                        <div class="cart-area">
                                            <a class="rs-search" data-target=".search-modal" data-toggle="modal" href="#">
                                                <i class="fa fa-search"></i></a>
                                            <a href="<?php _e(_root_); ?>basket"><i class="fa fa-shopping-basket"></i><span><?php _e( items_in_basket() ); ?></span></a>
                                        </div>
                                    </div><!-- .mainmenu-area end -->
								</div>
							</div>
						</div>
					</div>
				</div>
			</header>
			<!--Header End-->
		</div>
        <!--Full width header End-->