<?php
/**
 * Utility class.
 */
class Hoo_Util {

	/**
	 * Constructor.
	 *
	 * @access public
	 */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'acf_pro_compatibility' ) );
	}

	/**
	 * Changes select2 version in ACF.
	 * Fixes a plugin conflict that was causing select fields to crash
	 * because of a version mismatch between ACF's and Hoo's select2 scripts.
	 * Props @hellor0bot
	 *
	 * @see https://github.com/aristath/options-framework/issues/1302
	 * @access public
	 */
	public function acf_pro_compatibility() {
		if ( is_customize_preview() ) {
			add_filter( 'acf/settings/enqueue_select2', '__return_false', 99 );
		}
	}



	/**
	 * Build the variables.
	 *
	 * @static
	 * @access public
	 * @return array Formatted as array( 'variable-name' => value ).
	 */
	public static function get_variables() {

		$variables = array();

		// Loop through all fields.
		foreach ( Hoo::$fields as $field ) {

			// Check if we have variables for this field.
			if ( isset( $field['variables'] ) && $field['variables'] && ! empty( $field['variables'] ) ) {

				// Loop through the array of variables.
				foreach ( $field['variables'] as $field_variable ) {

					// Is the variable ['name'] defined? If yes, then we can proceed.
					if ( isset( $field_variable['name'] ) ) {

						// Sanitize the variable name.
						$variable_name = esc_attr( $field_variable['name'] );

						// Do we have a callback function defined? If not then set $variable_callback to false.
						$variable_callback = ( isset( $field_variable['callback'] ) && is_callable( $field_variable['callback'] ) ) ? $field_variable['callback'] : false;

						// If we have a variable_callback defined then get the value of the option
						// and run it through the callback function.
						// If no callback is defined (false) then just get the value.
						$variables[ $variable_name ] = Hoo::get_option( $field['settings'] );
						if ( $variable_callback ) {
							$variables[ $variable_name ] = call_user_func( $field_variable['callback'], Hoo::get_option( $field['settings'] ) );
						}
					}
				}
			}
		}

		// Pass the variables through a filter ('options-framework/variable') and return the array of variables.
		return apply_filters( 'options-framework/variable', $variables );

	}




}
