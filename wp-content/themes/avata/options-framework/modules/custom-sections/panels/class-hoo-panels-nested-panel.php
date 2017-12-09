<?php

/**
 * Nested panel.
 */
class Hoo_Panels_Nested_Panel extends WP_Customize_Panel {

	/**
	 * The parent panel.
	 *
	 * @access public
	 * @var string
	 */
	public $panel;

	/**
	 * Type of this panel.
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
			'type',
			'panel',
		) );

		$array['title']          = html_entity_decode( $this->title, ENT_QUOTES, get_bloginfo( 'charset' ) );
		$array['content']        = $this->get_content();
		$array['active']         = $this->active();
		$array['instanceNumber'] = $this->instance_number;

		return $array;
	}
}
