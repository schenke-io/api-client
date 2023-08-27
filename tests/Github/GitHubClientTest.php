<?php

namespace SchenkeIo\ApiClient\Tests\Github;

use PHPUnit\Framework\TestCase;
use SchenkeIo\ApiClient\Tests\Github\GitHubClient;

class GitHubClientTest extends TestCase
{
    public function testGithubClientGet()
    {
        $baseUrl = 'https://api.github.com/repos/schenke-io/api-client/';
        $client = new GitHubClient($baseUrl);
        $result = $client->get('/contents/README.md');
        $this->assertIsArray($result);
        $this->assertArrayHasKey('name', $result);
    }

    public function testGithubClientGetNotFound()
    {
        $baseUrl = 'https://api.github.com/';
        $client = new GitHubClient($baseUrl);
        $result = $client->get('/no-repos/do-not-exist');
        $this->assertIsArray($result);
        $this->assertArrayHasKey('documentation_url', $result);
    }

    public function testGithubClientGetError()
    {
        $baseUrl = 'https://invalid-domian/';
        $client = new GitHubClient($baseUrl);
        $result = $client->get('/no-repos/do-not-exist');
        $this->assertIsArray($result);
        $this->assertArrayHasKey('_error', $result);
    }
}
