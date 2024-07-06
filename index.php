<?php include("admincp/config.php");
$loadslider=true; $loadfanybox=false; $loadowl=true; $_page="home";
include("common/header.php"); ?>

        <?php
        $banner_query = mysql_query("SELECT * FROM `banner_images` WHERE `banner`=1 ORDER BY `rank` ASC");
        if( mysql_num_rows($banner_query) > 0 ){
            $loadowl = true;
        ?>
        <div id="rs-slider" class="rs-slider rs-slider1">     
        	<div id="home-slider">
				<?php $j=1; while( $banner = mysql_fetch_array($banner_query) ){ ?>
				<div class="item <?php if( $j == 1 ){ ?>active<?php } ?>">
					<img src="<?php _e( $__url_banner.$banner['image'] ); ?>" alt="<?php _e( $banner['name'] ); ?>" />
					<div class="slide-content">
						<div class="display-table">
							<div class="display-table-cell">
								<div class="container">
									<?php _e( $banner['text'] ); ?>
									<a href="<?php _e( $banner['link'] ); ?>" class="transfarent-btn mr-30" data-animation-in="lightSpeedIn" data-animation-out="animate-out">View More</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php $j++; } ?>
        	</div>         
        </div>
        <?php } ?>
        
        <?php $menu_cat_query = mysql_query("SELECT * FROM `categories` WHERE `img_thumb`!='' AND `parent`=0 AND `show`='y' ORDER BY `rank` ASC");
        if( mysql_num_rows($menu_cat_query) > 0 ){ ?>
        <div class="rs-what-wedo section-padding">
            <div class="container">
                <div class="section-title text-center sec-arrow-dark">
                    <h4>OUR <?php _e( get_option('cname') ); ?></h4>
                    <h2> Categories</h2>      
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="rs-carousel owl-carousel" data-loop="true" data-items="3" data-margin="30" data-autoplay="true" data-autoplay-timeout="5000" data-smart-speed="2000" data-dots="true" data-nav="false" data-nav-speed="false" data-mobile-device="1" data-mobile-device-nav="true" data-mobile-device-dots="true" data-ipad-device="2" data-ipad-device-nav="false" data-ipad-device-dots="false" data-ipad-device2="2" data-ipad-device-nav2="false" data-ipad-device-dots2="true" data-md-device="3" data-md-device-nav="false" data-md-device-dots="true">
                            <?php while( $RS_cat = mysql_fetch_array($menu_cat_query) ){ 
                            $link = ( $RS_cat['parent']==0 ) ? category_link_first_sub($RS_cat['id']) : _root_.'products/'.$RS_cat['slug']; ?>
                            <div class="single-postion">
                                <img src="<?php _e( $__url_attimgs.$RS_cat['img_thumb'] ); ?>" alt="">
                                <div class="position-details text-center">
                                    <h4 class="htitle"><?php _e( $RS_cat['name'] ); ?></h4>
                                    <div class="hover-text">
                                        <a href="#"><h4><?php _e( $RS_cat['name'] ); ?></h4></a>
                                        <div class="link">
                                            <a href="<?php _e( $link ); ?>">View Products </a><i class="fa fa-long-arrow-right"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>    
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <!-- About Us Start -->
        <div id="rs-about" class="rs-about section-padding about-bg">
            <div class="container">
                <div class="section-title text-center sec-arrow-dark">
                    <h4>About Us</h4>
                    <h2>Welcome to <?php _e( get_option('cname') ); ?></h2>  
                </div><!-- .section-title end-->
                <div class="row">
                    <div class="col-lg-6">
                        <div class="about-details">
                            <p><?php _e( get_option('about-profile') ); ?></p>
                            <div class="ceo-founder">
                                <div class="author-info">
                                     <div class="signature-img">
                                        <img src="<?php _e($__url_attimgs.get_option('logo')); ?>" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="about-left mmt-40">
                            <img src="<?php _e(_root_); ?>assets/images/about.png" alt="" style="margin-left: -120px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- About Us End -->
        
        <?php
        $feat_prd_query = mysql_query("SELECT * FROM `products` WHERE `img_small`!='' AND `grp_fet`='y' AND `show`='y' ORDER BY rand() LIMIT 0, 8");
        if( mysql_num_rows($feat_prd_query) > 0 ){
        ?>
        <div id="rs-products" class="rs-products section-padding">
			<div class="container">
				<div class="row">
                    <div class="col-md-12">
                        <div class="section-title text-center sec-arrow-dark">
                            <h4>Choose Your One</h4>
                		    <h2>Our Featured Products</h2>      
                			
                    	</div>
                    </div>
                </div>
				<div class="rs-carousel owl-carousel" data-loop="true" data-items="3" data-margin="30" data-autoplay="true" data-autoplay-timeout="6000" data-smart-speed="2000" data-dots="false" data-nav="true" data-nav-speed="false" data-mobile-device="1" data-mobile-device-nav="true" data-mobile-device-dots="false" data-ipad-device="2" data-ipad-device-nav="true" data-ipad-device-dots="false" data-ipad-device2="2" data-ipad-device-nav2="true" data-ipad-device-dots2="false" data-md-device="3" data-md-device-nav="true" data-md-device-dots="false">
					<?php while( $PRD = mysql_fetch_array($feat_prd_query) ){ prd($PRD, 'row'); } ?>
				</div>
			</div>
		</div>
		<?php } ?>
		
		<?php
        $new_prd_query = mysql_query("SELECT * FROM `products` WHERE `img_small`!='' AND `grp_new`='y' AND `show`='y' ORDER BY rand() LIMIT 0, 10");
        if( mysql_num_rows($new_prd_query) > 0 ){
        ?>
        <div id="rs-products" class="rs-products section-padding dark-bg">
			<div class="container">
				<div class="row">
                    <div class="col-md-12">
                        <div class="section-title text-center sec-arrow-dark">
                            <h4>Latest Update</h4>
                		    <h2>New Arrivals Products</h2>      
                			
                    	</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="about-left mmt-40">
                            <img src="<?php _e(_root_); ?>assets/images/new-prd.png" alt="">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="rs-carousel owl-carousel" data-loop="true" data-items="2" data-margin="30" data-autoplay="true" data-autoplay-timeout="6000" data-smart-speed="2000" data-dots="false" data-nav="true" data-nav-speed="false" data-mobile-device="1" data-mobile-device-nav="true" data-mobile-device-dots="false" data-ipad-device="1" data-ipad-device-nav="true" data-ipad-device-dots="false" data-ipad-device2="1" data-ipad-device-nav2="true" data-ipad-device-dots2="false" data-md-device="2" data-md-device-nav="true" data-md-device-dots="false">
					        <?php while( $PRD = mysql_fetch_array($new_prd_query) ){ prd($PRD, 'row'); } ?>
				        </div>
                    </div>
                </div>
			</div>
		</div>
		<?php } ?>
		
		<?php
        $text_info_query = mysql_query("SELECT * FROM `widgets` WHERE `area_id`=1 ORDER BY `rank` ASC LIMIT 6");
        if( mysql_num_rows( $text_info_query ) ){
        ?>
		<div id="rs-gallery-sction" class="rs-gallery-sction section-padding bg2">
			<div class="">
				<div class="section-title white-text text-center sec-arrow-dark">
                    <h4>Instagram Gallery</h4>
                    <h2><?php $scl_gram = get_social('gram');
                        if( $scl_gram ){ ?><a href="<?php _e( $scl_gram ); ?>">@ Follow Us </a><?php } ?>On Instagram</h2>      
                </div>
				<div class="row">
				    <?php while( $RS_text_info = mysql_fetch_array($text_info_query) ){ ?>
			        <div class="col-lg-2 col-md-6">
                         <div class="gallery-item popup-inner">
                            <div class="blog-img popup-box">
                                <img src="<?php _e( $__url_attimgs.$RS_text_info['field_2'] ); ?>" alt="">
                                <div class="popup-arrow">
                                    <a class="image-popup" href="<?php _e( $__url_attimgs.$RS_text_info['field_2'] ); ?>">
                                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                    </a>
                                 </div>
                            </div>
                         </div>
                    </div>
			        <?php } ?>
                </div>
            </div>
        </div>
        <?php } ?>

	<?php include("common/footer.php"); ?>

</body>
</html>