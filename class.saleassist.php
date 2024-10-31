<?php

class Saleassist {
	const API_HOST = 'https://platform.saleassist.ai/api/sources/v1';
	const API_PORT = 80;
	const MAX_DELAY_BEFORE_MODERATION_EMAIL = 86400; // One day in seconds

	
	const API_HOST_TEMP 	= 'https://platform.saleassist.ai/partners/workflows/onboard_web_store_to_new_client';
	
    // const PLUGIN_WP_PATH 	= plugin_dir_path(__FILE__);
	// const PLUGIN_WP_URL 	= plugin_dir_url(__FILE__);

	private static $initiated = false;
	
	public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}

	/*
	https://my.saleassist.ai/auth/login-with-email-otp?email=<email address used in the api>
	*/
	/**
	 * Initializes WordPress hooks
	 */
	private static function init_hooks() {
		self::$initiated = true;
		
		add_action( 'update_option_saleassist_api_key', array( 'Saleassist', 'updated_option' ), 10, 2 );
		add_action( 'add_option_saleassist_api_key', array( 'Saleassist', 'added_option' ), 10, 2 );

		add_action( 'update_option_saleassist_secret_key', array( 'Saleassist', 'updated_option' ), 10, 2 );
		add_action( 'add_option_saleassist_secret_key', array( 'Saleassist', 'added_option' ), 10, 2 );

		add_action( 'wp_enqueue_scripts', array( 'Saleassist', 'saleassist_load_js' ), 10, 2);
		add_action( 'wp_footer', array( 'Saleassist', 'saleassist_load_script' ), 10, 2 );		
		
		// register shortcode
		add_shortcode('saleassist_button', array( 'Saleassist', 'saleassist_button_shortcode' )); 
		add_shortcode('saleassist_iframe', array( 'Saleassist', 'saleassist_iframe_shortcode' )); 

	}
	public static function saleassist_button_shortcode( $atts ) {	
		if(isset($atts['wid']) && !empty($atts['wid'])) {
			$style = '';
			$wid = $atts['wid'];

			//(isset($atts['bo_co']) && !empty($atts['bo_co'])) ? $style .= ' border-color:'.$atts['bo_co']." !important;" : "";
			(isset($atts['bk_co']) && !empty($atts['bk_co'])) ? $style .= ' background-color:'.$atts['bk_co']." !important;" : "";
			(isset($atts['tx_co']) && !empty($atts['tx_co'])) ? $style .= ' color:'.$atts['tx_co']." !important;" : "";
			(isset($atts['fo_si']) && !empty($atts['fo_si'])) ? $style .= ' font-size:'.$atts['fo_si']." !important;" : "";
			(isset($atts['wwi']) && !empty($atts['wwi'])) ? $style .= ' width:'.$atts['wwi']."px !important;" : "";
			(isset($atts['whi']) && !empty($atts['whi'])) ? $style .= ' height:'.$atts['whi']."px !important;" : "";

			?>
			<button onclick="EmbeddableWidget.mount({source_key: '<?php esc_attr_e( $wid );?>', form_factor: 'button'});" style="<?php esc_html_e($style);?>">Live Assistance</button>
			<?php
		} else {
			echo "<span>Widget ID not defined in shortcode ! please regenerate it.</span>";
		}
	} 

	
	public static function saleassist_iframe_shortcode($atts ) { 
		if(isset($atts['wid']) && !empty($atts['wid'])) {
			$style = '';
			$wid = $atts['wid'];

			(isset($atts['wwi']) && !empty($atts['wwi'])) ? $style .= ' width:'.$atts['wwi']."px !important;" : "";
			(isset($atts['whi']) && !empty($atts['whi'])) ? $style .= ' height:'.$atts['whi']."px !important;" : "";

			?>
			<div id="saleassistEmbed" style="<?php esc_html_e($style); ?>"></div>
			<script>EmbeddableWidget.mount({source_key: "<?php esc_attr_e( $wid );?>", parentElementId: "saleassistEmbed", form_factor: "embed"});</script>
			<?php
		} else {
			echo "<span>Widget ID not defined in shortcode ! please regenerate it.</span>";
		}
	}

	public static function saleassist_load_script() {
		global $wpdb;				
		$getWidget = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}saleassist WHERE in_use = %d", '1' ) );	
		if(!empty($getWidget)) {
			$widgetKey = $getWidget->widget_id;
			echo '<script>EmbeddableWidget.mount({source_key: "'.esc_attr($widgetKey).'"});</script>';
		}	
	}

	public static function saleassist_load_script_bkup() {
		global $wpdb;		
		// var_dump(get_post_type());
		if(get_post_type() === 'page') {
			$pageID = get_the_ID();			
			$getWidget = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}saleassist WHERE find_in_set(%d, page_list)", $pageID ) );
			
			if(empty($getWidget)){
				$getWidget = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}saleassist WHERE page_list = '*'");
			}
			
			if(!empty($getWidget)) {
				$widgetKey = $getWidget->widget_id;
				echo '<script>EmbeddableWidget.mount({source_key: "'.esc_attr($widgetKey).'"});</script>';
			} else {
				//for home page

			}
		} elseif(is_single() || get_post_type() == "post") {
			$postType = get_post_type();
			$getWidget = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}saleassist WHERE find_in_set(%s, page_list)", $postType ) );
			if(empty($getWidget)){
				$getWidget = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}saleassist WHERE post_list = '*'");
			}
			if(!empty($getWidget)) {
				$widgetKey = $getWidget->widget_id;
				echo '<script>EmbeddableWidget.mount({source_key: "'.esc_attr($widgetKey).'"});</script>';
			}
		}
	}
	
	public static function saleassist_load_js() {
		wp_register_script( 'saleassist-widget', 'https://static.saleassist.ai/widgets/widget.js', array('jquery'), SALEASSIST_VERSION );
		wp_enqueue_script( 'saleassist-widget' );			
	}

	public static function get_api_key() {
		return apply_filters( 'saleassist_get_api_key', defined('SALEASSIST_API_KEY') ? constant('SALEASSIST_API_KEY') : get_option('saleassist_api_key') );
	}
	public static function get_secret_key() {
		return apply_filters( 'saleassist_get_secret_key', defined('SALEASSIST_SECRET_KEY') ? constant('SALEASSIST_SECRET_KEY') : get_option('saleassist_secret_key') );
	}

	public static function get_widgets(){
		global $wpdb;
		$getWidgets =  $wpdb->get_results("SELECT * FROM {$wpdb->prefix}saleassist", ARRAY_A);
		
		return $getWidgets;
	}
	
	public static function get_widget_setting() {
		$pageEnable = apply_filters( 'saleassist_get_widget_setting', get_option('saleassist_page_enable') );
		$postEnable = apply_filters( 'saleassist_get_widget_setting', get_option('saleassist_post_enable') );
		return ['page_enable' => $pageEnable, 'post_enable' => $postEnable];
	}

	public static function saleassist_install_db() {
		global $wpdb;	
		$charset_collate = $wpdb->get_charset_collate();
	
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$sql = "CREATE TABLE {$wpdb->prefix}saleassist (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			title varchar(255) NULL,
			description text NULL,
			source_image_url varchar(500) NULL,
			widget_id varchar(100) DEFAULT '' NOT NULL,
			page_list varchar(255) DEFAULT '*' NULL,
			post_list varchar(255) DEFAULT '*' NULL,
			source_url text NULL,
			source_exclude_url text NULL,
			source_type varchar(255) NULL,
			is_enabled TINYINT(1) DEFAULT 0 NOT NULL,
			in_use TINYINT(1) DEFAULT 0 NOT NULL,
			created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
			updated_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";	
		dbDelta( $sql );

		$sql2 = "CREATE TABLE {$wpdb->prefix}saleassist_btn (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			class_name varchar(20) NOT NULL,
			widget_id varchar(100) NOT NULL,
			button_text varchar(255) NULL,
			button_icon varchar(255) NULL,
			style text NOT NULL,
			created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";	
		dbDelta( $sql2 );
	
		add_option( 'saleassist_db_version', constant('SALEASSIST_DB_VERSION') );

		add_option( 'saleassist_api_key', constant('SALEASSIST_TEMP_KEY') );
		add_option( 'saleassist_secret_key', constant('SALEASSIST_TEMP_SEC') );
	}

	public static function saleassist_uninstall_db() {
		global $wpdb;	
		$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}saleassist" );
		$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}saleassist_btn" );
	}

	public static function check_key_status( $key, $secret ) {
		return self::http_post( Saleassist::build_query( array( 'key' => $key, 'secret' => $secret ) ), 'verify-key', 'GET');
	}

	public static function verify_key( $key, $secret ) {		
		$response = self::check_key_status( $key, $secret );
		if( $response[1] == "errordata" )
			return 'errordata';
		elseif( $response[1] == "emptydata" )
			return 'emptydata';
		elseif ( $response[1] != 'valid' && $response[1] != 'invalid' )
			return 'failed';
		return $response[1];
	}
	
	
	public static function deactivate_key( ) {
		// Delete from options		
		delete_option( 'saleassist_alert_code' );
		delete_option( 'saleassist_alert_msg' );
		delete_option( 'saleassist_api_key' );
		delete_option( 'saleassist_client_id' );
		delete_option( 'saleassist_data' );
		delete_option( 'saleassist_db_version' );
		delete_option( 'saleassist_page_enable' );
		delete_option( 'saleassist_post_enable' );
		delete_option( 'saleassist_secret_key' );
		delete_option( 'widget_saleassist_widget' );		
		return 'valid';
	}



	/**
	 * When the saleassist option is updated, run the registration call.
	 *
	 * This should only be run when the option is updated from the Jetpack/WP.com
	 * API call, and only if the new key is different than the old key.
	 *
	 * @param mixed  $old_value   The old option value.
	 * @param mixed  $value       The new option value.
	 */
	public static function updated_option( $old_value, $value ) {
		// Not an API call
		if ( ! class_exists( 'WPCOM_JSON_API_Update_Option_Endpoint' ) ) {
			return;
		}
		// Only run the registration if the old key is different.
		if ( $old_value !== $value ) {
			self::verify_key( $value );
		}
	}

	
	
	/**
	 * Treat the creation of an API key the same as updating the API key to a new value.
	 *
	 * @param mixed  $option_name   Will always be "saleassist_api_key", until something else hooks in here.
	 * @param mixed  $value         The option value.
	 */
	public static function added_option( $option_name, $value ) {
		if ( 'saleassist_api_key' === $option_name ) {
			return self::updated_option( '', $value );
		}
		if ( 'saleassist_secret_key' === $option_name ) {
			return self::updated_option( '', $value );
		}
	}

	public static function _cmp_time( $a, $b ) {
		return $a['time'] > $b['time'] ? -1 : 1;
	}

	public static function _get_microtime() {
		$mtime = explode( ' ', microtime() );
		return $mtime[1] + $mtime[0];
	}

	/**
	 * Make a POST request to the Saleassist API.
	 *
	 * @param string $request The body of the request.
	 * @param string $path The path for the request.
	 * @param string $ip The specific IP address to hit.
	 * @return array A two-member array consisting of the headers and the response body, both empty in the case of a failure.
	 */
	public static function http_post( $request, $path, $method = 'POST' ) {
		$saleassist_ua = sprintf( 'WordPress/%s | Saleassist/%s', $GLOBALS['wp_version'], constant( 'SALEASSIST_VERSION' ) );
		$saleassist_ua = apply_filters( 'saleassist_ua', $saleassist_ua );
		
		$webinfo = array(
						"email" => SALEASSIST_ADMIN_EMAIL,
						"mobile_number" => "",
						"name" => SALEASSIST_BLOG_URL,
						"store_name" => SALEASSIST_BLOG_NAME
					);
		
		// print_r($webinfo);
		
		
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => self::API_HOST_TEMP,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => json_encode( $webinfo ),
			CURLOPT_HTTPHEADER => array(
				'api_key: '.SALEASSIST_TEMP_KEY,
				'api_secret: '.SALEASSIST_TEMP_SEC,
				'Content-Type: Application/json',
				'accept: application/json',
			),
		));

		$res = curl_exec($curl);	
		curl_close($curl);	
		$json_response = json_decode($res);
		// print_r($json_response);
		// die;
		// $json_response = json_decode($res , true);	
		//{"detail":"Api key not found"}
		//{"detail":"API Key or API Secret not valid"}
		//{"widgets":[],"client":{"id":"3131cd2d-b101-43c8-9f8b-3432c0322c22"}}
		if(isset($json_response->detail)) {
			$res = array('', "invalid");
			return $res;
		}		
		$result = $json_response;
		
		if(isset($path) && $path == "verify-key") {	
			update_option( 'saleassist_api_key', SALEASSIST_TEMP_KEY );
			update_option( 'saleassist_secret_key', SALEASSIST_TEMP_SEC );
			update_option( "saleassist_client_id", ((!empty($json_response->client->id))? $json_response->client->id : '') );
			if(empty($result)) {
				$res = array($result, "widgetempty");	
			}
			$res = array($result, "valid");	

		} else if(isset($path) && $path == "save-widget") {
			$res = array($result, "valid");			
		} 	
		return $res;
	}

	public static function save_widgets() {		
		global $wpdb;		
		$data = self::http_post( [], 'save-widget', 'GET');
		if(empty($data[0]) || $data[0] == "[]") {
			// delete all saved widgets
			$wpdb->query( "DELETE FROM {$wpdb->prefix}saleassist WHERE 1" );
			// Delete options
			return;
		}
		$result 	= $data[0]->widgets;
		$clientID 	= $data[0]->client->id;

		$in_use 	= 0;
		if(count($result) == 1) {
			$in_use = 1;
		}

		$myWidgets		= [];
		foreach($result as $key => $widget) {
			$post_url = $page_url = '*';		
			$myWidgets[] = $widget->id;
			if($key == 0) {
				update_option( "saleassist_client_id",  $clientID);
			}
			$check = $wpdb->get_row($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}saleassist WHERE widget_id = %d", $widget->id), ARRAY_A);
			if(!empty($check)) {
				// if already exist in database then update it
				$wpdb->update( 
					$wpdb->prefix.'saleassist', 
					array( 
						'title' 				=> $widget->name, 
						'description' 			=> $widget->description,
						'source_image_url'		=> $widget->source_image_url,
						'source_url'			=> $widget->source_url,
						'source_exclude_url'	=> $widget->source_exclude_url,
						'source_type'			=> $widget->source_type,
						'is_enabled' 			=> $widget->is_enabled, 
						'in_use' 				=> $in_use, 
						'updated_at' 			=> date('Y-m-d H:i:s', strtotime($widget->updated_on)) 
					), 
					array( 'widget_id' => $widget->id )
				);
				continue;
			}

			$wpdb->insert( 
				$wpdb->prefix.'saleassist', 
				array(
					'title' 				=> $widget->name, 
					'description' 			=> $widget->description,
					'widget_id' 			=> $widget->id,
					'source_image_url'		=> $widget->source_image_url,
					'page_list' 			=> $page_url,
					'post_list' 			=> $post_url,
					'source_url'			=> $widget->source_url,
					'source_exclude_url'	=> $widget->source_exclude_url,
					'source_type'			=> $widget->source_type,
					'is_enabled' 			=> $widget->is_enabled, 
					'in_use' 				=> $in_use, 
					'created_at' 			=> date('Y-m-d H:i:s', strtotime($widget->created_on)),
					'updated_at' 			=> date('Y-m-d H:i:s', strtotime($widget->updated_on)) 
				)
			);
		}
		if(count($myWidgets) > 0) {
			// remove deleted widget from database
			$oldWidgets = $wpdb->get_results("SELECT id, widget_id FROM {$wpdb->prefix}saleassist", ARRAY_A);
		
			foreach($oldWidgets as $key => $widget) {
				if(!in_array($widget['widget_id'] , $myWidgets)) {
					$wpdb->delete( $wpdb->prefix.'saleassist' , array( "widget_id" => $widget['widget_id'] ) );
				}
			}
		}		
		update_option( "saleassist_data", $data);
		return true;
	}

	// given a response from an API call like check_key_status(), update the alert code options if an alert is present.
	public static function update_alert( $response ) {
		$alert_option_prefix = 'saleassist_alert_';
		$alert_header_prefix = 'x-saleassist-alert-';
		$alert_header_names  = array(
			'code',
			'msg',
			'api-calls',
			'usage-limit',
			'upgrade-plan',
			'upgrade-url',
			'upgrade-type',
		);

		foreach ( $alert_header_names as $alert_header_name ) {
			$value = null;
			if ( isset( $response[0][ $alert_header_prefix . $alert_header_name ] ) ) {
				$value = $response[0][ $alert_header_prefix . $alert_header_name ];
			}

			$option_name = $alert_option_prefix . str_replace( '-', '_', $alert_header_name );
			if ( $value != get_option( $option_name ) ) {
				if ( ! $value ) {
					delete_option( $option_name );
				} else {
					update_option( $option_name, $value );
				}
			}
		}
	}


	
	private static function bail_on_activation( $message, $deactivate = true ) {
		?>
		<!doctype html>
		<html>
		<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<style>
		* {
			text-align: center;
			margin: 0;
			padding: 0;
			font-family: "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;
		}
		p {
			margin-top: 1em;
			font-size: 18px;
			text-align: left;
		}
		</style>
		</head>
		<body>
		<p><?php esc_html_e($message); ?></p>
		</body>
		</html>
		<?php
		if ( $deactivate ) {
			$plugins = get_option( 'active_plugins' );
			$saleassist = plugin_basename( SALEASSIST__PLUGIN_DIR . 'saleassist.php' );
			$update  = false;
			foreach ( $plugins as $i => $plugin ) {
				if ( $plugin === $saleassist ) {
					$plugins[$i] = false;
					$update = true;
				}
			}

			if ( $update ) {
				update_option( 'active_plugins', array_filter( $plugins ) );
			}
		}
		exit;
	}

	public static function view( $name, array $args = array() ) {
		$args = apply_filters( 'saleassist_view_arguments', $args, $name );
		
		foreach ( $args AS $key => $val ) {
			$$key = $val;
		}
		
		load_plugin_textdomain( 'saleassist' );

		$file = SALEASSIST__PLUGIN_DIR . 'views/'. $name . '.php';

		include( $file );
	}

	/**
	 * Attached to activate_{ plugin_basename( __FILES__ ) } by register_activation_hook()
	 * @static
	 */
	public static function plugin_activation() {
		if ( version_compare( $GLOBALS['wp_version'], SALEASSIST__MINIMUM_WP_VERSION, '<' ) ) {
			load_plugin_textdomain( 'saleassist' );
			
			$message = '<strong>'.sprintf(esc_html__( 'Saleassist %s requires WordPress %s or higher.' , 'saleassist'), SALEASSIST_VERSION, SALEASSIST__MINIMUM_WP_VERSION ).'</strong> '.sprintf(__('Please <a href="%1$s">upgrade WordPress</a> to a current version, or <a href="%2$s">downgrade to version 2.4 of the Saleassist plugin</a>.', 'saleassist'), 'https://codex.wordpress.org/Upgrading_WordPress', 'https://wordpress.org/extend/plugins/saleassist/download/');

			Saleassist::bail_on_activation( $message );
		} elseif ( ! empty( $_SERVER['SCRIPT_NAME'] ) && false !== strpos( $_SERVER['SCRIPT_NAME'], '/wp-admin/plugins.php' ) ) {
			add_option( 'Activated_Saleassist', true );
		}
		// create database table
		self::saleassist_install_db();

		// activate default plugin
		Saleassist_Admin::temp_setup();
	}

	/**
	 * Removes all connection options
	 * @static
	 */
	public static function plugin_deactivation( ) {
		self::deactivate_key( );

		// delete database table
		self::saleassist_uninstall_db();
	}
	
	/**
	 * Essentially a copy of WP's build_query but one that doesn't expect pre-urlencoded values.
	 *
	 * @param array $args An array of key => value pairs
	 * @return string A string ready for use as a URL query string.
	 */
	public static function build_query( $args ) {
		return $args;
		return _http_build_query( $args, '', '&' );
	}

	/**
	 * Log debugging info to the error log.
	 *
	 * Enabled when WP_DEBUG_LOG is enabled (and WP_DEBUG, since according to
	 * core, "WP_DEBUG_DISPLAY and WP_DEBUG_LOG perform no function unless
	 * WP_DEBUG is true), but can be disabled via the saleassist_debug_log filter.
	 *
	 * @param mixed $saleassist_debug The data to log.
	 */
	public static function log( $saleassist_debug ) {
		if ( apply_filters( 'saleassist_debug_log', defined( 'WP_DEBUG' ) && WP_DEBUG && defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG && defined( 'SALEASSIST_DEBUG' ) && SALEASSIST_DEBUG ) ) {
			error_log( print_r( compact( 'saleassist_debug' ), true ) );
		}
	}	
	public static function predefined_api_key() {
		if ( defined( 'SALEASSIST_API_KEY' ) ) {
			return true;
		}		
		return apply_filters( 'saleassist_predefined_api_key', false );
	}	
}
