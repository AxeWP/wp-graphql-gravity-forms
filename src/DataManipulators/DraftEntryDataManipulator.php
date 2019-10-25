<?php

namespace WPGraphQLGravityForms\DataManipulators;

class DraftEntryDataManipulator {
    /**
     * EntryDataManipulator instance.
     */
    private $entry_data_manipulator;

    public function __construct( EntryDataManipulator $entry_data_manipulator ) {
        $this->entry_data_manipulator = $entry_data_manipulator;
    }

    /**
     * Manipulate draft entry data.
     *
     * @param array  $draft_entry  The draft entry data to be manipulated.
     * @param string $resume_token The resume token for the draft entry.
     *
     * @return array Manipulated entry data.
     */
    public function manipulate( array $draft_entry, string $resume_token ) : array {
        $draft_entry = $this->set_resume_token_value( $draft_entry, $resume_token );
        $draft_entry = $this->entry_data_manipulator->manipulate( $draft_entry );

        return $draft_entry;
    }

    private function set_resume_token_value( array $draft_entry, string $resume_token ) : array {
        $draft_entry['resumeToken'] = $resume_token;
        return $draft_entry;
    }
}
