<?php
/**
 * Entry Model class
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
 * Class - Entry
 */
class Entry extends Model {
	/**
	 * Stores the incoming Entry to be modeled.
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
				'createdById'     => fn() : ?int => ! empty( $this->data['created_by'] ) ? (int) $this->data['created_by'] : null,
				'databaseId'      => fn() : ?int => ! empty( $this->data['id'] ) ? (int) $this->data['id'] : null,
				'dateCreatedUTC'  => fn() : ?string => ! empty( $this->data['date_created'] ) ? $this->data['date_created'] : null,
				'dateUpdatedUTC'  => fn() : ?string => ! empty( $this->data['date_updated'] ) ? $this->data['date_updated'] : null,
				'entry'           => fn() : array => $this->data,
				'entryValues'     => fn() : ?array => array_filter( $this->data, fn( $key ) => is_numeric( $key ), ARRAY_FILTER_USE_KEY ) ?: null,
				'formDatabaseId'  => fn() : ?int => ! empty( $this->data['form_id'] ) ? (int) $this->data['form_id'] : null,
				'formId'          => fn() => ! empty( $this->data['form_id'] ) ? Relay::toGlobalId( Form::$type, $this->data['form_id'] ) : null,
				'id'              => fn() : string => Relay::toGlobalId( GraphQLEntry::$type, $this->data['resumeToken'] ?? (string) $this->data['id'] ),
				'ip'              => fn() : ?string => ! empty( $this->data['ip'] ) ? $this->data['ip'] : null,
				'isDraft'         => fn() : bool => empty( $this->data['id'] ),
				'isFulfilled'     => fn() : bool => ! empty( $this->data['is_fulfilled'] ),
				'isStarred'       => fn() : bool => ! empty( $this->data['is_starred'] ),
				'isRead'          => fn() : bool => ! empty( $this->data['is_read'] ),
				'paymentAmount'   => fn() : ?float => isset( $this->data['payment_amount'] ) ? (float) $this->data['payment_amount'] : null,
				'paymentDate'     => fn() : ?string => ! empty( $this->data['payment_date'] ) ? $this->data['payment_date'] : null,
				'paymentMethod'   => fn() : ?string => ! empty( $this->data['payment_method'] ) ? $this->data['payment_method'] : null,
				'paymentStatus'   => fn() : ?string => ! empty( $this->data['payment_status'] ) ? $this->data['payment_status'] : null,
				'postId'          => fn() : ?int => ! empty( $this->data['post_id'] ) ? (int) $this->data['post_id'] : null,
				'resumeToken'     => fn() : ?string => ! empty( $this->data['resumeToken'] ) ? $this->data['resumeToken'] : null,
				'sourceUrl'       => fn() : ?string => ! empty( $this->data['source_url'] ) ? $this->data['source_url'] : null,
				'status'          => fn() : ?string => ! empty( $this->data['status'] ) ? $this->data['status'] : null,
				'transactionId'   => fn() : ?string => ! empty( $this->data['transaction_id'] ) ? $this->data['transaction_id'] : null,
				'transactionType' => fn() : ?string => ! empty( $this->data['transaction_type'] ) ? $this->data['transaction_type'] : null,
				'userAgent'       => fn() : ?string => ! empty( $this->data['user_agent'] ) ? $this->data['user_agent'] : null,
			];
		}
	}
}
