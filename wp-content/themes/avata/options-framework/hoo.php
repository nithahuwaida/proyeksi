<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// No need to proceed if Hoo already exists.
if ( class_exists( 'Hoo' ) ) {
	return;
}

// Include the autoloader.
include_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'autoloader.php';

if ( ! defined( 'HOO_OF_FILE' ) ) {
	define( 'HOO_OF_FILE', __FILE__ );
}

if ( ! function_exists( 'Hoo' ) ) {
	// @codingStandardsIgnoreStart
	/**
	 * Returns an instance of the Hoo object.
	 */
	function Hoo() {
		$hoo = Hoo_Toolkit::get_instance();
		return $hoo;
	}
	// @codingStandardsIgnoreEnd

}
// Start Hoo.
global $hoo;
$hoo = Hoo();
// Instamtiate the modules.
$hoo->modules = new Hoo_Modules();

// Make sure the path is properly set.
Hoo::$path = wp_normalize_path( dirname( __FILE__ ) );

if ( function_exists( 'is_link' ) && is_link( dirname( __FILE__ ) ) && function_exists( 'readlink' ) ) {
	// If the path is a symlink, get the target.
	Hoo::$path = readlink( Hoo::$path );
}

// Instantiate 2ndary classes.
new Hoo();

// Include deprecated functions & methods.
include_once wp_normalize_path( dirname( __FILE__ ) . '/core/deprecated.php' );

// Include the ariColor library.
include_once wp_normalize_path( dirname( __FILE__ ) . '/lib/class-aricolor.php' );

// Add an empty config for global fields.
Hoo::add_config( '' );

$custom_config_path = dirname( __FILE__ ) . '/custom-config.php';
$custom_config_path = wp_normalize_path( $custom_config_path );
if ( file_exists( $custom_config_path ) ) {
	include_once $custom_config_path;
}


