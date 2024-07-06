<!-- Footer Start -->
        <footer id="rs-footer" class="rs-footer">
			<!-- Footer Top -->
            <div class="footer-top-section">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8">
                             <ul class="sitemap-widget">
                                <li><a href="<?php _e(_root_); ?>">Home</a></li>
                                <li><a href="<?php _e(_root_); ?>about">About</a></li>
                                <li><a href="<?php get_prd_link(); ?>">Products</a></li>
                                <li><a href="<?php _e(_root_); ?>basket">Inquiry</a></li>
                                <li><a href="<?php _e(_root_); ?>contacts">Contact</a></li>
                            </ul>                                       
                        </div>
                        <div class="col-lg-4">
                            <div class="footer-share">
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
                        </div>
                    </div>
                    <span class="border-link"></span>
                </div>
            </div>
            <div class="footer-middle-section">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="about-widget widgets">
                                <div class="footer-logo">
                                    <a href="<?php _e(_root_); ?>"><img src="<?php _e($__url_attimgs.get_option('footer_logo')); ?>" alt=""></a>
                                </div>
                                <p><?php _e( get_option('profile') ); ?></p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="contact-info widgets">
                                <h6>Address</h6>
                                <div class="address-info">
                                    <i class="fa fa-map"></i> 
                                    <p><?php _e( get_option('footer-address') ); ?></p>
                                </div>
                                <div class="phn-number">
                                    <i class="fa fa-phone"></i>
                                    <p>Phone: <a href="tel:<?php _e( get_option('phone-number') ); ?>"><?php _e( get_option('phone-number') ); ?></a></p>
                                </div>
                                <div class="email">
                                    <i class="fa fa-envelope-o"></i>
                                    <p>Mail: <a href="mailto:<?php _e( get_option('mail-email') ); ?>"><?php _e( get_option('mail-email') ); ?></a></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="subscribe-footer widgets">
                                <h6>Join With Us</h6>
                                <p>Thank you for visting us. Please subscribe to our newsletter today. </p>
                                <form class="news-form" onsubmit="javascript: return newsletter();" method="get" action="<?php _e( _root_.'common/process.php' ); ?>">
                                    <i class="fa fa-envelope"></i>
                                    <input type="hidden" name="_p" value="newsletter">
                                    <input type="email" name="email" class="form-input" placeholder="yourmail@gmail.com">
                                    <button type="submit" class="form-button">Subscribe</button>
                                </form>
                            </div>
                        </div>
                    </div>                               
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom-section">
                <div class="container">
                    <div class="copyright">
                        <p>&copy; 2020 <?php _e( get_option('cname') ); ?> | All Rights Reserved</p>
                    </div>
                </div>
            </div>
        </footer>
        <!-- Footer End -->

        <!-- start scrollUp  -->
        <div id="scrollUp">
            <i class="fa fa-long-arrow-up"></i>
        </div>
        
        <!-- Search Modal Start -->
        <div aria-hidden="true" class="modal fade search-modal" role="dialog" tabindex="-1">
        	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true" class="fa fa-close"></span>
	        </button>
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="search-block clearfix">
                        <form role="search" method="get" action="<?php _e(_root_); ?>search" onsubmit="javascript: if(document.getElementById('search_field_lg').value=='') return false; else return true;">
                            <div class="form-group">
                                <input class="form-control" name="q" placeholder="Type Your Keyword..." type="text">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- modernizr js -->
        <script src="<?php _e(_root_); ?>assets/js/modernizr-2.8.3.min.js"></script>
        <!-- jquery latest version -->
        <script src="<?php _e(_root_); ?>assets/js/jquery.min.js"></script>
        <!-- bootstrap js -->
        <script src="<?php _e(_root_); ?>assets/js/bootstrap.min.js"></script>
        <!-- timetable js -->
        <script src="<?php _e(_root_); ?>assets/js/timetable.js"></script>
        <!-- op nav js -->
        <script src="<?php _e(_root_); ?>assets/js/jquery.nav.js"></script>
        <!-- owl.carousel js -->
        <script src="<?php _e(_root_); ?>assets/js/owl.carousel.min.js"></script>
		<!-- slick.min js -->
        <script src="<?php _e(_root_); ?>assets/js/slick.min.js"></script>
        <!-- isotope.pkgd.min js -->
        <script src="<?php _e(_root_); ?>assets/js/isotope.pkgd.min.js"></script>
        <!-- imagesloaded.pkgd.min js -->
        <script src="<?php _e(_root_); ?>assets/js/imagesloaded.pkgd.min.js"></script>
        <!-- wow js -->
        <script src="<?php _e(_root_); ?>assets/js/wow.min.js"></script>
        <!-- counter top js -->
        <script src="<?php _e(_root_); ?>assets/js/waypoints.min.js"></script>
        <script src="<?php _e(_root_); ?>assets/js/jquery.counterup.min.js"></script>
        <!-- magnific popup -->
        <script src="<?php _e(_root_); ?>assets/js/jquery.magnific-popup.min.js"></script>
        <!-- rsmenu js -->
        <script src="<?php _e(_root_); ?>assets/js/rsmenu-main.js"></script>
		<!-- Skill bar js -->
		<script src="<?php _e(_root_); ?>assets/js/skill.bars.jquery.js"></script>
        <!-- plugins js -->
        <script src="<?php _e(_root_); ?>assets/js/plugins.js"></script>
        <!-- BMI js -->
        <script src="<?php _e(_root_); ?>assets/js/bmi.js"></script>
		 <!-- main js -->
        <script src="<?php _e(_root_); ?>assets/js/main.js"></script>
    </body>

</html>