<?php
class Saleassist_Admin {
	const NONCE = 'saleassist-update-key';

	private static $initiated = false;
	private static $notices   = array();
	private static $allowed   = array(
	    'a' => array(
	        'href' => true,
	        'title' => true,
	    ),
	    'b' => array(),
	    'code' => array(),
	    'del' => array(
	        'datetime' => true,
	    ),
	    'em' => array(),
	    'i' => array(),
	    'q' => array(
	        'cite' => true,
	    ),
	    'strike' => array(),
	    'strong' => array(),
	);

	public static function init() {
		if ( ! self::$initiated ) {			
			self::init_hooks();
		}
		$postdata = map_deep($_POST, 'sanitize_text_field');
		
		//$action = sanitize_text_field($_POST['action']);
		
		if(!isset( $postdata['action'] ) || empty( $postdata['action'] ) || $postdata['action'] == "") {
			return false;
		}
		$action = $postdata['action'];
		if ( $action == 'temp-setup' ) {
			self::temp_setup();
		}
		if ( $action == 'enter-key' ) {
			self::enter_api_key();
		}
		if ( $action == 'setup-chat' ) {
			self::setup_chat();
		}		
		if ( $action == 'update-widget-setting' ) {
			self::update_widget_setting();
			//wp_redirect( add_query_arg( array( 'page' => 'saleassist-key-config', 'view' => 'widget', 'show' => $_POST['setup_widget_id'] ), admin_url( 'options-general.php' ) ) );
		}	
		if ( $action == 'active-widget-inuse' ) {
			self::active_widget_inuse();
			//wp_redirect( add_query_arg( array( 'page' => 'saleassist-key-config', 'view' => 'widget', 'show' => $_POST['setup_widget_id'] ), admin_url( 'options-general.php' ) ) );
		}	
		if ( $action == 'save-button-ui') {
			self::save_button_ui();;
		}	
		return true;
	}

	public static function init_hooks() {
		
		self::$initiated = true;

		add_action( 'admin_init', array( 'Saleassist_Admin', 'admin_init' ) );
		add_action( 'admin_menu', array( 'Saleassist_Admin', 'admin_menu' ), 6 ); # Priority 5
		add_action( 'admin_notices', array( 'Saleassist_Admin', 'display_notice' ) );
		add_action( 'admin_enqueue_scripts', array( 'Saleassist_Admin', 'load_resources' ) );
		add_action( 'activity_box_end', array( 'Saleassist_Admin', 'dashboard_stats' ) );
		add_action( 'rightnow_end', array( 'Saleassist_Admin', 'rightnow_stats' ) );
		
		//remove code iis
		add_action( 'admin_action_saleassist_recheck_queue', array( 'Saleassist_Admin', 'recheck_queue' ) );
		add_action( 'wp_ajax_saleassist_recheck_queue', array( 'Saleassist_Admin', 'recheck_queue' ) );

		add_filter( 'plugin_action_links', array( 'Saleassist_Admin', 'plugin_action_links' ), 10, 2 );
		add_filter( 'plugin_action_links_'.plugin_basename( plugin_dir_path( __FILE__ ) . 'saleassist.php'), array( 'Saleassist_Admin', 'admin_plugin_settings_link' ) );
		
		add_filter( 'all_plugins', array( 'Saleassist_Admin', 'modify_plugin_description' ) );
		

	}

	public static function admin_init() {
		if ( get_option( 'Activated_Saleassist' ) ) {
			delete_option( 'Activated_Saleassist' );
			if ( ! headers_sent() ) {
				// wp_redirect( add_query_arg( array( 'page' => 'saleassist-key-config', 'view' => 'start' ), admin_url( 'options-general.php' ) ) );
				wp_redirect( add_query_arg( array( 'page' => 'saleassist-key-config' ), admin_url( 'admin.php' ) ) );
			}
		}

		load_plugin_textdomain( 'saleassist' );
	
		if ( function_exists( 'wp_add_privacy_policy_content' ) ) {
			wp_add_privacy_policy_content(
				__( 'Saleassist', 'saleassist' ),
				__( 'We collect information about visitors who comment on Sites that use our Saleassist live chat service. The information we collect depends on how the User sets up Saleassist for the Site, but typically includes the commenter\'s IP address, user agent, referrer, and Site URL (along with other information directly provided by the commenter such as their name, username, email address, and the comment itself).', 'saleassist' )
			);
		}
	}

