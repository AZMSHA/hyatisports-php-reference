<?php require("config.php"); include("inc/check_session.php");

if( isset($_GET['product']) and is_valide_product(esc($_GET['product'])) ){
	$product_id = $_GET['product'];
	$product = get_product($product_id);
	$_TITLE = "Gallery for ".$product['art_no'];
}else{
	$product_id = 0;
	$_TITLE = "Gallery";
}

$_TOPBAR = true;
$_SIDEBAR = true;

require("inc/header.php");
?>

<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex">
			<h4><i class="icon-gallery mr-2"></i> <span class="font-weight-semibold"><?php _e( $_TITLE ); ?></span></h4>
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
				<?php if( $product_id != 0 ){ ?>
				<a href="listprd.php?parent=<?php _e( $product['parent'] ); ?>" class="breadcrumb-item"><?php _e( $product['art_no'] ); ?></a>
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

	<div id="add-new-card" class="card" style="display:none;">
		<div class="card-header header-elements-inline">
			<h5 class="card-title">Add New Image</h5>
		</div>
		<div class="card-body">
				
			<form action="mini_process.php" method="post" enctype="multipart/form-data">

				<input type="hidden" name="p" value="AddGallery">
				<input type="hidden" name="product" value="<?php _e($product_id); ?>">
				
				<div class="form-group row">
					<label for="title" class="col-form-label col-md-2">Title:</label>
					<div class="col-md-10">
						<input id="title" name="title" class="form-control" type="text">
					</div>
				</div>

				<div class="form-group row">
					<label for="description" class="col-form-label col-md-2">Description:</label>
					<div class="col-md-10">
						<textarea id="description" name="description" class="form-control"></textarea>
					</div>
				</div>

				<div class="form-group row">
					<label for="description" class="col-form-label col-md-2">Image:</label>
					<div class="col-md-10">
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="image" name="image" required>
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

	<div class="card">

		<div class="card-body">
			Total of <?php $images = mysql_result(mysql_query("SELECT count(*) FROM `gallery` WHERE `product`=".$product_id),0); _e( appendzero($images) ); ?> images found!
		</div>

		<?php
		$gallery_top_rank = gallery_top_rank($product_id);
		$gallery_bottom_rank = gallery_bottom_rank($product_id);
		$gallery_query = mysql_query("SELECT * FROM `gallery` WHERE `product`=".$product_id." ORDER BY `rank` ASC");
		if( mysql_num_rows($gallery_query) > 0 ){
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
					
					<?php while($RS_gall = mysql_fetch_array($gallery_query)){ ?>
					<tr>

						<td class="text-center"><?php _e( $RS_gall['id'] ); ?></td>
						
						<td>
							<a href="<?php _e($__url_gallery.$RS_gall['image']); ?>" target="_blank" class="font-weight-bold"><?php _e( $RS_gall['title'] ); ?></a>
						</td>

						<td class="text-center">
							<div class="btn-group" role="group">


								<?php if( $RS_gall['rank'] == $gallery_top_rank ){ ?>
									<button type="button" class="btn btn-sm bg-secondary">
								<?php }else{ ?>
									<a class="btn btn-sm bg-teal-400" href="mini_process.php?p=gallrank&id=<?php _e($RS_gall['id']); ?>&r=up">
								<?php } ?>
									<i class="icon-arrow-up5"></i>
								<?php if( $RS_gall['rank'] == $gallery_top_rank ){ ?>
									</button>
								<?php }else{ ?>
									</a>
								<?php } ?>

								<?php if( $RS_gall['rank'] == $gallery_bottom_rank ){ ?>
									<button type="button" class="btn btn-sm bg-secondary">
								<?php }else{ ?>
									<a class="btn btn-sm bg-teal-400" href="mini_process.php?p=gallrank&id=<?php _e($RS_gall['id']); ?>&r=down">
								<?php } ?>
									<i class="icon-arrow-down5"></i>
								<?php if( $RS_gall['rank'] == $gallery_bottom_rank ){ ?>
								 	</button>
								<?php }else{ ?>
									</a>
								<?php } ?>

							</div>
						</td>

						<td class="text-center">
							<form action="mini_process.php" method="post" onSubmit="javascript: if(confirm('Are you sure you want to delete this?')) return true; else return false; ">
								<input type="hidden" name="p" value="DelGallery">
								<input type="hidden" name="id" value="<?php _e($RS_gall['id']); ?>">
								<div class="btn-group" role="group">
									<a href="editgallery.php?product=<?php _e($product_id); ?>&id=<?php _e($RS_gall['id']); ?>" class="btn btn-sm bg-indigo-400"><i class="icon-pencil"></i></a>
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