<?php

namespace System\Http;

class Request
{
    private static $instance = null;

    private function __construct(
        private array $server,
        private array $get,
        private array $post,
        private array $files,
        private array $cookie,
        private array $env,
    )
    {
    }


    public static function create(): static
    {
        if (null === static::$instance) {
            static::$instance = new static(
                $_SERVER,
                $_GET,
                $_POST,
                $_FILES,
                $_COOKIE,
                $_ENV,
            );
        }

        return static::$instance;
    }

    public function getMethod(): string
    {
        return $this->server['REQUEST_METHOD'];
    }

    public function getUri(): string
    {
        return $this->server['REQUEST_URI'];
    }

    public function getPostParams(?string $name = null): array|string|null
    {
        if ($name !== null) {
            if (isset($this->post[$name])) {
                return $this->post[$name];
            }
            return null;
        }
        return $this->post;
    }

    public function getGetParams(string $name, ?string $default = null): ?string
    {
        return $this->get[$name] ?? $default;
    }
}