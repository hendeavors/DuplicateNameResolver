<?php namespace Endeavors\DuplicateNameResolver;

use Illuminate\Support\ServiceProvider;

class DuplicateNameResolverServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
        $app = $this->app;
        $app['config']->package('endeavors/duplicate-name-resolver', $this->guessPackagePath() . '/config');
        
        $namespace = $app['config']->get('duplicate-name-resolver::config.options.namespace');
        $class = $app['config']->get('duplicate-name-resolver::config.options.class');

        $this->app['duplicate.name.resolver'] = $this->app->share(function($app) use($class, $namespace)
		{
            $instance = ltrim($namespace . $class);

            if(class_exists($instance) ) {
                return new NameResolver(new $instance);
            }

            throw new Exceptions\NameDataSourceException("Your datasource class " . $instance . " could not be found.");
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
