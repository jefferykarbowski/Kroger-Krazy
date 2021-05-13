<?php

/**
 * Rest API Setup for list and lists
 *
 * @link       https://krogerkrazy.com
 * @since      1.0.0
 *
 * @package    Krogerkrazy
 * @subpackage Krogerkrazy/includes
 */

class KrogerKrazy_Rest_API extends WP_REST_Controller {


	/**
	 * Customize the Rest API Endpoint
	 * @param $slug
	 * @return string
	 */
	public function kk_api_slug( ) {
		return 'kk_api';
	}


	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes() {

		//Meta Fields that should be added to the API
		$list_item_meta_fields = array(
			'price',
			'final_price',
			'is_heading',
		);
		//Iterate through all fields and add register each of them to the API
		foreach ($list_item_meta_fields as $field) {
			register_rest_field( 'list_item',
				$field,
				array(
					'get_callback'    => array( $this, 'kk_get_meta'),
					'update_callback' => array( $this, 'kk_update_meta'),
					'schema'          => null,
				)
			);
		}

		$args = array(
			'type' => 'number',
			'description' => 'Price',
			'single' => true,
			'show_in_rest' => true,
		);
		register_meta( 'list_item', 'price', $args );

		$args = array(
			'type' => 'number',
			'description' => 'Final Price',
			'single' => true,
			'show_in_rest' => true,
		);
		register_meta( 'list_item', 'final_price', $args );

		$args = array(
			'type' => 'boolean',
			'description' => 'Is this to be used as an item heading',
			'single' => true,
			'default' => false,
			'show_in_rest' => true,
		);
		register_meta( 'list_item', 'is_heading', $args );



		//Meta Fields that should be added to the API
		$list_meta_fields = array(
			'item_order',
			'expires',
			'updated',
		);
		//Iterate through all fields and add register each of them to the API
		foreach ($list_meta_fields as $field) {
			register_rest_field( 'list',
				$field,
				array(
					'get_callback'    => array( $this, 'kk_get_term_meta'),
					'update_callback' => array( $this, 'kk_update_term_meta'),
					'schema'          => null,
				)
			);
		}
		$args = array(
			'type' => 'array',
			'description' => 'Order of the associated items',
			'single' => true,
			'show_in_rest' => true,
		);
		register_term_meta( 'list', 'item_order', $args );

		$args = array(
			'type' => 'string',
			'description' => 'Last Updated',
			'single' => true,
			'show_in_rest' => true,
		);
		register_term_meta( 'list', 'updated', $args );

		$args = array(
			'type' => 'string',
			'description' => 'Expires Date',
			'single' => true,
			'show_in_rest' => true,
		);
		register_term_meta( 'list', 'expires', $args );


	}


	/**
	 * Handler for getting custom field data.
	 *
	 * @since 0.1.0
	 *
	 *
	 * @param array $object The object from the response
	 * @param string $field_name Name of field
	 *
	 * @return mixed
	 */
	public function kk_get_meta( $object, $field_name ) {
			return get_post_meta( $object[ 'id' ], $field_name );
	}
	public function kk_get_term_meta( $object, $field_name ) {
		return get_term_meta( $object[ 'id' ], $field_name );
	}


	/**
	 * Handler for updating custom field data.
	 *
	 * @since 0.1.0
	 * @link  http://manual.unyson.io/en/latest/helpers/php.html#database
	 * @param mixed $value The value of the field
	 * @param object $object The object from the response
	 * @param string $field_name Name of field
	 *
	 * @return bool|int
	 */
	public function kk_update_meta( $value, $object, $field_name ) {
		if ( !isset($value) ) {
			return true;
		}
		return update_post_meta( $object->ID, $field_name, maybe_serialize( strip_tags( $value ) ) );
	}

	public function kk_update_term_meta( $value, $object, $field_name ) {
		if ( !isset($value) ) {
			return true;
		}
		return update_term_meta( $object->ID, $field_name, maybe_serialize( strip_tags( $value ) ) );
	}

}
