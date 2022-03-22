<?php
/**
 * Array configs for all field properties.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldValue;
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldValue;

use GFCommon;
use GF_Field;
use GF_Field_FileUpload;
use GFFormsModel;
use WPGraphQL\AppContext;
use WPGraphQL\GF\Type\WPObject\FormField\FieldValue\ValueProperty;
use WPGraphQL\GF\Utils\Utils;

/**
 * Class - FieldValues
 */
class FieldValues {
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
				'type'        => ValueProperty\AddressFieldValue::$type,
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
	 * Get `checkboxValues` property.
	 */
	public static function checkbox_values() : array {
		return [
			'checkboxValues' => [
				'type'        => [ 'list_of' => ValueProperty\CheckboxFieldValue::$type ],
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
				'type'        => [ 'list_of' => ValueProperty\ListFieldValue::$type ],
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
				'type'        => ValueProperty\NameFieldValue::$type,
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
	 * Get `fileUploadValue` property.
	 *
	 * @return array
	 */
	public static function file_upload_values() : array {
		return [
			'fileUploadValues' => [
				'type'        => [ 'list_of' => ValueProperty\FileUploadFieldValue::$type ],
				'description' => __( 'File upload value', 'wp-graphql-gravity-forms' ),
				'resolve'     => function( $source, array $args, AppContext $context ) {
					if ( ! self::is_field_and_entry( $source, $context ) ) {
						return null;
					}

					return self::get_file_upload_extra_entry_metadata( $source, $context->gfEntry->entry, $context->gfForm->form ) ?: null;
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
				'type'        => ValueProperty\ImageFieldValue::$type,
				'description' => __( 'Image field value.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function ( $source, array $args, AppContext $context ) {
					if ( ! self::is_field_and_entry( $source, $context ) ) {
						return null;
					}

					$image_data = array_pad( explode( '|:|', $context->gfEntry->entry[ $source->id ] ), 5, false );

					$values_to_return = [
						'altText'     => $image_data[4] ?: null,
						'caption'     => $image_data[2] ?: null,
						'description' => $image_data[3] ?: null,
						'title'       => $image_data[1] ?: null,
						'url'         => $image_data[0] ?: null,
					];

					/**
					 * Strip out the meta from the entry value.
					 *
					 * @see GF_Field_PostImage::get_extra_entry_metadata().
					 */
					$file_values = [];

					// Draft entries don't upload files.
					if ( ! $context->gfEntry->isDraft ) {
						$entry                = $context->gfEntry->entry;
						$entry[ $source->id ] = $image_data[0];
						$file_values          = self::get_file_upload_extra_entry_metadata( $source, $entry, $context->gfForm->form );
						// Add the file values if they exist.
						$values_to_return = array_merge( $file_values[ $image_data[0] ] ?? [], $values_to_return );
					}

					return $values_to_return;
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
				'type'        => ValueProperty\TimeFieldValue::$type,
				'description' => __( 'Time field value.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function ( $source, array $args, AppContext $context ) {
					if ( ! self::is_field_and_entry( $source, $context ) ) {
						return null;
					}

					$display_value = $context->gfEntry->entry[ $source->id ];

					$parts_by_colon = explode( ':', $display_value );
					$parts_by_space = explode( ' ', $parts_by_colon[1] ?? '' );

					$hours   = $parts_by_colon[0] ?? '';
					$minutes = $parts_by_space[0] ?? '';
					$am_pm   = $parts_by_space[1] ?? '';

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
				'description' => __( 'An array of field values.', 'wp-graphql-gravity-forms' ),
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
	protected static function is_field_and_entry( $source, AppContext $context ) : bool {
		return $source instanceof GF_Field
			&& isset( $context->gfEntry )
			&& isset( $context->gfEntry->entry );
	}

	/**
	 * Gets the file data meta saved to the entry.
	 *
	 * Shim GF_Field_FileUpload::get_extra_entry_metadata().
	 *
	 * @param GF_Field_FileUpload $field .
	 * @param array               $entry .
	 * @param array               $form .
	 */
	protected static function get_file_upload_extra_entry_metadata( GF_Field_FileUpload $field, array $entry, array $form ) : array {
		$file_values = $entry[ $field->id ] ?? null;

		// Bail if no files.
		if ( empty( $file_values ) ) {
			return [];
		}

		// Corerce files into an array.
		if ( $field->multipleFiles ) {
			$file_values = json_decode( $file_values, true );
		} else {
			$file_values = [ $file_values ];
		}

		$info = [];

		// Generate the file info for all files.
		foreach ( $file_values as $file_value ) {
			// Backcompat with v2.5x.
			if ( version_compare( GFCommon::$version, '2.6.0', '<' ) ) {
				$time                    = current_time( 'mysql' );
				$y                       = substr( $time, 0, 4 );
				$m                       = substr( $time, 5, 2 );
				$default_target_root     = GFFormsModel::get_upload_path( $form['id'] ) . "/$y/$m/";
				$default_target_root_url = GFFormsModel::get_upload_url( $form['id'] ) . "/$y/$m/";

				$filename = explode( '/', $file_value );

				$info[ $file_value ] = [
					'url'      => $file_value,
					'basePath' => $default_target_root,
					'baseUrl'  => $default_target_root_url,
					'filename' => end( $filename ),
					'hash'     => wp_hash( $form['id'] ),
				];
				continue;
			}

			$stored_path_info = gform_get_meta( $entry['id'], $field::get_file_upload_path_meta_key_hash( $file_value ) );

			if ( empty( $stored_path_info ) ) {
				// Use the filtered path to get the actual file path.
				$upload_root_info = $field::get_upload_root_info( rgar( $form, 'id' ) );

				// Default upload path to fall back to.
				$default_upload_root_info = $field::get_default_upload_roots( rgar( $form, 'id' ) );

				$url              = rgar( $upload_root_info, 'url', $default_upload_root_info['url'] );
				$path             = rgar( $upload_root_info, 'path', $default_upload_root_info['path'] );
				$stored_path_info = [
					'path'      => $path,
					'url'       => $url,
					'file_name' => wp_basename( $file_value ),
				];
			}

			$info[ $file_value ] = [
				'basePath' => $stored_path_info['path'] ?? null,
				'baseUrl'  => $stored_path_info['url'] ?? null,
				'filename' => $stored_path_info['file_name'] ?? null,
				'hash'     => $field::get_file_upload_path_meta_key_hash( $file_value ),
				'url'      => $file_value,
			];
		}

		return $info;
	}
}
