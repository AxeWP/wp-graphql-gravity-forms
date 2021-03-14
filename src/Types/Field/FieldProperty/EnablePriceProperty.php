<?php
/**
 * Allows enable price field property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.2.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Interfaces\FieldProperty;

/**
 * Class - EnablePriceProperty
 */
class EnablePriceProperty implements FieldProperty {
	/**
	 * Get 'enablePrice' property.
	 *
	 * Applies to: @TODO
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'enablePrice' => [
				'type'        => 'Boolean',
				'description' => __( 'This property is used when the radio button is a product option field and will be set to true. If not associated with a product, then it is false.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
