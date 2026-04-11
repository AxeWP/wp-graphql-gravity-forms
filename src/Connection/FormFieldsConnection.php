<?php
/**
 * Connection - EntriesConnection
 *
 * Registers all connections TO Gravity Forms Field.
 *
 * @package WPGraphQL\GF\Connection
 * @since 0.10.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Connection;

use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQL\GF\Data\Factory;
use WPGraphQL\GF\Data\Loader\FormsLoader;
use WPGraphQL\GF\Mutation\SubmitForm;
use WPGraphQL\GF\Type\Enum\FormFieldTypeEnum;
use WPGraphQL\GF\Type\WPInterface\FormField;

/**
 * Class - FormFieldsConnection
 */
class FormFieldsConnection extends AbstractConnection {
	/**
	 * {@inheritDoc}
	 */
	public static function register(): void {
		// SubmitGfFormPayload to FormFields.
		register_graphql_connection(
			[
				'fromType'      => SubmitForm::$name . 'Payload',
				'toType'        => FormField::$type,
				'fromFieldName' => 'targetPageFormFields',
				'resolve'       => static function ( $source, array $args, AppContext $context, ResolveInfo $info ) {
					// If the source doesn't have a targetPageNumber, we can't resolve the connection.
					if ( empty( $source['targetPageNumber'] ) ) {
						return null;
					}

					// If the form isn't stored in the context, we need to fetch it.
					$form = $context->get( 'gf', 'gfForm' );
					if ( empty( $form ) && ! empty( $source['form_id'] ) ) {
						/** @var \WPGraphQL\GF\Model\Form $form */
						$form = $context->get_loader( FormsLoader::$name )->load( (int) $source['form_id'] );

						if ( null === $form ) {
							return null;
						}

						// Store it in the context for easy access.
						$context->set( 'gf', 'gfForm', $form );
					}

					if ( empty( $form->formFields ) ) {
						return null;
					}

					// Set the Args for the connection resolver.
					$args['where']['pageNumber'] = $source['targetPageNumber'];

					return Factory::resolve_form_fields_connection( $form, $args, $context, $info );
				},
			]
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_connection_args(): array {
		return [
			'ids'         => [
				'type'        => [ 'list_of' => 'ID' ],
				'description' => static fn () => __( 'Array of form field IDs to return.', 'wp-graphql-gravity-forms' ),
			],
			'adminLabels' => [
				'type'        => [ 'list_of' => 'String' ],
				'description' => static fn () => __( 'Array of form field adminLabels to return.', 'wp-graphql-gravity-forms' ),
			],
			'fieldTypes'  => [
				'type'        => [ 'list_of' => FormFieldTypeEnum::$type ],
				'description' => static fn () => __( 'Array of Gravity Forms Field types to return.', 'wp-graphql-gravity-forms' ),
			],
			'pageNumber'  => [
				'type'        => 'Int',
				'description' => static fn () => __( 'The form page number to return.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
