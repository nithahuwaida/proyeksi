<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds a "code" control, using CodeMirror.
 */
class Hoo_Control_Code extends WP_Customize_Control {

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'hoo-code';

	/**
	 * Used to automatically generate all CSS output.
	 *
	 * @access public
	 * @var array
	 */
	public $output = array();

	/**
	 * Data type
	 *
	 * @access public
	 * @var string
	 */
	public $option_type = 'theme_mod';

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @access public
	 */
	public function enqueue() {

		// Register codemirror.
		wp_register_script( 'codemirror', trailingslashit( Hoo::$url ) . 'assets/vendor/codemirror/lib/codemirror.js', array( 'jquery' ) );

		// If we're using html mode, we'll also need to include the multiplex addon
		// as well as dependencies for XML, JS, CSS languages.
		switch ( $this->choices['language'] ) {
			case 'html':
			case 'htmlmixed':
				wp_enqueue_script( 'codemirror-multiplex', trailingslashit( Hoo::$url ) . 'assets/vendor/codemirror/addon/mode/multiplex.js', array( 'jquery', 'codemirror' ) );
				wp_enqueue_script( 'codemirror-language-xml', trailingslashit( Hoo::$url ) . 'assets/vendor/codemirror/mode/xml/xml.js', array( 'jquery', 'codemirror' ) );
				wp_enqueue_script( 'codemirror-language-javascript', trailingslashit( Hoo::$url ) . 'assets/vendor/codemirror/mode/javascript/javascript.js', array( 'jquery', 'codemirror' ) );
				wp_enqueue_script( 'codemirror-language-css', trailingslashit( Hoo::$url ) . 'assets/vendor/codemirror/mode/css/css.js', array( 'jquery', 'codemirror' ) );
				wp_enqueue_script( 'codemirror-language-htmlmixed', trailingslashit( Hoo::$url ) . 'assets/vendor/codemirror/mode/htmlmixed/htmlmixed.js', array( 'jquery', 'codemirror', 'codemirror-multiplex', 'codemirror-language-xml', 'codemirror-language-javascript', 'codemirror-language-css' ) );
				break;
			case 'php':
				wp_enqueue_script( 'codemirror-language-xml', trailingslashit( Hoo::$url ) . 'assets/vendor/codemirror/mode/xml/xml.js', array( 'jquery', 'codemirror' ) );
				wp_enqueue_script( 'codemirror-language-clike', trailingslashit( Hoo::$url ) . 'assets/vendor/codemirror/mode/clike/clike.js', array( 'jquery', 'codemirror' ) );
				wp_enqueue_script( 'codemirror-language-php', trailingslashit( Hoo::$url ) . 'assets/vendor/codemirror/mode/php/php.js', array( 'jquery', 'codemirror', 'codemirror-language-xml', 'codemirror-language-clike' ) );
				break;
			default:
				// Add language script.
				wp_enqueue_script( 'codemirror-language-' . $this->choices['language'], trailingslashit( Hoo::$url ) . 'assets/vendor/codemirror/mode/' . $this->choices['language'] . '/' . $this->choices['language'] . '.js', array( 'jquery', 'codemirror' ) );
				break;
		}

		// Add theme styles.
		wp_enqueue_style( 'codemirror-theme-' . $this->choices['theme'], trailingslashit( Hoo::$url ) . 'assets/vendor/codemirror/theme/' . $this->choices['theme'] . '.css' );

		wp_enqueue_script( 'hoo-code', trailingslashit( Hoo::$url ) . 'controls/code/code.js', array( 'jquery', 'customize-base', 'codemirror' ), false, true );
		wp_enqueue_style( 'hoo-code-css', trailingslashit( Hoo::$url ) . 'controls/code/code.css', null );

	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @see WP_Customize_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();

		$this->json['default'] = $this->setting->default;
		if ( isset( $this->default ) ) {
			$this->json['default'] = $this->default;
		}
		$this->json['output']  = $this->output;
		$this->json['value']   = $this->value();
		$this->json['choices'] = $this->choices;
		$this->json['link']    = $this->get_link();
		$this->json['id']      = $this->id;

		$this->json['inputAttrs'] = '';
		foreach ( $this->input_attrs as $attr => $value ) {
			$this->json['inputAttrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
		}

	}

	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
	 *
	 * @see WP_Customize_Control::print_template()
	 *
	 * @access protected
	 */
	protected function content_template() {
		?>
		<div class="hoo-controls-loading-spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>
		<label>
			<# if ( data.label ) { #><span class="customize-control-title">{{{ data.label }}}</span><# } #>
			<# if ( data.description ) { #><span class="description customize-control-description">{{{ data.description }}}</span><# } #>
			<div class="codemirror-hoo-wrapper">
				<textarea {{{ data.inputAttrs }}} class="hoo-codemirror-editor">{{{ data.value }}}</textarea>
			</div>
		</label>
		<?php
	}
}
