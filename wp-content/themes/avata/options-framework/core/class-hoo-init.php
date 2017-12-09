<?php
/**
 * Initialize Hoo
 */
class Hoo_Init {

	/**
	 * Control types.
	 *
	 * @access private
	 * @var array
	 */
	private $control_types = array();

	/**
	 * The class constructor.
	 */
	public function __construct() {

		$this->set_url();
		add_action( 'after_setup_theme', array( $this, 'set_url' ) );
		add_action( 'wp_loaded', array( $this, 'add_to_customizer' ), 1 );
		add_filter( 'options-framework/control_types', array( $this, 'default_control_types' ) );

		new Hoo_Custom_Build();
	}

	/**
	 * Properly set the Hoo URL for assets.
	 * Determines if Hoo is installed as a plugin, in a child theme, or a parent theme
	 * and then does some calculations to get the proper URL for its CSS & JS assets.
	 */
	public function set_url() {

		Hoo::$path = wp_normalize_path( dirname( HOO_OF_FILE ) );

		// Works in most cases.
		// Serves as a fallback in case all other checks fail.
		if ( defined( 'WP_CONTENT_DIR' ) ) {
			$content_dir = wp_normalize_path( WP_CONTENT_DIR );
			if ( false !== strpos( Hoo::$path, $content_dir ) ) {
				$relative_path = str_replace( $content_dir, '', Hoo::$path );
				Hoo::$url = content_url( $relative_path );
				
	
			}
		}


		// Get the path to the theme.
		$theme_path = wp_normalize_path( get_template_directory() );

		// Is Hoo included in the theme?
		if ( false !== strpos( Hoo::$path, $theme_path ) ) {
			Hoo::$url = get_template_directory_uri() . str_replace( $theme_path, '', Hoo::$path );
			
		}

		// Is there a child-theme?
		$child_theme_path = wp_normalize_path( get_stylesheet_directory_uri() );
		if ( $child_theme_path !== $theme_path &&  is_child_theme()) {
			// Is Hoo included in a child theme?
			if ( false !== strpos( Hoo::$path, $child_theme_path ) ) {
				Hoo::$url = get_template_directory_uri() . str_replace( $child_theme_path, '', Hoo::$path );
			}
		}
		

		// Apply the options-framework/config filter.
		$config = apply_filters( 'options-framework/config', array() );
		if ( isset( $config['url_path'] ) ) {
			Hoo::$url = $config['url_path'];
		}

		// Escapes the URL.
		Hoo::$url = esc_url_raw( Hoo::$url );
		// Make sure the right protocol is used.
		Hoo::$url = set_url_scheme( Hoo::$url );
	}

	/**
	 * Add the default Hoo control types.
	 *
	 * @access public
	 * @param array $control_types The control types array.
	 * @return array
	 */
	public function default_control_types( $control_types = array() ) {

		$this->control_types = array(
			'checkbox'              => 'WP_Customize_Control',
			'hoo-background'      => 'Hoo_Control_Background',
			'hoo-code'            => 'Hoo_Control_Code',
			'hoo-color'           => 'Hoo_Control_Color',
			'hoo-color-palette'   => 'Hoo_Control_Color_Palette',
			'hoo-custom'          => 'Hoo_Control_Custom',
			'hoo-date'            => 'Hoo_Control_Date',
			'hoo-dashicons'       => 'Hoo_Control_Dashicons',
			'hoo-dimension'       => 'Hoo_Control_Dimension',
			'hoo-dimensions'      => 'Hoo_Control_Dimensions',
			'hoo-editor'          => 'Hoo_Control_Editor',
			'hoo-fontawesome'     => 'Hoo_Control_FontAwesome',
			'hoo-gradient'        => 'Hoo_Control_Gradient',
			'hoo-image'           => 'Hoo_Control_Image',
			'hoo-multicolor'      => 'Hoo_Control_Multicolor',
			'hoo-multicheck'      => 'Hoo_Control_MultiCheck',
			'hoo-number'          => 'Hoo_Control_Number',
			'hoo-palette'         => 'Hoo_Control_Palette',
			'hoo-preset'          => 'Hoo_Control_Preset',
			'hoo-radio'           => 'Hoo_Control_Radio',
			'hoo-radio-buttonset' => 'Hoo_Control_Radio_ButtonSet',
			'hoo-radio-image'     => 'Hoo_Control_Radio_Image',
			'repeater'              => 'Hoo_Control_Repeater',
			'hoo-select'          => 'Hoo_Control_Select',
			'hoo-slider'          => 'Hoo_Control_Slider',
			'hoo-sortable'        => 'Hoo_Control_Sortable',
			'hoo-spacing'         => 'Hoo_Control_Dimensions',
			'hoo-switch'          => 'Hoo_Control_Switch',
			'hoo-generic'         => 'Hoo_Control_Generic',
			'hoo-toggle'          => 'Hoo_Control_Toggle',
			'hoo-typography'      => 'Hoo_Control_Typography',
			'image'                 => 'Hoo_Control_Image',
			'cropped_image'         => 'WP_Customize_Cropped_Image_Control',
			'upload'                => 'WP_Customize_Upload_Control',
		);
		return array_merge( $control_types, $this->control_types );

	}

