<?php
/**
 * GraphQL Object Type - Gravity Forms Form
 *
 * @see https://docs.gravityforms.com/form-object/
 *
 * @package WPGraphQL\GF\Type\WPObject\Form
 * @since   0.0.1
 * @since   0.4.0 Accept database Ids in query.
 */

namespace WPGraphQL\GF\Type\WPObject\Form;

use GraphQLRelay\Relay;
use GraphQL\Error\UserError;
use WPGraphQL\AppContext;
use WPGraphQL\GF\Data\Factory;
use WPGraphQL\GF\Interfaces\Field;
use WPGraphQL\GF\Type\WPObject\AbstractObject;
use WPGraphQL\GF\Type\WPObject\Button;
use WPGraphQL\GF\Type\Enum;
use WPGraphQL\GF\Type\Enum\PostFormatTypeEnum;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - Form
 */
class Form extends AbstractObject implements Field {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GravityFormsForm';

	/**
	 * Field registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $field_name = 'gravityFormsForm';

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
					'interfaces'      => [ 'Node', 'DatabaseIdentifier' ],
				]
			)
		);

		self::register_field();
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Gravity Forms form.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'button'                     => [
				'type'        => Button\Button::$type,
				'description' => __( 'Contains the form button settings such as the button text or image button source.', 'wp-graphql-gravity-forms' ),
			],
			'confirmations'              => [
				'type'        => [ 'list_of' => FormConfirmation::$type ],
				'description' => __( 'Contains the form confirmation settings such as confirmation text or redirect URL', 'wp-graphql-gravity-forms' ),
			],
			'cssClass'                   => [
				'type'        => 'String',
				'description' => __( 'String containing the custom CSS classes to be added to the <form> tag.', 'wp-graphql-gravity-forms' ),
			],
			'customRequiredIndicator'    => [
				'type'        => 'String',
				'description' => __( 'The custom text to use to indicate a field is required.', 'wp-graphql-gravity-forms' ),
			],
			'dateCreated'                => [
				'type'        => 'String',
				'description' => __( 'The date the form was created in this format: YYYY-MM-DD HH:mm:ss.', 'wp-graphql-gravity-forms' ),
			],
			'description'                => [
				'type'        => 'String',
				'description' => __( 'Form description.', 'wp-graphql-gravity-forms' ),
			],
			'descriptionPlacement'       => [
				'type'        => Enum\FormDescriptionPlacementEnum::$type,
				'description' => __( 'Determines if the field description is displayed above the field input (i.e. immediately after the field label) or below the field input.', 'wp-graphql-gravity-forms' ),
			],
			'enableAnimation'            => [
				'type'        => 'Boolean',
				'description' => __( 'When enabled, conditional logic hide/show operation will be performed with a jQuery slide animation. Only applicable to forms with conditional logic.', 'wp-graphql-gravity-forms' ),
			],
			'enableHoneypot'             => [
				'type'        => 'Boolean',
				'description' => __( 'Specifies if the form has the Honeypot spam-protection feature.', 'wp-graphql-gravity-forms' ),
			],
			'firstPageCssClass'          => [
				'type'        => 'String',
				'description' => __( 'CSS class for the first page.', 'wp-graphql-gravity-forms' ),
			],
			'formId'                     => [
				'type'              => 'Int',
				'description'       => __( 'Form ID.', 'wp-graphql-gravity-forms' ),
				'deprecationReason' => __( 'Deprecated in favor of the databaseId field', 'wp-graphql-gravity-forms' ),
				'resolve'           => fn( $source ) => $source->databaseId,
			],
			'isActive'                   => [
				'type'        => 'Boolean',
				'description' => __( 'Determines whether the form is active.', 'wp-graphql-gravity-forms' ),
			],
			'isTrash'                    => [
				'type'        => 'Boolean',
				'description' => __( 'Determines whether the form is in the trash.', 'wp-graphql-gravity-forms' ),
			],
			'labelPlacement'             => [
				'type'        => Enum\FormLabelPlacementEnum::$type,
				'description' => __( 'Determines where the field labels should be placed in relation to the field.', 'wp-graphql-gravity-forms' ),
			],
			'lastPageButton'             => [
				'type'        => Button\LastPageButton::$type,
				'description' => __( 'Last page button data.', 'wp-graphql-gravity-forms' ),
			],
			'limitEntries'               => [
				'type'        => 'Boolean',
				'description' => __( 'Specifies if this form has a limit on the number of submissions. 1 if the form limits submissions, 0 otherwise.', 'wp-graphql-gravity-forms' ),
			],
			'limitEntriesCount'          => [
				'type'        => 'Int',
				'description' => __( 'When limitEntries is set to 1, this property specifies the number of submissions allowed.', 'wp-graphql-gravity-forms' ),
			],
			'limitEntriesMessage'        => [
				'type'        => 'String',
				'description' => __( 'Message that will be displayed when the maximum number of submissions have been reached.', 'wp-graphql-gravity-forms' ),
			],
			'limitEntriesPeriod'         => [
				'type'        => Enum\FormLimitEntriesPeriodEnum::$type,
				'description' => __( 'When limitEntries is set to 1, this property specifies the time period during which submissions are allowed.', 'wp-graphql-gravity-forms' ),
			],
			'markupVersion'              => [
				'type'        => 'Int',
				'description' => __( 'The Gravity Forms markup version.', 'wp-graphql-gravity-forms' ),
			],
			'notifications'              => [
				'type'        => [ 'list_of' => FormNotification::$type ],
				'description' => __( 'The properties for all the email notifications which exist for a form.', 'wp-graphql-gravity-forms' ),
			],
			'nextFieldId'                => [
				'type'        => 'Int',
				'description' => __( 'The ID to assign to the next field that is added to the form.', 'wp-graphql-gravity-forms' ),
			],
			'pagination'                 => [
				'type'        => FormPagination::$type,
				'description' => __( 'Pagination data.', 'wp-graphql-gravity-forms' ),
			],
			'postAuthor'                 => [
				'type'        => 'Int',
				'description' => __( 'When useCurrentUserAsAuthor is set to 0, this property contains the user Id that will be used as the Post author.', 'wp-graphql-gravity-forms' ),
			],
			'postCategory'               => [
				'type'        => 'Int',
				'description' => __( 'Form forms with Post fields, but without a Post Category field, this property determines the default category that the post will be associated with when created.', 'wp-graphql-gravity-forms' ),
			],
			'postContentTemplate'        => [
				'type'        => 'String',
				'description' => __( 'Template to be used when creating the post content. Field variables (i.e. {Name:3} ) can be added to the template to insert user submitted values into the post content. Only applicable when postContentTemplateEnabled is true.', 'wp-graphql-gravity-forms' ),
			],
			'postContentTemplateEnabled' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if the post template functionality is enabled. When enabled, the post content will be created based on the template specified by postContentTemplate.', 'wp-graphql-gravity-forms' ),
			],
			'postFormat'                 => [
				'type'        => PostFormatTypeEnum::$type,
				'description' => __( 'For forms with Post fields, determines the format that the Post should be created with.', 'wp-graphql-gravity-forms' ),
			],
			'postStatus'                 => [
				'type'        => 'String',
				'description' => __( 'For forms with Post fields, determines the status that the Post should be created with.', 'wp-graphql-gravity-forms' ),
			],
			'postTitleTemplate'          => [
				'type'        => 'String',
				'description' => __( 'Template to be used when creating the post title. Field variables (i.e. {Name:3} ) can be added to the template to insert user submitted values into the post title. Only applicable when postTitleTemplateEnabled is true', 'wp-graphql-gravity-forms' ),
			],
			'postTitleTemplateEnabled'   => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if the post title template functionality is enabled. When enabled, the post title will be created based on the template specified by postTitleTemplate.', 'wp-graphql-gravity-forms' ),
			],
			'quizSettings'               => [
				'type'        => QuizSettings::$type,
				'description' => __( 'Quiz-specific settings that will affect ALL Quiz fields in the form. Requires Gravity Forms Quiz addon.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function( $source, array $args, AppContext $context ) : ?array {
					$context->gfForm = $source;
					return ! empty( $source['quizSettings'] ) ? $source['quizSettings'] : null;
				},
			],
			'requiredIndicator'          => [
				'type'        => Enum\FormFieldRequiredIndicatorEnum::$type,
				'description' => __( 'Type of indicator to use when field is required.', 'wp-graphql-gravity-forms' ),
			],
			'requireLogin'               => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the form is configured to be displayed only to logged in users.', 'wp-graphql-gravity-forms' ),
			],
			'requireLoginMessage'        => [
				'type'        => 'String',
				'description' => __( 'When requireLogin is set to true, this controls the message displayed when non-logged in user tries to access the form.', 'wp-graphql-gravity-forms' ),
			],
			'save'                       => [
				'type'        => SaveAndContinue::$type,
				'description' => __( '"Save and Continue" data.', 'wp-graphql-gravity-forms' ),
			],
			'scheduleEnd'                => [
				'type'        => 'String',
				'description' => __( 'Date in the format (mm/dd/yyyy) that the form will become inactive/hidden.', 'wp-graphql-gravity-forms' ),
			],
			'scheduleEndAmpm'            => [
				'type'        => 'String',
				'description' => __( '"am? or "pm?. Applies to scheduleEndHour', 'wp-graphql-gravity-forms' ),
			],
			'scheduleEndHour'            => [
				'type'        => 'Int',
				'description' => __( 'Hour (1 to 12) that the form will become inactive/hidden.', 'wp-graphql-gravity-forms' ),
			],
			'scheduleEndMinute'          => [
				'type'        => 'Int',
				'description' => __( 'Minute that the form will become inactive/hidden.', 'wp-graphql-gravity-forms' ),
			],
			'scheduleForm'               => [
				'type'        => 'Boolean',
				'description' => __( 'Specifies if this form is scheduled to be displayed only during a certain configured date/time.', 'wp-graphql-gravity-forms' ),
			],
			'scheduleMessage'            => [
				'type'        => 'String',
				'description' => __( 'Message to be displayed when form is no longer available', 'wp-graphql-gravity-forms' ),
			],
			'schedulePendingMessage'     => [
				'type'        => 'String',
				'description' => __( 'Message to be displayed when form is not yet available.', 'wp-graphql-gravity-forms' ),
			],
			'scheduleStart'              => [
				'type'        => 'String',
				'description' => __( 'Date in the format (mm/dd/yyyy) that the form will become active/visible.', 'wp-graphql-gravity-forms' ),
			],
			'scheduleStartAmpm'          => [
				'type'        => 'String',
				'description' => __( '"am" or "pm". Applies to scheduleStartHour', 'wp-graphql-gravity-forms' ),
			],
			'scheduleStartHour'          => [
				'type'        => 'Int',
				'description' => __( 'Hour (1 to 12) that the form will become active/visible.', 'wp-graphql-gravity-forms' ),
			],
			'scheduleStartMinute'        => [
				'type'        => 'Int',
				'description' => __( 'Minute that the form will become active/visible.', 'wp-graphql-gravity-forms' ),
			],
			'subLabelPlacement'          => [
				'type'        => Enum\FormSubLabelPlacementEnum::$type,
				'description' => __( 'How sub-labels are aligned.', 'wp-graphql-gravity-forms' ),
			],
			'title'                      => [
				'type'        => 'String',
				'description' => __( 'Form title.', 'wp-graphql-gravity-forms' ),
			],
			'useCurrentUserAsAuthor'     => [
				'type'        => 'Boolean',
				'description' => __( 'For forms with Post fields, this determines if the post should be created using the current logged in user as the author. 1 to use the current user, 0 otherwise.', 'wp-graphql-gravity-forms' ),
			],
			'validationSummary'          => [
				'type'        => 'Boolean',
				'description' => __( 'If enabled, will show a summary that lists form validation errors at the top of the form when a user attempts a failed submission.', 'wp-graphql-gravity-forms' ),
			],
			'version'                    => [
				'type'        => 'String',
				'description' => __( 'The version of Gravity Forms used to create this form.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Register form query.
	 */
	public static function register_field() : void {
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
						'type'        => Enum\IdTypeEnum::$type,
						'description' => __( 'Type of unique identifier to fetch a content node by. Default is Global ID', 'wp-graphql-gravity-forms' ),
					],
				],
				'resolve'     => function( $root, array $args, AppContext $context ) {
					$idType = $args['idType'] ?? 'global_id';

					/**
					 * If global id is used, get the (int) id.
					 */
					if ( 'global_id' === $idType ) {
						$id_parts = Relay::fromGlobalId( $args['id'] );

						if ( ! is_array( $id_parts ) || empty( $id_parts['id'] ) || empty( $id_parts['type'] ) ) {
							throw new UserError( __( 'A valid global ID must be provided.', 'wp-graphql-gravity-forms' ) );
						}
						$id = (int) sanitize_text_field( $id_parts['id'] );
					} else {
						$id = (int) sanitize_text_field( $args['id'] );
					}

					return Factory::resolve_form( $id, $context );
				},
			]
		);
	}
}
