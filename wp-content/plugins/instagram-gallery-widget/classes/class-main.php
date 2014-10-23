<?php
/*
| ----------------------------------------------------
| File        : class-main.php
| Project     : Instagram Gallery Widget for Wordpress
| Version     : 1.4.1
| Description : This file contains the main plugin class
|               which handles all the important visualization processes.
| Author      : Luca Grandicelli
| Author URL  : http://www.lucagrandicelli.com
| Plugin URL  : http://www.lucagrandicelli.com/instagram-gallery-widget-wordpress
| License     : GPLv3 or later
| ----------------------------------------------------
*/

class InstagramGalleryWidget {

/*
| ---------------------------------------------
| CLASS PROPERTIES
| ---------------------------------------------
*/
	
	// Declaring widget instance options array.
	private $widget_args;
	
	// Declaring Instagr.am Data Array
	private $instagramData;

/*
| ---------------------------------------------
| CLASS CONSTRUCTOR & DECONSTRUCTOR
| ---------------------------------------------
*/
	// Class Constructor.
	// In this section we define selected widget values.
	public function __construct($args = array()) {
		
		// Including external widget values.
		global $igw_default_widget_values;
		
		// Double check if $args is an array.
		if (!is_array($args)) {
			$args = array();
		}
		
		// Setting up widget options to be available throughout the plugin.
		$this->widget_args = array_merge($igw_default_widget_values, $args);
		
		// Setting up collected data.
		$this->instagramData = array(
			'userID'          => NULL,
			'user_pictures'   => array()
		);
	}
	
	// Class Deconstructor.
	public function __deconstruct() {}

/*
| ---------------------------------------------
| STATIC METHODS
| ---------------------------------------------
*/

	// This method handles all the actions for the plugin to be initialized.
	static function install_plugin() {
		
		// Loading text domain for translations.
		load_plugin_textdomain(IGW_TRANSLATION_ID, false, dirname(plugin_basename(__FILE__)) . IGW_LANG_FOLDER);
		
		// Importing global default options array.
		global $igw_default_plugin_values;
		
		// Creating WP Option with default values.
		add_option('igw_plugin_options', $igw_default_plugin_values, '', 'no');
	}
	
	// This method handles all the actions for the plugin to be uninstalled.
	static function uninstall_plugin() {
		
		// Deleting main WP Option.
		delete_option('igw_plugin_options');
	}

/*
| ---------------------------------------------
| CLASS MAIN METHODS
| ---------------------------------------------
*/
	
	private function igw_get_userID($photo_url){
		
		// Initialize CURL process.
		$ch = curl_init($photo_url);
		
		// Checking for Open Basedir option in PHP settings.
		if(ini_get("open_basedir") == "")
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		
		// CURL Settings.
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, "Instagram 1.18.17 (iPhone; iPhone OS 4.3.2; lv_LV)");
		
		// Executing CURL Call.
		$data = curl_exec($ch);
		
