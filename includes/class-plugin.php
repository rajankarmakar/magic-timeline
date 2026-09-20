<?php
namespace Magic_Timeline;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the widget category, the widget itself, and its front-end assets.
 */
final class Plugin {

	const CATEGORY  = 'magic-timeline';
	const STYLE_ID  = 'magic-timeline';
	const SCRIPT_ID = 'magic-timeline';

	private static $instance = null;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'elementor/elements/categories_registered', array( $this, 'register_category' ) );
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
		add_action( 'elementor/frontend/after_register_styles', array( $this, 'register_styles' ) );
		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'register_scripts' ) );
	}

	public function register_category( $elements_manager ) {
		$elements_manager->add_category(
			self::CATEGORY,
			array(
				'title' => __( 'Magic Timeline', 'magic-timeline' ),
				'icon'  => 'eicon-time-line',
			)
		);
	}

	public function register_widgets( $widgets_manager ) {
		require_once MAGIC_TIMELINE_PATH . 'widgets/class-timeline-widget.php';
		$widgets_manager->register( new Widgets\Timeline_Widget() );
	}

	public function register_styles() {
		wp_register_style(
			self::STYLE_ID,
			MAGIC_TIMELINE_URL . 'assets/css/timeline.css',
			array(),
			MAGIC_TIMELINE_VERSION
		);
	}

	public function register_scripts() {
		wp_register_script(
			self::SCRIPT_ID,
			MAGIC_TIMELINE_URL . 'assets/js/timeline.js',
			array(),
			MAGIC_TIMELINE_VERSION,
			true
		);
	}
}
