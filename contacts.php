<?php include("admincp/config.php");

$page = get_page(2);

if( !$page ){ redirect(_root_."404"); }

$title = $page['title'] . " - " .$title;
$keywords = $page['keywords'];
$description = $page['description'];

if( !empty($page['img_banner']) ){ $img_banner = $__url_attimgs.$page['img_banner']; }

$loadtabs=false; include("common/header.php"); ?>

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
		
		<div class="contact-page-section sec-spacer">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="row contact-info get-in-touch">
							<div class="col-lg-3">
    							<h2 class="font-weight-bold text-uppercase ls-m mb-2">Contact us</h2>
                                <p>Looking for help? Fill the form and start a new adventure.</p>
                                
                                <h4 class="mb-1 text-uppercase">Phone</h4>
                                <p><?php _e( get_option('phone-number') ); ?></p>

                                <h4 class="mb-1 text-uppercase">Email</h4>
                                <p><a href="mailto:<?php _e( get_option('mail-email') ); ?>"><?php _e( get_option('mail-email') ); ?></a></p>
                                
                                <h4 class="mb-1 text-uppercase">Address</h4>
                                <p><?php _e( get_option('footer-address') ); ?></p>
							</div>
							<div class="col-lg-9">
								<div class="contact-form-area">
                                    <div id="form-messages"></div>
                                    <form id="contact-form" method="post" action="<?php _e(_root_); ?>common/process.php">
                                        <input type="hidden" name="_p" value="sendfb">
                                        <h3 class="ls-m mb-1">Let’s Connect</h3>
                                        <p class="text-grey">Your email addres will not be published. Required fields are marked *</p>
                                        <div class="row">                                      
                                            <div class="col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <input type="text" id="name" placeholder="Name*" name="fname"  class="form-control">
                                                </div>
                                            </div>                                      
                                            <div class="col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <input type="email" id="email" placeholder="Email*" name="email" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row"> 
                                            <div class="col-md-12 col-sm-12 col-xs-12">    
                                                <div class="form-group">
                                                    <textarea cols="40" rows="10" id="message" name="message" placeholder="Message" class="textarea form-control"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-xs-12">         
                                                <div class="form-group mb-0">
                                                    <input type="submit" id="submitbtn" class="primary-btn" value="Send Message">
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
    <br>
    <!-- Google Maps - Go to the bottom of the page to change settings and map location. -->
        <div class="mapouter"><div class="gmap_canvas"><iframe width="100%" height="286" id="gmap_canvas" src="https://maps.google.com/maps?q=silakot%20panjab&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe><a href="https://fmovies2.org">fmovies unblocked</a></div><style>.mapouter{position:relative;text-align:right;height:286px;width:100%;}.gmap_canvas {overflow:hidden;background:none!important;height:286px;width:100%;}</style></div>
    <!-- End Map Section -->

	<?php include("common/footer.php"); ?>

</body>
</html>