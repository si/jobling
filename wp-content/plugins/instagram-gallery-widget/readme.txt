=== Instagram Gallery Widget ===
Contributors: lgrandicelli
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6DXPBEJV5QTNJ
Tags: instagram, gallery, widget, wordpress, photos, pictures, instagr.am
Requires at least: 3.0
Tested up to: 3.2.1
Stable tag: 1.4.1
License: GPLv3 or later


Instagram Gallery Widget (IGW) lets you display your beautiful Instagr.am pictures in a simple and elegant gallery onto your Wordpress website.

== Description ==

<p>Instagram Gallery Widget (IGW) lets you display your beautiful Instagr.am pictures in a simple and elegant gallery onto your Wordpress website.
You can drag multiple widget instances and configure each one with different settings.
You can also use custom PHP code to insert the widget in any part of your theme or if you prefer, you can embed the special shortcode.</p>

<strong>Special features</strong>:
<ul>
	<li>Easy-to-install procedure. Just enter one of your Instagr.am pictures URL and you're done</li>
	<li>Nice FancyBox effect when clicking on thumbnails</li>
	<li>Browse your Instagram pictures just like a gallery</li>
	<li>Thumbnails custom sizes option for every widget instance</li>
	<li>Random Display Mode</li>
	<li>Filter images by Effects</li>
	<li>Multiple widgets configurations</li>
	<li>Specific PHP function call for theme customization</li>
	<li>Shortcodes Support</li>
</ul>

<strong>Plugin's homepage</strong><br />
http://www.lucagrandicelli.com/instagram-gallery-widget-wordpress

<strong>Credits</strong><br />
This plugin relies on the excellent Instagr.am front-end application http://instagram.heroku.com/
Made by http://twitter.com/mislav

== Installation ==

The automatic plugin installer should work for most people. Manual installation is easy and takes fewer than five minutes.

1. Download the plugin, unpack it and upload the '<em>instagram-gallery-widget</em>' folder to your wp-content/plugins directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. On the widgets panel, drag the Instagram Gallery Widget widget onto one of your sidebars and configure its specific settings.
4. If you need to adjust some CSS to suite your theme layout, go to Settings->Instagram Gallery Widget page and change the inner stylesheet.
5. You're done. Enjoy.

If you wish to use the Instagram Gallery Widget in another part of your theme which is not widget-handled, you can put the following snippet:

`
<?php
	if(function_exists('instagram_gallery_widget')) {
		instagram_gallery_widget($args);
	}
?>
`

where $args is an array of the following options:
`
// Widget Title
igw_widget_title => text

// Max number of pictures to display
igw_maxnum_photos => numeric

// Thumbnail Width
igw_thumbnail_width => numeric

// Thumbnail Height
igw_thumbnail_height => numeric

// Show/Hide widget title
igw_widget_title_hide_option => yes|no

// Your Instagr.am pictures URL
igw_photo_url => text url (one of your instagr.am pictures url (E.G: http://instagr.am/p/FuCsj/)

// Randomize pictures option
igw_randomize_option => yes|no

// Enable/Disable FancyBox Effect
igw_fancybox_option => yes|no

// Filter images by Effect (Available Effects: none, Amaro, Rise, Hudson, Valencia, X-Pro II, Lomo-fi, Earlybird, Sutro, Toaster, Brannan, Inkwell, Walden, Hefe, Apollo, Poprocket, Nashville, Gotham, 1977, Lord Kelvin)
igw_effect_filter => name of the effect

`

Example:
`
<?php
// Defining widget options.
$args = array(
	'igw_widget_title'        => 'My Beautiful Gallery',
	'igw_maxnum_photos'       => 16,
	'igw_thumbnail_width'     => 50,
	'igw_thumbnail_height'    => 50,
	'igw_randomize_option'    => 'yes',
	'igw_photo_url'           => 'http://instagr.am/p/FuCsj/',
	'igw_effect_filter'       => 'X-Pro II'
);


// Function call.
if(function_exists('instagram_gallery_widget')) {
	instagram_gallery_widget($args);
}
?>
`

If you wish to use a shortcode, put the following inside any of your post/pages:
`[igw]`

REMEMBER: When using shortcodes, you must provide the Instagr.am Photo URL.

Shortcodes parameters names are the same of direct PHP call ones, but you have to put them with the '=' sign instead of the '=>' arrow.
String values must be enclosed within single/double quotes.

Example:
`[igw igw_maxnum_photos='16' igw_photo_url='http://instagr.am/p/FuCsj/']`

== Changelog ==

= 1.4.1 =
* Added new 4 effects from the Instagr.am 2.0 version

= 1.4 =
* Added new option to filter images by effect.
* Minor bugs fixed.

= 1.3 =
* Added Random Mode Option.

= 1.2 =
* REMOVED THE USER BOX INFO. Instagr.am API, which is in beta test, has changed some stuff and this completely prevented the plugin from showing the images in versione 1.1 This update will temporary remove the user box info but it will continue to display the images.
* Minor bugs fixed.

= 1.1 = 
* When disabling FancyBox effect, images will open in the original Instagr.am Window instead of a blank page.

= 1.0 = 
* Initial release

== Frequently Asked Questions ==

= The Widget doesn't show properly on my sidebar =

In this case, you will have to deal with the custom CSS provided inside the plugin settings panel.

== Screenshots ==

1. The Gallery
2. The Widget admin panel

== Requirements ==

In order to work, the Instagram Gallery Widget plugin needs the following settings:

1. PHP version 5+
2. CURL libraries installed and enabled on your server.