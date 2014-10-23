<?php

// Theme Prefix: dcs_

/* ========================================= Constants ========================================= */

if(!defined('DCS_THEME_DIR')) {
	define('DCS_THEME_DIR', dirname(__FILE__));
}

/* ========================================= File Includes ========================================= */

include(DCS_THEME_DIR . '/includes/scripts.php');

/* ========================================= General Things We Need ========================================= */

add_editor_style(); // Adds CSS to the editor to match the front end of the site.
add_theme_support('automatic-feed-links');
if ( ! isset( $content_width ) ) $content_width = 590; // This is the max width of the content, thus the max width of large images that are uploaded.
require_once(dirname(__FILE__) . "/includes/shortcodes/friendly-shortcode-buttons.php"); // Includes shortcode insert button in the editor
require_once(dirname(__FILE__) . "/includes/support/support.php"); // Load support tab

// Load Language File
load_theme_textdomain( 'designcrumbs', TEMPLATEPATH.'/languages' );
$locale = get_locale();
$locale_file = TEMPLATEPATH."/languages/$locale.php";
if ( is_readable($locale_file) )
	require_once($locale_file);

// Check for Options Framework Plugin
of_check();

	function of_check()
{
  if ( !function_exists('of_get_option') )
  {
    add_thickbox(); // Required for the plugin install dialog.
    add_action('admin_notices', 'of_check_notice');
  }
}

// The Admin Notice
function of_check_notice()
{
?>
  <div class='updated fade'>
    <p><?php _e('The Options Framework plugin is required for this theme to function properly.', 'designcrumbs'); ?> <a href="<?php echo admin_url('plugin-install.php?tab=plugin-information&plugin=options-framework&TB_iframe=true&width=640&height=517'); ?>" class="thickbox onclick"><?php _e('Install now.', 'designcrumbs'); ?></a></p>
  </div>
<?php
}

/* =================================== Options Framework =================================== */

if ( !function_exists( 'of_get_option' ) ) {
function of_get_option($name, $default = 'false') {
	
	$optionsframework_settings = get_option('optionsframework');
	
	// Gets the unique option id
	$option_name = $optionsframework_settings['id'];
	
	if ( get_option($option_name) ) {
		$options = get_option($option_name);
	}
		
	if ( !empty($options[$name]) ) {
		return $options[$name];
	} else {
		return $default;
	}
}
}

/* 
 * This is an example of how to add custom scripts to the options panel.
 * This one shows/hides the an option when a checkbox is clicked.
 */
 
add_action('optionsframework_custom_scripts', 'optionsframework_custom_scripts');
 
function optionsframework_custom_scripts() { ?>
 
<script type="text/javascript">
jQuery(document).ready(function() {

	// adds support tab
	jQuery(".embed-themes").html("<iframe width='770' height='390' src='http://themes.designcrumbs.com/iframe/index.html'></iframe>");
	
});
</script>
 
<?php
}
 
/* Removes the code stripping */
 
add_action('admin_init','optionscheck_change_santiziation', 100);
 
function optionscheck_change_santiziation() {
    remove_filter( 'of_sanitize_textarea', 'of_sanitize_textarea' );
    add_filter( 'of_sanitize_textarea', 'of_sanitize_textarea_custom' );
}
 
function of_sanitize_textarea_custom($input) {
    global $allowedposttags;
        $of_custom_allowedtags["embed"] = array(
			"src" => array(),
			"type" => array(),
			"allowfullscreen" => array(),
			"allowscriptaccess" => array(),
			"height" => array(),
			"width" => array()
		);
		$of_custom_allowedtags["script"] = array(
			"type" => array()
		);
		$of_custom_allowedtags["iframe"] = array(
			"height" => array(),
			"width" => array(),
			"src" => array(),
			"frameborder" => array(),
			"allowfullscreen" => array()
		);
		$of_custom_allowedtags["object"] = array(
			"height" => array(),
			"width" => array()
		);
		$of_custom_allowedtags["param"] = array(
			"name" => array(),
			"value" => array()
		);
 
	$of_custom_allowedtags = array_merge($of_custom_allowedtags, $allowedposttags);
	$output = wp_kses( $input, $of_custom_allowedtags);
	return $output;
}

