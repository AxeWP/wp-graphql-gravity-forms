<?php
/**
 * GraphQL Object Type - Gravity Forms Form
 *
 * @see https://docs.gravityforms.com/form-object/
 *
 * @package WPGraphQL\GF\Type\WPObject\Form
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Type\WPObject\Form;

use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQL\GF\Connection\EntriesConnection;
use WPGraphQL\GF\Connection\FormFieldsConnection;
use WPGraphQL\GF\Data\Connection\EntriesConnectionResolver;
use WPGraphQL\GF\Data\Connection\FormFieldsConnectionResolver;
use WPGraphQL\GF\Data\Factory;
use WPGraphQL\GF\Interfaces\Field;
use WPGraphQL\GF\Interfaces\TypeWithConnections;
use WPGraphQL\GF\Interfaces\TypeWithInterfaces;
use WPGraphQL\GF\Type\Enum;
use WPGraphQL\GF\Type\WPInterface\Entry;
use WPGraphQL\GF\Type\WPInterface\FormField;
use WPGraphQL\GF\Type\WPObject\AbstractObject;
use WPGraphQL\GF\Type\WPObject\Button;
use WPGraphQL\GF\Utils\Utils;

/**
 * Class - Form
 */
class Form extends AbstractObject implements TypeWithConnections, TypeWithInterfaces, Field {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfForm';

	/**
	 * Field registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $field_name = 'gfForm';

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

		$config['connections'] = static::get_connections();
		$config['interfaces']  = static::get_interfaces();

		return $config;
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_connections(): array {
		return [
			'entries'    => [
				'toType'           => Entry::$type,
				'connectionArgs'   => EntriesConnection::get_filtered_connection_args( [ 'status', 'dateFilters', 'fieldFilters', 'fieldFiltersMode', 'orderby' ] ),
				'connectionFields' => [
					'count' => [
						'type'        => 'Int',
						'description' => __( 'The number of (filtered) entries submitted to the form.', 'wp-graphql-gravity-forms' ),
						'resolve'     => static function ( $root ) {
							// Bail early if no entries.
							if ( empty( $root['edges'][0]['connection'] ) ) {
								return 0;
							}

							/**
							 * The current entry query.
							 *
							 * @todo get the connection resolver directly, once supported by WPGraphQL AppContext::get_current_connection();
							 *
							 * @var \GF_Query
							 */
							$connection = $root['edges'][0]['connection'] instanceof EntriesConnectionResolver ? $root['edges'][0]['connection']->get_query() : null;

							// Needed to resolve the counts.
							$connection->get_ids();

