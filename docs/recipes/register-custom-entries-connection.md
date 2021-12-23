# Recipes

## How to: register a custom Gravity Forms entries connection to a GraphQL type

Connections to submitted Gravity Forms entries can be created using the `\WPGraphQL\GF\Data\Factory::resolve_entries_connection()` function. Connections to draft and partial entries are not currently supported.

To create a connection to a submitted entry, we use [WPGraphQL's `register_graphql_connection()` function](https://www.wpgraphql.com/functions/register_graphql_connection/), then return the results of `resolve_entries_connection()` .

```php
'resolve' => static function( $source, array $args, \WPGraphQL\AppContext $context, \GraphQL\Type\Definition\ResolveInfo $info ) {
  // Typically modifications would be made to the `$args` here.

  return \WPGraphQL\GF\Data\Factory::resolve_entries_connection($source, $args, $context, $info );
}
```

### Example: Creating a connection from a Post to a specific form saved in the post meta

```php
add_action( 'graphql_register_types', 'my_add_entries_to_post' );
function my_add_entries_to_post() {
  register_graphql_connection(
    [
      'fromType' => 'Post', // The GraphQL object type
      'toType'   => \WPGraphQL\GF\Type\WPObject\Entry\SubmittedEntry::$type,
      'resolve' => static function( $source, array $args, \WPGraphQL\AppContext $context, \GraphQL\Type\Definition\ResolveInfo $info ) {
        // Get the form id from the post meta.
        $form_id = get_post_meta( $source->ID, 'my_custom_meta_field_form_id', true );

        // Set the form id in the where args so the connection knows what data to fetch.
        $args['where']['formIds'] = $form_id;

        return \WPGraphQL\GF\Data\Factory::resolve_entries_connection($source, $args, $context, $info );
      }
    ]
  );
}
```
