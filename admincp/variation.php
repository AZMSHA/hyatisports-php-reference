<?php require("config.php"); include("inc/check_session.php");

$_TITLE = "Variations";
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
			<h5 class="card-title">Add New Variation</h5>
		</div>
		<div class="card-body">
				
			<form action="mini_process.php" method="post" enctype="multipart/form-data">
				
				<input type="hidden" name="p" value="AddVariation">
				
				<div class="form-group row">
					<label for="name" class="col-form-label col-md-2">Name:</label>
					<div class="col-md-10">
						<input id="name" name="name" class="form-control" type="text" required>
					</div>
				</div>

				<div class="form-group row">
					<label for="group_id" class="col-form-label col-md-2">Group:</label>
					<div class="col-md-10">
						<select id="group_id" name="group_id" class="form-control">
							<option value="0" selected>- none -</option>
							<?php
							$gp_query = mysql_query("SELECT * FROM `variation_groups` ORDER BY `rank` ASC");
							if( mysql_num_rows($gp_query) > 0 ){
								while( $RS_gp = mysql_fetch_array($gp_query) ){
								?>
									<option value="<?php _e( $RS_gp['id'] ); ?>"><?php _e( $RS_gp['name'] ); ?></option>
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
						<textarea id="description" name="description" class="form-control"></textarea>
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
			Total of <?php $variations = mysql_result(mysql_query("SELECT count(*) FROM `variations`"),0); _e( appendzero($variations) ); ?> variations found!
		</div>

		<?php
		$top_rank = top_rank("variations");
		$bottom_rank = bottom_rank("variations");
		$variation_query = mysql_query("SELECT * FROM `variations` ORDER BY `rank` ASC");
		if( mysql_num_rows($variation_query) > 0 ){
		?>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				
				<thead>
					<tr>
						<th class="text-center" style="width:50px;">ID</th>
						<th>Name</th>
						<th class="text-center" style="width:150px;">Group</th>
						<th class="text-center" style="width:150px;">Contains</th>
						<th class="text-center" style="width:100px;">Rank</th>
						<th class="text-center" style="width:100px;">Options</th>
					</tr>
				</thead>

				<tbody>
					
					<?php while($RS_variation = mysql_fetch_array($variation_query)){ ?>
					<tr>

						<td class="text-center"><?php _e( $RS_variation['id'] ); ?></td>
						
						<td>
							<a href="attributes.php?variation=<?php _e($RS_variation['id']); ?>" class="font-weight-bold"><?php _e( $RS_variation['name'] ); ?></a>
						</td>

						<td class="text-center">
							<?php if( $RS_variation['group_id'] == 0 ){ _e("- none -"); }else{ echo _e(get_group($RS_variation['group_id'], "name")); } ?>
						</td>
						
						<td class="text-center">
							(<?php _e( num("attributes", "`variation_id`=".$RS_variation['id']) ); ?>) Attributes
						</td>

						<td class="text-center">
							<div class="btn-group" role="group">


								<?php if( $RS_variation['rank'] == $top_rank ){ ?>
									<button type="button" class="btn btn-sm bg-secondary">
								<?php }else{ ?>
									<a class="btn btn-sm bg-teal-400" href="mini_process.php?p=variationrank&id=<?php _e($RS_variation['id']); ?>&r=up">
								<?php } ?>
									<i class="icon-arrow-up5"></i>
								<?php if( $RS_variation['rank'] == $top_rank ){ ?>
									</button>
								<?php }else{ ?>
									</a>
								<?php } ?>

								<?php if( $RS_variation['rank'] == $bottom_rank ){?>
									<button type="button" class="btn btn-sm bg-secondary">
								<?php }else{ ?>
									<a class="btn btn-sm bg-teal-400" href="mini_process.php?p=variationrank&id=<?php _e($RS_variation['id']); ?>&r=down">
								<?php } ?>
									<i class="icon-arrow-down5"></i>
								<?php if( $RS_variation['rank'] == $bottom_rank ){?>
								 	</button>
								<?php }else{ ?>
									</a>
								<?php } ?>

							</div>
						</td>

						<td class="text-center">
							<form action="mini_process.php" method="post" onSubmit="javascript: if(confirm('Are you sure you want to delete this?')) return true; else return false; ">
								<input type="hidden" name="p" value="DelVariation">
								<input type="hidden" name="id" value="<?php _e($RS_variation['id']); ?>">
								<div class="btn-group" role="group">
									<a href="editvariation.php?id=<?php _e($RS_variation['id']); ?>" class="btn btn-sm bg-indigo-400"><i class="icon-pencil"></i></a>
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