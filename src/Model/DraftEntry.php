<?php
/**
 * DraftEntry Model class
 *
 * @package \WPGraphQL\GF\Model
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Model;

use GraphQLRelay\Relay;
use WPGraphQL\GF\Type\WPObject\Entry\Entry as GraphQLEntry;
use WPGraphQL\GF\Type\WPObject\Form\Form;
use WPGraphQL\Model\Model;

/**
 * Class - DraftEntry
 */
class DraftEntry extends Model {
	/**
	 * Stores the incoming DraftEntry to be modeled.
	 *
	 * @var array $data
	 */
	protected $data;

	/**
	 * Stores the decoded draft entry Submission.
	 *
	 * @var array $submission
	 */
	protected $submission;

	/**
	 * Stores the draft entry resume token/
	 *
	 * @var string $resume_token
	 */
	protected $resume_token;

	/**
	 * DraftEntry constructor.
	 *
	 * @param array  $entry The incoming DraftEntry to be modeled.
	 * @param string $resume_token the resume token to use.
	 *
	 * @throws \Exception .
	 */
	public function __construct( $entry, string $resume_token ) {
		$this->data         = $entry;
		$this->submission   = json_decode( $this->data['submission'], true );
		$this->resume_token = $resume_token;

		parent::__construct();
	}

	/**
	 * {@inheritDoc}
	 */
	protected function is_private() : bool {
		if ( ! is_user_logged_in() ) {
			return true;
		}

		if ( current_user_can( 'gravityforms_view_entries' ) || current_user_can( 'gform_full_access' ) ) {
			return false;
		}

		if ( get_current_user_id() === $this->data['createdById'] ) {
			return false;
		}

		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function init() : void {
		if ( empty( $this->fields ) ) {
			$this->fields = [
				'createdById'    => fn() : ?int => ! empty( $this->submission['partial_entry']['created_by'] ) ? (int) $this->submission['partial_entry']['created_by'] : null,
				'currency'       => fn() : ?int => ! empty( $this->submission['partial_entry']['currency'] ) ? (int) $this->submission['partial_entry']['currency'] : null,
				'dateCreated'    => fn() : ?string => ! empty( $this->data['date_created'] ) ? get_date_from_gmt( $this->data['date_created'] ) : null,
				'dateCreatedGmt' => fn() : ?string => ! empty( $this->data['date_created'] ) ? $this->data['date_created'] : null,
				'dateUpdated'    => fn() : ?string => ! empty( $this->submission['partial_entry']['date_updated'] ) ? get_date_from_gmt( $this->submission['partial_entry']['date_updated'] ) : null,
				'dateUpdatedGmt' => fn() : ?string => ! empty( $this->submission['partial_entry']['date_updated'] ) ? $this->submission['partial_entry']['date_updated'] : null,

				'entry'          => fn() : array => $this->submission['partial_entry'],
				'formDatabaseId' => fn() : ?int => ! empty( $this->data['form_id'] ) ? (int) $this->data['form_id'] : null,
				'formId'         => fn() => ! empty( $this->data['form_id'] ) ? Relay::toGlobalId( Form::$type, $this->data['form_id'] ) : null,
				'id'             => fn() : string => Relay::toGlobalId( GraphQLEntry::$type, $this->resume_token ),
				'ip'             => fn() : ?string => ! empty( $this->submission['partial_entry']['ip'] ) ? $this->submission['partial_entry']['ip'] : null,
				'isDraft'        => fn() : bool => true,
				'postId'         => fn() : ?int => ! empty( $this->data['post_id'] ) ? (int) $this->data['post_id'] : null,
				'resumeToken'    => fn() : string => $this->resume_token,
				'sourceUrl'      => fn() : ?string => ! empty( $this->submission['partial_entry']['source_url'] ) ? $this->submission['partial_entry']['source_url'] : null,
				'userAgent'      => fn() : ?string => ! empty( $this->data['user_agent'] ) ? $this->submission['partial_entry']['user_agent'] : null,
			];
		}
	}
}
