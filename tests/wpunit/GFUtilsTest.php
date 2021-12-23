<?php
/**
 * Test GFUtils functions.
 *
 * @package .
 */

use GraphQL\Error\UserError;
use WPGraphQL\GF\Utils\GFUtils;
use Tests\WPGraphQL\GF\TestCase\GFGraphQLTestCase;

/**
 * Class - GFUtilsTest
 */
class GFUtilsTest extends GFGraphQLTestCase {
	private $fields = [];
	private $form_id;
	private $entry_id;
	private $draft_token;
	private $text_field_helper;

	/**
	 * Run before each test.
	 */
	public function setUp(): void {
		// Before...
		parent::setUp();

		// Your set up methods here.
		$this->text_field_helper = $this->tester->getPropertyHelper( 'TextField' );
		$this->fields[]          = $this->factory->field->create( $this->text_field_helper->values );
		$this->form_id           = $this->factory->form->create( array_merge( [ 'fields' => $this->fields ], $this->tester->getFormDefaultArgs() ) );
		$this->entry_id          = $this->factory->entry->create(
			[
				'form_id'              => $this->form_id,
				$this->fields[0]['id'] => 'This is a default Text Entry',
			]
		);
		$this->draft_token       = $this->factory->draft_entry->create(
			[
				'form_id'    => $this->form_id,
				'created_by' => $this->admin->ID,
			]
		);
	}

	/**
	 * Run after each test.
	 */
	public function tearDown(): void {
		// Your tear down methods here.
		$this->factory->entry->delete( $this->entry_id );
		$this->factory->draft_entry->delete( $this->draft_token );
		$this->factory->form->delete( $this->form_id );

		// Then...
		parent::tearDown();
	}

	/**
	 * Tests GFUtils::get_ip().
	 */
	public function testGetIp() : void {
		$ip = '192.168.0.1';

		$actual = GFUtils::get_ip( $ip );
		$this->assertEquals( $ip, $actual );

		// Test no IP.
		$expected = '127.0.0.1';
		$actual   = GFUtils::get_ip( '' );
		$this->assertEquals( $expected, $actual );
	}

	/**
	 * Tests GFUtils::get_form().
	 */
	public function testGetForm() : void {
		$expected = $this->factory->form->get_object_by_id( $this->form_id );

		// No second parameter.
		$actual = GFUtils::get_form( $this->form_id );
		$this->assertEquals( $expected, $actual );

		// Test Exception.
		$this->expectException( UserError::class );
		$this->expectExceptionMessage( 'Unable to retrieve the form for the given ID' );
		$actual = GFUtils::get_form( $this->form_id + 1 );
	}



	/**
	 * Tests GFUtils::get_form() for trashed forms.
	 */
	public function testGetForm_trash() : void {
		// Create Trash Entry.
		$form_id    = $this->factory->form->create( array_merge( [ 'fields' => $this->fields ], $this->tester->getFormDefaultArgs() ) );
		$is_updated = \GFFormsModel::trash_form( $form_id );

		$expected = $this->factory->form->get_object_by_id( $form_id );
		$actual   = GFUtils::get_form( $form_id, false );
		$this->assertEquals( $expected, $actual );

		$this->expectException( UserError::class );
		$this->expectExceptionMessage( 'The form for the given ID ' . $form_id . ' is inactive or trashed' );

		$actual = GFUtils::get_form( $form_id );

		$this->factory->form->delete( $form_id );
	}

	/**
	 * Tests GFUtils::get_forms().
	 */
	public function testGetForms() : void {
		$expected = $this->factory->form->get_object_by_id( $this->form_id );

		$actual = GFUtils::get_forms( [ $this->form_id ] );
		$this->assertEquals( [ $expected ], $actual );
	}

	/**
	 * Tests GFUtils::get_last_form_page().
	 */
	public function testGetLastFormPage() : void {
		$this->markTestIncomplete(
			'This test has not been implemented yet. Requires PageField arguments.'
		);
	}

	/**
	 * Tests GFUtils::get_form_unique_id().
	 */
	public function testGetFormUniqueId() : void {
		$expected = GFUtils::get_form_unique_id( $this->form_id );
		$actual   = GFUtils::get_form_unique_id( $this->form_id );
		$this->assertEquals( $expected, $actual );
	}

	/**
	 * Tests GFUtils::get_field_by_id().
	 */
	public function testGetFieldById() : void {
		$form     = $this->factory->form->get_object_by_id( $this->form_id );
		$expected = $this->fields[0];
		$actual   = GFUtils::get_field_by_id( $form, $this->fields[0]->id );

		$this->assertInstanceOf( get_class( $expected ), $actual );
		$this->assertEquals( $this->fields[0]->id, $actual->id );

		$this->expectException( UserError::class );
		$this->expectExceptionMessage( 'The form (ID ' . $this->form_id . ') does not contain a field with the field ID ' . ( $this->fields[0]->id + 1 ) );

		$actual = GFUtils::get_field_by_id( $form, $this->fields[0]->id + 1 );
	}

