<?php

define('LINK_LIBRARY_ADMIN_PAGE_NAME', 'link-library');

require_once(ABSPATH . '/wp-admin/includes/bookmark.php');
require_once(ABSPATH . '/wp-admin/includes/taxonomy.php');

$rss_settings = "";
$pagehooktop = "";
$pagehookmoderate = "";
$pagehooksettingssets = "";
$pagehookstylesheet = "";
$pagehookreciprocal = "";

class link_library_plugin_admin {
    
    function link_library_plugin_admin() {
        add_action('admin_init', array($this, 'action_admin_init' ));
        
        //add filter for WordPress 2.8 changed backend box system !
        add_filter('screen_layout_columns', array($this, 'on_screen_layout_columns'), 10, 2);
        //register callback for admin menu  setup
        add_action('admin_menu', array($this, 'on_admin_menu'));
        
        // Capture and process user submissions for custom fields in Link Edition page
		add_action('add_link', array($this, 'add_link_field'));
		add_action('edit_link', array($this, 'add_link_field'));
		add_action('delete_link', array($this, 'delete_link_field'));
        
        add_action( 'wp_dashboard_setup', array( $this, 'dashboard_widget') );
        
        add_filter("plugin_row_meta", array( $this, 'set_plugin_row_meta' ), 1, 2);
		
		add_action( 'wpmu_new_blog', array($this, 'new_network_site'), 10, 6);

        add_action( 'admin_head', array( $this, 'admin_header') );
    }

    function admin_header() {
        global $pagenow;
        if ( ( $pagenow == 'link.php' && $_GET['action'] == 'edit' ) || ( $pagenow == 'link-add.php' ) ) {
            if ( function_exists( 'wp_enqueue_media' ) ) {
                wp_enqueue_media();
            }
        }
		
		if ( isset( $_GET['page'] ) && ( $_GET['page'] == 'link-library' ) || $_GET['page'] == 'link-library-settingssets' || $_GET['page'] == 'link-library-moderate' || $_GET['page'] == 'link-library-stylesheet' || $_GET['page'] == 'link-library-reciprocate' ) {
			wp_enqueue_style( 'LibraryLibraryAdminStyle', plugins_url( 'link-library-admin.css', __FILE__ ) );
		}        
    }
    
    function set_plugin_row_meta($links_array, $plugin_file) {
        $genoptions = get_option('LinkLibraryGeneral');

        if ( substr( $plugin_file, 0, 25) == substr( plugin_basename( __FILE__ ), 0, 25 ) && !$genoptions['hidedonation'] ) {
        	$links_array = array_merge($links_array, array('<a target="_blank" href="http://ylefebvre.ca/wordpress-plugins/link-library">Donate</a>'));
        }
	    return $links_array;
    }
    
    function db_prefix() {
		global $wpdb;
		if (method_exists($wpdb, "get_blog_prefix"))
			return $wpdb->get_blog_prefix();
		else
			return $wpdb->prefix;
	}
	
	/************************** Function called to create default settings or to reset them on user request **************************/
	function ll_reset_options($settings = 1, $layout = 'list')
	{
		$uploads = wp_upload_dir();
		
		if ($layout == "list")
		{
			$options['num_columns'] = 1;
			$options['showdescription'] = false;
			$options['shownotes'] = false;
			$options['beforenote'] = '<br />';
			$options['afternote'] = '';
			$options['beforeitem'] = '<li>';
			$options['afteritem'] = '</li>';
			$options['beforedesc'] = '';
			$options['afterdesc'] = '';
			$options['displayastable'] = false;
			$options['beforelink'] = '';
			$options['afterlink'] = '';
			$options['showcolumnheaders'] = false;
			$options['linkheader'] = '';
			$options['descheader'] = '';
			$options['notesheader'] = '';
			$options['beforerss'] = '';
			$options['afterrss'] = '';
			$options['beforedate'] = '';
			$options['afterdate'] = '';
			$options['beforeimage'] = '';
			$options['afterimage'] = '';
			$options['beforeweblink'] = '';
			$options['afterweblink'] = '';
			$options['beforetelephone'] = '';
			$options['aftertelephone'] = '';
			$options['beforeemail'] = '';
			$options['afteremail'] = '';
			$options['beforelinkhits'] = '';
			$options['afterlinkhits'] = '';
		}
		elseif ($layout == "table")
		{
			$options['num_columns'] = 3;
			$options['showdescription'] = true;
			$options['shownotes'] = true;
			$options['beforenote'] = '<td>';
			$options['afternote'] = '</td>';
			$options['beforeitem'] = '<tr>';
			$options['afteritem'] = '</tr>';
			$options['beforedesc'] = '<td>';
			$options['afterdesc'] = '</td>';
			$options['displayastable'] = true;
			$options['beforelink'] = '<td>';
			$options['afterlink'] = '</td>';
			$options['showcolumnheaders'] = true;
			$options['linkheader'] = 'Application';
			$options['descheader'] = 'Description';
			$options['notesheader'] = 'Similar to';
			$options['beforerss'] = '<td>';
			$options['afterrss'] = '</td>';
			$options['beforedate'] = '<td>';
			$options['afterdate'] = '</td>';
			$options['beforeimage'] = '<td>';
			$options['afterimage'] = '</td>';
			$options['beforeweblink'] = '<td>';
			$options['afterweblink'] = '</td>';	
			$options['beforetelephone'] = '<td>';
			$options['aftertelephone'] = '</td>';
			$options['beforeemail'] = '<td>';
			$options['afteremail'] = '</td>';
			$options['beforelinkhits'] = '<td>';
			$options['afterlinkhits'] = '</td>';
		}

		$options['order'] = 'name';
		$options['hide_if_empty'] = true;
		$options['table_width'] = 100;
		$options['catanchor'] = true;
		$options['flatlist'] = 'table';
		$options['categorylist'] = null;
		$options['excludecategorylist'] = null;
		$options['showrating'] = false;
		$options['showupdated'] = false;
		$options['show_images'] = false;
		$options['use_html_tags'] = false;
		$options['show_rss'] = false;
		$options['nofollow'] = false;
		$options['catlistwrappers'] = 1;
		$options['beforecatlist1'] = '';
		$options['beforecatlist2'] = '';
		$options['beforecatlist3'] = '';
		$options['divorheader'] = false;
		$options['catnameoutput'] = 'linklistcatname';
		$options['show_rss_icon'] = false;
		$options['linkaddfrequency'] = 0;
		$options['addbeforelink'] = '';
		$options['addafterlink'] = '';
		$options['linktarget'] = '';
		$options['showcategorydescheaders'] = false;
		$options['showcategorydesclinks'] = false;
		$options['settingssetname'] = 'Default';
		$options['showadmineditlinks'] = true;
		$options['showonecatonly'] = false;
		$options['loadingicon'] = '/icons/Ajax-loader.gif';
		$options['defaultsinglecat'] = '';
		$options['rsspreview'] = false;
		$options['rsspreviewcount'] = 3;
		$options['rssfeedinline'] = false;
		$options['rssfeedinlinecontent'] = false;
		$options['rssfeedinlinecount'] = 1;
		$options['direction'] = 'ASC';
		$options['linkdirection'] = 'ASC';
		$options['linkorder'] = 'name';
		$options['pagination'] = false;
		$options['linksperpage'] = 5;
		$options['hidecategorynames'] = false;
		$options['showinvisible'] = false;
		$options['showdate'] = false;
		$options['catdescpos'] = 'right';
		$options['catlistdescpos'] = 'right';
		$options['showuserlinks'] = false;
		$options['addnewlinkmsg'] = __('Add new link', 'link-library');
		$options['linknamelabel'] = __('Link name', 'link-library');
		$options['linkaddrlabel'] = __('Link address', 'link-library');
		$options['linkrsslabel'] = __('Link RSS', 'link-library');
		$options['linkcatlabel'] = __('Link Category', 'link-library');
		$options['linkdesclabel'] = __('Link Description', 'link-library');
		$options['linknoteslabel'] = __('Link Notes', 'link-library');
		$options['addlinkbtnlabel'] = __('Add Link', 'link-library');
		$options['newlinkmsg'] = __('New link submitted.', 'link-library');
		$options['moderatemsg'] = __('It will appear in the list once moderated. Thank you.', 'link-library');
		$options['rsspreviewwidth'] = 900;
		$options['rsspreviewheight'] = 700;
		$options['imagepos'] = 'beforename';
		$options['imageclass'] = '';
		$options['emailnewlink'] = false;
		$options['showaddlinkrss'] = false;
		$options['showaddlinkdesc'] = false;
		$options['showaddlinkcat'] = false;
		$options['showaddlinknotes'] = false;
		$options['usethumbshotsforimages'] = false;
        $options['uselocalimagesoverthumbshots'] = false;
		$options['addlinkreqlogin'] = false;
		$options['showcatlinkcount'] = false;
		$options['publishrssfeed'] = false;
		$options['numberofrssitems'] = 10;
		$options['rssfeedtitle'] = __('Link Library-Generated RSS Feed', 'link-library');
		$options['rssfeeddescription'] = __('Description of Link Library-Generated Feed', 'link-library');
		$options['showonecatmode'] = 'AJAX';
        $options['paginationposition'] = 'AFTER';
		$options['addlinkcustomcat'] = false;
		$options['linkcustomcatlabel'] = __('User-submitted category', 'link-library');
		$options['linkcustomcatlistentry'] = __('User-submitted category (define below)', 'link-library');
		$options['searchlabel'] = 'Search';
		$options['dragndroporder'] = '1,2,3,4,5,6,7,8,9,10,11,12';
		$options['showname'] = true;
		$options['cattargetaddress'] = '';
		$options['displayweblink'] = 'false';
		$options['sourceweblink'] = 'primary';
		$options['showtelephone'] = 'false';
		$options['sourcetelephone'] = 'primary';
		$options['showemail'] = 'false';
		$options['showlinkhits'] = false;
		$options['weblinklabel'] = '';
		$options['telephonelabel'] = '';
		$options['emaillabel'] = '';
		$options['showaddlinkreciprocal'] = false;
		$options['linkreciprocallabel'] = __('Reciprocal Link', 'link-library');
		$options['showaddlinksecondurl'] = false;
		$options['linksecondurllabel'] = __('Secondary Address', 'link-library');
		$options['showaddlinktelephone'] = false;
		$options['linktelephonelabel'] = __('Telephone', 'link-library');
		$options['showaddlinkemail'] = false;
		$options['linkemaillabel'] = __('E-mail', 'link-library');
		$options['emailcommand'] = '';
		$options['sourceimage'] = 'primary';
		$options['sourcename'] = 'primary';
		$options['enablerewrite'] = false;
		$options['rewritepage'] = '';
		$options['storelinksubmitter'] = false;
		$options['maxlinks'] = '';
		$options['showcaptcha'] = false;
		$options['beforelinkrating'] = '';
		$options['afterlinkrating'] = '';
		$options['linksubmitternamelabel'] = __('Submitter Name', 'link-library');
		$options['showlinksubmittername'] = false;
		$options['linksubmitteremaillabel'] = __('Submitter E-mail', 'link-library');
		$options['showaddlinksubmitteremail'] = false;
		$options['linksubmittercommentlabel'] = __('Submitter Comment', 'link-library');
		$options['showlinksubmittercomment'] = false;
		$options['addlinkcatlistoverride'] = '';
		$options['showlargedescription'] = false;
		$options['beforelargedescription'] = '';
		$options['afterlargedescription'] = '';
		$options['showcustomcaptcha'] = false;
		$options['customcaptchaquestion'] = __('Is boiling water hot or cold?', 'link-library');
		$options['customcaptchaanswer'] = __('hot','link-library');
		$options['rssfeedaddress'] = '';
		$options['addlinknoaddress'] = false;
		$options['featuredfirst'] = false;
		$options['linklargedesclabel'] = __('Large Description', 'link-library');
		$options['showuserlargedescription'] = false;
		$options['usetextareaforusersubmitnotes'] = false;
		$options['showcatonsearchresults'] = false;
		$options['shownameifnoimage'] = false;
        $options['searchresultsaddress'] = '';
        $options['enable_link_popup'] = false;
        $options['link_popup_text'] = __( '%link_image%<br />Click through to visit %link_name%.', 'link-library');
        $options['popup_width'] = 300;
        $options['popup_height'] = 400;
        $options['nocatonstartup'] = false;

		$settingsname = 'LinkLibraryPP' . $settings;
		update_option($settingsname, $options);

        return $options;
	}

	// Function used to set initial settings or reset them on user request
	function ll_reset_gen_settings()
	{
		$genoptions['numberstylesets'] = 1;
		$genoptions['includescriptcss'] = '';
		$genoptions['debugmode'] = false;
		$genoptions['schemaversion'] = "4.9";
		$genoptions['pagetitleprefix'] = '';
		$genoptions['pagetitlesuffix'] = '';
		$genoptions['thumbshotscid'] = '';
		$genoptions['emaillinksubmitter'] = false;
        $genoptions['suppressemailfooter'] = false;
		$genoptions['moderatorname'] = '';
		$genoptions['moderatoremail'] = '';	
		$genoptions['approvalemailtitle'] = '';
		$genoptions['approvalemailbody'] = '';
		$genoptions['rejectedemailtitle'] = '';
		$genoptions['rejectedemailbody'] = '';
		$genoptions['moderationnotificationtitle'] = '';
		$genoptions['linksubmissionthankyouurl'] = '';
		$genoptions['usefirstpartsubmittername'] = '';
		$genoptions['recipcheckaddress'] = get_bloginfo('wpurl');
		$genoptions['recipcheckdelete403'] = false;
        $genoptions['imagefilepath'] = 'absolute';
        $genoptions['hidedonation'] = false;

		$stylesheetlocation = plugins_url( 'stylesheettemplate.css' , __FILE__ );
		$genoptions['fullstylesheet'] = @file_get_contents($stylesheetlocation);

		update_option('LinkLibraryGeneral', $genoptions);
        return $genoptions;
	}
    
    /* the function */
	function remove_querystring_var($url, $key) { 
	
		$keypos = strpos($url, $key);
		if ($keypos)
		{
			$ampersandpos = strpos($url, '&', $keypos);
			$newurl = substr($url, 0, $keypos - 1);
			
			if ($ampersandpos)
				$newurl .= substr($url, $ampersandpos);
		}
		else
			$newurl = $url;
		
		return $newurl; 
	}
    
    function ReciprocalLinkChecker($RecipCheckAddress = '', $recipcheckdelete403 = false) {
		global $wpdb;
		
		if ($RecipCheckAddress != '')
		{		
			$linkquery = "SELECT distinct *, l.link_id as proper_link_id, UNIX_TIMESTAMP(l.link_updated) as link_date, ";
			$linkquery .= "IF (DATE_ADD(l.link_updated, INTERVAL " . get_option('links_recently_updated_time') . " MINUTE) >= NOW(), 1,0) as recently_updated ";
			$linkquery .= "FROM " . $this->db_prefix() . "terms t ";
			$linkquery .= "LEFT JOIN " . $this->db_prefix() . "term_taxonomy tt ON (t.term_id = tt.term_id) ";
			$linkquery .= "LEFT JOIN " . $this->db_prefix() . "term_relationships tr ON (tt.term_taxonomy_id = tr.term_taxonomy_id) ";
			$linkquery .= "LEFT JOIN " . $this->db_prefix() . "links l ON (tr.object_id = l.link_id) ";
			$linkquery .= "LEFT JOIN " . $this->db_prefix() . "links_extrainfo le ON (l.link_id = le.link_id) ";	
			$linkquery .= "WHERE tt.taxonomy = 'link_category' ";
			$linkquery .= "AND le.link_reciprocal <> '' ";
			$linkquery .= "order by l.link_name ASC";
			
			$links = $wpdb->get_results($linkquery);
			$output = "<strong>Reciprocal Link Checker Report</strong><br /><br />";

			if ($links)
			{
				foreach($links as $link)
				{
					$sitecontent = file_get_contents($link->link_reciprocal);

					$output .= "<a href='" . $link->link_url . "'>" . $link->link_name . "</a>: ";
					
					if (strpos($http_response_header[0], "200")) {
						if (strpos($sitecontent, $RecipCheckAddress) === false)
						{
							$output .= "<span style='color: #FF0000'>Not Found</span><br />";
						}
						elseif (strpos($sitecontent, $RecipCheckAddress) !== false)
						{
							$output .= "<span style='color: #00FF00'>OK</span><br />";
						}
					}
					elseif (strpos($http_response_header[0], "403") && $recipcheckdelete403 == true)
					{
						wp_delete_link($link->link_id);
						$output .= "Error 403: Link Deleted";
					}
					else
						$output .= "Website Unreachable";
				}
			}
			else
			{
				$output = "There are no links with reciprocal links associated with them.<br />";
			}			
			
			return $output;
		}
	}

	function ll_get_link_image($url, $name, $mode, $linkid, $cid, $filepath, $filepathtype )
	{
		if ($url != "" && $name != "")
		{
			if ($mode == 'thumb' || $mode == 'thumbonly')
			{
				if ( !empty ($cid) )
					$genthumburl = "http://images.thumbshots.com/image.aspx?cid=" . rawurlencode($cid) . "&v1=w=120&url=" . esc_html($url);
			}
			elseif ($mode == 'favicon' || $mode == 'favicononly')
			{
				$genthumburl = "http://g.etfv.co/" . $url;
			}
			
			$uploads = wp_upload_dir();
			
			if (!file_exists($uploads['basedir']))
			{
				return __('Please create a folder called uploads under your Wordpress /wp-content/ directory with write permissions to use this functionality.', 'link-library');				
			}
			elseif (!is_writable($uploads['basedir']))
			{
				return __('Please make sure that the /wp-content/uploads/ directory has write permissions to use this functionality.', 'link-library');				
			}
			else
			{
				if (!file_exists($uploads['basedir'] . '/' . $filepath))
					mkdir($uploads['basedir'] . '/' . $filepath);
			}			
			
			$img = $uploads['basedir'] . "/" . $filepath. "/" . $linkid . ".jpg";
			$status = file_put_contents($img, @file_get_contents($genthumburl));

			if ( $status !== false ) {
                if ( $filepathtype == 'absolute' || empty( $filepathtype ) ) {
                       $newimagedata = array("link_id" => $linkid, "link_image" => $uploads['baseurl'] . "/" . $filepath . "/" . $linkid . ".jpg");               
                } elseif ( $filepathtype == 'relative' ) {
                    $parsedaddress = parse_url( $uploads['baseurl'] );
                    $newimagedata = array("link_id" => $linkid, "link_image" => $parsedaddress['path'] . "/" . $filepath . "/" . $linkid . ".jpg");
                }

				if ($mode == 'thumb' || $mode == 'favicon')
					wp_update_link($newimagedata);

				return $newimagedata['link_image'];
			}
			else
				return "";
		}
		return "Parameters are missing";
	}
    
    

	//for WordPress 2.8 we have to tell, that we support 2 columns !
	function on_screen_layout_columns($columns, $screen) {
		return $columns;
	}
	
	/**
	 * Returns the full URL of this plugin including trailing slash.
	 */
    	
	function action_admin_init() {
            // Add addition section to Link Edition page
            add_meta_box ('linklibrary_meta_box', __('Link Library - Additional Link Parameters', 'link-library'), array($this, 'll_link_edit_extra'), 'link', 'normal', 'high');
            
            //register the callback been used if options of page been submitted and needs to be processed
            add_action('admin_post_save_link_library_general', array($this, 'on_save_changes_general'));
            add_action('admin_post_save_link_library_settingssets', array($this, 'on_save_changes_settingssets'));
            add_action('admin_post_save_link_library_moderate', array($this, 'on_save_changes_moderate'));
            add_action('admin_post_save_link_library_stylesheet', array($this, 'on_save_changes_stylesheet'));
            add_action('admin_post_save_link_library_reciprocal', array($this, 'on_save_changes_reciprocal'));
            
            // Under development, trying to display extra columns in link list page
            add_filter('manage_link-manager_columns', array($this, 'll_linkmanager_addcolumn'));
            add_action('manage_link_custom_column', array($this, 'll_linkmanager_populatecolumn'), 10, 2);
            add_filter('get_bookmarks', array( $this, 'll_bookmarks_filter' ) );

            //add_filter( 'attachment_fields_to_edit', array( $this, 'add_custom_media_fields' ), null, 2 );
            //add_filter( 'attachment_fields_to_save', array( $this, 'save_custom_media_fields' ), null, 2 );

            $genoptions = get_option('LinkLibraryGeneral');
            
            if ( !empty( $genoptions ) ) {            
                if ( empty( $genoptions['numberstylesets'] ) ) {
                    $numberofsets = 1; 
                } else { 
                    $numberofsets = $genoptions['numberstylesets'];
                }
            
                $thumbshotsactive = false;
            
				for ($counter = 1; $counter <= $numberofsets; $counter++) {
                    $tempoptionname = "LinkLibraryPP" . $counter;
					$tempoptions = get_option($tempoptionname);
                    if ( $tempoptions['usethumbshotsforimages'] ) {
                        $thumbshotsactive = true;
                    }                    
                }
            
                if ( $thumbshotsactive && empty($genoptions['thumbshotscid']) ) {
                    add_action('admin_notices', array( $this, 'll_thumbshots_warning') ); 
                }
            }            
	}

    function add_custom_media_fields( $form_fields, $post ) {

        $form_fields[ 'link_library_add_link' ] = array(
            'label' => 'Create Link for New Media',
            'input' => 'html',
            'html' => "<input type='checkbox' name='createlink' id='createlink' />" );

        return $form_fields;
    }

    function ll_bookmarks_filter( $bookmarks ) {
        if ( isset( $_GET['linksperpage'] ) && isset( $_GET['linkspage'] ) ) {
            return array_slice( $bookmarks, ( $_GET['linkspage'] - 1 ) *  $_GET['linksperpage'], $_GET['linksperpage'] );
        } else {
            return $bookmarks;
        }
    }

    function save_custom_media_fields( $post, $attachment ) {

        if ( $_POST['createlink'] == true) {
            print_r($post);
            print_r($attachment);
            die();
        }

        return $post;
    }


    function ll_thumbshots_warning() {
        echo "
        <div id='ll-warning' class='updated fade'><p><strong>". __('Link Library: Missing Thumbshots API Key', 'link-library') ."</strong></p> <p>". __('One of your link libraries is configured to use Thumbshots for link thumbails, but you have not entered your Thumbshots.com API Key. Please visit Thumbshots.com to apply for a free or paid account and enter your API in the Link Library admin panel.', 'link-library'). " <a href='" . add_query_arg( array( 'page' => 'link-library'), admin_url('admin.php') ) . "'>". __('Jump to Link Library admin', 'link-library') . "</a></p></div>";
    }
	
	function filter_mce_buttons( $buttons ) {
		
		array_push( $buttons, '|', 'scn_button');
		return $buttons;
	}
	
	function filter_mce_external_plugins( $plugins ) {
		
        $plugins['LinkLibraryPlugin'] = plugins_url( 'tinymce/editor_plugin.js', __FILE__ );
        return $plugins;
	}
    
    	function ajax_action_check_url() {

		$hadError = true;

		$url = isset( $_REQUEST['url'] ) ? $_REQUEST['url'] : '';

		if ( strlen( $url ) > 0  && function_exists( 'get_headers' ) ) {
				
			$file_headers = @get_headers( $url );
			$exists       = $file_headers && $file_headers[0] != 'HTTP/1.1 404 Not Found';
			$hadError     = false;
		}

		echo '{ "exists": '. ($exists ? '1' : '0') . ($hadError ? ', "error" : 1 ' : '') . ' }';

		die();
	}
    
    function dashboard_widget() {
        wp_add_dashboard_widget( 'link_library_dashboard_widget',
                                 'Link Library',
                                 array( $this, 'render_dashboard_widget' ) );
    }
    
    function render_dashboard_widget() {
        global $wpdb;
		
		$linkmoderatecount = 0;
		
		$linkmoderatequery = "SELECT count(*) ";
		$linkmoderatequery .= "FROM " . $this->db_prefix() . "links l ";
		$linkmoderatequery .= "WHERE l.link_description like '%LinkLibrary:AwaitingModeration:RemoveTextToApprove%' ";
		$linkmoderatequery .= " ORDER by link_name ASC";

		$linkmoderatecount = $wpdb->get_var($linkmoderatequery);
        
        echo '<strong>' . $linkmoderatecount . '</strong> ';
        _e('Links to moderate', 'link-library');
    }


