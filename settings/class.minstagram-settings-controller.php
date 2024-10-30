<?php
/**
 * Minstagram Settings
 */
class Minstagram_Settings_Controller {
	/**
	 * Manage HTTP Request and Response
	 */
	function index() {

		// Get options.
		$options = (array ) json_decode( get_option( 'minsta' ) );

		/*
		 * Update settings.
		 */
		if ( isset( $_POST['type'] ) && 'update-settings' === $_POST['type'] ) {
			if ( ! current_user_can( 'edit_others_posts' ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this page', 'minsta' ) );

				return false;
			}
			check_admin_referer( 'update-settings' );
			// Check CLIENT ID. CLIENT ID is required.
			if ( isset( $_POST['minsta_client_id'] ) && '' !== $_POST['minsta_client_id'] ) {
				set_transient( 'minsta_client_id_error', false, 10 );
				$options = Minstagram_Settings_Model::add_options(
					'minsta_client_id',
					sanitize_text_field( $_POST['minsta_client_id'] ),
					$options
				);
			} else {
				set_transient( 'minsta_client_id_error', true, 10 );
				$options = Minstagram_Settings_Model::add_options(
					'minsta_client_id',
					'',
					$options
				);
			}
			// Check CLIENT SECRET.
			if ( isset( $_POST['minsta_client_secret'] ) && '' !== $_POST['minsta_client_secret'] ) {
				$options = Minstagram_Settings_Model::add_options(
					'minsta_client_secret',
					sanitize_text_field( $_POST['minsta_client_secret'] ),
					$options
				);
				set_transient( 'minsta_client_secret_error', false, 10 );
			} else {
				$options = Minstagram_Settings_Model::add_options(
					'minsta_client_secret',
					'',
					$options
				);
				set_transient( 'minsta_client_secret_error', true, 10 );
			}
			// Check ACCESS TOKEN
			if ( isset( $_POST['minsta_access_token'] ) && '' !== $_POST['minsta_access_token'] ) {
				set_transient( 'minsta_access_token_error', false, 10 );
				$options = Minstagram_Settings_Model::add_options(
					'minsta_access_token',
					sanitize_text_field( $_POST['minsta_access_token'] ),
					$options
				);
			} else {
				$options = Minstagram_Settings_Model::add_options(
					'minsta_access_token',
					'',
					$options
				);
				set_transient( 'minsta_access_token_error', true, 10 );
			}
			Minstagram_Settings_Model::update( $options );
		}

		/*
		 * Get access token.
		 */
		if ( 'GET' === strtoupper( $_SERVER['REQUEST_METHOD'] ) && isset( $_GET['code'] ) ) {
			$redirect_uri = $this->getUrl();
			$redirect_uri .= '?page=minsta-settings';
			$params              = array(
				'client_id'     => $options['minsta_client_id'],
				'client_secret' => $options['minsta_client_secret'],
				'redirect_uri'  => esc_url($redirect_uri),
				'code'          => sanitize_text_field( $_GET['code'] ),
			);
			$response            = Minstagram_Settings_Model::requestAccessToken( $params );
			$minsta_access_token = Minstagram_Settings_Model::getAccessToken( $response );
		}
		$minsta_client_id     = isset( $options['minsta_client_id'] ) ? $options['minsta_client_id'] : '';
		$minsta_client_secret = isset( $options['minsta_client_secret'] ) ? $options['minsta_client_secret'] : '';
		if ( ! empty( $minsta_client_id ) && ! empty( $minsta_client_secret ) ) {
			$minsta_access_token_uri = 'https://api.instagram.com/oauth/authorize/'
			                           . '?client_id=' . sanitize_text_field( $minsta_client_id )
			                           . '&redirect_uri=' . esc_url( $this->getUrl() )
			                           . '?page=minsta-settings&response_type=code';
		} else {
			$minsta_access_token_uri = '';
		}
		if ( isset( $options['minsta_access_token'] ) && empty( $minsta_access_token ) ) {
			$minsta_access_token = $options['minsta_access_token'];
		}
		if ( empty( $minsta_access_token ) ) {
			$minsta_access_token = '';
		}
		$minsta_search_users_uri = '';

		/*
		 * Get searched users list.
		 */
		$minsta_users = '';
		if ( isset( $_POST['type'] ) && 'search-users' === $_POST['type'] ) {
			if ( ! current_user_can( 'edit_others_posts' ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this page', 'minsta' ) );

				return false;
			}
			check_admin_referer( 'search-users' );
			if ( ! empty( $minsta_access_token ) && isset( $_POST['minsta_user_name'] ) ) {
				$query        = '?q=' . sanitize_text_field( $_POST['minsta_user_name'] )
				                . '&access_token=' . sanitize_text_field( $minsta_access_token )
				                . '&count=10';
				$response     = Minstagram_Settings_Model::request_search_users( $query );
				$minsta_users = Minstagram_Settings_Model::get_users( $response );
			}
		}

		/*
		 * Display form.
		 */
		$data = array(
			'minsta_client_id'        => sanitize_text_field( $minsta_client_id ),
			'minsta_client_secret'    => sanitize_text_field( $minsta_client_secret ),
			'minsta_access_token_uri' => sanitize_text_field( $minsta_access_token_uri ),
			'minsta_access_token'     => sanitize_text_field( $minsta_access_token ),
			'minsta_search_users_uri' => sanitize_text_field( $minsta_search_users_uri ),
			'minsta_users'            => $minsta_users,
			'minsta_user_name'        => '',
		);
		$this->render( $data );
	}

	/**
	 * Render view.
	 * 
	 * Extra data for view.
	 *
	 * @param array  $data     associative array to view.
	 * @param string $template name of view file.
	 */
	private function render( $data, $template = 'minstagram-settings-view.php' ) {
		extract( $data );
		include( dirname( __FILE__ ) . '/' . $template );
	}

	/**
	 * Get current page uri
	 *
	 * @return string current page uri.
	 */
	private function getUrl() {
		if ( isset( $_SERVER['HTTPS'] ) AND $_SERVER['HTTPS'] == 'on' ) {
			$uri = 'https://';
		} else {
			$uri = 'http://';
		}
		$uri .= $_SERVER["HTTP_HOST"];
		$uri .= $_SERVER["PHP_SELF"];

		return $uri;
	}
}
