<?php

namespace Alexwijn\DatabaseUrl;

/**
 * Alexwijn\DatabaseUrl\ServiceProvider
 */
class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->singleton('db.parser', static function () {
            return new Parser('DATABASE_URL', 'REDIS_URL', 'ELASTICSEARCH_URL');
        });

        $this->app->afterResolving('db', function () {
            $this->app['db.parser']->process();
        });
    }
}
