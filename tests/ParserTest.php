<?php

namespace Alexwijn\Tests;

use Alexwijn\DatabaseUrl\ServiceProvider;
use Orchestra\Testbench\TestCase;

/**
 * Alexwijn\Tests\ParserTest
 */
class ParserTest extends TestCase
{
    public function testDatabase()
    {
        putenv('DATABASE_URL=mariadb://user:testing-password@production-mariadb:1000/production');

        $this->app['db.parser']->process();

        $this->assertEquals('mysql', config('database.connections.mysql.driver'));
        $this->assertEquals('production-mariadb', config('database.connections.mysql.host'));
        $this->assertEquals('1000', config('database.connections.mysql.port'));
        $this->assertEquals('production', config('database.connections.mysql.database'));
        $this->assertEquals('user', config('database.connections.mysql.username'));
        $this->assertEquals('testing-password', config('database.connections.mysql.password'));
    }

    public function testRedis()
    {
        putenv('REDIS_URL=redis://username:password@redissrv:1000/5');

        $this->app['db.parser']->process();

        $this->assertEquals('redissrv', config('database.redis.default.host'));
        $this->assertEquals(1000, config('database.redis.default.port'));
        $this->assertEquals(5, config('database.redis.default.database'));
        $this->assertEquals('password', config('database.redis.default.password'));
    }

    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }
}
