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
	protected function is_private(): bool {
		if ( ! isset( $this->data['requireLogin'] ) || ! $this->data['requireLogin'] || is_user_logged_in() ) {
			return false;
		}

		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function init(): void {
		if ( empty( $this->fields ) ) {
			$this->fields = [
				'confirmations'                => function (): ?array {
					if ( empty( $this->data['confirmations'] ) ) {
						return null;
					}

					// Set necessary fields before returning.
					return array_map(
						static function ( $confirmation ) {
							// By default confirmations don't have the `isActive` array key.
							$confirmation['isActive']  = isset( $confirmation['isActive'] ) ? (bool) $confirmation['isActive'] : true;
							$confirmation['isDefault'] = ! empty( $confirmation['isDefault'] );

							// Set empty pageIds to null.
							$confirmation['pageId'] = $confirmation['pageId'] ?: null;
							return $confirmation;
						},
						$this->data['confirmations']
					);
				},
				'cssClass'                     => fn (): ?string => ! empty( $this->data['cssClass'] ) ? $this->data['cssClass'] : null,
				'customRequiredIndicator'      => fn (): ?string => ! empty( $this->data['customRequiredIndicator'] ) ? $this->data['customRequiredIndicator'] : null,
				'databaseId'                   => fn (): int => (int) $this->data['id'],
				'dateCreatedGmt'               => fn (): ?string => $this->data['date_created'] ?? null,
				'dateCreated'                  => fn (): ?string => ! empty( $this->data['date_created'] ) ? get_date_from_gmt( $this->data['date_created'] ) : null,
				'description'                  => fn (): ?string => $this->data['description'] ?? null,
				'descriptionPlacement'         => fn (): ?string => $this->data['descriptionPlacement'] ?? null,
				'entryLimits'                  => function (): array {
					return [
						'hasLimit'            => ! empty( $this->data['limitEntries'] ),
						'limitationPeriod'    => ! empty( $this->data['limitEntriesPeriod'] ) ? $this->data['limitEntriesPeriod'] : null,
						'limitReachedMessage' => ! empty( $this->data['limitEntriesMessage'] ) ? $this->data['limitEntriesMessage'] : null,
						'maxEntries'          => isset( $this->data['limitEntriesCount'] ) ? (int) $this->data['limitEntriesCount'] : null,
					];
				},
				'hasConditionalLogicAnimation' => fn (): bool => $this->data['enableAnimation'] ?? false,
				'hasHoneypot'                  => fn (): bool => $this->data['enableHoneypot'] ?? false,
				'firstPageCssClass'            => fn (): ?string => $this->data['firstPageCssClass'] ?? null,
				'form'                         => fn (): array => $this->data,
				'formFields'                   => fn (): ?array => ! empty( $this->data['fields'] ) ? $this->data['fields'] : null,
				'id'                           => fn (): string => Relay::toGlobalId( FormsLoader::$name, $this->data['id'] ),
				'isActive'                     => fn (): bool => $this->data['is_active'] ?? true,
				'isTrash'                      => fn (): bool => $this->data['is_trash'] ?? false,
				'labelPlacement'               => fn (): ?string => $this->data['labelPlacement'] ?? null,
				'login'                        => function (): array {
					return [
						'isLoginRequired'      => ! empty( $this->data['requireLogin'] ),
						'loginRequiredMessage' => ! empty( $this->data['requireLoginMessage'] ) ? $this->data['requireLoginMessage'] : null,
					];
				},
				'markupVersion'                => fn (): ?string => $this->data['markupVersion'] ?? null,
				'notifications'                => fn (): ?array => ! empty( $this->data['notifications'] ) ? $this->data['notifications'] : null,
				'nextFieldId'                  => fn (): ?int => isset( $this->data['nextFieldId'] ) ? (int) $this->data['nextFieldId'] : null,
				'pagination'                   => function (): ?array {
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

					// Relocate logically.
					$pagination['lastPageButton'] = ! empty( $this->data['lastPageButton'] ) ? $this->data['lastPageButton'] : null;

					return $pagination;
				},
				'personalData'                 => function (): ?array {
					$personal_data = ! empty( $this->data['personalData'] ) ? $this->data['personalData'] : null;

					if ( ! is_array( $personal_data ) ) {
						return $personal_data;
					}

					$personal_data = $this->data['personalData'];

					if ( isset( $personal_data['preventIP'] ) ) {
						$personal_data['shouldSaveIP'] = empty( $personal_data['preventIP'] );
						unset( $personal_data['preventIP'] );
					}

					if ( isset( $personal_data['retention']['policy'] ) ) {
						$personal_data['retentionPolicy'] = $personal_data['retention']['policy'];
					}

					if ( isset( $personal_data['retention']['retain_entries_days'] ) ) {
						$personal_data['daysToRetain'] = 'retain' !== $personal_data['retentionPolicy'] ? $personal_data['retention']['retain_entries_days'] : null;
					}

					unset( $personal_data['retention'] );

					if ( isset( $personal_data['exportingAndErasing'] ) ) {
						$personal_data['dataPolicies']['canExportAndErase'] = ! empty( $personal_data['exportingAndErasing']['enabled'] );

						$personal_data['dataPolicies']['identificationFieldDatabaseId'] = $personal_data['exportingAndErasing']['identificationField'];

						foreach ( $personal_data['exportingAndErasing']['columns'] as $field => $settings ) {
							$personal_data['dataPolicies']['entryData'][] = [
								'key'          => $field,
								'shouldExport' => ! empty( $settings['export'] ),
								'shouldErase'  => ! empty( $settings['erase'] ),
							];
						}

						unset( $personal_data['exportingAndErasing'] );
					}

					return $personal_data;
				},
				'postCreation'                 => function (): array {
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
				'requiredIndicator'            => fn (): ?string => $this->data['requiredIndicator'] ?? null,
				'saveAndContinue'              => fn (): ?array => ! empty( $this->data['save'] ) ? $this->data['save'] : null,
				'scheduling'                   => function (): array {
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
				'subLabelPlacement'            => fn (): ?string => ! empty( $this->data['subLabelPlacement'] ) ? $this->data['subLabelPlacement'] : null,
				'submitButton'                 => function (): ?array {
					$button = isset( $this->data['button'] ) ? $this->data['button'] : null;
					// Coax types.
					$button['layoutGridColumnSpan'] = ! empty( $button['layoutGridColumnSpan'] ) ? (int) $button['layoutGridColumnSpan'] : null;
					$button['imageUrl']             = ! empty( $button['imageUrl'] ) ? $button['imageUrl'] : null;
					$button['text']                 = ! empty( $button['text'] ) ? $button['text'] : null;
					return $button;
				},
				'title'                        => fn (): ?string => ! empty( $this->data['title'] ) ? $this->data['title'] : null,
				'hasValidationSummary'         => fn (): bool => ! empty( $this->data['validationSummary'] ),
				'version'                      => fn (): ?string => ! empty( $this->data['version'] ) ? $this->data['version'] : null,
			];

			/**
			 * Internal Filter for modifying the model.
			 *
			 * To be replaced by https://github.com/wp-graphql/wp-graphql/issues/2198
			 *
			 * @deprecated 0.11.0 use "graphql_model_prepare_fields"
			 */
			$this->fields = apply_filters_deprecated( 'graphql_gf_form_modeled_data_experimental', [ $this->fields, $this->data ], '0.11.0', 'graphql_model_prepare_fields' );
		}
	}
}
