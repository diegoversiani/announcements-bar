<?php
/*
Plugin Name: Announcements Bar
Plugin URI:
Description: Display horizontal scrolling announcements on the website.
Version: 1.0.0
Author: PageSpeed.Guru
Author URI: https://pagespeed.guru/
*/

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}




define( 'ANNB_PLUGIN_PATH', plugin_dir_path(__FILE__) );
define( 'ANNB_PLUGIN_URL', plugin_dir_url(__FILE__) );
define( 'ANNB_THEME_TEMPLATES_FOLDER', 'plugins/announcement-bar/templates/' );
define( 'ANNB_TEMPLATES_FOLDER',  ANNB_PLUGIN_PATH . 'templates/' );



/**
 * Load enqueue scripts and styles
 */
require plugin_dir_path( __FILE__ ) . '/inc/enqueue.php';

/**
 * Load classes
 */
require plugin_dir_path( __FILE__ ) . '/inc/classes/class.announcement-bar-plugin.php';

/**
 * Load custom post types
 */
require_once( ANNB_PLUGIN_PATH . 'inc/custom-post-types/custom-post-type-announcement-bar.php' );

/**
 * Load output front end html.
 */
require_once( ANNB_PLUGIN_PATH . 'inc/output-announcement-bars.php' );
