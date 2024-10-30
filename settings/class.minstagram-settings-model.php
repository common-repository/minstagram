<?php
/**
 * Model for Minstagram Settings Page
 */
class Minstagram_Settings_Model {

	/**
	 * Update options.
	 *
	 * Update option_value field. The corresponding option_name is minsta in wp_options.
	 *
	 * @param array $options associated array that is set in wp_options. Passed array id serialized.
	 *
	 * @return bool Success is true, No update or fails is false.
	 */
	public static function update( $options ) {
		if ( true === empty( $options ) ) {
			return false;
		}
		if ( ! update_option( 'minsta', json_encode( $options ) ) ) {
			return false;
		};

		return true;
	}

	/**
	 * Add to key/value pair to associated array. That array register to wp_options.
	 *
	 * @param string $key     key of array.
	 * @param string $value   value for key.
	 * @param array  $options associated array to add key/value pair.
	 *
	 * @return array associated array to register wp_options.
	 */
	public static function add_options( $key, $value, $options ) {
		if ( true === array_key_exists( $key, $options ) ) {
			$options[ $key ] = sanitize_text_field( $value );
		} else {
			$options += array(
				$key => sanitize_text_field( $value )
			);
		}

		return $options;
	}

	/**
	 * Post request to OAuth authentication server.
	 *
	 * @param array $params          Associated array parameter for getting access token.
	 *                               client id
	 *                               client_secret
	 *                               redirect_uri
	 *
	 * @return array HTTP Response that contains access token.
	 */
	public static function requestAccessToken( $params ) {
		$response = wp_remote_post( 'https://api.instagram.com/oauth/access_token',
			array(
				'method' => 'POST',
				'body'   => array(
					'client_id'     => $params['client_id'],
					'client_secret' => $params['client_secret'],
					'grant_type'    => 'authorization_code',
					'redirect_uri'  => $params['redirect_uri'],
					'code'          => $params['code'],
				),
			)
		);

		return $response;
	}

	/**
	 * OAuth認証 access token取得
	 *
	 * @param array $response access token用のInstagram APIからのレスポンスです。
	 *
	 * @return string 取得したaccess tokenです。
	 */
	public static function getAccessToken( $response ) {
		$data = json_decode( $response['body'] );

		if ( isset($data->access_token) && ! empty($data->access_token) ) {
			return $data->access_token;
		} else {
			return false;
		}
	}

	/**
	 * Get a search user endpoint.
	 *
	 * @param array $params     Parameters for create endpoint uri.
	 *                          minsta_base_uri Instagram API base uri(Required).
	 *                          minsta_user_id target user id(Required).
	 *                          minsta_client_id CLIENT ID(Either minsta_client_id or minsta_access_token Required).
	 *                          minsta_access_token(Either minsta_access_token or minsta_client_id Required).
	 *                          count number of recent post(Required).
	 *
	 * @return array associated array of method, uri
	 */
	public static function get_end_point_search_users( $params ) {
		if ( ! isset( $params['minsta_count'] ) || $params['minsta_count'] < 1 ) {
			return false;
		}
		// ACCESS TOKEN is prior to CLIENT ID.
		if ('' !==$params['minsta_access_token']) {
			$endpoint = $params['minsta_base_uri'] . '/users/' . $params['minsta_user_id']
			            . '/media/recent?access_token=' . $params['minsta_access_token']
			            . '&count=' . $params['minsta_count'];
		} else {
			$endpoint = $params['minsta_base_uri'] . '/users/' . $params['minsta_user_id']
			            . '/media/recent?client_id=' . $params['minsta_client_id']
			            . '&count=' . $params['minsta_count'];
		}
		return $endpoint;
	}

	/**
	 * Post request to OAuth authentication server for searching USER ID.
	 *
	 * @param string $param query parameter.
	 *
	 * @return array HTTP Response that contains access token.
	 */
	public static function request_search_users( $param ) {
		$response = wp_remote_get( 'https://api.instagram.com/v1/users/search'. $param);

		return $response;
	}

	/**
	 * Get user id list
	 *
	 * @param array $response access token用のInstagram APIからのレスポンスです。
	 *
	 * @return string 取得したaccess tokenです。
	 */
	public static function get_users( $response ) {
		$body = json_decode( $response['body'] );
		$users = $body->data;
		$html = '';
		foreach ( $users as $user ) {
			$html .= 'USER NAME: ' . sanitize_text_field($user->username) . ', '
			         . 'USER ID: ' . sanitize_text_field($user->id) . '<br>';
		}

		return $html;
	}
}
