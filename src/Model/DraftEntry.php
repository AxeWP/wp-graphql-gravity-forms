<?php
/**
 * DraftEntry Model class
 *
 * @package \WPGraphQL\GF\Model
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Model;

use GraphQLRelay\Relay;
use WPGraphQL\GF\Data\Loader\DraftEntriesLoader;
use WPGraphQL\GF\Data\Loader\FormsLoader;
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
	protected function is_private(): bool {
		$can_view = true;

		/**
		 * Filter to control whether the user should be allowed to view draft entries.
		 *
		 * @since 0.10.0
		 *
		 * @param bool $can_view_entries Whether the current user should be allowed to view form entries.
		 * @param int  $form_ids The specific form ID being queried.
		 * @param string $resume_token The specific resume token being queried.
		 * @param array $draft_entry the current draft entry.
		 */
		$can_view = apply_filters( 'graphql_gf_can_view_draft_entries', $can_view, $this->data['form_id'], $this->resume_token, $this->data );

		return ! $can_view;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function init(): void {
		if ( empty( $this->fields ) ) {
			$this->fields = [
				// Interface fields.
				'createdByDatabaseId' => fn (): ?int => ! empty( $this->submission['partial_entry']['created_by'] ) ? (int) $this->submission['partial_entry']['created_by'] : null,
				'createdById'         => fn (): ?string => ! empty( $this->data['created_by'] ) ? Relay::toGlobalId( 'user', $this->data['created_by'] ) : null,
				'dateCreated'         => fn (): ?string => ! empty( $this->data['date_created'] ) ? get_date_from_gmt( $this->data['date_created'] ) : null,
				'dateCreatedGmt'      => fn (): ?string => ! empty( $this->data['date_created'] ) ? $this->data['date_created'] : null,
				'dateUpdated'         => fn (): ?string => ! empty( $this->submission['partial_entry']['date_updated'] ) ? get_date_from_gmt( $this->submission['partial_entry']['date_updated'] ) : null,
				'dateUpdatedGmt'      => fn (): ?string => ! empty( $this->submission['partial_entry']['date_updated'] ) ? $this->submission['partial_entry']['date_updated'] : null,
				'entry'               => fn (): array => $this->submission['partial_entry'],
				'entryValues'         => fn (): ?array => array_filter( $this->submission['partial_entry'], static fn ( $key ) => is_numeric( $key ), ARRAY_FILTER_USE_KEY ) ?: null,
				'formId'              => fn () => ! empty( $this->data['form_id'] ) ? Relay::toGlobalId( FormsLoader::$name, $this->data['form_id'] ) : null,
				'formDatabaseId'      => fn (): ?int => ! empty( $this->data['form_id'] ) ? (int) $this->data['form_id'] : null,
				'id'                  => fn (): string => Relay::toGlobalId( DraftEntriesLoader::$name, $this->resume_token ),
				'ip'                  => fn (): ?string => ! empty( $this->submission['partial_entry']['ip'] ) ? $this->submission['partial_entry']['ip'] : null,
				'isDraft'             => fn (): bool => ! empty( $this->resume_token ),
				'isSubmitted'         => fn (): bool => ! empty( $this->submission['partial_entry']['id'] ),
				'sourceUrl'           => fn (): ?string => ! empty( $this->submission['partial_entry']['source_url'] ) ? $this->submission['partial_entry']['source_url'] : null,
				'userAgent'           => fn (): ?string => ! empty( $this->submission['partial_entry']['user_agent'] ) ? $this->submission['partial_entry']['user_agent'] : null,

				// Fields specific to the model.
				'currency'            => fn (): ?int => ! empty( $this->submission['partial_entry']['currency'] ) ? (int) $this->submission['partial_entry']['currency'] : null,
				'resumeToken'         => fn (): string => $this->resume_token,
			];
		}
	}
}
