<?php
/**
 * GraphQL Object Type - Gravity Forms Form Entry
 *
 * @see https://docs.gravityforms.com/entry-object/
 *
 * @package WPGraphQL\GF\Type\WPObject\Entry
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Type\WPObject\Entry;

use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQLRelay\Relay;
use WPGraphQL\AppContext;
use WPGraphQL\Data\DataSource;
use WPGraphQL\GF\Data\Factory;
use WPGraphQL\GF\Interfaces\Field;
use WPGraphQL\GF\Type\WPObject\AbstractObject;
use WPGraphQL\GF\Type\Enum\EntryStatusEnum;
use WPGraphQL\GF\Type\Enum\EntryIdTypeEnum;
use WPGraphQL\GF\Type\WPInterface\NodeWithForm;
use WPGraphQL\GF\Type\WPObject\Form\Form;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - Entry
 */
class Entry extends AbstractObject implements Field {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GravityFormsEntry';

	/**
	 * Field registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $field_name = 'gravityFormsEntry';

	/**
	 * {@inheritDoc}
	 */
	public static function register( TypeRegistry $type_registry = null ) : void {
		register_graphql_object_type(
			static::$type,
			static::prepare_config(
				[
					'description'     => static::get_description(),
					'eagerlyLoadType' => static::$should_load_eagerly,
					'fields'          => static::get_fields(),
					'interfaces'      => [ 'Node', 'DatabaseIdentifier', NodeWithForm::$type ],
				]
			)
		);

		self::register_field();
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Gravity Forms entry.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'createdBy'           => [
				'type'        => 'User',
				'description' => __( 'The user who created the entry.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function( $source, array $args, AppContext $context ) {
					if ( empty( $source->createdByDatabaseId ) ) {
						return null;
					}

					return DataSource::resolve_user( $source->createdByDatabaseId, $context );
				},
			],
			'createdByDatabaseId' => [
				'type'        => 'Int',
				'description' => __( 'Database ID of the user that submitted of the form if a logged in user submitted the form.', 'wp-graphql-gravity-forms' ),
			],
			'createdById'         => [
				'type'        => 'ID',
				'description' => __( 'Global ID of the user that submitted of the form if a logged in user submitted the form.', 'wp-graphql-gravity-forms' ),
			],
			'dateCreated'         => [
				'type'        => 'String',
				'description' => __( 'The date and time that the entry was created in local time.', 'wp-graphql-gravity-forms' ),
			],
			'dateCreatedGmt'      => [
				'type'        => 'String',
				'description' => __( 'The date and time that the entry was created in GMT.', 'wp-graphql-gravity-forms' ),
			],
			'dateUpdated'         => [
				'type'        => 'String',
				'description' => __( 'The date and time that the entry was created in local time.', 'wp-graphql-gravity-forms' ),
			],
			'dateUpdatedGmt'      => [
				'type'        => 'String',
				'description' => __( 'The date and time that the entry was updated in GMT.', 'wp-graphql-gravity-forms' ),
			],
			'entryId'             => [
				'type'              => 'Int',
				'description'       => __( 'The entry ID. Returns null for draft entries.', 'wp-graphql-gravity-forms' ),
				'deprecationReason' => __( 'Deprecated in favor of the databaseId field', 'wp-graphql-gravity-forms' ),
				'resolve'           => fn( $source ) => $source->databaseId ?? null,
			],
			'ip'                  => [
				'type'        => 'String',
				'description' => __( 'Client IP of user who submitted the form.', 'wp-graphql-gravity-forms' ),
			],
			'isStarred'           => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates if the entry has been starred (i.e marked with a star). 1 for entries that are starred and 0 for entries that are not starred.', 'wp-graphql-gravity-forms' ),
			],
			'isRead'              => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates if the entry has been read. 1 for entries that are read and 0 for entries that have not been read.', 'wp-graphql-gravity-forms' ),
			],
			'isDraft'             => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the entry is a draft.', 'wp-graphql-gravity-forms' ),
			],
			'post'                => [
				'type'        => 'Post',
				'description' => __( 'For forms with Post fields, this is the post object that was created.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source, array $args, AppContext $context ) => ! empty( $source->postDatabaseId ) ? DataSource::resolve_post_object( $source->postDatabaseId, $context ) : null,
			],
			'postDatabaseId'      => [
				'type'        => 'Int',
				'description' => __( 'For forms with Post fields, this property contains the Id of the Post that was created.', 'wp-graphql-gravity-forms' ),
			],
			'quizResults'         => [
				'type'        => EntryQuizResults::$type,
				'description' => __( 'The quiz results for the entry. Requires Gravity Forms Quiz to be enabled.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function( $root ) {
					return class_exists( 'GFQuiz' ) ? $root : null;
				},
			],
			'resumeToken'         => [
				'type'        => 'String',
				'description' => __( 'The resume token. Only applies to draft entries.', 'wp-graphql-gravity-forms' ),
			],
			'sourceUrl'           => [
				'type'        => 'String',
				'description' => __( 'Source URL of page that contained the form when it was submitted.', 'wp-graphql-gravity-forms' ),
			],
			'status'              => [
				'type'        => EntryStatusEnum::$type,
				'description' => __( 'The current status of the entry.', 'wp-graphql-gravity-forms' ),
			],
			'userAgent'           => [
				'type'        => 'String',
				'description' => __( 'Provides the name and version of both the browser and operating system from which the entry was submitted.', 'wp-graphql-gravity-forms' ),
			],

			/**
			 * TODO: Add support for these pricing properties that are only relevant
			 * when a Gravity Forms payment gateway add-on is being used:
			 * https://docs.gravityforms.com/entry-object/#pricing-properties
			 */
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
						'type'        => EntryIdTypeEnum::$type,
						'description' => __( 'Type of unique identifier to fetch a content node by. Default is Global ID', 'wp-graphql-gravity-forms' ),
					],
				],
				'resolve'     => function( $root, array $args, AppContext $context, ResolveInfo $info ) {
					if ( ! apply_filters( 'wp_graphql_gf_can_view_entries', current_user_can( 'gravityforms_view_entries' ) || current_user_can( 'gform_full_access' ) ) ) {
						throw new UserError( __( 'Sorry, you are not allowed to view Gravity Forms entries.', 'wp-graphql-gravity-forms' ) );
					}

					$idType = $args['idType'] ?? 'global_id';

					if ( 'global_id' === $idType ) {
						$id_parts = Relay::fromGlobalId( $args['id'] );

						if ( ! is_array( $id_parts ) || empty( $id_parts['id'] ) || empty( $id_parts['type'] ) ) {
							throw new UserError( __( 'A valid global ID must be provided.', 'wp-graphql-gravity-forms' ) );
						}

						$idType = 'GravityFormsEntry' === $id_parts['type'] ? 'database_id' : 'resume_token';

						$id = sanitize_text_field( $id_parts['id'] );
					} else {
						$id = sanitize_text_field( $args['id'] );
					}

					return 'database_id' === $idType ? Factory::resolve_entry( (int) $id, $context ) : Factory::resolve_draft_entry( $id, $context );
				},
			]
		);
	}
}
