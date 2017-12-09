<?php

/**
 * The Hoo_Modules_CSS object.
 */
class Hoo_Modules_CSS {

	/**
	 * The object instance.
	 *
	 * @static
	 * @access private
	 * @var object
	 */
	private static $instance;

	/**
	 * Whether we've already processed this or not.
	 *
	 * @access public
	 * @var bool
	 */
	public $processed = false;

	/**
	 * The CSS array
	 *
	 * @access public
	 * @var array
	 */
	public static $css_array = array();

	/**
	 * Set to true if you want to use the AJAX method.
	 *
	 * @access public
	 * @var bool
	 */
	public static $ajax = false;

	/**
	 * The Hoo_CSS_To_File object.
	 *
	 * @access protected
	 * @var object
	 */
	protected $css_to_file;

	/**
	 * Constructor
	 *
	 * @access protected
	 */
	protected function __construct() {

		$class_files = array(
			'Hoo_CSS_To_File'                         => '/class-hoo-css-to-file.php',
			'Hoo_Modules_CSS_Generator'               => '/class-hoo-modules-css-generator.php',
			'Hoo_Output'                              => '/class-hoo-output.php',
			'Hoo_Output_Field_Background'             => '/field/class-hoo-output-field-background.php',
			'Hoo_Output_Field_Multicolor'             => '/field/class-hoo-output-field-multicolor.php',
			'Hoo_Output_Field_Dimensions'             => '/field/class-hoo-output-field-dimensions.php',
			'Hoo_Output_Field_Typography'             => '/field/class-hoo-output-field-typography.php',
			'Hoo_Output_Property'                     => '/property/class-hoo-output-property.php',
			'Hoo_Output_Property_Background_Image'    => '/property/class-hoo-output-property-background-image.php',
			'Hoo_Output_Property_Background_Position' => '/property/class-hoo-output-property-background-position.php',
			'Hoo_Output_Property_Font_Family'         => '/property/class-hoo-output-property-font-family.php',
		);

		foreach ( $class_files as $class_name => $file ) {
			if ( ! class_exists( $class_name ) ) {
				include_once wp_normalize_path( dirname( __FILE__ ) . $file );
			}
		}

		add_action( 'init', array( $this, 'init' ) );

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
	 * Init.
	 *
	 * @access public
	 */
	public function init() {

		Hoo_Modules_Webfonts::get_instance();

		global $wp_customize;

		$config   = apply_filters( 'options-framework/config', array() );
		$priority = 999;
		if ( isset( $config['styles_priority'] ) ) {
			$priority = absint( $config['styles_priority'] );
		}

		// Allow completely disabling Hoo CSS output.
		if ( ( defined( 'HOO_NO_OUTPUT' ) && true === HOO_NO_OUTPUT ) || ( isset( $config['disable_output'] ) && true === $config['disable_output'] ) ) {
			return;
		}

		$method = apply_filters( 'options-framework/dynamic_css/method', 'inline' );
		if ( $wp_customize ) {
			// If we're in the customizer, load inline no matter what.
			add_action( 'wp_enqueue_scripts', array( $this, 'inline_dynamic_css' ), $priority );

			// If we're using file method, on save write the new styles.
			if ( 'file' === $method ) {
				$this->css_to_file = new Hoo_CSS_To_File();
				add_action( 'customize_save_after', array( $this->css_to_file, 'write_file' ) );
			}
			return;
		}

		if ( 'file' === $method ) {
			// Attempt to write the CSS to file.
			$this->css_to_file = new Hoo_CSS_To_File();
			// If we succesd, load this file.
			$failed = get_transient( 'hoo_css_write_to_file_failed' );
			// If writing CSS to file hasn't failed, just enqueue this file.
			if ( ! $failed ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_compiled_file' ), $priority );
				return;
			}
		}

		// If we are in the customizer, load CSS using inline-styles.
		// If we are in the frontend AND self::$ajax is true, then load dynamic CSS using AJAX.
		if ( ( true === self::$ajax ) || ( isset( $config['inline_css'] ) && false === $config['inline_css'] ) ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_styles' ), $priority );
			add_action( 'wp_ajax_hoo_dynamic_css', array( $this, 'ajax_dynamic_css' ) );
			add_action( 'wp_ajax_nopriv_hoo_dynamic_css', array( $this, 'ajax_dynamic_css' ) );
			return;
		}

		// If we got this far then add styles inline.
		add_action( 'wp_enqueue_scripts', array( $this, 'inline_dynamic_css' ), $priority );
	}

	/**
	 * Enqueues compiled CSS file.
	 *
	 * @access public
	 */
	public function enqueue_compiled_file() {

		wp_enqueue_style( 'hoo-styles', $this->css_to_file->get_url(), array(), $this->css_to_file->get_timestamp() );

	}
	/**
	 * Adds inline styles.
	 *
	 * @access public
	 */
	public function inline_dynamic_css() {
		$configs = Hoo::$config;
		if ( ! $this->processed ) {
			foreach ( $configs as $config_id => $args ) {
				if ( isset( $args['disable_output'] ) && true === $args['disable_output'] ) {
					continue;
				}
				$styles = self::loop_controls( $config_id );
				$styles = apply_filters( "options-framework/{$config_id}/dynamic_css", $styles );
				if ( ! empty( $styles ) ) {
					wp_enqueue_style( 'hoo-styles-' . $config_id, trailingslashit( Hoo::$url ) . 'assets/css/hoo-styles.css', null, null );
					wp_add_inline_style( 'hoo-styles-' . $config_id, $styles );
				}
			}
			$this->processed = true;
		}
	}

	/**
	 * Get the dynamic-css.php file
	 *
	 * @access public
	 */
	public function ajax_dynamic_css() {
		require wp_normalize_path( Hoo::$path . '/modules/css/dynamic-css.php' );
		exit;
	}

	/**
	 * Enqueues the ajax stylesheet.
	 *
	 * @access public
	 */
	public function frontend_styles() {
		wp_enqueue_style( 'hoo-styles-php', admin_url( 'admin-ajax.php' ) . '?action=hoo_dynamic_css', null, null );
	}

	/**
	 * Loop through all fields and create an array of style definitions.
	 *
	 * @static
	 * @access public
	 * @param string $config_id The configuration ID.
	 */
	public static function loop_controls( $config_id ) {

		// Get an instance of the Hoo_Modules_CSS_Generator class.
		// This will make sure google fonts and backup fonts are loaded.
		Hoo_Modules_CSS_Generator::get_instance();

		$fields = Hoo::$fields;
		$css    = array();

		// Early exit if no fields are found.
		if ( empty( $fields ) ) {
			return;
		}

		foreach ( $fields as $field ) {

			// Only process fields that belong to $config_id.
			if ( $config_id !== $field['hoo_config'] ) {
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

			// Only continue if $field['output'] is set.
			if ( isset( $field['output'] ) && ! empty( $field['output'] ) ) {
				$css = Hoo_Helper::array_replace_recursive( $css, Hoo_Modules_CSS_Generator::css( $field ) );

				// Add the globals.
				if ( isset( self::$css_array[ $config_id ] ) && ! empty( self::$css_array[ $config_id ] ) ) {
					Hoo_Helper::array_replace_recursive( $css, self::$css_array[ $config_id ] );
				}
			}
		}

		$css = apply_filters( "options-framework/{$config_id}/styles", $css );

		if ( is_array( $css ) ) {
			return Hoo_Modules_CSS_Generator::styles_parse( Hoo_Modules_CSS_Generator::add_prefixes( $css ) );
		}
	}
}
