<?php
/**
 * Array configs for all field properties.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;

/**
 * Class - FieldProperties
 */
class FieldProperties {

	/**
	 * Get 'autocompleteAttribute' property.
	 */
	public static function autocomplete_attribute() : array {
		return [
			'autocompleteAttribute' => [
				'type'        => 'String',
				'description' => __( 'The autocomplete attribute for the field.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'isOtherChoice' choice property.
	 */
	public static function choice_is_other() : array {
		return [
			'isOtherChoice' => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates the radio button item is the “Other” choice.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'isSelected' choice property.
	 */
	public static function choice_is_selected() : array {
		return [
			'isSelected' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if this choice should be selected by default when displayed. The value true will select the choice, whereas false will display it unselected.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get the 'formattedPrice' choice property.
	 */
	public static function choice_formatted_price() : array {
		return [
			'formattedPrice' => [
				'type'        => 'String',
				'description' => __( 'The price associated with the choice.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source['price'] ) ? $source['price'] : null,
			],
		];
	}

	/**
	 * Get the 'price' choice property.
	 */
	public static function choice_price() : array {
		return [
			'price' => [
				'type'        => 'Float',
				'description' => __( 'The price associated with the choice.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source['price'] ) ? floatval( preg_replace( '/[^\d\.]/', '', $source['price'] ) ) : null,
			],
		];
	}

	/**
	 * Get 'defaultValue' property.
	 */
	public static function default_value() : array {
		return [
			'defaultValue' => [
				'type'        => 'String',
				'description' => __( 'Contains the default value for the field. When specified, the field\'s value will be populated with the contents of this property when the form is displayed.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'hasChoiceValue' property.
	 */
	public static function has_choice_value() : array {
		return [
			'hasChoiceValue' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if the field (checkbox, select or radio) have choice values enabled, which allows the field to have choice values different from the labels that are displayed to the user.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->enableChoiceValue ),
			],
		];
	}

	/**
	 * Get 'hasPrice' property.
	 */
	public static function has_price() : array {
		return [
			'hasPrice' => [
				'type'        => 'Boolean',
				'description' => __( 'This property is used when the radio button is a product option field and will be set to true. If not associated with a product, then it is false.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->enablePrice ),
			],
		];
	}

	/**
	 * Get 'customLabel' property for input.
	 */
	public static function input_custom_label() : array {
		return [
			'customLabel' => [
				'type'        => 'String',
				'description' => __( 'The custom label for the input. When set, this is used in place of the label.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'isHidden' property for input.
	 */
	public static function input_is_hidden() : array {
		return [
			'isHidden' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether or not this field should be hidden.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'key' property for input.
	 */
	public static function input_key() : array {
		return [
			'key' => [
				'type'        => 'String',
				'description' => __( 'Key used to identify this input.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'name' property for input.
	 */
	public static function input_name() : array {
		return [
			'name' => [
				'type'        => 'String',
				'description' => __( 'Assigns a name to this field so that it can be populated dynamically via this input name. Only applicable when canPrepopulate is `true`.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'inputName' property.
	 */
	public static function name() : array {
		return [
			'inputName' => [
				'type'        => 'String',
				'description' => __( 'Assigns a name to this field so that it can be populated dynamically via this input name. Only applicable when canPrepopulate is `true`.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'placeholder' property.
	 */
	public static function placeholder() : array {
		return [
			'placeholder' => [
				'type'        => 'String',
				'description' => __( 'Placeholder text to give the user a hint on how to fill out the field. This is not submitted with the form.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
