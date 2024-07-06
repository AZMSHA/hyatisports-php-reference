<?php require("config.php"); include("inc/check_session.php");

$_TITLE = "Site Scripts";
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
			<h4><i class="icon-gear mr-2"></i> <span class="font-weight-semibold"><?php _e( $_TITLE ); ?></span></h4>
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

	<?php
	$option_query = mysql_query("SELECT * FROM `scripts` WHERE `meta`!='hide'");
	if( mysql_num_rows($option_query) > 0 ){
	?>
	<div id="add-new-card" class="card">
		<div class="card-body">
				
			<form action="mini_process.php" method="post" enctype="multipart/form-data">
				
				<input type="hidden" name="p" value="UpdScripts">

				<?php
				while( $RS_option = mysql_fetch_array($option_query) ){
				?>
					<div class="form-group row">
						<label for="<?php _e($RS_option['name']); ?>" class="col-form-label col-md-2"><?php _e($RS_option['title']); ?>:</label>
						<div class="col-md-10">
							<?php if( $RS_option['meta']=='fixed_txt' OR $RS_option['meta']=='txt' ){ ?>
								<textarea id="<?php _e($RS_option['name']); ?>" name="<?php _e($RS_option['name']); ?>" class="form-control"><?php _e($RS_option['value']); ?></textarea>
							<?php }elseif( $RS_option['meta']=='rte' ){ ?>
								<textarea id="<?php _e($RS_option['name']); ?>" name="<?php _e($RS_option['name']); ?>" class="summernote"><?php _e($RS_option['value']); ?></textarea>
							<?php }else{ ?>
								<input id="<?php _e($RS_option['name']); ?>" name="<?php _e($RS_option['name']); ?>" class="form-control" type="text" value="<?php _e($RS_option['value']); ?>">
							<?php } ?>
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
	<?php } ?>

</div>

<?php require("inc/footer.php"); ?>