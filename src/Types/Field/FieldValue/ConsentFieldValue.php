<?php
/**
 * GraphQL Object Type - ConsentFieldValue
 * Values for an individual Consent field.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldValue
 * @since   0.3.0
 * @since   0.3.1 `value` changed to type `String`.
 */

namespace WPGraphQLGravityForms\Types\Field\FieldValue;

use GF_Field;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;
use WPGraphQLGravityForms\Interfaces\FieldValue;
use WPGraphQLGravityForms\Types\Field\ConsentField;

/**
 * Class - ConsentFieldValue
 */
class ConsentFieldValue implements Hookable, Type, FieldValue {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = ConsentField::TYPE . 'Value';

	/**
	 * Register hooks to WordPress.
	 */
	public function register_hooks() {
		add_action( 'graphql_register_types', [ $this, 'register_type' ] );
	}

	/**
	 * Register Object type to GraphQL schema.
	 */
	public function register_type() {
		register_graphql_object_type(
			self::TYPE,
			[
				'description' => __( 'Consent field value.', 'wp-graphql-gravity-forms' ),
				'fields'      => [
					'value' => [
						'type'        => 'String',
						'description' => __( 'The value. Returns the consent message on `true`, `null` on false.', 'wp-graphql-gravity-forms' ),
					],
				],
			]
		);
	}

	/**
	 * Get the field value.
	 *
	 * @param array    $entry Gravity Forms entry.
	 * @param GF_Field $field Gravity Forms field.
	 *
	 * @return array Entry field value.
	 */
	public static function get( array $entry, GF_Field $field ) : array {
			return [
				'value' => $entry[ $field['inputs'][1]['id'] ] ?? null,
			];
	}
}
