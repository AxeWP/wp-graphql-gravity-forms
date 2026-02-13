<?php
/**
 * FormField Model class
 *
 * @package \WPGraphQL\GF\Model
 * @since   0.13.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Model;

use GF_Field;
use GraphQLRelay\Relay;
use WPGraphQL\GF\Data\Loader\FormFieldsLoader;
use WPGraphQL\GF\Data\Loader\FormsLoader;
use WPGraphQL\GF\Registry\FieldChoiceRegistry;
use WPGraphQL\GF\Registry\FieldInputRegistry;
use WPGraphQL\Model\Model;

/**
 * Class - FormField
 *
 * @property array<string,mixed>[] $choices    The choices for the field.
 * @property int                   $databaseId The database ID of the field.
 * @property string                $id         The global Relay ID of the field.
 * @property array<string,mixed>[] $inputs     The inputs for the field.
 * @property string                $inputType  The input type of the field.
 * @property \GF_Field             $gfField    The Gravity Forms field object.
 * @property int                   $layoutGridColumSpan The layout grid column span of the field.
 *
 * @extends \WPGraphQL\Model\Model<\GF_Field>
 */
class FormField extends Model {
	/**
	 * @var array<string,null>
	 */
	private const EMPTY_CHOICES = [
		'text'       => null,
		'value'      => null,
		'isSelected' => null,
		'price'      => null,
	];

	/**
	 * The unmodified Gravity Forms field object.
	 *
	 * @var \GF_Field
	 */
	protected $gf_field;

	/**
	 * The GF Form associated with the field.
	 *
	 * @var array<string,mixed>
	 */
	protected $form;

	/**
	 * Form constructor.
	 *
	 * @param \GF_Field            $field The incoming field to be modeled.
	 * @param ?array<string,mixed> $form The source form data.
	 *
	 * @throws \Exception .
	 */
	public function __construct( GF_Field $field, ?array $form = null ) {
		if ( empty( $form ) ) {
			$context = \WPGraphQL::get_app_context();

			$form_model = $context->get_loader( FormsLoader::$name )->load( $field->formId );

			if ( empty( $form_model->form ) ) {
				throw new \Exception(
					sprintf(
						/* translators: %s: GF_Field */
						esc_html__( 'Form ID % not found for Field ID %s', 'wp-graphql-gravity-forms' ),
						esc_html( $field->formId ),
						esc_html( $field->id )
					)
				);
			}

			$form = $form_model->form;
		}

		$this->form = $form;
		$this->data = self::prepare_model_data( $field );

		parent::__construct();
	}

	/**
	 * {@inheritDoc}
	 */
	public function __isset( $key ) {
		return isset( $this->fields[ $key ] ) || isset( $this->data->$key );
	}

	/**
	 * {@inheritDoc}
	 */
	public function __get( $key ) {
		// First check the fields array.
		if ( isset( $this->fields[ $key ] ) ) {

			/**
			 * If the property has already been processed and cached to the model
			 * return the processed value.
			 *
			 * Otherwise, if it's a callable, process it and cache the value.
			 */
			if ( is_scalar( $this->fields[ $key ] ) || ( is_object( $this->fields[ $key ] ) && ! is_callable( $this->fields[ $key ] ) ) || is_array( $this->fields[ $key ] ) ) {
				return $this->fields[ $key ];
			} elseif ( is_callable( $this->fields[ $key ] ) ) {
				$data       = call_user_func( $this->fields[ $key ] );
				$this->$key = $data;

				return $data;
			} else {
				return $this->fields[ $key ];
			}
		} elseif ( property_exists( $this->data, $key ) ) {
			// Pass through to the \GF_Field object.
			$data       = $this->data->$key;
			$this->$key = $data;
			return $data;
		}

		return null;
	}

