<?php
/**
 * GraphQL Object Type - Gravity Forms Submission Confirmation
 *
 * @see https://docs.gravityforms.com/submitting-forms-with-the-gfapi/#h-returns
 *
 * @package WPGraphQL\GF\Type\WPObject
 * @since   @todo
 */

namespace WPGraphQL\GF\Type\WPObject;

use WPGraphQL\GF\Type\WPObject\AbstractObject;
use WPGraphQL\GF\Type\Enum\FormConfirmationTypeEnum;

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
	public static function get_description() : string {
		return __( 'The Confirmation object returned on submission.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'message' => [
				'type'        => 'String',
				'description' => __( 'Contains the confirmation message that will be displayed. Only applicable when type is set to `MESSAGE`.', 'wp-graphql-gravity-forms' ),
			],
			'type'    => [
				'type'        => FormConfirmationTypeEnum::$type,
				'description' => __( 'Determines the type of confirmation to be used.', 'wp-graphql-gravity-forms' ),
			],
			'url'     => [
				'type'        => 'String',
				'description' => __( 'The URL the submission should redirect to. Only applicable when type is set to `REDIRECT`.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
