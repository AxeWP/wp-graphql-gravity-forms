<?php
/**
 * Interface - Gravity Forms Entry.
 *
 * @package WPGraphQL\GF\Type\Interface
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Type\WPInterface;

use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQLRelay\Relay;
use WPGraphQL\AppContext;
use WPGraphQL\GF\Connection\FormFieldsConnection;
use WPGraphQL\GF\Data\Connection\FormFieldsConnectionResolver;
use WPGraphQL\GF\Data\Loader\DraftEntriesLoader;
use WPGraphQL\GF\Data\Loader\EntriesLoader;
use WPGraphQL\GF\Interfaces\Field;
use WPGraphQL\GF\Interfaces\Registrable;
use WPGraphQL\GF\Interfaces\Type;
use WPGraphQL\GF\Interfaces\TypeWithFields;
use WPGraphQL\GF\Model\Form;
use WPGraphQL\GF\Type\Enum\EntryIdTypeEnum;
use WPGraphQL\GF\Utils\GFUtils;
use WPGraphQL\GF\Utils\Utils;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - FormEntry
 */
class Entry implements Field, Registrable, Type, TypeWithFields {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfEntry';

	/**
	 * Field registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $field_name = 'gfEntry';

	/**
	 * Whether the type should be loaded eagerly by WPGraphQL. Defaults to false.
	 *
	 * Eager load should only be necessary for types that are not referenced directly (e.g. in Unions, Interfaces ).
	 *
	 * @var boolean
	 */
	public static bool $should_load_eagerly = false;

	/**
	 * Register Object type to GraphQL schema.
	 *
	 * @param TypeRegistry $type_registry Instance of the WPGraphQL TypeRegistry.
	 */
	public static function register( TypeRegistry $type_registry = null ) : void {
		// Bail early if no type registry.
		if ( null === $type_registry ) {
			return;
		}

		register_graphql_interface_type(
			static::$type,
			[
				'description'     => self::get_description(),
				'connections'     => [
					'formFields' => [
						'toType'         => FormField::$type,
						'connectionArgs' => FormFieldsConnection::get_filtered_connection_args(),
						'resolve'        => static function( $source, array $args, AppContext $context, ResolveInfo $info ) {
							$context->gfEntry = $source;

							if ( empty( $context->gfForm ) ) {
								$context->gfForm = new Form( GFUtils::get_form( $source->formDatabaseId, false ) );
							}

							if ( empty( $context->gfForm->formFields ) ) {
								return null;
							}

							return FormFieldsConnectionResolver::resolve( $context->gfForm->formFields, $args, $context, $info );
						},
					],
				],
				'interfaces'      => [ 'Node', NodeWithForm::$type ],
				'fields'          => self::get_fields(),
				'resolveType'     => function( $value ) use ( $type_registry ) {
					$possible_types = Utils::get_registered_entry_types();

					$id_parts = Relay::fromGlobalId( $value->id );

					if ( empty( $id_parts['type'] ) ) {
						throw new UserError( __( 'The entry type cannot be resolved.', 'wp-graphql-gravity-forms' ) );
					}

					if ( isset( $possible_types[ $id_parts['type'] ] ) ) {
						return $type_registry->get_type( $possible_types[ $id_parts['type'] ] );
					}

					throw new UserError(
						sprintf(
							/* translators: %s: GF entry type */
							__( 'The Gravity Forms "%s" type is not supported by the schema.', 'wp-graphql-gravity-forms' ),
							$id_parts['type']
						)
					);
				},
				'eagerlyLoadType' => static::$should_load_eagerly,
			]
		);

		self::register_field();
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Gravity Forms entry interface.', 'wp-graphql-gravity-forms' );
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

					return $context->get_loader( 'user' )->load_deferred( $source->createdByDatabaseId );
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
			'ip'                  => [
				'type'        => 'String',
				'description' => __( 'Client IP of user who submitted the form.', 'wp-graphql-gravity-forms' ),
			],
			'isDraft'             => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the entry is a draft.', 'wp-graphql-gravity-forms' ),
			],
			'isSubmitted'         => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the entry has been submitted.', 'wp-graphql-gravity-forms' ),
			],
			'sourceUrl'           => [
				'type'        => 'String',
				'description' => __( 'Source URL of page that contained the form when it was submitted.', 'wp-graphql-gravity-forms' ),
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
	 * {@inheritDoc}
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
						'description' => __( 'Type of unique identifier to fetch a content node by. Default is Global ID.', 'wp-graphql-gravity-forms' ),
					],
				],
				'resolve'     => function( $root, array $args, AppContext $context ) {
					$id_type = $args['idType'] ?? 'global_id';

					if ( 'global_id' === $id_type ) {
						$id_parts = Relay::fromGlobalId( $args['id'] );

						if ( ! is_array( $id_parts ) || empty( $id_parts['id'] ) || empty( $id_parts['type'] ) ) {
							throw new UserError( __( 'A valid global ID must be provided.', 'wp-graphql-gravity-forms' ) );
						}

						$loader = $id_parts['type'];
						$id     = sanitize_text_field( $id_parts['id'] );
					} else {
						$loader = 'database_id' === $id_type ? EntriesLoader::$name : DraftEntriesLoader::$name;
						$id     = sanitize_text_field( $args['id'] );
					}

					return $context->get_loader( $loader )->load_deferred( $id );
				},
			]
		);
	}
}
