<?php get_header(); ?>
<h2 id="page_title"><?php the_title(); ?></h2>
<div class="posts-wrap" id="page">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div <?php post_class(); ?>>
		<div class="entry-content" id="page-content">
				<?php the_content(); ?>
				<?php wp_link_pages(); ?>
		</div><!-- end #page-content -->
	</div><!-- end #page -->
		<?php endwhile; endif; ?>
</div><!-- end .posts-wrap -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>