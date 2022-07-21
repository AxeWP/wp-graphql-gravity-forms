<?php
/**
 * GraphQL Interface for a FormField with the `duplicate_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldSetting;

/**
 * Class - FieldWithDuplicate
 */
class FieldWithDuplicate extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithDuplicate';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'duplicate_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'shouldAllowDuplicates' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if the field allows duplicate submissions.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => empty( $source->noDuplicates ),
			],
		];
	}
}
