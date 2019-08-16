<?php

namespace WPGraphQLGravityForms\Interfaces;

/**
 * Interface for classes that perform data manipulation.
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
