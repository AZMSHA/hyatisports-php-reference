<?php require("config.php"); include("inc/check_session.php");

$_TITLE = "News";
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
			<h4><i class="icon-calendar mr-2"></i> <span class="font-weight-semibold"><?php _e( $_TITLE ); ?></span></h4>
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
			<h5 class="card-title">Add New News</h5>
		</div>
		<div class="card-body">
				
			<form action="mini_process.php" method="post" enctype="multipart/form-data">
				
				<input type="hidden" name="p" value="AddNews">
				
				<div class="form-group row">
					<label for="title" class="col-form-label col-md-2">Title:</label>
					<div class="col-md-10">
						<input id="title" name="title" class="form-control" type="text" required>
					</div>
				</div>

				<div class="form-group row">
					<label class="col-form-label col-md-2">Date (DD/MM/YYYY):</label>
					<div class="col-md-10">
						
						<select id="date_day" name="date_day" class="form-control" style="display:inline-block; width:auto;">
							<?php for ($d=1; $d <= 31; $d++){ ?>
							<option value="<?php _e( $d ); ?>"<?php if( $d == date('j') ){ ?> selected<?php } ?>><?php _e( appendzero($d) ); ?></option>
							<?php } ?>
						</select>

						<select id="date_month" name="date_month" class="form-control" style="display:inline-block; width:auto;">
							<?php for ($m=1; $m <= 12; $m++){ ?>
							<option value="<?php _e( $m ); ?>"<?php if( $m == date('m') ){ ?> selected<?php } ?>><?php _e( appendzero($m) ); ?></option>
							<?php } ?>
						</select>

						<select id="date_year" name="date_year" class="form-control" style="display:inline-block; width:auto;">
							<?php for ($y=2000; $y <= 2030; $y++){ ?>
							<option value="<?php _e( $y ); ?>"<?php if( $y == date('Y') ){ ?> selected<?php } ?>><?php _e( appendzero($y) ); ?></option>
							<?php } ?>
						</select>

					</div>
				</div>

				<?php if( _new_descp_ ){ ?><div class="form-group row">
					<label for="description" class="col-form-label col-md-2">Description:</label>
					<div class="col-md-10">
						<textarea id="description" name="description" class="form-control"></textarea>
					</div>
				</div><?php }else{ ?><input name="description" type="hidden" value=""><?php } ?>

				<?php if( _new_text_ ){ ?><div class="form-group row">
					<label for="text" class="col-form-label col-md-2">Content:</label>
					<div class="col-md-10">
						<textarea id="text" name="text" class="summernote"></textarea>
					</div>
				</div><?php }else{ ?><input name="text" type="hidden" value=""><?php } ?>

				<div class="form-group row">
					<label for="image" class="col-form-label col-md-2">Image:</label>
					<div class="col-md-10">
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="image" name="image">
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
			Total of <?php $news = mysql_result(mysql_query("SELECT count(*) FROM `news`"),0); _e( appendzero($news) ); ?> news found!
		</div>

		<?php
		$top_rank = top_rank("news");
		$bottom_rank = bottom_rank("news");
		$query = mysql_query("SELECT * FROM `news` ORDER BY `rank` ASC");
		if( mysql_num_rows($query) > 0 ){
		?>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				
				<thead>
					<tr>
						<th class="text-center" style="width:50px;">ID</th>
						<th class="text-center" style="width:150px;">Image</th>
						<th>Name</th>
						<th class="text-center" style="width:150px;">Date</th>
						<th class="text-center" style="width:80px;">Display</th>
						<th class="text-center" style="width:100px;">Rank</th>
						<th class="text-center" style="width:100px;">Options</th>
					</tr>
				</thead>

				<tbody>
					
					<?php while($RS = mysql_fetch_array($query)){ ?>
					<tr>

						<td class="text-center"><?php _e( $RS['id'] ); ?></td>
						
						<td class="text-center">
							<?php if( !empty($RS['image']) ){ ?><a href="<?php _e($__url_attimgs.$RS['image']); ?>" target="_blank">
								<img style="max-width:100%;" class="rounded mx-auto d-block img-fluid img-thumbnail" src="<?php _e($__url_attimgs.$RS['image']); ?>" alt="">
							</a><?php } ?>
						</td>
						<td>
							<strong><?php _e( $RS['title'] ); ?></strong>
						</td>

						<td class="text-center">
							<?php _e( appendzero($RS['date_day']) . '-' . appendzero($RS['date_month']) . '-' . appendzero($RS['date_year']) ); ?>
						</td>
						
						<td class="text-center">
							<?php if( $RS['show']=="y" ){ ?>
								<a class="btn btn-sm bg-green-400" href="mini_process.php?p=dnews&id=<?php _e($RS['id']); ?>" title="Click to Hide">Yes</a>
							<?php }else{ ?>
								<a class="btn btn-sm bg-purple-400" href="mini_process.php?p=dnews&id=<?php _e($RS['id']); ?>" title="Click to Show">No</a>
							<?php } ?>
						</td>

						<td class="text-center">
							<div class="btn-group" role="group">


								<?php if( $RS['rank'] == $top_rank ){ ?>
									<button type="button" class="btn btn-sm bg-secondary">
								<?php }else{ ?>
									<a class="btn btn-sm bg-teal-400" href="mini_process.php?p=newsrank&id=<?php _e($RS['id']); ?>&r=up">
								<?php } ?>
									<i class="icon-arrow-up5"></i>
								<?php if( $RS['rank'] == $top_rank ){ ?>
									</button>
								<?php }else{ ?>
									</a>
								<?php } ?>

								<?php if( $RS['rank'] == $bottom_rank ){?>
									<button type="button" class="btn btn-sm bg-secondary">
								<?php }else{ ?>
									<a class="btn btn-sm bg-teal-400" href="mini_process.php?p=newsrank&id=<?php _e($RS['id']); ?>&r=down">
								<?php } ?>
									<i class="icon-arrow-down5"></i>
								<?php if( $RS['rank'] == $bottom_rank ){?>
								 	</button>
								<?php }else{ ?>
									</a>
								<?php } ?>

							</div>
						</td>

						<td class="text-center">
							<form action="mini_process.php" method="post" onSubmit="javascript: if(confirm('Are you sure you want to delete this?')) return true; else return false; ">
								<input type="hidden" name="p" value="DelNews">
								<input type="hidden" name="id" value="<?php _e($RS['id']); ?>">
								<div class="btn-group" role="group">
									<a href="editnews.php?id=<?php _e($RS['id']); ?>" class="btn btn-sm bg-indigo-400"><i class="icon-pencil"></i></a>
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