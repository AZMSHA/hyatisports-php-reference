<?php include("admincp/config.php");

if( isset($_GET['url']) and !empty($_GET['url']) ){
	
	$_URL = esc($_GET['url']);

	if( substr($_URL, -1) == "/" ){
		$_URL = substr($_URL, 0, -1);
	}

	if( empty( $_URL ) ){

		$_PAGE_TYPE = "cat";
		$_CAT_ID = 0;
		$_parent_category = 0;
		$pagetitle = "Products";
		$title = "Products - ".$title;
		$_PAGE_heading = "Our Categories";
		$_PAGE_banner = false;

	}else{

		$_A_URL = explode("/", $_URL);
		$_C_URL = count($_A_URL);

		if( $_C_URL > 0 ){
			
			$_parent_category = 0;

			$_S_URL = $_A_URL[$_C_URL-1];
			
			if( is_category( $_S_URL ) ){

				$category = get_category_by_slug( $_S_URL );
				$category_ID = $category['id'];
				$_parent_category = $category_ID;

				if( has_products( $category_ID ) ){
					
					$_PAGE_TYPE = "prd";
					$_CAT_ID = $category_ID;
					$pagetitle = $category['name'];
					$title = $category['name']." - ".$title;
					if( !empty($category['img_banner']) ){ $_PAGE_banner = $__url_attimgs.$category['img_banner']; }

				}else{

					$_PAGE_TYPE = "cat";
					$_CAT_ID = $category_ID;
					$pagetitle = $category['name'];
					$title = $category['name']." - ".$title;
					if( !empty($category['img_banner']) ){ $_PAGE_banner = $__url_attimgs.$category['img_banner']; }

				}

			}elseif( is_product( $_S_URL ) ){

				$_PAGE_TYPE = "single";
				$product = get_product_by_slug( $_S_URL );
				$product_ID = $product['id'];
				$pagetitle = $product['name'];
				$title = $product['name']." - ".$title;

				if( is_category( $_A_URL[$_C_URL-2] ) ){

					$category = get_category_by_slug( $_A_URL[$_C_URL-2] );
					$category_ID = $category['id'];
					$_CAT_ID = $category_ID;
					$_parent_category = $category_ID;
					if( !empty($category['img_banner']) ){ $_PAGE_banner = $__url_attimgs.$category['img_banner']; }

				}else{
					redirect( _root_.'404?e=prd-cat' );
				}

			}elseif( is_numeric( $_S_URL ) ){

				$current_page = $_S_URL;
				if( $current_page < 0 ){ $current_page = 1; }

				if( is_category( $_A_URL[$_C_URL-2] ) ){

					$category = get_category_by_slug( $_A_URL[$_C_URL-2] );
					$category_ID = $category['id'];
					$_parent_category = $category['parent'];

					if( does_category_has_subs( $category_ID ) ){
					
						$_PAGE_TYPE = "cat";
						$_CAT_ID = $category_ID;
						$pagetitle = $category['name'];
						$title = $category['name']." - ".$title;
						if( !empty($category['img_banner']) ){ $_PAGE_banner = $__url_attimgs.$category['img_banner']; }

					}elseif( has_products( $category_ID ) ){
						
						$_PAGE_TYPE = "prd";
						$_CAT_ID = $category_ID;
						$pagetitle = $category['name'];
						$title = $category['name']." - ".$title;
						if( !empty($category['img_banner']) ){ $_PAGE_banner = $__url_attimgs.$category['img_banner']; }

					}else{
						
						redirect( _root_.'404?e=prd-cat-empty' );						

					}

				}else{
					
					redirect( _root_.'404?e=pg-no' );

				}

			}else{

				redirect( _root_.'404?e=em' );

			}

		}else{

			$_PAGE_TYPE = "cat";
			$_CAT_ID = 0;
			$_parent_category = 0;
			$pagetitle = "Products";
			$title = "Products - ".$title;
			$_PAGE_heading = "Our Categories";
			$_PAGE_banner = false;

		}

	}

}else{

	$_PAGE_TYPE = "cat";
	$_CAT_ID = 0;
	$_parent_category = 0;
	$pagetitle = "Products";
	$title = "Products - ".$title;
	$_PAGE_heading = "Our Categories";
	$_PAGE_banner = false;

}

