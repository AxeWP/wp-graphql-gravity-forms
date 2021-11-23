<?php
/**
 * Interface for classes that perform data manipulation.
 *
 * @package WPGraphQL\GF\Interfaces
 * @since 0.0.1
 */

namespace WPGraphQL\GF\Interfaces;

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
	public static function manipulate( array $data ) : array;
}
