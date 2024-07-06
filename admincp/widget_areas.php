<?php require("config.php"); include("inc/check_session.php");

$_TITLE = "Widget Area";
$_TOPBAR = true;
$_SIDEBAR = true;

require("inc/header.php");
require("inc/widget_setting.php");
?>

<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex">
			<h4><i class="icon-versions mr-2"></i> <span class="font-weight-semibold"><?php _e( $_TITLE ); ?></span></h4>
			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
		</div>

		<?php /*<div class="header-elements d-none">
			<div class="d-flex justify-content-center">
				<button id="add-new" class="btn btn-link btn-float text-default"><i class="icon-plus3 text-primary"></i><span>Add New</span></button>
			</div>
		</div>*/ ?>
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

	<?php /*<div id="add-new-card" class="card" style="display:none;">
		<div class="card-header header-elements-inline">
			<h5 class="card-title">Add Widget Area</h5>
		</div>
		<div class="card-body">
				
			<form action="mini_process.php" method="post" enctype="multipart/form-data">

				<input type="hidden" name="p" value="AddWidgetArea">
				
				<div class="form-group row">
					<label for="name" class="col-form-label col-md-2">Name:</label>
					<div class="col-md-10">
						<input id="name" name="name" class="form-control" type="text" required>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-md-2"></div>
					<div class="col-md-10"><button type="submit" id="edit" class="btn btn-primary"><i class="icon-floppy-disk mr-2"></i> Save</button></div>
				</div>

			</form>

		</div>
	</div>

	<div id="edit-card" class="card" style="display:none;">
		<div class="card-header header-elements-inline">
			<h5 class="card-title">Edit Widget Area</h5>
		</div>
		<div class="card-body">
				
			<form action="mini_process.php" method="post" enctype="multipart/form-data">

				<input type="hidden" name="p" value="UpdWidgetArea">
				<input id="edit_id" type="hidden" name="id" value="0">
				
				<div class="form-group row">
					<label for="edit_name" class="col-form-label col-md-2">Name:</label>
					<div class="col-md-10">
						<input id="edit_name" name="name" class="form-control" type="text" value="" required>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-md-2"></div>
					<div class="col-md-10"><button type="submit" id="edit" class="btn btn-primary"><i class="icon-floppy-disk mr-2"></i> Save</button></div>
				</div>

			</form>

		</div>
	</div>*/ ?>

	<div class="card">

		<div class="card-body">
			Total of <?php $widget_areas = mysql_result(mysql_query("SELECT count(*) FROM `widget_areas`"),0); _e( appendzero($widget_areas) ); ?> widget areas found!
		</div>

		<?php
		$widget_areas_query = mysql_query("SELECT * FROM `widget_areas`");
		if( mysql_num_rows($widget_areas_query) > 0 ){
		?>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				
				<thead>
					<tr>
						<th class="text-center" style="width:50px;">ID</th>
						<th>Name</th>
						<?php /*<th class="text-center" style="width:100px;">Options</th>*/ ?>
					</tr>
				</thead>

				<tbody>
					
					<?php while($widget_area = mysql_fetch_array($widget_areas_query)){
					if( has_widget_setting($widget_area['id']) ){ ?>
					<tr>

						<td class="text-center"><?php _e( $widget_area['id'] ); ?></td>
						
						<td>
							<a href="widgets.php?area=<?php _e($widget_area['id']); ?>" class="font-weight-bold"><?php _e( $widget_area['name'] ); ?></a>
						</td>

						<?php /*<td class="text-center">
							<form action="mini_process.php" method="post" onSubmit="javascript: if(confirm('Are you sure you want to delete this?')) return true; else return false; ">
								<input type="hidden" name="p" value="DelWidgetArea">
								<input type="hidden" name="id" value="<?php _e($widget_area['id']); ?>">
								<div class="btn-group" role="group">
									<button onclick="javascript: edit_card(<?php _e($widget_area['id']); ?>, '<?php _e( $widget_area['name'] ); ?>');" type="button" class="btn btn-sm bg-indigo-400"><i class="icon-pencil"></i></button>
									<button type="submit" class="btn btn-sm bg-blue-400"><i class="icon-bin"></i></button>
								</div>
							</form>
						</td>*/ ?>

					</tr>
					<?php } } ?>

				</tbody>

			</table>
		</div>
		<?php } ?>

	</div>

</div>

<script type="text/javascript">

function edit_card(id, name){
	$("#edit_id").val(id);
	$("#edit_name").val(name);
	$("#edit-card").show();
}

$(document).ready(function(){
	$("#add-new").click(function(){
	    $("#add-new-card").toggle();
	});
});

</script>

<?php require("inc/footer.php"); ?>