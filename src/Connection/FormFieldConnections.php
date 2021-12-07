<?php
/**
 * Connection - EntryConnections
 *
 * Registers all connections TO Gravity Forms Field.
 *
 * @package WPGraphQL\GF\Connection
 * @since 0.8.0
 */

namespace WPGraphQL\GF\Connection;

use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQL\GF\Data\Connection\FormFieldsConnectionResolver;
use WPGraphQL\GF\Model\Form as FormModel;
use WPGraphQL\GF\Type\WPObject\Entry\Entry;
use WPGraphQL\GF\Type\WPObject\Form\Form;
use WPGraphQL\GF\Type\WPInterface\FormField;
use WPGraphQL\GF\Type\Enum\FormFieldsEnum;
use WPGraphQL\GF\Utils\GFUtils;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - EntryConnections
 */
class FormFieldConnections extends AbstractConnection {
	/**
	 * {@inheritDoc}
	 */
	public static function register( TypeRegistry $type_registry = null ) : void {
		// Form to Field.
		register_graphql_connection(
			self::prepare_config(
				[
					'fromType'       => Form::$type,
					'toType'         => FormField::$type,
					'fromFieldName'  => 'formFields',
					'connectionArgs' => self::get_connection_args(),
					'resolve'        => static function( $source, array $args, AppContext $context, ResolveInfo $info ) {
						$context->gfForm = $source;

						if ( empty( $source->formFields ) ) {
							return null;
						}

						$fields = static::filter_form_fields_by_connection_args( $source->formFields, $args );

						return FormFieldsConnectionResolver::resolve( $fields, $args, $context, $info );
					},
				]
			)
		);

		// Entry to Field.
		register_graphql_connection(
			self::prepare_config(
				[
					'fromType'       => Entry::$type,
					'toType'         => FormField::$type,
					'fromFieldName'  => 'formFields',
					'connectionArgs' => self::get_connection_args(),
					'resolve'        => static function( $source, array $args, AppContext $context, ResolveInfo $info ) {
						if ( empty( $context->gfForm ) ) {
							$context->gfForm = new FormModel( GFUtils::get_form( $source->formId, false ) );
						}

						$context->gfEntry = $source;

						$fields = self::filter_form_fields_by_connection_args( $context->gfForm->formFields, $args );

						return FormFieldsConnectionResolver::resolve( $fields, $args, $context, $info );
					},
				]
			),
		);
	}

	/**
	 * Gets custom connection configuration arguments, such as the resolver, edgeFields, connectionArgs, etc.
	 *
	 * @return array
	 */
	public static function get_connection_args() : array {
		return [
			'ids'         => [
				'type'        => [ 'list_of' => 'ID' ],
				'description' => __( 'Array of form field IDs to return.', 'wp-graphql-gravity-forms' ),
			],
			'adminLabels' => [
				'type'        => [ 'list_of' => 'String' ],
				'description' => __( 'Array of form field adminLabels to return.', 'wp-graphql-gravity-forms' ),
			],
			'types'       => [
				'type'        => [ 'list_of' => FormFieldsEnum::$type ],
				'description' => __( 'Array of Gravity Forms Field types to return.', 'wp-graphql-gravity-forms' ),
			],
			'page'        => [
				'type'        => 'Int',
				'description' => __( 'The form page number to return', 'wp-graphql-gravity-forms' ),
			],
		];
	}


	/**
	 * Filters the form fields by the connection's where args.
	 *
	 * @param array $fields .
	 * @param array $args .
	 * @return array
	 */
	private static function filter_form_fields_by_connection_args( $fields, $args ) : array {
		if ( isset( $args['where']['ids'] ) ) {
			if ( ! is_array( $args['where']['ids'] ) ) {
				$args['where']['ids'] = [ $args['where']['ids'] ];
			}
			$ids = array_map( 'absint', $args['where']['ids'] );

			$fields = array_filter( $fields, fn( $field ) => in_array( (int) $field['id'], $ids, true ) );
		}

		if ( isset( $args['where']['adminLabels'] ) ) {
			if ( ! is_array( $args['where']['adminLabels'] ) ) {
				$args['where']['adminLabels'] = [ $args['where']['adminLabels'] ];
			}

			$admin_labels = array_map( 'sanitize_text_field', $args['where']['adminLabels'] );

			$fields = array_filter( $fields, fn( $field)  => in_array( $field['adminLabel'], $admin_labels, true ) );
		}

		if ( isset( $args['where']['types'] ) ) {
			if ( ! is_array( $args['where']['types'] ) ) {
				$args['where']['types'] = [ $args['where']['types'] ];
			}

			$fields = array_filter( $fields, fn( $field ) => in_array( $field['type'], $args['where']['types'], true ) );
		}

		if ( isset( $args['where']['page'] ) ) {
			$page = absint( $args['where']['page'] );

			$fields = array_filter( $fields, fn( $field ) => $page === (int) $field['pageNumber'] );
		}

		return $fields;
	}
}