		// Retrieving http code response.
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
		// If the response is ok, proceed.
		if($httpcode >= 200 && $httpcode < 400){
			
			// Matching User ID.
		    $pattern = "/\/profiles\/profile_([0-9]+)_/i";
		    preg_match($pattern, $data, $matches);
			
			// Check for a valid user ID.
		    if(isset($matches[1]) && intval($matches[1])) {
			
				// Return UserID.
		    	return $matches[1];
				
		    } else {
			
				// Can't retrieve User ID.
				return "Sorry, an error occoured while trying to get your instagr.am ID! Please try another instagr.am url.";
			}
			
		} else {
		
			// Can't connect to give phot URL.
			return "Sorry, an error occoured while connecting to the photo url! Please try another instagram url.";
		}	
	}	
	
	private function igw_get_userPictures($userID){
	
		// Declaring temporary data arrays.
		$images         = array();
		$temp_user_data = array();
		$feed_url       = "http://instagram.heroku.com/users/" . $userID . ".json";
		
		// Checking for valid userID.
		if(intval($userID)){
		
			// Setting internal counter.
			$c = 0;
			
			// Initialize CURL process.
			$ch = curl_init($feed_url);
			
			// Checking for Open Basedir option in PHP settings.
			if(ini_get("open_basedir") == "")
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			
			// CURL Settings.
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, "Instagram 1.18.17 (iPhone; iPhone OS 4.3.2; lv_LV)");
			
			// Executing CURL Call.
			$data = curl_exec($ch);
			
			// Retrieving http code response.
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			
			// If the response is ok, proceed.
			if($httpcode >= 200 && $httpcode < 400){
			
				// Decode JSON Data.
				$decoded_data = json_decode($data);

				// Looping through images arrays.
				foreach($decoded_data->data as $entry){
					
					// Incremental counter.
					$c++;
					
					$images[] = array(
						"image_link"                => $entry->link,
						"image_tags"                => $entry->tags,
						"image_filter"              => $entry->filter,
						"image_timestamp"           => $entry->created_time,
						"image_low_resolution"      => $entry->images->low_resolution->url,
						"image_thumbnail"           => $entry->images->thumbnail->url,
						"image_standard_resolution" => $entry->images->standard_resolution->url,
						"image_title"               => $entry->caption->text
					);
					
					// Checking if visualization limit has been reached.
					if ($c == $this->widget_args["igw_maxnum_photos"]) break;
				}
				
				// Check for randomize option.
				if ('yes' == $this->widget_args["igw_randomize_option"]) {
					
					// Shuffle Array.
					shuffle($images);
				}
				
				// Assigning retrieved images to global collected data array.
				$this->instagramData["user_pictures"] = $images;
				
				// Unset temprary arrays.
				unset($images);
				unset($temp_user_data);
			}
		}
	}
	
	// Main method to display posts.
	public function displayGallery($widget_call = NULL, $return_mode) {

		$igw_content = "<div id=\"igw-widget-container\" class=\"igw-ver-" . IGW_PLUGIN_VERSION . "\">";
		
		// Check if this method has been called from a widget or from a direct PHP call.
		if (!$widget_call) {
		
			// Check for widget title hiding option.
			if ('yes' != $this->widget_args["igw_widget_title_hide_option"]) {
			
				// Display Widget Title.
				$igw_content .= "<h3 class=\"widget-title\">" . $this->widget_args["igw_widget_title"] . "</h3>";
			}
		}
		
		// Check for Instagr.am Photo URL.
		if (!empty($this->widget_args["igw_photo_url"])) {
			
			// Retrieving Instagr.am User ID.
			$this->instagramData["userID"] = $this->igw_get_userID($this->widget_args["igw_photo_url"]);
			
			// Checking if retrieved UserID is a valid value.
			if (intval($this->instagramData["userID"])) {

				// Retrieve User Pictures Feed.
				$this->igw_get_userPictures($this->instagramData["userID"]);
				
				// Building Instagr.am Gallery.
				$igw_content .= "<div id=\"igw_gallery_content\">";
				
				// Looping through images.
				foreach($this->instagramData["user_pictures"] as $image_item) {
					
					// Determining image resolution source.
					$image_source = ( ($this->widget_args["igw_thumbnail_width"] > 150) && ($this->widget_args["igw_thumbnail_height"] > 150)) ? $image_item["image_standard_resolution"] : $image_item["image_thumbnail"];
					
					// Checking if Effect filtering is on.
					if ( ($this->widget_args["igw_effect_filter"] == 'none') || ($image_item["image_filter"] == $this->widget_args["igw_effect_filter"]) ) {
					
						// Checking for FancyBox Option.
						if ($this->widget_args["igw_fancybox_option"] != 'yes') {
						
							// Open image with FancyBox Effect.
							$igw_content .= "<a class=\"igw_fancy igw_thumblink\" rel=\"igw-gallery\" href=\"" . $image_item["image_standard_resolution"] . "\" title=\"" . $image_item["image_title"] . "\">";
						
						} else {
						
							// Open image in a new Instagr.am Window.
							$igw_content .= "<a target=\"_blank\" rel=\"igw-gallery\" href=\"" . $image_item["image_link"] . "\" title=\"" . $image_item["image_title"] . "\">";
						}
						
						$igw_content .= "<img src=\"" . $image_source . "\" alt=\"" . $image_item["image_title"] . "\" width=\"" . $this->widget_args["igw_thumbnail_width"] . "\" height=\"" . $this->widget_args["igw_thumbnail_height"] . "\" />";
						$igw_content .= "</a>";
					}
				}
				
				$igw_content .= "</div>";
				$igw_content .= "<br style=\"height: 0px; clear:both;\" />";
				
			} else {
			
				// Connection problems.
				$igw_content .= $this->instagramData["userID"]; // This is a string now, containing the error returned.
			}
		} else {
			
			// No Photo URL has been submitted in the widget panel. Abort and report.
			$igw_content .= __("No photos available. Please configure your settings.", IGW_TRANSLATION_ID);
		}
		
		$igw_content .= "</div>";
		
		
		// Switch through display return mode
		switch($return_mode) {
		
			case"print":
				echo $igw_content;
			break;
			
			case "return":
				return $igw_content;
			break;
		}
	}
} // EOF Class.