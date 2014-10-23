<div class="clear"></div>
<div id="footer" class="<?php $sb_count = wp_get_sidebars_widgets(); if (count( $sb_count['Footer']) <= '5') { ?>footer_widget_count<?php count_sidebar_widgets( 'Footer' );?><?php } else { ?>footer_widget_overflow<?php } ?>">
	<?php if ((of_get_option('twitter') != '') || (of_get_option('facebook') != '') || (of_get_option('google') != '') || (of_get_option('flickr') != '') || (of_get_option('vimeo') != '') || (of_get_option('forrst') != '') || (of_get_option('dribbble') != '') || (of_get_option('tumblr') != '') || (of_get_option('pinterest') != '') ) { ?>
	<div id="footer_socnets_wrap">
		<div id="footer_socnets">
			<?php if (of_get_option('twitter') != '') { ?>
			<a href="<?php echo stripslashes(of_get_option('twitter')); ?>" title="Twitter"><img src="<?php echo get_template_directory_uri('template_url'); ?>/images/socnets/twitter.png" alt="Twitter" /></a>
			<?php } if (of_get_option('facebook') != '') { ?>
			<a href="<?php echo stripslashes(of_get_option('facebook')); ?>" title="Facebook"><img src="<?php echo get_template_directory_uri('template_url'); ?>/images/socnets/facebook.png" alt="Facebook" /></a>
			<?php } if (of_get_option('google') != '') { ?>
			<a href="<?php echo stripslashes(of_get_option('google')); ?>" title="Google+"><img src="<?php echo get_template_directory_uri('template_url'); ?>/images/socnets/google.png" alt="Google+" /></a>
			<?php } if (of_get_option('flickr') != '') { ?>
			<a href="<?php echo stripslashes(of_get_option('flickr')); ?>" title="Flickr"><img src="<?php echo get_template_directory_uri('template_url'); ?>/images/socnets/flickr.png" alt="Flickr" /></a>
			<?php } if (of_get_option('forrst') != '') { ?>
			<a href="<?php echo stripslashes(of_get_option('forrst')); ?>" title="Forrst"><img src="<?php echo get_template_directory_uri('template_url'); ?>/images/socnets/forrst.png" alt="Forrst" /></a>
			<?php } if (of_get_option('dribbble') != '') { ?>
			<a href="<?php echo stripslashes(of_get_option('dribbble')); ?>" title="Dribbble"><img src="<?php echo get_template_directory_uri('template_url'); ?>/images/socnets/dribbble.png" alt="Dribbble" /></a>
			<?php } if (of_get_option('tumblr') != '') { ?>
			<a href="<?php echo stripslashes(of_get_option('tumblr')); ?>" title="Tumblr"><img src="<?php echo get_template_directory_uri('template_url'); ?>/images/socnets/tumblr.png" alt="Tumblr" /></a>
			<?php } if (of_get_option('vimeo') != '') { ?>
			<a href="<?php echo stripslashes(of_get_option('vimeo')); ?>" title="Vimeo"><img src="<?php echo get_template_directory_uri('template_url'); ?>/images/socnets/vimeo.png" alt="Vimeo" /></a>
			<?php } if (of_get_option('pinterest') != '') { ?>
			<a href="<?php echo stripslashes(of_get_option('pinterest')); ?>" title="Vimeo"><img src="<?php echo get_template_directory_uri('template_url'); ?>/images/socnets/pinterest.png" alt="Pinterest" /></a>
			<?php } ?>
		</div>
	</div>
	<div class="clear"></div>
	<?php } ?>
	<div id="footer_widgets_wrap">
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer') ) : endif; ?>
		<div class="clear"></div>
	</div>
</div>
</div><!-- end div.container, begins in header.php -->
</div><!-- end div.wrapper, begins in header.php -->
<div id="post_footer" class="wrapper">
	<div class="container">
		<div class="left" id="footer_menu">
			<?php wp_nav_menu( array( 'theme_location' => 'secondary', 'depth' => '1' ) ); ?>
		</div>
		<div class="right">
			&copy; <?php echo date("Y"); ?> <?php bloginfo('name'); ?>
			<?php if (of_get_option('give_credit') == '1') { ?>
			&nbsp;&nbsp;::&nbsp;&nbsp;&nbsp;<a href="http://www.designcrumbs.com/wordpress-themes" title="Modest WordPress Theme"><?php _e('Modest Theme', 'designcrumbs'); ?></a> <?php _e('by', 'designcrumbs'); ?> <a href="http://www.designcrumbs.com/wordpress-themes" title="Jake Caputo"><?php _e('Design Crumbs', 'designcrumbs'); ?></a>
			<?php } ?>
		</div>
		<div class="clear"></div>
	</div>
</div>
<?php wp_footer(); //leave for plugins ?>
<?php echo stripslashes(of_get_option('analytics')); ?>
</body>
</html>