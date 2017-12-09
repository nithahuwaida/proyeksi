<?php

/**
 * Our main Hoo_Control object
 */
class Hoo_Custom_Build {

	/**
	 * Is this a custom build?
	 *
	 * @static
	 * @access private
	 * @var bool|null
	 */
	private static $is_custom_build = null;

	/**
	 * An array of dependencies for the script.
	 *
	 * @static
	 * @access private
	 * @var array
	 */
	private static $dependencies = array();

	/**
	 * Constructor.
	 *
	 * @access public
	 */
	public function __construct() {
		if ( ! self::is_custom_build() ) {
			return;
		}
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'customize_controls_enqueue_scripts' ), 500 );
	}

	/**
	 * Figure out if this is a custom build or not.
	 *
	 * @static
	 * @access public
	 * @return bool
	 */
	public static function is_custom_build() {
		if ( null === self::$is_custom_build ) {
			if ( file_exists( Hoo::$path . '/build.min.js' ) && file_exists( Hoo::$path . '/build.min.css' ) ) {
				self::$is_custom_build = true;
				return true;
			}
			self::$is_custom_build = false;
			return false;
		}
		return self::$is_custom_build;
	}

	/**
	 * Registers a dependency for the custom build JS.
	 *
	 * @static
	 * @access public
	 * @param string $dependency The script's identifier.
	 */
	public static function register_dependency( $dependency ) {
		if ( in_array( $dependency, self::$dependencies, true ) ) {
			return;
		}
		self::$dependencies[] = $dependency;
	}

	/**
	 * Enqueues the scripts and styles we need.
	 *
	 * @access public
	 */
	public function customize_controls_enqueue_scripts() {

		wp_enqueue_script( 'hoo-build', trailingslashit( Hoo::$url ) . 'build.min.js', self::$dependencies );
		wp_enqueue_style( 'hoo-build', trailingslashit( Hoo::$url ) . 'build.min.css', null );

	}
}
