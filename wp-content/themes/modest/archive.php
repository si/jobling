<?php get_header(); ?>

<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
						
<h2 id="page_title" class="archive-title">
<?php /* If this is a category */ if (is_category()) { ?>
	<?php single_cat_title(); ?>
<?php /* If this is a tag */ } elseif( is_tag() ) { ?>
	<?php _e('Posts tagged with', 'designcrumbs'); ?> <em><?php single_tag_title(); ?></em>
<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
	<?php _e('Archive for', 'designcrumbs'); ?> <?php the_time('F jS, Y'); ?>
<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
	<?php _e('Archive for', 'designcrumbs'); ?> <?php the_time('F, Y'); ?>
<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
	<?php _e('Archive for', 'designcrumbs'); ?> <?php the_time('Y'); ?>
<?php /* If this is an author archive */ } elseif (is_author()) { ?>
	<?php _e('Posts by', 'designcrumbs'); ?> <?php $curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author)); echo $curauth->nickname; ?>
<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
	<?php _e('Blog archives ', 'designcrumbs'); ?>
<?php } ?>
</h2>
	
<div class="posts-wrap the_archive"> 
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
     	<div class="post-archive_wrap">
     		<div <?php post_class('post-archive'); ?> id="post-<?php the_ID(); ?>">
				<?php if (has_post_thumbnail()) { ?>
				<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" class="archive_image_link">
					<?php the_post_thumbnail( 'archive_image', array( 'alt' => get_the_title()) ); ?>
				</a>
				<?php } ?>
				<h3 class="archive-entry-title">
					<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
				</h3>
				<div class="archive-meta">
					<span class="left">
    				<?php _e('Posted in', 'designcrumbs'); ?> <?php the_category(', ') ?> 
  					on <?php the_time('d F Y'); ?></span>
  					<?php // _e('by', 'designcrumbs'); ?> <?php // the_author_posts_link(); ?> 
  					<?php /* if ((in_category(stripslashes(of_get_option('port_cat')))) || (post_is_in_descendant_category(stripslashes(of_get_option('port_cat'))))) { ?>
  					<?php if (of_get_option('port_comments') == 'yes') { ?>
  						<span class="right"><?php comments_popup_link( __( 'No Comments', 'designcrumbs' ), __( '1 Comment', 'designcrumbs' ), __( '% Comments', 'designcrumbs' ), 'comments-link', __('Comments Closed', 'designcrumbs')); ?></span>
  					<?php } ?>
  					<?php } else { ?>
  						<span class="right"><?php comments_popup_link( __( 'No Comments', 'designcrumbs' ), __( '1 Comment', 'designcrumbs' ), __( '% Comments', 'designcrumbs' ), 'comments-link', __('Comments Closed', 'designcrumbs')); ?></span>
  					<?php } */ ?>
					<div class="clear"></div>
				</div><!-- end .archive-meta -->
			</div><!-- end .post -->
		</div><!-- end .post-archive_wrap -->
        
		<?php endwhile; ?>
        
		<div class="navigation">
			<div class="nav-prev"><?php next_posts_link( __('&laquo; Older Entries', 'designcrumbs')) ?></div>
			<div class="nav-next"><?php previous_posts_link( __('Newer Entries &raquo;', 'designcrumbs')) ?></div>
			<div class="clear"></div>
		</div>

	<?php else : ?>

		<h2><?php _e('Sorry, we can\'t seem to find what you\'re looking for.', 'designcrumbs'); ?></h2>
		<p><?php _e('Please, try one of the links on top.', 'designcrumbs'); ?></p>
        
	<?php endif; ?>
</div><!-- end .posts-wrap -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>