	public static function admin_menu() {
		self::load_menu();
	}

	public static function admin_head() {
		if ( !current_user_can( 'manage_options' ) )
			return;
	}
	
	public static function admin_plugin_settings_link( $links ) { 
  		$settings_link = '<a href="'.esc_url( self::get_page_url() ).'">'.__('Settings', 'saleassist').'</a>';
  		array_unshift( $links, $settings_link ); 
  		return $links; 
	}

	public static function load_menu() {
		
		// $hook = add_options_page( __('SaleAssist Manager', 'saleassist'), __('SaleAssist Manager', 'saleassist'), 'manage_options', 'saleassist-key-config', array( 'Saleassist_Admin', 'display_page' ) );
		
		// if ( $hook ) {
		// 	add_action( "load-$hook", array( 'Saleassist_Admin', 'admin_help' ) );
		// }

		$hookname = add_menu_page(
			__('SaleAssist Manager', 'saleassist'),
			__('SaleAssist', 'saleassist'),
			'manage_options',
			'saleassist-key-config',
			array( 'Saleassist_Admin', 'display_page' ),
			plugin_dir_url(__FILE__) . 'assets/images/icon.png',
			// 'dashicons-embed-video',
			20
		);

		add_action( 'load-' . $hookname, array( 'Saleassist_Admin', 'admin_help')  );
		
		
	
		// add_action( 'admin_menu' , 'linkedurl_function' );
		

	}
	public function linked_url() {
		add_menu_page( 'linked_url', 'Saleassist', 'read', 'my_slug', '', 'dashicons-embed-video', 1 );
	}
	public function linkedurl_function() {
		global $menu;
		$menu[1][2] = "http://www.example.com";
	}

