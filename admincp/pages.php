<?php require("config.php"); include("inc/check_session.php");

$_TITLE = "Pages";
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
			<h4><i class="icon-grid mr-2"></i> <span class="font-weight-semibold"><?php _e( $_TITLE ); ?></span></h4>
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
			<h5 class="card-title">Add New Page</h5>
		</div>
		<div class="card-body">
				
			<form action="mini_process.php" method="post" enctype="multipart/form-data">

				<input type="hidden" name="p" value="AddPage">
				
				<div class="form-group row">
					<label for="title" class="col-form-label col-md-2">Title:</label>
					<div class="col-md-10">
						<input id="title" name="title" class="form-control" type="text" required>
					</div>
				</div>

				<div class="form-group row">
					<label for="keywords" class="col-form-label col-md-2">Keywords:</label>
					<div class="col-md-10">
						<textarea id="keywords" name="keywords" class="form-control"></textarea>
					</div>
				</div>

				<div class="form-group row">
					<label for="description" class="col-form-label col-md-2">Description:</label>
					<div class="col-md-10">
						<textarea id="description" name="description" class="form-control"></textarea>
					</div>
				</div>

				<div class="form-group row">
					<label for="text" class="col-form-label col-md-2">Content:</label>
					<div class="col-md-10">
						<textarea id="text" name="text" class="summernote"></textarea>
					</div>
				</div>

				<?php if( _page_img_thumb_ ){ ?>
				<div class="form-group row">
					<label for="description" class="col-form-label col-md-2">Thumb:</label>
					<div class="col-md-10">
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="img_thumb" name="img_thumb">
							<label class="custom-file-label" for="img_thumb">Choose file...</label>
						</div>
					</div>
				</div>
				<?php } ?>

				<?php if( _page_img_banner_ ){ ?>
				<div class="form-group row">
					<label for="description" class="col-form-label col-md-2">Banner:</label>
					<div class="col-md-10">
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="img_banner" name="img_banner">
							<label class="custom-file-label" for="img_banner">Choose file...</label>
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

	<div class="card">

		<div class="card-body">
			Total of <?php $pages = mysql_result(mysql_query("SELECT count(*) FROM `pages`"),0); _e( appendzero($pages) ); ?> pages found!
		</div>

		<?php
		$pages_query = mysql_query("SELECT * FROM `pages`");
		if( mysql_num_rows($pages_query) > 0 ){
		?>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				
				<thead>
					<tr>
						<th class="text-center" style="width:50px;">ID</th>
						<th>Name</th>
						<th class="text-center" style="width:100px;">Options</th>
					</tr>
				</thead>

				<tbody>
					
					<?php while($RS_page = mysql_fetch_array($pages_query)){ ?>
					<tr>

						<td class="text-center"><?php _e( $RS_page['id'] ); ?></td>
						
						<td>
							<strong><?php _e( $RS_page['title'] ); ?></strong>
						</td>

						<td class="text-center">
							<?php if( _setting_page_edit_ ){ ?>
							<form action="mini_process.php" method="post" onSubmit="javascript: if(confirm('Are you sure you want to delete this page?')) return true; else return false; ">
								<input type="hidden" name="p" value="DelPage">
								<input type="hidden" name="id" value="<?php _e($RS_page['id']); ?>">
								<div class="btn-group" role="group"><?php } ?>
									<a href="editpage.php?id=<?php _e($RS_page['id']); ?>" class="btn btn-sm bg-indigo-400"><i class="icon-pencil"></i></a>
									<?php if( _setting_page_edit_ ){ ?>
									<button type="submit" class="btn btn-sm bg-blue-400"><i class="icon-bin"></i></button>
								</div>
							</form><?php } ?>
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