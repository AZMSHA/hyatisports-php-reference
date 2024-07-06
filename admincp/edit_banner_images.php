<?php require("config.php"); include("inc/check_session.php");

if( isset($_GET['id']) and get_banner_image($_GET['id']) ){
	$banner_image_id = esc($_GET['id']);
	$RS_banner_image = get_banner_image($banner_image_id);
}else{
	die("Invalide Banner Image!");
}

$_TITLE = "Edit Banner Image";
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
			<h4><i class="icon-image2 mr-2"></i> <span class="font-weight-semibold"><?php _e( $_TITLE ); ?></span></h4>
		</div>
	</div>

	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="index.php" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
				<a href="banners.php" class="breadcrumb-item">Banners</a>
				<a href="banner_images.php?banner=<?php _e( $RS_banner_image['banner'] ); ?>" class="breadcrumb-item"><?php _e( get_banner($RS_banner_image['banner'], 'name') ); ?></a>
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

	<div class="card">
		<div class="card-header header-elements-inline">
			<h5 class="card-title">Add New Image</h5>
		</div>
		<div class="card-body">
				
			<form action="mini_process.php" method="post" enctype="multipart/form-data">

				<input type="hidden" name="p" value="EditBannerImage">
				<input type="hidden" name="id" value="<?php _e($banner_image_id); ?>">
				
				<div class="form-group row">
					<label for="name" class="col-form-label col-md-2">Name:</label>
					<div class="col-md-10">
						<input id="name" name="name" class="form-control" type="text" required value="<?php _e($RS_banner_image['name']); ?>">
					</div>
				</div>

				<div class="form-group row">
					<label for="link" class="col-form-label col-md-2">Link:</label>
					<div class="col-md-10">
						<input id="link" name="link" class="form-control" type="text" value="<?php _e($RS_banner_image['link']); ?>">
					</div>
				</div>

				<?php if( !empty($RS_banner_image['image']) ){ ?>
				<div class="form-group row">
					<label for="description" class="col-form-label col-md-2">Old Image:</label>
					<div class="col-md-10">
						<div class="imgcontainer">
							<img class="mw-100 mx-auto" src="<?php _e($__url_banner.$RS_banner_image['image']); ?>">
						</div>
					</div>
				</div>
				<?php } ?>

				<div class="form-group row">
					<label for="description" class="col-form-label col-md-2">Image</label>
					<div class="col-md-10">
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="image" name="image">
							<label class="custom-file-label" for="image">Choose file...</label>
						</div>
					</div>
				</div>
				
				<?php if( !empty($RS_banner_image['imagebg']) ){ ?>
				<div class="form-group row">
					<label for="description" class="col-form-label col-md-2">Old Image Bg:</label>
					<div class="col-md-10">
						<div class="imgcontainer">
							<img class="mw-100 mx-auto" src="<?php _e($__url_banner.$RS_banner_image['imagebg']); ?>">
						</div>
					</div>
				</div>
				<?php } ?>

				<div class="form-group row">
					<label for="description" class="col-form-label col-md-2">Image Bg</label>
					<div class="col-md-10">
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="imagebg" name="imagebg">
							<label class="custom-file-label" for="image">Choose file...</label>
						</div>
					</div>
				</div>

				<div class="form-group row">
					<label for="description" class="col-form-label col-md-2">Description:</label>
					<div class="col-md-10">
						<textarea id="description" name="description" class="summernote"><?php _e( $RS_banner_image['text'] ); ?></textarea>
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

<script type="text/javascript">
	$(document).ready(function(){
		$('.imgcontainer').hover(
			function(){ $('.imgwrapper', this).fadeIn(100); },
			function(){ $('.imgwrapper', this).fadeOut(100); }
		);
	});
</script>

<?php require("inc/footer.php"); ?>