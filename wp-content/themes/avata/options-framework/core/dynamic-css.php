<?php

// Do not allow directly accessing this file.
if ( ! class_exists( 'Hoo' ) ) {
	die( 'File can\'t be accessed directly' );
}

// Make sure we set the correct MIME type.
header( 'Content-Type: text/css' );

// Echo the styles.
$configs = Hoo::$config;
foreach ( $configs as $config_id => $args ) {
	if ( true === $args['disable_output'] ) {
		continue;
	}

	$styles = Hoo_Styles_Frontend::loop_controls( $config_id );
	$styles = apply_filters( "options-framework/{$config_id}/dynamic_css", $styles );

	// Some people put weird stuff in their CSS, KSES tends to be greedy.
	$styles = str_replace( '<=', '&lt;=', $styles );

	$styles = wp_kses_post( $styles );

	// @codingStandardsIgnoreStart

	// Why both KSES and strip_tags? Because we just added some '>'.
	// kses replaces lone '>' with &gt;.
	echo strip_tags( str_replace( '&gt;', '>', $styles ) );
	// @codingStandardsIgnoreStop
}
