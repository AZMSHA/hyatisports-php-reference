<?php require("config.php"); include("inc/check_session.php");

$_TITLE = "Inquiries";
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

	<div class="card">

		<div class="card-body">
			Total of <?php $inquiries = mysql_result(mysql_query("SELECT count(*) FROM `inquiry` WHERE `submitted`=1"),0); _e( appendzero($inquiries) ); ?> inquiries found!
		</div>

		<?php
		$inquiry_query = mysql_query("SELECT * FROM `inquiry` WHERE `submitted`=1 ORDER BY `date` DESC");
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

	</div>

</div>

<?php require("inc/footer.php"); ?>