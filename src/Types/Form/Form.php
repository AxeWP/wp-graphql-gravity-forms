<?php
/**
 * GraphQL Object Type - Gravity Forms Form
 *
 * @see https://docs.gravityforms.com/form-object/
 *
 * @package WPGraphQLGravityForms\Types\Form
 * @since   0.0.1
 * @since   0.4.0 Accept database Ids in query.
 */

namespace WPGraphQLGravityForms\Types\Form;

use GraphQLRelay\Relay;
use GraphQL\Error\UserError;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;
use WPGraphQLGravityForms\Interfaces\Field;
use WPGraphQLGravityForms\DataManipulators\FormDataManipulator;
use WPGraphQLGravityForms\Types\Button\Button;
use WPGraphQLGravityForms\Types\Button\LastPageButton;
use WPGraphQLGravityForms\Types\Enum\FormDescriptionPlacementEnum;
use WPGraphQLGravityForms\Types\Enum\FormLabelPlacementEnum;
use WPGraphQLGravityForms\Types\Enum\FormLimitEntriesPeriodEnum;
use WPGraphQLGravityForms\Types\Enum\FormSubLabelPlacementEnum;
use WPGraphQLGravityForms\Types\Enum\IdTypeEnum;
use WPGraphQLGravityForms\Utils\GFUtils;

/**
 * Class - Form
 */
class Form implements Hookable, Type, Field {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'GravityFormsForm';

	/**
	 * Field registered in WPGraphQL.
	 */
	const FIELD = 'gravityFormsForm';

	/**
	 * FormDataManipulator instance.
	 *
	 * @var FormDataManipulator
	 */
	private $form_data_manipulator;

	/**
	 * Constructor
	 *
	 * @param FormDataManipulator $form_data_manipulator .
	 */
	public function __construct( FormDataManipulator $form_data_manipulator ) {
		$this->form_data_manipulator = $form_data_manipulator;
	}

	/**
	 * Register hooks to WordPress.
	 */
	public function register_hooks() : void {
		add_action( 'graphql_register_types', [ $this, 'register_type' ] );
		add_action( 'graphql_register_types', [ $this, 'register_field' ] );
	}

