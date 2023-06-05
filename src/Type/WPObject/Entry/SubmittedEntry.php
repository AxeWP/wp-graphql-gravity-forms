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

use WPGraphQL\AppContext;
use WPGraphQL\GF\Data\Factory;
use WPGraphQL\GF\Interfaces\Field;
use WPGraphQL\GF\Interfaces\TypeWithInterfaces;
use WPGraphQL\GF\Type\Enum\EntryStatusEnum;
use WPGraphQL\GF\Type\Enum\SubmittedEntryIdTypeEnum;
use WPGraphQL\GF\Type\WPInterface\Entry;
use WPGraphQL\GF\Type\WPObject\AbstractObject;
use WPGraphQL\GF\Utils\Utils;

/**
 * Class - Submitted
 */
class SubmittedEntry extends AbstractObject implements TypeWithInterfaces, Field {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfSubmittedEntry';

	/**
	 * Field registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $field_name = 'gfSubmittedEntry';

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
		return __( 'A Gravity Forms submitted entry.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'entryId'        => [
				'type'              => 'Int',
				'description'       => __( 'The entry ID. Returns null for draft entries.', 'wp-graphql-gravity-forms' ),
				'deprecationReason' => __( 'Deprecated in favor of the databaseId field.', 'wp-graphql-gravity-forms' ),
				'resolve'           => static fn ( $source ) => $source->databaseId ?? null,
			],
			'isStarred'      => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates if the entry has been starred (i.e marked with a star).', 'wp-graphql-gravity-forms' ),
			],
			'isRead'         => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the entry has been read.', 'wp-graphql-gravity-forms' ),
			],
			'post'           => [
				'type'        => 'Post',
				'description' => __( 'For forms with Post fields, this is the post object that was created.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source, array $args, AppContext $context ) => ! empty( $source->postDatabaseId ) ? $context->get_loader( 'post' )->load( $source->postDatabaseId ) : null,
			],
			'postDatabaseId' => [
				'type'        => 'Int',
				'description' => __( 'For forms with Post fields, this property contains the Id of the Post that was created.', 'wp-graphql-gravity-forms' ),
			],
			'status'         => [
				'type'        => EntryStatusEnum::$type,
				'description' => __( 'The current status of the entry.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_interfaces(): array {
		return [ 'DatabaseIdentifier', Entry::$type ];
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
						'type'        => SubmittedEntryIdTypeEnum::$type,
						'description' => __( 'Type of unique identifier to fetch a content node by. Default is Global ID.', 'wp-graphql-gravity-forms' ),
					],
				],
				'resolve'     => static function ( $source, array $args, AppContext $context ) {
					$id = Utils::get_entry_id_from_id( $args['id'] );

					return Factory::resolve_entry( (int) $id, $context );
				},
			]
		);
	}
}
