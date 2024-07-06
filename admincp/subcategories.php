<?php require("config.php"); include("inc/check_session.php");

if( isset($_GET['parent']) and is_valide_category(esc($_GET['parent'])) ){
	$parent_id = esc($_GET['parent']);
	$parent = get_cateogry($parent_id);
}else{
	die("Invalide Category!");
}

if( has_products($parent_id) ){ redirect("listprd.php?parent=".$parent_id); }

$_TITLE = $parent['name'];
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
			<h4><i class="icon-list-unordered mr-2"></i> <span class="font-weight-semibold"><?php _e( $parent['name'] ); ?></span></h4>
			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
		</div>

		<div class="header-elements d-none">
			<div class="d-flex justify-content-center">
				<?php if( !has_products($parent_id) ){ ?><button id="add-new" class="btn btn-link btn-float text-default"><i class="icon-plus3 text-primary"></i><span>Add Category</span></button><?php } ?>
				<?php if( !does_category_has_subs($parent_id) ){ ?><a href="addprd.php?parent=<?php _e($parent_id); ?>" class="btn btn-link btn-float text-default"><i class="icon-plus3 text-primary"></i><span>Add Products</span></a><?php } ?>
			</div>
		</div>
	</div>

	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="index.php" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>

				
				<a href="categories.php" class="breadcrumb-item">Categories</a>
				<?php if( get_category($parent_id, 'parent') != 0 ){ ?>
				<a href="subcategories.php?parent=<?php _e(get_category($parent_id, 'parent')); ?>" class="breadcrumb-item"><?php _e( get_category(get_category($parent_id, 'parent'), 'name') ); ?></a>
				<?php } ?>

				<span class="breadcrumb-item active"><?php _e( $parent['name'] ); ?></span>
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
			<h5 class="card-title">Add New Category</h5>
		</div>
		<div class="card-body">
				
			<form action="mini_process.php" method="post" enctype="multipart/form-data">
				
				<input type="hidden" name="p" value="AddCategory">
				<input type="hidden" name="parent" value="<?php _e($parent_id); ?>">
				
				<div class="form-group row">
					<label for="name" class="col-form-label col-md-2">Name:</label>
					<div class="col-md-10">
						<input id="name" name="name" class="form-control" type="text" required>
					</div>
				</div>

				<div class="form-group row">
					<label for="title" class="col-form-label col-md-2">Title:</label>
					<div class="col-md-10">
						<input id="title" name="title" class="form-control" type="text">
					</div>
				</div>

				<div class="form-group row">
					<label for="keywords" class="col-form-label col-md-2">Keywords:</label>
					<div class="col-md-10">
						<textarea id="keywords" name="title" class="form-control"></textarea>
					</div>
				</div>

				<div class="form-group row">
					<label for="description" class="col-form-label col-md-2">Description:</label>
					<div class="col-md-10">
						<textarea id="description" name="title" class="summernote"></textarea>
					</div>
				</div>

				<div class="form-group row">
					<label for="description" class="col-form-label col-md-2">Thumb:</label>
					<div class="col-md-10">
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="img_thumb" name="img_thumb">
							<label class="custom-file-label" for="img_thumb">Choose file...</label>
						</div>
					</div>
				</div>

				<div class="form-group row">
					<label for="description" class="col-form-label col-md-2">Banner:</label>
					<div class="col-md-10">
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="img_banner" name="img_banner">
							<label class="custom-file-label" for="img_banner">Choose file...</label>
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
			Total of <?php $categories = mysql_result(mysql_query("SELECT count(*) FROM `categories` WHERE `parent`=".$parent_id),0); _e( appendzero($categories) ); ?> categories found!
		</div>

		<?php if( $categories > 0 ){
			$category_top_rank = category_top_rank($parent_id);
			$category_bottom_rank = category_bottom_rank($parent_id);
			$cat_query = mysql_query("SELECT * FROM `categories` WHERE `parent`=".$parent_id." ORDER BY `rank` ASC");
		?>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				
				<thead>
					<tr>
						<th class="text-center" style="width:50px;">ID</th>
						<th>Name</th>
						<th class="text-center" style="width:150px;">Contains</th>
						<th class="text-center" style="width:100px;">Display</th>
						<th class="text-center" style="width:100px;">Rank</th>
						<th class="text-center" style="width:100px;">Options</th>
					</tr>
				</thead>

				<tbody>
					
					<?php while( $RS_cat = mysql_fetch_array($cat_query) ){ ?>
					<tr>

						<td class="text-center"><?php _e( $RS_cat['id'] ); ?></td>
						
						<td>
							<?php $link = (has_products($RS_cat['id'])) ? "listprd.php?parent=".$RS_cat['id'] : "subcategories.php?parent=".$RS_cat['id']; ?>
							<a href="<?php _e( $link ); ?>" class="font-weight-bold"><?php _e( $RS_cat['name'] ); ?></a>
						</td>

						<td class="text-center">
							<?php
                            if( does_category_has_subs($RS_cat['id']) ){
								_e( num_of_sub_categories($RS_cat['id']) . " Sub Category(s)" );
							}elseif( has_products($RS_cat['id']) ){
								_e( num_of_products($RS_cat['id']) . " Product(s)" );
							}else{
								_e("- Empty -");
							}
							?>
						</td>
						
						<td class="text-center">
							<?php if( $RS_cat['show']=="y" ){ ?>
								<a class="btn btn-sm bg-green-400" href="mini_process.php?p=dcat&id=<?php _e($RS_cat['id']); ?>" title="Click to Hide">Yes</a>
							<?php }else{ ?>
								<a class="btn btn-sm bg-purple-400" href="mini_process.php?p=dcat&id=<?php _e($RS_cat['id']); ?>" title="Click to Show">No</a>
							<?php } ?>
						</td>

						<td class="text-center">
							<div class="btn-group" role="group">


								<?php if( $RS_cat['rank'] == $category_top_rank ){ ?>
									<button type="button" class="btn btn-sm bg-secondary">
								<?php }else{ ?>
									<a class="btn btn-sm bg-teal-400" href="mini_process.php?p=catrank&id=<?php _e($RS_cat['id']); ?>&r=up">
								<?php } ?>
									<i class="icon-arrow-up5"></i>
								<?php if( $RS_cat['rank'] == $category_top_rank ){ ?>
									</button>
								<?php }else{ ?>
									</a>
								<?php } ?>

								<?php if( $RS_cat['rank'] == $category_bottom_rank ){ ?>
									<button type="button" class="btn btn-sm bg-secondary">
								<?php }else{ ?>
									<a class="btn btn-sm bg-teal-400" href="mini_process.php?p=catrank&id=<?php _e($RS_cat['id']); ?>&r=down">
								<?php } ?>
									<i class="icon-arrow-down5"></i>
								<?php if( $RS_cat['rank'] == $category_bottom_rank ){ ?>
								 	</button>
								<?php }else{ ?>
									</a>
								<?php } ?>

							</div>
						</td>

						<td class="text-center">
							<form action="mini_process.php" method="post" onsubmit="javascript: if(confirm('Are you sure you want to delete this category?')) return true; else return false; ">
								<input type="hidden" name="p" value="DelCategory">
								<input type="hidden" name="id" value="<?php _e($RS_cat['id']); ?>">
								<div class="btn-group" role="group">
									<a href="editcategory.php?id=<?php _e($RS_cat['id']); ?>" class="btn btn-sm bg-indigo-400"><i class="icon-pencil"></i></a>
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
	function ssb_form(the_form){
		alert('aaaaa');
		/*
		if(confirm('Are you sure that you want to delete?') ){
			$("#"+the_form).submit();
		}else{
			event.preventDefault();
		}*/
	}
});
</script>

<?php require("inc/footer.php"); ?>