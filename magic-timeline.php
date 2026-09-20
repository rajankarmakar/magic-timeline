<?php
/**
 * Plugin Name:       Magic Timeline
 * Description:       A fully customizable vertical timeline widget for Elementor.
 * Version:           1.1.0
 * Author:            Rajan Karmaker
 * Author URI:        https://rajankarmaker.com
 * Text Domain:       magic-timeline
 * Domain Path:       /languages
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'MAGIC_TIMELINE_VERSION', '1.1.0' );
define( 'MAGIC_TIMELINE_FILE', __FILE__ );
define( 'MAGIC_TIMELINE_PATH', plugin_dir_path( __FILE__ ) );
define( 'MAGIC_TIMELINE_URL', plugin_dir_url( __FILE__ ) );
define( 'MAGIC_TIMELINE_BASENAME', plugin_basename( __FILE__ ) );
define( 'MAGIC_TIMELINE_MIN_ELEMENTOR_VERSION', '3.5.0' );
define( 'MAGIC_TIMELINE_MIN_PHP_VERSION', '7.4' );

/**
 * Bootstraps the plugin once we know Elementor and PHP requirements are met.
 */
final class Magic_Timeline_Loader {

	private static $instance = null;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'plugins_loaded', array( $this, 'on_plugins_loaded' ) );
	}

	public function on_plugins_loaded() {
		load_plugin_textdomain( 'magic-timeline', false, dirname( MAGIC_TIMELINE_BASENAME ) . '/languages' );

		if ( ! $this->is_compatible() ) {
			return;
		}

		require_once MAGIC_TIMELINE_PATH . 'includes/class-plugin.php';
		\Magic_Timeline\Plugin::instance();
	}

	private function is_compatible() {
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_elementor' ) );
			return false;
		}

		if ( ! defined( 'ELEMENTOR_VERSION' ) || ! version_compare( ELEMENTOR_VERSION, MAGIC_TIMELINE_MIN_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
			return false;
		}

		if ( version_compare( PHP_VERSION, MAGIC_TIMELINE_MIN_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
			return false;
		}

		return true;
	}

	public function admin_notice_missing_elementor() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$message = sprintf(
			/* translators: 1: Plugin name, 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'magic-timeline' ),
			'<strong>' . esc_html__( 'Magic Timeline', 'magic-timeline' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'magic-timeline' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%s</p></div>', wp_kses_post( $message ) );
	}

	public function admin_notice_minimum_elementor_version() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$message = sprintf(
			/* translators: 1: Plugin name, 2: Elementor, 3: Required version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'magic-timeline' ),
			'<strong>' . esc_html__( 'Magic Timeline', 'magic-timeline' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'magic-timeline' ) . '</strong>',
			MAGIC_TIMELINE_MIN_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%s</p></div>', wp_kses_post( $message ) );
	}

	public function admin_notice_minimum_php_version() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$message = sprintf(
			/* translators: 1: Plugin name, 2: PHP, 3: Required version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'magic-timeline' ),
			'<strong>' . esc_html__( 'Magic Timeline', 'magic-timeline' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'magic-timeline' ) . '</strong>',
			MAGIC_TIMELINE_MIN_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%s</p></div>', wp_kses_post( $message ) );
	}
}

Magic_Timeline_Loader::instance();
