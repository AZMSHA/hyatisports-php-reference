<?php include("../admincp/config.php");

$EmlTo = get_option('mail-email');
$EmlTo2 = get_option('mail-email2');

if( isset($_POST['_p']) and $_POST['_p']=='addinq' ){
    
    if( isset($_POST['_pid']) and is_numeric($_POST['_pid']) ){
        
        $product_id = esc($_POST['_pid']);
        
        $qty = esc($_POST['qty']);
        if( is_numeric($qty) and $qty > 0 ){
            
            $check_inquiry = mysql_query("SELECT * FROM `inquiry` WHERE `session_id`='".session_id()."'");
            if( mysql_num_rows($check_inquiry) > 0 ){
                $RS_inquiry = mysql_fetch_array($check_inquiry);
                $inquiry_id = $RS_inquiry['id'];
            }else{
                $inquiry_query = mysql_query("INSERT INTO `inquiry` (`session_id`) VALUES ('".session_id()."')");
                if( !$inquiry_query ){ die('Error! Inquiry error. Please try again later.'); }
                $inquiry_id = mysql_insert_id();
            }
            
            $check_product = mysql_query("SELECT * FROM `inquiry_products` WHERE `inquiry_id`=".$inquiry_id." AND `product_id`='".$product_id."'");
            if( mysql_num_rows($check_product) > 0 ){
                $update_product_qty = mysql_query("UPDATE `inquiry_products` SET `qty`=".$qty." WHERE `inquiry_id`=".$inquiry_id." AND `product_id`='".$product_id."'");
            }else{
                $product_query = mysql_query("INSERT INTO `inquiry_products` (`inquiry_id`, `product_id`, `qty`) VALUES (".$inquiry_id.", ".$product_id.", ".$qty.")");
                if( !$product_query ){ die('Error! Product Inquiry error. Please try again later.'); }
            }
            
            if( prd_has_any_attribute($product_id) ){
                
                $check_inquiry_attribute = mysql_query("DELETE FROM `inquiry_attributes` WHERE `inquiry_id`=".$inquiry_id." AND `product_id`=".$product_id);
                
                $attribute_query = mysql_query("SELECT * FROM `prd_attributes` WHERE `prd_id`=".$product_id);
                while( $RS_attribute = mysql_fetch_array($attribute_query) ){
                    if( isset($_POST['at_'.$RS_attribute['attribute_id']]) and $_POST['at_'.$RS_attribute['attribute_id']] ){
                        
                        $inset = mysql_query("INSERT INTO `inquiry_attributes` (`inquiry_id`, `product_id`, `attribute_id`) VALUES (".$inquiry_id.", ".$product_id.", ".$RS_attribute['attribute_id'].")");
                        if( !$inset ){ die("Error! Attribute error. Please try again later."); }
                        
                    }
                }
                
            }
            
            redirect(_root_.'basket');

        }else{
            redirect(_root_);
        }
        
    }else{
		redirect(_root_);
	}


}elseif( isset($_POST['_p']) and $_POST['_p']=='updinq' ){

	// UPDATE THE QUANITY IN THE CART
	$id = $_POST['_pid'];
	$qty = $_POST['qty'];
	$sec_id = inquiry_sec_to_id();

	$query = mysql_query("UPDATE `inquiry_products` SET `qty`=".$qty." WHERE `inquiry_id`=".$sec_id." AND `product_id`=".$id);
	if( $query ){
		redirect(_root_."basket");
	}else{
		die("Error!");
	}


}elseif( isset($_POST['_p']) and $_POST['_p']=='delinq' ){

	// DELETE FROM CART
	$id = $_POST['_pid'];
	$del = delete_product_from_inquiry($id);

	if( $del ){
		redirect(_root_."basket");
	}else{
		die("Error!");
	}


}elseif( isset($_POST['_p']) and $_POST['_p']=='sendinq' ){

	// SEND THE INQ VIA EMAIL

	if( items_in_basket() == 0 ){ redirect(_root_."basket"); }

	$fname = esc($_POST['fname']);
	$ph = esc($_POST['ph']);
	$fax = esc($_POST['fax']);
	$email = esc($_POST['email']);
	$country = esc($_POST['country']);
	$address = esc($_POST['address']);
	$message = esc($_POST['message']);

	$sec_id = inquiry_sec_to_id();

	$Company = get_option('cname');

	$query = mysql_query("UPDATE `inquiry` SET `first_name`='".$fname."', `phone`='".$ph."', `fax`='".$fax."', `email`='".$email."', `country`='".$country."', `address`='".$address."', `message`='".$message."', `submitted`=1 WHERE `id`=".$sec_id);
	if( !$query ){ die("Error!"); }

	$basket_query = mysql_query("SELECT * FROM `inquiry_products` WHERE `inquiry_id`=".$sec_id);

	$email_body = "<font size='3'>Inquiry details from ".$Company.". <br />Name = " . $fname . " <br />Phone No. = " . $ph . "
		<br />Fax = " . $fax . " <br />Email = " . $email . " <br />Address = " . $address .  "<br />Country = " . $country . "
		<br />Message = " . $message . "</font><br /><br />";

	$email_body .= '<table align="left" cellpadding="2" cellspacing="2" width="100%" border="1">';
		
		$email_body .= '<tr>';
			$email_body .= '<th style="width:200px;">Image</th>';
			$email_body .= '<th>Description</th>';
			$email_body .= '<th>Quantity</th>';
		$email_body .= '</tr>';

		while( $RS = mysql_fetch_array($basket_query) ){
			$item = get_product($RS['product_id']);
			$email_body .= '<tr>';
				$email_body .= '<td style="text-align:center;"><a target="_blank" href="'.generate_prd_link($RS['product_id']).'"><img style="max-width:200px;" src="'.$__url_sm_imgs.$item['img_small'].'" alt=""></a></td>';
				
				$email_body .= '<td>';
					$email_body .= '<b>Art No:</b> '.$item['art_no'];
					$email_body .= '<br /><b>Name:</b> '.$item['name'];
					$email_body .= print_product_inquiry( inquiry_sec_to_id(), $RS['product_id'] );
				$email_body .= '</td>';

				$email_body .= '<td>'.$RS['qty'].' Pcs</td>';

			$email_body .= '</tr>';
		}

	$email_body .= '</table>';

	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$headers .= "From: ".$EmlTo."\r\n";

	if (mail($EmlTo, "Inquiry From ".$Company, $email_body, $headers)){
	    
		ob_start();
		session_regenerate_id();
		session_unset();
		session_destroy();
		redirect(_root_);

	}else{
		redirect(_root_."basket");
	}


}elseif( isset($_POST['_p']) and $_POST['_p']=='senddelreq' ){

	$email_body = "<font size='3'>Distribution Application from ".get_option('cname'). "<br /><br />".
	"Full Name = ". esc($_POST['fname']) . "<br />".
	"Company Name = ". esc($_POST['cname']) . "<br />".
	"Job Title = ". esc($_POST['jtitle']) . "<br />".
	"Billing Address = ". esc($_POST['billaddress']) . "<br />".
	"Shipping Address = ". esc($_POST['shipaddress']) . "<br />".
	"Phone No. = ". esc($_POST['ph']) . "<br />".
	"Fax = ". esc($_POST['fax']) . "<br />".
	"Email = ". esc($_POST['email']) . "<br />".
	"Web = ". esc($_POST['web']) . "<br />".
	"Business Is A = ". esc($_POST['bussines']) . "<br />".
	"Tax No. = ". esc($_POST['taxno']) . "<br />".
	"Business Type = ". esc($_POST['busstype']) . "<br />".
	"Establishment Year = ". esc($_POST['estyear']) . "<br />".
	"Annual Sale = ". esc($_POST['anusale']) . "<br />".
	"No. of Employees = ". esc($_POST['noempl']) . "<br />".
	"No. of Applicants = ". esc($_POST['noaplicants']) . "<br />".
	"Address = ". esc($_POST['address']) . "<br />".
	"Message= ". esc($_POST['message']) . "<br />".
	"</font>";
	
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$headers .= "From: ".esc($_POST['email'])."\r\n";
	if (mail($EmlTo, "Distribution Application from ".get_option('cname'), $email_body, $headers)){
	    mail($EmlTo2, "Distribution Application from ".get_option('cname'), $email_body, $headers);
		redirect(_root_);
	}else{
		_e("Error! Email Delivery Failed.".$email_body);
	}

}elseif( isset($_POST['_p']) and $_POST['_p']=='suppinq' ){

	$id = esc($_POST['_pid']);
	$product = get_product($id);

	if( !$product ){ die("Error! Invalide Product."); }

	$total_qty = 0;
	$size_txt = "";
	$query_addsize = mysql_query("SELECT * FROM `tblprodsize` WHERE `pid`=".$id) or die('Error!');
	$query_addsize_num = mysql_num_rows($query_addsize);
	if( $query_addsize_num  > 0 ){
		$size_i = 1;
		while( $RS_size = mysql_fetch_array($query_addsize) ){

			if( isset($_POST['size_'.$RS_size['SizeID']]) and is_numeric($_POST['size_'.$RS_size['SizeID']]) ){
				$total_qty = $total_qty + esc($_POST['size_'.$RS_size['SizeID']]);
				$size_txt .= get_sizename($RS_size['SizeID']).": ".$_POST['size_'.$RS_size['SizeID']];
				if( $size_i < $query_addsize_num ){ $size_txt .= ", "; }else{ $size_txt .= "."; }
				$size_i++;
			}

		}
	}

	if( $total_qty < $product['ex_txt_6'] ){
		$_SESSION['error_txt'] = "The Minimum Order Quintity is ".$product['ex_txt_6']." Pairs";
		redirect(generate_prd_link($id));
	}
	
	$name = esc($_POST['name']);
	$email = esc($_POST['email']);
	$subject = esc($_POST['subject']);
	$msg = esc($_POST['msg']);

	$email_body = "<font size='3'>Suppllier inquiry from ".get_option('cname'). "<br /><br />" .
	"Product Name = ". $product['name'] . "<br />".
	"Product Art No. = ". $product['art_no'] . "<br />".
	"Product Size and Quintity = ". $size_txt . "<br />".
	"Product = <a href=\"".generate_prd_link($product['id'])."\">".get_product($product['id'], "name")."</a><br />".
	"<br /><br />Name = " . $name . " <br />
	Email = " . $email . "<br />
	Subject = " . $subject . "<br />
	Message = " . $msg . "</font>";

	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$headers .= "From: ".$email."\r\n";
	if (mail($EmlTo, "Suppllier inquiry from ".get_option('cname'), $email_body, $headers)){
		mail($email, "Sample Request from ".get_option('cname'), $email_body, $headers);
		$_SESSION['error_txt'] = "Your Inquiry has been send!";
		redirect(generate_prd_link($id));
	}else{
		_e("Error! Email Delivery Failed.<br /><br /><hr /><br /><br />".$email_body);
	}

}elseif( isset($_POST['_p']) and $_POST['_p']=='needsample' ){

	$id = esc($_POST['_pid']);
	$product = get_product($id);

	if( !$product ){ die("Error! Invalide Product."); }
	
	$name = esc($_POST['name']);
	$company = esc($_POST['company']);
	$ph = esc($_POST['ph']);
	$mobile = esc($_POST['mobile']);
	$address = esc($_POST['address']);
	$email = esc($_POST['email']);
	$country = esc($_POST['country']);
	$msg = esc($_POST['msg']);

	$email_body = "<font size='3'>Sample Request from ".get_option('cname'). "<br />" .
	"Product Art No. = ". $product['art_no'] . "<br />".
	"Product = <a href=\"".generate_prd_link($product['id'])."\">".get_product($product['id'], "name")."</a><br />".
	"<br />Name = " . $name . " <br />
	Company = " . $company . " <br />
	Phone Number = " . $ph . " <br />
	Mobile Number = " . $mobile . " <br />
	Email = " . $email . "<br />
	Address = " . $address . " <br />
	Country = " . $country . "<br />
	Message = " . $msg . "</font>";

	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$headers .= "From: ".$email."\r\n";
	if(mail($EmlTo, "Sample Request from ".get_option('cname'), $email_body, $headers)){
		mail($email, "Sample Request from ".get_option('cname'), $email_body, $headers);
		$_SESSION['error_txt'] = "Your Request has been send!";
		redirect(generate_prd_link($id));
	}else{
		_e("Error! Email Delivery Failed.<br /><br /><hr /><br /><br />".$email_body);
	}

}elseif( isset($_POST['_p']) and $_POST['_p']=='sendfb' ){

	// SEND THE INQ VIA EMAIL
	$fname = $_POST['fname'];
	$ph = $_POST['ph'];
	$fax = $_POST['fax'];
	$email = $_POST['email'];
	$address = $_POST['address'];
	$country = $_POST['country'];
	$message = $_POST['message'];

	$email_body = "<font size='3'>Feedback details from ".$Company.". <br /> Name = " . $fname . " <br />Phone No. = " . $ph . "
	<br />Fax = " . $fax . " <br />Email = " . $email . " <br />Address = " . $address .  "<br />Country = " . $country . "
	<br />Message = " . $message . "</font><br /><br />";

	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$headers .= "From: ".$email."\r\n";
	if (mail($EmlTo, "Feedback From ".$Company, $email_body, $headers)){
		mail($EmlTo2, "Feedback From ".$Company, $email_body, $headers);
		redirect(_root_);
	}else{
		_e("Email Delivery Failed!<br /><br /><br />".$email_body);
	}

}elseif( isset($_POST['_p']) and $_POST['_p']=='newsletter' ){

	// NEWSLETTER SIGNUP
	$header = "MIME-Version: 1.0\r\n"."Content-type: text/html; charset=iso-8859-1\r\n";
	$style = '<style>body{font:"Segoe UI",Helvetica,sans-serif; line-height:25px; font-size:13px; color:#222;}</style>';
	
	$msg = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">'.$style."</head><body>";
	$msg.= "An email was submited to you newsletter's subscribion at ".$Company.".<br />";
	$msg.= "The email is: <b>".$_POST['email']."</b><br />";
	$msg.= "</body></html>";
	
	$mail = mail($EmlTo, "Newsletter subscribion at ".$Company, $msg, $header);
	mail($EmlTo2, "Newsletter subscribion at ".$Company, $msg, $header);
	if( $mail ){ _e("Thankyou for subscribing!"); $_SESSION['nl'] == true; redirect(_root_); }
	else{ _e("Could not send the subscribion email!"); }
    
}elseif( isset($_POST['_p']) and $_POST['_p']=='sendsuppiler' ){
    
    $fname = $_POST['fname'];
	$ph = $_POST['ph'];
	$email = $_POST['email'];
	$country = $_POST['country'];
	$message = $_POST['message'];
	$products = esc( $_POST['products'] );
	$all_products = explode(",", $products);
	
	$header = "MIME-Version: 1.0\r\n"."Content-type: text/html; charset=iso-8859-1\r\n";
	$style = '<style>body{font:"Segoe UI",Helvetica,sans-serif; line-height:25px; font-size:13px; color:#222;}</style>';
	
	$msg = "<html><head>".$style."</head><body>";
	$msg.= "Contact supplier email from: ".get_option('cname').".<br />";
	$msg.= "<b>User Details:</b><br />";
	$msg.= "Name: <b>".$fname."</b><br />";
	$msg.= "Phone: <b>".$ph."</b><br />";
	$msg.= "Email: <b>".$email."</b><br />";
	$msg.= "Country: <b>".$country."</b><br />";
	$msg.= "Message: <b>".$message."</b><br /><br />";
	
	$msg .= "<b>Selected Products:</b><br />";
	
	foreach ($all_products as $product_slug){
	    $product = get_product_by_slug($product_slug);
	    $msg .= 'Product Name: <b>'.$product['name'].'</b><br />';
	    $msg .= 'Product Art No: <b>'.$product['art_no'].'</b><br />';
	    $msg .= 'Quantity: <b>'.$_POST['qty_'.$product['slug']].'</b> pcs<br />';
	    $msg .= '<a href="'.generate_prd_link($product['id']).'" target="_blank">Product Link</a><br />';
	    $msg .= '<hr />';
	}
	
	$msg.= "</body></html>";
	
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$headers .= "From: ".$email."\r\n";
	
	if(mail($EmlTo, "Cotact Supplier Request from ".get_option('cname'), $msg, $headers)){
		//mail($email, "Cotact Supplier Request from ".get_option('cname'), $msg, $headers);
		mail($EmlTo2, "Cotact Supplier Request from ".get_option('cname'), $msg, $headers);
		$_SESSION['error_txt'] = "Your request has been send!";
		redirect(_root_);
	}else{
		_e("Error! Email Delivery Failed.<br /><br /><hr /><br /><br />".$msg);
	}
	

}else{
	redirect(_root_);
}