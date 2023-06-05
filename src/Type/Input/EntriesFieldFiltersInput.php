<?php
/**
 * GraphQL Input Type - EntriesFieldFiltersInput
 * Field Filters input type for Entries queries.
 *
 * @package WPGraphQL\GF\Type\Input
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Type\Input;

use WPGraphQL\GF\Type\Enum\FieldFiltersOperatorInputEnum;

/**
 * Class - EntriesFieldFiltersInput
 */
class EntriesFieldFiltersInput extends AbstractInput {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'EntriesFieldFiltersInput';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Field Filters input fields for Entries queries.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'key'          => [
				'type'        => 'String',
				'description' => __( 'Optional. The entry meta key to filter by. You can use the ID of the form field, or the names of any of the columns in Gravity Form\'s database table for entries, such as "date_created", "is_read, "created_by", etc. If omitted, the value will be checked against all meta keys. .', 'wp-graphql-gravity-forms' ),
			],
			'operator'     => [
				'type'        => FieldFiltersOperatorInputEnum::$type,
				'description' => __( 'The operator to use for filtering.', 'wp-graphql-gravity-forms' ),
			],
			// @todo - Is there a cleaner way to do this? Values can be any of these types.
			'stringValues' => [
				'type'        => [ 'list_of' => 'String' ],
				'description' => __( 'The field value(s) to filter by. Must be string values. If using this field, do not also use intValues, floatValues or boolValues.', 'wp-graphql-gravity-forms' ),
			],
			'intValues'    => [
				'type'        => [ 'list_of' => 'Int' ],
				'description' => __( 'The field value(s) to filter by. Must be integer values. If using this field, do not also use stringValues, floatValues or boolValues.', 'wp-graphql-gravity-forms' ),
			],
			'floatValues'  => [
				'type'        => [ 'list_of' => 'Float' ],
				'description' => __( 'The field value(s) to filter by. Must be float values. If using this field, do not also use stringValues, intValues or boolValues.', 'wp-graphql-gravity-forms' ),
			],
			'boolValues'   => [
				'type'        => [ 'list_of' => 'Boolean' ],
				'description' => __( 'The field value(s) to filter by. Must be boolean values. If using this field, do not also use stringValues, intValues or floatValues.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
