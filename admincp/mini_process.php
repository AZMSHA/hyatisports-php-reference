<?php

require("config.php"); require("inc/check_session.php");

if( isset($_POST['p']) and $_POST['p']=='AddBannerImage' ){

	$banner = esc($_POST['banner']);

	$name = esc($_POST['name']);
	$link = esc($_POST['link']);
	$text = esc($_POST['description']);

	$rank = get_next_banner_image_rank($banner);

	if( isset($_FILES['image']) and !empty($_FILES['image']['name']) ){
		$thumb_file_ext = get_file_extension(esc($_FILES['image']['name']));
		$thumb_filename = randomKey(10).".".$thumb_file_ext;
		if( !only_img($thumb_file_ext) ){ die(); }
		$move = move_uploaded_file($_FILES['image']['tmp_name'], $__dir_banner.$thumb_filename);
		if( !$move ){ die("Error! Could not upload image."); }
	}else{
		$thumb_filename = "";
	}

	$query = mysql_query("INSERT INTO `banner_images`(`id`, `banner`, `name`, `link`, `text`, `image`, `rank`) VALUES (NULL, '".$banner."', '".$name."', '".$link."', '".$text."', '".$thumb_filename."', '".$rank."')");
	
	if( $query ){
		redirect("banner_images.php?banner=".$banner."&mess=Image+has+been+added!");
	}else{
		die("Error! Could not add the image.");
	}

}elseif( isset($_POST['p']) and $_POST['p']=='EditBannerImage' ){

	$id = esc($_POST['id']);

	$name = esc($_POST['name']);
	$link = esc($_POST['link']);
	$text = esc($_POST['description']);

	$banner_image_query = mysql_query("SELECT * FROM `banner_images` WHERE `id`=".$id);
  	$RS_banner_image = mysql_fetch_array($banner_image_query);

	if( isset($_FILES['image']) and !empty($_FILES['image']['name']) ){
		
		if( $RS_banner_image['image']!="" ){ unlink($__dir_banner.$RS_banner_image['image']); }

		$thumb_file_ext = get_file_extension(esc($_FILES['image']['name']));
		$thumb_filename = randomKey(10).".".$thumb_file_ext;
		if( !only_img($thumb_file_ext) ){ die(); }
		$move = move_uploaded_file($_FILES['image']['tmp_name'], $__dir_banner.$thumb_filename);
		if( !$move ){ die("Error! Could not upload image."); }
	}else{
		$thumb_filename = $RS_banner_image['image'];
	}
	
	if( isset($_FILES['imagebg']) and !empty($_FILES['imagebg']['name']) ){
		
		if( $RS_banner_image['imagebg']!="" ){ unlink($__dir_banner.$RS_banner_image['imagebg']); }

		$thumb_file_ext = get_file_extension(esc($_FILES['imagebg']['name']));
		$imagebg_filename = randomKey(10).".".$thumb_file_ext;
		if( !only_img($thumb_file_ext) ){ die(); }
		$move = move_uploaded_file($_FILES['imagebg']['tmp_name'], $__dir_banner.$imagebg_filename);
		if( !$move ){ die("Error! Could not upload image."); }
	}else{
		$imagebg_filename = $RS_banner_image['imagebg'];
	}

	$query = mysql_query("UPDATE `banner_images` SET `name`='".$name."',`link`='".$link."',`text`='".$text."',`image`='".$thumb_filename."',`imagebg`='".$imagebg_filename."' WHERE `id`=".$id);

	if( $query ){
		redirect("banner_images.php?banner=".$RS_banner_image['banner']."&mess=Banner+image+has+been+updated!");
	}else{
		die("Error! Could not update the banner image.");
	}

}elseif( isset($_GET['p']) and $_GET['p']=='bannerimagerank' and isset($_GET['id']) and isset($_GET['r']) ){

	$id = esc($_GET['id']);
	if( esc($_GET['r']) == 'up' ){ $rank = "u"; }else{ $rank = "d"; }

	$banner_image_query = mysql_query("SELECT * FROM `banner_images` WHERE `id`=".$id);
  	$RS_banner_image = mysql_fetch_array($banner_image_query);

	if( $rank == "d" ){
		$old_rank_update = mysql_query("UPDATE `banner_images` SET `rank`=".$RS_banner_image['rank']." WHERE `banner`=".$RS_banner_image['banner']." AND `rank`=".($RS_banner_image['rank']+1));
		$current_rank_update = mysql_query("UPDATE `banner_images` SET `rank`=".($RS_banner_image['rank']+1)." WHERE `id`=".$id);
	}else{
		$old_rank_update = mysql_query("UPDATE `banner_images` SET `rank`=".$RS_banner_image['rank']." WHERE `banner`=".$RS_banner_image['banner']." AND `rank`=".($RS_banner_image['rank']-1));
		$current_rank_update = mysql_query("UPDATE `banner_images` SET `rank`=".($RS_banner_image['rank']-1)." WHERE `id`=".$id);
	}

	if( !$old_rank_update or !$current_rank_update ){ die("Error! Could not update rank."); }

	redirect("banner_images.php?banner=".$_GET['banner_id']."&mess=Rank+Updated!");

}elseif( isset($_POST['p']) and $_POST['p']=='DelBannerImage' and isset($_POST['id']) ){

	$id = esc($_POST['id']);
	$banner = esc($_POST['banner']);

	$banner_image_query = mysql_query("SELECT * FROM `banner_images` WHERE `id`=".$id);
  	$RS_banner_image = mysql_fetch_array($banner_image_query);

	if( $RS_banner_image['image']!="" ){ unlink($__dir_banner.$RS_banner_image['image']); }

	$query = mysql_query("DELETE FROM `banner_images` WHERE `id`=".$id);
	
	redirect("banner_images.php?banner=".$banner."&mess=Image+Deleted!");

}elseif( isset($_POST['p']) and $_POST['p']=='AddBanner' ){

	$name = esc($_POST['name']);

	if( empty($name) ){ redirect("banners.php"); }

	$query = mysql_query("INSERT INTO `banners`(`id`, `name`) VALUES (NULL, '".$name."')");
	if( $query ){
		redirect("banners.php?mess=Banner+has+been+added!");
	}else{
		die("Error! could not add the banner.");
	}

}elseif( isset($_POST['p']) and $_POST['p']=='UpdBanner' ){

	$ID = esc($_POST['id']);
	$name = esc($_POST['name']);

	$query = mysql_query("UPDATE `banners` SET `name`='".$name."' WHERE `id`=".$ID);
	if( $query ){
		redirect("banners.php?mess=Banner+has+been+updated!");
	}else{
		die("Error! could not update the banner.");
	}

}elseif( isset($_POST['p']) and $_POST['p']=='DelBanner' ){

	$ID = esc($_POST['id']);
	$query = mysql_query("SELECT * FROM `banner_images` WHERE `id`=".$ID);
	
	if( mysql_num_rows($query) > 0 ){
		redirect("banners.php?mess=Cannot+delete+banner.+ +It+has+images.");
	}else{
		$query = mysql_query("DELETE FROM `banners` WHERE `id`=".$ID);
		if( $query ){
			redirect("banners.php?mess=Banner+has+been+deleted!");
		}else{
			die("Error! could not delete the banner.");
		}
	}

}elseif( isset($_POST['p']) and $_POST['p']=='AddWidget' ){

	require("inc/widget_setting.php");

	$area_id = esc($_POST['area_id']);
	$area = get_widget_areas($area_id);
	$settings = $widgets[$area_id];
	$num = count($settings);

	if( !has_widget_setting($area_id) ){ die(); }

	$name = esc($_POST['name']);
	$rank = get_next_rank("widgets", "`area_id`=".$area_id);

	$fl = 1; foreach($settings as $field){
		
		if( $field[1] == 'img' ){
			
			if( isset($_FILES['field_'.$fl]) and !empty($_FILES['field_'.$fl]['name']) ){
				$file_ext = get_file_extension(esc($_FILES['field_'.$fl]['name']));
				$file_name = randomKey(10).".".$file_ext;
				if( !only_img($file_ext) ){ die(); }
				$move = move_uploaded_file($_FILES['field_'.$fl]['tmp_name'], $__dir_attimgs.$file_name);
				if( !$move ){ die("Error! Could not upload image."); }
			}else{
				$file_name = "";
			}

			${'field_'.$fl} = $file_name;

		}else{
			${'field_'.$fl} = esc($_POST['field_'.$fl]);
		}

	$fl++; }


	if( $num < 7 ){
		for($i=($num+1); $i <= 7; $i++){
			${'field_'.$i} = "";
		}
	}

	$query = mysql_query("INSERT INTO `widgets`(`area_id`, `name`, `field_1`, `field_2`, `field_3`, `field_4`, `field_5`, `field_6`, `field_7`, `rank`) VALUES (".$area_id.", '".$name."', '".$field_1."', '".$field_2."', '".$field_3."', '".$field_4."', '".$field_5."', '".$field_6."', '".$field_7."', ".$rank.")");

	if( $query ){
		redirect("widgets.php?area=".$area_id."&mess=Widget+has+been+added!");
	}else{
		die("Error! Could not add the widget.");
	}

}elseif( isset($_POST['p']) and $_POST['p']=='EditWidget' ){

	require("inc/widget_setting.php");

	$id = esc($_POST['id']);
	$widget = get_widget( $id );

	if( !$widget ){ die("Error! Invalid Widget"); }

	$area_id = esc($_POST['area_id']);
	$area = get_widget_areas($area_id);
	$settings = $widgets[$area_id];
	$num = count($settings);

	if( !has_widget_setting($area_id) ){ die(); }

	$name = esc($_POST['name']);

	$fl = 1; foreach($settings as $field){
		
		if( $field[1] == 'img' ){
			
			if( isset($_FILES['field_'.$fl]) and !empty($_FILES['field_'.$fl]['name']) ){				
				if( !empty($widget['field_'.$fl]) ){ unlink($__dir_attimgs.$widget['field_'.$fl]); }
				$file_ext = get_file_extension(esc($_FILES['field_'.$fl]['name']));
				$file_name = randomKey(10).".".$file_ext;
				if( !only_img($file_ext) ){ die(); }
				$move = move_uploaded_file($_FILES['field_'.$fl]['tmp_name'], $__dir_attimgs.$file_name);
				if( !$move ){ die("Error! Could not upload image."); }
			}else{
				$file_name = $widget['field_'.$fl];
			}

			${'field_'.$fl} = $file_name;

		}else{
			${'field_'.$fl} = esc($_POST['field_'.$fl]);
		}

	$fl++; }


	if( $num < 7 ){
		for($i=($num+1); $i <= 7; $i++){
			${'field_'.$i} = $widget['field_'.$i];
		}
	}

	$query = mysql_query("UPDATE `widgets` SET `name`='".$name."', `field_1`='".$field_1."', `field_2`='".$field_2."', `field_3`='".$field_3."', `field_4`='".$field_4."', `field_5`='".$field_5."', `field_6`='".$field_6."', `field_7`='".$field_7."' WHERE `id`=".$id);

	if( $query ){
		redirect("widgets.php?area=".$area_id."&mess=Widget+has+been+updated!");
	}else{
		die("Error! Could not update the widget.");
	}

}elseif( isset($_GET['p']) and $_GET['p']=='widgetrank' and isset($_GET['id']) and isset($_GET['r']) ){

	$id = esc($_GET['id']);
	if( esc($_GET['r']) == 'up' ){ $rank = "u"; }else{ $rank = "d"; }

	$query = mysql_query("SELECT * FROM `widgets` WHERE `id`=".$id);
  	$RS_widget = mysql_fetch_array($query);

	if( $rank == "d" ){
		$old_rank_update = mysql_query("UPDATE `widgets` SET `rank`=".$RS_widget['rank']." WHERE `area_id`=".$RS_widget['area_id']." AND `rank`=".($RS_widget['rank']+1));
		$current_rank_update = mysql_query("UPDATE `widgets` SET `rank`=".($RS_widget['rank']+1)." WHERE `id`=".$id);
	}else{
		$old_rank_update = mysql_query("UPDATE `widgets` SET `rank`=".$RS_widget['rank']." WHERE `area_id`=".$RS_widget['area_id']." AND `rank`=".($RS_widget['rank']-1));
		$current_rank_update = mysql_query("UPDATE `widgets` SET `rank`=".($RS_widget['rank']-1)." WHERE `id`=".$id);
	}

	if( !$old_rank_update or !$current_rank_update ){ die("Error! Could not update rank."); }

	redirect("widgets.php?area=".$_GET['area_id']."&mess=Rank+Updated!");

}elseif( isset($_POST['p']) and $_POST['p']=='DelWidget' ){

	require("inc/widget_setting.php");

	$id = esc($_POST['id']);
	$widget = get_widget( $id );

	if( !$widget ){ die("Error! Invalid Widget"); }

	$area_id = esc($_POST['area_id']);
	$area = get_widget_areas($area_id);
	$settings = $widgets[$area_id];
	$num = count($settings);

	if( !has_widget_setting($area_id) ){ die(); }

	$fl = 1; foreach($settings as $field){
		if( $field[1] == 'img' ){
			if( !empty($widget['field_'.$fl]) ){ unlink($__dir_attimgs.$widget['field_'.$fl]); }
		}
	$fl++; }

	$query = mysql_query("DELETE FROM `widgets` WHERE `id`=".$id);

	if( $query ){
		redirect("widgets.php?area=".$area_id."&mess=Widget+has+been+deleted!");
	}else{
		die("Error! Could not delete the widget.");
	}

}elseif( isset($_POST['p']) and $_POST['p']=='AddWidgetArea' ){

	$name = esc($_POST['name']);

	if( empty($name) ){ redirect("widget_areas.php"); }

	$query = mysql_query("INSERT INTO `widget_areas`(`id`, `name`) VALUES (NULL, '".$name."')");
	if( $query ){
		redirect("widget_areas.php?mess=Widget+Area+has+been+added!");
	}else{
		die("Error! could not add the Widget Area.");
	}

}elseif( isset($_POST['p']) and $_POST['p']=='UpdWidgetArea' ){

	$ID = esc($_POST['id']);
	$name = esc($_POST['name']);

	$query = mysql_query("UPDATE `widget_areas` SET `name`='".$name."' WHERE `id`=".$ID);
	if( $query ){
		redirect("widget_areas.php?mess=Widget+Area+has+been+updated!");
	}else{
		die("Error! could not update the Widget Area.");
	}

}elseif( isset($_POST['p']) and $_POST['p']=='DelWidgetArea' ){

	$ID = esc($_POST['id']);
	$query = mysql_query("SELECT * FROM `widgets` WHERE `id`=".$ID);
	
	if( mysql_num_rows($query) > 0 ){
		redirect("widget_areas.php?mess=Cannot+delete+Widget+Area.+ +It+has+images.");
	}else{
		$query = mysql_query("DELETE FROM `widget_areas` WHERE `id`=".$ID);
		if( $query ){
			redirect("widget_areas.php?mess=Widget+Area+has+been+deleted!");
		}else{
			die("Error! could not delete the Widget Area.");
		}
	}

}elseif( isset($_POST['p']) and $_POST['p']=='UpdScripts' ){

	$query = mysql_query("SELECT * FROM `scripts` WHERE `meta`!='hide'");
	if( mysql_num_rows($query) > 0 ){
		while( $RS = mysql_fetch_array($query) ){
			if( isset($_POST[$RS['name']]) ){

				$update = mysql_query("UPDATE `scripts` SET `value`='".esc($_POST[$RS['name']])."' WHERE `id`=".$RS['id']);
				if( !$update ){ die("Could not update the option ".$RS['id']); }

			}else{
				redirect("scripts.php?");
			}
		}

		redirect("scripts.php?mess=Options+Updated!");

	}else{
		die("No Scripts Found!");
	}

}elseif( isset($_POST['p']) and $_POST['p']=='UpdSocial' ){

	$query = mysql_query("SELECT * FROM `social` WHERE `meta`!='hide'");
	if( mysql_num_rows($query) > 0 ){
		while( $RS = mysql_fetch_array($query) ){
			if( isset($_POST[$RS['name']]) ){

				$update = mysql_query("UPDATE `social` SET `value`='".esc($_POST[$RS['name']])."' WHERE `id`=".$RS['id']);
				if( !$update ){ die("Could not update the option ".$RS['id']); }

			}else{
				redirect("social.php?");
			}
		}

		redirect("social.php?mess=Links+Updated!");

	}else{
		die("No Social Media links Found!");
	}
    
}elseif( isset($_POST['p']) and $_POST['p']=='UpdOptions' ){

	$query = mysql_query("SELECT * FROM `options` WHERE `meta`!='hide'");
	if( mysql_num_rows($query) > 0 ){
		while( $RS = mysql_fetch_array($query) ){

			if( $RS['meta']!='hide' ){

				if( $RS['meta']=='fixed_line' or $RS['meta']=='fixed_txt' or $RS['meta']=='fixed_rte' ){
					
					if( !isset($_POST[$RS['name']]) or empty($_POST[$RS['name']]) ){
						redirect("configuration.php?mess=Please+fill+the+'".$RS['title']."'+filed!");
					}
					$option = $_POST[$RS['name']];

				}elseif( $RS['meta']=='fixed_num' ){

					if( !isset($_POST[$RS['name']]) or empty($_POST[$RS['name']]) or !is_numeric($_POST[$RS['name']]) ){
						redirect("configuration.php?mess=Please+fill+the+'".$RS['title']."'+filed! It can only be a number.");
					}
					$option = $_POST[$RS['name']];

				}elseif( $RS['meta']=='img' ){
					
					$option = $RS['value'];

					if( isset($_FILES[$RS['name']]) and !empty($_FILES[$RS['name']]['name']) ){
						if( !empty($option) ){ unlink($__dir_attimgs.$option); }
						$option_file_ext = get_file_extension(esc($_FILES[$RS['name']]['name']));
						$option = randomKey(10).".".$option_file_ext;
						if( !only_img($option_file_ext) ){ die(); }
						$move = move_uploaded_file($_FILES[$RS['name']]['tmp_name'], $__dir_attimgs.$option);
						if( !$move ){ die("Error! Could not upload image."); }
					}

				}else{
					$option = $_POST[$RS['name']];
				}

				$update = mysql_query("UPDATE `options` SET `value`='".esc($option)."' WHERE `id`=".$RS['id']);
				if( !$update ){ die("Could not update the option ".$RS['title']); }

			}

		}

		redirect("configuration.php?mess=Options+Updated!");

	}else{
		die("No Options Found!");
	}

}elseif( isset($_GET['p']) and $_GET['p']=='removeimg' and isset($_GET['r2']) and isset($_GET['type']) and isset($_GET['id']) and isset($_GET['col']) and isset($_GET['dir']) ){

	$r2 = esc($_GET['r2']);
	$type = esc($_GET['type']);
	$id = esc($_GET['id']);
	$col = esc($_GET['col']);
	$dir = ($_GET['dir']);

	$query = mysql_query("SELECT `".$col."` FROM `".$type."` WHERE `id`=".$id);
	if( $query and mysql_num_rows($query) > 0 ){
		$RS = mysql_fetch_array($query);

		if( !empty($RS[$col]) ){ $delimg = unlink($dir.$RS[$col]); }else{ $delimg = true; }
		if( !$delimg ){ die("Error! could not delte the image."); }

		$query_update = mysql_query("UPDATE `".$type."` SET `".$col."`='' WHERE `id`=".$id);
		if( $query_update ){
			redirect($r2);
		}else{
			die("Error! Could not update.");
		}

	}else{
		redirect($r2);
	}

}elseif( isset($_POST['p']) and $_POST['p']=='AddCurrency' ){

	$name = esc($_POST['name']);
	$rate = esc($_POST['rate']);
	$symbol = esc($_POST['symbol']);
	$abbreviation = esc($_POST['abbreviation']);

	if( isset($_FILES['img']) and !empty($_FILES['img']['name']) ){
		$thumb_file_ext = get_file_extension(esc($_FILES['img']['name']));
		if( !only_img($thumb_file_ext) ){ die(); }
		$thumb_filename = randomKey(10).".".$thumb_file_ext;
		$move = move_uploaded_file($_FILES['img']['tmp_name'], $__dir_attimgs.$thumb_filename);
		if( !$move ){ die("Error! Could not upload thumb."); }
	}else{
		$thumb_filename = "";
	}

	$query = mysql_query("INSERT INTO `exchange`(`id`, `name`, `rate`, `symbol`, `abbreviation`, `image`) VALUES (NULL, '".$name."', '".$rate."', '".$symbol."', '".$abbreviation."', '".$thumb_filename."')");
	if( $query ){
		redirect("currencies.php?mess=Currency+has+been+added!");
	}else{
		die("Error! Could not add the currency.");
	}

}elseif( isset($_POST['p']) and $_POST['p']=='EditCurrency' and isset($_POST['id']) ){

	$id = esc($_POST['id']);
	$currency = get_currency($id);

	$name = esc($_POST['name']);
	$rate = esc($_POST['rate']);
	$symbol = esc($_POST['symbol']);
	$abbreviation = esc($_POST['abbreviation']);

	if( $currency ){
		
		if( isset($_FILES['img']) and !empty($_FILES['img']['name']) ){
			if( $currency['image']!="" ){ unlink($__dir_attimgs.$currency['image']); }
			$thumb_file_ext = get_file_extension(esc($_FILES['img']['name']));
			if( !only_img($thumb_file_ext) ){ die(); }
			$thumb_filename = randomKey(10).".".$thumb_file_ext;
			$move = move_uploaded_file($_FILES['img']['tmp_name'], $__dir_attimgs.$thumb_filename);
			if( !$move ){ die("Error! Could not upload thumb."); }
		}else{
			$thumb_filename = $currency['image'];
		}

		$query = mysql_query("UPDATE `exchange` SET `name`='".$name."',`rate`='".$rate."',`symbol`='".$symbol."',`abbreviation`='".$abbreviation."',`image`='".$thumb_filename."' WHERE `id`=".$id);

		if( $query ){
			redirect("currencies.php?mess=Currency+has+been+updated!");
		}else{
			die("Error! Could not update the currency.");
		}

	}else{
		die("Error! Invalide Currency.");
	}

}elseif( isset($_POST['p']) and $_POST['p']=='DelCurrency' and isset($_POST['id']) ){

	$id = esc($_POST['id']);
	$currency = get_currency($id);

	if( no_of_currency() < 2 ){ redirect("currencies.php?mess=There+has+too+be+atleast+one+currency!"); }

	if( $currency ){

		if( $currency['image']!="" ){ unlink($__dir_attimgs.$currency['image']); }
		$query = mysql_query("DELETE FROM `exchange` WHERE `id`=".$id);
		
		if( $query ){
			redirect("currencies.php?mess=Currency+has+been+deleted!");
		}else{
			die("Error! Could not delete the page.");
		}

	}else{
		die("Error! Invalide Currency.");
	}

}elseif( isset($_POST['p']) and $_POST['p']=='AddNews' ){

	if( isset($_POST['title']) and !empty($_POST['title']) ){
		
		$title = esc($_POST['title']);
		$date_day = esc($_POST['date_day']);
		$date_month = esc($_POST['date_month']);
		$date_year = esc($_POST['date_year']);
		$description = esc($_POST['description']);
		$text = esc($_POST['text']);

		$rank = get_next_rank('news');

		if( isset($_FILES['image']) and !empty($_FILES['image']['name']) ){
			$thumb_file_ext = get_file_extension(esc($_FILES['image']['name']));
			if( !only_img($thumb_file_ext) ){ die(); }
			$image_filename = randomKey(10).".".$thumb_file_ext;
			$move = move_uploaded_file($_FILES['image']['tmp_name'], $__dir_attimgs.$image_filename);
			if( !$move ){ die("Error! Could not upload image."); }
		}else{
			$image_filename = "";
		}

		$query = mysql_query("INSERT INTO `news`(`title`, `image`, `description`, `text`, `date_day`, `date_month`, `date_year`, `show`, `rank`)VALUES('".$title."', '".$image_filename."', '".$description."', '".$text."', '".$date_day."', '".$date_month."', '".$date_year."', 'y', ".$rank.")");
		if( $query ){
			redirect("news.php?mess=News+has+been+added!");
		}else{
			die("Error! Could not add the news.");
		}

	}else{
		redirect("news.php?mess=Please+fill+the+required+fileds!");
	}

}elseif( isset($_POST['p']) and $_POST['p']=='EditNews' ){

	if( isset($_POST['id']) and isset($_POST['title']) and !empty($_POST['title']) ){
		
		$id = esc($_POST['id']);
		$news = get_news($id);

		$title = esc($_POST['title']);
		$date_day = esc($_POST['date_day']);
		$date_month = esc($_POST['date_month']);
		$date_year = esc($_POST['date_year']);
		$description = esc($_POST['description']);
		$text = esc($_POST['text']);

		if( $news ){
			
			if( isset($_FILES['image']) and !empty($_FILES['image']['name']) ){
				if( $news['image']!="" ){ unlink($__dir_attimgs.$news['image']); }
				$thumb_file_ext = get_file_extension(esc($_FILES['image']['name']));
				if( !only_img($thumb_file_ext) ){ die(); }
				$image_filename = randomKey(10).".".$thumb_file_ext;
				$move = move_uploaded_file($_FILES['image']['tmp_name'], $__dir_attimgs.$image_filename);
				if( !$move ){ die("Error! Could not upload image."); }
			}else{
				$image_filename = $news['image'];
			}

			$query = mysql_query("UPDATE `news` SET `title`='".$title."', `image`='".$image_filename."', `description`='".$description."', `text`='".$text."', `date_day`='".$date_day."', `date_month`='".$date_month."', `date_year`='".$date_year."' WHERE `id`=".$id);
			
			if( $query ){
				redirect("news.php?mess=News+has+been+updated!");
			}else{
				die("Error! Could not edit the news.");
			}

		}else{
			die("Error! Invalide News.");
		}

	}else{
		redirect("news.php?mess=Please+fill+the+required+fileds!");
	}

}elseif( isset($_POST['p']) and $_POST['p']=='DelNews' and isset($_POST['id']) ){

	$id = esc($_POST['id']);
	$news = get_news($id);

	if( $news ){

		if( $news['image']!="" ){ unlink($__dir_attimgs.$news['image']); }
		
		$query = mysql_query("DELETE FROM `news` WHERE `id`=".$id);
		
		if( $query ){
			redirect("news.php?mess=News+has+been+deleted!");
		}else{
			die("Error! Could not delete the news.");
		}

	}else{
		die("Error! Invalide News.");
	}

}elseif( isset($_GET['p']) and $_GET['p']=='dnews' and isset($_GET['id']) ){

	$id = esc($_GET['id']);
	$news = get_news($id);

	if( $news ){

		if( $news['show'] == "y" ){
			$query = mysql_query("UPDATE `news` SET `show`='n' WHERE `id`=".$id);
		}else{
			$query = mysql_query("UPDATE `news` SET `show`='y' WHERE `id`=".$id);
		}

		if( !$query ){
			die("Error! Could not update display setting.");
		}else{
			if( isset($_GET['r2']) and !empty($_GET['r2']) ){ redirect($_GET['r2']); }
			else{ redirect("news.php?mess=News+Updated!"); }
		}
	}else{
		die("Invalide news!");
	}

}elseif( isset($_GET['p']) and $_GET['p']=='newsrank' and isset($_GET['id']) and isset($_GET['r']) ){

	$id = esc($_GET['id']);
	if( esc($_GET['r']) == 'up' ){ $rank = "u"; }else{ $rank = "d"; }

	$news = get_news($id);

	if( $news ){
		if( $rank == "d" ){
			$old_rank_update = mysql_query("UPDATE `news` SET `rank`=".$news['rank']." WHERE `rank`=".($news['rank']+1));
			$current_rank_update = mysql_query("UPDATE `news` SET `rank`=".($news['rank']+1)." WHERE `id`=".$id);
		}else{
			$old_rank_update = mysql_query("UPDATE `news` SET `rank`=".$news['rank']." WHERE `rank`=".($news['rank']-1));
			$current_rank_update = mysql_query("UPDATE `news` SET `rank`=".($news['rank']-1)." WHERE `id`=".$id);
		}

		if( !$old_rank_update or !$current_rank_update ){
			die("Error! Could not update rank.");
		}else{
			redirect("news.php?mess=News+Rank+Updated!");
		}
	}else{
		die("Invalide news!");
	}

}elseif( isset($_POST['p']) and $_POST['p']=='AddPage' ){

	if( isset($_POST['title']) and !empty($_POST['title']) ){
		
		$title = esc($_POST['title']);
		$keywords = esc($_POST['keywords']);
		$description = esc($_POST['description']);
		$text = esc($_POST['text']);

		if( isset($_FILES['img_thumb']) and !empty($_FILES['img_thumb']['name']) ){
			$thumb_file_ext = get_file_extension(esc($_FILES['img_thumb']['name']));
			if( !only_img($thumb_file_ext) ){ die(); }
			$thumb_filename = randomKey(10).".".$thumb_file_ext;
			$move = move_uploaded_file($_FILES['img_thumb']['tmp_name'], $__dir_attimgs.$thumb_filename);
			if( !$move ){ die("Error! Could not upload thumb."); }
		}else{
			$thumb_filename = "";
		}

		if( isset($_FILES['img_banner']) and !empty($_FILES['img_banner']['name']) ){
			$banner_file_ext = get_file_extension(esc($_FILES['img_banner']['name']));
			if( !only_img($banner_file_ext) ){ die(); }
			$banner_filename = randomKey(10).".".$banner_file_ext;
			$move = move_uploaded_file($_FILES['img_banner']['tmp_name'], $__dir_attimgs.$banner_filename);
			if( !$move ){ die("Error! Could not upload banner."); }
		}else{
			$banner_filename = "";
		}

		$query = mysql_query("INSERT INTO `pages`(`id`, `title`, `keywords`, `description`, `text`, `img_thumb`, `img_banner`) VALUES (NULL, '".$title."', '".$keywords."', '".$description."', '".$text."', '".$thumb_filename."', '".$banner_filename."')");
		if( $query ){
			redirect("pages.php?mess=Page+has+been+added!");
		}else{
			die("Error! Could not add the page.");
		}

	}else{
		redirect("addpage.php?mess=Please+fill+the+required+fileds!");
	}

}elseif( isset($_POST['p']) and $_POST['p']=='EditPage' and isset($_POST['id']) ){

	if( isset($_POST['id']) and isset($_POST['title']) and !empty($_POST['title']) ){
		
		$id = esc($_POST['id']);
		$page = get_page($id);

		$title = esc($_POST['title']);
		$keywords = esc($_POST['keywords']);
		$description = esc($_POST['description']);
		$text = esc($_POST['text']);

		if( $page ){

			if( isset($_FILES['img_thumb']) and !empty($_FILES['img_thumb']['name']) ){
				if( $page['img_thumb']!="" ){ unlink($__dir_attimgs.$page['img_thumb']); }
				$thumb_file_ext = get_file_extension(esc($_FILES['img_thumb']['name']));
				if( !only_img($thumb_file_ext) ){ die(); }
				$thumb_filename = randomKey(10).".".$thumb_file_ext;
				$move = move_uploaded_file($_FILES['img_thumb']['tmp_name'], $__dir_attimgs.$thumb_filename);
				if( !$move ){ die("Error! Could not upload thumb."); }
			}else{
				$thumb_filename = $page['img_thumb'];
			}

			if( isset($_FILES['img_banner']) and !empty($_FILES['img_banner']['name']) ){
				if( $page['img_banner']!="" ){ unlink($__dir_attimgs.$page['img_banner']); }
				$banner_file_ext = get_file_extension(esc($_FILES['img_banner']['name']));
				if( !only_img($banner_file_ext) ){ die(); }
				$banner_filename = randomKey(10).".".$banner_file_ext;
				$move = move_uploaded_file($_FILES['img_banner']['tmp_name'], $__dir_attimgs.$banner_filename);
				if( !$move ){ die("Error! Could not upload banner."); }
			}else{
				$banner_filename = $page['img_banner'];
			}

			$query = mysql_query("UPDATE `pages` SET `title`='".$title."', `keywords`='".$keywords."', `description`='".$description."', `text`='".$text."', `img_thumb`='".$thumb_filename."',`img_banner`='".$banner_filename."' WHERE `id`=".$id);
			
			if( $query ){
				redirect("pages.php?mess=Page+has+been+updated!");
			}else{
				die("Error! Could not edit the page.");
			}

		}else{
			die("Error! Invalide Page.");
		}

	}else{
		redirect("addpage.php?mess=Please+fill+the+required+fileds!");
	}

}elseif( isset($_POST['p']) and $_POST['p']=='DelPage' and isset($_POST['id']) ){

	$id = esc($_POST['id']);
	$page = get_page($id);

	if( $page ){

		if( $page['img_thumb']!="" ){ unlink($__dir_attimgs.$page['img_thumb']); }
		if( $page['img_banner']!="" ){ unlink($__dir_attimgs.$page['img_banner']); }
		
		$query = mysql_query("DELETE FROM `pages` WHERE `id`=".$id);
		
		if( $query ){
			redirect("pages.php?mess=Page+has+been+deleted!");
		}else{
			die("Error! Could not delete the page.");
		}

	}else{
		die("Error! Invalide Page.");
	}

}elseif( isset($_POST['p']) and $_POST['p']=='AddGallery' ){

	if( isset($_POST['product']) and isset($_FILES['image']) and !empty($_FILES['image']['name']) ){

		$product = esc($_POST['product']);
		if( $product != 0 ){ if(!is_valide_product($product)){ die("Error! Invalide Product."); } }
		$title = esc($_POST['title']);
		$description = esc($_POST['description']);
		$rank = get_next_gallery_rank($product);

		$file_ext = get_file_extension(esc($_FILES['image']['name']));
		$file_name = randomKey(10).".".$file_ext;
		if( !only_img($file_ext) ){ die(); }
		$move = move_uploaded_file($_FILES['image']['tmp_name'], $__dir_gallery.$file_name);
		if( !$move ){ die("Error! Could not upload image."); }

		$query = mysql_query("INSERT INTO `gallery`(`id`, `product`, `title`, `description`, `image`, `rank`) VALUES (NULL, ".$product.", '".$title."', '".$description."', '".$file_name."', ".$rank.")");
		if( !$query ){ die("Error! Could not insert the record."); }

		if( $product == 0 ){
			redirect("gallery.php?mess=Image+added!");
		}else{
			redirect("gallery.php?product=".$product."&mess=Image+added!");
		}

	}else{
		if( $_POST['product'] == 0 ){
			redirect("gallery.php?mess=Please+fill+the+required+fileds!");
		}else{
			redirect("gallery.php?product=".$_POST['product']."&mess=Please+fill+the+required+fileds!");
		}
	}

}elseif( isset($_POST['p']) and $_POST['p']=='EditGallery' and isset($_POST['id']) ){

	if( isset($_POST['id']) and is_numeric($_POST['id']) ){
		
		$id = esc($_POST['id']);
		$gallery = get_gallery($id);

		if( $gallery ){

			$title = esc($_POST['title']);
			$description = esc($_POST['description']);

			if( isset($_FILES['image']) and !empty($_FILES['image']['name']) ){
				if( $gallery['image']!="" ){ unlink($__dir_gallery.$gallery['image']); }
				$file_ext = get_file_extension(esc($_FILES['image']['name']));
				$file_name = randomKey(10).".".$file_ext;
				if( !only_img($file_ext) ){ die(); }
				$move = move_uploaded_file($_FILES['image']['tmp_name'], $__dir_gallery.$file_name);
				if( !$move ){ die("Error! Could not upload image."); }
			}else{
				$file_name = $gallery['image'];
			}

			$query = mysql_query("UPDATE `gallery` SET `title`='".$title."',`description`='".$description."',`image`='".$file_name."' WHERE `id`=".$id);
			if( !$query ){ die("Error! Could not update the record!"); }

			if( $_POST['product'] == 0 ){
				redirect("gallery.php?mess=Updated!");
			}else{
				redirect("gallery.php?product=".$_POST['product']."&mess=Updated!");
			}

		}else{
			die("Error! Invalide ID!");
		}

	}else{
		die("Error! Invalide ID!");
	}

}elseif( isset($_GET['p']) and $_GET['p']=='gallrank' and isset($_GET['id']) and isset($_GET['r']) ){

	$id = esc($_GET['id']);
	if( esc($_GET['r']) == 'up' ){ $rank = "u"; }else{ $rank = "d"; }
	$gallery = get_gallery($id);

	if( $gallery ){
		if( $rank == "d" ){
			$old_rank_update = mysql_query("UPDATE `gallery` SET `rank`=".$gallery['rank']." WHERE `product`=".$gallery['product']." AND `rank`=".($gallery['rank']+1));
			$current_rank_update = mysql_query("UPDATE `gallery` SET `rank`=".($gallery['rank']+1)." WHERE `id`=".$id);
		}else{
			$old_rank_update = mysql_query("UPDATE `gallery` SET `rank`=".$gallery['rank']." WHERE `product`=".$gallery['product']." AND `rank`=".($gallery['rank']-1));
			$current_rank_update = mysql_query("UPDATE `gallery` SET `rank`=".($gallery['rank']-1)." WHERE `id`=".$id);
		}

		if( !$old_rank_update or !$current_rank_update ){ die("Error! Could not update rank."); }

		if( $gallery['product'] == 0 ){
			redirect("gallery.php?mess=Rank+Updated!");
		}else{
			redirect("gallery.php?product=".$gallery['product']."&mess=Rank+Updated!");
		}
	}else{
		die("Error! Invalide ID.");
	}

}elseif( isset($_POST['p']) and $_POST['p']=='DelGallery' and isset($_POST['id']) ){

	$id = esc($_POST['id']);
	$gallery = get_gallery($id);

	if( $gallery ){

		unlink($__dir_gallery.$gallery['image']);

		$query = mysql_query("DELETE FROM `gallery` WHERE `id`=".$id);
		if( !$query ){ die("Error! Could not delete the record!"); }

		if( $gallery['product'] == 0 ){
			redirect("gallery.php?mess=Record+deleted!");
		}else{
			redirect("gallery.php?product=".$gallery['product']."&mess=Record+deleted!");
		}

	}else{
		die("Error! Invalide ID.");
	}

}elseif( isset($_POST['p']) and $_POST['p']=='AddCategory' ){

	if( isset($_POST['name']) and !empty($_POST['name']) and isset($_POST['parent']) and is_numeric($_POST['parent']) ){

		$name = esc($_POST['name']);
		$parent = esc($_POST['parent']);
		$slug = make_category_slug_parent($name, $parent);		
		$title = empty($_POST['title']) ? $name : esc($_POST['title']);
		$keywords = esc($_POST['keywords']);
		$description = esc($_POST['description']);
		$rank = get_next_category_rank($parent);

		if( isset($_FILES['img_thumb']) and !empty($_FILES['img_thumb']['name']) ){
			$thumb_file_ext = get_file_extension(esc($_FILES['img_thumb']['name']));
			$thumb_filename = randomKey(10).".".$thumb_file_ext;
			if( !only_img($thumb_file_ext) ){ die(); }
			$move = move_uploaded_file($_FILES['img_thumb']['tmp_name'], $__dir_attimgs.$thumb_filename);
			if( !$move ){ die("Error! Could not upload thumb."); }
		}else{
			$thumb_filename = "";
		}

		if( isset($_FILES['img_banner']) and !empty($_FILES['img_banner']['name']) ){
			$banner_file_ext = get_file_extension(esc($_FILES['img_banner']['name']));
			$banner_filename = randomKey(10).".".$banner_file_ext;
			if( !only_img($banner_file_ext) ){ die(); }
			$move = move_uploaded_file($_FILES['img_banner']['tmp_name'], $__dir_attimgs.$banner_filename);
			if( !$move ){ die("Error! Could not upload banner."); }
		}else{
			$banner_filename = "";
		}

		if( isset($_FILES['img_x']) and !empty($_FILES['img_x']['name']) ){
			$extra_img_ext = get_file_extension(esc($_FILES['img_x']['name']));
			$extra_img_filename = randomKey(10).".".$extra_img_ext;
			if( !only_img($extra_img_ext) ){ die(); }
			$move = move_uploaded_file($_FILES['img_x']['tmp_name'], $__dir_attimgs.$extra_img_filename);
			if( !$move ){ die("Error! Could not upload Extra Image."); }
		}else{
			$extra_img_filename = "";
		}

		$query = mysql_query("INSERT INTO `categories`(`id`, `name`, `slug`, `title`, `keywords`, `description`, `parent`, `rank`, `show`, `img_thumb`, `img_banner`, `img_x`) VALUES (NULL, '".$name."', '".$slug."', '".$title."', '".$keywords."', '".$description."', '".$parent."', '".$rank."', 'y', '".$thumb_filename."', '".$banner_filename."', '".$extra_img_filename."')");

		if( !$query ){ die("Error! Could not add new category."); }

		if( $parent == 0 ){
			redirect("categories.php?mess=Category+has+been+added!");
		}else{
			redirect("subcategories.php?parent=".$parent."&mess=Category+has+been+added!");
		}
		
	}else{
		if( $parent == 0 ){
			redirect("categories.php?mess=Please+fill+the+required+fileds!");
		}else{
			redirect("subcategories.php?parent=".$parent."&mess=Please+fill+the+required+fileds!");
		}
	}

}elseif( isset($_POST['p']) and $_POST['p']=='EditCategory' and isset($_POST['id']) ){

	if( isset($_POST['id']) and is_numeric($_POST['id']) and isset($_POST['name']) and !empty($_POST['name']) ){
		
		$id = esc($_POST['id']);
		$cat = get_cateogry($id);

		if( $cat ){
			$name = esc($_POST['name']);
			$slug = esc($_POST['slug']);
			$title = empty($_POST['title']) ? $name : esc($_POST['title']);
			$keywords = esc($_POST['keywords']);
			$description = esc($_POST['description']);

			if( $name==$cat['name'] AND $slug==$cat['slug'] ){
				$slug = $cat['slug'];
			}elseif( empty($slug) ){
				$slug = make_category_slug_parent($name, $cat['parent']);
			}else{
				$slug = make_category_slug_parent($slug, $cat['parent']);
			}

			if( isset($_FILES['img_thumb']) and !empty($_FILES['img_thumb']['name']) ){
				if( $cat['img_thumb']!="" ){ unlink($__dir_attimgs.$cat['img_thumb']); }
				$thumb_file_ext = get_file_extension(esc($_FILES['img_thumb']['name']));
				$thumb_filename = randomKey(10).".".$thumb_file_ext;
				if( !only_img($thumb_file_ext) ){ die(); }
				$move = move_uploaded_file($_FILES['img_thumb']['tmp_name'], $__dir_attimgs.$thumb_filename);
				if( !$move ){ die("Error! Could not upload thumb."); }
			}else{
				$thumb_filename = $cat['img_thumb'];
			}

			if( isset($_FILES['img_banner']) and !empty($_FILES['img_banner']['name']) ){
				if( $cat['img_banner']!="" ){ unlink($__dir_attimgs.$cat['img_banner']); }
				$banner_file_ext = get_file_extension(esc($_FILES['img_banner']['name']));
				$banner_filename = randomKey(10).".".$banner_file_ext;
				if( !only_img($banner_file_ext) ){ die(); }
				$move = move_uploaded_file($_FILES['img_banner']['tmp_name'], $__dir_attimgs.$banner_filename);
				if( !$move ){ die("Error! Could not upload banner."); }
			}else{
				$banner_filename = $cat['img_banner'];
			}

			if( isset($_FILES['img_x']) and !empty($_FILES['img_x']['name']) ){
				if( $cat['img_x']!="" ){ unlink($__dir_attimgs.$cat['img_x']); }
				$extra_img_ext = get_file_extension(esc($_FILES['img_x']['name']));
				$extra_img_filename = randomKey(10).".".$extra_img_ext;
				if( !only_img($extra_img_ext) ){ die(); }
				$move = move_uploaded_file($_FILES['img_x']['tmp_name'], $__dir_attimgs.$extra_img_filename);
				if( !$move ){ die("Error! Could not upload Extra Image."); }
			}else{
				$extra_img_filename = $cat['img_x'];
			}

			$query = mysql_query("UPDATE `categories` SET `name`='".$name."',`slug`='".$slug."',`title`='".$title."',`keywords`='".$keywords."',`description`='".$description."', `img_thumb`='".$thumb_filename."',`img_banner`='".$banner_filename."',`img_x`='".$extra_img_filename."' WHERE `id`=".$id);
			if( !$query ){ die("Error! Could not update the category!"); }

			if( $cat['parent'] == 0 ){
				redirect("categories.php?mess=This+category+has+been+updated!");
			}else{
				redirect("subcategories.php?parent=".$cat['parent']."&mess=This+category+has+been+updated!");
			}

		}else{
			die("Error! Invalide category!");
		}

	}else{
		redirect("editcategory.php?id=".$_POST['id']."&mess=Please+fill+the+required+fileds!");
	}

}elseif( isset($_POST['p']) and $_POST['p']=='DelCategory' and isset($_POST['id']) ){

	$id = esc($_POST['id']);
	$cat = get_cateogry($id);

	if( $cat ){

		if( does_category_has_subs($id) ){
			if( $cat['parent'] == 0 ){
				redirect("categories.php?mess=Can+not+delete.+This+category+has+sub+category(s)!");
			}else{
				redirect("subcategories.php?parent=".$cat['parent']."&mess=Can+not+delete.+This+category+has+sub+category(s)!");
			}
		}elseif( has_products($id) ){
			if( $cat['parent'] == 0 ){
				redirect("categories.php?mess=Can+not+delete.+This+category+has+product(s)!");
			}else{
				redirect("subcategories.php?parent=".$cat['parent']."&mess=Can+not+delete.+This+category+has+product(s)!");
			}
		}else{
			if( $cat['img_thumb']!="" ){ unlink($__dir_attimgs.$cat['img_thumb']); }
			if( $cat['img_banner']!="" ){ unlink($__dir_attimgs.$cat['img_banner']); }
			if( $cat['img_x']!="" ){ unlink($__dir_attimgs.$cat['img_x']); }
			$query = mysql_query("DELETE FROM `categories` WHERE `id`=".$id);
			if( !$query ){ die("Error! Could not delete the category!"); }

			if( $cat['parent'] == 0 ){
				redirect("categories.php?mess=This+category+has+been+deleted!");
			}else{
				redirect("subcategories.php?parent=".$cat['parent']."&mess=This+category+has+been+deleted!");
			}
		}

	}else{
		redirect("categories.php?mess=Invalide+category!");
	}

}elseif( isset($_GET['p']) and $_GET['p']=='catrank' and isset($_GET['id']) and isset($_GET['r']) ){

	$id = esc($_GET['id']);
	if( esc($_GET['r']) == 'up' ){ $rank = "u"; }else{ $rank = "d"; }

	$cat = get_cateogry($id);

	if( $cat ){
		if( $rank == "d" ){
			$old_rank_update = mysql_query("UPDATE `categories` SET `rank`=".$cat['rank']." WHERE `parent`=".$cat['parent']." AND `rank`=".($cat['rank']+1));
			$current_rank_update = mysql_query("UPDATE `categories` SET `rank`=".($cat['rank']+1)." WHERE `id`=".$id);
		}else{
			$old_rank_update = mysql_query("UPDATE `categories` SET `rank`=".$cat['rank']." WHERE `parent`=".$cat['parent']." AND `rank`=".($cat['rank']-1));
			$current_rank_update = mysql_query("UPDATE `categories` SET `rank`=".($cat['rank']-1)." WHERE `id`=".$id);
		}

		if( !$old_rank_update or !$current_rank_update ){ die("Error! Could not update rank."); }

		if( $cat['parent'] == 0 ){
			redirect("categories.php?mess=Rank+Updated!");
		}else{
			redirect("subcategories.php?parent=".$cat['parent']."&mess=Rank+Updated!");
		}
	}else{
		redirect("categories.php?mess=Invalide+category!");
	}

}elseif( isset($_GET['p']) and $_GET['p']=='dcat' and isset($_GET['id']) ){

	$id = esc($_GET['id']);
	$cat = get_cateogry($id);

	if( $cat ){

		if( $cat['show'] == "y" ){
			$query = mysql_query("UPDATE `categories` SET `show`='n' WHERE `id`=".$id);
		}else{
			$query = mysql_query("UPDATE `categories` SET `show`='y' WHERE `id`=".$id);
		}

		if( !$query ){ die("Error! Could not update display setting."); }

		if( $cat['parent'] == 0 ){
			redirect("categories.php?mess=Category+Updated!");
		}else{
			redirect("subcategories.php?parent=".$cat['parent']."&mess=Category+Updated!");
		}
	}else{
		die("Invalide category!");
	}

}elseif( isset($_POST['p']) and $_POST['p']=='AddPrd' ){

	if( isset($_POST['art_no']) and !empty($_POST['art_no']) and isset($_POST['parent']) and is_valide_category(esc($_POST['parent'])) ){

		$art_no = esc($_POST['art_no']);
		$slug = make_product_slug($art_no);
		$name = esc($_POST['name']);
		$parent = esc($_POST['parent']);
		$mini_description = esc($_POST['mini_description']);
		$description = esc($_POST['description']);
		$price = esc($_POST['price']);

		$ex_txt_1 = esc($_POST['ex_txt_1']);
		$ex_txt_2 = esc($_POST['ex_txt_2']);
		$ex_txt_3 = esc($_POST['ex_txt_3']);
		$ex_txt_4 = esc($_POST['ex_txt_4']);
		$ex_txt_5 = esc($_POST['ex_txt_5']);
		$ex_txt_6 = esc($_POST['ex_txt_6']);
		$ex_txt_7 = esc($_POST['ex_txt_7']);
		$ex_txt_8 = esc($_POST['ex_txt_8']);
		$ex_txt_9 = esc($_POST['ex_txt_9']);
		$ex_txt_10 = esc($_POST['ex_txt_10']);
		$ex_txt_11 = esc($_POST['ex_txt_11']);
		$ex_txt_12 = esc($_POST['ex_txt_12']);
		$ex_txt_13 = esc($_POST['ex_txt_13']);
		$ex_txt_14 = esc($_POST['ex_txt_14']);
		$ex_txt_15 = esc($_POST['ex_txt_15']);
		
		if( isset($_FILES['img_large']) and !empty($_FILES['img_large']['name']) ){
			$img_large_file_ext = get_file_extension(esc($_FILES['img_large']['name']));
			$img_large_filename = randomKey(10).".".$img_large_file_ext;
			if( !only_img($img_large_file_ext) ){ die(); }
			$move = move_uploaded_file($_FILES['img_large']['tmp_name'], $__dir_lg_imgs.$img_large_filename);
			if( !$move ){ die("Error! Could not upload large image."); }
		}else{
			$img_large_filename = "";
		}

		if( isset($_FILES['img_small']) and !empty($_FILES['img_small']['name']) ){
			$img_small_file_ext = get_file_extension(esc($_FILES['img_small']['name']));
			$img_small_filename = randomKey(10).".".$img_small_file_ext;
			if( !only_img($img_small_file_ext) ){ die(); }
			$move = move_uploaded_file($_FILES['img_small']['tmp_name'], $__dir_sm_imgs.$img_small_filename);
			if( !$move ){ die("Error! Could not upload small image."); }
		}elseif( !empty($img_large_filename) ){
			
			$original_image_size = getimagesize($__dir_lg_imgs.$img_large_filename);
			$original_width = $original_image_size[0];
			$original_height = $original_image_size[1];

			if($img_large_file_ext == 'jpg'){
				$original_image_gd = imagecreatefromjpeg($__dir_lg_imgs.$img_large_filename);
			}elseif($img_large_file_ext == 'gif'){
				$original_image_gd = imagecreatefromgif($__dir_lg_imgs.$img_large_filename);
				imagealphablending($original_image_gd, false);
				imagesavealpha($original_image_gd,true);
			}elseif($img_large_file_ext == 'png'){
				$original_image_gd = imagecreatefrompng($__dir_lg_imgs.$img_large_filename);
				imagealphablending($original_image_gd, false);
				imagesavealpha($original_image_gd,true);
			}

			if( $original_width > $original_height ){
				$new_width = _setting_img_sm_width_;
				$new_height = ( $original_height / $original_width ) * _setting_img_sm_width_;
			}elseif( $original_width < $original_height ){
				$new_height = _setting_img_sm_height_;
				$new_width = ( $original_width / $original_height ) * _setting_img_sm_height_;
			}else{
				$new_width = _setting_img_sm_width_;
				$new_height = _setting_img_sm_height_;
			}

			$cropped_image_gd = imagecreatetruecolor($new_width, $new_height);
			$backgroundColor = imagecolorallocate($cropped_image_gd, 255, 255, 255);
			imagefill($cropped_image_gd, 0, 0, $backgroundColor);

			imagecopyresampled($cropped_image_gd , $original_image_gd ,0,0,0,0, $new_width, $new_height, $original_width , $original_height );

			$img_small_filename = randomKey(10).".".$img_large_file_ext;

			if($img_large_file_ext == 'jpg'){
				$create = imagejpeg($cropped_image_gd, $__dir_sm_imgs.$img_small_filename, 100);
			}elseif($img_large_file_ext == 'gif'){
				$create = imagegif($cropped_image_gd, $__dir_sm_imgs.$img_small_filename);
			}elseif($img_large_file_ext == 'png'){
				$create = imagepng($cropped_image_gd, $__dir_sm_imgs.$img_small_filename, 9);
			}

		}else{
			$img_small_filename = "";
		}

		if( isset($_FILES['img_xlarge']) and !empty($_FILES['img_xlarge']['name']) ){
			$img_xlarge_file_ext = get_file_extension(esc($_FILES['img_xlarge']['name']));
			$img_xlarge_filename = randomKey(10).".".$img_xlarge_file_ext;
			if( !only_img($img_xlarge_file_ext) ){ die(); }
			$move = move_uploaded_file($_FILES['img_xlarge']['tmp_name'], $__dir_xlg_imgs.$img_xlarge_filename);
			if( !$move ){ die("Error! Could not upload xlarge image."); }
		}else{
			$img_xlarge_filename = "";
		}

		$product_id = get_nextid("products");

		$attribute_query = mysql_query("SELECT * FROM `attributes` ORDER BY `rank` ASC");
		if( mysql_num_rows($attribute_query) > 0 ){
			while( $RS_attribute = mysql_fetch_array($attribute_query) ){
				if( isset($_POST["attribute_".$RS_attribute['id']]) and $_POST["attribute_".$RS_attribute['id']]=="on" ){
					$query_attribute = mysql_query("INSERT INTO `prd_attributes`(`id`, `prd_id`, `variation_id`, `attribute_id`) VALUES ( NULL, ".$product_id.", ".$RS_attribute['variation_id'].", ".$RS_attribute['id'].")");
					if( !$query_attribute ){ die("Error! Could not insert product/attribute connection."); }
				}
			}
		}

		$query = mysql_query("INSERT INTO `products`(`id`, `parent`, `art_no`, `slug`, `name`, `mini_description`, `description`, `price`, `rank`, `grp_fet`, `grp_new`, `grp_hot`, `img_small`, `img_large`, `img_xlarge`, `ex_txt_1`, `ex_txt_2`, `ex_txt_3`, `ex_txt_4`, `ex_txt_5`, `ex_txt_6`, `ex_txt_7`, `ex_txt_8`, `ex_txt_9`, `ex_txt_10`, `ex_txt_11`, `ex_txt_12`, `ex_txt_13`, `ex_txt_14`, `ex_txt_15`, `show`) VALUES (NULL, ".$parent.", '".$art_no."', '".$slug."', '".$name."', '".$mini_description."', '".$description."', '".$price."', ".get_next_product_rank($parent).", 'n', 'n', 'n', '".$img_small_filename."', '".$img_large_filename."', '".$img_xlarge_filename."', '".$ex_txt_1."', '".$ex_txt_2."', '".$ex_txt_3."', '".$ex_txt_4."', '".$ex_txt_5."', '".$ex_txt_6."', '".$ex_txt_7."', '".$ex_txt_8."', '".$ex_txt_9."', '".$ex_txt_10."', '".$ex_txt_11."', '".$ex_txt_12."', '".$ex_txt_13."', '".$ex_txt_14."', '".$ex_txt_15."', 'y')");
		$new_id = mysql_insert_id();
		
		if( $query ){
			if( isset($_POST['CopyPrd']) and $_POST['CopyPrd']=="true" ){
                redirect("copyprd.php?id=".$new_id);
			}else{
			    redirect("listprd.php?parent=".esc($_POST['parent'])."&mess=Product+Added!");
			}
			
		}else{
			die("Error! Could not insert product.");
		}

	}else{
		redirect("addprd.php?parent=".esc($_POST['parent'])."&mess=Invalide+values!");
	}

}elseif( isset($_POST['p']) and $_POST['p']=='EditPrd' and isset($_POST['id']) ){

	$id = esc($_POST['id']);
	$prd = get_product($id);

	$art_no = esc($_POST['art_no']);
	$slug = esc($_POST['slug']);
	$name = esc($_POST['name']);
	$mini_description = esc($_POST['mini_description']);
	$description = esc($_POST['description']);
	$price = esc($_POST['price']);

	$ex_txt_1 = esc($_POST['ex_txt_1']);
	$ex_txt_2 = esc($_POST['ex_txt_2']);
	$ex_txt_3 = esc($_POST['ex_txt_3']);
	$ex_txt_4 = esc($_POST['ex_txt_4']);
	$ex_txt_5 = esc($_POST['ex_txt_5']);
	$ex_txt_6 = esc($_POST['ex_txt_6']);
	$ex_txt_7 = esc($_POST['ex_txt_7']);
	$ex_txt_8 = esc($_POST['ex_txt_8']);
	$ex_txt_9 = esc($_POST['ex_txt_9']);
	$ex_txt_10 = esc($_POST['ex_txt_10']);
	$ex_txt_11 = esc($_POST['ex_txt_11']);
	$ex_txt_12 = esc($_POST['ex_txt_12']);
	$ex_txt_13 = esc($_POST['ex_txt_13']);
	$ex_txt_14 = esc($_POST['ex_txt_14']);
	$ex_txt_15 = esc($_POST['ex_txt_15']);

	if( $art_no==$prd['art_no'] AND $slug==$prd['slug'] ){
		$slug = $prd['slug'];
	}elseif( $art_no==$prd['art_no'] ){
		$slug = make_product_slug($slug, $id);
	}else{
		$slug = make_product_slug($art_no, $id);
	}

	if( $prd ){

		if( isset($_FILES['img_small']) and !empty($_FILES['img_small']['name']) ){
			if( $prd['img_small']!="" ){ unlink($__dir_sm_imgs.$prd['img_small']); }
			$img_small_file_ext = get_file_extension(esc($_FILES['img_small']['name']));
			$img_small_filename = randomKey(10).".".$img_small_file_ext;
			if( !only_img($img_small_file_ext) ){ die(); }
			$move = move_uploaded_file($_FILES['img_small']['tmp_name'], $__dir_sm_imgs.$img_small_filename);
			if( !$move ){ die("Error! Could not upload small image."); }
		}else{
			$img_small_filename = $prd['img_small'];
		}

		if( isset($_FILES['img_large']) and !empty($_FILES['img_large']['name']) ){
			if( $prd['img_large']!="" ){ unlink($__dir_lg_imgs.$prd['img_large']); }
			$img_large_file_ext = get_file_extension(esc($_FILES['img_large']['name']));
			$img_large_filename = randomKey(10).".".$img_large_file_ext;
			if( !only_img($img_large_file_ext) ){ die(); }
			$move = move_uploaded_file($_FILES['img_large']['tmp_name'], $__dir_lg_imgs.$img_large_filename);
			if( !$move ){ die("Error! Could not upload large image."); }
		}else{
			$img_large_filename = $prd['img_large'];
		}

		if( isset($_FILES['img_xlarge']) and !empty($_FILES['img_xlarge']['name']) ){
			if( $prd['img_xlarge']!="" ){ unlink($__dir_xlg_imgs.$prd['img_xlarge']); }
			$img_xlarge_file_ext = get_file_extension(esc($_FILES['img_xlarge']['name']));
			$img_xlarge_filename = randomKey(10).".".$img_xlarge_file_ext;
			if( !only_img($img_xlarge_file_ext) ){ die(); }
			$move = move_uploaded_file($_FILES['img_xlarge']['tmp_name'], $__dir_xlg_imgs.$img_xlarge_filename);
			if( !$move ){ die("Error! Could not upload xlarge image."); }
		}else{
			$img_xlarge_filename = $prd['img_xlarge'];
		}

		$attribute_delete = mysql_query("DELETE FROM `prd_attributes` WHERE `prd_id`=".$id);
		
		$attribute_query = mysql_query("SELECT * FROM `attributes` ORDER BY `rank` ASC");
		if( mysql_num_rows($attribute_query) > 0 ){
			while( $RS_attribute = mysql_fetch_array($attribute_query) ){
				if( isset($_POST["attribute_".$RS_attribute['id']]) and $_POST["attribute_".$RS_attribute['id']]=="on" ){
					$query_attribute = mysql_query("INSERT INTO `prd_attributes`(`id`, `prd_id`, `variation_id`, `attribute_id`) VALUES ( NULL, ".$id.", ".$RS_attribute['variation_id'].", ".$RS_attribute['id'].")");
					if( !$query_attribute ){ die("Error! Could not insert product/attribute connection."); }
				}
			}
		}

		$query = mysql_query("UPDATE `products` SET `art_no`='".$art_no."', `slug`= '".$slug."', `name`='".$name."', `mini_description`='".$mini_description."', `description`='".$description."', `price`='".$price."', `img_small`='".$img_small_filename."', `img_large`='".$img_large_filename."', `img_xlarge`='".$img_xlarge_filename."', `ex_txt_1`='".$ex_txt_1."', `ex_txt_2`='".$ex_txt_2."', `ex_txt_3`='".$ex_txt_3."', `ex_txt_4`='".$ex_txt_4."', `ex_txt_5`='".$ex_txt_5."', `ex_txt_6`='".$ex_txt_6."', `ex_txt_7`='".$ex_txt_7."', `ex_txt_8`='".$ex_txt_8."', `ex_txt_9`='".$ex_txt_9."', `ex_txt_10`='".$ex_txt_10."', `ex_txt_11`='".$ex_txt_11."', `ex_txt_12`='".$ex_txt_12."', `ex_txt_13`='".$ex_txt_13."', `ex_txt_14`='".$ex_txt_14."', `ex_txt_15`='".$ex_txt_15."' WHERE `id`=".$id);

		if( $query ){
			redirect("listprd.php?parent=".esc($prd['parent'])."&mess=Product+Updated!");
		}else{
			die("Error! Could not update product.");
		}

	}else{
		die("Invalide product!");
	}

}elseif( isset($_POST['p']) and $_POST['p']=='smlPrdEdit' and isset($_POST['id']) ){

	$id = esc($_POST['id']);
	$prd = get_product($id);

	$art_no = esc($_POST['art_no']);
	$name = esc($_POST['name']);
	$price = esc($_POST['price']);

	if( $art_no==$prd['art_no'] ){
		$slug = $prd['slug'];
	}else{
		$slug = make_product_slug($art_no, $id);
	}

	if( $prd ){

		if( empty($art_no) or empty($name) or empty($price) ){ echo "a"; /*redirect( urldecode($_POST['r2']) );*/ }

		$query = mysql_query("UPDATE `products` SET `art_no`='".$art_no."', `slug`= '".$slug."', `name`='".$name."', `price`='".$price."' WHERE `id`=".$id);

		if( $query ){
			redirect( urldecode($_POST['r2']) );
		}else{
			die("Error! Could not update product.");
		}

	}else{
		die("Invalide product!");
	}

}elseif( isset($_GET['p']) and $_GET['p']=='DelPrd' and isset($_GET['id']) ){

	$id = esc($_GET['id']);
	$prd = get_product($id);

	if( $prd ){
		if( $prd['img_small']!="" ){ unlink($__dir_sm_imgs.$prd['img_small']); }
		if( $prd['img_large']!="" ){ unlink($__dir_lg_imgs.$prd['img_large']); }
		if( $prd['img_xlarge']!="" ){ unlink($__dir_xlg_imgs.$prd['img_xlarge']); }

		$delete_attribute = mysql_query("DELETE INTO `prd_attributes` WHERE `prd_id`=".$id.")");

		$del_query = mysql_query("DELETE FROM `products` WHERE `id`=".$id);
		if( !$del_query ){ die("Error! Could not delete the product."); }

		if( isset($_GET['r2']) and !empty($_GET['r2']) ){ redirect($_GET['r2']); }
		else{ redirect("listprd.php?parent=".$prd['parent']."&mess=Product+Deleted!"); }
		
	}else{
		die("Invalide product!");
	}

}elseif( isset($_GET['p']) and $_GET['p']=='prdrank' and isset($_GET['id']) and isset($_GET['r']) ){

	$id = esc($_GET['id']);
	if( esc($_GET['r']) == 'up' ){ $rank = "u"; }else{ $rank = "d"; }

	$prd = get_product($id);

	if( $prd ){
		if( $rank == "d" ){
			$old_rank_update = mysql_query("UPDATE `products` SET `rank`=".$prd['rank']." WHERE `parent`=".$prd['parent']." AND `rank`=".($prd['rank']+1));
			$current_rank_update = mysql_query("UPDATE `products` SET `rank`=".($prd['rank']+1)." WHERE `id`=".$id);
		}else{
			$old_rank_update = mysql_query("UPDATE `products` SET `rank`=".$prd['rank']." WHERE `parent`=".$prd['parent']." AND `rank`=".($prd['rank']-1));
			$current_rank_update = mysql_query("UPDATE `products` SET `rank`=".($prd['rank']-1)." WHERE `id`=".$id);
		}

		if( !$old_rank_update or !$current_rank_update ){
			die("Error! Could not update rank.");
		}else{
			redirect("listprd.php?parent=".$prd['parent']."&mess=Product+Updated!");
		}
	}else{
		die("Invalide product!");
	}

}elseif( isset($_GET['p']) and $_GET['p']=='dprd' and isset($_GET['id']) ){

	$id = esc($_GET['id']);
	$prd = get_product($id);

	if( $prd ){

		if( $prd['show'] == "y" ){
			$query = mysql_query("UPDATE `products` SET `show`='n' WHERE `id`=".$id);
		}else{
			$query = mysql_query("UPDATE `products` SET `show`='y' WHERE `id`=".$id);
		}

		if( !$query ){
			die("Error! Could not update display setting.");
		}else{
			if( isset($_GET['r2']) and !empty($_GET['r2']) ){ redirect($_GET['r2']); }
			else{ redirect("listprd.php?parent=".$prd['parent']."&mess=Product+Updated!"); }
		}
	}else{
		die("Invalide product!");
	}

}elseif( isset($_GET['p']) and $_GET['p']=='is_fet' and isset($_GET['id']) ){

	$id = esc($_GET['id']);
	$prd = get_product($id);

	if( $prd ){

		if( $prd['grp_fet']=='y' ){
			$query = mysql_query("UPDATE `products` SET `grp_fet`='n' WHERE `id`=".$id);
		}else{
			$query = mysql_query("UPDATE `products` SET `grp_fet`='y' WHERE `id`=".$id);
		}

		if( !$query ){
			die("Error! Could not update display setting.");
		}else{
			if( isset($_GET['r2']) and !empty($_GET['r2']) ){ redirect($_GET['r2']); }
			else{ redirect("listprd.php?parent=".$prd['parent']."&mess=Product+Updated!"); }
		}
	}else{
		die("Invalide product!");
	}

}elseif( isset($_GET['p']) and $_GET['p']=='is_new' and isset($_GET['id']) ){

	$id = esc($_GET['id']);
	$prd = get_product($id);

	if( $prd ){

		if( $prd['grp_new']=='y' ){
			$query = mysql_query("UPDATE `products` SET `grp_new`='n' WHERE `id`=".$id);
		}else{
			$query = mysql_query("UPDATE `products` SET `grp_new`='y' WHERE `id`=".$id);
		}

		if( !$query ){
			die("Error! Could not update display setting.");
		}else{
			if( isset($_GET['r2']) and !empty($_GET['r2']) ){ redirect($_GET['r2']); }
			else{ redirect("listprd.php?parent=".$prd['parent']."&mess=Product+Updated!"); }
		}
	}else{
		die("Invalide product!");
	}

}elseif( isset($_GET['p']) and $_GET['p']=='is_hot' and isset($_GET['id']) ){

	$id = esc($_GET['id']);
	$prd = get_product($id);

	if( $prd ){

		if( $prd['grp_hot']=='y' ){
			$query = mysql_query("UPDATE `products` SET `grp_hot`='n' WHERE `id`=".$id);
		}else{
			$query = mysql_query("UPDATE `products` SET `grp_hot`='y' WHERE `id`=".$id);
		}

		if( !$query ){
			die("Error! Could not update display setting.");
		}else{
			if( isset($_GET['r2']) and !empty($_GET['r2']) ){ redirect($_GET['r2']); }
			else{ redirect("listprd.php?parent=".$prd['parent']."&mess=Product+Updated!"); }
		}
	}else{
		die("Invalide product!");
	}

}elseif( isset($_GET['p']) and $_GET['p']=='branddisplay' and isset($_GET['id']) and isset($_GET['set']) ){

  $query = mysql_query("UPDATE `brands` set `show`='".$_GET['set']."' WHERE `recid`=".$_GET['id']);
  redirect("brands.php?mess=Brand+display+updated!");

}elseif( isset($_POST['p']) and $_POST['p']=='AddVariationGroup' ){

	$name = esc($_POST['name']);

	if( empty($name) ){ redirect("variation_group.php?mess=Please enter a name."); }
	$rank = get_next_rank("variation_groups");

	$query = mysql_query("INSERT INTO `variation_groups`(`id`, `name`, `rank`) VALUES (NULL, '".$name."', ".$rank.")");
	if( $query ){
		redirect("variation_group.php?mess=Variation Group added!");
	}else{
		die("Error! Could not insert the variation group.");
	}

}elseif( isset($_POST['p']) and $_POST['p']=='EditVariationGroup' and isset($_POST['id']) and is_numeric($_POST['id']) ){
	
	$id = esc($_POST['id']);
	$name = esc($_POST['name']);

	if( empty($name) ){ redirect("editvariationgroup.php?id=".$id."&mess=Please enter a name."); }

	$query = mysql_query("UPDATE `variation_groups` SET `name`='".$name."' WHERE `id`=".$id);
	if( $query ){
		redirect("variation_group.php?mess=Variation Group updated!");
	}else{
		die("Error! Could not update variation group.");
	}

}elseif( isset($_POST['p']) and $_POST['p']=='DelVariationGroup' ){

	$id = esc($_POST['id']);

	$query = mysql_query("UPDATE `variations` SET `group_id`=0 WHERE `group_id`=".$id);
	$query_2 = mysql_query("DELETE FROM `variation_groups` WHERE `id`=".$id);
	if( $query and $query_2 ){
		redirect("variation_group.php?mess=Variation Group deleted!.");
	}else{
		die("Error! Could not delete variation group.");
	}

}elseif( isset($_GET['p']) and $_GET['p']=='variationgrouprank' and isset($_GET['id']) and isset($_GET['r']) ){

	$id = esc($_GET['id']);
	if( esc($_GET['r']) == 'up' ){ $rank = "u"; }else{ $rank = "d"; }
	$variation = get_variation_group($id);

	if( $variation ){
		if( $rank == "d" ){
			$old_rank_update = mysql_query("UPDATE `variation_groups` SET `rank`=".$variation['rank']." WHERE `rank`=".($variation['rank']+1));
			$current_rank_update = mysql_query("UPDATE `variation_groups` SET `rank`=".($variation['rank']+1)." WHERE `id`=".$id);
		}else{
			$old_rank_update = mysql_query("UPDATE `variation_groups` SET `rank`=".$variation['rank']." WHERE `rank`=".($variation['rank']-1));
			$current_rank_update = mysql_query("UPDATE `variation_groups` SET `rank`=".($variation['rank']-1)." WHERE `id`=".$id);
		}

		if( !$old_rank_update or !$current_rank_update ){ die("Error! Could not update rank."); }

		redirect("variation_group.php?mess=Rank+Updated!");
		
	}else{
		die("Error! Invalide ID.");
	}

}elseif( isset($_POST['p']) and $_POST['p']=='AddVariation' ){

	$name = esc($_POST['name']);
	$description = esc($_POST['description']);
	$group_id = esc($_POST['group_id']);

	if( empty($name) ){ redirect("variation.php?mess=Please enter a name."); }
	$rank = get_next_rank("variations");

	$query = mysql_query("INSERT INTO `variations`(`id`, `group_id`, `name`, `description`, `rank`) VALUES (NULL, '".$group_id."', '".$name."', '".$description."', ".$rank.")");
	if( $query ){
		redirect("variation.php?mess=Variation added!");
	}else{
		die("Error! Could not insert variation.");
	}

}elseif( isset($_POST['p']) and $_POST['p']=='EditVariation' and isset($_POST['id']) and is_numeric($_POST['id']) ){
	
	$id = esc($_POST['id']);
	$name = esc($_POST['name']);
	$description = esc($_POST['description']);
	$group_id = esc($_POST['group_id']);

	if( empty($name) ){ redirect("editvariation.php?id=".$id."&mess=Please enter a name."); }

	$query = mysql_query("UPDATE `variations` SET `name`='".$name."', `group_id`='".$group_id."', `description`='".$description."' WHERE `id`=".$id);
	if( $query ){
		redirect("variation.php?mess=Variation updated!");
	}else{
		die("Error! Could not update variation.");
	}

}elseif( isset($_POST['p']) and $_POST['p']=='DelVariation' ){

	$id = esc($_POST['id']);

	$query = mysql_query("DELETE FROM `attributes` WHERE `variation_id`=".$id);
	$query_2 = mysql_query("DELETE FROM `variations` WHERE `id`=".$id);
	if( $query and $query_2 ){
		redirect("variation.php?mess=Variation deleted!.");
	}else{
		die("Error! Could not delete variation.");
	}

}elseif( isset($_GET['p']) and $_GET['p']=='variationrank' and isset($_GET['id']) and isset($_GET['r']) ){

	$id = esc($_GET['id']);
	if( esc($_GET['r']) == 'up' ){ $rank = "u"; }else{ $rank = "d"; }
	$variation = get_variation($id);

	if( $variation ){
		if( $rank == "d" ){
			$old_rank_update = mysql_query("UPDATE `variations` SET `rank`=".$variation['rank']." WHERE `rank`=".($variation['rank']+1));
			$current_rank_update = mysql_query("UPDATE `variations` SET `rank`=".($variation['rank']+1)." WHERE `id`=".$id);
		}else{
			$old_rank_update = mysql_query("UPDATE `variations` SET `rank`=".$variation['rank']." WHERE `rank`=".($variation['rank']-1));
			$current_rank_update = mysql_query("UPDATE `variations` SET `rank`=".($variation['rank']-1)." WHERE `id`=".$id);
		}

		if( !$old_rank_update or !$current_rank_update ){ die("Error! Could not update rank."); }

		if( $variation['product'] == 0 ){
			redirect("variation.php?mess=Rank+Updated!");
		}else{
			redirect("variation.php?mess=Rank+Updated!");
		}
	}else{
		die("Error! Invalide ID.");
	}

}elseif( isset($_POST['p']) and $_POST['p']=='AddAttribute' ){

	$name = esc($_POST['name']);
	$x_data = esc($_POST['x_data']);
	$variation = esc($_POST['variation']);

	if( empty($name) ){ redirect("attributes.php?variation=".$variation."&mess=Please enter a name."); }
	$rank = get_next_rank("attributes", "`variation_id`=".$variation);

	$query = mysql_query("INSERT INTO `attributes`(`id`, `variation_id`, `name`, `x_data`, `rank`) VALUES (NULL, ".$variation.", '".$name."', '".$x_data."', ".$rank.")");
	if( $query ){
		redirect("attributes.php?variation=".$variation."&mess=Attribute added!");
	}else{
		die("Error! Could not insert attribute.");
	}

}elseif( isset($_POST['p']) and $_POST['p']=='EditAttribute' and isset($_POST['id']) and is_numeric($_POST['id']) ){
	
	$id = esc($_POST['id']);
	$name = esc($_POST['name']);
	$x_data = esc($_POST['x_data']);
	$variation = esc($_POST['variation']);

	if( empty($name) ){ redirect("editattribute.php?variation=".$variation."&id=".$id."&mess=Please enter a name."); }

	$query = mysql_query("UPDATE `attributes` SET `name`='".$name."', `x_data`='".$x_data."' WHERE `id`=".$id);
	if( $query ){
		redirect("attributes.php?variation=".$variation."&mess=Attribute updated!");
	}else{
		die("Error! Could not update attribute.");
	}

}elseif( isset($_POST['p']) and $_POST['p']=='DelAttribute' ){

	$id = esc($_POST['id']);
	$variation = esc($_POST['variation']);

	$query = mysql_query("DELETE FROM `attributes` WHERE `id`=".$id);
	if( $query ){
		redirect("attributes.php?variation=".$variation."&mess=Attribute deleted!");
	}else{
		die("Error! Could not delete attribute.");
	}

}elseif( isset($_GET['p']) and $_GET['p']=='attributerank' and isset($_GET['id']) and isset($_GET['r']) ){

	$id = esc($_GET['id']);
	$variation = esc($_GET['variation']);
	if( esc($_GET['r']) == 'up' ){ $rank = "u"; }else{ $rank = "d"; }
	$attribute = get_attribute($id);

	if( $variation ){
		if( $rank == "d" ){
			$old_rank_update = mysql_query("UPDATE `attributes` SET `rank`=".$attribute['rank']." WHERE `variation_id`=".$variation." AND `rank`=".($attribute['rank']+1));
			$current_rank_update = mysql_query("UPDATE `attributes` SET `rank`=".($attribute['rank']+1)." WHERE `id`=".$id);
		}else{
			$old_rank_update = mysql_query("UPDATE `attributes` SET `rank`=".$attribute['rank']." WHERE `variation_id`=".$variation." AND `rank`=".($attribute['rank']-1));
			$current_rank_update = mysql_query("UPDATE `attributes` SET `rank`=".($attribute['rank']-1)." WHERE `id`=".$id);
		}

		if( !$old_rank_update or !$current_rank_update ){ die("Error! Could not update rank."); }
		redirect("attributes.php?variation=".$variation."&mess=Rank+Updated!");
		
	}else{
		die("Error! Invalide ID.");
	}

}elseif( isset($_POST['p']) and $_POST['p']=='password' ){

	$o_password = esc($_POST['o_password']);
	$password = esc($_POST['password']);
	$a_password = esc($_POST['a_password']);

	if( empty($o_password) or empty($password) or empty($a_password) ){
		redirect('password.php?mess=Please fill all fields.');
	}else{

		if( $password != $a_password ){
			redirect('password.php?mess=New passwords do not match.');
		}elseif( strlen($password) < 3 ){
			redirect('password.php?mess=New password is too small.');
		}
		
		$check_query = mysql_query("SELECT * FROM `tbl_admin` WHERE `usrname`='".$_SESSION['usrname']."' AND `password`='".md5($o_password)."'");
		if( mysql_num_rows($check_query) > 0 ){
			
			$update = mysql_query("UPDATE `tbl_admin` SET `password`='".md5($password)."' WHERE `usrname`='".$_SESSION['usrname']."' AND `password`='".md5($o_password)."'");
			if( $update ){
				redirect('password.php?mess=Password Updated!');
			}else{
				die();
			}

		}else{
			redirect('password.php?mess=Incorect password.');
		}

	}

}else{
	redirect("index.php?np");
}
