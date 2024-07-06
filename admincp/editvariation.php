<?php require("config.php"); include("inc/check_session.php");

if( isset($_GET['id']) and is_numeric($_GET['id']) ){
	$id = esc($_GET['id']);
	$variation = get_variation($id);
}else{
	die("Invalide Variation!");
}

$_TITLE = "Edit ".$variation['name'];
$_TOPBAR = true;
$_SIDEBAR = true;

require("inc/header.php");
?>

<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex">
			<h4><i class="icon-grid mr-2"></i> <span class="font-weight-semibold"><?php _e( $_TITLE ); ?></span></h4>
		</div>

	</div>

	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="index.php" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
				<a href="variation.php" class="breadcrumb-item">Variations</a>
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
			<h5 class="card-title">Edit Variation</h5>
		</div>
		<div class="card-body">
				
			<form action="mini_process.php" method="post" enctype="multipart/form-data">

				<input type="hidden" name="p" value="EditVariation">
				<input type="hidden" name="id" value="<?php _e($id); ?>">
				
				<div class="form-group row">
					<label for="name" class="col-form-label col-md-2">Name:</label>
					<div class="col-md-10">
						<input id="name" name="name" class="form-control" type="text" required value="<?php _e($variation['name']); ?>">
					</div>
				</div>

				<div class="form-group row">
					<label for="group_id" class="col-form-label col-md-2">Group:</label>
					<div class="col-md-10">
						<select id="group_id" name="group_id" class="form-control">
							<option value="0" <?php if( $variation['group_id'] == 0 ){ ?> selected<?php } ?>>- none -</option>
							<?php
							$gp_query = mysql_query("SELECT * FROM `variation_groups` ORDER BY `rank` ASC");
							if( mysql_num_rows($gp_query) > 0 ){
								while( $RS_gp = mysql_fetch_array($gp_query) ){
								?>
									<option value="<?php _e( $RS_gp['id'] ); ?>"<?php if( $variation['group_id'] == $RS_gp['id'] ){ ?> selected<?php } ?>><?php _e( $RS_gp['name'] ); ?></option>
								<?php
								}
							}
							?>
						</select>
					</div>
				</div>

				<div class="form-group row">
					<label for="description" class="col-form-label col-md-2">Description:</label>
					<div class="col-md-10">
						<textarea id="description" name="description" class="form-control"><?php _e($variation['description']); ?></textarea>
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