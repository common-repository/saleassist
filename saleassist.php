<?php
/**
 * @package Saleassist
 */
/*
Plugin Name: Saleassist
Plugin URI: https://saleassist.ai/
Description: SaleAssist Video Chat is the leading SaaS plugin for engaging, acquiring, and retaining customers via video chat. This plugin uses video as the foundation for real-time virtual commerce, assisting businesses in building live customer encounters by bridging the online-offline barrier.The SaleAssist Video Chat plugin caters to the needs of all types of businesses, regardless of size or industry. It specializes in offering tailored customer service solutions based on the needs of the company.
Version: 2.0.0
Author: SaleAssist Innov8 Private Limited
Author URI: https://saleassist.ai/
License: GPLv2 or later
Text Domain: saleassist
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2013-2020 Inroys, Inc.
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( 'SALEASSIST_VERSION', '2.0.0' );
define( 'SALEASSIST_DB_VERSION', '2.0' );
define( 'SALEASSIST__MINIMUM_WP_VERSION', '5.0' );
define( 'SALEASSIST__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SALEASSIST_DB_TABLE', 'saleassist');
define( 'SALEASSIST_BTNDB_TABLE', 'saleassist_btn');

define( 'SALEASSIST_API_URL', 'https://platform-stg.saleassist.ai/partners/workflows/onboard_web_store_to_new_client');
define( 'SALEASSIST_API_TEMP_URL', 'https://platform-stg.saleassist.ai/partners/workflows/onboard_web_store_to_new_client');

define('SALEASSIST_TEMP_KEY', '01615B04A93FD2558FEA8121A7246285');
define('SALEASSIST_TEMP_SEC', '597facc5c0d63ef3abf29be8ebd44770e7f2059d853b5138b0c2547bb2bdd555');

define('SALEASSIST_ADMIN_EMAIL', get_bloginfo('admin_email'));
define('SALEASSIST_BLOG_NAME', "Wordpress - ".get_bloginfo('name') ."#".get_bloginfo('url'));
define('SALEASSIST_BLOG_URL', get_bloginfo('url'));

register_activation_hook( __FILE__, array( 'Saleassist', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'Saleassist', 'plugin_deactivation' ) );

require_once( SALEASSIST__PLUGIN_DIR . 'class.saleassist.php' );
require_once( SALEASSIST__PLUGIN_DIR . 'class.saleassist-widget.php' );
require_once( SALEASSIST__PLUGIN_DIR . 'class.saleassist-rest-api.php' );

add_action( 'init', array( 'Saleassist', 'init' ) );


add_action( 'rest_api_init', array( 'SALEASSIST_REST_API', 'init' ) );

if ( is_admin() ) {
	require_once( SALEASSIST__PLUGIN_DIR . 'class.saleassist-admin.php' );
	add_action( 'init', array( 'Saleassist_Admin', 'init' ) );
}

//add wrapper class around deprecated saleassist functions that are referenced elsewhere
require_once( SALEASSIST__PLUGIN_DIR . 'wrapper.php' );
