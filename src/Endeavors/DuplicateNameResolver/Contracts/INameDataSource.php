<?php 

namespace Endeavors\DuplicateNameResolver\Contracts;

interface INameDataSource
{
    /**
     * Get the names
     * @return array
     */
    public function all();

    /**
     * If this is false we'll need
     * The index of the name
     * @return boolean
     */
    public function isSequential();

    /**
     * This can be null if we have
     * A sequential array
     * @return null|string
     */
    public function key();
}