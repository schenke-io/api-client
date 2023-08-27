<?php

namespace SchenkeIo\ApiClient;

enum ApiType
{
    case POST;

    case JSON;

    /**
     * @return array<string>
     */
    public function getHeaders(): array
    {
        return match ($this) {
            self::JSON => [
                'Content-Type: application/json; charset=UTF-8',
                'Accept: application/json',
            ],
            default => []
        };
    }

    public function isJson(): bool
    {
        return match ($this) {
            self::JSON => true,
            default => false

        };
    }
}
