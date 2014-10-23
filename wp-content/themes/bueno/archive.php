<?php
/**
 * @package WordPress
 * @subpackage Bueno
 */
get_header();
?>
       
    <div id="content" class="col-full">
		<div id="main" class="col-left">
            
            <?php if (have_posts()) : $count = 0; ?>
            
				<?php if (is_category()) { ?>
                <span class="archive_header"><span class="fl cat"><?php _e('Archive', 'woothemes'); ?> | <?php single_cat_title(); ?></span> <span class="fr catrss"><?php $cat_obj = $wp_query->get_queried_object(); $cat_id = $cat_obj->cat_ID; echo '<a href="'; get_category_rss_link(true, $cat, ''); echo '">RSS feed for this section</a>'; ?></span></span>        
            
                <?php } elseif (is_day()) { ?>
                <span class="archive_header"><?php _e('Archive', 'woothemes'); ?> | <?php the_time($GLOBALS['woodate']); ?></span>
    
                <?php } elseif (is_month()) { ?>
                <span class="archive_header"><?php _e('Archive', 'woothemes'); ?> | <?php the_time('F, Y'); ?></span>
    
                <?php } elseif (is_year()) { ?>
                <span class="archive_header"><?php _e('Archive', 'woothemes'); ?> | <?php the_time('Y'); ?></span>
    
                <?php } elseif (is_author()) { ?>
                <span class="archive_header"><?php _e('Archive by Author', 'woothemes'); ?></span>
    
                <?php } elseif (is_tag()) { ?>
                <span class="archive_header"><?php _e('Tag Archives:', 'woothemes'); ?> <?php single_tag_title(); ?></span>
                
                <?php } ?>
				
				<div class="fix"></div>
            
            <?php while (have_posts()) : the_post(); $count++; ?>
                                                                        
                <!-- Post Starts -->
                <div class="post">

                    <?php woo_tumblog_the_title($class= "title", $icon = true, $before = "", $after = "", $return = false, $outer_element = "h2") ?>
                    
                    <p class="date">
                    	<span class="day"><?php the_time('j'); ?></span>
                    	<span class="month"><?php the_time('M'); ?></span>
                    </p>
                    
          					<?php if ( has_post_thumbnail() ) : ?>
          					<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail(); ?></a>
          					<?php endif; ?>
                    
                    <div class="entry">
                      <?php woo_tumblog_content($return = false); ?>
                    	<?php the_content(); ?>
                      <p>
                        <a href="<?php echo get_permalink(); ?>">∞</a>
            						<?php edit_post_link( __( 'Edit This', 'woothemes' ), '', '' ); ?>
            				  </p>
                    </div>
                    
                    <div class="post-meta">
                    
                    	<ul>
                    		<li class="comments">
                    			<span class="head"><?php _e('Tags', 'woothemes') ?></span>
                    			<span class="body"><?php the_tags( '<ul><li>' , '</li><li>', '</li></ul>' ); ?> </span>
                    		</li>
                    		<li class="categories">
                    			<span class="head"><?php _e('Categories', 'woothemes') ?></span>
                    			<span class="body"><?php the_category(', ') ?></span>
                    		</li>
                    		<li class="author">
                    			<span class="head"><?php _e('Author', 'woothemes') ?></span>
                    			<span class="body"><?php the_author_posts_link(); ?></span>
                    		</li>
                    	</ul>
                    	
                    	<div class="fix"></div>
                    
                    </div><!-- /.post-meta -->

                </div><!-- /.post -->
                                                    
			<?php endwhile; else: ?>
				<div class="post">
                	<p><?php _e('Sorry, no posts matched your criteria.', 'woothemes') ?></p>
                </div><!-- /.post -->
            <?php endif; ?>  
        
			<?php if (  $wp_query->max_num_pages > 1 ) : ?>        
			<div class="more_entries">
			    <div class="fl"><?php next_posts_link(__('&larr; Older Entries', 'woothemes')) ?></div>
				<div class="fr"><?php previous_posts_link(__('Newer Entries &rarr;', 'woothemes')) ?></div>
			    <br class="fix" />
			</div>		
			<?php endif; ?>
                
		</div><!-- /#main -->

        <?php get_sidebar(); ?>

    </div><!-- /#content -->
		
<?php get_footer(); ?>