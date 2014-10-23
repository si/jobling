<?php
/*
| ----------------------------------------------------
| File        : class-widgets.php
| Project     : Instagram Gallery Widget for Wordpress
| Version     : 1.4.1
| Description : This file contains the widget main class.
| Author      : Luca Grandicelli
| Author URL  : http://www.lucagrandicelli.com
| Plugin URL  : http://www.lucagrandicelli.com/instagram-gallery-widget-wordpress
| License     : GPLv3 or later
| ----------------------------------------------------
*/

class WDG_InstagramGalleryWidget extends WP_Widget {

/*
| ---------------------------------------------
| CLASS CONSTRUCTOR & DECONSTRUCTOR
| ---------------------------------------------
*/
	// Class Constructor.
	// In this section we define the widget global values.
	function WDG_InstagramGalleryWidget() {
	
		// Setting up widget options.
        $widget_ops = array (
            'classname'   => 'instagram-gallery-widget',
            'description' => __('The Instagram Gallery Widget. Drag to configure.', IGW_TRANSLATION_ID)
        );
		
        // Assigning widget options.
		$this->WP_Widget('WDG_InstagramGalleryWidget', __('Instagram Gallery Widget', IGW_TRANSLATION_ID), $widget_ops);
		
		// Assigning global plugin option values to local variable.
		$this->plugin_args = get_option('igw_plugin_options');
	}
	
/*
| ---------------------------------------------
| WIDGET FORM DISPLAY METHOD
| ---------------------------------------------
*/
	// Main form widget method.
	function form($instance) {
	
		// Outputs the options form on widget panel.
		$this->buildWidgetForm($instance);
	}

/*
| ---------------------------------------------
| WIDGET UPDATE & MAIN METHODS
| ---------------------------------------------
*/
	// Main method for widget update process.
	function update($new_instance, $old_instance) {
	
		// Importing global widget values.
		global $igw_default_widget_values;
		
		// Processes widget options to be saved.
		$instance = $old_instance;
		
		foreach($igw_default_widget_values as $k => $v) {
			$instance[$k] = $new_instance[$k];
		}
		
		// Return new widget instance.
		return $instance;
	}
	
	// Main widget method. Main logic here.
	function widget($args, $instance) {
	
		// Extracting Arguments.
		extract($args, EXTR_SKIP);
		
		// Print Before Widget stuff.
		echo $before_widget;
		
		if ('yes' != $instance["igw_widget_title_hide_option"]) {
		
			// Print before title.
			echo $before_title;

			// Display Widget Title.
			echo $instance["igw_widget_title"];
			
			// Print after title.
			echo $after_title;
		}

		// Creating an instance of Special Posts Class.
		$igw = new InstagramGalleryWidget($instance);
		
		// Display Posts.
		$igw->displayGallery(true, 'print');
		
		// Print After Widget stuff.
		echo $after_widget;
	}
	
/*
| ---------------------------------------------
|  METHODS
| ---------------------------------------------
*/
	
