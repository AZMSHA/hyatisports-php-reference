<?php

require("config.php"); require("inc/check_session.php");

if( isset($_POST['bulkprd']) and isset($_POST['bulk_action']) and $_POST['bulk_action'] == "delete" ){

	foreach ($_POST['bulkprd'] as $bulkprd){

		$id = $bulkprd;
		$prd = get_product($id);

		if( $prd ){
			if( $prd['img_small']!="" ){ unlink($__dir_sm_imgs.$prd['img_small']); }
			if( $prd['img_large']!="" ){ unlink($__dir_lg_imgs.$prd['img_large']); }
			if( $prd['img_xlarge']!="" ){ unlink($__dir_xlg_imgs.$prd['img_xlarge']); }

			$delete_attribute = mysql_query("DELETE INTO `prd_attributes` WHERE `prd_id`=".$id.")");

			$del_query = mysql_query("DELETE FROM `products` WHERE `id`=".$id);
			if( !$del_query ){ die("Error! Could not delete the product."); }

			if( $delete_attribute and $del_query ){
				if( isset($_POST['r2']) and !empty($_POST['r2']) ){ redirect($_POST['r2']); }
				else{ redirect("listprd.php?parent=".$prd['parent']."&mess=Product+Deleted!"); }
			}
		}else{
			die("Invalide product!");
		}

	}

	redirect("listprd.php?parent=".$_POST['parent']."&mess=Products+Deleted!");

}elseif( isset($_POST['bulkprd']) and isset($_POST['bulk_action']) and $_POST['bulk_action'] == "move" ){

	foreach ($_POST['bulkprd'] as $bulkprd){
		
		$ID = $bulkprd;
		$new_parent = $_POST['new_parent'];
		$new_rank = get_next_product_rank($new_parent);

		$query = mysql_query("UPDATE `products` SET `parent`=".$new_parent.", `rank`=".$new_rank." WHERE `id`=".$ID);
		if( !$query ){ die("Error! Could not move the product."); }

	}

	redirect("listprd.php?parent=".$_POST['new_parent']."&mess=Products+Moved!");

}else{ die("-x-"); }