	/**
	 * Register Object type to GraphQL schema.
	 */
	public function register_type() : void {
		register_graphql_object_type(
			self::TYPE,
			[
				'description' => __( 'Gravity Forms form.', 'wp-graphql-gravity-forms' ),
				'fields'      => [
					'id'                         => [
						'type'        => [ 'non_null' => 'ID' ],
						'description' => __( 'Unique global ID for the object.', 'wp-graphql-gravity-forms' ),
					],
					'formId'                     => [
						'type'        => 'Int',
						'description' => __( 'Form ID.', 'wp-graphql-gravity-forms' ),
					],
					'title'                      => [
						'type'        => 'String',
						'description' => __( 'Form title.', 'wp-graphql-gravity-forms' ),
					],
					'description'                => [
						'type'        => 'String',
						'description' => __( 'Form description.', 'wp-graphql-gravity-forms' ),
					],
					'labelPlacement'             => [
						'type'        => FormLabelPlacementEnum::$type,
						'description' => __( 'Determines where the field labels should be placed in relation to the field.', 'wp-graphql-gravity-forms' ),
					],
					'descriptionPlacement'       => [
						'type'        => FormDescriptionPlacementEnum::$type,
						'description' => __( 'Determines if the field description is displayed above the field input (i.e. immediately after the field label) or below the field input.', 'wp-graphql-gravity-forms' ),
					],
					'button'                     => [
						'type'        => Button::TYPE,
						'description' => __( 'Contains the form button settings such as the button text or image button source.', 'wp-graphql-gravity-forms' ),
					],
					'useCurrentUserAsAuthor'     => [
						'type'        => 'Boolean',
						'description' => __( 'For forms with Post fields, this determines if the post should be created using the current logged in user as the author. 1 to use the current user, 0 otherwise.', 'wp-graphql-gravity-forms' ),
					],
					'postContentTemplateEnabled' => [
						'type'        => 'Boolean',
						'description' => __( 'Determines if the post template functionality is enabled. When enabled, the post content will be created based on the template specified by postContentTemplate.', 'wp-graphql-gravity-forms' ),
					],
					'postTitleTemplateEnabled'   => [
						'type'        => 'Boolean',
						'description' => __( 'Determines if the post title template functionality is enabled. When enabled, the post title will be created based on the template specified by postTitleTemplate.', 'wp-graphql-gravity-forms' ),
					],
					'postTitleTemplate'          => [
						'type'        => 'String',
						'description' => __( 'Template to be used when creating the post title. Field variables (i.e. {Name:3} ) can be added to the template to insert user submitted values into the post title. Only applicable when postTitleTemplateEnabled is true', 'wp-graphql-gravity-forms' ),
					],
					'postContentTemplate'        => [
						'type'        => 'String',
						'description' => __( 'Template to be used when creating the post content. Field variables (i.e. {Name:3} ) can be added to the template to insert user submitted values into the post content. Only applicable when postContentTemplateEnabled is true.', 'wp-graphql-gravity-forms' ),
					],
					'lastPageButton'             => [
						'type'        => LastPageButton::TYPE,
						'description' => __( 'Last page button data.', 'wp-graphql-gravity-forms' ),
					],
					'pagination'                 => [
						'type'        => FormPagination::TYPE,
						'description' => __( 'Pagination data.', 'wp-graphql-gravity-forms' ),
					],
					'firstPageCssClass'          => [
						'type'        => 'String',
						'description' => __( 'CSS class for the first page.', 'wp-graphql-gravity-forms' ),
					],
					'postAuthor'                 => [
						'type'        => 'Int',
						'description' => __( 'When useCurrentUserAsAuthor is set to 0, this property contains the user Id that will be used as the Post author.', 'wp-graphql-gravity-forms' ),
					],
					'postCategory'               => [
						'type'        => 'Int',
						'description' => __( 'Form forms with Post fields, but without a Post Category field, this property determines the default category that the post will be associated with when created.', 'wp-graphql-gravity-forms' ),
					],
					'postFormat'                 => [
						'type'        => 'String',
						'description' => __( 'For forms with Post fields, determines the format that the Post should be created with.', 'wp-graphql-gravity-forms' ),
					],
					'postStatus'                 => [
						'type'        => 'String',
						'description' => __( 'For forms with Post fields, determines the status that the Post should be created with.', 'wp-graphql-gravity-forms' ),
					],
					'subLabelPlacement'          => [
						'type'        => FormSubLabelPlacementEnum::$type,
						'description' => __( 'How sub-labels are aligned.', 'wp-graphql-gravity-forms' ),
					],
					'cssClass'                   => [
						'type'        => 'String',
						'description' => __( 'String containing the custom CSS classes to be added to the <form> tag.', 'wp-graphql-gravity-forms' ),
					],
					'cssClassList'               => [
						'type'              => [ 'list_of' => 'String' ],
						'description'       => __( 'Array of the custom CSS classes to be added to the <form> tag.', 'wp-graphql-gravity-forms' ),
						'deprecationReason' => __( 'Please use `cssClass` instead.', 'wp-graphql-gravity-forms' ),
					],
					'enableHoneypot'             => [
						'type'        => 'Boolean',
						'description' => __( 'Specifies if the form has the Honeypot spam-protection feature.', 'wp-graphql-gravity-forms' ),
					],
					'enableAnimation'            => [
						'type'        => 'Boolean',
						'description' => __( 'When enabled, conditional logic hide/show operation will be performed with a jQuery slide animation. Only applicable to forms with conditional logic.', 'wp-graphql-gravity-forms' ),
					],
					'save'                       => [
						'type'        => SaveAndContinue::TYPE,
						'description' => __( '"Save and Continue" data.', 'wp-graphql-gravity-forms' ),
					],
					'limitEntries'               => [
						'type'        => 'Boolean',
						'description' => __( 'Specifies if this form has a limit on the number of submissions. 1 if the form limits submissions, 0 otherwise.', 'wp-graphql-gravity-forms' ),
					],
					'limitEntriesCount'          => [
						'type'        => 'Int',
						'description' => __( 'When limitEntries is set to 1, this property specifies the number of submissions allowed.', 'wp-graphql-gravity-forms' ),
					],
					'limitEntriesPeriod'         => [
						'type'        => FormLimitEntriesPeriodEnum::$type,
						'description' => __( 'When limitEntries is set to 1, this property specifies the time period during which submissions are allowed.', 'wp-graphql-gravity-forms' ),
					],
					'limitEntriesMessage'        => [
						'type'        => 'String',
						'description' => __( 'Message that will be displayed when the maximum number of submissions have been reached.', 'wp-graphql-gravity-forms' ),
					],
					'scheduleForm'               => [
						'type'        => 'Boolean',
						'description' => __( 'Specifies if this form is scheduled to be displayed only during a certain configured date/time.', 'wp-graphql-gravity-forms' ),
					],
					'scheduleStart'              => [
						'type'        => 'String',
						'description' => __( 'Date in the format (mm/dd/yyyy) that the form will become active/visible.', 'wp-graphql-gravity-forms' ),
					],
					'scheduleStartHour'          => [
						'type'        => 'Int',
						'description' => __( 'Hour (1 to 12) that the form will become active/visible.', 'wp-graphql-gravity-forms' ),
					],
					'scheduleStartMinute'        => [
						'type'        => 'Int',
						'description' => __( 'Minute that the form will become active/visible.', 'wp-graphql-gravity-forms' ),
					],
					'scheduleStartAmpm'          => [
						'type'        => 'String',
						'description' => __( '"am" or "pm". Applies to scheduleStartHour', 'wp-graphql-gravity-forms' ),
					],
					'scheduleEnd'                => [
						'type'        => 'String',
						'description' => __( 'Date in the format (mm/dd/yyyy) that the form will become inactive/hidden.', 'wp-graphql-gravity-forms' ),
					],
					'scheduleEndHour'            => [
						'type'        => 'Int',
						'description' => __( 'Hour (1 to 12) that the form will become inactive/hidden.', 'wp-graphql-gravity-forms' ),
					],
					'scheduleEndMinute'          => [
						'type'        => 'Int',
						'description' => __( 'Minute that the form will become inactive/hidden.', 'wp-graphql-gravity-forms' ),
					],
					'scheduleEndAmpm'            => [
						'type'        => 'String',
						'description' => __( '"am? or "pm?. Applies to scheduleEndHour', 'wp-graphql-gravity-forms' ),
					],
					'schedulePendingMessage'     => [
						'type'        => 'String',
						'description' => __( 'Message to be displayed when form is not yet available.', 'wp-graphql-gravity-forms' ),
					],
					'scheduleMessage'            => [
						'type'        => 'String',
						'description' => __( 'Message to be displayed when form is no longer available', 'wp-graphql-gravity-forms' ),
					],
					'requireLogin'               => [
						'type'        => 'Boolean',
						'description' => __( 'Whether the form is configured to be displayed only to logged in users.', 'wp-graphql-gravity-forms' ),
					],
					'requireLoginMessage'        => [
						'type'        => 'String',
						'description' => __( 'When requireLogin is set to true, this controls the message displayed when non-logged in user tries to access the form.', 'wp-graphql-gravity-forms' ),
					],
					'notifications'              => [
						'type'        => [ 'list_of' => FormNotification::TYPE ],
						'description' => __( 'The properties for all the email notifications which exist for a form.', 'wp-graphql-gravity-forms' ),
					],
					'confirmations'              => [
						'type'        => [ 'list_of' => FormConfirmation::TYPE ],
						'description' => __( 'Contains the form confirmation settings such as confirmation text or redirect URL', 'wp-graphql-gravity-forms' ),
					],
					'nextFieldId'                => [
						'type'        => 'Int',
						'description' => __( 'The ID to assign to the next field that is added to the form.', 'wp-graphql-gravity-forms' ),
					],
					'isActive'                   => [
						'type'        => 'Boolean',
						'description' => __( 'Determines whether the form is active.', 'wp-graphql-gravity-forms' ),
					],
					'dateCreated'                => [
						'type'        => 'String',
						'description' => __( 'The date the form was created in this format: YYYY-MM-DD HH:mm:ss.', 'wp-graphql-gravity-forms' ),
					],
					'isTrash'                    => [
						'type'        => 'Boolean',
						'description' => __( 'Determines whether the form is in the trash.', 'wp-graphql-gravity-forms' ),
					],
				],
			]
		);
	}

	/**
	 * Register form query.
	 */
	public function register_field() : void {
		register_graphql_field(
			'RootQuery',
			self::FIELD,
			[
				'description' => __( 'Get a Gravity Forms form.', 'wp-graphql-gravity-forms' ),
				'type'        => self::TYPE,
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
				'resolve'     => function( $root, array $args ) : array {
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

					$form_raw = GFUtils::get_form( $id, false );

					$form = $this->form_data_manipulator->manipulate( $form_raw );

					/**
					 * "wp_graphql_gf_form_object" filter
					 *
					 * Provides the ability to manipulate the form data before it is sent to the
					 * client. This hook is somewhat similar to Gravity Forms' gform_pre_render hook
					 * and can be used for dynamic field input population, among other things.
					 *
					 * @param array $form Form meta array.
					 */
					return apply_filters( 'wp_graphql_gf_form_object', $form );
				},
			]
		);
	}
}
