<?php
/**
 * Test submitGfForm mutation .
 *
 * @package .
 */

use Tests\WPGraphQL\GF\TestCase\GFGraphQLTestCase;

/**
 * Class - SubmitFormMutationTest
 */
class SubmitFormMutationTest extends GFGraphQLTestCase {
	/**
	 * Run before each test.
	 */
	public function setUp(): void {
		// Before...
		parent::setUp();

		$this->clearSchema();
	}

	/**
	 * Tests `submitGfDraft
	 */
	public function testSubmitFormWithEmptyFieldValues(): void {
		// Create Form.
		$helper  = $this->tester->getPropertyHelper( 'TextField' );
		$fields  = [ $this->factory->field->create( $helper->values ) ];
		$form_id = $this->factory->form->create( array_merge( [ 'fields' => $fields ], $this->tester->getFormDefaultArgs() ) );

		$query = $this->submit_mutation();

		// Test with errors.

		$variables = [
			'input' => [
				'id'          => $form_id,
				'saveAsDraft' => false,
				'entryMeta'   => null,
				'fieldValues' => [],
			],
		];

		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );

		$this->assertEmpty( $actual['data']['submitGfForm']['confirmation'], 'Confirmation should be empty on failure' );
		$this->assertEmpty( $actual['data']['submitGfForm']['entry'], 'Entry should be empty o' );
		$this->assertEmpty( $actual['data']['submitGfForm']['resumeUrl'] );

