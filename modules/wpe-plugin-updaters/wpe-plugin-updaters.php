<?php
/**
 * Module Name: Plugin Updaters
 * Description: This module handles ACF updates.
 *
 * @package wpe-secure-updater
 */

namespace WpeSecureUpdater\PluginUpdaters;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Initialize the checking for plugin updates.
 */
function check_for_updates() {

	$cache_transient_name     = 'wpesu-wpe-plugins-cache';
	$cache_transient_exp_name = 'wpesu-wpe-plugins-cache-expiry';
	$cache_time               = time() + HOUR_IN_SECONDS * 5;

	// First, get the list of WPE plugins we want to handle updates for, checking a cache first.
	$expiry   = get_option( $cache_transient_exp_name, 0 );
	$response = get_option( $cache_transient_name );

	if ( empty( $expiry ) || time() > $expiry || empty( $response ) ) {
		$response = wp_remote_get( 'https://wpe-plugin-updates.wpengine.com/plugins.json' );

		if (
			is_wp_error( $response ) ||
			200 !== wp_remote_retrieve_response_code( $response ) ||
			empty( wp_remote_retrieve_body( $response ) )
		) {
			return false;
		}

		$response = wp_remote_retrieve_body( $response );

		// Cache the response.
		update_option( $cache_transient_exp_name, $cache_time, false );
		update_option( $cache_transient_name, $response, false );
	}

	$wpe_plugins_to_handle = json_decode( $response, true );

	if ( json_last_error() !== JSON_ERROR_NONE ) {
		return false;
	}

	if ( empty( $wpe_plugins_to_handle ) || ! is_array( $wpe_plugins_to_handle ) ) {
		return false;
	}

	foreach( $wpe_plugins_to_handle as $plugin_slug => $wpe_plugin_to_handle ) {

		if (
			empty( $wpe_plugin_to_handle['plugin_name'] ) ||
			empty( $wpe_plugin_to_handle['plugin_author'] ) ||
			empty( $plugin_slug )
		) {
			// Response for this plugin was malformed, skip it.
			continue;
		}

		$properties = array(
			'plugin_name'   => $wpe_plugin_to_handle['plugin_name'],
			'plugin_author' => $wpe_plugin_to_handle['plugin_author'],
			'plugin_slug'   => $plugin_slug,
		);

		new \WpeSecureUpdater\PluginUpdaterClass\PluginUpdater( $properties );
	}
}
add_action( 'admin_init', __NAMESPACE__ . '\check_for_updates' );
