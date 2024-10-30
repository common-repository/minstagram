<?php
/*
Plugin Name: Minstagram
Plugin URI: http://www.info-town.jp
Description: A simple Instagram Widget. This Widget shows your latest Instagram images. 
Author: Hiroshi Sawai
Author URI: http://www.info-town.jp
Plugin URI: http://www.creationlabs.net/minstagram/1428.html
Text Domain: minsta
Version: 0.0.3
*/

// Block direct requests
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
if ( ! defined( 'MINSTA_PLUGIN_URL' ) ) {
	define( 'MINSTA_PLUGIN_URL', untrailingslashit( plugins_url( '', __FILE__ ) ) );
}
if ( ! defined( 'MIMSTAGRAM_API_BASE_URI' ) ) {
	define( 'MINSTAGRAM_API_BASE_URI', 'https://api.instagram.com/v1' );
}

//  Register Minstagram widget.
require_once( dirname( __FILE__ ) . '/widget/class.minstagram-users-widget.php' );
require_once( dirname( __FILE__ ) . '/widget/class.minstagram-tags-widget.php' );
function widget_register_minstagram() {
	register_widget( 'Minstagram_Users_Widget' );
	register_widget( 'Minstagram_Tags_Widget' );
}

add_action( 'widgets_init', 'widget_register_minstagram' );

// Register Minstagram settings.
require_once( dirname( __FILE__ ) . '/settings/minstagram-settings-setup.php' );
