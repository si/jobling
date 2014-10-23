<?php
/*
Template Name: Full Width
*/
get_header(); ?>
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div <?php post_class(); ?> id="page">
			<div class="entry-content" id="page-content">
				<?php the_content(); ?>
				<?php wp_link_pages(); ?>
			</div><!-- end #page-content -->
		</div><!-- end #page -->
	<?php endwhile; endif; ?>
<?php get_footer(); ?>