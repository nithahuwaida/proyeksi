<?php

/**
 * Field overrides.
 */
class Hoo_Field_Switch extends Hoo_Field_Checkbox {

	/**
	 * Sets the control type.
	 *
	 * @access protected
	 */
	protected function set_type() {

		$this->type = 'hoo-switch';

	}

	/**
	 * Sets the control choices.
	 *
	 * @access protected
	 */
	protected function set_choices() {

		if ( ! is_array( $this->choices ) ) {
			$this->choices = array();
		}

		$l10n = array(
			'on'  => esc_attr__( 'On', 'avata' ),
			'off' => esc_attr__( 'Off', 'avata' ),
		);

		if ( ! isset( $this->choices['on'] ) ) {
			$this->choices['on'] = $l10n['on'];
		}

		if ( ! isset( $this->choices['off'] ) ) {
			$this->choices['off'] = $l10n['off'];
		}

		if ( ! isset( $this->choices['round'] ) ) {
			$this->choices['round'] = false;
		}

	}
}
