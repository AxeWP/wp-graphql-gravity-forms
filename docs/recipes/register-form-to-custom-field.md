# Recipes

## How to: register a Gravity Forms form to a GraphQL object

Individual Gravity Forms forms can be fetched performantly using a [WPGraphQL Loader](https://www.wpgraphql.com/docs/wpgraphql-request-lifecycle/).

To fetch the form with the WPGraphQL Loader, we use the `(AppContext) $context` variable provided by the `resolve` callback.

```php
'resolve' => static function( $source, array $args, AppContext $context, ResolveInfo $info ) {
  $form_id = 1; // Typically, this would be a function or a property on `$source` or `$args['where']`.

  return $context->get_loader( \WPGraphQL\GF\Data\Loader\FormsLoader::$name )->load_deferred( $form_id );
}
```

*Note:* `AppContext::get_loader()` takes a `string` name of the data Loader, in our case `gf_form`. We recommend using the `FormsLoader::$name` variable for future compatibility.

### Example: Resolving a Gravity Forms from a custom Post meta field

```php
add_action( 'graphql_register_types', 'my_add_form_to_post' );
function my_add_form_to_post() {
  // @see https://www.wpgraphql.com/functions/register_graphql_field/
  register_graphql_field(
    'Post', // The GraphQL Object Type
    'form', // The field name to add.
    // The config object:
    [
      'description' => __( 'The Gravity Forms form for the post', 'my-plugin' ),
      'type' => \WPGraphQL\GF\Type\WPObject\Form\Form::$type,
      'resolve' => static function( $source, array $args, \WPGraphQL\AppContext $context ){
        // Get the form id from the post meta.
        $form_id = get_post_meta( $source->ID, 'my_custom_meta_field_form_id', true );

        // Return the form.
        return $context->get_loader( \WPGraphQL\GF\Data\Loader\FormsLoader::$name )->load_deferred( (int) $form_id );
      }
    ]
  );
}
```
