<?php
/**
 * Form Model class
 *
 * @package \WPGraphQL\GF\Model
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Model;

use GraphQLRelay\Relay;
use WPGraphQL\GF\Type\WPObject\Form\Form as GraphQLForm;
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
				'button'                     => fn() : ?array => isset( $this->data['button'] ) ? $this->data['button'] : null,
				'confirmations'              => function() : ?array {
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
				'cssClass'                   => fn() : ?string => ! empty( $this->data['cssClass'] ) ? $this->data['cssClass'] : null,
				'customRequiredIndicator'    => fn() : ?string => ! empty( $this->data['customRequiredIndicator'] ) ? $this->data['customRequiredIndicator'] : null,
				'databaseId'                 => fn() : int => (int) $this->data['id'],
				'dateCreated'                => fn() : ?string => $this->data['date_created'] ?? null,
				'description'                => fn() : ?string => $this->data['description'] ?? null,
				'descriptionPlacement'       => fn() : ?string => $this->data['descriptionPlacement'] ?? null,
				'enableAnimation'            => fn() : bool => $this->data['enableAnimation'] ?? false,
				'enableHoneypot'             => fn() : bool => $this->data['enableHoneypot'] ?? false,
				'firstPageCssClass'          => fn() : ?string => $this->data['firstPageCssClass'] ?? null,
				// @todo switch to model.
				'formFields'                 => function() : ?array {
					$return = ! empty( $this->data['fields'] ) ? $this->data['fields'] : null;
					return $return;
				},
				'id'                         => fn() : string => Relay::toGlobalId( GraphQLForm::$type, $this->data['id'] ),
				'isActive'                   => fn() : bool => $this->data['is_active'] ?? true,
				'isTrash'                    => fn() : bool => $this->data['is_trash'] ?? false,
				'labelPlacement'             => fn() : ?string => $this->data['labelPlacement'] ?? null,
				'lastPageButton'             => fn() : ?array => ! empty( $this->data['lastPageButton'] ) ? $this->data['lastPageButton'] : null,
				'limitEntries'               => fn() : bool => $this->data['limitEntries'] ?? false,
				'limitEntriesCount'          => fn() : ?int => isset( $this->data['limitEntriesCount'] ) ? (int) $this->data['limitEntriesCount'] : null,
				'limitEntriesMessage'        => fn() : ?string => $this->data['limitEntriesMessage'] ?? null,
				'limitEntriesPeriod'         => fn() : ?string => $this->data['limitEntriesPeriod'] ?? null,
				'markupVersion'              => fn() : ?string => $this->data['markupVersion'] ?? null,
				'notifications'              => fn() : ?array => ! empty( $this->data['notifications'] ) ? $this->data['notifications'] : null,
				'nextFieldId'                => fn() : ?int => isset( $this->data['nextFieldId'] ) ? (int) $this->data['nextFieldId'] : null,
				'pagination'                 => function() : ?array {
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
				'postAuthor'                 => fn() : ?int => isset( $this->data['postAuthor'] ) ? (int) $this->data['postAuthor'] : null,
				'postCategory'               => fn() : ?int => isset( $this->data['postCategory'] ) ? (int) $this->data['postCategory'] : null,
				'postContentTemplate'        => fn() : ?string => $this->data['postContentTemplate'] ?? null,
				'postContentTemplateEnabled' => fn() : bool => $this->data['postContentTemplateEnabled'] ?? false,
				'postFormat'                 => fn() : ?string => $this->data['postFormat'] ?? null,
				'postStatus'                 => fn() : ?string => $this->data['postStatus'] ?? null,
				'postTitleTemplate'          => fn() : ?string => $this->data['postTitleTemplate'] ?? null,
				'postTitleTemplateEnabled'   => fn() : bool => $this->data['postContentTemplateEnabled'] ?? false,
				'quizSettings'               => fn() : ?array => ! empty( $this->data['gravityformsquiz'] ) ? $this->data['gravityformsquiz'] : null,
				'requiredIndicator'          => fn() : ?string => $this->data['requiredIndicator'] ?? null,
				'requireLogin'               => fn() : bool => $this->data['requireLogin'] ?? false,
				'requireLoginMessage'        => fn() : ?string => ! empty( $this->data['requireLoginMessage'] ) ? $this->data['requireLoginMessage'] : null,
				'save'                       => fn() : ?array => ! empty( $this->data['save'] ) ? $this->data['save'] : null,
				'scheduleEnd'                => fn() : ?string => ! empty( $this->data['scheduleEnd'] ) ? $this->data['scheduleEnd'] : null,
				'scheduleEndAmpm'            => fn() : ?string => ! empty( $this->data['scheduleEndAmpm'] ) ? $this->data['scheduleEndAmpm'] : null,
				'scheduleEndHour'            => fn() : ?int => isset( $this->data['scheduleEndHour'] ) ? (int) $this->data['scheduleEndHour'] : null,
				'scheduleEndMinute'          => fn() : ?int => isset( $this->data['scheduleEndMinute'] ) ? (int) $this->data['scheduleEndMinute'] : null,
				'scheduleForm'               => fn() : bool => $this->data['scheduleForm'] ?? false,
				'scheduleMessage'            => fn() : ?string => ! empty( $this->data['scheduleMessage'] ) ? $this->data['scheduleMessage'] : null,
				'schedulePendingMessage'     => fn() : ?string => ! empty( $this->data['schedulePendingMessage'] ) ? $this->data['schedulePendingMessage'] : null,
				'scheduleStart'              => fn() : ?string => ! empty( $this->data['scheduleStart'] ) ? $this->data['scheduleStart'] : null,
				'scheduleStartAmpm'          => fn() : ?string => ! empty( $this->data['scheduleStartAmpm'] ) ? $this->data['scheduleStartAmpm'] : null,
				'scheduleStartHour'          => fn() : ?int => isset( $this->data['scheduleStartHour'] ) ? (int) $this->data['scheduleStartHour'] : null,
				'scheduleStartMinute'        => fn() : ?int => isset( $this->data['scheduleStartMinute'] ) ? (int) $this->data['scheduleStartMinute'] : null,
				'subLabelPlacement'          => fn() : ?string => ! empty( $this->data['subLabelPlacement'] ) ? $this->data['subLabelPlacement'] : null,
				'title'                      => fn() : ?string => ! empty( $this->data['title'] ) ? $this->data['title'] : null,
				'useCurrentUserAsAuthor'     => fn() : bool => $this->data['useCurrentUserAsAuthor'] ?? false,
				'validationSummary'          => fn() : bool => $this->data['validationSummary'] ?? false,
				'version'                    => fn() : ?string => ! empty( $this->data['version'] ) ? $this->data['version'] : null,
			];
		}
	}
}
