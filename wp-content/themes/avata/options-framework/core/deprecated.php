<?php

// @codingStandardsIgnoreFile

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'hoo_get_option' ) ) {
	/**
	 * Get the value of a field.
	 * This is a deprecated function that we in use when there was no API.
	 * Please use the Hoo::get_option() method instead.
	 * Documentation is available for the new method on https://github.com/aristath/options-framework/wiki/Getting-the-values
	 *
	 * @return mixed
	 */
	function hoo_get_option( $option = '' ) {
		return Hoo::get_option( '', $option );
	}
}

if ( ! function_exists( 'hoo_sanitize_hex' ) ) {
	function hoo_sanitize_hex( $color ) {
		return Hoo_Color::sanitize_hex( $color );
	}
}

if ( ! function_exists( 'hoo_get_rgb' ) ) {
	function hoo_get_rgb( $hex, $implode = false ) {
		return Hoo_Color::get_rgb( $hex, $implode );
	}
}

if ( ! function_exists( 'hoo_get_rgba' ) ) {
	function hoo_get_rgba( $hex = '#fff', $opacity = 100 ) {
		return Hoo_Color::get_rgba( $hex, $opacity );
	}
}

if ( ! function_exists( 'hoo_get_brightness' ) ) {
	function hoo_get_brightness( $hex ) {
		return Hoo_Color::get_brightness( $hex );
	}
}

/**
 * Class was deprecated in 2.2.7
 *
 * @see https://github.com/aristath/options-framework/commit/101805fd689fa8828920b789347f13efc378b4a7
 */
if ( ! class_exists( 'Hoo_Colourlovers' ) ) {
	/**
	 * Deprecated.
	 */
	class Hoo_Colourlovers {
		public static function get_palettes( $palettes_nr = 5 ) {
			return array();
		}
	}
}
