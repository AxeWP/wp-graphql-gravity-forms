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
use WPGraphQLGravityForms\Types\Enum\PageProgressStyleEnum;
use WPGraphQLGravityForms\Types\Enum\PageProgressTypeEnum;

/**
 * Class - FormPagination
 */
class FormPagination implements Hookable, Type {
	const TYPE = 'FormPagination';

	/**
	 * Register hooks to WordPress.
	 */
	public function register_hooks() : void {
		add_action( 'graphql_register_types', [ $this, 'register_type' ] );
	}

	/**
	 * Register Object type to GraphQL schema.
	 */
	public function register_type() : void {
		register_graphql_object_type(
			self::TYPE,
			[
				'description' => __( 'Gravity Forms form pagination data.', 'wp-graphql-gravity-forms' ),
				'fields'      => [
					'type'                             => [
						'type'        => PageProgressTypeEnum::$type,
						'description' => __( 'Type of progress indicator.', 'wp-graphql-gravity-forms' ),
					],
					'pages'                            => [
						'type'        => [ 'list_of' => 'String' ],
						'description' => __( 'Names of the form\'s pages.', 'wp-graphql-gravity-forms' ),
					],
					'style'                            => [
						'type'        => PageProgressStyleEnum::$type,
						'description' => __( 'Style of progress bar.', 'wp-graphql-gravity-forms' ),
					],
					'backgroundColor'                  => [
						'type'        => 'String',
						'description' => __( 'Progress bar background color. Can be any CSS color value. Only applies when "style" is set to "CUSTOM".', 'wp-graphql-gravity-forms' ),
					],
					'color'                            => [
						'type'        => 'String',
						'description' => __( 'Progress bar text color. Can be any CSS color value. Only applies when "style" is set to "CUSTOM".', 'wp-graphql-gravity-forms' ),
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
