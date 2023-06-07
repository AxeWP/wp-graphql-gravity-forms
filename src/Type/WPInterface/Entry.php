<?php
/**
 * Interface - Gravity Forms Entry.
 *
 * @package WPGraphQL\GF\Type\Interface
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Type\WPInterface;

use GFCommon;
use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQLRelay\Relay;
use WPGraphQL\AppContext;
use WPGraphQL\GF\Connection\FormFieldsConnection;
use WPGraphQL\GF\Data\Connection\FormFieldsConnectionResolver;
use WPGraphQL\GF\Data\Loader\DraftEntriesLoader;
use WPGraphQL\GF\Data\Loader\EntriesLoader;
use WPGraphQL\GF\Data\Loader\FormsLoader;
use WPGraphQL\GF\Interfaces\Field;
use WPGraphQL\GF\Interfaces\TypeWithConnections;
use WPGraphQL\GF\Interfaces\TypeWithInterfaces;
use WPGraphQL\GF\Model\Form;
use WPGraphQL\GF\Type\Enum\EntryIdTypeEnum;
use WPGraphQL\GF\Type\WPInterface\AbstractInterface;
use WPGraphQL\GF\Type\WPObject\Order\OrderSummary;
use WPGraphQL\GF\Utils\GFUtils;
use WPGraphQL\GF\Utils\Utils;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - FormEntry
 */
class Entry extends AbstractInterface implements TypeWithConnections, TypeWithInterfaces, Field {
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
	 * {@in}
	 *
	 * @param \WPGraphQL\Registry\TypeRegistry $type_registry Instance of the WPGraphQL TypeRegistry.
	 */
	public static function register( TypeRegistry $type_registry = null ): void {
		parent::register( $type_registry );

		self::register_field();
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_type_config( TypeRegistry $type_registry = null ): array {
		$config = parent::get_type_config();

		$config['connections'] = self::get_connections();
		$config['interfaces']  = self::get_interfaces();

		if ( null !== $type_registry ) {
			$config['resolveType'] = self::resolve_type( $type_registry );
		}

		return $config;
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_connections(): array {
		return [
			'formFields' => [
				'toType'         => FormField::$type,
				'connectionArgs' => FormFieldsConnection::get_filtered_connection_args(),
				'resolve'        => static function ( $source, array $args, AppContext $context, ResolveInfo $info ) {
					$context->gfEntry = $source;

					// If the form isn't stored in the context, we need to fetch it.
					if ( empty( $context->gfForm ) ) {
						$form = GFUtils::get_form( $source->formDatabaseId, false );

						// Cache the form for later use.
						$context->get_loader( FormsLoader::$name )->prime( $form['id'], $form );

						// Store it in the context for easy access.
						$context->gfForm = new Form( $form );
					}

					if ( empty( $context->gfForm->formFields ) ) {
						return null;
					}

					return FormFieldsConnectionResolver::resolve( $context->gfForm->formFields, $args, $context, $info );
				},
			],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Gravity Forms entry interface.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		$fields = [
			'createdBy'           => [
				'type'        => 'User',
				'description' => __( 'The user who created the entry.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $source, array $args, AppContext $context ) {
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

		// Order Summaries are only available in GF 2.6+.
		if ( version_compare( GFCommon::$version, '2.6.0', '>=' ) ) {
			$fields['orderSummary'] = [
				'type'        => OrderSummary::$type,
				'description' => __( 'The entry order summary. Null if the entry has no pricing fields', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $source, array $args, AppContext $context ) {
					if ( ! class_exists( 'Gravity_Forms\Gravity_Forms\Orders\Factories\GF_Order_Factory' ) ) {
						return null;
					}

					if ( empty( $context->gfForm ) ) {
						$context->gfForm = new Form( GFUtils::get_form( $source->formDatabaseId, false ) );
					}

					$order = \Gravity_Forms\Gravity_Forms\Orders\Factories\GF_Order_Factory::create_from_entry( $context->gfForm->form, $source->entry );

					/** @var array $items */
					$items = $order->get_items();
					if ( empty( $items ) ) {
						return null;
					}

					// Convert order items to array.
					$items = array_map(
						static fn ( $item) => $item->to_array(),
						$items,
					);

					$totals   = $order->get_totals();
					$currency = ! empty( $order->currency ) ? $order->currency : null;

					return [
						'currency' => $currency,
						'items'    => $items,
						'subtotal' => $totals['sub_total'] ?? null,
						'total'    => $totals['total'] ?? null,
					];
				},
			];
		}

		return $fields;
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_interfaces(): array {
		return [ 'Node', NodeWithForm::$type ];
	}

	/**
	 * Resolves the interface to the GraphQL type.
	 *
	 * @param \WPGraphQL\Registry\TypeRegistry $type_registry The WPGraphQL type registry.
	 */
	public static function resolve_type( TypeRegistry $type_registry ): callable {
		return static function ( $value ) use ( $type_registry ) {
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
		};
	}

	/**
	 * {@inheritDoc}
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
						'type'        => EntryIdTypeEnum::$type,
						'description' => __( 'Type of unique identifier to fetch a content node by. Default is Global ID.', 'wp-graphql-gravity-forms' ),
					],
				],
				'resolve'     => static function ( $root, array $args, AppContext $context ) {
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
