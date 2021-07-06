<?php
/**
 * GraphQL Input Type - FormsSortingInput
 * Sorting input type for Forms queries.
 *
 * @package WPGraphQLGravityForms\Types\Input
 * @since   0.6.0
 */

namespace WPGraphQLGravityForms\Types\Input;

use WPGraphQLGravityForms\Types\Enum\SortingInputEnum;

/**
 * Class - FormsSortingInput
 */
class FormsSortingInput extends AbstractInput {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'FormsSortingInput';

	/**
	 * Sets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'Sorting input fields for Forms queries.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 */
	public function get_type_fields() : array {
		return [
			'key'       => [
				'type'        => 'String',
				'description' => __( 'The key of the field to sort by.', 'wp-graphql-gravity-forms' ),
			],
			'direction' => [
				'type'        => SortingInputEnum::$type,
				'description' => __( 'The sorting direction.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
