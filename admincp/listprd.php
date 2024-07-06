<?php require("config.php"); include("inc/check_session.php");

if( isset($_GET['parent']) and is_valide_category(esc($_GET['parent'])) ){
	$parent_id = esc($_GET['parent']);
	$parent = get_cateogry($parent_id);
}else{
	die("Invalide Category!");
}

if( !has_products($parent_id) ){ redirect("subcategories.php?parent=".$parent_id); }

$_TITLE = "Products for ".$parent['name'];
$_TOPBAR = true;
$_SIDEBAR = true;

require("inc/header.php");
?>

<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex">
			<h4><i class="icon-box-add mr-2"></i> <span class="font-weight-semibold"><?php _e( $_TITLE ); ?></span></h4>
			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
		</div>

		<div class="header-elements d-none">
			<div class="d-flex justify-content-center">
				<a href="addprd.php?parent=<?php _e($parent_id); ?>" class="btn btn-link btn-float text-default"><i class="icon-plus3 text-primary"></i><span>Add Products</span></a>
			</div>
		</div>
	</div>

	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="index.php" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
				
				<a href="categories.php" class="breadcrumb-item">Categories</a>
				<?php if( get_category($parent_id, 'parent') != 0 ){ ?>
				<a href="subcategories.php?parent=<?php _e(get_category($parent_id, 'parent')); ?>" class="breadcrumb-item"><?php _e( get_category($parent_id, 'name') ); ?></a>
				<?php } ?>

				<span class="breadcrumb-item active">Products</span>

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

		<div class="card-body">
			Total of <?php $products = mysql_result(mysql_query("SELECT count(*) FROM `products` WHERE `parent`=".$parent_id),0); _e( appendzero($products) ); ?> products found!
		</div>

		<?php if( $products > 0 ){ ?>
			
			<?php if( (defined('_setting_prdmove_') and _setting_prdmove_) or (defined('_setting_prddel_') and _setting_prddel_) ){ ?>
			<form id="bulkactionform" action="bulkaction.php" method="post">
			<input type="hidden" name="parent" value="<?php _e( $parent_id ); ?>">
			<?php } ?>

		<?php
			$product_top_rank = product_top_rank($parent_id);
			$product_bottom_rank = product_bottom_rank($parent_id);
			$prd_query = mysql_query("SELECT * FROM `products` WHERE `parent`=".$parent_id." ORDER BY `rank` ASC");
		?>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				
				<thead>
					<tr>

						<?php if( (defined('_setting_prdmove_') and _setting_prdmove_) or (defined('_setting_prddel_') and _setting_prddel_) ){ ?>
						<th class="text-center" style="width:50px;">
							<input type="checkbox" name="check_all">
						</th>
						<?php } ?>

						<th>Art No & Name</th>
						
						<?php if( _setting_price_ ){ ?><th class="text-center" style="width:100px;">Price</th><?php } ?>

						<?php if( _setting_isgrp_01_ ){ ?><th class="text-center" style="width:80px;"><?php _e( _setting_isgrp_01_ ); ?></th><?php } ?>
						<?php if( _setting_isgrp_02_ ){ ?><th class="text-center" style="width:80px;"><?php _e( _setting_isgrp_02_ ); ?></th><?php } ?>
						<?php if( _setting_isgrp_03_ ){ ?><th class="text-center" style="width:80px;"><?php _e( _setting_isgrp_03_ ); ?></th><?php } ?>

						<?php if( _setting_prd_gallery_ ){ ?><th class="text-center" style="width:80px;">Gallery</th><?php } ?>

						<th class="text-center" style="width:80px;">Display</th>
						<th class="text-center" style="width:100px;">Rank</th>
						<th class="text-center" style="width:180px;">Options</th>
					</tr>
				</thead>

				<tbody>
					
					<?php while($RS_prd = mysql_fetch_array($prd_query)){ ?>
					<tr>

						<?php if( (defined('_setting_prdmove_') and _setting_prdmove_) or (defined('_setting_prddel_') and _setting_prddel_) ){ ?><td align="center"><input class="idRow" type="checkbox" name="bulkprd[]" value="<?php _e( $RS_prd['id'] ); ?>"></td><?php } ?>
						
						<td>
							<a href="<?php _e( generate_prd_link($RS_prd['id']) ); ?>" target="_blank" class="font-weight-bold">
								<?php _e( $RS_prd['art_no'] ); ?><br />
								<?php _e( $RS_prd['name'] ); ?>
							</a>
						</td>

						<?php if( _setting_price_ ){ ?><th class="text-center"><?php _e( $RS_prd['price'] ); ?></th><?php } ?>

						<?php if( _setting_isgrp_01_ ){ ?>
							<th class="text-center">
								<?php if( $RS_prd['grp_fet']=="y" ){ ?>
									<a class="btn btn-sm bg-green-400" href="mini_process.php?p=is_fet&id=<?php _e($RS_prd['id']); ?>" title="Click to Remove">Yes</a>
								<?php }else{ ?>
									<a class="btn btn-sm bg-purple-400" href="mini_process.php?p=is_fet&id=<?php _e($RS_prd['id']); ?>" title="Click to Add">No</a>
								<?php } ?>
							</th>
						<?php } ?>

						<?php if( _setting_isgrp_02_ ){ ?>
							<th class="text-center">
								<?php if( $RS_prd['grp_new']=="y" ){ ?>
									<a class="btn btn-sm bg-green-400" href="mini_process.php?p=is_new&id=<?php _e($RS_prd['id']); ?>" title="Click to Remove">Yes</a>
								<?php }else{ ?>
									<a class="btn btn-sm bg-purple-400" href="mini_process.php?p=is_new&id=<?php _e($RS_prd['id']); ?>" title="Click to Add">No</a>
								<?php } ?>
							</th>
						<?php } ?>

						<?php if( _setting_isgrp_03_ ){ ?>
							<th class="text-center">
								<?php if( $RS_prd['grp_hot']=="y" ){ ?>
									<a class="btn btn-sm bg-green-400" href="mini_process.php?p=is_hot&id=<?php _e($RS_prd['id']); ?>" title="Click to Remove">Yes</a>
								<?php }else{ ?>
									<a class="btn btn-sm bg-purple-400" href="mini_process.php?p=is_hot&id=<?php _e($RS_prd['id']); ?>" title="Click to Add">No</a>
								<?php } ?>
							</th>
						<?php } ?>

						<?php if( _setting_prd_gallery_ ){ ?><td class="text-center"><a class="btn btn-sm bg-pink-400" href="gallery.php?product=<?php _e($RS_prd['id']); ?>">Gallery</a></td><?php } ?>
						
						<td class="text-center">
							<?php if( $RS_prd['show']=="y" ){ ?>
								<a class="btn btn-sm bg-green-400" href="mini_process.php?p=dprd&id=<?php _e($RS_prd['id']); ?>" title="Click to Hide">Yes</a>
							<?php }else{ ?>
								<a class="btn btn-sm bg-purple-400" href="mini_process.php?p=dprd&id=<?php _e($RS_prd['id']); ?>" title="Click to Show">No</a>
							<?php } ?>
						</td>

						<td class="text-center">
							<div class="btn-group" role="group">


								<?php if( $RS_prd['rank'] == $product_top_rank ){ ?>
									<button type="button" class="btn btn-sm bg-secondary">
								<?php }else{ ?>
									<a class="btn btn-sm bg-teal-400" href="mini_process.php?p=prdrank&id=<?php _e($RS_prd['id']); ?>&r=up">
								<?php } ?>
									<i class="icon-arrow-up5"></i>
								<?php if( $RS_prd['rank'] == $product_top_rank ){ ?>
									</button>
								<?php }else{ ?>
									</a>
								<?php } ?>

								<?php if( $RS_prd['rank'] == $product_bottom_rank ){ ?>
									<button type="button" class="btn btn-sm bg-secondary">
								<?php }else{ ?>
									<a class="btn btn-sm bg-teal-400" href="mini_process.php?p=prdrank&id=<?php _e($RS_prd['id']); ?>&r=down">
								<?php } ?>
									<i class="icon-arrow-down5"></i>
								<?php if( $RS_prd['rank'] == $product_bottom_rank ){ ?>
								 	</button>
								<?php }else{ ?>
									</a>
								<?php } ?>

							</div>
						</td>

						<td class="text-center">
							<form action="mini_process.php" method="post" onsubmit="javascript: if(confirm('Are you sure you want to delete this?')) return true; else return false; ">
								<input type="hidden" name="p" value="DelPrd">
								<input type="hidden" name="id" value="<?php _e($RS_prd['id']); ?>">
								<div class="btn-group" role="group">
									<a title="Copy" href="copyprd.php?id=<?php _e($RS_prd['id']); ?>" class="btn btn-sm bg-orange-400" target="_blank"><i class="icon-copy"></i></a>
									<a href="editprd.php?id=<?php _e($RS_prd['id']); ?>" class="btn btn-sm bg-indigo-400"><i class="icon-pencil"></i></a>
									<a onclick="javascript: if(confirm('Are you sure you want to delete this product?')) return true; else return false;" href="mini_process.php?p=DelPrd&id=<?php _e($RS_prd['id']); ?>" class="btn btn-sm bg-blue-400"><i class="icon-bin"></i></i></a>									
								</div>
							</form>
						</td>

					</tr>
					<?php } ?>

				</tbody>

				<tfoot>
					<tr>
						<td colspan="100%">
							
							<select id="bulk_action_select" class="form-control" name="bulk_action" style="width:auto;">
								<option value="0" selected>- Bulk Action -</option>
								<?php if( defined('_setting_prddel_') and _setting_prddel_ ){ ?><option value="delete">Delete Selected Products</option><?php } ?>
								<?php if( defined('_setting_prdmove_') and _setting_prdmove_ ){ ?><option value="move">Move Select Products</option><?php } ?>
							</select>

							<?php if( defined('_setting_prdmove_') and _setting_prdmove_ ){ ?>
							<div id="bulk_new_parent" style="display:none; margin-top:10px;">
								<b>Move To:</b>
								<select id="new_parent_select" name="new_parent" class="form-control" style="margin-top:10px; width:auto;">
									<?php
									$query = mysql_query("SELECT * FROM `categories` WHERE `parent`=0 ORDER BY `rank`");
									if( mysql_num_rows($query) ){
										while( $RS_m_cat = mysql_fetch_array($query) ){
											if( does_category_has_subs($RS_m_cat['id']) ){
												_e('<optgroup label="'.$RS_m_cat['name'].'">');
											}else{
												_e('<option value="'.$RS_m_cat['id'].'"');
												if( $RS_m_cat['id'] == $parent_id ){ _e(" selected"); }
												_e('>'.$RS_m_cat['name'].'</option>');
											}

											if( does_category_has_subs($RS_m_cat['id']) ){ print_category_subs_options_1($RS_m_cat['id'], 1, $parent_id); }

											if( does_category_has_subs($RS_m_cat['id']) ){
												_e('</optgroup>');
											}

										}
									}
									?>
								</select>

							</div>
							<?php } ?>



						</td>
					</tr>
				</tfoot>

			</table>
		</div>

			<?php if( (defined('_setting_prdmove_') and _setting_prdmove_) or (defined('_setting_prddel_') and _setting_prddel_) ){ ?>

			<script type="text/javascript">
			$(document).ready(function(){

				$(document).on('change','input[name="check_all"]',function() {
					$('.idRow').prop("checked" , this.checked);
				});

				$('#bulk_action_select').on('change', function(){

					var atLeastOneIsChecked = $('input[name="bulkprd[]"]:checked').length > 0;

					if( this.value == "delete" || this.value == "move" ){
						if( !atLeastOneIsChecked ){
							alert("No Products Selected!");
							$('#bulk_action_select option[value=0]').prop('selected', true);
							return false;
						}
					}

					if( this.value == "delete" ){
						if( confirm('Are you sure you want to delete these products?') ){
							$("#bulkactionform").submit();
						}else{
							return false;
						}
					}else if( this.value == "move" ){
						$("#bulk_new_parent").show("fast");
					}else{
						$("#bulk_new_parent").hide("fast");
					}

				});

				$('#new_parent_select').on('change', function(){
					$("#bulkactionform").submit();
				});

			});
			</script>
			<?php } ?>

		<?php } ?>

	</div>

</div>

<?php require("inc/footer.php"); ?>