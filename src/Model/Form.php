<?php
/**
 * Form Model class
 *
 * @package \WPGraphQL\GF\Model
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Model;

use GraphQLRelay\Relay;
use WPGraphQL\GF\Data\Loader\FormsLoader;
use WPGraphQL\Model\Model;

/**
 * Class - Form
 */
class Form extends Model {
	/**
	 * Stores the incoming form to be modeled.
	 *
	 * @var array $data;
	 */
	protected $data;

	/**
	 * Form constructor.
	 *
	 * @param array $form The incoming form to be modeled.
	 *
	 * @throws \Exception .
	 */
	public function __construct( $form ) {
		$this->data = $form;
		parent::__construct();
	}

	/**
	 * {@inheritDoc}
	 */
	protected function is_private() : bool {
		if ( ! isset( $this->data['requireLogin'] ) || ! $this->data['requireLogin'] || is_user_logged_in() ) {
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
				'button'                       => fn() : ?array => isset( $this->data['button'] ) ? $this->data['button'] : null,
				'confirmations'                => function() : ?array {
					if ( empty( $this->data['confirmations'] ) ) {
						return null;
					}
					return array_map(
						function( $confirmation ) {
							$confirmation['pageId'] = $confirmation['pageId'] ?: null;
							return $confirmation;
						},
						$this->data['confirmations']
					);
				},
				'cssClass'                     => fn() : ?string => ! empty( $this->data['cssClass'] ) ? $this->data['cssClass'] : null,
				'customRequiredIndicator'      => fn() : ?string => ! empty( $this->data['customRequiredIndicator'] ) ? $this->data['customRequiredIndicator'] : null,
				'databaseId'                   => fn() : int => (int) $this->data['id'],
				'dateCreatedGmt'               => fn() : ?string => $this->data['date_created'] ?? null,
				'dateCreated'                  => fn() : ?string => ! empty( $this->data['date_created'] ) ? get_date_from_gmt( $this->data['date_created'] ) : null,
				'description'                  => fn() : ?string => $this->data['description'] ?? null,
				'descriptionPlacement'         => fn() : ?string => $this->data['descriptionPlacement'] ?? null,
				'entryLimits'                  => function() : array {
					return [
						'hasLimit'            => ! empty( $this->data['limitEntries'] ),
						'limitationPeriod'    => ! empty( $this->data['limitEntriesPeriod'] ) ? $this->data['limitEntriesPeriod'] : null,
						'limitReachedMessage' => ! empty( $this->data['limitEntriesMessage'] ) ? $this->data['limitEntriesMessage'] : null,
						'maxEntries'          => isset( $this->data['limitEntriesCount'] ) ? (int) $this->data['limitEntriesCount'] : null,
					];
				},
				'hasConditionalLogicAnimation' => fn() : bool => $this->data['enableAnimation'] ?? false,
				'hasHoneypot'                  => fn() : bool => $this->data['enableHoneypot'] ?? false,
				'firstPageCssClass'            => fn() : ?string => $this->data['firstPageCssClass'] ?? null,
				'formFields'                   => function() : ?array {
					$return = ! empty( $this->data['fields'] ) ? $this->data['fields'] : null;
					return $return;
				},
				'id'                           => fn() : string => Relay::toGlobalId( FormsLoader::$name, $this->data['id'] ),

				'isActive'                     => fn() : bool => $this->data['is_active'] ?? true,
				'isTrash'                      => fn() : bool => $this->data['is_trash'] ?? false,
				'labelPlacement'               => fn() : ?string => $this->data['labelPlacement'] ?? null,
				'lastPageButton'               => fn() : ?array => ! empty( $this->data['lastPageButton'] ) ? $this->data['lastPageButton'] : null,
				'login'                        => function() : array {
					return [
						'isLoginRequired'      => ! empty( $this->data['requireLogin'] ),
						'loginRequiredMessage' => ! empty( $this->data['requireLoginMessage'] ) ? $this->data['requireLoginMessage'] : null,
					];
				},
				'markupVersion'                => fn() : ?string => $this->data['markupVersion'] ?? null,
				'notifications'                => fn() : ?array => ! empty( $this->data['notifications'] ) ? $this->data['notifications'] : null,
				'nextFieldId'                  => fn() : ?int => isset( $this->data['nextFieldId'] ) ? (int) $this->data['nextFieldId'] : null,
				'pagination'                   => function() : ?array {
					if ( ! isset( $this->data['pagination'] ) ) {
						return null;
					}

					$pagination = $this->data['pagination'];

					if ( isset( $pagination['display_progressbar_on_confirmation'] ) ) {
						$pagination['displayProgressbarOnConfirmation'] = $pagination['display_progressbar_on_confirmation'];
						unset( $pagination['display_progressbar_on_confirmation'] );
					}

					if ( isset( $pagination['progressbar_completion_text'] ) ) {
						$pagination['progressbarCompletionText'] = $pagination['progressbar_completion_text'];
						unset( $pagination['progressbar_completion_text'] );
					}

					return $pagination;
				},
				'postCreation'                 => function() : array {
					return [
						'authorDatabaseId'             => isset( $this->data['postAuthor'] ) ? (int) $this->data['postAuthor'] : null,
						'authorId'                     => isset( $this->data['postAuthor'] ) ? Relay::toGlobalId( 'user', $this->data['postAuthor'] ) : null,
						'categoryDatabaseId'           => isset( $this->data['postCategory'] ) ? (int) $this->data['postCategory'] : null,
						'contentTemplate'              => ! empty( $this->data['postContentTemplate'] ) ? $this->data['postContentTemplate'] : null,
						'format'                       => $this->data['postFormat'] ?? '0',
						'hasContentTemplate'           => ! empty( $this->data['postContentTemplateEnabled'] ),
						'hasTitleTemplate'             => ! empty( $this->data['postTitleTemplateEnabled'] ),
						'status'                       => ! empty( $this->data['postStatus'] ) ? $this->data['postStatus'] : null,
						'titleTemplate'                => ! empty( $this->data['postTitleTemplate'] ) ? $this->data['postTitleTemplate'] : null,
						'shouldUseCurrentUserAsAuthor' => ! empty( $this->data['useCurrentUserAsAuthor'] ),
					];
				},
				'quiz'                         => fn() : ?array => ! empty( $this->data['gravityformsquiz'] ) ? $this->data['gravityformsquiz'] : null,
				'requiredIndicator'            => fn() : ?string => $this->data['requiredIndicator'] ?? null,
				'saveAndContinue'              => fn() : ?array => ! empty( $this->data['save'] ) ? $this->data['save'] : null,
				'scheduling'                   => function() : array {
					return [
						'closedMessage'  => ! empty( $this->data['scheduleMessage'] ) ? $this->data['scheduleMessage'] : null,
						'hasSchedule'    => ! empty( $this->data['scheduleForm'] ),
						'pendingMessage' => ! empty( $this->data['schedulePendingMessage'] ) ? $this->data['schedulePendingMessage'] : null,
						'endDetails'     => [
							'date'    => ! empty( $this->data['scheduleEnd'] ) ? get_date_from_gmt( $this->data['scheduleEnd'] ) : null,
							'dateGmt' => ! empty( $this->data['scheduleEnd'] ) ? $this->data['scheduleEnd'] : null,
							'amPm'    => ! empty( $this->data['scheduleEndAmpm'] ) ? $this->data['scheduleEndAmpm'] : null,
							'hour'    => isset( $this->data['scheduleEndHour'] ) ? (int) $this->data['scheduleEndHour'] : null,
							'minute'  => isset( $this->data['scheduleEndMinute'] ) ? (int) $this->data['scheduleEndMinute'] : null,
						],
						'startDetails'   => [
							'date'    => ! empty( $this->data['scheduleStart'] ) ? get_date_from_gmt( $this->data['scheduleStart'] ) : null,
							'dateGmt' => ! empty( $this->data['scheduleStart'] ) ? $this->data['scheduleStart'] : null,
							'amPm'    => ! empty( $this->data['scheduleStartAmpm'] ) ? $this->data['scheduleStartAmpm'] : null,
							'hour'    => isset( $this->data['scheduleStartHour'] ) ? (int) $this->data['scheduleStartHour'] : null,
							'minute'  => isset( $this->data['scheduleStartMinute'] ) ? (int) $this->data['scheduleStartMinute'] : null,
						],
					];
				},
				'subLabelPlacement'            => fn() : ?string => ! empty( $this->data['subLabelPlacement'] ) ? $this->data['subLabelPlacement'] : null,
				'title'                        => fn() : ?string => ! empty( $this->data['title'] ) ? $this->data['title'] : null,
				'hasValidationSummary'         => fn() : bool => ! empty( $this->data['validationSummary'] ),
				'version'                      => fn() : ?string => ! empty( $this->data['version'] ) ? $this->data['version'] : null,
			];
		}
	}
}
