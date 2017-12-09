<?php

/**
 * Manages the way Google Fonts are enqueued.
 */
final class Hoo_Modules_Webfonts_Async {

	/**
	 * The config ID.
	 *
	 * @access protected
	 * @var string
	 */
	protected $config_id;

	/**
	 * The Hoo_Modules_Webfonts object.
	 *
	 * @access protected
	 * @var object
	 */
	protected $webfonts;

	/**
	 * The Hoo_Fonts_Google object.
	 *
	 * @access protected
	 * @var object
	 */
	protected $googlefonts;

	/**
	 * Constructor.
	 *
	 * @access public
	 * @param string $config_id   The config-ID.
	 * @param object $webfonts    The Hoo_Modules_Webfonts object.
	 * @param object $googlefonts The Hoo_Fonts_Google object.
	 * @param array  $args        Extra args we want to pass.
	 */
	public function __construct( $config_id, $webfonts, $googlefonts, $args = array() ) {

		$this->config_id   = $config_id;
		$this->webfonts    = $webfonts;
		$this->googlefonts = $googlefonts;

		add_action( 'wp_head', array( $this, 'webfont_loader' ) );
	}

	/**
	 * Webfont Loader for Google Fonts.
	 *
	 * @access public
	 */
	public function webfont_loader() {

		// Go through our fields and populate $this->fonts.
		$this->webfonts->loop_fields( $this->config_id );

		$this->googlefonts->fonts = apply_filters( 'options-framework/enqueue_google_fonts', $this->googlefonts->fonts );

		// Goes through $this->fonts and adds or removes things as needed.
		$this->googlefonts->process_fonts();

		$fonts_to_load = array();
		foreach ( $this->googlefonts->fonts as $font => $weights ) {
			foreach ( $weights as $key => $value ) {
				$weights[ $key ] = str_replace( array( 'regular', 'bold', 'italic' ), array( '400', '', 'i' ), $value );
				if ( 'i' === $value ) {
					$weights[ $key ] = '400i';
				}
			}
			$fonts_to_load[] = $font . ':' . join( ',', $weights );
		}
		wp_enqueue_script( 'webfont-loader', 'https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js' );
		if ( empty( $fonts_to_load ) ) {
			return;
		}
		wp_add_inline_script(
			'webfont-loader',
			'WebFont.load({google:{families:[\'' . join( '\', \'', $fonts_to_load ) . '\']}});',
			'after'
		);
	}
}
