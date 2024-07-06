<?php require("config.php"); include("inc/check_session.php"); require("inc/widget_setting.php");

if( isset($_GET['area']) ){

	$area_id = esc($_GET['area']);
	$area = get_widget_areas($area_id);
	$settings = $widgets[$area_id];

}else{
	die("Invalide Widget Area!");
}

$_TITLE = "Widgets for ".$area['name'];
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
				<a href="widget_areas.php" class="breadcrumb-item">Widget Aears</a>
				<span class="breadcrumb-item active">Widgets for <?php _e( $area['name'] ); ?></span>
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
			<h5 class="card-title">Add New Widget</h5>
		</div>
		<div class="card-body">
				
			<form action="mini_process.php" method="post" enctype="multipart/form-data">

				<input type="hidden" name="p" value="AddWidget">
				<input type="hidden" name="area_id" value="<?php _e($area_id); ?>">
				
				<div class="form-group row">
					<label for="name" class="col-form-label col-md-2">Name/Title:</label>
					<div class="col-md-10">
						<input id="name" name="name" class="form-control" type="text" required>
					</div>
				</div>

				<?php $fl = 1; foreach ($settings as $field){ ?>

					<div class="form-group row">
						<label for="<?php _e( 'field_'.$fl ); ?>" class="col-form-label col-md-2"><?php _e( $field[0] ); ?>:</label>
						<div class="col-md-10">
								
							<?php if( $field[1] == 'txt' ){ ?>
								<textarea id="<?php _e( 'field_'.$fl ); ?>" name="<?php _e( 'field_'.$fl ); ?>" class="form-control"></textarea>
							<?php }elseif( $field[1] == 'rte' ){ ?>
								<textarea id="<?php _e( 'field_'.$fl ); ?>" name="<?php _e( 'field_'.$fl ); ?>" class="summernote"></textarea>
							<?php }elseif( $field[1] == 'img' ){ ?>
								<div class="custom-file">
									<input type="file" class="custom-file-input" id="<?php _e( 'field_'.$fl ); ?>" name="<?php _e( 'field_'.$fl ); ?>">
									<label class="custom-file-label" for="<?php _e( 'field_'.$fl ); ?>">Choose file...</label>
								</div>
							<?php }else{ ?>
								<input id="<?php _e( 'field_'.$fl ); ?>" name="<?php _e( 'field_'.$fl ); ?>" class="form-control" type="text">
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

	<div class="card">

		<div class="card-body">
			Total of <?php $widgets = mysql_result(mysql_query("SELECT count(*) FROM `widgets` WHERE `area_id`=".$area_id),0); _e( appendzero($widgets) ); ?> widgets found!
		</div>

		<?php
		$top_rank = top_rank("widgets", "`area_id`=".$area_id);
        $bottom_rank = bottom_rank("widgets", "`area_id`=".$area_id);
        $query = mysql_query("SELECT * FROM `widgets` WHERE `area_id`=".$area_id." ORDER BY `rank` ASC");
        if( mysql_num_rows($query) > 0 ){
		?>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				
				<thead>
					<tr>
						<th class="text-center" style="width:50px;">ID</th>
						<th>Name / Title</th>
						<th class="text-center" style="width:100px;">Rank</th>
						<th class="text-center" style="width:100px;">Options</th>
					</tr>
				</thead>

				<tbody>
					
					<?php while($RS_widget = mysql_fetch_array($query)){ ?>
					<tr>

						<td class="text-center"><?php _e( $RS_widget['id'] ); ?></td>
						
						<td>
							<strong><?php _e( $RS_widget['name'] ); ?></strong>
							<?php if( !empty( $RS_widget['link'] ) ){ ?><br /><a href="<?php _e( $RS_widget['link'] ); ?>" title="<?php _e( $RS_widget['link'] ); ?>" target="_blank"><- link -></a><?php } ?>
						</td>

						<td class="text-center">
							<div class="btn-group" role="group">


								<?php if( $RS_widget['rank'] == $top_rank ){ ?>
									<button type="button" class="btn btn-sm bg-secondary">
								<?php }else{ ?>
									<a class="btn btn-sm bg-teal-400" href="mini_process.php?p=widgetrank&id=<?php _e($RS_widget['id']); ?>&area_id=<?php _e($area_id); ?>&r=up">
								<?php } ?>
									<i class="icon-arrow-up5"></i>
								<?php if( $RS_widget['rank'] == $top_rank ){ ?>
									</button>
								<?php }else{ ?>
									</a>
								<?php } ?>

								<?php if( $RS_widget['rank'] == $bottom_rank ){ ?>
									<button type="button" class="btn btn-sm bg-secondary">
								<?php }else{ ?>
									<a class="btn btn-sm bg-teal-400" href="mini_process.php?p=widgetrank&id=<?php _e($RS_widget['id']); ?>&area_id=<?php _e($area_id); ?>&r=down">
								<?php } ?>
									<i class="icon-arrow-down5"></i>
								<?php if( $RS_widget['rank'] == $bottom_rank ){ ?>
								 	</button>
								<?php }else{ ?>
									</a>
								<?php } ?>

							</div>
						</td>

						<td class="text-center">
							<form action="mini_process.php" method="post" onSubmit="javascript: if(confirm('Are you sure you want to delete this?')) return true; else return false; ">
								<input type="hidden" name="p" value="DelWidget">
								<input type="hidden" name="id" value="<?php _e($RS_widget['id']); ?>">
								<input type="hidden" name="area_id" value="<?php _e($area_id); ?>">
								<div class="btn-group" role="group">
									<a href="edit_widgets.php?id=<?php _e($RS_widget['id']); ?>" class="btn btn-sm bg-indigo-400"><i class="icon-pencil"></i></a>
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