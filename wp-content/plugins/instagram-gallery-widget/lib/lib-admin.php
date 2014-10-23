<?php
/*
| --------------------------------------------------------
| File        : lib-admin.php
| Version     : 1.4.1
| Description : This file contains various functions
|               for plugin initialization and
|               admin panel building.
| Project     : Instagram Gallery Widget for Wordpress
| Author      : Luca Grandicelli
| Author URL  : http://www.lucagrandicelli.com
| Plugin URL  : http://www.lucagrandicelli.com/instagram-gallery-widget-wordpress
| License     : GPLv3 or later
| --------------------------------------------------------
*/

/*
| ---------------------------------------------
| PLUGIN INIT FUNCTIONS
| ---------------------------------------------
*/

// Main initializing function.
function igw_admin_init() {

	// Registering Plugin admin stylesheet.
	wp_register_style('igw-admin-stylesheet' , IGW_PLUGIN_URL . IGW_BACKEND_ADMIN_CSS);

	// Forcing Loading jQuery.
	wp_enqueue_script('jquery');
}

// Function for plugin widget registration.
function igw_install_widgets() {

	// Register widget.
	register_widget("WDG_InstagramGalleryWidget");
	
	if (!is_admin()) {
		
		// FRONT-END SCRIPTS AND CSS
		
		// Registering front-end custom JS init script.
		wp_register_script('igw-frontend-js-init'  , IGW_PLUGIN_URL . IGW_FRONTEND_JS_INIT);
		
		// Registering FancyBox Script.
		wp_register_script('igw-fancybox-script'   , IGW_PLUGIN_URL . IGW_FANCYBOX_SCRIPT);
		
		// Registering FancyBox CSS.
		wp_register_style('igw-fancybox-stylesheet', IGW_PLUGIN_URL . IGW_FANCYBOX_CSS);
		
		// Forcing Loading jQuery.
		wp_enqueue_script('jquery');
		
		// Enqueuing front-end custom JS init script.
		wp_enqueue_script('igw-frontend-js-init');
		
		// Enqueuing FancyBox Script.
		wp_enqueue_script('igw-fancybox-script');
		
		// Enqueuing FancyBox CSS.
		wp_enqueue_style('igw-fancybox-stylesheet');
		
	}
}

// This function checks whether the plugin has been updated.
// If it's so, it performs several checks before updating the plugin db options.
function igw_plugin_init() {

	// Importing global default options array.
	global $igw_default_plugin_values;
	
	// Checking if plugin db options exist.
	if (get_option('igw_plugin_options')) {
	
		// Setting current db options.
		$igw_current_options = get_option('igw_plugin_options');		
		
		// Checking if plugin has a db version option or if this is minor than the current version declared through the updated code.
		if ( (!isset($igw_current_options["igw_version"]) && isset($igw_default_plugin_values["igw_version"]) ) || ( version_compare($igw_current_options["igw_version"], $igw_default_plugin_values["igw_version"], '<')) ) {
		
			// Plugin version is prior to 1.5 or is lower to the current updated files.
			// For first, let's check for new array keys and eventually put them in the current array option.
			$igw_diff_array = array_diff_key($igw_default_plugin_values, $igw_current_options);
			
			// Check if there are no new array keys. In this case, we need to update only the version option.
			if (!empty($igw_diff_array)) {
				
				// Merge current option array with new values.
				$igw_result_array = array_merge($igw_current_options, $igw_diff_array);
				
				// Update current plugin option version.
				$igw_result_array["igw_version"] = $igw_default_plugin_values["igw_version"];
				
				// Update db options.
				update_option('igw_plugin_options', $igw_result_array);
				
			} else {
			
				// Update current plugin option version.
				$igw_current_options["igw_version"] = $igw_default_plugin_values["igw_version"];
				
				// Update db options.
				update_option('igw_plugin_options', $igw_current_options);
			}

		} else {
			// Current bulk is updated. Do Nothing.
		}
	} else {
		// First Install. Do nothing.
	}
}

/*
| ---------------------------------------------
| AMIN MENUS PAGE AND STYLESHEETS
| ---------------------------------------------
*/

// Main Admin setup function.
function igw_admin_setup() {
	
	// Adding SubMenu Page.
	$page = add_submenu_page('options-general.php', __('Instagram Gallery Widget - Settings Page', 'Instagram Gallery Widget - Settings Page'), __('Instagram Gallery Widget', 'Instagram Gallery Widget'), 'administrator', __FILE__, 'igw_admin_menu_options');
	
    // Using registered $page handle to hook stylesheet loading.
    add_action('admin_print_styles-' . $page, 'igw_admin_plugin_add_style');
}

// Main function to add admin stylesheet.
function igw_admin_plugin_add_style() {
	
	// Enqueuing plugin admin stylesheet.
	wp_enqueue_style('igw-admin-stylesheet');
}

// Main function to add widget stylesheet into current theme.
function igw_theme_css() {
	
	// Printing spcific stylesheet for widgets in current theme.
	$theme_css =  get_option('igw_plugin_options');
	echo "<style type=\"text/css\" media=\"screen\">" . stripslashes($theme_css['igw_themecss']) . "</style>";
}