/* =================================== Add Fancybox to linked Images =================================== */

/**
 * Attach a class to linked images' parent anchors
 * e.g. a img => a.img img
 */
function give_linked_images_class($html, $id, $caption, $title, $align, $url, $size, $alt = '' ){
	$classes = 'lightbox'; // separated by spaces, e.g. 'img image-link'

	// check if there are already classes assigned to the anchor
	if ( preg_match('/<a.*? class=".*?">/', $html) ) {
		$html = preg_replace('/(<a.*? class=".*?)(".*?>)/', '$1 ' . $classes . '$2', $html);
	} else {
		$html = preg_replace('/(<a.*?)>/', '$1 class="' . $classes . '" >', $html);
	}
	return $html;
}
add_filter('image_send_to_editor','give_linked_images_class',10,8);

/* =================================== Add Menus =================================== */
add_theme_support( 'menus' );

register_nav_menus( array(
	'primary' => __( 'Main Menu', 'modest' ),
	'secondary' => __( 'Footer Menu', 'modest' ),
) );

/* ========================================= Featured Images ========================================= */

add_theme_support( 'post-thumbnails', array( 'post', 'slides' ) ); /* ===== ADDS FEATURED IMAGE TO PAGES ===== */
add_image_size( 'port_thumb', 108, 108, true ); /* ===== SETS FEATURED IMAGE SIZE  ===== */
add_image_size( 'port_image', 590, 9999 ); /* ===== SETS FEATURED IMAGE SIZE  ===== */
add_image_size( 'port_image2', 440, 200, true ); /* ===== SETS FEATURED IMAGE SIZE  ===== */
add_image_size( 'port_image3', 270, 175, true ); /* ===== SETS FEATURED IMAGE SIZE  ===== */
add_image_size( 'port_image4', 195, 150, true ); /* ===== SETS FEATURED IMAGE SIZE  ===== */
add_image_size( 'port_image5', 146, 150, true ); /* ===== SETS FEATURED IMAGE SIZE  ===== */
add_image_size( 'blog_image', 590, 300, true ); /* ===== SETS FEATURED IMAGE SIZE  ===== */
add_image_size( 'single_latest', 240, 150, true ); /* ===== SETS FEATURED IMAGE SIZE  ===== */
add_image_size( 'alt_blog_image', 260, 170, true ); /* ===== SETS FEATURED IMAGE SIZE  ===== */
add_image_size( 'archive_image', 46, 46, true ); /* ===== SETS FEATURED IMAGE SIZE  ===== */
add_image_size( 'slide_image', 940, 320, true ); /* ===== SETS FEATURED IMAGE SIZE  ===== */

/* =================================== Add Slides Post Type =================================== */

register_post_type('slides', array(
	'label' => __('Slides', 'designcrumbs'),
	'singular_label' => __('Slide', 'designcrumbs'),
	'public' => true,
	'show_ui' => true, // UI in admin panel
	'_builtin' => false, // It's a custom post type, not built in!
	'_edit_link' => 'post.php?post=%d',
	'capability_type' => 'post',
	'hierarchical' => false,
	'has_archive' => false,
	'supports' => array(
			'title',
			'thumbnail',)
	));
	
/* ====================================================== Slide Meta Box ====================================================== */

add_action( 'init' , 'dcs_create_metaboxes' );
function dcs_create_metaboxes() {
$prefix = '_dc_';
$meta_boxes = array();

$meta_boxes[] = array(
    'id' => 'dc_slides',
    'title' => __('Slide Video Embeds', 'designcrumbs'),
    'pages' => array('slides'), // post type
	'context' => 'normal',
	'priority' => 'high',
	'show_names' => true, // Show field names on the left
    'fields' => array(
    	array(
	        'name' => __('Slide Link', 'designcrumbs'),
	        'desc' => __('Where would you like the slide to link to? Put in the full URL including http://', 'designcrumbs'),
	        'id' => $prefix . 'slide_link',
	        'type' => 'text'
	    ),
        array(
	        'name' => __('YouTube Video ID', 'designcrumbs'),
	        'desc' => __('If the YouTube link is http://www.youtube.com/watch?v=Iv69kB_e9KY, the ID is Iv69kB_e9KY. Enter that ID above.', 'designcrumbs'),
	        'id' => $prefix . 'video_youtube',
	        'type' => 'text'
	    ),
        array(
	        'name' => __('Vimeo Video ID', 'designcrumbs'),
	        'desc' => __('If the Vimeo link is http://vimeo.com/22639018, the ID is 22639018. Enter that ID above.', 'designcrumbs'),
	        'id' => $prefix . 'video_vimeo',
	        'type' => 'text'
	    ),
    )
);
require_once('lib/metabox/init.php');
}

