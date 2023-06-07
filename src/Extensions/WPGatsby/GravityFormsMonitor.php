<?php
/**
 * Creates WPGatsby Action Monitor for Gravity Forms
 *
 * @package WPGraphQL\GF\Extensions\WPGatsby
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Extensions\WPGatsby;

use GraphQLRelay\Relay;
use WPGatsby\ActionMonitor\ActionMonitor;
use WPGraphQL\GF\Data\Loader\DraftEntriesLoader;
use WPGraphQL\GF\Data\Loader\EntriesLoader;
use WPGraphQL\GF\Data\Loader\FormsLoader;
use WPGraphQL\GF\Type\WPObject\Entry\DraftEntry;
use WPGraphQL\GF\Type\WPObject\Entry\SubmittedEntry;
use WPGraphQL\GF\Type\WPObject\Form\Form;

/**
 * Class - ActionMonitors
 */
class GravityFormsMonitor extends \WPGatsby\ActionMonitor\Monitors\Monitor {
	/**
	 * An array of enabled actions to monitor.
	 *
	 * @var array
	 */
	public static array $enabled_actions;

	/**
	 * The class constructor.
	 *
	 * @param \WPGatsby\ActionMonitor\ActionMonitor $action_monitor .
	 */
	public function __construct( ActionMonitor $action_monitor ) {
		self::$enabled_actions = Settings::get_enabled_actions();

		parent::__construct( $action_monitor );
	}

	/**
	 * {@inheritDoc}
	 *
	 * @todo Trigger on delete entry, once filter is added.
	 */
	public function init(): void {
		// Create form.
		add_action( 'gform_post_form_duplicated', [ $this, 'after_duplicate_form' ], 10, 2 );
		// Create or update form.
		add_action( 'gform_after_save_form', [ $this, 'after_save_form' ], 10, 2 );
		// Update form.
		add_action( 'gform_post_update_form_meta', [ $this, 'post_update_form_meta' ], 10, 2 );
		add_action( 'gform_post_form_activated', [ $this, 'after_update_form' ] );
		add_action( 'gform_post_form_deactivated', [ $this, 'after_update_form' ] );
		add_action( 'gform_post_form_restored', [ $this, 'after_update_form' ] );
		add_action( 'gform_post_form_trashed', [ $this, 'after_update_form' ] );
		// Delete form.
		add_action( 'gform_after_delete_form', [ $this, 'after_delete_form' ] );
		// Create submission.
		add_action( 'gform_after_submission', [ $this, 'after_create_entry' ] );
		// Update Submission.
		add_action( 'gform_after_update_entry', [ $this, 'after_update_entry' ], 10, 2 );
		add_action( 'gform_post_update_entry', [ $this, 'post_update_entry' ], 10 );
		// Save draft entry.
		add_action( 'gform_incomplete_submission_post_save', [ $this, 'after_save_draft_entry' ], 10, 2 );
	}

	/**
	 * Logs a Gatsby action.
	 *
	 * @param string $action_name the name of the action defined in the settings.
	 * @param array  $args the action config.
	 */
	public function log( string $action_name, array $args ): void {
		if ( ! in_array( $action_name, self::$enabled_actions, true ) ) {
			return;
		}

		$this->log_action( $args );
	}

	/**
	 * Triggers a Gatsby `log_action()` after a form is created.
	 *
	 * @param integer $form_id .
	 */
	public function after_create_form( int $form_id ): void {
		$args = [
			'action_type'         => 'CREATE',
			'title'               => sprintf( 'Form #%d', $form_id ),
			'node_id'             => $form_id,
			'relay_id'            => Relay::toGlobalId( FormsLoader::$name, (string) $form_id ),
			'graphql_single_name' => Form::$type,
			'graphql_plural_name' => Form::$type . 's',
			// Forms don't have post status. This is for Gatsby.
			'status'              => 'publish',
		];
		$this->log( 'create_form', $args );
	}

	/**
	 * Wraps `after_update_form()` for specific gf actions.
	 *
	 * @param int $old_id .
	 * @param int $new_id .
	 */
	public function after_duplicate_form( int $old_id, int $new_id ): void {
		$this->after_create_form( $new_id );
	}

