<?php

/**
 * Plugin Name: AwayShops - Publish and Unpublish Menu Options
 * Plugin URI: https://github.com/AwayShops-LLC/publish-unpublish-menu-options
 *
 * Description: A simple plugin that toggles publish status for the current post.
 * This plugins only works in conjunction with pages with "Publish" and "Unpublish" 
 * links, as shown below:
 * 
 *   Publish URL: [current-url]publish=yes
 *   Publish URL Container: class="publish-link"
 *   Unpublish URL: [current-url]publish=no
 *   Unpublish URL Container: class="unpublish-link"
 * 
 * Note: The [current-url] shortocdes is provided by this plugin.
 *
 * The complete code can be represented as html (with shortcodes) as follows:
 * <div class="publish-link"><a href="[current-url]publish=yes">Publish</a></div>
 * <div class="unpublish-link"><a href="[current-url]publish=no">Unpublish</a></div>
 * [display-modal-confirmation]

 * The plugin works with posts and pages by default. This can be controlled in the 
 * wp-config.php file, by defining an array of post types to the as_post_types 
 * variable. For example:
 *   $as_post_types = array('book', 'movie');
 * 
 * This plugin optionally can include a confirmation dialog. To use it place the 
 * following shortcode on the page:
 *   [display-modal-confirmation] 
 * 
 * Version: 1.0
 * Author: James Whalen
 * Author URI: https://github.com/awayshops
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * @author James Whalen <james.whalen@awayshops.com>
 * @copyright Copyright (c) 2021, AwayShops, LLC
**/

function as_version() {
	return("1.00.00");
}

// Conditionally initialize as_post_types - might be defined in wp-config.php
global $as_post_types;
if (!isset($as_post_types)) $as_post_types = array('post', 'page');

// Conditionally initialize as_allowed_roles - might be defined in wp-config.php
global $as_allowed_roles;
if (!isset($as_allowed_roles)) $as_allowed_roles = array('administrator');

// Get url parts
$as_complete_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$as_url_parts = parse_url($as_complete_url); 

// Get url parameters
$as_url_parms = array();
if (isset($as_url_parts['query'])) parse_str($as_url_parts['query'], $as_url_parms);

add_action( 'wp_enqueue_scripts', 'as_wp_enqueue_scripts', 15 );
function as_wp_enqueue_scripts() {
	wp_enqueue_style ('publish-unpublish-menu-options-style', plugins_url( '/css/styles.css', __FILE__ ), array(), as_version(), 'all');		
	wp_register_script('publish-unpublish-menu-options-script', plugins_url( '/js/index.js', __FILE__ ), array(), as_version(), true);
}

$as_post_type = "";
add_action('wp_footer', 'as_wp_footer'); 
function as_wp_footer() {
	global $as_post_type, $as_post_types;
	if (!in_array($as_post_type, $as_post_types)) return;
	wp_enqueue_script('publish-unpublish-menu-options-script');
}

add_action('init', 'as_init');
function as_init() {
	// Plugin only works for specified roles and only on the front-end.
	$user = wp_get_current_user();
	global $as_allowed_roles;
	if ((array_intersect($as_allowed_roles, $user->roles )) && (!is_admin())) {
		add_action('wp', 'as_wp');
	} else {
		echo '<style type="text/css">.publish-link {display:none !important;} .unpublish-link {display:none !important;}</style>';
	}
}

function as_wp() {
	global $post, $as_post_types, $as_url_parms;
	$as_post_id = $post->ID;
	global $as_post_type;
	$as_post_type = $post->post_type;
	$as_post_status = $post->post_status;
	
	if ((isset($as_post_type)) && (in_array($as_post_type, $as_post_types))) {
		if (count($as_url_parms) > 0) {
			if ($as_url_parms['publish'] == "no") {
				 $parms = array(
					'ID'		   => $as_post_id,
					'post_status'   => ''
				 );
				 wp_update_post( $parms );
				 $as_post_status = "";
			}
			if ($as_url_parms['publish'] == "yes") {
				wp_publish_post( $as_post_id );
				$as_post_status = "publish";
			}
		}
		if ($as_post_status == "publish") {
			echo '<style type="text/css">.publish-link {display:none !important;}</style>';
		} else {
			echo '<style type="text/css">.unpublish-link {display:none !important;}</style>';
		}
	}
}

add_filter('wp_nav_menu_items', 'do_shortcode'); // allow shortcodes in menu options

add_shortcode( 'current-url', 'as_current_url' );
function as_current_url( $atts ) {
	global $as_url_parms;
	unset($as_url_parms['publish']); // Remove 'publish' from url parmeters
	
	// Create new url parameters string (without publish)
	$as_url_parms_string = "";
	foreach ($as_url_parms as $key => $value) {
		$as_url_parms_string .= $key . "=" . $value;
		$as_url_parms_string .= "&";
	}
	// Create url with revised url parameters string
	global $as_url_parts;
	$as_url = $as_url_parts['scheme'] . "://" . $as_url_parts['host'] . $as_url_parts['path'];
	if ($as_url_parms_string == "") return($as_url . "?"); else return($as_url . $as_url_parms_string);
}

add_shortcode( 'display-modal-confirmation', 'as_display_modal_confirmation' );
function as_display_modal_confirmation( $atts ) {
    $output = "";
    $output .= '<div id="myModal" class="modal">';
        $output .= '<div style="text-align: center; padding: 10px;" class="modal-content">';
            $output .= '<p>Are you sure?<br><br></p>';
            $output .= '<button class="ok-button">OK</button>';
            $output .= '<button class="cancel-button">Cancel</button>';
        $output .= '</div>';
    $output .= '</div>';
    return $output;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';