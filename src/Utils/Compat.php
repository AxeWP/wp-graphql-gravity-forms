<?php
/**
 * Helper functions for cross-version compatibility.
 *
 * @see https://github.com/AxeWP/wp-graphql-plugin-boilerplate
 *
 * @package WPGraphQL\GF\Utils
 */

declare( strict_types=1 );

namespace WPGraphQL\GF\Utils;

/**
 * Class - Compat
 */
class Compat {
	/**
	 * Adds backwards compatibility for lazy-loaded configs added in WPGraphQL versions 2.3.0 and later.
	 *
	 * Specifically resolves `description` and `deprecationReason` in configs and any nested configs.
	 *
	 * @template T of array
	 * @param T $config The config to check.
	 *
	 * @return T&array{description?:string,deprecationReason?:string} The config with lazy-loaded configs replaced with their values.
	 */
	public static function resolve_graphql_config( array $config ): array {
		// Bail if WPGraphQL version is less than 2.3.0, since WPGraphQL can handle it.
		if ( ! defined( 'WPGRAPHQL_VERSION' ) || version_compare( WPGRAPHQL_VERSION, '2.3.0', '>=' ) ) {
			return $config;
		}

		/**
		 * Recursively resolve nested configuration arrays.
		 * Some keys contain arrays of configurations that might also contain lazy-loaded values.
		 */
		$nested_configs = [
			'args',
			'connections',
			'connectionArgs',
			'connectionFields',
			'edgeFields',
			'fields',
			'inputFields',
			'outputFields',
			'values',
		];

		foreach ( $nested_configs as $nested_key ) {
			// Skip if the key doesn't exist or isn't an array.
			if ( ! isset( $config[ $nested_key ] ) || ! is_array( $config[ $nested_key ] ) ) {
				continue;
			}

			foreach ( $config[ $nested_key ] as $key => $value ) {
				// If the value is an array, it might be a nested config requiring resolution.
				if ( is_array( $value ) ) {
					$config[ $nested_key ][ $key ] = self::resolve_graphql_config( $value );
				}
			}
		}

		/**
		 * Resolve the keys that cant be lazy-loaded in < 2.3.0.
		 *
		 * Mock \WPGraphQL\TypeRegistry::get_introspection_keys().
		 *
		 * @see https://github.com/wp-graphql/wp-graphql/blob/f0988f9d70c592ae34902e6cd0a0ecf91774608e/src/Registry/TypeRegistry.php#L823-L836
		 */
		$introspection_keys = [ 'description', 'deprecationReason' ];

		// @phpstan-ignore function.alreadyNarrowedType (`WPGraphQL::is_introspection_query()` is only available in WPGraphQL 1.28.0+)
		$has_introspection_check = method_exists( \WPGraphQL::class, 'is_introspection_query' );
		$is_introspection_query  = $has_introspection_check ? \WPGraphQL::is_introspection_query() : false;

		foreach ( $introspection_keys as $introspection_key ) {
			// Skip if the key doesn't need to be resolved.
			if ( ! isset( $config[ $introspection_key ] ) || ! is_callable( $config[ $introspection_key ] ) ) {
				continue;
			}

			// If we 're _sure_ we are not introspecting, we can safely set the value to null.
			if ( $has_introspection_check && ! $is_introspection_query ) {
				$config[ $introspection_key ] = null;
				continue;
			}

			$config[ $introspection_key ] = $config[ $introspection_key ]();
		}

		return $config;
	}

	/**
	 * Gets context from AppContext.
	 *
	 * @todo remove when WPGraphQL < 2.3.8 is no longer supported.
	 *
	 * @param \WPGraphQL\AppContext $app_context The app context.
	 * @param string                $key The context key.
	 *
	 * @return (
	 *   $key is 'gfForm' ? ?\WPGraphQL\GF\Model\Form : (
	 *     $key is 'gfEntry' ? \WPGraphQL\GF\Model\SubmittedEntry|\WPGraphQL\GF\Model\DraftEntry|null : (
	 *       $key is 'gfField' ? ?\WPGraphQL\GF\Model\FormField : mixed
	 *     )
	 *   )
	 * )
	 */
	public static function get_app_context( \WPGraphQL\AppContext $app_context, string $key ) {
		// @phpstan-ignore function.alreadyNarrowedType (@todo remove when we don't support WPGraphQL < 2.3.8.)
		if ( method_exists( $app_context, 'get' ) ) {
			return $app_context->get( 'gf', $key );
		}

		// Old versions don't have namespaced context keys.
		return property_exists( $app_context, $key ) ? $app_context->$key : null;
	}

	/**
	 * Sets context on AppContext.
	 *
	 * @todo remove when WPGraphQL < 2.3.8 is no longer supported.
	 *
	 * @param \WPGraphQL\AppContext $app_context The app context.
	 * @param string                $key The context key.
	 * @param mixed                 $value The context value.
	 */
	public static function set_app_context( \WPGraphQL\AppContext $app_context, string $key, $value ): void {
		// @phpstan-ignore function.alreadyNarrowedType (@todo remove when we don't support WPGraphQL < 2.3.8.)
		if ( method_exists( $app_context, 'set' ) ) {
			$app_context->set( 'gf', $key, $value );
			return;
		}

		// Old versions don't have namespaced context keys.
		$app_context->$key = $value;
	}
}
