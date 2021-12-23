<?php
/**
 * Factory for Gravity Forms draft entries.
 *
 * @package Tests\WPGraphQL\GF\Factory
 */

namespace Tests\WPGraphQL\GF\Factory;

use GFAPI;
use GFFormsModel;
use WP_UnitTest_Generator_Sequence;

/**
 * Class - Entry
 */
class DraftEntry extends \WP_UnitTest_Factory_For_Thing {

	/**
	 * Constructor
	 *
	 * @param object $factory .
	 */
	public function __construct( $factory = null ) {
		parent::__construct( $factory );
		$this->default_generation_definitions = [
			'source_url'   => '',
			'page_number'  => 1,
			'resume_token' => '',
		];
	}

	/**
	 * Creates an entry object.
	 *
	 * @param array $args entry arguments.
	 */
	public function create_object( $args ) : string {
		$form = GFAPI::get_form( $args['form_id'] );

		$entry = array_replace(
			[
				'id'           => null,
				'post_id'      => null,
				'date_created' => null,
				'date_updated' => null,
				'form_id'      => $args['form_id'],
				'ip'           => null,
				'source_url'   => $args['source_url'] ?? null,
				'user_agent'   => null,
				'created_by'   => $args['created_by'] ?? null,
				'curency'      => 'USD',
			],
			$args['entry'] ?? []
		);

		$resume_token = 0;
		do {
			$resume_token = GFFormsModel::save_draft_submission(
				$form,
				$entry,
				$args['field_values'] ?? null,
				$args['page_number'],
				[],
				GFFormsModel::$unique_ids[ $args['form_id'] ],
				$args['source_url'],
				$args['resume_token'],
			);
		} while ( ! is_string( $resume_token ) );

		return $resume_token;
	}

	/**
	 * Creates multiple draft entry objects.
	 *
	 * @param int   $count number to create.
	 * @param array $args  draft entry arguments.
	 * @param array $generation_definitions .
	 */
	public function create_many( $count, $args = [], $generation_definitions = null ) {
		$resume_tokens = [];
		for ( $n = 0; $n < $count; $n++ ) {
			$resume_tokens[] = $this->create( $args );
		}

		return $resume_tokens;
	}

	/**
	 * Updates a draft entry object.
	 *
	 * @param string $resume_token .
	 * @param array  $properties properties to update.
	 */
	public function update_object( $resume_token, $properties ) {
		$properties['resume_token'] = $resume_token;
		$this->create( $properties );
	}

	/**
	 * Gets the draft entry from an object id.
	 *
	 * @param string $resume_token .
	 * @return array
	 */
	public function get_object_by_id( $resume_token ) : array {
		return GFFormsModel::get_draft_submission_values( $resume_token );
	}

	/**
	 * Delete entries.
	 *
	 * @param array|string $resume_tokens .
	 */
	public function delete( $resume_tokens ) {
		if ( ! is_array( $resume_tokens ) ) {
			$resume_tokens = [ $resume_tokens ];
		}
		foreach ( $resume_tokens as $token ) {
			$success = GFFormsModel::delete_draft_submission( $token );
		}
		return $success;
	}
}
