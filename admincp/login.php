<?php require("config.php"); $isError=false; $isLogout=false; $usrname="";

if( isset($_SESSION['logged']) and $_SESSION['logged']==true ){

	if( isset($_GET['logout']) ){		
		$_SESSION['logged'] = false;
		$isLogout = true;
		session_destroy();
	}else{
		redirect("index.php");
	}
	
}

if( isset($_POST["usrname"]) and isset($_POST["password"]) ){
	
	$usrname = esc($_POST["usrname"]);
	$password = esc($_POST["password"]);
	
	$qry=mysql_query("SELECT * FROM `tbl_admin` WHERE `usrname`='".$usrname."' AND `password`='".md5($password)."'");
	
	if( mysql_num_rows($qry)>0 ){

		$_SESSION['usrname'] = $usrname;
		$_SESSION['logged'] = true;
		redirect("index.php");

	}else{
		$isError=true;
	}

}

$_TITLE = "Login";
$_TOPBAR = false;
$_SIDEBAR = false;

require("inc/header.php");

?>

<div class="content d-flex justify-content-center align-items-center">

	<form class="login-form" action="" method="post">
		<div class="card mb-0">
			<div class="card-body">
				
				<div class="text-center mb-3">
					<i class="icon-reading icon-2x text-slate-300 border-slate-300 border-3 rounded-round p-3 mb-3 mt-1"></i>
					<h5 class="mb-0">Login to your account</h5>
					<span class="d-block text-muted">Enter your credentials below</span>
				</div>

				<?php if( $isLogout ){ ?>
					<div class="alert alert-primary alert-dismissible">
						<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
						You have been logged out.
				    </div>
				<?php }elseif( $isError ){ ?>
					<div class="alert alert-danger alert-dismissible">
						<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
						Invalid Username or Password.
				    </div>
			    <?php } ?>

				<div class="form-group form-group-feedback form-group-feedback-left">
					<input name="usrname" type="text" class="form-control" placeholder="Username" value="<?php _e( $usrname ); ?>" required autofocus>
					<div class="form-control-feedback">
						<i class="icon-user text-muted"></i>
					</div>
				</div>

				<div class="form-group form-group-feedback form-group-feedback-left">
					<input name="password" type="password" class="form-control" placeholder="Password" required>
					<div class="form-control-feedback">
						<i class="icon-lock2 text-muted"></i>
					</div>
				</div>

				<div class="form-group">
					<button type="submit" class="btn btn-primary btn-block">Sign in <i class="icon-circle-right2 ml-2"></i></button>
				</div>

			</div>
		</div>
	</form>

</div>

<?php require("inc/footer.php"); ?>