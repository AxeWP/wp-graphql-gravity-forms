<?php
/**
 * DataManipulators - DraftEntryData
 *
 * Manipulates draft entry data.
 *
 * @package WPGraphQL\GF\DataManipulators
 * @since 0.0.1
 */

namespace WPGraphQL\GF\DataManipulators;

/**
 * Class - DraftEntryDataManipulator
 */
class DraftEntryDataManipulator {
	/**
	 * Manipulate draft entry data.
	 *
	 * @param array  $draft_entry  The draft entry data to be manipulated.
	 * @param string $resume_token The resume token for the draft entry.
	 *
	 * @return array Manipulated entry data.
	 */
	public static function manipulate( array $draft_entry, string $resume_token ) : array {
		$draft_entry = self::set_resume_token_value( $draft_entry, $resume_token );

		return EntryDataManipulator::manipulate( $draft_entry );
	}

	/**
	 * Sets resume token for the draft entry.
	 *
	 * @param array  $draft_entry  The draft entry data to be manipulated.
	 * @param string $resume_token The resume token for the draft entry.
	 *
	 * @return array Manipulated entry data.
	 */
	private static function set_resume_token_value( array $draft_entry, string $resume_token ) : array {
		$draft_entry['resumeToken'] = $resume_token;
		return $draft_entry;
	}
}
