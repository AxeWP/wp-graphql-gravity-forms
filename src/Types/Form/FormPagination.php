<?php
/**
 * GraphQL Object Type - Gravity Forms form pagination
 *
 * @see https://docs.gravityforms.com/page-break/
 *
 * @package WPGraphQLGravityForms\Types\Form
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Form;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;

/**
 * Class - FormPagination
 */
class FormPagination implements Hookable, Type {
	const TYPE = 'FormPagination';

	/**
	 * Register hooks to WordPress.
	 */
	public function register_hooks() {
		add_action( 'graphql_register_types', [ $this, 'register_type' ] );
	}

	/**
	 * Register Object type to GraphQL schema.
	 */
	public function register_type() {
		register_graphql_object_type(
			self::TYPE,
			[
				'description' => __( 'Gravity Forms form pagination data.', 'wp-graphql-gravity-forms' ),
				'fields'      => [
					// @TODO - convert to enum
					'type'                             => [
						'type'        => 'String',
						'description' => __( 'Type of progress indicator. Possible values are: percentage, steps or none.', 'wp-graphql-gravity-forms' ),
					],
					'pages'                            => [
						'type'        => [ 'list_of' => 'String' ],
						'description' => __( 'Names of the form\'s pages.', 'wp-graphql-gravity-forms' ),
					],
					// @TODO - convert to enum
					'style'                            => [
						'type'        => 'String',
						'description' => __( 'Style of progress bar. Possible values are: blue, gray, green, orange, red or custom.', 'wp-graphql-gravity-forms' ),
					],
					'backgroundColor'                  => [
						'type'        => 'String',
						'description' => __( 'Progress bar background color. Can be any CSS color value. Only applies when "style" is set to "custom".', 'wp-graphql-gravity-forms' ),
					],
					'color'                            => [
						'type'        => 'String',
						'description' => __( 'Progress bar text color. Can be any CSS color value. Only applies when "style" is set to "custom".', 'wp-graphql-gravity-forms' ),
					],
					'displayProgressbarOnConfirmation' => [
						'type'        => 'Boolean',
						'description' => __( 'Whether the confirmation bar should be displayed with the confirmation text.', 'wp-graphql-gravity-forms' ),
					],
					'progressbarCompletionText'        => [
						'type'        => 'String',
						'description' => __( 'The confirmation text to display once the end of the progress bar has been reached. Only applies when displayProgressbarOnConfirmation is set to true.', 'wp-graphql-gravity-forms' ),
					],
				],
			]
		);
	}
}
