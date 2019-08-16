<?php

namespace WPGraphQLGravityForms\Types\Entry;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\InputType;

/**
 * Field Filters input type for Entries queries.
 */
class EntriesFieldFiltersInput implements Hookable, InputType {
    /**
     * Type registered in WPGraphQL.
     */
    const TYPE = 'EntriesFieldFiltersInput';

    public function register_hooks() {
        add_action( 'graphql_register_types', [ $this, 'register_input_type' ] );
    }

    public function register_input_type() {
        register_graphql_input_type( self::TYPE, [
            'description' => __('Field Filters input fields for Entries queries.', 'wp-graphql-gravity-forms'),
            'fields'      => [
                'key' => [
                    'type'        => 'String',
                    'description' => __( 'The ID of the field to filter by. Use "0" to search all keys.', 'wp-graphql-gravity-forms' ),
                ],
                // @TODO: Convert to enum.
                'operator' => [
                    'type'        => 'String',
                    'description' => __( 'The operator to use for filtering. Possible values: "in" (find field values that match those in the values array), "notIn" (find field values that do NOT match those in the values array), or "contains" (find field values that contain the value in the values array). When "contains" is used, only the first value in the values array will be used; any others will be disregarded.', 'wp-graphql-gravity-forms' ),
                ],
                // @TODO - Is there a cleaner way to do this? Values can be any of these types.
                'stringValues' => [
                    'type'        => [ 'list_of' => 'String' ],
                    'description' => __( 'The field value(s) to filter by. Must be string values. If using this field, do not also use intValues, floatValues or boolValues.', 'wp-graphql-gravity-forms' ),
                ],
                'intValues' => [
                    'type'        => [ 'list_of' => 'Integer' ],
                    'description' => __( 'The field value(s) to filter by. Must be integer values. If using this field, do not also use stringValues, floatValues or boolValues.', 'wp-graphql-gravity-forms' ),
                ],
                'floatValues' => [
                    'type'        => [ 'list_of' => 'Float' ],
                    'description' => __( 'The field value(s) to filter by. Must be float values. If using this field, do not also use stringValues, intValues or boolValues.', 'wp-graphql-gravity-forms' ),
                ],
                'boolValues' => [
                    'type'        => [ 'list_of' => 'Boolean' ],
                    'description' => __( 'The field value(s) to filter by. Must be boolean values. If using this field, do not also use stringValues, intValues or floatValues.', 'wp-graphql-gravity-forms' ),
                ],
            ],
        ] );
    }
}
