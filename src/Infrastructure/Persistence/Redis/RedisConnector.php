<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure\Persistence\Redis;

use Redis;
use RedisException;

class RedisConnector
{
    private Redis $redis;
    private bool $isConnected = false;

    public function __construct(
        private readonly string $host,
        private readonly int $port,
        private readonly ?string $password = null
    ) {
        $this->redis = new Redis();
    }

    /**
     * @throws RedisException
     */
    public function connect(): void
    {
        if ($this->isConnected) {
            return;
        }

        $this->redis->connect($this->host, $this->port);

        if ($this->password !== null) {
            $this->redis->auth($this->password);
        }

        $this->isConnected = true;
    }

    /**
     * @throws RedisException
     */
    public function get(string $key): ?string
    {
        $this->ensureConnected();

        $value = $this->redis->get($key);

        return $value === false ? null : $value;
    }

    /**
     * @throws RedisException
     */
    public function setex(string $key, int $ttl, string $value): void
    {
        $this->ensureConnected();
        $this->redis->setex($key, $ttl, $value);
    }

    /**
     * @throws RedisException
     */
    public function del(string $key): void
    {
        $this->ensureConnected();
        $this->redis->del($key);
    }

    /**
     * @throws RedisException
     */
    private function ensureConnected(): void
    {
        if (!$this->isConnected) {
            $this->connect();
        }

        if (!$this->redis->ping()) {
            $this->isConnected = false;
            $this->connect();
        }
    }
}