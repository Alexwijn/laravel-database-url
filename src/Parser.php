<?php

namespace Alexwijn\DatabaseUrl;

use Illuminate\Support\Str;

/**
 * Alexwijn\DatabaseUrl\Parser
 */
class Parser
{
    /** @var string */
    protected $redis;

    /** @var string */
    protected $database;

    /** @var string */
    protected $elasticsearch;

    /**
     * Parser constructor.
     *
     * @param  string  $database
     * @param  string  $redis
     */
    public function __construct(string $redis, string $database, string $elasticsearch)
    {
        $this->redis = $redis;
        $this->database = $database;
        $this->elasticsearch = $elasticsearch;
    }

    /**
     * Loop through and parse all supported environments configurations.
     */
    public function process()
    {
        foreach (get_class_methods($this) as $method) {
            if (Str::startsWith($method, 'parse')) {
                $this->{$method}();
            }
        }
    }

    /**
     * Parse the ELASTICSEARCH into laravel environment variables (Scout).
     */
    public function parseElasticsearch()
    {
        if (empty($config = getenv($this->elasticsearch))) {
            return;
        }

        if ($url = parse_url($config)) {
            if (isset($url['host'])) {
                config(['scout_elastic.client.hosts' => [$url['host'] . ':' . ($url['port'] ?? 9200)]]);
            }
        }
    }

    /**
     * Parse the REDIS_URL into laravel environment variables.
     */
    protected function parseRedis()
    {
        if (empty($config = getenv($this->redis))) {
            return;
        }

        if ($url = parse_url($config)) {
            if (isset($url['host'])) {
                config(['database.redis.default.host' => $url['host']]);
                config(['database.redis.cache.host' => $url['host']]);
            }

            if (isset($url['path'])) {
                config(['database.redis.default.database' => substr($url['path'], 1)]);
                config(['database.redis.cache.database' => substr($url['path'], 1)]);
            }

            if (isset($url['port'])) {
                config(['database.redis.default.port' => $url['port']]);
                config(['database.redis.cache.port' => $url['port']]);
            }

            if (isset($url['pass'])) {
                config(['database.redis.default.password' => $url['pass']]);
                config(['database.redis.cache.password' => $url['pass']]);
            }
        }
    }

    /**
     * Parse the DATABASE_URL into laravel environment variables.
     */
    protected function parseDatabase()
    {
        $default = config('database.default');
        if (empty($config = getenv($this->database))) {
            return;
        }

        if ($url = parse_url($config)) {
            config(["database.connections.$default.driver" => 'mysql']);
            if (isset($url['scheme']) && $url['scheme'] === 'postgres') {
                config(["database.connections.$default.driver" => 'pgsql']);
            }

            if (isset($url['host'])) {
                config(["database.connections.$default.host" => $url['host']]);
            }

            if (isset($url['port'])) {
                config(["database.connections.$default.port" => $url['port']]);
            }

            if (isset($url['path'])) {
                config(["database.connections.$default.database" => substr($url['path'], 1)]);
            }

            if (isset($url['user'])) {
                config(["database.connections.$default.username" => $url['user']]);
            }

            if (isset($url['pass'])) {
                config(["database.connections.$default.password" => $url['pass']]);
            }
        }
    }
}
