<?php /*

**************************************************************************

Plugin Name:  YOURLS: Short URL Widget
Plugin URI:   http://www.viper007bond.com/wordpress-plugins/yourls-shorturl-widget/
Version:      1.1.0
Description:  Creates a widget that outputs the short URL to the current post or page. Requires the <a href="http://wordpress.org/extend/plugins/yourls-wordpress-to-twitter/">YOURLS: WordPress to Twitter</a> plugin.
Author:       Viper007Bond
Author URI:   http://www.viper007bond.com/

**************************************************************************/

add_action( 'init',         'viper_yourls_localization' );
add_action( 'widgets_init', 'viper_yourls_register_widget' );

// Load the localization textdomains
function viper_yourls_localization() {
	load_plugin_textdomain( 'yourls-shorturl-widget', false, '/yourls-shorturl-widget/localization' );
}

// Register the widget
function viper_yourls_register_widget() {
	register_widget( 'Viper_YOURLS_Widget' );
}

// The widget class
class Viper_YOURLS_Widget extends WP_Widget {

	// Contruct the widget
	function Viper_YOURLS_Widget() {
		$widget_ops = array( 'classname' => 'widget_viper_yourls', 'description' => __( 'Display the short URL to the current post or page.', 'yourls-shorturl-widget' ) );
		$control_ops = array( 'width' => 400, 'height' => 350 );
		$this->WP_Widget( 'viper_yourls', __( 'YOURLS: Short URL', 'yourls-shorturl-widget' ), $widget_ops, $control_ops );
	}

	// Output the widget
	function widget( $args, $instance ) {
		global $posts;

		// Only do something on posts/pages
		if ( !function_exists('wp_ozh_yourls_geturl') || !is_singular() || empty($posts) || !$url = wp_ozh_yourls_geturl( $posts[0]->ID ) )
			return;

		$type = false;
		if ( is_single() )
			$type = __( 'post', 'yourls-shorturl-widget' );
		if ( is_page() )
			$type = __( 'page', 'yourls-shorturl-widget' );
		if ( !$type )
			$type = apply_filters( 'yourls_shorturl_widget_type', $posts[0]->post_type );

		$title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance );

		$placeholders = array(
			'[url]'     => $url,
			'[type]'    => $type,
			'[title]'   => get_the_title( $posts[0]->ID ),
			'[longurl]' => get_permalink( $posts[0]->ID ),
		);

		$text  = apply_filters( 'widget_text', str_replace( array_keys( $placeholders ), array_map( 'esc_html', array_values( $placeholders ) ), $instance['text'] ), $instance );

		// Widget output
		echo $args['before_widget'];

		if ( !empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];

		echo '<div class="widget_viper_yourls_content">';
		echo $instance['filter'] ? wpautop( $text ) : $text;
		echo '</div>';

		echo $args['after_widget'];
	}

	// Handle settings form submits
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		if ( current_user_can('unfiltered_html') )
			$instance['text'] = $new_instance['text'];
		else
			$instance['text'] = stripslashes( wp_filter_post_kses( $new_instance['text'] ) );
		$instance['filter'] = isset( $new_instance['filter'] );
		return $instance;
	}

	// The settings form
	function form( $instance ) {
		if ( !function_exists('wp_ozh_yourls_geturl') ) {
			echo '<p>' . sprintf( __( 'The <a href="%s">YOURLS: WordPress to Twitter</a> plugin is not currently installed and activated! This widget requires the plugin to function.</p>', 'yourls-shorturl-widget' ), 'http://wordpress.org/extend/plugins/yourls-wordpress-to-twitter/' ) . '</p>';
			return;
		}

		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '' ) );
		$title = strip_tags( $instance['title'] );
		$text = format_to_edit( $instance['text'] );
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<textarea class="widefat" style="margin-bottom:10px" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>

		<p><?php _e( '<code>[url]</code> will be replaced with the short URL, <code>[type]</code> will be replaced with the content type (post or page), and <code>[title]</code> will be replaced with the current post/page title.', 'yourls-shorturl-widget' ); ?></p>

		<p><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs.'); ?></label></p>
<?php
	}
}

?>