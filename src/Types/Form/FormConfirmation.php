<?php
/**
 * GraphQL Object Type - Gravity Forms Form confirmation
 *
 * @see https://docs.gravityforms.com/confirmation/
 *
 * @package WPGraphQLGravityForms\Types\Form
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Form;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;
use WPGraphQLGravityForms\Types\Enum\ConfirmationTypeEnum;

/**
 * Class - FormConfirmation
 */
class FormConfirmation implements Hookable, Type {
	const TYPE = 'FormConfirmation';

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
				'description' => __( 'Properties for all the email notifications which exist for a form.', 'wp-graphql-gravity-forms' ),
				'fields'      => [
					'id'          => [
						'type'        => 'String',
						'description' => __( 'ID.', 'wp-graphql-gravity-forms' ),
					],
					'name'        => [
						'type'        => 'String',
						'description' => __( 'Name.', 'wp-graphql-gravity-forms' ),
					],
					'isDefault'   => [
						'type'        => 'Boolean',
						'description' => __( 'Whether this is the default confirmation.', 'wp-graphql-gravity-forms' ),
					],
					'type'        => [
						'type'        => ConfirmationTypeEnum::$type,
						'description' => __( 'Determines the type of confirmation to be used.', 'wp-graphql-gravity-forms' ),
					],
					'message'     => [
						'type'        => 'String',
						'description' => __( 'Contains the confirmation message that will be displayed. Only applicable when type is set to "MESSAGE".', 'wp-graphql-gravity-forms' ),
					],
					'url'         => [
						'type'        => 'String',
						'description' => __( 'Contains the URL that the browser will be redirected to. Only applicable when type is set to "REDIRECT".', 'wp-graphql-gravity-forms' ),
					],
					'pageId'      => [
						'type'        => 'Integer',
						'description' => __( 'Contains the Id of the WordPress page that the browser will be redirected to. Only applicable when type is set to "PAGE".', 'wp-graphql-gravity-forms' ),
					],
					'queryString' => [
						'type'        => 'String',
						'description' => __( 'Contains the query string to be appended to the redirection url. Only applicable when type is set to redirect.', 'wp-graphql-gravity-forms' ),
					],
				],
			]
		);
	}
}