	//extend the admin menu
	function on_admin_menu() {
		//add our own option page, you can also add it to different sections or use your own one
		global $wpdb, $pagehooktop, $pagehookmoderate, $pagehooksettingssets, $pagehookstylesheet, $pagehookreciprocal;
		
		$linkmoderatecount = 0;
		
		$linkmoderatequery = "SELECT count(*) ";
		$linkmoderatequery .= "FROM " . $this->db_prefix() . "links l ";
		$linkmoderatequery .= "WHERE l.link_description like '%LinkLibrary:AwaitingModeration:RemoveTextToApprove%' ";
		$linkmoderatequery .= " ORDER by link_name ASC";

		$linkmoderatecount = $wpdb->get_var($linkmoderatequery);
		
        if ($linkmoderatecount == 0) {
            $pagehooktop = add_menu_page('Link Library - ' . __('General Options', 'link-library'), 'Link Library', 'manage_options', LINK_LIBRARY_ADMIN_PAGE_NAME, array($this, 'on_show_page'), plugins_url('icons/folder-beige-internet-icon.png', __FILE__ ) );
        } else {
            $pagehooktop = add_menu_page('Link Library - ' . __('General Options', 'link-library'), 'Link Library ' . '<span class="update-plugins count-' . $linkmoderatecount . '"><span class="plugin-count">' . number_format_i18n($linkmoderatecount) . '</span></span>', 'manage_options', LINK_LIBRARY_ADMIN_PAGE_NAME, array($this, 'on_show_page'), plugins_url('icons/folder-beige-internet-icon.png', __FILE__ ) );
        }
        
        $pagehookgeneraloptions = add_submenu_page( LINK_LIBRARY_ADMIN_PAGE_NAME, 'Link Library - ' . __('General Options', 'link-library'), __('General Options', 'link-library'), 'manage_options', LINK_LIBRARY_ADMIN_PAGE_NAME, array($this,'on_show_page') );
		
		$pagehooksettingssets = add_submenu_page( LINK_LIBRARY_ADMIN_PAGE_NAME, 'Link Library - ' . __('Settings', 'link-library') , __('Library Settings', 'link-library'), 'manage_options', 'link-library-settingssets', array($this,'on_show_page'));
		
		if ($linkmoderatecount == 0)
			$pagehookmoderate = add_submenu_page( LINK_LIBRARY_ADMIN_PAGE_NAME, 'Link Library - ' . __('Moderate', 'link-library') , __('Moderate', 'link-library'), 'manage_options', 'link-library-moderate', array($this,'on_show_page'));
		else
			$pagehookmoderate = add_submenu_page( LINK_LIBRARY_ADMIN_PAGE_NAME, 'Link Library - ' . __('Moderate', 'link-library') , sprintf( __('Moderate', 'link-library') . ' %s', "<span class='update-plugins count-" . $linkmoderatecount . "'><span class='plugin-count'>" . number_format_i18n($linkmoderatecount) . "</span></span>"), 'manage_options', 'link-library-moderate', array($this,'on_show_page'));	
		
		$pagehookstylesheet = add_submenu_page( LINK_LIBRARY_ADMIN_PAGE_NAME, 'Link Library - ' . __('Stylesheet', 'link-library') , __('Stylesheet', 'link-library'), 'manage_options', 'link-library-stylesheet', array($this,'on_show_page')); 
		
		$pagehookreciprocal = add_submenu_page( LINK_LIBRARY_ADMIN_PAGE_NAME, 'Link Library - ' . __('Reciprocal Checker', 'link-library') , __('Reciprocal Check', 'link-library'), 'manage_options', 'link-library-reciprocal', array($this,'on_show_page')); 
		
		//register  callback gets call prior your own page gets rendered
		add_action('load-'.$pagehooktop, array($this, 'on_load_page'));
		add_action('load-'.$pagehooksettingssets, array($this, 'on_load_page'));
		add_action('load-'.$pagehookmoderate, array($this, 'on_load_page'));
		add_action('load-'.$pagehookstylesheet, array($this, 'on_load_page'));
		add_action('load-'.$pagehookreciprocal, array($this, 'on_load_page'));
	}

	//will be executed if wordpress core detects this page has to be rendered
	function on_load_page() {
	
		global $pagehooktop, $pagehookmoderate, $pagehooksettingssets, $pagehookstylesheet, $pagehookreciprocal;
		
		//ensure, that the needed javascripts been loaded to allow drag/drop, expand/collapse and hide/show of boxes
		wp_enqueue_script('tiptip', plugins_url( '/tiptip/jquery.tipTip.minified.js' , __FILE__ ), "jQuery", "1.0rc3");
		wp_enqueue_style('tiptipstyle', plugins_url( '/tiptip/tipTip.css' , __FILE__ ));
		add_thickbox();
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('common');
		wp_enqueue_script('wp-lists');
		wp_enqueue_script('postbox');

        $genoptions = get_option('LinkLibraryGeneral');

		//add several metaboxes now, all metaboxes registered during load page can be switched off/on at "Screen Options" automatically, nothing special to do therefore
		add_meta_box('linklibrary_general_meta_box', __('General Settings', 'link-library'), array($this, 'general_meta_box'), $pagehooktop, 'normal', 'high');
		add_meta_box('linklibrary_general_bookmarklet_meta_box', __('Bookmarklet', 'link-library'), array($this, 'general_meta_bookmarklet_box'), $pagehooktop, 'normal', 'high');
		add_meta_box('linklibrary_general_moderation_meta_box', __('General Moderation Options', 'link-library'), array($this, 'general_moderation_meta_box'), $pagehooktop, 'normal', 'high');

        if ( !$genoptions['hidedonation'] ) {
            add_meta_box('linklibrary_hide_donation_meta_box', __('Hide Donation and Support Links', 'link-library'), array($this, 'general_hide_donation_meta_box'), $pagehooktop, 'normal', 'high');
        }

		add_meta_box('linklibrary_general_save_meta_box', __('Save', 'link-library'), array($this, 'general_save_meta_box'), $pagehooktop, 'normal', 'high');
		add_meta_box('linklibrary_moderation_meta_box', __('Links awaiting moderation', 'link-library'), array($this, 'moderate_meta_box'), $pagehookmoderate, 'normal', 'high');
		add_meta_box('linklibrary_stylesheet_meta_box', __('Editor', 'link-library'), array($this, 'stylesheet_meta_box'), $pagehookstylesheet, 'normal', 'high');
		add_meta_box('linklibrary_settingssets_usage_meta_box', __('Library Selection and Usage Instructions', 'link-library'), array($this, 'settingssets_usage_meta_box'), $pagehooksettingssets, 'normal', 'high');
		add_meta_box('linklibrary_settingssets_side_meta_box', __('Save', 'link-library'), array($this, 'settingssets_save_meta_box'), $pagehooksettingssets, 'normal', 'high');				
		add_meta_box('linklibrary_settingssets_common_meta_box', __('Common Parameters', 'link-library'), array($this, 'settingssets_common_meta_box'), $pagehooksettingssets, 'normal', 'high');
 		add_meta_box('linklibrary_settingssets_categories_meta_box', __('Link Categories Settings', 'link-library'), array($this, 'settingssets_categories_meta_box'), $pagehooksettingssets, 'normal', 'high');
		add_meta_box('linklibrary_settingssets_linkelement_meta_box', __('Link Element Settings', 'link-library'), array($this, 'settingssets_linkelement_meta_box'), $pagehooksettingssets, 'normal', 'high');
		add_meta_box('linklibrary_settingssets_subfieldtable_meta_box', __('Link Sub-Field Configuration Table', 'link-library'), array($this, 'settingssets_subfieldtable_meta_box'), $pagehooksettingssets, 'normal', 'high');
        add_meta_box('linklibrary_settingssets_linkpopup_meta_box', __('Link Pop-Up', 'link-library'), array($this, 'settingssets_linkpopup_meta_box'), $pagehooksettingssets, 'normal', 'high');
		add_meta_box('linklibrary_settingssets_rssconfig_meta_box', __('RSS Field Configuration', 'link-library'), array($this, 'settingssets_rssconfig_meta_box'), $pagehooksettingssets, 'normal', 'high');
		add_meta_box('linklibrary_settingssets_thumbnails_meta_box', __('Thumbnail Generation and Use', 'link-library'), array($this, 'settingssets_thumbnails_meta_box'), $pagehooksettingssets, 'normal', 'high');
		add_meta_box('linklibrary_settingssets_rssgen_meta_box', __('RSS Generation', 'link-library'), array($this, 'settingssets_rssgen_meta_box'), $pagehooksettingssets, 'normal', 'high');
		add_meta_box('linklibrary_settingssets_search_meta_box', __('Search Form Configuration', 'link-library'), array($this, 'settingssets_search_meta_box'), $pagehooksettingssets, 'normal', 'high');
		add_meta_box('linklibrary_settingssets_linksubmission_meta_box', __('Link User Submission', 'link-library'), array($this, 'settingssets_linksubmission_meta_box'), $pagehooksettingssets, 'normal', 'high');		
		add_meta_box('linklibrary_settingssets_importexport_meta_box', __('Import / Export', 'link-library'), array($this, 'settingssets_importexport_meta_box'), $pagehooksettingssets, 'normal', 'high');		
		add_meta_box('linklibrary_settingssets_side_meta_box_2', __('Save', 'link-library'), array($this, 'settingssets_save_meta_box'), $pagehooksettingssets, 'normal', 'high');
		add_meta_box('linklibrary_reciprocal_meta_box', __('Reciprocal Link Checker', 'link-library'), array($this, 'reciprocal_meta_box'), $pagehookreciprocal, 'normal', 'high');
		add_meta_box('linklibrary_reciprocal_save_meta_box', __('Save', 'link-library'), array($this, 'general_save_meta_box'), $pagehookreciprocal, 'normal', 'high');		
	}

