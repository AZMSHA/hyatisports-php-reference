<?php require("config.php"); include("inc/check_session.php");

if( isset($_GET['id']) and is_valide_product(esc($_GET['id'])) ){
	$id = esc($_GET['id']);
	$product = get_product($id);
}else{
	die("Invalide Products!");
}

$_TITLE = "Edit ".$product['art_no'];
$_TOPBAR = true;
$_SIDEBAR = true;

require("inc/header.php");
?>

<script src="js/summernote.min.js"></script>
<script src="js/uniform.min.js"></script>

<script src="js/editor_summernote.js"></script>

<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex">
			<h4><i class="icon-list-unordered mr-2"></i> <span class="font-weight-semibold"><?php _e( $_TITLE ); ?></span></h4>
			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
		</div>
	</div>

	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="index.php" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
				
				<a href="categories.php" class="breadcrumb-item">Categories</a>
				<?php if( get_category($product['parent'], 'parent') != 0 ){ ?>
				<a href="subcategories.php?parent=<?php _e($product['parent']); ?>" class="breadcrumb-item"><?php _e( get_category($product['parent'], 'name') ); ?></a>
				<?php } ?>

				<span class="breadcrumb-item active"><?php _e( $_TITLE ); ?></span>
			</div>
		</div>
	</div>
</div>

