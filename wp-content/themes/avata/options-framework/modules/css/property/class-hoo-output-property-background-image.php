<?php

/**
 * Output overrides.
 */
class Hoo_Output_Property_Background_Image extends Hoo_Output_Property {

	/**
	 * Modifies the value.
	 *
	 * @access protected
	 */
	protected function process_value() {

		if ( is_array( $this->value ) && isset( $this->value['url'] ) ) {
			$this->value = $this->value['url'];
		}

		if ( false === strpos( $this->value, 'gradient' ) && false === strpos( $this->value, 'url(' ) ) {
			if ( empty( $this->value ) ) {
				return;
			}

			if ( preg_match( '/^\d+$/', $this->value ) ) {
				$this->value = 'url("' . wp_get_attachment_url( $this->value ) . '")';
			} else {
				$this->value = 'url("' . $this->value . '")';
			}
		}
	}
}
