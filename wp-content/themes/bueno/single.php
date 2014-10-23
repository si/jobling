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
          						<?php if(function_exists("tweet_button")) { echo tweet_button('Si'); }  ?>
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
                    		<li class="short-url">
                    			<span class="head"><?php _e('Short URL', 'woothemes') ?></span>
                    			<span class="body">
						<?php
						  $mykey_values = get_post_custom_values('yourls_shorturl');
						  if(count($mykey_values)>0) {
  						  foreach ($mykey_values as $key => $value) {
  						    echo '<a href="'.$value.'">'.$value.'</a>'; 
  						  }
  						} else {
  						  echo 'N/A';
						  }
						?>
					</span>
                    		</li>
                    	</ul>              	                    	
                    	
                    	<div class="fix"></div>                   	
                   
                    </div><!-- /.post-meta -->
                    
					<div id="nav-below" class="navigation">
						<div class="nav-previous"><?php previous_post_link( '%link', __( '<span class="meta-nav">&larr;</span> %title' ) ); ?></div>
						<div class="nav-next"><?php next_post_link( '%link', __('%title <span class="meta-nav">&rarr;</span>') ); ?></div>
					</div><!-- #nav-below -->                                    	                    

                </div><!-- /.post -->                
                
				<?php 
				// Remove reference to Comments as no longer available. Need to replace with Twitter reactions.
				//  comments_template('', true); 
				
				?>
                                                    
			<?php endwhile; else: ?>
				<div class="post">
                	<p><?php _e('Sorry, no posts matched your criteria.', 'woothemes') ?></p>
  				</div><!-- /.post -->             
           	<?php endif; ?>  
        
		</div><!-- /#main -->

        <?php get_sidebar(); ?>

    </div><!-- /#content -->
		
<?php get_footer(); ?>