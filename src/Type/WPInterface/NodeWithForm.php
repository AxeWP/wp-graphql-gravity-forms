<?php
/**
 * Interface - Node with Gravity Forms form
 *
 * @package WPGraphQL\GF\Type\Interface
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Type\WPInterface;

use WPGraphQL\AppContext;
use WPGraphQL\GF\Data\Factory;
use WPGraphQL\GF\Type\WPInterface\AbstractInterface;
use WPGraphQL\GF\Type\WPObject\Form\Form;

/**
 * Class - NodeWithForm
 */
class NodeWithForm extends AbstractInterface {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'NodeWithForm';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'A node that can have a Gravity Forms form assigned to it.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'formDatabaseId' => [
				'type'        => 'Int',
				'description' => __( 'The database identifier of the form of the node.', 'wp-graphql-gravity-forms' ),
			],
			'formId'         => [
				'type'        => 'ID',
				'description' => __( 'The globally unique identifier of the form of the node.', 'wp-graphql-gravity-forms' ),
			],
			'form'           => [
				'type'        => Form::$type,
				'description' => __( 'The form object of the node.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $source, array $args, AppContext $context ) {
					if ( empty( $source->formDatabaseId ) ) {
						return null;
					}
					return Factory::resolve_form( $source->formDatabaseId, $context );
				},
			],
		];
	}
}
