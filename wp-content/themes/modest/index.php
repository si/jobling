<?php get_header(); ?>
<h2 id="page_title"><?php echo get_the_title(get_option('page_for_posts')); ?></h2>
<div class="posts-wrap the_blog blog_<?php echo stripslashes(of_get_option('blog_layout')); ?>">
<?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; query_posts('cat=-'. (stripslashes(of_get_option('port_cat'))) .'&ignore_sticky_posts=1&paged='.$paged.''); if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>

<?php if ((of_get_option('blog_layout')) == 'magazine') { ?>
	<?php $count++; ?>
	<?php /* BEGIN ALT FIRST POST */ if ($count <= 3) : ?>
	
	<div <?php post_class('blog-home-post'); ?> id="post-<?php the_ID(); ?>">
		<?php if (has_post_thumbnail()) { ?>
			<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
				<?php the_post_thumbnail( 'blog_image', array('alt' => get_the_title()) ); ?>
			</a>
		<?php } ?>
		<div class="post_content first_blog_post">
			<?php if (comments_open() == "true") { ?> 
			<div class="additional-meta_comments">
				<span class="comment_color_<?php echo stripslashes(of_get_option('background_color')); ?>">
					<?php comments_popup_link( __( '0', 'designcrumbs' ), __( '1', 'designcrumbs' ), __( '%', 'designcrumbs' ), 'comments-link', __('', 'designcrumbs')); ?>
				</span>		
			</div>
			<?php } ?>
			<h3 class="post_title index-post_title">
				<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
    	    </h3>
			<?php the_excerpt(); ?>
			<div class="clear"></div>
			<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" class="more-link">Read More &raquo;</a>
			<div class="clear"></div>
			<div class="post_meta">
				<div class="meta_block">
					<span><?php _e('Category', 'designcrumbs'); ?></span>
					<?php the_category(', ') ?>
				</div>
				<div class="meta_block">	
					<span><?php _e('Author', 'designcrumbs'); ?></span>
					<?php the_author_posts_link(); ?>
				</div>
				<div class="meta_block">	
					<span><?php _e('Post Date', 'designcrumbs'); ?></span>
					<?php the_time('F d, Y'); ?>
				</div>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
		</div>
	</div><!-- end .post -->
	
	<?php /* END ALT FIRST POST */ else : ?>
	
	<div <?php post_class('blog-home-post mag_alt_post'); ?> id="post-<?php the_ID(); ?>">
		<?php if (has_post_thumbnail()) { ?>
			<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
				<?php the_post_thumbnail( 'alt_blog_image', array('alt' => get_the_title(), 'title' => get_the_title()) ); ?>
			</a>
		<?php } ?>
		<div class="post_content">
			<?php if (comments_open() == "true") { ?> 
			<div class="additional-meta_comments">
				<span class="comment_color_<?php echo stripslashes(of_get_option('background_color')); ?>">
					<?php comments_popup_link( __( '0', 'designcrumbs' ), __( '1', 'designcrumbs' ), __( '%', 'designcrumbs' ), 'comments-link', __('', 'designcrumbs')); ?>
				</span>		
			</div>
			<?php } ?>
			<h4 class="post_title"><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h4>
			<?php the_excerpt(); ?>
			<div class="clear"></div>
			<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" class="more-link"><?php _e('Read More', 'designcrumbs'); ?> &raquo;</a>
			<div class="clear"></div>
			<div class="post_meta">
				<div class="meta_block">	
					<span><?php _e('Category', 'designcrumbs'); ?></span>
					<?php the_category(', ') ?>
				</div>
				<div class="meta_block">	
					<span><?php _e('Post Date', 'designcrumbs'); ?></span>
					<?php the_time('F d, Y'); ?>
				</div>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
		</div>
	</div><!-- end .post -->
	
	<?php /* END REST OF POSTS */ endif; ?>
	
<?php } else { ?>

	<div <?php post_class('blog-home-post'); ?> id="post-<?php the_ID(); ?>">
		<h3 class="post_title index-post_title">
			<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
        </h3>
		<?php if (comments_open() == "true") { ?> 
		<div class="additional-meta_comments">
			<?php comments_popup_link( __( '0', 'designcrumbs' ), __( '1', 'designcrumbs' ), __( '%', 'designcrumbs' ), 'comments-link', __('', 'designcrumbs')); ?>			
		</div>
		<?php } if (has_post_thumbnail()) { ?>
			<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
				<?php the_post_thumbnail( 'blog_image', array('alt' => get_the_title()) ); ?>
			</a>
		<?php } ?>
		<div class="post_content">
			<?php the_excerpt(); ?>
			<div class="clear"></div>
			<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" class="more-link"><?php _e('Read More', 'designcrumbs'); ?> &raquo;</a>
			<div class="clear"></div>
			<div class="post_meta">
				<div class="meta_block">
					<span><?php _e('Category', 'designcrumbs'); ?></span>
					<?php the_category(', ') ?>
				</div>
				<div class="meta_block">	
					<span><?php _e('Author', 'designcrumbs'); ?></span>
					<?php the_author_posts_link(); ?>
				</div>
				<div class="meta_block">	
					<span><?php _e('Post Date', 'designcrumbs'); ?></span>
					<?php the_time('F d, Y'); ?>
				</div>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
		</div>
	</div><!-- end .post -->

<?php } ?>
        
	<?php endwhile; ?>
        
	<div class="navigation navigation-index">
		<div class="nav-prev"><?php next_posts_link( __('&laquo; Older Entries', 'designcrumbs')) ?></div>
		<div class="nav-next"><?php previous_posts_link( __('Newer Entries &raquo;', 'designcrumbs')) ?></div>
		<div class="clear"></div>
	</div>

	<?php else : ?>

	<h2><?php _e('Sorry, we can\'t seem to find what you\'re looking for.', 'designcrumbs'); ?></h2>
	<p><?php _e('Please, try one of the links on top.', 'designcrumbs'); ?></p>
        
	<?php endif; wp_reset_query(); ?>
	
</div><!-- end .posts-wrap -->
<?php get_sidebar(); ?>

<?php get_footer(); ?>
