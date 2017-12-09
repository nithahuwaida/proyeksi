<?php

/**
 * Manages the way Google Fonts are enqueued.
 */
final class Hoo_Modules_Webfonts_Link {

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
	 * The google link
	 *
	 * @access public
	 * @var string
	 */
	public $link = '';

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

		if ( ! isset( $args['enqueue'] ) || false !== $args['enqueue'] ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ), 105 );
		}
	}

	/**
	 * Calls all the other necessary methods to populate and create the link.
	 */
	public function enqueue() {

		// Go through our fields and populate $this->fonts.
		$this->webfonts->loop_fields( $this->config_id );

		$this->googlefonts->fonts = apply_filters( 'options-framework/enqueue_google_fonts', $this->googlefonts->fonts );

		// Goes through $this->fonts and adds or removes things as needed.
		$this->googlefonts->process_fonts();

		// Go through $this->fonts and populate $this->link.
		$this->create_link();

		// If $this->link is not empty then enqueue it.
		if ( '' !== $this->link ) {
			wp_enqueue_style( 'hoo_google_fonts', $this->link, array(), null );
		}
	}

	/**
	 * Creates the google-fonts link.
	 */
	public function create_link() {

		// If we don't have any fonts then we can exit.
		if ( empty( $this->googlefonts->fonts ) ) {
			return;
		}

		// Add a fallback to Roboto.
		$font = 'Roboto';

		// Get font-family + subsets.
		$link_fonts = array();
		foreach ( $this->googlefonts->fonts as $font => $variants ) {

			$variants = implode( ',', $variants );

			$link_font = str_replace( ' ', '+', $font );
			if ( ! empty( $variants ) ) {
				$link_font .= ':' . $variants;
			}
			$link_fonts[] = $link_font;
		}

		// Are we force-loading all subsets?
		if ( true === Hoo_Fonts_Google::$force_load_all_subsets ) {

			if ( isset( $this->google_fonts[ $font ]['subsets'] ) ) {
				foreach ( $this->google_fonts[ $font ]['subsets'] as $subset ) {
					$this->subsets[] = $subset;
				}
			}
		}

		if ( ! empty( $this->subsets ) ) {
			$this->subsets = array_unique( $this->subsets );
		}

		$this->link = add_query_arg( array(
			'family' => str_replace( '%2B', '+', urlencode( implode( '|', $link_fonts ) ) ),
			'subset' => rawurlencode( implode( ',', $this->googlefonts->subsets ) ),
		), 'https://fonts.googleapis.com/css' );

	}
}
