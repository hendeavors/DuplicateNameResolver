<?php

namespace Endeavors\DuplicateNameResolver\Facades;

use  Illuminate\Support\Facades\Facade;
/**
 * @see \Endeavors\DuplicateNameResolver\NameResolver
 */
class Resolve extends Facade {

	/**
	 * Resolve a conflicting name.
	 *
	 * @param  string $name
	 * @return string
	 */
	public static function name($name = null)
	{
		return static::$app['duplicate.name.resolver']->resolve($name);
	}

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'duplicate.name.resolver'; }

}
