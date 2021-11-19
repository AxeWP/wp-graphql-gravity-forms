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

use GFAPI;
use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQLRelay\Relay;
use WPGraphQL\AppContext;
use WPGraphQL\GF\DataManipulators\DraftEntryDataManipulator;
use WPGraphQL\GF\DataManipulators\EntryDataManipulator;
use WPGraphQL\GF\Interfaces\Field;
use WPGraphQL\GF\Type\WPObject\AbstractObject;

use WPGraphQL\GF\Type\Enum\EntryStatusEnum;
use WPGraphQL\GF\Type\Enum\IdTypeEnum;
use WPGraphQL\GF\Utils\GFUtils;
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
					'fields'          => static::get_fields(),
					'eagerlyLoadType' => static::$should_load_eagerly,
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
			'id'          => [
				'type'        => [ 'non_null' => 'ID' ],
				'description' => __( 'Unique global ID for the object.', 'wp-graphql-gravity-forms' ),
			],
			'entryId'     => [
				'type'        => 'Int',
				'description' => __( 'The entry ID. Returns null for draft entries.', 'wp-graphql-gravity-forms' ),
			],
			'formId'      => [
				'type'        => 'Int',
				'description' => __( 'The ID of the form that was submitted to generate this entry.', 'wp-graphql-gravity-forms' ),
			],
			// @TODO: Add field to get post data.
			'postId'      => [
				'type'        => 'Int',
				'description' => __( 'For forms with Post fields, this property contains the Id of the Post that was created.', 'wp-graphql-gravity-forms' ),
			],
			// @TODO: Gravity Forms stores and returns the dateCreated and dateUpdated in UTC time.
			// Change the fields below to be in the blog's local time, and add new ones for
			// dateCreatedUTC and dateUpdatedUTC.
			'dateCreated' => [
				'type'        => 'String',
				'description' => __( 'The date and time that the entry was created, in the format "yyyy-mm-dd hh:mi:ss" (i.e. 2010-07-15 17:26:58).', 'wp-graphql-gravity-forms' ),
			],
			'dateUpdated' => [
				'type'        => 'String',
				'description' => __( 'The date and time that the entry was updated, in the format "yyyy-mm-dd hh:mi:ss" (i.e. 2010-07-15 17:26:58).', 'wp-graphql-gravity-forms' ),
			],
			'isStarred'   => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates if the entry has been starred (i.e marked with a star). 1 for entries that are starred and 0 for entries that are not starred.', 'wp-graphql-gravity-forms' ),
			],
			'isRead'      => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates if the entry has been read. 1 for entries that are read and 0 for entries that have not been read.', 'wp-graphql-gravity-forms' ),
			],
			'ip'          => [
				'type'        => 'String',
				'description' => __( 'Client IP of user who submitted the form.', 'wp-graphql-gravity-forms' ),
			],
			'sourceUrl'   => [
				'type'        => 'String',
				'description' => __( 'Source URL of page that contained the form when it was submitted.', 'wp-graphql-gravity-forms' ),
			],
			'userAgent'   => [
				'type'        => 'String',
				'description' => __( 'Provides the name and version of both the browser and operating system from which the entry was submitted.', 'wp-graphql-gravity-forms' ),
			],
			// @TODO: Add field to get user data.
			'createdById' => [
				'type'        => 'Int',
				'description' => __( 'ID of the user that submitted of the form if a logged in user submitted the form.', 'wp-graphql-gravity-forms' ),
			],
			'status'      => [
				'type'        => EntryStatusEnum::$type,
				'description' => __( 'The current status of the entry.', 'wp-graphql-gravity-forms' ),
			],
			'isDraft'     => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the entry is a draft.', 'wp-graphql-gravity-forms' ),
			],
			'quizResults' => [
				'type'        => EntryQuizResults::$type,
				'description' => __( 'The quiz results for the entry. Requires Gravity Forms Quiz to be enabled.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function( $root ) {
					return class_exists( 'GFQuiz' ) ? $root : null;
				},
			],
			'resumeToken' => [
				'type'        => 'String',
				'description' => __( 'The resume token. Only applies to draft entries.', 'wp-graphql-gravity-forms' ),
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
						'type'        => IdTypeEnum::$type,
						'description' => __( 'Type of unique identifier to fetch a content node by. Default is Global ID', 'wp-graphql-gravity-forms' ),
					],
				],
				'resolve'     => function( $root, array $args, AppContext $context, ResolveInfo $info ) {
					if ( ! apply_filters( 'wp_graphql_gf_can_view_entries', current_user_can( 'gravityforms_view_entries' ) || current_user_can( 'gform_full_access' ) ) ) {
						throw new UserError( __( 'Sorry, you are not allowed to view Gravity Forms entries.', 'wp-graphql-gravity-forms' ) );
					}

					$idType = $args['idType'] ?? 'global_id';
					/**
					 * If global id is used, get the (int) id.
					 */

					if ( 'database_id' === $idType ) {
						$id = (int) sanitize_text_field( $args['id'] );
					} else {
						$id_parts = Relay::fromGlobalId( $args['id'] );

						// Check if Global ID or resumeToken .
						if ( ! is_array( $id_parts ) || empty( $id_parts['id'] ) || empty( $id_parts['type'] ) ) {
							$id = sanitize_text_field( $args['id'] );
						} else {
							$id = (int) sanitize_text_field( $id_parts['id'] );
						}
					}

					if ( is_int( $id ) ) {
						$entry = GFAPI::get_entry( $id );

						if ( ! is_wp_error( $entry ) ) {
							return EntryDataManipulator::manipulate( $entry );
						}
					}

					// TODO: Test if draft entry actually gets returned.
					$submission = GFUtils::get_draft_submission( (string) $id );

					// @TODO: Evaluate if resume_token is actually needed.
					return DraftEntryDataManipulator::manipulate( $submission['partial_entry'], (string) $id );
				},
			]
		);
	}
}
