<?php include("admincp/config.php");

$page = get_page(18);

if( isset($_GET['q']) and !empty($_GET['q']) ){	
	$Q = $_GET['q']; $searchkeywords = explode(" ", $Q);
}else{
	$Q = ""; $searchkeywords = explode(" ", $Q);
}

$pagetitle = "Search Results For '".$Q."'";
$title = "Search Results For '".$Q."' - ".$title;
$keywords = $Q.",".$keywords;
$m_link = _root_.'search?q='.$Q.'&p=';

if( !empty($page['img_banner']) ){ $img_banner = $__url_attimgs.$page['img_banner']; }

$loadslider=false; $loadticker=false; $loadfancybox=false; include("common/header.php"); ?>

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
        
        <div id="rs-products" class="rs-products shop-page sec-spacer">
            <div class="container">
                <div class="row pagination-product">
                    <div class="col-lg-12">
                        <div class="page-container">
                            <div id="page-content-wrapper">
    									    
    				            <div class="row">
                                    <?php
                					$search_query = '';
                					for( $i=0; $i<count($searchkeywords); $i++ ){
                						$search_query .= "SELECT * FROM `products` WHERE `art_no` LIKE '%".$searchkeywords[$i]."%'"." OR `name` LIKE '%".$searchkeywords[$i]."%'"." OR `description` LIKE '%".$searchkeywords[$i]."%'"." OR `mini_description` LIKE '%".$searchkeywords[$i]."%' AND `show`='y'";
                							if( $i < ( count($searchkeywords)-1 ) ){ $search_query .= " UNION ALL "; }
                					}
                					$total_page = ceil(mysql_num_rows(mysql_query($search_query)) / get_option('prd-search-n'));
                					$current_page = ( isset($_GET['p']) and is_numeric($_GET['p']) ) ? ( abs($_GET['p']) < $total_page ) ? abs($_GET['p']) : $total_page : 1;
                					$limit_start = ($current_page - 1) * get_option('prd-search-n');
                					$current_query = mysql_query($search_query." ORDER BY `art_no` ASC LIMIT ".$limit_start.", ".get_option('prd-search-n'));
                					?>
                					<?php if( mysql_num_rows($current_query) > 0 ){ ?>
                						<div class="row">
                						    <?php while( $RS_PRD = mysql_fetch_array($current_query) ){ prd($RS_PRD, 'col-xl-5col col-lg-3 col-sm-4 col-6'); } ?>
                						</div>
                					<?php }else{ ?>
                						<div class="noprdfound">No products found!</div>
                					<?php } ?>
                
                					<?php if( $total_page > 1 ){ ?>
                						<div class="col-lg-12">
                							<ul class="pagination">
                								<?php for( $i=1; $i<=$total_page; $i++ ){ ?>
                									<?php if( $current_page == $i ){ ?>
                										<li><strong class="current"><?php _e( appendzero($i) ); ?></strong></li>
                									<?php }else{ ?>
                										<li><a href="<?php _e( $m_link.$i ); ?>"><?php _e( appendzero($i) ); ?></a></li>
                									<?php } ?>
                								<?php } ?>
                							</ul>
                						</div>
                					<?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        

	<?php include("common/footer.php"); ?>

</body>
</html>