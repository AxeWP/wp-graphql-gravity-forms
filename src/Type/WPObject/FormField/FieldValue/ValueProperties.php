<?php
/**
 * Array configs for all field properties.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldValue;
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldValue;

use GF_Field;
use WPGraphQL\AppContext;
use WPGraphQL\GF\Type\WPObject\FormField\FieldValue\ValueProperty;
use WPGraphQL\GF\Utils\Utils;

/**
 * Class - ValueProperties
 */
class ValueProperties {
	/**
	 * Get `value` property.
	 */
	public static function value() : array {
		return [
			'value' => [
				'type'        => 'String',
				'description' => __( 'The string-formatted entry value for the `formField`. For complex fields this might be a JSON-encoded or serialized array.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function ( $source, array $args, AppContext $context ) {
					if ( ! self::is_field_and_entry( $source, $context ) ) {
						return null;
					}

					return $source->get_value_export( $context->gfEntry->entry, $source->id ) ?: null;
				},
			],
		];
	}

	/**
	 * Get `consentValue` property.
	 *
	 * @return array
	 */
	public static function consent_value() : array {
		return [
			'consentValue' => [
				'type'        => 'Boolean',
				'description' => __( 'Consent field value. This is `true` when consent is given, `false` when it is not.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function ( $source, array $args, AppContext $context ) {
					if ( ! self::is_field_and_entry( $source, $context ) ) {
						return null;
					}

					$input_key = $source->inputs[0]['id'];
					return isset( $context->gfEntry->entry[ $input_key ] ) ? (bool) $context->gfEntry->entry[ $input_key ] : null;
				},
			],
		];
	}

	/**
	 * Get `addressValues` property.
	 */
	public static function address_values() : array {
		return [
			'addressValues' => [
				'type'        => ValueProperty\AddressValueProperty::$type,
				'description' => __( 'Address field value.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function ( $source, array $args, AppContext $context ) {
					if ( ! self::is_field_and_entry( $source, $context ) ) {
						return null;
					}

					return [
						'street'  => $context->gfEntry->entry[ $source->inputs[0]['id'] ] ?: null,
						'lineTwo' => $context->gfEntry->entry[ $source->inputs[1]['id'] ] ?: null,
						'city'    => $context->gfEntry->entry[ $source->inputs[2]['id'] ] ?: null,
						'state'   => $context->gfEntry->entry[ $source->inputs[3]['id'] ] ?: null,
						'zip'     => $context->gfEntry->entry[ $source->inputs[4]['id'] ] ?: null,
						'country' => $context->gfEntry->entry[ $source->inputs[5]['id'] ] ?: null,
					];
				},
			],
		];
	}

	/**
	 * Get `values` property for Chained Select field.
	 */
	public static function chained_select_values() : array {
		return [
			'values' => [
				'type'        => [ 'list_of' => 'String' ],
				'description' => __( 'ChainedSelect field value.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function ( $source, array $args, AppContext $context ) {
					if ( ! self::is_field_and_entry( $source, $context ) ) {
						return null;
					}

					return array_map(
						function( $input ) use ( $context ) {
							return $context->gfEntry->entry[ $input['id'] ] ?: null;
						},
						$source->inputs
					);
				},
			],
		];
	}

	/**
	 * Get `checkboxValues` property.
	 */
	public static function checkbox_values() : array {
		return [
			'checkboxValues' => [
				'type'        => [ 'list_of' => ValueProperty\CheckboxValueProperty::$type ],
				'description' => __( 'Checkbox field value.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function ( $source, array $args, AppContext $context ) {
					if ( ! self::is_field_and_entry( $source, $context ) ) {
						return null;
					}

					$field_input_ids = wp_list_pluck( $source->inputs, 'id' );
					$checkboxValues  = [];

					foreach ( $field_input_ids as $input_id ) {
						$input_key = array_search( $input_id, array_column( $source->inputs, 'id' ), true );

						$value = ! empty( $context->gfEntry->entry[ $input_id ] ) ? $context->gfEntry->entry[ $input_id ] : null;
						$text  = $source->choices[ $input_key ]['text'] ?: $value;

						$checkboxValues[] = [
							'inputId' => $input_id,
							'value'   => $value,
							'text'    => $text,
						];
					}

					return $checkboxValues;
				},
			],
		];
	}

