<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds script for tooltips.
 */
class Hoo_Modules_Tooltips {

	/**
	 * The object instance.
	 *
	 * @static
	 * @access private
	 * @var object
	 */
	private static $instance;

	/**
	 * An array containing field identifieds and their tooltips.
	 *
	 * @access private
	 * @var array
	 */
	private $tooltips_content = array();

	/**
	 * The class constructor
	 *
	 * @access protected
	 */
	protected function __construct() {
		add_action( 'customize_controls_print_footer_scripts', array( $this, 'customize_controls_print_footer_scripts' ) );
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
	 * Parses fields and if any tooltips are found, they are added to the
	 * object's $tooltips_content property.
	 *
	 * @access private
	 */
	private function parse_fields() {

		$fields = Hoo::$fields;
		foreach ( $fields as $field ) {
			if ( isset( $field['tooltip'] ) && ! empty( $field['tooltip'] ) ) {
				$id = str_replace( '[', '-', str_replace( ']', '', $field['settings'] ) );
				$this->tooltips_content[ $id ] = array(
					'id'      => $id,
					'content' => wp_kses_post( $field['tooltip'] ),
				);
			}
		}
	}

	/**
	 * Allows us to add a tooltip to any control.
	 *
	 * @access public
	 * @param string $field_id The field-ID.
	 * @param string $tooltip  The tooltip content.
	 */
	public function add_tooltip( $field_id, $tooltip ) {

		$this->tooltips_content[ $field_id ] = array(
			'id'      => sanitize_key( $field_id ),
			'content' => wp_kses_post( $tooltip ),
		);

	}

	/**
	 * Enqueue scripts.
	 *
	 * @access public
	 */
	public function customize_controls_print_footer_scripts() {

		$this->parse_fields();

		wp_enqueue_script( 'hoo-tooltip', trailingslashit( Hoo::$url ) . 'modules/tooltips/tooltip.js', array( 'jquery' ) );
		wp_localize_script( 'hoo-tooltip', 'hooTooltips', $this->tooltips_content );
		wp_enqueue_style( 'hoo-tooltip', trailingslashit( Hoo::$url ) . 'modules/tooltips/tooltip.css', null );

	}
}
