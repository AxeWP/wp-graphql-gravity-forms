<?php
/**
 * GraphQL Object Type - Gravity Forms Form confirmation
 *
 * @see https://docs.gravityforms.com/confirmation/
 *
 * @package WPGraphQL\GF\Type\WPObject\Form
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Type\WPObject\Form;

use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQL\Data\Connection\PostObjectConnectionResolver;
use WPGraphQL\GF\Interfaces\TypeWithConnections;
use WPGraphQL\GF\Type\Enum\FormConfirmationTypeEnum;
use WPGraphQL\GF\Type\WPObject\AbstractObject;
use WPGraphQL\GF\Type\WPObject\ConditionalLogic\ConditionalLogic;

/**
 * Class - FormConfirmation
 */
class FormConfirmation extends AbstractObject implements TypeWithConnections {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormConfirmation';

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
					$page_id = $source['pageId'];

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
		return __( 'Properties for all the email notifications which exist for a form.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'conditionalLogic' => [
				'type'        => ConditionalLogic::$type,
				'description' => __( 'Controls which form confirmation message should be displayed.', 'wp-graphql-gravity-forms' ),
			],
			'id'               => [
				'type'        => 'String',
				'description' => __( 'ID.', 'wp-graphql-gravity-forms' ),
			],
			'isActive'         => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the confirmation is active or inactive. The default confirmation is always active.', 'wp-graphql-gravity-forms' ),
			],
			'isAutoformatted'  => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the confirmation message should be formatted so that paragraphs are automatically added for new lines.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => empty( $source['disableAutoformat'] ),
			],
			'isDefault'        => [
				'type'        => 'Boolean',
				'description' => __( 'Whether this is the default confirmation.', 'wp-graphql-gravity-forms' ),
			],
			'message'          => [
				'type'        => 'String',
				'description' => __( 'Contains the confirmation message that will be displayed. Only applicable when type is set to "MESSAGE".', 'wp-graphql-gravity-forms' ),
			],
			'name'             => [
				'type'        => 'String',
				'description' => __( 'The confirmation name.', 'wp-graphql-gravity-forms' ),
			],
			'pageId'           => [
				'type'        => 'Int',
				'description' => __( 'Contains the Id of the WordPress page that the browser will be redirected to. Only applicable when type is set to `PAGE`.', 'wp-graphql-gravity-forms' ),
			],
			'queryString'      => [
				'type'        => 'String',
				'description' => __( 'Contains the query string to be appended to the redirection url. Only applicable when type is set to `REDIRECT`.', 'wp-graphql-gravity-forms' ),
			],
			'type'             => [
				'type'        => FormConfirmationTypeEnum::$type,
				'description' => __( 'Determines the type of confirmation to be used.', 'wp-graphql-gravity-forms' ),
			],
			'url'              => [
				'type'        => 'String',
				'description' => __( 'Contains the URL that the browser will be redirected to. Only applicable when type is set to `REDIRECT`.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