							return $connection->total_found;
						},
					],
				],
				'resolve'          => static function ( $source, array $args, AppContext $context, ResolveInfo $info ) {
					$context->gfForm = $source;

					$args['where']['formIds'] = $source->databaseId;

					return Factory::resolve_entries_connection( $source, $args, $context, $info );
				},
			],
			'formFields' => [
				'toType'         => FormField::$type,
				'connectionArgs' => FormFieldsConnection::get_filtered_connection_args(),
				'resolve'        => static function ( $source, array $args, AppContext $context, ResolveInfo $info ) {
					$context->gfForm = $source;

					if ( empty( $source->formFields ) ) {
						return null;
					}

					return FormFieldsConnectionResolver::resolve( $source->formFields, $args, $context, $info );
				},
			],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Gravity Forms form.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'button'                       => [
				'type'              => FormSubmitButton::$type,
				'description'       => __( 'Contains the form button settings such as the button text or image button source.', 'wp-graphql-gravity-forms' ),
				'deprecationReason' => __( 'Use `submitButton` field instead', 'wp-graphql-gravity-forms' ),
				'resolve'           => static fn ( $source ) => $source->submitButton,
			],
			'confirmations'                => [
				'type'        => [ 'list_of' => FormConfirmation::$type ],
				'description' => __( 'Contains the form confirmation settings such as confirmation text or redirect URL.', 'wp-graphql-gravity-forms' ),
			],
			'cssClass'                     => [
				'type'        => 'String',
				'description' => __( 'String containing the custom CSS classes to be added to the <form> tag.', 'wp-graphql-gravity-forms' ),
			],
			'customRequiredIndicator'      => [
				'type'        => 'String',
				'description' => __( 'The custom text to use to indicate a field is required.', 'wp-graphql-gravity-forms' ),
			],
			'dateCreated'                  => [
				'type'        => 'String',
				'description' => __( 'The date and time that the entry was created in local time.', 'wp-graphql-gravity-forms' ),
			],
			'dateCreatedGmt'               => [
				'type'        => 'String',
				'description' => __( 'The date and time that the entry was created in GMT.', 'wp-graphql-gravity-forms' ),
			],
			'description'                  => [
				'type'        => 'String',
				'description' => __( 'Form description.', 'wp-graphql-gravity-forms' ),
			],
			'descriptionPlacement'         => [
				'type'        => Enum\FormDescriptionPlacementEnum::$type,
				'description' => __( 'Determines if the field description is displayed above the field input (i.e. immediately after the field label) or below the field input.', 'wp-graphql-gravity-forms' ),
			],
			'entryLimits'                  => [
				'type'        => FormEntryLimits::$type,
				'description' => __( 'The entry limit settings.', 'wp-graphql-gravity-forms' ),
			],
			'hasConditionalLogicAnimation' => [
				'type'        => 'Boolean',
				'description' => __( 'When enabled, conditional logic hide/show operation will be performed with a jQuery slide animation. Only applicable to forms with conditional logic.', 'wp-graphql-gravity-forms' ),
			],
			'hasHoneypot'                  => [
				'type'        => 'Boolean',
				'description' => __( 'Specifies if the form has the Honeypot spam-protection feature.', 'wp-graphql-gravity-forms' ),
			],
			'firstPageCssClass'            => [
				'type'        => 'String',
				'description' => __( 'CSS class for the first page.', 'wp-graphql-gravity-forms' ),
			],
			'formId'                       => [
				'type'              => 'Int',
				'description'       => __( 'Form ID.', 'wp-graphql-gravity-forms' ),
				'deprecationReason' => __( 'Deprecated in favor of the databaseId field.', 'wp-graphql-gravity-forms' ),
				'resolve'           => static fn ( $source ) => $source->databaseId,
			],
			'hasValidationSummary'         => [
				'type'        => 'Boolean',
				'description' => __( 'If enabled, will show a summary that lists form validation errors at the top of the form when a user attempts a failed submission.', 'wp-graphql-gravity-forms' ),
			],
			'isActive'                     => [
				'type'        => 'Boolean',
				'description' => __( 'Determines whether the form is active.', 'wp-graphql-gravity-forms' ),
			],
			'isTrash'                      => [
				'type'        => 'Boolean',
				'description' => __( 'Determines whether the form is in the trash.', 'wp-graphql-gravity-forms' ),
			],
			'labelPlacement'               => [
				'type'        => Enum\FormLabelPlacementEnum::$type,
				'description' => __( 'Determines where the field labels should be placed in relation to the field.', 'wp-graphql-gravity-forms' ),
			],
			'lastPageButton'               => [
				'type'              => Button\FormLastPageButton::$type,
				'description'       => __( 'Last page button data.', 'wp-graphql-gravity-forms' ),
				'deprecationReason' => __( 'Use `pagination.lastPageButton` instead', 'wp-graphql-gravity-forms' ),
				'resolve'           => static fn ( $source ) => ! empty( $source->pagination ) ? $source->pagination['lastPageButton'] : null,
			],
			'login'                        => [
				'type'        => FormLogin::$type,
				'description' => __( 'Login requirements data.', 'wp-graphql-gravity-forms' ),
			],
			'markupVersion'                => [
				'type'        => 'Int',
				'description' => __( 'The Gravity Forms markup version.', 'wp-graphql-gravity-forms' ),
			],
			'notifications'                => [
				'type'        => [ 'list_of' => FormNotification::$type ],
				'description' => __( 'The properties for all the email notifications which exist for a form.', 'wp-graphql-gravity-forms' ),
			],
			'nextFieldId'                  => [
				'type'        => 'Int',
				'description' => __( 'The ID to assign to the next field that is added to the form.', 'wp-graphql-gravity-forms' ),
			],
			'pagination'                   => [
				'type'        => FormPagination::$type,
				'description' => __( 'Pagination data.', 'wp-graphql-gravity-forms' ),
			],
			'personalData'                 => [
				'type'        => FormPersonalData::$type,
				'description' => __( 'Personal data settings.', 'wp-graphql-gravity-forms' ),
			],
			'postCreation'                 => [
				'type'        => FormPostCreation::$type,
				'description' => __( 'Post creation data.', 'wp-graphql-gravity-forms' ),
			],
			'requiredIndicator'            => [
				'type'        => Enum\FormFieldRequiredIndicatorEnum::$type,
				'description' => __( 'Type of indicator to use when field is required.', 'wp-graphql-gravity-forms' ),
			],
			'saveAndContinue'              => [
				'type'        => FormSaveAndContinue::$type,
				'description' => __( '\"Save and Continue\" data.', 'wp-graphql-gravity-forms' ),
			],
			'scheduling'                   => [
				'type'        => FormSchedule::$type,
				'description' => __( 'Form scheduling data.', 'wp-graphql-gravity-forms' ),
			],
			'subLabelPlacement'            => [
				'type'        => Enum\FormSubLabelPlacementEnum::$type,
				'description' => __( 'How sub-labels are aligned.', 'wp-graphql-gravity-forms' ),
			],
			'submitButton'                 => [
				'type'        => FormSubmitButton::$type,
				'description' => __( 'Contains the form button settings such as the button text or image button source.', 'wp-graphql-gravity-forms' ),
			],
			'title'                        => [
				'type'        => 'String',
				'description' => __( 'Form title.', 'wp-graphql-gravity-forms' ),
			],
			'version'                      => [
				'type'        => 'String',
				'description' => __( 'The version of Gravity Forms used to create this form.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_interfaces(): array {
		return [ 'Node', 'DatabaseIdentifier' ];
	}

	/**
	 * Register form query.
	 */
	public static function register_field(): void {
		register_graphql_field(
			'RootQuery',
			self::$field_name,
			[
				'description' => __( 'Get a Gravity Forms form.', 'wp-graphql-gravity-forms' ),
				'type'        => self::$type,
				'args'        => [
					'id'     => [
						'type'        => [ 'non_null' => 'ID' ],
						'description' => __( 'Unique identifier for the object.', 'wp-graphql-gravity-forms' ),
					],
					'idType' => [
						'type'        => Enum\FormIdTypeEnum::$type,
						'description' => __( 'Type of unique identifier to fetch a content node by. Default is Global ID.', 'wp-graphql-gravity-forms' ),
					],
				],
				'resolve'     => static function ( $source, array $args, AppContext $context ) {
					$id = Utils::get_form_id_from_id( $args['id'] );

					return Factory::resolve_form( $id, $context );
				},
			]
		);
	}
}
