<div id="footer">

		<div id="wptouch-switch-link">
			<?php wptouch_core_footer_switch_link(); ?>
		</div>
		<?php do_action( 'wp_mobile_theme_footer' ); ?>
	<p>
	<?php if ( !bnc_wptouch_is_exclusive() ) { wp_footer(); } ?>
	</p>
</div>

<?php wptouch_get_stats(); 
//WPtouch theme designed and developed by Dale Mugford and Duane Storey for BraveNewCode.com
//If you modify it, please keep the link credit *visible* in the footer (and keep the WordPress credit, too!) that's all we ask folks.
?>
</body>
</html>
