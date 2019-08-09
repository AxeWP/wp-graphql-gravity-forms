<?php

namespace WPGraphQLGravityForms\Types\Field\FieldValue;

use GF_Field;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;
use WPGraphQLGravityForms\Interfaces\FieldValue;

/**
 * Values for an individual Time field.
 */
class TimeFieldValues implements Hookable, Type, FieldValue {
    /**
     * Type registered in WPGraphQL.
     */
    const TYPE = 'TimeFieldValues';

    public function register_hooks() {
        add_action( 'graphql_register_types', [ $this, 'register_type' ] );
    }

    public function register_type() {
        register_graphql_object_type( self::TYPE, [
            'description' => __('Gravity Forms time field values.', 'wp-graphql-gravity-forms'),
            'fields'      => [
                'displayValue' => [
                    'type'        => 'String',
                    'description' => __('The full display value. Example: "08:25 am".', 'wp-graphql-gravity-forms'),
                ],
                'hours' => [
                    'type'        => 'String',
                    'description' => __('The hours, in this format: hh.', 'wp-graphql-gravity-forms'),
                ],
                'minutes' => [
                    'type'        => 'String',
                    'description' => __('The minutes, in this format: mm.', 'wp-graphql-gravity-forms'),
                ],
                'amPm' => [
                    'type'        => 'String',
                    'description' => __('AM or PM.', 'wp-graphql-gravity-forms'),
                ],
            ],
        ] );
    }

    /**
     * Get the field value.
     *
     * @param array    $entry Gravity Forms entry.
     * @param GF_Field $field Gravity Forms field.
     *
     * @return array Entry field value.
     */
    public static function get( array $entry, GF_Field $field ) : array {
        $displayValue = $entry[ $field['id'] ];

        $parts_by_colon = explode( ':', $displayValue );
        $hours          = $parts_by_colon[0] ?? '';

        $parts_by_space = explode( ' ', $displayValue );
        $amPm          = $parts_by_space[1] ?? '';

        $minutes = rtrim( ltrim( $displayValue, "{$hours}:" ), " {$amPm}" );

        return compact( 'displayValue', 'hours', 'minutes', 'amPm' );
    }
}
