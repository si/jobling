<?php
/**
 * @package WordPress
 * @subpackage Bueno
 */
?>
	<div id="extended-footer">
	
		<div class="col-full">
	
			<div class="block one">
				
				<?php dynamic_sidebar('footer-1'); ?>
				
			</div><!-- /.block -->
			
			<div class="block two">
			
				<?php dynamic_sidebar('footer-2'); ?>
			
			</div><!-- /.block -->
			
			<div class="block three">
				
				<?php dynamic_sidebar('footer-3'); ?>
			
			</div><!-- /.block -->
			
		</div><!-- /.col-full -->
		
	</div><!-- /#extended-footer -->
	
	<div id="footer">
	
		<div class="col-full">	
	
			<div id="copyright" class="col-left">
				<p>&copy; 1980–<?php echo Date('Y'); ?> <a href="http://sijobling.com/">Simon Jobling</a></p>
			</div>
			
			<div id="credit" class="col-right">
				<?php printf( __( 'Temporary Theme: %1$s by %2$s.', 'woothemes' ), 'Bueno', '<a href="http://www.woothemes.com/woomember/go?r=56181&redirect=www.woothemes.com/2009/11/bueno/" rel="designer">WooThemes</a>' ); ?>
			</div>
			
		</div><!-- /.col-full -->
		
	</div><!-- /#footer -->
	
</div><!-- /#container -->
<?php wp_footer(); ?>

</body>
</html>