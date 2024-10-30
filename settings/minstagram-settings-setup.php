<?php
require_once( dirname( __FILE__ ) . '/class.minstagram-settings-controller.php' );
require_once( dirname( __FILE__ ) . '/class.minstagram-settings-model.php' );

// Add Minstagram setting page in admin page.
add_action( 'admin_menu', 'minsta_register_admin_menu', 10 );
function minsta_register_admin_menu() {
	$settings_controller = new Minstagram_Settings_Controller();
	/*
	 * Add Minstagram menu in admin page.
	 */
	add_object_page(
		'Minstagram',
		'Minstagram',
		'edit_others_posts',
		'minsta-settings',
		array( $settings_controller, 'index' )
	);
}
