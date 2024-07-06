<?php require("config.php"); include("inc/check_session.php");

if( isset($_GET['id']) and is_valide_category(esc($_GET['id'])) ){
	$id = esc($_GET['id']);
	$cat = get_cateogry($id);
}else{
	die("Invalide Category!");
}

$_TITLE = "Edit ".$cat['name'];
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
				<span class="breadcrumb-item active"><?php _e( $cat['name'] ); ?></span>
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
			<h5 class="card-title">Edit Category</h5>
		</div>
		<div class="card-body">
				
			<form action="mini_process.php" method="post" enctype="multipart/form-data">
				
				<input type="hidden" name="p" value="EditCategory">
				<input type="hidden" name="id" value="<?php _e($id); ?>">
				<input type="hidden" name="parent" value="0">
				
				<div class="form-group row">
					<label for="name" class="col-form-label col-md-2">Name:</label>
					<div class="col-md-10">
						<input id="name" name="name" class="form-control" type="text" value="<?php _e($cat['name']); ?>" required>
					</div>
				</div>

				<div class="form-group row">
					<label for="slug" class="col-form-label col-md-2">Slug:</label>
					<div class="col-md-10">
						<input id="slug" name="slug" class="form-control" type="text" value="<?php _e($cat['slug']); ?>">
					</div>
				</div>

				<div class="form-group row">
					<label for="title" class="col-form-label col-md-2">Title:</label>
					<div class="col-md-10">
						<input id="title" name="title" class="form-control" type="text" value="<?php _e($cat['title']); ?>">
					</div>
				</div>

				<div class="form-group row">
					<label for="keywords" class="col-form-label col-md-2">Keywords:</label>
					<div class="col-md-10">
						<textarea id="keywords" name="title" class="form-control"><?php _e($cat['keywords']); ?></textarea>
					</div>
				</div>

				<div class="form-group row">
					<label for="description" class="col-form-label col-md-2">Description:</label>
					<div class="col-md-10">
						<textarea id="description" name="title" class="summernote"><?php _e($cat['description']); ?></textarea>
					</div>
				</div>

				<?php if( !empty($cat['img_thumb']) ){ ?>
				<div class="form-group row">
					<label for="description" class="col-form-label col-md-2">Old Thumb:</label>
					<div class="col-md-10">
						<div class="imgcontainer">
							<img class="mw-100 mx-auto" src="<?php _e($__url_attimgs.$cat['img_thumb']); ?>">
							<div class="imgwrapper">
								<a href="<?php _e($__url_attimgs.$cat['img_thumb']); ?>" target="_blank"><i class="icon-eye"></i></a>
								<?php if( _setting_rm_img_ ){ ?><a onClick="javascript: if(confirm('Are you sure you want to remove this image?')) return true; else return false;" href="mini_process.php?p=removeimg&r2=<?php _e(urlencode(current_page())); ?>&type=categories&id=<?php _e($cat['id']); ?>&col=img_thumb&dir=<?php _e(urlencode($__dir_attimgs)); ?>"><i class="icon-bin"></i></a><?php } ?>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>

				<div class="form-group row">
					<label for="description" class="col-form-label col-md-2">Thumb:</label>
					<div class="col-md-10">
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="img_thumb" name="img_thumb">
							<label class="custom-file-label" for="img_thumb">Choose file...</label>
						</div>
					</div>
				</div>

				<?php if( !empty($cat['img_banner']) ){ ?>
				<div class="form-group row">
					<label for="description" class="col-form-label col-md-2">Old Banner:</label>
					<div class="col-md-10">
						<div class="imgcontainer">
							<img class="mw-100 mx-auto" src="<?php _e($__url_attimgs.$cat['img_banner']); ?>">
							<div class="imgwrapper">
								<a href="<?php _e($__url_attimgs.$cat['img_banner']); ?>" target="_blank"><i class="icon-eye"></i></a>
								<?php if( _setting_rm_img_ ){ ?><a onClick="javascript: if(confirm('Are you sure you want to remove this image?')) return true; else return false;" href="mini_process.php?p=removeimg&r2=<?php _e(urlencode(current_page())); ?>&type=categories&id=<?php _e($cat['id']); ?>&col=img_banner&dir=<?php _e(urlencode($__dir_attimgs)); ?>"><i class="icon-bin"></i></a><?php } ?>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>

				<div class="form-group row">
					<label for="description" class="col-form-label col-md-2">Banner:</label>
					<div class="col-md-10">
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="img_banner" name="img_banner">
							<label class="custom-file-label" for="img_banner">Choose file...</label>
						</div>
					</div>
				</div>

				<?php if( !empty($cat['img_x']) ){ ?>
				<div class="form-group row">
					<label for="description" class="col-form-label col-md-2">Old Banner:</label>
					<div class="col-md-10">
						<div class="imgcontainer">
							<img class="mw-100 mx-auto" src="<?php _e($__url_attimgs.$cat['img_x']); ?>">
							<div class="imgwrapper">
								<a href="<?php _e($__url_attimgs.$cat['img_x']); ?>" target="_blank"><i class="icon-pencil"></i></a>
								<?php if( _setting_rm_img_ ){ ?><a onClick="javascript: if(confirm('Are you sure you want to remove this image?')) return true; else return false;" href="mini_process.php?p=removeimg&r2=<?php _e(urlencode(current_page())); ?>&type=categories&id=<?php _e($cat['id']); ?>&col=img_x&dir=<?php _e(urlencode($__dir_attimgs)); ?>"><i class="icon-bin"></i></a><?php } ?>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>

				<?php if( !empty($cat['img_x']) OR $cat['parent']==0 ){ ?>
				<div class="form-group row">
					<label for="description" class="col-form-label col-md-2">Extra Image:</label>
					<div class="col-md-10">
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="img_x" name="img_x">
							<label class="custom-file-label" for="img_x">Choose file...</label>
						</div>
					</div>
				</div>
				<?php } ?>

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