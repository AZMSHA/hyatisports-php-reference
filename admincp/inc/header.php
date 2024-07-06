<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title><?php _e( $_TITLE ); ?></title>

	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">

	<link href="css/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="css/bootstrap_limitless.min.css" rel="stylesheet" type="text/css">
	<link href="css/layout.min.css" rel="stylesheet" type="text/css">
	<link href="css/components.min.css" rel="stylesheet" type="text/css">
	<link href="css/colors.min.css" rel="stylesheet" type="text/css">

	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.bundle.min.js"></script>
	<script src="js/blockui.min.js"></script>

	<script src="js/app.js"></script>

</head>

<body>

	<div class="page-content">

		<?php if( $_SIDEBAR ){ ?><div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">

			<div class="sidebar-content">

				<div class="sidebar-user">
					<div class="card-body">
						<div class="media">

							<div class="media-body">
								<div class="media-title font-weight-semibold"><?php _e( $Company ); ?></div>
								<div class="font-size-xs opacity-50">
									<i class="icon-sphere font-size-sm"></i> &nbsp;<?php _e( $domainname ); ?>
								</div>
							</div>

						</div>
					</div>
				</div>


				<div class="card card-sidebar-mobile">
					<ul class="nav nav-sidebar" data-nav-type="accordion">

						<li class="nav-item">
							<a href="index.php" class="nav-link">
								<i class="icon-home4"></i>
								<span>Dashboard</span>
							</a>
						</li>

						<?php if( _menu_category_product_ ){ ?><li class="nav-item">
							<a href="categories.php" class="nav-link">
								<i class="icon-list-unordered"></i>
								<span>Manage Categories / Products</span>
							</a>
						</li><?php } ?>

						<?php if( _menu_inquiry_ ){ ?><li class="nav-item">
							<a href="inquiry.php" class="nav-link">
								<i class="icon-question3"></i>
								<span>Manage Inquiries</span>
							</a>
						</li><?php } ?>

						<?php if( _menu_news_ ){ ?><li class="nav-item">
							<a href="news.php" class="nav-link">
								<i class="icon-calendar"></i>
								<span>Manage News & Updates</span>
							</a>
						</li><?php } ?>

						<?php if( _setting_gallery_ ){ ?><li class="nav-item">
							<a href="gallery.php" class="nav-link">
								<i class="icon-gallery"></i>
								<span>Manage Gallery</span>
							</a>
						</li><?php } ?>

						<?php if( _menu_search_ ){ ?><li class="nav-item">
							<a href="prdsearch.php" class="nav-link">
								<i class="icon-search4"></i>
								<span>Product Search</span>
							</a>
						</li><?php } ?>

						<?php if( _menu_variation_group_ ){ ?><li class="nav-item">
							<a href="variation_group.php" class="nav-link">
								<i class="icon-insert-template"></i>
								<span>Manage Variation Group</span>
							</a>
						</li><?php } ?>

						<?php if( _menu_variation_ ){ ?><li class="nav-item">
							<a href="variation.php" class="nav-link">
								<i class="icon-grid"></i>
								<span>Manage Variations</span>
							</a>
						</li><?php } ?>

						<?php if( _menu_banner_ ){ ?><li class="nav-item">
							<a href="banners.php" class="nav-link">
								<i class="icon-image2"></i>
								<span>Manage Banners</span>
							</a>
						</li><?php } ?>

						<?php if( _menu_widgets_ ){ ?><li class="nav-item">
							<a href="widget_areas.php" class="nav-link">
								<i class="icon-versions"></i>
								<span>Manage Widgets</span>
							</a>
						</li><?php } ?>

						<?php if( _menu_page_ ){ ?><li class="nav-item">
							<a href="pages.php" class="nav-link">
								<i class="icon-file-text"></i>
								<span>Manage Pages</span>
							</a>
						</li><?php } ?>

						<?php if( _menu_config_ ){ ?><li class="nav-item">
							<a href="configuration.php" class="nav-link">
								<i class="icon-wrench3"></i>
								<span>Website Configuration</span>
							</a>
						</li><?php } ?>
						
						<?php if( _menu_scripts_ ){ ?><li class="nav-item">
							<a href="scripts.php" class="nav-link">
								<i class="icon-gear"></i>
								<span>Scripts</span>
							</a>
						</li><?php } ?>

						<?php if( _menu_social_ ){ ?><li class="nav-item">
							<a href="social.php" class="nav-link">
								<i class="icon-facebook"></i>
								<span>Social Media</span>
							</a>
						</li><?php } ?>
						
						<?php if( $_TOPBAR ){ ?>
						<li class="nav-item">
							<a href="password.php" class="nav-link">
								<i class="icon-key"></i>
								<span>Change Password</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="login.php?logout" class="nav-link">
								<i class="icon-switch"></i>
								<span>logout</span>
							</a>
						</li>
						<?php } ?>

					</ul>
				</div>

			</div>
			
		</div><?php } ?>

		<div class="content-wrapper">