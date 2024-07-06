<?php require("config.php"); require("inc/check_session.php");

if( isset($_GET['parent']) and is_valide_category(esc($_GET['parent'])) ){
	$parent_id = esc($_GET['parent']);
	$parent = get_cateogry($parent_id);
}else{
	die("Invalide Category!");
}

$_TITLE = "Add Product";
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
				<?php if( get_category($parent_id, 'parent') != 0 ){ ?>
				<a href="subcategories.php?parent=<?php _e($parent_id); ?>" class="breadcrumb-item"><?php _e( get_category($parent_id, 'name') ); ?></a>
				<?php } ?>

				<span class="breadcrumb-item active">Add Product</span>
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
			<h5 class="card-title">Add Product</h5>
		</div>
		<div class="card-body">
				
			<form action="mini_process.php" method="post" enctype="multipart/form-data">
				
				<input type="hidden" name="p" value="AddPrd">
				<input type="hidden" name="parent" value="<?php _e($parent_id);?>">
				
				<div class="form-group row">
					<label for="art_no" class="col-form-label col-md-2">Art No:</label>
					<div class="col-md-10">
						<input id="art_no" name="art_no" class="form-control" type="text" value="" required autofocus>
					</div>
				</div>

				<div class="form-group row">
					<label for="name" class="col-form-label col-md-2">Name:</label>
					<div class="col-md-10">
						<input id="name" name="name" class="form-control" type="text" value="" required>
					</div>
				</div>

				<?php if( _setting_mini_descp_ ){ ?>
				<div class="form-group row">
					<label for="mini_description" class="col-form-label col-md-2">Meta Description:</label>
					<div class="col-md-10">
						<textarea id="mini_description" name="title" class="form-control"></textarea>
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
									<input class="form-check-input" type="checkbox" name="attribute_<?php _e($attribute['id']); ?>" id="attribute_<?php _e($attribute['id']); ?>">
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
						<input id="price" name="price" class="form-control" type="text" value="">
					</div>
				</div>
				<?php }else{ ?><input name="price" type="hidden" value="0.00"><?php } ?>

				<div class="form-group row">
					<label for="description" class="col-form-label col-md-2">Description:</label>
					<div class="col-md-10">
						<textarea id="description" name="description" class="summernote"></textarea>
					</div>
				</div>

				<div class="form-group row">
					<label for="description" class="col-form-label col-md-2">Small Image (<?php _e( _setting_img_sm_width_."x"._setting_img_sm_height_."px" );?>):</label>
					<div class="col-md-10">
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="img_small" name="img_small">
							<label class="custom-file-label" for="img_small">Choose file...</label>
						</div>
					</div>
				</div>

				<div class="form-group row">
					<label for="description" class="col-form-label col-md-2">Large Image (<?php _e( _setting_img_lg_width_."x"._setting_img_lg_height_."px" );?>):</label>
					<div class="col-md-10">
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="img_large" name="img_large">
							<label class="custom-file-label" for="img_large">Choose file...</label>
						</div>
					</div>
				</div>

				<?php if( _setting_img_xlg_ ){ ?>
				<div class="form-group row">
					<label for="description" class="col-form-label col-md-2">xLarge Image (<?php _e( _setting_img_xlg_width_."x"._setting_img_xlg_height_."px" );?>):</label>
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
									<textarea id="<?php _e( str_to_slug( constant('_setting_prd_ex_txt_'.$i.'_') ) ); ?>" name="ex_txt_<?php _e( $i ); ?>" class="summernote"></textarea>
								<?php }elseif( constant('_setting_prd_ex_txt_'.$i.'_type_') == "txt" ){ ?>
									<textarea id="<?php _e( str_to_slug( constant('_setting_prd_ex_txt_'.$i.'_') ) ); ?>" name="ex_txt_<?php _e( $i ); ?>" class="form-control"></textarea>
								<?php }else{ ?>
									<input id="<?php _e( str_to_slug( constant('_setting_prd_ex_txt_'.$i.'_') ) ); ?>" name="ex_txt_<?php _e( $i ); ?>" class="form-control" type="text" value="">
								<?php } ?>
							</div>
						</div>
					<?php }else{ ?><input name="ex_txt_<?php _e( $i ); ?>" type="hidden" value=""><?php }
				} ?>

				<div class="form-group row">
					<div class="col-md-2"></div>
					<div class="col-md-10"><button type="submit" id="edit" class="btn btn-primary"><i class="icon-floppy-disk mr-2"></i> Save</button></div>
				</div>

			</form>

		</div>
	</div>

</div>

<?php require("inc/footer.php"); ?>