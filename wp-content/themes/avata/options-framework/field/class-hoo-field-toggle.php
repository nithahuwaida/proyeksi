<?php

/**
 * Field overrides.
 */
class Hoo_Field_Toggle extends Hoo_Field_Checkbox {

	/**
	 * Sets the control type.
	 *
	 * @access protected
	 */
	protected function set_type() {

		$this->type = 'hoo-toggle';

	}
}
