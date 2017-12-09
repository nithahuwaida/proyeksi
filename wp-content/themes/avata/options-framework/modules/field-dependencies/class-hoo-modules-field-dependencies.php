<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds styles to the customizer.
 */
class Hoo_Modules_Field_Dependencies {

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
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'field_dependencies' ) );
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
	 * Enqueues the field-dependencies script
	 * and adds variables to it using the wp_localize_script function.
	 * The rest is handled via JS.
	 */
	public function field_dependencies() {

		wp_enqueue_script( 'hoo_field_dependencies', trailingslashit( Hoo::$url ) . 'modules/field-dependencies/field-dependencies.js', array( 'jquery', 'customize-base', 'customize-controls' ), false, true );
		$field_dependencies = array();
		$fields = Hoo::$fields;
		foreach ( $fields as $field ) {
			if ( isset( $field['required'] ) && is_array( $field['required'] ) && ! empty( $field['required'] ) ) {
				$field_dependencies[ $field['id'] ] = $field['required'];
			}
		}
		wp_localize_script( 'hoo_field_dependencies', 'fieldDependencies', $field_dependencies );

	}
}
