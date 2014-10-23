<?php
//-----------------------------------  // Add Support Tab Styles //-----------------------------------//

function load_custom_wp_admin_style(){
        //Support Tab Style
        wp_register_style( 'custom_wp_admin_css', get_template_directory_uri() . '/includes/support/options-style.css', false, '1.0.0' );
        wp_enqueue_style( 'custom_wp_admin_css' );
}
add_action('admin_enqueue_scripts', 'load_custom_wp_admin_style');

?>