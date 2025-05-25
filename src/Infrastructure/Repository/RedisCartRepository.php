<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure\Repository;

use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Domain\Repository\CartRepositoryInterface;
use Raketa\BackendTestTask\Infrastructure\Persistence\Redis\RedisConnector;
use RedisException;
use Throwable;

class RedisCartRepository implements CartRepositoryInterface
{
    private const CART_TTL = 86400; // 1 день в секундах

    public function __construct(
        private readonly RedisConnector  $redis,
        private readonly LoggerInterface $logger
    )
    {
    }

    public function find(string $sessionId): ?Cart
    {
        try {
            $data = $this->redis->get($this->getKey($sessionId));

            if ($data === null) {
                return null;
            }

            return unserialize($data);
        } catch (Throwable $e) {
            $this->logger->error('Failed to get cart from Redis', [
                'session_id' => $sessionId,
                'error'      => $e->getMessage(),
                'trace'      => $e->getTraceAsString(),
            ]);

            return null;
        }
    }

    /**
     * @throws Throwable
     * @throws RedisException
     */
    public function save(Cart $cart): void
    {
        try {
            $this->redis->setex(
                $this->getKey($cart->getSessionId()),
                self::CART_TTL,
                serialize($cart)
            );
        } catch (Throwable $e) {
            $this->logger->error('Failed to save cart to Redis', [
                'session_id' => $cart->getSessionId(),
                'error'      => $e->getMessage(),
                'trace'      => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    public function delete(string $sessionId): void
    {
        try {
            $this->redis->del($this->getKey($sessionId));
        } catch (Throwable $e) {
            $this->logger->error('Failed to delete cart from Redis', [
                'session_id' => $sessionId,
                'error'      => $e->getMessage(),
                'trace'      => $e->getTraceAsString(),
            ]);
        }
    }

    private function getKey(string $sessionId): string
    {
        return sprintf('cart:%s', $sessionId);
    }
}