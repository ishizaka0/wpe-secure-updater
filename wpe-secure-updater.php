<?php
/**
 * Plugin Name: WPE Secure Updater
 * Description: Securely update WP Engine powered plugins.
 * Plugin URI: https://www.wpengine.com
 * Update URI: false
 * Version: 0.0.5
 * Author: WP Engine
 * Text Domain: wpe-secure-updater
 *
 * @package wpe-secure-updater
 */

namespace WpeSecureUpdater;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( '\WpeSecureUpdater\PluginUpdaterClass\PluginUpdater' ) ) {
	/**
	 * Include required modules, which are in the "modules" directory.
	 *
	 * @return void
	 */
	function include_custom_modules() {
		require __DIR__ . '/modules/plugin-updater-class/class-PluginUpdater.php';
		require __DIR__ . '/modules/plugin-updater-class/plugin-updater-class.php';
		require __DIR__ . '/modules/wpe-plugin-updaters/wpe-plugin-updaters.php';
	}
	// Use priority 9 to allow other modules to hook to plugins_loaded at priority 10, which is the default.
	add_action( 'plugins_loaded', __NAMESPACE__ . '\include_custom_modules', 9 );
}
