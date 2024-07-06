<?php require("config.php"); include("inc/check_session.php");

if( isset($_GET['id']) and get_inquiry($_GET['id']) ){
	$inquiry = get_inquiry($_GET['id']);
}else{
	die();
}

$_TITLE = "View Inquiry";
$_TOPBAR = true;
$_SIDEBAR = true;

require("inc/header.php");
?>

<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex">
			<h4><i class="icon-question3 mr-2"></i> <span class="font-weight-semibold"><?php _e( $_TITLE ); ?></span></h4>
		</div>
	</div>

	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="index.php" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
				<a href="inquiry.php" class="breadcrumb-item">Inquiries</a>
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

	<div id="add-new-card" class="card">
		<div class="card-body">
				
			<div class="form-group row">
				<label for="date" class="col-form-label col-md-2">Date:</label>
				<div class="col-md-10">
					<input id="date" name="date" class="form-control" type="text" value="<?php _e( formatenews($inquiry['date']) ); ?>" disabled>
				</div>
			</div>

			<div class="form-group row">
				<label for="first_name" class="col-form-label col-md-2">First Name:</label>
				<div class="col-md-10">
					<input id="first_name" name="first_name" class="form-control" type="text" value="<?php _e( $inquiry['first_name'] ); ?>" disabled>
				</div>
			</div>

			<div class="form-group row">
				<label for="last_name" class="col-form-label col-md-2">Last Name:</label>
				<div class="col-md-10">
					<input id="last_name" name="last_name" class="form-control" type="text" value="<?php _e( $inquiry['last_name'] ); ?>" disabled>
				</div>
			</div>

			<div class="form-group row">
				<label for="company_name" class="col-form-label col-md-2">Company Name:</label>
				<div class="col-md-10">
					<input id="company_name" name="company_name" class="form-control" type="text" value="<?php _e( $inquiry['company_name'] ); ?>" disabled>
				</div>
			</div>

			<div class="form-group row">
				<label for="phone" class="col-form-label col-md-2">Phone:</label>
				<div class="col-md-10">
					<input id="phone" name="phone" class="form-control" type="text" value="<?php _e( $inquiry['phone'] ); ?>" disabled>
				</div>
			</div>

			<div class="form-group row">
				<label for="fax" class="col-form-label col-md-2">Fax:</label>
				<div class="col-md-10">
					<input id="fax" name="fax" class="form-control" type="text" value="<?php _e( $inquiry['fax'] ); ?>" disabled>
				</div>
			</div>

			<div class="form-group row">
				<label for="email" class="col-form-label col-md-2">Email:</label>
				<div class="col-md-10">
					<input id="email" name="email" class="form-control" type="text" value="<?php _e( $inquiry['email'] ); ?>" disabled>
				</div>
			</div>

			<div class="form-group row">
				<label for="city" class="col-form-label col-md-2">City:</label>
				<div class="col-md-10">
					<input id="city" name="city" class="form-control" type="text" value="<?php _e( $inquiry['city'] ); ?>" disabled>
				</div>
			</div>

			<div class="form-group row">
				<label for="state" class="col-form-label col-md-2">State:</label>
				<div class="col-md-10">
					<input id="state" name="state" class="form-control" type="text" value="<?php _e( $inquiry['state'] ); ?>" disabled>
				</div>
			</div>

			<div class="form-group row">
				<label for="country" class="col-form-label col-md-2">Country:</label>
				<div class="col-md-10">
					<input id="country" name="country" class="form-control" type="text" value="<?php _e( $inquiry['country'] ); ?>" disabled>
				</div>
			</div>

			<div class="form-group row">
				<label for="address" class="col-form-label col-md-2">address:</label>
				<div class="col-md-10">
					<textarea id="address" name="address" class="form-control" disabled><?php _e( $inquiry['address'] ); ?></textarea>
				</div>
			</div>

			<div class="form-group row">
				<label for="message" class="col-form-label col-md-2">Message:</label>
				<div class="col-md-10">
					<textarea id="message" name="message" class="form-control" disabled><?php _e( $inquiry['message'] ); ?></textarea>
				</div>
			</div>

		</div>
	</div>

	<?php
	$product_query = mysql_query("SELECT * FROM `inquiry_products` WHERE `inquiry_id`=".$inquiry['id']);
	if( mysql_num_rows($product_query) > 0 ){
	?>
	<div class="card">

		<div class="card-body">
			Following are the products in the inquiry.
		</div>

		<div class="table-responsive">
			<table class="table table-striped table-hover">
				
				<thead>
					<tr>
						<th class="text-center" style="width:50px;">ID</th>
						<th class="text-center" style="width:150px;">Image</th>
						<th>Name</th>
						<th class="text-center" style="width:150px;">Quantity</th>
					</tr>
				</thead>

				<tbody>
					
					<?php while($RS_product = mysql_fetch_array($product_query)){ $product = get_product($RS_product['product_id']); ?>
					<tr>

						<td class="text-center"><?php _e( $RS_product['id'] ); ?></td>

						<td class="text-center">
							<a href="<?php _e($__url_sm_imgs.$product['img_small']); ?>" target="_blank">
								<img style="max-width:100%;" class="rounded mx-auto d-block img-fluid img-thumbnail" src="<?php _e($__url_sm_imgs.$product['img_small']); ?>" alt="">
							</a>
						</td>
						
						<td>
							<strong><?php _e( $product['art_no'] ); ?></strong><br />
							<?php _e( $product['name'] ); ?>
							<hr style="margin-top:4px; margin-bottom:4px;" />
							<?php _e( print_product_inquiry($inquiry['id'], $RS_product['product_id']) ); ?>
						</td>

						<td class="text-center">
							<?php _e( $RS_product['qty'] ); ?> Pcs
						</td>

					</tr>
					<?php } ?>

				</tbody>

			</table>
		</div>

	</div>
	<?php } ?>

</div>

<?php require("inc/footer.php"); ?>