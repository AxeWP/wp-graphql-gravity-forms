<?php
/**
 * GraphQL Object Type - Gravity Forms Entry Limits data.
 *
 * @package WPGraphQL\GF\Type\WPObject\Form
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Type\WPObject\Form;

use WPGraphQL\AppContext;
use WPGraphQL\GF\Type\Enum\PostFormatTypeEnum;
use WPGraphQL\GF\Type\WPObject\AbstractObject;

/**
 * Class - FormPostCreation
 */
class FormPostCreation extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormPostCreation';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Gravity Forms form entry limititation details.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'authorDatabaseId'             => [
				'type'        => 'Int',
				'description' => __( 'When `useCurrentUserAsAuthor` is `false`, this property contains the user database that will be used as the Post author.', 'wp-graphql-gravity-forms' ),
			],
			'authorId'                     => [
				'type'        => 'ID',
				'description' => __( 'When `useCurrentUserAsAuthor` is `false`, this property contains the user ID that will be used as the Post author.', 'wp-graphql-gravity-forms' ),
			],
			'author'                       => [
				'type'        => 'User',
				'description' => __( 'When `useCurrentUserAsAuthor` is `false`, this contains the User object for the author.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $source, array $args, AppContext $context ) {
					if ( empty( $source['authorDatabaseId'] ) ) {
						return null;
					}
					return $context->get_loader( 'user' )->load_deferred( (int) $source['authorDatabaseId'] );
				},
			],
			'categoryDatabaseId'           => [
				'type'        => 'Int',
				'description' => __( 'Form forms with Post fields, but without a Post Category field, this property contains the default category database ID the post will be associated with when created.', 'wp-graphql-gravity-forms' ),
			],
			'contentTemplate'              => [
				'type'        => 'String',
				'description' => __( 'Template to be used when creating the post content. Field variables (i.e. {Name:3} ) can be added to the template to insert user submitted values into the post content. Only applicable when `hasContentTemplate` is `true`.', 'wp-graphql-gravity-forms' ),
			],
			'format'                       => [
				'type'        => PostFormatTypeEnum::$type,
				'description' => __( 'Determines the format that the Post should be created with.', 'wp-graphql-gravity-forms' ),
			],
			'hasContentTemplate'           => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if the post template functionality is enabled. When enabled, the post content will be created based on the template specified by `contentTemplate`.', 'wp-graphql-gravity-forms' ),
			],
			'hasTitleTemplate'             => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if the post title template functionality is enabled. When enabled, the post title will be created based on the template specified by `titleTemplate`.', 'wp-graphql-gravity-forms' ),
			],
			'status'                       => [
				'type'        => 'String',
				'description' => __( 'For forms with Post fields, determines the status that the Post should be created with.', 'wp-graphql-gravity-forms' ),
			],
			'titleTemplate'                => [
				'type'        => 'String',
				'description' => __( 'Template to be used when creating the post title. Field variables (i.e. {Name:3} ) can be added to the template to insert user submitted values into the post title. Only applicable when `hasTitleTemplate` is `true`.', 'wp-graphql-gravity-forms' ),
			],
			'shouldUseCurrentUserAsAuthor' => [
				'type'        => 'Boolean',
				'description' => __( 'For forms with Post fields, this determines if the post should be created using the current logged in user as the author.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
