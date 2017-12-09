<?php

/**
 * Field overrides.
 */
class Hoo_Field_Preset extends Hoo_Field_Select {

	/**
	 * Sets the control type.
	 *
	 * @access protected
	 */
	protected function set_type() {

		$this->type = 'hoo-preset';

	}

	/**
	 * Sets the $multiple
	 *
	 * @access protected
	 */
	protected function set_multiple() {

		$this->multiple = 1;

	}
}
