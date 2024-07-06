<?php include("admincp/config.php");

$ID = $_GET['id'];
$page = get_page($ID);

if( !$page ){ redirect(_root_."404"); }

$title = $page['title'] . " - " .$title;
$keywords = $page['keywords'];
$description = $page['description'];

if( !empty($page['img_banner']) ){ $img_banner = $__url_attimgs.$page['img_banner']; }

include("common/header.php"); ?>

	    <main class="main">
        
            <div class="page-header bg-dark" style="background-image:url(<?php _e( $img_banner ); ?>)">
                <h1 class="page-title"><?php _e($page['title']); ?></h1>
                <ul class="breadcrumb">
                	<li><a href="<?php _e(_root_); ?>"><i class="d-icon-home"></i></a></li>
                	<li class="active"><?php _e($page['title']); ?></li>
                </ul>
            </div>
                	
            <div class="page-content mt-5">
                <section class="about-section">
                    <div class="container-fluid">
                        <h2 class="title"><?php _e($page['title']); ?></h2>
                        <div class="row mb-5">
                            <div class="col-md-12 order-md-first">
                                <?php _e($page['text']); ?>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
                	
    </main>
	
	<?php include("common/footer.php"); ?>

</body>
</html>