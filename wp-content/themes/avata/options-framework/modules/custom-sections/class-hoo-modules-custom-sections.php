<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds styles to the customizer.
 */
class Hoo_Modules_Custom_Sections {

	/**
	 * The object instance.
	 *
	 * @static
	 * @access private
	 * @var object
	 */
	private static $instance;

	/**
	 * Constructor.
	 *
	 * @access protected
	 */
	protected function __construct() {
		// Register the new section types.
		add_filter( 'options-framework/section_types', array( $this, 'set_section_types' ) );
		// Register the new panel types.
		add_filter( 'options-framework/panel_types', array( $this, 'set_panel_types' ) );
		// Include the section-type files.
		add_action( 'customize_register', array( $this, 'include_sections_and_panels' ) );
		// Enqueue styles & scripts.
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_scrips' ), 999 );
	}

	/**
	 * Gets an instance of this object.
	 * Prevents duplicate instances which avoid artefacts and improves performance.
	 *
	 * @static
	 * @access public
	 * @return object
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Add the custom section types.
	 *
	 * @access public
	 * @param array $section_types The registered section-types.
	 * @return array
	 */
	public function set_section_types( $section_types ) {

		$new_types = array(
			'hoo-default'  => 'Hoo_Sections_Default_Section',
			'hoo-expanded' => 'Hoo_Sections_Expanded_Section',
			'hoo-nested'   => 'Hoo_Sections_Nested_Section',
		);
		return array_merge( $section_types, $new_types );

	}

	/**
	 * Add the custom panel types.
	 *
	 * @access public
	 * @param array $panel_types The registered section-types.
	 * @return array
	 */
	public function set_panel_types( $panel_types ) {

		$new_types = array(
			'hoo-nested' => 'Hoo_Panels_Nested_Panel',
		);
		return array_merge( $panel_types, $new_types );

	}

	/**
	 * Include the custom-section classes.
	 *
	 * @access public
	 */
	public function include_sections_and_panels() {

		// Sections.
		$folder_path   = dirname( __FILE__ ) . '/sections/';
		$section_types = apply_filters( 'options-framework/section_types', array() );

		foreach ( $section_types as $id => $class ) {
			if ( ! class_exists( $class ) ) {
				$path = wp_normalize_path( $folder_path . 'class-hoo-sections-' . $id . '-section.php' );
				if ( file_exists( $path ) ) {
					include_once $path;
					continue;
				}
				$path = str_replace( 'class-hoo-sections-hoo-', 'class-hoo-sections-', $path );
				if ( file_exists( $path ) ) {
					include_once $path;
				}
			}
		}

		// Panels.
		$folder_path = dirname( __FILE__ ) . '/panels/';
		$panel_types = apply_filters( 'options-framework/panel_types', array() );

		foreach ( $panel_types as $id => $class ) {
			if ( ! class_exists( $class ) ) {
				$path = wp_normalize_path( $folder_path . 'class-hoo-panels-' . $id . '-panel.php' );
				if ( file_exists( $path ) ) {
					include_once $path;
					continue;
				}
				$path = str_replace( 'class-hoo-panels-hoo-', 'class-hoo-panels-', $path );
				if ( file_exists( $path ) ) {
					include_once $path;
				}
			}
		}

	}

	/**
	 * Enqueues any necessary scripts and styles.
	 *
	 * @access public
	 */
	public function enqueue_scrips() {

		wp_enqueue_style( 'hoo-custom-sections', trailingslashit( Hoo::$url ) . 'modules/custom-sections/sections.css' );
		wp_enqueue_script( 'hoo-custom-sections', trailingslashit( Hoo::$url ) . 'modules/custom-sections/sections.js', array( 'jquery', 'customize-base', 'customize-controls' ) );

	}

}
