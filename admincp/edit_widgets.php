<?php require("config.php"); include("inc/check_session.php"); require("inc/widget_setting.php");

if( isset($_GET['id']) and get_widget($_GET['id']) ){

	$widget_id = esc($_GET['id']);
	$widget = get_widget($widget_id);
	$settings = $widgets[$widget['area_id']];

}else{
	die("Invalide Widget!");
}

$_TITLE = "Edit Widget ".$widget['name'];
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
			<h4><i class="icon-versions mr-2"></i> <span class="font-weight-semibold"><?php _e( $_TITLE ); ?></span></h4>
		</div>
	</div>

	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="index.php" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
				<a href="widget_areas.php" class="breadcrumb-item">Widget Aears</a>
				<a href="widgets.php?area=<?php _e( $widget['area_id'] ); ?>" class="breadcrumb-item">Widgets for <?php _e( get_widget_areas($widget['area_id'], "name") ); ?></a>
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

	<div id="edit-card" class="card">
		<div class="card-header header-elements-inline">
			<h5 class="card-title"><?php _e( $_TITLE ); ?></h5>
		</div>
		<div class="card-body">
				
			<form action="mini_process.php" method="post" enctype="multipart/form-data">

				<input type="hidden" name="p" value="EditWidget">
				<input type="hidden" name="id" value="<?php _e($widget['id']); ?>">
				<input type="hidden" name="area_id" value="<?php _e($widget['area_id']); ?>">
				
				<div class="form-group row">
					<label for="name" class="col-form-label col-md-2">Name/Title:</label>
					<div class="col-md-10">
						<input id="name" name="name" class="form-control" type="text" value="<?php _e( $widget['name'] ); ?>" required>
					</div>
				</div>

				<?php $fl = 1; foreach ($settings as $field){ ?>

					<?php if( $field[1] == 'img' and !empty( $widget['field_'.$fl] ) ){ ?>
						<div class="form-group row">
							<label class="col-form-label col-md-2">Old <?php _e( $field[0] ); ?> Image:</label>
							<div class="col-md-10">
								<div class="imgcontainer">
									<img class="mw-100 mx-auto" src="<?php _e($__url_attimgs.$widget['field_'.$fl]); ?>">
								</div>
							</div>
						</div>
					<?php } ?>

					<div class="form-group row">
						<label for="<?php _e( 'field_'.$fl ); ?>" class="col-form-label col-md-2"><?php _e( $field[0] ); ?>:</label>
						<div class="col-md-10">
								
							<?php if( $field[1] == 'txt' ){ ?>
								<textarea id="<?php _e( 'field_'.$fl ); ?>" name="<?php _e( 'field_'.$fl ); ?>" class="form-control"><?php _e( $widget['field_'.$fl] ); ?></textarea>
							<?php }elseif( $field[1] == 'rte' ){ ?>
								<textarea id="<?php _e( 'field_'.$fl ); ?>" name="<?php _e( 'field_'.$fl ); ?>" class="summernote"><?php _e( $widget['field_'.$fl] ); ?></textarea>
							<?php }elseif( $field[1] == 'img' ){ ?>
								<div class="custom-file">
									<input type="file" class="custom-file-input" id="<?php _e( 'field_'.$fl ); ?>" name="<?php _e( 'field_'.$fl ); ?>">
									<label class="custom-file-label" for="<?php _e( 'field_'.$fl ); ?>">Choose file...</label>
								</div>
							<?php }else{ ?>
								<input id="<?php _e( 'field_'.$fl ); ?>" name="<?php _e( 'field_'.$fl ); ?>" class="form-control" type="text" value="<?php _e( $widget['field_'.$fl] ); ?>">
							<?php } ?>
							
						</div>
					</div>

				<?php $fl++; } ?>

				<div class="form-group row">
					<div class="col-md-2"></div>
					<div class="col-md-10"><button type="submit" class="btn btn-primary"><i class="icon-floppy-disk mr-2"></i> Save</button></div>
				</div>

			</form>

		</div>
	</div>

</div>

<?php require("inc/footer.php"); ?>