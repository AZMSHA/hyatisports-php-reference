<?php require("config.php"); require("inc/check_session.php");

if( isset($_GET['id']) and get_news($_GET['id']) ){
	$news_id = esc($_GET['id']);
	$news = get_news($news_id);
}else{
	die();
}

$_TITLE = "Edit News";
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
		</div>
	</div>

	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="index.php" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
				<a href="news.php" class="breadcrumb-item">News</a>
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
		<div class="card-header header-elements-inline">
			<h5 class="card-title">Edit News</h5>
		</div>
		<div class="card-body">
				
			<form action="mini_process.php" method="post" enctype="multipart/form-data">
				
				<input type="hidden" name="p" value="EditNews">
				<input type="hidden" name="id" value="<?php _e( $news_id ); ?>">
				
				<div class="form-group row">
					<label for="title" class="col-form-label col-md-2">Title:</label>
					<div class="col-md-10">
						<input id="title" name="title" class="form-control" type="text" value="<?php _e( $news['title'] ); ?>" required autofocus>
					</div>
				</div>

				<div class="form-group row">
					<label class="col-form-label col-md-2">Date (DD/MM/YYYY):</label>
					<div class="col-md-10">
						
						<select id="date_day" name="date_day" class="form-control" style="display:inline-block; width:auto;">
							<?php for ($d=1; $d <= 31; $d++){ ?>
							<option value="<?php _e( $d ); ?>"<?php if( $d == $news['date_day'] ){ ?> selected<?php } ?>><?php _e( appendzero($d) ); ?></option>
							<?php } ?>
						</select>

						<select id="date_month" name="date_month" class="form-control" style="display:inline-block; width:auto;">
							<?php for ($m=1; $m <= 12; $m++){ ?>
							<option value="<?php _e( $m ); ?>"<?php if( $m == $news['date_month'] ){ ?> selected<?php } ?>><?php _e( appendzero($m) ); ?></option>
							<?php } ?>
						</select>

						<select id="date_year" name="date_year" class="form-control" style="display:inline-block; width:auto;">
							<?php for ($y=2000; $y <= 2030; $y++){ ?>
							<option value="<?php _e( $y ); ?>"<?php if( $y == $news['date_year'] ){ ?> selected<?php } ?>><?php _e( appendzero($y) ); ?></option>
							<?php } ?>
						</select>

					</div>
				</div>

				<?php if( _new_descp_ ){ ?><div class="form-group row">
					<label for="description" class="col-form-label col-md-2">Description:</label>
					<div class="col-md-10">
						<textarea id="description" name="description" class="form-control"><?php _e( $news['description'] ); ?></textarea>
					</div>
				</div><?php }else{ ?><input name="description" type="hidden" value="<?php _e( $news['description'] ); ?>"><?php } ?>

				<?php if( _new_text_ ){ ?><div class="form-group row">
					<label for="text" class="col-form-label col-md-2">Content:</label>
					<div class="col-md-10">
						<textarea id="text" name="text" class="summernote"><?php _e( $news['text'] ); ?></textarea>
					</div>
				</div><?php }else{ ?><input name="text" type="hidden" value="<?php _e( $news['text'] ); ?>"><?php } ?>

				<?php if( !empty($news['image']) ){ ?>
				<div class="form-group row">
					<label class="col-form-label col-md-2">Old Image:</label>
					<div class="col-md-10">
						<div class="imgcontainer">
							<img class="mw-100 mx-auto" src="<?php _e($__url_attimgs.$news['image']); ?>">
							<div class="imgwrapper">
								<a href="<?php _e($__url_attimgs.$news['image']); ?>" target="_blank"><i class="icon-eye"></i></a>
								<?php if( _setting_rm_img_ ){ ?><a onClick="javascript: if(confirm('Are you sure you want to remove this image?')) return true; else return false;" href="mini_process.php?p=removeimg&r2=<?php _e(urlencode(current_page())); ?>&type=news&id=<?php _e($news['id']); ?>&col=image&dir=<?php _e(urlencode($__dir_attimgs)); ?>"><i class="icon-bin"></i></a><?php } ?>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>

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

</div>

<script type="text/javascript">
$(document).ready(function(){
	$('.imgcontainer').hover(
		function(){ $('.imgwrapper', this).fadeIn(100); },
		function(){ $('.imgwrapper', this).fadeOut(100); }
	);
});
</script>

<?php require("inc/footer.php"); ?>