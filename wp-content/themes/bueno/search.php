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
            
                <span class="archive_header"><?php printf( __('Search results for \'%s\'', 'woothemes'), $s ) ?></span>
                
            <?php while (have_posts()) : the_post(); $count++; ?>
                                                                        
                <div class="post">

                    <?php woo_tumblog_the_title($class= "title", $icon = true, $before = "", $after = "", $return = false, $outer_element = "h2") ?>
                    
                    <p class="date">
                    	<span class="day"><?php the_time('j'); ?></span>
                    	<span class="month"><?php the_time('M'); ?></span>
                    </p>
                    
          					<div class="entry">
                      <?php woo_tumblog_content($return = false); ?>
          						<?php the_content( __( 'Continue&nbsp;reading&nbsp;<span class="meta-nav">&rarr;</span>', 'woothemes' ) ); ?>
          						<?php wp_link_pages( array( 'before' => '<p class="page-link">' . __( 'Pages:', 'woothemes' ), 'after' => '</p>' ) ); ?>
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
        
                <div class="more_entries">
                    <?php if (function_exists('wp_pagenavi')) wp_pagenavi(); else { ?>
                    <div class="fl"><?php next_posts_link(__('&larr; Previous Entries', 'woothemes')) ?></div>
                    <div class="fr"><?php previous_posts_link(__('Next Entries &rarr;', 'woothemes')) ?></div>
                    <br class="fix" />
                    <?php } ?> 
                </div>	
                
        </div><!-- /#main -->

        <?php get_sidebar(); ?>

    </div><!-- /#content -->
		
<?php get_footer(); ?>
