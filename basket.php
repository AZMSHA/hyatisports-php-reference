<?php include("admincp/config.php");

$page = get_page(16);

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
        
        <div class="shipping-cart-area">
            <div class="container">
                <div class="tab-content">
                    <div class="tab-pane active" id="checkout">
                        <div class="row">
                            <?php
							if( items_in_basket() > 0 ) {
							    $basket_query = mysql_query("SELECT * FROM `inquiry_products` WHERE `inquiry_id`=".inquiry_sec_to_id());
							    if( mysql_num_rows($basket_query) > 0 ) {
							?>
                            <div class="col-lg-12 col-md-12">
                                <div class="product-list">
                                    <div class="title-shop">Submit Inquiry</div>
                                    <?php while( $RS = mysql_fetch_array($basket_query) ){ $product = get_product($RS['product_id']); ?>
                                    <div class="row" style="margin-bottom: 10px;border-bottom: 1px solid #bfbfbf;">
                                        <div class="col-sm-3">
                                            <div class=" product-count">
                                                <div class="title">Products</div>
                                                <div class="product-image">
                                                    <img src="<?php _e($__url_sm_imgs.$product['img_small']); ?>" width="100" height="100" alt="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class=" product-count">
                                                <div class="title">Detail</div>
                                                <a>
												    Art No: <?php _e($product['art_no']); ?><br />
										            Name: <?php _e($product['name']); ?><br />
										            <?php _e( print_product_inquiry(inquiry_sec_to_id(), $RS['product_id']) ); ?>
												</a>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class=" product-count">
                                                <div class="title">Quantity</div>
                                                <div class="order-pro order1">
                                                    <form method="post" action="<?php _e(_root_); ?>common/process.php">
    												    <input name="_p" value="updinq" type="hidden">
    												    <input name="_pid" value="<?php _e($RS['product_id']); ?>" type="hidden">
                                                        <input type="number" name="qty" step="1" min="1" value="<?php _e($RS['qty']); ?>">
                                                        <input value="Update" class="btn btn-primary btn-md ml-2" type="submit">
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="product-price text-right" style="margin-top: 35px;">
                                                <form method="post" action="<?php _e(_root_); ?>common/process.php" onsubmit="javascript:if(confirm('Are you sure you want to delete')) return true; else return false">
    											    <input name="_p" value="delinq" type="hidden">
    											    <input name="_pid" value="<?php _e($RS['product_id']); ?>" type="hidden">
    											    <input value="Delete" class="btn btn-primary btn-md ml-2" type="submit">
										        </form>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div><!-- .product-list end -->
                            </div>
                            
                            <div class="cart-actions mb-6 pt-6">
								<div class="coupon">
								    <form name="FeBaForm" method="get" action="<?php _e(_root_); ?>inquiry">
									    <input value="Submit Inquiry" class="btn btn-primary btn-md ml-2" type="submit">
									</form>
								</div>
							</div>
							<?php _c(); ?>
								
							<?php } }else{ ?>
								<div class="noprdfound">Inquiry Basket is empty!</div>
							<?php } ?>
							
							
                        </div>
                    </div>                                 
                </div>
            </div>
        </div>

	<?php include("common/footer.php"); ?>

</body>
</html>