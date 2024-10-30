<?php
/**
 * Widget for Instagram API
 *
 * @author  Hiroshi Sawai <info@info-town.jp>
 * @version 0.0.1
 * @since   0.0.1
 */
class Minstagram_Users_Widget extends WP_Widget {

	function __construct() {
		require_once( dirname( __FILE__ ) . '/class.minstagram-widget-model.php' );
		parent::__construct(
			false,
			__( 'Minstagram Users Widget', 'minsta' ),
			array(
				'description' => __( 'Shows Instagram images specified in users id.', 'minsta' )
			)
		);
	}

	/**
	 * Render view.
	 *
	 * @param array  $items    associative array to view.
	 * @param string $title    title for display.
	 * @param string $template name of view file.
	 */
	private function render( $data, $template = 'minstagram-widget-view.php' ) {
		extract( $data );
		include( dirname( __FILE__ ) . '/' . $template );
	}

	/**
	 * Display Widget of client side.
	 *
	 * @param array $args     Given from WordPress
	 * @param array $instance Given from WordPress
	 */
	function widget( $args, $instance ) {
		// Get title( Input escape execs in minstagram-widget-view.php.
		$title = isset( $instance['minsta_title'] ) ? $instance['minsta_title'] : '';
		// Get response data from Instagram API.
		$items = Minstagram_Widget_Model::get( $instance, 'users' );
		// Render html of response.
		$data = array(
			'minsta_items' => $items,
			'minsta_title' => $title,
		);
		$this->render( $data, 'minstagram-widget-view.php' );
	}

	/**
	 * Update Widget settings.
	 *
	 * This method update settings values in Widget form.  
	 * Required input is minsta_user_id, minsta_client_id, minsta_count.  
	 * minsta_client_id is set at Minstagram settings page(admin page).  
	 * if Required input is empty then error key is true at transient. transient is checked at view.
	 * 
	 *
	 * @param array $new_instance Given from WordPress.
	 * @param array $old_instance Given from WordPress.
	 *
	 * @return array updated settings value.
	 */
	function update( $new_instance, $old_instance ) {
		$new_instance             = (array) $new_instance;
		$options                  = (array ) json_decode( get_option( 'minsta' ) );
		$instance['minsta_title'] = $new_instance['minsta_title'];
		if ( '' !== $new_instance['minsta_user_id'] ) {
			set_transient( 'minsta_user_id_error', false, 10 );
			$instance['minsta_user_id'] = $new_instance['minsta_user_id'];
		} else {
			set_transient( 'minsta_user_id_error', true, 10 );
		}
		if ( isset( $options['minsta_client_id'] ) && '' !== $options['minsta_client_id'] ) {
			set_transient( 'minsta_client_id_error', false, 10 );
		} else {
			set_transient( 'minsta_client_id_error', true, 10 );
		}
		if ( intval( $new_instance['minsta_count'] ) > 0 ) {
			set_transient( 'minsta_count_error', false, 10 );
			$instance['minsta_count'] = intval( $new_instance['minsta_count'] );
		} else {
			set_transient( 'minsta_count_error', true, 10 );
		}

		return $instance;
	}

	/**
	 * Display Widget form of settings.
	 *
	 * This is form for individual widget settings.  
	 * Required input is minsta_user_id, minsta_client_id, minsta_count.  
	 * minsta_client_id is set at Minstagram settings page(admin page).  
	 * All output have to escape.
	 *
	 * @param array $instance Given from WordPress.
	 *
	 * @return string widget form markup.
	 */
	function form( $instance ) {
		$data = array(
			'minsta_type'            => 'users',
			'minsta_title'           => isset( $instance['minsta_title'] ) ? $instance['minsta_title'] : false,
			'minsta_user_id'         => isset( $instance['minsta_user_id'] ) ? $instance['minsta_user_id'] : '',
			'minsta_client_id'       => isset( $instance['minsta_client_id'] ) ? $instance['minsta_client_id'] : '',
			'minsta_count'           => isset( $instance['minsta_count'] ) ? $instance['minsta_count'] : '',
			// On error, Set true.
			'minsta_user_id_error'   => get_transient( 'minsta_user_id_error' ),
			'minsta_client_id_error' => get_transient( 'minsta_client_id_error' ),
			'minsta_count_error'     => get_transient( 'minsta_count_error' ),
		);
		$this->render( $data, 'minstagram-widget-form-view.php' );
	}
}