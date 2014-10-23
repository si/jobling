<?php get_header(); ?>
<h2 id="page_title"><?php _e('Search Results', 'designcrumbs'); ?></h2>
<div class="posts-wrap search_results">
	<h3 id="search" class="page-title"><?php /* Search Count */ $allsearch = &new WP_Query("s=$s&showposts=-1"); $count = $allsearch->post_count; echo $count . ' '; wp_reset_query(); ?><?php _e('Search Results for', 'designcrumbs'); ?> <strong><?php the_search_query() ?></strong></h3>
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
	<div class="post-archive_wrap">
     	<div <?php post_class('post-archive'); ?> id="post-<?php the_ID(); ?>">
			<?php if (has_post_thumbnail()) { ?>
			<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" class="archive_image_link">
				<?php the_post_thumbnail( 'archive_image', array( 'title' => get_the_title()) ); ?>
			</a>
			<?php } ?>
			<h3 class="archive-entry-title">
				<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
			</h3>
			<div class="archive-meta">
				<span class="left"><?php _e('Posted in', 'designcrumbs'); ?> <?php the_category(', ') ?> <?php _e('by', 'designcrumbs'); ?> <?php the_author_posts_link(); ?> <?php _e('on', 'designcrumbs'); ?> <?php the_time('F d, Y'); ?></span>
				<?php if ((in_category(stripslashes(of_get_option('port_cat')))) || (post_is_in_descendant_category(stripslashes(of_get_option('port_cat'))))) { ?>
					<?php if (of_get_option('port_comments') == 'yes') { ?>
					<span class="right"><?php comments_popup_link( __( 'No Comments', 'designcrumbs' ), __( '1 Comment', 'designcrumbs' ), __( '% Comments', 'designcrumbs' ), 'comments-link', __('Comments Closed', 'designcrumbs')); ?></span>
					<?php } ?>
				<?php } else { ?>
					<span class="right"><?php comments_popup_link( __( 'No Comments', 'designcrumbs' ), __( '1 Comment', 'designcrumbs' ), __( '% Comments', 'designcrumbs' ), 'comments-link', __('Comments Closed', 'designcrumbs')); ?></span>
				<?php } ?>
				<div class="clear"></div>
			</div><!-- end .archive-meta -->
		</div><!-- end .post -->
	</div><!-- end .post-archive_wrap -->
        
		<?php endwhile; ?>
		
	<div class="navigation navigation-index">
		<div class="nav-prev"><?php next_posts_link( __('&laquo; Older Entries', 'designcrumbs')) ?></div>
		<div class="nav-next"><?php previous_posts_link( __('Newer Entries &raquo;', 'designcrumbs')) ?></div>
		<div class="clear"></div>
	</div>

	<?php else : ?>
	<div class="post">
		<div class="entry-content"><?php _e('Sorry, but we can\'t find any results for', 'designcrumbs'); ?>
			 <strong><?php the_search_query() ?></strong>. <?php _e('Please try your search again, or navigate around the site with the links on top.', 'designcrumbs'); ?>
		</div>
	</div>
	<?php endif; ?>
</div><!-- end .posts-wrap -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>