/* =================================== The Excerpt =================================== */

function improved_trim_excerpt($text) {
        global $post;
        if ( '' == $text ) {
                $text = get_the_content('');
                $text = apply_filters('the_content', $text);
                $text = str_replace('\]\]\>', ']]&gt;', $text);
                $text = strip_tags($text, '<p>');
                $excerpt_length = 25;
                $words = explode(' ', $text, $excerpt_length + 1);
                if (count($words)> $excerpt_length) {
                        array_pop($words);
                        array_push($words, '...');
                        $text = implode(' ', $words);
                }
        }
        return $text;
}

remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'improved_trim_excerpt');

/* ========================================= Checks for subcategories for the archives ========================================= */

// If is category or subcategory of $cat_id
if (!function_exists('is_category_or_sub')) {
	function is_category_or_sub($cat_id = 0) {
	    foreach (get_the_category() as $cat) {
	    	if ($cat_id == $cat->cat_ID || cat_is_ancestor_of($cat_id, $cat)) return true;
	    }
	    return false;
	}
}

/* ========================================= Creates function to check if single page is in child category ========================================= */

if ( ! function_exists( 'post_is_in_descendant_category' ) ) {
	function post_is_in_descendant_category( $cats, $_post = null ) {
		foreach ( (array) $cats as $cat ) {
			// get_term_children() accepts integer ID only
			$descendants = get_term_children( (int) $cat, 'category' );
			if ( $descendants && in_category( $descendants, $_post ) )
				return true;
		}
		return false;
	}
}

/* ========================================= Sidebars ========================================= */

if ( function_exists('register_sidebars') )
	register_sidebar(array(
		'name' => 'Overall_Sidebar',
		'id' => 'Overall_Sidebar',
		'description' => __('These widgets will show up on every page and post.', 'designcrumbs'),
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>'
	));
	register_sidebar(array(
		'name' => 'Pages_Sidebar',
		'id' => 'Pages_Sidebar',
		'description' => __('These widgets will show up only on pages.', 'designcrumbs'),
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>'
	));
	register_sidebar(array(
		'name' => 'Blog_Sidebar',
		'id' => 'Blog_Sidebar',
		'description' => __('These widgets will show up in the blog and on blog posts.', 'designcrumbs'),
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>'
	));
	register_sidebar(array(
		'name' => 'Footer',
		'id' => 'Footer',
		'description' => __('These widgets will appear in the footer.', 'designcrumbs'),
		'before_widget' => '<div class="footer_widget">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>'
	));
	
/* =================================== Count How Many Widgets are in a Sidebar =================================== */

function count_sidebar_widgets( $sidebar_id, $echo = true ) {
    $the_sidebars = wp_get_sidebars_widgets();
    if( !isset( $the_sidebars[$sidebar_id] ) )
        return __( 'Invalid sidebar ID', 'designcrumbs' );
    if( $echo )
        echo count( $the_sidebars[$sidebar_id] );
    else
        return count( $the_sidebars[$sidebar_id] );
}

// To call it on the front end - count_sidebar_widgets( 'some-sidebar-id' );

/* =================================== User Extras =================================== */

