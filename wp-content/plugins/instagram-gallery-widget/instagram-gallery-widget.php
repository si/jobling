<?php
/*
Plugin Name: Instagram Gallery Widget
Plugin URI: http://www.lucagrandicelli.com/instagram-gallery-widget-wordpress
Description: Instagram Gallery Widget (IGW) lets you display your beautiful Instagr.am pictures in a simple and elegant gallery onto your Wordpress website. Switch through several customization options, and put the widget in any part of your theme. <strong>To get started:</strong> 1) Click the "Activate" link to the left of this description, 2) Go to the Widget page and drag the 'Instagram Widget Gallery' onto your sidebar and configure its settings, 4) If you wish to use PHP code, use the <code>instagram_gallery_widget()</code> function provided. If you prefer shortcodes, use the <code>[igw]</code> shortcode inside any of your posts/pages. Check the <a href='http://wordpress.org/extend/plugins/instagram-gallery-widget/installation/'>documentation</a> or readme.txt file for further details. 5) If you need to adjust some CSS to suite your theme layout, go to the Instagram Gallery Widget <a href='options-general.php?page=instagram-gallery-widget/lib/lib-admin.php'>setting page</a> and change the inner stylesheet. 6) Enjoy.
Version: 1.4.1
Author: Luca Grandicelli
Author URI: http://www.lucagrandicelli.com
License: GPLv3 or later

Copyright (C) 2011  Luca Grandicelli

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/*
| ---------------------------------------------
| GLOBAL DECLARATIONS
| In this section we define the enviroment
| basic constants and global paths.
| ---------------------------------------------
*/

define('IGW_PLUGIN_URL'        , plugin_dir_url( __FILE__ ));                    // Defining plugin url path.
define('IGW_PLUGIN_MAINFILE'   , __FILE__);                                      // Defining plugin main filename.
define('IGW_PLUGIN_VERSION'    , '1.4.1');                                       // Defining plugin version.
define('IGW_REQUIRED_PHPVER'   , '5.0.0');                                       // Defining required PHP version.
define('IGW_TRANSLATION_ID'    , 'igwlang');                                     // Defining gettext translation ID.
define('IGW_CLASS_FOLDER'      , 'classes/');                                    // Defining path for main plugin classes.
define('IGW_CSS_FOLDER'        , 'css/');                                        // Defining path for CSS stylesheets.
define('IGW_IMAGES_FOLDER'     , 'images/');                                     // Defining path for images.s
define('IGW_JS_FOLDER'         , 'js/');                                         // Defining path for javascript scripts.
define('IGW_LIB_FOLDER'        , 'lib/');                                        // Defining path for external libraries.
define('IGW_LANG_FOLDER'       , 'language/');                                   // Defining path for language packs.
define('IGW_BACKEND_ADMIN_CSS' , IGW_CSS_FOLDER    . 'css-backend-admin.css');   // Defining path for back-end stylesheet.
define('IGW_FRONTEND_CSS'      , IGW_CSS_FOLDER    . 'css-frontend-widget.css'); // Defining path for front-end stylesheet.
define('IGW_FRONTEND_JS_INIT'  , IGW_JS_FOLDER     . 'igw-frontend-init.js');    // Defining path for front-end custom js init script.

/*
| ---------------------------------------------
| INCLUDING FANCYBOX SCRIPT
| A nice lightbox effect.
| ---------------------------------------------
*/

define('IGW_FANCYBOX_SCRIPT'  , IGW_LIB_FOLDER . 'fancybox/jquery.fancybox-1.3.4.pack.js'); // Defining FancyBox library.
define('IGW_FANCYBOX_CSS'     , IGW_LIB_FOLDER . 'fancybox/jquery.fancybox-1.3.4.css');     // Defining FancyBox library.

/*
| ---------------------------------------------
| GLOBAL INCLUDES
| In this section we include all the needed
| files for the plugin to work.
| ---------------------------------------------
*/

require_once('config.php');                            // Including main config file.
require_once(IGW_CLASS_FOLDER  . 'class-main.php');    // Including main plugin class.
require_once(IGW_CLASS_FOLDER  . 'class-widgets.php'); // Including widgets class.
require_once(IGW_LIB_FOLDER    . 'lib-admin.php');     // Including plugin admin file.

/*
| -------------------------------------------------------------
| External function to call plugin from PHP inline code.
| Check documentation on # for further configuration settings.
| -------------------------------------------------------------
*/

function instagram_gallery_widget($args = array()) {
	
	// Creating an instance of Special Posts Class with widget args passed in manual mode.
	$igw = new InstagramGalleryWidget($args);
	
	// Display Posts.
	$igw->displayGallery(NULL, 'print');
}

function igw_shortcode($atts) {

	// Including external widget values.
	global $igw_default_widget_values;
	
	// If shortcode comes without parameters, make $atts value an array.
	if (!is_array($atts)) {
		$atts = array();
	}
	
	// Assembling default widget options with available shortcode options.
	extract(shortcode_atts($igw_default_widget_values, $atts));
	
	// Creating an instance of Special Posts Class with widget args passed in manual mode.
	$igw = new InstagramGalleryWidget($atts);
	
	// Display Posts.
	return $igw->displayGallery(NULL, 'return');
}

// Load Translation Table.
load_plugin_textdomain(IGW_TRANSLATION_ID, false, dirname(plugin_basename( __FILE__ )) . '/' . IGW_LANG_FOLDER );

/*
| ---------------------------------------------
| PLUGIN HOOKS & ACTIONS
| Listing plugin hooks and actions.
| ---------------------------------------------
*/

register_activation_hook(__FILE__    , array('InstagramGalleryWidget', 'install_plugin'));   // Registering plugin activation hook.
register_uninstall_hook( __FILE__    , array('InstagramGalleryWidget', 'uninstall_plugin')); // Registering plugin deactivation hook.
add_action('widgets_init'            , 'igw_install_widgets');                               // Defining actions on plugin init.
add_action('admin_init'              , 'igw_admin_init');                                    // Defining actions on admin page init.
add_action('admin_menu'              , 'igw_admin_setup');                                   // Defining actions for admin page setup.
add_action('wp_head'                 , 'igw_theme_css');                                     // Defining front-end widget CSS.
add_shortcode('igw'                  , 'igw_shortcode' );                                    // Registering IGW Shortcode.