<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds script for tooltips.
 */
class Hoo_Modules_Webfonts {

	/**
	 * The object instance.
	 *
	 * @static
	 * @access private
	 * @var object
	 */
	private static $instance;

	/**
	 * Whether we should fallback to the link method or not.
	 *
	 * @access private
	 * @var bool
	 */
	private $fallback_to_link = false;

	/**
	 * The Hoo_Fonts_Google object.
	 *
	 * @access protected
	 * @var object
	 */
	protected $fonts_google;


	/**
	 * The class constructor
	 *
	 * @access protected
	 */
	protected function __construct() {

		include_once wp_normalize_path( dirname( __FILE__ ) . '/class-hoo-fonts.php' );
		include_once wp_normalize_path( dirname( __FILE__ ) . '/class-hoo-fonts-google.php' );

		add_action( 'wp_loaded', array( $this, 'run' ) );

	}

	/**
	 * Run on after_setup_theme.
	 *
	 * @access public
	 */
	public function run() {
		$this->fonts_google = Hoo_Fonts_Google::get_instance();
		$this->maybe_fallback_to_link();
		$this->init();
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
	 * Init other objects depending on the method we'll be using.
	 *
	 * @access protected
	 */
	protected function init() {

		foreach ( Hoo::$config as $config_id => $args ) {
			$method = $this->get_method( $config_id );
			$classname = 'Hoo_Modules_Webfonts_' . ucfirst( $method );
			new $classname( $config_id, $this, $this->fonts_google );
		}
	}

	/**
	 * Get the method we're going to use.
	 *
	 * @access public
	 * @param string $config_id The config-ID.
	 * @return string
	 */
	public function get_method( $config_id ) {

		// Figure out which method to use.
		$method = apply_filters( 'options-framework/googlefonts_load_method', 'link' );

		// Fallback to 'link' if value is invalid.
		if ( 'async' !== $method && 'embed' !== $method && 'link' !== $method ) {
			$method = 'link';
		}

		// Fallback to 'link' if embed was not possible.
		if ( 'embed' === $method && $this->fallback_to_link ) {
			$method = 'link';
		}

		// Force using the JS method while in the customizer.
		// This will help us work-out the live-previews for typography fields.
		// If we're not in the customizer use the defined method.
		return ( is_customize_preview() ) ? 'async' : $method;
	}

	/**
	 * Should we fallback to link method?
	 *
	 * @access protected
	 */
	protected function maybe_fallback_to_link() {

		// Get the $fallback_to_link value from transient.
		$fallback_to_link = get_transient( 'hoo_googlefonts_fallback_to_link' );
		if ( 'yes' === $fallback_to_link ) {
			$this->fallback_to_link = true;
		}

		// Use links when in the customizer.
		global $wp_customize;
		if ( $wp_customize ) {
			$this->fallback_to_link = true;
		}
	}

	/**
	 * Goes through all our fields and then populates the $this->fonts property.
	 *
	 * @access public
	 * @param string $config_id The config-ID.
	 */
	public function loop_fields( $config_id ) {
		foreach ( Hoo::$fields as $field ) {
			if ( isset( $field['hoo_config'] ) && $config_id !== $field['hoo_config'] ) {
				continue;
			}
			// Only continue if field dependencies are met.
			if ( ! empty( $field['required'] ) ) {
				$valid = true;

				foreach ( $field['required'] as $requirement ) {
					if ( isset( $requirement['setting'] ) && isset( $requirement['value'] ) && isset( $requirement['operator'] ) ) {
						$controller_value = Hoo_Values::get_value( $config_id, $requirement['setting'] );
						if ( ! Hoo_Active_Callback::compare( $controller_value, $requirement['value'], $requirement['operator'] ) ) {
							$valid = false;
						}
					}
				}

				if ( ! $valid ) {
					continue;
				}
			}

			$this->fonts_google->generate_google_font( $field );
		}
	}
}
