<?php

namespace SchenkeIo\ApiClient\Tests\Feature\Github;

use SchenkeIo\ApiClient\BaseClient;

class GitHubClient extends BaseClient
{
    /**
     * additional headers for authorization
     */
    public function getAuthHeader(): array
    {
        return [
            'Accept: application/vnd.github+json',
            'X-GitHub-Api-Version: 2022-11-28',
        ];
    }
}