	/**
	 * Helper function that adds the fields, sections and panels to the customizer.
	 *
	 * @return void
	 */
	public function add_to_customizer() {
		$this->fields_from_filters();
		add_action( 'customize_register', array( $this, 'register_control_types' ) );
		add_action( 'customize_register', array( $this, 'add_panels' ), 97 );
		add_action( 'customize_register', array( $this, 'add_sections' ), 98 );
		add_action( 'customize_register', array( $this, 'add_fields' ), 99 );
	}

	/**
	 * Register control types
	 *
	 * @return  void
	 */
	public function register_control_types() {
		global $wp_customize;

		$section_types = apply_filters( 'options-framework/section_types', array() );
		foreach ( $section_types as $section_type ) {
			$wp_customize->register_section_type( $section_type );
		}
		if ( empty( $this->control_types ) ) {
			$this->control_types = $this->default_control_types();
		}
		$do_not_register_control_types = apply_filters( 'options-framework/control_types/exclude', array(
			'Hoo_Control_Repeater',
		) );
		foreach ( $this->control_types as $control_type ) {
			if ( 0 === strpos( $control_type, 'Hoo' ) && ! in_array( $control_type, $do_not_register_control_types, true ) && class_exists( $control_type ) ) {
				$wp_customize->register_control_type( $control_type );
			}
		}
	}

	/**
	 * Register our panels to the WordPress Customizer.
	 *
	 * @access public
	 */
	public function add_panels() {
		if ( ! empty( Hoo::$panels ) ) {
			foreach ( Hoo::$panels as $panel_args ) {
				// Extra checks for nested panels.
				if ( isset( $panel_args['panel'] ) ) {
					if ( isset( Hoo::$panels[ $panel_args['panel'] ] ) ) {
						// Set the type to nested.
						$panel_args['type'] = 'hoo-nested';
					}
				}

				new Hoo_Panel( $panel_args );
			}
		}
	}

	/**
	 * Register our sections to the WordPress Customizer.
	 *
	 * @var	object	The WordPress Customizer object
	 * @return  void
	 */
	public function add_sections() {
		if ( ! empty( Hoo::$sections ) ) {
			foreach ( Hoo::$sections as $section_args ) {
				// Extra checks for nested sections.
				if ( isset( $section_args['section'] ) ) {
					if ( isset( Hoo::$sections[ $section_args['section'] ] ) ) {
						// Set the type to nested.
						$section_args['type'] = 'hoo-nested';
						// We need to check if the parent section is nested inside a panel.
						$parent_section = Hoo::$sections[ $section_args['section'] ];
						if ( isset( $parent_section['panel'] ) ) {
							$section_args['panel'] = $parent_section['panel'];
						}
					}
				}
				new Hoo_Section( $section_args );
			}
		}
	}

	/**
	 * Create the settings and controls from the $fields array and register them.
	 *
	 * @var	object	The WordPress Customizer object
	 * @return  void
	 */
	public function add_fields() {

		global $wp_customize;
		foreach ( Hoo::$fields as $args ) {

			// Create the settings.
			new Hoo_Settings( $args );

			// Check if we're on the customizer.
			// If we are, then we will create the controls, add the scripts needed for the customizer
			// and any other tweaks that this field may require.
			if ( $wp_customize ) {

				// Create the control.
				new Hoo_Control( $args );

			}
		}
	}

	/**
	 * Process fields added using the 'options-framework/fields' and 'options-framework/controls' filter.
	 * These filters are no longer used, this is simply for backwards-compatibility.
	 *
	 * @access private
	 */
	private function fields_from_filters() {

		$fields = apply_filters( 'options-framework/controls', array() );
		$fields = apply_filters( 'options-framework/fields', $fields );

		if ( ! empty( $fields ) ) {
			foreach ( $fields as $field ) {
				Hoo::add_field( 'global', $field );
			}
		}
	}


	/**
	 * Alias for the get_variables static method in the Hoo_Util class.
	 * This is here for backwards-compatibility purposes.
	 *
	 * @static
	 * @access public
	 * @return array Formatted as array( 'variable-name' => value ).
	 */
	public static function get_variables() {
		// Log error for developers.
		// @codingStandardsIgnoreLine
		error_log( 'We detected you\'re using Hoo_Init::get_variables(). Please use Hoo_Util::get_variables() instead. This message was added in Hoo 3.0.9.' );
		// Return result using the Hoo_Util class.
		return Hoo_Util::get_variables();
	}

}
