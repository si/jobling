<?php get_header();
	//These are also in includes/js/modestjs.php
	//Sets variables for queries
	$projects_per = (stripslashes(of_get_option('projects_per')));
	$projects_total = (stripslashes(of_get_option('projects_total')));
	$project_offset = ($projects_total - $projects_per);
			
	//sets the name for classes depending on projects per line
	if ($projects_per == '2') {$projects_count_class = "half";};
	if ($projects_per == '3') {$projects_count_class = "third";};
	if ($projects_per == '4') {$projects_count_class = "fourth";};
	if ($projects_per == '5') {$projects_count_class = "fifth";};
?>
<div id="featured_wrap">
	<?php if (((of_get_option('heading_text')) != '') || ((of_get_option('subheading_text')) != '')) { ?>
	<div id="slogan_space">
		<?php if (stripslashes(of_get_option('heading_text')) != '') { ?>
		<h1><?php echo stripslashes(of_get_option('heading_text')); ?></h1>
		<?php } if (stripslashes(of_get_option('subheading_text')) != '') { ?>
		<h3 class="slogan"><?php echo stripslashes(of_get_option('subheading_text')); ?></h3>
		<?php } ?>
	</div>
	<?php } ?>
	<div id="featured">
		<div<?php if ((stripslashes(of_get_option('heading_text')) != '') && (stripslashes(of_get_option('subheading_text')) != '')) { ?> id="first_featured"<?php } ?>>
		<?php $query_default = new WP_Query( array(
			'orderby'      => 'desc',
			'post_type'    => 'post',
			'cat'    => ''.(stripslashes(of_get_option('port_cat'))).'',
			'post_status'  => 'publish',
			'posts_per_page' => ''. $projects_per .'' 
		));
		if ( $query_default->have_posts() ) : while ( $query_default->have_posts() ) : $query_default->the_post(); ?>
			<div class="single_featured_wrap single_featured_wrap_<?php echo $projects_count_class; ?>">
				<div class="<?php echo $projects_count_class; ?>_wrap info_wrap">
					<?php if (is_sticky()) { ?><span class="sticky_star"></span><?php } ?>
					<a href="<?php the_permalink() ?>" class="touch_link"></a>
					<div class="hover_info">
						<div class="hover_content">
							<?php if ($projects_per == '3') { ?>
							<h3><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
							<?php } elseif ($projects_per == '2') { ?>
							<h2><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
							<?php } else { ?>
							<h4><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h4>
							<?php } if ($projects_per <= '3') { ?>
							<div class="port_cat_position"><?php _e('Posted in', 'designcrumbs'); ?> <?php the_category(', ') ?> <?php _e('on', 'designcrumbs'); ?> <?php the_time('F d, Y'); ?></div>
							<?php } else { ?>
							<div class="port_cat_position"><?php if ($projects_per != '5') { ?><?php the_category(', ') ?> / <?php } the_time('F d, Y'); ?></div>
							<?php } ?>
							<a href="<?php $imageArray = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large' ); $imageURL = $imageArray[0]; echo $imageURL; ?>" class="lightbox preview"  title="<?php the_title(); ?>"><?php _e('Preview', 'designcrumbs'); ?></a>
							<?php if ($projects_per != '5') { ?><a href="<?php the_permalink() ?>" class="view_post"><?php _e('View Post', 'designcrumbs'); ?></a><?php } ?>
						</div>
					</div>
					<div class="<?php echo $projects_count_class; ?>_shadow"></div>
					<?php the_post_thumbnail( 'port_image'.$projects_per.'', array('alt' => get_the_title())); ?>
				</div>
			</div>
			<?php endwhile; else : ?>
			<?php endif; ?>
			<?php wp_reset_query(); ?>
		<div class="clear"></div>
	</div>
