<?php

/**
 * Field overrides.
 */
class Hoo_Field_Code extends Hoo_Field {

	/**
	 * Sets the control type.
	 *
	 * @access protected
	 */
	protected function set_type() {

		$this->type = 'hoo-code';

	}

	/**
	 * Sets the $choices
	 *
	 * @access protected
	 */
	protected function set_choices() {

		// Make sure we have some defaults in case none are defined.
		$defaults = array(
			'language' => 'css',
			'theme'    => 'elegant',
		);
		$this->choices = wp_parse_args( $this->choices, $defaults );

		// Make sure the choices are defined and set as an array.
		if ( ! is_array( $this->choices ) ) {
			$this->choices = array();
		}

		// An array of valid languages.
		$valid_languages = array(
			'coffescript',
			'css',
			'haml',
			'htmlembedded',
			'htmlmixed',
			'javascript',
			'markdown',
			'php',
			'sass',
			'smarty',
			'sql',
			'stylus',
			'textile',
			'twig',
			'xml',
			'yaml',
		);
		// Make sure the defined language exists.
		// If not, fallback to CSS.
		if ( ! in_array( $this->choices['language'], $valid_languages, true ) ) {
			$this->choices['language'] = 'css';
		}
		// Hack for 'html' mode.
		if ( 'html' === $this->choices['language'] ) {
			$this->choices['language'] = 'htmlmixed';
		}

		// Set the theme.
		$valid_themes = array(
			'hoo-light' => 'elegant',
			'light'       => 'elegant',
			'elegant'     => 'elegant',
			'hoo-dark'  => 'monokai',
			'dark'        => 'monokai',
			'monokai'     => 'monokai',
			'material'    => 'material',
		);
		if ( isset( $valid_themes[ $this->choices['theme'] ] ) ) {
			$this->choices['theme'] = $valid_themes[ $this->choices['theme'] ];
		} else {
			$this->choices['theme'] = 'elegant';
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
		// Code fields must NOT be filtered. Their values usually contain CSS/JS.
		// It is the responsibility of the theme/plugin that registers this field
		// to properly apply any necessary filtering.
		$this->sanitize_callback = array( 'Hoo_Sanitize_Values', 'unfiltered' );

	}
}
