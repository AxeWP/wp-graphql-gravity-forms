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
use GraphQL\Type\Definition\ResolveInfo;
use GraphQLRelay\Relay;
use WPGraphQL\AppContext;
use WPGraphQL\Data\DataSource;
use WPGraphQL\GF\Data\Factory;
use WPGraphQL\GF\Interfaces\Field;
use WPGraphQL\GF\Type\Enum\SubmittedEntryIdTypeEnum;
use WPGraphQL\GF\Type\Enum\EntryStatusEnum;
use WPGraphQL\GF\Type\WPInterface\Entry;
use WPGraphQL\GF\Type\WPObject\AbstractObject;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - Submitted
 */
class SubmittedEntry extends AbstractObject implements Field {
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
	public static function register( TypeRegistry $type_registry = null ) : void {
		register_graphql_object_type(
			static::$type,
			[
				'description'     => static::get_description(),
				'eagerlyLoadType' => static::$should_load_eagerly,
				'fields'          => static::get_fields(),
				'interfaces'      => [ 'DatabaseIdentifier', Entry::$type ],
			]
		);

		self::register_field();
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'A Gravity Forms submitted entry.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'entryId'        => [
				'type'              => 'Int',
				'description'       => __( 'The entry ID. Returns null for draft entries.', 'wp-graphql-gravity-forms' ),
				'deprecationReason' => __( 'Deprecated in favor of the databaseId field.', 'wp-graphql-gravity-forms' ),
				'resolve'           => fn( $source ) => $source->databaseId ?? null,
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
				'resolve'     => fn( $source, array $args, AppContext $context ) => ! empty( $source->postDatabaseId ) ? DataSource::resolve_post_object( $source->postDatabaseId, $context ) : null,
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
	 * Register entry query.
	 */
	public static function register_field() : void {
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
				'resolve'     => function( $root, array $args, AppContext $context, ResolveInfo $info ) {
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

					return Factory::resolve_entry( (int) $id, $context );
				},
			]
		);
	}
}
