<?php require("config.php"); include("inc/check_session.php");

if( isset($_GET['banner']) ){
	$banner_id = esc($_GET['banner']);
	$banner_query = mysql_query("SELECT * FROM `banners` WHERE `id`=".$banner_id);
	if( mysql_num_rows($banner_query) < 1 ){ die(); }
	$RS_banner = mysql_fetch_array($banner_query);
}else{
	die("Invalide Banner!");
}

$_TITLE = "Images for ".$RS_banner['name'];
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
			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
		</div>

		<div class="header-elements d-none">
			<div class="d-flex justify-content-center">
				<button id="add-new" class="btn btn-link btn-float text-default"><i class="icon-plus3 text-primary"></i><span>Add New</span></button>
			</div>
		</div>
	</div>

	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="index.php" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
				<a href="banners.php" class="breadcrumb-item">Banners</a>
				<span class="breadcrumb-item active"><?php _e( $RS_banner['name'] ); ?></span>
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

	<div id="add-new-card" class="card" style="display:none;">
		<div class="card-header header-elements-inline">
			<h5 class="card-title">Add New Image</h5>
		</div>
		<div class="card-body">
				
			<form action="mini_process.php" method="post" enctype="multipart/form-data">

				<input type="hidden" name="p" value="AddBannerImage">
				<input type="hidden" name="banner" value="<?php _e($banner_id); ?>">
				
				<div class="form-group row">
					<label for="name" class="col-form-label col-md-2">Name:</label>
					<div class="col-md-10">
						<input id="name" name="name" class="form-control" type="text" required>
					</div>
				</div>

				<div class="form-group row">
					<label for="link" class="col-form-label col-md-2">Link:</label>
					<div class="col-md-10">
						<input id="link" name="link" class="form-control" type="text">
					</div>
				</div>

				<div class="form-group row">
					<label for="image" class="col-form-label col-md-2">Image</label>
					<div class="col-md-10">
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="image" name="image">
							<label class="custom-file-label" for="image">Choose file...</label>
						</div>
					</div>
				</div>

				<div class="form-group row">
					<label for="description" class="col-form-label col-md-2">Description:</label>
					<div class="col-md-10">
						<textarea id="description" name="description" class="summernote"></textarea>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-md-2"></div>
					<div class="col-md-10"><button type="submit" id="edit" class="btn btn-primary"><i class="icon-floppy-disk mr-2"></i> Save</button></div>
				</div>

			</form>

		</div>
	</div>

	<div class="card">

		<div class="card-body">
			Total of <?php $images = mysql_result(mysql_query("SELECT count(*) FROM `banner_images` WHERE `banner`=".$banner_id),0); _e( appendzero($images) ); ?> images found!
		</div>

		<?php
		$banner_image_top_rank = banner_image_top_rank($banner_id);
        $banner_image_bottom_rank = banner_image_bottom_rank($banner_id);
        $banner_image_query = mysql_query("SELECT * FROM `banner_images` WHERE `banner`=".$banner_id." ORDER BY `rank` ASC");
        if( mysql_num_rows($banner_image_query) > 0 ){
		?>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				
				<thead>
					<tr>
						<th class="text-center" style="width:50px;">ID</th>
						<th>Name</th>
						<th class="text-center" style="width:100px;">Rank</th>
						<th class="text-center" style="width:100px;">Options</th>
					</tr>
				</thead>

				<tbody>
					
					<?php while($RS_banner_image = mysql_fetch_array($banner_image_query)){ ?>
					<tr>

						<td class="text-center"><?php _e( $RS_banner_image['id'] ); ?></td>
						
						<td>
							<strong><?php _e( $RS_banner_image['name'] ); ?></strong>
							<?php if( !empty( $RS_banner_image['link'] ) ){ ?><br /><a href="<?php _e( $RS_banner_image['link'] ); ?>" title="<?php _e( $RS_banner_image['link'] ); ?>" target="_blank"><- link -></a><?php } ?>
						</td>

						<td class="text-center">
							<div class="btn-group" role="group">


								<?php if( $RS_banner_image['rank'] == $banner_image_top_rank ){ ?>
									<button type="button" class="btn btn-sm bg-secondary">
								<?php }else{ ?>
									<a class="btn btn-sm bg-teal-400" href="mini_process.php?p=bannerimagerank&id=<?php _e($RS_banner_image['id']); ?>&banner_id=<?php _e($banner_id); ?>&r=up">
								<?php } ?>
									<i class="icon-arrow-up5"></i>
								<?php if( $RS_banner_image['rank'] == $banner_image_top_rank ){ ?>
									</button>
								<?php }else{ ?>
									</a>
								<?php } ?>

								<?php if( $RS_banner_image['rank'] == $banner_image_bottom_rank ){ ?>
									<button type="button" class="btn btn-sm bg-secondary">
								<?php }else{ ?>
									<a class="btn btn-sm bg-teal-400" href="mini_process.php?p=bannerimagerank&id=<?php _e($RS_banner_image['id']); ?>&banner_id=<?php _e($banner_id); ?>&r=down">
								<?php } ?>
									<i class="icon-arrow-down5"></i>
								<?php if( $RS_banner_image['rank'] == $banner_image_bottom_rank ){ ?>
								 	</button>
								<?php }else{ ?>
									</a>
								<?php } ?>

							</div>
						</td>

						<td class="text-center">
							<form action="mini_process.php" method="post" onSubmit="javascript: if(confirm('Are you sure you want to delete this?')) return true; else return false; ">
								<input type="hidden" name="p" value="DelBannerImage">
								<input type="hidden" name="id" value="<?php _e($RS_banner_image['id']); ?>">
								<input type="hidden" name="banner" value="<?php _e($banner_id); ?>">
								<div class="btn-group" role="group">
									<a href="edit_banner_images.php?id=<?php _e($RS_banner_image['id']); ?>" class="btn btn-sm bg-indigo-400"><i class="icon-pencil"></i></a>
									<button type="submit" class="btn btn-sm bg-blue-400"><i class="icon-bin"></i></button>
								</div>
							</form>
						</td>

					</tr>
					<?php } ?>

				</tbody>

			</table>
		</div>
		<?php } ?>

	</div>

</div>

<script type="text/javascript">
$(document).ready(function(){
	$("#add-new").click(function(){
	    $("#add-new-card").toggle();
	});
});
</script>

<?php require("inc/footer.php"); ?>