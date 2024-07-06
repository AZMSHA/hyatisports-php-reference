<?php require("config.php"); include("inc/check_session.php");

if( isset($_GET['id']) and is_valide_galery(esc($_GET['id'])) ){
	$galery_id = $_GET['id'];
	$galery = get_gallery($galery_id);
}else{
	redirect("index.php");
}

if( isset($_GET['product']) and is_valide_product(esc($_GET['product'])) ){
	$product_id = $_GET['product'];
	$product = get_product($product_id);
}else{
	$product_id = 0;
}

$_TITLE = "Edit Gallery Image";
$_TOPBAR = true;
$_SIDEBAR = true;

require("inc/header.php");
?>

<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex">
			<h4><i class="icon-gallery mr-2"></i> <span class="font-weight-semibold"><?php _e( $_TITLE ); ?></span></h4>
		</div>
	</div>

	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="index.php" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
				<?php if( $product_id != 0 ){ ?>
				<a href="listprd.php?parent=<?php _e( $product['parent'] ); ?>" class="breadcrumb-item"><?php _e( $product['art_no'] ); ?></a>
				<a href="gallery.php?product=<?php _e( $product_id ); ?>" class="breadcrumb-item">Gallery for <?php _e( $product['art_no'] ); ?></a>
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
			<h5 class="card-title">Edit Image</h5>
		</div>
		<div class="card-body">
				
			<form action="mini_process.php" method="post" enctype="multipart/form-data">

				<input type="hidden" name="p" value="EditGallery">
				<input type="hidden" name="id" value="<?php _e($galery['id']); ?>">
				<input type="hidden" name="product" value="<?php _e($galery['product']); ?>">
				
				<div class="form-group row">
					<label for="title" class="col-form-label col-md-2">Title:</label>
					<div class="col-md-10">
						<input id="title" name="title" class="form-control" type="text" value="<?php _e( $galery['title'] ); ?>">
					</div>
				</div>

				<div class="form-group row">
					<label for="description" class="col-form-label col-md-2">Description:</label>
					<div class="col-md-10">
						<textarea id="description" name="description" class="form-control"><?php _e( $galery['description'] ); ?></textarea>
					</div>
				</div>

				<?php if( !empty($galery['image']) ){ ?>
				<div class="form-group row">
					<label for="description" class="col-form-label col-md-2">Old Image:</label>
					<div class="col-md-10">
						<div class="imgcontainer">
							<img class="mw-100 mx-auto" src="<?php _e($__url_gallery.$galery['image']); ?>">
						</div>
					</div>
				</div>
				<?php } ?>

				<div class="form-group row">
					<label for="description" class="col-form-label col-md-2">Image:</label>
					<div class="col-md-10">
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="image" name="image">
							<label class="custom-file-label" for="image">Choose file...</label>
						</div>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-md-2"></div>
					<div class="col-md-10"><button type="submit" id="edit" class="btn btn-primary"><i class="icon-floppy-disk mr-2"></i> Save</button></div>
				</div>

			</form>

		</div>
	</div>

</div>

<?php require("inc/footer.php"); ?>