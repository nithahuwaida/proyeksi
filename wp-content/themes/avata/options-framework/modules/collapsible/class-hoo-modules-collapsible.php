<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds styles to the customizer.
 */
class Hoo_Modules_Collapsible {

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
		add_action( 'customize_controls_print_scripts', array( $this, 'customize_controls_print_scripts' ) );
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
	 * Enqueues the script responsible for branding the customizer
	 * and also adds variables to it using the wp_localize_script function.
	 * The actual branding is handled via JS.
	 *
	 * @access public
	 */
	public function customize_controls_print_scripts() {

		wp_enqueue_script( 'hoo-collapsible', trailingslashit( Hoo::$url ) . 'modules/collapsible/collapsible.js', array( 'customize-preview' ), false, true );
		wp_enqueue_style( 'hoo-collapsible', trailingslashit( Hoo::$url ) . 'modules/collapsible/collapsible.css' );

		$collapsible_fields = array();
		$fields = Hoo::$fields;
		foreach ( $fields as $field ) {
			if ( isset( $field['collapsible'] ) && true === $field['collapsible'] && isset( $field['settings'] ) && isset( $field['label'] ) ) {
				$collapsible_fields[ $field['settings'] ] = $field['label'];
			}
		}
		$collapsible_fields = array_unique( $collapsible_fields );
		wp_localize_script( 'hoo-collapsible', 'collapsible', $collapsible_fields );

	}
}
