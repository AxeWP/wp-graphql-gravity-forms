<?php
declare(strict_types=1);

use Tests\WPGraphQL\GF\TestCase\GFGraphQLTestCase;

class CheckPasswordInputTypeTest extends GFGraphQLTestCase {
    public function testCheckPasswordInputType() {
        $query = '
            {
                __type(name: "PasswordInputProperty") {
                    fields {
                        name
                    }
                }
            }
        ';

        $response = $this->graphql(compact('query'));

        echo "\n=== PasswordInputProperty type fields ===\n";
        print_r($response['data']['__type']['fields']);
        echo "\n=== End ===\n";

        $this->assertArrayNotHasKey('errors', $response);
    }
}
