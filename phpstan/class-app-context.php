<?php

namespace WPGraphQL\GF\Model;
class Form {}
class SubmittedEntry {}
class DraftEntry {}
class FormField {}

namespace WPGraphQL\Data\Loader;
abstract class AbstractDataLoader {}

namespace WPGraphQL\GF\Data\Loader;
class DraftEntriesLoader {}
class EntriesLoader {}
class FormsLoader {}
class FormFieldsLoader {}

namespace WPGraphQL;
/**
 * @property ?\WPGraphQL\GF\Model\Form $gfForm
 * @property \WPGraphQL\GF\Model\SubmittedEntry|\WPGraphQL\GF\Model\DraftEntry|null $gfEntry
 * @property ?\WPGraphQL\GF\Model\FormField $gfField
 */
class AppContext {
	/**
	 * @param string $key
	 * @return ( $key is 'gf_draft_entry' ? \WPGraphQL\GF\Data\Loader\DraftEntriesLoader :
	 *                  ( $key is 'gf_entry' ? \WPGraphQL\GF\Data\Loader\EntriesLoader :
	 *                   ( $key is 'gf_form' ? \WPGraphQL\GF\Data\Loader\FormsLoader :
	 *                    ( $key is 'gf_form_field' ? \WPGraphQL\GF\Data\Loader\FormFieldsLoader : \WPGraphQL\Data\Loader\AbstractDataLoader )
	 *                   )
	 *                  )
	 *                 )
	 */
	public function get_loader($key){}
}
