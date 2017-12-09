<?php

/**
 * Field overrides.
 */
class Hoo_Field_Color extends Hoo_Field {

	/**
	 * Backwards compatibility.
	 *
	 * @access protected
	 * @var bool
	 */
	protected $alpha = false;

	/**
	 * Sets the control type.
	 *
	 * @access protected
	 */
	protected function set_type() {

		$this->type = 'hoo-color';

	}

	/**
	 * Sets the $choices
	 *
	 * @access protected
	 */
	protected function set_choices() {

		if ( ! is_array( $this->choices ) ) {
			$this->choices = array();
		}
		if ( true === $this->alpha ) {
			$this->choices['alpha'] = true;
		}
		if ( ! isset( $this->choices['alpha'] ) || true !== $this->choices['alpha'] ) {
			$this->choices['alpha'] = true;
			if ( property_exists( $this, 'default' ) && ! empty( $this->default ) && false === strpos( 'rgba', $this->default ) ) {
				$this->choices['alpha'] = false;
			}
		}

		if ( ( ! isset( $this->choices['mode'] ) ) || ( 'hex' !== $this->choices['mode'] || 'hue' !== $this->choices['mode'] ) ) {
			$this->choices['mode'] = 'hex';
		}

	}

	/**
	 * Sets the $sanitize_callback
	 *
	 * @access protected
	 */
	protected function set_sanitize_callback() {

		// If a custom sanitize_callback has been defined,
		// then we don't need to proceed any further.
		if ( ! empty( $this->sanitize_callback ) ) {
			return;
		}
		$this->sanitize_callback = array( 'Hoo_Sanitize_Values', 'color' );

	}
}
