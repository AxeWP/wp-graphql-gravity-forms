<?php
/**
 * GraphQL Input Type - UpdateEntryMetaInput
 * Input fields for entry meta.
 *
 * @package WPGraphQL\GF\Type\Input
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Type\Input;

use WPGraphQL\GF\Type\Enum\EntryStatusEnum;

/**
 * Class - UpdateEntryMetaInput
 */
class UpdateEntryMetaInput extends AbstractInput {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'UpdateEntryMetaInput';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Entry meta input fields for updating Gravity Forms entries.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
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
			'isRead'         => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the entry has been read.', 'wp-graphql-gravity-forms' ),
			],
			'isStarred'      => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the entry has been starred (i.e marked with a star).', 'wp-graphql-gravity-forms' ),
			],
			'sourceUrl'      => [
				'type'        => 'String',
				'description' => __( 'Used to overwrite the sourceUrl the form was submitted from.', 'wp-graphql-gravity-forms' ),
			],
			'status'         => [
				'type'        => EntryStatusEnum::$type,
				'description' => __( 'The current status of the entry.', 'wp-graphql-gravity-forms' ),
			],
			'userAgent'      => [
				'type'        => 'String',
				'description' => __( 'The name and version of both the browser and operating system from which the entry was submitted.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
