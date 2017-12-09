<?php


/**
 * Nested section.
 */
class Hoo_Sections_Nested_Section extends WP_Customize_Section {

	/**
	 * The parent section.
	 *
	 * @access public
	 * @var string
	 */
	public $section;

	/**
	 * The section type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'hoo-nested';

	/**
	 * Gather the parameters passed to client JavaScript via JSON.
	 *
	 * @access public
	 * @return array The array to be exported to the client as JSON.
	 */
	public function json() {

		$array = wp_array_slice_assoc( (array) $this, array(
			'id',
			'description',
			'priority',
			'panel',
			'type',
			'description_hidden',
			'section',
		) );

		$array['title']          = html_entity_decode( $this->title, ENT_QUOTES, get_bloginfo( 'charset' ) );
		$array['content']        = $this->get_content();
		$array['active']         = $this->active();
		$array['instanceNumber'] = $this->instance_number;

		$array['customizeAction'] = esc_attr__( 'Customizing', 'avata' );
		if ( $this->panel ) {
			/* translators: The title. */
			$array['customizeAction'] = sprintf( esc_attr__( 'Customizing &#9656; %s', 'avata' ), esc_html( $this->manager->get_panel( $this->panel )->title ) );
		}
		return $array;
	}
}