</div>
<?php if ($project_offset >= '1') { ?>
<div id="toggle-featured_extend">
	<?php $query_default = new WP_Query( array(
		'orderby'      => 'desc',
		'post_type'    => 'post',
		'cat'    => ''.(stripslashes(of_get_option('port_cat'))).'',
		'post_status'  => 'publish',
		'posts_per_page' => ''. $project_offset .'',
		'offset' => ''. $projects_per .''
	));
	if ( $query_default->have_posts() ) : while ( $query_default->have_posts() ) : $query_default->the_post(); ?>
	<div class="single_featured_wrap single_featured_wrap_<?php echo $projects_count_class; ?>">
		<div class="<?php echo $projects_count_class; ?>_wrap info_wrap">
			<?php if (is_sticky()) { ?><span class="sticky_star"></span><?php } ?>
			<a href="<?php the_permalink() ?>" class="touch_link"></a>
			<div class="hover_info">
				<div class="hover_content">
					<?php if ($projects_per == '3') { ?>
					<h3><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
					<?php } elseif ($projects_per == '2') { ?>
					<h2><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
					<?php } else { ?>
					<h4><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h4>
					<?php } if ($projects_per <= '3') { ?>
					<div class="port_cat_position"><?php _e('Posted in', 'designcrumbs'); ?> <?php the_category(', ') ?> <?php _e('on', 'designcrumbs'); ?> <?php the_time('F d, Y'); ?></div>
					<?php } else { ?>
					<div class="port_cat_position"><?php if ($projects_per != '5') { ?><?php the_category(', ') ?> / <?php } the_time('F d, Y'); ?></div>
					<?php } ?>
					<a href="<?php $imageArray = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large' ); $imageURL = $imageArray[0]; echo $imageURL; ?>" class="lightbox preview"  title="<?php the_title(); ?>"><?php _e('Preview', 'designcrumbs'); ?></a>
					<?php if ($projects_per != '5') { ?><a href="<?php the_permalink() ?>" class="view_post"><?php _e('View Post', 'designcrumbs'); ?></a><?php } ?>
				</div>
			</div>
			<div class="<?php echo $projects_count_class; ?>_shadow"></div>
			<?php the_post_thumbnail( 'port_image'.$projects_per.'', array('alt' => get_the_title())); ?>
		</div>
	</div>
	<?php endwhile; else : ?>
	<?php endif; ?>
	<?php wp_reset_query(); ?>
	<div class="clear"></div>
</div>
<?php } ?>
<?php if ($project_offset >= '1') { ?><a href="#" class="slicktoggle-featured the_toggle"><span class="featured_toggle"><?php _e('View More', 'designcrumbs'); ?> +</span></a><?php } ?>
</div> <?php /* End #featured_wrap */ ?>
<?php
/*
Disabled as content not defined

<div id="slides">
	<div class="slidearea slides_container">
		<?php // START THE SLIDE LOOP ?>
		<?php $loop = new WP_Query( array( 'post_type' => 'slides', 'posts_per_page' => 10, 'order' => 'desc' ) ); ?>
		<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
		<div>
			<?php if (has_post_thumbnail()) { ?>
			<a href="<?php if (get_post_meta($post->ID, '_dc_slide_link', true) != '') { ?><?php echo get_post_meta($post->ID, '_dc_slide_link', true);?><?php } else { ?>#<?php }?>" class="slide_link" >
				<?php the_post_thumbnail( 'slide_image', array('alt' => get_the_title(), 'class' => 'left') ); ?>
			</a>
			<?php } else { ?>
			<?php if (get_post_meta($post->ID, '_dc_video_vimeo', true) != '') { ?>
			<iframe src="http://player.vimeo.com/video/<?php echo get_post_meta($post->ID, '_dc_video_vimeo', true);?>?portrait=0" width="940" height="320" frameborder="0"></iframe>
			<?php } elseif (get_post_meta($post->ID, '_dc_video_youtube', true) != '') { ?>
			<iframe width="940" height="320" src="http://www.youtube.com/embed/<?php echo get_post_meta($post->ID, '_dc_video_youtube', true);?>?wmode=opaque" frameborder="0" allowfullscreen></iframe>
			<?php } ?>
			<?php } ?>
		</div>
		<?php endwhile; ?>
		<?php // END THE SLIDE LOOP ?>
	</div>
</div>
<?php
*/
?>
	<?php $query_default = new WP_Query( array(
		'orderby'      => 'desc',
		'post_type'    => 'post',
		'cat'    => '-'.(stripslashes(of_get_option('port_cat'))).'',
		'post_status'  => 'publish',
		'ignore_sticky_posts' => 1,
		'posts_per_page' => '3' 
	));
	if ( $query_default->have_posts() ) : ?>
<div id="home_latest_posts">
	<div id="latest_title"><h3><?php _e('Latest Blog Posts', 'designcrumbs'); ?></h3></div>
	<?php while ( $query_default->have_posts() ) : $query_default->the_post(); global $more; $more = 0; ?>
	<div class="single_latest left">
		<?php if (has_post_thumbnail()) { ?>
		<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
			<?php the_post_thumbnail( 'single_latest', array('alt' => get_the_title()) ); ?>
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
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php endwhile; ?>
	<div class="clear"></div>
</div>
	<?php else : // else; no posts

	endif; ?>
	<?php wp_reset_query(); ?>
	<?php if (of_get_option('cta_text') != '') { ?>
<div class="cta">
	<div class="<?php if ((of_get_option('cta_button') != '') && (of_get_option('cta_link') != '')) { ?>left<?php } else { ?>cta_center<?php } ?>">
	<h3>
		<?php echo stripslashes(of_get_option('cta_text')); ?>
	</h3>
	<?php if (of_get_option('cta_desc') != '') { ?>
	<span><?php echo stripslashes(of_get_option('cta_desc')); ?></span>
	<?php } ?>
	</div>
	<?php if ((of_get_option('cta_button') != '') && (of_get_option('cta_link') != '')) { ?>
	<a href="<?php echo stripslashes(of_get_option('cta_link')); ?>" title="<?php echo stripslashes(of_get_option('cta_button')); ?>" class="button button_secondary right">
		<?php echo stripslashes(of_get_option('cta_button')); ?>
	</a>
	<?php } ?>
	<div class="clear"></div>
</div>
<?php } ?>
<?php get_footer(); ?>