		$this->assertNotEmpty( $actual['data']['submitGfForm']['errors'] );
		$this->assertCount( 1, $actual['data']['submitGfForm']['errors'] );
		$this->assertNotEmpty( $actual['data']['submitGfForm']['errors'][0]['id'] );
		$this->assertNotEmpty( $actual['data']['submitGfForm']['errors'][0]['message'] );
		$this->assertNotEmpty( $actual['data']['submitGfForm']['errors'][0]['connectedFormField'] );
		$this->assertEquals( $actual['data']['submitGfForm']['errors'][0]['id'], $actual['data']['submitGfForm']['errors'][0]['connectedFormField']['databaseId'] );
	}

	public function testSubmitForm(): void {
		// Create Form.
		$helper  = $this->tester->getPropertyHelper( 'TextField' );
		$fields  = [ $this->factory->field->create( $helper->values ) ];
		$form_id = $this->factory->form->create( array_merge( [ 'fields' => $fields ], $this->tester->getFormDefaultArgs() ) );

		$query = $this->submit_mutation();

		$variables = [
			'input' => [
				'id'          => $form_id,
				'saveAsDraft' => false,
				'entryMeta'   => null,
				'fieldValues' => [
					[
						'id'    => $fields[0]->id,
						'value' => 'Test',
					],
				],
			],
		];

		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertEmpty( $actual['data']['submitGfForm']['errors'] );
		$this->assertEmpty( $actual['data']['submitGfForm']['resumeUrl'] );

		// Confirmation
		$this->assertEquals( 'MESSAGE', $actual['data']['submitGfForm']['confirmation']['type'] );
		$this->assertNotEmpty( $actual['data']['submitGfForm']['confirmation']['message'] );

		// Entry
		$this->assertNotEmpty( $actual['data']['submitGfForm']['entry'] );
		$this->assertNotEmpty( $actual['data']['submitGfForm']['entry']['databaseId'] );
		$this->assertEquals( $form_id, $actual['data']['submitGfForm']['entry']['form']['databaseId'] );
		$this->assertNotEmpty( $actual['data']['submitGfForm']['entry']['dateCreated'] );
		$this->assertNotEmpty( $actual['data']['submitGfForm']['entry']['dateCreatedGmt'] );
		$this->assertNotEmpty( $actual['data']['submitGfForm']['entry']['dateUpdated'] );
		$this->assertNotEmpty( $actual['data']['submitGfForm']['entry']['dateUpdatedGmt'] );
		$this->assertNotEmpty( $actual['data']['submitGfForm']['entry']['ip'] );
		$this->assertFalse( $actual['data']['submitGfForm']['entry']['isDraft'] );
		$this->assertTrue( $actual['data']['submitGfForm']['entry']['isSubmitted'] );
		$this->assertNotEmpty( $actual['data']['submitGfForm']['entry']['sourceUrl'] );
		$this->assertEmpty( $actual['data']['submitGfForm']['entry']['userAgent'] );

		// Cleanup
		$this->factory->entry->delete( $actual['data']['submitGfForm']['entry']['databaseId'] );
		$this->factory->form->delete( $form_id );
	}

	public function testSubmitWithUrlConfirmation(): void {
		// Create Form.
		$helper = $this->tester->getPropertyHelper( 'TextField' );
		$fields = [ $this->factory->field->create( $helper->values ) ];

		$form_id = $this->factory->form->create(
			array_merge(
				[ 'fields' => $fields ],
				$this->tester->getFormDefaultArgs(),
				[
					'confirmations' => [
						[
							'id'          => '1',
							'name'        => 'Default Confirmation',
							'type'        => 'REDIRECT',
							'url'         => 'https://example.com/test',
							'queryString' => 'foo=bar',
							'pageId'      => '',
							'eventId'     => '',
							'eventDelay'  => '',
							'message'     => '',
							'cssClass'    => '',
						],
					],
				]
			)
		);

		$query = $this->submit_mutation();

		$variables = [
			'input' => [
				'id'          => $form_id,
				'saveAsDraft' => false,
				'entryMeta'   => null,
				'fieldValues' => [
					[
						'id'    => $fields[0]->id,
						'value' => 'Test',
					],
				],
			],
		];

		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertEmpty( $actual['data']['submitGfForm']['errors'] );
		$this->assertEmpty( $actual['data']['submitGfForm']['resumeUrl'] );

		$this->assertEquals( 'REDIRECT', $actual['data']['submitGfForm']['confirmation']['type'] );
		$this->assertStringContainsString( 'https://example.com/test', $actual['data']['submitGfForm']['confirmation']['url'] );

		// Cleanup
		$this->factory->entry->delete( $actual['data']['submitGfForm']['entry']['databaseId'] );
		$this->factory->form->delete( $form_id );
	}

	public function testSubmitWithPageConfirmation(): void {
		// Create page.
		$page_id = $this->factory()->post->create(
			[
				'post_type'   => 'page',
				'post_status' => 'publish',
				'post_title'  => 'Testing Submit with Page Confirmation',
			]
		);

		// Create Form.
		$helper  = $this->tester->getPropertyHelper( 'TextField' );
		$fields  = [ $this->factory->field->create( $helper->values ) ];
		$form_id = $this->factory->form->create(
			array_merge(
				[ 'fields' => $fields ],
				$this->tester->getFormDefaultArgs(),
				[
					'confirmations' => [
						[
							'id'          => '1',
							'name'        => 'Default Confirmation',
							'type'        => 'page',
							'url'         => '',
							'queryString' => 'foo=bar',
							'pageId'      => $page_id,
							'eventId'     => '',
							'eventDelay'  => '',
							'message'     => '',
							'cssClass'    => '',
						],
					],
				]
			)
		);

		$query = $this->submit_mutation();

		$variables = [
			'input' => [
				'id'          => $form_id,
				'saveAsDraft' => false,
				'entryMeta'   => null,
				'fieldValues' => [
					[
						'id'    => $fields[0]->id,
						'value' => 'Test',
					],
				],
			],
		];

		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertEmpty( $actual['data']['submitGfForm']['errors'] );
		$this->assertEmpty( $actual['data']['submitGfForm']['resumeUrl'] );

		$this->assertEquals( 'REDIRECT', $actual['data']['submitGfForm']['confirmation']['type'] );
		$this->assertEquals( $page_id, $actual['data']['submitGfForm']['confirmation']['pageId'] );
		$this->assertEquals( $page_id, $actual['data']['submitGfForm']['confirmation']['page']['node']['databaseId'] );

		// Cleanup
		$this->factory->entry->delete( $actual['data']['submitGfForm']['entry']['databaseId'] );
		$this->factory->form->delete( $form_id );
	}

	public function testSubmitWithLoggedInUser(): void {
		// Create Form.
		$helper  = $this->tester->getPropertyHelper( 'TextField' );
		$fields  = [ $this->factory->field->create( $helper->values ) ];
		$form_id = $this->factory->form->create( array_merge( [ 'fields' => $fields ], $this->tester->getFormDefaultArgs() ) );

		$query = $this->submit_mutation();

		$variables = [
			'input' => [
				'id'          => $form_id,
				'saveAsDraft' => false,
				'entryMeta'   => null,
				'fieldValues' => [
					[
						'id'    => $fields[0]->id,
						'value' => 'Test',
					],
				],
			],
		];

		wp_set_current_user( $this->admin->ID );

		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertEmpty( $actual['data']['submitGfForm']['errors'] );
		$this->assertEmpty( $actual['data']['submitGfForm']['resumeUrl'] );

		$this->assertEquals( $this->admin->ID, $actual['data']['submitGfForm']['entry']['createdBy']['databaseId'] );
		$this->assertEquals( $this->admin->ID, $actual['data']['submitGfForm']['entry']['createdByDatabaseId'] );
		$this->assertNotEmpty( $actual['data']['submitGfForm']['entry']['createdById'] );

		// Cleanup
		$this->factory->entry->delete( $actual['data']['submitGfForm']['entry']['databaseId'] );
		$this->factory->form->delete( $form_id );
	}

	public function testSubmitWithEntryMeta(): void {
		// Create Form.
		$helper  = $this->tester->getPropertyHelper( 'TextField' );
		$fields  = [ $this->factory->field->create( $helper->values ) ];
		$form_id = $this->factory->form->create( array_merge( [ 'fields' => $fields ], $this->tester->getFormDefaultArgs() ) );

		$query = $this->submit_mutation();

		$variables = [
			'input' => [
				'id'          => $form_id,
				'saveAsDraft' => false,
				'entryMeta'   => [
					'createdById'    => $this->admin->ID,
					'dateCreatedGmt' => '2020-01-01 00:00:00',
					'ip'             => '10.0.0.1',
					'sourceUrl'      => 'https://example.com',
					'userAgent'      => 'Test',
				],
				'fieldValues' => [
					[
						'id'    => $fields[0]->id,
						'value' => 'Test',
					],
				],
			],
		];

		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertEmpty( $actual['data']['submitGfForm']['errors'] );
		$this->assertEmpty( $actual['data']['submitGfForm']['resumeUrl'] );

		// @todo this is probably unsafe.
		$this->assertEquals( $this->admin->ID, $actual['data']['submitGfForm']['entry']['createdByDatabaseId'] );

		$this->assertEquals( $variables['input']['entryMeta']['dateCreatedGmt'], $actual['data']['submitGfForm']['entry']['dateCreatedGmt'] );
		$this->assertEquals( $variables['input']['entryMeta']['ip'], $actual['data']['submitGfForm']['entry']['ip'] );
		$this->assertEquals( $variables['input']['entryMeta']['sourceUrl'], $actual['data']['submitGfForm']['entry']['sourceUrl'] );
		$this->assertEquals( $variables['input']['entryMeta']['userAgent'], $actual['data']['submitGfForm']['entry']['userAgent'] );

		// Cleanup
		$this->factory->entry->delete( $actual['data']['submitGfForm']['entry']['databaseId'] );
		$this->factory->form->delete( $form_id );
	}

	public function testSubmitWithPostCreation(): void {
		$category = $this->factory->category->create_and_get();
		$tag      = $this->factory->tag->create_and_get();

		// Create Form.
		$fields = [
			// Post Title.
			$this->factory->field->create(
				array_merge(
					$this->tester->getPropertyHelper( 'PostTitleField' )->values,
					[
						'id' => 1,
					]
				)
			),
			// Post Excerpt.
			$this->factory->field->create(
				array_merge(
					$this->tester->getPropertyHelper( 'PostExcerptField' )->values,
					[
						'id' => 2,
					]
				)
			),
			// Post Content.
			$this->factory->field->create(
				array_merge(
					$this->tester->getPropertyHelper( 'PostContentField' )->values,
					[
						'id' => 3,
					]
				)
			),
			// Post Category.
			$this->factory->field->create(
				array_merge(
					$this->tester->getPropertyHelper( 'PostCategoryField' )->values,
					[
						'id'        => 4,
						'inputType' => 'select',
						'choices'   => [
							[
								'text'       => $category->name,
								'value'      => $category->term_id,
								'isSelected' => false,
							],
							[
								'text'       => 'Uncategorized',
								'value'      => '1',
								'isSelected' => true,
							],
						],
					]
				)
			),
			// Post Tag.
			$this->factory->field->create(
				array_merge(
					$this->tester->getPropertyHelper( 'PostTagsField' )->values,
					[
						'id'        => 5,
						'inputType' => 'text',
					]
				)
			),
			// Post Image.
			$this->factory->field->create(
				array_merge(
					$this->tester->getPropertyHelper( 'PostImageField' )->values,
					[
						'id' => 6,
					]
				)
			),
		];
		$form_id = $this->factory->form->create(
			array_merge(
				$this->tester->getFormDefaultArgs(),
				[
					'fields'                 => $fields,
					'useCurrentUserAsAuthor' => true,
					'postStatus'             => 'draft',
					'postAuthor'             => $this->admin->ID,
					'postFormat'             => 0,
				],
			)
		);

		$query = $this->submit_mutation();

		$variables = [
			'input' => [
				'id'          => $form_id,
				'saveAsDraft' => false,
				'fieldValues' => [
					// Post Title.
					[
						'id'    => $fields[0]->id,
						'value' => 'Test title',
					],
					// Post Excerpt.
					[
						'id'    => $fields[1]->id,
						'value' => 'Test excerpt',
					],
					// Post content.
					[
						'id'    => $fields[2]->id,
						'value' => 'Test content',
					],
					// Post Category.
					[
						'id'    => $fields[3]->id,
						'value' => (string) $fields[3]->choices[0]['value'],
					],
					// Post Tags.
					[
						'id'    => $fields[4]->id,
						'value' => $tag->name,
					],
					// Post Image.
					[
						'id'          => $fields[5]->id,
						'imageValues' => [
							'altText'     => 'someAlt',
							'caption'     => 'someCaption',
							'description' => 'someDesc',
							'title'       => 'someTitle',
							'image'       => [
								'name'     => 'img1.png',
								'type'     => 'image/png',
								'size'     => filesize( '/tmp/img1.png' ),
								'tmp_name' => '/tmp/img1.png',
							],
						],
					],
				],
			],
		];

		codecept_debug( $variables );

		wp_set_current_user( $this->admin->ID );

		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertNotEmpty( $actual['data']['submitGfForm']['entry']['postDatabaseId'] );
		$this->assertNotEmpty( $actual['data']['submitGfForm']['entry']['post']['databaseId'] );

		$this->assertEquals( 'Test title', $actual['data']['submitGfForm']['entry']['post']['title'] );
		$this->assertStringContainsString( 'Test excerpt', $actual['data']['submitGfForm']['entry']['post']['excerpt'] );
		$this->assertStringContainsString( 'Test content', $actual['data']['submitGfForm']['entry']['post']['content'] );
		$this->assertEquals( $this->admin->ID, $actual['data']['submitGfForm']['entry']['post']['author']['node']['databaseId'] );
		$this->assertEquals( $category->term_id, $actual['data']['submitGfForm']['entry']['post']['categories']['nodes'][0]['databaseId'] );
		$this->assertEquals( $tag->term_id, $actual['data']['submitGfForm']['entry']['post']['tags']['nodes'][0]['databaseId'] );
		// @todo Featured image.
		$this->assertEmpty( $actual['data']['submitGfForm']['entry']['post']['postFormats']['nodes'] );
		$this->assertEquals( 'draft', $actual['data']['submitGfForm']['entry']['post']['status'] );

		// Cleanup.
		$this->factory->entry->delete( $actual['data']['submitGfForm']['entry']['databaseId'] );
		$this->factory->form->delete( $form_id );
		wp_delete_category( $category->term_id );
		wp_delete_term( $tag->term_id, 'post_tag' );
		wp_delete_post( $actual['data']['submitGfForm']['entry']['post']['databaseId'], true );
	}

	public function testSubmitWithOrderItems(): void {

		// Create Form.
		$fields = [
			// Single Product With Quantity.
			$this->factory->field->create(
				array_merge(
					$this->tester->getPropertyHelper(
						'ProductField',
						[
							'id'              => 1,
							'inputType'       => 'singleproduct',
							'disableQuantity' => false,
						]
					)->values,
					[
						'inputs' => [
							[
								'id'    => 1.1,
								'label' => 'Name',
								'name'  => null,
							],
							[
								'id'    => 1.2,
								'label' => 'Price',
								'name'  => null,
							],
							[
								'id'    => 1.3,
								'label' => 'Quantity',
								'name'  => null,
							],
						],
					]
				)
			),
			// Single Product No Quantity.
			$this->factory->field->create(
				array_merge(
					$this->tester->getPropertyHelper(
						'ProductField',
						[
							'id'                    => 2,
							'inputType'             => 'singleproduct',
							'autocompleteAttribute' => 'autocomplete',
							'disableQuantity'       => true,
						]
					)->values,
					[

						'inputs' => [
							[
								'id'    => 2.1,
								'label' => 'Name',
								'name'  => null,
							],
							[
								'id'    => 2.2,
								'label' => 'Price',
								'name'  => null,
							],
							[
								'id'    => 2.3,
								'label' => 'Quantity',
								'name'  => null,
							],
						],
					]
				)
			),
			// Quantity.
			$this->factory->field->create(
				array_merge(
					$this->tester->getPropertyHelper(
						'QuantityField',
						[
							'id'                 => 3,
							'inputType'          => 'number',
							'enableAutocomplete' => true,
							'productField'       => 2,
							'rangeMin'           => 1,
							'rangeMax'           => 99,
						]
					)->values,
				)
			),
			// Option.
			$this->factory->field->create(
				array_merge(
					$this->tester->getPropertyHelper(
						'OptionField',
						[
							'id'                => 4,
							'inputType'         => 'radio',
							'productField'      => 2,
							'enablePrice'       => true,
							'enableOtherChoice' => false,
							'noDuplicates'      => false,
						]
					)->values,
				)
			),
			// Shipping.
			$this->factory->field->create(
				array_merge(
					$this->tester->getPropertyHelper( 'ShippingField' )->values,
					[
						'id'        => 5,
						'inputType' => 'singleshipping',
						'basePrice' => '$5.00',
					]
				)
			),
			// // Total.
			// $this->factory->field->create(
			// array_merge(
			// $this->tester->getPropertyHelper( 'TotalField' )->values,
			// [
			// 'id' => 7,
			// ]
			// )
			// ),
		];
		$form_id = $this->factory->form->create(
			array_merge(
				$this->tester->getFormDefaultArgs(),
				[
					'fields' => $fields,

				],
			)
		);

		$query = $this->submit_mutation();

		$variables = [
			'input' => [
				'id'          => $form_id,
				'saveAsDraft' => false,
				'fieldValues' => [
					// Single Product With Quantity.
					[
						'id'    => $fields[0]->id,
						'value' => '2',
					],
					// Single Product No Quantity.
					[
						'id'            => $fields[1]->id,
						'productValues' => [
							'price' => floatval( preg_replace( '/[^\d\.]/', '', $fields[1]['basePrice'] ) ),
						],
					],
					// Quantity.
					[
						'id'    => $fields[2]->id,
						'value' => '2',
					],
					// Option.
					[
						'id'    => $fields[3]->id,
						'value' => $fields[3]->choices[0]['value'],
					],
					// Shipping.
					[
						'id'    => $fields[4]->id,
						'value' => $fields[4]->basePrice,
					],
				],
			],
		];

		wp_set_current_user( $this->admin->ID );

		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );

		$this->assertNotEmpty( $actual['data']['submitGfForm']['entry']['orderSummary'] );
		$this->assertEquals( 'USD', $actual['data']['submitGfForm']['entry']['orderSummary']['currency'] );

		// Test first Order Item.
		$this->assertEquals( $fields[0]->id, $actual['data']['submitGfForm']['entry']['orderSummary']['items'][0]['connectedFormField']['databaseId'] );
		$this->assertEquals( 'USD', $actual['data']['submitGfForm']['entry']['orderSummary']['items'][0]['currency'] );
		$this->assertStringContainsString( $actual['data']['submitGfForm']['entry']['orderSummary']['items'][0]['price'], $fields[0]->basePrice );
		$this->assertEquals( 2, $actual['data']['submitGfForm']['entry']['orderSummary']['items'][0]['quantity'] );
		$expected_subtotal_one = floatval( preg_replace( '/[^\d\.]/', '', $fields[0]->basePrice ) ) * 2;
		$this->assertEquals( $expected_subtotal_one, $actual['data']['submitGfForm']['entry']['orderSummary']['items'][0]['subtotal'] );

		// Test second Order Item.
		$this->assertEquals( $fields[1]->id, $actual['data']['submitGfForm']['entry']['orderSummary']['items'][1]['connectedFormField']['databaseId'] );
		$this->assertEquals( 'USD', $actual['data']['submitGfForm']['entry']['orderSummary']['items'][1]['currency'] );
		$this->assertStringContainsString( $actual['data']['submitGfForm']['entry']['orderSummary']['items'][1]['price'], $fields[1]->basePrice );
		$this->assertEquals( 2, $actual['data']['submitGfForm']['entry']['orderSummary']['items'][1]['quantity'] );
		// Test options.
		$this->assertNotEmpty( $actual['data']['submitGfForm']['entry']['orderSummary']['items'][1]['options'] );
		$this->assertEquals( $fields[3]->id, $actual['data']['submitGfForm']['entry']['orderSummary']['items'][1]['options'][0]['connectedFormField']['databaseId'] );
		$this->assertEquals( $fields[3]->label, $actual['data']['submitGfForm']['entry']['orderSummary']['items'][1]['options'][0]['fieldLabel'] );
		$this->assertEquals( $fields[3]->choices[0]['value'], $actual['data']['submitGfForm']['entry']['orderSummary']['items'][1]['options'][0]['name'] );
		$this->assertEquals( $fields[3]->label . ': ' . $fields[3]->choices[0]['value'], $actual['data']['submitGfForm']['entry']['orderSummary']['items'][1]['options'][0]['optionLabel'] );
		$this->assertEquals( GFCommon::to_number( $fields[3]->choices[0]['price'] ), $actual['data']['submitGfForm']['entry']['orderSummary']['items'][1]['options'][0]['price'] );

		$expected_subtotal_two =
		// The product price.
		( floatval( preg_replace( '/[^\d\.]/', '', $fields[1]->basePrice ) ) * 2 ) +
		// The option price.
		( GFCommon::to_number( $fields[3]->choices[0]['price'] ) * 2 );

		$this->assertEquals( $expected_subtotal_two, $actual['data']['submitGfForm']['entry']['orderSummary']['items'][1]['subtotal'] );

		// Test shipping field.
		$this->assertEquals( $fields[4]->id, $actual['data']['submitGfForm']['entry']['orderSummary']['items'][2]['connectedFormField']['databaseId'] );
		$this->assertEquals( 'USD', $actual['data']['submitGfForm']['entry']['orderSummary']['items'][2]['currency'] );
		$this->assertStringContainsString( $actual['data']['submitGfForm']['entry']['orderSummary']['items'][2]['price'], $fields[4]->basePrice );
		$this->assertTrue( $actual['data']['submitGfForm']['entry']['orderSummary']['items'][2]['isShipping'] );
		$this->assertEquals( 1, $actual['data']['submitGfForm']['entry']['orderSummary']['items'][2]['quantity'] );
		$expected_subtotal_shipping = floatval( preg_replace( '/[^\d\.]/', '', $fields[4]->basePrice ) );
		$this->assertEquals( $expected_subtotal_shipping, $actual['data']['submitGfForm']['entry']['orderSummary']['items'][2]['subtotal'] );

		// Test totals.
		$this->assertEquals( $expected_subtotal_one + $expected_subtotal_two, $actual['data']['submitGfForm']['entry']['orderSummary']['subtotal'] );
		$this->assertEquals( $expected_subtotal_one + $expected_subtotal_two + $expected_subtotal_shipping, $actual['data']['submitGfForm']['entry']['orderSummary']['total'] );

		// Cleanup.
		$this->factory->entry->delete( $actual['data']['submitGfForm']['entry']['databaseId'] );
		$this->factory->form->delete( $form_id );
	}

	/**
	 * Creates the mutation.
	 */
	public function submit_mutation(): string {
		return 'mutation SubmitForm( $input:SubmitGfFormInput! ) {
			submitGfForm(input:$input) {
				confirmation{
					message
					page {
						node {
							databaseId
						}
					}
					pageId
					queryString
					type
					url
				}
				entry {
					createdBy {
						databaseId
					}
					createdByDatabaseId
					createdById
					dateCreated
					dateCreatedGmt
					dateUpdated
					dateUpdatedGmt
					form {
						databaseId
					}
					formId
					id
					ip
					isDraft
					isSubmitted
					# orderSummary
					sourceUrl
					userAgent
					... on GfSubmittedEntry {
						databaseId
						postDatabaseId
						post {
							databaseId
							title
							excerpt
							content
							author {
								node {
									databaseId
								}
							}
							categories {
								nodes {
									databaseId
								}
							}
							tags {
								nodes {
									databaseId
								}
							}
							featuredImage {
								node {
									databaseId
								}
							}
							postFormats {
								nodes {
									databaseId
								}
							}
							status
						}
						orderSummary{
							currency
							items{
								connectedFormField {
									type
									databaseId
								}
								currency
								description
								isDiscount
								isLineItem
								isRecurring
								isTrial
								isSetupFee
								isShipping
								isTrial
								name
								options{
									connectedFormField {
										databaseId
									}
									fieldLabel
									name
									optionLabel
									price
								}
								price
								quantity
								section
								subtotal
							}
							subtotal
							total
						}
					}
				}
				errors {
					id
					message
					connectedFormField {
						databaseId
						type
					}
				}
				resumeUrl
			}
		}';
	}
}