	/**
	 * Get `listValues` property.
	 */
	public static function list_values() : array {
		return [
			'listValues' => [
				'type'        => [ 'list_of' => ValueProperty\ListValueProperty::$type ],
				'description' => __( 'List field value.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function ( $source, array $args, AppContext $context ) {
					if ( ! self::is_field_and_entry( $source, $context ) ) {
						return null;
					}

					$values = $context->gfEntry->entry[ $source->id ] ?: null;

					if ( empty( $values ) ) {
						return null;
					}

					if ( is_string( $values ) ) {
						$values = maybe_unserialize( $values );
					} else {
						$values = $source->create_list_array_recursive( $values );
					}

					// If columns are enabled, save each row-value pair.
					if ( $source->enableColumns ) {
						// Save each row-value pair.
						return array_map(
							function( $row ) {
								$row_values = [];

								foreach ( $row as $single_value ) {
									$row_values[] = $single_value;
								}

								return [
									'values' => $row_values,
								];
							},
							$values
						);
					}

					// If no columns, entry values can be mapped directly to 'value'.
					return array_map(
						function( $single_value ) {
							return [
								'values' => [ $single_value ], // $single_value must be Iteratable.
							];
						},
						$values
					);
				},
			],
		];
	}

	/**
	 * Get `nameValues` property.
	 */
	public static function name_values() : array {
		return [
			'nameValues' => [
				'type'        => ValueProperty\NameValueProperty::$type,
				'description' => __( 'Name field value.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function ( $source, array $args, AppContext $context ) {
					if ( ! self::is_field_and_entry( $source, $context ) ) {
						return null;
					}

					return [
						'prefix' => $context->gfEntry->entry[ $source->inputs[0]['id'] ] ?: null,
						'first'  => $context->gfEntry->entry[ $source->inputs[1]['id'] ] ?: null,
						'middle' => $context->gfEntry->entry[ $source->inputs[2]['id'] ] ?: null,
						'last'   => $context->gfEntry->entry[ $source->inputs[3]['id'] ] ?: null,
						'suffix' => $context->gfEntry->entry[ $source->inputs[4]['id'] ] ?: null,
					];
				},
			],
		];
	}

	/**
	 * Get `imageValues` property.
	 */
	public static function image_values() : array {
		return [
			'imageValues' => [
				'type'        => ValueProperty\ImageValueProperty::$type,
				'description' => __( 'Name field value.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function ( $source, array $args, AppContext $context ) {
					if ( ! self::is_field_and_entry( $source, $context ) ) {
						return null;
					}

					$value = array_pad( explode( '|:|', $context->gfEntry->entry[ $source->id ] ), 5, false );
					return [
						'altText'     => $value[4] ?: null,
						'caption'     => $value[2] ?: null,
						'description' => $value[3] ?: null,
						'title'       => $value[1] ?: null,
						'url'         => $value[0] ?: null,
					];
				},
			],
		];
	}

	/**
	 * Get `timeValues` property.
	 */
	public static function time_values() : array {
		return [
			'timeValues' => [
				'type'        => ValueProperty\TimeValueProperty::$type,
				'description' => __( 'Time field value.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function ( $source, array $args, AppContext $context ) {
					if ( ! self::is_field_and_entry( $source, $context ) ) {
						return null;
					}

						$display_value  = $context->gfEntry->entry[ $source->id ];
						$parts_by_colon = explode( ':', $display_value );
						$hours          = $parts_by_colon[0] ?? '';
						$parts_by_space = explode( ' ', $display_value );
						$am_pm          = $parts_by_space[1] ?? '';
						$minutes        = rtrim( ltrim( $display_value, "{$hours}:" ), " {$am_pm}" );

						return [
							'displayValue' => $display_value ?: null,
							'hours'        => $hours ?: null,
							'minutes'      => $minutes ?: null,
							'amPm'         => $am_pm ?: null,
						];
				},
			],
		];
	}

	/**
	 * Get `values` property.
	 */
	public static function values() : array {
		return [
			'values' => [
				'type'        => [ 'list_of' => 'String' ],
				'description' => __( 'Checkbox field value.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function ( $source, array $args, AppContext $context ) {
					if ( ! self::is_field_and_entry( $source, $context ) ) {
						return null;
					}

					$values = ! empty( $context->gfEntry->entry[ $source->id ] ) ? $context->gfEntry->entry[ $source->id ] : null;

					if ( null === $values ) {
						return $values;
					}

					if ( 'multiselect' === $source->inputType ) {
						$values = $source->to_array( $values );
					}

					$values = Utils::maybe_decode_json( $values );

					// Sometimes GF likes to nest their jsons twice.
					if ( is_string( $values ) ) { // @phpstan-ignore-line

						$values = Utils::maybe_decode_json( $values );
					}

					return $values ?: null;
				},
			],
		];
	}

	/**
	 * Checks that the necessary values to retrieve the values are set in the resolver.
	 *
	 * @param mixed      $source .
	 * @param AppContext $context .
	 */
	private static function is_field_and_entry( $source, AppContext $context ) : bool {
		return $source instanceof GF_Field
			&& isset( $context->gfEntry )
			&& isset( $context->gfEntry->entry );
	}
}
