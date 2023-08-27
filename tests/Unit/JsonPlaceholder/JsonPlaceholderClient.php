<?php

namespace SchenkeIo\ApiClient\Tests\Unit\JsonPlaceholder;

use SchenkeIo\ApiClient\BaseJsonClient;

class JsonPlaceholderClient extends BaseJsonClient
{
    public function getAuthHeader(): array
    {
        return [];
    }
}
