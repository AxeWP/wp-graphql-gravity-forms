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
use WPGraphQL\GF\Interfaces\TypeWithConnections;
use WPGraphQL\GF\Type\Enum\SubmissionConfirmationTypeEnum;
use WPGraphQL\GF\Type\WPObject\AbstractObject;

/**
 * Class - Submission Confirmation
 */
class SubmissionConfirmation extends AbstractObject implements TypeWithConnections {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'SubmissionConfirmation';

	/**
	 * {@inheritDoc}
	 */
	public static function get_type_config(): array {
		$config = parent::get_type_config();

		$config['connections'] = self::get_connections();

		return $config;
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_connections(): array {
		return [
			'page' => [
				'toType'   => 'Page',
				'oneToOne' => true,
				'resolve'  => static function ( $source, array $args, AppContext $context, ResolveInfo $info ) {
					$page_id = url_to_postid( $source['url'] ); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.url_to_postid_url_to_postid

					$resolver = new PostObjectConnectionResolver( $source, $args, $context, $info, 'page' );

					$resolver->set_query_arg( 'p', $page_id );

					return $resolver->one_to_one()->get_connection();
				},
			],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'The Confirmation object returned on submission. Null if the submission was not successful.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
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
				'resolve'     => static function ( $source ) {
					$post_id = url_to_postid( $source['url'] ); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.url_to_postid_url_to_postid

					return ! empty( $post_id ) ? $post_id : null;
				},
			],
			'queryString' => [
				'type'        => 'String',
				'description' => __( 'Contains the query string to be appended to the redirection url. Only applicable when type is set to `REDIRECT` or `PAGE` .', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $source ) {
					$parts = wp_parse_url( $source['url'] );

					return false !== $parts && ! empty( $parts['query'] ) ? $parts['query'] : null;
				},
			],
		];
	}
}