	// Build the widget admin form.
	function buildWidgetForm($instance) {
	
		if (empty($instance)) {
			
			// Loading default widget values.
			global $igw_default_widget_values;
			
			// Merging default values with instance array, in case this is empty.
			$instance = wp_parse_args( (array) $instance, $igw_default_widget_values);
		}
?>
		
		<!-- BOF Widget Options -->
		<ul id="igw-widget-optionlist-basic-<?php echo $this->number; ?>" class="igw-widget-optionlist-basic">

			<!-- BOF Widget Title Option. -->
			<li>
				<label for="<?php echo $this->get_field_id('igw_widget_title'); ?>" class="igw-widget-label"><?php _e('Enter Widget Title', IGW_TRANSLATION_ID); ?></label>
				<input type="text" id="<?php echo $this->get_field_id('igw_widget_title'); ?>" name="<?php echo $this->get_field_name('igw_widget_title'); ?>" value="<?php echo htmlspecialchars($instance["igw_widget_title"], ENT_QUOTES); ?>" size="30" />
			</li>
			<!-- EOF Widget Title Option. -->
			
			<!-- BOF Widget Title Hide Option. -->
			<li>
				<input type="checkbox" id="<?php echo $this->get_field_id('igw_widget_title_hide_option'); ?>" name="<?php echo $this->get_field_name('igw_widget_title_hide_option'); ?>" value="yes" <?php checked($instance["igw_widget_title_hide_option"], 'yes'); ?> />
				<label for="<?php echo $this->get_field_id('igw_widget_title_hide_option'); ?>" class="igw-widget-label-inline"><?php _e('Hide Widget Title', IGW_TRANSLATION_ID); ?></label>
			</li>
			<!-- EOF Widget Title Hide Option. -->
			
			<!-- BOF Instagram Photo URL. -->
			<li>
				<label for="<?php echo $this->get_field_id('igw_photo_url'); ?>" class="igw-widget-label"><?php _e('Enter an Instagr.am photos URL', IGW_TRANSLATION_ID); ?></label>
				<input type="text" id="<?php echo $this->get_field_id('igw_photo_url'); ?>" name="<?php echo $this->get_field_name('igw_photo_url'); ?>" value="<?php echo htmlspecialchars($instance["igw_photo_url"], ENT_QUOTES); ?>" size="30" />
			</li>
			<!-- EOF Instagram Photo URL. -->
			
			<!-- BOF Max number of photos. -->
			<li>
				<label for="<?php echo $this->get_field_id('igw_maxnum_photos'); ?>" class="igw-widget-label"><?php _e('Max number of photos to display', IGW_TRANSLATION_ID); ?></label><br />
				<input type="text" id="<?php echo $this->get_field_id('igw_maxnum_photos'); ?>" name="<?php echo $this->get_field_name('igw_maxnum_photos'); ?>" value="<?php echo htmlspecialchars($instance["igw_maxnum_photos"], ENT_QUOTES); ?>" size="8" />
			</li>
			<!-- EOF Max number of photos. -->
			
			<!-- BOF Thumbnail Width. -->
			<li>
				<label for="<?php echo $this->get_field_id('igw_thumbnail_width'); ?>" class="igw-widget-label"><?php _e('Thumbnail Width', IGW_TRANSLATION_ID); ?></label><br />
				<input type="text" id="<?php echo $this->get_field_id('igw_thumbnail_width'); ?>" name="<?php echo $this->get_field_name('igw_thumbnail_width'); ?>" value="<?php echo htmlspecialchars($instance["igw_thumbnail_width"], ENT_QUOTES); ?>" size="8" />px
			</li>
			<!-- EOF Thumbnail Width. -->
			
			<!-- BOF Thumbnail Height. -->
			<li>
				<label for="<?php echo $this->get_field_id('igw_thumbnail_height'); ?>" class="igw-widget-label"><?php _e('Thumbnail Height', IGW_TRANSLATION_ID); ?></label><br />
				<input type="text" id="<?php echo $this->get_field_id('igw_thumbnail_height'); ?>" name="<?php echo $this->get_field_name('igw_thumbnail_height'); ?>" value="<?php echo htmlspecialchars($instance["igw_thumbnail_height"], ENT_QUOTES); ?>" size="8" />px
			</li>
			<!-- EOF Thumbnail Height. -->
			
			<!-- BOF Randomize Option. -->
			<li>
				<input type="checkbox" id="<?php echo $this->get_field_id('igw_randomize_option'); ?>" name="<?php echo $this->get_field_name('igw_randomize_option'); ?>" value="yes" <?php checked($instance["igw_randomize_option"], 'yes'); ?> />
				<label for="<?php echo $this->get_field_id('igw_randomize_option'); ?>" class="igw-widget-label-inline"><?php _e('Randomize Pictures', IGW_TRANSLATION_ID); ?></label><br />
				<small><?php _e('Display thumbnails in random order.'); ?></small>
			</li>
			<!-- EOF Randomize Option. -->
			
			<!-- BOF Effect Filtering Option. -->
			<li>
				<label for="<?php echo $this->get_field_id('igw_effect_filter'); ?>" class="igw-widget-label-inline"><?php _e('Filter images by effect', IGW_TRANSLATION_ID); ?></label>
				<select id="<?php echo $this->get_field_id('igw_effect_filter'); ?>" name="<?php echo $this->get_field_name('igw_effect_filter'); ?>" class="igw-widget-select">
					<option value="none" <?php selected($instance["igw_effect_filter"], 'none'); ?>><?php _e('None (All Images)', IGW_TRANSLATION_ID); ?></option>
					<option value="Normal" <?php selected($instance["igw_effect_filter"], 'Normal'); ?>><?php _e('Normal', IGW_TRANSLATION_ID); ?></option>
					<option value="Amaro" <?php selected($instance["igw_effect_filter"], 'Amaro'); ?>><?php _e('Amaro', IGW_TRANSLATION_ID); ?></option>
					<option value="Rise" <?php selected($instance["igw_effect_filter"], 'Rise'); ?>><?php _e('Rise', IGW_TRANSLATION_ID); ?></option>
					<option value="Hudson" <?php selected($instance["igw_effect_filter"], 'Hudson'); ?>><?php _e('Hudson', IGW_TRANSLATION_ID); ?></option>
					<option value="Valencia" <?php selected($instance["igw_effect_filter"], 'Valencia'); ?>><?php _e('Valencia', IGW_TRANSLATION_ID); ?></option>
					<option value="X-Pro II" <?php selected($instance["igw_effect_filter"], 'X-Pro II'); ?>><?php _e('X-Pro II', IGW_TRANSLATION_ID); ?></option>
					<option value="Lomo-fi" <?php selected($instance["igw_effect_filter"], 'Lomo-fi'); ?>><?php _e('Lomo-fi', IGW_TRANSLATION_ID); ?></option>
					<option value="Earlybird" <?php selected($instance["igw_effect_filter"], 'Earlybird'); ?>><?php _e('Earlybird', IGW_TRANSLATION_ID); ?></option>
					<option value="Sutro" <?php selected($instance["igw_effect_filter"], 'Sutro'); ?>><?php _e('Sutro', IGW_TRANSLATION_ID); ?></option>
					<option value="Toaster" <?php selected($instance["igw_effect_filter"], 'Toaster'); ?>><?php _e('Toaster', IGW_TRANSLATION_ID); ?></option>
					<option value="Brannan" <?php selected($instance["igw_effect_filter"], 'Brannan'); ?>><?php _e('Brannan', IGW_TRANSLATION_ID); ?></option>
					<option value="Inkwell" <?php selected($instance["igw_effect_filter"], 'Inkwell'); ?>><?php _e('Inkwell', IGW_TRANSLATION_ID); ?></option>
					<option value="Walden" <?php selected($instance["igw_effect_filter"], 'Walden'); ?>><?php _e('Walden', IGW_TRANSLATION_ID); ?></option>
					<option value="Hefe" <?php selected($instance["igw_effect_filter"], 'Hefe'); ?>><?php _e('Hefe', IGW_TRANSLATION_ID); ?></option>
					<option value="Apollo" <?php selected($instance["igw_effect_filter"], 'Apollo'); ?>><?php _e('Apollo', IGW_TRANSLATION_ID); ?></option>
					<option value="Poprocket" <?php selected($instance["igw_effect_filter"], 'Poprocket'); ?>><?php _e('Poprocket', IGW_TRANSLATION_ID); ?></option>
					<option value="Nashville" <?php selected($instance["igw_effect_filter"], 'Nashville'); ?>><?php _e('Nashville', IGW_TRANSLATION_ID); ?></option>
					<option value="Gotham" <?php selected($instance["igw_effect_filter"], 'Gotham'); ?>><?php _e('Gotham', IGW_TRANSLATION_ID); ?></option>
					<option value="1977" <?php selected($instance["igw_effect_filter"], '1977'); ?>><?php _e('1977', IGW_TRANSLATION_ID); ?></option>
					<option value="Lord Kelvin" <?php selected($instance["igw_effect_filter"], 'Lord Kelvin'); ?>><?php _e('Lord Kelvin', IGW_TRANSLATION_ID); ?></option>
				</select>
			</li>
			<!-- EOF Effect Filtering Option. -->
			
			<!-- BOF FancyBox Option. -->
			<li>
				<input type="checkbox" id="<?php echo $this->get_field_id('igw_fancybox_option'); ?>" name="<?php echo $this->get_field_name('igw_fancybox_option'); ?>" value="yes" <?php checked($instance["igw_fancybox_option"], 'yes'); ?> />
				<label for="<?php echo $this->get_field_id('igw_fancybox_option'); ?>" class="igw-widget-label-inline"><?php _e('Disable FancyBox Effect', IGW_TRANSLATION_ID); ?></label><br />
				<small><?php _e('Disabling will open images in the original instagr.am window'); ?></small>
			</li>
			<!-- EOF FancyBox Option. -->
		</ul>
		<!-- EOF Widget Options -->
<?php
	}
}