<?php get_header(); ?>


<?php if ((in_category(stripslashes(of_get_option('port_cat')))) || (post_is_in_descendant_category(stripslashes(of_get_option('port_cat'))))) { ?>
	
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<h2 id="page_title"><?php the_title(); ?></h2>
			<div class="posts-wrap">
				<div id="post-single_portfolio" <?php post_class(); ?>>
				<?php 
/*
				if (has_post_thumbnail()) { ?>
					<a href="<?php $image_id = get_post_thumbnail_id(); $image_url = wp_get_attachment_image_src($image_id,'full', true); echo $image_url[0]; ?>" class="lightbox">
						<?php the_post_thumbnail( 'port_image', array('alt' => get_the_title()) ); ?>
					</a>
				<?php } 
  				
*/
				?>
				<?php 
				/*
				Removed the attachments section
				$args = array(
					'post_type' => 'attachment',
					'numberposts' => null,
					'post_status' => null,
					'order'		  => 'ASC',
					'post_parent' => $post->ID,
					'posts_per_page'	=> '99'
				);
				$attachments = get_posts($args); ?>
				<?php if(count($attachments) > 1) { ?>
				<div id="port_thumbs">
				<?php foreach ($attachments as $attachment) { ?>		
					<a href="<?php echo wp_get_attachment_url($attachment->ID); ?>" class="lightbox">
						<?php echo wp_get_attachment_image( $attachment->ID, 'port_thumb' ); ?>
					</a>
				<?php } ?>
					<div class="clear"></div>
				</div>
				<?php } ?>
				<?php if (of_get_option('port_comments') == 'yes') { ?>
					<?php comments_template('', true); ?>
				<?php } ?>
			*/
			?>
				</div>
			</div><!-- end .posts-wrap -->
			<div class="entry-content">

        <?php if (has_post_thumbnail()) the_post_thumbnail(); ?>

				<?php the_content(); ?>
				<div class="clear"></div>
				
				<?php if (has_tag()) { ?>
					<div class="single-meta">
						<?php the_tags( __('Tagged with ', 'designcrumbs'), ", ", " " ) ?>
					</div>
				<?php } ?>

				<?php
				  $mykey_values = get_post_custom_values('yourls_shorturl');
				  if(count($mykey_values)>0) {
  				foreach ($mykey_values as $key => $value) : ?>
					<div class="single-meta">
						<?php echo 'Short Link: <a href="'.$value.'">'.$value.'</a>'; ?>
					</div>
				<?php endforeach;
  				}
				?>

			</div>
		<?php endwhile; else: ?>

			<div class="posts-wrap">
				<?php _e('Sorry, no posts matched your criteria', 'designcrumbs'); ?>.
			</div><!-- end .posts-wrap -->
			<?php get_sidebar(); ?>
			
		<?php endif; ?>

<?php } else { ?>
	
	<h2 id="page_title"><?php the_title(); ?></h2>
	<div class="posts-wrap">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<div id="post-single" <?php post_class(); ?>>
				<div class="entry-content" id="entry-content-single">
					<?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>
					<div class="clear"></div>
					<?php if (has_tag()) { ?>
						<div class="single-meta">
							<?php the_tags( __('Tagged with ', 'designcrumbs'), ", ", " " ) ?>
						</div>
					<?php } ?>
					<div class="additional-meta">
					<?php if (has_category()) { ?>
						<div class="meta_block">
							<span><?php _e('Category', 'designcrumbs'); ?></span>
							<?php the_category(', ') ?>
						</div>
					<?php } ?>
						<div class="meta_block">	
							<span><?php _e('Author', 'designcrumbs'); ?></span>
							<?php the_author_posts_link(); ?>
						</div>
						<div class="meta_block">	
							<span><?php _e('Post Date', 'designcrumbs'); ?></span>
							<?php the_time('F d, Y'); ?>
						</div>
    				<?php
    				  $mykey_values = get_post_custom_values('yourls_shorturl');
    				  if(count($mykey_values)>0) {
      				foreach ($mykey_values as $key => $value) : ?>
  						<div class="meta_block">	
  							<span><?php _e('Short Link', 'designcrumbs'); ?></span>
  							<?php echo '<a href="'.$value.'">'.$value.'</a>'; ?>
  						</div>
    				<?php endforeach;
      				}
    				?>

						<div class="clear"></div>
					</div>
				</div><!-- end .entry-content -->
				<?php /** 
				REMOVED Author Box 
				my_author_box(); 
				**/
				?>
			</div><!-- end .post -->		
			
			<?php comments_template('', true); ?>
	
		<?php endwhile; else: ?>

			<?php _e('Sorry, no posts matched your criteria', 'designcrumbs'); ?>.

		<?php endif; ?>
	</div><!-- end .posts-wrap -->	
	<?php get_sidebar(); ?>

<?php } ?>

<?php get_footer(); ?>
