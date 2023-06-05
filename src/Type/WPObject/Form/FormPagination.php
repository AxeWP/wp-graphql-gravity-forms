<?php
/**
 * GraphQL Object Type - Gravity Forms form pagination
 *
 * @see https://docs.gravityforms.com/page-break/
 *
 * @package WPGraphQL\GF\Type\WPObject\Form
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Type\WPObject\Form;

use WPGraphQL\GF\Type\Enum\FormPageProgressStyleEnum;
use WPGraphQL\GF\Type\Enum\FormPageProgressTypeEnum;
use WPGraphQL\GF\Type\WPObject\AbstractObject;
use WPGraphQL\GF\Type\WPObject\Button\FormLastPageButton;

/**
 * Class - FormPagination
 */
class FormPagination extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormPagination';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Gravity Forms form pagination data.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'backgroundColor'              => [
				'type'        => 'String',
				'description' => __( 'Progress bar background color. Can be any CSS color value. Only applies when `style` is set to "CUSTOM".', 'wp-graphql-gravity-forms' ),
			],
			'color'                        => [
				'type'        => 'String',
				'description' => __( 'Progress bar text color. Can be any CSS color value. Only applies when `style` is set to "CUSTOM".', 'wp-graphql-gravity-forms' ),
			],
			'hasProgressbarOnConfirmation' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the confirmation bar should be displayed with the confirmation text.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source) => ! empty( $source['displayProgressbarOnConfirmation'] ),
			],
			'lastPageButton'               => [
				'type'        => FormLastPageButton::$type,
				'description' => __( 'Last page button data.', 'wp-graphql-gravity-forms' ),
			],
			'pageNames'                    => [
				'type'        => [ 'list_of' => 'String' ],
				'description' => __( 'Names of the form\'s pages.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source) => ! empty( $source['pages'] ) ? $source['pages'] : null,
			],
			'progressbarCompletionText'    => [
				'type'        => 'String',
				'description' => __( 'The confirmation text to display once the end of the progress bar has been reached. Only applies when `hasProgressbarOnConfirmation` is set to true.', 'wp-graphql-gravity-forms' ),
			],
			'style'                        => [
				'type'        => FormPageProgressStyleEnum::$type,
				'description' => __( 'Style of progress bar.', 'wp-graphql-gravity-forms' ),
			],
			'type'                         => [
				'type'        => FormPageProgressTypeEnum::$type,
				'description' => __( 'Type of progress indicator.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
