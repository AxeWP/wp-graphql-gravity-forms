<?php
/**
 * Interface for classes that perform data manipulation.
 *
 * @package WPGraphQLGravityForms\Interfaces
 * @since 0.0.1
 */

namespace WPGraphQLGravityForms\Interfaces;

/**
 * Interface - DataManipulator
 */
interface DataManipulator {
	/**
	 * Manipulate data.
	 *
	 * @param array $data The data to be manipulated.
	 *
	 * @return array Manipulated data.
	 */
	public function manipulate( array $data ) : array;
}
