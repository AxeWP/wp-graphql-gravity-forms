<?php
/**
 * Interface - Form Field personal data.
 *
 * @package WPGraphQL\GF\Type\Interface
 * @since   0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface;

use GF_Field;
use WPGraphQL\AppContext;
use WPGraphQL\GF\Type\WPObject\FormField\FormFieldDataPolicy;

/**
 * Class - FieldWithPersonalData
 */
class FieldWithPersonalData extends AbstractInterface {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithPersonalData';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'The form field-specifc policies for exporting and erasing personal data.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'personalData' => [
				'type'        => FormFieldDataPolicy::$type,
				'description' => __( 'The form field-specifc policies for exporting and erasing personal data.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( GF_Field $source, array $args, AppContext $context ) {
					if ( empty( $context->gfForm->personalData['dataPolicies']['identificationFieldDatabaseId'] ) ) {
						return null;
					}

					return [
						'id'                    => $source->id ?? null,
						'isIdentificationField' => isset( $context->gfForm->personalData['dataPolicies']['identificationFieldDatabaseId'] ) && $context->gfForm->personalData['dataPolicies']['identificationFieldDatabaseId'] === $source->id,
						'shouldErase'           => ! empty( $source->personalDataErase ),
						'shouldExport'          => ! empty( $source->personalDataExport ),
					];
				},
			],
		];
	}
}