<div class="content">

	<?php if( isset($_GET['mess']) and !empty($_GET['mess']) ){ ?>
	<div class="alert text-violet-800 alpha-violet alert-dismissible">
		<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
		<?php _e( $_GET['mess'] ); ?>
	</div>
	<?php } ?>

	<div id="add-new-card" class="card">
		<div class="card-header header-elements-inline">
			<h5 class="card-title">Edit Product</h5>
		</div>
		<div class="card-body">
				
			<form action="mini_process.php" method="post" enctype="multipart/form-data">
				
				<input type="hidden" name="p" value="EditPrd">
				<input type="hidden" name="id" value="<?php _e($id);?>">
				
				<div class="form-group row">
					<label for="art_no" class="col-form-label col-md-2">Art No:</label>
					<div class="col-md-10">
						<input id="art_no" name="art_no" class="form-control" type="text" value="<?php _e($product['art_no']); ?>" required autofocus>
					</div>
				</div>

				<div class="form-group row">
					<label for="name" class="col-form-label col-md-2">Name:</label>
					<div class="col-md-10">
						<input id="name" name="name" class="form-control" type="text" value="<?php _e($product['name']); ?>" required>
					</div>
				</div>

				<div class="form-group row">
					<label for="slug" class="col-form-label col-md-2">Slug:</label>
					<div class="col-md-10">
						<input id="slug" name="slug" class="form-control" type="text" value="<?php _e($product['slug']); ?>" required>
					</div>
				</div>

				<?php if( _setting_mini_descp_ ){ ?>
				<div class="form-group row">
					<label for="mini_description" class="col-form-label col-md-2">Meta Description:</label>
					<div class="col-md-10">
						<textarea id="mini_description" name="mini_description" class="form-control"><?php _e($product['mini_description']); ?></textarea>
					</div>
				</div>
				<?php }else{ ?><input name="mini_description" type="hidden" value=""><?php } ?>

				<?php
				$variation_query = mysql_query("SELECT * FROM `variations` ORDER BY `rank` ASC");
					if( mysql_num_rows($variation_query) > 0 ){
						while( $vaiation = mysql_fetch_array($variation_query) ){
							$attribute_query = mysql_query("SELECT * FROM `attributes` WHERE `variation_id`=".$vaiation['id']." ORDER BY `rank` ASC");
								if( mysql_num_rows($attribute_query) > 0 ){
				?>
				<div class="form-group row">
					<label class="col-form-label col-md-2"><?php _e( $vaiation['name'] ); ?>:</label>
					<div class="col-md-10">
						<?php while( $attribute = mysql_fetch_array($attribute_query) ){ ?>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="checkbox" name="attribute_<?php _e($attribute['id']); ?>" id="attribute_<?php _e($attribute['id']); ?>"<?php if( prd_has_attribute( $id, $attribute['id'] ) ){ ?> checked="checked"<?php } ?>>
									<label class="form-check-label" for="attribute_<?php _e($attribute['id']); ?>"><?php _e($attribute['name']); ?></label>
								</div>
						<?php } ?>
					</div>
				</div>
				<?php
								}
						}
					}
				?>

				<?php if( _setting_price_ ){ ?>
				<div class="form-group row">
					<label for="price" class="col-form-label col-md-2">Price:</label>
					<div class="col-md-10">
						<input id="price" name="price" class="form-control" type="text" value="<?php _e($product['price']); ?>">
					</div>
				</div>
				<?php }else{ ?><input name="price" type="hidden" value="<?php _e($product['price']); ?>"><?php } ?>

				<div class="form-group row">
					<label for="description" class="col-form-label col-md-2">Description:</label>
					<div class="col-md-10">
						<textarea id="description" name="description" class="summernote"><?php _e($product['description']); ?></textarea>
					</div>
				</div>

				<?php if( !empty($product['img_small']) ){ ?>
				<div class="form-group row">
					<label class="col-form-label col-md-2">Old Small Image:</label>
					<div class="col-md-10">
						<div class="imgcontainer">
							<img class="mw-100 mx-auto" src="<?php _e($__url_sm_imgs.$product['img_small']); ?>">
							<div class="imgwrapper">
								<a href="<?php _e($__url_sm_imgs.$product['img_small']); ?>" target="_blank"><i class="icon-eye"></i></a>
								<?php if( _setting_rm_img_ ){ ?><a onClick="javascript: if(confirm('Are you sure you want to remove this image?')) return true; else return false;" href="mini_process.php?p=removeimg&r2=<?php _e(urlencode(current_page())); ?>&type=products&id=<?php _e($product['id']); ?>&col=img_small&dir=<?php _e(urlencode($__dir_sm_imgs)); ?>"><i class="icon-bin"></i></a><?php } ?>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>

				<div class="form-group row">
					<label for="img_small" class="col-form-label col-md-2">Small Image (<?php _e( _setting_img_sm_width_."x"._setting_img_sm_height_."px" );?>):</label>
					<div class="col-md-10">
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="img_small" name="img_small">
							<label class="custom-file-label" for="img_small">Choose file...</label>
						</div>
					</div>
				</div>

				<?php if( !empty($product['img_large']) ){ ?>
				<div class="form-group row">
					<label class="col-form-label col-md-2">Old Large Image:</label>
					<div class="col-md-10">
						<div class="imgcontainer">
							<img class="mw-100 mx-auto" src="<?php _e($__url_lg_imgs.$product['img_large']); ?>">
							<div class="imgwrapper">
								<a href="<?php _e($__url_lg_imgs.$product['img_large']); ?>" target="_blank"><i class="icon-eye"></i></a>
								<?php if( _setting_rm_img_ ){ ?><a onClick="javascript: if(confirm('Are you sure you want to remove this image?')) return true; else return false;" href="mini_process.php?p=removeimg&r2=<?php _e(urlencode(current_page())); ?>&type=products&id=<?php _e($product['id']); ?>&col=img_large&dir=<?php _e(urlencode($__dir_lg_imgs)); ?>"><i class="icon-bin"></i></a><?php } ?>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>

				<div class="form-group row">
					<label for="img_large" class="col-form-label col-md-2">Large Image (<?php _e( _setting_img_lg_width_."x"._setting_img_lg_height_."px" );?>):</label>
					<div class="col-md-10">
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="img_large" name="img_large">
							<label class="custom-file-label" for="img_large">Choose file...</label>
						</div>
					</div>
				</div>

				<?php if( _setting_img_xlg_ ){ ?>
				<?php if( !empty($product['img_xlarge']) ){ ?>
				<div class="form-group row">
					<label class="col-form-label col-md-2">Old xLarge Image:</label>
					<div class="col-md-10">
						<div class="imgcontainer">
							<img class="mw-100 mx-auto" src="<?php _e($__url_xlg_imgs.$product['img_xlarge']); ?>">
							<div class="imgwrapper">
								<a href="<?php _e($__url_xlg_imgs.$product['img_xlarge']); ?>" target="_blank"><i class="icon-eye"></i></a>
								<?php if( _setting_rm_img_ ){ ?><a onClick="javascript: if(confirm('Are you sure you want to remove this image?')) return true; else return false;" href="mini_process.php?p=removeimg&r2=<?php _e(urlencode(current_page())); ?>&type=products&id=<?php _e($product['id']); ?>&col=img_xlarge&dir=<?php _e(urlencode($__dir_xlg_imgs)); ?>"><i class="icon-bin"></i></a><?php } ?>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>

				<div class="form-group row">
					<label for="img_xlarge" class="col-form-label col-md-2">xLarge Image (<?php _e( _setting_img_xlg_width_."x"._setting_img_xlg_height_."px" );?>):</label>
					<div class="col-md-10">
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="img_xlarge" name="img_xlarge">
							<label class="custom-file-label" for="img_xlarge">Choose file...</label>
						</div>
					</div>
				</div>
				<?php } ?>

				<?php for($i=1; $i <= 15; $i++){
					if( constant('_setting_prd_ex_txt_'.$i.'_') ){ ?>
						<div class="form-group row">
							<label for="<?php _e( str_to_slug( constant('_setting_prd_ex_txt_'.$i.'_') ) ); ?>" class="col-form-label col-md-2"><?php _e( constant('_setting_prd_ex_txt_'.$i.'_') ); ?>:</label>
							<div class="col-md-10">
								<?php if( constant('_setting_prd_ex_txt_'.$i.'_type_') == "rte" ){ ?>
									<textarea id="<?php _e( str_to_slug( constant('_setting_prd_ex_txt_'.$i.'_') ) ); ?>" name="ex_txt_<?php _e( $i ); ?>" class="summernote"><?php _e($product['ex_txt_'.$i]); ?></textarea>
								<?php }elseif( constant('_setting_prd_ex_txt_'.$i.'_type_') == "txt" ){ ?>
									<textarea id="<?php _e( str_to_slug( constant('_setting_prd_ex_txt_'.$i.'_') ) ); ?>" name="ex_txt_<?php _e( $i ); ?>" class="form-control"><?php _e($product['ex_txt_'.$i]); ?></textarea>
								<?php }else{ ?>
									<input id="<?php _e( str_to_slug( constant('_setting_prd_ex_txt_'.$i.'_') ) ); ?>" name="ex_txt_<?php _e( $i ); ?>" class="form-control" type="text" value="<?php _e($product['ex_txt_'.$i]); ?>">
								<?php } ?>
							</div>
						</div>
					<?php }else{ ?><input name="ex_txt_<?php _e( $i ); ?>" type="hidden" value="<?php _e($product['ex_txt_'.$i]); ?>"><?php }
				} ?>

				<div class="form-group row">
					<div class="col-md-2"></div>
					<div class="col-md-10"><button type="submit" id="edit" class="btn btn-primary"><i class="icon-floppy-disk mr-2"></i> Save</button></div>
				</div>

			</form>

		</div>
	</div>

</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('.imgcontainer').hover(
			function(){ $('.imgwrapper', this).fadeIn(100); },
			function(){ $('.imgwrapper', this).fadeOut(100); }
		);
	});
</script>

<?php require("inc/footer.php"); ?>