	/**
	 * Pass calls to the GF_Field object.
	 *
	 * @param string  $name      The method name.
	 * @param mixed[] $arguments The method arguments.
	 *
	 * @return mixed
	 * @throws \BadMethodCallException .
	 */
	public function __call( $name, $arguments ) {
		if ( method_exists( $this->data, $name ) ) {
			return $this->data->$name( ...$arguments );
		}

		throw new \BadMethodCallException( 'Method ' . esc_html( $name ) . ' does not exist on ' . self::class . ' or the underlying' . \GF_Field::class );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function is_private(): bool {
		if ( ! isset( $this->form['requireLogin'] ) || ! $this->form['requireLogin'] || is_user_logged_in() ) {
			return false;
		}

		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function init(): void {
		if ( empty( $this->fields ) ) {
			$fields = $this->prepare_model_fields( $this->data );

			$this->fields = $fields;
		}
	}

	/**
	 * Prepares the GF field data for the model.
	 *
	 * @param \GF_Field $field The field to prepare.
	 */
	protected static function prepare_model_data( GF_Field $field ): GF_Field {
		// Set empty values to null.
		$data = get_object_vars( $field );
		foreach ( $data as $key => $value ) {
			if ( '' !== $value ) {
				continue;
			}

			$field->$key = null;
		}

		return $field;
	}

	/**
	 * Prepares the Model fields.
	 *
	 * @param \GF_Field $data The model data.
	 *
	 * @return array<string,callable> The model fields.
	 */
	protected function prepare_model_fields( \GF_Field $data ): array {
		return [
			'choices'             => static function () use ( $data ): ?array {
				if ( empty( $data->choices ) || ! is_array( $data->choices ) ) {
					return null;
				}

				$choices = $data->choices;

				// Set choices for single-column list fields, so we can use the same mutation for both.
				if ( 'list' === $data->type && isset( $data->columns ) && 1 === $data->columns ) {
					$choices = self::EMPTY_CHOICES;
				}

				// Include GraphQL Type in resolver.
				return array_map(
					static function ( $choice ) use ( $data ) {
						$choice['graphql_type'] = FieldChoiceRegistry::get_type_name( $data );

						return $choice;
					},
					$choices
				);
			},
			'databaseId'          => static fn (): int => (int) $data->id,
			'gfField'             => static fn (): GF_Field => $data,
			'id'                  => static fn (): string => Relay::toGlobalId( FormFieldsLoader::$name, $data->formId . ':' . $data->id ),
			'inputs'              => static function () use ( $data ): ?array {
				// Emails fields are handled later.
				if ( ( empty( $data->inputs ) || ! is_array( $data->inputs ) ) && 'email' !== $data->type ) {
					return null;
				}

				$inputs = $data->inputs;

				// Prime inputs for address and name fields.
				if ( in_array( $data->type, [ 'address', 'name' ], true ) ) {
					foreach ( $inputs as $input_index => $input ) {
						// set isHidden to boolean.
						$inputs[ $input_index ]['isHidden'] = ! empty( $inputs[ $input_index ]['isHidden'] );

						$input_keys = 'address' === $data['type'] ? self::get_address_input_keys() : self::get_name_input_keys();

						$inputs[ $input_index ]['key'] = $input_keys[ $input_index ];
					}

					// Apply dynamic labels for address fields based on addressType.
					if ( 'address' === $data->type ) {
						$address_labels = self::get_address_input_type_labels( $data->formId, $data->addressType ?? '' );

						$inputs[3]['label'] = $address_labels['state_label'] ?? $inputs[3]['label'];
						$inputs[4]['label'] = $address_labels['zip_label'] ?? $inputs[3]['label'];
					}
				} elseif ( 'email' === $data->type && empty( $data->emailConfirmEnabled ) ) {
					// Prime inputs for email fields without confirmation.
					$inputs = [
						[
							'autocompleteAttribute' => $data->autocompleteAttribute ?? null,
							'defaultValue'          => $data->defaultValue ?? null,
							'customLabel'           => $data->customLabel ?? null,
							'id'                    => $data->id ?? null,
							'label'                 => $data->label ?? null,
							'name'                  => $data->inputName ?? null,
							'placeholder'           => $data->placeholder ?? null,
						],
					];
				}

				$inputs = array_map(
					static function ( $input ) use ( $data ) {
						$input['graphql_type'] = FieldInputRegistry::get_type_name( $data );

						return $input;
					},
					$inputs
				);

				return $inputs;
			},
			'inputType'           => static fn (): string => $data->get_input_type(),
			'layoutGridColumSpan' => static fn (): ?int => ! empty( $data->layoutGridColumnSpan ) ? (int) $data->layoutGridColumnSpan : null,
		];
	}

	/**
	 * Returns input keys for Address field.
	 *
	 * @return string[]
	 */
	private static function get_address_input_keys(): array {
		return [
			'street',
			'lineTwo',
			'city',
			'state',
			'zip',
			'country',
		];
	}

	/**
	 * Returns dynamic labels for Address field based on addressType.
	 *
	 * Mirrors the logic from GF_Field_Address::get_address_types().
	 *
	 * @param int    $form_id The form ID to get the address type for.
	 * @param string $address_type The address type (international, us, canadian).
	 *
	 * @return array{state_label:string, zip_label:string}
	 */
	private static function get_address_input_type_labels( int $form_id, string $address_type ): array {
		/** @var \GF_Field_Address $field */
		$field         = \GF_Fields::get( 'address' );
		$address_types = $field->get_address_types( $form_id );
		$address_type  = $address_type ?: $field->get_default_address_type( $form_id );

		return $address_types[ $address_type ] ?? $address_types['international'];
	}

	/**
	 * Returns input keys for Name field.
	 *
	 * @return string[]
	 */
	private static function get_name_input_keys(): array {
		return [
			'prefix',
			'first',
			'middle',
			'last',
			'suffix',
		];
	}
}
