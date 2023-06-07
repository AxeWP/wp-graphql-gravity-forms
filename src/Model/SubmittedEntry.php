<?php
/**
 * Entry Model class
 *
 * @package \WPGraphQL\GF\Model
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Model;

use GraphQLRelay\Relay;
use WPGraphQL\GF\Data\Loader\EntriesLoader;
use WPGraphQL\GF\Data\Loader\FormsLoader;
use WPGraphQL\Model\Model;

/**
 * Class - Entry
 */
class SubmittedEntry extends Model {
	/**
	 * Stores the incoming Gravity Forms entry to be modeled.
	 *
	 * @var array $data;
	 */
	protected $data;

	/**
	 * Entry constructor.
	 *
	 * @param array $entry The incoming entry to be modeled.
	 *
	 * @throws \Exception .
	 */
	public function __construct( $entry ) {
		$this->data = $entry;

		parent::__construct();
	}

	/**
	 * {@inheritDoc}
	 */
	protected function is_private(): bool {
		$can_view = false;

		if (
			current_user_can( 'gravityforms_view_entries' ) ||
			current_user_can( 'gform_full_access' ) ||
			get_current_user_id() === $this->data['created_by'] ) {
			$can_view = true;
		}

		/**
		 * Filter to control whether the user should be allowed to view entries.
		 *
		 * @since 0.10.0
		 *
		 * @param bool      $can_view_entries Whether the current user should be allowed to view form entries.
		 * @param int       $form_id The specific form ID being queried.
		 * @param int       $entry_id The specific entry ID being queried.
		 * @param array     $entry the current entry.
		 */
		$can_view = apply_filters( 'graphql_gf_can_view_entries', $can_view, $this->data['form_id'], (int) $this->data['id'], $this->data );

		return ! $can_view;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function init(): void {
		if ( empty( $this->fields ) ) {
			$this->fields = [
				// Interface fields.
				'createdByDatabaseId' => fn (): ?int => ! empty( $this->data['created_by'] ) ? (int) $this->data['created_by'] : null,
				'createdById'         => fn (): ?string => ! empty( $this->data['created_by'] ) ? Relay::toGlobalId( 'user', $this->data['created_by'] ) : null,
				'dateCreated'         => fn (): ?string => ! empty( $this->data['date_created'] ) ? get_date_from_gmt( $this->data['date_created'] ) : null,
				'dateCreatedGmt'      => fn (): ?string => ! empty( $this->data['date_created'] ) ? $this->data['date_created'] : null,
				'dateUpdated'         => fn (): ?string => ! empty( $this->data['date_updated'] ) ? get_date_from_gmt( $this->data['date_updated'] ) : null,
				'dateUpdatedGmt'      => fn (): ?string => ! empty( $this->data['date_updated'] ) ? $this->data['date_updated'] : null,
				'entry'               => fn (): array => $this->data,
				'entryValues'         => fn (): ?array => array_filter( $this->data, static fn ( $key ) => is_numeric( $key ), ARRAY_FILTER_USE_KEY ) ?: null,
				'formDatabaseId'      => fn (): ?int => ! empty( $this->data['form_id'] ) ? (int) $this->data['form_id'] : null,
				'formId'              => fn () => ! empty( $this->data['form_id'] ) ? Relay::toGlobalId( FormsLoader::$name, $this->data['form_id'] ) : null,
				'id'                  => fn (): string => Relay::toGlobalId( EntriesLoader::$name, (string) $this->data['id'] ),
				'ip'                  => fn (): ?string => ! empty( $this->data['ip'] ) ? $this->data['ip'] : null,
				'isDraft'             => fn (): bool => ! empty( $this->data['resume_token'] ),
				'isSubmitted'         => fn (): bool => ! empty( $this->data['id'] ),
				'sourceUrl'           => fn (): ?string => ! empty( $this->data['source_url'] ) ? $this->data['source_url'] : null,
				'userAgent'           => fn (): ?string => ! empty( $this->data['user_agent'] ) ? $this->data['user_agent'] : null,

				// Fields specific to the model.
				'databaseId'          => fn (): ?int => ! empty( $this->data['id'] ) ? (int) $this->data['id'] : null,
				'isFulfilled'         => fn (): bool => ! empty( $this->data['is_fulfilled'] ),
				'isStarred'           => fn (): bool => ! empty( $this->data['is_starred'] ),
				'isRead'              => fn (): bool => ! empty( $this->data['is_read'] ),
				'paymentAmount'       => fn (): ?float => isset( $this->data['payment_amount'] ) ? (float) $this->data['payment_amount'] : null,
				'paymentDate'         => fn (): ?string => ! empty( $this->data['payment_date'] ) ? $this->data['payment_date'] : null,
				'paymentMethod'       => fn (): ?string => ! empty( $this->data['payment_method'] ) ? $this->data['payment_method'] : null,
				'paymentStatus'       => fn (): ?string => ! empty( $this->data['payment_status'] ) ? $this->data['payment_status'] : null,
				'postDatabaseId'      => fn (): ?int => ! empty( $this->data['post_id'] ) ? (int) $this->data['post_id'] : null,
				'status'              => fn (): ?string => ! empty( $this->data['status'] ) ? $this->data['status'] : null,
				'transactionId'       => fn (): ?string => ! empty( $this->data['transaction_id'] ) ? $this->data['transaction_id'] : null,
				'transactionType'     => fn (): ?string => ! empty( $this->data['transaction_type'] ) ? $this->data['transaction_type'] : null,
			];
		}
	}
}
