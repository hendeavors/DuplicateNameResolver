<?php

namespace Endeavors\DuplicateNameResolver\Facades;

use Illuminate\Support\Facades\Facade;
use Endeavors\DuplicateNameResolver\Exceptions\NameDataSourceException;
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
	public static function name($name = null, $schema = null)
	{
        static::runTimeResolution($schema);

		return static::$app['duplicate.name.resolver']->resolve($name);
	}

    public static function runTimeResolution($schema)
    {
        $namespace = static::$app['config']->get('duplicate-name-resolver::config.options.' . $schema . '.namespace');
        $class = static::$app['config']->get('duplicate-name-resolver::config.options.' . $schema . '.class');

        if( null !== $schema ) {
            static::$app['duplicate.name.resolver'] = static::$app->share(function($app) use($class, $namespace)
		    {
                $instance = ltrim($namespace . $class);

                if(class_exists($instance) ) {
                    return new \Endeavors\DuplicateNameResolver\NameResolver(new $instance);
                }

                throw new NameDataSourceException("Your datasource class " . $instance . " could not be found.");
            });
        }
    }

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'duplicate.name.resolver'; }

}
