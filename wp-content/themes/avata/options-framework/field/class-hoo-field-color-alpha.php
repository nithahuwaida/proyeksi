<?php

/**
 * Field overrides.
 */
class Hoo_Field_Color_Alpha extends Hoo_Field_Color {

	/**
	 * Sets the $choices
	 *
	 * @access protected
	 */
	protected function set_choices() {

		if ( ! is_array( $this->choices ) ) {
			$this->choices = array();
		}
		$this->choices['alpha'] = true;

	}
}
