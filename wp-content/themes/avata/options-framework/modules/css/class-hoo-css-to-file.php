<?php

/**
 * Handles writing CSS to a file.
 */
class Hoo_CSS_To_File {

	/**
	 * Fallback to inline CSS?
	 *
	 * @access protected
	 * @var bool
	 */
	protected $fallback = false;

	/**
	 * Constructor.
	 *
	 * @access public
	 */
	public function __construct() {

		// If the file doesn't exist, create it.
		if ( ! file_exists( $this->get_path( 'file' ) ) ) {
			// If the file-write fails, fallback to inline
			// and cache the failure so we don't try again immediately.
			$this->write_file();
		}
		add_action( 'customize_save_after', array( $this, 'write_file' ) );
	}

	/**
	 * Gets the path of the CSS file and folder in the filesystem.
	 *
	 * @access protected
	 * @param string $context Can be "file" or "folder". If empty, returns both as array.
	 * @return string|array
	 */
	protected function get_path( $context = '' ) {

		$upload_dir = wp_upload_dir();

		$paths = array(
			'file'   => wp_normalize_path( $upload_dir['basedir'] . '/hoo-css/styles.css' ),
			'folder' => wp_normalize_path( $upload_dir['basedir'] . '/hoo-css' ),
		);

		if ( 'file' === $context ) {
			return $paths['file'];
		}
		if ( 'folder' === $context ) {
			return $paths['folder'];
		}
		return $paths;

	}

	/**
	 * Gets the URL of the CSS file in the filesystem.
	 *
	 * @access public
	 * @return string
	 */
	public function get_url() {

		$upload_dir = wp_upload_dir();
		return esc_url_raw( $upload_dir['baseurl'] . '/hoo-css/styles.css' );

	}

	/**
	 * Gets the timestamp of the file.
	 * This will be used as "version" for cache-busting purposes.
	 *
	 * @access public
	 * @return string|bool
	 */
	public function get_timestamp() {

		if ( file_exists( $this->get_path( 'file' ) ) ) {
			return filemtime( $this->get_path( 'file' ) );
		}
		return false;
	}

	/**
	 * Writes the file to disk.
	 *
	 * @access public
	 * @return bool
	 */
	public function write_file() {

		$css = array();
		$configs = Hoo::$config;
		foreach ( $configs as $config_id => $args ) {
			// Get the CSS we want to write.
			$css[ $config_id ] = apply_filters( "options-framework/{$config_id}/dynamic_css", Hoo_Modules_CSS::loop_controls( $config_id ) );
		}
		$css = implode( $css, '' );

		// Minimize the CSS a bit.
		$css = str_replace( array( "\n", "\t", "\r\n" ), '', $css );
		$css = str_replace( array( '{ ', '{  ', '{   ' ), '{', $css );
		$css = str_replace( ': ', ':', $css );
		$css = str_replace( array( '; ', ';  ', ';   ' ), ';', $css );
		$css = explode( '}', $css );
		$css = array_unique( $css );
		$css = implode( $css, '}' );

		// If the folder doesn't exist, create it.
		if ( ! file_exists( $this->get_path( 'folder' ) ) ) {
			wp_mkdir_p( $this->get_path( 'folder' ) );
		}

		$filesystem = $this->get_filesystem();
		$write_file = (bool) $filesystem->put_contents( $this->get_path( 'file' ), $css );
		if ( ! $write_file ) {
			$this->fallback = true;
			set_transient( 'hoo_css_write_to_file_failed', true, HOUR_IN_SECONDS );
		}
		return $write_file;

	}

	/**
	 * Gets the WP_Filesystem object.
	 *
	 * @access protected
	 * @return object
	 */
	protected function get_filesystem() {

		// The Wordpress filesystem.
		global $wp_filesystem;

		if ( empty( $wp_filesystem ) ) {
			require_once wp_normalize_path( ABSPATH . '/wp-admin/includes/file.php' );
			WP_Filesystem();
		}

		return $wp_filesystem;
	}
}
