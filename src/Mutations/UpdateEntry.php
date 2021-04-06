<?php
/**
 * Mutation - UpdateGravityFormsEntry
 *
 * Updates a Gravity Forms entry.
 *
 * @package WPGraphQLGravityForms\Mutation
 * @since 0.4.0
 */

namespace WPGraphQLGravityForms\Mutations;

use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQLGravityForms\DataManipulators\EntryDataManipulator;
use WPGraphQLGravityForms\Types\Entry\Entry;
use WPGraphQLGravityForms\Types\FieldError\FieldError;
use WPGraphQLGravityForms\Types\Input\FieldValuesInput;
use WPGraphQLGravityForms\Types\Enum\EntryStatusEnum;
use WPGraphQLGravityForms\Utils\GFUtils;

/**
 * Class - UpdateEntry
 */
class UpdateEntry extends AbstractMutation {
	/**
	 * Mutation Name
	 *
	 * @var string
	 */
	public static $name = 'updateGravityFormsEntry';

	/**
	 * EntryDataManipulator instance.
	 *
	 * @var EntryDataManipulator
	 */
	private $entry_data_manipulator;

	/**
	 * The Gravity Forms Form object.
	 *
	 * @var array
	 */
	private $form;

	/**
	 * Gravity Forms field validation errors.
	 *
	 * @var array
	 */
	protected $errors = [];

	/**
	 * Constructor
	 *
	 * @param EntryDataManipulator $entry_data_manipulator .
	 */
	public function __construct( EntryDataManipulator $entry_data_manipulator ) {
		$this->entry_data_manipulator = $entry_data_manipulator;
	}

	/**
	 * Defines the input field configuration.
	 *
	 * @return array
	 */
	public function get_input_fields() : array {
		return [
			'entryId'     => [
				'type'        => [ 'non_null' => 'Integer' ],
				'description' => __( 'The Gravity Forms entry id.', 'wp-graphql-gravity-forms' ),
			],
			'fieldValues' => [
				'type'        => [ 'list_of' => FieldValuesInput::TYPE ],
				'description' => __( 'The field ids and their values.', 'wp-graphql-gravity-forms' ),
			],
			'isStarred'   => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates if the entry has been starred (i.e marked with a star).', 'wp-graphql-gravity-forms' ),
			],
			'isRead'      => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates if the entry has been read. 1 for entries that are read and 0 for entries that have not been read.', 'wp-graphql-gravity-forms' ),
			],
			'ip'          => [
				'type'        => 'String',
				'description' => __( 'Client IP of user who submitted the form.', 'wp-graphql-gravity-forms' ),
			],
			'createdBy'   => [
				'type'        => 'Integer',
				'description' => __( 'ID of the user that submitted of the form if a logged in user submitted the form.', 'wp-graphql-gravity-forms' ),
			],
			'status'      => [
				'type'        => EntryStatusEnum::$type,
				'description' => __( 'The current status of the entry.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Defines the output field configuration.
	 *
	 * @return array
	 */
	public function get_output_fields() : array {
		return [
			'entryId' => [
				'type'        => 'Integer',
				'description' => __( 'The ID of the entry that was created. Null if the entry was only partially submitted or submitted as a draft.', 'wp-graphql-gravity-forms' ),
			],
			'entry'   => [
				'type'        => Entry::TYPE,
				'description' => __( 'The entry that was created.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function( array $payload ) {
					if ( ! empty( $payload['errors'] ) || ! $payload['entryId'] ) {
						return null;
					}

					$entry = GFUtils::get_entry( $payload['entryId'] );

					return $this->entry_data_manipulator->manipulate( $entry );
				},
			],
			'errors'  => [
				'type'        => [ 'list_of' => FieldError::TYPE ],
				'description' => __( 'Field errors.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Defines the data modification closure.
	 *
	 * @return callable
	 */
	public function mutate_and_get_payload() : callable {
		return function( $input, AppContext $context, ResolveInfo $info ) : array {
			// Check for required fields.
			$this->check_required_inputs( $input );

			// Set default values.
			$entry      = GFUtils::get_entry( (int) $input['entryId'] );
			$this->form = GFUtils::get_form( $entry['form_id'] );

			$entry_data = $this->prepare_entry_data( $input, $entry );

			if ( ! empty( $this->errors ) ) {
				return [ 'errors' => $this->errors ];
			}

			$updated_entry_id = GFUtils::update_entry( $entry_data );

			return [
				'entryId' => $updated_entry_id,
			];
		};
	}

	/**
	 * Prepares entry object for update.
	 *
	 * @param array $input .
	 * @param array $entry .
	 * @return array
	 */
	private function prepare_entry_data( array $input, array $entry ) : array {
			$is_starred = $input['isStarred'] ?? null;
			$is_read    = $input['isRead'] ?? null;
			$ip         = empty( $this->form['personalData']['preventIP'] ) ? GFUtils::get_ip( $input['ip'] ?? '' ) : '';
			$created_by = isset( $input['createdBy'] ) ? absint( $input['createdBy'] ) : null;
			$status     = $input['status'] ?? null;

			$entry_properties = array_filter(
				[
					'is_starred' => $is_starred,
					'is_read'    => $is_read,
					'ip'         => $ip,
					'created_by' => $created_by,
					'status'     => $status,
				],
				fn( $property ) => (bool) strlen( $property )
			);
			$field_values     = $this->prepare_field_values( $input['fieldValues'] );

			return array_replace(
				$entry,
				$entry_properties,
				$field_values,
			);
	}

	/**
	 * Converts the provided field values into a format that Gravity Forms can understand.
	 *
	 * @param array $field_values .
	 * @return array
	 */
	private function prepare_field_values( array $field_values ) : array {
		$formatted_values = [];

		foreach ( $field_values as $values ) {
			$field = GFUtils::get_field_by_id( $this->form, $values['id'] );

			$this->validate_field_value_type( $field, $values );

			$value = $values['addressValues'] ?? $values['chainedSelectValues'] ?? $values['checkboxValues'] ?? $values['listValues'] ?? $values['nameValues'] ?? $values['values'] ?? $values['value'];

			$value = $this->prepare_field_value_by_type( $value, $field );

			$this->validate_field_value( $this->form, $field, $value );

			// Add values to array based on field type.
			if ( in_array( $field->type, [ 'address', 'chainedselect', 'checkbox', 'consent', 'name' ], true ) ) {
				$formatted_values += $value;
			} else {
				$formatted_values[ $values['id'] ] = $value;
			}
		}

		return $formatted_values;
	}

	/**
	 * Ensures required input fields are set.
	 *
	 * @param mixed $input .
	 * @throws UserError .
	 */
	protected function check_required_inputs( $input = null ) : void {
		parent::check_required_inputs( $input );

		if ( ! isset( $input['entryId'] ) ) {
			throw new UserError( __( 'Mutation not processed. Entry ID not provided.', 'wp-graphql-gravity-forms' ) );
		}

		if ( empty( $input['fieldValues'] ) ) {
			throw new UserError( __( 'Mutation not processed. Field values not provided.', 'wp-graphql-gravity-forms' ) );
		}
	}
}
