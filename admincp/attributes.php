<?php require("config.php"); include("inc/check_session.php");

if( isset($_GET['variation']) and get_variation(esc($_GET['variation'])) ){
	$variation_id = esc($_GET['variation']);
	$variation = get_variation($variation_id);
}else{
	redirect("variation.php");
}

$_TITLE = "Attributes for ".$variation['name'];
$_TOPBAR = true;
$_SIDEBAR = true;

require("inc/header.php");
?>

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

	<div id="add-new-card" class="card" style="display:none;">
		<div class="card-header header-elements-inline">
			<h5 class="card-title">Add New Attribute</h5>
		</div>
		<div class="card-body">
				
			<form action="mini_process.php" method="post" enctype="multipart/form-data">

				<input type="hidden" name="p" value="AddAttribute">
				<input type="hidden" name="variation" value="<?php _e($variation_id); ?>">
				
				<div class="form-group row">
					<label for="name" class="col-form-label col-md-2">Name:</label>
					<div class="col-md-10">
						<input id="name" name="name" class="form-control" type="text" required>
					</div>
				</div>

				<div class="form-group row">
					<label for="x_data" class="col-form-label col-md-2">Data:</label>
					<div class="col-md-10">
						<textarea id="x_data" name="x_data" class="form-control"></textarea>
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
			<h5 class="card-title">Edit Attribute</h5>
		</div>
		<div class="card-body">
				
			<form action="mini_process.php" method="post" enctype="multipart/form-data">

				<input type="hidden" name="p" value="EditAttribute">
				<input type="hidden" id="edit_id" name="id" value="">
				<input type="hidden" name="variation" value="<?php _e($variation_id); ?>">
				
				<div class="form-group row">
					<label for="edit_name" class="col-form-label col-md-2">Name:</label>
					<div class="col-md-10">
						<input id="edit_name" name="name" class="form-control" type="text" required value="">
					</div>
				</div>

				<div class="form-group row">
					<label for="edit_x_data" class="col-form-label col-md-2">Data:</label>
					<div class="col-md-10">
						<textarea id="edit_x_data" name="x_data" class="form-control"></textarea>
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
			Total of <?php $attributes = mysql_result(mysql_query("SELECT count(*) FROM `attributes` WHERE `variation_id`=".$variation_id),0); _e( appendzero($attributes) ); ?> attributes found!
		</div>

		<?php
		$top_rank = top_rank("attributes", "`variation_id`=".$variation_id);
		$bottom_rank = bottom_rank("attributes", "`variation_id`=".$variation_id);
		$attribute_query = mysql_query("SELECT * FROM `attributes` WHERE `variation_id`=".$variation_id." ORDER BY `rank` ASC");
		if( mysql_num_rows($attribute_query) > 0 ){
		?>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				
				<thead>
					<tr>
						<th class="text-center" style="width:50px;">ID</th>
						<th>Name</th>
						<th class="text-center" style="width:150px;">xData</th>
						<th class="text-center" style="width:100px;">Rank</th>
						<th class="text-center" style="width:100px;">Options</th>
					</tr>
				</thead>

				<tbody>
					
					<?php while($RS_attribute = mysql_fetch_array($attribute_query)){ ?>
					<tr>

						<td class="text-center"><?php _e( $RS_attribute['id'] ); ?></td>
						
						<td>
							<strong><?php _e( $RS_attribute['name'] ); ?></strong>
						</td>

						<td class="text-center">
							<?php _e( $RS_attribute['x_data'] ); ?>
						</td>

						<td class="text-center">
							<div class="btn-group" role="group">


								<?php if( $RS_attribute['rank'] == $top_rank ){ ?>
									<button type="button" class="btn btn-sm bg-secondary">
								<?php }else{ ?>
									<a class="btn btn-sm bg-teal-400" href="mini_process.php?p=attributerank&variation=<?php _e($variation_id); ?>&id=<?php _e($RS_attribute['id']); ?>&r=up">
								<?php } ?>
									<i class="icon-arrow-up5"></i>
								<?php if( $RS_attribute['rank'] == $top_rank ){ ?>
									</button>
								<?php }else{ ?>
									</a>
								<?php } ?>

								<?php if( $RS_attribute['rank'] == $bottom_rank ){ ?>
									<button type="button" class="btn btn-sm bg-secondary">
								<?php }else{ ?>
									<a class="btn btn-sm bg-teal-400" href="mini_process.php?p=attributerank&variation=<?php _e($variation_id); ?>&id=<?php _e($RS_attribute['id']); ?>&r=down">
								<?php } ?>
									<i class="icon-arrow-down5"></i>
								<?php if( $RS_attribute['rank'] == $bottom_rank ){ ?>
								 	</button>
								<?php }else{ ?>
									</a>
								<?php } ?>

							</div>
						</td>

						<td class="text-center">
							<form action="mini_process.php" method="post" onSubmit="javascript: if(confirm('Are you sure you want to delete this?')) return true; else return false; ">
								<input type="hidden" name="p" value="DelAttribute">
								<input type="hidden" name="variation" value="<?php _e($variation_id); ?>">
								<input type="hidden" name="id" value="<?php _e($RS_attribute['id']); ?>">
								<div class="btn-group" role="group">
									<button onclick="javascript: edit_card(<?php _e( $RS_attribute['id'] ); ?>, '<?php _e( $RS_attribute['name'] ); ?>', '<?php _e( $RS_attribute['x_data'] ); ?>');" type="button" class="btn btn-sm bg-indigo-400"><i class="icon-pencil"></i></button>
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

function edit_card(id, name, x_data){
	$("#edit_id").val(id);
	$("#edit_name").val(name);
	$("#edit_x_data").val(x_data);
	$("#edit-card").show();
}

$(document).ready(function(){
	$("#add-new").click(function(){
	    $("#add-new-card").toggle();
	});
});
</script>

<?php require("inc/footer.php"); ?>