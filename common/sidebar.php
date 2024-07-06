                    <div class="col-lg-3">
                        <div class="sidebar-area mmt-40">
                            <div class="cate-box">
                                <?php if( isset( $_CAT_ID ) and is_numeric($_CAT_ID) and $_CAT_ID > 0 ){
                    			$_CAT_PARENT = get_cateogry($_CAT_ID, 'parent');
                    			$sidebar_cat_main_query = mysql_query("SELECT * FROM `categories` WHERE `parent`=".$_CAT_PARENT." AND `show`='y' ORDER BY `rank` ASC");
                    			if( mysql_num_rows($sidebar_cat_main_query) > 0 ){ ?>
                                <h4 class="title">More <?php if( !empty(get_cateogry($_CAT_PARENT, 'name')) ){ _e('in '.get_cateogry($_CAT_PARENT, 'name')); }else{ _e('Products'); } ?></h4>
                                <ul>
                    				<?php while( $RS_menu_cat_main = mysql_fetch_array($sidebar_cat_main_query) ){ ?>
                    				<li><a href="<?php if( does_category_has_subs($RS_menu_cat_main['id']) ){ _e( get_max_category_link($RS_menu_cat_main['id']) ); }else{ _e( get_category_link($RS_menu_cat_main['id']) ); } ?>"><?php _e($RS_menu_cat_main['name']); ?></a></li>
                    				<?php } ?>
                    			</ul>
                    			<?php } } ?>
                            </div>
                        </div>
                        <div class="sidebar-area mmt-40">
                            <div class="cate-box">
                                <h4 class="title">Categories</h4>
                                <?php $menu_cat_query = mysql_query("SELECT * FROM `categories` WHERE `parent`=0 AND `show`='y' ORDER BY `rank` ASC");
                                if( mysql_num_rows($menu_cat_query) > 0 ){
                                ?>
                                <ul>
                                    <?php while( $RS_cat = mysql_fetch_array($menu_cat_query) ){ ?>
                                        <li><a href="<?php _e( get_first_subcat_link($RS_cat['id']) ); ?>"><?php _e( $RS_cat['name'] ); ?></a></li>
                                    <?php } ?>
                                </ul>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    
                    
        