	//executed to show the plugins complete admin page
	function on_show_page() {
		//we need the global screen column value to beable to have a sidebar in WordPress 2.8
		global $screen_layout_columns;
                
        $settings = ( isset( $_GET['settings'] ) ? $_GET['settings'] : 1 );

		// Retrieve general options
		$genoptions = get_option('LinkLibraryGeneral');

		// If general options don't exist, create them
		if ($genoptions == FALSE)
		{
			$genoptions = $this->ll_reset_gen_settings();
		}
		elseif ($genoptions['schemaversion'] == '' || floatval($genoptions['schemaversion']) < "4.6") // If they exist, make sure they are up to date
		{
			$this->ll_install();
			$genoptions = get_option('LinkLibraryGeneral');
		}
        
        $settingsname = 'LinkLibraryPP' . $settings;
        $options = get_option($settingsname);

        if ( empty($options) )
        {
            $options = $this->ll_reset_options($settings, 'list');
        }

		if ( isset($_GET['genthumbs']) || isset($_GET['genfavicons']) || isset($_GET['genthumbsingle']) || isset($_GET['genfaviconsingle'])) {
			global $wpdb;

			if (isset($_GET['genthumbs']) || isset($_GET['genthumbsingle']))
				$filepath = "link-library-images";
			elseif (isset($_GET['genfavicons']) || isset($_GET['genfaviconsingle']))
				$filepath = "link-library-favicons";

			$uploads = wp_upload_dir();
			
			if (!file_exists($uploads['basedir']))
			{
				echo "<div id='message' class='updated fade'><p><strong>" . __('Please create a folder called uploads under your Wordpress /wp-content/ directory with write permissions to use this functionality.', 'link-library') . "</strong></p></div>";				
			}
			elseif (!is_writable($uploads['basedir']))
			{
				echo "<div id='message' class='updated fade'><p><strong>" . __('Please make sure that the /wp-content/uploads/ directory has write permissions to use this functionality.', 'link-library') . "</strong></p></div>";				
			}
			else
			{
				if (!file_exists($uploads['basedir'] . '/' . $filepath))
					mkdir($uploads['basedir'] . '/' . $filepath);
					
				if (isset($_GET['genthumbs']) || isset($_GET['genthumbsingle']))
					$genmode = 'thumb';
				elseif (isset($_GET['genfavicons']) || isset($_GET['genfaviconsingle']))
					$genmode = 'favicon';

				$linkquery = "SELECT distinct * ";
				$linkquery .= "FROM " . $this->db_prefix() . "terms t ";
				$linkquery .= "LEFT JOIN " . $this->db_prefix() . "term_taxonomy tt ON (t.term_id = tt.term_id) ";
				$linkquery .= "LEFT JOIN " . $this->db_prefix() . "term_relationships tr ON (tt.term_taxonomy_id = tr.term_taxonomy_id) ";
				$linkquery .= "LEFT JOIN " . $this->db_prefix() . "links l ON (tr.object_id = l.link_id) ";
				$linkquery .= "WHERE tt.taxonomy = 'link_category' ";

				if ($options['categorylist'] != "" && !isset($_GET['genthumbsingle']) && !isset($_GET['genfaviconsingle']))
					$linkquery .= " AND t.term_id in (" . $options['categorylist'] . ")";

				if (isset($_GET['genthumbsingle']) || isset($_GET['genfaviconsingle']))
					$linkquery .= " AND l.link_id = " . $_GET['linkid'];

				$linkitems = $wpdb->get_results($linkquery);

				if ($linkitems)
				{
					$filescreated = 0;
					$totallinks = count($linkitems);
					foreach($linkitems as $linkitem)
					{
						$this->ll_get_link_image($linkitem->link_url, $linkitem->link_name, $genmode, $linkitem->link_id, $genoptions['thumbshotscid'], $filepath, $genoptions['imagefilepath']);
						$linkname = $linkitem->link_name;
					}

					if (isset($_GET['genthumbs']))
						echo "<div id='message' class='updated fade'><p><strong>" . __('Thumbnails successfully generated!', 'link-library') . "</strong></p></div>";
					elseif (isset($_GET['genfavicons']))
						echo "<div id='message' class='updated fade'><p><strong>" . __('Favicons successfully generated!', 'link-library') . "</strong></p></div>";
					elseif (isset($_GET['genthumbsingle']))
						echo "<div id='message' class='updated fade'><p><strong>" . __('Thumbnail successfully generated for', 'link-library') . " " . $linkname . ".</strong></p></div>";
					elseif (isset($_GET['genfaviconsingle']))
						echo "<div id='message' class='updated fade'><p><strong>" . __('Favicon successfully generated for', 'link-library') . " " . $linkname . ".</strong></p></div>";
				}
			}
		}

		// Check for current page to set some page=specific variables
		if ($_GET['page'] == 'link-library')
		{
			if (isset( $_GET['message'] ) && $_GET['message'] == '1') {
				echo "<div id='message' class='updated fade'><p><strong>" . __('General Settings Saved', 'link-library') . ".</strong></p></div>";
            } else if (isset( $_GET['message'] ) && $_GET['message'] == '2') {
                $linksexportdir = wp_upload_dir();
                echo "<div id='message' class='updated fade'><p><strong><a href='" . $linksexportdir['url'] . '/LinksExport.csv' . "'>" . __('Download exported links', 'link-library') . "</a></strong></p></div>";
            } else if (isset( $_GET['message'] ) && $_GET['message'] == '3') {
                echo "<div id='message' class='updated fade'><p><strong>" . __('Link Library plugin directory needs to be writable to perform this action', 'link-library') . "</strong></p></div>";
            }

			$formvalue = 'save_link_library_general';
			$pagetitle = '';
		}
		elseif ($_GET['page'] == 'link-library-settingssets')
		{
			$formvalue = 'save_link_library_settingssets';

			if ( isset($_GET['reset']))
				$options = $this->ll_reset_options($settings, 'list');

			if ( isset($_GET['resettable']) )
				$options = $this->ll_reset_options($settings, 'table');

			if ( isset($_GET['copy']))
			{
				$destination = $_GET['copy'];
				$source = $_GET['source'];

				$sourcesettingsname = 'LinkLibraryPP' . $source;
				$sourceoptions = get_option($sourcesettingsname);

				$destinationsettingsname = 'LinkLibraryPP' . $destination;
				update_option($destinationsettingsname, $sourceoptions);

				$settings = $destination;
			}

			if ( isset($_GET['deletesettings']) ) {
				$settings = $_GET['deletesettings'];
				$deletesettingsname = 'LinkLibraryPP' . $settings;
				$options = delete_option($deletesettingsname);
				$settings = 1;
			}
			
			$pagetitle = __('Library', 'link-library') . ' #' . $settings . " - " . $options['settingssetname'];

			if (isset($_GET['messages']))
			{
				$messagelist = explode(",", $_GET['messages']);

				foreach ($messagelist as $message)
				{
					switch($message) {

						case '1':
							echo "<div id='message' class='updated fade'><p><strong>" . __('Library #', 'link-library') . $settings . " " . __('Updated', 'link-library') . "!</strong></p></div>";				
							break;

						case '2':
							echo '<br /><br />' . __('Included Category ID', 'link-library') . ' ' . $categoryid . ' ' . __('is invalid. Please check the ID in the Link Category editor.', 'link-library');						
							break;

						case '3':
							echo '<br /><br />' . __('Excluded Category ID', 'link-library') . ' ' . $categoryid . ' ' . __('is invalid. Please check the ID in the Link Category editor.', 'link-library');						
							break;

						case '4':
							echo "<div id='message' class='updated fade'><p><strong>" . __('Invalid column count for link on row', 'link-library') . "</strong></p></div>";
							break;

						case '5':
                            $upload_dir = wp_upload_dir();
							echo "<div id='message' class='updated fade'><p><strong>" . __('Library Settings Exported', 'link-library') . ". <a href='" . $upload_dir['url'] . '/SettingSet' . $settings . 'Export.csv' . "'>" . __('Download here', 'link-library') . "</a>.</strong></p></div>";
							break;

						case '6':
							echo "<div id='message' class='updated fade'><p><strong>" . __('Link Library plugin directory needs to be writable to perform this action', 'link-library') . ".</strong></p></div>";
							break;

						case '7':
							echo "<div id='message' class='updated fade'><p><strong>" . __('Library Settings imported successfully', 'link-library') . ".</strong></p></div>";
							break;

						case '8':
							echo "<div id='message' class='updated fade'><p><strong>" . __('Library Settings Upload Failed', 'link-library') . "</strong></p></div>";
							break;

						case '9':
							echo "<div id='message' class='updated fade'><p><strong>" . $_GET['importrowscount'] . " " . __('row(s) found', 'link-library') . ". " . ( isset( $_GET['successimportcount'] ) ? $_GET['successimportcount'] : '0' ) . " " . __('link(s) imported successfully', 'link-library') . ".</strong></p></div>";

					}

				}

			}
		}
		elseif ($_GET['page'] == 'link-library-moderate')
		{
			$formvalue = 'save_link_library_moderate';
			$pagetitle = '';

			if (isset( $_GET['message'] ) && $_GET['message'] == '1')
				echo "<div id='message' class='updated fade'><p><strong>" . __('Link(s) Approved', 'link-library') . "</strong></p></div>";
			elseif (isset( $_GET['message'] ) && $_GET['message'] == '2')
				echo "<div id='message' class='updated fade'><p><strong>" . __('Link(s) Deleted', 'link-library') . "</strong></p></div>";

			?>

			<?php
		}
		elseif ($_GET['page'] == 'link-library-stylesheet')
		{
			$formvalue = 'save_link_library_stylesheet';
			$pagetitle = '';

			if (isset( $_GET['message'] ) && $_GET['message'] == '1')
				echo "<div id='message' class='updated fade'><p><strong>" . __('Stylesheet updated', 'link-library') . ".</strong></p></div>";
			elseif (isset( $_GET['message'] ) && $_GET['message'] == '2')
				echo "<div id='message' class='updated fade'><p><strong>" . __('Stylesheet reset to original state', 'link-library') . ".</strong></p></div>";			
		}
		elseif ($_GET['page'] == 'link-library-reciprocal')
		{
			$formvalue = 'save_link_library_reciprocal';
			$pagetitle = '';
			
			if (isset( $_GET['message'] ) && $_GET['message'] == '1')
				echo "<div id='message' class='updated fade'><p><strong>" . __('Settings updated', 'link-library') . ".</strong></p></div>";
			elseif (isset( $_GET['message'] ) && $_GET['message'] == '2')
			{
				echo "<div id='message' class='updated fade'><p>";
				echo $this->ReciprocalLinkChecker($genoptions['recipcheckaddress'], $genoptions['recipcheckdelete403']);
				echo "</p></div>";
			}
		}		

		$data = array();
		$data['settings'] = $settings;
		$data['options'] = isset( $options ) ? $options : '';
		$data['genoptions'] = $genoptions;
		global $pagehooktop, $pagehookmoderate, $pagehookstylesheet, $pagehooksettingssets, $pagehookreciprocal;
		?>
        <div class="ll-content">
            <div class="ll-frame">
                <div class="header">
                    <nav role="navigation" class="header-nav drawer-nav nav-horizontal">

                        <ul class="main-nav">
                            <li class="link-library-logo"><img src="<?php echo plugins_url('icons/folder-beige-internet-icon32.png', __FILE__ ); ?>" /><span>Link Library</span></li>
                            <li class="link-library-page">
                                <a href="<?php echo add_query_arg( array( 'page' => 'link-library'), admin_url('admin.php') ); ?>" <?php if ( isset( $_GET['page'] ) && $_GET['page'] == 'link-library' ) echo 'class="current"'; ?>><?php _e( 'General Options', 'link-library'); ?></a>
                            </li>
                            <li class="link-library-page">
                                <a href="<?php echo add_query_arg( array( 'page' => 'link-library-settingssets'), admin_url('admin.php') ); ?>" <?php if ( isset( $_GET['page'] ) && $_GET['page'] == 'link-library-settingssets' ) echo 'class="current"'; ?>><?php _e( 'Library Settings', 'link-library' ); ?></a>
                            </li>
                            <li class="link-library-page">
                                <a href="<?php echo add_query_arg( array( 'page' => 'link-library-moderate'), admin_url('admin.php') ); ?>" <?php if ( isset( $_GET['page'] ) && $_GET['page'] == 'link-library-moderate' ) echo 'class="current"'; ?>><?php _e( 'Moderate', 'link-library' ); ?></a>
                            </li>
                            <li class="link-library-page">
                                <a href="<?php echo add_query_arg( array( 'page' => 'link-library-stylesheet'), admin_url('admin.php') ); ?>" <?php if ( isset( $_GET['page'] ) && $_GET['page'] == 'link-library-stylesheet' ) echo 'class="current"'; ?>><?php _e( 'Stylesheet', 'link-library' ); ?></a>
                            </li>
                            <li class="link-library-page">
                                <a href="<?php echo add_query_arg( array( 'page' => 'link-library-reciprocal'), admin_url('admin.php') ); ?>" <?php if ( isset( $_GET['page'] ) && $_GET['page'] == 'link-library-reciprocal' ) echo 'class="current"'; ?>><?php _e( 'Reciprocal Check', 'link-library' ); ?></a>
                            </li>
							<?php if ( !$genoptions['hidedonation'] ) { ?>
							<li class="link-library-page">
                                <a href="http://ylefebvre.ca/wordpress-plugins/link-library/"><img src="<?php echo plugins_url( '/icons/btn_donate_LG.gif', __FILE__ ); ?>" /></a>
                            </li>							
							<?php } ?>
							
                        </ul>

                    </nav>
                </div><!-- .header -->
            </div>
        </div>
		<div id="link-library-general" class="wrap">
		<div class='icon32'><img src="<?php echo plugins_url( 'icons/folder-beige-internet-icon32.png', __FILE__ ); ?>" /></div>
		<div ><h2><?php if ( !empty( $pagetitle ) ) echo $pagetitle; ?>

                

</h2></div>
		<div><form name='linklibrary' enctype="multipart/form-data" action="admin-post.php" method="post">
			<input type="hidden" name="MAX_FILE_SIZE" value="100000" />

			<?php wp_nonce_field('link-library'); ?>
			<?php wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false ); ?>
			<?php wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false ); ?>
			<input type="hidden" name="action" value="<?php echo $formvalue; ?>" />

			<style type="text/css">
				#sortable { list-style-type: none; margin: 0; padding: 0; white-space:nowrap; list-style-type:none;}
				#sortable li { list-style: none; margin: 0 6px 4px 6px; padding: 10px 15px 10px 15px; border: #CCCCCC solid 1px; color:#fff; display: inline; width:100px;height: 30px;cursor:move}
				#sortable li span { position: absolute; margin-left: -1.3em; }
			</style>

			<div id="poststuff" class="metabox-holder">
				<div id="post-body" class="has-sidebar">
					<div id="post-body-content" class="has-sidebar-content">
						<?php 
							if ($_GET['page'] == 'link-library')
								do_meta_boxes($pagehooktop, 'normal', $data); 
							elseif ($_GET['page'] == 'link-library-settingssets')
								do_meta_boxes($pagehooksettingssets, 'normal', $data); 
							elseif ($_GET['page'] == 'link-library-moderate')
								do_meta_boxes($pagehookmoderate, 'normal', $data); 
							elseif ($_GET['page'] == 'link-library-stylesheet')
								do_meta_boxes($pagehookstylesheet, 'normal', $data);
							elseif ($_GET['page'] == 'link-library-reciprocal')
								do_meta_boxes($pagehookreciprocal, 'normal', $data);
						?>
					</div>
				</div>
				<br class="clear"/>
			</div>
		</form>
		</div></div>
	<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready( function($) {
			// close postboxes that should be closed
			$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
			// postboxes setup
			postboxes.add_postbox_toggles('<?php 
				if ($_GET['page'] == 'link-library')
					echo $pagehooktop;
				elseif ($_GET['page'] == 'link-library-settingssets')
					echo $pagehooksettingssets;
				elseif ($_GET['page'] == 'link-library-moderate')
					echo $pagehookmoderate;
				elseif ($_GET['page'] == 'link-library-stylesheet')
					echo $pagehookstylesheet;
				elseif ($_GET['page'] == 'link-library-reciprocal')
					echo $pagehookreciprocal;
				?>');
		});
		//]]>

		// Create the tooltips only on document load
		jQuery(document).ready(function()
			{
				jQuery('.lltooltip').each(function()
						{
						jQuery(this).tipTip();
						}
				);

				jQuery("#sortable").sortable({ opacity: 0.6, cursor: 'move', update: function() {
						var order = jQuery("#sortable").sortable('toArray');
						stringorder = order.join(',') 
						document.getElementById('dragndroporder').value = stringorder;
					}
				});

		});
		</script>

		<?php
	}

		//executed if the post arrives initiated by pressing the submit button of form
	function on_save_changes_general() {
		//user permission check
		if ( !current_user_can('manage_options') )
			wp_die( __('Not allowed', 'link-library') );			
		//cross check the given referer
		check_admin_referer('link-library');

        $genoptions = get_option('LinkLibraryGeneral');

		foreach (array('numberstylesets', 'includescriptcss', 'pagetitleprefix', 'pagetitlesuffix', 'schemaversion', 'thumbshotscid', 'approvalemailtitle',
						'moderatorname', 'moderatoremail', 'rejectedemailtitle', 'approvalemailbody', 'rejectedemailbody', 'moderationnotificationtitle',
						'linksubmissionthankyouurl', 'recipcheckaddress', 'imagefilepath') as $option_name) {
			if (isset($_POST[$option_name])) {
				$genoptions[$option_name] = $_POST[$option_name];
			}
		}

		foreach (array('debugmode', 'emaillinksubmitter', 'suppressemailfooter', 'usefirstpartsubmittername', 'hidedonation' ) as $option_name) {
			if (isset($_POST[$option_name])) {
				$genoptions[$option_name] = true;
			} else {
				$genoptions[$option_name] = false;
			}
		}

		update_option('LinkLibraryGeneral', $genoptions);

        update_option( 'links_updated_date_format', $_POST['links_updated_date_format'] );

        $message = "1";

        if (isset($_POST['exportalllinks']))
        {
            $upload_dir = wp_upload_dir();

            if (is_writable( $upload_dir['path'] ) )
            {
                $myFile = $upload_dir['path'] . "/LinksExport.csv";
                $fh = fopen($myFile, 'w') or die("can't open file");

                global $wpdb;

                $linkquery = "SELECT distinct l.link_name, l.link_url, l.link_rss, l.link_description, l.link_notes, ";
                $linkquery .= "t.name, l.link_visible, le.link_second_url, le.link_telephone, le.link_email, le.link_reciprocal, ";
                $linkquery .= "l.link_image, le.link_textfield, le.link_no_follow ";
                $linkquery .= "FROM " . $this->db_prefix() . "terms t ";
                $linkquery .= "LEFT JOIN " . $this->db_prefix() . "term_taxonomy tt ON (t.term_id = tt.term_id) ";
                $linkquery .= "LEFT JOIN " . $this->db_prefix() . "term_relationships tr ON (tt.term_taxonomy_id = tr.term_taxonomy_id) ";
                $linkquery .= "LEFT JOIN " . $this->db_prefix() . "links l ON (tr.object_id = l.link_id) ";
                $linkquery .= "LEFT JOIN " . $this->db_prefix() . "links_extrainfo le ON (l.link_id = le.link_id) ";
                $linkquery .= "WHERE tt.taxonomy = 'link_category' ";

                $linkitems = $wpdb->get_results($linkquery, ARRAY_A);

                if ( $linkitems ) {
                    $headerrow = array();

                    foreach ($linkitems[0] as $key => $option)
                    {
                        $headerrow[] = '"' . $key . '"';
                    }

                    $headerdata .= join(',', $headerrow)."\n";
                    fwrite($fh, $headerdata);

                    foreach ( $linkitems as $linkitem ) {
                        $datarow = array();
                        foreach ( $linkitem as $key => $value ) {
                            $datarow[] = '"' . $value . '"';
                        }
                        $data = join(',', $datarow)."\n";
                        fwrite($fh, $data);
                    }

                    fclose($fh);

                    $message = "2";
                }
            }
            else
            {
                $message = "3";
            }
        }


        //lets redirect the post request into get request (you may add additional params at the url, if you need to show save results
		wp_redirect($this->remove_querystring_var($_POST['_wp_http_referer'], 'message') . "&message=" . $message);
	}

		//executed if the post arrives initiated by pressing the submit button of form
	function on_save_changes_settingssets() {
		//user permission check
		if ( !current_user_can('manage_options') )
			wp_die( __('Not allowed', 'link-library') );	
		//cross check the given referer
		check_admin_referer('link-library');

		$messages = array();
		$row = 0;
		$successfulimport = 0;

		if (isset($_POST['importlinks']))
		{
            wp_suspend_cache_addition(true);
            set_time_limit ( 600 );

			global $wpdb;

			$handle = fopen($_FILES['linksfile']['tmp_name'], "r");

			if ($handle)
			{
				$skiprow = 1;

				while (($data = fgetcsv($handle, 5000, ",")) !== FALSE) {
					$row += 1;
					if ($skiprow == 1 && isset($_POST['firstrowheaders']) && $row >= 2)
						$skiprow = 0;
					elseif (!isset($_POST['firstrowheaders']))
						$skiprow = 0;

					if (!$skiprow)
					{
						if (count($data) == 14)
						{
							$existingcatquery = "SELECT t.term_id FROM " . $this->db_prefix() . "terms t, " . $this->db_prefix() . "term_taxonomy tt ";
							$existingcatquery .= "WHERE t.name = '" . $data[5] . "' AND t.term_id = tt.term_id AND tt.taxonomy = 'link_category'";
							$existingcat = $wpdb->get_var($existingcatquery);

							if (!$existingcat)
							{
								$newlinkcatdata = array("cat_name" => $data[5], "category_description" => "", "category_nicename" => $wpdb->escape($data[5]));
								$newlinkcat = wp_insert_category($newlinkcatdata);

								$newcatarray = array("term_id" => $newlinkcat);

								$newcattype = array("taxonomy" => 'link_category');

								$wpdb->update( $this->db_prefix().'term_taxonomy', $newcattype, $newcatarray);

								$newlinkcat = array($newlinkcat);
							}
							else
							{
								$newlinkcat = array($existingcat);
							}

							$newlink = array("link_name" => esc_html(stripslashes($data[0])), 
											"link_url" => esc_html(stripslashes($data[1])),
											"link_rss" => esc_html(stripslashes($data[2])),
											"link_description" => esc_html(stripslashes($data[3])),
											"link_notes" => esc_html(stripslashes($data[4])),
											"link_category" => $newlinkcat,
											"link_visible" => $data[6],
											"link_image" => $data[11]);

							$newlinkid = wp_insert_link($newlink);

							if ($newlinkid != 0)
							{
								$extradatatable = $this->db_prefix() . "links_extrainfo";
								$nofollowvalue = ($data[13] == 'Y' ? true : false);
								$wpdb->update( $extradatatable, array( 'link_second_url' => $data[7], 'link_telephone' => $data[8], 'link_email' => $data[9], 'link_reciprocal' => $data[10], 'link_textfield' => esc_html($data[12]), 'link_no_follow' => $nofollowvalue ), array( 'link_id' => $newlinkid ));

								$successfulimport += 1;
							}
						}
						else
						{
							$messages[] = '4';
						}
					}
				}
			}

			if (isset($_POST['firstrowheaders']))
				$row -= 1;
			
			$messages[] = '9';

            wp_suspend_cache_addition(false);
		}
		elseif (isset($_POST['exportsettings']))
		{
            $upload_dir = wp_upload_dir();
			if (is_writable($upload_dir['path']))
			{
				$myFile = $upload_dir['path'] . "/SettingSet" . $_POST['settingsetid'] . "Export.csv";
				$fh = fopen($myFile, 'w') or die("can't open file");

				$sourcesettingsname = 'LinkLibraryPP' . $_POST['settingsetid'];
				$sourceoptions = get_option($sourcesettingsname);

				$headerrow = array();

				foreach ($sourceoptions as $key => $option)
				{
					$headerrow[] = '"' . $key . '"';
				}

				$headerdata .= join(',', $headerrow)."\n";
				fwrite($fh, $headerdata);

				$datarow = array();

				foreach ($sourceoptions as $key => $option)
				{
					$datarow[] = '"' . $option . '"';
				}

				$data .= join(',', $datarow)."\n";
				fwrite($fh, $data);

				fclose($fh);

				$messages[] = '5';
			}
			else
				$messages[] = '6';
		}
		elseif (isset($_POST['importsettings']))
		{
			global $wpdb;

			if ($_FILES['settingsfile']['tmp_name'] != "")
			{
				$handle = fopen($_FILES['settingsfile']['tmp_name'], "r");

				$row = 1;
				$optionnames = "";
				$options = "";

				while (($data = fgetcsv($handle, 5000, ",")) !== FALSE) {
					if ($row == 1)
					{
						$optionnames = $data;
						$row++;
					}
					else if ($row == 2)
					{
						for ($counter = 0; $counter <= count($data) - 1; $counter++)
							$options[$optionnames[$counter]] = $data[$counter];
						$row++;
					}
				}

				if ($options != "")
				{
					$settingsname = 'LinkLibraryPP' . $_POST['settingsetid'];

					update_option($settingsname, $options);
					
					$messages[] = '7';
				}

				fclose($handle);
			}
			else 
			{
				$messages[] = '8';
			}
		}
		else
		{
			$settingsetid = $_POST['settingsetid'];
			$settings = $_POST['settingsetid'];

			$settingsname = 'LinkLibraryPP' . $settingsetid;
			
			$options = get_option($settingsname);
			
			foreach (array('order', 'table_width', 'num_columns', 'categorylist', 'excludecategorylist', 'position',
						   'beforecatlist1', 'beforecatlist2', 'beforecatlist3','catnameoutput', 'linkaddfrequency', 
						   'defaultsinglecat', 'rsspreviewcount', 'rssfeedinlinecount', 'linksperpage', 'catdescpos',
						   'catlistdescpos', 'rsspreviewwidth', 'rsspreviewheight', 'numberofrssitems',
						   'displayweblink', 'sourceweblink', 'showtelephone', 'sourcetelephone', 'showemail', 'sourceimage', 'sourcename', 'popup_width', 'popup_height' ) 
						   as $option_name) {
				if (isset($_POST[$option_name])) {
					$options[$option_name] = str_replace("\"", "'", strtolower( $_POST[$option_name] ) );
				}
			}

			foreach (array('linkheader', 'descheader', 'notesheader','linktarget', 'settingssetname', 'loadingicon',
							'direction', 'linkdirection', 'linkorder', 'addnewlinkmsg', 'linknamelabel', 'linkaddrlabel', 'linkrsslabel',
							'linkcatlabel', 'linkdesclabel', 'linknoteslabel', 'addlinkbtnlabel', 'newlinkmsg', 'moderatemsg', 'imagepos',
							'imageclass', 'rssfeedtitle', 'rssfeeddescription', 'showonecatmode', 'linkcustomcatlabel', 'linkcustomcatlistentry',
							'searchlabel', 'dragndroporder', 'cattargetaddress', 'beforeweblink', 'afterweblink', 'weblinklabel', 'beforetelephone',
							'aftertelephone', 'telephonelabel', 'beforeemail', 'afteremail', 'emaillabel', 'beforelinkhits', 'afterlinkhits',
							'linkreciprocallabel', 'linksecondurllabel', 'linktelephonelabel', 'linkemaillabel', 'emailcommand', 'rewritepage',
							'maxlinks', 'beforedate', 'afterdate', 'beforeimage', 'afterimage', 'beforerss','afterrss', 'beforenote', 'afternote',
							'beforelink','afterlink', 'beforeitem', 'afteritem', 'beforedesc', 'afterdesc', 'addbeforelink', 'addafterlink',
							'beforelinkrating', 'afterlinkrating', 'linksubmitternamelabel', 'linksubmitteremaillabel', 'linksubmittercommentlabel',
							'addlinkcatlistoverride', 'beforelargedescription', 'afterlargedescription', 'customcaptchaquestion', 'customcaptchaanswer',
							'rssfeedaddress', 'linklargedesclabel', 'flatlist', 'searchresultsaddress', 'link_popup_text', 'linktitlecontent', 'paginationposition' ) as $option_name) {
				if (isset($_POST[$option_name])) {
					$options[$option_name] = str_replace("\"", "'", $_POST[$option_name]);
				}
			}

			foreach (array('hide_if_empty', 'catanchor', 'showdescription', 'shownotes', 'showrating', 'showupdated', 'show_images', 
							'use_html_tags', 'show_rss', 'nofollow','showcolumnheaders','show_rss_icon', 'showcategorydescheaders',
							'showcategorydesclinks', 'showadmineditlinks', 'showonecatonly', 'rsspreview', 'rssfeedinline', 'rssfeedinlinecontent',
							'pagination', 'hidecategorynames', 'showinvisible', 'showdate', 'showuserlinks', 'emailnewlink', 'usethumbshotsforimages', 'uselocalimagesoverthumbshots',
							'addlinkreqlogin', 'showcatlinkcount', 'publishrssfeed', 'showname', 'enablerewrite', 'storelinksubmitter', 'showlinkhits', 'showcaptcha',
							'showlargedescription', 'addlinknoaddress', 'featuredfirst', 'usetextareaforusersubmitnotes', 'showcatonsearchresults', 'shownameifnoimage',
                            'enable_link_popup', 'nocatonstartup')
							as $option_name) {
				if ( isset( $_POST[$option_name] ) ) {
					$options[$option_name] = true;
				} else {
					$options[$option_name] = false;
				}
			}

			foreach(array('displayastable', 'divorheader','showaddlinkrss', 'showaddlinkdesc', 'showaddlinkcat', 'showaddlinknotes','addlinkcustomcat',
						  'showaddlinkreciprocal', 'showaddlinksecondurl', 'showaddlinktelephone', 'showaddlinkemail', 'showcustomcaptcha', 'showlinksubmittername',
						  'showaddlinksubmitteremail', 'showlinksubmittercomment', 'showuserlargedescription') as $option_name) {
				if ($_POST[$option_name] == 'true')
					$options[$option_name] = true;
				elseif ($_POST[$option_name] == 'false')
					$options[$option_name] = false;
			}

			foreach (array('catlistwrappers') as $option_name)
			{
				if (isset($_POST[$option_name])) {
					$options[$option_name] = (int)($_POST[$option_name]);
				}
			}

			update_option($settingsname, $options);
			$messages[] = "1";

			global $wpdb;

			if ($options['categorylist'] != '')
			{
				$categoryids = explode(',', $options['categorylist']);

				foreach($categoryids as $categoryid)
				{
					$linkcatquery = "SELECT distinct t.name, t.term_id, t.slug as category_nicename, tt.description as category_description ";
					$linkcatquery .= "FROM " . $this->db_prefix() . "terms t, " . $this->db_prefix(). "term_taxonomy tt ";

					if (isset($_POST['hide_if_empty']))
						$linkcatquery .= ", " . $this->db_prefix() . "term_relationships tr, " . $this->db_prefix() . "links l ";

					$linkcatquery .= "WHERE t.term_id = tt.term_id AND tt.taxonomy = 'link_category'";

					$linkcatquery .= " AND t.term_id = " . $categoryid;

					$catnames = $wpdb->get_results($linkcatquery);

					if (!$catnames)
					{
						$messages[] = '2';
					}
				}
			}
			
			if ($options['excludecategorylist'] != '')
			{
				$categoryids = explode(',', $options['excludecategorylist']);

				foreach($categoryids as $categoryid)
				{
					$linkcatquery = "SELECT distinct t.name, t.term_id, t.slug as category_nicename, tt.description as category_description ";
					$linkcatquery .= "FROM " . $this->db_prefix() . "terms t, " . $this->db_prefix(). "term_taxonomy tt ";

					if (isset($_POST['hide_if_empty']))
						$linkcatquery .= ", " . $this->db_prefix() . "term_relationships tr, " . $this->db_prefix() . "links l ";

					$linkcatquery .= "WHERE t.term_id = tt.term_id AND tt.taxonomy = 'link_category'";

					$linkcatquery .= " AND t.term_id = " . $categoryid;
					
					$catnames = $wpdb->get_results($linkcatquery);

					if (!$catnames)
					{
						$messages[] = '3';
					}
				}
			}
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
		}

		//lets redirect the post request into get request (you may add additional params at the url, if you need to show save results
		$messagelist = implode(",", $messages);
		$cleanredirecturl = $this->remove_querystring_var($_POST['_wp_http_referer'], 'messages');
		$cleanredirecturl = $this->remove_querystring_var($cleanredirecturl, 'importrowscount');
		$cleanredirecturl = $this->remove_querystring_var($cleanredirecturl, 'successimportcount');
		$cleanredirecturl = $this->remove_querystring_var($cleanredirecturl, 'copy');
		$cleanredirecturl = $this->remove_querystring_var($cleanredirecturl, 'reset');
		$cleanredirecturl = $this->remove_querystring_var($cleanredirecturl, 'resettable');		
		$cleanredirecturl = $this->remove_querystring_var($cleanredirecturl, 'source');
		$redirecturl = $cleanredirecturl;

		if (!empty($messages))
			$redirecturl = $cleanredirecturl . "&messages=" . $messagelist;

		if ($row != 0)
			$redirecturl .= "&importrowscount=" . $row;

		if ($successfulimport != 0)
			$redirecturl .= "&successimportcount=" . $successfulimport;

		wp_redirect( $redirecturl );
	}

			//executed if the post arrives initiated by pressing the submit button of form
	function on_save_changes_moderate() {
		//user permission check
		if ( !current_user_can('manage_options') )
			wp_die( __('Not allowed', 'link-library') );	
		//cross check the given referer
		check_admin_referer('link-library');

		$message = '';
		
		$genoptions = get_option('LinkLibraryGeneral');

		if (isset($_POST['approvelinks']) && (isset($_POST['links']) && count($_POST['links']) > 0))
		{
			global $wpdb;

			$section = 'moderate';

			foreach ($_POST['links'] as $approved_link)
			{
				$linkdescquery = "SELECT link_description, link_name ";
				$linkdescquery .= "FROM " . $this->db_prefix() . "links l ";
				$linkdescquery .= "WHERE link_id = " . $approved_link;

				$linkdata = $wpdb->get_row($linkdescquery, ARRAY_A); 

				$modpos = strpos($linkdata['link_description'], "LinkLibrary:AwaitingModeration:RemoveTextToApprove");

				if ($modpos)
				{
					$startpos = $modpos + 51;
					$newlinkdesc = substr($linkdata['link_description'], $startpos);

					$id = array("id" => $linkdescquery);
					$newdesc = array ("link_description", $newlinkdesc);

					$tablename = $this->db_prefix() . "links";
					$wpdb->update( $tablename, array( 'link_description' => $newlinkdesc, 'link_visible' => 'Y' ), array( 'link_id' => $approved_link ));
				}
				
				$linkextradata = $wpdb->get_row("select * from " . $this->db_prefix() . "links_extrainfo where link_id = " . $approved_link, ARRAY_A);
				
				if ($genoptions['emaillinksubmitter'] == true && $linkextradata['link_submitter_email'] != '')
				{
					if ($genoptions['usefirstpartsubmittername'] == true)
					{
						$spacepos = strpos($linkextradata['link_submitter_name'], " ");
						if ($spacepos !== false)
							$linkextradata['link_submitter_name'] = substr($linkextradata['link_submitter_name'], 0, $spacepos);
					}
					
					$emailtitle = str_replace('%linkname%', $linkdata['link_name'], $genoptions['approvalemailtitle']);
					$emailbody = nl2br($genoptions['approvalemailbody']);
					$emailbody = str_replace('%submittername%', stripslashes($linkextradata['link_submitter_name']), stripslashes($emailbody));
					$emailbody = str_replace('%linkname%', $linkdata['link_name'], $emailbody);
					
					$headers = "MIME-Version: 1.0\r\n";
					$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
					
					if ($genoptions['moderatorname'] != '' && $genoptions['moderatoremail'] != '')
						$headers .= "From: \"" . $genoptions['moderatorname'] . "\" <" . $genoptions['moderatoremail'] . ">\n";
					
					$message = $emailbody;
						
					if ( ! $genoptions['suppressemailfooter'] ) {
                        $message .= "<br /><br />" . __('Message generated by', 'link-library') . " <a href='http://ylefebvre.ca/wordpress-plugins/link-library/'>Link Library</a> for Wordpress";
                    }

					wp_mail($linkextradata['link_submitter_email'], $emailtitle, $message, $headers);
				}
			}

			$message = '1';
		}
		elseif (isset($_POST['deletelinks']) && (isset($_POST['links']) && count($_POST['links']) > 0))
		{
			global $wpdb;

			$section = 'moderate';

			foreach ($_POST['links'] as $approved_link)
			{
				$linkdescquery = "SELECT link_description, link_name ";
				$linkdescquery .= "FROM " . $this->db_prefix() . "links l ";
				$linkdescquery .= "WHERE link_id = " . $approved_link;

				$linkdata = $wpdb->get_row($linkdescquery, ARRAY_A);
				
				$linkextradata = $wpdb->get_row("select * from " . $this->db_prefix() . "links_extrainfo where link_id = " . $approved_link, ARRAY_A);
				
				if ($genoptions['emaillinksubmitter'] == true && $linkextradata['link_submitter_email'] != '')
				{					
					$emailtitle = str_replace('%linkname%', $linkdata['link_name'], $genoptions['rejectedemailtitle']);
					$emailbody = nl2br($genoptions['rejectedemailbody']);
					$emailbody = str_replace('%submittername%', stripslashes($linkextradata['link_submitter_name']), stripslashes($emailbody));
					$emailbody = str_replace('%linkname%', $linkdata['link_name'], $emailbody);
					
					$headers = "MIME-Version: 1.0\r\n";
					$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
					
					if ($genoptions['moderatorname'] != '' && $genoptions['moderatoremail'] != '')
						$headers .= "From: \"" . $genoptions['moderatorname'] . "\" <" . $genoptions['moderatoremail'] . ">\n";
					
					$message = $emailbody;
						
					$message .= "<br /><br />" . __('Message generated by', 'link-library') . " <a href='http://ylefebvre.ca/wordpress-plugins/link-library/'>Link Library</a> for Wordpress";
					
					wp_mail($linkextradata['link_submitter_email'], $emailtitle, $message, $headers);
				}
			
				$wpdb->query("DELETE FROM " . $this->db_prefix() . "links WHERE link_id = " . $approved_link);
			}

			$message = '2';
		}

		$cleanredirecturl = $this->remove_querystring_var($_POST['_wp_http_referer'], 'message');

		if ($message != '')
			$redirecturl = $cleanredirecturl . "&message=" . $message;
		else
			$redirecturl = $cleanredirecturl;

		//lets redirect the post request into get request (you may add additional params at the url, if you need to show save results
		wp_redirect($redirecturl);
	}

	function on_save_changes_stylesheet() {
		//user permission check
		if ( !current_user_can('manage_options') )
			wp_die( __('Not allowed', 'link-library') );	
		//cross check the given referer
		check_admin_referer('link-library');

		if (isset($_POST['submitstyle']))
		{
			$genoptions = get_option('LinkLibraryGeneral');

			$genoptions['fullstylesheet'] = $_POST['fullstylesheet'];

			update_option('LinkLibraryGeneral', $genoptions);
			$message = 1;
		}
		elseif (isset($_POST['resetstyle']))
		{
			$genoptions = get_option('LinkLibraryGeneral');

			$stylesheetlocation = plugins_url( 'stylesheettemplate.css', __FILE__ );
			if (file_exists($stylesheetlocation))
				$genoptions['fullstylesheet'] = file_get_contents($stylesheetlocation);

			update_option('LinkLibraryGeneral', $genoptions);

			$message = 2;
		}

		//lets redirect the post request into get request (you may add additional params at the url, if you need to show save results
		wp_redirect($this->remove_querystring_var($_POST['_wp_http_referer'], 'message') . "&message=" . $message);
	}
	
	function on_save_changes_reciprocal() {
		//user permission check
		if ( !current_user_can('manage_options') )
			wp_die( __('Not allowed', 'link-library') );	
		//cross check the given referer
		check_admin_referer('link-library');
		
		$message = -1;
		
		$genoptions = get_option('LinkLibraryGeneral');

		$genoptions['recipcheckaddress'] = ( ( isset( $_POST['recipcheckaddress'] ) && $_POST['recipcheckaddress'] !== '' ) ? $_POST['recipcheckaddress'] : "" );
		$genoptions['recipcheckdelete403'] = ( ( isset( $_POST['recipcheckdelete403'] ) && $_POST['recipcheckdelete403'] !== '' ) ? $_POST['recipcheckdelete403'] : "" );

		update_option('LinkLibraryGeneral', $genoptions);
		
		if (!isset($_POST['recipcheck']))
		{
			$message = 1;
		}
		elseif (isset($_POST['recipcheck']))
		{
			$message = 2;
		}
		
		if ($message != -1)
			$messageend = "&message=" . $message;
		else
			$messageend = '';

		//lets redirect the post request into get request (you may add additional params at the url, if you need to show save results
		wp_redirect($this->remove_querystring_var($_POST['_wp_http_referer'], 'message') . $messageend);
	}

	function general_meta_box($data) {
		$genoptions = $data['genoptions'];

		?>
			<table>
			<tr>
			<td>
				<input type='hidden' value='<?php echo $genoptions['schemaversion']; ?>' name='schemaversion' id='schemaversion' />
				<table>
				<tr>
				<td class='lltooltip' title='<?php _e('The stylesheet is now defined and stored using the Link Library admin interface. This avoids problems with updates from one version to the next.', 'link-library'); ?>' style='width:200px'><?php _e('Stylesheet','link-library'); ?></td>
				<td class='lltooltip' title='<?php _e('The stylesheet is now defined and stored using the Link Library admin interface. This avoids problems with updates from one version to the next.', 'link-library'); ?>'><a href="<?php echo add_query_arg( array( 'page' => 'link-library-stylesheet', 'section' => 'stylesheet'), admin_url('admin.php') ); ?>"><?php _e('Editor', 'link-library'); ?></a></td>
				</tr>
				<tr>
				<td><?php _e('Number of Libraries','link-library'); ?></td>
				<td><input type="text" id="numberstylesets" name="numberstylesets" size="5" value="<?php if ($genoptions['numberstylesets'] == '') echo '1'; echo $genoptions['numberstylesets']; ?>"/></td>
				</tr>
				<tr>
				<td class="lltooltip" title="<?php _e('Enter comma-separate list of pages on which the Link Library stylesheet and scripts should be loaded. Primarily used if you display Link Library using the API','link-library'); ?>"><?php _e('Additional pages to load styles and scripts','link-library'); ?></td>
				<td class="lltooltip" title="<?php _e('Enter comma-separate list of pages on which the Link Library stylesheet and scripts should be loaded. Primarily used if you display Link Library using the API','link-library'); ?>"><input type="text" id="includescriptcss" name="includescriptcss" size="40" value="<?php echo $genoptions['includescriptcss']; ?>"/></td>
				</tr>
				<tr>
				<td><?php _e('Debug Mode', 'link-library'); ?></td>
				<td><input type="checkbox" id="debugmode" name="debugmode" <?php if ($genoptions['debugmode']) echo ' checked="checked" '; ?>/></td>
				</tr>
				<tr>
				<td class="lltooltip" title="<?php _e('This function is only possible when showing one category at a time and while the default category is not shown.', 'link-library'); ?>"><?php _e('Page Title Prefix','link-library'); ?></td>
				<td class="lltooltip" title="<?php _e('This function is only possible when showing one category at a time and while the default category is not shown.', 'link-library'); ?>"><input type="text" id="pagetitleprefix" name="pagetitleprefix" size="10" value="<?php echo $genoptions['pagetitleprefix']; ?>"/></td>
				</tr>
				<tr>
				<td class="lltooltip" title="<?php _e('This function is only possible when showing one category at a time and while the default category is not shown.', 'link-library'); ?>"><?php _e('Page Title Suffix','link-library'); ?></td>
				<td class="lltooltip" title="<?php _e('This function is only possible when showing one category at a time and while the default category is not shown.', 'link-library'); ?>"><input type="text" id="pagetitlesuffix" name="pagetitlesuffix" size="10" value="<?php echo $genoptions['pagetitlesuffix']; ?>"/></td>
				</tr>
				<tr>
					<td class='lltooltip' title='<?php _e('API Key for Thumbshots.com thumbnail generation accounts', 'link-library'); ?>'><?php _e('Thumbshots API Key', 'link-library'); ?></td>
					<td colspan='4' class='lltooltip' title='<?php _e('API Key for Thumbshots.com thumbnail generation accounts', 'link-library'); ?>'><input type="text" id="thumbshotscid" name="thumbshotscid" size="20" value="<?php echo $genoptions['thumbshotscid']; ?>"/></td>
				</tr>
                <tr>
					<td class='lltooltip' title='<?php _e('Path for images files that are uploaded manually or generated through Thumbshots service', 'link-library'); ?>'><?php _e('Link Image File Path', 'link-library'); ?></td>
                    <td colspan='4' class='lltooltip' title='<?php _e('Path for images files that are uploaded manually or generated through Thumbshots service', 'link-library'); ?>'><select id="imagefilepath" name="imagefilepath">
                            <option value="absolute" <?php selected($genoptions['imagefilepath'], 'absolute'); ?>>Absolute
                            <option value="relative" <?php selected($genoptions['imagefilepath'], 'relative'); ?>>Relative
                        </select></td>
				</tr>
                <tr>
                    <td><?php _e( 'Links Date Format', 'link-library' ); ?> (<a target="datehelp" href="https://codex.wordpress.org/Formatting_Date_and_Time"><?php _e( 'Help', 'link-library' ); ?></a>)</td>
                    <td><input type="text" id="links_updated_date_format" name="links_updated_date_format" size="20" value="<?php echo get_option('links_updated_date_format'); ?>"/></td>
                </tr>
                <tr>
                    <td><input type="submit" name="exportalllinks" value="<?php _e('Export All Links', 'link-library'); ?>" /></td>
                </tr>
                
				
				</table>
			</td>
            <?php if ( !$genoptions['hidedonation'] ) { ?>
                <td style='padding: 8px; border: 1px solid #cccccc;'>
                    <div style="width: 400px"><h3>Support the author</h3><br /><table><tr><td><a href="http://www.packtpub.com/wordpress-plugin-development-cookbook/book"><img src='<?php echo plugins_url('icons/7683os_cover_small.jpg', __FILE__); ?>'></td><td></a>Learn how to create your own plugins with my upcoming book, coming out August 2012.<br /><br />Pre-order now!<br /><br /><a href="http://www.packtpub.com/wordpress-plugin-development-cookbook/book">Packt Publishing</a><br /><a href="http://www.amazon.com/dp/1849517681/?tag=packtpubli-20">Amazon.com</a><br /><a href="http://www.amazon.ca/WordPress-Development-Cookbook-Yannick-Lefebvre/dp/1849517681/ref=sr_1_1?ie=UTF8&qid=1336252569&sr=8-1">Amazon.ca</a></td></tr></table></div>
                </td>
            <?php } ?>
			</table>
		<?php }
		
	function general_meta_bookmarklet_box($data) {
			$bookmarkletcode = 'javascript:void(linkmanpopup=window.open(\''.get_bloginfo('wpurl').'/wp-admin/link-add.php?action=popup&linkurl=\'+escape(location.href)+\'&name=\'+(document.title),\'LinkManager\',\'scrollbars=yes,width=900px,height=600px,left=15,top=15,status=yes,resizable=yes\'));linkmanpopup.focus();window.focus();linkmanpopup.focus();';
			?>
			<p><?php _e('Add new links to your site with this bookmarklet.', 'link-library'); ?></p>
			<p><?php _e('To use this feature, drag-and-drop the button below to your favorite / bookmark toolbar.', 'link-library'); ?></p>
			<a href="<?php echo $bookmarkletcode; ?>" class='button' title="Add to Links"><?php _e('Add to Links', 'link-library'); ?></a>
		
	<?php }
		
	function general_moderation_meta_box($data) {
		$genoptions = $data['genoptions'];
	?>
		<table>
            <tr><td colspan="2"><strong><?php _e('Approval and rejection e-mail functionality will only work correctly if the submitter e-mail field is displayed on the user link submission form', 'link-library'); ?></strong></td></tr>
			<tr>
				<td class='lltooltip' title='<?php _e('URL that user will be redirected to after submitting new link. When used, the short code [link-library-addlinkcustommsg] should be placed on the destination page.', 'link-library'); ?>.' style='width:250px'><?php _e('Link Acknowledgement URL', 'link-library'); ?></td>
				<td class='lltooltip' style='width:75px;padding-right:20px' title='<?php _e('URL that user will be redirected to after submitting new link. When used, the short code [link-library-addlinkcustommsg] should be placed on the destination page.', 'link-library'); ?>.'><input type="text" id="linksubmissionthankyouurl" name="linksubmissionthankyouurl" size="60" value='<?php echo $genoptions['linksubmissionthankyouurl']; ?>' /></td>
			</tr>
			<tr>
				<td class='lltooltip' title='<?php _e('Title of e-mail sent to site admin when new links are submitted. Use %linkname% as a variable to be replaced by the actual link name', 'link-library'); ?>.' style='width:250px'><?php _e('Moderation Notification Title', 'link-library'); ?></td>
				<td style='width:75px;padding-right:20px'><input type="text" id="moderationnotificationtitle" name="moderationnotificationtitle" size="60" value='<?php echo $genoptions['moderationnotificationtitle']; ?>' /></td>
			</tr>
			<tr>
				<td class='lltooltip' title='<?php _e('Will send a confirmation e-mail to link submitter if they provided their contact information', 'link-library'); ?>.' style='width:250px'><?php _e('E-mail submitter on link approval or rejection', 'link-library'); ?></td>
				<td style='width:75px;padding-right:20px'><input type="checkbox" id="emaillinksubmitter" name="emaillinksubmitter" <?php if ($genoptions['emaillinksubmitter']) echo ' checked="checked" '; ?>/></td>
			</tr>
            <tr>
                <td class='lltooltip' style='width:250px'><?php _e('Suppress Link Library message in e-mail footer', 'link-library'); ?></td>
                <td style='width:75px;padding-right:20px'><input type="checkbox" id="suppressemailfooter" name="suppressemailfooter" <?php if ($genoptions['suppressemailfooter']) echo ' checked="checked" '; ?>/></td>
            </tr>
			<tr>
				<td style='width:250px'><?php _e('Only use first part of submitter name', 'link-library'); ?></td>
				<td style='width:75px;padding-right:20px'><input type="checkbox" id="usefirstpartsubmittername" name="usefirstpartsubmittername" <?php if ($genoptions['usefirstpartsubmittername']) echo ' checked="checked" '; ?>/></td>
			</tr>			
			<tr>
				<td class='lltooltip' title='<?php _e('The name of the e-mail account that the approval e-mail will be sent from', 'link-library'); ?>'><?php _e('Moderator Name', 'link-library'); ?></td>
				<td><input type="text" id="moderatorname" name="moderatorname" size="60" value="<?php echo $genoptions['moderatorname']; ?>"/></td>
			</tr>
			<tr>
				<td class='lltooltip' title='<?php _e('The e-mail address that the approval e-mail will be sent from', 'link-library'); ?>'><?php _e('Moderator E-mail', 'link-library'); ?></td>
				<td><input type="text" id="moderatoremail" name="moderatoremail" size="60" value="<?php echo $genoptions['moderatoremail']; ?>"/></td>
			</tr>
			<tr>
				<td class='lltooltip' title='<?php _e('Title of approval e-mail. Use %linkname% as a variable to be replaced by the actual link name', 'link-library'); ?>'><?php _e('Approval e-mail title', 'link-library'); ?></td>
				<td><input type="text" id="approvalemailtitle" name="approvalemailtitle" size="60" value="<?php echo $genoptions['approvalemailtitle']; ?>"/></td>
			</tr>
			<tr>
				<td class='lltooltip' title='<?php _e('Body of approval e-mail. Use %linkname% as a variable to be replaced by the actual link name and %submittername% for the submitter name', 'link-library'); ?>'><?php _e('Approval e-mail body', 'link-library'); ?></td>
				<td><textarea id="approvalemailbody" name="approvalemailbody" cols="60"><?php echo stripslashes($genoptions['approvalemailbody']); ?></textarea></td>
			</tr>
			<tr>
				<td class='lltooltip' title='<?php _e('Title of rejection e-mail. Use %linkname% as a variable to be replaced by the actual link name', 'link-library'); ?>'><?php _e('Rejection e-mail title', 'link-library'); ?></td>
				<td><input type="text" id="rejectedemailtitle" name="rejectedemailtitle" size="60" value="<?php echo $genoptions['rejectedemailtitle']; ?>"/></td>
			</tr>
			<tr>
				<td class='lltooltip' title='<?php _e('Body of rejection e-mail. Use %linkname% as a variable to be replaced by the actual link name and %submittername% for the submitter name', 'link-library'); ?>'><?php _e('Rejection e-mail body', 'link-library'); ?></td>
				<td><textarea id="rejectedemailbody" name="rejectedemailbody" cols="60"><?php echo stripslashes($genoptions['rejectedemailbody']); ?></textarea></td>
			</tr>			
		</table>
	<?php }

    function general_hide_donation_meta_box () {
        ?>
        <p><?php _e( 'The following option allows you to hide the Donate button and Support the Author section in the Link Library Admin pages. If you enjoy this plugin and use it regularly, please consider making a donation to the author before turning off these messages. This menu section will disappear along with the other elements.', 'link-library' ); ?></p>
        <table>
            <tr>
                <td class='lltooltip'><?php _e('Hide Donation and Support Links', 'link-library'); ?></td>
                <td><input type="checkbox" id="hidedonation" name="hidedonation" <?php if ( $genoptions['hidedonation']) echo ' checked="checked" '; ?>/></td>
            </tr>
        </table>
    <?php
    }
	
	function general_save_meta_box() {
	?>
		<div class="submitbox">
		<input type="submit" name="submit" class="button-primary" value="<?php _e('Save','link-library'); ?>" />
		</div>
	<?php
	}

	function settingssets_save_meta_box() {
	?>

		<div class="submitbox">
		<input type="submit" name="submit" class="button-primary" value="<?php _e('Update Settings','link-library'); ?>" />
		</div>
	<?php
	}

	function moderate_meta_box() {
        $genoptions = get_option('LinkLibraryGeneral');
	?>
			<table class='widefat' style='clear:none;width:100%;background-color:#F1F1F1;background-image: linear-gradient(to top, #ECECEC, #F9F9F9);background-position:initial initial;background-repeat: initial initial'>
				<tr>
					<th style='width: 30px'></th>
					<th style='width: 200px'><?php _e('Link Name', 'link-library'); ?></th>
					<th style='width: 200px'><?php _e('Link Category', 'link-library'); ?></th>
					<th style='width: 300px'><?php _e('Link URL', 'link-library'); ?></th>
					<th><?php _e('Link Description', 'link-library'); ?></th>
				</tr>
			<?php global $wpdb;

				$linkquery = "SELECT distinct *, l.link_id as true_link_id ";
				$linkquery .= "FROM " . $this->db_prefix() . "links_extrainfo le ";
				$linkquery .= "LEFT JOIN " . $this->db_prefix() . "links l ON (le.link_id = l.link_id) ";
				$linkquery .= "LEFT JOIN " . $this->db_prefix() . "term_relationships tr ON (l.link_id = tr.object_id) ";
				$linkquery .= "LEFT JOIN " . $this->db_prefix() . "term_taxonomy tt ON (tt.term_taxonomy_id = tr.term_taxonomy_id AND tt.taxonomy = 'link_category') ";
				$linkquery .= "LEFT JOIN " . $this->db_prefix() . "terms t ON (t.term_id = tt.term_id) ";
				$linkquery .= "WHERE l.link_description like '%LinkLibrary:AwaitingModeration:RemoveTextToApprove%' ";
				$linkquery .= " ORDER by link_name ASC";

                if ( $genoptions['debugmode'] ) {
                    echo '<!-- ' . $linkquery . ' -->' . "\n";
                }
				
				$linkitems = $wpdb->get_results($linkquery);

                if ( $genoptions['debugmode'] ) {
                    echo '<!-- ';
                    print_r( $linkitems );
                    echo ' -->';
                }
                                
				if ($linkitems) {
					foreach($linkitems as $linkitem) {

					$modpos = strpos($linkitem->link_description, "LinkLibrary:AwaitingModeration:RemoveTextToApprove");

					if ($modpos)
					{
						$startpos = $modpos + 51;
						$newlinkdesc = substr($linkitem->link_description, $startpos);
					}
			?>
					<tr style='background: #FFF'>
						<td><input type="checkbox" name="links[]" value="<?php echo $linkitem->true_link_id; ?>" /></td>
						<td><?php echo "<a title='Edit Link: " . $linkitem->link_name . "' href='" . add_query_arg( array( 'action' => 'edit', 'link_id' => $linkitem->true_link_id ), admin_url( 'link.php' ) ) . "'>" . $linkitem->link_name . "</a>"; ?></td>
						<td><?php echo $linkitem->name; ?></td>
						<td><?php echo "<a href='" . $linkitem->link_url . "'>" . $linkitem->link_url . "</a>"; ?></td>
						<td><?php echo $newlinkdesc; ?></td>
					</tr>
			<?php	}
				}
				else { ?>
					<tr>
						<td></td>
						<td><?php _e('No Links Found to Moderate', 'link-library'); ?></td>
						<td></td>
						<td></td>
					</tr>
			<?php } ?>

			</table><br />
			<input type="button" id="CheckAll" value="<?php _e('Check All','link-library'); ?>">
			<input type="button" id="UnCheckAll" value="<?php _e('Uncheck All', 'link-library'); ?>">

			<input type="submit" name="approvelinks" value="<?php _e('Approve Selected Items','link-library'); ?>" />
			<input type="submit" name="deletelinks" value="<?php _e('Delete Selected Items', 'link-library'); ?>" />
			
			<script type="text/javascript">
				jQuery(document).ready(function()
				{
					jQuery('#CheckAll').click(function() {
						jQuery("INPUT[type='checkbox']").attr('checked', true);
					});
					
					jQuery('#UnCheckAll').click(function() {
						jQuery("INPUT[type='checkbox']").attr('checked', false);
					});
				});
			</script>
			
		</div>

	<?php
	}

	function stylesheet_meta_box($data) {
		$genoptions = $data['genoptions'];
	?>

		<?php _e('If the stylesheet editor is empty after upgrading, reset to the default stylesheet using the button below or copy/paste your backup stylesheet into the editor.', 'link-library'); ?><br /><br />

		<textarea name='fullstylesheet' id='fullstylesheet' style='font-family:Courier' rows="30" cols="100">
<?php echo stripslashes($genoptions['fullstylesheet']);?>
</textarea>
		<div><input type="submit" name="submitstyle" value="<?php _e('Submit','link-library'); ?>" /><span style='padding-left: 650px'><input type="submit" name="resetstyle" value="<?php _e('Reset to default','link-library'); ?>" /></span></div>
	<?php
	}

	function settingssets_usage_meta_box($data) {
		$options = $data['options'];
		$settings = $data['settings'];
		$genoptions = $data['genoptions'];
	?>
			<div>
						<?php _e('Select Current Library Settings', 'link-library'); ?> : 
						<SELECT id="settingsetlist" name="settingsetlist" style='width: 300px'>
						<?php if ($genoptions['numberstylesets'] == '') $numberofsets = 1; else $numberofsets = $genoptions['numberstylesets'];
							for ($counter = 1; $counter <= $numberofsets; $counter++): ?>
								<?php $tempoptionname = "LinkLibraryPP" . $counter;
								   $tempoptions = get_option($tempoptionname); ?>
								   <option value="<?php echo $counter ?>" <?php if ($settings == $counter) echo 'SELECTED';?>><?php _e('Library', 'link-library'); ?> <?php echo $counter ?><?php if ($tempoptions != "") echo " (" . $tempoptions['settingssetname'] . ")"; ?></option>
							<?php endfor; ?>
						</SELECT>
						<INPUT type="button" name="go" value="<?php _e('Go', 'link-library'); ?>!" onClick="window.location= 'admin.php?page=link-library-settingssets&amp;settings=' + jQuery('#settingsetlist').val()">
						<?php if ($numberofsets > 1): ?>
						<?php _e('Copy from:', 'link-library'); ?> 
						<SELECT id="copysource" name="copysource" style='width: 300px'>
						<?php for ($counter = 1; $counter <= $numberofsets; $counter++): ?>
								<?php $tempoptionname = "LinkLibraryPP" . $counter;
								   $tempoptions = get_option($tempoptionname); 
								   if ($counter != $settings):?>
								   <option value="<?php echo $counter ?>" <?php if ($settings == $counter) echo 'SELECTED';?>><?php _e('Library', 'link-library'); ?> <?php echo $counter ?><?php if ($tempoptions != "") echo " (" . $tempoptions['settingssetname'] . ")"; ?></option>
								   <?php endif; 
								endfor;
							?>
						</SELECT>
						<INPUT type="button" name="copy" value="<?php _e('Copy', 'link-library'); ?>!" onClick="window.location= 'admin.php?page=link-library-settingssets&amp;settings=<?php echo $settings; ?>&amp;copy=<?php echo $settings; ?>&source=' + jQuery('#copysource').val()">
						<?php endif; ?>
				<br />
				<br />
				<table class='widefat' style='clear:none;width:100%;background-color:#F1F1F1;background-image: linear-gradient(to top, #ECECEC, #F9F9F9);background-position:initial initial;background-repeat: initial initial'>
					<thead>
					<tr>
						<th style='width:40px' class="lltooltip" title='<?php _e('Link Library Supports the Creation of an unlimited number of configurations to display link lists on your site', 'link-library'); ?>'>
							<?php _e('Library #', 'link-library'); ?>
						</th>
						<th style='width:130px' class="lltooltip" title='<?php _e('Link Library Supports the Creation of an unlimited number of configurations to display link lists on your site', 'link-library'); ?>'>
							<?php _e('Library Name', 'link-library'); ?>
						</th>
						<th style='width: 230px'><?php _e('Feature', 'link-library'); ?></th>
						<th class="lltooltip" title='<?php _e('Link Library Supports the Creation of an unlimited number of configurations to display link lists on your site', 'link-library'); ?>'>
							<?php _e('Code to insert on a Wordpress page', 'link-library'); ?>
						</th>
					</tr>
					</thead>
					<tr>
						<td style='background: #FFF'><?php echo $settings; ?></td><td style='background: #FFF'><?php echo $options['settingssetname']; ?></a></td><td style='background: #FFF'><?php _e('Display basic link library', 'link-library'); ?></td><td style='background: #FFF'><?php echo "[link-library settings=" . $settings . "]"; ?></td>
					</tr>
					<tr>
						<td style='background: #FFF'></td><td style='background: #FFF'></td><td style='background: #FFF'><?php _e('Display list of link categories', 'link-library'); ?></td><td style='background: #FFF'><?php echo "[link-library-cats settings=" . $settings . "]"; ?></td>
					</tr>
					<tr>
						<td style='background: #FFF'></td><td style='background: #FFF'></td><td style='background: #FFF'><?php _e('Display search box', 'link-library'); ?></td><td style='background: #FFF'><?php echo "[link-library-search settings=" . $settings . "]"; ?></td>						
					</tr>
					<tr>
						<td style='background: #FFF'></td><td style='background: #FFF'></td><td style='background: #FFF'><?php _e('Display link submission form', 'link-library'); ?></td><td style='background: #FFF'><?php echo "[link-library-addlink settings=" . $settings . "]"; ?></td>						
					</tr>
				</table> 
				<br />
			</div>

			<div>
				<table>
				<tr>
					<td style='text-align:right'>
						<span><button type="button" <?php echo "onclick=\"if ( confirm('" . esc_js(sprintf( __("You are about to Delete Library #'%s'\n  'Cancel' to stop, 'OK' to delete.", "link-library"), $settings ) ) . "') ) window.location.href='admin.php?page=link-library-settingssets&amp;deletesettings=" . $settings . "'\""; ?>><?php _e('Delete Library', 'link-library'); ?> <?php echo $settings ?></button></span>
						<span><button type="button" <?php echo "onclick=\"if ( confirm('" . esc_js(sprintf( __("You are about to reset Library '%s'\n  'Cancel' to stop, 'OK' to reset.", "link-library"), $settings )) . "') ) window.location.href='admin.php?page=link-library-settingssets&amp;settings=" . $settings . "&reset=" . $settings . "'\"";?>><?php _e('Reset current Library', 'link-library'); ?></button></span>
						<span><button type="button" <?php echo "onclick=\"if ( confirm('" . esc_js(sprintf( __("You are about to reset Library '%s' for a table layout\n  'Cancel' to stop, 'OK' to reset.", "link-library"), $settings )) . "') ) window.location.href='admin.php?page=link-library-settingssets&amp;settings=" . $settings . "&resettable=" . $settings . "'\""; ?>><?php _e('Reset current Library for table layout', 'link-library'); ?> </button></span>
					</td>
				</tr>
				</table>
			</div>
	<?php }
		
	function settingssets_common_meta_box($data) { 
		$options = $data['options'];
		$settings = $data['settings'];
	?>

		<div style='padding-top: 15px'>
		<input type='hidden' value='<?php echo $settings; ?>' name='settingsetid' id='settingsetid' />
		<table>

		<tr>
			<td style='width: 300px;padding-right: 50px'>
				<?php _e('Current Library Name', 'link-library'); ?>
			</td>
			<td>
				<input type="text" id="settingssetname" name="settingssetname" size="40" value="<?php echo $options['settingssetname']; ?>"/>
			</td>
		</tr>
		<tr>
			<td class="lltooltip" title="<?php _e('Leave Empty to see all categories', 'link-library'); ?><br /><br /><?php _e('Enter list of comma-separated', 'link-library'); ?><br /><?php _e('numeric category IDs', 'link-library'); ?><br /><br /><?php _e('To find the IDs, go to the Link Categories admin page, place the mouse above a category name and look for its ID in the address shown in your browsers status bar. For example', 'link-library'); ?>: 2,4,56">
				<?php _e('Categories to be displayed (Empty=All)', 'link-library'); ?>
			</td>
			<td class="lltooltip" title="<?php _e('Leave Empty to see all categories', 'link-library'); ?><br /><br /><?php _e('Enter list of comma-separated', 'link-library'); ?><br /><?php _e('numeric category IDs', 'link-library'); ?><br /><br /><?php _e('For example', 'link-library'); ?>: 2,4,56">
				<input type="text" id="categorylist" name="categorylist" size="40" value="<?php echo $options['categorylist']; ?>"/>
			</td>
		</tr>
		<tr>
			<td class="lltooltip" title="<?php _e('Enter list of comma-separated', 'link-library'); ?><br /><?php _e('numeric category IDs that should not be shown', 'link-library'); ?><br /><br /><?php _e('For example', 'link-library'); ?>: 5,34,43">
				<?php _e('Categories to be excluded', 'link-library'); ?>
			</td>
			<td class="lltooltip" title="<?php _e('Enter list of comma-separated', 'link-library'); ?><br /><?php _e('numeric category IDs that should not be shown', 'link-library'); ?><br /><br /><?php _e('For example', 'link-library'); ?>: 5,34,43">
				<input type="text" id="excludecategorylist" name="excludecategorylist" size="40" value="<?php echo $options['excludecategorylist']; ?>"/>
			</td>
		</tr>
		<tr>
			<td class="lltooltip" title="<?php _e('Only show one category of links at a time', 'link-library'); ?>">
				<?php _e('Only show one category at a time', 'link-library'); ?>
			</td>
			<td class="lltooltip" title="<?php _e('Only show one category of links at a time', 'link-library'); ?>">
				<input type="checkbox" id="showonecatonly" name="showonecatonly" <?php if ($options['showonecatonly']) echo ' checked="checked" '; ?>/>
			</td>
			<td class="lltooltip" title="<?php _e('Select if AJAX should be used to only reload the list of links without reloading the whole page or HTML GET to reload entire page with a new link. The Permalinks option must be enabled for HTML GET + Permalink to work correctly.', 'link-library'); ?>"><?php _e('Switching Method', 'link-library'); ?></td>
			<td>
				<select name="showonecatmode" id="showonecatmode" style="width:200px;">
					<option value="AJAX"<?php if ($options['showonecatmode'] == 'AJAX' || $options['showonecatmode'] == '') { echo ' selected="selected"';} ?>>AJAX</option>
					<option value="HTMLGET"<?php if ($options['showonecatmode'] == 'HTMLGET') { echo ' selected="selected"';} ?>>HTML GET</option>
					<option value="HTMLGETPERM"<?php if ($options['showonecatmode'] == 'HTMLGETPERM') { echo ' selected="selected"';} ?>>HTML GET + Permalink</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<?php _e('Default category to be shown when only showing one at a time (numeric ID)', 'link-library'); ?>
			</td>
			<td>
				<input type="text" id="defaultsinglecat" name="defaultsinglecat" size="4" value="<?php echo $options['defaultsinglecat']; ?>"/>
			</td>
            <td><?php _e('Hide category on start in single cat AJAX mode', 'link-library'); ?></td>
            <td><input type="checkbox" id="nocatonstartup" name="nocatonstartup" <?php if ($options['nocatonstartup']) echo ' checked="checked" '; ?>/></td>
		</tr>
		<tr>
			<td class="lltooltip" title="<?php _e('File path is relative to Link Library plugin directory', 'link-library'); ?>">
				<?php _e('Icon to display when performing AJAX queries', 'link-library'); ?>
			</td>
			<td class="lltooltip" title="<?php _e('File path is relative to Link Library plugin directory', 'link-library'); ?>">
				<input type="text" id="loadingicon" name="loadingicon" size="40" value="<?php if ($options['loadingicon'] == '') {echo '/icons/Ajax-loader.gif';} else {echo strval($options['loadingicon']);} ?>"/>
			</td>
		</tr>
		<tr>
			<td class="lltooltip" title='<?php _e('Only show a limited number of links and add page navigation links', 'link-library'); ?>'>
				<?php _e('Paginate Results', 'link-library'); ?>
			</td>
			<td class="lltooltip" title='<?php _e('Only show a limited number of links and add page navigation links', 'link-library'); ?>'>
				<input type="checkbox" id="pagination" name="pagination" <?php if ($options['pagination']) echo ' checked="checked" '; ?>/>
			</td>
			<td class="lltooltip" title="<?php _e('Number of Links to be Displayed per Page in Pagination Mode', 'link-library'); ?>">
				<?php _e('Links per Page', 'link-library'); ?>
			</td>
			<td class="lltooltip" title="<?php _e('Number of Links to be Displayed per Page in Pagination Mode', 'link-library'); ?>">
				<input type="text" id="linksperpage" name="linksperpage" size="3" value="<?php echo $options['linksperpage']; ?>"/>
			</td>
		</tr>
        <tr>
            <td>
                <?php _e('Pagination Position', 'link-library'); ?>
            </td>
            <td>
                <select name="paginationposition" id="paginationposition" style="width:200px;">
                    <option value="AFTER"<?php if ($options['paginationposition'] == 'AFTER' || $options['paginationposition'] == '') { echo ' selected="selected"';} ?>><?php _e( 'After Links', 'link-library' ); ?></option>
                    <option value="BEFORE"<?php if ($options['paginationposition'] == 'BEFORE') { echo ' selected="selected"';} ?>><?php _e( 'Before Links', 'link-library' ); ?></option>
                </select>
            </td>
			<td>
				<?php _e('Hide Results if Empty', 'link-library'); ?>
			</td>
			<td>
				<input type="checkbox" id="hide_if_empty" name="hide_if_empty" <?php if ($options['hide_if_empty']) echo ' checked="checked" '; ?>/>
			</td>
		</tr>
		<tr>
			<td>
				<?php _e('Enable Permalinks', 'link-library'); ?>
			</td>
			<td>
				<input type="checkbox" id="enablerewrite" name="enablerewrite" <?php if ($options['enablerewrite']) echo ' checked="checked" '; ?>/>
			</td>
			<td>
				<?php _e('Permalinks Page', 'link-library'); ?>
			</td>
			<td>
				<input type="text" id="rewritepage" name="rewritepage" size="40" value="<?php echo $options['rewritepage']; ?>"/>
			</td>
		</tr>
		</table>
		</div>

	<?php }

	function settingssets_categories_meta_box($data) 
	{ 
		$options = $data['options'];
		$settings = $data['settings'];
	?>
		<div style='padding-top:15px'>
		<table>
		<tr>
			<td>
				<?php _e('Results Order', 'link-library'); ?>
			</td>
			<td>
				<select name="order" id="order" style="width:200px;">
					<option value="name"<?php if ($options['order'] == 'name') { echo ' selected="selected"';} ?>><?php _e('Order by Name', 'link-library'); ?></option>
					<option value="id"<?php if ($options['order'] == 'id') { echo ' selected="selected"';} ?>><?php _e('Order by ID', 'link-library'); ?></option>
					<option value="catlist"<?php if ($options['order'] == 'catlist') { echo ' selected="selected"';} ?>><?php _e('Order of categories based on included category list', 'link-library'); ?></option>
					<option value="order"<?php if ($options['order'] == 'order') { echo ' selected="selected"';} ?>><?php _e('Order by', 'link-library'); ?> 'My Link Order' <?php _e('Wordpress Plugin', 'link-library'); ?></option>
				</select>
			</td>
			<td style='width:100px'></td>
			<td style='width:200px'>
				<?php _e('Link Categories Display Format', 'link-library'); ?>
			</td>
			<td>
				<select name="flatlist" id="flatlist" style="width:200px;">
					<option value="table"<?php if ($options['flatlist'] == 'table') { echo ' selected="selected"';} ?>><?php _e('Table', 'link-library'); ?></option>
					<option value="unordered"<?php if ($options['flatlist'] == 'unordered') { echo ' selected="selected"';} ?>><?php _e('Unordered List', 'link-library'); ?></option>
					<option value="dropdown"<?php if ($options['flatlist'] == 'dropdown') { echo ' selected="selected"';} ?>><?php _e('Drop-Down List', 'link-library'); ?></option>					
				</select>
			</td>
		</tr>	
		<tr>
			<td>
				<?php _e('Display link counts', 'link-library'); ?>
			</td>
			<td>
				<input type="checkbox" id="showcatlinkcount" name="showcatlinkcount" <?php if ($options['showcatlinkcount']) echo ' checked="checked" '; ?>/>
			</td>
			<td style='width:100px'></td>
			<td style='width:200px'><?php _e('Display categories with search results', 'link-library'); ?>	</td>
			<td><input type="checkbox" id="showcatonsearchresults" name="showcatonsearchresults" <?php if ($options['showcatonsearchresults']) echo ' checked="checked" '; ?>/></td>
		</tr>
        <tr>
            <td><?php _e('Link Title Content', 'link-library'); ?></td>
            <td>
                <select name="linktitlecontent">

                    <?php $modes = array('linkname' => __('Link Name', 'link-library'), 'linkdesc' => __('Link Description', 'link-library') );

                    // Generate all items of drop-down list
                    foreach ( $modes as $mode => $modename) {
                    ?>
                    <option value="<?php echo $mode; ?>"
                        <?php selected( $options['linktitlecontent'], $mode ); ?>>
                        <?php echo $modename; ?>
                        <?php } ?>
                </select>
            </td>
            <td style='width:100px'></td><td></td><td></td>
        </tr>
		<tr>
			<td class="lltooltip" title="<?php _e('This setting does not apply when selecting My Link Order for the order', 'link-library'); ?>">
				<?php _e('Direction', 'link-library'); ?>
			</td>
			<td class="lltooltip" title="<?php _e('This setting does not apply when selecting My Link Order for the order', 'link-library'); ?>">
				<select name="direction" id="direction" style="width:100px;">
					<option value="ASC"<?php if ($options['direction'] == 'ASC') { echo ' selected="selected"';} ?>><?php _e('Ascending', 'link-library'); ?></option>
					<option value="DESC"<?php if ($options['direction'] == 'DESC') { echo ' selected="selected"';} ?>><?php _e('Descending', 'link-library'); ?></option>
				</select>
			</td>
			<td></td>
			<td class="lltooltip" title="<?php _e('Use [ and ] in the description to perform special actions using HTML such as inserting images instead of < and >', 'link-library'); ?>">
				<?php _e('Show Category Description', 'link-library'); ?>
			</td>
			<td class="lltooltip" title="<?php _e('Use [ and ] in the description to perform special actions using HTML such as inserting images instead of < and >', 'link-library'); ?>">
				<input type="checkbox" id="showcategorydescheaders" name="showcategorydescheaders" <?php if ($options['showcategorydescheaders']) echo ' checked="checked" '; ?>/>
				<span style='margin-left: 17px'><?php _e('Position', 'link-library'); ?>:</span>							
				<select name="catlistdescpos" id="catlistdescpos" style="width:100px;">
					<option value="right"<?php if ($options['catlistdescpos'] == 'right') { echo ' selected="selected"';} ?>><?php _e('Right', 'link-library'); ?></option>
					<option value="left"<?php if ($options['catlistdescpos'] == 'left') { echo ' selected="selected"';} ?>><?php _e('Left', 'link-library'); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<?php _e('Width of Categories Table in Percents', 'link-library'); ?>
			</td>
			<td>
				<input type="text" id="table_width" name="table_width" size="10" value="<?php echo strval($options['table_width']); ?>"/>
			</td>
			<td></td>
			<td class="lltooltip" title='<?php _e('Determines the number of alternating div tags that will be placed before and after each link category', 'link-library'); ?>.<br /><br /><?php _e('These div tags can be used to style of position link categories on the link page', 'link-library'); ?>.'>
				<?php _e('Number of alternating div classes', 'link-library'); ?>
			</td>
			<td class="lltooltip" title='<?php _e('Determines the number of alternating div tags that will be placed before and after each link category', 'link-library'); ?>.<br /><br /><?php _e('These div tags can be used to style of position link categories on the link page', 'link-library'); ?>.'>
				<select name="catlistwrappers" id="catlistwrappers" style="width:200px;">
					<option value="1"<?php if ($options['catlistwrappers'] == 1) { echo ' selected="selected"';} ?>>1</option>
					<option value="2"<?php if ($options['catlistwrappers'] == 2) { echo ' selected="selected"';} ?>>2</option>
					<option value="3"<?php if ($options['catlistwrappers'] == 3) { echo ' selected="selected"';} ?>>3</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<?php _e('Number of columns in Categories Table', 'link-library'); ?>
			</td>
			<td>
				<input type="text" id="num_columns" name="num_columns" size="10" value="<?php echo strval($options['num_columns']); ?>">
			</td>
			<td></td>
			<td>
				<?php _e('First div class name', 'link-library'); ?>
			</td>
			<td>
				<input type="text" id="beforecatlist1" name="beforecatlist1" size="40" value="<?php echo $options['beforecatlist1']; ?>" />
			</td>
		</tr>
		<tr>
			<td>
				<?php _e('Use Div Class or Heading tag around Category Names', 'link-library'); ?>
			</td>
			<td>
				<select name="divorheader" id="divorheader" style="width:200px;">
					<option value="false"<?php if ($options['divorheader'] == false) { echo ' selected="selected"';} ?>><?php _e('Div Class', 'link-library'); ?></option>
					<option value="true"<?php if ($options['divorheader'] == true) { echo ' selected="selected"';} ?>><?php _e('Heading Tag', 'link-library'); ?></option>
				</select>
			</td>
			<td></td>
			<td>
				<?php _e('Second div class name', 'link-library'); ?>
			</td>
			<td>
				<input type="text" id="beforecatlist2" name="beforecatlist2" size="40" value="<?php echo $options['beforecatlist2']; ?>" />
			</td>
		</tr>
		<tr>
			<td class="lltooltip" title="<?php _e('Example div class name: linklistcatname, Example Heading Label: h3', 'link-library'); ?>">
				<?php _e('Div Class Name or Heading label', 'link-library'); ?>
			</td>
			<td  class="lltooltip" title="<?php _e('Example div class name: linklistcatname, Example Heading Label: h3', 'link-library'); ?>">
				<input type="text" id="catnameoutput" name="catnameoutput" size="30" value="<?php echo strval($options['catnameoutput']); ?>"/>
			</td>
			<td></td>
			<td>
				<?php _e('Third div class name', 'link-library'); ?>
			</td>
			<td>
				<input type="text" id="beforecatlist3" name="beforecatlist3" size="40" value="<?php echo $options['beforecatlist3']; ?>" />
			</td>
		</tr>
		<tr>
		<td class="lltooltip" title="<?php _e('Set this address to a page running Link Library to place categories on a different page. Should always be used with the Show One Category at a Time and HTMLGET fetch method.', 'link-library'); ?>">
			<?php _e('Category Target Address', 'link-library'); ?>
		</td>
		<td colspan="4" class="lltooltip" title="<?php _e('Set this address to a page running Link Library to place categories on a different page. Should always be used with the Show One Category at a Time and HTMLGET fetch method.', 'link-library'); ?>">
			<input type="text" id="cattargetaddress" name="cattargetaddress" size="120" value="<?php echo $options['cattargetaddress']; ?>" /></td>
		</tr>
		</table>
		</div>
	<?php }
	
	function settingssets_linkelement_meta_box($data) { 
		$options = $data['options'];
		$settings = $data['settings'];
	?>

		<table>
		<tr>
			<td>
				<?php _e('Link Results Order', 'link-library'); ?>
			</td>
			<td>
				<select name="linkorder" id="linkorder" style="width:250px;">
					<option value="name"<?php if ($options['linkorder'] == 'name') { echo ' selected="selected"';} ?>><?php _e('Order by Name', 'link-library'); ?></option>
					<option value="id"<?php if ($options['linkorder'] == 'id') { echo ' selected="selected"';} ?>><?php _e('Order by ID', 'link-library'); ?></option>
					<option value="order"<?php if ($options['linkorder'] == 'order') { echo ' selected="selected"';} ?>><?php _e('Order set by ', 'link-library'); ?>'My Link Order' <?php _e('Wordpress Plugin', 'link-library'); ?></option>
					<option value="random"<?php if ($options['linkorder'] == 'random') { echo ' selected="selected"';} ?>><?php _e('Order randomly', 'link-library'); ?></option>
					<option value="date"<?php if ($options['linkorder'] == 'date') { echo ' selected="selected"';} ?>><?php _e('Order by updated date', 'link-library'); ?></option>                     
				</select>
			</td>
			<td style='width:100px'></td>
			<td class="lltooltip" title="<?php _e('Use [ and ] in the description to perform special actions using HTML such as inserting images instead of < and >', 'link-library'); ?>">
				<?php _e('Show Category Description', 'link-library'); ?>
			</td>
			<td class="lltooltip" title="<?php _e('Use [ and ] in the description to perform special actions using HTML such as inserting images instead of < and >', 'link-library'); ?>">
				<input type="checkbox" id="showcategorydesclinks" name="showcategorydesclinks" <?php if ($options['showcategorydesclinks']) echo ' checked="checked" '; ?>/>
				<span style='margin-left: 17px'><?php _e('Position', 'link-library'); ?>:</span>
				<select name="catdescpos" id="catdescpos" style="width:100px;">
					<option value="right"<?php if ($options['catdescpos'] == 'right') { echo ' selected="selected"';} ?>><?php _e('Right', 'link-library'); ?></option>
					<option value="left"<?php if ($options['catdescpos'] == 'left') { echo ' selected="selected"';} ?>><?php _e('Left', 'link-library'); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td><?php _e('List Featured Links ahead of Regular Links', 'link-library'); ?></td>
			<td><input type="checkbox" id="featuredfirst" name="featuredfirst" <?php if ($options['featuredfirst']) echo ' checked="checked" '; ?>/></td>
		</tr>
		<tr>
			<td class="lltooltip" title='<?php _e('Except for My Link Order mode', 'link-library'); ?>'>
				<?php _e('Direction', 'link-library'); ?>
			</td>
			<td class="lltooltip" title='<?php _e('Except for My Link Order mode', 'link-library'); ?>'>
				<select name="linkdirection" id="linkdirection" style="width:200px;">
					<option value="ASC"<?php if ($options['linkdirection'] == 'ASC') { echo ' selected="selected"';} ?>><?php _e('Ascending', 'link-library'); ?></option>
					<option value="DESC"<?php if ($options['linkdirection'] == 'DESC') { echo ' selected="selected"';} ?>><?php _e('Descending', 'link-library'); ?></option>
				</select>
			</td>
			<td></td>
			<td class="lltooltip" title="<?php _e('Leave empty to show all results', 'link-library'); ?>">
				<?php _e('Max number of links to display', 'link-library'); ?>
			</td>
			<td class="lltooltip" title="<?php _e('Leave empty to show all results', 'link-library'); ?>">
				<input type="text" id="maxlinks" name="maxlinks" size="4" value="<?php echo $options['maxlinks']; ?>"/>
			</td>
		</tr>	
		<tr>
			<td class="lltooltip" title="<?php _e('Sets default link target window, does not override specific targets set in links', 'link-library'); ?>">
				<?php _e('Link Target', 'link-library'); ?>
			</td>
			<td class="lltooltip" title="<?php _e('Sets default link target window, does not override specific targets set in links', 'link-library'); ?>">
				<input type="text" id="linktarget" name="linktarget" size="40" value="<?php echo $options['linktarget']; ?>"/>
			</td>
			<td></td>
			<td>
				<?php _e('Link Display Format', 'link-library'); ?>
			</td>
			<td>
				<select name="displayastable" id="displayastable" style="width:200px;">
					<option value="true"<?php if ($options['displayastable'] == true) { echo ' selected="selected"';} ?>><?php _e('Table', 'link-library'); ?></option>
					<option value="false"<?php if ($options['displayastable'] == false) { echo ' selected="selected"';} ?>><?php _e('Unordered List', 'link-library'); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<?php _e('Show Column Headers', 'link-library'); ?>
			</td>
			<td>
				<input type="checkbox" id="showcolumnheaders" name="showcolumnheaders" <?php if ($options['showcolumnheaders']) echo ' checked="checked" '; ?>/>
			</td>
			<td></td>
			<td>
				<?php _e('Link Column Header', 'link-library'); ?>
			</td>
			<td>
				<input type="text" id="linkheader" name="linkheader" size="40" value="<?php echo $options['linkheader']; ?>"/>
			</td>
		</tr>	
		<tr>
			<td>
				<?php _e('Description Column Header', 'link-library'); ?>
			</td>
			<td>
				<input type="text" id="descheader" name="descheader" size="40" value="<?php echo $options['descheader']; ?>"/>
			</td>
			<td></td>
			<td>
				<?php _e('Notes Column Header', 'link-library'); ?>
			</td>
			<td>
				<input type="text" id="notesheader" name="notesheader" size="40" value="<?php echo $options['notesheader']; ?>"/>
			</td>
		</tr>
		<tr>
			<td>
				<?php _e('Hide Category Names', 'link-library'); ?>
			</td>
			<td>
				<input type="checkbox" id="hidecategorynames" name="hidecategorynames" <?php if ($options['hidecategorynames'] == true) echo ' checked="checked" '; ?>/>
			</td>
			<td></td>
			<td>
				<?php _e('Show Hidden Links', 'link-library'); ?>
			</td>
			<td>
				<input type="checkbox" id="showinvisible" name="showinvisible" <?php if ($options['showinvisible'] == true) echo ' checked="checked" '; ?>/>
			</td>
		</tr>	
		<tr>
			<td class="lltooltip" title='<?php _e('Need to be active for Link Categories to work', 'link-library'); ?>'>
				<?php _e('Embed HTML anchors', 'link-library'); ?>
			</td>
			<td class="lltooltip" title='<?php _e('Need to be active for Link Categories to work', 'link-library'); ?>'>
				<input type="checkbox" id="catanchor" name="catanchor" <?php if ($options['catanchor']) echo ' checked="checked" '; ?>/>
			</td>
		</tr>
		</table>
	<?php }

	function settingssets_subfieldtable_meta_box($data) { 
		$options = $data['options'];
		$settings = $data['settings'];
	?>

			<?php _e('Arrange the items below via drag-and-drop to order the various Link Library elements.', 'link-library'); ?><br /><br />
			<ul id="sortable">
			<?php if ($options['dragndroporder'] == '') $dragndroporder = '1,2,3,4,5,6,7,8,9,10'; else $dragndroporder = $options['dragndroporder'];
				  $dragndroparray = explode(',', $dragndroporder);
				  if ($dragndroparray)
				  {
					foreach ($dragndroparray as $arrayelements) {
						switch ($arrayelements) {
							case 1: ?>
								<li id="1" style='background-color: #1240ab'><?php _e('Image', 'link-library'); ?></li>
							<?php break;
							case 2: ?>
								<li id="2" style='background-color: #4671d5'><?php _e('Name', 'link-library'); ?></li>
							<?php break;
							case 3: ?>
								<li id="3" style='background-color: #39e639'><?php _e('Date', 'link-library'); ?></li>
							<?php break;
							case 4: ?>
								<li id="4" style='background-color: #009999'><?php _e('Desc', 'link-library'); ?></li>
							<?php break;
							case 5: ?>
								<li id="5" style='background-color: #00cc00'><?php _e('Notes', 'link-library'); ?></li>
							<?php break;
							case 6: ?>
								<li id="6" style='background-color: #008500'><?php _e('RSS', 'link-library'); ?></li>
							<?php break;
							case 7: ?>
								<li id="7" style='background-color: #5ccccc'><?php _e('Web Link', 'link-library'); ?></li>
							<?php break;
							case 8: ?>
								<li id="8" style='background-color: #6c8cd5'><?php _e('Phone', 'link-library'); ?></li>
							<?php break;
							case 9: ?>
								<li id="9" style='background-color: #67e667'><?php _e('E-mail', 'link-library'); ?></li>
							<?php break;
							case 10: ?>
								<li id="10" style='background-color: #33cccc'><?php _e('Hits', 'link-library'); ?></li>
							<?php break;
							case 11: ?>
								<li id="11" style='background-color: #33cc00'><?php _e('Rating', 'link-library'); ?></li>
							<?php break;
							case 12: ?>
								<li id="12" style='background-color: #33ccff'><?php _e('Large Desc', 'link-library'); ?></li>
							<?php break;
						}
					}
				}
			?>
			</ul>
			<input type="hidden" id="dragndroporder" name="dragndroporder" size="60" value="<?php echo $options['dragndroporder']; ?>"/>
			<br />
			<table class='widefat' style='width: 1000px;margin:15px 5px 10px 0px;clear:none;background-color:#F1F1F1;background-image: linear-gradient(to top, #ECECEC, #F9F9F9);background-position:initial initial;background-repeat: initial initial'>
				<thead>
					<th style='width: 100px'></th>
					<th style='width: 40px'><?php _e('Display', 'link-library'); ?></th>
					<th style='width: 80px'><?php _e('Before', 'link-library'); ?></th>
					<th style='width: 80px'><?php _e('After', 'link-library'); ?></th>
					<th style='width: 80px'><?php _e('Additional Details', 'link-library'); ?></th>
					<th style='width: 80px'><?php _e('Link Source', 'link-library'); ?></th>
				</thead>
				<tr>
					<td class="lltooltip" title='<?php _e('This column allows for the output of text/code before a number of links determined by the Display field', 'link-library'); ?>'><?php _e('Intermittent Before Link', 'link-library'); ?></td>
					<td style='background: #FFF' class="lltooltip" title='<?php _e('Frequency of additional output before and after complete link group', 'link-library'); ?>'>
						<input type="text" id="linkaddfrequency" name="linkaddfrequency" size="10" value="<?php echo strval($options['linkaddfrequency']); ?>"/>
					</td>
					<td style='background: #FFF' class="lltooltip" title='<?php _e('Output before complete link group (link, notes, desc, etc...)', 'link-library'); ?>'>
						<input type="text" id="addbeforelink" name="addbeforelink" size="22" value="<?php echo stripslashes($options['addbeforelink']); ?>"/>
					</td>
					<td style='background: #FFF'></td>
					<td style='background: #FFF'></td>
					<td style='background: #FFF'></td>
				</tr>
				<tr>
					<td class="lltooltip" title='<?php _e('This column allows for the output of text/code before each link', 'link-library'); ?>'><?php _e('Before Link', 'link-library'); ?></td>
					<td style='background: #FFF'></td>
					<td style='background: #FFF' class="lltooltip" title='<?php _e('Output before complete link group (link, notes, desc, etc...)', 'link-library'); ?>'>
						<input type="text" id="beforeitem" name="beforeitem" size="22" value="<?php echo stripslashes($options['beforeitem']); ?>"/>
					</td>
					<td style='background: #FFF'></td>
					<td style='background: #FFF'></td>
					<td style='background: #FFF'></td>
				</tr>
				<?php if ($options['dragndroporder'] == '') $dragndroporder = '1,2,3,4,5,6,7,8,9,10'; else $dragndroporder = $options['dragndroporder'];
				  $dragndroparray = explode(',', $dragndroporder);
				  if ($dragndroparray)
				  {
					foreach ($dragndroparray as $arrayelements) {
						switch ($arrayelements) {
							case 1: /* -------------------------------- Link Image -------------------------------------------*/ ?>	
				<tr>
					<td style='background-color: #1240ab; color: #fff' class="lltooltip" title='<?php _e('This column allows for the output of text/code before each link image', 'link-library'); ?>'><?php _e('Image', 'link-library'); ?></td>
					<td style='text-align:center;background: #FFF'>
						<input type="checkbox" id="show_images" name="show_images" <?php if ($options['show_images']) echo ' checked="checked" '; ?>/>
					</td>
					<td style='background: #FFF' class="lltooltip" title='<?php _e('Code/Text to be displayed before each link image', 'link-library'); ?>'>
						<input type="text" id="beforeimage" name="beforeimage" size="22" value="<?php echo stripslashes($options['beforeimage']); ?>"/>
					</td>
					<td style='background: #FFF' class="lltooltip" title='<?php _e('Code/Text to be displayed after each link image', 'link-library'); ?>'>
						<input type="text" id="afterimage" name="afterimage" size="22" value="<?php echo stripslashes($options['afterimage']); ?>"/>
					</td>
					<td style='background: #FFF' class="lltooltip" title='<?php _e('CSS Class to be assigned to link image', 'link-library'); ?>'>
						<input type="text" id="imageclass" name="imageclass" size="22" value="<?php echo $options['imageclass']; ?>"/>
					</td>
					<td style='background: #FFF'>
						<select name="sourceimage" id="sourceimage" style="width:200px;">
							<option value="primary"<?php if ($options['sourceimage'] == "primary") { echo ' selected="selected"';} ?>><?php _e('Primary', 'link-library'); ?></option>
							<option value="secondary"<?php if ($options['sourceimage'] == "secondary") { echo ' selected="selected"';} ?>><?php _e('Secondary', 'link-library'); ?></option>
						</select>
					</td>
				</tr>
				<?php break;
				case 2: /* -------------------------------- Link Name -------------------------------------------*/ ?>
				<tr>
					<td style='background-color: #4671d5; color: #fff' class="lltooltip" title='<?php _e('This column allows for the output of text/code before and after each link name', 'link-library'); ?>'><?php _e('Link Name', 'link-library'); ?></td>
					<td style='text-align:center;background: #FFF'>
						<input type="checkbox" id="showname" name="showname" <?php if ($options['showname'] == true) echo ' checked="checked" '; ?>/>
					</td>
					<td style='background: #FFF' class="lltooltip" title='<?php _e('Code/Text to be displayed before each link', 'link-library'); ?>'>
						<input type="text" id="beforelink" name="beforelink" size="22" value="<?php echo stripslashes($options['beforelink']); ?>"/>
					</td>
					<td style='background: #FFF' class="lltooltip" title='<?php _e('Code/Text to be displayed after each link', 'link-library'); ?>'>
						<input type="text" id="afterlink" name="afterlink" size="22" value="<?php echo stripslashes($options['afterlink']); ?>"/>
					</td>
					<td style='background: #FFF'></td>
					<td style='background: #FFF'>
						<select name="sourcename" id="sourcename" style="width:200px;">
							<option value="primary"<?php if ($options['sourcename'] == "primary") { echo ' selected="selected"';} ?>><?php _e('Primary', 'link-library'); ?></option>
							<option value="secondary"<?php if ($options['sourcename'] == "secondary") { echo ' selected="selected"';} ?>><?php _e('Secondary', 'link-library'); ?></option>
						</select>
					</td>
				</tr>
				<?php break;
				case 3: /* -------------------------------- Link Date -------------------------------------------*/ ?>
				<tr>
					<td style='background-color: #39e639; color:#fff' class="lltooltip" title='<?php _e('This column allows for the output of text/code before and after each link date stamp', 'link-library'); ?>'><?php _e('Link Date', 'link-library'); ?></td>
					<td style='background: #FFF;text-align:center' class="lltooltip" title='<?php _e('Check to display link date', 'link-library'); ?>'>
						<input type="checkbox" id="showdate" name="showdate" <?php if ($options['showdate']) echo ' checked="checked" '; ?>/>
					</td>
					<td style='background: #FFF' class="lltooltip" title='<?php _e('Code/Text to be displayed before each date', 'link-library'); ?>'>
						<input type="text" id="beforedate" name="beforedate" size="22" value="<?php echo stripslashes($options['beforedate']); ?>"/>
					</td>
					<td style='background: #FFF' class="lltooltip" title='<?php _e('Code/Text to be displayed after each date', 'link-library'); ?>'>
						<input type="text" id="afterdate" name="afterdate" size="22" value="<?php echo stripslashes($options['afterdate']); ?>"/>
					</td>
					<td style='background: #FFF'></td>
					<td style='background: #FFF'></td>
				</tr>
				<?php break;
				case 4: /* -------------------------------- Link Description -------------------------------------------*/ ?>
				<tr>
					<td style='background-color: #009999;color:#fff' class="lltooltip" title='<?php _e('This column allows for the output of text/code before and after each link description', 'link-library'); ?>'><?php _e('Link Description', 'link-library'); ?></td>
					<td style='background: #FFF;text-align: center' class="lltooltip" title='<?php _e('Check to display link descriptions', 'link-library'); ?>'>
						<input type="checkbox" id="showdescription" name="showdescription" <?php if ($options['showdescription']) echo ' checked="checked" '; ?>/>
					</td>
					<td style='background: #FFF' class="lltooltip" title='<?php _e('Code/Text to be displayed before each description', 'link-library'); ?>'>
						<input type="text" id="beforedesc" name="beforedesc" size="22" value="<?php echo stripslashes($options['beforedesc']); ?>"/>
					</td>
					<td style='background: #FFF' class="lltooltip" title='<?php _e('Code/Text to be displayed after each description', 'link-library'); ?>'>
						<input type="text" id="afterdesc" name="afterdesc" size="22" value="<?php echo stripslashes($options['afterdesc']); ?>"/>
					</td>
					<td style='background: #FFF'></td>
					<td style='background: #FFF'></td>
				</tr>
				<?php break;
				case 5: /* -------------------------------- Link Notes -------------------------------------------*/ ?>
				<tr>
					<td style='background-color: #00cc00;color:#fff' class="lltooltip" title='<?php _e('This column allows for the output of text/code before and after each link notes', 'link-library'); ?>'><?php _e('Link Notes', 'link-library'); ?></td>
					<td style='background: #FFF;text-align: center' class="lltooltip" title='<?php _e('Check to display link notes', 'link-library'); ?>'>
						<input type="checkbox" id="shownotes" name="shownotes" <?php if ($options['shownotes']) echo ' checked="checked" '; ?>/>
					</td>
					<td style='background: #FFF' class="lltooltip" title='<?php _e('Code/Text to be displayed before each note', 'link-library'); ?>'>
						<input type="text" id="beforenote" name="beforenote" size="22" value="<?php echo stripslashes($options['beforenote']); ?>"/>
					</td>
					<td style='background: #FFF' class="lltooltip" title='<?php _e('Code/Text to be displayed after each note', 'link-library'); ?>'>
						<input type="text" id="afternote" name="afternote" size="22" value="<?php echo stripslashes($options['afternote']); ?>"/>
					</td>
					<td style='background: #FFF'></td>
					<td style='background: #FFF'></td>
				</tr>
				<?php break;
				case 6: /* -------------------------------- Link RSS Icons -------------------------------------------*/ ?>
				<tr>
					<td style='background-color: #008500;color:#fff' class="lltooltip" title='<?php _e('This column allows for the output of text/code before and after the RSS icons', 'link-library'); ?>'><?php _e('RSS Icons', 'link-library'); ?></td>
					<td style='text-align:center;background: #FFF'>
						<?php _e('See below', 'link-library'); ?>
					</td>
					<td style='background: #FFF' class="lltooltip" title='<?php _e('Code/Text to be displayed before RSS Icons', 'link-library'); ?>'>
						<input type="text" id="beforerss" name="beforerss" size="22" value="<?php echo stripslashes($options['beforerss']); ?>"/>
					</td>
					<td  style='background: #FFF' class="lltooltip" title='<?php _e('Code/Text to be displayed after RSS Icons', 'link-library'); ?>'>
						<input type="text" id="afterrss" name="afterrss" size="22" value="<?php echo stripslashes($options['afterrss']); ?>"/>
					</td>
					<td style='background: #FFF'></td>
					<td style='background: #FFF'></td>
				</tr>
				<?php break;
				case 7: /* -------------------------------- Web Link -------------------------------------------*/ ?>
				<tr>
					<td style='background-color: #5ccccc;color:#fff' class="lltooltip" title='<?php _e('This column allows for the output of text/code before and after the Web Link', 'link-library'); ?>'><?php _e('Web Link', 'link-library'); ?></td>
					<td style='text-align:center;background: #FFF'>
						<select name="displayweblink" id="displayweblink" style="width:80px;">
							<option value="false"<?php if ($options['displayweblink'] == "false") { echo ' selected="selected"';} ?>><?php _e('False', 'link-library'); ?></option>
							<option value="address"<?php if ($options['displayweblink'] == "address") { echo ' selected="selected"';} ?>><?php _e('Web Address', 'link-library'); ?></option>
							<option value="label"<?php if ($options['displayweblink'] == "label") { echo ' selected="selected"';} ?>><?php _e('Label', 'link-library'); ?></option>
						</select>
					</td>
					<td style='background: #FFF' class="lltooltip" title='<?php _e('Code/Text to be displayed before Web Link', 'link-library'); ?>'>
						<input type="text" id="beforeweblink" name="beforeweblink" size="22" value="<?php echo stripslashes($options['beforeweblink']); ?>"/>
					</td>
					<td  style='background: #FFF' class="lltooltip" title='<?php _e('Code/Text to be displayed after Web Link', 'link-library'); ?>'>
						<input type="text" id="afterweblink" name="afterweblink" size="22" value="<?php echo stripslashes($options['afterweblink']); ?>"/>
					</td>
					<td style='background: #FFF' class="lltooltip" title='<?php _e('Text Label that the web link will be assigned to.', 'link-library'); ?>'>
						<input type="text" id="weblinklabel" name="weblinklabel" size="22" value="<?php echo stripslashes($options['weblinklabel']); ?>"/>
					</td>
					<td style='background: #FFF' class="lltooltip" title='<?php _e('Select which link address will be displayed / used for link', 'link-library'); ?>'>
						<select name="sourceweblink" id="sourceweblink" style="width:200px;">
							<option value="primary"<?php if ($options['sourceweblink'] == "primary") { echo ' selected="selected"';} ?>><?php _e('Primary', 'link-library'); ?></option>
							<option value="secondary"<?php if ($options['sourceweblink'] == "secondary") { echo ' selected="selected"';} ?>><?php _e('Secondary', 'link-library'); ?></option>
						</select>
					</td>
				</tr>
				<?php break;
				case 8: /* -------------------------------- Telephone -------------------------------------------*/ ?>
				<tr>
					<td style='background-color: #6c8cd5;color:#fff' class="lltooltip" title='<?php _e('This column allows for the output of text/code before and after the Telephone Number', 'link-library'); ?>'><?php _e('Telephone', 'link-library'); ?></td>
					<td style='text-align:center;background: #FFF'>
						<select name="showtelephone" id="showtelephone" style="width:80px;">
							<option value="false"<?php if ($options['showtelephone'] == "false") { echo ' selected="selected"';} ?>><?php _e('False', 'link-library'); ?></option>
							<option value="plain"<?php if ($options['showtelephone'] == "plain") { echo ' selected="selected"';} ?>><?php _e('Plain Text', 'link-library'); ?></option>
							<option value="link"<?php if ($options['showtelephone'] == "link") { echo ' selected="selected"';} ?>><?php _e('Link', 'link-library'); ?></option>
							<option value="label"<?php if ($options['showtelephone'] == "label") { echo ' selected="selected"';} ?>><?php _e('Label', 'link-library'); ?></option>
						</select>
					</td>
					<td style='background: #FFF' class="lltooltip" title='<?php _e('Code/Text to be displayed before Telephone Number', 'link-library'); ?>'>
						<input type="text" id="beforetelephone" name="beforetelephone" size="22" value="<?php echo stripslashes($options['beforetelephone']); ?>"/>
					</td>
					<td  style='background: #FFF' class="lltooltip" title='<?php _e('Code/Text to be displayed after Telephone Number', 'link-library'); ?>'>
						<input type="text" id="aftertelephone" name="aftertelephone" size="22" value="<?php echo stripslashes($options['aftertelephone']); ?>"/>
					</td>
					<td style='background: #FFF' class="lltooltip" title='<?php _e('Text Label that the telephone will be assigned to.', 'link-library'); ?>'>
						<input type="text" id="telephonelabel" name="telephonelabel" size="22" value="<?php echo stripslashes($options['telephonelabel']); ?>"/>
					</td>
					<td style='background: #FFF' class="lltooltip" title='<?php _e('Select which link address will be displayed / used for link', 'link-library'); ?>'>
						<select name="sourcetelephone" id="sourcetelephone" style="width:200px;">
							<option value="primary"<?php if ($options['sourcetelephone'] == "primary") { echo ' selected="selected"';} ?>><?php _e('Primary', 'link-library'); ?></option>
							<option value="secondary"<?php if ($options['sourcetelephone'] == "secondary") { echo ' selected="selected"';} ?>><?php _e('Secondary', 'link-library'); ?></option>
						</select>
					</td>
				</tr>
				<?php break;
				case 9: /* -------------------------------- E-mail -------------------------------------------*/ ?>
				<tr>
					<td style='background-color: #67e667;color:#fff' class="lltooltip" title='<?php _e('This column allows for the output of text/code before and after the E-mail', 'link-library'); ?>'><?php _e('E-mail', 'link-library'); ?></td>
					<td style='text-align:center;background: #FFF'>
						<select name="showemail" id="showemail" style="width:80px;">
							<option value="false"<?php if ($options['showemail'] == "false") { echo ' selected="selected"';} ?>><?php _e('False', 'link-library'); ?></option>
							<option value="plain"<?php if ($options['showemail'] == "plain") { echo ' selected="selected"';} ?>><?php _e('Plain Text', 'link-library'); ?></option>
							<option value="mailto"<?php if ($options['showemail'] == "mailto") { echo ' selected="selected"';} ?>><?php _e('MailTo Link', 'link-library'); ?></option>
							<option value="mailtolabel"<?php if ($options['showemail'] == "mailtolabel") { echo ' selected="selected"';} ?>><?php _e('MailTo Link with Label', 'link-library'); ?></option>
							<option value="command"<?php if ($options['showemail'] == "command") { echo ' selected="selected"';} ?>><?php _e('Formatted Command', 'link-library'); ?></option>
							<option value="commandlabel"<?php if ($options['showemail'] == "commandlabel") { echo ' selected="selected"';} ?>><?php _e('Formatted Command with Labels', 'link-library'); ?></option>
						</select>
					</td>
					<td style='background: #FFF' class="lltooltip" title='<?php _e('Code/Text to be displayed before E-mail', 'link-library'); ?>'>
						<input type="text" id="beforeemail" name="beforeemail" size="22" value="<?php echo stripslashes($options['beforeemail']); ?>"/>
					</td>
					<td  style='background: #FFF' class="lltooltip" title='<?php _e('Code/Text to be displayed after E-mail', 'link-library'); ?>'>
						<input type="text" id="afteremail" name="afteremail" size="22" value="<?php echo stripslashes($options['afteremail']); ?>"/>
					</td>
					<td style='background: #FFF' class="lltooltip" title='<?php _e('Text Label that the e-mail will be assigned to represent the e-mail link.', 'link-library'); ?>'>
						<input type="text" id="emaillabel" name="emaillabel" size="22" value="<?php echo stripslashes($options['emaillabel']); ?>"/>
					</td>
					<td style='background: #FFF' class="lltooltip" title='<?php _e('Command that the e-mail will be embedded in. In the case of a command, use the symbols #email and #company to indicate the position where these elements should be inserted.', 'link-library'); ?>'>
						<input type="text" id="emailcommand" name="emailcommand" size="22" value="<?php echo stripslashes($options['emailcommand']); ?>"/>
					</td>
				</tr>
				<?php break;
				case 10: /* -------------------------------- Link Hits -------------------------------------------*/ ?>
				<tr>
					<td style='background-color: #33cccc;color:#fff' class="lltooltip" title='<?php _e('This column allows for the output of text/code before and after Link Hits', 'link-library'); ?>'><?php _e('Link Hits', 'link-library'); ?></td>
					<td style='text-align:center;background: #FFF'>
						<input type="checkbox" id="showlinkhits" name="showlinkhits" <?php if ($options['showlinkhits']) echo ' checked="checked" '; ?>/>
					</td>
					<td style='background: #FFF' class="lltooltip" title='<?php _e('Code/Text to be displayed before Link Hits', 'link-library'); ?>'>
						<input type="text" id="beforelinkhits" name="beforelinkhits" size="22" value="<?php echo stripslashes($options['beforelinkhits']); ?>"/>
					</td>
					<td  style='background: #FFF' class="lltooltip" title='<?php _e('Code/Text to be displayed after Link Hits', 'link-library'); ?>'>
						<input type="text" id="afterlinkhits" name="afterlinkhits" size="22" value="<?php echo stripslashes($options['afterlinkhits']); ?>"/>
					</td>
					<td style='background: #FFF'></td>
					<td style='background: #FFF'></td>
				</tr>
				<?php break;
				case 11: /* -------------------------------- Link Rating -------------------------------------------*/ ?>
				<tr>
					<td style='background-color: #33cc00;color:#fff' class="lltooltip" title='<?php _e('This column allows for the output of text/code before and after the Link Rating', 'link-library'); ?>'><?php _e('Link Rating', 'link-library'); ?></td>
					<td style='text-align:center;background: #FFF'>
						<input type="checkbox" id="showrating" name="showrating" <?php if ($options['showrating']) echo ' checked="checked" '; ?>/>
					</td>
					<td style='background: #FFF' class="lltooltip" title='<?php _e('Code/Text to be displayed before Link Rating', 'link-library'); ?>'>
						<input type="text" id="beforelinkrating" name="beforelinkrating" size="22" value="<?php echo stripslashes($options['beforelinkrating']); ?>"/>
					</td>
					<td  style='background: #FFF' class="lltooltip" title='<?php _e('Code/Text to be displayed after Link Rating', 'link-library'); ?>'>
						<input type="text" id="afterlinkrating" name="afterlinkrating" size="22" value="<?php echo stripslashes($options['afterlinkrating']); ?>"/>
					</td>
					<td style='background: #FFF'></td>
					<td style='background: #FFF'></td>
				</tr>
				<?php break;
				case 12: /* -------------------------------- Large Description -------------------------------------------*/ ?>
				<tr>
					<td style='background-color: #33ccff;color:#fff' class="lltooltip" title='<?php _e('This column allows for the output of text/code before and after the Link Large Description', 'link-library'); ?>'><?php _e('Link Large Description', 'link-library'); ?></td>
					<td style='text-align:center;background: #FFF'>
						<input type="checkbox" id="showlargedescription" name="showlargedescription" <?php if ($options['showlargedescription']) echo ' checked="checked" '; ?>/>
					</td>
					<td style='background: #FFF' class="lltooltip" title='<?php _e('Code/Text to be displayed before Link Large Description', 'link-library'); ?>'>
						<input type="text" id="beforelargedescription" name="beforelargedescription" size="22" value="<?php echo stripslashes($options['beforelargedescription']); ?>"/>
					</td>
					<td  style='background: #FFF' class="lltooltip" title='<?php _e('Code/Text to be displayed after Link Large Description', 'link-library'); ?>'>
						<input type="text" id="afterlargedescription" name="afterlargedescription" size="22" value="<?php echo stripslashes($options['afterlargedescription']); ?>"/>
					</td>
					<td style='background: #FFF'></td>
					<td style='background: #FFF'></td>
				</tr>
				<?php break;				
						}
					}
				}
				?>
				<tr>
					<td class="lltooltip" title='<?php _e('This column allows for the output of text/code after each link', 'link-library'); ?>'><?php _e('After Link Block', 'link-library'); ?></td>
					<td style='background: #FFF'></td>
					<td style='background: #FFF'></td>
					<td style='background: #FFF' class="lltooltip" title='<?php _e('Output after complete link group (link, notes, desc, etc...)', 'link-library'); ?>'>
						<input type="text" id="afteritem" name="afteritem" size="22" value="<?php echo stripslashes($options['afteritem']); ?>"/>
					</td>
					<td style='background: #FFF'></td>
					<td style='background: #FFF'></td>
				</tr>
				<tr>
					<td class="lltooltip" title='<?php _e('This column allows for the output of text/code after a number of links determined in the first column', 'link-library'); ?>'><?php _e('Intermittent After Link', 'link-library'); ?></td>
					<td style='background: #FFF'></td>
					<td style='background: #FFF'></td>
					<td style='background: #FFF'>
						<input type="text" id="addafterlink" name="addafterlink" size="22" value="<?php echo stripslashes($options['addafterlink']); ?>"/>
					</td>
					<td style='background: #FFF'></td>
					<td style='background: #FFF'></td>
				</tr>
			</table>
		</table>
		<br />
		<table>
		<tr>
			<td style='width:150px'>
				<?php _e('Show Link Updated Flag', 'link-library'); ?>
			</td>
			<td style='width:75px;padding:0px 20px 0px 20px'>
				<input type="checkbox" id="showupdated" name="showupdated" <?php if ($options['showupdated']) echo ' checked="checked" '; ?>/>
			</td>
			<td style='width:20px'>
			</td>
			<td>
				<?php _e('Convert [] to &lt;&gt; in Link Description and Notes', 'link-library'); ?>
			</td>
			<td style='width:75px;padding:0px 20px 0px 20px'>
				<input type="checkbox" id="use_html_tags" name="use_html_tags" <?php if ($options['use_html_tags']) echo ' checked="checked" '; ?>/>
			</td>
		</tr>
		<tr>
			<td>
				<?php _e('Add nofollow tag to outgoing links', 'link-library'); ?>
			</td>
			<td style='width:75px;padding:0px 20px 0px 20px'>
				<input type="checkbox" id="nofollow" name="nofollow" <?php checked( $options['nofollow'] ); ?>/>
			</td>
			<td></td>
			<td>
				<?php _e('Show edit links when logged in as editor or administrator', 'link-library'); ?>
			</td>
			<td style='width:75px;padding:0px 20px 0px 20px'>
				<input type="checkbox" id="showadmineditlinks" name="showadmineditlinks" <?php if ($options['showadmineditlinks']) echo ' checked="checked" '; ?>/>
			</td>
		</tr>
		<tr>
			<td>
				<?php _e('Show link name when no image is assigned', 'link-library'); ?>
			</td>
			<td style='width:75px;padding:0px 20px 0px 20px'>
				<input type="checkbox" id="shownameifnoimage" name="shownameifnoimage" <?php if ($options['shownameifnoimage']) echo ' checked="checked" '; ?>/>
			</td>
		</tr>
		</table>

	<?php }
    
    function settingssets_linkpopup_meta_box($data) { 
		$options = $data['options'];
		$settings = $data['settings'];
	?>
        <table>
            <tr>
                <td style='width:175px;'><?php _e( 'Enable link Pop-Ups', 'link-library' ); ?></td>
                <td style='width:75px;padding-right:20px'>
                    <input type="checkbox" id="enable_link_popup" name="enable_link_popup" <?php ( isset( $options['enable_link_popup'] ) ? checked( $options['enable_link_popup'] ) : '' ); ?>/>
                </td>
                <td><?php _e('Pop-Up Width', 'link-library' ); ?></td>
                <td><input type="text" id="popup_width" name="popup_width" size="4" value="<?php if ( !isset( $options['popup_width'] ) || $options['popup_width'] == '') echo '300'; else echo strval($options['popup_width']); ?>"/></td>
                <td><?php _e('Pop-Up Height', 'link-library' ); ?></td>
                <td><input type="text" id="popup_height" name="popup_height" size="4" value="<?php if ( !isset( $options['popup_height'] ) || $options['popup_height'] == '') echo '400'; else echo strval($options['popup_height']); ?>"/></td>
            </tr>
            <tr>
                <td><?php _e( 'Dialog contents', 'link-library' ); ?></td>
                <td colspan="5"><textarea id="link_popup_text" name="link_popup_text" cols="80" /><?php echo ( isset( $options['link_popup_text'] ) ? stripslashes($options['link_popup_text']) : '' ); ?></textarea></td>
            </tr>
        </table>
    <?php }

	function settingssets_rssconfig_meta_box($data) { 
		$options = $data['options'];
		$settings = $data['settings'];
	?>

		<table>
		<tr>
			<td>
				<?php _e('Show RSS Link using Text', 'link-library'); ?>
			</td>
			<td style='width:75px;padding-right:20px'>
				<input type="checkbox" id="show_rss" name="show_rss" <?php if ($options['show_rss']) echo ' checked="checked" '; ?>/>
			</td>
			<td>
				<?php _e('Show RSS Link using Standard Icon', 'link-library'); ?>
			</td>
			<td style='width:75px;padding-right:20px'>
				<input type="checkbox" id="show_rss_icon" name="show_rss_icon" <?php if ($options['show_rss_icon']) echo ' checked="checked" '; ?>/>
			</td>
			<td></td><td style='width:75px;padding-right:20px'></td>
		</tr>
		<tr>
			<td>
				<?php _e('Show RSS Preview Link', 'link-library'); ?>
			</td>
			<td>
				<input type="checkbox" id="rsspreview" name="rsspreview" <?php if ($options['rsspreview']) echo ' checked="checked" '; ?>/>
			</td>
			<td>
				<?php _e('Number of articles shown in RSS Preview', 'link-library'); ?>
			</td>
			<td>
				<input type="text" id="rsspreviewcount" name="rsspreviewcount" size="2" value="<?php if ($options['rsspreviewcount'] == '') echo '3'; else echo strval($options['rsspreviewcount']); ?>"/>
			</td>
			<td>
				<?php _e('Show RSS Feed Headers in Link Library output', 'link-library'); ?>
			</td>
			<td>
				<input type="checkbox" id="rssfeedinline" name="rssfeedinline" <?php if ($options['rssfeedinline']) echo ' checked="checked" '; ?>/>
			</td>
		</tr>
		<tr>
			<td>
				<?php _e('Show RSS Feed Content in Link Library output', 'link-library'); ?>
			</td>
			<td>
				<input type="checkbox" id="rssfeedinlinecontent" name="rssfeedinlinecontent" <?php if ($options['rssfeedinlinecontent']) echo ' checked="checked" '; ?>/>
			</td>
			<td>
				<?php _e('Number of RSS articles shown in Link Library Output', 'link-library'); ?>
			</td>
			<td>
				<input type="text" id="rssfeedinlinecount" name="rssfeedinlinecount" size="2" value="<?php if ($options['rssfeedinlinecount'] == '') echo '1'; else echo strval($options['rssfeedinlinecount']); ?>"/>
			</td>
			<td></td><td></td>
		</tr>
		<tr>
			<td><?php _e('RSS Preview Width', 'link-library'); ?></td>
			<td><input type="text" id="rsspreviewwidth" name="rsspreviewwidth" size="5" value="<?php if ($options['rsspreviewwidth'] == '') echo '900'; else echo strval($options['rsspreviewwidth']); ?>"/></td>
			<td><?php _e('RSS Preview Height', 'link-library'); ?></td>
			<td><input type="text" id="rsspreviewheight" name="rsspreviewheight" size="5" value="<?php if ($options['rsspreviewheight'] == '') echo '700'; else echo strval($options['rsspreviewheight']); ?>"/></td>
			<td></td><td></td>
		</tr>
		</table>

	<?php }

	function settingssets_thumbnails_meta_box($data) {
		$options = $data['options'];
        $genoptions = $data['genoptions'];
		$settings = $data['settings'];
	?>

		<table>
		<tr>
			<td style='width: 400px' class='lltooltip' title='<?php _e('Checking this option will get images from the thumbshots web site every time', 'link-library'); ?>.'>
				<?php _e('Use Thumbshots.org for dynamic link images', 'link-library'); ?>
			</td>
			<td class='lltooltip' title='<?php _e('Checking this option will get images from the thumbshots web site every time', 'link-library'); ?>.' style='width:75px;padding-right:20px'>
				<input type="checkbox" id="usethumbshotsforimages" name="usethumbshotsforimages" <?php if ($options['usethumbshotsforimages']) echo ' checked="checked" '; ?>/>
			</td>
        </tr>
        <tr>
            <td>
                <?php _e('Give priority to images assigned to links if present'); ?>
            </td>
            <td><input type="checkbox" id="uselocalimagesoverthumbshots" name="uselocalimagesoverthumbshots" <?php if ($options['uselocalimagesoverthumbshots']) echo ' checked="checked" '; ?>/></td>
		</tr>
		<tr>
			<td><?php _e('Generate Images / Favorite Icons', 'link-library'); ?></td>
			<td class="lltooltip" title="<?php if (empty($genoptions['thumbshotscid'])) _e('This button is only available when a valid API key is entered under the Link Library General Settings.', 'link-library'); ?>"><INPUT type="button" name="genthumbs" <?php if ( empty( $genoptions['thumbshotscid'] ) ) echo 'disabled'; ?> value="<?php _e('Generate Thumbnails and Store locally', 'link-library'); ?>" onClick="window.location= 'admin.php?page=link-library-settingssets&amp;settings=<?php echo $settings; ?>&amp;genthumbs=<?php echo $settings; ?>'"></td>
			<td><INPUT type="button" name="genfavicons" value="<?php _e('Generate Favorite Icons and Store locally', 'link-library'); ?>" onClick="window.location= 'admin.php?page=link-library-settingssets&amp;settings=<?php echo $settings; ?>&amp;genfavicons=<?php echo $settings; ?>'"></td>
		</tr>
		</table>
	<?php }

	function settingssets_rssgen_meta_box($data) {
		$options = $data['options'];
		$settings = $data['settings'];
	?>

		<table>
		<tr>
			<td>
				<?php _e('Publish RSS Feed', 'link-library'); ?>
			</td>
			<td style='width:75px;padding-right:20px'>
				<input type="checkbox" id="publishrssfeed" name="publishrssfeed" <?php if ($options['publishrssfeed']) echo ' checked="checked" '; ?>/>
			</td>
			<td><?php _e('Number of items in RSS feed', 'link-library'); ?></td><td style='width:75px;padding-right:20px'><input type="text" id="numberofrssitems" name="numberofrssitems" size="3" value="<?php if ($options['numberofrssitems'] == '') echo '10'; else echo strval($options['numberofrssitems']); ?>"/></td>
		</tr>	
		<tr>
			<td><?php _e('RSS Feed Title', 'link-library'); ?></td><td colspan=3><input type="text" id="rssfeedtitle" name="rssfeedtitle" size="80" value="<?php echo strval(esc_html(stripslashes($options['rssfeedtitle']))); ?>"/></td>
		</tr>
		<tr>
			<td><?php _e('RSS Feed Description', 'link-library'); ?></td><td colspan=3><input type="text" id="rssfeeddescription" name="rssfeeddescription" size="80" value="<?php echo strval(esc_html(stripslashes($options['rssfeeddescription']))); ?>"/></td>
		</tr>
		<tr>
			<td><?php _e('RSS Feed Web Address (default /linkrss/1)', 'link-library'); ?></td><td colspan=3><input type="text" id="rssfeedaddress" name="rssfeedaddress" size="80" value="<?php echo strval(esc_html(stripslashes($options['rssfeedaddress']))); ?>"/></td>
		</tr>
		</table>

	<?php }

	function settingssets_search_meta_box($data) {
		$options = $data['options'];
		$settings = $data['settings'];
	?>

		<div>
		<table>
			<tr>
				<td style='width:200px'><?php _e('Search Label', 'link-library'); ?></td>
				<?php if ($options['searchlabel'] == "") $options['searchlabel'] =  __('Search', 'link-library'); ?>
				<td style='padding-right:20px'><input type="text" id="searchlabel" name="searchlabel" size="30" value="<?php echo $options['searchlabel']; ?>"/></td>
                                <td class="lltooltip" title='<?php _e('Leave empty when links are to be displayed on same page as search box', 'link-library'); ?>'>Results Page Address</td>
                                <td class="lltooltip" title='<?php _e('Leave empty when links are to be displayed on same page as search box', 'link-library'); ?>'>
                                   <input type="text" id="searchresultsaddress" name="searchresultsaddress" size="80" value="<?php echo strval(esc_html(stripslashes($options['searchresultsaddress']))); ?>"/> 
                                </td>
			</tr>
		</table>
		</div>

	<?php }

	function settingssets_linksubmission_meta_box($data) {
		$options = $data['options'];
		$settings = $data['settings'];
	?>
		<div>
		<table>
			<tr>
				<td colspan=5 class="lltooltip" title='<?php _e('Following this link shows a list of all links awaiting moderation', 'link-library'); ?>.'><a href="<?php echo add_query_arg( 's', 'LinkLibrary%3AAwaitingModeration%3ARemoveTextToApprove' , admin_url( 'link-manager.php' ) ); ?>"><?php _e('View list of links awaiting moderation', 'link-library'); ?></a></td>
			</tr>
			<tr>
				<td style='width:200px'><?php _e('Show user links immediately', 'link-library'); ?></td>
				<td style='width:75px;padding-right:20px'><input type="checkbox" id="showuserlinks" name="showuserlinks" <?php if ($options['showuserlinks']) echo ' checked="checked" '; ?>/></td>
				<td style='width: 20px'></td>
				<td style='width: 20px'></td>
				<td style='width:250px'><?php _e('E-mail admin on link submission', 'link-library'); ?></td>
				<td style='width:75px;padding-right:20px'><input type="checkbox" id="emailnewlink" name="emailnewlink" <?php if ($options['emailnewlink']) echo ' checked="checked" '; ?>/></td>
				<td style='width: 20px'></td>
			</tr>
			<tr>
				<td style='width:200px'><?php _e('Require login to display form', 'link-library'); ?></td>
				<td style='width:75px;padding-right:20px'><input type="checkbox" id="addlinkreqlogin" name="addlinkreqlogin" <?php if ($options['addlinkreqlogin']) echo ' checked="checked" '; ?>/></td>
<td style='width: 20px'></td>
				<td style='width: 20px'></td>
				<td style='width:250px'><?php _e('Allow link submission with empty link', 'link-library'); ?></td>
				<td style='width:75px;padding-right:20px'><input type="checkbox" id="addlinknoaddress" name="addlinknoaddress" <?php if ($options['addlinknoaddress']) echo ' checked="checked" '; ?>/></td>
				<td style='width: 20px'></td>
			</tr>
			<tr>
				<td style='width:200px'><?php _e('Display captcha', 'link-library'); ?></td>
				<td style='width:75px;padding-right:20px'><input type="checkbox" id="showcaptcha" name="showcaptcha" <?php if ($options['showcaptcha']) echo ' checked="checked" '; ?>/></td>
<td style='width: 20px'></td>
				<td style='width: 20px'></td>
				<td class='lltooltip' title='<?php _e('This function will only store data when users are logged in to Wordpress', 'link-library'); ?>.' style='width:250px'><?php _e('Store login name on link submission', 'link-library'); ?></td>
				<td style='width:75px;padding-right:20px'><input type="checkbox" id="storelinksubmitter" name="storelinksubmitter" <?php if ($options['storelinksubmitter']) echo ' checked="checked" '; ?>/></td>
				<td style='width: 20px'></td>				
			</tr>
			<tr>
				<td style='width:200px'><?php _e('Add new link label', 'link-library'); ?></td>
				<?php if ($options['addnewlinkmsg'] == "") $options['addnewlinkmsg'] = __('Add new link', 'link-library'); ?>
				<td><input type="text" id="addnewlinkmsg" name="addnewlinkmsg" size="30" value="<?php echo $options['addnewlinkmsg']; ?>"/></td>
				<td style='width: 20px'></td>
				<td style='width: 20px'></td>
				<td style='width:200px'><?php _e('Link name label', 'link-library'); ?></td>
				<?php if ($options['linknamelabel'] == "") $options['linknamelabel'] = __('Link Name', 'link-library'); ?>
				<td><input type="text" id="linknamelabel" name="linknamelabel" size="30" value="<?php echo $options['linknamelabel']; ?>"/></td>
				<td style='width: 20px'></td>
			</tr>
			<tr>
				<td style='width:200px'><?php _e('Link address label', 'link-library'); ?></td>
				<?php if ($options['linkaddrlabel'] == "") $options['linkaddrlabel'] = __('Link Address', 'link-library'); ?>
				<td><input type="text" id="linkaddrlabel" name="linkaddrlabel" size="30" value="<?php echo $options['linkaddrlabel']; ?>"/></td>
				<td style='width: 20px'></td>
				<td style='width: 20px'></td>
				<td style='width:200px'><?php _e('Link RSS label', 'link-library'); ?></td>
				<?php if ($options['linkrsslabel'] == "") $options['linkrsslabel'] = __('Link RSS', 'link-library'); ?>
				<td><input type="text" id="linkrsslabel" name="linkrsslabel" size="30" value="<?php echo $options['linkrsslabel']; ?>"/></td>
				<td>
					<select name="showaddlinkrss" id="showaddlinkrss" style="width:60px;">
						<option value="false"<?php if ($options['showaddlinkrss'] == false) { echo ' selected="selected"';} ?>><?php _e('Hide', 'link-library'); ?></option>
						<option value="true"<?php if ($options['showaddlinkrss'] == true) { echo ' selected="selected"';} ?>><?php _e('Show', 'link-library'); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td style='width:200px'><?php _e('Link category label', 'link-library'); ?></td>
				<?php if ($options['linkcatlabel'] == "") $options['linkcatlabel'] = __('Link Category', 'link-library'); ?>
				<td><input type="text" id="linkcatlabel" name="linkcatlabel" size="30" value="<?php echo $options['linkcatlabel']; ?>"/></td>
				<td>
					<select name="showaddlinkcat" id="showaddlinkcat" style="width:60px;">
						<option value="false"<?php if ($options['showaddlinkcat'] == false) { echo ' selected="selected"';} ?>><?php _e('Hide', 'link-library'); ?></option>
						<option value="true"<?php if ($options['showaddlinkcat'] == true) { echo ' selected="selected"';} ?>><?php _e('Show', 'link-library'); ?></option>
					</select>
				</td>
				<td style='width: 20px'></td>
				<td style='width:200px' class='lltooltip' title='<?php _e('Comma-seperated list of categories to be displayed in category selection box (e.g. 1,5,4) instead of displaying the set of categories specified by the library.', 'link-library'); ?>'><?php _e('Link category override selection list', 'link-library'); ?></td>
				<td colspan=3 class='lltooltip' title='<?php _e('Comma-seperated list of categories to be displayed in category selection box (e.g. 1,5,4)', 'link-library'); ?>'><input type="text" id="addlinkcatlistoverride" name="addlinkcatlistoverride" size="50" value="<?php echo $options['addlinkcatlistoverride']; ?>" />
				<td style='width:200px'></td>
			</tr>
			<tr>
				<td style='width:200px'><?php _e('User-submitted category', 'link-library'); ?></td>
				<?php if ($options['linkcustomcatlabel'] == "") $options['linkcustomcatlabel'] = __('User-submitted category', 'link-library'); ?>
				<td><input type="text" id="linkcustomcatlabel" name="linkcustomcatlabel" size="30" value="<?php echo $options['linkcustomcatlabel']; ?>"/></td>
				<td>
					<select name="addlinkcustomcat" id="addlinkcustomcat" style="width:60px;">
						<option value="false"<?php if ($options['addlinkcustomcat'] == false) { echo ' selected="selected"';} ?>><?php _e('No', 'link-library'); ?></option>
						<option value="true"<?php if ($options['addlinkcustomcat'] == true) { echo ' selected="selected"';} ?>><?php _e('Allow', 'link-library'); ?></option>
					</select>
				</td>			
				<td></td>				
				<td style='width:200px'><?php _e('User-submitted category prompt', 'link-library'); ?></td>
				<?php if ($options['linkcustomcatlistentry'] == "") $options['linkcustomcatlistentry'] = __('User-submitted category (define below)', 'link-library'); ?>
				<td colspan=3><input type="text" id="linkcustomcatlistentry" name="linkcustomcatlistentry" size="50" value="<?php echo $options['linkcustomcatlistentry']; ?>"/></td>
				<td style='width:200px'></td>
			</tr>
			<tr>
				<td style='width:200px'><?php _e('Link description label', 'link-library'); ?></td>
				<?php if ($options['linkdesclabel'] == "") $options['linkdesclabel'] = __('Link Description', 'link-library'); ?>
				<td><input type="text" id="linkdesclabel" name="linkdesclabel" size="30" value="<?php echo $options['linkdesclabel']; ?>"/></td>
				<td>
					<select name="showaddlinkdesc" id="showaddlinkdesc" style="width:60px;">
						<option value="false"<?php if ($options['showaddlinkdesc'] == false) { echo ' selected="selected"';} ?>><?php _e('Hide', 'link-library'); ?></option>
						<option value="true"<?php if ($options['showaddlinkdesc'] == true) { echo ' selected="selected"';} ?>><?php _e('Show', 'link-library'); ?></option>
					</select>
				</td>
				<td style='width: 20px'></td>
				<td style='width:200px'><?php _e('Link notes label', 'link-library'); ?></td>
				<?php if ($options['linknoteslabel'] == "") $options['linknoteslabel'] = __('Link Notes', 'link-library'); ?>
				<td><input type="text" id="linknoteslabel" name="linknoteslabel" size="30" value="<?php echo $options['linknoteslabel']; ?>"/></td>
				<td>
					<select name="showaddlinknotes" id="showaddlinknotes" style="width:60px;">
						<option value="false"<?php if ($options['showaddlinknotes'] == false) { echo ' selected="selected"';} ?>><?php _e('Hide', 'link-library'); ?></option>
						<option value="true"<?php if ($options['showaddlinknotes'] == true) { echo ' selected="selected"';} ?>><?php _e('Show', 'link-library'); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td>Use Text Area for Notes</td>
				<td><input type="checkbox" id="usetextareaforusersubmitnotes" name="usetextareaforusersubmitnotes" <?php if ($options['usetextareaforusersubmitnotes']) echo ' checked="checked" '; ?>/></td>
			</tr>
			<tr>
				<td style='width:200px'><?php _e('Reciprocal Link label', 'link-library'); ?></td>
				<?php if ($options['linkreciprocallabel'] == "") $options['linkreciprocallabel'] = __('Reciprocal Link', 'link-library'); ?>
				<td><input type="text" id="linkreciprocallabel" name="linkreciprocallabel" size="30" value="<?php echo $options['linkreciprocallabel']; ?>"/></td>
				<td>
					<select name="showaddlinkreciprocal" id="showaddlinkreciprocal" style="width:60px;">
						<option value="false"<?php if ($options['showaddlinkreciprocal'] == false) { echo ' selected="selected"';} ?>><?php _e('Hide', 'link-library'); ?></option>
						<option value="true"<?php if ($options['showaddlinkreciprocal'] == true) { echo ' selected="selected"';} ?>><?php _e('Show', 'link-library'); ?></option>
					</select>
				</td>
				<td style='width: 20px'></td>
				<td style='width:200px'><?php _e('Secondary Address label', 'link-library'); ?></td>
				<?php if ($options['linksecondurllabel'] == "") $options['linksecondurllabel'] = __('Secondary Address', 'link-library'); ?>
				<td><input type="text" id="linksecondurllabel" name="linksecondurllabel" size="30" value="<?php echo $options['linksecondurllabel']; ?>"/></td>
				<td>
					<select name="showaddlinksecondurl" id="showaddlinksecondurl" style="width:60px;">
						<option value="false"<?php if ($options['showaddlinksecondurl'] == false) { echo ' selected="selected"';} ?>><?php _e('Hide', 'link-library'); ?></option>
						<option value="true"<?php if ($options['showaddlinksecondurl'] == true) { echo ' selected="selected"';} ?>><?php _e('Show', 'link-library'); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td style='width:200px'><?php _e('Link Telephone label', 'link-library'); ?></td>
				<?php if ($options['linktelephonelabel'] == "") $options['linktelephonelabel'] = __('Telephone', 'link-library'); ?>
				<td><input type="text" id="linktelephonelabel" name="linktelephonelabel" size="30" value="<?php echo $options['linktelephonelabel']; ?>"/></td>
				<td>
					<select name="showaddlinktelephone" id="showaddlinktelephone" style="width:60px;">
						<option value="false"<?php if ($options['showaddlinktelephone'] == false) { echo ' selected="selected"';} ?>><?php _e('Hide', 'link-library'); ?></option>
						<option value="true"<?php if ($options['showaddlinktelephone'] == true) { echo ' selected="selected"';} ?>><?php _e('Show', 'link-library'); ?></option>
					</select>
				</td>
				<td style='width: 20px'></td>
				<td style='width:200px'><?php _e('Link E-mail label', 'link-library'); ?></td>
				<?php if ($options['linkemaillabel'] == "") $options['linkemaillabel'] = __('E-mail', 'link-library'); ?>
				<td><input type="text" id="linkemaillabel" name="linkemaillabel" size="30" value="<?php echo $options['linkemaillabel']; ?>"/></td>
				<td>
					<select name="showaddlinkemail" id="showaddlinkemail" style="width:60px;">
						<option value="false"<?php if ($options['showaddlinkemail'] == false) { echo ' selected="selected"';} ?>><?php _e('Hide', 'link-library'); ?></option>
						<option value="true"<?php if ($options['showaddlinkemail'] == true) { echo ' selected="selected"';} ?>><?php _e('Show', 'link-library'); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td style='width:200px'><?php _e('Link Submitter Name label', 'link-library'); ?></td>
				<?php if ($options['linksubmitternamelabel'] == "") $options['linksubmitternamelabel'] = __('Submitter Name', 'link-library'); ?>
				<td><input type="text" id="linksubmitternamelabel" name="linksubmitternamelabel" size="30" value="<?php echo $options['linksubmitternamelabel']; ?>"/></td>
				<td>
					<select name="showlinksubmittername" id="showlinksubmittername" style="width:60px;">
						<option value="false"<?php if ($options['showlinksubmittername'] == false) { echo ' selected="selected"';} ?>><?php _e('Hide', 'link-library'); ?></option>
						<option value="true"<?php if ($options['showlinksubmittername'] == true) { echo ' selected="selected"';} ?>><?php _e('Show', 'link-library'); ?></option>
					</select>
				</td>
				<td style='width: 20px'></td>
				<td style='width:200px'><?php _e('Link Submitter E-mail label', 'link-library'); ?></td>
				<?php if ($options['linksubmitteremaillabel'] == "") $options['linksubmitteremaillabel'] = __('Submitter E-mail', 'link-library'); ?>
				<td><input type="text" id="linksubmitteremaillabel" name="linksubmitteremaillabel" size="30" value="<?php echo $options['linksubmitteremaillabel']; ?>"/></td>
				<td>
					<select name="showaddlinksubmitteremail" id="showaddlinksubmitteremail" style="width:60px;">
						<option value="false"<?php if ($options['showaddlinksubmitteremail'] == false) { echo ' selected="selected"';} ?>><?php _e('Hide', 'link-library'); ?></option>
						<option value="true"<?php if ($options['showaddlinksubmitteremail'] == true) { echo ' selected="selected"';} ?>><?php _e('Show', 'link-library'); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td style='width:200px'><?php _e('Link Submitter Comment Label', 'link-library'); ?></td>
				<?php if ($options['linksubmittercommentlabel'] == "") $options['linksubmittercommentlabel'] = __('Submitter Comment', 'link-library'); ?>
				<td><input type="text" id="linksubmittercommentlabel" name="linksubmittercommentlabel" size="30" value="<?php echo $options['linksubmittercommentlabel']; ?>"/></td>
				<td>
					<select name="showlinksubmittercomment" id="showlinksubmittercomment" style="width:60px;">
						<option value="false"<?php if ($options['showlinksubmittercomment'] == false) { echo ' selected="selected"';} ?>><?php _e('Hide', 'link-library'); ?></option>
						<option value="true"<?php if ($options['showlinksubmittercomment'] == true) { echo ' selected="selected"';} ?>><?php _e('Show', 'link-library'); ?></option>
					</select>
				</td>
				<td style='width: 20px'></td>
				<td style='width:200px'><?php _e('Large Description Label', 'link-library'); ?></td>
				<?php if ($options['linklargedesclabel'] == "") $options['linklargedesclabel'] = __('Large Description', 'link-library'); ?>
				<td><input type="text" id="linklargedesclabel" name="linklargedesclabel" size="30" value="<?php echo $options['linklargedesclabel']; ?>"/></td>
				<td>
					<select name="showuserlargedescription" id="showuserlargedescription" style="width:60px;">
						<option value="false"<?php if ($options['showuserlargedescription'] == false) { echo ' selected="selected"';} ?>><?php _e('Hide', 'link-library'); ?></option>
						<option value="true"<?php if ($options['showuserlargedescription'] == true) { echo ' selected="selected"';} ?>><?php _e('Show', 'link-library'); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td style='width:200px'><?php _e('Custom Captcha Question', 'link-library'); ?></td>
				<?php if ($options['customcaptchaquestion'] == "") $options['customcaptchaquestion'] = __('Is boiling water hot or cold?', 'link-library'); ?>
				<td><input type="text" id="customcaptchaquestion" name="customcaptchaquestion" size="30" value="<?php echo $options['customcaptchaquestion']; ?>"/></td>
				<td>
					<select name="showcustomcaptcha" id="showcustomcaptcha" style="width:60px;">
						<option value="false"<?php if ($options['showcustomcaptcha'] == false) { echo ' selected="selected"';} ?>><?php _e('Hide', 'link-library'); ?></option>
						<option value="true"<?php if ($options['showcustomcaptcha'] == true) { echo ' selected="selected"';} ?>><?php _e('Show', 'link-library'); ?></option>
					</select>
				</td>
				<td style='width: 20px'></td>
				<td style='width:200px'><?php _e('Custom Captcha Answer', 'link-library'); ?></td>
				<?php if ($options['customcaptchaanswer'] == "") $options['customcaptchaanswer'] = __('hot', 'link-library'); ?>
				<td><input type="text" id="customcaptchaanswer" name="customcaptchaanswer" size="30" value="<?php echo $options['customcaptchaanswer']; ?>"/></td>
			</tr>
			<tr>
				<td style='width:200px'><?php _e('Add Link button label', 'link-library'); ?></td>
				<?php if ($options['addlinkbtnlabel'] == "") $options['addlinkbtnlabel'] = __('Add Link', 'link-library'); ?>
				<td><input type="text" id="addlinkbtnlabel" name="addlinkbtnlabel" size="30" value="<?php echo $options['addlinkbtnlabel']; ?>"/></td>
				<td style='width: 20px'></td>
				<td style='width: 20px'></td>
				<td style='width:200px'><?php _e('New Link Message', 'link-library'); ?></td>
				<?php if ($options['newlinkmsg'] == "") $options['newlinkmsg'] = __('New link submitted', 'link-library'); ?>
				<td><input type="text" id="newlinkmsg" name="newlinkmsg" size="30" value="<?php echo $options['newlinkmsg']; ?>"/></td>
			</tr>
			<tr>
				<td style='width:200px'><?php _e('New Link Moderation Label', 'link-library'); ?></td>
				<?php if ($options['moderatemsg'] == "") $options['moderatemsg'] = __('it will appear in the list once moderated. Thank you.', 'link-library'); ?>
				<td colspan=6><input type="text" id="moderatemsg" name="moderatemsg" size="90" value="<?php echo $options['moderatemsg']; ?>"/></td>
			</tr>
		</table>
		</div>

	<?php }

	function settingssets_importexport_meta_box($data) {
		$options = $data['options'];
		$settings = $data['settings'];
	?>

		<table>
			<tr>
				<td class='lltooltip' title='<?php _e('Allows for links to be added in batch to the Wordpress links database. CSV file needs to follow template for column layout.', 'link-library'); ?>' style='width: 330px'><?php _e('CSV file to upload to import links', 'link-library'); ?> (<a href="<?php echo plugins_url( 'importtemplate.csv', __FILE__ ); ?>"><?php _e('file template', 'link-library'); ?></a>)</td>
				<td><input size="80" name="linksfile" type="file" /></td>
				<td><input type="submit" name="importlinks" value="<?php _e('Import Links', 'link-library'); ?>" /></td>
			</tr>
			<tr>
				<td><?php _e('First row contains column headers', 'link-library'); ?></td>
				<td><input type="checkbox" id="firstrowheaders" name="firstrowheaders" checked="checked" /></td>
			</tr>
		</table>

		<hr style='color: #CCC; ' />
		<input type='hidden' value='<?php echo $settings; ?>' name='settingsetid' id='settingsetid' />
		<table>
			<tr>
				<td class='lltooltip' title='<?php _e('Overwrites current library settings with contents of CSV file', 'link-library'); ?>' style='width: 330px'><?php _e('Library Settings CSV file to import', 'link-library'); ?></td>
				<td><input size="80" name="settingsfile" type="file" /></td>
				<td><input type="submit" name="importsettings" value="<?php _e('Import Library Settings', 'link-library'); ?>" /></td>
			</tr>
			<tr>
				<td class='lltooltip' style='width: 330px' title='<?php _e('Generates CSV file with current library configuration for download', 'link-library'); ?>'><?php _e('Export current library settings', 'link-library'); ?></td>
				<td><input type="submit" name="exportsettings" value="<?php _e('Export Library Settings', 'link-library'); ?>" /></td>
			</tr>
		</table>

	<?php }
	
	function reciprocal_meta_box($data) {
		$genoptions = $data['genoptions'];
	?>
		<table>
			<tr>
				<td style='width: 250px'><?php _e('Search string', 'link-library'); ?></td>
				<td><input type="text" id="recipcheckaddress" name="recipcheckaddress" size="60" value="<?php echo $genoptions['recipcheckaddress']; ?>"/></td>
				<td><input type='submit' id="recipcheck" name="recipcheck" value="<?php _e('Check Reciprocal Links', 'link-library'); ?>" /></td>
			</tr>
			<tr>
				<td><?php _e('Delete links that return a 403 error', 'link-library'); ?></td>
				<td><input type="checkbox" id="recipcheckdelete403" name="recipcheckdelete403" <?php if ($genoptions['recipcheckdelete403']) echo ' checked="checked" '; ?>/></td>
			</tr>
		</table>
		
	<?php
	}


	/************************************************ Render Custom Meta Box in Link Editor *******************************************/
	function ll_link_edit_extra($link) {
		global $wpdb;

		$genoptions = get_option('LinkLibraryGeneral');

		if (floatval($genoptions['schemaversion']) < "4.6")
		{
			$this->ll_install();
		}

		if (isset( $link->link_id ) && $link->link_id != '')
		{
			$link_updated_query = "select link_updated from " . $this->db_prefix() . "links where link_id = " . $link->link_id;
			$link_updated = $wpdb->get_var($link_updated_query);
			
			$linkextradataquery = "select * from " . $this->db_prefix() . "links_extrainfo where link_id = " . $link->link_id;
			$extradata = $wpdb->get_row($linkextradataquery, ARRAY_A);
			
			if ($extradata['link_visits'] == '') $extradata['link_visits'] = 0;
			
			$originallinkdata = "select * from " . $this->db_prefix() . "links where link_id = " . $link->link_id;
			$originaldata = $wpdb->get_row($originallinkdata, ARRAY_A);
		}
		else
		{
			$link_updated = date("Y-m-d H:i", current_time( 'timestamp' ) );
			$extradata = array();
			$originaldata = array();
		}
	?>
		<table>
			<tr>
				<td style='width: 200px'><?php _e('Featured Link', 'link-library'); ?></td>
				<td><input type="checkbox" id="link_featured" name="link_featured" <?php ( isset( $extradata['link_featured'] ) ? checked( $extradata['link_featured'] ) : '' ); ?>/></td>
			</tr>
			<tr>
				<td style='width: 200px'><?php _e('No Follow', 'link-library'); ?></td>
				<td><input type="checkbox" id="link_no_follow" name="link_no_follow" <?php ( isset( $extradata['link_no_follow'] ) ? checked ( $extradata['link_no_follow'] ) : '' ); ?>/></td>
			</tr>
			<tr>
				<td style='width: 200px'><?php _e('Updated Date', 'link-library'); ?></td>
				<td>Set Manually  <input type="checkbox" id="ll_updated_manual" name="ll_updated_manual" <?php if ( isset( $extradata['link_manual_updated'] ) && $extradata['link_manual_updated'] == 'Y') echo ' checked="checked" '; ?>/> <input type="text" <?php if ( !isset( $extradata['link_manual_updated'] ) || ( isset( $extradata['link_manual_updated'] ) && ( $extradata['link_manual_updated'] == 'N' || $extradata['link_manual_updated'] == '' ) ) ) echo 'disabled="disabled"'; ?> id="ll_link_updated" name="ll_link_updated" size="80" value="<?php echo $link_updated; ?>"/></td>
			</tr>
			<tr>
				<td style='width: 200px'><?php _e('Secondary Web Address', 'link-library'); ?></td>
				<td><input type="text" id="ll_secondwebaddr" name="ll_secondwebaddr" size="80" value="<?php echo ( isset( $extradata['link_second_url'] ) ? $extradata['link_second_url'] : '' ); ?>"/> <?php if ( isset( $extradata['link_second_url'] ) && $extradata['link_second_url'] != "") echo " <a href=" . esc_url( stripslashes($extradata['link_second_url']) ) . ">" . __('Visit', 'link-library') . "</a>"; ?></td>
			</tr>
			<tr>
				<td><?php _e('Telephone', 'link-library'); ?></td>
				<td><input type="text" id="ll_telephone" name="ll_telephone" size="80" value="<?php echo ( isset( $extradata['link_telephone'] ) ? esc_attr( stripslashes($extradata['link_telephone']) ) : '' ); ?>"/></td>
			</tr>
			<tr>
				<td><?php _e('E-mail', 'link-library'); ?></td>
				<td><input type="text" id="ll_email" name="ll_email" size="80" value="<?php echo ( isset( $extradata['link_email'] ) ? esc_attr( stripslashes($extradata['link_email']) ) : '' ); ?>"/></td>
			</tr>
			<tr>
				<td><?php _e('Reciprocal Link', 'link-library'); ?></td>
				<td><input type="text" id="ll_reciprocal" name="ll_reciprocal" size="80" value="<?php echo ( isset( $extradata['link_reciprocal'] ) ? $extradata['link_reciprocal'] : '' ); ?>"/> <?php if ( isset( $extradata['link_reciprocal'] ) && $extradata['link_reciprocal'] != "") echo " <a href=" . esc_url( stripslashes($extradata['link_reciprocal']) ) . ">" . __('Visit', 'link-library') . "</a>"; ?></td>
			</tr>	
			<tr>
				<td><?php _e('Number of link views', 'link-library'); ?></td>
				<td><input disabled type="text" id="ll_hits" name="ll_hits" size="80" value="<?php echo ( isset( $extradata['link_visits'] ) ? esc_attr( $extradata['link_visits'] ) : '' ); ?>"/></td>
			</tr>
			<tr>
				<td><?php _e('Link Submitter', 'link-library'); ?></td>
				<td><input disabled type="text" id="ll_submitter" name="ll_submitter" size="80" value="<?php echo ( isset( $extradata['link_submitter'] ) ? esc_attr( stripslashes($extradata['link_submitter']) ) : '' ); ?>"/></td>
			</tr>
			<tr>
				<td><?php _e('Link Submitter Name', 'link-library'); ?></td>
				<td><input disabled type="text" id="link_submitter_name" name="link_submitter_name" size="80" value="<?php echo ( isset( $extradata['link_submitter_name'] ) ? esc_attr( stripslashes($extradata['link_submitter_name']) ) : '' ); ?>"/></td>
			</tr>
			<tr>
				<td><?php _e('Link Submitter E-mail', 'link-library'); ?></td>
				<td><input disabled type="text" id="link_submitter_email" name="link_submitter_email" size="80" value="<?php echo ( isset( $extradata['link_submitter_email'] ) ? esc_attr( stripslashes($extradata['link_submitter_email'])) : '' ); ?>"/></td>
			</tr>
			<tr>
				<td><?php _e('Link Large Description', 'link-library'); ?></td>
				<td><textarea id="link_textfield" name="link_textfield" cols='66'><?php echo ( isset( $extradata['link_textfield'] ) ? ( function_exists( 'esc_textarea' ) ? esc_textarea( stripslashes($extradata['link_textfield']) ) : stripslashes($extradata['link_textfield']) ) : '' ); ?></textarea></td>
			</tr>
			<tr>
				<td><?php _e('Current Link Image', 'link-library'); ?></td>
				<td>
					<div id='current_link_image'>
					<?php if ( isset($originaldata['link_image']) && $originaldata['link_image'] != ''): ?>
						<img id="actual_link_image" src="<?php echo $originaldata['link_image'] ?>" />
					<?php else: ?>
						<span id="noimage"><?php _e('None Assigned', 'link-library'); ?></span>
					<?php endif; ?>
					</div>
				</td>
			</tr>
			<?php if ( isset( $link->link_id ) && $link->link_id != ''): ?>
			<tr>
				<td><?php _e('Automatic Image Generation', 'link-library'); ?></td>
				<td title="<?php if ( empty( $genoptions['thumbshotscid'] ) ) _e('This button is only available when a valid API key is entered under the Link Library General Settings.', 'link-library'); ?>"><INPUT type="button" id="genthumbs" name="genthumbs" <?php if ( empty ( $genoptions['thumbshotscid'] ) ) echo 'disabled'; ?> value="<?php _e('Generate Thumbnail and Store locally', 'link-library'); ?>">
					<INPUT type="button" id="genfavicons" name="genfavicons" value="<?php _e('Generate Favorite Icon and Store locally', 'link-library'); ?>">
				</td>
			</tr>
			<?php else: ?>
			<tr>
				<td><?php _e('Automatic Image Generation', 'link-library'); ?></td>
				<td><?php _e('Only available once link is saved', 'link-library'); ?></td>
			</tr>
			<?php endif; ?>
            <?php if ( function_exists( 'wp_enqueue_media' ) ) { ?>
			<tr>
				<td><?php _e('Manual Image Upload', 'link-library'); ?></td>
				<td><input type="button" class="upload_image_button" value="<?php _e( 'Launch Media Uploader', 'link-library' ); ?>"></td>
			</tr>
			<tr>
				<td colspan='2'><p><?php _e('Manual upload requires a wp-content\uploads directory to be present with write permissions', 'link-library'); ?>.</p></td>
			</tr>
            <?php } ?>
		</table>

	<?php $genoptions = get_option('LinkLibraryGeneral'); ?>

		<script type="text/javascript">
			jQuery(document).ready(function()
			{
                // Uploading files
                var file_frame;

                jQuery('.upload_image_button').live('click', function( event ){

                    event.preventDefault();

                    // If the media frame already exists, reopen it.
                    if ( file_frame ) {
                        file_frame.open();
                        return;
                    }

                    // Create the media frame.
                    file_frame = wp.media.frames.file_frame = wp.media({
                        title: jQuery( this ).data( 'uploader_title' ),
                        button: {
                            text: jQuery( this ).data( 'uploader_button_text' ),
                        },
                        multiple: false  // Set to true to allow multiple files to be selected
                    });

                    // When an image is selected, run a callback.
                    file_frame.on( 'select', function() {
                        // We set multiple to false so only get one image from the uploader
                        attachment = file_frame.state().get('selection').first().toJSON();

                        // Do something with attachment.id and/or attachment.url here
                        jQuery('#link_image').val(attachment.url);

                        jQuery('#current_link_image').replaceWith("<div id='current_link_image'><img src='" + attachment.url + "' /></div>");
                        jQuery('#current_link_image').fadeIn('fast');
                    });

                    // Finally, open the modal
                    file_frame.open();
                });


				jQuery("#ll_updated_manual").click(function() {
					 if (jQuery('#ll_updated_manual').is(':checked')) {
					   jQuery('#ll_link_updated').attr('disabled', false);
					 } else {
					   jQuery('#ll_link_updated').attr('disabled', true);
					 } 
				});
			    // Using jQuery, set both the enctype and the encoding
				// attributes to be multipart/form-data.
				jQuery( "form#editlink" )
					.attr( "enctype", "multipart/form-data" )
					.attr( "encoding", "multipart/form-data" )
					;
                jQuery( "form#addlink" )
                    .attr( "enctype", "multipart/form-data" )
                    .attr( "encoding", "multipart/form-data" )
                ;
				jQuery('#genthumbs').click(function()
				{
					var linkname = jQuery('#link_name').val();
					var linkurl = jQuery('#link_url').val();
					
					if (linkname != '' && linkurl != '')
					{
						jQuery('#current_link_image').fadeOut('fast');

						jQuery.ajax( {
                                type: 'POST',
                                url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                                data: {
                                    action: 'link_library_generate_image',
                                    _ajax_nonce: '<?php echo wp_create_nonce( 'link_library_generate_image' ); ?>',
                                    name: linkname,
                                    url: linkurl,
                                    mode: 'thumbonly',
                                    cid: '<?php echo $genoptions['thumbshotscid']; ?>',
                                    filepath: 'link-library-images',
                                    filepathtype: 'absolute',
                                    linkid: <?php if( isset( $link->link_id ) ) { echo $link->link_id; } else { echo "''"; } ?>
                                    },
                                    success:
							            function( data ){
								            if (data != '')
								            {
									            jQuery('#current_link_image').replaceWith("<div id='current_link_image'><img src='" + data + "' /></div>");
									            jQuery('#current_link_image').fadeIn('fast');
									            jQuery('#link_image').val(data);
									            alert('<?php _e('Thumbnail successfully generated for', 'link-library'); ?> ' + linkname);
								            }
							            } } );
					}
					else
					{
						alert("<?php _e('Cannot generate thumbnail when no name and no web address are specified.', 'link-library'); ?>");
					}
				} );
				
				jQuery('#genfavicons').click(function()
				{
					var linkname = jQuery('#link_name').val();
					var linkurl = jQuery('#link_url').val();
					
					if (linkname != '' && linkurl != '')
					{
						jQuery('#current_link_image').fadeOut('fast');
                        jQuery.ajax( {
                            type: 'POST',
                            url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                            data: {
                                action: 'link_library_generate_image',
                                _ajax_nonce: '<?php echo wp_create_nonce( 'link_library_generate_image' ); ?>',
                                name: linkname,
                                url: linkurl,
                                mode: 'favicononly',
                                cid: '<?php echo $genoptions['thumbshotscid']; ?>',
                                filepath: 'link-library-favicons',
                                filepathtype: 'absolute',
                                linkid: <?php if( isset( $link->link_id ) ) { echo $link->link_id; } else { echo "''"; }?>


                                },
                            success:
							    function(data){
								    if (data != '') {
									jQuery('#current_link_image').replaceWith("<div id='current_link_image'><img src='" + data + "' /></div>");
									jQuery('#current_link_image').fadeIn('fast');
									jQuery('#link_image').val(data);
									alert('<?php _e('Favicon successfully generated for', 'link-library') ?> ' + linkname);
								    }
							    } } );
					}
					else
					{
						alert("<?php _e('Cannot generate favorite icon when no name and no web address are specified.', 'link-library'); ?>");
					}
				} );

			});
	</script>
		
	<?php
	}
	
	/******************************* Store extra field data when link is saved *******************************************/
	function add_link_field($link_id) {
		global $wpdb;
		
		$uploads = wp_upload_dir();
        
        $genoptions = get_option('LinkLibraryGeneral');

		if(array_key_exists('linkimageupload', $_FILES))
		{
			if (!file_exists($uploads['basedir'] . '/link-library-images'))
				mkdir($uploads['basedir'] . '/link-library-images');
			$target_path = $uploads['basedir'] . "/link-library-images/" . $link_id . ".jpg";
            
            if ( $genoptions['imagefilepath'] == 'absolute' || empty( $genoptions['imagefilepath'] ) ) {
                $file_path = $uploads['baseurl'] . "/link-library-images/" . $link_id . ".jpg";
            } elseif ( $genoptions['imagefilepath'] == 'relative' ) {
                $parseaddress = parse_url( $uploads['baseurl'] );
                $file_path = $parseaddress['path'] . "/link-library-images/" . $link_id . ".jpg";
            }
            
			if (move_uploaded_file($_FILES['linkimageupload']['tmp_name'], $target_path))
				$withimage = true;
			else
				$withimage = false;
		}
		else
			$withimage = false;
			
		$tablename = $this->db_prefix() . "links";
		
		if (isset($_POST['ll_link_updated']))
			$link_updated = $_POST['ll_link_updated'];
		elseif (!isset($_POST['ll_link_updated']))
			$link_updated = date("Y-m-d H:i", current_time( 'timestamp' ) );
		
		if ($withimage == true)
			$wpdb->update( $tablename, array( 'link_updated' => $link_updated, 'link_image' => $file_path ), array( 'link_id' => $link_id ));
		else
			$wpdb->update( $tablename, array( 'link_updated' => $link_updated ), array( 'link_id' => $link_id ));
				
		$extradatatable = $this->db_prefix() . "links_extrainfo";
		
		$linkextradataquery = "select * from " . $this->db_prefix() . "links_extrainfo where link_id = " . $link_id;
		$extradata = $wpdb->get_row($linkextradataquery, ARRAY_A);
		
		global $current_user;

		get_currentuserinfo();

		$username = $current_user->user_login;
		
		$updatearray = array();
		
		if (isset($_POST['ll_updated_manual']))
			$updatearray['link_manual_updated'] = 'Y';
		else
			$updatearray['link_manual_updated'] = 'N';
		
		if (isset($_POST['ll_secondwebaddr']))
			$updatearray['link_second_url'] = $_POST['ll_secondwebaddr'];
			
		if (isset($_POST['ll_telephone']))
			$updatearray['link_telephone'] = $_POST['ll_telephone'];
			
		if (isset($_POST['ll_email']))
			$updatearray['link_email'] = $_POST['ll_email'];
			
		if (isset($_POST['ll_reciprocal']))
			$updatearray['link_reciprocal'] = $_POST['ll_reciprocal'];
		
		if (isset($_POST['link_textfield']))
			$updatearray['link_textfield'] = esc_html($_POST['link_textfield']);
			
		if ( isset($_POST['link_no_follow']) && $_POST['link_no_follow'] == 'on' )
            $updatearray['link_no_follow'] = true;
        else
            $updatearray['link_no_follow'] = false;
			
		if ( isset($_POST['link_featured']) && $_POST['link_featured'] == 'on' )
			$updatearray['link_featured'] = true;
        else
            $updatearray['link_featured'] = false;

		if ($extradata)
			$wpdb->update( $extradatatable, $updatearray, array( 'link_id' => $link_id ));
		else
		{
			$updatearray['link_id'] = $link_id;
			$updatearray['link_submitter'] = $username;
			$wpdb->insert( $extradatatable, $updatearray );
		}
	}

	/************************************************ Delete extra field data when link is deleted ***********************************/
	function delete_link_field($link_id) {
		global $wpdb;
		
		$deletequery = "delete from " . $this->db_prefix() . "links_extrainfo where link_id = " . $link_id;
		$wpdb->get_results($deletequery);
	}

	/***************************************** Add column to link list view ****************************************/
	function ll_linkmanager_addcolumn($columns) {
		$columns['hits'] = 'Hits';
		return $columns;
	}

	function ll_linkmanager_populatecolumn($arg1, $arg2) {
		global $wpdb;
		
		switch ($arg1) {
			case 'hits':
				$linkextradataquery = "select * from " . $this->db_prefix() . "links_extrainfo where link_id = " . $arg2;
				$extradata = $wpdb->get_row($linkextradataquery, ARRAY_A);
				$hits = $extradata['link_visits'];
				if ($hits == '') $hits = 0;
				echo $hits;
                break;
		}
	}


}

?>
