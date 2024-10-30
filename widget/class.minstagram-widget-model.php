<?php
/**
 * Model for Minstagram_Users_Widget.
 *
 * @author    Hiroshi Sawai <info@info-town.jp>
 * @copyright Hiroshi Sawai
 * @version   0.0.1
 * @since     0.0.1
 */
class Minstagram_Widget_Model {


	/**
	 * Variables for creating endpoint
	 * 
	 * @param object $instance widget form input data. Get from WordPress.
	 * @param string $type api type. users or tags.
	 */
	private static function create_params( $instance, $type ) {
		$data   = array();
		$errors = array();
		$options                  = (array ) json_decode( get_option( 'minsta' ) );

		$data['minsta_base_uri'] = MINSTAGRAM_API_BASE_URI;

		if ( isset( $options['minsta_access_token'] ) && '' !== $options['minsta_access_token'] ) {
			$data['minsta_access_token'] = $options['minsta_access_token'];
		} else {
			$data['minsta_access_token']   = '';
		}
		if ( isset( $options['minsta_client_id'] ) && '' !== $options['minsta_client_id'] ) {
			$data['minsta_client_id'] = $options['minsta_client_id'];
		} else {
			$data['minsta_client_id']   = '';
			$errors['minsta_client_id'] = __( 'CLIENT ID is not founded. CLIENT ID is required.', 'minsta' );
		}
		if ( isset( $instance['minsta_user_id'] ) && ( '' !== $instance['minsta_user_id'] ) ) {
			$data['minsta_user_id'] = $instance['minsta_user_id'];
		} else {
			$errors['minsta_user_id'] = __( 'user id is not founded or Invalid. user id is required', 'minsta' );
		}
		if ( isset( $instance['minsta_tag'] ) && ( '' !== $instance['minsta_tag'] ) ) {
			$data['minsta_tag'] = $instance['minsta_tag'];
		} else {
			$errors['minsta_tag'] = __( 'tag is not founded or Invalid. tag is required', 'minsta' );
		}
		if ( isset( $instance['minsta_count'] ) && intval( $instance['minsta_count'] ) ) {
			$data['minsta_count'] = $instance['minsta_count'];
		} else {
			$error['minsta_count'] = __( 'count is not founded. count is required.', 'minsta' );
		}
		if ('users' === $type) {
			if ( isset( $errors['minsta_user_id'] ) || isset( $errors['minsta_count'] ) ) {
				return false;
			}
		}
		if ( 'tags' === $type ) {
			if ( isset( $errors['minsta_tag'] ) || isset( $errors['minsta_count'] ) ) {
				return false;
			}
		}

		return $data;
	}

	/**
	 * Get a user endpoint.
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
	public static function get_end_point_users_recent( $params ) {
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
	 * Get a tags endpoint.
	 *
	 * @param array $params     Parameters for create endpoint uri.
	 *                          minsta_base_uri Instagram API base uri(Required).
	 *                          minsta_tag(Required).
	 *                          minsta_client_id CLIENT ID(Either minsta_client_id or minsta_access_token Required).
	 *                          minsta_access_token(Either minsta_access_token or minsta_client_id Required)
	 *                          count number of recent post(Required).
	 *
	 * @return array associated array of method, uri
	 */
	public static function get_end_point_tags_recent( $params ) {
		if ( ! isset( $params['minsta_count'] ) || $params['minsta_count'] < 1 ) {
			return false;
		}
		if ('' !==$params['minsta_access_token']) {
			$endpoint = $params['minsta_base_uri'] . '/tags/' . $params['minsta_tag']
			            . '/media/recent?access_token=' . $params['minsta_access_token']
			            . '&count=' . $params['minsta_count'];
		} else {
			$endpoint = $params['minsta_base_uri'] . '/tags/' . $params['minsta_tag']
			            . '/media/recent?client_id=' . $params['minsta_client_id']
			            . '&count=' . $params['minsta_count'];
		}
		return $endpoint;
	}

	/**
	 * Get response data from Instagram API.
	 *
	 * @param array $instance associated array for creating endpoint.
	 *                        user_name
	 *                        user_id | tag
	 *                        client_id
	 *                        access_token (Optional)
	 *                        base_uri
	 *                        count
	 *
	 * @return mixed(array|boolean) associated data for display to widget. if error then return false.
	 */
	public static function get( $instance, $type = 'users' ) {
		$params = self::create_params( $instance, $type );
		if ( ! $params ) {
			return false;
		}
		if ( 'users' === $type ) {
			$endpoint = self::get_end_point_users_recent( $params );
		}
		if ( 'tags' === $type ) {
			$endpoint = self::get_end_point_tags_recent( $params );
		}
		$data = self::get_response_data( $endpoint );
		// Check response code
		if ( '200' !== (string) $data['response']['code'] ) {
			return false;
		}
		// Get associated array from Response data.
		$items = self::get_display_data( $data['body'], 'thumbnail' );

		return $items;
	}

	/**
	 * Get data from Instagram
	 *
	 * This method get data from specified endpoint by arg.
	 *
	 * @return array Instagram associated array data.
	 */
	private static function get_response_data( $endpoint ) {
		$args     = array(
			'headers' => array(
				'content-type' => 'application/json; charset=utf-8'
			)
		);
		$data = wp_remote_get( $endpoint, $args );
		return $data; 

	}

	/**
	 * Converts JSON string to associated array to get display data at widget area.
	 *
	 * @param string $json JSON string
	 * @param string $type display format type( only 'thumbnail')
	 *
	 * @return array associated array to display data at widget area
	 */
	private static function get_display_data( $json, $type = 'thumbnail' ) {
		if ( 'thumbnail' !== $type ) {
			return false;
		}
		$data  = array();
		$items = json_decode( $json )->data;
		if ( ! empty( $json ) ) {
			foreach ( $items as $item ) {
				$thumb_uri = $item->images->thumbnail->url;
				$link      = $item->link;
				$data[] = array(
					'thumb_uri' => esc_url( $thumb_uri ),
					'link'      => esc_url( $link )
				);
			}
		}

		return $data;
	}
}
