<?php
/**
 * GraphQL Object Type - Gravity Forms Form Entry
 *
 * @see https://docs.gravityforms.com/entry-object/
 *
 * @package WPGraphQL\GF\Type\WPObject\Entry
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Type\WPObject\Entry;

use GraphQL\Error\UserError;
use GraphQLRelay\Relay;
use WPGraphQL\AppContext;
use WPGraphQL\GF\Data\Factory;
use WPGraphQL\GF\Interfaces\Field;
use WPGraphQL\GF\Interfaces\TypeWithInterfaces;
use WPGraphQL\GF\Type\Enum\DraftEntryIdTypeEnum;
use WPGraphQL\GF\Type\WPInterface\Entry;
use WPGraphQL\GF\Type\WPObject\AbstractObject;

/**
 * Class - Draft
 */
class DraftEntry extends AbstractObject implements TypeWithInterfaces, Field {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfDraftEntry';

	/**
	 * Field registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $field_name = 'gfDraftEntry';

	/**
	 * {@inheritDoc}
	 */
	public static function register(): void {
		parent::register();

		self::register_field();
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_type_config(): array {
		$config = parent::get_type_config();

		$config['interfaces'] = self::get_interfaces();

		return $config;
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'A Gravity Forms draft entry.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'resumeToken' => [
				'type'        => 'String',
				'description' => __( 'The resume token. Only applies to draft entries.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_interfaces(): array {
		return [ Entry::$type ];
	}

	/**
	 * Register entry query.
	 */
	public static function register_field(): void {
		register_graphql_field(
			'RootQuery',
			self::$field_name,
			[
				'description' => __( 'Get a Gravity Forms entry.', 'wp-graphql-gravity-forms' ),
				'type'        => self::$type,
				'args'        => [
					'id'     => [
						'type'        => [ 'non_null' => 'ID' ],
						'description' => __( 'Unique identifier for the object.', 'wp-graphql-gravity-forms' ),
					],
					'idType' => [
						'type'        => DraftEntryIdTypeEnum::$type,
						'description' => __( 'Type of unique identifier to fetch a content node by. Default is Global ID.', 'wp-graphql-gravity-forms' ),
					],
				],
				'resolve'     => static function ( $root, array $args, AppContext $context ) {
					$idType = $args['idType'] ?? 'global_id';

					if ( 'global_id' === $idType ) {
						$id_parts = Relay::fromGlobalId( $args['id'] );

						if ( ! is_array( $id_parts ) || empty( $id_parts['id'] ) || empty( $id_parts['type'] ) ) {
							throw new UserError( __( 'A valid global ID must be provided.', 'wp-graphql-gravity-forms' ) );
						}

						$id = sanitize_text_field( $id_parts['id'] );
					} else {
						$id = sanitize_text_field( $args['id'] );
					}

					return Factory::resolve_draft_entry( $id, $context );
				},
			]
		);
	}
}
