<?php
/**
 * Maps the Gravity Forms Field setting to the appropriate field settings.
 *
 * @package WPGraphQL\GF\Extensions\GFChainedSelects\Type\WPObject\FormField\FieldProperty;
 * @since   0.10
 */

namespace WPGraphQL\GF\Extensions\GFChainedSelects\Type\WPObject\FormField\FieldProperty;

use GF_Field;
use WPGraphQL\GF\Extensions\GFChainedSelects\Type\Enum;
use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty\ChoiceMapper;
use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty\FieldProperties;
use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty\InputMapper;

/**
 * Class - PropertyMapper
 */
class PropertyMapper {

	/**
	 * Maps the `chained_selects_alignment_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function chained_selects_alignment_setting( GF_Field $field, array &$properties ) : void {
		$properties += [
			'chainedSelectsAlignment' => [
				'type'        => Enum\ChainedSelectFieldAlignmentEnum::$type,
				'description' => __( 'Alignment of the dropdown fields.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Maps the `chained_selects_hide_inactive_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function chained_selects_hide_inactive_setting( GF_Field $field, array &$properties ) : void {
		$properties += [
			'shouldHideInactiveChoices' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether inactive dropdowns should be hidden.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->chainedSelectsHideInactive ),
			],
		];
	}

	/**
	 * Maps the `chained_choices_setting` to its field properties.
	 *
	 * @todo make nested choices flat.
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function chained_choices_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::has_choice_value();

		$choice_fields  = FieldProperties::choice_text();
		$choice_fields += FieldProperties::choice_value();
		$choice_fields += FieldProperties::choice_is_selected();

		// Nest choices.
		$choice_fields += ChoiceMapper::map_choices( $field, $choice_fields );

		$properties += ChoiceMapper::map_choices( $field, $choice_fields );

		$input_fields  = FieldProperties::label();
		$input_fields += FieldProperties::input_id();
		$input_fields += FieldProperties::input_name();

		$properties += InputMapper::map_inputs( $field, $input_fields );
	}

}
