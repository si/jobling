<?php
/*
Plugin Name: Friendly Short Code Buttons
Plugin URI: http://pippinsplugins.com
Description: Adds user-friendly short code buttons to your  WordPress site. This plugin is more of an example than anything, but does provide a few nice looking buttons
Version: 1.0.1
Author: Pippin Williamson
Author URI: http://pippinsplugins.com
*/

// plugin root folder
$fscb_base_dir = '/'.str_replace(basename( __FILE__), "" ,plugin_basename(__FILE__));
$fscb_base_url = str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, $fscb_base_dir);



// ****** Build Column Shortcode ****** //

function column( $atts, $content = null )
{
	extract( shortcode_atts( array(
      'colcontent' => '',
	  'width' => '',
      ), $atts ) );

	// convert the shortcode to this on the front end
	return '<div class="' . $width . '_wide">' . $content . '</div>';
}
add_shortcode('column', 'column');



// ****** Build Message Shortcode ****** //
function message( $atts, $content = null )
{
	extract( shortcode_atts( array(
      'type' => '',
      ), $atts ) );

	// convert the shortcode to this on the front end
	return '<div class="' . $type . '">' . $content . '</div>';
}
add_shortcode('message', 'message');



// ****** Build Button Shortcode ****** //
function button( $atts, $content = null )
{
	extract( shortcode_atts( array(
      'alignment' => '',
      'link' => '',
      ), $atts ) );

	// convert the shortcode to this on the front end
	return '<a class="button button' . $alignment . '" href="' . $link . '">' . $content . '</a>';
}
add_shortcode('button', 'button');



// ****** Shouldn't have to edit past this line ****** //
add_action('wp_ajax_fscb', 'fscb_ajax_tinymce' );
function fscb_ajax_tinymce()
{

	// check for rights
	if ( ! current_user_can('edit_pages') && ! current_user_can('edit_posts') )
		die( __("You are not allowed to be here") );

	global $fscb_base_url;
	$window = dirname(__FILE__) . '/button_popup.php';
	include_once( $window );

	die();
}

// load button css
function friendly_buttons_css()
{
	global $fscb_base_url;
	wp_enqueue_style('friendly-buttons', $fscb_base_url . 'includes/css/friendly_buttons.css');
}
add_action('wp_print_styles', 'friendly_buttons_css');


// registers the buttons for use
function register_friendly_buttons($buttons) {
	// inserts a separator between existing buttons and our new one
	// "friendly_button" is the ID of our button
	array_push($buttons, "|", "friendly_button");
	return $buttons;
}

// filters the tinyMCE buttons and adds our custom buttons
function friendly_shortcode_buttons() {
	// Don't bother doing this stuff if the current user lacks permissions
	if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
		return;

	// Add only in Rich Editor mode
	if ( get_user_option('rich_editing') == 'true') {
		// filter the tinyMCE buttons and add our own
		add_filter("mce_external_plugins", "add_friendly_tinymce_plugin");
		add_filter('mce_buttons', 'register_friendly_buttons');
	}
}
// init process for button control
add_action('init', 'friendly_shortcode_buttons');

// add the button to the tinyMCE bar
function add_friendly_tinymce_plugin($plugin_array) {
	global $fscb_base_url;
	$plugin_array['friendly_button'] = $fscb_base_url . 'friendly-shortcode-buttons.js';
	return $plugin_array;
}
