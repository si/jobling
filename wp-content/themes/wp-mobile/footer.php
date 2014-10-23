<hr />
<form method="get" id="searchform" action="/">
	<fieldset>
		<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" />
		<input type="submit" id="searchsubmit" value="<?php _e('Search'); ?>" />
	</fieldset>
</form>
<hr />
<p><?php _e("Mobile Site"); ?> | <a accesskey="0" href="<?php bloginfo('wpurl'); ?>/?ak_action=reject_mobile"><?php _e("Full Site"); ?></a></p>
<hr />
<?php do_action( 'wp_mobile_theme_footer' ); ?>
<p><a href="http://wordpress.com/" rel="generator">Get a free blog at WordPress.com</a> <?php printf( __( 'Theme: %1$s by %2$s.' ), 'WordPress Mobile Edition', '<a href="http://alexking.org/" rel="designer">Alex King</a>' ); ?></p>
</body>
</html>