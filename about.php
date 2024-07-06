<?php include("admincp/config.php");

$page = get_page(1);

if( !$page ){ redirect(_root_."404"); }

$title = $page['title'] . " - " .$title;
$keywords = $page['keywords'];
$description = $page['description'];

if( !empty($page['img_banner']) ){ $img_banner = $__url_attimgs.$page['img_banner']; }

include("common/header.php"); ?>

        <div class="rs-breadcrumbs">
            <img src="<?php _e(_root_); ?>assets/images/bg/about-bg.jpg" alt="">
            <div class="breadcrumbs-inner">
		        <div class="container">
		            <div class="row">
		                <div class="col-md-12 text-center">
		                    <h1 class="page-title"><?php _e($page['title']); ?></h1>
		                    <ul>
		                        <li>
		                            <a class="active" href="<?php _e(_root_); ?>">Home</a>
		                        </li>
		                        <li><?php _e($page['title']); ?></li>
		                    </ul>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
		
		<div id="rs-about" class="rs-about-inner section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="about-details mmt-40">
                            <h2>About The <span class="primary-color"><?php _e( get_option('cname') ); ?></span></h2>
                            <p><?php _e($page['text']); ?></p> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
	
	<?php include("common/footer.php"); ?>

</body>
</html>