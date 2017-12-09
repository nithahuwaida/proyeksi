<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Dimensions control.
 * multiple fields with CSS units validation.
 */
class Hoo_Control_Dimensions extends WP_Customize_Control {

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'hoo-dimensions';

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
	 * The hoo_config we're using for this control
	 *
	 * @access public
	 * @var string
	 */
	public $hoo_config = 'global';

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
		$this->json['l10n']    = $this->l10n();

		$this->json['inputAttrs'] = '';
		foreach ( $this->input_attrs as $attr => $value ) {
			$this->json['inputAttrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
		}

		if ( is_array( $this->choices ) ) {
			foreach ( $this->choices as $choice => $value ) {
				if ( 'labels' !== $choice && true === $value ) {
					$this->json['choices'][ $choice ] = true;
				}
			}
		}
		if ( is_array( $this->json['default'] ) ) {
			foreach ( $this->json['default'] as $key => $value ) {
				if ( isset( $this->json['choices'][ $key ] ) && ! isset( $this->json['value'][ $key ] ) ) {
					$this->json['value'][ $key ] = $value;
				}
			}
		}
	}

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @access public
	 */
	public function enqueue() {

		if ( class_exists( 'Hoo_Custom_Build' ) ) {
			Hoo_Custom_Build::register_dependency( 'jquery' );
			Hoo_Custom_Build::register_dependency( 'customize-base' );
		}

		$script_to_localize = 'hoo-build';
		if ( ! class_exists( 'Hoo_Custom_Build' ) || ! Hoo_Custom_Build::is_custom_build() ) {
			$script_to_localize = 'hoo-dimensions';
			wp_enqueue_script( 'hoo-dimensions', trailingslashit( Hoo::$url ) . 'controls/dimensions/dimensions.js', array( 'jquery', 'customize-base' ), false, true );
			wp_enqueue_style( 'hoo-dimensions-css', trailingslashit( Hoo::$url ) . 'controls/dimensions/dimensions.css', null );
		}
		wp_localize_script( $script_to_localize, 'dimensionshooL10n', $this->l10n() );
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
			<div class="wrapper">
				<div class="control">
					<# for ( choiceKey in data.default ) { #>
						<div class="{{ choiceKey }}">
							<h5>
								<# if ( ! _.isUndefined( data.choices.labels ) && ! _.isUndefined( data.choices.labels[ choiceKey ] ) ) { #>
									{{ data.choices.labels[ choiceKey ] }}
								<# } else if ( ! _.isUndefined( data.l10n[ choiceKey ] ) ) { #>
									{{ data.l10n[ choiceKey ] }}
								<# } else { #>
									{{ choiceKey }}
								<# } #>
							</h5>
							<div class="{{ choiceKey }} input-wrapper">
								<input {{{ data.inputAttrs }}} type="text" value="{{ data.value[ choiceKey ] }}"/>
							</div>
						</div>
					<# } #>
				</div>
			</div>
		</label>
		<?php
	}

	/**
	 * Render the control's content.
	 *
	 * @see WP_Customize_Control::render_content()
	 */
	protected function render_content() {}

	/**
	 * Returns an array of translation strings.
	 *
	 * @access protected
	 * @return string
	 */
	protected function l10n() {
		return array(
			'left-top'              => esc_attr__( 'Left Top', 'avata' ),
			'left-center'           => esc_attr__( 'Left Center', 'avata' ),
			'left-bottom'           => esc_attr__( 'Left Bottom', 'avata' ),
			'right-top'             => esc_attr__( 'Right Top', 'avata' ),
			'right-center'          => esc_attr__( 'Right Center', 'avata' ),
			'right-bottom'          => esc_attr__( 'Right Bottom', 'avata' ),
			'center-top'            => esc_attr__( 'Center Top', 'avata' ),
			'center-center'         => esc_attr__( 'Center Center', 'avata' ),
			'center-bottom'         => esc_attr__( 'Center Bottom', 'avata' ),
			'font-size'             => esc_attr__( 'Font Size', 'avata' ),
			'font-weight'           => esc_attr__( 'Font Weight', 'avata' ),
			'line-height'           => esc_attr__( 'Line Height', 'avata' ),
			'font-style'            => esc_attr__( 'Font Style', 'avata' ),
			'letter-spacing'        => esc_attr__( 'Letter Spacing', 'avata' ),
			'word-spacing'          => esc_attr__( 'Word Spacing', 'avata' ),
			'top'                   => esc_attr__( 'Top', 'avata' ),
			'bottom'                => esc_attr__( 'Bottom', 'avata' ),
			'left'                  => esc_attr__( 'Left', 'avata' ),
			'right'                 => esc_attr__( 'Right', 'avata' ),
			'center'                => esc_attr__( 'Center', 'avata' ),
			'size'                  => esc_attr__( 'Size', 'avata' ),
			'height'                => esc_attr__( 'Height', 'avata' ),
			'spacing'               => esc_attr__( 'Spacing', 'avata' ),
			'width'                 => esc_attr__( 'Width', 'avata' ),
			'height'                => esc_attr__( 'Height', 'avata' ),
			'invalid-value'         => esc_attr__( 'Invalid Value', 'avata' ),
		);
	}
}