add_action( 'show_user_profile', 'my_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'my_show_extra_profile_fields' );

function my_show_extra_profile_fields( $user ) { ?>

	<h3><?php _e('Extra profile information', 'designcrumbs'); ?></h3>

	<table class="form-table">

		<tr>
			<th><label for="twitter"><?php _e('Twitter', 'designcrumbs'); ?></label></th>

			<td>
				<input type="text" name="twitter" id="twitter" value="<?php echo esc_attr( get_the_author_meta( 'twitter', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e('Please enter your Twitter username without the @.', 'designcrumbs'); ?></span>
			</td>
		</tr>

	</table>
<?php }

add_action( 'personal_options_update', 'my_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'my_save_extra_profile_fields' );

function my_save_extra_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	update_user_meta( $user_id, 'twitter', $_POST['twitter'] );
}

function my_author_box() { ?>
			<div class="about_the_author">
				<?php echo get_avatar( get_the_author_meta('email'), '70' ); ?>
				<div class="author_info">
					<div class="author_title"><?php _e('This post was written by', 'designcrumbs'); ?> <?php the_author_posts_link(); ?>
					</div>
					<div class="author_about">
					<?php the_author_meta( 'description' ); ?>
					</div>
					<?php if (get_the_author_meta('twitter') != '' || get_the_author_meta('url') != '' ) { ?>
					<div class="author_links">
						<?php if (get_the_author_meta('twitter') != '' ) { ?>
						<a href="http://twitter.com/<?php the_author_meta('twitter'); ?>" title="<?php _e('My Twitter', 'designcrumbs'); ?>"><?php _e('My Twitter', 'designcrumbs'); ?> &raquo;</a>
						<?php } if (get_the_author_meta('url') != '' ) { ?>
						<a href="<?php the_author_meta('url'); ?>" title="My Website"><?php _e('My Website', 'designcrumbs'); ?> &raquo;</a>
						<?php } ?>
					<div class="clear"></div>
					</div>
					<?php } // End check for twitter & url ?>
				</div>
				<div class="clear"></div>
			</div>
	<?php
}
	
/* =================================== Specific User Widget =================================== */

class dcs_featured_user_widget extends WP_Widget {

		//function to set up widget in admin
		function dcs_featured_user_widget() {
		
				$widget_ops = array( 'classname' => 'featured-user', 
				'description' => __('A widget that will display a specified user\'s gravatar, display name, bio, and link to their author post archive.', 'designcrumbs') );
				
				$control_ops = array( 'width' => 200, 'height' => 350, 'id_base' => 'featured-user' );
				$this->WP_Widget( 'featured-user', __('Featured User', 'designcrumbs'), $widget_ops, $control_ops );
		
		}


		//function to echo out widget on sidebar
		function widget( $args, $instance ) {
		extract( $args );
		
				$title = $instance['title'];
				
				echo $before_widget;
				echo "<div class='featured_user'>";
		
				// if user written title echo out
				if ( $title ){
				echo $before_title . $title . $after_title;
				}
			    //don't touch this!
				$userid = $instance['user_id'];
				
				//user information array
				//refer to http://codex.wordpress.org/Function_Reference/get_userdata
				$userinfo = get_userdata($userid);
				
				//user meta data
				//refer to http://codex.wordpress.org/Function_Reference/get_user_meta
				$userbio = get_user_meta($userid,'description',true);
				
				//user post url
				//refer to http://codex.wordpress.org/Function_Reference/get_author_posts_url
				$userposturl = get_author_posts_url($userid);	
				
				?>			
				
				<!--Now we print out speciifc user informations to screen!-->
				<div class='specific_user'>
				<a href='<?php echo $userposturl; ?>' title='<?php echo $userinfo->display_name; ?>'>
				<?php echo get_avatar($userid,58); ?>
				</a>
				<h4>
				<a href='<?php echo $userposturl; ?>' title='<?php echo $userinfo->display_name; ?>' class='featured_user_name'>
				<?php echo $userinfo->display_name; ?>
				</a></h4>
				<?php echo $userbio; ?>
				<div class="clear"></div>
				</div>
				<!--end-->
				
				<?php

				echo '</div>';
				echo $after_widget;
		
		 }//end of function widget



		//function to update widget setting
		function update( $new_instance, $old_instance ) {
		
				$instance = $old_instance;
				$instance['title'] = strip_tags( $new_instance['title'] );
				$instance['user_id'] = strip_tags( $new_instance['user_id'] );
				return $instance;
		
		}//end of function update


		//function to create Widget Admin form
		function form($instance) {
		
				$instance = wp_parse_args( (array) $instance, array( 'title' => '','user_id' => '') );
				
				$instance['title'] = $instance['title'];
				$instance['user_id'] = $instance['user_id'];
						
				?>

				<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title:', 'designcrumbs'); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>"
				 type="text" value="<?php echo $instance['title']; ?>" />
				</p>
				
				<p>
				<label for="<?php echo $this->get_field_id( 'user_id' ); ?>"><?php _e('Select User:', 'designcrumbs'); ?></label> 
				<select id="<?php echo $this->get_field_id( 'user_id' );?>" name="<?php echo $this->get_field_name( 'user_id' );?>" class="widefat" style="width:100%;">

				<?php
				$instance = $instance['user_id'];
				$option_list = user_get_users_list_option($instance);
				echo $option_list;
				?>
				</select>
				
				</p>
				
				
				<?php
		
	      }//end of function form($instance)

}//end of  Class

//function to get all users
function user_get_users_list_option($instance){
$output = '';
global $wpdb; 
$users = $wpdb->get_results("SELECT display_name, ID FROM $wpdb->users");
	foreach($users as $u){
    $uname = $u->display_name;
    $uid = $u->ID;
    $output .="<option value='$uid'";
    if($instance == $uid){
    $output.= 'selected="selected"';
    } 
    $output.= ">$uname</option>";
	}
return $output;
}

register_widget('dcs_featured_user_widget');

/* =================================== Testimonial Widget =================================== */

class dcs_TestimonialWidget extends WP_Widget
{
 /**
  * Declares the TestimonialWidget class.
  *
  */
    function dcs_TestimonialWidget(){
    $widget_ops = array('classname' => 'widget_testimonial', 'description' => __( 'Displays a testimonial along with a name, company, and URL.', 'designcrumbs') );
    $control_ops = array('width' => 300, 'height' => 300);
    $this->WP_Widget('testimonial', __('Testimonial', 'designcrumbs'), $widget_ops, $control_ops);
    }

  /**
    * Displays the Widget
    *
    */
    function widget($args, $instance){
      extract($args);
      $testi_title = apply_filters('widget_title', empty($instance['testi_title']) ? '' : $instance['testi_title']);
      $testi_name = empty($instance['testi_name']) ? '' : $instance['testi_name'];
      $testi_company = empty($instance['testi_company']) ? '' : $instance['testi_company'];
      $testi_url = empty($instance['testi_url']) ? '' : $instance['testi_url'];
      $testi_testimonial = empty($instance['testi_testimonial']) ? '' : $instance['testi_testimonial'];

      # Before the widget
      echo '<div class="widget_testimonial widget">';

      # The title
      if ( $testi_title )
      	echo $before_title . $testi_title . $after_title;
		echo '<div class="the_testimonial">'.$testi_testimonial . '</div>';
		echo '<div class="the_testimonial_author">';
		echo '<strong>- ' . $testi_name . '</strong>';
      if ( $testi_url ) {
      	echo '<span><a href="'.$testi_url.'" title="'.$testi_company.'">' . $testi_company . '</a></span>';
      } else {
		echo '<span>' . $testi_company .'</span>';}
		echo '</div>';
		echo '<div class="clear"></div>';
		
      # After the widget
      echo '</div>';
  }

  /**
    * Saves the widgets settings.
    *
    */
    function update($new_instance, $old_instance){
      $instance = $old_instance;
      $instance['testi_title'] = strip_tags(stripslashes($new_instance['testi_title']));
      $instance['testi_name'] = strip_tags(stripslashes($new_instance['testi_name']));
      $instance['testi_company'] = strip_tags(stripslashes($new_instance['testi_company']));
      $instance['testi_url'] = strip_tags(stripslashes($new_instance['testi_url']));
      $instance['testi_testimonial'] = strip_tags(stripslashes($new_instance['testi_testimonial']));

    return $instance;
  }

  /**
    * Creates the edit form for the widget.
    *
    */
    function form($instance){
      //Defaults

      $testi_title = htmlspecialchars($instance['testi_title']);
      $testi_name = htmlspecialchars($instance['testi_name']);
      $testi_company = htmlspecialchars($instance['testi_company']);
      $testi_url = htmlspecialchars($instance['testi_url']);
      $testi_testimonial = htmlspecialchars($instance['testi_testimonial']);

    //output  
	# Title
	echo '<p><label for="' . $this->get_field_name('testi_title') . '">' . __('Title (Optional):','designcrumbs') . '</label><input class="widefat" id="' . $this->get_field_id('testi_title') . '" name="' . $this->get_field_name('testi_title') . '" type="text" value="' . $testi_title . '" /></p>';
	# Name
	echo '<p><label for="' . $this->get_field_name('testi_name') . '">' . __('Name:','designcrumbs') . '</label><input class="widefat" id="' . $this->get_field_id('testi_name') . '" name="' . $this->get_field_name('testi_name') . '" type="text" value="' . $testi_name . '" /></p>';
	# Company
	echo '<p><label for="' . $this->get_field_name('testi_company') . '">' . __('Company:','designcrumbs') . '</label><input class="widefat" id="' . $this->get_field_id('testi_company') . '" name="' . $this->get_field_name('testi_company') . '" type="text" value="' . $testi_company . '" /></p>';
	# URL
	echo '<p><label for="' . $this->get_field_name('testi_url') . '">' . __('URL:','designcrumbs') . '</label><input class="widefat" id="' . $this->get_field_id('testi_url') . '" name="' . $this->get_field_name('testi_url') . '" type="text" value="' . $testi_url . '" /></p>';
	# Testimonial
	echo '<p><label for="' . $this->get_field_name('testi_testimonial') . '">' . __('The Testimonial:','designcrumbs') . '</label><textarea class="widefat" id="' . $this->get_field_id('testi_testimonial') . '" cols="20" rows="6" value="' . $testi_testimonial . '" name="' . $this->get_field_name('testi_testimonial') . '">' . $testi_testimonial . '</textarea></p>';
  }

}// END class

  function dcs_TestimonialInit() {
  register_widget('dcs_TestimonialWidget');
  }
  add_action('widgets_init', 'dcs_TestimonialInit');
  
/* =================================== Recent Posts Widget =================================== */

class dcs_Widget_Recent_Posts extends WP_Widget {

    function dcs_Widget_Recent_Posts() {
        $widget_ops = array('classname' => 'widget_recent_entries', 'description' => __('Displays your recent posts along with a thumbnail.','designcrumbs') );
        $this->WP_Widget('my-recent-posts', __('Modest Recent Posts','designcrumbs'), $widget_ops);
        $this->alt_option_name = 'widget_recent_entries';

        add_action( 'save_post', array(&$this, 'flush_widget_cache') );
        add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
        add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
    }

    function widget($args, $instance) {
        $cache = wp_cache_get('widget_my_recent_posts', 'widget');

        if ( !is_array($cache) )
            $cache = array();

        if ( isset($cache[$args['widget_id']]) ) {
            echo $cache[$args['widget_id']];
            return;
        }

        ob_start();
        extract($args);

        $title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Posts', 'designcrumbs') : $instance['title'], $instance, $this->id_base);
        if ( !$number = (int) $instance['number'] )
            $number = 10;
        else if ( $number < 1 )
            $number = 1;
        else if ( $number > 15 )
            $number = 15;

        $r = new WP_Query(array('showposts' => $number, 'nopaging' => 0, 'post_status' => 'publish', 'ignore_sticky_posts' => true, 'post_type' => 'post'));
        if ($r->have_posts()) :
?>
        <?php echo $before_widget; ?>
        <?php if ( $title ) echo $before_title . $title . $after_title; ?>
        <ul id="recent_posts">
        <?php  while ($r->have_posts()) : $r->the_post(); ?>
        <li class="recent_line">
        	<?php if (has_post_thumbnail()) { ?>
        	<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
				<?php the_post_thumbnail( 'archive_image', array( 'alt' => get_the_title()) ); ?>
			</a>
			<?php } ?>
        	<a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?></a>
		</li>
        <?php endwhile; ?>
        </ul>
        <?php echo $after_widget; ?>
<?php
        // Reset the global $the_post as this query will have stomped on it
        wp_reset_postdata();

        endif;

        $cache[$args['widget_id']] = ob_get_flush();
        wp_cache_set('widget_my_recent_posts', $cache, 'widget');
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = (int) $new_instance['number'];
        $this->flush_widget_cache();

        $alloptions = wp_cache_get( 'alloptions', 'options' );
        if ( isset($alloptions['widget_recent_entries']) )
            delete_option('widget_recent_entries');

        return $instance;
    }

    function flush_widget_cache() {
        wp_cache_delete('widget_my_recent_posts', 'widget');
    }

    function form( $instance ) {
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        if ( !isset($instance['number']) || !$number = (int) $instance['number'] )
            $number = 5;
?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','designcrumbs'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

        <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:','designcrumbs'); ?></label>
        <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
<?php
    }
}

register_widget('dcs_Widget_Recent_Posts');

/* ====================================================== COMMENTS ====================================================== */

function custom_comment($comment, $args, $depth) {
       $GLOBALS['comment'] = $comment; ?>
<li <?php comment_class(); ?> id="comment-<?php comment_ID( ); ?>">
<div class="the_comment">
<?php if(function_exists('get_avatar')) { echo get_avatar($comment, '50'); } ?>
<div class="the_comment_author"><?php comment_author_link() ?></div>
<small class="commentmetadata">
<?php comment_date('F d, Y') ?> <?php _e('at', 'designcrumbs'); ?> <?php comment_date('g:i a') ?><?php edit_comment_link( __('Edit', 'designcrumbs'),' &nbsp;|&nbsp; ',''); ?>
</small>
<div class="clear"></div>
<?php if ($comment->comment_approved == '0') : //message if comment is held for moderation ?>
<br><em><?php _e('Your comment is awaiting moderation', 'designcrumbs'); ?>.</em><br>
<?php endif; ?>
	<div class="the_comment_text"><?php comment_text() ?></div>
<div class="reply">
<?php echo comment_reply_link(array('reply_text' => __('Reply', 'designcrumbs'), 'depth' => $depth, 'max_depth' => $args['max_depth']));  ?>
</div>	
</div>
<?php } ?>
<?php function custom_pings($comment, $args, $depth) {
       $GLOBALS['comment'] = $comment; ?>
<li <?php comment_class(); ?> id="comment-<?php comment_ID( ); ?>">
     <?php _e('Trackback from', 'designcrumbs'); ?> <em><?php comment_author_link() ?></em>
<br /><small><?php comment_date('l, j F, Y') ?></small>
<br /><?php comment_text() ?>
     <?php edit_comment_link( __('Edit', 'designcrumbs'),'<br /> &nbsp;|&nbsp; ',''); ?>
<?php } ?>
<?php
add_filter('get_comments_number', 'comment_count', 0);
function comment_count( $count ) {
	global $id;
	$comments_by_type = &separate_comments(get_comments('post_id=' . $id));
	return count($comments_by_type['comment']);
}

/* ====================================================== PRESSTRENDS ====================================================== */

// Presstrends
function presstrends() {

// Add your PressTrends and Theme API Keys
$api_key = '1x4ox5f40ysz73fsqt9x0npg9a2dswj0164d';
$auth = '4incww9eswsbqlyoug4w1umz1521q8so0';

// NO NEED TO EDIT BELOW
$data = get_transient( 'presstrends_data' );
if (!$data || $data == ''){
$api_base = 'http://api.presstrends.io/index.php/api/sites/add/auth/';
$url = $api_base . $auth . '/api/' . $api_key . '/';
$data = array();
$count_posts = wp_count_posts();
$comments_count = wp_count_comments();
$theme_data = get_theme_data(get_stylesheet_directory() . '/style.css');
$plugin_count = count(get_option('active_plugins'));
$data['url'] = stripslashes(str_replace(array('http://', '/', ':' ), '', site_url()));
$data['posts'] = $count_posts->publish;
$data['comments'] = $comments_count->total_comments;
$data['theme_version'] = $theme_data['Version'];
$data['theme_name'] = str_replace( ' ', '', get_bloginfo( 'name' ));
$data['plugins'] = $plugin_count;
$data['wpversion'] = get_bloginfo('version');
foreach ( $data as $k => $v ) {
$url .= $k . '/' . $v . '/';
}
$response = wp_remote_get( $url );
set_transient('presstrends_data', $data, 60*60*24);
}}

add_action('wp_head', 'presstrends'); 

?>