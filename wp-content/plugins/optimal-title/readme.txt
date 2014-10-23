=== Optimal Title ===
Contributors: ElasticDog
Tags: post, seo, wordpress
Requires at least: 1.2
Tested up to: 2.3.3
Stable tag: 3.0

Optimal Title mirrors the function of wp_title() exactly, but moves the position of the 'separator' to after the title rather than before.

== Description ==

Optimal Title mirrors the function of wp_title() exactly, but moves the position of the 'separator' to after the title rather than before.  This allows you to have your blog name tacked on to the end of the page title instead of having it appear first.

Having your page information appear before your blog name in the title is advantageous because it provides more meaningful search engine results and browser bookmark names.  The text that appears between your `<title>` tags is used to generate both of these things, and will often be truncated when viewed.  Because of this, it is more effective to have words directly relating to the content of your page appear before common markers.  Not only will the titles be more meaningful, but the they will also be more scannable when being viewed in a list.  For more information about these concepts, see the [plugin&#8217;s homepage](http://elasticdog.com/2004/09/optimal-title/).

== Installation ==

1. Upload `optimal-title.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. In your `header.php` template file, replace the function call to `wp_title()` with `optimal_title()` (see the FAQ for specific examples)

== Frequently Asked Questions ==

= What options can be passed to optimal_title()? =

Optimal Title functions in the exact same way as [wp_title()](http://codex.wordpress.org/Template_Tags/wp_title), and thus you may specify the following options:

`<?php optimal_title('separator', display); ?>`

* **separator** - (*string*) Text to display between portions of the title (i.e. the separator), such as the archive elements of year and month.  Defaults to '&amp;raquo;' (&raquo;).
* **display** - (*boolean*) Display the page title (TRUE), or return it for use in PHP (FALSE).  Defaults to TRUE.

= What specifically needs to be changed in the header.php file? =

The most simple answer is to remove everything between the HTML `<title></title>` tags and then put the following code in its place:

`<?php optimal_title(); ?> <?php bloginfo('name'); ?>`

If you want a slightly safer version that won't break if you do not have the plugin activated or installed, then try something like this in between the HTML `<title></title>` tags:

`<?php
if ( function_exists('optimal_title') ) {
	optimal_title(); bloginfo('name');
} else {
	bloginfo('name'); wp_title(); }
?>
<?php if ( is_home() ) { ?> | <?php bloginfo('description'); } ?>`
