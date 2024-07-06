<?php

ob_start();
session_start();
$sId = session_id();

date_default_timezone_set('Asia/Karachi');

$Company="Hiyati Sports Wears";
$CpTitle="Control Pane ( ".$Company." )";
$FSTitle=":::".$Company.":::";
$domainname = "hiyatisportswears.com";
$Referrer = "https://www.hiyatisportswears.com";


define("_root_", "https://www.hiyatisportswears.com/"); // THE ROOT DIRECTORY
define("_cssjs_", _root_."cssjs/"); // CSS, JS LOCATION
define("_img_", _root_."images/"); // IMAGES LOCATION

define("_db_host_", "localhost"); // DATABASE HOSTNAME
define("_db_user_", "root"); // DATABASE USER
define("_db_pass_", ''); // DATABASE PASSWORD
define("_db_name_", "site_db"); // DATABASE NAME

$__dir_banner = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."items".DIRECTORY_SEPARATOR."banners".DIRECTORY_SEPARATOR;
$__url_banner = _root_."items/banners/";

$__dir_courier = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."items".DIRECTORY_SEPARATOR."courier".DIRECTORY_SEPARATOR;
$__url_courier = _root_."items/courier/";

$__dir_gallery = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."items".DIRECTORY_SEPARATOR."gallery".DIRECTORY_SEPARATOR;
$__url_gallery = _root_."items/gallery/";

$__dir_attimgs = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."items".DIRECTORY_SEPARATOR."attimgs".DIRECTORY_SEPARATOR;
$__url_attimgs = _root_."items/attimgs/";

$__dir_sm_imgs = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."items".DIRECTORY_SEPARATOR."sm_imgs".DIRECTORY_SEPARATOR;
$__url_sm_imgs = _root_."items/sm_imgs/";

$__dir_lg_imgs = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."items".DIRECTORY_SEPARATOR."lg_imgs".DIRECTORY_SEPARATOR;
$__url_lg_imgs = _root_."items/lg_imgs/";

$__dir_xlg_imgs = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."items".DIRECTORY_SEPARATOR."xlg_imgs".DIRECTORY_SEPARATOR;
$__url_xlg_imgs = _root_."items/xlg_imgs/";


define("_setting_variation_", false);
define("_setting_shipping_", false);


define("_setting_brands_", false);
define("_setting_downloads_", false);
define("_setting_gallery_", false);
define("_setting_prd_gallery_", true);
define("_setting_page_edit_", true);
define("_setting_rm_img_", true);

define("_page_img_thumb_", true);
define("_page_img_banner_", true);

define("_setting_isgrp_01_", "FEAT PRD.");
define("_setting_isgrp_02_", "NEW PRD.");
define("_setting_isgrp_03_", false);

define("_setting_mini_descp_", true);

define("_setting_prdmove_", true);
define("_setting_prddel_", true);

define("_setting_price_", false);
define("_setting_price_exchange_", false);

define("_new_descp_", true);
define("_new_text_", false);

define("_setting_img_sm_width_", 250);
define("_setting_img_sm_height_", 250);
define("_setting_img_lg_width_", 500);
define("_setting_img_lg_height_", 500);

define("_setting_img_xlg_", false);
define("_setting_img_xlg_width_", 1000);
define("_setting_img_xlg_height_", 1000);


define("_setting_prd_ex_txt_1_", "Features");				define("_setting_prd_ex_txt_1_type_", "rte");
define("_setting_prd_ex_txt_2_", false);					define("_setting_prd_ex_txt_2_type_", "line");
define("_setting_prd_ex_txt_3_", false);					define("_setting_prd_ex_txt_3_type_", "line");
define("_setting_prd_ex_txt_4_", false);					define("_setting_prd_ex_txt_4_type_", "line");
define("_setting_prd_ex_txt_5_", false);					define("_setting_prd_ex_txt_5_type_", "line");
define("_setting_prd_ex_txt_6_", false);					define("_setting_prd_ex_txt_6_type_", "line");
define("_setting_prd_ex_txt_7_", false);					define("_setting_prd_ex_txt_7_type_", "line");
define("_setting_prd_ex_txt_8_", false);					define("_setting_prd_ex_txt_8_type_", "line");
define("_setting_prd_ex_txt_9_", false);					define("_setting_prd_ex_txt_9_type_", "line");
define("_setting_prd_ex_txt_10_", false);					define("_setting_prd_ex_txt_10_type_", "line");
define("_setting_prd_ex_txt_11_", false);					define("_setting_prd_ex_txt_11_type_", "line");
define("_setting_prd_ex_txt_12_", false);					define("_setting_prd_ex_txt_12_type_", "line");
define("_setting_prd_ex_txt_13_", false);					define("_setting_prd_ex_txt_13_type_", "line");
define("_setting_prd_ex_txt_14_", false);					define("_setting_prd_ex_txt_14_type_", "line");
define("_setting_prd_ex_txt_15_", false);					define("_setting_prd_ex_txt_15_type_", "line");


define("_menu_category_product_", true);
define("_menu_inquiry_", true);
define("_menu_news_", true);
define("_menu_search_", false);
define("_menu_variation_group_", true);
define("_menu_variation_", true);
define("_menu_banner_", true);
define("_menu_page_", true);
define("_menu_config_", true);
define("_menu_password_", true);
define("_menu_scripts_", false);
define("_menu_social_", true);
define("_menu_widgets_", true);


include("inc/functions.php");

$title = get_option("home-title");
$keywords = get_option("home-keywords");
$description = get_option("home-description");

$downloadimg="../items/downloads/";
$downloadfile="../download/";
