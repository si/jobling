=== YOURLS: Short URL Widget ===
Contributors: Viper007Bond
Donate link: http://www.viper007bond.com/donate/
Tags: yourls, widget, short url
Requires at least: 2.8
Tested up to: 3.0
Stable tag: trunk

Creates a widget that outputs the short URL to the current post or page. Requires the YOURLS: WordPress to Twitter plugin.

== Description ==

Creates a fully configurable widget that outputs the short URL to the current post or page. Requires the [YOURLS: WordPress to Twitter](http://wordpress.org/extend/plugins/yourls-wordpress-to-twitter/) plugin.

A demo can be found on [this plugin's homepage](http://www.viper007bond.com/wordpress-plugins/yourls-shorturl-widget/) (the text can be customized).

== Installation ==

###Upgrading From A Previous Version###

To upgrade from a previous version of this plugin, delete the entire folder and files from the previous version of the plugin and then follow the installation instructions below.

###Uploading The Plugin###

Extract all files from the ZIP file, **making sure to keep the file/folder structure intact**, and then upload it to `/wp-content/plugins/`.

**See Also:** ["Installing Plugins" article on the WP Codex](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins)

###Plugin Activation###

Go to the admin area of your WordPress install and click on the "Plugins" menu. Click on "Activate" for the plugin.

== Frequently Asked Questions ==

= How do I use this plugin? =

Visit Appearance -> Widgets and then drag the "YOURLS: Short URL" widget into your sidebar.

Type a title for your widget, such as "Short URL".

Enter some content to be displayed. Use the placeholders where you want the data to show up. I use the following on my blog with the add paragraphs checkbox checked:

`Want to Tweet about this [type]? Then here's the short URL to this specific [type]:

<a href="[url]">[url]</a>`

== ChangeLog ==

= Version 1.1.0 =

* Added `[title]` and `[longurl]` placeholders.

= Version 1.0.0 =

* Initial release!