	public static function load_resources() {
		global $hook_suffix;
		if ( in_array( $hook_suffix, apply_filters( 'saleassist_admin_page_hook_suffixes', array(
			'index.php', # dashboard
			'edit-comments.php',
			'comment.php',
			'post.php',
			'settings_page_saleassist-key-config', // for options
			'toplevel_page_saleassist-key-config', // for admin page
			'plugins.php',
		) ) ) ) {
			wp_enqueue_style("jquery-ui-core");

			wp_register_script('saleassist-jquery', 'https://code.jquery.com/jquery-3.6.1.min.js'); // Custom scripts
			wp_enqueue_script('saleassist-jquery'); // Enqueue it!
			
			wp_enqueue_script( 'jquery-ui-core');
			wp_enqueue_script( 'jquery-ui-slider');
			
			
			wp_register_script('Popper', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js'); // Custom scripts
			wp_enqueue_script('Popper'); // Enqueue it!
			
			wp_register_script( 'colorpicker.js', plugin_dir_url( __FILE__ ) . 'assets/colorpicker/js/colorpicker.js', array('jquery'), SALEASSIST_VERSION, true );
			wp_enqueue_script( 'colorpicker.js' );
			wp_register_style( 'colorpicker.css', plugin_dir_url( __FILE__ ) . 'assets/colorpicker/css/colorpicker.css', array(), SALEASSIST_VERSION );
			wp_enqueue_style( 'colorpicker.css');
			
			
			wp_register_style( 'style.css', plugin_dir_url( __FILE__ ) . '_inc/styles.css', array(), SALEASSIST_VERSION );
			wp_enqueue_style( 'style.css');

			wp_register_script( 'zclip.js', plugin_dir_url( __FILE__ ) . 'assets/js/jquery.zclip.min.js', array('jquery'), SALEASSIST_VERSION );
			wp_enqueue_script( 'zclip.js' );
			wp_register_script( 'scripts.js', plugin_dir_url( __FILE__ ) . 'assets/js/scripts.js', array('jquery'), SALEASSIST_VERSION, true );
			wp_enqueue_script( 'scripts.js' );

			wp_register_script( 'bootstrap.js', plugin_dir_url( __FILE__ ) . 'assets/bootstrap-4.3.1-dist/js/bootstrap.min.js', array('jquery'), SALEASSIST_VERSION, true );
			wp_enqueue_script( 'bootstrap.js' );
			wp_register_style( 'bootstrap.css', plugin_dir_url( __FILE__ ) . 'assets/bootstrap-4.3.1-dist/css/bootstrap.min.css', array(), SALEASSIST_VERSION );
			wp_enqueue_style( 'bootstrap.css');
			
			wp_register_style( 'select2.css', plugin_dir_url( __FILE__ ) . 'assets/select2/select2.min.css', array(), SALEASSIST_VERSION );
			wp_enqueue_style( 'select2.css');
			wp_register_script( 'select2.js', plugin_dir_url( __FILE__ ) . 'assets/select2/select2.min.js', array('jquery'), SALEASSIST_VERSION, true );
			wp_enqueue_script( 'select2.js' );

			wp_register_style( 'saleassist.css', plugin_dir_url( __FILE__ ) . '_inc/saleassist.css', array(), SALEASSIST_VERSION );
			wp_enqueue_style( 'saleassist.css');

			wp_register_script( 'saleassist.js', plugin_dir_url( __FILE__ ) . '_inc/saleassist.js', array('jquery'), SALEASSIST_VERSION );
			wp_enqueue_script( 'saleassist.js' );
			
			

			$inline_js = array(
				'comment_author_url_nonce' => wp_create_nonce( 'comment_author_url_nonce' ),
				'strings' => array(
					'Remove this URL' => __( 'Remove this URL' , 'saleassist'),
					'Removing...'     => __( 'Removing...' , 'saleassist'),
					'URL removed'     => __( 'URL removed' , 'saleassist'),
					'(undo)'          => __( '(undo)' , 'saleassist'),
					'Re-adding...'    => __( 'Re-adding...' , 'saleassist'),
				)
			);

			if ( isset( $_GET['saleassist_recheck'] ) && wp_verify_nonce( $_GET['saleassist_recheck'], 'saleassist_recheck' ) ) {
				$inline_js['start_recheck'] = true;
			}

			if ( apply_filters( 'saleassist_enable_mshots', true ) ) {
				$inline_js['enable_mshots'] = true;
			}

			wp_localize_script( 'saleassist.js', 'WPSaleassist', $inline_js );
		}
	}

	/**
	 * Add help to the Saleassist page
	 *
	 * @return false if not the Saleassist page
	 */
	public static function admin_help() {
		$current_screen = get_current_screen();

		// Screen Content
		if ( current_user_can( 'manage_options' ) ) {
			/*
			if ( !Saleassist::get_api_key() || ( isset( $_GET['view'] ) && $_GET['view'] == 'start' ) ) {
				//setup page
				$current_screen->add_help_tab(
					array(
						'id'		=> 'overview',
						'title'		=> __( 'Overview' , 'saleassist'),
						'content'	=>
							'<p><strong>' . esc_html__( 'Saleassist Setup' , 'saleassist') . '</strong></p>' .
							'<ul><li>' . esc_html__( 'Compatible with WordPress222' , 'saleassist') . '</li>' .
							'<li>' . esc_html__( 'Making e-commerce human again' , 'saleassist') . '</li>',
							'<li>' . esc_html__( 'Recreate the in-store experience online' , 'saleassist') . '</li>',
							'<li>' . esc_html__( 'Higher conversions with video conversations' , 'saleassist') . '</li>',
							'<li>' . esc_html__( 'The best interface is your face' , 'saleassist') . '</li>',
							'<li>' . esc_html__( 'Record video feedback & testimonials' , 'saleassist') . '</li>',
							'<li>' . esc_html__( 'Schedule video meet at the convenience' , 'saleassist') . '</li>',
							'<li>' . esc_html__( 'Group shopping experience' , 'saleassist') . '</li></ul>',
					)
				);

				$current_screen->add_help_tab(
					array(
						'id'		=> 'setup-signup',
						'title'		=> __( 'New to Saleassist' , 'saleassist'),
						'content'	=>
							'<p><strong>' . esc_html__( 'Saleassist Setup' , 'saleassist') . '</strong></p>' .
							'<p>' . esc_html__( 'You need to enter an API key and Secret key to activate the Saleassist service on your site.' , 'saleassist') . '</p>' .
							'<p>' . sprintf( __( 'Sign up for an account on %s to get an API Key.' , 'saleassist'), '<a href="https://saleassist.ai/plugin-signup/" target="_blank">Saleassist.ai</a>' ) . '</p>',
					)
				);

				$current_screen->add_help_tab(
					array(
						'id'		=> 'setup-manual',
						'title'		=> __( 'Enter an API Key & Secret Key' , 'saleassist'),
						'content'	=>
							'<p><strong>' . esc_html__( 'Saleassist Setup' , 'saleassist') . '</strong></p>' .
							'<p>' . esc_html__( 'If you already have an API key & Secret Key' , 'saleassist') . '</p>' .
							'<ol>' .
								'<li>' . esc_html__( 'Copy and paste the both API key and Secret key into the text fields.' , 'saleassist') . '</li>' .
								'<li>' . esc_html__( 'Click the Use this Key button.' , 'saleassist') . '</li>' .
							'</ol>',
					)
				);
			}
			elseif ( isset( $_GET['view'] ) && $_GET['view'] == 'stats' ) {
				//stats page
				$current_screen->add_help_tab(
					array(
						'id'		=> 'overview',
						'title'		=> __( 'Overview' , 'saleassist'),
						'content'	=>
							'<p><strong>' . esc_html__( 'Saleassist Stats' , 'saleassist') . '</strong></p>' .
							'<p>' . esc_html__( 'Saleassist provide you live chatting and meeting support.' , 'saleassist') . '</p>' .
							'<p>' . esc_html__( 'On this page, you are able to view stats on your site.' , 'saleassist') . '</p>',
					)
				);
			}
			else {*/
				//configuration page
				$current_screen->add_help_tab(
					array(
						'id'		=> 'features',
						'title'		=> __( 'Features' , 'saleassist'),
						'content'	=>
							'<p><h4>' . esc_html__( 'Saleassist Features' , 'saleassist') . '</h4></p>'.
							'<h5>' . esc_html__( 'Improve conversion ratio and make you website live with Video Commerce by enabling Live video shopping on your website' , 'saleassist') . '</h5>' .
							'<ul><li>' . esc_html__( 'One to One Video shopping' , 'saleassist') . '</li>' .
							'<li>' . esc_html__( 'One to Many Live streaming selling' , 'saleassist') . '</li>'.
							'<li>' . esc_html__( 'Schedule call button' , 'saleassist') . '</li>'.
							'<li>' . esc_html__( 'Short/ Shoppable Videos' , 'saleassist') . '</li>'.
							'<li>' . esc_html__( 'Video Emails' , 'saleassist') . '</li>'.
							'<li>' . esc_html__( 'AI Videos' , 'saleassist') . '</li>'.
							'<li>' . esc_html__( 'Video Intents' , 'saleassist') . '</li></ul>',
					)
				);

				// $current_screen->add_help_tab(
				// 	array(
				// 		'id'		=> 'settings',
				// 		'title'		=> __( 'Settings' , 'saleassist'),
				// 		'content'	=>
				// 			'<p><strong>' . esc_html__( 'Saleassist Configuration' , 'saleassist') . '</strong></p>' .
				// 			( Saleassist::predefined_api_key() ? '' : '<p><strong>' . esc_html__( 'API Key' , 'saleassist') . '</strong> - ' . esc_html__( 'Enter/remove an API key.' , 'saleassist') . '</p>' ) .
				// 			( Saleassist::predefined_api_key() ? '' : '<p><strong>' . esc_html__( 'Secret Key' , 'saleassist') . '</strong> - ' . esc_html__( 'Enter/remove an Secret key.' , 'saleassist') . '</p>' ),
				// 	)
				// );

				// if ( ! Saleassist::predefined_api_key() ) {
				// 	$current_screen->add_help_tab(
				// 		array(
				// 			'id'		=> 'account',
				// 			'title'		=> __( 'Account' , 'saleassist'),
				// 			'content'	=>
				// 				'<p><strong>' . esc_html__( 'Saleassist Configuration' , 'saleassist') . '</strong></p>' .
				// 				'<p><strong>' . esc_html__( 'Subscription Type' , 'saleassist') . '</strong> - ' . esc_html__( 'The Saleassist subscription plan' , 'saleassist') . '</p>' .
				// 				'<p><strong>' . esc_html__( 'Status' , 'saleassist') . '</strong> - ' . esc_html__( 'The subscription status - active, cancelled or suspended' , 'saleassist') . '</p>',
				// 		)
				// 	);
				// }
			//}
		}

		// Help Sidebar
		$current_screen->set_help_sidebar(
			'<p><strong>' . esc_html__( 'For more information:' , 'saleassist') . '</strong></p>' .
			'<p><a href="https://www.saleassist.ai/pricing/" target="_blank">'     . esc_html__( 'Saleassist FAQ' , 'saleassist') . '</a></p>' .
			'<p><a href="https://calendly.com/chetanj/30min" target="_blank">'     . esc_html__( 'Book a Demo' , 'saleassist') . '</a></p>'.
			'<p>For support <a href="https://www.saleassist.ai/demo" target="_blank">' . esc_html__( 'Click here' , 'saleassist') . '</a> <b>OR</b> write us at <a href="mail:support@saleassist.ai" target="_blank">' . esc_html__( 'support@saleassist.ai' , 'saleassist') . '</a></p>'
		);
	}
	
	public static function temp_setup() {
		
		if ( ! current_user_can( 'manage_options' ) ) {
			die( __( 'Cheatin&#8217; uh?', 'saleassist' ) );
		}
		// if ( !wp_verify_nonce( $_POST['_wpnonce'], self::NONCE ) )
		// 	return false;
		if ( Saleassist::predefined_api_key() ) {
			return false; //shouldn't have option to save key if already defined
		}
		
		$new_key 		= sanitize_text_field(SALEASSIST_TEMP_KEY);
		$new_secret 	= sanitize_text_field(SALEASSIST_TEMP_SEC);

		$old_key 		= Saleassist::get_api_key();
		$old_secret 	= Saleassist::get_secret_key();

		if ( empty( $new_key ) || empty( $new_secret ) ) {
			if ( empty( $new_key ) ) {
				if ( !empty( $old_key ) ) {
					delete_option( 'saleassist_api_key' );
					self::$notices[] = 'new-key-empty';
				}
			}
			if ( empty( $new_secret ) ) {
				if ( !empty( $old_secret ) ) {
					delete_option( 'saleassist_secret_key' );
					self::$notices[] = 'new-key-empty';
				}
			}
		} else {
			$saveKey	= $old_key;
			$saveSecret	= $old_secret;
			if ( $new_key != $old_key ) {
				$saveKey	= $new_key;
			}
			if ( $new_secret != $old_secret ) {
				$saveSecret	= $new_secret;
			}
			
			self::save_key( $saveKey, $saveSecret );
		}
		return true;
	}

	public static function enter_api_key() {
		if ( ! current_user_can( 'manage_options' ) ) {
			die( __( 'Cheatin&#8217; uh?', 'saleassist' ) );
		}
		if ( !wp_verify_nonce( $_POST['_wpnonce'], self::NONCE ) )
			return false;
		
		if ( Saleassist::predefined_api_key() ) {
			return false; //shouldn't have option to save key if already defined
		}
		
		$new_key 		= sanitize_text_field($_POST['key']);
		$new_secret 	= sanitize_text_field($_POST['secret']);

		$old_key 		= Saleassist::get_api_key();
		$old_secret 	= Saleassist::get_secret_key();

		if ( empty( $new_key ) || empty( $new_secret ) ) {
			if ( empty( $new_key ) ) {
				if ( !empty( $old_key ) ) {
					delete_option( 'saleassist_api_key' );
					self::$notices[] = 'new-key-empty';
				}
			}
			if ( empty( $new_secret ) ) {
				if ( !empty( $old_secret ) ) {
					delete_option( 'saleassist_secret_key' );
					self::$notices[] = 'new-key-empty';
				}
			}
		} else {
			$saveKey	= $old_key;
			$saveSecret	= $old_secret;
			if ( $new_key != $old_key ) {
				$saveKey	= $new_key;
			}
			if ( $new_secret != $old_secret ) {
				$saveSecret	= $new_secret;
			}
			self::save_key( $saveKey, $saveSecret );
		}
		return true;
	}

	public static function setup_chat() {
		if ( ! current_user_can( 'manage_options' ) ) {
			die( __( 'Cheatin&#8217; uh?', 'saleassist' ) );
		}
		if ( !wp_verify_nonce( sanitize_text_field($_POST['_wpnonce']), self::NONCE ) )
			return false;
		
		$widget_key 		= sanitize_text_field($_POST['widget_id']);
		
		$old_key 		= Saleassist::get_api_key();
		$old_secret 	= Saleassist::get_secret_key();

		// if ( empty( $widget_key ) ) {
		// 	self::$notices[] = 'new-key-empty';
		// } else {
		// 	if($widget_key != $old_widget_key) {
		// 		update_option('saleassist_widget_key', $widget_key);
		// 		self::$notices['status'] = 'widget-update';
		// 	}
		// }
		return true;
	}

	public static function update_widget_setting() {
		if ( ! current_user_can( 'manage_options' ) ) {
			die( __( 'Cheatin&#8217; uh?', 'saleassist' ) );
		}
		if ( !wp_verify_nonce( sanitize_text_field($_POST['_wpnonce']), self::NONCE ) )
			return false;
		
		$widgetID			= sanitize_text_field($_POST['setup_widget_id']);	
		$radio_page_data	= sanitize_text_field($_POST['radio_page']);
		$radio_post_data	= sanitize_text_field($_POST['radio_post']);
		
		if(isset($radio_page_data) && $radio_page_data == "all") {
			$page_setup = "*";
		} else {
			$page_setup_data	= map_deep( $_POST['page_setup'], 'sanitize_text_field' );
			$page_setup = implode(",",$page_setup_data);
		}
		
		if(isset($radio_post_data) && $radio_post_data == "all") {
			$post_setup = "*";
		} else {
			$post_setup_data	= map_deep($_POST['post_setup'], 'sanitize_text_field');
			$post_setup = implode(",",$post_setup_data);		
		}
		global $wpdb;

		$wpdb->update($wpdb->prefix.'saleassist' , array('page_list'=> $page_setup, 'post_list' => $post_setup), array('widget_id' => $widgetID) );
		
		self::$notices['status'] = 'setting-updated';		
		return true;

	}

	public static function active_widget_inuse() {
		if ( ! current_user_can( 'manage_options' ) ) {
			die( __( 'Cheatin&#8217; uh?', 'saleassist' ) );
		}
		if ( !wp_verify_nonce( sanitize_text_field($_POST['_wpnonce']), self::NONCE ) )
			return false;
		
		global $wpdb;

		$widgetID			= sanitize_text_field($_POST['setup_widget_id']);

		$wpdb->query( 
			$wpdb->prepare( 
				"UPDATE ".$wpdb->prefix."saleassist
				 SET `in_use` = %s",
				 '0'
			)
		);
		// $wpdb->update($wpdb->prefix.'saleassist' , array('in_use'=> 2), null );
		$wpdb->update($wpdb->prefix.'saleassist' , array('in_use'=> 1), array('widget_id' => $widgetID) );
		
		self::$notices['status'] = 'setting-updated';		
		return true;
	}

	public static function save_key( $api_key, $secret_key ) {
		$key_status 	= Saleassist::verify_key( $api_key, $secret_key );		
		if ( $key_status == 'valid' ) {
			Saleassist::save_widgets();			
		} elseif ( $key_status == 'emptydata' ) {
			self::$notices['status'] = 'new-key-'.$key_status;
		} 
		elseif ( in_array( $key_status, array( 'invalid', 'failed' ) ) ) {
			self::$notices['status'] = 'new-key-'.$key_status;
		}
		/*
		if ( $saleassist_user ) {				
				self::$notices['status'] = 'new-key-valid';
			} else
				self::$notices['status'] = 'new-key-invalid';
		}	
		*/
		return true;
	}

	public static function dashboard_stats() {
		if ( did_action( 'rightnow_end' ) ) {
			return; // We already displayed this info in the "Right Now" section
		}


		global $submenu;

		echo '<h3>' . esc_html( _x( 'Spam', 'comments' , 'saleassist') ) . '</h3>';

		echo '<p>'.sprintf( _n(
				'<a href="%1$s">Saleassist</a> has protected your site from <a href="%2$s">%3$s spam comment</a>.',
				'<a href="%1$s">Saleassist</a> has protected your site from <a href="%2$s">%3$s spam comments</a>.',
				esc_attr($count)
			, 'saleassist'), 'https://saleassist.ai/wordpress/', esc_url( add_query_arg( array( 'page' => 'saleassist-admin' ), admin_url( isset( $submenu['edit-comments.php'] ) ? 'edit-comments.php' : 'edit.php' ) ) ), number_format_i18n(150) ).'</p>';
	}

	public static function save_button_ui() {
		if ( ! current_user_can( 'manage_options' ) ) {
			die( __( 'Cheatin&#8217; uh?', 'saleassist' ) );
		}
		if ( !wp_verify_nonce( sanitize_text_field($_POST['_wpnonce']), self::NONCE ) )
			return false;
		//print_r($_POST);
	}

	// WP 2.5+
	public static function rightnow_stats() {
		echo "<h3>Saleassist Info</h3><p class='saleassist-right-now'>Your website is intigrated with saleassist plugin which is mainly used for provide live meeting feature to your website, configure it and use it to attract your valuable customers.</p>";
	}

	public static function plugin_action_links( $links, $file ) {
		if ( $file == plugin_basename( plugin_dir_url( __FILE__ ) . '/saleassist.php' ) ) {
			$links[] = '<a href="' . esc_url( self::get_page_url() ) . '">'.esc_html__( 'Settings' , 'saleassist').'</a>';
		}
		return $links;
	}
	
	// Simpler connectivity check
	public static function check_server_connectivity($cache_timeout = 86400) {
		return true;		// iis rework
	}

	// Check the server connectivity and store the available servers in an option. 
	public static function get_server_connectivity($cache_timeout = 86400) {
		return true;		// iis rework
	}

	public static function get_page_url( $page = 'config', $queryd = "" ) {

		$args = array( 'page' => 'saleassist-key-config' );
		
		if ( $page == 'stats' )
			$args = array( 'page' => 'saleassist-key-config', 'view' => 'stats' );
		if ( $page == 'view' )
			$args = array( 'page' => 'saleassist-key-config', 'view' => 'widget' );
		elseif ( $page == 'delete_key' )
			$args = array( 'page' => 'saleassist-key-config', 'view' => 'start', 'action' => 'delete-key', '_wpnonce' => wp_create_nonce( self::NONCE ) );
		elseif ( $page == 'view_widget' ) {
			$args = array( 'page' => 'saleassist-key-config', 'view' => 'widget', 'show' => $queryd );
		}
		$url = add_query_arg( $args, admin_url( 'options-general.php' ) );
		
		return $url;
	}
	
	public static function get_saleassist_user( ) {
		$api_key   		= Saleassist::get_api_key();
		$secret_key   	= Saleassist::get_secret_key();
		if(empty($api_key)) {
			return "";
		}
		return get_option('saleassist_client_id');
	}
	
	public static function display_alert() {
		Saleassist::view( 'notice', array(
			'type' => 'alert',
			'code' => (int) get_option( 'saleassist_alert_code' ),
			'msg'  => get_option( 'saleassist_alert_msg' )
		) );
	}

	public static function display_api_key_warning() {
		Saleassist::view( 'notice', array( 'type' => 'plugin' ) );
	}

	public static function display_page() {
		if ( !Saleassist::get_api_key() || ( isset( $_GET['view'] ) && $_GET['view'] == 'start' ) )
			self::display_start_page();
		elseif ( isset( $_GET['view'] ) && $_GET['view'] == 'stats' )
			self::display_stats_page();
		elseif ( isset( $_GET['view'] ) && $_GET['view'] == 'widget' ) 		
			self::display_widget_page();		
		else
			self::display_configuration_page();
			
	}

	public static function display_start_page() {
		if ( isset( $_GET['action'] ) ) {
			if ( $_GET['action'] == 'delete-key' ) {
				global $wpdb;
				if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], self::NONCE ) ) {

					delete_option( 'saleassist_page_enable' );
					delete_option( 'saleassist_post_enable' );
					delete_option( 'saleassist_api_key' );
					delete_option( 'saleassist_secret_key' );
					delete_option( "saleassist_data" );
					delete_option( "saleassist_client_id" );
					delete_option( "widget_saleassist_widget" );

					$wpdb->query("TRUNCATE TABLE {$wpdb->prefix}saleassist");
					
				}
			}
		}
	
		if ( isset( $_GET['action'] ) ) {
			if ( $_GET['action'] == 'save-key' ) {
				if ( is_object( $saleassist_user ) ) {					
					self::display_configuration_page();
					return;
				}
			}
		}
		Saleassist::view( 'start' );
	}

	public static function display_stats_page() {
		Saleassist::view( 'stats' );
	}

	public static function display_configuration_page() {
		$api_key      = Saleassist::get_api_key();
		$saleassist_user = self::get_saleassist_user( );
		
		if ( ! $saleassist_user ) {
			// This could happen if the user's key became invalid after it was previously valid and successfully set up.
			self::$notices['status'] = 'existing-key-invalid';
			self::display_start_page();
			return;
		}
		Saleassist::view( 'config' );
	}

	public static function display_widget_page() {
		$api_key      		= Saleassist::get_api_key();
		$saleassist_user 	= self::get_saleassist_user( );
		
		if ( ! $saleassist_user ) {
			// This could happen if the user's key became invalid after it was previously valid and successfully set up.
			self::$notices['status'] = 'existing-key-invalid';
			self::display_start_page();
			return;
		}
		Saleassist::view( 'widget-setup' );
	}
	

	public static function display_notice() {
		global $hook_suffix;

		if ( isset( $_GET['saleassist_recheck_complete'] ) ) {
			$recheck_count = (int) $_GET['recheck_count'];
			
			if ( $recheck_count === 0 ) {
				$message = __( 'There were no comments to check. Saleassist will only check comments awaiting moderation.', 'saleassist' );
			}
			else {
				$message = sprintf( _n( 'Saleassist checked %s comment.', 'Saleassist checked %s comments.', $recheck_count, 'saleassist' ), number_format( $recheck_count ) );
				$message .= ' ';
			}
			
			echo '<div class="notice notice-success"><p>' . esc_html( $message ) . '</p></div>';
		}
		else if ( isset( $_GET['saleassist_recheck_error'] ) ) {
			echo '<div class="notice notice-error"><p>' . esc_html( __( 'Saleassist could not recheck your comments for spam.', 'saleassist' ) ) . '</p></div>';
		}
	}

	public static function display_status() {		
		if ( ! self::get_server_connectivity() ) {
			Saleassist::view( 'notice', array( 'type' => 'servers-be-down' ) );
		}
		else if ( ! empty( self::$notices ) ) {
			foreach ( self::$notices as $index => $type ) {
				if ( is_object( $type ) ) {
					$notice_header = $notice_text = '';
					
					if ( property_exists( $type, 'notice_header' ) ) {
						$notice_header = wp_kses( $type->notice_header, self::$allowed );
					}
				
					if ( property_exists( $type, 'notice_text' ) ) {
						$notice_text = wp_kses( $type->notice_text, self::$allowed );
					}
					
					if ( property_exists( $type, 'status' ) ) {
						$type = wp_kses( $type->status, self::$allowed );
						Saleassist::view( 'notice', compact( 'type', 'notice_header', 'notice_text' ) );
						
						unset( self::$notices[ $index ] );
					}
				}
				else {
					Saleassist::view( 'notice', compact( 'type' ) );
					unset( self::$notices[ $index ] );
				}
			}
		}
	}
	
	/**
	 * When Saleassist is active, remove the "Activate Saleassist" step from the plugin description.
	 */
	public static function modify_plugin_description( $all_plugins ) {
		if ( isset( $all_plugins['saleassist/saleassist.php'] ) ) {
			if ( Saleassist::get_api_key() ) {
				$all_plugins['saleassist/saleassist.php']['Description'] = __( 'Used by thousands, SaleAssist is a Live Video Commerce solution offering <strong>Live video shopping & Live Streaming Selling</strong> to enhance <strong>Customer engagement & Sales conversions </strong> Your site is fully configured and being featured, even while you sleep.', 'saleassist' );
			}
			else {
				$all_plugins['saleassist/saleassist.php']['Description'] = __( 'Used by thousands, Saleassist is quite possibly the best way in the world to <strong>connect with customer or users with live meetings and chat meetings</strong>. It keeps your site uptodated even while you sleep. To get started, just go to <a href="admin.php?page=saleassist-key-config">your Saleassist Settings page</a> to set up your API key.', 'saleassist' );
			}
		}
		
		return $all_plugins;
	}
	
	public static function generateRandomString($length = 5) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
}