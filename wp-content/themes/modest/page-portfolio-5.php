<?php
/*
Template Name: Portfolio Five Wide
*/
get_header(); ?>

<h2 id="page_title"><?php the_title(); ?></h2>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<?php if($post->post_content != "") : ?>
		<div id="portpage_content">
			<?php the_content(); ?>
			<div class="clear"></div>
		</div>
	<?php endif; ?>
<?php endwhile; endif; ?>

<ul class="filter-list filter"> 
	<li class="active all-projects" ><a href=""><?php _e('All', 'designcrumbs'); ?></a></li> 
	<?php  
	//echo '<pre>';
	//print_r($terms);
	$categories = get_categories('child_of='.(stripslashes(of_get_option('port_cat'))).''); 
	foreach($categories as $category) {
		echo '<li class="cat-item '.str_replace('-', '', $category->slug).'"><a href="" title="'.$category->name.' projects">'.$category->name.'</a> </li>'; 
	} 
	?>
</ul>
<div class="clear"></div>

<ul class="filter-posts">
<?php $query_default = new WP_Query( array(
	'orderby'      => 'desc',
	'post_type'    => 'post',
	'cat'    => ''.(stripslashes(of_get_option('port_cat'))).'',
	'post_status'  => 'publish',
	'posts_per_page'   => '-1',
));
if ( $query_default->have_posts() ) : while ( $query_default->have_posts() ) : $query_default->the_post(); ?>

<?php if (has_post_thumbnail()) { ?>
<li data-id="post-<?php the_ID(); ?>" data-type="<?php $categories = get_the_category(); $count = count($categories); $i=1; foreach($categories as $category) {	echo str_replace('-', '', $category->slug); if ($i<$count) echo ' '; $i++;} ?>" class="post-<?php the_ID(); ?> <?php $categories = get_the_category(); foreach($categories as $category) {	echo str_replace('-', '', $category->slug).' '; } ?> project">                    
	<div class="single_featured_wrap single_featured_wrap_fifth">
		<div class="fifth_wrap info_wrap">
			<?php if (is_sticky()) { ?><span class="sticky_star"></span><?php } ?>
			<a href="<?php the_permalink() ?>" class="touch_link"></a>
			<div class="hover_info">
				<div class="hover_content">
					<h4><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h4>
					<div class="port_cat_position"><?php the_time('F d, Y'); ?></div>
					<a href="<?php $imageArray = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large' ); $imageURL = $imageArray[0]; echo $imageURL; ?>" class="lightbox preview"  title="<?php the_title(); ?>"><?php _e('Preview', 'designcrumbs'); ?></a>
				</div>
			</div>
			<div class="fifth_shadow"></div>
			<?php the_post_thumbnail( 'port_image5', array('alt' => get_the_title(), 'title' => get_the_title()) ); ?>
		</div>
	</div>
</li>
<?php } ?>

<?php endwhile; else : ?>

	<h2><?php _e('Sorry, we can\'t seem to find what you\'re looking for.', 'designcrumbs'); ?></h2>
	<p><?php _e('Please, try one of the links on top.', 'designcrumbs'); ?></p>
        
<?php endif; ?>
<?php wp_reset_query(); ?>
</ul>

<?php get_footer(); ?>