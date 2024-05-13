<?php
/**
 * Interface - Form Field with `inputs`.
 *
 * @package WPGraphQL\GF\Type\Interface
 * @since 0.12.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Type\WPInterface;

use WPGraphQL\AppContext;
use WPGraphQL\GF\Registry\FieldInputRegistry;

/**
 * Class - FieldWithInputs
 */
class FieldWithInputs extends AbstractInterface {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithInputs';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'A Gravity Forms field with possible field inputs.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'inputs' => [
				'type'        => [ 'list_of' => FieldInput::$type ],
				'description' => __( 'The inputs for the field.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $source, array $args, AppContext $context ) {
					// Email fields without confirmation need to be coerced as an input.
					if ( $source instanceof \GF_Field_Email && ! $source->emailConfirmEnabled ) {
						$source->inputs = [
							[
								'autocompleteAttribute' => $source->autocompleteAttribute ?? null,
								'defaultValue'          => $source->defaultValue ?? null,
								'customLabel'           => $source->customLabel ?? null,
								'id'                    => $source->id ?? null,
								'label'                 => $source->label ?? null,
								'name'                  => $source->inputName ?? null,
								'placeholder'           => $source->placeholder ?? null,
							],
						];
					}

					/** @var \GF_Field $source */
					$context->gfField = $source;

					return isset( $source->inputs )
						// Include GraphQL Type in resolver.
						? array_map(
							static function ( $choice ) use ( $source ) {
								$choice['graphql_type'] = FieldInputRegistry::get_type_name( $source );

								return $choice;
							},
							$source->inputs
						)
						: null;
				},
			],
		];
	}
}
