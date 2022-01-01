<?php
/**
 * GraphQL Input Type - SubmitFormMetaInput
 * Input fields for submit formMeta.
 *
 * @package WPGraphQL\GF\Type\Input
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Type\Input;

use WPGraphQL\GF\Type\Enum\EntryStatusEnum;

/**
 * Class - SubmitFormMetaInput
 */
class SubmitFormMetaInput extends AbstractInput {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'SubmitFormMetaInput';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Entry meta input fields for submitting Gravity Forms forms.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'createdById'    => [
				'type'        => 'Int',
				'description' => __( 'ID of the user that submitted of the form if a logged in user submitted the form.', 'wp-graphql-gravity-forms' ),
			],
			'dateCreatedGmt' => [
				'type'        => 'String',
				'description' => __( 'The UTC date the entry was created, in `Y-m-d H:i:s` format.', 'wp-graphql-gravity-forms' ),
			],
			'ip'             => [
				'type'        => 'String',
				'description' => __( 'Client IP of user who submitted the form.', 'wp-graphql-gravity-forms' ),
			],
			'sourceUrl'      => [
				'type'        => 'String',
				'description' => __( 'Used to overwrite the sourceUrl the form was submitted from.', 'wp-graphql-gravity-forms' ),
			],
			'userAgent'      => [
				'type'        => 'String',
				'description' => __( 'The name and version of both the browser and operating system from which the entry was submitted.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