	/**
	 * Tests GFUtils::get_entry().
	 */
	public function testGetEntry() : void {
		$expected = $this->factory->entry->get_object_by_id( $this->entry_id );
		$actual   = GFUtils::get_entry( $this->entry_id );
		$this->assertEquals( $expected, $actual );

		$this->expectException( UserError::class );
		$this->expectExceptionMessage( 'The entry for the given ID ' . ( $this->entry_id + 1 ) . ' was not found' );

		$actual = GFUtils::get_entry( $this->entry_id + 1 );
	}

	/**
	 * Tests GFUtils::update_entry().
	 */
	public function testUpdateEntry() : void {
		$entry_data = $this->factory->entry->get_object_by_id( $this->entry_id );

		$entry_data['is_starred'] = true;

		$updated_entry_id = GFUtils::update_entry( $entry_data );

		$actual_entry_data = $this->factory->entry->get_object_by_id( $updated_entry_id );

		$this->assertEquals( $entry_data, $actual_entry_data );

		$this->expectException( UserError::class );
		$this->expectExceptionMessage( 'An error occured while trying to update the entry' );

		$updated_entry_id = GFUtils::update_entry( $entry_data, $this->entry_id + 1 );
	}

	/**
	 * Tests GFUtils::get_draft_entry().
	 */
	public function testGetDraftEntry() : void {
		$expected = $this->factory->draft_entry->get_object_by_id( $this->draft_token );
		$actual   = GFUtils::get_draft_entry( $this->draft_token );
		$this->assertEquals( $expected, $actual );

		$this->expectException( UserError::class );
		$this->expectExceptionMessage( 'A draft entry with the resume token abcdef could not be found.' );

		$actual = GFUtils::get_draft_entry( 'abcdef' );
	}

	/**
	 * Tests GFUtils::get_draft_submission().
	 */
	public function testGetDraftSubmission() : void {
		$expected_entry = $this->factory->draft_entry->get_object_by_id( $this->draft_token );
		$expected       = json_decode( $expected_entry['submission'], true );

		$expected['partial_entry']['resumeToken'] = $this->draft_token;

		$actual = GFUtils::get_draft_submission( $this->draft_token );
		$this->assertEquals( $expected, $actual );
	}

	/**
	 * Tests GFUtils::get_resume_url().
	 */
	public function testGetResumeUrl() : void {
		$url      = 'test.com';
		$expected = 'http://' . $url . '?gf_token=' . $this->draft_token;
		$actual   = GFUtils::get_resume_url( '$url', $this->draft_token );

		// Test empty source_url.
		$actual = GFUtils::get_resume_url( '', $this->draft_token );
		$this->assertEquals( '', $actual );
	}

	/**
	 * Tests GFUtils::save_draft_submission();
	 */
	public function testSaveDraftSubmission() : void {
		$form       = $this->factory->form->get_object_by_id( $this->form_id );
		$entry_data = $this->factory->draft_entry->get_object_by_id( $this->draft_token );
		$submission = json_decode( $entry_data['submission'], true );

		// Test new submission.
		$actual = GFUtils::save_draft_submission( $form, $submission );
		$this->assertIsString( $actual );

		// Test updating submission.
		$actual = GFUtils::save_draft_submission( $form, $submission, null, 1, [], null, '', '', $this->draft_token );

		$this->assertEquals( $this->draft_token, $actual );

		// Test empty form.
		$this->expectException( UserError::class );
		$this->expectExceptionMessage( 'An error occured while trying to save the draft entry. Form or Entry not set.' );
		$actual = GFUtils::save_draft_submission( [], $submission );

		// Test empty entry.
		$this->expectException( UserError::class );
		$this->expectExceptionMessage( 'An error occured while trying to save the draft entry. Form or Entry not set.' );
		$actual = GFUtils::save_draft_submission( $form, [] );

		// Test error updating.
		$this->expectException( UserError::class );
		$this->expectExceptionMessage( 'An error occured while trying to save the draft entry. Database Error:' );
		$actual = GFUtils::save_draft_submission( $form, $entry_data );
		$this->factory->draft_entry->delete( $actual );
	}

	/**
	 * Tests GFUtils::submit_form() when invalid.
	 */
	public function testSubmitForm_invalid() : void {
		$form         = $this->factory->form->get_object_by_id( $this->form_id );
		$input_values = [
			'input_' . $form['fields'][0]->id => 'value2',
		];

		$this->expectException( UserError::class );
		$this->expectExceptionMessage( 'There was an error while processing the form.' );
		$actual = GFUtils::submit_form( $this->form_id, $input_values, $input_values );
	}

	/**
	 * Tests GFUtils::submit_form().
	 */
	public function testSubmitForm() : void {
		$form         = $this->factory->form->get_object_by_id( $this->form_id );
		$input_values = [
			'input_' . $form['fields'][0]->id => 'value1',
		];

		$actual = GFUtils::submit_form(
			$this->form_id,
			$input_values,
			$input_values,
		);

		$this->assertIsArray( $actual );
		$this->factory->entry->delete( $actual['entry_id'] );
	}
}