/*
| ---------------------------------------------
| PLUGIN COMPATIBILITY CHECK
| ---------------------------------------------
*/

function igw_check_plugin_compatibility() {
	
	// Checking for PHP version.
	$current_ver = phpversion();
    switch(version_compare($current_ver, IGW_REQUIRED_PHPVER)) {
		case -1:
			$error = new WP_Error('broke', __("<strong>Error!</strong> You're running an old version of PHP. In order for this plugin to work, you must enable your server with PHP support version 5.0.0+. Please contact your hosting/housing company support, and check how to enable it.</a>"));
			if (is_wp_error($error)) {
				echo "<div id=\"message\" class=\"error\"><p>" . $error->get_error_message() . "</p></div>";
			}
		break;
			
        case 0:
        case 1:
		break;
    }	
	
	if (!in_array  ('curl', get_loaded_extensions())) {
		$error = new WP_Error('broke', __("<strong>Error!</strong> CURL libraries are not supported by your server. Please contact your hosting/housing company support, and check how to enable it."));
		if (is_wp_error($error)) {
		   echo "<div id=\"message\" class=\"error\"><p>" . $error->get_error_message() . "</p></div>";
		}
	}
}

/*
| ---------------------------------------------
| BUILDING PLUGIN OPTION PAGE
| ---------------------------------------------
*/

// Main function that builds the plugin admin page.
function igw_admin_menu_options() {

	// Checking if we have the manage option permission enabled.
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
?>
	<!-- Generating Option Page HTML. -->
	<div class="wrap">
		<div id="igw-admin-container">
			<?php
			
				// For first, let's check if there is some kind of compatibility error.
				igw_check_plugin_compatibility();
				
				// Updating and validating data/POST Check.
				igw_update_data($_POST, get_option('igw_plugin_options'));
				
				// Importing global default options array.
				$igw_current_options = get_option('igw_plugin_options');
			?>
			
			<!-- BOF Title and Description section. -->
			<h2><?php _e('Instagram Gallery Widget v' . IGW_PLUGIN_VERSION . '- Settings Page', IGW_TRANSLATION_ID); ?></h2>
			<div class="igw_option_header_l1">
			<?php _e('In this page you can configure the stylesheet for the Instagram Gallery Widget. If you need to restore the original CSS, you can find it under instagram-gallery-widget/css/css-frontend-widget-restore.css.<br />
				All other options available are under the widget panel.<p><strong>(*) Required Field</strong></p>', IGW_TRANSLATION_ID); ?>
			</div>
			<div class="igw_option_header_l2">
				<a class="donate-logo" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6DXPBEJV5QTNJ" title="Feel free to donate for this plugin. I'll be grateful if you will :)">
					<img src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" alt="" />
				</a>
			</div>
			<br style="clear:both;" />
			<!-- EOF Title and Description section. -->
			
				<!--  Open Form. -->
				<form id="igw_admin_form" name="igw_admin_form" action="" method="POST">
				
				<!-- BOF Thumbnail Section. -->
					<div class="metabox-holder">
						<div class="postbox">
							
							<h3><?php _e('Appearance Section', IGW_TRANSLATION_ID);?></h3>
							
							<!-- BOF Left Box. -->
							<div id="igw-admin-leftcontent">
								<p><?php  _e('This is the stylesheet that handles the widget visualization on your theme. Basic properties are applied. Feel free to modify it to suite your needs.', IGW_TRANSLATION_ID); ?></p>
							</div>"
							<!-- EOF Left Box. -->
							
							<!-- BOF Right Box. -->
							<div id="igw-admin-rightcontent">
								<ul>
									
									<!--BOF Thumbnail Size -->
									<li>
										<label for="igw_themecss"><?php _e('Theme CSS', IGW_TRANSLATION_ID); ?></label>
										<textarea id="igw_themecss" name="igw_themecss" rows="20" cols="80" /><?php echo stripslashes($igw_current_options['igw_themecss']); ?></textarea>
									</li>
									<!--EOF Thumbnail Size -->
								</ul>
							</div>
							<!-- EOF Right Box. -->
							
							<div class="clearer"></div>
							
						</div><!-- EOF postbox. -->
					</div><!-- EOF metabox-holder. -->
					<!-- EOF Thumbnail section. -->
				
				<input type="submit" name="submit" class="button-primary" value="<?php _e('Save Options', IGW_TRANSLATION_ID); ?>" />
			</form> <!--EOF Form. -->
		</div> <!-- EOF igw_adm_container -->
	</div> <!-- EOF Wrap. -->
<?php
}

// Main function to update form option data.
function igw_update_data($data, $igw_current_options) {

	// Checking if form has been submitted.
	if (isset($_POST['submit'])) {
		
		// Remove the "submit" $_POST entry.
		unset($data['submit']);
		
		// Updating WP Option with new $_POST data.
		update_option('igw_plugin_options', $data);
		
		// Displaying "save settings" message.
		echo "<div id=\"message\" class=\"updated\"><p><strong>" . __('Settings Saved', IGW_TRANSLATION_ID) . "</strong></p></div>";
	}
}