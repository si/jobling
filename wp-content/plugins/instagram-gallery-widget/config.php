<?php
/*
| --------------------------------------------------------
| File        : config.php
| Project     : Instagram Gallery Widget for Wordpress
| Version     : 1.4.1
| Description : This file contains the default global
|               enviroment values
| Author      : Luca Grandicelli
| Author URL  : http://www.lucagrandicelli.com
| Plugin URL  : http://www.lucagrandicelli.com/instagram-gallery-widget-wordpress
| License     : GPLv3 or later
| --------------------------------------------------------
*/

/*
| ---------------------------------------------
| GLOBAL ENVIROMENT VALUES
| ---------------------------------------------
*/

// Defining global default plugin values.
global $igw_default_plugin_values;

$igw_default_plugin_values = array(
	'igw_version'              => '1.4.1',
	'igw_themecss'             => "
div.igw-widget-container {
	clear:both;
}

div#igw-gallery-header {
	margin           : 0px 0px 5px 0px;
	background       : url('" . IGW_PLUGIN_URL . IGW_IMAGES_FOLDER . "icons/header-background.jpg') top repeat-x;
	padding          : 5px;
	font-family      : Arial, Verdana, sans-serif;
	height           : 65px;
}

div#igw-gallery-content {
	clear:both;
}

div#profile_picture_box {
	vertical-align : middle;
	margin         : 0px 10px 0px 0px;
	float          : left;
}

div#profile_userinfo_box {
	float: left;
}

h4#igw_profile_username {
	font-size   : 18px;
	color       : #FFFFFF;
	margin      : 1px 0px 5px 0px;
	font-family : Arial, Verdana, sans-serif;
}

div.igw_userinfo_block {
	float              : left;
	margin             : 0px 2px 0px 0px;
	color              : #FFFFFF;
	width              : 60px;
	text-align         : center;
	font-weight        : bold;
	box-shadow         : 2px 2px 2px #000000;
	-webkit-box-shadow : 2px 2px 2px #000000;
	-moz-box-shadow    : 2px 2px 2px #000000;
}

span.igw_userinfo_block_number {
	display                     : block;
	height                      : 15px;
	line-height                 : 16px;
	background-color            : #3a6899;
	padding                     : 2px;
	font-size                   : 15px;
	-moz-border-radius-topright : 4px;
	border-top-right-radius     : 4px;
	-moz-border-radius-topleft  : 4px;
	border-top-left-radius      : 4px;
	font-family                 : Arial, Verdana, sans-serif;
}

span.igw_userinfo_block_label {
	display                        : block;
	height                         : 15px;
	line-height                    : 12px;
	background-color               : #1f3d5c;
	padding                        : 2px;
	font-size                      : 11px;
	-moz-border-radius-bottomright : 4px;
	border-bottom-right-radius     : 4px;
	-moz-border-radius-bottomleft  : 4px;
	border-bottom-left-radius      : 4px;
	font-family                    : Arial, Verdana, sans-serif;
}

img.igw_thumb {}

a.igw_thumblink {
	float   : left;
	display : block;
	margin  : 0px 1px 0px 0px;
}"
);

// Defining global default widget values.
global $igw_default_widget_values;

$igw_default_widget_values = array(
	'igw_widget_title'              => "My Instagr.am Gallery",
	'igw_maxnum_photos'             => 16,
	'igw_thumbnail_width'           => 50,
	'igw_thumbnail_height'          => 50,
	'igw_widget_title_hide_option'  => "no",
	'igw_photo_url'                 => "",
	'igw_randomize_option'          => 'no',
	'igw_effect_filter'             => 'none',
	'igw_fancybox_option'           => 'no'
);