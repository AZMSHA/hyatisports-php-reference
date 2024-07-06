<?php require("config.php"); include("inc/check_session.php");

$_TITLE = "Change Password";
$_TOPBAR = true;
$_SIDEBAR = true;

require("inc/header.php");
?>

<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex">
			<h4><i class="icon-grid mr-2"></i> <span class="font-weight-semibold"><?php _e( $_TITLE ); ?></span></h4>
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
				
			<form action="mini_process.php" method="post" enctype="multipart/form-data">
				
				<input type="hidden" name="p" value="password">
				
				<div class="form-group row">
					<label for="o_password" class="col-form-label col-md-2">Current Password:</label>
					<div class="col-md-10">
						<input id="o_password" name="o_password" class="form-control" type="password" required>
					</div>
				</div>

				<div class="form-group row">
					<label for="password" class="col-form-label col-md-2">New Password:</label>
					<div class="col-md-10">
						<input id="password" name="password" class="form-control" type="password" required>
					</div>
				</div>

				<div class="form-group row">
					<label for="a_password" class="col-form-label col-md-2">Again:</label>
					<div class="col-md-10">
						<input id="a_password" name="a_password" class="form-control" type="password" required>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-md-2"></div>
					<div class="col-md-10"><button type="submit" id="edit" class="btn btn-primary"><i class="icon-floppy-disk mr-2"></i> Save</button></div>
				</div>

			</form>

		</div>
	</div>

</div>

<?php require("inc/footer.php"); ?>