<form method="get" id="searchform" action="<?php echo home_url(); ?>/">
	<div>
		<input type="text" class="search_input" value="<?php _e('Search', 'designcrumbs'); ?>" name="s" id="s" onfocus="if (this.value == '<?php _e('Search', 'designcrumbs'); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('Search', 'designcrumbs'); ?>';}" />
		<input type="submit" id="searchsubmit" value="<?php _e('Search', 'designcrumbs'); ?>" />
	</div>
</form>