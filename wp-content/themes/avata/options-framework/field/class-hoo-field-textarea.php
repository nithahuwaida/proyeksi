<?php

/**
 * Field overrides.
 */
class Hoo_Field_Textarea extends Hoo_Field_Hoo_Generic {

	/**
	 * Sets the $choices
	 *
	 * @access protected
	 */
	protected function set_choices() {

		if ( ! is_array( $this->choices ) ) {
			$this->choices = array();
		}
		$this->choices['element'] = 'textarea';
		$this->choices['rows']    = '5';

	}
}
