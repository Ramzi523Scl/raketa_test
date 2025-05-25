<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract readonly class AbstractController
{
    public function __construct(protected LoggerInterface $logger)
    {
    }

    protected function json(mixed $data, int $status = 200): ResponseInterface
    {
        $response = new JsonResponse();
        $response->getBody()->write(
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );

        return $response
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withStatus($status);
    }

    protected function success(mixed $data): ResponseInterface
    {
        return $this->json([
            'status' => 'success',
            'data'   => $data,
        ]);
    }

    protected function error(string $message, int $status = 400): ResponseInterface
    {
        return $this->json([
            'status'  => 'error',
            'message' => $message,
        ], $status);
    }
}