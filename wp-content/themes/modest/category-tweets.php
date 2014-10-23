<?php get_header(); ?>

<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
						
<h2 id="page_title" class="archive-title">Tweets</h2>
	
<div class="posts-wrap the_archive"> 
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
     	<div class="post-archive_wrap">
     	
     		<div <?php post_class('post-archive'); ?> id="post-<?php the_ID(); ?>">
  					<?php the_content(); ?>

				<div class="archive-meta">
  				<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">&#8734;</a>
				</div><!-- end .archive-meta -->
			</div><!-- end .post -->
		</div><!-- end .post-archive_wrap -->
        
		<?php endwhile; ?>
        
		<div class="navigation">
			<div class="nav-prev"><?php next_posts_link( __('Older', 'designcrumbs')) ?></div>
			<div class="nav-next"><?php previous_posts_link( __('Newer', 'designcrumbs')) ?></div>
			<div class="clear"></div>
		</div>

	<?php else : ?>

		<h2><?php _e('Sorry, we can\'t seem to find what you\'re looking for.', 'designcrumbs'); ?></h2>
		<p><?php _e('Please, try one of the links on top.', 'designcrumbs'); ?></p>
        
	<?php endif; ?>
</div><!-- end .posts-wrap -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>