	/**
	 * Triggers a Gatsby `log_action()` after a form is updated.
	 *
	 * @param integer $form_id .
	 */
	public function after_update_form( int $form_id ): void {
		$args = [
			'action_type'         => 'UPDATE',
			'title'               => sprintf( 'Form #%d', $form_id ),
			'node_id'             => $form_id,
			'relay_id'            => Relay::toGlobalId( FormsLoader::$name, (string) $form_id ),
			'graphql_single_name' => Form::$type,
			'graphql_plural_name' => Form::$type . 's',
			// Forms don't have post status. This is for Gatsby.
			'status'              => 'publish',
		];
		$this->log( 'update_form', $args );
	}

	/**
	 * Wraps `after_update_form()` for specific gf actions.
	 *
	 * @param mixed $form_meta .
	 * @param int   $form_id The form ID.
	 */
	public function post_update_form_meta( $form_meta, int $form_id ): void {
		$this->after_update_form( $form_id );
	}

	/**
	 * Triggers either `after_create_form()` or `after_update_form()`
	 *
	 * @param array $form .
	 * @param bool  $is_new whether the form was created or updated.
	 */
	public function after_save_form( array $form, bool $is_new ): void {
		if ( $is_new ) {
			$this->after_create_form( $form['id'] );
		} else {
			$this->after_update_form( $form['id'] );
		}
	}

	/**
	 * Triggers a Gatsby `log_action()` after a form is deleted.
	 *
	 * @param integer $form_id .
	 */
	public function after_delete_form( int $form_id ): void {
		$args = [
			'action_type'         => 'DELETE',
			'title'               => sprintf( 'Form #%d', $form_id ),
			'node_id'             => $form_id,
			'relay_id'            => Relay::toGlobalId( FormsLoader::$name, (string) $form_id ),
			'graphql_single_name' => Form::$type,
			'graphql_plural_name' => Form::$type . 's',
			// Forms don't have post status. This is for Gatsby.
			'status'              => 'trash',
		];
		$this->log( 'delete_form', $args );
	}

	/**
	 * Triggers a Gatsby `log_action()` after an entry is created.
	 *
	 * @param array $entry .
	 */
	public function after_create_entry( array $entry ): void {
		$args = [
			'action_type'         => 'CREATE',
			'title'               => sprintf( 'Entry #%s', $entry['id'] ),
			'node_id'             => $entry['id'],
			'relay_id'            => Relay::toGlobalId( EntriesLoader::$name, (string) $entry['id'] ),
			'graphql_single_name' => SubmittedEntry::$type,
			'graphql_plural_name' => SubmittedEntry::$type . 's',
			// Forms don't have post status. This is for Gatsby.
			'status'              => 'publish',
		];
		$this->log( 'create_entry', $args );
	}

	/**
	 * Triggers a Gatsby `log_action()` after an entry is updated.
	 *
	 * @param array $form .
	 * @param int   $entry_id .
	 */
	public function after_update_entry( array $form, int $entry_id ): void {
		$args = [
			'action_type'         => 'UPDATE',
			'title'               => sprintf( 'Entry #%d', $entry_id ),
			'node_id'             => $entry_id,
			'relay_id'            => Relay::toGlobalId( EntriesLoader::$name, (string) $entry_id ),
			'graphql_single_name' => SubmittedEntry::$type,
			'graphql_plural_name' => SubmittedEntry::$type . 's',
			// Forms don't have post status. This is for Gatsby.
			'status'              => 'publish',
		];
		$this->log( 'update_entry', $args );
	}

	/**
	 * Wraps `after_update_entry()` for specific gf actions.
	 *
	 * @param array $entry .
	 */
	public function post_update_entry( array $entry ): void {
		$this->after_update_entry( [], $entry['id'] );
	}

	/**
	 * Triggers a Gatsby `log_action()` after a draft entry is updated.
	 *
	 * Currently unused, as Draft Entries arent yet part of the schema.
	 *
	 * @param array  $submission .
	 * @param string $resume_token .
	 */
	public function after_save_draft_entry( array $submission, string $resume_token ): void {
		$args = [
			'action_type'         => 'Create',
			'title'               => sprintf( 'Draft Entry #%s', $resume_token ),
			'node_id'             => $resume_token,
			'relay_id'            => Relay::toGlobalId( DraftEntriesLoader::$name, $resume_token ),
			'graphql_single_name' => DraftEntry::$type,
			'graphql_plural_name' => DraftEntry::$type . 's',
			// Forms don't have post status. This is for Gatsby.
			'status'              => 'publish',
		];
		$this->log( 'create_draft_entry', $args );
	}
}
