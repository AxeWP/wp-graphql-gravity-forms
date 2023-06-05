<?php
/**
 * Enum Type - FormsConnectionOrderByEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.12.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - FormsConnectionOrderByEnum
 */
class FormsConnectionOrderByEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormsConnectionOrderByEnum';

	// Individual elements.
	public const DATE_CREATED = 'date_created';
	public const ID           = 'id';
	public const IS_ACTIVE    = 'is_active';
	public const IS_TRASH     = 'is_trash';
	public const TITLE        = 'title';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Type of button to be displayed. Default is TEXT.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			'DATE_CREATED' => [
				'description' => __( 'The date the form was created.', 'wp-graphql-gravity-forms' ),
				'value'       => self::DATE_CREATED,
			],
			'ID'           => [
				'description' => __( 'The database ID of the form.', 'wp-graphql-gravity-forms' ),
				'value'       => self::ID,
			],
			'IS_ACTIVE'    => [
				'description' => __( 'The Form\'s active status.', 'wp-graphql-gravity-forms' ),
				'value'       => self::IS_ACTIVE,
			],
			'IS_TRASH'     => [
				'description' => __( 'The form\'s trash status .', 'wp-graphql-gravity-forms' ),
				'value'       => self::IS_TRASH,
			],
			'TITLE'        => [
				'description' => __( 'The title of the form.', 'wp-graphql-gravity-forms' ),
				'value'       => self::TITLE,
			],
		];
	}
}
