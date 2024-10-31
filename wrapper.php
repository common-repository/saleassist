<?php

global $wpcom_api_key, $wpcom_secret_key, $saleassist_api_host, $saleassist_api_port;

$wpcom_api_key    = defined( 'SALEASSIST_API_KEY' ) ? constant( 'SALEASSIST_API_KEY' ) : '';
$wpcom_secret_key    = defined( 'SALEASSIST_SECRET_KEY' ) ? constant( 'SALEASSIST_SECRET_KEY' ) : '';
$saleassist_api_host = Saleassist::get_api_key() . '.rest.saleassist.ai';
$saleassist_api_port = 80;

function saleassist_test_mode() {
	return Saleassist::is_test_mode();
}

function saleassist_http_post( $request, $host, $path, $port = 80 ) {
	// echo "check3";
	return Saleassist::http_post( $request, $path ); 
}

function saleassist_rightnow() {
	if ( !class_exists( 'Saleassist_Admin' ) )
		return false;
   
   	return Saleassist_Admin::rightnow_stats();
}

function saleassist_nonce_field( $action = -1 ) {
	return wp_nonce_field( $action );
}
function saleassist_plugin_action_links( $links, $file ) {
	return Saleassist_Admin::plugin_action_links( $links, $file );
}
function saleassist_stats() {
	return Saleassist_Admin::dashboard_stats();
}

function saleassist_check_server_connectivity() {
	return Saleassist_Admin::check_server_connectivity();
}
function saleassist_get_server_connectivity( $cache_timeout = 86400 ) {
	return Saleassist_Admin::get_server_connectivity( $cache_timeout );
}
function saleassist_admin_menu() {
	return Saleassist_Admin::admin_menu();
}
function saleassist_load_menu() {
	return Saleassist_Admin::load_menu();
}
function saleassist_get_key() {
	return Saleassist::get_api_key();
}
function saleassist_check_key_status( $key, $secret ) {
	return Saleassist::check_key_status( $key, $secret );
}
function saleassist_update_alert( $response ) {
	return Saleassist::update_alert( $response );
}
function saleassist_verify_key( $key, $secret ) {
	return Saleassist::verify_key( $key, $secret );
}
function saleassist_get_user_roles( $user_id ) {
	return Saleassist::get_user_roles( $user_id );
}
function saleassist_cmp_time( $a, $b ) {
	return Saleassist::_cmp_time( $a, $b );
}
function saleassist_auto_check_update_meta( $id, $comment ) {
	return Saleassist::auto_check_update_meta( $id, $comment );
}

function saleassist_pingback_forwarded_for( $r, $url ) {
	// This functionality is now in core.
	return false;
}
