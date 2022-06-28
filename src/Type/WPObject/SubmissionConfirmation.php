<?php
/**
 * GraphQL Object Type - Gravity Forms Submission Confirmation
 *
 * @see https://docs.gravityforms.com/submitting-forms-with-the-gfapi/#h-returns
 *
 * @package WPGraphQL\GF\Type\WPObject
 * @since   0.11.1
 */

namespace WPGraphQL\GF\Type\WPObject;

use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQL\Data\Connection\PostObjectConnectionResolver;
use WPGraphQL\GF\Type\WPObject\AbstractObject;
use WPGraphQL\GF\Type\Enum\SubmissionConfirmationTypeEnum;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - Submission Confirmation
 */
class SubmissionConfirmation extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'SubmissionConfirmation';

	/**
	 * {@inheritDoc}
	 */
	public static function register( TypeRegistry $type_registry = null ) : void {
		register_graphql_object_type(
			static::$type,
			[
				'connections'     => [
					'page' => [
						'toType'   => 'Page',
						'oneToOne' => true,
						'resolve'  => static function( $source, array $args, AppContext $context, ResolveInfo $info ) {
							$page_id = url_to_postid( $source['url'] );

							$resolver = new PostObjectConnectionResolver( $source, $args, $context, $info, 'page' );

							$resolver->set_query_arg( 'p', $page_id );

							return $resolver->one_to_one()->get_connection();
						},
					],
				],
				'description'     => static::get_description(),
				'fields'          => static::get_fields(),
				'eagerlyLoadType' => static::$should_load_eagerly,
			]
		);
	}
	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'The Confirmation object returned on submission. Null if the submission was not successful.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'message'     => [
				'type'        => 'String',
				'description' => __( 'Contains the confirmation message HTML to display. Only applicable when type is set to `MESSAGE`.', 'wp-graphql-gravity-forms' ),
			],
			'type'        => [
				'type'        => SubmissionConfirmationTypeEnum::$type,
				'description' => __( 'Determines the type of confirmation to be used.', 'wp-graphql-gravity-forms' ),
			],
			'url'         => [
				'type'        => 'String',
				'description' => __( 'The URL the submission should redirect to. Only applicable when type is set to `REDIRECT`.', 'wp-graphql-gravity-forms' ),
			],
			'pageId'      => [
				'type'        => 'Int',
				'description' => __( 'Contains the Id of the WordPress page that the browser will be redirected to. Only applicable when type is set to `PAGE`.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function( $source ) {
					$post_id = url_to_postid( $source['url'] );

					return ! empty( $post_id ) ? $post_id : null;
				},
			],
			'queryString' => [
				'type'        => 'String',
				'description' => __( 'Contains the query string to be appended to the redirection url. Only applicable when type is set to `REDIRECT` or `PAGE` .', 'wp-graphql-gravity-forms' ),
				'resolve'     => function( $source ) {
					$parts = wp_parse_url( $source['url'] );

					return false !== $parts && ! empty( $parts['query'] ) ? $parts['query'] : null;
				},
			],
		];
	}
}
