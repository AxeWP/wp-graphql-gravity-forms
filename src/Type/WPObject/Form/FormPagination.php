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

use WPGraphQL\GF\Type\WPObject\AbstractObject;

use WPGraphQL\GF\Type\Enum\PageProgressStyleEnum;
use WPGraphQL\GF\Type\Enum\PageProgressTypeEnum;

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
	public static function get_description() : string {
		return __( 'Gravity Forms form pagination data.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
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
		];
	}
}
