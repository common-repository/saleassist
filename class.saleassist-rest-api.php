<?php

class SALEASSIST_REST_API {
	/**
	 * Register the REST API routes.
	 */
	public static function init() {
		if ( ! function_exists( 'register_rest_route' ) ) {
			// The REST API wasn't integrated into core until 4.4, and we support 4.0+ (for now).
			return false;
		}		
	}

	/**
	 * Get the current Saleassist API key.
	 *
	 * @param WP_REST_Request $request
	 * @return WP_Error|WP_REST_Response
	 */
	public static function get_key( $request = null ) {
		return rest_ensure_response( Saleassist::get_api_key() );
	}

	/**
	 * Unset the API key, if possible.
	 *
	 * @param WP_REST_Request $request
	 * @return WP_Error|WP_REST_Response
	 */
	public static function delete_key( $request ) {
		// die("code000547");
		global $wpdb;
		if ( defined( 'SALEASSIST_API_KEY' ) ) {
			return rest_ensure_response( new WP_Error( 'hardcoded_key', __( 'This site\'s API key is hardcoded and cannot be deleted.', 'saleassist' ), array( 'status'=> 409 ) ) );
		}
		
		
		delete_option( 'saleassist_page_enable' );
		delete_option( 'saleassist_post_enable' );
		delete_option( 'saleassist_api_key' );
		delete_option( 'saleassist_secret_key' );
		delete_option( "saleassist_data" );
		delete_option( "saleassist_client_id" );
		delete_option( "widget_saleassist_widget" );
		
		$wpdb->query("TRUNCATE TABLE {$wpdb->prefix}saleassist");

		return rest_ensure_response( true );
	}

	/**
	 * Get the Saleassist settings.
	 *
	 * @param WP_REST_Request $request
	 * @return WP_Error|WP_REST_Response
	 */
	public static function get_settings( $request = null ) {
		return rest_ensure_response( array(
			'saleassist_page_enable' => ( get_option( 'saleassist_page_enable', '1' ) === '1' ),
			'saleassist_post_enable' => ( get_option( 'saleassist_post_enable', '1' ) === '1' ),
		) );
	}

	/**
	 * Parse a numeric or string boolean value into a boolean.
	 *
	 * @param mixed $value The value to convert into a boolean.
	 * @return bool The converted value.
	 */
	public static function parse_boolean( $value ) {
		switch ( $value ) {
			case true:
			case 'true':
			case '1':
			case 1:
				return true;

			case false:
			case 'false':
			case '0':
			case 0:
				return false;

			default:
				return (bool) $value;
		}
	}

	

	/**
	 * Get the current alert code and message. Alert codes are used to notify the site owner
	 * if there's a problem, like a connection issue between their site and the Saleassist API,
	 * invalid requests being sent, etc.
	 *
	 * @param WP_REST_Request $request
	 * @return WP_Error|WP_REST_Response
	 */
	public static function get_alert( $request ) {
		return rest_ensure_response( array(
			'code' => get_option( 'saleassist_alert_code' ),
			'message' => get_option( 'saleassist_alert_msg' ),
		) );
	}

	/**
	 * Update the current alert code and message by triggering a call to the Saleassist server.
	 *
	 * @param WP_REST_Request $request
	 * @return WP_Error|WP_REST_Response
	 */
	public static function set_alert( $request ) {
		delete_option( 'saleassist_alert_code' );
		delete_option( 'saleassist_alert_msg' );
		
		// Make a request so the most recent alert code and message are retrieved.
		Saleassist::verify_key( Saleassist::get_api_key(), Saleassist::get_secret_key()  );

		return self::get_alert( $request );
	}

	/**
	 * Clear the current alert code and message.
	 *
	 * @param WP_REST_Request $request
	 * @return WP_Error|WP_REST_Response
	 */
	public static function delete_alert( $request ) {
		delete_option( 'saleassist_alert_code' );
		delete_option( 'saleassist_alert_msg' );

		return self::get_alert( $request );
	}

	private static function key_is_valid( $key ) {
		// die("code000548");
		$response = Saleassist::http_post(
			Saleassist::build_query(
				array(
					'blog' => get_option( 'home' )
				)
			),
			'verify-key'
		);

		if ( $response[1] == 'valid' ) {
			return true;
		}

		return false;
	}

	public static function privileged_permission_callback() {
		return current_user_can( 'manage_options' );
	}

	/**
	 * For calls that Saleassist.ai makes to the site to clear outdated alert codes, use the API key for authorization.
	 */
	public static function remote_call_permission_callback( $request ) {
		$local_key = Saleassist::get_api_key();

		return $local_key && ( strtolower( $request->get_param( 'key' ) ) === strtolower( $local_key ) );
	}

	public static function sanitize_interval( $interval, $request, $param ) {
		$interval = trim( $interval );

		$valid_intervals = array( '60-days', '6-months', 'all', );

		if ( ! in_array( $interval, $valid_intervals ) ) {
			$interval = 'all';
		}

		return $interval;
	}

	public static function sanitize_key( $key, $request, $param ) {
		return trim( $key );
	}
}
