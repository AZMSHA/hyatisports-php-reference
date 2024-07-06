<?php require("config.php"); include("inc/check_session.php");

$_TITLE = "Dashboard";
$_TOPBAR = true;
$_SIDEBAR = true;

require("inc/header.php");
?>

<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex">
			<h4><i class="icon-home4 mr-2"></i> <span class="font-weight-semibold">Dashboard</span></h4>
		</div>
	</div>

	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="index.php" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
				<span class="breadcrumb-item active">Dashboard</span>
			</div>
		</div>
	</div>
</div>

<div class="content">

	<div class="row">
		
		<?php if( _menu_category_product_ ){ ?>
		<div class="col-md-3 col-sm-6">
			<div class="card bg-indigo-400">
				<div class="card-body">
					<div class="d-flex">
						<h3 class="font-weight-semibold mb-0"><?php $categories = mysql_result(mysql_query("SELECT count(*) FROM `categories`"),0); _e( appendzero($categories) ); ?></h3>
						<div class="list-icons badge ml-auto bg-indigo-800"><i class="icon-list-unordered"></i></div>
                	</div>
                	<div>Categories</div>
				</div>
			</div>
		</div>

		<div class="col-md-3 col-sm-6">
			<div class="card bg-pink-400">
				<div class="card-body">
					<div class="d-flex">
						<h3 class="font-weight-semibold mb-0"><?php $products = mysql_result(mysql_query("SELECT count(*) FROM `products`"),0); _e( appendzero($products) ); ?></h3>
						<div class="list-icons badge ml-auto bg-pink-800"><i class="icon-box-add"></i></div>
                	</div>
                	<div>Products</div>
				</div>
			</div>
		</div>
		<?php } ?>

		<?php if( _menu_page_ ){ ?>
		<div class="col-md-3 col-sm-6">
			<div class="card bg-teal-400">
				<div class="card-body">
					<div class="d-flex">
						<h3 class="font-weight-semibold mb-0"><?php $pages = mysql_result(mysql_query("SELECT count(*) FROM `pages`"),0); _e( appendzero($pages) ); ?></h3>
						<div class="list-icons badge ml-auto bg-teal-800"><i class="icon-stack4"></i></div>
                	</div>
                	<div>Pages</div>
				</div>
			</div>
		</div>
		<?php } ?>

		<?php if( _menu_inquiry_ ){ ?><div class="col-md-3 col-sm-6">
			<div class="card bg-blue-400">
				<div class="card-body">
					<div class="d-flex">
						<h3 class="font-weight-semibold mb-0"><?php $inquiries = mysql_result(mysql_query("SELECT count(*) FROM `inquiry` WHERE `submitted`=1"),0); _e( appendzero($inquiries) ); ?></h3>
						<div class="list-icons badge ml-auto bg-blue-800"><i class="icon-question3"></i></div>
                	</div>
                	<div>Inquiries</div>
				</div>
			</div>
		</div><?php } ?>

	</div>
	
	<?php if( _menu_inquiry_ ){ ?><div class="card">

		<div class="card-body">
			Latest 5 inquiry submitted on <?php _e( $Company ); ?>.
		</div>

		<?php
		$inquiry_query = mysql_query("SELECT * FROM `inquiry` WHERE `submitted`=1 ORDER BY `date` DESC LIMIT 0, 5");
		if( mysql_num_rows($inquiry_query) > 0 ){
		?>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				
				<thead>
					<tr>
						<th class="text-center" style="width:50px;">ID</th>
						<th>Name</th>
						<th class="text-center" style="width:100px;">Products</th>
						<th class="text-center" style="width:150px;">Country</th>
						<th class="text-center" style="width:150px;">Date</th>
						<th class="text-center" style="width:100px;">Options</th>
					</tr>
				</thead>

				<tbody>
					
					<?php while($RS_inquiry = mysql_fetch_array($inquiry_query)){ ?>
					<tr>

						<td class="text-center"><?php _e( $RS_inquiry['id'] ); ?></td>
						
						<td>
							<strong><?php _e( $RS_inquiry['first_name'] ); ?> <?php _e( $RS_inquiry['last_name'] ); ?></strong><br />
							<?php _e( $RS_inquiry['email'] ); ?>
						</td>

						<td class="text-center">
							<?php _e( appendzero(num_inquiry_products($RS_inquiry['id'])) ); ?>
						</td>

						<td class="text-center">
							<?php _e( $RS_inquiry['country'] ); ?>
						</td>

						<td class="text-center">
							<?php _e( formatenews($RS_inquiry['date']) ); ?>
						</td>

						<td class="text-center">
							<a title="View Detail" href="viewinquiry.php?id=<?php _e($RS_inquiry['id']); ?>" class="btn btn-sm bg-indigo-400"><i class="icon-eye"></i></a>
						</td>

					</tr>
					<?php } ?>

				</tbody>

			</table>
		</div>
		<?php } ?>

	</div><?php } ?>

</div>

<?php require("inc/footer.php"); ?>