<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds styles to the customizer.
 */
class Hoo_Modules_Customizer_Branding {

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

		$config = apply_filters( 'options-framework/config', array() );
		$vars   = array(
			'logoImage'   => '',
			'description' => '',
		);
		if ( isset( $config['logo_image'] ) && '' !== $config['logo_image'] ) {
			$vars['logoImage'] = esc_url_raw( $config['logo_image'] );
		}
		if ( isset( $config['description'] ) && '' !== $config['description'] ) {
			$vars['description'] = esc_textarea( $config['description'] );
		}

		if ( ! empty( $vars['logoImage'] ) || ! empty( $vars['description'] ) ) {
			wp_register_script( 'hoo-branding', Hoo::$url . '/modules/customizer-branding/branding.js' );
			wp_localize_script( 'hoo-branding', 'hooBranding', $vars );
			wp_enqueue_script( 'hoo-branding' );
		}
	}
}
