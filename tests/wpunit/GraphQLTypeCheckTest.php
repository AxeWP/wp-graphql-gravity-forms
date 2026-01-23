<?php
declare(strict_types=1);

class GraphQLTypeCheckTest extends \Tests\WPGraphQL\GF\TestCase\GFGraphQLTestCase {
    public function testPasswordFieldIsRegisteredInGraphQL() {
        $types = \WPGraphQL\GF\Utils\Utils::get_registered_form_field_types();

        $this->assertTrue(isset($types['password']), 'Password field type should be registered in GraphQL schema');
        $this->assertEquals('PasswordField', $types['password']);
    }

    public function testGraphQLSchemaHasPasswordFieldType() {
        $schema = \WPGraphQL::get_schema();

        $types = $schema->getTypeMap();

        $this->assertTrue(isset($types['PasswordField']), 'PasswordField type should exist in GraphQL schema');
    }
}
