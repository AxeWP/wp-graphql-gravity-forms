<?php
declare(strict_types=1);

class PasswordFieldDebugTest extends \Tests\WPGraphQL\GF\TestCase\GFGraphQLTestCase {
    public function testDebugPasswordGraphQLQuery() {
        $form_id = $this->factory->form->create();

        $query = '
            query($formId: ID!) {
                gfForm(id: $formId, idType: DATABASE_ID) {
                    formFields {
                        nodes {
                            __typename
                            databaseId
                            type
                            ... on PasswordField {
                                label
                            }
                        }
                    }
                }
            }
        ';

        $variables = [
            'formId' => $form_id,
        ];

        $response = $this->graphql(compact('query', 'variables'));

        print_r($response);

        $this->assertArrayNotHasKey('errors', $response);
        $this->assertArrayHasKey('data', $response);
    }
}
