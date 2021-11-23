<?php
/**
 * Factory Class
 *
 * This class serves as a factory for all GF resolvers.
 *
 * @package WPGraphQL\GF\Data
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Data;

use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQL\GF\Data\Loader\EntriesLoader;
use WPGraphQL\GF\Data\Loader\FormsLoader;

/**
 * Class - Factory
 */
class Factory {
	/**
	 * Registers loaders to AppContext.
	 *
	 * @param array      $loaders Data loaders.
	 * @param AppContext $context App context.
	 *
	 * @return array Data loaders, with new ones added.
	 */
	public static function register_loaders( array $loaders, AppContext $context ) : array {
		$loaders[ EntriesLoader::$name ] = new EntriesLoader( $context );
		$loaders[ FormsLoader::$name ]   = new FormsLoader( $context );

		return $loaders;
	}

	/**
	 * Bump max query amount to account for forms with many fields.
	 *
	 * @param int         $max_query_amount Max query amount.
	 * @param mixed       $source     source passed down from the resolve tree.
	 * @param array       $args       array of arguments input in the field as part of the GraphQL query.
	 * @param AppContext  $context    Object containing app context that gets passed down the resolve tree.
	 * @param ResolveInfo $info       Info about fields passed down the resolve tree.
	 *
	 * @return int Max query amount, possibly bumped.
	 */
	public static function set_max_query_amount( int $max_query_amount, $source, array $args, AppContext $context, ResolveInfo $info ) : int {
		if ( 'formFields' === $info->fieldName ) {
			return (int) max( $max_query_amount, 600 );
		}
		return $max_query_amount;
	}
}