$loadslider=false; $loadticker=false; $loadfancybox=false; $_page="products";

if( $_PAGE_TYPE == "prd" ){ $loadprdjs=true; }

if( isset( $_PAGE_banner ) and !empty($_PAGE_banner) ){ $img_banner = $_PAGE_banner; }

include("common/header.php"); ?>

                    <div class="rs-breadcrumbs">
                        <img src="<?php _e(_root_); ?>assets/images/bg/about-bg.jpg" alt="">
                        <div class="breadcrumbs-inner">
            		        <div class="container">
            		            <div class="row">
            		                <div class="col-md-12 text-center">
            		                    <h1 class="page-title"><?php _e($page['title']); ?></h1>
            		                    <ul>
                            		        <li class="breadcrumb-item"><a href="<?php _e(_root_); ?>">Home</a></li>
                            				<?php if( $_PAGE_TYPE == "single" ){ ?>
                            				<li class="breadcrumb-item"><a href="<?php _e( _root_.'products/'.get_category($product['parent'], 'slug') ); ?>"><?php _e( get_category($product['parent'], 'title') ); ?></a></li>
                            				<?php } ?>
                            				<li class="breadcrumb-item active" aria-current="page"><?php _e($pagetitle); ?></li>
            		                    </ul>
            		                </div>
            		            </div>
            		        </div>
            		    </div>
            		</div>
		
	                <div id="rs-products" class="rs-products shop-page sec-spacer">
				        <div class="container">
					        <div class="row pagination-product">
				
    							<?php if( $_PAGE_TYPE != "single" ){ ?><?php include("common/sidebar.php"); ?><?php } ?>
                                    
    							<div class="<?php if( $_PAGE_TYPE !="single" ){ ?>col-lg-9<?php }else{ ?>col-lg-12<?php } ?>">
    								
    								<div class="page-container">
    
    									<div id="page-content-wrapper">
    
    										<?php if( $_PAGE_TYPE == "cat" ){ ?>

                    							<?php
                								$main_cat_query = mysql_query("SELECT * FROM `categories` WHERE `parent`=".$_parent_category." AND `show`='y' ORDER BY `rank`");
                								if( mysql_num_rows($main_cat_query) > 0 ){
                								?>
                									<div class="row">
                										<?php while( $RS_cat = mysql_fetch_array($main_cat_query) ){ category( $RS_cat['id'], "col-sm-6"); } ?>
                									</div>
                
                								<?php }else{ ?>
                									<div class="noprdfound">No Categories or Products Found!</div>
                								<?php } ?>
        
        										<?php }elseif( $_PAGE_TYPE == "prd" ){ ?>

                								<?php
                								$prd_main_query = mysql_query("SELECT * FROM `products` WHERE `parent`=".$category_ID." AND `show`='y' ORDER BY `rank`");
                								$total_page = ceil(mysql_num_rows($prd_main_query) / get_option('prd-page-n'));
                								if( !isset($current_page) or !is_numeric($current_page) ){ $current_page = 1; }
                								if( $current_page != 1 ){ $current_page = ( abs($current_page) < $total_page ) ? abs($current_page) : $total_page; }
                								$prod_limit_start = ($current_page - 1) * get_option('prd-page-n');
                								if( $prod_limit_start < 0 ){ $prod_limit_start = 0; }
                								$prd_query = mysql_query("SELECT * FROM `products` WHERE `parent`=".$category_ID." AND `show`='y' ORDER BY `rank` ASC LIMIT ".$prod_limit_start.", ".get_option('prd-page-n'));
                								?>
                
                								<div class="row">
    												<?php while( $RS_PRD = mysql_fetch_array($prd_query) ){ prd($RS_PRD, 'col-lg-4 col-md-6'); } ?>
    											</div>
    											
                        						<div class="pagination__controls">
                        						    <p class="show-info">Showing <span><?php _e( ($prod_limit_start+1) ); ?> - <?php _e( $prod_limit_start+get_option('prd-page-n') ); ?> of <?php _e( mysql_num_rows($prd_main_query) ); ?></span> Products</p>
                    								<?php if( $total_page > 1 ){ ?>
                        								<ul class="pagination" style="float: right;">
        											        <?php for( $i=1; $i<=$total_page; $i++ ){ ?>
                                        					<?php if( $current_page == $i ){ ?>
        											        <li class="active"><a><?php _e( appendzero($i) ); ?></a></li>
        											        <?php }else{ ?>
        											        <li><a href="<?php _e( get_category_sub_link($category_ID).'/'.$i ); ?>"><?php _e( appendzero($i) ); ?></a></li>
        											        <?php } ?>
                                        					<?php } ?>
        											    </ul>
                    								<?php } ?>
                    							</div><br>

                							    <?php }elseif( $_PAGE_TYPE == "single" ){ ?>
                							    
                                                <!-- Shop Single Page Start Here -->
                                                <div class="shop-single-page-area">
                                                    <div class="container">
                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12">
                                                                <div class="single-product-area left-area">
                                                                    <div class="row">
                                                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                                                            <div class="inner-single-product-slider">
                                                                                <div class="inner">
                                                                                    <div class="slider single-product">
                                                                                        <div>
                                                                                             <div class="images-single"><a class="image-popup" href="<?php _e( $__url_sm_imgs.$product['img_small'] ); ?>"><img src="<?php _e( $__url_sm_imgs.$product['img_small'] ); ?>" alt="<?php _e( $product['name'] ); ?>"></a></div>
                                                                                        </div>
                                                                                        <?php
                                                                                    	$single_prd_gallery_query = mysql_query("SELECT * FROM `gallery` WHERE `product`=".$product['id']." ORDER BY `rank` ASC");
                                                                                    	if( mysql_num_rows($single_prd_gallery_query) ){
                                                                                    		while( $RS_gallery = mysql_fetch_array($single_prd_gallery_query) ){
                                                                                    	?>
                                                                                        <div>
                                                                                             <div class="images-single"><a class="image-popup" href="<?php _e( $__url_gallery.$RS_gallery['image'] ); ?>"><img src="<?php _e( $__url_gallery.$RS_gallery['image'] ); ?>" alt="<?php _e( $RS_gallery['name'] ); ?>"></a></div>
                                                                                        </div>
                                                                                        <?php } } ?>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="slider single-product-nav">
                                                                                    <div class="images-slide-single"> 
                                                                                        <a class="image-popup" href="<?php _e( $__url_sm_imgs.$product['img_small'] ); ?>"><img src="<?php _e( $__url_sm_imgs.$product['img_small'] ); ?>" alt="<?php _e( $product['name'] ); ?>"></a>
                                                                                    </div>
                                                                                    <?php
                                                                                    $single_prd_gallery_query = mysql_query("SELECT * FROM `gallery` WHERE `product`=".$product['id']." ORDER BY `rank` ASC");
                                                                                    if( mysql_num_rows($single_prd_gallery_query) ){
                                                                                    	while( $RS_gallery = mysql_fetch_array($single_prd_gallery_query) ){
                                                                                    ?>
                                                                                    <div class="images-slide-single"> 
                                                                                        <a class="image-popup" href="<?php _e( $__url_gallery.$RS_gallery['image'] ); ?>"><img src="<?php _e( $__url_gallery.$RS_gallery['image'] ); ?>" alt="<?php _e( $RS_gallery['name'] ); ?>"></a>
                                                                                    </div>
                                                                                    <?php } } ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                                                            <form class="" action="<?php _e(_root_); ?>common/process.php" method="post" onSubmit="return validateForm(['suppinq_name', 'suppinq_email', 'suppinq_subject', 'suppinq_msg'], '!');" name="frmsupp">
                                        
                                                                        		<input type="hidden" name="_p" value="addinq">
                                                                        		<input type="hidden" name="_pid" value="<?php _e($product["id"]); ?>">
                                                                        		
                                                                                <h4 class="uppercase"><?php _e($product['name']); ?></h4>
                                                                                <span class="price">Art No: <?php _e($product['art_no']); ?></span>
                                                                                <p><?php _e($product['description']); ?></p>
                                                                            
                                                                                <?php $query_variation = mysql_query("SELECT * FROM `variations` WHERE `group_id`=1 ORDER BY `rank` ASC");
                                    											if( mysql_num_rows($query_variation) > 0 ){
                                    												$sub_parms = array();
                                    												
                                    												while( $RS_variation = mysql_fetch_array($query_variation) ){
                                    													if( prd_has_variation($product['id'], $RS_variation['id']) ){
                                    													    $att_data = ($RS_variation['description'] == 'color') ? print_product_check_attributes($RS_variation['id'], $product['id'], true) : print_product_check_attributes($RS_variation['id'], $product['id']);
                                    														array_push($sub_parms, array( get_variation($RS_variation['id'], "name"), $att_data ) );
                                    													}
                                    												}
                                    												
                                    												if( count($sub_parms) > 0 ){
                                    											?>
                                    											<div class="">
                                    												<?php foreach ($sub_parms as $parm){ ?>
                                    												<div class="variation-wrapper">
                                    													<div class="variation-lbl"><?php _e( $parm[0] ); ?></div>
                                    													<div class="variation-att"><?php _e( $parm[1] ); ?></div>
                                    												</div>
                                    												<?php } ?>
                                    											</div>
                                    											<?php } } ?>
                                											
                                                                        		<div class="inq-but-wrapper">
                                    											    <input type="number" min="1" max="1000" name="qty" value="50">
                                    												<input type="submit" value="Add to Inquiry">
                                    											</div>
                                                    					    </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Shop Single Page End Here --> 
                                                <br>
                                                <?php
                                            	$rel_prd_query = mysql_query("SELECT * FROM `products` WHERE `parent`=".$product['parent']." AND `id`!=".$product['id']." AND `show`='y' ORDER BY rand() LIMIT 0, 4");
                                            	if( mysql_num_rows($rel_prd_query) > 0 ){ ?>
                                                <div id="rs-products" class="rs-products">
                                        			<div class="container">
                                        				<div class="row">
                                                            <div class="col-md-12">
                                                                <div class="section-title text-center sec-arrow-dark">
                                                                    <h4>Choose Your One</h4>
                                                        		    <h2>Related Products</h2>      
                                                        			
                                                            	</div>
                                                            </div>
                                                        </div>
                                        				<div class="rs-carousel owl-carousel" data-loop="true" data-items="3" data-margin="30" data-autoplay="true" data-autoplay-timeout="6000" data-smart-speed="2000" data-dots="false" data-nav="true" data-nav-speed="false" data-mobile-device="1" data-mobile-device-nav="true" data-mobile-device-dots="false" data-ipad-device="2" data-ipad-device-nav="true" data-ipad-device-dots="false" data-ipad-device2="2" data-ipad-device-nav2="true" data-ipad-device-dots2="false" data-md-device="3" data-md-device-nav="true" data-md-device-dots="false">
                                        					
                                        					<?php while( $RS_PRD = mysql_fetch_array($rel_prd_query) ){ prd($RS_PRD, 'row'); } ?>
                                        
                                        				</div>
                                        			</div>
                                        		</div>
                                        		<?php } ?>

    										<?php } ?>
    									</div>
    								</div>
    							</div>
    		            	</div>
    		            </div>
    	            </div>

    <?php include("common/footer.php"); ?>

</body>
</html>
