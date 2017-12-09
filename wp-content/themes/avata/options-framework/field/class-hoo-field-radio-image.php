<?php

/**
 * Field overrides.
 */
class Hoo_Field_Radio_Image extends Hoo_Field_Radio {

	/**
	 * Sets the control type.
	 *
	 * @access protected
	 */
	protected function set_type() {

		$this->type = 'hoo-radio-image';

	}
}
