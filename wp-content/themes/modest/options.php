<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 * 
 */

function optionsframework_option_name() {

	// This gets the theme name from the stylesheet (lowercase and without spaces)
	$themename = get_theme_data(STYLESHEETPATH . '/style.css');
	$themename = $themename['Name'];
	$themename = preg_replace("/\W/", "", strtolower($themename) );
	
	$optionsframework_settings = get_option('optionsframework');
	$optionsframework_settings['id'] = $themename;
	update_option('optionsframework', $optionsframework_settings);
	
	// echo $themename;
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the "id" fields, make sure to use all lowercase and no spaces.
 *  
 */

function optionsframework_options() {

	$heading_fonts = array("bitter" => "Bitter","droidsans" => "Droid Sans","franchise" => "Franchise","marketingscript" => "Marketing Script","museo" => "Museo Slab","rokkitt" => "Rokkitt");
	$projects_per = array("2" => "2","3" => "3","4" => "4","5" => "5");
	
	
	// Pull all the categories into an array
	$options_categories = array();  
	$options_categories_obj = get_categories();
	foreach ($options_categories_obj as $category) {
    	$options_categories[$category->cat_ID] = $category->cat_name;
	}
	
	// Pull all the pages into an array
	$options_pages = array();  
	$options_pages_obj = get_pages('sort_column=post_parent,menu_order');
	$options_pages[''] = 'Select a page:';
	foreach ($options_pages_obj as $page) {
    	$options_pages[$page->ID] = $page->post_title;
	}
		
	// If using image radio buttons, define a directory path
	$imagepath =  get_bloginfo('stylesheet_directory') . '/images/';
		
	$options = array();
		
	$options[] = array( "name" => __('Basic Settings', 'designcrumbs'),
						"type" => "heading");
						
	$options[] = array( "name" => __('Logo', 'designcrumbs'),
						"desc" => __('Upload your logo. Max height should be 40px, if it is bigger, it will be resized.', 'designcrumbs'),
						"id" => "logo",
						"type" => "upload");
						
	$options[] = array( "name" => __('Favicon', 'designcrumbs'),
						"desc" => __('The Favicon is the little 16x16 icon that appears next to your URL in the browser. It is not required, but recommended.', 'designcrumbs'),
						"id" => "favicon",
						"type" => "upload");
						
	$options[] = array( "name" => __('Site Layout', 'designcrumbs'),
						"desc" => __('Select a layout for the site.', 'designcrumbs'),
						"id" => "layout",
						"std" => "content_left",
						"type" => "images",
						"options" => array(
							'content_right' => $imagepath . '2cl.png',
							'content_left' => $imagepath . '2cr.png',)
						);
						
	$options[] = array( "name" => __('Blog Style', 'designcrumbs'),
						"desc" => __('Select the style for your blog.', 'designcrumbs'),
						"id" => "blog_layout",
						"std" => "magazine",
						"type" => "select",
						"options" => array("classic" => __('Classic', 'designcrumbs'),"magazine" => __('Magazine', 'designcrumbs')));				
										
	$options[] = array( "name" => __('Portfolio Category', 'designcrumbs'),
						"desc" => __('Select your portfolio category. If you have multiple, they should be children of one main category. <em>Note: you must have posts in the category in order for it to show in the list.</em>', 'designcrumbs'),
						"id" => "port_cat",
						"type" => "select",
						"options" => $options_categories);
						
	$options[] = array( "name" => __('Portfolio Comments', 'designcrumbs'),
						"desc" => __('Do you want to enable comments on posts in the portfolio category? <em>Note that comments must be enabled on each post and in Settings > Discussion. These will both be on by default.</em>', 'designcrumbs'),
						"id" => "port_comments",
						"std" => "no",
						"type" => "radio",
						"options" => array("yes" => __('Yes', 'designcrumbs'),"no" => __('No', 'designcrumbs')));					
						
	$options[] = array( "name" => __('Pre Header Message', 'designcrumbs'),
						"desc" => __('This is the message that shows up on top of the site. It is completely optional.', 'designcrumbs'),
						"id" => "pre_message",
						"std" => "",
						"type" => "text");
						
	$options[] = array( "name" => __('Tracking Code', 'designcrumbs'),
						"desc" => __('Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme. If you need analytics, you can <a href="http://www.google.com/analytics" target="_blank">go here</a>.', 'designcrumbs'),
						"id" => "analytics",
						"std" => "",
						"type" => "textarea");
						
	$options[] = array( "name" => __('Credit Where Credit Is Due', 'designcrumbs'),
						"desc" => __('Checking this box will give credit to Jake Caputo and the Modest theme in the footer.', 'designcrumbs'),
						"id" => "give_credit",
						"std" => "1",
						"type" => "checkbox");
						
	$options[] = array( "name" => __('Home Page', 'designcrumbs'),
						"type" => "heading");
						
	$options[] = array( "name" => __('Heading Text', 'designcrumbs'),
						"desc" => __('The big, strong wording on the top of your home page.', 'designcrumbs'),
						"id" => "heading_text",
						"std" => "",
						"type" => "text");
						
	$options[] = array( "name" => __('Subheading Text', 'designcrumbs'),
						"desc" => __('Maybe a slogan or a few lines about you or your company.', 'designcrumbs'),
						"id" => "subheading_text",
						"std" => "",
						"type" => "textarea");
						
	$options[] = array( "name" => __('Projects Per Line', 'designcrumbs'),
						"desc" => __('Select the number of projects you would like to display per line on the home page. Any posts after the first line will be show in the "View More" drawer section.', 'designcrumbs'),
						"id" => "projects_per",
						"std" => "4",
						"type" => "select",
						"class" => "mini",
						"options" => $projects_per);
						
	$options[] = array( "name" => __('Max Number of Projects to be Displayed', 'designcrumbs'),
						"desc" => __('This number should be a multiple of the number chosen above, just to keep things pretty.', 'designcrumbs'),
						"id" => "projects_total",
						"std" => "8",
						"class" => "mini",
						"type" => "text");
						
	$options[] = array( "name" => __('Slider FX', 'designcrumbs'),
						"desc" => __('Select your slide transition.', 'designcrumbs'),
						"id" => "slider_fx",
						"type" => "select",
						"std" => "fade",
						"options" => array("fade" => __('Fade', 'designcrumbs'),"slide" => __('Slide', 'designcrumbs')));
						
	$options[] = array( "name" => __('Slider Time', 'designcrumbs'),
						"desc" => __('How long in seconds (use a whole number) would you like a slide to hold for before moving on to the next one? Setting this to 0 (zero) will not autoplay the slides. Zero is recommended if you are using videos in your slider.', 'designcrumbs'),
						"id" => "slider_time",
						"type" => "text",
						"class" => "mini",
						"std" => "8");
						
	$options[] = array( "name" => __('Call To Action Heading', 'designcrumbs'),
						"desc" => __('This is a call to action on the bottom of the home page. If left blank, the area will not show.', 'designcrumbs'),
						"id" => "cta_text",
						"std" => "",
						"type" => "text");
						
	$options[] = array( "name" => __('Call To Action Text', 'designcrumbs'),
						"desc" => __('A short description if you want.', 'designcrumbs'),
						"id" => "cta_desc",
						"std" => "",
						"type" => "text");
						
	$options[] = array( "name" => __('Call To Action Button Text', 'designcrumbs'),
						"desc" => __('What does the button say?', 'designcrumbs'),
						"id" => "cta_button",
						"std" => "",
						"type" => "text");
						
	$options[] = array( "name" => __('Call To Action Button Link', 'designcrumbs'),
						"desc" => __('The link the call to action button goes to, including the http://', 'designcrumbs'),
						"id" => "cta_link",
						"std" => "",
						"type" => "text");
						
	$options[] = array( "name" => __('Styles', 'designcrumbs'),
						"type" => "heading");
						
	$options[] = array( "name" => __('Heading Font', 'designcrumbs'),
						"desc" => __('Select the font for the headings of the site.', 'designcrumbs'),
						"id" => "heading_font",
						"std" => "museo",
						"type" => "select",
						"options" => $heading_fonts);
						
	$options[] = array( "name" => __('Link Color', 'designcrumbs'),
						"desc" => __('Select the color for your links.', 'designcrumbs'),
						"id" => "link_color",
						"std" => "",
						"type" => "color");
						
	$options[] = array( "name" => __('Button Color', 'designcrumbs'),
						"desc" => __('Select the color for your buttons.', 'designcrumbs'),
						"id" => "button_color",
						"std" => "",
						"type" => "color");
						
	$options[] = array( "name" => __('Secondary Button Color', 'designcrumbs'),
						"desc" => __('Select the color for secondary buttons. This will be used in places such as the call to action on the home page.', 'designcrumbs'),
						"id" => "button_color_secondary",
						"std" => "",
						"type" => "color");
										
	$options[] = array( "name" => __('Social Networks', 'designcrumbs'),
						"type" => "heading");
					
	$options[] = array( "name" => __('Twitter', 'designcrumbs'),
						"desc" => __('Enter the URL to your Twitter profile.', 'designcrumbs'),
						"id" => "twitter",
						"type" => "text"); 

	$options[] = array( "name" => __('Facebook', 'designcrumbs'),
						"desc" => __('Enter the URL to your Facebook profile.', 'designcrumbs'),
						"id" => "facebook",
						"type" => "text");
						
	$options[] = array( "name" => __('Google+', 'designcrumbs'),
						"desc" => __('Enter the URL to your Google+ profile.', 'designcrumbs'),
						"id" => "google",
						"type" => "text");
					
	$options[] = array( "name" => __('Flickr', 'designcrumbs'),
						"desc" => __('Enter the URL to your Flickr Profile.', 'designcrumbs'),
						"id" => "flickr",
						"type" => "text");
					
	$options[] = array( "name" => __('Forrst', 'designcrumbs'),
						"desc" => __('Enter the URL to your Forrst Profile.', 'designcrumbs'),
						"id" => "forrst",
						"type" => "text");
					
	$options[] = array( "name" => __('Dribbble', 'designcrumbs'),
						"desc" => __('Enter the URL to your Dribbble Profile.', 'designcrumbs'),
						"id" => "dribbble",
						"type" => "text");
					
	$options[] = array( "name" => __('Tumblr', 'designcrumbs'),
						"desc" => __('Enter the URL to your Tumblr Profile.', 'designcrumbs'),
						"id" => "tumblr",
						"type" => "text");
					
	$options[] = array( "name" => __('Vimeo', 'designcrumbs'),
						"desc" => __('Enter the URL to your Vimeo Profile.', 'designcrumbs'),
						"id" => "vimeo",
						"type" => "text");
						
	$options[] = array( "name" => __('Pinterest', 'designcrumbs'),
						"desc" => __('Enter the URL to your Pinterest Profile.', 'designcrumbs'),
						"id" => "pinterest",
						"type" => "text");
						
	// Support
						
	$options[] = array( "name" => __('Support', 'designcrumbs'),
						"type" => "heading");					
						
	$options[] = array( "name" => __('Theme Documentation & Support', 'designcrumbs'),
						"desc" => "<p class='dc-text'>Due to the nature of the deal you got this theme in, there's no support available. However, there is a detailed help file that was included with the theme, or you can hit button below to view it online.</p>
						
						<div class='dc-buttons'><a target='blank' class='dc-button help-button' href='http://support.designcrumbs.com/help_files/modestwp/'><span class='dc-icon icon-help'>Help File</span></a><a target='blank' class='dc-button custom-button' href='http://www.designcrumbs.com/theme-customization-request'><span class='dc-icon icon-custom'>Customize Theme</span></a></div>
						
						<h4 class='heading'>More Themes by Design Crumbs</h4>
						
						<div class='embed-themes'></div>
						
						",
						"type" => "info");		
				
	return $options;
}