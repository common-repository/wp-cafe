<?php

/**
 *  @package wpcafe
 */

/**
 * Plugin Name:        WP Cafe
 * Plugin URI:         https://product.themewinter.com/wpcafe
 * Description:        WordPress Restaurant solution plugin to launch Restaurant Websites.
 * Version:            2.2.30
 * Author:             Themewinter
 * Author URI:         http://themewinter.com/
 * License:            GPL-2.0+
 * License URI:        http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:        wpcafe
 * Domain Path:       /languages
 */

defined( 'ABSPATH' ) || exit;

final class Wpcafe {

	/**
	 * Plugin Version
	 *
	 * @since 1.3.9
	 *
	 * @var string The plugin version.
	 */
	static function version() {
		return '2.2.30';
	}

	/**
	 * Instance of self
	 *
	 * @since 1.3.9
	 *
	 * @var Wpcafe
	 */
	private static $instance = null;

	/**
	 * Initializes the Wpcafe() class
	 *
	 * Checks for an existing Wpcafe() instance
	 * and if it doesn't find one, creates it.
	 */
	public static function init() {

			if ( self::$instance === null ) {
					self::$instance = new self();
			}

			return self::$instance;
	}

	/**
	 * Instance of Wpcafe
	 */
	private function __construct() {
		// Load translation
		add_action( 'init', [$this, 'i18n'] );

				// Instantiate Base Class after plugins loaded
				add_action( 'plugins_loaded', [$this, 'initialize_modules'], 999 );

				define( 'WPCAFE_DEFAULT_DATE_FORMAT', 'Y-m-d' );
				define( 'WPCAFE_DEFAULT_TIME_FORMAT', 'H:i:s' );
	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 * Fired by `init` action hook.
	 *
	 */
	public function i18n() {
		load_plugin_textdomain( 'wpcafe', false, dirname( self::plugins_basename( ) ) . '/languages/' );
	}

	/**
	 * Initialize Modules
	 *
	 * @since 1.3.9
	 */
	public function initialize_modules() {

		do_action( 'wpcafe/before_load' );

		require_once self::plugin_dir() . 'bootstrap.php';
		require_once self::plugin_dir() . 'utils/notice/notice.php';
		require_once self::plugin_dir() . 'utils/banner/banner.php';
		require_once self::plugin_dir() . 'utils/pro-awareness/pro-awareness.php';

		\Oxaim\Libs\Notice::init();
		\Wpmet\Libs\Pro_Awareness::init();

		// action plugin instance class
		\WpCafe\Bootstrap::instance()->init();

		do_action( 'wpcafe/after_load' );
	}

	/**
	 * Assets Directory Url
	 *
	 * @return void
	 */
	public static function assets_url() {
		return trailingslashit( self::plugin_url() . 'assets' );
	}

	/**
	 * Assets Folder Directory Path
	 *
	 * @since 1.3.9
	 *
	 * @return void
	 */
	public static function assets_dir() {
		return trailingslashit( self::plugin_dir() . 'assets' );
	}

	/**
	 * Plugin Core File Directory Url
	 *
	 * @since 1.3.9
	 *
	 * @return void
	 */
	public static function core_url() {
		return trailingslashit( self::plugin_url() . 'core' );
	}

	/**
	 * Plugin Core File Directory Path
	 *
	 * @since 1.3.9
	 *
	 * @return void
	 */
	public static function core_dir() {
		return trailingslashit( self::plugin_dir() . 'core' );
	}

	/**
	 * Plugin Url
	 *
	 * @since 1.3.9
	 *
	 * @return void
	 */
	public static function plugin_url() {
		return trailingslashit( plugin_dir_url( self::plugin_file() ) );
	}

	/**
  * Plugin Directory Path
  *
  * @since 1.3.9
  *
  * @return void
  */
	public static function plugin_dir() {
		return trailingslashit( plugin_dir_path( self::plugin_file() ) );
	}

	/**
	 * Plugins Basename
	 *
	 * @since 1.3.9
	 */
	public static function plugins_basename(){
		return plugin_basename( self::plugin_file() );
	}

	/**
	 * Plugin File
	 *
	 * @since 1.3.9
	 *
	 * @return void
	 */
	public static function plugin_file(){
		return __FILE__;
	}
}

/**
 * Load Wpcafe Addon when all plugins are loaded
 *
 * @return Wpcafe
 */
function wpcafe() {
  return Wpcafe::init();
}

// Let's Go...
wpcafe();