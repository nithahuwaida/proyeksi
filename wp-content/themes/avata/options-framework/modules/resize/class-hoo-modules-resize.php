<?php

/**
 * The Hoo_Modules_Resize object.
 */
class Hoo_Modules_Resize {

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
		add_action( 'customize_controls_print_scripts', array( $this, 'enqueue_scripts' ) );
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
	 * Enqueue scripts.
	 *
	 * @access public
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'hoo-customizer-resize', trailingslashit( Hoo::$url ) . 'modules/resize/resize.js', array( 'jquery-ui-resizable' ) );
		wp_enqueue_style( 'hoo-customizer-resize', trailingslashit( Hoo::$url ) . 'modules/resize/resize.css' );
	}
}
