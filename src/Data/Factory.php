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

use GraphQL\Deferred;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQL\GF\Data\Connection\EntriesConnectionResolver;
use WPGraphQL\GF\Data\Connection\FormsConnectionResolver;
use WPGraphQL\GF\Data\Loader\DraftEntriesLoader;
use WPGraphQL\GF\Data\Loader\EntriesLoader;
use WPGraphQL\GF\Data\Loader\FormsLoader;
use WPGraphQL\GF\Model;

/**
 * Class - Factory
 */
class Factory {
	/**
	 * Registers loaders to AppContext.
	 *
	 * @param array                 $loaders Data loaders.
	 * @param \WPGraphQL\AppContext $context App context.
	 *
	 * @return array Data loaders, with new ones added.
	 */
	public static function register_loaders( array $loaders, AppContext $context ): array {
		$loaders[ DraftEntriesLoader::$name ] = new DraftEntriesLoader( $context );
		$loaders[ EntriesLoader::$name ]      = new EntriesLoader( $context );
		$loaders[ FormsLoader::$name ]        = new FormsLoader( $context );

		return $loaders;
	}

	/**
	 * Resolves Relay node for Gravity Forms types.
	 *
	 * @param mixed $type     Node type.
	 * @param mixed $node     Node object.
	 *
	 * @return mixed
	 */
	public static function resolve_node_type( $type, $node ) {
		switch ( true ) {
			case is_a( $node, Model\Form::class ):
				$type = Model\Form::class;
				break;
			case is_a( $node, Model\SubmittedEntry::class ):
				$type = Model\SubmittedEntry::class;
				break;
			case is_a( $node, Model\DraftEntry::class ):
				$type = Model\DraftEntry::class;
				break;
		}

		return $type;
	}

	/**
	 * Bump max query amount to account for forms with many fields.
	 *
	 * @param int                                  $max_query_amount Max query amount.
	 * @param mixed                                $source     source passed down from the resolve tree.
	 * @param array                                $args       array of arguments input in the field as part of the GraphQL query.
	 * @param \WPGraphQL\AppContext                $context Object containing app context that gets passed down the resolve tree.
	 * @param \GraphQL\Type\Definition\ResolveInfo $info Info about fields passed down the resolve tree.
	 *
	 * @return int Max query amount, possibly bumped.
	 */
	public static function set_max_query_amount( int $max_query_amount, $source, array $args, AppContext $context, ResolveInfo $info ): int {
		if ( 'formFields' === $info->fieldName ) {
			return (int) max( $max_query_amount, 600 );
		}

		return $max_query_amount;
	}

	/**
	 * Resolves the form object for the form ID specified.
	 *
	 * @param int                   $id .
	 * @param \WPGraphQL\AppContext $context .
	 */
	public static function resolve_form( $id, AppContext $context ): ?Deferred {
		return $context->get_loader( FormsLoader::$name )->load_deferred( $id );
	}

	/**
	 * Wrapper for the FormsConnectionResolver::resolve method.
	 *
	 * @param mixed                                $source  The object the connection is coming from.
	 * @param array                                $args    Array of args to be passed down to the resolve method.
	 * @param \WPGraphQL\AppContext                $context The AppContext object to be passed down.
	 * @param \GraphQL\Type\Definition\ResolveInfo $info The ResolveInfo object.
	 *
	 * @return mixed|array|\GraphQL\Deferred
	 */
	public static function resolve_forms_connection( $source, array $args, AppContext $context, ResolveInfo $info ) {
		$resolver = new FormsConnectionResolver( $source, $args, $context, $info );

		return $resolver->get_connection();
	}

	/**
	 * Resolves the entry object for the entry ID specified.
	 *
	 * @param string                $id .
	 * @param \WPGraphQL\AppContext $context .
	 */
	public static function resolve_draft_entry( $id, AppContext $context ): ?Deferred {
		return $context->get_loader( DraftEntriesLoader::$name )->load_deferred( $id );
	}

	/**
	 * Resolves the entry object for the entry ID specified.
	 *
	 * @param int                   $id .
	 * @param \WPGraphQL\AppContext $context .
	 */
	public static function resolve_entry( $id, AppContext $context ): ?Deferred {
		return $context->get_loader( EntriesLoader::$name )->load_deferred( $id );
	}

	/**
	 * Wrapper for the EntriesConnectionResolver::resolve method.
	 *
	 * @param mixed                                $source  The object the connection is coming from.
	 * @param array                                $args    Array of args to be passed down to the resolve method.
	 * @param \WPGraphQL\AppContext                $context The AppContext object to be passed down.
	 * @param \GraphQL\Type\Definition\ResolveInfo $info The ResolveInfo object.
	 *
	 * @return mixed|array|\GraphQL\Deferred
	 */
	public static function resolve_entries_connection( $source, array $args, AppContext $context, ResolveInfo $info ) {
		$resolver = new EntriesConnectionResolver( $source, $args, $context, $info );

		return $resolver->get_connection();
	}
}
